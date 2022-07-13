<?php

/**
 * VUT FIT - PIS 2021
 * Tím - PIS2021
 * Projekt - Banka (zadanie z AIS)
 *
 * Vypracoval - Jozef Ondria (xondri05)
 */

defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 20px;
		font-weight: bold;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	h2 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 17px;
		font-weight: bolder;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	h3 {
		color: #444;
		background-color: transparent;
		font-size: 15px;
		font-weight: bolder;
		margin: 0 0 14px 0;
		padding: 14px 0 0 0;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 14px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>


<div id="container">
    <h1>Mikro-služba Transactions (Bankové transakcie)</h1>

    <div id="body">
        <p>Táto stránka obsahuje jednotlive API mikroslužby</p>

    </div>

        <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
    </div>
</div>


<div id="container">
    <h2>Príklad tela negatívnej odpovede mikroslužby</h2>

    <div id="body">
        <code>
            {<br>
            "status": false,<br>
            "message": "Error message!",<br>
            }
        </code>
    </div>
</div>

<div id="container">
    <h2>Vytvorenie účtu</h2>

    <div id="body">
        <code>POST <br> <?php echo base_url() ?>api/accounts/create</code>

        <code>
            Content-Type: application/json<br>
            Input data:<br>
            iban = reťazec - iban novo-vytvoreného účtu v Core
        </code>
        <p>Vytvorí účet v transactions service, ktorý sa nastaví ako účet "našej" banky.</p>


        <h3>Príklad žiadosti:</h3>
        <code>
            http://localhost:8080/api/accounts/create<br>
            {<br>
            "iban": "CZ6907101781240000004159"<br>
            }
        </code>

        <h3>Príklad tela odpovede (json):</h3>
        <code>
            {<br>
            "status": true,<br>
            "message": "Account created!",<br>
            "data": {<br>
            "account_id": 10<br>
            }<br>
            }
        </code>
    </div>
</div>

<div id="container">
    <h2>Uzatvorenie/deaktivácia účtu</h2>

    <div id="body">
        <code>PUT <br> <?php echo base_url() ?>api/accounts/close</code>

        <code>
            Content-Type: application/json<br>
            Input data:<br>
            iban = reťazec - iban novo-vytvoreného účtu v Core
        </code>
        <p>Uzatvorí resp. deaktivuje účet v transactions service. Vygeneruje sa posledný výpis z účtu. Tomuto účtu sa už nebudú generovať výpisy z účtu.</p>


        <h3>Príklad žiadosti:</h3>
        <code>
            http://localhost:8080/api/accounts/close<br>
            {<br>
            "iban": "CZ6907101781240000004159"<br>
            }
        </code>

        <h3>Príklad tela odpovede (json):</h3>
        <code>
            {<br>
            "status": true,<br>
            "message": "Account closed!"<br>
            }
        </code>
    </div>
</div>


<div id="container">
    <h2>Dáta konkrétnej transakcie</h2>

    <div id="body">
        <code>GET <br> <?php echo base_url() ?>api/transactions/{transakcia_id}</code>

		<p>Vráti všetky dáta z DB pre transakciu definoanú identifikátorom {transakcia_id}.</p>


		<h3>Príklad žiadosti:</h3>
        <code>
            http://localhost:8080/api/transactions/5
        </code>

        <h3>Príklad tela odpovede (json):</h3>
        <code>
            {<br>
            "status": true,<br>
            "message": "Transaction found!",<br>
            "data":<br>
            {<br>
            "id": 5,<br>
            "kindOfTransaction": "transfer",<br>
            "status": "completed-successfully",<br>
            "statusDate": "2021-04-10 22:05:36",<br>
            "moneyAmount": "14755.55",<br>
            "currency": "CZK",<br>
            "detail": "ahoj",<br>
            "clientId": 2,<br>
            "clientName": "Marek Ulicny",<br>
            "employeeId": 1,<br>
            "employeeName": "Erika Forisova",<br>
            "sourceAccount": "CZ6508000000192000145399",<br>
            "sourceAccountIsOur": true,<br>
            "destinationAccount": "CZ6907101781240000004159",<br>
            "destinationAccountIsOur": true<br>
            }<br>
            }
        </code>
    </div>
</div>

<div id="container">
    <h2>Dáta transakcii konkretného bankového účtu</h2>

    <div id="body">
        <code>GET <br> <?php echo base_url() ?>api/transactions/of_account?account={iban_uctu}&date_from={datum_od}&date_to={datum_do}</code>

		<p>
            Vráti všetky úspešne vykonané transakcie bankového účtu definovaným {iban_uctu} v období od dňa {datum_od} do {datum_do}
            (obe dátumy v rátane, format vstupov v priklade). V prípade nezadania parametra {datum_od} alebo {datum_do} sa vypíšu
            všetky transakcie daného účtu v banke. V prípade nezadania parametru {iban_uctu} bude vrátený neúspech -> BAD_REQUEST.
        </p>


		<h3>Príklad žiadosti:</h3>
        <code>
            http://localhost:8080/api/transactions/of_account?account=CZ6907101781240000004159&date_from=2021-04-11&date_to=2021-04-12
        </code>

        <h3>Príklad tela odpovede (json):</h3>
        <code>
            {<br>
            "status": true,<br>
            "message": "Transactions found!",<br>
            "data": <br>
            [<br>
            {<br>
            "id": 5,<br>
            "kindOfTransaction": "transfer",<br>
            "status": "completed-successfully",<br>
            "statusDate": "2021-04-10 22:05:36",<br>
            "moneyAmount": "14755.55",<br>
            "currency": "CZK",<br>
            "detail": "ahoj",<br>
            "clientId": 2,<br>
            "clientName": "Marek Ulicny",<br>
            "employeeId": 1,<br>
            "employeeName": "Erika Forisova",<br>
            "sourceAccount": "CZ6508000000192000145399",<br>
            "sourceAccountIsOur": true,<br>
            "destinationAccount": "CZ6907101781240000004159",<br>
            "destinationAccountIsOur": true<br>
            },<br>
            {<br>
            "id": 8,<br>
            "kindOfTransaction": "transfer",<br>
            "status": "completed-successfully",<br>
            "statusDate": "2021-04-11 00:08:25",<br>
            "moneyAmount": "1454.54",<br>
            "currency": "CZK",<br>
            "detail": "ahoj",<br>
            "clientId": 2,<br>
            "clientName": "Marek Ulicny",<br>
            "employeeId": 1,<br>
            "employeeName": "Erika Forisova",<br>
            "sourceAccount": "CZ6907101781240000004159",<br>
            "sourceAccountIsOur": true,<br>
            "destinationAccount": "CZ6508000000192000145399",<br>
            "destinationAccountIsOur": true<br>
            }<br>
            ]<br>
            }
        </code>
    </div>
</div>

<div id="container">
    <h2>Vytvorenie/vykonanie transakcie</h2>

    <div id="body">
        <code>POST <br> <?php echo base_url() ?>api/transactions/create_transaction</code>

        <code>
            Content-Type: application/json<br>
            Input data:<br>
            kindOfTransaction = {'transfer', 'deposit', 'withdraw'} - druh transakcie<br>
            moneyAmount = {čislo xxx.xx} - čiastka<br>
            detail = {string, prazdny_string/'NULL'} - popis<br>
            clientId = {čislo} - identifikator klienta ktory transakciu zadava/požaduje<br>
            clientName = {string} - meno klienta klienta ktory transakciu zadava/požaduje<br>
            employeeId = {čislo} - identifikator pracovnika ktory na žiadosť klienta transakciu vykonáva<br>
            employeeName = {string} - meno pracovnika ktory na žiadosť klienta transakciu vykonáva<br>
            sourceAccount = {iban_string, prazdny_string/'NULL'} - iban zdrojoveho učtu, NULL v pripade vkladu/depositu<br>
            destinationAccount = {iban_string, prazdny_string/'NULL'} - iban cieľového učtu, NULL v pripade výberu/withdraw
        </code>
		<p>Vytvorí transakciu a poverí Core na jej prevedenie.</p>


		<h3>Príklad žiadosti:</h3>
        <code>
            http://localhost:8080/api/transactions/create_transaction<br>
            {<br>
            "kindOfTransaction": "transfer",<br>
            "moneyAmount": 1454.54,<br>
            "detail": "ahoj2",<br>
            "clientId": 2,<br>
            "clientName": "Marek Ulicny",<br>
            "employeeId": 1,<br>
            "employeeName": "Erika Forisova",<br>
            "sourceAccount": "CZ6907101781240000004159",<br>
            "destinationAccount": "CZ6508000000192000145399"<br>
            }

        </code>

        <h3>Príklad tela odpovede (json):</h3>
        <code>
            {<br>
            "status": true,<br>
            "message": "Transactions found!",<br>
            "data": <br>
            {<br>
            "transaction_id": 5<br>
            }<br>
            }<br>
        </code>
    </div>
</div>


<div id="container">
    <h2>Zobrazenie pdf reportu konkretneho účtu</h2>

    <div id="body">
        <code>POST <br> <?php echo base_url() ?>/api/reports/</code>

        <code>
            Content-Type: application/json<br>
            Input data:<br>
            iban = string - iban účtu<br>
            year = číslo - číslo kalendárneho roka (2021)<br>
            month = {1,2,...,12} - číslo kalendárneho mesiaca
        </code>
		<p>Vygeneruje pdf a priamo vrati cez HTTP.</p>


		<h3>Príklad žiadosti:</h3>
        <code>
            http://localhost:5001/api/reports/<br>
            {<br>
            "iban": "AL35202111090000000001234567",<br>
            "year": 2021,<br>
            "month": 3<br>
            }
        </code>
    </div>
</div>

<div id="container">
    <h2>Vygenerovanie všetkych vypisov účtu z konkretného obdobia (manuálne - obhajoba)</h2>

    <div id="body">
        <code>POST <br> <?php echo base_url() ?>/api/reports/generate</code>

        <code>
            Content-Type: application/json<br>
            Input data:<br>
            year - číslo - rok
            month - číslo {1,...,12}
        </code>
		<p>Vygenerovanie reportov všetkých aktívnych účtov.</p>


		<h3>Príklad žiadosti:</h3>
        <code>
            http://localhost:5001/api/reports/generate<br>
            {<br>
            "year": 2021,<br>
            "month": 3<br>
            }
        </code>
    </div>
</div>


</body>
</html>