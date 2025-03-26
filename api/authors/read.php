<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//Include the database and Author class
include_once '../../config/Database.php';
include_once '../../models/Author.php';

//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

//Instantiate the Author object
$author = new Author($db);

//Get authors
$result = $author->read();

//Check if there are authors
if ($result->rowCount() > 0) {
    //Authors array
    $authors_arr = array();

    //Fetch all authors
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        
        //Format each author entry
        $author_item = array(
            'id' => $id,
            'author' => $author
        );
        
        //Push to authors array
        array_push($authors_arr, $author_item);
    }

    //Output as a JSON array (no 'data' wrapper)
    echo json_encode($authors_arr);
} else {
    //If no authors are found, return an empty array
    echo json_encode(array("message" => "author_id Not Found"));
}
?>
