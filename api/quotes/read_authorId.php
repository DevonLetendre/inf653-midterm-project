<?php
    // Include the Database class & quotes data model
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Ensure author_id is given
    if (isset($_GET['author_id'])) {

        // Set author id to read
        $quote->author_id = $_GET['author_id'];

        // Get quotes by author_id
        $result = $quote->read_by_author();

        // Get row count
        $num = $result->rowCount();

        // We return all quotes by author with given id
        if ($num > 0) {
            $quotes_arr = array();

            // Get all quotes
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $quote_item = array(
                    'id' => $id,
                    'quote' => $quote,
                    'author' => $author_name,
                    'category' => $category_name
                );
                array_push($quotes_arr, $quote_item);
            }
            // Return the quotes
            echo json_encode($quotes_arr);
        } 
        // No quotes found, return message
        else {
            echo json_encode(array('message' => 'No Quotes Found'));
        }
    } 
    // Send missing paramters message
    else {
        echo json_encode(array('message' => 'Missing required parameter: author_id.'));
    }
?>