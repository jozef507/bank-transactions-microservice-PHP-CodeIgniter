<?php
/**
 * VUT FIT - PIS 2021
 * Tím - PIS2021
 * Projekt - Banka (zadanie z AIS)
 *
 * Vypracoval - Jozef Ondria (xondri05)
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class PhpMailerLib
{
    public function __construct()
    {
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    public static function load()
    {
        require_once(dirname(__FILE__) . '/vendor/phpmailer/phpmailer/src/Exception.php');
        require_once(dirname(__FILE__) . '/vendor/phpmailer/phpmailer/src/PHPMailer.php');
        require_once(dirname(__FILE__) . '/vendor/phpmailer/phpmailer/src/SMTP.php');

        $objMail = new PHPMailer(true);;
        return $objMail;
    }
}