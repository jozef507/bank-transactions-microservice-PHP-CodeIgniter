<?php
// cli-config.php
//require_once "libraries/bootstrap.php";

define('APPPATH', dirname(__FILE__) . '/');

require_once "libraries/Doctrine.php";

$doctrine = new Doctrine;
$entityManager = $doctrine->em;

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);