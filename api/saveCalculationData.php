<?php

require_once "./helpers/connect.php";

class SaveCalculation
{
  public function saveToDatabase($input1, $select1Currency, $select2Currency, $result, $codeSelect2, $codeSelect1)
  {
    global $conn;

    try {

      $conversion_result = $result . ' ' . $codeSelect2;

      //sprawdzenie czy już taka kalkulacja istnieje
      $checkStmt = $conn->prepare("SELECT COUNT(*) AS count FROM calculations WHERE conversion_result = ? AND amount_entered = ?");
      $checkStmt->bindParam(1, $conversion_result);
      $checkStmt->bindParam(2, $input1);
      $checkStmt->execute();
      $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
      $count = $row['count'];

      if ($count > 0) {
        echo '<div class="error">Taka kalkulacja już istnieje w bazie danych</div>';
      } else {

      // Dodanie nowej kalkulacji do bazy danych

      $stmt = $conn->prepare("INSERT INTO calculations (saved_date, amount_entered, code_currency_from, currency_from, currency_convert_to, conversion_result) 
      VALUES (NOW(), ?, ?, ?, ?, ?)");

      $stmt->bindParam(1, $input1);
      $stmt->bindParam(2, $codeSelect1);
      $stmt->bindParam(3, $select1Currency);
      $stmt->bindParam(4, $select2Currency);
      $stmt->bindParam(5, $conversion_result);
      $stmt->execute();

        echo '<div class="success">Nowa kalkulacja została zapisana 😀</div>';

      }
    } catch (Exception $e) {
      echo "Wystąpił błąd podczas zapisu kalkulacji do bazy danych: " . $e->getMessage();
    }
  }
}
