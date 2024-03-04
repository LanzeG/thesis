<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>Change Password</title><meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!-- <link rel="stylesheet" href="css/bootstrap.min.css" /> -->
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
        <!-- <link rel="stylesheet" href="css/maruti-login.css" /> -->
</head>


    <body>
    <?php include('navbar2.php'); ?> 
    <div class="content">
    <div id="content">
    <div class="title d-flex justify-content-center  pt-4">
        <h3>Change Password</h3>
  
    </div>
    <hr>
        <center><br>
            <h1></h1>
            <h2><small></small></h2>

            <br>
        <div id="loginbox">            
            <div class="widget-content nopadding col-3 card shadow mx-auto my-5 p-3 mt-5">
            <form method = "post" id="loginform" class="form-vertical" action="email.php">
				 <div class="control-group normal_text"> <h5><b>RESET FOR NEW PASSWORD</b></h5></div>
                 <hr>


            

            
            <!-- <form id="recoverform" action="email.php" class="form-vertical" method="POST"> -->
				<!-- <p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p> -->
				
                    <div class="controls">
                        <div class="main_input_box col-6">
                            <input type="hidden" name="passtoken" value="<?php if(isset($_GET['token'])){echo $_GET['token'];}?>">
                            <input type="hidden" placeholder="E-mail address" name="email" value="<?php if(isset($_GET['email'])){echo $_GET['email'];}?>"/>
                            <span class="add-on"><i class="icon-lock"></i></span><input type="password" class="form-control" placeholder="Password" name="newpassword"/>
                            <br>
                            <span class="add-on"><i class="icon-lock"></i></span><input type="password" class="form-control " placeholder="Confirm Password" name="confirmpassword"/>
                            
                        </div>
                    </div>
               
                <div class="form-actions pt-5">
                   
                    <span class="pull-right"><input type="submit" class="btn btn-success" value="Recover" name="passwordupdate" /></span>
                    <span class="pull-left"><a href="login.php" class="flip-link btn btn-danger" id="to-login"> Back to login</a></span>
                </div>
            </form>
         
            </div>
        </div>
        
        <script src="js/jquery.min.js"></script>  
        <script src="js/maruti.login.js"></script> 
    </body>

</html>