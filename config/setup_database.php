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
            'users'        => __DIR__ . '/sqls/users.sql',
            'flashcards'   => __DIR__ . '/sqls/flashcards.sql',  // has CREATE + INSERT
            'flashcards_qa'=> __DIR__ . '/sqls/flashcards_qa.sql',
            'books'        => __DIR__ . '/sqls/books.sql',
            'vocabs'       => __DIR__ . '/sqls/vocabs.sql'
        ];

        foreach ($sqlFiles as $table => $file) {
            // Import only if TABLE DOESN'T EXIST
            if ($pdo->query("SHOW TABLES LIKE '$table'")->rowCount() == 0) {
                $pdo->exec(file_get_contents($file));
            }
        }

        // Detect if table is empty -> Only import CSV if empty
        $count = $pdo->query("SELECT COUNT(*) AS c FROM vocabs")->fetch()['c'];
        if ($count == 0) {
            $csvFiles = [
                'n5.csv' => 'N5',
                'n4.csv' => 'N4',
                'n3.csv' => 'N3',
                'n2.csv' => 'N2',
                'n1.csv' => 'N1'
            ];

            foreach ($csvFiles as $file => $level) {
                $path = __DIR__ . "/csv/vocabulary/" . $file;
                if (!file_exists($path)) {
                    continue;
                }
                $handle = fopen($path, "r"); // Open CSV

                // Skip header row
                fgetcsv($handle);

                $stmt = $pdo->prepare("
                    INSERT INTO vocabs (word, reading, meaning, jlpt_level)
                    VALUES (?, ?, ?, ?)
                ");

                while (($data = fgetcsv($handle, 10000, ",")) !== false) { // Read CSV line by line
                    $stmt->execute([
                        $data[0] ?? '',
                        $data[1] ?? '',
                        $data[2] ?? '',
                        $level // JLPT level not from CSV
                    ]);
                }

                fclose($handle);
            }
        }
    }catch(PDOException $e){
        die("Connection failed: " . $e->getMessage());
    }
    function getPDO():PDO {
        global $pdo;
        return $pdo;
    }
?>
