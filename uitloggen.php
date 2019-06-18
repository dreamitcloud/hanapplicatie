<?php
include("functies/session.php");
// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 
header("Location: /");
die();
?>