<?php
    // Include the Database class & quotes data model
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Check if author_id is provided
    if (isset($_GET['category_id'])) {

        // Set category ID to read
        $quote->category_id = $_GET['category_id'];

        // Get quotes by category_id
        $result = $quote->read_by_category();
        $num = $result->rowCount();

        // We return all quotes with the given category ID
        if ($num > 0) {
            $quotes_arr = array();

            // Get all quotes with given author & category ID
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
    // Send missing parameters message
    else {
        echo json_encode(array('message' => 'Missing required parameter: category_id.'));
    }
?>