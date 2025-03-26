<?php
    class Category {
        //DB stuff
        private $conn;
        private $table = 'categories';

        //Properties
        public $id;
        public $category;

        //Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        //Get categories
        public function read(){

            //Create query
            $query = 'SELECT
                        id,
                        category
                    FROM
                        ' . $this->table . '
                    ORDER BY 
                        id DESC';

            //Prepare statement
            $stmt = $this->conn->prepare($query);

            //Execute query
            $stmt->execute();

            //Return the statement object so it can be handled in read.php
            return $stmt;
        }

        //Get single category
        public function read_single(){
            // Create query
            $query = 'SELECT id, category FROM categories WHERE id = :id LIMIT 1';
        
            // Prepare statement
            $stmt = $this->conn->prepare($query);
        
            // Bind ID
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        
            // Execute query
            $stmt->execute();
        
            // Fetch
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($row) {
                // Set properties if found
                $this->id = $row['id'];
                $this->category = $row['category'];
            } else {
                // If not found, set ID to null
                $this->id = null;
            }
        }

        //Create category
        public function create() {

            //Create query (corrected for postGre SQL)
            $query = 'INSERT INTO ' . $this->table . '
                    (category)
                    VALUES
                    (:category)';
                    
            //Prepare statement
            $stmt = $this->conn->prepare($query);

            //Clean & sanitize data
            $this->author = htmlspecialchars(strip_tags($this->category));

            //Bind data
            $stmt->bindParam(':category', $this->category);

            /*OLD
            //Execute query
            if($stmt->execute()){
                return true;
            }
            */

            //Execute query
            if($stmt->execute()){
                // Retrieve the last inserted ID
                $this->id = $this->conn->lastInsertId();  // This will give the ID of the new category
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        //Update category
        public function update() {

            //Create query (corrected for postGre SQL)
            $query = 'UPDATE ' . $this->table . ' 
                    SET 
                        category = :category
                    WHERE
                        id = :id';
                        
            //Prepare statement
            $stmt = $this->conn->prepare($query);

            //Clean & sanitize data
            $this->author = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));

            //Bind data
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':id', $this->id);
            //$stmt->bindParam(':id', $this->id, PDO::PARAM_INT); // Ensuring ID is bound as an integer

            // Execute query
            if($stmt->execute()){
                // Check if any rows were updated
                if ($stmt->rowCount() > 0){
                    return true; // Successful update
                } 
                else{
                    // No rows updated, likely the category doesn't exist or the new value is the same
                    return false;
                }
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        //Delete category
        public function delete(){

            //Create query (updated for postGre SQL)
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            //Prepare statement
            $stmt = $this->conn->prepare($query);

            //Clean/Sanitize Data
            $this->id = htmlspecialchars(strip_tags($this->id));

            //Bind data
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if ($stmt->execute()) {
                // Check if any rows were affected
                if ($stmt->rowCount() > 0) {
                    return $this->id;  // Return the ID of the deleted category
                }
            }

            // Return false if no rows were affected (i.e., category not found)
            return false;
        }
    }
?>