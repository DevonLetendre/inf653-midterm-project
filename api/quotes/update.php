<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//Include the database and Quote class
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

//Instantiate the Quote object
$quote = new Quote($db);

//Get input data (JSON body)
$data = json_decode(file_get_contents("php://input"));

//Ensure the necessary parameters are provided
if (!isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
    echo json_encode(array("message" => "Missing Required Parameters"));
    return;
}

//Set quote properties from the input data
$quote->id = $data->id;
$quote->quote = $data->quote;
$quote->author_id = $data->author_id;
$quote->category_id = $data->category_id;

//Attempt to update the quote
$response = $quote->update();

//Output the response
echo json_encode($response);
?>
