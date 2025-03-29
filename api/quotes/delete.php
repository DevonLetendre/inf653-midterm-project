<?php
    // Include the Database class & quotes data model
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure we have the required parameters
    if (!empty($data->id)) {

        // Set the ID to update
        $quote->id = intval($data->id);

        // Check if quote exists
        if (!$quote->exists()) {
            echo json_encode(['message' => 'No Quotes Found']);
        // Delete quote
        } elseif ($quote->delete()) {
            echo json_encode([
                'id' => $quote->id
            ]);
        // There was some other issue
        } else {
            echo json_encode(['message' => 'Quote Not Deleted']);
        }
    } 
    // Send missing parameters message 
    else {
        echo json_encode(['message' => 'Invalid ID']);
    }
// Production Commit
