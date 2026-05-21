<?php

if (!isset($_SESSION["loggedin"]) ) {
    header("location: auth-login.php");
    exit;
}?>