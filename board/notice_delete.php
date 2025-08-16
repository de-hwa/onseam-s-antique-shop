<?php
include "../include/db_connect.php";

$no = $_POST['no'];
$sql = "delete from tb_notice where no=$no";
$result = mysqli_query($conn, $sql);
echo "<script>alert('삭제되었습니다.');
        location.href='../board/notice_list.php';
        </script>";
?>