<?php
    // Include the Database class & Category data model
    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure we have the required parameters
    if (!empty($data->id)) {

        // Set the ID to update
        $category->id = intval($data->id);

        // Check if the category exists
        if (!$category->exists()) {
            echo json_encode(['message' => 'Category ID Not Found']);
        // Delete category
        } elseif ($category->delete()) {
            echo json_encode(['id' => $category->id]);
        // There was some other issue
        } else {
            echo json_encode(['message' => 'Category Not Deleted']);
        }
    }
    // Send missing parameters message 
    else {
        echo json_encode(['message' => 'Category ID is required.']);
    }
?>