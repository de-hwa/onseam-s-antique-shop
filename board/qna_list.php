<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>Q&A</title>
  <link rel="stylesheet" href="../css/style.css?v=1">
</head>
<body>

<h2 class="page-title">Q&A</h2>

<?php
include "../include/db_connect.php";
session_start();

$sql = "SELECT * from tb_qna";
$result = mysqli_query($conn, $sql);
?>

<table class="board-table">
  <thead>
    <tr>
      <th>No.</th>
      <th>제목</th>
      <th>작성자</th>
      <th>작성일자</th>
    </tr>
  </thead>
  <tbody>
<?php
while($row = mysqli_fetch_assoc($result)) {
?>
    <tr>
      <td><?=$row['no']?></td>
      <td><a href="../board/qna_view.php?no=<?=$row['no']?>"><?=htmlspecialchars($row['subject'])?></a></td>
      <td><?=$row['registerer']?></td>
      <td><?=$row['regist_day']?></td>
    </tr>
<?php
}
?>
  </tbody>
</table>

<div class="action-btns">
<?php
if(!isset($_SESSION['s_name'])) {
?>
  <button type="button" class="btn" onclick="location.href='../main/index.php'">메인으로</button>
<?php
} else {
?>
  <button type="button" class="btn" onclick="location.href='../main/index.php'">메인으로</button>
  <button type="button" class="btn" onclick="location.href='../board/qna_form.php'">글 작성</button>
<?php
}
?>
</div>

</body>
</html>