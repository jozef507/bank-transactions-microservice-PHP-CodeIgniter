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

class BankAccountModel extends CI_Model
{
    public function get_account($iban)
    {
        $account = $this->doctrine->em
            ->getRepository('BankAccount')
            ->findOneBy(array('iban' => $iban));
        return $account;
    }
    
    public function create_account($iban)
    {
        $this->doctrine->em->getConnection()->beginTransaction();
        try {
            $account = $this->doctrine->em->getRepository('BankAccount')
                ->findOneBy(array('iban' => $iban));

            if(!$account)
            {
                $account = new BankAccount();
                $account->setIban($iban);
                $account->setIsOurAccount(true);

                $this->doctrine->em->persist($account);
            }
            else
            {
                if($account->getIsOurAccount())
                    return false;
                $account->setIsOurAccount(true);
            }

            $this->doctrine->em->flush();
            $this->doctrine->em->getConnection()->commit();
            return $account->getId();
        } catch (Exception $e) {
            $this->doctrine->em->getConnection()->rollBack();
            throw $e;
        }
    }


    public function close_account($iban)
    {
        $this->doctrine->em->getConnection()->beginTransaction();
        try {
            $account = $this->doctrine->em->getRepository('BankAccount')
                ->findOneBy(array('iban' => $iban));

            if(!$account)
            {
                return false;
            }

            $account->setIsOurAccount(false);

            $this->doctrine->em->flush();
            $this->doctrine->em->getConnection()->commit();
            return true;
        } catch (Exception $e) {
            $this->doctrine->em->getConnection()->rollBack();
            throw $e;
        }
    }

    public function get_active_ibans()
    {
        $dql = "SELECT a.iban FROM BankAccount a WHERE a.isOurAccount = 1";
        $data =  $this->doctrine->em->createQuery($dql)->getResult();
        return $data;
    }

}