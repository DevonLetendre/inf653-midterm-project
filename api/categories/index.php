<?php
    //Headers to deal with CORS
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    }

    // Data models & DB connection class
    require_once '../../config/Database.php';
    require_once '../../models/Category.php';

    // Create DB & connect
    $database = new Database();
    $db = $database->connect();

    // Create category object
    $category = new Category($db);

    // Route to the correct operation based on HTTP method
    if($method == "GET"){
        require_once 'read.php';
    }

    if ($method == "POST"){
        require_once 'create.php';
    }

    if ($method == "PUT"){
        require_once 'update.php';
    }

    if ($method == "DELETE"){
        require_once 'delete.php';
    }
// Production Commit
