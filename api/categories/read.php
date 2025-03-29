<?php
    // Include the Database class & Catrgory data model
    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Get categories
    $result = $category->read();

    // Get row count
    $num = $result->rowCount();

    // If the ID is set, we just return single category
    if (isset($_GET['id'])){
        require_once 'read_single.php'; 
    } 
    // We return all categories
    else{
        if ($num > 0) {
            $categories_arr = array();

            // Get all categories
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $categories_arr[] = array('id' => $id, 'category' => $category);
            }
            // Return the categories
            echo json_encode($categories_arr);
            } 
            // Send categories not found message
            else {
                echo json_encode(array('message' => 'category_id Not Found'));
            }
    }
// Production Commit
