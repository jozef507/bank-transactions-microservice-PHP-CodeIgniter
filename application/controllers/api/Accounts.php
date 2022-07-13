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


class Accounts extends RestController
{
    public function create_post()
    {
        //// Input json-data processing
        $data = $this->create_processInput();

        //// Data format checking
        $errorArray = $this->create_checkInput($data);

        if(!empty($errorArray)){
            $this->response(['status' => false,'message' => $errorArray],
                RestController::HTTP_BAD_REQUEST);
        }

        try{
            $accountId = $this->BankAccountModel->create_account($data['iban']);
        } catch (Exception $e) {
            $this->response(['status' => false,'message' => $e->getFile() . "   " . $e->getLine() . "   " . $e->getTrace() . "   " . $e->getMessage()],
                RestController::HTTP_INTERNAL_ERROR);
        }

        if(!$accountId)
            $this->response(['status' => false,'message' => 'Account already exists!'],
                RestController::HTTP_BAD_REQUEST);

        $this->response(['status' => true,'message' => 'Account created!','data' => array("account_id" => $accountId)],
            RestController::HTTP_CREATED);

    }

    protected function create_processInput()
    {
        $data = [
            'iban' =>  $this->post('iban')
        ];

        return $data;
    }

    protected function create_checkInput(array $data)
    {
        $errorArray = [];

        addErrorMessageIfNecessary(isStringLengthRight($data['iban'], 30),
            $errorArray, "'iban' too long! Max length: 30 characters!");

        return $errorArray;
    }


    public function close_put()
    {
        //// Input json-data processing
        $data = $this->close_processInput();

        //// Data format checking
        $errorArray = $this->close_checkInput($data);

        if(!empty($errorArray)){
            $this->response(['status' => false,'message' => $errorArray],
                RestController::HTTP_BAD_REQUEST);
        }

        try{
            $status = $this->BankAccountModel->close_account($data['iban']);
        } catch (Exception $e) {
            $this->response(['status' => false,'message' => $e->getFile() . "   " . $e->getLine() . "   " . $e->getTrace() . "   " . $e->getMessage()],
                RestController::HTTP_INTERNAL_ERROR);
        }

        if(!$status)
            $this->response(['status' => false,'message' => "Entered account not exists!"],
                RestController::HTTP_BAD_REQUEST);

        //// OK respond getting
        //$this->response(['status' => true,'message' => 'Account closed!'],
        //    RestController::HTTP_OK);

        //$this->session->set_flashdata('iban', $data['iban']);
        redirect(base_url().'api/reports/generate_last/'.$data['iban']);

    }

    protected function close_processInput()
    {
        $data = [
            'iban' =>  $this->put('iban')
        ];

        return $data;
    }

    protected function close_checkInput(array $data)
    {
        $errorArray = [];

        addErrorMessageIfNecessary(isStringLengthRight($data['iban'], 30),
            $errorArray, "'iban' too long! Max length: 30 characters!");

        return $errorArray;
    }

}