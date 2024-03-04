<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<?php
include("DBCONFIG.PHP");
include("LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();

include('navbar2.php'); 

if(isset($_SESSION['masterfilenotif'])){

    $mfnotif = $_SESSION['masterfilenotif'];
    ?>  
    <script>
    alert("<?php echo $mfnotif;?>");
    </script>
    <?php
    }
    
    $results_perpage = 20;
    
                   if (isset($_GET['page'])){
    
                        $page = $_GET['page'];
                   } else {
    
                        $page=1;
                   }
    
    $currentempid = $_SESSION['empID'];
    
    $userIdpage  = $_SESSION['empID'];
    
    $pageViewed = basename($_SERVER['PHP_SELF']);
    $pageInfo = pathinfo($pageViewed);
    
    // Get the filename without extension
    $pageViewed1 = $pageInfo['filename'];
    
    
    
    // Log the page view
    logPageView($conn, $userIdpage, $pageViewed1);
    
    if (isset($_POST['searchbydate_btn'])){
        $start_from = ($page-1) * $results_perpage;
       $datefrom = $_POST['dpfrom'];
       $dateto = $_POST['dpto'];
       $searchquery = "SELECT * FROM DTR,employees WHERE DTR.emp_id = '$currentempid' AND DTR.emp_id = employees.emp_id AND DATE(DTR_day) BETWEEN '$datefrom' and '$dateto' ORDER BY DTR_day DESC LIMIT $start_from,".$results_perpage;
       $search_result = filterTable($searchquery);
    
    } else  {
      $start_from = ($page-1) * $results_perpage;
      $searchquery ="SELECT * FROM DTR,employees WHERE DTR.emp_id = '$currentempid' AND DTR.emp_id = employees.emp_id ORDER BY DTR_day DESC LIMIT $start_from,".$results_perpage; 
      $search_result = filterTable($searchquery);
      }
    
    $countdataqry = "SELECT COUNT(emp_id) AS total FROM DTR where emp_id = '$currentempid'";
    $countdataqryresult = mysqli_query($conn,$countdataqry) or die ("FAILED TO EXECUTE COUNT QUERY ". mysql_error());      
    $row = $countdataqryresult->fetch_assoc();
    $totalpages=ceil($row['total'] / $results_perpage);
    // echo "Generated Query: $searchquery";
    
    ?>



<!DOCTYPE html>
<html lang="en">
<head>
<title>Apply Leave</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../css/fullcalendar.css" />
<link rel="stylesheet" href="../jquery-ui-1.12.1/jquery-ui.css">
<script src="../jquery-ui-1.12.1/jquery-3.2.1.js"></script>
<script src="../jquery-ui-1.12.1/jquery-ui.js"></script>
<script type ="text/javascript">
   $( function() {
      $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd'});
      } );
  
</script>
</head>

<body>

<div class="masterdiv">
<div class="titlediv pt-5" >
       
<h3 href="newapplyovertime.php" style = "text-align: center;">ATTENDANCE RECORD</h3>
             

          </div>
         

<div class ="control-group">
                    <label class="control-label" style= "margin-bottom:10px; margin-top:10px;">Search by date: </label>
                      <div class="controls">
                        <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                        <div id="search">
                        <input class ="span8" type="text" id="date" name ="dpfrom" placeholder="From" value="">
                        </div>
                        <div id = "search">
                        <input class ="span8" type="text" id="date" name ="dpto" placeholder="To" value="">
                        <button button type="submit" class = "btn btn-primary" name ="searchbydate_btn">
  <i class="fas fa-search text-white"></i>
</button>
    </form>
                        </div>
                       
                      </div>        

<div class="table-responsive-sm" >
                  <table class="table table-bordered data-table">
                  <thead class="table-dark">
                  <tr>
                  <th>Employee ID</th>
                  <th>Last Name</th>
                  <th>First Name</th>
                  <th>Middle Name</th>
                  <th>Morning In</th>
                  <th>Morning Out</th>
                  <th>Afternoon In</th>
                  <th>Afternoon Out</th>
                  <th>Day of Record</th>
                  <th>Remarks</th>                
                  
                </tr>
              </thead>
              <tbody> 

              <?php

              

               function filterTable($searchquery)
               {

                    $conn1 = mysqli_connect("localhost:3307","root","","masterdb");
                    $filter_Result = mysqli_query($conn1,$searchquery) or die ("failed to query masterfile ".mysqli_error($conn1));
                    return $filter_Result;
               }

               
               while($row1 = mysqli_fetch_array($search_result)):;
               ?>
                  <tr class="gradeX">
                  <td><?php echo $row1['prefix_ID'];?><?php echo $row1['emp_id'];?></td>
                  <td><?php echo $row1['last_name'];?></td>
                  <td><?php echo $row1['first_name'];?></td>
                  <td><?php echo $row1['middle_name']; ?></td>
                  <td><?php echo $row1['in_morning'];?></td>
                  <td><?php echo $row1['out_morning'];?></td>
                  <td><?php echo $row1['in_afternoon'];?></td>
                  <td><?php echo $row1['out_afternoon'];?></td>
                  <td><?php echo $row1['DTR_day'];?></td>
                  <td><?php echo $row1['DTR_remarks'];?></td>
                </tr>
              <?php endwhile;?>
              </tbody>
            </table>
               <div class = "pagination alternate" style="float:right;">
               <ul>
               <!-- <?php

                    for ($i=1; $i<=$totalpages; $i++){
                         echo "<li><a href='adminMasterfile.php?page=".$i."'";
                         if ($i==$page) echo " class='curPage'";
                              echo ">".$i."</a></li> ";
                         };
               ?> -->
               </ul>
               </div>

                  </div>          
                        </div>
                        <!-- <span class ="label label-important"><?php echo $rfidError; ?></span> -->
                      </div>
                  </div>
                    <div class="buttons">
                    
                 

                  
                  
                  <a href ="empnewATTENDANCE.php" class = "btn btn-success" style = "float:left; margin-right: 10px;"><span class="icon"><i class="icon-refresh"></i></span> Refresh</a>
                </div>
                </div>
                  </form>
                 

                     
  </div>

  <?php
unset($_SESSION['masterfilenotif']);
?>

<script src="../js/maruti.dashboard.js"></script> 
<script src="../js/excanvas.min.js"></script> 

<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.flot.min.js"></script> 
<script src="../js/jquery.flot.resize.min.js"></script> 
<script src="../js/jquery.peity.min.js"></script> 
<script src="../js/fullcalendar.min.js"></script> 
<script src="../js/maruti.js"></script> 


<style>
  .widget-box {
  border-radius: 10px; /* You can adjust the value to control the amount of rounding */
  border: 1px solid #ccc; /* Optional: You can add a border for further styling */
  padding: 15px; /* Optional: Add padding to the box for better appearance */
}
@media (max-width: 768px) {
  .widget-box {
    margin: auto;
    margin-top: 70px; /* This will center the widget-box */
  }

  .widget-title li {
    /* Adjust the styles for list items inside widget-title at smaller screens */
    display: block;
    margin-bottom: 10px;
  }

  .active {
    /* Adjust the styles for the active class when the screen width is 768px or less */
    
  }
}

.table{
                   
    margin-left: 0px;
    margin-top: 40px;
    width:100%;
    table-layout:auto;
}
    .table-responsive {
    overflow-x: auto;
    max-width: 100%;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function () {
                flatpickr("#date", {
                    dateFormat: "Y-m-d", // Adjust the date format as needed
                });
            });
            </script>
</body>
</html>