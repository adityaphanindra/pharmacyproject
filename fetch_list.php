<?php
require "db_connect.php";

$success = false;
$results = array();
$errors = array();
$queriedObject = "";

$listId = $_POST['listId'];

if (empty($listId)) {
    $success = false;
    $errors[] = array(
        'errorId'           =>  1,
        'errorMessage'      =>  "No List ID",
        'displayMessage'    =>  "");
} else {
    if ($listId === "manufacturer_names") {
        $queriedObject = "Manufacturers";
        $success = true;
    } else if ($listId === "medication_names") {
        $queriedObject = "Medications";
        $success = true;
    } else {
        $success = false;
        $errors[] = array(
        'errorId'           =>  2,
        'errorMessage'      =>  "Incorrect List ID",
        'displayMessage'    =>  "");
    }
}

try {
    if ($queriedObject === "Manufacturers") {
        $sql = 'SELECT manufacturer_name FROM Manufacturers ORDER BY manufacturer_name ASC ';
    } else if ($queriedObject === "Medications") {
        $sql = 'SELECT medication_name FROM Medications ORDER BY medication_name ASC ';
    }
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $statement = $connection->prepare($sql);
    $statement->execute();
    $rowCount = $statement->rowCount();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    $success = ($rowCount > 0);
    if (!$success) {
        $errors[] = array(
            'errorId'           =>  3,
            'errorMessage'      =>  "Empty List",
            'displayMessage'    =>  "No ".$queriedObject." Found!");
    }
} catch (PDOException $e) {
    $success = false;
    $errors[] = array(
        'errorId'           =>  4,
        'errorMessage'      =>  $e->getMessage(),
        'displayMessage'    =>  "No ".$queriedObject." Found!");
}

$dataOut = array(
    'success'   =>  $success,
    'results'   =>  $results,
    'errors'    =>  $errors);

echo json_encode($dataOut);