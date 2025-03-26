<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and object files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$quote = new Quote($db);

// Query quotes
$result = $quote->read();
$num = $result->rowCount();

// Check if any quotes exist
//If id is specified, only read_single quote
if (isset($_GET['id'])){
    require_once 'read_single.php'; 
} 
else{

    //If categoryId and authorId both specified
    if (isset($_GET['category_id']) && isset($_GET['author_id'])){
    require_once 'read_categoryId_authorId.php'; 
    } 
    else{

        //If authorId is specified
        if (isset($_GET['author_id']) && !isset($_GET['category_id'])){
        require_once 'read_authorId.php'; 
        } 
        else{

            //If categoryId is specified
            if (isset($_GET['category_id']) && !isset($_GET['author_id'])){
            require_once 'read_categoryId.php'; 
            } 
            else{
                // If no specific ID is given, return all quotes
                $result = $quote->read();
                $num = $result->rowCount();

                if ($num > 0) {
                    $quotes_arr = [];

                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $quotes_arr[] = [
                            'id' => $row['id'],
                            'quote' => $row['quote'],
                            'author' => $row['author'],
                            'category' => $row['category']
                        ];
                    }

                    echo json_encode($quotes_arr);
                } else {
                    echo json_encode(['message' => 'No Quotes Found']);
                }
            }
        }
    }
}