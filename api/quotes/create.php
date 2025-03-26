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

//Get the input data
$data = json_decode(file_get_contents("php://input"));

//Check if the required parameters are provided
if (isset($data->quote) && isset($data->author_id) && isset($data->category_id)) {
    //Set the Quote properties
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    //Attempt to create the quote and get the response
    $response = $quote->create();

    //Return the response (either success or error message)
    echo json_encode($response);
} else {
    //If any required parameters are missing, return an error message
    echo json_encode(array('message' => 'Missing Required Parameters'));
}
?>