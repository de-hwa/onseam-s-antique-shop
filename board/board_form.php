<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();
include "../include/db_connect.php";

$s_role = $_SESSION['s_role'] ?? '';
if ($s_role == '') {
    header('Location: ../error.html');
    exit;
}

$no = $_GET['no'] ?? '';
$is_new = ($no === '' || !ctype_digit((string)$no));

$row = null;
if(!$is_new) {
    $stmt = $conn->prepare("select * from tb_bulletin where no = ?");
    $stmt->bind_param("i", $no);
    $stmt->execute();
    $res = $stmt->get_result();
    if (!$res || !($row = $res->fetch_assoc())) {
        header('Location: ../access.html');
        exit;
    }
    $subject = $row['subject'] ?? '';
    $content = $row['content'] ?? '';
    $registerer = $row['registerer'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>REVIEW</title>
  <link rel="stylesheet" href="../css/style.css?v=1">
  <script>
  function bulletin_check() {
    const subject = document.getElementById("subject");
    const content = document.getElementById("content");

    if(!subject.value) {
      alert("제목을 입력해 주세요.");
      subject.focus();
      return false;
    }

    if(!content.value) {
      alert("내용을 입력해 주세요.");
      content.focus();
      return false;
    }

    return true;
  }
  </script>
</head>
<body>

<h2 class="page-title">REVIEW</h2>

<?php if ($is_new):?>
<form class="form-box" action="../board/board_insert.php" name="board_form" id="board_form" method="POST" onsubmit="return bulletin_check()" enctype="multipart/form-data">
  <div class="row">
    <label for="subject">제목</label>
    <input type="text" name="subject" id="subject" placeholder="제목">
  </div>
  <div class="row">
    <label for="content">내용</label>
    <textarea name="content" id="content" placeholder="내용"></textarea>
  </div>
  <div class="row">
    <label for="b_upload_file">파일 업로드</label>
    <input type="file" name="b_upload_file" id="b_upload_file">
  </div>
  <div class="actions">
    <input type="submit" class="btn" value="게시">
    <button type="button" class="btn" onclick="location.href='../board/board_list.php'">목록으로</button>
  </div>
</form>

<?php else:?>
<form class="form-box" action="../board/board_update.php" name="update_form" id="update_form" method="POST" onsubmit="return bulletin_check()" enctype="multipart/form-data">
  <input type="hidden" name="no" value="<?= htmlspecialchars($no, ENT_QUOTES) ?>">
  <div class="row">
    <label for="subject">제목</label>
    <input type="text" name="subject" id="subject" value="<?= htmlspecialchars($subject, ENT_QUOTES) ?>">
  </div>
  <div class="row">
    <label for="content">내용</label>
    <textarea name="content" id="content"><?= htmlspecialchars($content, ENT_QUOTES) ?></textarea>
  </div>
  <div class="row">
    <label for="b_upload_file">파일 업로드</label>
    <input type="file" name="b_upload_file" id="b_upload_file">
  </div>
  <div class="actions">
    <input type="submit" class="btn" value="수정하기">
    <button type="button" class="btn" onclick="location.href='../board/board_list.php'">목록으로</button>
  </div>
</form>
<?php endif; ?>

</body>
</html>