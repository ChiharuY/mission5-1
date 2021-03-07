<?php
//データベースへの接続
$dsn = 'mysql:dbname=*********;host=localhost';
	$user = '*********';
	$password = '*********';
	$pdo = new PDO($dsn, $user, $password, 
	array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS posts"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date char(32),"
	. "password char(10)"
	.");";
	$stmt = $pdo->query($sql);
  
//投稿機能
  if (isset($_POST['name']) &&
      isset($_POST['comment']) &&
      isset($_POST['password'])) {

    $get_name = (string)$_POST['name'];
    $get_comment = (string)$_POST['comment'];
    $get_password = (string)$_POST['password'];
    $get_date = (string)date('Y/m/d H:i:s');

    if ($get_comment !== '') {
        
      $get_name = ($get_name === '') ? '名無し' : $get_name;

    $sql = $pdo -> prepare("INSERT INTO posts 
    (name, comment, date, password) 
    VALUES (:name, :comment, :date, :password)");
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $sql -> bindParam(':date', $date, PDO::PARAM_STR);
      $sql -> bindParam(':password', $password, PDO::PARAM_STR);
      $name = "$get_name";
      $comment = "$get_comment";
      $date = "$get_date";
      $password = "$get_password";
      $sql -> execute();
    }
  }

//削除機能
  if (isset($_POST['deleteNo']) && 
      isset($_POST['delPassword'])){

    $delete = (int)$_POST['deleteNo'];
    $delPassword = (string)$_POST['delPassword'];
        
    $id = (int)$delete;
    $password = (string)$delPassword;

    $sql = 'delete from posts where id=:id AND password=:password';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
  }

//編集機能
  if (isset($_POST['editNo']) &&
      isset($_POST['editName']) &&
      isset($_POST['editComment']) &&
      isset($_POST['editPassword'])) {

    $edit = (int)$_POST["editNo"];
    $editName = (string)$_POST['editName'];
    $editComment = (string)$_POST['editComment'];
    $editPassword = (string)$_POST['editPassword'];
    $date = (string)date('Y/m/d H:i:s');

    $id = (int)$edit;
    $name = (string)$editName;
    $comment = (string)$editComment;
    $password = (string)$editPassword;
    $date = (string)$date;

    $sql = 'UPDATE posts SET name=:name, 
    comment=:comment,  date=:date WHERE id=:id AND 
    password=:password';
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->execute();
    
    $edit = null;
  }

//表示
  $sql = 'SELECT * FROM posts';
  $stmt = $pdo->query($sql);
  $results = $stmt->fetchAll();

  function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
  }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>mission_5-1</title>
</head>
<body>
  <h1>ひとこと掲示板</h1>
  <form action="" method="post">
    <p>---------- 投稿 ----------</p>
    お名前: <input type="text" name="name" 
    placeholder="お名前"><br/>
    コメント: <input type="text" name="comment" 
    placeholder="コメント"><br/>
    パスワード: <input type="text" name="password" 
    placeholder="パスワード" maxlength="10">
    <input type="submit" name="post" value="投稿">
    
    <p>---------- 編集 ----------</p>
    編集番号: <input type="number" name="editNo" 
    min="1" max="" placeholder="半角数字"><br/>
    お名前: <input type="text" name="editName" 
    placeholder="お名前"><br/>
    コメント: <input type="text" name="editComment" 
    placeholder="コメント"><br/>
    パスワード: <input type="text" name="editPassword" 
    placeholder="パスワード" maxlength="10">
    <input type="submit" name="submit" value="編集">
    
    <p>---------- 削除 ----------</p>
    削除番号: <input type="number" name="deleteNo" 
    min="1" max="" placeholder="半角数字"><br/>
    パスワード: <input type="text" name="delPassword" 
    placeholder="パスワード" maxlength="10">
    <input type="submit" name="submit" value="削除">
    <hr>
  </form>

  <h2>投稿（<?= count($results); ?>件）</h2>
  <ul>
    <?php if (count($results)) : ?>
      <?php foreach ($results as $row) : ?>
          <li>
            <?php
            echo $row['id'].",";
            echo $row['name'].",";  
            echo $row['comment'].",";
            echo $row['date']."<br>";
            echo "<hr>";
            ?>
          </li>
      <?php endforeach; ?>
    <?php else : ?>
      <li>まだ投稿はありません。</li>
    <?php endif; ?>
  </ul>
</body>
</html>