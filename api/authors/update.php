<?php
    // Include the Database class & Author data model
    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure both ID and author are provided
    if (isset($data->id) && isset($data->author) && !empty($data->id) && !empty($data->author)) {
       
        // Set ID and author to update
        $author->id = intval($data->id);
        $author->author = $data->author;

        // Check if author exists
        if ($author->exists()) {
            // Update author
            if ($author->update()) {
                // Return the updated author in the response
                echo json_encode(array(
                    'id' => $author->id,
                    'author' => $author->author
                ));
            } 
            // Return failed to update
            else {
                echo json_encode(array('message' => 'Failed to update author.'));
            }
        } 
        // Return author ID not found 
        else {
            echo json_encode(array('message' => 'Author ID Not Found.'));
        }
    } 
    // Return missing required parameters message
    else {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }
// Production Commit
