<?php 
include_once "./sassion.php";
   session_unset();
   session_destroy();
   header("Location: ../index.php");
?>