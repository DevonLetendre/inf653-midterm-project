<?php
    // Include the Database class & Author data model
    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Get ID
    $author->id = isset($_GET['id']) ? intval($_GET['id']) : die();

    // Get author
    $author->read_single();

    // Set properties if found & return
    if ($author->author) {
        $author_arr = array(
            'id' => $author->id,
            'author' => $author->author
        );

        echo json_encode($author_arr);
    } 
    // Send authors not found message
    else {
        echo json_encode(['message' => 'author_id Not Found']);
    }
// Production Commit
