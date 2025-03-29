<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure we have the required parameters
    if (!empty($data->author)) {
        $author->author = $data->author;

        // Create author
        if ($author->create()) {
            echo json_encode([
                'id' => $author->id,
                'author' => $author->author
            ]);
        } else {
            echo json_encode(['message' => 'Author not created.']);
        }
    } 
    // Send missing parameters message
    else {
        echo json_encode(['message' => 'Missing Required Parameters']);
    }
?>