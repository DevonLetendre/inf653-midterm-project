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

    // Get ID
    $category->id = isset($_GET['id']) ? intval($_GET['id']) : die();

    // Get category
    $category->read_single();

    // Set properties if found & return
    if ($category->category) {
        $category_arr = array(
            'id' => $category->id,
            'category' => $category->category
        );

        echo json_encode($category_arr);
    } 
    // Send category not found message
    else {
        echo json_encode(array('message' => 'category_id Not Found'));
    }
?>