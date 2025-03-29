<?php
    // Include the Database class & Category data model
    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Create DB & connect
    $database = new Database();
    $db = $database->connect();

    // Create category object
    $category = new Category($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ensure both ID and category are provided
    if (isset($data->id) && isset($data->category) && !empty($data->id) && !empty($data->category)) {

        // Set ID and author to update
        $category->id = intval($data->id);
        $category->category = htmlspecialchars(strip_tags($data->category));

        // Check if category exists
        if ($category->exists()) {
            // Update category
            if ($category->update()) {
                // Return the updated author in the response
                echo json_encode(array(
                    'id' => $category->id,
                    'category' => $category->category
                ));
            } 
            // Return failed to update message
            else {
                echo json_encode(array('message' => 'Failed to update category.'));
            }
        } 
        // Return category ID not found
        else {
            echo json_encode(array('message' => 'Category ID Not Found.'));
        }
    } 
    // Return missing required parameters message
    else {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }
// Production Commit
