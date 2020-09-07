<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>掲示板</title>
</head> 

<body>
    <form action="" method="POST">
    【投稿欄】    <br>
     <input type="text" name="name" placeholder="名前を入力してください" ><br> 
     
    <input type="text" name="comment" style="width:200px;height:50px;" placeholder="コメントを入力してください" ><br>
    
    <input type="password" name="pass"  placeholder="パスワードを入力してください"><br>
    【編集するときだけ入力】<br>
    <input type="number" name="number" placeholder="編集する番号を入力"  ><br>
    <input type="submit" name="submit"> <br>
    【削除対象番号】<br>
    <input type="number" name="delete" placeholder="数字を入力してください"><br>
    
    <input type="password" name="delpass"  placeholder="パスワードを入力してください"><br>
    <input type="submit" value="削除"><br>
   ※ 編集するときは、内容を投稿欄に書き、編集番号とパスワードを入力して送信してください。<br>
    </form>
【一覧】<br>    
    <?php
    
    //データベースの接続
    $dsn = 'mysql:dbname=データベース名;host=localhost';
	$user = 'ユーザ名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブルの作成
    $sql="CREATE TABLE IF NOT EXISTS penpen2"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment TEXT,"
    ."date datetime,"
    ."pass char(32)"
    .");";
    $stmt=$pdo->query($sql);
    
    //投稿機能、データベースへの挿入
    
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["number"]) 
    &&!empty($_POST["pass"])){//名前とコメントとパスワードが空じゃない、かつ、編集番号の登録がされていないとき。
         $sql=$pdo->prepare("INSERT INTO penpen2 (name,comment,date,pass) VALUES(:name,:comment,:date,:pass)");
        $sql->bindParam(':name',$name,PDO::PARAM_STR);
        
        $sql->bindParam(':comment',$comment,PDO::PARAM_STR);
        
        $sql->bindParam(':date',$date,PDO::PARAM_STR);
        
        $sql->bindParam(':pass',$pass,PDO::PARAM_STR);
        
        $name=$_POST['name'];
        $comment=$_POST['comment'];
        $date=date('Y/m/d/H:i:d');
        $pass=$_POST['pass'];
        
        $sql->execute();
    }
    
    //削除機能
    if(!empty($_POST["delete"]) &&!empty($_POST["delpass"])){ //もし削除対象番号とパスワードが空じゃなければ、
        $delete=$_POST['delete'];
    $delpass=$_POST['delpass'];
        $id=$delete;
        $sql='SELECT*FROM penpen2 WHERE id=:id';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $results=$stmt->fetch();
        
             if($results['pass'] == $delpass){
        $id=$delete;
            $sql=' delete from penpen2 where id=:id';
            $stmt=$pdo -> prepare($sql);
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();
             }
             
    }
    
    //編集機能
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) &&!empty($_POST["number"]) &&!empty($_POST["pass"])){
        $number=$_POST["number"];
        $pass=$_POST["pass"];
       $id=$number; 
         $sql='SELECT*FROM penpen2 WHERE id=:id';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $results=$stmt->fetch();
        
             if($results['pass'] == $pass){
        
        $id=$number;
        $name=$_POST['name'];
        $comment=$_POST['comment'];
        $date=date('Y/m/d/H:i:s');
        
        $sql='UPDATE penpen2 SET name=:name, comment=:comment, date=:date WHERE id=:id';
        $stmt=$pdo -> prepare($sql);
        $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
        $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
        
        $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
        
        $stmt ->execute();
             }
    }
    
    //表示機能
    $sql='SELECT id,name,comment,date FROM penpen2';
    $stmt=$pdo->query($sql);
    $results=$stmt->fetchAll();
    
    foreach($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
        
       echo "<hr>"; 
    }
    ?>
</body>
</html>