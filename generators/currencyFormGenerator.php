<?php

require_once "./helpers/functiondatepl.php";
require_once "./helpers/connect.php";
require_once "./api/saveCalculationData.php";

class CurrencyFormGenerator
{
  private $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  public function generateForm()
  {
    if (empty($this->data)) {
      echo "Brak danych do wyświetlenia.";
    } else {
      $rates = $this->data[0]["rates"];
      $allOK = true;

      $input1 = isset($_SESSION['in_form_input1']) ? $_SESSION['in_form_input1'] : '';
      $select1 = isset($_SESSION['in_form_select1']) ? $_SESSION['in_form_select1'] : '';
      $select2 = isset($_SESSION['in_form_select2']) ? $_SESSION['in_form_select2'] : '';

      if (isset($_POST['input1'])) {

        //Sprawdzenie input1 
        $input1 = $_POST['input1'];
        if ($input1 <= 0) {
          $allOK = false;
          $_SESSION['e_input1'] = "Proszę wprowadzić kwotę większą od zera!";
        }

        //Sprawdzenie select1
        $select1 = $_POST['select1'];
        if ($select1 == "wybierz walutę") {
          $allOK = false;
          $_SESSION['e_select1'] = "Proszę wybrać walutę";
        }

        //Sprawdzenie select2
        $select2 = $_POST['select2'];
        if ($select2 == "wybierz walutę") {
          $allOK = false;
          $_SESSION['e_select2'] = "Proszę wybrać walutę";
        }
      }

      //Zapamietaj wprowadzone dane
      $_SESSION['in_form_input1'] = $input1;
      $_SESSION['in_form_select1'] = $select1;
      $_SESSION['in_form_select2'] = $select2;

      echo <<<EOD
        <h1 class="header">Kalkulator Walut</h1>
        <form class="form" method="post">
        <fieldset class="form__fieldset">
        <legend class="form__legend">Wprowadź kwotę do przeliczenia</legend>
      EOD;
          if (isset($_SESSION['e_input1'])) {
        echo '<div class="error">' . $_SESSION['e_input1'] . '</div>';
        unset($_SESSION['e_input1']);
      }
      echo <<<EOD
        <label>
          <span class="form__labelText">Mam*: </span>
          <input class="form__field" type="number" id="input1" min="1" name="input1" value="$input1" placeholder="wpisz kwotę" step="any"/>
        </label>
      EOD;

      if (isset($_SESSION['e_select1'])) {
        echo '<div class="error">' . $_SESSION['e_select1'] . '</div>';
        unset($_SESSION['e_select1']);
      }

      echo <<<EOD
        <label>
          <span class="form__labelText">Przelicz z*: </span>
        <select class="form__field" id="select1" name="select1">
        <option selected>wybierz walutę</option>
      EOD;

      //generowanie option w select1
      foreach ($rates as $rate) {
        echo '<option value="' . $rate['mid'] . '"';
        if ($rate['mid'] == $select1) {
          echo ' selected="selected"';
        }
        echo '>' . $rate['currency'] . '</option>';
      }

      echo '</select>';

        

      echo '</label>';

      if (isset($_SESSION['e_select2'])) {
        echo '<div class="error">' . $_SESSION['e_select2'] . '</div>';
        unset($_SESSION['e_select2']);
      }

      echo <<<EOD
        <label>
          <span class="form__labelText">Przelicz na*: </span>
          <select class="form__field" id="select2" name="select2">
          <option selected>wybierz walutę</option>
      EOD;

      //generowanie option w select2
      foreach ($rates as $rate) {
        echo '<option value="' . $rate['mid'] . '"';
        if ($rate['mid'] == $select2) {
          echo ' selected="selected"';
        }
        echo '>' . $rate['currency'] . '</option>';
      }

      echo '</select>';
      echo '</label>';

      

      echo <<<EOD
        </fieldset>
        <button class="form__button" type="submit">Przelicz</button>
        <button class="form__button js-clear_button" type="button">Wyczyść</button>
        <button class="form__button js-save_calculation" type="submit" name="save_calculation" onclick="removeSaveCalculationButton()">Zapisz kalkulację</button>
        </form>
      EOD;

      if (
        $allOK
        && $_POST["input1"] != 0
        && $_POST["select1"] != 0
        && $_POST["select2"] != 0
      ) {
        $input1 = $_POST["input1"];
        $select1 = $_POST["select1"];
        $select2 = $_POST["select2"];

        if ($select1) {
          foreach ($rates as $rate) {
            if ($rate["mid"] == $select1) {
              $select1Currency = $rate["currency"];
            }
          }
        }

        if ($select2) {
          foreach ($rates as $rate) {
            if ($rate["mid"] == $select2) {
              $select2Currency = $rate["currency"];
            }
          }
        }

        $result = $this->calculateConversion($input1, $select1, $select2);
        $codeSelect1 = $this->getCodeSelect1($select1, $rates);
        $codeSelect2 = $this->getCodeSelect2($select2, $rates);

        //zapis kalkulacji do bazy danych
        if (isset($_POST['save_calculation'])) {

          $saveCalculation = new SaveCalculation();
          $saveCalculation->saveToDatabase($input1, $select1Currency, $select2Currency, $result, $codeSelect2, $codeSelect1);
          
        }

        echo '<div class="form__result js-result">';
        echo '<p class="result">' .$input1 .' ' .$codeSelect1 . ' = <b>' . $result . ' ' . $codeSelect2 . '</b></p>';
        echo '</div>';

      } else if (isset($_POST['input1'])) {
        echo '<div class="error">Wypełnij wszystkie pola!</div>';
      }

      echo '<a href="saved-calculations.php" class="anchor"><div class="anchor__container">Zapisane kalkulacje</div></a>';

      echo '<a href="actual-table.php" class="anchor"><div class="anchor__container">Aktualna Tabela kursów walut NBP</div></a>';

    }
  }

  //generowanie wyniku kalkulacji
  public function calculateConversion($input1, $select1, $select2)
  {
    if ($select1 != 0 && $select2 != 0 && $input1 != 0) {

      $conversionRate = $select1 / $select2;
      $result = $input1 * $conversionRate;
      return number_format($result, 2);
    } else {
      throw new Exception('Wszystkie pola formularza muszą być wypełnione');
    }
  }

  //ustawianie odpowiedniego kodu waluty do wyboru w polach select
  public function getCodeSelect1($select1, $rates)
  {
    foreach ($rates as $rate) {
      if ($rate['mid'] == $select1) {
        return $rate['code'];
      }
    }
    return '';
  }

  public function getCodeSelect2($select2, $rates)
  {
    foreach ($rates as $rate) {
      if ($rate['mid'] == $select2) {
        return $rate['code'];
      }
    }
    return '';
  }
}

