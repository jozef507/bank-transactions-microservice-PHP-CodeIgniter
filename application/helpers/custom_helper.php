<?php
/**
 * VUT FIT - PIS 2021
 * Tím - PIS2021
 * Projekt - Banka (zadanie z AIS)
 *
 * Vypracoval - Jozef Ondria (xondri05)
 */


define("KOT_TRANSFER",     "transfer");
define("KOT_DEPOSIT",     "deposit");
define("KOT_WITHDRAW",     "withdraw");

define("TRST_PROCESSING",     "entered-processing");
define("TRST_SUCCESS",     "completed-successfully");
define("TRST_FAILURE",     "completed-failed");

define("CORE_BASE_URL", "http://core:5000/");
//define("CORE_BASE_URL", "http://localhost:5002/");
define("CORE_URL_TRANSACTION_EXEC", CORE_BASE_URL."transaction");
define("CORE_URL_GET_ACCOUNT", CORE_BASE_URL."account");
define("CORE_URL_GET_OWNER", CORE_BASE_URL."clients");
define("CORE_URL_GET_DISPONENTS", CORE_BASE_URL."disponents/iban");



function addErrorMessageIfNecessary($operationResult, array &$errorArray, $errorMessage)
{
    $message = "Error: " . $errorMessage;
    if(!$operationResult)
    {
        array_push($errorArray, $message);
    }
}

function getHttpCode($http_response_header)
{
    if(is_array($http_response_header))
    {
        $parts=explode(' ',$http_response_header[0]);
        if(count($parts)>1) //HTTP/1.0 <code> <text>
            return intval($parts[1]); //Get code
    }
    return 0;
}

function isMoneyAmountValid($value)
{
    if($value > 0)
        return true;
    else
        return false;
}


function setVarWithNull($var)
{
    $var = trim($var);
    if ($var === '') {
        return null;
    } elseif ($var === 'NULL') {
        return null;
    } else {
        return $var;
    }
}

function isStringLengthRight($string, $maxLength)
{
    if(strlen($string) > $maxLength)
    {
        return false;
    }
    else
    {
        return true;
    }
}

function checkEnum($value, array $enum)
{
    foreach ($enum as $item)
    {
        if (strcmp($item, $value) == 0)
        {
            return true;
        }
    }
    return false;
}

function json_output($statusHeader, $response)
{
    $ci =& get_instance();
    $ci->output->set_content_type('application/json');
    $ci->output->set_status_header($statusHeader);
    $ci->output->set_output(json_encode($response));
}

function json_output1($message)
{
    $ci =& get_instance();
    $ci->output->set_content_type('application/json');
    $ci->output->set_output(json_encode($message));
}

function assign_value($var)
{
    $var = trim($var);
    if ($var === '') {
        return null;
    } elseif ($var === 'NULL') {
        return null;
    } else {
        return $var;
    }
}
