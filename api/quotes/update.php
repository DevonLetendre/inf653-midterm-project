<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';
    include_once '../../models/Author.php';
    include_once '../../models/Category.php';

    // Create DB & connect
    $database = new Database();
    $db = $database->connect();

    // Create quote, author, and category objects
    $quote = new Quote($db);
    $author = new Author($db);
    $category = new Category($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure required data is present
    if (!isset($data->id, $data->quote, $data->author_id, $data->category_id) || 
        empty($data->id) || empty($data->quote) || empty($data->author_id) || empty($data->category_id)) {
       // Error message if missing any parameters
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit();
    }

    // Assign data to the quote object
    $quote->id = intval($data->id);
    $quote->quote = $data->quote;
    $quote->author_id = intval($data->author_id);
    $quote->category_id = intval($data->category_id);

    // Ensure quote exists
    if (!$quote->exists()) {
        echo json_encode(["message" => "No Quotes Found"]);
        exit();
    }

    // Ensure author_id exists
    if (!$author->exists($quote->author_id)) {
        echo json_encode(["message" => "author_id Not Found"]);
        exit();
    }

    // Ensure category_id exists
    if (!$category->exists($quote->category_id)) {
        echo json_encode(["message" => "category_id Not Found"]);
        exit();
    }

    // Update the quote
    if ($quote->update()) {
        echo json_encode([
            "id" => $quote->id,
            "quote" => $quote->quote,
            "author_id" => $quote->author_id,
            "category_id" => $quote->category_id
        ]);
    }
    // If unable to update, send message 
    else {
        echo json_encode(["message" => "Failed to update quote."]);
    }
?>
