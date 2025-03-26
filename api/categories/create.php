<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Create DB & connect
    $database = new Database();
    $db = $database->connect();

    // Create category object
    $category = new Category($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure we have the required parameters
    if (!empty($data->category)) {
        $category->category = $data->category;

        // Create category
        if ($category->create()) {
            echo json_encode([
                'id' => $category->id,
                'category' => $category->category
            ]);
        } else {
            echo json_encode(['message' => 'Category not created.']);
        }
    } 
    // Send missing parameters message
    else {
        echo json_encode(['message' => 'Missing Required Parameters']);
    }
?>