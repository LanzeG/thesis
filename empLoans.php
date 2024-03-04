<?php
include("DBCONFIG.PHP");
include("LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();



$results_perpage = 20;

               if (isset($_GET['page'])){

                    $page = $_GET['page'];
               } else {

                    $page=1;
               }

$currentempid = $_SESSION['empID'];

$userIdpage  = $_SESSION['empID'];

$searchquery ="SELECT * FROM loangsis JOIN employees ON loangsis.emp_id = employees.emp_id  WHERE employees.emp_id = $currentempid";
$searchresult= filterTable($searchquery);
// $searchquery2 ="SELECT * FROM loanpagibig JOIN employees ON loanpagibig.emp_id = employees.emp_id  WHERE employees.emp_id = $currentempid";
// $searchresult2= filterTable2($searchquery2);



// echo "Generated Query: $searchquery";

?>


<!DOCTYPE html>
<html lang="en">
<head>
<title>Employee Records</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../css/fullcalendar.css" />
<link rel="stylesheet" href="../css/maruti-style.css" />
<link rel="stylesheet" href="../css/maruti-media.css" class="skin-color" />
<link rel="stylesheet" href="../jquery-ui-1.12.1/jquery-ui.css">
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

<body>

<!--Header-part-->

<?php
INCLUDE ('navbar2.php');
?>


<div id="content">
    <div class="title d-flex justify-content-center pt-3">
        <h3>MY LOANS</h3>
    </div>
    <hr>
    <br>
    <div class="container p-5">
        <div class="">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                  <th>Loan ID</th>
                  <th>Employee ID</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Loan Amount</th>
                  <th>Monthly Deduction</th>
                  <th>Number of Pays Left</th>
                  <th>Status</th>
                  <th>Added by</th>
         
                  
                </tr>
              </thead>
              <tbody> 
                </div>
         

               <?php

              

               function filterTable($searchquery)
               {

                    $conn1 = mysqli_connect("localhost:3307","root","","masterdb");
                    $filter_Result = mysqli_query($conn1,$searchquery) or die ("failed to query masterfile ".mysqli_error($conn1));
                    return $filter_Result;
               }
               

               
               while($row1 = mysqli_fetch_array($searchresult)):;
               ?>
                  <tr class="gradeX">
                  <td><?php echo $row1['gsisloan_id'];?></td>
                  <td><?php echo $row1['emp_id'];?></td>
                  <td><?php echo $row1['start_date'];?></td>
                  <td><?php echo $row1['end_date'];?></td>
                  <td><?php echo $row1['loan_amount'];?></td>
                  <td><?php echo $row1['monthly_deduct'];?></td>
                  <td><?php echo $row1['no_of_pays'];?></td>
                  <td><?php echo $row1['status'];?></td>
                  <td><?php echo $row1['admin_id'];?></td>
                </tr>
              <?php endwhile;?>
              </form>
                
            

              
               
              </tbody>
            </table>


            <div class = "span9">
                  <a href ="empLoans.php" class = "btn btn-success" style = "float:right; margin-left: 4px;"><span class="icon"><i class="icon-refresh"></i></span> Refresh</a>
                 <!-- <small><?php echo $attrecordview; ?></small>  -->


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
          </div><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB--><!--EMPLOYEE TAB-->
          </div>
          
        </div>
      </div>
    </div>
  </div>


  
</div>
<?php

?>



<div class="row-fluid">
  <!-- <div id="footer" class="span12" style="position: fixed; bottom: 0; left: 0; width: 100%; text-align: center; padding: 10px;"> 2023 &copy; WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</div> -->
</div>

<script src="../js/maruti.dashboard.js"></script> 
<script src="../js/excanvas.min.js"></script> 

<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.flot.min.js"></script> 
<script src="../js/jquery.flot.resize.min.js"></script> 
<script src="../js/jquery.peity.min.js"></script> 
<script src="../js/fullcalendar.min.js"></script> 
<script src="../js/maruti.js"></script> 
</body>
</html>
<style>
              .widget-box {
  border-radius: 10px; /* You can adjust the value to control the amount of rounding */
  border: 1px solid #ccc; /* Optional: You can add a border for further styling */
  padding: 15px; /* Optional: Add padding to the box for better appearance */
}
@media (max-width: 768px) {
  .widget-box{
    margin-top:70px;
  }
  .span2 {
    margin: auto;
    margin-top: 70px; /* This will center the widget-box */
  }

  .span2 {
    /* Adjust the styles for list items inside widget-title at smaller screens */
    display: block;
    margin-bottom: 10px;
  }

  .active {
    /* Adjust the styles for the active class when the screen width is 768px or less */
    
  }
}
          </style>