<?php
include("DBCONFIG.PHP");
include("LoginControl.php");
include("BASICLOGININFO.PHP");
$currentempid = $_SESSION['empID'];
$query = "SELECT * FROM empnotifications WHERE emp_id ='$currentempid'";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Admin Home</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../css/fullcalendar.css" />
<link rel="stylesheet" href="../css/maruti-style.css" />
<link rel="stylesheet" href="../css/maruti-media.css" class="skin-color" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


</head>
<body>

<!--Header-part-->

<?php
INCLUDE ('navbar2.php');
?>

<div id="content">
    <div class="title d-flex justify-content-center pt-3">
        <h3>ALL NOTIFICATIONS</h3>
    </div>
    <hr>
    <br>
    <div class="container p-5">
        <div class="">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
            <tr>
                <th>Notifications</th>
                <th>Admin</th>

                <!-- Add more columns as needed -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through the notifications and display them in the table rows
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                if ($row['type'] == 'Overtime') {
                    echo "<td><a href='empAPPLYOvertime.php'>{$row['message']}</a></td>";
                } if ($row['type'] == 'Leave') {
                    echo "<td><a href='LeaveApplication.php'>{$row['message']}</a></td>"; 
                } if ($row['type'] == 'Loan') {
                    echo "<td><a href='empLoans.php'>{$row['message']}</a></td>";
                } if ($row['type'] == 'Payroll') {
                    echo "<td><a href='empPAYROLLrecords.php'>{$row['message']}</a></td>";
                } if ($row['type'] == 'Announcement') {
                    echo "<td><a href='empAnnouncement.php?notification_id={$row['notification_id']}'>{$row['message']}</a></td>";
                } 

                echo "</td>";
                echo "<td>{$row['adminname']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>


    
      <span class="span2">
      </span>
    </div>
    <hr>

    

 
</body>
</html>