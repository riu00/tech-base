 <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
     <p><font size="7">【ディズニーランド】・【USJ】無料で一日貸し切れるならどっち？</font></p>
</head>
<body bgcolor="#f5f5dc"></body>
 <body>
  <p>現在時刻は<span id="real-time"></span>です</p>

  <script type="text/javascript">
function Time() {
   var realTime = new Date();
   var hour = realTime.getHours();
   var minutes  = realTime.getMinutes();
   var seconds  = realTime.getSeconds();
   var text = hour + ":" + minutes + ":" + seconds;
   document.getElementById("real-time").innerHTML = text;
}
setInterval('Time()',1000);
  </script>
  </body>
 
  
    <?php
    $dsn = 'mysql:dbname=tb240306db;host=localhost';
    $user = 'tb-240306';
    $password = 'KPNnsTyX7B';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "password varchar(8),"
    . "col_date datetime"
    .");";
    $stmt = $pdo->query($sql);
    
    //
    if(!empty($_POST["name"]) && !empty($_POST["comment"])){    

       if(empty($_POST["edit"])){
            if(!empty($_POST["pass"])){
                //テーブルにデータを入力
                $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, password, col_date) VALUES (:name, :comment, :password, now())");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                $name = $_POST['name'];
                $comment = $_POST['comment']; 
                $password = $_POST['pass'];
                $sql -> execute();
            }
        }else{
            //編集機能
             $id = $_POST["edit"]; //変更する投稿番号
            $name = $_POST["name"];
            $comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
            $sql = 'UPDATE tbtest SET name=:name,comment=:comment, col_date=CURRENT_TIMESTAMP WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        
        }
    }
        
    //削除処理
    if(!empty($_POST["delete"]) && !empty($_POST["delpass"])){
        $delete = $_POST["delete"];
        $delpass = $_POST["delpass"];
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            if($delpass == $row['password']){
                $sql = 'delete from tbtest where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }
    
    //
    if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
        $edit = $_POST["edit"];
        $editpass = $_POST["editpass"];
 
     $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            if($edit == $row['id'] && $editpass == $row['password']){
                $editnum = $row['id'];
                $editname = $row['name'];
                $editcomment = $row['comment'];
            }
        }
    }
    ?>
    
    
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前を入力してください" value=<?php  if(!empty($editname)){echo $editname;} ?>>
        <br>
        <input type="text" name="comment" placeholder="記入してください" value=<?php if(!empty($editcomment)) {echo $editcomment;} ?>>
        <br>
        <input type="password" name="pass" placeholder="パスワードを入力してください">
        <input type="submit" class="btn" value="送信する">
        <br>
        <input type="number" name="delete" placeholder="削除対象番号を入力">
        <br>
        <input type="password" name="delpass" placeholder="パスワードを入力してください">
        <input type="submit" name="submit" value="削除">
        <br>
        <input type ="number" name="edit" placeholder="編集対象番号を入力" value=<?php if(!empty($editnum)) {echo $editnum;} ?>>
        <input type="hidden" value="<?php echo "{$_POST['edit']}" ?>" name="blank">
        <br>
        <input type="password" name="editpass" placeholder="パスワードを入力してください">
        <input type="submit" name="submit" value="編集">
    </form>
    
    
  <?php
    //テーブルのデータ表示
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['col_date'].'<br>';
    echo "<hr>";
    }
    ?>
    
</body>
</html> 