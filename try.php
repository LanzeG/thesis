
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
include("DBCONFIG.PHP");
include("LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();

$uname = $_SESSION['uname'];
$empid = $_SESSION['empId'];


$getinfoqry = "SELECT * from employees WHERE user_name = '$uname'";
$getinfoexecqry = mysqli_query($conn,$getinfoqry) or die ("FAILED TO GET INFORMATION ".mysqli_error($conn));
$getinfoarray = mysqli_fetch_array($getinfoexecqry);
$getinforows = mysqli_num_rows($getinfoexecqry);
if ($getinfoarray && $getinforows !=0){

 $currprefixid = $getinfoarray['prefix_ID'];
 $currempid = $getinfoarray['emp_id'];
        //$currcardnumber = $getinfoarray['card_number'];
        $currfingerprintid = $getinfoarray['fingerprint_id'];
        $currusername = $getinfoarray['user_name'];
        $currlastname = $getinfoarray['last_name'];
        $currfirstname = $getinfoarray['first_name'];
        $currmiddlename = $getinfoarray['middle_name'];
        $currdateofbirth = $getinfoarray['date_of_birth'];
        $currposition = $getinfoarray['position'];
        $curremptype = $getinfoarray['employment_TYPE'];
        $curraddress = $getinfoarray['emp_address'];
        $currnationality = $getinfoarray['emp_nationality'];
        $currdeptname = $getinfoarray['dept_NAME'];
        $currshiftsched = $getinfoarray['shift_SCHEDULE'];
        $currcontact = $getinfoarray['contact_number'];
        $currdatehired = $getinfoarray['date_hired'];
        $currdateregularized = $getinfoarray['date_regularized'];
        $currdateresigned = $getinfoarray['date_resigned'];
        $currimg = $getinfoarray['img_tmp'];
$_SESSION['empID'] = $currempid;
}

$customQuery = "SELECT position.position_name, salarygrade.salarygrade
               FROM position
               JOIN salarygrade ON position.salarygrade = salarygrade.salarygrade
               WHERE position.position_name = '$currposition'";

// Execute the query
$customQueryResult = mysqli_query($conn, $customQuery) or die("FAILED TO GET INFORMATION " . mysqli_error($conn));
// Fetch the result
if (mysqli_num_rows($customQueryResult) > 0) {
  // Fetch the result
  $row = mysqli_fetch_assoc($customQueryResult);

  // Now you can use $row to access the data
  $positionName = $row['position_name'];
  $salaryGrade = $row['salarygrade'];

  // Do something with the data
  // echo "Position Name: $positionName, Salary Grade: $salaryGrade";
} else {
  // No result found
  $salaryGrade ='';
}




//  if ($_SERVER["REQUEST_METHOD"] == "POST") {
     if (isset($_POST['pperiod_btn1'])) {
         // Handle the case for the first "Go" button (redirect to empaction.php)
         $payfunction = $_POST['payfunction'];
        $payperiod = $_POST['payperiod'];
        $_SESSION['payperiods'] = $_POST['payperiod'];
        $_SESSION['payfunction'] = $_POST['payfunction'];

        // Redirect to empaction.php with the form data
        echo '<script>';
        echo 'var url = "employee/empaction.php?payfunction=' . urlencode($payfunction) . '&payperiod=' . urlencode($payperiod) . '";';
        echo 'window.open(url, "_blank");';
        echo '</script>';
      
     } elseif (isset($_POST['pperiod_btn'])){

      $payperiod = $_POST['payperiod'];
      $_SESSION['payperiods'] = $_POST['payperiod'];
      $searchquery = "SELECT * FROM employees, PAY_PER_PERIOD WHERE employees.emp_id = PAY_PER_PERIOD.emp_id AND PAY_PER_PERIOD.emp_id = '$empid' AND PAY_PER_PERIOD.pperiod_range = '$payperiod' ORDER BY pperiod_range";
      $search_result = filterTable($searchquery);
      // echo "<script>alert('hello')</script>";
      

    }  else  {
      $searchquery = "SELECT * from employees, PAY_PER_PERIOD WHERE employees.emp_id = PAY_PER_PERIOD.emp_id AND PAY_PER_PERIOD.emp_id = '$empid' ORDER BY PAY_PER_PERIOD.pperiod_range ";  
      $search_result = filterTable($searchquery);
      // $_SESSION['payperiods'] = 'noset';
      }
      if (isset($payperiod)) {
        $query = "SELECT * FROM payperiods WHERE pperiod_range = '$payperiod'";
        $result = mysqli_query($conn, $query);
        
       
        if ($result) {
          // Fetch the data from the result set
          $data = mysqli_fetch_assoc($result);
          $period_start = isset($data['pperiod_start']) ? $data['pperiod_start'] : null;
          $period_end = isset($data['pperiod_end']) ? $data['pperiod_end'] : null;
       
          $dateTime = new DateTime($period_start);
          $month = $dateTime->format('F'); // Full month name (e.g., January)
          $year = $dateTime->format('Y');  // 4-digit year (e.g., 2024)
       }
       
       $printquery = "SELECT * FROM DTR, employees WHERE DTR.emp_id = employees.emp_id and DTR.emp_id = '$empid' AND DTR.DTR_day BETWEEN '$period_start' and '$period_end' ORDER BY DTR_day ASC";
       $printqueryexec = mysqli_query($conn,$printquery);
       $printarray = mysqli_fetch_array($printqueryexec);
       $d = strtotime("now");
              $currtime = date ("Y-m-d H:i:s", $d);
       // $payperiod = $_SESSION['payperiodrange'];
       
       
       
       if ($printarray){
       
        $prefix = $printarray['prefix_ID'];
        $idno = $printarray['emp_id'];
        $lname = $printarray['last_name'];
        $fname = $printarray['first_name'];
        $mname = $printarray['middle_name'];
        $dept = $printarray['dept_NAME'];
        $position = $printarray['position'];
       
        $name = "$lname, $fname $mname";
        $empID = "$prefix$idno";
       }
       
       
       $payperiodval = "SELECT DTR.*,(TIME_KEEPING.hours_work+TIME_KEEPING.overtime_hours) as totalhours,TIME_KEEPING.hours_work,TIME_KEEPING.overtime_hours FROM DTR INNER JOIN TIME_KEEPING ON TIME_KEEPING.emp_id=DTR.emp_id AND TIME_KEEPING.timekeep_day=DTR.DTR_day WHERE DTR.emp_id = '$empid' AND DTR_day BETWEEN '$period_start' AND '$period_end' ORDER BY DTR_day ASC";
       $payperiodexec = mysqli_query($conn,$payperiodval) or die ("FAILED TO QUERY TIMEKEEP DETAILS ".mysqli_error($conn));
       
       $totalot = "SELECT SUM(undertime_hours) as totalUT, SUM(overtime_hours) as totalOT, SUM(hours_work) as totalWORKhours, SUM(late_hours) as totalLATEhours, SUM((hours_work+overtime_hours)-late_hours) as totalness FROM TIME_KEEPING WHERE emp_id = '$empid' AND timekeep_day BETWEEN '$period_start' and '$period_end' ORDER BY timekeep_day ASC";
       $totalotexec =mysqli_query($conn,$totalot) or die ("OT ERROR ".mysqli_error($conn));
       $totalotres = mysqli_fetch_array($totalotexec);
       
      $searchquery = "SELECT * from employees, PAY_PER_PERIOD WHERE employees.emp_id = PAY_PER_PERIOD.emp_id AND PAY_PER_PERIOD.emp_id = '$empid' ORDER BY PAY_PER_PERIOD.pperiod_range ";  
      $search_result = filterTable($searchquery);
         // $_SESSION['payperiods'] = 'noset';
       
       
       
         //for late and undertime bc i like it redundant
         $attquery = "SELECT
         emp_id,
         SUM(CASE WHEN in_morning > 0 THEN 1 ELSE 0 END) AS TOTAL_ATTENDANCE,
         SUM(CASE WHEN late_hours > 0 THEN 1 ELSE 0 END) AS TOTAL_LATE_HOURS,
         SUM(CASE WHEN undertime_hours > 0 THEN 1 ELSE 0 END) AS TOTAL_UNDERTIME_HOURS
       FROM
         time_keeping
       WHERE
         emp_id = $empid
         AND timekeep_day BETWEEN '$period_start' AND '$period_end'
       GROUP BY
         emp_id";
        
        $resultattquery = mysqli_query($conn, $attquery) or die(mysqli_error($conn));
        $rowattquery = mysqli_fetch_assoc($resultattquery);
       }
//  }  else{
//   $_SESSION['payperiods'] = 'noset';
//  }
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Employee Home</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../css/fullcalendar.css" />
<link rel="stylesheet" href="../css/maruti-style.css" />
<link rel="stylesheet" href="../css/maruti-media.css" class="skin-color" />
<link rel="stylesheet" href="../jquery-ui-1.12.1/jquery-ui.css">
<link rel="stylesheet" href="timeline.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<!-- Chartist.js -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chartist@0.11.4/dist/chartist.min.css">
<script src="https://cdn.jsdelivr.net/npm/chartist@0.11.4/dist/chartist.min.js"></script>

<script src="../jquery-ui-1.12.1/jquery-3.2.1.js"></script>
<script src="../jquery-ui-1.12.1/jquery-ui.js"></script>

<script type ="text/javascript">
  $( function() {
      $( "#datepickerfrom" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );
  $( function() {
      $( "#datepickerto" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );
  
</script>
<script>
  function toggleCollapse() {
    var content = document.getElementById("content1");
    content.classList.toggle("collapsed");

    // Store the collapse state in local storage
    localStorage.setItem("collapseState", content.classList.contains("collapsed"));
  }

  // Check local storage for the collapse state on page load
  window.onload = function() {
    var isCollapsed = localStorage.getItem("collapseState");

    // If the collapse state is true, toggle the collapse
    if (isCollapsed === "true") {
      toggleCollapse();
    }
  };
</script>
</head>
<body>
<style>
  #content1 {
    display: block;
}

#content1.collapsed {
    display: none;
}
</style>
<style>
  .card1 {
  box-sizing: border-box;

  background: rgba(217, 217, 217, 0.58);
  border: 1px solid white;
  box-shadow: 12px 17px 51px rgba(0, 0, 0, 0.22);
  backdrop-filter: blur(6px);
  border-radius: 20px;
  text-align: center;
  transition: all 0.5s;
  display: flex;
  align-items: center;
  justify-content: center;
  user-select: none;
  font-weight: bolder;
  color: black;
}

.card1:hover {
  border: 1px solid black;
  transform: scale(1.05);
}

.card1:active {
  transform: scale(0.95) rotateZ(1.7deg);
}
.title1{
    font-size: 18px;
    padding-top: 10px;
  }
  #hr
  {
    width: 60px;
  }
  .shadow
  {
    border-radius: 18px;
  }
  




@media (max-width: 767.98px) { 
  .info
{
text-align: center;
}
}

  
  
</style>

    <header>
    <?php include('navbar2.php'); ?> 

    </header>

 
    <div class="content">
    <div class="row">
        <!-- unang hati -->

          <div class="col-xl-8 col-lg border height-100vh ">
          <div class="content row d-flex">
      <!-- INFO 1 START -->

        <div class="card col-lg-12 shadow">
        <div class="row">

            <div class="img1 col-lg-6 pb-3 d-flex justify-content-center">
              <img class="img-fluid mt-3" height="200" width="200" src="data:image;base64,<?php echo $currimg?>" style="border-radius: 20%;">  
             </div>

            <div class="text info col-lg-6 col-md-12 mt-5">
              <h1>
                Hi! <?php $fullName = implode(' ', [$currfirstname, $currlastname]); echo $fullName; ?>
              </h1>
              <ul class="list-unstyled">
                <li>
                <span class="fw-semibold">Employee ID:</span><?php echo $currempid; ?>
                </li>
                <li>
                <span class="fw-semibold">Deparment:</span> <?php echo $currdeptname; ?>
                </li>
                <li>
                <span class="fw-semibold">Position:</span> <?php echo $currposition; ?>
                <li>
                <span class="fw-semibold">Status:</span> <?php echo $curremptype; ?>
                </li>
                <li>
                <div class="button mt-2">
  <div class = "uinfotab2"><a href ="empCHANGEPASS.php" class = "btn btn-success btn-sm"><span class="icon"><i class="icon-edit"></i> </span>Change Password</a></div>
  </div>
                </li>
              </ul>
            </div>
        </div>
        

    
        
        <!-- <div class="card-body">
        <h5><?php $fullName = implode(' ', [$currfirstname, $currlastname]); echo $fullName; ?></h3>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">Employee ID:<?php echo $currempid; ?></li>
    <li class="list-group-item">Department: <?php echo $currdeptname; ?></li>
    <li class="list-group-item">Salary Grade: Grade 6</li>
    <li class="list-group-item">Employment Status: ok lang</li>
  </ul> -->

</div>
<div class="card col-12 shadow mt-3">

<!-- <div class="card text-center">
<div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab">
                <li class="nav-item">
                    <a href="#home" class="nav-link active" data-bs-toggle="tab">OVERALL</a>
                </li>
                <li class="nav-item">
                    <a href="#profile" class="nav-link" data-bs-toggle="tab">TIME KEEP</a>
                </li>
                
            </ul>
        </div>
</div> -->

<div class="content">
<div class="row row-cols-1 row-cols-md-3 m-4">

<div class="card1 text-bg-info  col-4">
  <div class="h-100">
    <div class="title1">
      Total Attendance
      <hr>
    </div>
    <div class="card-body text-center">
      <h3><?php echo isset($rowattquery['TOTAL_ATTENDANCE']) ? $rowattquery['TOTAL_ATTENDANCE'] : 0; ?></h3>
    </div>
  </div>
</div>
<div class="card1 text-bg-info col-4">
  <div class=" h-100">
    <div class="title1">
      Total Late
      <hr>
    </div>
    <div class="card-body text-center">
      <h3><?php echo isset($rowattquery['TOTAL_LATE_HOURS']) ? $rowattquery['TOTAL_LATE_HOURS'] : 0; ?></h3>
    </div>
  </div>
</div>
   <div class="card1 text-bg-info col-4">
  <div class="h-100">
    <div class="title1">
      Total Undertime
      <hr>  
    </div>
    <div class="card-body text-center">
      <h3><?php echo isset($rowattquery['TOTAL_UNDERTIME_HOURS']) ? $rowattquery['TOTAL_UNDERTIME_HOURS'] : 0; ?></h3>
    </div>
  </div>
</div>
</div>
</div>
 

</div>

<div class="col-12 card shadow">
<div class="d-flex justify-content-center ">
  <a  class="btn btn-sm" id="collapseBtn" onclick="toggleCollapse()">Attendance Table  <i class="fa-solid fa-arrow-down"></i></a>

  </div>

  <div id="content1">
  <div class="content">
    <div class="row">
                            <div class="col-10">
                            <div class="table-responsive" >
              <table class="table table-bordered table-responsive table-striped " >
                <thead class="table-dark " >
                  <tr>
                    <th>DATE</th>
                     <th  >IN</th>
                    <th>OUT</th>
                    <th>Reg. Hours</th>
                    <th></th>
                    <th>IN</th>
                    <th>OUT</th>
                    <th>OT Hours</th>
                    <th>Daily Total</th>                 
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if(isset($payperiodexec)){
              while ($payperiodarray = mysqli_fetch_array($payperiodexec)) {
                  $dtrday = $payperiodarray['DTR_day'];
                  $day = date('d', strtotime($dtrday));
                  $hrswrk = $payperiodarray['hours_work'];
                  $overtimeinout = "SELECT * FROM OVER_TIME WHERE emp_id = '$empid' and ot_day = '$dtrday' and ot_remarks ='Approved'";
                  $overtimeinoutexec = mysqli_query($conn, $overtimeinout) or die ("FAILED TO EXECUTE OT QUERY " . mysqli_error($conn));
                  $overtimearray = mysqli_fetch_array($overtimeinoutexec);

                  if ($overtimearray) {
                      $otin = $overtimearray['ot_time'];
                      $otout = $overtimearray['ot_timeout'];
                  } else {
                      $otin = "";
                      $otout = "";
                  }
                  ?>
                  <tr>
                      <td><?php echo $day; ?></td>
                      <td><?php echo $payperiodarray['in_morning']; ?></td>
                      <td><?php echo $payperiodarray['out_afternoon']; ?></td>
                      <td><?php echo $hrswrk; ?></td>
                      <td></td>
                      <td><?php echo $otin; ?></td>
                      <td><?php echo $otout; ?></td>
                      <td><?php echo $payperiodarray['overtime_hours']; ?></td>
                      <td><?php echo $payperiodarray['totalhours']; ?></td>
                  </tr>
                  <?php
              }
            }
          ?>

                  
                  </tr>
                </tbody>
              </table>
            </div>
                  </div>
                </div>
    </div>
  </div>
          
</div>

          <!-- <div class="card__avatar mt-2">
              <img class="img-fluid" height="200" width="200" src="data:image;base64,<?php echo $currimg?>" style="border-radius: 50%;">
      </div>
      <div class="textContainer">
    <p class="name"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
</svg> 
<i class="bi bi-person-fill"><?php $fullName = implode(' ', [$currfirstname, $currlastname]);
    echo $fullName; ?></i></p>

    <p class="empID">Employee ID: <i><?php echo $currempid; ?></i></p>
    <p class="position">Position: <i>Position 1</i></p>
    <p class="department">Department: <i><?php echo $currdeptname; ?></i></p>
    <p class="salgrade">Salary Grade: <i>Grade 6</i></p>
    <p class="empstatus">Employment Status: ok lang</p>
    
   
  </div>
  <div class="buttons mb-2">
  <div class = "uinfotab2"><a href ="empCHANGEPASSWORD.php" class = "btn btn-info"><span class="icon"><i class="icon-edit"></i> </span>Change Password</a></div>
  </div> -->

</div>
          </div>
<!-- end unang hati -->

<!-- 2nd hati -->
          <div class="col-lg-4 col-md-12 height-100vh" >

          <div class="card text-center">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab">
                <li class="nav-item">
                    <a href="#home" class="nav-link active" data-bs-toggle="tab">Chart</a>
                </li>
                <li class="nav-item">
                    <a href="#profile" class="nav-link" data-bs-toggle="tab">Timeline</a>
                </li>
                
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content d-flex align-items-center justify-content-center">
                <div class="tab-pane fade show active" id="home">
                  <div class="chart pb-2">             
                       <canvas id="myPieChart" width="280" height="280"></canvas>
</div>

                <form action="" method="post">
                <div class="row mt-1">
                  <div class="col-10">
                  <select class="form-select form-select-sm" id="sel" aria-label="Small select example" name="payfunction">

                <option value="Generate Payslip" <?php echo (isset($_SESSION['payfunction']) && $_SESSION['payfunction'] == 'Generate Payslip') ? 'selected' : ''; ?>>Generate Payslip</option>
                <option value="View DTR" <?php echo (isset($_SESSION['payfunction']) && $_SESSION['payfunction'] == 'View DTR') ? 'selected' : ''; ?>>View DTR</option>
                <option value="View Timesheet" <?php echo (isset($_SESSION['payfunction']) && $_SESSION['payfunction'] == 'View Timesheet') ? 'selected' : ''; ?>>View Timesheet</option>
                <option value="View Leaves" <?php echo (isset($_SESSION['payfunction']) && $_SESSION['payfunction'] == 'View Leaves') ? 'selected' : ''; ?>>View Leaves</option>
              </select>
              <label for="sel">Select Function</label>
                  </div>
               
              <div class="sub col-2">
              <button type="submit" class="btn btn-primary printbtn" name="pperiod_btn1" style="margin-bottom: 20px;" >Go</button>

              </div>
                </div>

                <div class="row1">
                <!-- <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> -->
    <?php
    $payperiodsquery = "SELECT * FROM payperiods";
    $payperiodsexecquery = mysqli_query($conn, $payperiodsquery) or die ("FAILED TO EXECUTE PAYPERIOD QUERY " . mysqli_error($conn));
    ?>
    <div class="controls">

    <div class="row">
      <div class="col-10">
            <select name="payperiod" class="form-select form-select-sm" id="sel2" required>
          <option value=""></option>
          <?php
          // Iterate through pay periods
          while ($payperiodchoice = mysqli_fetch_array($payperiodsexecquery)) {
              $selected = ($payperiodchoice['pperiod_range'] == $_SESSION['payperiods']) ? 'selected' : '';
              ?>
              <option value="<?php echo $payperiodchoice['pperiod_range']; ?>" <?php echo $selected; ?>>
                  <?php echo $payperiodchoice['pperiod_range']; ?>
              </option>
          <?php } ?>
      </select>
        <label for="sel2">Selecy Payroll Period</label>

      </div>
      <div class="col-2">
      <button type="submit" class="btn btn-primary printbtn" name="pperiod_btn" style="margin-bottom: 20px;" >Go</button>

      </div>
      <div class="button d-flex justify-content-center align-items-center pt-2">
                <div class = "uinfotab3"><a href ="try.php" class = "btn btn-success btn-sm"><span class="icon"><i class="icon-refresh"></i></span> Refresh</a></div>
                
                </div>
    </div>
        

    </div>
                </form>



                </div>
              

                </div>





                <div class="tab-pane fade border-start text-dark" id="profile">
                <ul class="timeline ">
    <li style="--accent-color:#0000000">
    <div class="date"><h5>Date Hired</h5></div>
    <div class="text mt-2 ml-2">
    <p><?php echo $currdatehired;?></p>
       <p><?php echo $currposition;?></p>
    </div>
    </li>
  <?php
  if ($currdateregularized != '0000-00-00' && $currdateregularized !='') 
  {?>
    <li>
    <div class="date">Date Regularized</div>
    <div class="text mt-2 ml-2">
    <p><?php echo $currdateregularized;?></p>
       <p><?php echo $currposition;?></p>
    </div>
        
    </li>
<?php


  }
  if ($currdateresigned != '0000-00-00' && $currdateresigned !='') 
  {?>
    <li style="--accent-color:#E24A68">
    <div class="date">Date Resigned</div>
    <div class="text mt-2 ml-2">
    <p><?php echo $currdateregularized;?></p>
       <p>Resigned</p>
    </div>
        
    </li>
<?php


  }
  ?>
    <!-- 
    
   
    <li style="--accent-color:#1B5F8C">
    <div class="date">promoted</div>
    <div class="text mt-2 ml-2">
    <p>asdfasdf</p>
       <p>asdfasdf</p>
    </div>
    </li>
    <li style="--accent-color:#1B5F8C">
        <div class="date">promoted</div>
        <p>asdfasdf</p>
       <p>asdfasdf</p>
    </li> -->
</ul>
                </div>
                
            </div>
        </div>
    </div>
  
            <!-- chart -->
           

      </div>
      
    </div>
    
  

 
             
    
    <?php

            

function filterTable($searchquery)
{

     $conn1 = mysqli_connect("localhost:3307","root","","masterdb");
     $filter_Result = mysqli_query($conn1,$searchquery) or die ("failed to query masterfile ".mysqli_error($conn1));
     return $filter_Result;
}
// while($row1 = mysqli_fetch_array($search_result)):;
// $basepay = $row1['reg_pay'];
// $otpay = $row1['ot_pay'];

// $grosspay = ($basepay + $otpay);
// $gpay = number_format((float)$grosspay,2,'.','');
// $philhealth = $row1['philhealth_deduct'];
// $sss = $row1['sss_deduct'];
// $pagibigloan = $row1['pagibigloan_deduct'];
// $withholdingtax = $row1['tax_deduct'];
// $totaldeduct = $row1['total_deduct'];
// $netpay = ($grosspay - $totaldeduct);
// $npay = number_format((float)$netpay,2,'.',''); 



       
?>
   <!-- <tr class="gradeX">
   <td><?php echo $row1['last_name'];?></td>
   <td><?php echo $row1['first_name'];?></td>
   <td><?php echo $row1['middle_name']; ?></td>
   <td><?php echo $row1['pperiod_range'];?></td>
   <td><?php echo $basepay;?></td>
   <td><?php echo $otpay;?></td>
   <td><?php echo $hdaypay;?></td>
   <td><?php echo $shdaypay;?></td>
   <td><?php echo $gpay;?></td>
   <td><?php echo $philhealth; ?></td>
   <td><?php echo $sss; ?></td>
   <td><?php echo $pagibig; ?></td>
   <td><?php echo $sssloan; ?></td>
   <td><?php echo $pagibigloan; ?></td>
   <td><?php echo $totaldeduct; ?></td>
   <td><center><b>&#8369; <?php echo $npay;?></td> -->

   
 <!-- </tr> -->


 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php
unset($_SESSION['changepassnotif']);
?>
<script src="../js/maruti.dashboard.js"></script> 
<script src="../js/excanvas.min.js"></script> 

<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.flot.min.js"></script> 
<script src="../js/jquery.flot.resize.min.js"></script> 
<script src="../js/jquery.peity.min.js"></script> 
<script src="../js/fullcalendar.min.js"></script> 
<script src="../js/maruti.js"></script> 
<div class="widget-title">

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart container -->
<canvas id="myPieChart" width="200" height="200"></canvas>

<script>
    // Initial static data
    var staticData = [
        { label: 'Label 1', value: 50 },
        { label: 'Label 2', value: 30 },
        // Add more data as needed
    ];

    // Create the chart with static data
    var initialChart = createCustomPieChart(staticData, 'myPieChart', 200, 200);

    // Fetch data from PHP script
    fetch('fetch_data.php')
        .then(response => response.json())
        .then(data => updateChartWithData(initialChart, data))
        .catch(error => console.error("Error fetching data:", error));

    // Function to create a custom pie chart
    function createCustomPieChart(data, chartId, chartWidth, chartHeight) {
        var labels = data.map(item => item.label);
        var values = data.map(item => item.value);

        var ctx = document.getElementById(chartId).getContext('2d');
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: getRandomColors(values.length),
                }],
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                width: chartWidth,
                height: chartHeight,
            },
        });

        return myPieChart; // Return the chart instance
    }

    // Function to update the chart with new data
    function updateChartWithData(chart, newData) {
        var labels = newData.map(item => item.label);
        var values = newData.map(item => item.value);

        // Update chart data
        chart.data.labels = labels;
        chart.data.datasets[0].data = values;

        // Update chart colors (optional)
        chart.data.datasets[0].backgroundColor = getRandomColors(values.length);

        // Update the chart
        chart.update();
    }

    // Function to generate random colors
    function getRandomColors(count) {
        var colors = [];
        for (var i = 0; i < count; i++) {
            var hue = (360 / count) * i;
            colors.push(`hsl(${hue}, 70%, 60%)`);
        }
        return colors;
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>