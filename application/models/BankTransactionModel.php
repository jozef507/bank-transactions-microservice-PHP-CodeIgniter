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

class BankTransactionModel extends CI_Model
{
    public function get_transaction($id)
    {
        try {
            $transaction = $this->doctrine->em->find("BankTransaction", (int)$id);
            if(!is_null($transaction))
                return $transaction->getArrayInfo();
            else
                return false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function get_account_transactions_by_date($accountId, $year, $month)
    {
        $from = (new DateTime($year.'-'.$month))->modify('first day of this month')
            ->format('Y-m-d H-i-s');
        $to = (new DateTime($year.'-'.$month))->modify('first day of next month')
            ->format('Y-m-d H-i-s');
        
        $dqlAsSourceAccount = "SELECT t FROM BankTransaction t  LEFT JOIN t.sourceAccount s LEFT JOIN t.destinationAccount d ".
            "WHERE (s.id = ?1 OR d.id = ?1) AND (t.status = 'completed-successfully') AND (t.statusDate BETWEEN ?2 AND ?3) ORDER BY t.statusDate";

        $transactions =  $this->doctrine->em->createQuery($dqlAsSourceAccount)
            ->setParameter(1, $accountId)
            ->setParameter(2, $from)
            ->setParameter(3, $to)
            ->getResult();
        
        return $transactions;
    }

    public function get_transaction_by_account($data)
    {
        $account_iban = $data["account"];
        $date_from = $data["date_from"];
        $date_to = $data["date_to"];

        try {
            $transactions = null;

            if (is_null($date_from) || is_null($date_to))
            {
                $dql = "SELECT t FROM BankTransaction t LEFT JOIN t.sourceAccount s LEFT JOIN t.destinationAccount d ".
                    "WHERE (s.iban = ?1 OR d.iban = ?1) AND (t.status = 'completed-successfully') ORDER BY t.statusDate";

                $transactions =  $this->doctrine->em->createQuery($dql)
                    ->setParameter(1, $account_iban)
                    ->getResult();
            }
            else
            {
                $dql = "SELECT t FROM BankTransaction t LEFT JOIN t.sourceAccount s LEFT JOIN t.destinationAccount d ".
                    "WHERE (s.iban = ?1 OR d.iban = ?1) AND (t.status = 'completed-successfully') AND (t.statusDate BETWEEN ?2 AND ?3) ORDER BY t.statusDate";

                $transactions =  $this->doctrine->em->createQuery($dql)
                    ->setParameter(1, $account_iban)
                    ->setParameter(2, $date_from." 00:00:00")
                    ->setParameter(3, $date_to." 23:59:59")
                    ->getResult();
            }

            $result = array();
            foreach ($transactions as $t)
                array_push($result, $t->getArrayInfo());

            return $result;
        } catch (Exception $e) {
            throw $e;
        }

    }

    public function create_transaction($data)
    {
        $this->doctrine->em->getConnection()->beginTransaction();
        try {

            $sourceAccount = null;
            $destinationAccount = null;
            $trasaction = new BankTransaction();

            if(!is_null($data['destinationAccount']))
            {
                $destinationAccount = $this->doctrine->em->getRepository('BankAccount')
                    ->findOneBy(array('iban' => $data['destinationAccount']));

                if(!$destinationAccount)
                {
                    $destinationAccount = new BankAccount();
                    $destinationAccount->setIban($data['destinationAccount']);
                    $destinationAccount->setIsOurAccount(false);

                    $this->doctrine->em->persist($destinationAccount);
                }

                $trasaction->setDestinationAccount($destinationAccount);
            }

            if(!is_null($data['sourceAccount']))
            {
                $sourceAccount = $this->doctrine->em->getRepository('BankAccount')
                    ->findOneBy(array('iban' => $data['sourceAccount']));

                if(!$sourceAccount)
                {
                    $sourceAccount = new BankAccount();
                    $sourceAccount->setIban($data['sourceAccount']);
                    $sourceAccount->setIsOurAccount(false);

                    $this->doctrine->em->persist($sourceAccount);
                    //$this->doctrine->em->flush();
                }
                
                $trasaction->setSourceAccount($sourceAccount);
            }


            $trasaction->setKindOfTransaction($data['kindOfTransaction']);
            $trasaction->setStatus(TRST_PROCESSING);
            $trasaction->setStatusDate(date("Y-m-d H:i:s"));
            $trasaction->setMoneyAmount($data['moneyAmount']);
            $trasaction->setCurrency('CZK');
            $trasaction->setDetail($data['detail']);
            $trasaction->setClientId($data['clientId']);
            $trasaction->setClientName($data['clientName']);
            $trasaction->setEmployeeId($data['employeeId']);
            $trasaction->setEmployeeName($data['employeeName']);

            $this->doctrine->em->persist($trasaction);
            $this->doctrine->em->flush();

            $this->doctrine->em->getConnection()->commit();
            return $trasaction->getId();
        } catch (Exception $e) {
            $this->doctrine->em->getConnection()->rollBack();
            throw $e;
        }
    }

    public function update_transaction_in_creation($transactionId, $isSuccess)
    {
        $this->doctrine->em->getConnection()->beginTransaction();
        try {
            $transaction = $this->doctrine->em->find("BankTransaction", $transactionId);
            $sourceAccount = null;
            $destinationAccount = null;
            
            if($isSuccess == true)
                $transaction->setStatus(TRST_SUCCESS);
            else
                $transaction->setStatus(TRST_FAILURE);
            $transaction->setStatusDate(date("Y-m-d H:i:s"));

            $this->doctrine->em->flush();
            $this->doctrine->em->getConnection()->commit();
            return true;
        } catch (Exception $e) {
            $this->doctrine->em->getConnection()->rollBack();
            throw $e;
        }
    }
    

    
}