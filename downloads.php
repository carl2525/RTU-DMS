<?php 
session_start();

require_once("connection.php");

if (isset($_GET['file_id'])) {
    $id = mysqli_real_escape_string($conn,$_GET['file_id']);

    // fetch file to download from database
    $sql = "SELECT * FROM  upload_files WHERE ID=$id";
    $result = mysqli_query($conn, $sql);

    $file = mysqli_fetch_assoc($result);
    $filepath = '../uploads/'.$file['DEPARTMENT'].'/'. $file['NAME'];

    
    if (file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filepath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('../uploads/'.$file['DEPARTMENT'].'/'. $file['NAME']));
        readfile('../uploads/'.$file['DEPARTMENT'].'/'. $file['NAME']);

        $download = $file['DOWNLOAD'];

        if ($download <1){
         date_default_timezone_set("asia/manila");
         $time = date("Y/m/d H:i:s",strtotime("+0 HOURS"));
            $conn->query("UPDATE `upload_files` SET `TIMEDOWNLOAD` = ' $time' WHERE ID =$id") or die(mysqli_error());
        }
        
        // Now update downloads count
        $newCount = $file['DOWNLOAD'] + 1;
        $name = $file['NAME'];
        $dep = $file['DEPARTMENT'];
        $size = $file['SIZE'];
        date_default_timezone_set("asia/manila");
        $month = date("F",strtotime("+0 HOURS"));
        $year = date("Y",strtotime("+0 HOURS"));
        $time = date("h:i:s A",strtotime("+0 HOURS"));
        $email = $file["EMAIL"];

        $updateQueryDownload = "INSERT INTO download_log (NAME, DEPARTMENT, SIZE, DOWNLOAD, MONTH, TIME, YEAR, ACC_STATUS, EMAIL) 
        VALUES ('$name', '$dep', '$size', '1', '$month', '$time', '$year', 'Admin', '$email')";
        
        
        
        $updateQuery = "UPDATE upload_files SET DOWNLOAD=$newCount WHERE ID=$id";

        
        
        mysqli_query($conn, $updateQuery);
        mysqli_query($conn, $updateQueryDownload);

    
        exit;
        
    } else {
        echo "Failed";
    }

}


        


     

  


?>


