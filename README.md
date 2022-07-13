# Mikro-služba: Transactions (Transakcie banky)

## 1 Úvod

## 2 Návod na spustenie mikroslužby na lokálnom stroji

Mikroslužba je implementovaná v programovacom jazyku PHP, preto základom 
spustenia mikroslužby je spustenie lokálneho PHP servera. To je možné docieliť 
dvomi overenými spôsobmi: buď prostrednictvom aplikácie **XAMPP** alebo **vstavaného 
webového servera jazyka PHP**.

### 2.1 Spustenie pomocou vstavaného serveru PHP

Pidmienkou takéhoto spustenia je nainštalovaný programovací jazyk PHP v operačnom sytéme 
(odskúšané na Linux/Ubuntu). V tomto prípade je nutné z príkazového riadku systému v 
(kdekoľvek na disku uložnej) zložke 
**transacions**, ktorá osahuje zdrojové súbory mikroslužby, spustiť PHP server príkazom:
**php -S localhost:CISLO_PORTU**. Tým by mal byť PHP server spustený a mikroslužba funkčna,
čo je možne overiť dotazanim sa na server prostrednictvom URL: **localhost:CISLO_PORTU/**, 
čoho výsledkom by mal byť uvítací obsah mikroslužby, kde sa aj nachádza podrobnejší popis 
jednotlivých endpointov.

### 2.2 Spustenie pomocou XAMPP

V prvom rade je potrebné mať nainštalovanú aplikáciu XAMPP. V jej inštalačných súboroch
na disku je nutné do zložky **htdocs** nakopírovať zložku mikroslužby **transactions**,
ktorá obsahuje zdrojové súbory tejto mikroslužby. Ďalej už len stačí v aplikacii 
odštartovať **Apache Web Server** a využívať mikroslužbu. V tomto prípade je mikroslužba 
dostupná na zakladnej URL adrese: **localhost/transactions/**, ktorá po dotázani vráti 
uvítací obsah mikroslužby, kde sa aj nachádza podrobnejší popis
jednotlivých endpointov.. 
