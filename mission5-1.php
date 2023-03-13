<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5-01</title>
    <style>
        .class p {
            font-size: 40px;
            color: black;
            margin: 0;
            padding: 0;
            font-style: italic;
        }
        .class_name, .class_com, .class_pass, .class_edit, .class_delete {
            padding: 0.5em 1em;
            margin: 2em 0;
            font-weight: bold;
            color: #6091d3;
            background: #FFF;
            border: solid 3px #6091d3;
            border-radius: 10px;
        }
        .class_sub {
            color: #fff;
            background-color: #eb6100;
        }
        .class_sub:hover {
            color: #fff;
            background: #f56500;
        }

    </style>
</head>
    <?php
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        $dsn = "データベース名";
        $user = "ユーザー名";
        $password = "パスワード名";
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        /*$sql = "DROP TABLE tbtest";
        $stmt = $pdo -> query($sql);*/
        
        
        $sql = "CREATE TABLE IF NOT EXISTS tbtest"
        ."("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        ."time TIMESTAMP,"
        ."pass char(32)"
        .");";
        $stmt = $pdo->query($sql);
        
        /*$sql = "SHOW TABLES";
        $result = $pdo->query($sql);
        foreach ($result as $row) {
            echo $row[0];
            echo "<br>";
        }
        echo "<hr>";*/
        
        /*$sql = "SHOW CREATE TABLE tbtest";
        $result = $pdo->query($sql);
        foreach ($result as $row) {
            echo $row[1];
            echo "<br>";
        }
        echo "<hr>";*/
        
        // 編集選択機能
        if (!empty($_POST["edit"])  && !empty($_POST["pass_edit"])) {
            $id = $_POST["edit"];
            $pass_edit = $_POST["pass_edit"];
            $sql = "SELECT * FROM tbtest";
            $stmt = $pdo->query($sql);
            
            foreach ($stmt as $row) {
                if ($row[0] == $id) {
                    $ename = $row["name"];
                    $ecomment = $row["comment"];
                    $pass = $row["pass"];
                    $editnumber = $row["id"];
                }
            }
        }
        
    ?>
<body>   
    <form action="m5-01.php" method="post">
        <div class="class">
            <p>掲示板</p>
        </div>
        <div class="class_name">
            <label for="name">名前</label>
            <input type="text" name="name" placeholder="名前" value = "<?php if(!empty($pass)){echo $ename;}?>">
        </div>
        <div class="class_com">
            <label for="name">内容</label>
            <input type="text" name="comment" placeholder="コメント" value = "<?php if(!empty($pass)){echo $ecomment;}?>">
        </div>
        <div class="class_pass">
            <label for="name">パスワード</label>
            <input id="pass" type="text" name="pass_post" placeholder="パスワード" value ="<?php if(!empty($pass)){echo $pass;}?>">
        </div>
        <input type="hidden" name="ed_num" value = "<?php if(!empty($pass)){echo $editnumber;}?>">
        <input class="class_sub" type="submit" name="submit">
        <div class="class_edit">
            <input type="number" name="edit" placeholder="編集対象番号">
            <input id="pass" type="text" name="pass_edit" placeholder="パスワード">
            <input class="class_sub" type="submit" name="submit" value="編集">
        </div>
        <div class="class_delete">
            <input type="number" name="delete" placeholder="削除対象番号">
            <input id="pass" type="text" name="pass_delete" placeholder="パスワード">
            <input class="class_sub" type="submit" name="submit" value="削除">
        </div>
    </form>
    <?php
        
        //新規投稿
        if (!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass_post"]) and empty($_POST["ed_num"])) {

            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $pass_post = $_POST["pass_post"];
            $time = date("Y/m/d H:i:s");
            $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, pass, time) VALUES(:name, :comment, :pass, :time)");
            $sql -> bindParam(":name", $name, PDO::PARAM_STR);        
            $sql -> bindParam(":comment", $comment, PDO::PARAM_STR);
            $sql -> bindParam(":time", $time, PDO::PARAM_STR);
            $sql -> bindParam(":pass", $pass_post, PDO::PARAM_STR);
            $sql -> execute();     //実行する
            
        } 
        //編集実行機能
        if (!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass_post"]) && !empty($_POST["ed_num"])) {
            $id = $_POST["ed_num"];
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $pass_post = $_POST["pass_post"];
            $time = date("Y/m/d H:i:s");
            $sql = "UPDATE tbtest SET name=:name,comment=:comment,pass=:pass,time=:time where id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt -> bindParam(":name", $name, PDO::PARAM_STR);
            $stmt -> bindParam(":comment", $comment, PDO::PARAM_STR);
            $stmt -> bindParam(":pass", $pass_post, PDO::PARAM_STR);
            $stmt -> bindParam(":time", $time, PDO::PARAM_STR);
            $stmt -> bindParam(":id", $id, PDO::PARAM_INT);
            $stmt -> execute();
        }
        
        //削除機能
        if (!empty($_POST["delete"]) && !empty($_POST["pass_delete"])) {
            $delete = $_POST["delete"];
            $pass_delete = $_POST["pass_delete"];
            $sql = "DELETE FROM tbtest WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt -> bindParam(":id", $delete, PDO::PARAM_INT);
            $stmt -> execute();
        }
        
        //表示機能
        $sql = "SELECT * FROM tbtest";
        $stmt = $pdo -> query($sql);
        foreach ($stmt as $row) {
            echo $row["id"] . ",";
            echo $row["name"] . ",";
            echo $row["comment"] . ",";
            echo $row["time"] . "<br>";
        }
        echo "<hr>";
    ?>
</body>
</html>
