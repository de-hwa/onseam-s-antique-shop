<?php
include "../include/db_connect.php";
session_start();
$no = $_GET['no'];

$sql = "select * from tb_bulletin where no=$no";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$subject   = htmlspecialchars($row['subject']);
$content   = htmlspecialchars($row['content']);
$username  = $row['registerer'];
$date      = $row['regist_day'];
$no        = $row['no'];

$sqlf = "select * from tb_bulletin_file where bulletin_no=$no";
$resultf = mysqli_query($conn, $sqlf);
$rowf = mysqli_fetch_assoc($resultf);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>BULLETIN</title>
  <link rel="stylesheet" href="../css/style.css?v=1">
  <script>
    function confirmDelete() {
      if(confirm("삭제하시겠습니까?")) {
        document.getElementById('deleteForm').submit();
      } else {
        return false;
      }
    }
  </script>
</head>
<body>

<h2 class="page-title">BULLETIN</h2>

<table class="board-view-table">
  <tr>
    <th>제목</th>
    <td><?=$subject?></td>
  </tr>
  <tr>
    <th>작성자</th>
    <td><?=$username?></td>
  </tr>
  <tr>
    <th>작성일자</th>
    <td><?=$date?></td>
  </tr>
  <tr>
    <th>내용</th>
    <td><?=$content?><br></td>
  </tr>
  <tr>
    <th>파일</th>
    <td>
      <?php if($rowf): ?>
        <a href="../file/download.php?board=board&file_id=<?=$rowf['file_id']?>"><?=$rowf['original_name']?> [저장]</a>
      <?php endif; ?>
    </td>
  </tr>
</table>

<div class="action-btns">
<?php
if(!isset($_SESSION['s_name'])) {
?>
  <button type="button" class="btn" onclick="location.href='../main/index.php'">메인으로</button>
  <button type="button" class="btn" onclick="location.href='../member/login_form.php'">로그인</button>
<?php
}
$s_name = $_SESSION['s_name'] ?? '';
if ($row['registerer'] == $s_name) {
?>
  <button type="button" class="btn" onclick="location.href='../board/board_form.php?no=<?=$no?>'">글 수정</button>
  <form id="deleteForm" action="../board/board_delete.php" method="POST" style="display:inline;">
    <input type="hidden" name="no" value="<?= htmlspecialchars($no,ENT_QUOTES) ?>">
    <button type="button" class="btn" onclick="confirmDelete()">글 삭제</button>
  </form>
<?php }
else if ($s_name == 'admin') {
?>
<form id="deleteForm" action="../board/board_delete.php" method="POST" style="display:inline;">
    <input type="hidden" name="no" value="<?= htmlspecialchars($no,ENT_QUOTES) ?>">
    <button type="button" class="btn" onclick="confirmDelete()">글 삭제</button>
</form>
<?php
}
?>
</div>

</body>
</html>
