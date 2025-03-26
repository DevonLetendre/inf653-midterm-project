<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$quote = new Quote($db);

// Get ID from URL
$quote->id = isset($_GET['id']) ? intval($_GET['id']) : die(json_encode(array('message' => 'Quote ID not provided.')));

// Fetch quote
$quote->read_single();

if ($quote->quote) {
    $quote_arr = array(
        'id' => $quote->id,
        'quote' => $quote->quote,
        'author' => $quote->author,
        'category' => $quote->category
    );

    echo json_encode($quote_arr);
} else {
    echo json_encode(array('message' => 'No Quotes Found'));
}
?>