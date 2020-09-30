<?php
    $dsn     ="mysql:dbname=tb220366db;host=localhost";
    $user    ="tb-220366";
    $password="zfprpBU5hJ";
    $pdo     =new PDO($dsn,$user,$password,
               array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
               
    $sql = 'SELECT * FROM mission5 ';
    $stmt = $pdo->prepare($sql);                 
    $stmt->execute();          
    $results = $stmt->fetchAll(); 
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].",";
            echo $row["password"].",";
            echo $row["time"]."<br>";
        echo "<hr>";
        }
?>