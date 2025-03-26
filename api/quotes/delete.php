<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quote = new Quote($db);
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $quote->id = intval($data->id);

    // Check if the quote exists before deleting
    if (!$quote->exists()) {
        echo json_encode(['message' => 'No Quotes Found']);
    } elseif ($quote->delete()) {
        echo json_encode([
            'id' => $quote->id
        ]);
    } else {
        echo json_encode(['message' => 'Quote Not Deleted']);
    }
} else {
    echo json_encode(['message' => 'Invalid ID']);
}
?>