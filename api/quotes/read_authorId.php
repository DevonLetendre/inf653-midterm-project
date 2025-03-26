<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$quote = new Quote($db);

// Check if author_id is provided
if (isset($_GET['author_id'])) {
    $quote->author_id = $_GET['author_id'];

    // Get quotes by author_id
    $result = $quote->read_by_author();
    $num = $result->rowCount();

    if ($num > 0) {
        $quotes_arr = array();

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

        echo json_encode($quotes_arr);
    } else {
        echo json_encode(array('message' => 'No Quotes Found'));
    }
} else {
    echo json_encode(array('message' => 'Missing required parameter: author_id.'));
}
?>