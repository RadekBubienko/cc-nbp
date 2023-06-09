<?php

session_start();
session_unset();
session_destroy();

require_once "./api/getDataFromApiNBP.php";
require_once "./api/getCurrenciesFromDatabase.php";
require_once "./api/getCalculationsFromDatabase.php";
require_once "./generators/currencyFormGenerator.php";
require_once "./generators/actualTableGenerator.php";
require_once "./generators/madeCalculationsGenerator.php";

$currenciesGetter = new GetCurrenciesFromDatabase($conn);
$calculationsGetter = new GetCalculationsFromDatabase($conn);
$actualTableGenerator = new ActualTableGenerator($currenciesGetter);
$madeCalculationsGenerator = new MadeCalculationsGenerator($calculationsGetter);
$currencyForm = new CurrencyFormGenerator($data);

?>

<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kalkulator Walut</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="icon" href="images/icon.png">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/form.css" rel="stylesheet">
  <link href="css/table.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
  <script defer src="js/script.js"></script>
</head>