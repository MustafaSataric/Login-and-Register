<?php
    session_start();
    if(!isset($_COOKIE["email"])) {
        header("Location: ../assets/login.php");
        exit();
    }

?>