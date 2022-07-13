<?php
/**
 * VUT FIT - PIS 2021
 * Tím - PIS2021
 * Projekt - Banka (zadanie z AIS)
 *
 * Vypracoval - Jozef Ondria (xondri05)
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/Pdf.php';
require_once APPPATH . '/libraries/PhpMailerLib.php';

class PdfReport
{
    private $reportId;
    private $year;
    private $month;
    private $dateFrom;
    private $dateTo;
    private $ownerInfo;
    private $disponentsInfo;
    private $accountInfo;
    private $moneyAmmountFrom;
    private $moneyAmmountTo;
    private $transactionsInfo;

    private $pdf;

    /**
     * PdfReport constructor.
     * @param $year
     * @param $month
     * @param $ownerInfo
     * @param $disponentsInfo
     * @param $accountInfo
     * @param $moneyAmmountFrom
     * @param $moneyAmmountTo
     * @param $transactionsInfo
     * @throws Exception
     */
    public function __construct($reportId, $year, $month, $ownerInfo, $disponentsInfo, $accountInfo
        , $moneyAmmountFrom, $moneyAmmountTo, $transactionsInfo)
    {
        $this->reportId = $reportId;
        $this->year = $year;
        $this->month = $month;
        $this->dateFrom = (new DateTime($year.'-'.$month))->modify('first day of this month')
            ->format('d.m.Y');
        $this->dateTo = (new DateTime($year.'-'.$month))->modify('last day of this month')
            ->format('d.m.Y');
        $this->ownerInfo = $ownerInfo;
        $this->disponentsInfo = $disponentsInfo;
        $this->accountInfo = $accountInfo;
        $this->moneyAmmountFrom = $moneyAmmountFrom;
        $this->moneyAmmountTo = $moneyAmmountTo;
        $this->transactionsInfo = $transactionsInfo;

        $this->generateReport();
    }

    private function getRowNumLines($pdf, array $strings)
    {
        $nums = array();
        foreach ($strings as $str)
        {
            $nums [] = $pdf->getNumLines($str[1], $str[0]) ;
        }

        return max($nums);
    }


    private function generateReport()
    {
        $this->pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

        $this->pdf->SetProtection(array('print', 'copy'), $this->ownerInfo['person_id_num'], null, 0, null);

        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor('PIS2021');
        $this->pdf->SetTitle('Mesačný výpis z účtu');
        $this->pdf->SetSubject('Mesačný výpis z účtu');
        $this->pdf->SetKeywords('TCPDF, PDF, report, PIS, PIS20201, VUT, FIT');

        $this->pdf->SetHeaderData("PIS Banka 2021");

        $this->pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $this->pdf->setFontSubsetting(true);
        $this->pdf->SetFont('freeserif', '', 11);
        $this->pdf->AddPage();


        /////////////////////////////////////////////////////////////
        /// Table 1

        $this->pdf->SetFillColor(200, 200, 200);
        $this->pdf->SetTextColor(0);
        $this->pdf->SetDrawColor(0);
        $this->pdf->SetLineWidth(0.2);
        $this->pdf->SetFont('', 'B');

        $this->pdf->Cell(180, 6, 'Mesačný výpis z účtu', 1, 0, 'L', 1);
        $this->pdf->Ln();

        $this->pdf->SetTextColor(0);
        $this->pdf->SetFont('');
        $this->pdf->SetFillColor(237, 237, 237);

        $this->pdf->Cell(15, 6, 'ID', 1, 0, 'L', 1);
        $this->pdf->Cell(165, 6, $this->reportId, 1, 0, 'L', 0);
        $this->pdf->Ln();
        $this->pdf->Cell(15, 6, 'Od', 1, 0, 'L', 1);
        $this->pdf->Cell(75, 6, $this->dateFrom, 1, 0, 'L', 0);
        $this->pdf->Cell(15, 6, 'Do', 1, 0, 'L', 1);
        $this->pdf->Cell(75, 6, $this->dateTo, 1, 0, 'L', 0);
        $this->pdf->Ln();
        $this->pdf->Cell(180, 0, '', 0);
        $this->pdf->Ln();

        $this->pdf->Ln(2);


        /////////////////////////////////////////////////////////////
        /// Table 2

        $this->pdf->SetFillColor(200, 200, 200);
        $this->pdf->SetTextColor(0);
        $this->pdf->SetDrawColor(0);
        $this->pdf->SetLineWidth(0.2);
        $this->pdf->SetFont('', 'B');

        $this->pdf->Cell(180, 6, 'Informácie o majiteľovi účtu', 1, 0, 'L', 1);
        $this->pdf->Ln();

        $this->pdf->SetTextColor(0);
        $this->pdf->SetFont('');
        $this->pdf->SetFillColor(237, 237, 237);

        $this->pdf->Cell(30, 6, 'Meno', 'LRT', 0, 'L', 1);
        $this->pdf->Cell(150, 6, $this->ownerInfo['name'].' '.$this->ownerInfo['surname'], 'RT', 0, 'L', 0);
        $this->pdf->Ln();
        $this->pdf->Cell(30, 6, 'Rodné číslo', 'LR', 0, 'L', 1);
        $this->pdf->Cell(150, 6, $this->ownerInfo['person_id_num'], 'R', 0, 'L', 0);
        $this->pdf->Ln();
        $this->pdf->Cell(30, 6, 'E-mail', 'LR', 0, 'L', 1);
        $this->pdf->Cell(150, 6, $this->ownerInfo['email'], 'R', 0, 'L', 0);
        $this->pdf->Ln();
        $this->pdf->Cell(30, 6, 'Telefón', 'LR', 0, 'L', 1);
        $this->pdf->Cell(150, 6, $this->ownerInfo['phone'], 'R', 0, 'L', 0);
        $this->pdf->Ln();
        $this->pdf->Cell(180, 0, '', 'T');
        $this->pdf->Ln();

        $this->pdf->Ln(2);


        /////////////////////////////////////////////////////////////
        /// Table 3

        $this->pdf->SetFillColor(200, 200, 200);
        $this->pdf->SetTextColor(0);
        $this->pdf->SetDrawColor(0);
        $this->pdf->SetLineWidth(0.2);
        $this->pdf->SetFont('', 'B');

        $this->pdf->Cell(180, 6, 'Informácie o disponentoch účtu', 1, 0, 'L', 1);
        $this->pdf->Ln();

        $this->pdf->SetTextColor(0);
        $this->pdf->SetFont('');
        $this->pdf->SetFillColor(237, 237, 237);

        foreach ($this->disponentsInfo as $disponent)
        {
            $this->pdf->Cell(30, 6, 'Meno', 'LRT', 0, 'L', 1);
            $this->pdf->Cell(150, 6, $disponent['name'].' '.$disponent['surname'], 'RT', 0, 'L', 0);
            $this->pdf->Ln();
            $this->pdf->Cell(30, 6, 'Rodné číslo', 'LR', 0, 'L', 1);
            $this->pdf->Cell(150, 6, $disponent['person_id_num'], 'R', 0, 'L', 0);
            $this->pdf->Ln();
        }

        $this->pdf->Cell(180, 0, '', 'T');
        $this->pdf->Ln();

        $this->pdf->Ln(2);

        /////////////////////////////////////////////////////////////
        /// Table 4

        $this->pdf->SetFillColor(200, 200, 200);
        $this->pdf->SetTextColor(0);
        $this->pdf->SetDrawColor(0);
        $this->pdf->SetLineWidth(0.2);
        $this->pdf->SetFont('', 'B');

        $this->pdf->Cell(180, 6, 'Informácie o účte', 1, 0, 'L', 1);
        $this->pdf->Ln();

        $this->pdf->SetTextColor(0);
        $this->pdf->SetFont('');
        $this->pdf->SetFillColor(237, 237, 237);

        $this->pdf->Cell(30, 6, 'IBAN', 'LRT', 0, 'L', 1);
        $this->pdf->Cell(150, 6, $this->accountInfo['iban'], 'RT', 0, 'L', 0);
        $this->pdf->Ln();
        $this->pdf->Cell(30, 6, 'Názov účtu', 'LR', 0, 'L', 1);
        $this->pdf->Cell(150, 6, $this->accountInfo['accountName'], 'R', 0, 'L', 0);
        $this->pdf->Ln();
        $this->pdf->Cell(30, 6, 'Mena', 'LR', 0, 'L', 1);
        $this->pdf->Cell(150, 6, $this->accountInfo['currency'], 'R', 0, 'L', 0);
        $this->pdf->Ln();
        $this->pdf->Cell(30, 6, 'Dátum otvorenia', 'LR', 0, 'L', 1);
        $this->pdf->Cell(150, 6, (new DateTime($this->accountInfo['dateOpened']))->format('d.m.Y'), 'R', 0, 'L', 0);
        $this->pdf->Ln();
        $this->pdf->Cell(180, 0, '', 'T');
        $this->pdf->Ln();

        $this->pdf->Ln(2);


        /////////////////////////////////////////////////////////////
        /// Table 5

        $this->pdf->SetFillColor(200, 200, 200);
        $this->pdf->SetTextColor(0);
        $this->pdf->SetDrawColor(0);
        $this->pdf->SetLineWidth(0.2);
        $this->pdf->SetFont('', 'B');

        $this->pdf->Cell(180, 6, 'Informácie o zostatkoch', 1, 0, 'L', 1);
        $this->pdf->Ln();

        $this->pdf->SetTextColor(0);
        $this->pdf->SetFont('');
        $this->pdf->SetFillColor(237, 237, 237);

        $this->pdf->Cell(40, 6, 'Počiatočný zostatok', 'LRT', 0, 'L', 1);
        $this->pdf->Cell(140, 6, $this->moneyAmmountFrom, 'RT', 0, 'L', 0);
        $this->pdf->Ln();
        $this->pdf->Cell(40, 6, 'Konečný zostatok', 'LR', 0, 'L', 1);
        $this->pdf->Cell(140, 6, $this->moneyAmmountTo, 'R', 0, 'L', 0);
        $this->pdf->Ln();
        $this->pdf->Cell(180, 0, '', 'T');
        $this->pdf->Ln();

        $this->pdf->Ln(2);


        /////////////////////////////////////////////////////////////
        /// Table 6


        $this->pdf->Ln(5);

        $this->pdf->SetFillColor(200, 200, 200);
        $this->pdf->SetTextColor(0);
        $this->pdf->SetDrawColor(0);
        $this->pdf->SetLineWidth(0.2);
        $this->pdf->SetFont('', 'B');

        $this->pdf->Cell(180, 6, 'Informácie o transakciách', 1, 0, 'L', 1);
        $this->pdf->Ln();


        $this->pdf->SetTextColor(0);
        $this->pdf->SetFont('');
        $this->pdf->SetFillColor(237, 237, 237);

        $this->pdf->Cell(20, 6, 'Dátum', 1, 0, 'C', 1);
        $this->pdf->Cell(120, 6, 'Popis', 1, 0, 'C', 1);
        $this->pdf->Cell(40, 6, 'Suma', 1, 0, 'C', 1);
        $this->pdf->Ln();

        $this->pdf->SetFont('', '', 11);

        foreach ($this->transactionsInfo as $transaction)
        {
            $str1 = (new DateTime($transaction['statusDate']))->format('d.m.Y');
            $str2 = $this->getKindOfTransaction($transaction['kindOfTransaction']);
            $str3 = $transaction['moneyAmount'];
            $isItSourceAccount = $this->isItSourceAccount($transaction['sourceAccount'], $transaction['destinationAccount']);
            $numLines = $this->getRowNumLines($this->pdf, [[20, $str1],[105, $str2],[40, $str3]]);
            $this->pdf->MultiCell(20, $numLines *5, $str1, 'LRT', 'C', 0, 0, '', '', false);
            $this->pdf->MultiCell(15, $numLines *5, 'Typ', 'TLR', 'R', 1, 0, '', '', false);
            $this->pdf->MultiCell(105, $numLines *5, $str2, 'RT', 'L', 0, 0, '', '', false);
            $isItSourceAccount  ?  $this->pdf->SetTextColor(179, 0, 0)  :  $this->pdf->SetTextColor(0, 153, 0);
            $this->pdf->MultiCell(40, $numLines *5, $str3, 'LRT', 'C', 0, 0, '' ,'', false);
            $this->pdf->SetTextColor(0);
            $this->pdf->Ln();
            $this->pdf->Cell(20, 5, '', 'LR', 0, 'C', 0);
            $this->pdf->Cell(15, 5, 'Z účtu', 'LR', 0, 'R', 1);
            if($isItSourceAccount)
                $this->pdf->SetFont('', 'B');
            $this->pdf->Cell(105, 5, $transaction['sourceAccount'], 'R', 0, 'L', 0);
            $this->pdf->SetFont('', '');
            $this->pdf->Cell(40, 5, '', 'LR', 0, 'C', 0);
            $this->pdf->Ln();
            $this->pdf->Cell(20, 5, '', 'LR', 0, 'C', 0);
            $this->pdf->Cell(15, 5, 'Na účet', 'LR', 0, 'R', 1);
            if(!$isItSourceAccount)
                $this->pdf->SetFont('', 'B');
            $this->pdf->Cell(105, 5, $transaction['destinationAccount'], 'R', 0, 'L', 0);
            $this->pdf->SetFont('', '');
            $this->pdf->Cell(40, 5, '', 'LR', 0, 'C', 0);
            $this->pdf->Ln();
            $this->pdf->Cell(20, 5, '', 'LRB', 0, 'C', 0);
            $this->pdf->Cell(15, 5, 'Žiadateľ', 'LRB', 0, 'R', 1);
            $this->pdf->Cell(105, 5, $transaction['clientName'], 'RB', 0, 'L', 0);
            $this->pdf->Cell(40, 5, '', 'LRB', 0, 'C', 0);
            $this->pdf->Ln();
        }

    }

    private function getKindOfTransaction($kind)
    {
        if ($kind == 'transfer')
            return 'Prevod peňazí medzi účtami';
        elseif ($kind == 'deposit')
            return 'Vklad peňazí na účet';
        else
            return 'Výber peňazí z účtu';
    }

    private function isItSourceAccount($sourceAccount, $destinationAccount)
    {
        if (strtoupper($this->accountInfo['iban']) == strtoupper($sourceAccount))
            return true;
        else
            return false;
    }


    public function sendOnMail()
    {

        $file = $this->pdf->Output('rep_'.$this->accountInfo['iban'].'_'.$this->year.'_'.$this->month.'.pdf', 'S');


        $mail = PhpMailerLib::load();

        $mail->Encoding = 'base64';
        $mail->CharSet = 'UTF-8';

        $mail->IsSMTP();
        //$mail->SMTPDebug  = 1;
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = "example@example.com";
        $mail->Password   = "example";

        $mail->setFrom('example@example.com', 'Pis2021 Banka');
        $mail->addAddress($this->ownerInfo['email']);
        $mail->addReplyTo('example@example.com', 'Pis2021 Banka');

        $mail->AddStringAttachment($file, 'rep_'.$this->accountInfo['iban'].'_'.$this->year.'_'.$this->month.'.pdf', 'base64', 'application/pdf');

        $mail->isHTML(true);
        $mail->Subject = 'Mesacny vypis';
        $mail->Body    = 'Vážený zákaznik,<br><br>v prílohe tohto mailu Vám zasielame mesačný výpis k Vášmu účtu <b>'.$this->accountInfo['iban'].
            '</b> za obdobie <b>'.$this->month.'-'.$this->year.'</b>.';
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        try {
            $mail->send();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getThroughHttp()
    {
        $this->pdf->Output('rep_'.$this->accountInfo['iban'].'_'.$this->year.'_'.$this->month.'.pdf', 'I');
    }
}
