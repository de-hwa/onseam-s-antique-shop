<title>NOTICE</title>
<link rel="stylesheet" href="../css/style.css">
<h2 class="page-title">NOTICE</h2>
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include "../include/db_connect.php";
session_start();

$sql = "SELECT * from tb_notice";
$result = mysqli_query($conn, $sql);
?>

<table class="notice-table">
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
      <td><a href="../board/notice_view.php?no=<?=$row['no']?>"><?=htmlspecialchars($row['subject'])?></a></td>
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
if(!isset($_SESSION['s_name']) || $_SESSION['s_role'] != 'admin') {
?>
  <button type="button" class="btn" onclick="location.href='../main/index.php'">메인으로</button>
<?php
} else {
?>
  <button type="button" class="btn" onclick="location.href='../main/index.php'">메인으로</button>
  <button type="button" class="btn" onclick="location.href='../board/notice_form.php'">글 작성</button>
<?php
}
?>
</div>