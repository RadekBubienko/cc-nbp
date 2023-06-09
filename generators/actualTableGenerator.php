<?php

//generowanie tabelki z aktualną tabelą walut z NBP

require_once "./helpers/functiondatepl.php";

class ActualTableGenerator
{
  private $currenciesGetter;

  public function __construct($currenciesGetter)
  {
    $this->currenciesGetter = $currenciesGetter;
  }

  public function generateTable()
  {
    $currencies = $this->currenciesGetter->getCurrencies();

    if (empty($currencies)) {
      echo "Baza danych jest pusta.";
    } else {

      $rates = json_decode($currencies['rates'], true);
      $tableDate = $currencies["effective_date"];
      $tableDatePL = date_create($tableDate);
      $tableNumber = $currencies["no"];

      echo '<a href="index.php" class="anchor__top"><div class="anchor__container--top">Powrót do Kalkulatora Walut</div></a>';
      echo '<h1 class="header">Aktualna tabela kursów</h1>';
      echo '<table class="table">';
      echo '<h2 class="table__number">Tabela: ' . $tableNumber . ' z dnia: ' . date_pl(date_format($tableDatePL, "d.m.Y")) . '</h2>';
      echo '<tr><th style="width: 60%" class="table__tableCell" scope="row">Waluta</th><th style="width: 15%; text-align: center" class="table__tableCell" scope="row">Kod</th><th class="table__tableCell" scope="row">Kurs</th></tr>';
      foreach ($rates as $rate) {
        echo '<tr>';
        echo '<td class="table__tableCell" scope="row">' . $rate['currency'] . '</td>';
        echo '<td style="text-align: center" class="table__tableCell" scope="row">' . $rate['code'] . '</td>';
        echo '<td class="table__tableCell" scope="row">' . $rate['mid'] . '</td>';
        echo '</tr>';
      }

      echo '</table>';

      echo '<a href="index.php" class="anchor"><div class="anchor__container">Powrót do Kalkulatora Walut</div></a>';
    }
  }
}
