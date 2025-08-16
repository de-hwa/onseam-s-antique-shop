<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>SIGN UP</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/style.css">
  <script>
    function qs(id){return document.getElementById(id)}

    function join_check(e){
      const userid = qs("f_id");
      const userpw = qs("f_pw");
      const username = qs("f_name");
      const usermail = qs("f_mail");

      const errId = qs("err_id");
      const errPw = qs("err_pw");
      const errName = qs("err_name");
      const errMail = qs("err_mail");

      errId.textContent = "";
      errPw.textContent = "";
      errName.textContent = "";
      errMail.textContent = "";

      let ok = true;

      const idPattern = /^[a-zA-Z0-9._-]{3,32}$/;
      const namePattern = /^[가-힣]{2,5}$/;

      if (!userid.value){
        errId.textContent = "아이디를 입력하세요.";
        userid.focus(); ok = false;
      } else if (!idPattern.test(userid.value)){
        errId.textContent = "아이디는 3~32자, 영문/숫자/._-만 허용됩니다.";
        userid.focus(); ok = false;
      }

      if (!userpw.value){
        errPw.textContent = "비밀번호를 입력하세요.";
        if (ok) userpw.focus();
        ok = false;
      } else if (userpw.value.length < 8){
        errPw.textContent = "비밀번호는 8자 이상이어야 합니다.";
        if (ok) userpw.focus();
        ok = false;
      }

      if (!username.value){
        errName.textContent = "이름을 입력하세요.";
        if (ok) username.focus();
        ok = false;
      } else if (!namePattern.test(username.value)){
        errName.textContent = "이름은 한글 2~5글자로 입력하세요.";
        if (ok) username.focus();
        ok = false;
      }

      if (!usermail.value){
        errMail.textContent = "이메일을 입력하세요.";
        if (ok) usermail.focus();
        ok = false;
      }

      if (!ok){
        e.preventDefault();
        return false;
      }

      const submitBtn = qs("submit_btn");
      submitBtn.disabled = true;
      submitBtn.value = "회원가입 중...";
      return true;
    }

    function togglePw(){
      const pw = qs("f_pw");
      const t  = qs("toggle_pw");
      const now = pw.getAttribute("type")==="password" ? "text" : "password";
      pw.setAttribute("type", now);
      t.textContent = (now==="password" ? "보기" : "숨기기");
    }
  </script>
</head>

<body>
<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];
?>
<h2>SIGN UP</h2>
<form class="login" action="../member/join_proc.php" method="POST" name="join_form" id="join_form" onsubmit="return join_check(event)" novalidate>
  <div class="row">
    <label for="f_id">ID</label>
    <input type="text" id="f_id" name="f_id" autocomplete="username" inputmode="latin" spellcheck="false" minlength="3" maxlength="32" required>
    <p class="err" id="err_id"></p>
  </div>

  <div class="row pw-wrap">
    <label for="f_pw">PW</label>
    <input type="password" id="f_pw" name="f_pw" autocomplete="new-password" minlength="8" required>
    <button class="toggle-pw" type="button" id="toggle_pw" onclick="togglePw()">보기</button>
    <p class="err" id="err_pw"></p>
  </div>

  <div class="row">
    <label for="f_name">NAME</label>
    <input type="text" id="f_name" name="f_name" autocomplete="name" pattern="^[가-힣]{2,5}$" title="이름은 한글 2~5글자로 입력하세요" required>
    <p class="err" id="err_name"></p>
  </div>

  <div class="row">
    <label for="f_mail">EMAIL</label>
    <input type="email" id="f_mail" name="f_mail" autocomplete="email" required>
    <p class="err" id="err_mail"></p>
  </div>

  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">

  <div class="actions">
    <span></span>
    <input class="btn" type="submit" id="submit_btn" value="SIGN UP">
    <a href="../main/index.php">메인으로</a>
  </div>
</form>
</body>
</html>