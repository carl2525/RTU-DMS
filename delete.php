<?php
session_start();
 require_once("connection.php");
include 'sweetAlert.php';

$id = mysqli_real_escape_string($conn,$_GET['ID']);

$q = mysqli_query($conn,"SELECT * FROM `upload_files` WHERE id = '$id'") or die (mysqli_error($conn));
 
 $rs1 = mysqli_fetch_array($q);
               $fname1=$rs1['NAME'];
               $dept = $rs1['DEPARTMENT'];


 unlink("../uploads/".$dept."/".$fname1);



mysqli_query($conn,"DELETE FROM upload_files WHERE ID='$id'")or die(mysql_error());
mysqli_query($conn,"DELETE FROM download_log WHERE ID='$id'")or die(mysql_error());
mysqli_query($conn,"DELETE FROM upload_log WHERE ID='$id'")or die(mysql_error());
			echo "<script type='text/javascript'>Swal.fire({
  icon: 'success',
  title: 'Deleted!',
  text:'Your File Has Been Deleted.',
  confirmButtonColor:'#00396D',
   confirmButtonText: 'Okay',
   timer:3000

}).then(function(){
          window.history.back();
          })
         </script>";
?>

