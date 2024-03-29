<?php
require_once("connection.php");

include 'sweetAlert.php';

//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
   
   
   if(isset($_POST['reguser'])){
    
        
         $user_name = mysqli_real_escape_string($conn,$_POST['name']);
         $email_address = mysqli_real_escape_string($conn,$_POST['admin_user']);
          $password =  mysqli_real_escape_string($conn,$_POST['user_password']);
         $user_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT, array('cost' => 12));  //PASSWORD_ARGON2I//PASSWORD_ARGON2ID
         $user_status = mysqli_real_escape_string($conn,$_POST['user_status']);
         $designation = mysqli_real_escape_string($conn,$_POST['designation']);

	$q_checkadmin = $conn->query("SELECT * FROM `login_user` WHERE `email_address` = '$email_address'") or die(mysqli_error($conn));
		$v_checkadmin = $q_checkadmin->num_rows;
		if($v_checkadmin == 1){
   echo "<script type='text/javascript'>Swal.fire({
  icon: 'error',
  title: 'Error Creating Account!!',
  text:'The Inputted User Email Account is Already Registered!!',
  confirmButtonColor:'#00396D',
   confirmButtonText: 'Okay',
   timer:3000

}).then(function(){
          window.history.back();
          })
         </script>";
		}else{
       $status = 'User';
      $query = mysqli_query($conn, "INSERT INTO newlycreatedAdmin(email, status) VALUES ('$email_address', '$status')");
    if(!$query){
        exit("Error");
    }

     $mail= new PHPMailer(true);
    try {
    //Server settings                   //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'cergorobao@rtu.edu.ph';                     //SMTP username
    $mail->Password   = '12345678';                               //SMTP password
    $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('cergorobao@rtu.edu.ph', 'RTU|DMS Newly Created Account');
    $mail->addAddress($email_address);     //Add a recipient        //Name is optional
    $mail->addReplyTo('no-reply@cergorobaortu.edu.ph', 'No Reply');

    $url="https://".$_SERVER["HTTP_HOST"].dirname($_SERVER["PHP_SELF"]). "/index.php";


   //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Your Login Credentials at RTU|DMS';
    $mail->Body    = "<h1>Your Account is Created in RTU|DMS</h1>
                      <h3>Hi $user_name,</h3>
                      Your Email Account is Sucessfully Registered at RTU|DMS.<br>
                      <h3>The Login Credentials of Your Account:</h3>
                      <b>Email: </b>$email_address<br>
                      <b>Password: </b>$password<br>
                      <b>Status: </b>User<br><br>

                      Access Link Via:<br>
                      <a href='$url'>$url</a><br><br>

              
                      Please Changed Your Default Password When you First Login<br>
                      If you need help, please contact the site administration.<br><br>   
                      <b>- RTU|DMS Team</b>" ;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->send();


			$conn->query("INSERT INTO `login_user` VALUES('0','$user_name', '$email_address', '$user_password', '$user_status', '$designation')") or die(mysqli_error($conn));
            echo "<script type='text/javascript'>Swal.fire({
  icon: 'success',
  title: 'Succesfully Created!!',
  text:'User Account is Successfully Created, The Login Credentials is Sent to the Inputted Email.',
  confirmButtonColor:'#00396D',
   confirmButtonText: 'Okay',
   timer:3000
}).then(function(){
          window.history.back();
          })
         </script>";
		}catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
} 
  } 
}



 ?>