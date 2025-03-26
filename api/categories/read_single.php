<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    //Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    //Instantiate new Category object
    $category = new Category($db);

    //Get ID
    $category->id = isset($_GET['id']) ? $_GET['id'] : die();

    //Get category
    $category->read_single();

    if ($category->id !== null) {
        //Create array
        $category_arr = array(
            'id' => $category->id,
            'category' => $category->category
        );

        //Make JSON
        echo json_encode($category_arr);
    } else {
        //Return not found message
        echo json_encode(array('message' => 'category_id Not Found'));
    }
?>