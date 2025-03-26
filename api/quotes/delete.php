<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Create DB & connect
    $database = new Database();
    $db = $database->connect();

    // Create quote
    $quote = new Quote($db);

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
?>