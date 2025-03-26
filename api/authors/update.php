<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate author object
$author = new Author($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Ensure required data is present
if (isset($data->id) && isset($data->author) && !empty($data->id) && !empty($data->author)) {
    $author->id = intval($data->id);
    $author->author = htmlspecialchars(strip_tags($data->author));

    // Check if author exists before updating
    if ($author->exists()) {
        // Update author
        if ($author->update()) {
            echo json_encode(array(
                'id' => $author->id,
                'author' => $author->author
            ));
        } else {
            echo json_encode(array('message' => 'Failed to update author.'));
        }
    } else {
        echo json_encode(array('message' => 'Author ID Not Found.'));
    }
} else {
    echo json_encode(array('message' => 'Missing Required Parameters'));
}
?>