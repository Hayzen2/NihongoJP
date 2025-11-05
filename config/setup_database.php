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
        if($pdo->query("SHOW TABLES LIKE 'countries'")->rowCount() <= 0){
            $pdo->exec(file_get_contents(__DIR__ . '/sqls/countries.sql'));
            
        } elseif($pdo->query("SHOW TABLES LIKE 'states'")->rowCount() <= 0){
            $pdo->exec(file_get_contents(__DIR__ . '/sqls/states.sql'));
        }
        $sqlFiles = [
            __DIR__ . '/sqls/users.sql',
            __DIR__ . '/sqls/flashcards.sql',
            __DIR__ . '/sqls/flashcards_qa.sql',
            __DIR__ . '/sqls/books.sql'
        ];
        foreach($sqlFiles as $sqlFile){
            $pdo->exec(file_get_contents($sqlFile));
        }
    }catch(PDOException $e){
        die("Connection failed: " . $e->getMessage());
    }
    function getPDO():PDO {
        global $pdo;
        return $pdo;
    }
?>
