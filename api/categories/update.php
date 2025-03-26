<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    //Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    //Instantiate Category object
    $category = new Category($db);

    //Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Validate input
    if (!isset($data->id) || !isset($data->category)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    //Set ID to update
    $category->id = $data->id;
    $category->category = $data->category;

    //Update category
    if($category->update()){
        //Return the updated category data in the response
        echo json_encode(array(
            'id' => (int)$category->id,
            'category' => $category->category
        ));
    }
    else{
        echo json_encode(array('message' => 'category_id Not Found'));
    }
?>