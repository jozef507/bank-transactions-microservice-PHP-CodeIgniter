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
require_once APPPATH . 'core/PdfReport.php';


class Reports extends RestController
{
    public function index_get()
    {
        $pdf = $this->pdf->load();

        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);


        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('PIS2021');
        $pdf->SetTitle('Mesačný výpis z účtu');
        $pdf->SetSubject('Mesačný výpis z účtu');
        $pdf->SetKeywords('TCPDF, PDF, report, PIS, PIS20201, VUT, FIT');

        $pdf->SetHeaderData("PIS Banka 2021");

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->setFontSubsetting(true);
        $pdf->SetFont('freeserif', '', 11);
        $pdf->AddPage();

        $pdf->Output('rep', 'I');

    }

    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    /// 1 Get report of account
    ///////////////////////////////////////////////////////////////////////////////////////


    public function index_post()
    {
        //spracuj vstup
        $data = $this->create_processInput();

        //skontroluj vstupne data aj či je datum aspon z minuleho mesiaca
        $errorArray = $this->create_checkInput($data);

        if(!empty($errorArray))
            $this->response(['status' => false,'message' => $errorArray],
                RestController::HTTP_BAD_REQUEST);

        $error = '';
        $info = $this->httpCommunication($data["iban"], $error);
        if(!$info)
        {
            $this->response(['status' => true,'message' => ("Processing account report for acoount: "
                .$data['iban'].". Error message:   ". $error)], RestController::HTTP_INTERNAL_ERROR);

        }


        try{
            $reportInfo = $this->TransactionsReportModel
                ->get_report_info($info['account']["iban"], $data['year'], $data['month']);
        } catch (Exception $e) {
            $this->response(['status' => false,'message' => $e->getFile()
                ."   ".$e->getLine()."   ".$e->getTrace()."   ".$e->getMessage()],
                RestController::HTTP_INTERNAL_ERROR);
        }

        if(!$reportInfo)
        {
            $this->response(['status' => true,'message' => "The report not exist. The report has not yet been generated."],
                RestController::HTTP_BAD_REQUEST);
        }


        $reportPdf = new PdfReport($reportInfo['id'], $data['year'], $data['month'], $info['owner'], $info['disponents']
            , $info['account'], $this->TransactionsReportModel->get_prev_report_balance($info['account']["iban"],$data['year'], $data['month'] )
            , $reportInfo['moneyBalance'], $reportInfo['transactions']);

        $reportPdf->getThroughHttp();
    }

    protected function create_processInput()
    {
        $data = array(
            "iban" => $this->post("iban"),
            "year" => $this->post("year"),
            "month" => $this->post("month")
        );
        return $data;
    }

    protected function create_checkInput($data)
    {
        $errorArray = [];

        addErrorMessageIfNecessary(!empty($data["iban"]),
            $errorArray, "'account' not defined! Key must be iban value!");
        addErrorMessageIfNecessary(is_numeric($data["year"]),
            $errorArray, "'year' wrong format! Value must be numeric!");
        addErrorMessageIfNecessary(is_numeric($data["month"]) && ((int)$data["month"]>=0
            && (int)$data["month"]<=12), $errorArray, "'month' wrong format! Allowed values: {1,2,...,12}");

        $currentDate = date("Y-m");
        $askedDate = date("Y-m", strtotime($data["year"]."-".$data["month"]));
        addErrorMessageIfNecessary($askedDate<$currentDate, $errorArray, "date must be at least last month!");

        return $errorArray;
    }





    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    /// 3 Generate all reports
    ///////////////////////////////////////////////////////////////////////////////////////

    public function generate_post()
    {
        $data = $this->generate_processInput();
        $errorArray = $this->generate_checkInput($data);

        if(!empty($errorArray))
            $this->response(['status' => false,'message' => $errorArray],
                RestController::HTTP_BAD_REQUEST);

        $errorArray = array();
        $activeAccountsIbans = $this->BankAccountModel->get_active_ibans();
        
        if(!$activeAccountsIbans)
            $this->response(['status' => false,'message' => 'No active accounts in transactions-database!'],
                RestController::HTTP_BAD_REQUEST);

        foreach ($activeAccountsIbans as $tsAccountIban)
        {
            $error = '';
            $info = $this->httpCommunication($tsAccountIban["iban"], $error);
            if(!$info)
            {
                $errorArray [] = "Processing account report for acoount: ".$tsAccountIban['iban'].". Error message:   ". $error;
                continue;
            }


            try{
                $reportInfo = $this->TransactionsReportModel
                    ->create_report($info['account']["iban"], $data['year'], $data['month'], $info['account']['accountBalance']);
            } catch (Exception $e) {
                $this->response(['status' => false,'message' => $e->getFile()
                    ."   ".$e->getLine()."   ".$e->getTrace()."   ".$e->getMessage()],
                    RestController::HTTP_INTERNAL_ERROR);
            }

            if(!$reportInfo)
            {
                $errorArray [] = "Processing account report for acoount: ".$tsAccountIban['iban']." failed. Error message:   Account report already exists";
                continue;
            }


            $reportPdf = new PdfReport($reportInfo['id'], $data['year'], $data['month'], $info['owner'], $info['disponents']
                , $info['account'], $this->TransactionsReportModel->get_prev_report_balance($info['account']["iban"],$data['year'], $data['month'] )
                , $reportInfo['moneyBalance'], $reportInfo['transactions']);


            try {
                $reportPdf->sendOnMail();
            } catch (Exception $e) {
                $errorArray [] = "Sending account report on e-mail for acoount: ".$tsAccountIban['iban']." failed. Error message:   ".$e->getMessage();
                continue;
            }

            $errorArray [] = "Proccessing and sending account report on e-mail for acoount: ".$tsAccountIban['iban']." successfully done.";
        }

       $this->response(['status' => true,'message' => $errorArray], RestController::HTTP_OK);
    }

    protected function generate_processInput()
    {
        $data = array(
            "year" => $this->post("year"),
            "month" => $this->post("month")
        );
        return $data;
    }

    protected function generate_checkInput($data)
    {
        $errorArray = [];

        addErrorMessageIfNecessary(is_numeric($data["year"]),
            $errorArray, "'year' wrong format! Value must be numeric!");
        addErrorMessageIfNecessary(is_numeric($data["month"]) && ((int)$data["month"]>=0
                && (int)$data["month"]<=12), $errorArray, "'month' wrong format! Allowed values: {1,2,...,12}");

        $currentDate = date("Y-m");
        $askedDate = date("Y-m", strtotime($data["year"]."-".$data["month"]));
        addErrorMessageIfNecessary($askedDate<$currentDate, $errorArray, "Date must be at least last month!");

        return $errorArray;
    }


    protected function httpCommunication($iban, &$error)
    {
        $accountInfo = $this->accountHttpCommunication($iban, $error);
        if(!$accountInfo)
            return false;

        $ownerInfo = $this->ownerHttpCommunication($accountInfo['owner_id'], $error);
        if(!$accountInfo)
            return false;

        $disponentsInfo = $this->disponentsHttpCommunication($iban, $error);
        if(!$accountInfo)
            return false;

        $info = array('account' => $accountInfo, 'owner' => $ownerInfo, 'disponents' => $disponentsInfo);
        return $info;
    }




    protected function accountHttpCommunication($iban, &$error)
    {
        $url = CORE_URL_GET_ACCOUNT."/".$iban;

        $options = array('http' => array('ignore_errors' => true, 'method'  => 'GET'));

        $context  = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if(!$result)
        {
            $error = "'Core' communication error!";
            return false;
        }

        $code = getHttpCode($http_response_header);

        if(!$code || $code==0)
        {
            $error = "'Core' communication error!";
            return false;
        }

        $result = json_decode($result, true);

        if(!($code >= RestController::HTTP_OK  &&  $code < 300))
        {

            if($result['detail'])
                $error = "'Core' responses with error: '".$code.": ".$result['detail']."'";
            else
                $error = "'Core' responses with error: '".$code."'";

            return false;
        }

        return $result;
    }

    protected function ownerHttpCommunication($ownerId, &$error)
    {
        $url = CORE_URL_GET_OWNER."/".$ownerId;

        $options = array('http' => array('ignore_errors' => true, 'method'  => 'GET'));

        $context  = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if(!$result)
        {
            $error = "'Core' communication error!";
            return false;
        }

        $code = getHttpCode($http_response_header);

        if(!$code || $code==0)
        {
            $error = "'Core' communication error!";
            return false;
        }

        $result = json_decode($result, true);

        if(!($code >= RestController::HTTP_OK  &&  $code < 300))
        {
            if($result['detail'])
                $error = "'Core' responses with error: '".$code.": ".$result['detail']."'";
            else
                $error = "'Core' responses with error: '".$code."'";

            return false;
        }

        return $result;
    }

    protected function disponentsHttpCommunication($iban, &$error)
    {
        $url = CORE_URL_GET_DISPONENTS."/".$iban;

        $options = array('http' => array('ignore_errors' => true, 'method'  => 'GET'));

        $context  = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if(!$result)
        {
            $error = "'Core' communication error!";
            return false;
        }

        $code = getHttpCode($http_response_header);

        if(!$code || $code==0)
        {
            $error = "'Core' communication error!";
            return false;
        }

        $result = json_decode($result, true);

        if(!($code >= RestController::HTTP_OK  &&  $code < 300))
        {
            if($result['detail'])
                $error = "'Core' responses with error: '".$code.": ".$result['detail']."'";
            else
                $error = "'Core' responses with error: '".$code."'";

            return false;
        }

        return $result;
    }


    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    /// 4 Generate last report of account
    ///////////////////////////////////////////////////////////////////////////////////////

    public function generate_last_get($iban)
    {
        //$data = $this->generate_last_processInput();
        $data['iban'] = $iban;

        $errorArray = $this->generate_last_checkInput($data);

        if(!empty($errorArray))
            $this->response(['status' => false,'message' => $errorArray],
                RestController::HTTP_BAD_REQUEST);

        $year = (new DateTime())->format('Y');
        $month = (new DateTime())->format('n');

        $error = '';
        $info = $this->httpCommunication($data["iban"], $error);
        if(!$info)
        {
            $this->response(['status' => true,'message' => ("Processing account report for acoount: "
                .$data['iban'].". Error message:   ". $error)], RestController::HTTP_INTERNAL_ERROR);
        }

        try{
            $reportInfo = $this->TransactionsReportModel
                ->create_report($info['account']["iban"], $year, $month, $info['account']['accountBalance']);
        } catch (Exception $e) {
            $this->response(['status' => false,'message' => $e->getFile()
                ."   ".$e->getLine()."   ".$e->getTrace()."   ".$e->getMessage()],
                RestController::HTTP_INTERNAL_ERROR);
        }

        if(!$reportInfo)
        {
            $this->response(['status' => true,'message' => "The report already exists."],
                RestController::HTTP_BAD_REQUEST);
        }

        $reportPdf = new PdfReport($reportInfo['id'], $year, $month, $info['owner'], $info['disponents']
            , $info['account'], $this->TransactionsReportModel->get_prev_report_balance($info['account']["iban"],$year, $month)
            , $reportInfo['moneyBalance'], $reportInfo['transactions']);


        try {
            $reportPdf->sendOnMail();
        } catch (Exception $e) {
            $this->response(['status' => true,'message' => "Sending account report on e-mail for acoount: ".$info['account']["iban"]." failed. Error message:   ".$e->getMessage()],
                RestController::HTTP_BAD_REQUEST);
        }

        $this->response(['status' => true,'message' => "Bank account closed! Proccessing and sending last account report on e-mail for acoount: ".$info['account']["iban"]." successfully done."], RestController::HTTP_OK);

    }

    protected function generate_last_processInput()
    {
        $data = array(
            "iban" => $this->post("iban"),
        );
        return $data;
    }

    protected function generate_last_checkInput($data)
    {
        $errorArray = [];

        addErrorMessageIfNecessary(!empty($data["iban"]),
            $errorArray, "'iban' not defined! Key must be iban value!");

        return $errorArray;
    }

    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    /// 5 Generate all reports but private - for auto running
    ///////////////////////////////////////////////////////////////////////////////////////

    public function generate_last_month_get()
    {
        log_message('info', 'Auto reports generating begins!');

        $data['year'] = (new DateTime())->modify('first day of last month')->format('Y');
        $data['month'] = (new DateTime())->modify('first day of last month')->format('n');

        $activeAccountsIbans = $this->BankAccountModel->get_active_ibans();

        if(!$activeAccountsIbans)
            log_message('error', 'No active accounts in transactions-database!');


        foreach ($activeAccountsIbans as $tsAccountIban)
        {
            $error = '';
            $info = $this->httpCommunication($tsAccountIban["iban"], $error);
            if(!$info)
            {
                log_message('error', "Processing account report for acoount: ".$tsAccountIban['iban'].". Error message:   ". $error);
                continue;
            }


            try{
                $reportInfo = $this->TransactionsReportModel
                    ->create_report($info['account']["iban"], $data['year'], $data['month'], $info['account']['accountBalance']);
            } catch (Exception $e) {
                log_message('error', $e->getFile()."   ".$e->getLine()."   ".$e->getTrace()."   ".$e->getMessage());
            }

            if(!$reportInfo)
            {
                log_message('error', "Processing account report for acoount: ".$tsAccountIban['iban']." failed. Error message:   Account report already exists");
                continue;
            }


            $reportPdf = new PdfReport($reportInfo['id'], $data['year'], $data['month'], $info['owner'], $info['disponents']
                , $info['account'], $this->TransactionsReportModel->get_prev_report_balance($info['account']["iban"],$data['year'], $data['month'] )
                , $reportInfo['moneyBalance'], $reportInfo['transactions']);


            try {
                $reportPdf->sendOnMail();
            } catch (Exception $e) {
                log_message('error', "Sending account report on e-mail for acoount: ".$tsAccountIban['iban']." failed. Error message:   ".$e->getMessage());
                continue;
            }

            log_message('info', "Proccessing and sending account report on e-mail for acoount: ".$tsAccountIban['iban']." successfully done.");
        }

        log_message('info', "Auto reports generating successfully done!");
    }


}