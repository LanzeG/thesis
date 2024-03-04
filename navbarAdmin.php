<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const showNavbar = (toggleId, navId, bodyId, headerId) => {
                const toggle = document.getElementById(toggleId),
                    nav = document.getElementById(navId),
                    bodypd = document.getElementById(bodyId),
                    headerpd = document.getElementById(headerId);

                // Validate that all variables exist
                if (toggle && nav && bodypd && headerpd) {
                    toggle.addEventListener('click', () => {
                        // show navbar
                        nav.classList.toggle('show');
                        // change icon
                        toggle.classList.toggle('bx-x');
                        // add padding to body
                        bodypd.classList.toggle('body-pd');
                        // add padding to header
                        headerpd.classList.toggle('body-pd');
                    });
                }
            }

            showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

            /*===== LINK ACTIVE =====*/
            const linkColor = document.querySelectorAll('.nav_link')

            function colorLink() {
                if (linkColor) {
                    linkColor.forEach(l => l.classList.remove('active'))
                    this.classList.add('active')
                }
            }

            linkColor.forEach(l => l.addEventListener('click', colorLink))
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.sidebar .nav-link').forEach(function (element) {

                element.addEventListener('click', function (e) {

                    let nextEl = element.nextElementSibling;
                    let parentEl = element.parentElement;

                    if (nextEl) {
                        e.preventDefault();
                        let mycollapse = new bootstrap.Collapse(nextEl);

                        if (nextEl.classList.contains('show')) {
                            mycollapse.hide();
                        } else {
                            mycollapse.show();
                            // find other submenus with class=show
                            var opened_submenu = parentEl.parentElement.querySelector('.submenu.show');
                            // if it exists, then close all of them
                            if (opened_submenu) {
                                new bootstrap.Collapse(opened_submenu);
                            }
                        }
                    }
                }); // addEventListener
            }) // forEach
        });
    </script>

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <style>
        .sidebar li .submenu {
            list-style: none;
            margin: 0;
            padding: 0;
            padding-left: 1rem;
            padding-right: 1rem;
        }
    </style>

    <title>Document</title>
</head>

<body id="body-pd">
    <header class="header" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> ADMIN MANAGEMENT </div>
        <!-- <div class="header_img"> <img src="https://i.imgur.com/hczKIze.jpg" alt=""> </div> -->
    </header>
    <div class="l-navbar" id="nav-bar">

        <nav class="nav sidebar">
            
            <div> <a href="#" class="nav_logo"> <i class='bx bx-layer nav_logo-icon'></i> <span class="nav_logo-name">Manage Account</span> </a>
               
            <div class="nav_list">
            <a href="admintry.php" class="nav_link  mb-2 "  > <i class='bx bx-grid-alt nav_icon'></i>Dashboard</a> 

    <li class="nav-item has-submenu ">
    <a href="try.php" class="nav_link nav-link mb-0 mt-3 " > <i class='bx bx-message-square-detail nav_icon'></i>Manage Data </a> 
		<ul class="submenu collapse">
            
			<li><a class="nav-link text-white" href="adminMasterfileTry.php" ><i class='bx bx-user nav_icon'></i> Employees </a></li>
			<li><a class="nav-link text-white" href="ADMIN/adminMasterfileDept.php" ><i class='bx bx-buildings nav_icon'></i> Department </a></li>
			<li><a class="nav-link text-white" href="ADMIN/adminMasterfileLeaves.php" ><i class='bx bx-time nav_icon'></i> Leave </a></li>
            <li><a class="nav-link text-white" href="ADMIN/adminPAYROLLPERIODS.php"><i class='bx bx-calendar nav_icon'></i> Payroll Period </a></li>
            <li><a class="nav-link text-white" href="ADMIN/adminPositions.php" ><i class='fa-solid fa-sitemap nav_icon'></i> Positions </a></li>
            <li><a class="nav-link text-white" href="ADMIN/adminSalaryGrades.php" ><i class="fa-solid fa-file-invoice-dollar nav_icon"></i> Salary Grades</a></li>


		</ul>
	</li>
    <li class="nav-item has-submenu ">
    <a href="admin/adminMasterfile.php" class="nav_link nav-link mb-2 mt-2 " > <i class="fa-solid fa-users nav-icon"></i> <span class="nav_name">Manage Attendance</span> </a>
		<ul class="submenu collapse">
			<li><a class="nav-link text-white" href="ADMIN/adminATTENDANCErecords.php"><i class='fa-solid fa-clipboard nav_icon'></i> Records</a></li>
			<li><a class="nav-link text-white" href="ADMIN/OVERTIME/adminOT.php"><i class='fa-solid fa-clock nav_icon'></i> Attendace </a></li>
            <li><a class="nav-link text-white" href="ADMIN/LEAVES/adminLeaves.php"><i class="fa-solid fa-users nav-icon"></i> Leaves </a></li>



		</ul>
	</li>

    <li class="nav-item has-submenu ">
    <a href="admin/adminMasterfile.php" class="nav_link nav-link mb-2 mt-2 " ><i class='bx bx-money-withdraw nav-icon'></i><span class="nav_name">Manage Payroll</span> </a>

    <!-- <a href="admin/adminPAYROLLINFO.php" class="nav_link nav-link mb-2 mt-2 "> <i class="fa-solid fa-receipt nav-icon"></i> <span class="nav_name">Manage Payroll</span> </a> -->
		<ul class="submenu collapse">
			<li><a class="nav-link text-white" href="ADMIN/adminPAYROLLINFO.php"><i class='fa-solid fa-user nav_icon'></i> Employees</a></li>
			<li><a class="nav-link text-white" href="#"><i class='fa-solid fa-landmark nav_icon'></i> Add Loans </a></li>
            <li><a class="nav-link text-white" href="#"><i class="fa-solid fa-money-check-dollar nav-icon"></i> Add Loan Type</a></li>
            <li><a class="nav-link text-white" href="ADMIN/adminPAYROLLProcess.php"><i class="fa-solid fa-users nav-icon"></i>Payroll Process  </a></li>
            <li><a class="nav-link text-white" href="ADMIN/admin13thmonth.php"><i class="fa-solid fa-users nav-icon"></i> 13th Month</a></li>
		</ul>
	</li>

    <li class="nav-item has-submenu ">
    <a href="try.php" class="nav_link nav-link mb-0 mt-3 " "> <i class='bx bxs-report nav-icon'></i></i>Reports</a> 
		<ul class="submenu collapse">
			<!-- <li><a class="nav-link text-white" href="#"><i class='bx bx-user nav_icon'></i>Timesheet</a></li> -->
			<li><a class="nav-link text-white" href="ADMIN/adminDTR.php"><i class='bx bx-buildings nav_icon'></i> DTR</a></li>
			<li><a class="nav-link text-white" href="ADMIN/adminPAYROLLRegister.php"><i class='bx bx-time nav_icon'></i> Payroll Register</a></li>
            <li><a class="nav-link text-white" href="ADMIN/adminPAYROLLPrintPayslip.php"><i class='bx bx-calendar nav_icon'></i> Payslip</a></li>
            <li><a class="nav-link text-white" href="ADMIN/REPORTS/adminGOVTReports.php"><i class='fa-solid fa-sitemap nav_icon'></i> Contributions</a></li>
            <li><a class="nav-link text-white" href="ADMIN/REPORTS/adminREPORTyearly.php"><i class="fa-solid fa-file-invoice-dollar nav_icon"></i> Yearly Report</a></li>


		</ul>
	</li>
                     <!-- <a href="#" class="nav_link"> <i class='bx bx-user nav_icon'></i> <span class="nav_name">Apply Overtime</span> </a>  -->
                     <!-- <a href="admin/adminMasterfile.php" class="nav_link"> <i class='bx bx-message-square-detail nav_icon'></i> <span class="nav_name">Data Management</span> </a>  -->
                     <!-- <a href="admin/adminMasterfile.php" class="nav_link mb-2 mt-2 "> <i class='bx bx-bookmark nav_icon'></i> <span class="nav_name">Attendace Management</span> </a>
                      <a href="adminPAYROLLINFO.php" class="nav_link mb-2 "> <i class='bx bx-folder nav_icon'></i> <span class="nav_name">Payroll</span> </a>
                       <a href="admin/adminTimesheet.php" class="nav_link mb-2"> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span class="nav_name">Reports </span></a> -->
                     </div>
            </div> <a href="LOGOUT.php" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">SignOut</span> </a>
        </nav>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
    <!--Container Main start-->
    
</html>

