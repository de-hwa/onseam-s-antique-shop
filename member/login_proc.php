<?php
include "../include/db_connect.php";
session_start();

$f_id = urldecode($_POST['f_id']);
$f_pw = urldecode($_POST['f_pw']);

// Error Based SQL Injection 방지
if(preg_match('/UNION\s+SELECT/i',$f_id)) {
                  echo "<script>
               alert(\"해당 문구는 사용하실 수 없습니다.\");
               location.href=\"../member/login_form.php\";
             </script>";
                exit;
}
if(preg_match('/UNION\s+SELECT/i',$f_pw)) {
                  echo "<script>
               alert(\"해당 문구는 사용하실 수 없습니다.\");
               location.href=\"../member/login_form.php\";
             </script>";
                exit;
}
// SQL Injection 방지
if (!preg_match('/^[A-Za-z0-9._]+$/', $f_id)) {
    echo "<script>
        alert(\"아이디는 영문, 숫자, '.', '_' 만 사용할 수 있습니다.\");
        location.href=\"../member/login_form.php\";
    </script>";
    exit;
}

if (strlen($f_pw) < 8 || strlen($f_pw) > 64) {
    echo "<script>
        alert(\"비밀번호는 8자 이상 64자 이하로 입력해야 합니다.\");
        location.href=\"../member/login_form.php\";
    </script>";
    exit;
}


$sql = "SELECT * from users_info where id='$f_id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if(!$row) {
        echo "<script>
                alert(\"아이디 혹은 비밀번호가 일치하지 않습니다.\");
                history.go(-1);
              </script>";
} else {
        if (password_verify($f_pw, $row['pw'])) {
        $_SESSION['s_id'] = $row['id'];
        $_SESSION['s_name'] = $row['username'];
        $_SESSION['s_mail'] = $row['email'];
        $_SESSION['s_role'] = $row['role'];
        echo "<script>
                alert(\"로그인되었습니다.\");
                location.href=\"../main/index.php\";
              </script>";
        } else {
        echo "<script>
                alert(\"아이디 혹은 비밀번호가 일치하지 않습니다.\");
                history.go(-1);
              </script>";
        }
}
?>
