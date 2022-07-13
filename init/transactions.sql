use pis_transactions_microservice;

insert into bankaccount (iban, isOurAccount) values ('CZ6907101781240000004159', 1);
insert into bankaccount (iban, isOurAccount) values ('CZ6508000000192000145399', 1);
insert into bankaccount (iban, isOurAccount) values ('CZ6907101781240000000001', 1);
insert into bankaccount (iban, isOurAccount) values ('CZ6907101781240000000025', 1);
insert into bankaccount (iban, isOurAccount) values ('CZ6907101781240000000203', 1);
insert into bankaccount (iban, isOurAccount) values ('AL35202111090000000001234567', 1);
insert into bankaccount (iban, isOurAccount) values ('CZ5508000000001234567899', 1);
insert into bankaccount (iban, isOurAccount) values ('CZJICSR00000757771716000744279', 1);
insert into bankaccount (iban, isOurAccount) values ('DK9520000123456789', 1);
insert into bankaccount (iban, isOurAccount) values ('CZ6907101781240000008699', 0);
insert into bankaccount (iban, isOurAccount) values ('CZ6907101781240000017894', 0);
insert into bankaccount (iban, isOurAccount) values ('CZ6508000000192000555555', 0);
insert into bankaccount (iban, isOurAccount) values ('CZ6508000000192000666666', 0);


insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('transfer', 'completed-successfully', '2021-03-01 08:01:25', 1755.55, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', 1 , 2, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('transfer', 'completed-successfully', '2021-03-03 22:01:25', 500.55, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', 2 , 1, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('deposit', 'completed-successfully', '2021-03-05 18:01:25', 2000, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', null , 1, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('deposit', 'completed-successfully', '2021-03-06 18:01:25', 2000, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', null , 1, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('withdraw', 'completed-successfully', '2021-03-07 22:01:25', 1000, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', 1 , null, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('transfer', 'completed-successfully', '2021-03-15 22:01:25', 1000.45, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', 6 , 1, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('transfer', 'completed-successfully', '2021-03-20 22:01:25', 1500.55, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', 1 , 7, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('transfer', 'completed-successfully', '2021-03-24 22:01:25', 600.55, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', 8 , 1, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('deposit', 'completed-successfully', '2021-03-24 22:01:25', 2000, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', null , 1, null);


insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('transfer', 'completed-successfully', '2021-03-01 08:01:25', 555.55, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', 2 , 3, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('transfer', 'completed-successfully', '2021-03-03 22:01:25', 400.55, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', 1 , 2, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('deposit', 'completed-successfully', '2021-03-05 18:01:25', 2000, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', null , 2, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('deposit', 'completed-successfully', '2021-03-06 18:01:25', 100, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', null , 2, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('withdraw', 'completed-successfully', '2021-03-07 22:01:25', 500, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', 2 , null, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('transfer', 'completed-successfully', '2021-03-15 22:01:25', 777.45, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', 6 , 2, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('transfer', 'completed-successfully', '2021-03-20 22:01:25', 2222.55, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', 2 , 8, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('transfer', 'completed-successfully', '2021-03-24 22:01:25', 555.55, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', 9 , 2, null);
insert into banktransaction (kindOfTransaction, status, statusDate, moneyAmount, currency, detail, clientId, clientName, employeeId, employeeName, sourceAccount_id, destinationAccount_id, transactionsReport_id)
    values ('deposit', 'completed-successfully', '2021-03-24 22:01:25', 7777, 'CZK', 'detail 1', 1, 'Jan Apo', 1, 'Marek Uličný', null , 2, null);


