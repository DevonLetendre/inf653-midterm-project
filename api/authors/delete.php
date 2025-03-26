<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

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
if (!empty($data->id)) {
    $author->id = intval($data->id);

    // Check if the author exists before deleting
    if (!$author->exists()) {
        echo json_encode(['message' => 'Author ID Not Found']);
    } elseif ($author->delete()) {
        echo json_encode([
            'id' => $author->id
        ]);
    } else {
        echo json_encode(['message' => 'Author Not Deleted']);
    }
} else {
    echo json_encode(['message' => 'Author ID is required.']);
}
?>