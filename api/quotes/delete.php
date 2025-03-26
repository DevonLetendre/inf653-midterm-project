
<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    //Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    //Instantiate Category object
    $quote = new Quote($db);

    //Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Check if ID is provided
    if (!isset($data->id) || empty($data->id)) {
        echo json_encode(array('message' => 'No Quotes Found'));
        exit;
    }

    //Set ID to update
    $quote->id = $data->id;

    //Delete quote
    $deleted_id = $quote->delete();

    if($deleted_id){
        echo json_encode(array('id' => $deleted_id));
    } else {
        echo json_encode(array('message' => 'No Quotes Found'));
    }
?>