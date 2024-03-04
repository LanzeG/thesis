<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
include("DBCONFIG.PHP");
include("LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();

$uname = $_SESSION['uname'];
$empid = $_SESSION['empId'];
if (isset($_GET['daterange_start']) && isset($_GET['daterange_end'])) {
  $_SESSION['start_date'] = $_GET['daterange_start'];
  $_SESSION['end_date'] = $_GET['daterange_end'];
}

$timeconv = strtotime("NOW");
$currtime = date("F d, Y", $timeconv); //january 01, 2000
$currdate = date("Y-m-d", $timeconv); //2000-01/01
$curryear = date("Y", $timeconv); //23
if (date('N') < 6) {
$sql = "SELECT emp_id FROM employees
        WHERE NOT EXISTS (
            SELECT 1 FROM time_keeping
            WHERE employees.emp_id = time_keeping.emp_id
            AND DATE(time_keeping.timekeep_day) = CURDATE()
        )";

$result = $conn->query($sql);
if (!$result) {
  echo "<script>Error executing query: </script>" . $conn->error;
  // handle the error, e.g., return or exit
}
// Iterate through the employees without a timekeeping record
while ($row = $result->fetch_assoc()) {
    $employee_id = $row['emp_id'];

    // Check if an absence record already exists for today
    $check_existing_sql = "SELECT 1 FROM absences
                           WHERE emp_id = $employee_id
                           AND absence_date = CURDATE()";

    $existing_result = $conn->query($check_existing_sql);

    if ($existing_result === false) {
      echo "Error executing query: " . $conn->error;
      // handle the error, e.g., return or exit
  }
    // If no absence record exists, insert a new record
    if (mysqli_num_rows($existing_result) == 0) {
        $insert_sql = "INSERT INTO absences (emp_id, absence_date)
                       VALUES ($employee_id, CURDATE())";

        if ($conn->query($insert_sql) === TRUE) {
            echo "<script> Record inserted successfully for employee with ID $employee_id.\n</script>";
        } else {
            echo "<script>Error inserting record: " . $conn->error . "\n </script>";
        }
    } else {
        echo "<script>Absence record already exists for employee with ID $employee_id.\n </script>";
    }
}
} else {
  echo "<script>Current date is a weekend. No action taken.</script>";
}

$checkpperiod = "SELECT pperiod_range FROM payperiods WHERE CURDATE() BETWEEN pperiod_start and pperiod_end";
$checkpperiodexec = mysqli_query($conn, $checkpperiod) or die("FAILED TO CHECK PAYPERIOD " . mysqli_error($conn));
$pperiodarray = mysqli_fetch_array($checkpperiodexec);
if ($pperiodarray) {
  $currpperiod = $pperiodarray['pperiod_range'];
} else {
  $currpperiod = "No Current Pay Period";
}
/** CHECK PAYROLL PERIOD **/
/** CHECK OVERTIME APPLICATIONS **/
$checkotapp = "SELECT COUNT(emp_id) as otapps FROM OVER_TIME WHERE ot_remarks = 'For approval'";
$checkotappexec = mysqli_query($conn, $checkotapp) or die("FAILED TO CHECK OT APPS " . mysqli_error($conn));
$otapparray = mysqli_fetch_array($checkotappexec);
if ($otapparray) {
  $otapps = $otapparray['otapps'];
}
/** CHECK OVERTIME APPLICATIONS **/
/** CHECK LEAVE APPLICATIONS **/
$checkleavesapp = "SELECT COUNT(emp_id) as leaveapps FROM LEAVES_APPLICATION WHERE leave_status = 'For approval'";
$checkleavesappexec = mysqli_query($conn, $checkleavesapp) or die("FAILED TO CHECK LEAVE APPS");
$leaveapparray = mysqli_fetch_array($checkleavesappexec);
if ($leaveapparray) {
  $leaveapps = $leaveapparray['leaveapps'];
}

/**CHECK LEAVE APPLICATIONS **/


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["submit"])) {

  $selectedDepartment = isset($_GET["department"]) ? $_GET["department"] : "";
  $selectedEmploymentType = isset($_GET["employmenttype"]) ? $_GET["employmenttype"] : "";
  $selectedposition = isset($_GET["position"]) ? $_GET["position"] : "";
  $selectedGender = isset($_GET["gender"]) ? $_GET["gender"] : "";


  $checkattendancemorning = "SELECT COUNT(t.emp_id) as morningatt
FROM TIME_KEEPING t
JOIN employees d ON t.emp_id = d.emp_id
WHERE DATE(t.timekeep_day) BETWEEN '{$_SESSION['start_date']}' AND '{$_SESSION['end_date']}'
  " . (!empty($selectedDepartment) ? "AND d.dept_NAME = '$selectedDepartment'" : "") . "
  " . (!empty($selectedposition) ? "AND d.position = '$selectedposition'" : "") . "
  " . (!empty($selectedGender) ? "AND d.emp_gender = '$selectedGender'" : "") . "
  " . (!empty($selectedEmploymentType) ? "AND d.employment_type= '$selectedEmploymentType'" : "");


  $checkattendancemorningexecquery = mysqli_query($conn, $checkattendancemorning) or die("FAILED TO CHECK MORNING ATTENDANCE " . mysqli_error($conn));
  $morningattarray = mysqli_fetch_array($checkattendancemorningexecquery);
  if ($morningattarray) {
    $morningatt = $morningattarray['morningatt'];
    echo "Generated Query: $checkattendancemorning";
  }
  $checkabsences = "SELECT COUNT(d.emp_id) as numemps
FROM employees d
WHERE " . (!empty($selectedDepartment) ? "d.dept_NAME = '$selectedDepartment' AND " : "")
    . (!empty($selectedposition) ? "d.position = '$selectedposition' AND " : "")
    . (!empty($selectedGender) ? "d.emp_gender = '$selectedGender' AND " : "")
    . (!empty($selectedEmploymentType) ? "d.employment_type = '$selectedEmploymentType' AND " : "")
    . "d.emp_status = 'Active'";


  $checkabsencesexec = mysqli_query($conn, $checkabsences) or die("FAILED TO CHECK ABSENCES " . mysqli_error($conn));
  $absencesarray = mysqli_fetch_array($checkabsencesexec);

  if ($absencesarray) {
    $activeemps = $absencesarray['numemps'];

    $absencestoday = $absencesarray['numemps'] - $morningatt;
    echo "Generated Query: $checkabsences";
    echo "Generated Query: $activeemps";
    echo "Generated Query: $absencestoday";
  }
 //gender
 $genderQuery = "SELECT 
 COUNT(emp_id) as totalEmps,
 SUM(CASE WHEN emp_gender = 'Male' THEN 1 ELSE 0 END) as numMales,
 SUM(CASE WHEN emp_gender = 'Female' THEN 1 ELSE 0 END) as numFemales
FROM employees 
WHERE emp_status = 'Active'
" . (!empty($selectedDepartment) ? "AND dept_NAME = '$selectedDepartment' " : "")
. (!empty($selectedGender) ? "AND emp_gender = '$selectedGender' " : "")
. (!empty($selectedposition) ? "AND position = '$selectedposition' " : "")
. (!empty($selectedEmploymentType) ? "AND employment_type = '$selectedEmploymentType' " : "");

$genderExec = mysqli_query($conn, $genderQuery) or die("FAILED TO CHECK ABSENCES " . mysqli_error($conn));
$genderArray = mysqli_fetch_array($genderExec);

if ($genderArray) {
$totalEmps = $genderArray['totalEmps'];
$numMales = $genderArray['numMales'];
$numFemales = $genderArray['numFemales'];
}

// Check late
$late = "SELECT COUNT(e.emp_id) as late FROM TIME_KEEPING t
JOIN employees e ON t.emp_id = e.emp_id
WHERE late_hours > 0 
AND DATE(t.timekeep_day) BETWEEN '{$_SESSION['start_date']}' AND '{$_SESSION['end_date']}'
" . (!empty($selectedDepartment) ? "AND e.dept_NAME = '$selectedDepartment' " : "")
. (!empty($selectedGender) ? "AND e.emp_gender = '$selectedGender' " : "")
. (!empty($selectedposition) ? "AND e.position = '$selectedposition' " : "")
. (!empty($selectedEmploymentType) ? "AND e.employment_type = '$selectedEmploymentType' " : "");

$lateExecQuery = mysqli_query($conn, $late) or die("FAILED TO CHECK LATE ATTENDANCE " . mysqli_error($conn));
$latearray = mysqli_fetch_array($lateExecQuery);

if ($latearray) {
$lateAtt = $latearray['late'];
}

//leaves
$leave = "SELECT COUNT(e.emp_id) as numLeaves
FROM leaves_application l
JOIN employees e ON l.emp_id = e.emp_id
WHERE l.leave_status = 'Approved' 
AND DATE(l.leave_datestart) <= '{$_SESSION['end_date']}'
AND DATE(l.leave_dateend) >= '{$_SESSION['start_date']}'
" . (!empty($selectedDepartment) ? "AND e.dept_NAME = '$selectedDepartment'" : "") . "
" . (!empty($selectedGender) ? "AND e.emp_gender = '$selectedGender'" : "") . "
" . (!empty($selectedposition) ? "AND e.position = '$selectedposition'" : "") . "
" . (!empty($selectedEmploymentType) ? "AND e.employment_type = '$selectedEmploymentType'" : "");

$leavesExec = mysqli_query($conn, $leave) or die("FAILED TO CHECK LEAVES " . mysqli_error($conn));
$leavesArray = mysqli_fetch_array($leavesExec);

if ($leavesArray) {
$numLeaves = $leavesArray['numLeaves'];
}

//undertime
$undertime = "SELECT COUNT(e.emp_id) as undertime 
FROM time_keeping t
JOIN employees e ON t.emp_id = e.emp_id
WHERE t.undertime_hours > 0 
AND DATE(timekeep_day) BETWEEN '{$_SESSION['start_date']}' AND '{$_SESSION['end_date']}'
" . (!empty($selectedDepartment) ? "AND e.dept_NAME = '$selectedDepartment' " : "")
. (!empty($selectedGender) ? "AND e.emp_gender = '$selectedGender' " : "")
. (!empty($selectedposition) ? "AND e.position = '$selectedposition' " : "")
. (!empty($selectedEmploymentType) ? "AND e.employment_type = '$selectedEmploymentType' " : "");

$undertimeExecQuery = mysqli_query($conn, $undertime) or die("FAILED TO CHECK UNDERTIME ATTENDANCE " . mysqli_error($conn));
$undertimearray = mysqli_fetch_array($undertimeExecQuery);

if ($undertimearray) {
$undertimeatt = $undertimearray['undertime'];
}






} else {
$checkattendancemorning = "SELECT COUNT(emp_id) as morningatt FROM TIME_KEEPING WHERE DATE(timekeep_day) = CURDATE()";
$checkattendancemorningexecquery = mysqli_query($conn, $checkattendancemorning) or die("FAILED TO CHECK MORNING ATTENDANCE " . mysqli_error($conn));
$morningattarray = mysqli_fetch_array($checkattendancemorningexecquery);
if ($morningattarray) {
$morningatt = $morningattarray['morningatt'];
}
/** CHECK ATTENDANCE **/
/** CHECK ABSENCES **/
$checkabsences = "SELECT COUNT(emp_id) as numemps FROM employees WHERE emp_status = 'Active'";
$checkabsencesexec = mysqli_query($conn, $checkabsences) or die("FAILED TO CHECK ABSENCES " . mysqli_error($conn));
$absencesarray = mysqli_fetch_array($checkabsencesexec);
if ($absencesarray) {
$activeemps = $absencesarray['numemps'];

$absencestoday = $activeemps - $morningatt;
}

//check gender
$genderQuery = "SELECT 
 COUNT(emp_id) as totalEmps,
 SUM(CASE WHEN emp_gender = 'Male' THEN 1 ELSE 0 END) as numMales,
 SUM(CASE WHEN emp_gender = 'Female' THEN 1 ELSE 0 END) as numFemales
FROM employees 
WHERE emp_status = 'Active'";

$genderExec = mysqli_query($conn, $genderQuery) or die("FAILED TO CHECK ABSENCES " . mysqli_error($conn));
$genderArray = mysqli_fetch_array($genderExec);

if ($genderArray) {
$totalEmps = $genderArray['totalEmps'];
$numMales = $genderArray['numMales'];
$numFemales = $genderArray['numFemales'];

}


//check late
$late = "SELECT COUNT(emp_id) as late FROM TIME_KEEPING WHERE late_hours > 0 AND DATE(timekeep_day) = CURDATE()";
$lateexecquery = mysqli_query($conn, $late) or die("FAILED TO CHECK MORNING ATTENDANCE " . mysqli_error($conn));
$latearray = mysqli_fetch_array($lateexecquery);
if ($latearray) {
$lateatt = $latearray['late'];
}



//check on leave employees
$leave = "SELECT COUNT(emp_id) as numLeaves
FROM leaves_application
WHERE leave_status = 'Approved' AND CURDATE() >= leave_datestart AND CURDATE() <= leave_dateend";

$leavesExec = mysqli_query($conn, $leave) or die("FAILED TO CHECK ABSENCES " . mysqli_error($conn));
$leavesArray = mysqli_fetch_array($leavesExec);

if ($leavesArray) {
$leavesArray['numLeaves'];


}

//check undertime
$undertime = "SELECT COUNT(emp_id) as undertime FROM TIME_KEEPING WHERE undertime_hours > 0 AND DATE(timekeep_day) = CURDATE()";
$undertimeexecquery = mysqli_query($conn, $undertime) or die("FAILED TO CHECK MORNING ATTENDANCE " . mysqli_error($conn));
$undertimearray = mysqli_fetch_array($undertimeexecquery);
if ($undertimearray) {
$undertimeatt = $undertimearray['undertime'];
}
}
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<link rel="stylesheet" href="timeline.css">
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
</head>
<body>

<?php include('navbarAdmin.php'); ?>

<div class="container-fluid">
  <div class="row">

    <!-- Main Content -->
 <div class="content">
 <div class="row">
      <div class="card-group p-0">
        
      <div class="card shadow col-3 m-1">
        <div class="card-header">
        Total Employees
        </div>
        <div class="card-body d-flex align-items-center justify-content-center">

          <div class="d-flex justify-content-center">      
            <h3> <?php echo $absencesarray['numemps']; ?></h3>
          </div>
         
    
       
      </div>



      </div>
      <div class="card shadow col-3 m-1">
      <div class="card">
        <div class="card-header">
        Overtime Pendings
        </div>
        <div class="card-body">

          <div class="d-flex justify-content-center">      
            <h3> <?php echo $otapps; ?></h3>
          </div>
          <div class="d-flex justify-content-center">      
          <a href="ADMIN/OVERTIME/adminOT.php" type="button"><button type="button" class="btn btn-success" >Manage Overtime</button></a>
         
          </div>
    
        </div>
      </div>


      </div>

      <div class="card shadow col-3 m-1">
      <div class="card">
        <div class="card-header">
        Leave Pendings
        </div>
        <div class="card-body">

          <div class="d-flex justify-content-center">      
            <h3><?php echo $leaveapps; ?> </h3>
          </div>
          <div class="d-flex justify-content-center">      
          <a href="ADMIN/LEAVES/adminLEAVES.php" type="button"><button type="button" class="btn btn-success" >Manage Leave</button></a>
         
          </div>
    
        </div>
      </div>


      </div>

      <div class="card shadow col-3 m-1">
        
    
        <div class="card-header">
         Current Payroll Period
        </div>
        <div class="card-body d-flex align-items-center">
          <h5>                  <?php echo $currpperiod; ?> </h5>
        </div>
      

      </div>
      
      </div>
      
    </div>

    <div class="second">

    <div class="row">
    <div class="col-6 card shadow">
      dito yung malaki Lorem ipsum dolor sit, amet consectetur adipisicing elit. Omnis perferendis laudantium nulla dicta ipsa sint eius, recusandae similique cumque blanditiis fuga officiis? Deserunt modi ad error amet quam laboriosam soluta.
      Lorem, ipsum dolor sit amet consectetur adipisicing elit. Rem laborum inventore sunt labore mollitia voluptas incidunt nulla eum magni maiores asperiores sit expedita sint neque vitae illum, earum ipsa quos!
      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorem, rerum iste cupiditate accusamus voluptas ab excepturi. Deserunt ullam unde facere nihil distinctio facilis, dignissimos officia corrupti rerum magni dolorum doloribus.
      Lorem ipsum dolor, sit amet consectetur adipisicing elit. Autem unde eaque, amet officia, expedita voluptas laudantium ad eligendi explicabo magni sunt deserunt laborum ab facere. Adipisci quos praesentium fugiat repudiandae.
      Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae beatae perferendis maiores vel ratione ipsam maxime unde consequuntur. Iure voluptatem dignissimos repellendus vitae repudiandae voluptas commodi quibusdam, natus delectus suscipit.
      Lorem ipsum dolor, sit amet consectetur adipisicing elit. Molestiae deleniti nam maiores hic debitis. Perferendis dolor, numquam illum totam obcaecati id animi in, voluptate, tenetur veritatis quod vel sed assumenda.
      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestiae a tempore voluptate quam! Ex eveniet asperiores ea dolore veritatis, harum ducimus ullam blanditiis laudantium autem obcaecati quae aliquid est minus.
    
    </div>
    <div class="col-6">
      <div class="card shadow">
        haha
      </div>
      <div class="card shadow">
        haha
      </div>
      
      </div>
      <!-- end ng col-6 -->
    </div>
    <!-- end ng second row -->

    </div>
    <!-- end ng second -->
   


     

    </div>
    <!-- end ng content -->
 </div>
 <!-- end ng row -->
</div>
 
  


<script src="../js/maruti.dashboard.js"></script>
  <script>
    $(document).ready(function () {
      // Initialize the daterangepicker with the default values
      $('#daterange').daterangepicker({
        opens: 'left',
        locale: {
          format: 'YYYY-MM-DD'
        }
      });

      // Set the initial values directly to the input fields
      var startDateInput = $('#start_date');
      var endDateInput = $('#end_date');

      // Update the values when the date range changes
      $('#daterange').on('apply.daterangepicker', function (ev, picker) {
        if (picker.startDate && picker.endDate) {
          startDateInput.val(picker.startDate.format('YYYY-MM-DD'));
          endDateInput.val(picker.endDate.format('YYYY-MM-DD'));
        }
      });

      // Trigger the apply event to set the initial values
      $('#daterange').trigger('apply.daterangepicker');

      // Set the initial values for start_date and end_date from PHP
      var start_date_php = '<?php echo isset($_GET['daterange_start']) ? htmlspecialchars($_GET['daterange_start']) : (isset($_SESSION['start_date']) ? htmlspecialchars($_SESSION['start_date']) : date("Y-m-d")); ?>';
      var end_date_php = '<?php echo isset($_GET['daterange_end']) ? htmlspecialchars($_GET['daterange_end']) : (isset($_SESSION['end_date']) ? htmlspecialchars($_SESSION['end_date']) : date("Y-m-d")); ?>';

      startDateInput.val(start_date_php);
      endDateInput.val(end_date_php);

      console.log('Start Date:', start_date_php);
      console.log('End Date:', end_date_php);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


</body>
</html>
