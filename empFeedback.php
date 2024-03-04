<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
include("DBCONFIG.PHP");
include("LoginControl.php");
include("BASICLOGININFO.PHP");
$userId=  $_SESSION['empId'];
// $adminId = $_SESSION['adminId'];
$error = false;
$adminname = "SELECT first_name, last_name FROM employees where emp_id = '$userId'";
$adminnameexecqry = mysqli_query($conn, $adminname) or die ("FAILED TO CHECK EMP ID ".mysqli_error($conn));
$adminData = mysqli_fetch_assoc($adminnameexecqry);

$empFullName = $adminData['first_name'] . " " . $adminData['last_name'];
if (isset($_GET['submitbtn']))
{
    $announcementTitle = $_GET['announcementTitle'];
    $message = $_GET['message'];

    
            // SQL query to insert data into the database
            $insertAnnouncementQuery = "INSERT INTO notifications (emp_id, emp_name, title,  message, type, status, created_at) 
                                        VALUES ('$userId','$empFullName','$announcementTitle', '$message', 'Issue', 'unread',NOW())";
    
            // Execute the query
            if ($conn->query($insertAnnouncementQuery) !== TRUE) {
                ?><script>
                document.addEventListener('DOMContentLoaded', function() {
                    swal({
                    // title: "Data ",
                    text: "Something went wrong.",
                    icon: "error",
                    button: "Try Again",
                    });
                }); </script>
                <?php
                // echo "Error: " . $insertAnnouncementQuery . "<br>" . $conn->error;
            }
            else {
                ?>
   
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    swal({
                     //  title: "Good job!",
                      text: "Data inserted successfully",
                      icon: "success",
                      button: "OK",
                     }).then(function() {
                        window.location.href = 'empFeedback.php'; // Replace 'your_new_page.php' with the actual URL
                    });
                });
             </script>
                 <?php
            }
        }
    
        // echo "Announcement records inserted successfully for all employees";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <title>Document</title>
</head>

<body>

    <?php
    INCLUDE('navbar2.php');
    ?>

    <div class="content d-flex align-items-center justify-content-center" style="height: 80vh;">

        <div class="container" style="width:80vh;">
            <div class="card shadow">
                <div class="card-header">
                    Issues Form
                </div>
                <form class="row g-3 p-3" method="GET">
                    <div class="col-12">
                        <label for="announcementTitle" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="announcementTitle" name="announcementTitle" required>
                    </div>
                    <div class="col-12">
                        <label for="message" class="form-label">Details</label>
                        <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary" name ="submitbtn">Submit</button>
                        
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

</body>

</html>
