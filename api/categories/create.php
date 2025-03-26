<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    //Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    //Instantiate Category object
    $category = new Category($db);

    //Get raw data
    $data = json_decode(file_get_contents("php://input"));

    //Check if 'category' is set in the request body
    if (empty($data->category)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    //Set the category property
    $category->category = $data->category;

    //Create category
    if($category->create()){
        //After successful insertion, retrieve the id of the new category
        $new_category = array(
            'id' => (int)$category->id, //Assuming the Category model has an $id property for the created ID
            'category' => $category->category
        );

        //Return the new category in the desired format
        echo json_encode($new_category);
    }
    else {
        echo json_encode(array('message' => 'Category Not Created'));
    }
?>