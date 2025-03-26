<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate author object
$author = new Author($db);

// Author query
$result = $author->read();

// Get row count
$num = $result->rowCount();

if (isset($_GET['id'])){
    require_once 'read_single.php'; 
} 
else{
    if ($num > 0) {
        $authors_arr = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $authors_arr[] = array('id' => $id, 'author' => $author);
        }

        echo json_encode($authors_arr);
        } else {
            echo json_encode(array('message' => 'author_id Not Found'));
        }
}
?>