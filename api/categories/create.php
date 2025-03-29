<?php
    // Include the Database class & Category data model
    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

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
// Production Commit
