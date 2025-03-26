<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
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

    //Check if ID is provided
    if (!isset($data->id) || empty($data->id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    //Set ID to update
    $category->id = $data->id;

    //Delete category
    $deleted_id = $category->delete();

    if($deleted_id){
        echo json_encode(array('id' => (int)$deleted_id));
    } else {
        echo json_encode(array('message' => 'Category ID Not Found'));
    }
?>