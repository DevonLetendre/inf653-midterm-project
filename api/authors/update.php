<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
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

    //Ensure both ID and author are provided
    if (!isset($data->id) || !isset($data->author) || empty(trim($data->author))) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    //Set ID and author to update
    $author->id = $data->id;
    $author->author = $data->author;


    //Update author
    if($author->update()){
        //Return the updated author data in the response
        echo json_encode(array(
            'id' => (int)$author->id,
            'author' => $author->author
        ));
    }
    else{
        echo json_encode(array('message' => 'author_id Not Found'));
    }
?>

