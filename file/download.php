<?php
require '../include/db_connect.php';
$board = $_GET['board'];
if($board == 'notice') {
$fileId = (int)$_GET['file_id'];
$q = $conn->prepare("SELECT original_name, stored_name FROM tb_notice_file WHERE file_id = ?");
$q->bind_param("i", $fileId);
$q->execute();
$res = $q->get_result();
if (!$row = $res->fetch_assoc()) {
    http_response_code(404); exit('파일 없음');
}

$uploadDir  = __DIR__ . '/../../data/';
$path = $uploadDir . $row['stored_name'];

if (!is_file($path)) { http_response_code(404); exit('파일 없음'); }

$orig = $row['original_name'];
header('Content-Type: application/octet-stream');
header('Content-Length: ' . filesize($path));
header('Content-Disposition: attachment; filename="' . rawurlencode($orig) . '"; filename*=UTF-8\'\'' . rawurlencode($orig));
header('X-Content-Type-Options: nosniff');

readfile($path);
exit;
} else if($board == 'board') {
$fileId = (int)$_GET['file_id'];
$q = $conn->prepare("SELECT original_name, stored_name FROM tb_bulletin_file WHERE file_id = ?");
$q->bind_param("i", $fileId);
$q->execute();
$res = $q->get_result();
if (!$row = $res->fetch_assoc()) {
    http_response_code(404); exit('파일 없음');
}

$uploadDir  = __DIR__ . '/../../b_data/';
$path = $uploadDir . $row['stored_name'];

if (!is_file($path)) { http_response_code(404); exit('파일 없음'); }

$orig = $row['original_name'];
header('Content-Type: application/octet-stream');
header('Content-Length: ' . filesize($path));
header('Content-Disposition: attachment; filename="' . rawurlencode($orig) . '"; filename*=UTF-8\'\'' . rawurlencode($orig));
header('X-Content-Type-Options: nosniff');

readfile($path);
exit;
} else {
        header('Location: ../error.html');
}
?>