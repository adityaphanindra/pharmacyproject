<?php
require "functions.php";
require "header.php";
require "db_connect.php";

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case "home":
            require "home.php";
            break;
        case "medications":
            require "medications.php";
            break;
        case "sales":
            require "sales.php";
            break;
        case "manufacturers":
            require "manufacturers.php";
            break;
        case "login":
            require "login.php";
            break;
        default:
            require "404.php";
    }
} else {
    require "home.php";
}
?>

<?php
require "footer.php";
require "db_disconnect.php";
?>