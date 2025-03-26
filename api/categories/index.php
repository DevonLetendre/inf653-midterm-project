<?php

    //Required for CORS
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    
    //Get the HTTP method (GET, POST, PUT, DELETE)
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    }

    //Route to the correct operation based on HTTP method
    switch($method) {
        case 'GET':
            //Handle GET request (for reading categories)
            if (isset($_GET['id'])) {
                //If an ID is provided, it's a single read request
                include_once 'read_single.php';
            } else {
                //Otherwise, it's a request to read all categories
                include_once 'read.php';
            }
            break;
        case 'POST':
            //Handle POST request (for creating a new category)
            include_once 'create.php';
            break;
        case 'PUT':
            //Handle PUT request (for updating an existing category)
            include_once 'update.php';
            break;
        case 'DELETE':
            //Handle DELETE request (for deleting a category)
            include_once 'delete.php';
            break;
        default:
            //If the method is not recognized, return a 405 Method Not Allowed error
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode(array('message' => 'Method Not Allowed'));
            break;
    }
?>

