<?php
    // Include the Database class & Author data model
    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Get authors
    $result = $author->read();

    // Get row count
    $num = $result->rowCount();

    // If the ID is set, we just return single author
    if (isset($_GET['id'])){
        require_once 'read_single.php'; 
    } 
    // We return all authors
    else{
        if ($num > 0) {
            $authors_arr = array();

            // Get all authors
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $authors_arr[] = array('id' => $id, 'author' => $author);
            }
            // Return the authors
            echo json_encode($authors_arr);
            } 
            // Send authors not found message
            else {
                echo json_encode(['message' => 'author_id Not Found']);
            }
    }
// Production Commit
