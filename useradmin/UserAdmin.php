<?php

session_start();
if (!isset($_SESSION["Authenticated"])) {
    header("Location: ../Login.php");
    exit();
} else {
    echo "Hello " . $_SESSION["UserName"] . ", you have been successfully authenticated.";
}
?>