<?php
// Connection parameters
$DatabaseServer = "localhost";
$DatabaseUser   = "root";
$DatabasePass   = "";
$DatabaseName   = "invoicemanager";

// Connecting to the database
$database = new mysqli($DatabaseServer, $DatabaseUser, $DatabasePass, $DatabaseName);

?>