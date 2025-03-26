<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Create DB & connect
    $database = new Database();
    $db = $database->connect();

    // Create quote object
    $quote = new Quote($db);

    // Check if specific parameters are provided and include the corresponding file
    if (isset($_GET['id'])) {
        require_once 'read_single.php';
    } 
    else if (isset($_GET['category_id']) && isset($_GET['author_id'])) {
        require_once 'read_categoryId_authorId.php';
    } 
    else if (isset($_GET['author_id'])) { 
        require_once 'read_authorId.php';
    } 
    else if (isset($_GET['category_id'])) { 
        require_once 'read_categoryId.php';
    } 
    else {
        // If no specific ID is given return all quotes
        $result = $quote->read();
        $num = $result->rowCount();

        if ($num > 0) {
            $quotes_arr = [];

            // Set properties if found & return
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $quotes_arr[] = [
                    'id' => $row['id'],
                    'quote' => $row['quote'],
                    'author' => $row['author'],
                    'category' => $row['category']
                ];
            }
            // return quotes
            echo json_encode($quotes_arr);
        } 
        // Output no quotes message
        else {
            echo json_encode(['message' => 'No Quotes Found']);
        }
}
