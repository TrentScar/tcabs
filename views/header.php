<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Team Contribution and Budget System</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
			<a class="nav-link" href="/tcabs/dashboard.php">Home </a>
      </li>
      <li class="nav-item">
				<a class="nav-link" href="/tcabs/profile.php">Profile</a>
      </li>

			<?php 

				$adminArr = array(
					"registerUser.php" => "Manage Users",
					"registerUnits.php" => "Manage Units",
					"registerUnitsOfStudy.php" => "Manage Units of Study",
					// "registerStudents.php" => "Register Students",
					"registerEnrolment.php" => "Manage Enrolments",
					"../manageStaffOffering.php" => "Manage Staff Offering",
					          "../report1.php" => "Registered Convenors",
          "../report2.php" => "Enrolled Students",
          "../report3.php" => "Registered Supervisors"
				);

				$convenorArr = array(
					"../manageTeams.php" => "Manage Teams",
					"registerProject.php" => "Manage Projects",
					"setupRoles.php" => "Manage Roles",
					"allocateRoles.php" => "Allocate Roles",
					"../manageStaffOffering.php" => "Manage Staff Offering",
					          "../report2.php" => "Enrolled Students",
          "../report3.php" => "Registered Supervisors",
          "../report4.php" => "Registered Projects",
          "../report5.php" => "Registered Teams",
				);

				$supervisorArr = array(
					"supervisorMeeting.php" => "Meetings",
					          "../report4.php" => "Registered Projects",
          "../report5.php" => "Registered Teams",
          "../report10.php" => "Meeting Summaries"
				);

				$studentArr = array(
					"studentTasks.php" => "Tasks",
					"studentPeerAssess.php" => "Peer Assessment",
					"studentSupervisorMeeting.php" => "Supervisor Meetings",
					"studentTeamMeeting.php" => "Team Meetings",
					
				);

				$formArr = array();

				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if(isset($access)) {
						switch($userType) {
							case 'admin'			: $formArr = $adminArr;
																	break;
							case 'convenor'		: $formArr = $convenorArr;
																	break;
							case 'supervisor'	: $formArr = $supervisorArr;
																	break;
							case 'student'		: $formArr = $studentArr;
																	break;
						}

	  				echo "<li id='dropdown-admin' class='nav-item dropdown'>
        						<a class='nav-link dropdown-toggle' href='#' id='navbarDropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
          						<i class='fas fa-chalkboard-teacher'></i>" . " " . ucfirst($userType) . "</a> 
        						<div class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>";

						foreach($formArr as $formPath => $value) {
							echo "<a class='dropdown-item' href='/tcabs/forms/{$formPath}'>$value</a>";
						}

        		echo "</div></li>";
					}
				}
			?>
			

	  	<li class="nav-item">
				<a class="nav-link" href="/tcabs/logout.php">Logout</a>
      </li>
    </ul>
    <?php  ?>
  </div>
</nav>
