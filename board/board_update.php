<?php
include "../include/db_connect.php";

session_start();
$no = $_POST['no'];
$content = $_POST['content'];
$subject = $_POST['subject'];

$s_name = $_SESSION['s_name'] ?? '';

$sql = "select * from tb_bulletin where no=$no";
$result = mysqli_query($conn, $sql);

if(!$result) {
        header('Location: ../access.html');
        exit;
}

$row = mysqli_fetch_assoc($result);
$registerer = $row['registerer'];

if($s_name == $registerer) {
        $sqlu = "update tb_bulletin set subject='$subject', content='$content' where no=$no";
        $resultu = mysqli_query($conn, $sqlu);
#       header('Location: ../board/board_list.php');
              echo "<script>alert('수정되었습니다.');location.href='../board/board_view.php?no=".$no."';</script>";
} else {
       header('Location: ../error.html');
       exit;
}
?>