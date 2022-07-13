<?php

/**
 * VUT FIT - PIS 2021
 * Tím - PIS2021
 * Projekt - Banka (zadanie z AIS)
 *
 * Vypracoval - Jozef Ondria (xondri05)
 */


use Doctrine\Common\ClassLoader,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\Cache\ArrayCache,
    Doctrine\DBAL\Logging\EchoSQLLogger,
    Doctrine\ORM\Tools\Setup;


class Doctrine {

    public $em = null;

    public function __construct()
    {
        require_once "vendor/autoload.php";
        //require_once dirname(__DIR__, 1) .'/config/database.php';
        // Create a simple "default" Doctrine ORM configuration for Annotations
        $isDevMode = true;
        $proxyDir = null;
        $cache = null;
        $useSimpleAnnotationReader = false;
        $config = Setup::createAnnotationMetadataConfiguration(array( APPPATH."/models/entities"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
        // or if you prefer yaml or XML
        //$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
        //$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

        // database configuration parameters
        $conn = array(
            'driver' => 'pdo_mysql',
            'user' =>     'pis',
            'password' => 'QzaQ&G4q?Pf@?7KTp',
            'host' =>     'garbage.profihost.cloud:3306',
            'dbname' =>   'pis_transactions_microservice',
            'charset'		=>	'UTF8',
            'driverOptions'	=> array('1002'=> "SET NAMES 'UTF8' COLLATE 'utf8_general_ci'")
        );
        /*$conn = array(
               'driver' => 'pdo_mysql',
                  'user' =>     'root',
                 'password' => '',
                 'host' =>     '127.0.0.1:3306',
                'dbname' =>   'test');*/


        // obtaining the entity manager
        $this->em = EntityManager::create($conn, $config);

        $conn1 = $this->em->getConnection();
        $conn1->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

    }
}