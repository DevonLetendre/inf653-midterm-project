<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    //Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    //Instantiate Author object
    $author = new Author($db);

    //Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    //Validate input - check if 'id' is provided and is not empty
    if (!isset($data->id) || empty($data->id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit; //Stop further execution
    }

    //Set ID to update
    $author->id = $data->id;

    //Delete author
    $deleted_id = $author->delete();

    if($deleted_id){
        echo json_encode(array('id' => (int)$deleted_id));
    } else {
        echo json_encode(array('message' => 'author_id Not Found'));
    }
?>
