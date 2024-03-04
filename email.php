
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
include("./DBCONFIG.PHP");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function send_password_reset($fname, $lname,$getemail,$token)
{
        $mail = new PHPMailer(true);
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'timekeepingweb@gmail.com';                     //SMTP username
        $mail->Password   = 'nqrk mkkm sxll kpwy';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('timekeepingweb@gmail.com', 'Set your new password!');
        $mail->addAddress($getemail);     //Add a recipient
    
        //Optional name
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Password Change';
       
        $mail->AltBody = 'hello';

        $mail->Body    = '<html>
        <!doctype html>
        <html lang="en-US">
        
        <head>
            <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
            <title>Reset Password Email Template</title>
            <meta name="description" content="Reset Password Email Template.">
            <style type="text/css">
                a:hover {text-decoration: underline !important;}
            </style>
        </head>
        
        <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
            <!--100% body table-->
            <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
                style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: "Open Sans", sans-serif;">
                <tr>
                    <td>
                        <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                            align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="height:80px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">
                                  <a href="login.php" title="logo" target="_blank">
                                   
                                  </a>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:20px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                        style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:0 35px;">
                                                <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:"Rubik",sans-serif;">You have
                                                    requested to reset your password</h1>
                                                <span
                                                
                                                    style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                                <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                                    We cannot simply send you your old password. A unique link to reset your
                                                    password has been generated for you. 
                                                    
                                                    If this request did not come from you, change your account password immediately to prevent further unauthorized access.
                                                </p>
                                                
                                                <a href="http://localhost/thesis/thesis/passwordchange.php?token='.$token.'&email='.$getemail.'"style="background:#20e277;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">Reset
                                                Password </a>
                                                
                                                    
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            <tr>
                                <td style="height:20px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">
                                    <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy; <strong>WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM</strong></p>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:80px;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
         
        </body>
            
        </html>
        <style>
        .cool-button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            transition-duration: 0.4s;
            cursor: pointer;
            border-radius: 50px;
          }
    
          .cool-button:hover {
            background-color: #3e8e41;
          }

        </style>
        
        <html> ';

        
        
        
        // "This is the HTML message body <b>in bold!</b>
        // <a href='http://localhost/thesisgithub/thesis-1/passwordchange.php?token=$token&email=$getemail'> click me</a>";
        
        $mail->send();
        echo 'Message has been sent';

}


if(isset($_POST['recover']))
{
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $token = md5(rand());

    $check_email = "SELECT * FROM employees WHERE email='$email' LIMIT 1";
    $check_email_run = mysqli_query ($conn, $check_email);

    if(mysqli_num_rows($check_email_run)>0)
    {
        $row = mysqli_fetch_array($check_email_run);
        $lname = $row['user_name'];
        $fname = $row['first_name'];
        $getemail = $row['email'];
        // $token = $row['last_name']

        $update_token= "UPDATE employees SET verify_token='$token' WHERE email ='$getemail'";
        $update_token_run = mysqli_query($conn, $update_token);

        if($update_token_run)
        {
            send_password_reset($fname, $lname,$email,$token);
            header('Location:login.php');
        }
        else{
            //something went wrong go back to adminlogin
            echo "<script>alert('something went wrong go back to adminlogin');</script>";
        }

    }else{
        //something went wrong go back to adminlogin
        echo "<script>alert('something went wrong go back to adminlogin');</script>";

    }
}



if(isset($_POST['passwordupdate'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $newpassword = mysqli_real_escape_string($conn, $_POST['newpassword']);
    $confirmpassword = mysqli_real_escape_string($conn, $_POST['confirmpassword']);
    $token = mysqli_real_escape_string($conn, $_POST['passtoken']);

    if(!empty($token))
    {
        if(!empty($email) && !empty($newpassword) && !empty($confirmpassword))
        {
            $check_token="SELECT verify_token FROM employees WHERE verify_token='$token' LIMIT 1";
            $check_token_run = mysqli_query($conn, $check_token);

            if(mysqli_num_rows($check_token_run)>0)
            {
                if($newpassword==$confirmpassword)
                {
                    $updatepassword="UPDATE employees SET pass_word='$newpassword' WHERE verify_token='$token' LIMIT 1";
                    $updatepasswordrun = mysqli_query($conn, $updatepassword);
                    if($updatepasswordrun)
                    {
                        //passwordupdate
                        header('Location:login.php');

                    }else{
                        //passwordupdatefailed
                        echo "<script>alert('failed');</script>";
                    }

                }else{
                    //passwords do notmatch
                    echo "<script>alert('pw dnt match');</script>";
                }

            }else{
                //invalidtoken
                echo "<script>alert('invalid token');</script>";
            }

        }else{
            //no token available
            echo "<script>alert('No token available');</script>";
        }

    }else{
        echo "<script>alert('token empty');</script>";
    }

}


//variables

// $email = $_POST["email"];
// //Import PHPMailer classes into the global namespace
// //These must be at the top of your script, not inside a function
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

// //Load Composer's autoloader
// require 'vendor/autoload.php';

// //Create an instance; passing `true` enables exceptions
// $mail = new PHPMailer(true);

// try {
//     //Server settings
     
//     $mail->isSMTP();                                            //Send using SMTP
//     $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
//     $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
//     $mail->Username   = 'timekeepingweb@gmail.com';                     //SMTP username
//     $mail->Password   = 'nqrk mkkm sxll kpwy';                               //SMTP password
//     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
//     $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

//     //Recipients
//     $mail->setFrom('timekeepingweb@gmail.com', 'Mailer');
//     $mail->addAddress($email);     //Add a recipient

//     //Optional name

//     //Content
//     $mail->isHTML(true);                                  //Set email format to HTML
//     $mail->Subject = 'Password Change';
//     $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
//     $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

//     $mail->send();
//     echo 'Message has been sent';
// } catch (Exception $e) {
//     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
// }

