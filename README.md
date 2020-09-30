# first-file
TACH-BASEにて作成

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-4-2</title>
</head>
<body>
<?php
    echo "データベースへの接続開始";
    //データベースへの接続
    $dsn     ="データベース名";
    $user    ="ユーザー名";
    $password="パスワード";
    $pdo     =new PDO($dsn,$user,$password,
               array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
                //4-2の作業
    $sql="CREATE TABLE IF NOT EXISTS mission5"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment TEXT,"
    ."time char(32),"
    ."password char(32)"
    .");";
    $stmt=$pdo->query($sql);
    echo"データベースへの接続終了<hr>";
?>           
<?php
        $time=date("Y/m/d/ H:i:s");
        $edit_num="";
        $rename="";
        $recomment="";
        $repassword="";
?>
<?php
    //name and comment新規投稿の条件分岐
    if(isset($_POST["submit"])){
        //名前とコメントが入力されてなかったら
        if(empty($_POST["name"]) && empty($_POST["comment"]) && empty($_POST["password"])){
            echo"<br>名前とコメントが入力されていません<br>
                !!名前とコメント,パスワードを入力してください<br><hr>"; 
            }
        //コメントのみ入力
        elseif(empty($_POST["name"]) && empty($_POST["password"]) && !empty($_POST["comment"])){
               echo"<br>コメントは入力されましたが、<br>
                    しかし<br>
                    名前とパスワードが入力されなかったため、
                    登録できませんでした。<br><hr>";
        }
        //名前のみ入力
        elseif(empty($_POST["comment"]) && empty($_POST["password"]) && !empty($_POST["name"])){
            echo"<br>名前は入力されましたが、<br>
                しかし<br>
                コメントとパスワードが入力されなかったため、
                登録できませんでした。<br><hr>";
        }
        
        elseif(empty($_POST["comment"]) && empty($_POST["name"]) && !empty($_POST["password"])){
            echo"<br>パスワードは入力されましたが、<br>
                しかし<br>
                コメントと名前が入力されなかったため、
                登録できませんでした。<br><hr>";
        }
        elseif(empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){
            echo"<br>コメントとパスワードは入力されましたが、<br>
                しかし<br>
                名前が入力されなかったため、
                登録できませんでした。<br><hr>";
        }
        elseif(empty($_POST["comment"]) && !empty($_POST["name"]) && !empty($_POST["password"])){
            echo"<br>名前とパスワードは入力されましたが、<br>
                しかし<br>
                コメントが入力されなかったため、
                登録できませんでした。<br><hr>";
        }
        elseif(empty($_POST["password"]) && !empty($_POST["name"]) && !empty($_POST["commment"])){
            echo"<br>名前とコメントは入力されましたが、<br>
                しかし<br/>
                パスワードが入力されなかったため、
                登録できませんでした。<br><hr>";
        //名前とコメント入力
        }else{
            $name=$_POST["name"];
            $comment   = $_POST["comment"];
            $password  = $_POST["password"];

            if($_POST["edit_hidden"] == ""){
                //編集番号が入っていない　→　新規投稿である場合
                echo"入力を受け付けました<br>".
                 $name."さん/ありがとうございます！<hr>";

                $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment,time,password) VALUES (:name, :comment,:time,:password)");
	            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':time', $time,PDO::PARAM_STR);
                $sql -> bindParam(':password', $password,PDO::PARAM_STR);

	            $sql -> execute();
            }else{
                $edit_hidden=$_POST["edit_hidden"];

               //変更したい名前、変更したいコメントは自分で決めること
	            $sql = 'UPDATE mission5 SET name=:name,comment=:comment,time=:time,password=:password WHERE id=:id';
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $edit_hidden, PDO::PARAM_INT);
                $stmt->bindParam(':time', $time, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->execute();
                
            }
        }
        
        
        
    //deleting削除機能
    //4-9をつかう
    }elseif(isset($_POST["deleting_data"])){  

        $deleting_number = $_POST["deleting_number"];
        $deleting_password=$_POST["deleting_password"];
        //No deleting_number.
        if(empty($_POST["deleting_number"]) && empty($_POST["deleting_password"])){
            echo"<br/>削除対象番号とパスワードが入力されていません<br/><hr>";
        }
        elseif(empty($deleting_number) && !empty($deleting_password)){
            echo"<br/>削除対象番号が入力されていません<br/><hr>";
        }
        elseif(empty($deleting_password) && !empty($deleting_number)){
            echo"<br/>パスワードが入力されていません<br/><hr>";
        //削除フォーム入力済み           
        }elseif(!empty($_POST['deleting_number']) && !empty($deleting_password)){

        $sql = 'SELECT * FROM mission5 WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $deleting_number, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                $db_pass=$row['password'];
            }
            if($db_pass==$deleting_password){
	            $sql = 'delete from  mission5 where id=:id';
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':id', $deleting_number, PDO::PARAM_INT);
                $stmt->execute();
                echo"削除が完了しました。";

            }else{
                echo"削除できません。";
            }
        }
        
    //editing
    //4-7をつかう
    }elseif(isset($_POST["editing_data"])){  

        $editing_number =$_POST["editing_number"];
        $editing_password=$_POST["editing_password"];

        if(empty($_POST["editing_number"]) && empty($_POST["editing_password"])){     
            echo"<br/>編集番号とパスワードが入力されていません<br>
                !!編集番号とパスワードを入力してください<br><hr>";
        
        }elseif(empty($editing_number)){     
            echo"<br/>編集番号が入力されていません<br>
                !!編集番号を入力してください<br><hr>";
        }elseif(empty($editing_password)){       
            echo"<br>編集番号とパスワードが入力されていません<br>
                !!編集番号とパスワードを入力してください<br><hr>";
                
        }else{      
            $sql = 'SELECT * FROM mission5 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $editing_number, PDO::PARAM_INT); 
        
        // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                $db_pass=$row['password'];
            }
            if($db_pass==$editing_password){
                $sql = 'SELECT * FROM mission5 WHERE id=:id ';
                $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':id', $editing_number, PDO::PARAM_INT);
                $stmt->execute();

                $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    $rename=$row["name"];
                    $recomment = $row["comment"];     
                    $repassword = $row["password"];  
                    $edit_num = $row["id"]; 
                }

            }else{
                echo"編集できません。";
            }
        }        
     }else{
         echo "<hr>名前とコメント、パスワード<br/>".
              "必要な場合には削除対象番号または編集対象番号を入力してください。<br>";
         echo "<hr>";
    }
?>
    <form action=""method="post">
        名前と
        自由にできるとしたら、
        やりたいことをコメント欄に書いてください！<br>
        お願いします<br><hr>
        <input type="text" name = 'name' placeholder="名前"
               value="<?php echo $rename ?>" >
        <br>
        <input name="comment" type="text" placeholder="コメント"
               value="<?php echo $recomment?>">
        <br>
        <input name="password" type="text" placeholder= "パスワード" value="<?= $repassword ?>">
        <input name="submit" type="submit" value="送信">
        <input name = "edit_hidden" type="hidden" value="<?= $edit_num ?>">       
        </form>
    <br>
    <form action="" method="post">
        削除対象番号を入力してください<br/>
        <input name="deleting_number" type="text" placeholder="削除対象番号">
        <input name="deleting_password" type="text" placeholder= "パスワード">
        <input name="deleting_data" type="submit" value="削除">
    </form>
    <br>
    <form action="" method="post">
        編集する場合は先頭の番号を入力してください<br/>
        <input name="editing_number" type="text" placeholder="編集番号">
        <input name="editing_password" type="text" placeholder="パスワード">
        <input name="editing_data" type="submit" value="編集">
    </form>
<?php
    $sql = 'SELECT * FROM mission5 ';
    $stmt = $pdo->prepare($sql);                 
    $stmt->execute();          
    $results = $stmt->fetchAll(); 
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].",";
            echo $row["time"]."<br>";
        echo "<hr>";
        }
?>
</body>
</html>
