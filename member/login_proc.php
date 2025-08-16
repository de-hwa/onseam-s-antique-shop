<?php
include "../include/db_connect.php";
session_start();

$f_id = $_POST['f_id'];
$f_pw = $_POST['f_pw'];

# ASM!!!!!!!!
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
