<?php

error_reporting(E_ALL ^ E_WARNING);

$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "cc_nbp";

try {

  $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $conn->exec("SET NAMES utf8");
  $conn->exec("SET CHARACTER SET utf8");
  
} catch (PDOException $e) {
  die("Błąd połączenia z bazą danych spróbuj ponownie później.");
}
