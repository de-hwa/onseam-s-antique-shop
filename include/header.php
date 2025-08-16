<head>
  <link rel="stylesheet" href="../css/style.css">
</head>

<header class="site-header">
  <h1 class="site-title">온새미로의 고물상</h1>

  <?php
  session_start();
  if (!isset($_SESSION['s_id'])) {
  ?>
    <nav>
      <ul class="main-menu">
        <li><a href="../member/login_form.php">Login</a></li>
        <li><a href="../member/join_form.php">Sign Up</a></li>
        <li><a href="../board/notice_list.php">Notice</a></li>
        <li><a href="../board/board_list.php">Review</a></li>
        <li><a href="../board/qna_list.php">Q&amp;A</a></li>
      </ul>
    </nav>
  <?php
  } else {
    echo '<div class="welcome-wrap"><p class="welcome-msg">'
        . htmlspecialchars($_SESSION["s_name"], ENT_QUOTES, "UTF-8")
        . '님, 환영합니다.</p></div>';
  ?>
    <nav>
      <ul class="main-menu">
        <li><a href="../member/logout.php">Logout</a></li>
        <li><a href="../member/join_form.php">Sign Up</a></li>
        <li><a href="../board/notice_list.php">Notice</a></li>
        <li><a href="../board/board_list.php">Review</a></li>
        <li><a href="../board/qna_list.php">Q&amp;A</a></li>
      </ul>
    </nav>
  <?php } ?>
</header>