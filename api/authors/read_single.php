<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate author object
$author = new Author($db);

// Get ID from URL
$author->id = isset($_GET['id']) ? intval($_GET['id']) : die(json_encode(array('message' => 'Author ID not provided.')));

// Fetch author
$author->read_single();

if ($author->author) {
    $author_arr = array(
        'id' => $author->id,
        'author' => $author->author
    );

    echo json_encode($author_arr);
} else {
    echo json_encode(array('message' => 'author_id Not Found'));
}
?>