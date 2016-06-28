<?php
require "db_connect.php";

$success = false;
$results = array();
$errors = array();
$queriedObject = "";

$tableId = $_POST['tableId'];

if (empty($tableId)) {
    $success = false;
    $errors[] = array(
        'errorId'           =>  1,
        'errorMessage'      =>  "No Table ID",
        'displayMessage'    =>  "");
} else {
    if ($tableId === "medications_table") {
        $queriedObject = "Medications";
        $success = true;
    } else if ($tableId === "manufacturers_table") {
        $queriedObject = "Manufacturers";
        $success = true;
    } else if ($tableId === "sales_table") {
        $queriedObject = "Sales";
        $success = true;
    } else {
        $success = false;
        $errors[] = array(
        'errorId'           =>  2,
        'errorMessage'      =>  "Incorrect Table ID",
        'displayMessage'    =>  "");
    }
}

if ($success) {
    try {
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($tableId === "manufacturers_table") {
            $sql = 'SELECT manufacturer_name, manufacturer_address, manufacturer_email FROM Manufacturers ORDER BY manufacturer_name ASC';
        } else if ($tableId === "medications_table") {
            $sql = 'SELECT medication_name, GenericEquivalents.generic_equivalent_name as medication_generic_equivalent, price as medication_price, stock_available as medication_stock_available, Manufacturers.manufacturer_name as medication_manufacturer_name FROM Medications, GenericEquivalents, Manufacturers WHERE Medications.generic_equivalent_id = GenericEquivalents.generic_equivalent_id AND Medications.manufacturer_id = Manufacturers.manufacturer_id ORDER BY medication_name ASC';
        } else if ($tableId === "sales_table") {
            $sql = 'SELECT Medications.medication_name, Sales.sale_amount, Sales.sale_timestamp FROM Sales, Medications WHERE Sales.medication_id = Medications.medication_id ORDER BY Sales.sale_timestamp DESC';
        }

        $statement = $connection->prepare($sql);
        $statement->execute();
        $rowCount = $statement->rowCount();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $success = ($rowCount > 0);
        if (!$success) {
            $errors[] = array(
                'errorId'           =>  3,
                'errorMessage'      =>  "Empty Table",
                'displayMessage'    =>  "No ".$queriedObject." Found!");
        }
    } catch (PDOException $e) {
        $success = false;
        $errors[] = array(
            'errorId'           =>  4,
            'errorMessage'      =>  $e->getMessage(),
            'displayMessage'    =>  "No ".$queriedObject." Found!");
    }
}

$dataOut = array(
    'success'   =>  $success,
    'results'   =>  $results,
    'errors'    =>  $errors);

echo json_encode($dataOut);