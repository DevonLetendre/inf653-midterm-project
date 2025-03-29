<?php
    // Include the Database class & quotes data model
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure we have the required parameters
    if (empty($data->quote) || empty($data->author_id) || empty($data->category_id)) {
        echo json_encode(['message' => 'Missing Required Parameters']);
        exit();
    }

    // Assign values
    $quote->quote = htmlspecialchars(strip_tags($data->quote));
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
    $newQuoteId = $quote->create();
    if ($newQuoteId) {
        echo json_encode([
            'id' => $newQuoteId,
            'quote' => $quote->quote,
            'author_id' => $quote->author_id,
            'category_id' => $quote->category_id
        ]);
    } 
    // Some other issue occured - send a message
    else {
        echo json_encode(['message' => 'Quote Not Created']);
    }
// Production Commit
