<?php
$host = '127.0.0.1';
$user = 'root';
$pass = 'root';
$db = 'Cybevasion';
require_once "../vendor/autoload.php";

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: array(__DIR__ . "/../src"),
    isDevMode: true,
);

$dbParams = [
    'driver' => 'pdo_pgsql',
    'host' => '127.0.0.1',
    'user' => 'root',
    'password' => 'root',
    'dbname' => 'Cybevasion',
];
$connection = DriverManager::getConnection($dbParams, $config);
$entityManager = new EntityManager($connection, $config);