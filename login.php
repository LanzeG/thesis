<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


</head>
<body>
<style>
    .title1{
            padding-top: 8rem;
    
    }
</style>

<style>
.form-control {
  margin: 20px;
  background-color: #ffffff;
  box-shadow: 0 15px 25px rgba(0, 0, 0, 0.6);
  width: 400px;
  display: flex;
  justify-content: center;
  flex-direction: column;
  gap: 10px;
  padding: 25px;
  border-radius: 8px;
}
.title {
  font-size: 28px;
  font-weight: 800;
}
.input-field {
  position: relative;
  width: 100%;
}

.input {
  margin-top: 15px;
  width: 100%;
  outline: none;
  border-radius: 8px;
  height: 45px;
  border: 1.5px solid #ecedec;
  background: transparent;
  padding-left: 10px;
}
.input:focus {
  border: 1.5px solid #2d79f3;
}
.input-field .label {
  position: absolute;
  top: 25px;
  left: 15px;
  color: #ccc;
  transition: all 0.3s ease;
  pointer-events: none;
  z-index: 2;
}
.input-field .input:focus ~ .label,
.input-field .input:valid ~ .label {
  top: 5px;
  left: 5px;
  font-size: 12px;
  color: #2d79f3;
  background-color: #ffffff;
  padding-left: 5px;
  padding-right: 5px;
}
.submit-btn {
  margin-top: 30px;
  height: 55px;
  background: #f2f2f2;
  border-radius: 11px;
  border: 0;
  outline: none;
  color: #ffffff;
  background:  #4723D9;
  font-size: 18px;
  font-weight: 700;
  /* background: linear-gradient(180deg, #363636 0%, #1b1b1b 50%, #000000 100%); */
  box-shadow: 0px 0px 0px 0px #ffffff, 0px 0px 0px 0px #000000;
  transition: all 0.3s cubic-bezier(0.15, 0.83, 0.66, 1);
  cursor: pointer;
}

.submit-btn:hover {
  box-shadow: 0px 0px 0px 2px #ffffff, 0px 0px 0px 4px #0000003a;
}

    </style>
<div class="container">
    <div class="title1 text-center">
   <h2>WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM </h2>    
   <h2>
   USING FINGERPRINT BIOMETRICS 
   </h2>
    </div>
    
<div class="loginform d-flex justify-content-center">

<form class="form-control " method = "post" id="loginform" action="LoginControl.php" action="">
<?php
                            if (isset($_SESSION['status'])) {
                            echo $_SESSION['status'];
                            unset($_SESSION['status']);
                            }
                            ?>

  <p class="title text-center">Login</p>
  <div class="input-field">
    <input required="" class="input" type="text" id="admID" name="adminUser" autofocus="autofocus" />
    <label class="label" for="input " >Enter Username</label>
  </div>
  <div class="input-field">
    <input  required="" class="input" type="password" id="admPASS" name="adminPass" />
    <label class="label" for="input">Enter Password</label>
  </div>

  <div class="form-actions">
    
  </div>
  <a id="to-recover" href="#"class="flip-link btn-inverse">Forgot your password?</a>
  <button class="submit-btn" name="login_btn"> <i class="fa-solid fa-right-to-bracket"></i> Log In</button>
</form>



<form id="recoverform" action="email.php" class="form-control" method="POST"
                style="display: none;">
                <p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p>
				
                <div class="controls">
                    <div class="main_input_box">
                        <span class="add-on "><i class="icon-envelope"></i></span><input type="text" placeholder="E-mail address"  class="input" name="email" required=""/>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="text-center">
                         <span class="pull-right"><input type="submit" class="submit-btn"  value="Recover" name="recover" /></div>
                   <div class="text-center">
                   <span class="pull-left">
                    <a href="#" class="flip-link btn btn-inverse"id="to-login"><i class="fa-solid fa-arrow-left"></i> Back to login</a></span>
                   </div>
                
                </span>
                </div>
            </form>

</div>
    
</div>

<script src="js/jquery.min.js"></script>  
        <script src="js/maruti.login.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>