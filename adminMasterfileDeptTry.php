<?php
include("DBCONFIG.PHP");
include("LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();

if(isset($_SESSION['masterfilenotif'])){

$mfnotif = $_SESSION['masterfilenotif'];
?>  
<script>
alert("<?php echo $mfnotif;?>");
</script>
<?php
}

$master = $_SESSION['master'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
  <link rel="stylesheet" href="../css/fullcalendar.css" />
  <link rel="stylesheet" href="../css/maruti-style.css" />
  <link rel="stylesheet" href="../css/maruti-media.css" class="skin-color" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
    <title>Manage Leave</title>
</head>
<body>
    
<?php
  include('navbaradmin.php');
  ?>
<div class="title d-flex justify-content-center pt-3">
      <h3>
       MANAGE DEPARTMENTS
      </h3>
    </div>
    
    <div class="d-flex justify-content-end mt-3 mb-1">
    <div class="button">
<a href="admin/adminADDdepartment.php" class="btn btn-info"><i class="fa-solid fa-plus"></i> Add Department</a>  
<a href="adminMasterfileDeptTry.php" class="btn btn-success"> <i class="fa-solid fa-arrows-rotate"></i></a>
</div>
    </div>
  
  <div class="table card shadow">

 
    <table class="table table-striped table-responsive-lg table-bordered">
    <thead class="table-dark">
<style>
  tbody tr {
    display: table-row;
    vertical-align: middle; /* You can change this to 'top' or 'bottom' based on your preference */
  }
</style>
          <tr>
            <th>Deparment ID</th>
            <th>Deparment Name</th>
            <th class="col-2">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php
              $results_perpageDEPT = 20;

              if (isset($_GET['page']))
                {
                  $pageDEPT = $_GET['page'];
                } 
              else 
                {
                  $pageDEPT=1;
                }

                $start_fromDEPT = ($pageDEPT-1) * $results_perpageDEPT;
                $searchqueryDEPT ="SELECT * FROM DEPARTMENT ORDER BY dept_ID ASC LIMIT $start_fromDEPT,".$results_perpageDEPT;
                $searchresultDEPT= filterTableDEPT($searchqueryDEPT);

               function filterTableDEPT($searchqueryDEPT)
               {

                    $connDEPT = mysqli_connect("localhost:3307","root","","masterdb");
                    $filter_ResultDEPT = mysqli_query($connDEPT,$searchqueryDEPT) or die ("failed to query masterfile ".mysql_error());
                    return $filter_ResultDEPT;
               }

                $countdataqryDEPT = "SELECT COUNT(dept_ID) AS total FROM DEPARTMENT";
                $countdataqryresultDEPT = mysqli_query($conn,$countdataqryDEPT) or die ("FAILED TO EXECUTE COUNT QUERY ". mysql_error());
                $rowDEPT = $countdataqryresultDEPT->fetch_assoc();
                $totalpagesDEPT=ceil($rowDEPT['total'] / $results_perpageDEPT);
                while($row1DEPT = mysqli_fetch_array($searchresultDEPT)):;
               ?>
                  <tr class="gradeX">
                  <td><?php echo $row1DEPT['dept_prefix_ID'],$row1DEPT['dept_ID'];?></td>
                  <td><?php echo $row1DEPT['dept_NAME'];?></td>
                 
                  <td class="d-flex align-items-center justify-content-center">
                    <a href = "admin/adminEDITMasterfileDept.php?id=<?php echo $row1DEPT['dept_NAME']?>" class = "btn btn-info btn-mini"><span class="icon"><i class="icon-eye-open"></i></span> View</a>
                    <a href = "admin/adminDELETEMasterfileDept.php?id=<?php echo $row1DEPT['dept_ID'];?>" class = "btn btn-danger btn-mini"><span class="icon"><i class="icon-trash"></i></span> Delete</a>
                  </td>
                </tr>
              <?php endwhile;?>


        </tbody>
    </table>
  </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>