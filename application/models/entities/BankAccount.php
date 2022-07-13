<?php
/**
 * VUT FIT - PIS 2021
 * Tím - PIS2021
 * Projekt - Banka (zadanie z AIS)
 *
 * Vypracoval - Jozef Ondria (xondri05)
 */


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="bankaccount", options={"collate"="utf8_unicode_ci"})
 */
class BankAccount
{

    ///////////////////////////////////////////////////////////////
    /// Entity columns
    //////////////////////////////////////////////////////////////

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true, length=30)
     */
    protected $iban;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isOurAccount;

    /**
     * @param mixed $iban
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
    }

    /**
     * @param mixed $isOurAccount
     */
    public function setIsOurAccount($isOurAccount)
    {
        $this->isOurAccount = $isOurAccount;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @return mixed
     */
    public function getIsOurAccount()
    {
        return $this->isOurAccount;
    }

    ///////////////////////////////////////////////////////////////
    /// Relations with BankTransaction
    //////////////////////////////////////////////////////////////

    /**
     * @ORM\OneToMany(targetEntity="BankTransaction", mappedBy="sourceAccount")
     */
    protected $asSourAccTransactions;

    /**
     * @ORM\OneToMany(targetEntity="BankTransaction", mappedBy="destinationAccount")
     */
    protected $asDestAccTransactions;

    public function __construct()
    {
        $this->asSourAccTransactions = new ArrayCollection();
        $this->asDestAccTransactions = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }

    public function addAsSourAccTransaction(BankTransaction $bankTransaction)
    {
        $this->asSourAccTransactions[] = $bankTransaction;
    }

    public function addAsDestAccTransaction(BankTransaction $bankTransaction)
    {
        $this->asDestAccTransactions[] = $bankTransaction;
    }

    /**
     * @return ArrayCollection
     */
    public function getAsSourAccTransactions()
    {
        return $this->asSourAccTransactions;
    }

    /**
     * @return ArrayCollection
     */
    public function getAsDestAccTransactions()
    {
        return $this->asDestAccTransactions;
    }


    ///////////////////////////////////////////////////////////////
    /// Relation with TransactionsReport
    //////////////////////////////////////////////////////////////

    /**
     * @ORM\OneToMany(targetEntity="TransactionsReport", mappedBy="bankAccount")
     */
    protected $reports;


    /* above
    public function __construct()
    {
        $this->reports = new ArrayCollection();
    }*/

    public function addReport(TransactionsReport $transactionsReport)
    {
        $this->reports[] = $transactionsReport;
    }

    /**
     * @return ArrayCollection
     */
    public function getTransactions()
    {
        return $this->reports;
    }




}