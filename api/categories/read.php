<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate category object
$category = new Category($db);

// Category query
$result = $category->read();

// Get row count
$num = $result->rowCount();

if (isset($_GET['id'])){
    require_once 'read_single.php'; 
} 
else{
    if ($num > 0) {
        $categories_arr = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $categories_arr[] = array('id' => $id, 'category' => $category);
        }

        echo json_encode($categories_arr);
        } else {
            echo json_encode(array('message' => 'category_id Not Found'));
        }
}
?>


