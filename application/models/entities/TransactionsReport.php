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
 * @ORM\Table(name="transactionsreport", options={"collate"="utf8_unicode_ci"})
 */
class TransactionsReport
{
    ///////////////////////////////////////////////////////////////
    /// Entity columns
    //////////////////////////////////////////////////////////////

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('monthly')")
     * @var string
     */
    protected $type;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    protected $creationDate;

    /**
     * @ORM\Column(type="string", length=5)
     */
    protected $year;

    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $month;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2)
     */
    protected $finalMoneyBalance;

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param DateTime $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $date1 = new DateTime($creationDate);
        $this->creationDate = $date1;
    }

    /**
     * @param $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @param $month
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }

    /**
     * @param $moneyBalance
     */
    public function setFinalMoneyBalance($moneyBalance)
    {
        $this->finalMoneyBalance = $moneyBalance;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

     /**
     * @return mixed
     */
    public function getFinalMoneyBalance()
    {
        return $this->finalMoneyBalance;
    }




    ///////////////////////////////////////////////////////////////
    /// Relations with BankTransaction
    //////////////////////////////////////////////////////////////

    /**
     * @ORM\OneToMany(targetEntity="BankTransaction", mappedBy="transactionsReport")
     */
    protected $transactions;


    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function addTransaction(BankTransaction $bankTransaction)
    {
        $this->transactions[] = $bankTransaction;
    }

    /**
     * @return ArrayCollection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    ///////////////////////////////////////////////////////////////
    /// Relations with BankAccount
    //////////////////////////////////////////////////////////////

    /**
     * @ORM\ManyToOne(targetEntity="BankAccount", inversedBy="reports")
     */
    protected $bankAccount;


    /**
     * @param BankAccount $bankAccount
     */
    public function setBankAccount(BankAccount $bankAccount)
    {
        $bankAccount->addReport($this);
        $this->bankAccount = $bankAccount;
    }

    /**
     * @return mixed
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }


}