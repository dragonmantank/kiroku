<?php

session_start();
session_destroy();

require_once 'includes/bsb.header.php';

$programObject = new programObject;
header("Location: index.php");

?>
