<?php

class MadeCalculationsGenerator
{
  private $calculationsGetter;

  public function __construct($calculationsGetter)
  {
    $this->calculationsGetter = $calculationsGetter;
  }

  public function generateTableCalculations()
  {
    $calculations = $this->calculationsGetter->getCalculations();

    if (empty($calculations)) {
      echo "Baza danych jest pusta.";
    } else {

      echo '<a href="index.php" class="anchor__top"><div class="anchor__container--top">Powrót do Kalkulatora Walut</div></a>';
      echo '<h1 class="header">Zapisane kalkulacje</h1>';
      echo '<h2 class="table__number">20 ostatnich kalkulacji</h2>';
      echo '<table class="table">';
      echo '<tr><th class="table__tableCell">Z dnia</th><th class="table__tableCell">Zadeklarowana kwota</th><th class="table__tableCell">Wynik kalkulacji</th></tr>';
      foreach ($calculations as $calculation) {

        $savedDate = date_create($calculation['saved_date']);
        $formattedDate = date_format($savedDate, 'd-m-Y H:i:s');

        echo "<tr>";
        echo '<td class="table__tableCell">' . $formattedDate . '</td>';
        echo '<td class="table__tableCell">' . number_format($calculation["amount_entered"],2, ".", "," ) . ' '  . $calculation["code_currency_from"] . ' (' . $calculation["currency_from"] . ') </td>';
        echo '<td class="table__tableCell">' . $calculation['conversion_result'] . ' (' . $calculation["currency_convert_to"] . ')</td>';
        echo "</tr>";
      }

      echo "</table>";
      echo '<a href="index.php" class="anchor"><div class="anchor__container">Powrót do Kalkulatora Walut</div></a>';
      
    }
  }
}