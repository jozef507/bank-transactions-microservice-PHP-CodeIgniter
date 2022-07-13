<?php
/**
 * VUT FIT - PIS 2021
 * Tím - PIS2021
 * Projekt - Banka (zadanie z AIS)
 *
 * Vypracoval - Jozef Ondria (xondri05)
 */


require_once "entities/BankTransaction.php";
require_once "entities/BankAccount.php";
require_once "entities/TransactionsReport.php";


class TransactionsReportModel extends CI_Model
{
    public function get_prev_report_balance($iban, $year, $month)
    {
        $date = (new DateTime($year.'-'.$month))->modify('first day of last month');
        $prevYear = $date->format('Y');
        $prevMonth = $date->format('n');
        $report = $this->get_account_report_by_date($iban, $prevYear, $prevMonth);
        if ($report)
            return $report[0]->getFinalMoneyBalance();
        else
            return "0";
    }

    public function get_report_info($iban, $year, $month)
    {
        $report = $this->get_account_report_by_date($iban, $year, $month);
        if (!$report)
            return false;

        $transactions = $report[0]->getTransactions();

        $transactionsInfo = array();
        foreach ($transactions as $tr)
            $transactionsInfo [] = $tr->getArrayInfo();

        $reportInfo = array('id' => $report[0]->getId(), 'moneyBalance' => $report[0]->getFinalMoneyBalance()
            , 'transactions' => $transactionsInfo);
        return $reportInfo;
    }

    public function create_report($iban, $year, $month, $moneyBalance)
    {
        $report = $this->get_account_report_by_date($iban, $year, $month);
        if ($report)
            return false;
        
        $account = $this->BankAccountModel->get_account($iban);

        $this->doctrine->em->getConnection()->beginTransaction();
        try {
            $report = $this->create_report_in_db($account, $year, $month, $moneyBalance);
            $transactions = $this->BankTransactionModel
                ->get_account_transactions_by_date($account->getId(), $year, $month);

            $this->add_transactions_to_report($report, $transactions);

            $this->doctrine->em->persist($report);
            $this->doctrine->em->flush();
            $this->doctrine->em->getConnection()->commit();
        } catch (Exception $e) {
            $this->doctrine->em->getConnection()->rollBack();
            throw $e;
        }

        $transactionsInfo = array();
        foreach ($transactions as $tr)
            $transactionsInfo [] = $tr->getArrayInfo();

        $reportInfo = array('id' => $report->getId(), 'moneyBalance' => $report->getFinalMoneyBalance()
            , 'transactions' => $transactionsInfo);
        return $reportInfo;

    }

    protected function get_account_report_by_date($iban, $year, $month)
    {
        $dql = "SELECT r FROM TransactionsReport r JOIN r.bankAccount a WHERE a.iban = ?1 AND r.year = ?2 AND r.month = ?3";

        $report = $this->doctrine->em->createQuery($dql)
            ->setParameter(1, $iban)
            ->setParameter(2, $year)
            ->setParameter(3, $month)
            ->getResult();

        return $report;
    }

    protected function create_report_in_db($account, $year, $month, $moneyBalance)
    {
        $report = new TransactionsReport();
        $report->setBankAccount($account);
        $report->setCreationDate(date("Y-m-d H:i:s"));
        $report->setMonth($month);
        $report->setType("monthly");
        $report->setYear($year);
        $report->setFinalMoneyBalance($moneyBalance);

        return $report;
    }

    protected function add_transactions_to_report($report, $transactions)
    {
        foreach ($transactions as $transaction)
        {
            $report->addTransaction($transaction);
        }
    }
}