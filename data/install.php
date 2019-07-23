<?php

$rootPath = dirname(dirname(__FILE__));
require $rootPath  . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create($rootPath);
$dotenv->load();

$options = [
    PDO::ATTR_PERSISTENT => true,  
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION  
];

try {
  $dbcon = new PDO('mysql:host=' . getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'), $options);
  $sql = file_get_contents('data/sample-customer.sql', FILE_USE_INCLUDE_PATH);
  $dbcon->exec($sql);
  
  echo 'Sample database initialization completed!' . "\n";
} catch(PDOException $error) {
  echo $sql . $error->getMessage() . "\n";
}