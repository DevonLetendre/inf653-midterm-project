<?php
//Headers to deal with CORS -- not needed in CRUD files
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
}

require_once '../../config/Database.php';
require_once '../../models/Author.php';
require_once '../../models/Category.php';
require_once '../../models/Quote.php';
require_once '../../function/isValid.php';


// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate category object
$category = new Category($db);

// Check if ID is provided in the query string
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
?>