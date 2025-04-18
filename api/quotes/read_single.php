<?php
    // Include the Database class & quotes data model
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Get ID
    $quote->id = isset($_GET['id']) ? intval($_GET['id']) : die();

    // Get quote
    $quote->read_single();

    // Set properties if found & return
    if ($quote->quote) {
        $quote_arr = array(
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author' => $quote->author,
            'category' => $quote->category
        );

        echo json_encode($quote_arr);
    } 
    // Send quotes not found message
    else {
        echo json_encode(array('message' => 'No Quotes Found'));
    }
?>