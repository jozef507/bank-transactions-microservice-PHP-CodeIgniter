<?php
/**
 * VUT FIT - PIS 2021
 * Tím - PIS2021
 * Projekt - Banka (zadanie z AIS)
 *
 * Vypracoval - Jozef Ondria (xondri05)
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="banktransaction", options={"collate"="utf8_unicode_ci"})
 */
class BankTransaction
{
    ///////////////////////////////////////////////////////////////
    /// Entity Columns
    //////////////////////////////////////////////////////////////

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('transfer', 'deposit', 'withdraw')")
     */
    protected $kindOfTransaction;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('entered-processing', 'completed-successfully', 'completed-failed', 'canceled')")
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $statusDate;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2)
     */
    protected $moneyAmount;

    /**
     * @ORM\Column(type="string")
     */
    protected $currency;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $detail;

    /**
     * @ORM\Column(type="integer")
     */
    protected $clientId;

    /**
     * @ORM\Column(type="string")
     */
    protected $clientName;

    /**
     * @ORM\Column(type="integer")
     */
    protected $employeeId;

    /**
     * @ORM\Column(type="string")
     */
    protected $employeeName;

    /**
     * @param mixed $kindOfTransaction
     */
    public function setKindOfTransaction($kindOfTransaction)
    {
        $this->kindOfTransaction = $kindOfTransaction;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param mixed $date
     */
    public function setStatusDate($date)
    {
        $date1 = new DateTime($date);
        $this->statusDate = $date1;
    }

    /**
     * @param mixed $moneyAmount
     */
    public function setMoneyAmount($moneyAmount)
    {
        $this->moneyAmount = $moneyAmount;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @param mixed $detail
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @param mixed $clientName
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;
    }

    /**
     * @param mixed $employeeId
     */
    public function setEmployeeId($employeeId)
    {
        $this->employeeId = $employeeId;
    }

    /**
     * @param mixed $employeeName
     */
    public function setEmployeeName($employeeName)
    {
        $this->employeeName = $employeeName;
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
    public function getKindOfTransaction()
    {
        return $this->kindOfTransaction;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getStatusDate()
    {
        return $this->statusDate;
    }

    /**
     * @return mixed
     */
    public function getMoneyAmount()
    {
        return $this->moneyAmount;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return mixed
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return mixed
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * @return mixed
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * @return mixed
     */
    public function getEmployeeName()
    {
        return $this->employeeName;
    }


    ///////////////////////////////////////////////////////////////
    /// Relations with BankAccount
    //////////////////////////////////////////////////////////////

    /**
     * @ORM\ManyToOne(targetEntity="BankAccount", inversedBy="asSourAccTransactions")
     */
    protected $sourceAccount;

    /**
     * @ORM\ManyToOne(targetEntity="BankAccount", inversedBy="asDestAccTransactions")
     */
    protected $destinationAccount;


    /**
     * @param BankAccount $sourceAccount
     */
    public function setSourceAccount(BankAccount $sourceAccount)
    {
        $sourceAccount->addAsSourAccTransaction($this);
        $this->sourceAccount = $sourceAccount;
    }

    /**
     * @param BankAccount $destinationAccount
     */
    public function setDestinationAccount(BankAccount $destinationAccount)
    {
        $destinationAccount->addAsDestAccTransaction($this);
        $this->destinationAccount = $destinationAccount;
    }

    /**
     * @return mixed
     */
    public function getSourceAccount()
    {
        return $this->sourceAccount;
    }

    /**
     * @return mixed
     */
    public function getDestinationAccount()
    {
        return $this->destinationAccount;
    }



    ///////////////////////////////////////////////////////////////
    /// Relations with TransactionsReport
    //////////////////////////////////////////////////////////////

    /**
     * @ORM\ManyToOne(targetEntity="TransactionsReport", inversedBy="transactions")
     */
    protected $transactionsReport;


    /**
     * @param TransactionsReport $transactionsReport
     */
    public function setTransactionsReport(TransactionsReport $transactionsReport)
    {
        $transactionsReport->assignedToBankTransaction($this);
        $this->transactionsReport = $transactionsReport;
    }

    /**
     * @return mixed
     */
    public function getTransactionsReport()
    {
        return $this->transactionsReport;
    }





    ///////////////////////////////////////////////////////////////
    /// To array info
    //////////////////////////////////////////////////////////////

    public function getArrayInfo()
    {
        $transactionArray = array(
            "id" => $this->id,
            "kindOfTransaction" => $this->kindOfTransaction,
            "status" => $this->status,
            "statusDate" => $this->statusDate->format('Y-m-d H:i:s'),
            "moneyAmount" => $this->moneyAmount,
            "currency" => $this->currency,
            "detail" => $this->detail,
            "clientId" => $this->clientId,
            "clientName" => $this->clientName,
            "employeeId" => $this->employeeId,
            "employeeName" => $this->employeeName,
            "sourceAccount" => null,
            "sourceAccountIsOur" => null,
            "destinationAccount" => null,
            "destinationAccountIsOur" => null
        );

        if(!is_null($this->sourceAccount))
        {
            $transactionArray["sourceAccount"] = $this->sourceAccount->getIban();
            $transactionArray["sourceAccountIsOur"] = $this->sourceAccount->getIsOurAccount();
        }
        if(!is_null($this->destinationAccount))
        {
            $transactionArray["destinationAccount"] = $this->destinationAccount->getIban();
            $transactionArray["destinationAccountIsOur"] = $this->destinationAccount->getIsOurAccount();
        }
        return $transactionArray;
    }
}