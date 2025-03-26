<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//Initialize API
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

//Instantiate quote object
$quote = new Quote($db);

//Fetch and return data
$result = $quote->read();
echo $result;
