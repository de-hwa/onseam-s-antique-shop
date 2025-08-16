<head>
<link rel="stylesheet" href="../css/style.css">
  <script>
    function qs(id){return document.getElementById(id)}

    function login_check(e) {
      const userid = qs("f_id");
      const userpw = qs("f_pw");
      const errId  = qs("err_id");
      const errPw  = qs("err_pw");
      let ok = true;

      errId.textContent = "";
      errPw.textContent = "";

      const idPattern = /^[a-zA-Z0-9._-]{3,32}$/;

      if (!userid.value) {
        errId.textContent = "아이디를 입력하세요.";
        userid.focus(); ok = false;
      } else if (!idPattern.test(userid.value)) {
        errId.textContent = "아이디는 3~32자, 영문/숫자/._-만 허용됩니다.";
        userid.focus(); ok = false;
      }

      if (!userpw.value) {
        errPw.textContent = "비밀번호를 입력하세요.";
        if (ok) userpw.focus();
        ok = false;
      } else if (userpw.value.length < 8) {
        errPw.textContent = "비밀번호는 8자 이상이어야 합니다.";
        if (ok) userpw.focus();
        ok = false;
      }

      if (!ok) {
        e.preventDefault();
        return false;
      }

      const submitBtn = qs("submit_btn");
      submitBtn.disabled = true;
      submitBtn.value = "로그인 중...";
      return true;
    }

    function togglePw() {
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
if($_GET['login'] == 1){
        echo "<script>alert(\"로그인 후 이용해 주세요.\")</script>";
}
session_start();

if(isset($_SESSION['s_name'])) {
  echo "<script>
          alert('이미 로그인 하셨습니다.');
          location.href = '../main/index.php';
        </script>";
  exit;
}

// CSRF 토큰 생성
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];
?>
<form class="login" action="login_proc.php" method="POST" name="login_form" onsubmit="return login_check(event)" novalidate>
  <div class="row">
    <label for="f_id">ID</label>
    <input type="text" name="f_id" id="f_id"
           autocomplete="username" inputmode="latin" spellcheck="false"
           minlength="3" maxlength="32" required>
    <p class="err" id="err_id"></p>
  </div>

  <div class="row pw-wrap">
    <label for="f_pw">PW</label>
    <input type="password" name="f_pw" id="f_pw"
           autocomplete="current-password" minlength="8" required>
    <button class="toggle-pw" type="button" id="toggle_pw" onclick="togglePw()">보기</button>
    <p class="err" id="err_pw"></p>
  </div>

  <!-- CSRF -->
  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">

  <div class="actions">
    <input class="btn" type="submit" id="submit_btn" value="LOGIN">
  </div>

  <div class="links">
    <a href="../main/index.php">메인으로</a>
    <a href="../member/join_form.php">회원가입</a>
  </div>
</form>
</body>