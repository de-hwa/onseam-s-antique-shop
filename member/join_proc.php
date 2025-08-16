<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();
include "../include/db_connect.php";
mysqli_set_charset($conn, 'utf8mb4');

// 공용 실패 응답
function fail(string $msg, int $code = 400): void {
  http_response_code($code);
  echo "<script>alert(".json_encode($msg, JSON_UNESCAPED_UNICODE).");history.go(-1);</script>";
  exit;
}
#function fail(string $msg, int $code = 400): void {
#  http_response_code($code);
#  echo "<script>alert('$msg');history.back();</script>";
#  exit;
#}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  fail('Invalid request', 405);
}

$f_id   = trim($_POST['f_id']   ?? '');
$f_pw   = $_POST['f_pw']        ?? '';
$f_name = trim($_POST['f_name'] ?? '');
$f_mail = trim($_POST['f_mail'] ?? '');

// 서버측 유효성 검사
// ID: 3~32자, 영/숫자/._-
if (!preg_match('/^[A-Za-z0-9._-]{3,32}$/', $f_id)) {
  fail('아이디는 3~32자, 영문/숫자/._-만 허용됩니다.');
}

// 이름: 한글 2~5자
if (!preg_match('/^\p{Hangul}{2,5}$/u', $f_name)) {
  fail('이름은 한글 2~5글자로 입력하세요.');
}

// 이메일
if (!filter_var($f_mail, FILTER_VALIDATE_EMAIL)) {
  fail('유효한 이메일을 입력하세요.');
}

// 비밀번호 복잡성
$len = strlen($f_pw);
if ($len < 8 || $len > 50) {
  fail('비밀번호는 8~50자여야 합니다.');
}
if (preg_match('/(.)\1{2,}/u', $f_pw)) {
  fail('비밀번호에 같은 문자를 3번 이상 연속 사용할 수 없습니다.');
}
if (preg_match('/(abcd|1234|5678|7890|qwer|asdf|zxcv)/i', $f_pw)) {
  fail('너무 쉬운 비밀번호입니다.');
}

$factor = 0;
$factor += preg_match('/[A-Z]/', $f_pw) ? 1 : 0;
$factor += preg_match('/[a-z]/', $f_pw) ? 1 : 0;
$factor += preg_match('/\d/',    $f_pw) ? 1 : 0;
$factor += preg_match('/[^A-Za-z0-9]/', $f_pw) ? 1 : 0;
if ($factor < 3) {
  fail('비밀번호는 대문자/소문자/숫자/특수문자 중 최소 3종류를 포함해야 합니다.');
}

// ID 중복 체크
$sql = "select * from users_info where id = '$f_id'";
$result = mysqli_query($conn, $sql);
if (!$conn || !$result) fail('Internal Server Error', 500);
$row = mysqli_fetch_row($result);
if ($row) {
  fail('이미 존재하는 ID 입니다.');
}

// 닉네임 중복 체크
$sql = "select * from users_info where username = '$f_name'";
$result = mysqli_query($conn, $sql);
if (!$conn || !$result) fail('Internal Server Error', 500);
$row = mysqli_fetch_row($result);
if ($row) {
  fail('중복되는 닉네임입니다.');
}

// 이메일 중복 체크
$f_email = $row['email'] ?? '';
$sql = "select * from users_info where email = '$f_email'";
$result = mysqli_query($conn, $sql);
if (!$conn || !$result) fail('Internal Server Error', 500);
$row = mysqli_fetch_row($result);
if ($row) {
  fail('중복되는 이메일입니다.');
}

// 비밀번호 해싱
$hashed = password_hash($f_pw, PASSWORD_DEFAULT);

// 저장
$sql2 = "INSERT INTO users_info (id, pw, username, email, role) VALUES ('$f_id', '$hashed', '$f_name', '$f_mail', 'user')";
$result2 = mysqli_query($conn, $sql2);
if (!$result2) {
        fail('Internal Server Error', 500);
} else {
        http_response_code(200);
        echo "<script>alert(\"회원가입 되었습니다.\");
        location.href=\"../member/login_form.php\";</script>";
}

mysqli_close($conn);