<?php
require "db_connect.php";

$success = false;
$message = "";
$errors = array();
$addedObject = "";

$formId = $_POST['formId'];
parse_str($_POST['formData'], $formData);

// Check if formId and formData are received
if (empty($formId)) {
    $success = false;
    $errors[] = array(
        'errorId'           =>  1,
        'errorMessage'      =>  "No Form ID",
        'displayMessage'    =>  "");
} else {
    if ($formId === "add_medication_form") {
        $addedObject = "Medication";
        $success = true;
    } else if ($formId === "add_manufacturer_form") {
        $addedObject = "Manufacturer";
        $success = true;
    } else if ($formId === "add_sale_form") {
        $addedObject = "Sale";
        $success = true;
    } else {
        $success = false;
        $errors[] = array(
        'errorId'           =>  2,
        'errorMessage'      =>  "Incorrect Form ID",
        'displayMessage'    =>  "");
    }
}

if ($success) {
    // Check if formData is received
    if (empty($formData)) {
        $success = false;
        $errors[] = array(
            'errorId'           =>  1,
            'errorMessage'      =>  "No Form Data",
            'displayMessage'    =>  "");
    }    
}

if ($success) {    
    // Get form data
    error_log(" Form Data: ".print_r($formData, true));
    if ($addedObject === "Medication") {
        $medication_name = $formData['medication_name'];
        $medication_generic_equivalent = $formData['medication_generic_equivalent'];
        $medication_price = $formData['medication_price'];
        $medication_stock_available = $formData['medication_stock_available'];
        $medication_manufacturer_name = $formData['medication_manufacturer_name'];
    } else if ($addedObject === "Manufacturer") {
        $manufacturer_name = $formData['manufacturer_name'];
        $manufacturer_address = $formData['manufacturer_address'];
        $manufacturer_email = $formData['manufacturer_email'];
    } else if ($addedObject === "Sale") {
        $sale_medication_name = $formData['sale_medication_name'];
        $sale_amount = $formData['sale_amount'];
    }
}

if ($success) {
    // Validation
    if ($addedObject === "Medication") {
        if (!isset($medication_name)) {
            $success = false;
            $errors[] = array(
            'errorId'           =>  3,
            'errorMessage'      =>  "No Medication Name",
            'displayMessage'    =>  "Medication Name is required.");
        }
        if (!isset($medication_generic_equivalent)) {
            $success = false;
            $errors[] = array(
            'errorId'           =>  3,
            'errorMessage'      =>  "No Generic Equivalent",
            'displayMessage'    =>  "Generic Equivalent is required.");
        }
        if (!isset($medication_price)) {
            $success = false;
            $errors[] = array(
            'errorId'           =>  3,
            'errorMessage'      =>  "No Medication Price",
            'displayMessage'    =>  "Medication Price is required.");
        } else {
            // Round currency
            if ($medication_price < 0) {
                $medication_price = 0;
            }
            $medication_price = round($medication_price, 2);
        }
        if (!isset($medication_stock_available)) {
            $success = false;
            $errors[] = array(
            'errorId'           =>  3,
            'errorMessage'      =>  "No Medication Stock Avaialble",
            'displayMessage'    =>  "Medication Stock Available is required.");
        } else {
            if ($medication_stock_available < 0) {
                $medication_stock_available = 0;
            }
            // Round to nearest integer
            $medication_stock_available = round($medication_stock_available);
        }
        if (!isset($medication_manufacturer_name)) {
            $success = false;
            $errors[] = array(
            'errorId'           =>  3,
            'errorMessage'      =>  "No Medication Manufacturer Name",
            'displayMessage'    =>  "Medication Manufacturer Name is required.");
        }
    } else if ($addedObject === "Manufacturer") {
        if (!isset($manufacturer_name)) {
            $success = false;
            $errors[] = array(
            'errorId'           =>  3,
            'errorMessage'      =>  "No Manufacturer Name",
            'displayMessage'    =>  "Manufacturer Name is required.");
        }
        if (!isset($manufacturer_address)) {
            $success = false;
            $errors[] = array(
            'errorId'           =>  3,
            'errorMessage'      =>  "No Manufacturer Address",
            'displayMessage'    =>  "Manufacturer Address is required.");
        }
    } else if ($addedObject === "Sale") {
        if (!isset($sale_medication_name)) {
            $success = false;
            $errors[] = array(
            'errorId'           =>  3,
            'errorMessage'      =>  "No Medication Name",
            'displayMessage'    =>  "Please choose a medication name.");
        }
        if (!isset($sale_amount)) {
            $success = false;
            $errors[] = array(
            'errorId'           =>  3,
            'errorMessage'      =>  "No Sale Amount",
            'displayMessage'    =>  "Sale Amount is required.");
        }
    }  
}

if ($success) {
    try {
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $success = false;
        $errorMessage = "";

        if ($addedObject === "Medication") {
            // Get generic_equivalent_id from medication_generic_equivalent
            $sql = 'SELECT generic_equivalent_id FROM GenericEquivalents WHERE generic_equivalent_name = (:generic_equivalent_name)';
            $statement = $connection->prepare($sql);
            $statement->execute(array(':generic_equivalent_name' => $medication_generic_equivalent));
            $rowCount = $statement->rowCount();
            $success = ($rowCount > 0);
            // If medication_generic_equivalent doesn't exist insert it.
            if ($success) {
                $generic_equivalent_id = $statement->fetchObject()->generic_equivalent_id;
            } else {
                $sql = 'INSERT INTO GenericEquivalents (generic_equivalent_name) VALUES (:generic_equivalent_name)';
                $query = $connection->prepare($sql);
                $success = $query->execute(array(':generic_equivalent_name' => $medication_generic_equivalent));
                if ($success) {
                    // If success, get the last inserted generic equivalent id
                    $generic_equivalent_id = $connection->lastInsertId('generic_equivalent_id');
                } else {
                    $errorMessage = "Cannot Submit Generic Equivalent: " . $medication_generic_equivalent;
                }
            }
            // Got generic_equivalent_id, now get manufacturer_id using manufacturer_name
            if ($success) {
                $sql = 'SELECT manufacturer_id FROM Manufacturers WHERE manufacturer_name = (:manufacturer_name)';
                $statement = $connection->prepare($sql);
                $statement->execute(array(':manufacturer_name' => $medication_manufacturer_name));
                $rowCount = $statement->rowCount();
                $success = ($rowCount > 0);
                if ($success) {
                    $medication_manufacturer_id = $statement->fetchObject()->manufacturer_id;
                } else {
                    $errorMessage = "Cannot Find Manufacturer with name: " . $medication_manufacturer_name;
                }
            }
            // All good, insert into medications table
            if ($success) {
                $sql = 'INSERT INTO Medications (medication_name, generic_equivalent_id, price, stock_available, manufacturer_id) VALUES (:medication_name, :generic_equivalent_id, :medication_price, :medication_stock_available, :manufacturer_id)';
                $query = $connection->prepare($sql);
                $success = $query->execute(array(':medication_name' => $medication_name, ':generic_equivalent_id' => $generic_equivalent_id, ':medication_price' => $medication_price, ':medication_stock_available' => $medication_stock_available, ':manufacturer_id' => $medication_manufacturer_id));
                if (!$success) {
                    $errorMessage = "Insertion Failed.";
                }
            }
        } else if ($addedObject === "Manufacturer") {
            $sql = 'INSERT INTO Manufacturers (manufacturer_name, manufacturer_address, manufacturer_email) VALUES (:manufacturer_name, :manufacturer_address, :manufacturer_email)';
            
            $query = $connection->prepare($sql);
            $success = $query->execute(array(':manufacturer_name' => $manufacturer_name, ':manufacturer_address' => $manufacturer_address, ':manufacturer_email' => $manufacturer_email));
            
            if (!$success) {
                $errorMessage = "Insertion Failed.";
            }          
        } else if ($addedObject === "Sale") {
            // Get medication_id from sale_medication_name
            $sql = 'SELECT medication_id, stock_available FROM Medications WHERE medication_name = (:medication_name)';
            $statement = $connection->prepare($sql);
            $statement->execute(array(':medication_name' => $sale_medication_name));
            $rowCount = $statement->rowCount();
            $success = ($rowCount > 0);
            if ($success) {
                $fetchedResult = $statement->fetchObject();
                $medication_id = $fetchedResult->medication_id;
                $stock_available = $fetchedResult->stock_available;
                // Check if sale amount is a legal amount.
                $success = $sale_amount > 0 && $sale_amount <= $stock_available;

                if (!$success) {
                    $errorMessage = "Cannot sell an amount of: " . $sale_amount . ". Stock available: " . $stock_available;
                }
            } else {
                $errorMessage = "Cannot Find Medication: " . $sale_medication_name;
            }
            // All good, insert into sales table
            if ($success) {
                $sql = 'INSERT INTO Sales (medication_id, sale_amount) VALUES (:medication_id, :sale_amount)';
                $query = $connection->prepare($sql);
                $success = $query->execute(array(':medication_id' => $medication_id, ':sale_amount' => $sale_amount));
                if (!$success) {
                    $errorMessage = "Insertion Failed.";
                }
            }
            // All good, reduce the stock available
            if ($success) {
                $new_stock_available = $stock_available - $sale_amount;
                $sql = 'UPDATE Medications SET stock_available = (:new_stock_available) WHERE medication_id = (:medication_id)';
                $query = $connection->prepare($sql);
                $success = $query->execute(array(':new_stock_available' => $new_stock_available, ':medication_id' => $medication_id));
                if (!$success) {
                    $errorMessage = "Updated of Medications Table Failed.";
                }
            }
        }

        if ($success) {
            $message = $addedObject . " successfully added.";
        } else {
            $errors[] = array(
            'errorId'           =>  4,
            'errorMessage'      =>  $errorMessage,
            'displayMessage'    =>  "Form Submission Failed.");
        }   
    } catch (PDOException $e) {
        $success = false;
        $errors[] = array(
        'errorId'           =>  5,
        'errorMessage'      =>  "Database Error: " . $e->getMessage(),
        'displayMessage'    =>  "Form Submission Failed.");
    }
}
$dataOut = array(
    'success'   =>  $success,
    'message'   =>  $message,
    'errors'    =>  $errors
);
echo json_encode($dataOut);