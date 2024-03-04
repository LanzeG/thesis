<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="style.css">
   <script >document.addEventListener("DOMContentLoaded", function(event) {
   
   const showNavbar = (toggleId, navId, bodyId, headerId) =>{
   const toggle = document.getElementById(toggleId),
   nav = document.getElementById(navId),
   bodypd = document.getElementById(bodyId),
   headerpd = document.getElementById(headerId)
   
   // Validate that all variables exist
   if(toggle && nav && bodypd && headerpd){
   toggle.addEventListener('click', ()=>{
   // show navbar
   nav.classList.toggle('show')
   // change icon
   toggle.classList.toggle('bx-x')
   // add padding to body
   bodypd.classList.toggle('body-pd')
   // add padding to header
   headerpd.classList.toggle('body-pd')
   })
   }
   }
   
   showNavbar('header-toggle','nav-bar','body-pd','header')
   
   /*===== LINK ACTIVE =====*/
   const linkColor = document.querySelectorAll('.nav_link')
   
   function colorLink(){
   if(linkColor){
   linkColor.forEach(l=> l.classList.remove('active'))
   this.classList.add('active')
   }
   }
   linkColor.forEach(l=> l.addEventListener('click', colorLink))
   
    // Your code to run since DOM is loaded and ready
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
        <style>
        .sidebar li .submenu {
            list-style: none;
            margin: 0;
            padding: 0;
            padding-left: 1rem;
            padding-right: 1rem;
        }
    </style>
    <title>Your Account</title>
</head>

<body id="body-pd">
    <header class="header" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
        <li class="dropdown" id="notification-icon">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa-solid fa-bell"></i>
        <span class="badge"></span>
      </a>
      <ul class="dropdown-menu">
      </ul>
    </li>
    </header>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav sidebar">
            <div> <a href="#" class="nav_logo"> <i class='bx bx-layer nav_logo-icon'></i> <span class="nav_logo-name">Manage Account</span> </a>
                <div class="nav_list">

                     <a href="try.php" class="nav_link "> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Dashboard</span> </a> 
                     <!-- <a href="#" class="nav_link"> <i class='bx bx-user nav_icon'></i> <span class="nav_name">Apply Overtime</span> </a>  -->
                     <a href="newapplyovertime.php" class="nav_link"> <i class='bx bx-message-square-detail nav_icon'></i> <span class="nav_name">Apply Overtime</span> </a> 
                     <a href="LeaveApplication.php" class="nav_link"> <i class='bx bx-bookmark nav_icon'></i> <span class="nav_name">Leave</span> </a>
                     
                     <li class="nav-item has-submenu ">
    
                     <a href="Employee/empATTENDANCErecords.php" class="nav_link nav-link mb-2 mt-2 " > <i class='bx bx-folder nav_icon'></i> <span class="nav_name">My Records</span> </a>
		<ul class="submenu collapse">
			<li><a class="nav-link text-white" href="empnewATTENDANCE.php"><i class='fa-solid fa-clipboard nav_icon'></i> Attendance</a></li>
			<li><a class="nav-link text-white" href="Employee/empPAYROLLrecords.php"><i class='fa-solid fa-clock nav_icon'></i> Payroll</a></li>
		</ul>
   
	</li>                       <a href="Employee/empLoans.php" class="nav_link"> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span class="nav_name">Loans </span></a> </div>
            </div> <a href="LOGOUT.PHP" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">SignOut</span> </a>
        </nav>
    </div>
    <!--Container Main start-->
    <script>
// Function to update the notification count and items
function updateNotifications() {
  // Make an AJAX request to your notifications.php
  $.ajax({
    url: 'notifications.php',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
      // Update or create dropdown items
      var dropdownMenu = $('#notification-icon .dropdown-menu');
      dropdownMenu.empty();

      // If no unread notifications, display a message
      if (response.count === 0) {
        dropdownMenu.append('<li><a href="#">No new notifications</a></li>');
        // Hide the badge when there are no notifications
        $('#notification-icon .badge').hide();
      } else {
        // Update the badge count and show it
        $('#notification-icon .badge').text(response.count).show();

        // Iterate through each notification in the response
        response.notifications.forEach(function(notification) {
          // Specify the appropriate links for each notification based on its type
          var link = '';

          if (notification.type === 'Overtime') {
            link = './OVERTIME/adminOT.php';
          } else if (notification.type === 'Leave') {
            link = './LEAVES/adminLEAVES.php';
          }

          // Make notifications clickable and link to the appropriate page
          dropdownMenu.append('<li><a href="' + link + '">' + notification.message + '</a></li>');
        });
      }

      // Add "See All Notifications" link
      dropdownMenu.append('<li class="see-all"><a href="all_notifications.php">See All Notifications</a></li>');
    },
    error: function(error) {
      console.error('Error checking notifications:', error.responseText);
    }
  });
}

// Use Bootstrap's built-in methods to handle dropdown toggle
$('#notification-icon').on('click', function (e) {
  e.stopPropagation(); // Prevent the event from reaching the document click handler
  $(this).toggleClass('open');

  // Clear the badge count when dropdown is opened
  $('#notification-icon .badge').text('');

  // Mark notifications as read when dropdown is clicked
  if ($(this).hasClass('open')) {
    markNotificationsAsRead();
  }
});

// Close the dropdown when clicking outside
$(document).on('click', function (e) {
  if (!$(e.target).closest('.dropdown').length) {
    $('#notification-icon').removeClass('open');
  }
});

// Function to mark notifications as read
function markNotificationsAsRead() {
  $.ajax({
    url: 'notifications.php',
    type: 'GET',
    data: { mark_as_read: true }, // Send a parameter to indicate marking as read
    success: function(response) {
      // Do something if needed
    },
    error: function(error) {
      console.error('Error marking notifications as read:', error.responseText);
    }
  });
}

// Initial update
updateNotifications();

// Set an interval to periodically update notifications (every 1 minute, adjust as needed)
setInterval(updateNotifications, 60000);

</script>
</body>
</html>