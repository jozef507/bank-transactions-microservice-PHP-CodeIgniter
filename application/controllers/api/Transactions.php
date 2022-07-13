<?php
/**
 * VUT FIT - PIS 2021
 * Tím - PIS2021
 * Projekt - Banka (zadanie z AIS)
 *
 * Vypracoval - Jozef Ondria (xondri05)
 */

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
require APPPATH . 'libraries/vendor/chriskacerguis/codeigniter-restserver/src/RestController.php';


class Transactions extends RestController
{

    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    /// 1 Get transaction request processing
    ///////////////////////////////////////////////////////////////////////////////////////

    /**
     * @param $id
     */
    public function index_get($id)
    {
        try{
            $transaction = $this->BankTransactionModel->get_transaction($id);
        } catch (Exception $e) {
            $this->response(['status' => false,'message' => $e->getFile()."   ".$e->getLine()."   ".$e->getMessage()."   ".$e->getTrace()],
                RestController::HTTP_INTERNAL_ERROR);
        }

        if (!$transaction)
            $this->response(['status' => false,'message' => "Transaction not found!"], RestController::HTTP_INTERNAL_ERROR);

        $this->response(['status' => true,'message' => 'Transaction found!', 'data' => $transaction], RestController::HTTP_OK);
    }


    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    /// 2 Get transactions by account request processing
    ///////////////////////////////////////////////////////////////////////////////////////

    /**
     *
     */
    public function of_account_get()
    {
        $queryParameters = $this->of_account_processInput();

        try{
            $transaction = $this->BankTransactionModel->get_transaction_by_account($queryParameters);
        } catch (Exception $e) {
            $this->response(['status' => false,'message' => $e->getFile()."   ".$e->getLine()."   ".$e->getMessage()."   ".$e->getTrace()],
                RestController::HTTP_INTERNAL_ERROR);
        }

        //if (!$transaction)
          //  $this->response(['status' => false,'message' => "Transactions not found!"], RestController::HTTP_INTERNAL_ERROR);

        $this->response(['status' => true,'message' => 'Transactions found!', 'data' => $transaction], RestController::HTTP_OK);

    }

    /**
     * @return array
     */
    protected function of_account_processInput()
    {
        $data = array(
            "account" => $this->query("account"),
            "date_from" => $this->query("date_from"),
            "date_to" => $this->query("date_to")
        );
        return $data;
    }



    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    /// 3 Create transaction request processing
    ///////////////////////////////////////////////////////////////////////////////////////

    /**
     *
     */
    public function create_transaction_post()
    {
        //// Input json-data processing
        $data = $this->processInput();

        //// Data format checking
        $errorArray = $this->checkInputValuesFormat($data);

        if(!empty($errorArray)){
            $this->response(['status' => false,'message' => $errorArray],
                RestController::HTTP_BAD_REQUEST);
        }

        //// Data format edit
        $this->editDataFormat($data);

        //// Create transaction in database
        try{
            $transactionId = $this->BankTransactionModel->create_transaction($data);
        } catch (Exception $e) {
            $this->response(['status' => false,'message' => $e->getFile()."   ".$e->getLine()."   ".$e->getTrace()."   ".$e->getMessage()],
                RestController::HTTP_INTERNAL_ERROR);
        }

        //// HTTP request to Core microservice
        $result = $this->httpPostRequestTransactionExecute($data, $code);
        if(!$result['status'])
            $this->response(['status' => false,'message' => "Communication error with Core service!"],
                RestController::HTTP_INTERNAL_ERROR);


        //// Negative response from Core processing
        if(!($code >= RestController::HTTP_OK  &&  $code < 300))
        {
            $errorInfo = $this->processNegativeRespond($code, $transactionId, $result['httpResult']);
            $this->response(['status' => false, 'message' => $errorInfo['message']], $errorInfo['code']);
        }

        //// Positive response from Core processing
        $this->processPositiveResponse($transactionId);

        //// OK respond getting
        $this->response(['status' => true,'message' => 'Transaction created!','data' => array("transaction_id" => $transactionId)],
            RestController::HTTP_CREATED);
    }

    /**
     * @return array
     */
    protected function processInput()
    {
        $data = [
            'kindOfTransaction' =>  $this->post('kindOfTransaction'),
            'moneyAmount' => $this->post('moneyAmount'),
            'detail' => $this->post('detail'),
            'clientId' => $this->post('clientId'),
            'clientName' => $this->post('clientName'),
            'employeeId' => $this->post('employeeId'),
            'employeeName' => $this->post('employeeName'),
            'sourceAccount' => $this->post('sourceAccount'),
            'destinationAccount' => $this->post('destinationAccount'),
        ];

        return $data;
    }


    /**
     * @param array $data
     * @return array
     */
    protected function checkInputValuesFormat(array $data)
    {
        $errorArray = [];

        addErrorMessageIfNecessary(!empty($data['clientName']), $errorArray, "'clientName' empty!");
        addErrorMessageIfNecessary(!empty($data['employeeName']), $errorArray, "'employeeName' empty!");

        addErrorMessageIfNecessary(checkEnum($data['kindOfTransaction'],[KOT_TRANSFER, KOT_DEPOSIT, KOT_WITHDRAW]), $errorArray, "'kindOfTransaction' is wrong! Allowed values are: transfer, deposit, withdraw");
        addErrorMessageIfNecessary(is_numeric($data['moneyAmount']), $errorArray, "'moneyAmount' empty or format is wrong!");
        addErrorMessageIfNecessary(isMoneyAmountValid($data['moneyAmount']), $errorArray, "'moneyAmount' invalid value!");
        addErrorMessageIfNecessary(isStringLengthRight($data['detail'], 255), $errorArray, "'detail' length is wrong! Max is 255 characters.");
        addErrorMessageIfNecessary(is_numeric($data['clientId']), $errorArray, "'clientId' empty or format is wrong!");
        addErrorMessageIfNecessary(isStringLengthRight($data['clientName'], 255), $errorArray, "'clientName' length is wrong! Max is 255 characters.");
        addErrorMessageIfNecessary(is_numeric($data['employeeId']), $errorArray, "'employeeId' empty or format is wrong!");
        addErrorMessageIfNecessary(isStringLengthRight($data['employeeName'], 255), $errorArray, "'employeeName' length is wrong! Max is 255 characters.");

        if(strcmp($data['kindOfTransaction'], KOT_TRANSFER)==0)
        {
            addErrorMessageIfNecessary(isStringLengthRight($data['detail'], 30), $errorArray, "'sourceAccount' too long!");
            addErrorMessageIfNecessary(isStringLengthRight($data['detail'], 30), $errorArray, "'destinationAccount' too long!");
        }
        elseif(strcmp($data['kindOfTransaction'], KOT_DEPOSIT)==0)
        {
            addErrorMessageIfNecessary(isStringLengthRight($data['detail'], 30), $errorArray, "'destinationAccount' too long!");
            addErrorMessageIfNecessary(empty($data['sourceAccount']), $errorArray, "'sourceAccount' not empty but in deposit must be!");
        }
        else
        {
            addErrorMessageIfNecessary(isStringLengthRight($data['detail'], 30), $errorArray, "'sourceAccount' too long!");
            addErrorMessageIfNecessary(empty($data['destinationAccount']), $errorArray, "'destinationAccount' not empty but in withdraw must be!");
        }

        return $errorArray;
    }

    /**
     * @param array $data
     */
    protected function editDataFormat(array &$data)
    {
        $data['detail'] = setVarWithNull($data['detail']);
        $data['sourceAccount'] = setVarWithNull($data['sourceAccount']);
        $data['destinationAccount'] = setVarWithNull($data['destinationAccount']);
    }


    /**
     * @param array $data
     * @param $code
     * @return array
     */
    protected function httpPostRequestTransactionExecute(array $data, &$code)
    {

        $url = CORE_URL_TRANSACTION_EXEC;
        $requestData = array(
            'kindOfTransaction' => $data['kindOfTransaction'],
            'moneyAmount' => $data['moneyAmount'],
            'clientId' => $data['clientId'],
            'sourceAccount' => $data['sourceAccount'],
            'destinationAccount' => $data['destinationAccount']
        );

        $options = array(
            'http' => array(
                'ignore_errors' => true,
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($requestData)
            )
        );

        $context  = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        $code = getHttpCode($http_response_header);
        if(!$code || $code==0)
            return (array('status' => false, 'httpResult'=>$result));

        return (array('status' => true, 'httpResult'=>$result));
    }


    /**
     * @param $code
     * @param $result
     * @param $transactionId
     * @param $kindOfTransaction
     * @return null[]
     */
    protected function processNegativeRespond($code, $transactionId, $result)
    {
        $errorInfo = array('code' => null, 'message' => null);

        $coreRespondArray = json_decode($result, true);

        if ($code == RestController::HTTP_BAD_REQUEST)
        {
            $errorInfo['message'] = "Microservice 'Core' is not able to perform transaction. HTTP response code from 'Core': 400. 'Core' message: ".$coreRespondArray['detail'];
            $errorInfo['code'] = RestController::HTTP_BAD_REQUEST;
        }
        else
        {
            $errorInfo['message'] = "Microservice 'Core' is not able to perform transaction. HTTP response code from 'Core':".$code.". 'Core' message: ".$coreRespondArray['detail'];
            $errorInfo['code'] = RestController::HTTP_BAD_REQUEST;
        }

        try{
            $this->BankTransactionModel->update_transaction_in_creation($transactionId, false);
        } catch (Exception $e) {
            $this->response(['status' => false, 'message' => $e->getFile()."   ".$e->getLine()."   ".$e->getTrace()."   ".$e->getMessage()],
                RestController::HTTP_INTERNAL_ERROR);
        }

        return $errorInfo;
    }


    /**
     * @param $result
     * @param $transactionId
     * @param $kindOfTransaction
     */
    protected function processPositiveResponse($transactionId)
    {
        //$coreRespondArray = json_decode($result, true);
       try{
            $this->BankTransactionModel->update_transaction_in_creation($transactionId, true);
        } catch (Exception $e) {
            $this->response(['status' => false,'message' => $e->getFile() . "   " . $e->getLine() . "   " . $e->getTrace() . "   " . $e->getMessage()],
                RestController::HTTP_INTERNAL_ERROR);
        }
    }

}