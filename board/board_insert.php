<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();
require '../include/db_connect.php';

if (!isset($_SESSION['s_name'])) {
    header('Location: ../login.html');
    exit;
}

$s_id   = $_SESSION['s_id']   ?? '';
$s_name = $_SESSION['s_name'] ?? '';
$subject = trim($_POST['subject'] ?? '');
$content = trim($_POST['content'] ?? '');

if ($subject === '' || $content === '') {
    echo "<script>alert('제목과 내용을 입력하세요.'); history.back();</script>";
    exit;
}

$conn->begin_transaction();

try {
    $stmt = $conn->prepare(
        "INSERT INTO tb_bulletin (id, registerer, regist_day, subject, content)
         VALUES (?, ?, NOW(), ?, ?)"
    );
    $stmt->bind_param("ssss", $s_id, $s_name, $subject, $content);
    $stmt->execute();

    if ($stmt->affected_rows !== 1) {
        throw new Exception('저장 실패');
    }

    $noticeNo = $conn->insert_id;

    if (isset($_FILES['b_upload_file']) && $_FILES['b_upload_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['b_upload_file']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('파일 업로드 오류 코드: ' . $_FILES['b_upload_file']['error']);
        }

        $origName = $_FILES['b_upload_file']['name'];
        $tmpPath  = $_FILES['b_upload_file']['tmp_name'];
        $fileSize = (int)$_FILES['b_upload_file']['size'];

        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        $allow = ['pdf','png','jpg','jpeg','zip','txt'];
        if (!in_array($ext, $allow, true)) {
            throw new Exception('허용되지 않은 확장자');
        }

        $rand = bin2hex(random_bytes(16));
        $storedName = $rand . ($ext ? ('.' . $ext) : '');

        $uploadDir = __DIR__ . '/../../b_data/';

        $dest = $uploadDir . $storedName;
        if (!move_uploaded_file($tmpPath, $dest)) {
            throw new Exception('파일 이동 실패');
        }

        $mime = mime_content_type($dest) ?: ($_FILES['b_upload_file']['type'] ?? 'application/octet-stream');

        $stmtFile = $conn->prepare(
            "INSERT INTO tb_bulletin_file (notice_no, original_name, stored_name, file_size, file_type)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmtFile->bind_param("issis", $noticeNo, $origName, $storedName, $fileSize, $mime);
        $stmtFile->execute();

        if ($stmtFile->affected_rows !== 1) {
            throw new Exception('파일 메타 저장 실패');
        }
    }

    $conn->commit();

    header('Location: ../board/board_list.php');
    exit;

} catch (Throwable $e) {
    $conn->rollback();
    echo "<script>alert('등록 중 오류가 발생했습니다.');</script>";
    exit;
}