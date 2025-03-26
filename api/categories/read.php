<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//Include the database and Category class
include_once '../../config/Database.php';
include_once '../../models/Category.php';

//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

//Instantiate the Category object
$category = new Category($db);

//Get categories
$result = $category->read();

//Check if there are categories
if ($result->rowCount() > 0) {
    //Categories array
    $categories_arr = array();

    //Fetch all categories
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        
        //Format each category entry
        $category_item = array(
            'id' => $id,
            'category' => $category
        );
        
        //Push to categories array
        array_push($categories_arr, $category_item);
    }

    //Output as a JSON array (no 'data' wrapper)
    echo json_encode($categories_arr);
} else {
    //If no categories are found, return an empty array
    echo json_encode(array('message' => 'category_id Not Found'));
}
?>
