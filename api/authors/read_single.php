<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Create DB & connect
    $database = new Database();
    $db = $database->connect();

    // Create author object
    $author = new Author($db);

    // Get ID from URL
    //$author->id = isset($_GET['id']) ? intval($_GET['id']) : die(json_encode(array('message' => 'Author ID not provided.')));
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
?>