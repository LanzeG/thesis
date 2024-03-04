<?php
include("DBCONFIG.PHP");
include("LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();

if(isset($_SESSION['OTAPPROVAL'])){

$mfnotif = $_SESSION['OTAPPROVAL'];
?>  
<script>
alert("<?php echo $mfnotif;?>");
</script>
<?php
}
$currentempid = $_SESSION['empID'];
$results_perpage = 20;

               if (isset($_GET['page'])){

                    $page = $_GET['page'];
               } else {

                    $page=1;
               }




if (isset($_POST['searchbydate_btn'])){

  $start_from = ($page-1) * $results_perpage;
  $datesearch = $_POST['dphired'];
  $searchquery = "SELECT OVER_TIME.*, employees.* from employees, OVER_TIME  WHERE OVER_TIME.ot_day = '$datesearch' AND employees.emp_id = OVER_TIME.emp_id and employees.emp_id = '$currentempid' ORDER BY OVER_TIME.ot_day DESC LIMIT $start_from,".$results_perpage;  
  $search_result = filterTable($searchquery);

}else{

  $start_from = ($page-1) * $results_perpage;
  // $datesearch = $_POST['dphired'];
  $searchquery = "SELECT OVER_TIME.*, employees.* from employees, OVER_TIME  WHERE employees.emp_id = OVER_TIME.emp_id and employees.emp_id = '$currentempid' ";
  $search_result = filterTable($searchquery);


}

$countdataqry = "SELECT COUNT(ot_ID) AS total FROM OVER_TIME WHERE emp_id = '$currentempid'";
$countdataqryresult = mysqli_query($conn,$countdataqry) or die ("FAILED TO EXECUTE COUNT QUERY ". mysql_error());      
$row = $countdataqryresult->fetch_assoc();
$totalpages=ceil($row['total'] / $results_perpage);


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

<?php include('navbar.php'); ?>

<div class="container-fluid">
  <div class="row">
    <?php include('side.php'); ?> 

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-1">
      <!-- hati sa dalawa -->
      
    </main>
  



<!-- Bootstrap JS (optional, if you need Bootstrap JavaScript features) -->
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


</body>
</html>
