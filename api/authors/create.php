<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    //Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    //Instantiate Author object
    $author = new Author($db);

    //Get raw data
    $data = json_decode(file_get_contents("php://input"));

    //Check if 'author' is set in the request body
    if (empty($data->author)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    //Set the author property
    $author->author = $data->author;

    //Create author
    if($author->create()){
        // After successful insertion, retrieve the id of the new author
        $new_author = array(
            'id' => (int)$author->id, // Assuming the Author model has an $id property for the created ID
            'author' => $author->author
        );

        // Return the new author in the desired format
        echo json_encode($new_author);
    }
    else {
        echo json_encode(array('message' => 'Author Not Created'));
    }
?>