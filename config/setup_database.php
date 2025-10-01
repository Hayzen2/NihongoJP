<?php 
    $severname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nihongojp";

    try{
        $conn = new PDO("mysql:host=$severname", $username, $password); //Connect to the server
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Check if database exists
        $stmt = $conn->query("SHOW DATABASES LIKE '$dbname'");
        $result = $stmt->fetch();
        if(!$result){
            //Create it if it doesn't exist
            $conn->exec("CREATE DATABASE $dbname CHARACTER SET utf8mb4");
        }
        //Connect to the database
        global $pdo;
        $pdo = new PDO("mysql:host=$severname;dbname=$dbname", $username, $password); //Connect to the database
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        //Create flashcard table
        $sql = "CREATE TABLE IF NOT EXISTS flashcards (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            topic VARCHAR(100) NOT NULL,
            author VARCHAR(100) NOT NULL,
            question VARCHAR(500) NOT NULL,
            answer VARCHAR(500) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql);
    }catch(PDOException $e){
        die("Connection failed: " . $e->getMessage());
    }
    function getPDO():PDO {
        global $pdo;
        return $pdo;
    }
?>
