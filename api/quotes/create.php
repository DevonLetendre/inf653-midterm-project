<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Create DB & connect
    $database = new Database();
    $db = $database->connect();

    // Create quote object
    $quote = new Quote($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure we have the required parameters
    if (empty($data->quote) || empty($data->author_id) || empty($data->category_id)) {
        echo json_encode(['message' => 'Missing Required Parameters']);
        exit();
    }

    // Assign our values
    $quote->quote = $data->quote;
    $quote->author_id = intval($data->author_id);
    $quote->category_id = intval($data->category_id);

    // Ensure author_id exists
    include_once '../../models/Author.php';
    $author = new Author($db);
    $author->id = $quote->author_id;

    if (!$author->exists()) {
        echo json_encode(['message' => 'author_id Not Found']);
        exit();
    }

    // Ensure category_id exists
    include_once '../../models/Category.php';
    $category = new Category($db);
    $category->id = $quote->category_id;

    if (!$category->exists()) {
        echo json_encode(['message' => 'category_id Not Found']);
        exit();
    }

    // Create quote
    $newQuoteId = $quote->create3();
    if ($newQuoteId) {
        echo json_encode([
            'id' => $newQuoteId,
            'quote' => $quote->quote,
            'author_id' => $quote->author_id,
            'category_id' => $quote->category_id
        ]);
    } else {
        echo json_encode(['message' => 'Quote Not Created']);
    }
?>