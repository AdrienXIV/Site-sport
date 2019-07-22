<?php
session_start(); //obligatoire
$_SESSION = array(); //remise à 0 des variables de session
session_destroy(); //destruction
header("Location: index.php"); //redirection
?>