<?php
include "../include/db_connect.php";
session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (!isset($_SESSION['s_name'])) {
        echo "<script>alert('로그인 후 이용해 주세요.');
        location.href='../member/login_form.php';
        </script>";
        exit;
}

$no = $_POST['no'];

$stmt = $conn->prepare("select * from tb_bulletin where no = ?");
$stmt->bind_param("i",$no);
$stmt->execute();
$res = $stmt->get_result();
$rows = $res->num_rows;
$row = $res->fetch_assoc();

if($rows == 1) {
        if($_SESSION['s_name'] == $row['registerer']){
                $stmt = $conn->prepare("delete from tb_bulletin where no = ?");
                $stmt->bind_param("i",$no);
                $stmt->execute();
                echo "<script>
                        alert('삭제되었습니다.');
                        location.href='../board/board_list.php';
                        </script>";
                exit;
        } else {
        echo "<script>
                        alert('권한이 없습니다.');
                        location.href='../board/board_list.php';
                        </script>";
                exit;
        }
} else {
        echo "<script>
                        alert('잘못된 접근입니다.');
                        location.href='../board/board_list.php';
                        </script>";
        exit;
}
?>
