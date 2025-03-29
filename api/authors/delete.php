<?php
    // Include the Database class & Author data model
    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure we have the required parameters
    if (!empty($data->id)) {

        // Set the ID to update
        $author->id = intval($data->id);

        // Check if author exists
        if (!$author->exists()) {
            echo json_encode(['message' => 'Author ID Not Found']);
        // Delete author
        } elseif ($author->delete()) {
            echo json_encode(['id' => $author->id]);
        // There was some other issue
        } else {
            echo json_encode(['message' => 'Author Not Deleted']);
        }
    } 
    // Send missing parameters message
    else {
        echo json_encode(['message' => 'Author ID is required.']);
    }
?>