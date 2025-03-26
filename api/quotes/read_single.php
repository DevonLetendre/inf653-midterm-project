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

//Get the ID from the URL query string
if (isset($_GET['id'])) {
    //Set the ID
    $quote->id = $_GET['id'];

    //Get the single quote
    $quote->read_single();

    //Check if quote exists
    if ($quote->id !== null) {
        //Quote array
        $quote_arr = array(
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author' => $quote->author_id,  //Could potentially retrieve author name, if needed
            'category' => $quote->category_id  //Same with category
        );

        //Return the single quote in JSON format
        echo json_encode($quote_arr);
    } else {
        //No quote found, return message
        echo json_encode(array('message' => 'No Quotes Found'));
    }
} else {
    //No ID provided, return an error message
    echo json_encode(array('message' => 'No ID provided.'));
}
?>