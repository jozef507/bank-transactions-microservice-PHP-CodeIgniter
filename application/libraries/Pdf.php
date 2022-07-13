<?php
/**
 * VUT FIT - PIS 2021
 * Tím - PIS2021
 * Projekt - Banka (zadanie z AIS)
 *
 * Vypracoval - Jozef Ondria (xondri05)
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/vendor/tecnickcom/tcpdf/tcpdf.php';

class Pdf extends TCPDF
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Header() {
        // Set font
        $this->setTextColor(118,120,133);
        $this->SetFont('courier', 'B', 30);

        // Title
        $this->Cell(0, 20, 'PIS2021 Banka', 0, false, 'L', 0, '', 0, false, 'M', 'B');

        $style = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $this->Line(15, 15, 195, 15, $style);

    }

    public function load()
    {
        $objPdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        return $objPdf;
    }
}
