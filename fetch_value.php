<?php
require "db_connect.php";

$success = false;
$results = array();
$errors = array();
$queriedObject = "";

$queriedObject = $_POST['type'];
$key = $_POST['key'];

if (empty($queriedObject)) {
    $success = false;
    $errors[] = array(
        'errorId'           =>  1,
        'errorMessage'      =>  "No Value Type Given",
        'displayMessage'    =>  "");
} else {
    if ($queriedObject === "stock_available") {
        $success = true;
    } else {
        $success = false;
        $errors[] = array(
        'errorId'           =>  2,
        'errorMessage'      =>  "Incorrect Value Type",
        'displayMessage'    =>  "");
    }
}

try {
    $success = false;
    if ($queriedObject === "stock_available") {
        $sql = 'SELECT stock_available FROM Medications WHERE medication_name = (:medication_name)';    
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $connection->prepare($sql);
        $statement->execute(array(':medication_name' => $key));
        $rowCount = $statement->rowCount();
        $results = $statement->fetchObject()->stock_available;
        $success = ($rowCount > 0);
    }
    if (!$success) {
        $errors[] = array(
            'errorId'           =>  3,
            'errorMessage'      =>  "Empty List",
            'displayMessage'    =>  "No ".$queriedObject." for ".$key." Found!");
    }
} catch (PDOException $e) {
    $success = false;
    $errors[] = array(
        'errorId'           =>  4,
        'errorMessage'      =>  $e->getMessage(),
        'displayMessage'    =>  "No ".$queriedObject." for ".$key." Found!");
}

$dataOut = array(
    'success'   =>  $success,
    'results'   =>  $results,
    'errors'    =>  $errors);

echo json_encode($dataOut);