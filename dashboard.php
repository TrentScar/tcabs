<!-- The main page of the system will show relevant functionality according to user role -->
<?php
	require_once("classes.php");
	session_start();

	if(!isset($_SESSION['logged_in'])) {
		header("location: /tcabs/login.php");
	}

	// similarly team - teamMember - Project - classes are alos done 
	// find functions to use in classes.php
	// as of now classes regarding Peer Assessments are left
	// till then you can use these to complete convenor functions

	// IF YOU NEED ANYTHING ELSE PLEASE SHOOT A MESSAGE ON SLACK
	
	/*
	// ##Team Project##
	$tProjObj = new TeamProject; // make an object of class TeamProject

	// add project to registered team
	// need - Project Name - TeamName - Convenor - unitCode - term - year
	echo 'Adding Team Project<br>';
	$tProjObj->addTeamProject("Big Test Project", "just a name", "dtargaryen@gmail.com", "ICT30001", "Semester 2", "2018");

	// get TeamProject object using TeamProjectID with all info
	echo 'Getting Team Project ID = 1<br>';
	print_r($tProjObj->getTeamProject(1));
	echo '<br><br>';

	// search allocated projects to registered teams
	echo "Getting Team Project with searchQuery = 'pro'<br>";
	$searchQuery = "pro"; // search bar value may contain TeamName/ProjectName
	print_r($tProjObj->searchTeamProject("%{$searchQuery}%")); // print everything
	echo '<br><br>';

	// ##ProjectRole##
	$projRoleObj = new ProjRole; // make an object of class ProjRole

	// get Project Role object with all info - using role name
	echo 'Getting Project Role = "Project Manager"<br>';
	print_r($projRoleObj->getProjRole("Project Manager"));
	echo '<br><br>';

	// get all project roles in multi dimensional array
	echo 'Getting all project roles"<br>';
	print_r($projRoleObj->getAllProjRoles()); // can be used for dropdown
	echo '<br><br>';

	// search allocated projects to registered teams
	echo "Getting all Project roles with searchQuery = 'pro'<br>";
	$searchQuery = "pro"; // search bar value may contain Role Name/Role Desc
	print_r($projRoleObj->searchProjRole("%{$searchQuery}%")); // print everything
	echo '<br><br>';

	/*
	// add Project Role
	// need - RoleName - salary - desc
	echo 'Adding Project Role<br>';
	print_r($projRoleObj->getProjRole("Project Manager"));
	echo '<br><br>';
 */

	/*
	// ## TASK ##

	$taskObj = new Task;

	// add Task
	// error due to error in wrong table link in ERD
	//$taskObj->addTask("astark@gmail.com", "Big Test Project", "just a name", 
	//				"dtargaryen@gmail.com", "ICT30001", "Semester 2", "2018", "1", 
	//				"task desc", "Developer");

	// search task
	echo "searchQuery = 'just a name'<br>";
	$searchQuery = "just a name"; // search bar value may contain TeamName/ProjectName/RoleName
	print_r($taskObj->searchTask("%{$searchQuery}%")); // print everything
	echo '<br><br>';
	*/

	// ## MEETING ##

	/*
	$meetingObj = new Meeting;

	echo "adding meeting";
	$meetingObj->addMeeting("2019-07-14 23:40:00" ,"2019-07-14 23:45:00", "maccas", "just a name", "dtargaryen@gmail.com", "ICT30001", "Semester 2", "2018"); //works

	// get Project Role object with all info - using role name
	echo 'Getting Meeting ID : 1"<br>';
	print_r($meetingObj->getMeeting("1"));
	echo '<br><br>';

	// search meeting
	echo "searchQuery = 'dtargaryen@gmail.com'<br>";
	$searchQuery = "dtargaryen@gmail.com"; // search bar value may contain Team Name or supervisor username
	print_r($meetingObj->searchMeeting("%{$searchQuery}%")); // print everything
	echo '<br><br>';

	// delete meeting
	echo 'Deleting above meeting<br>';
	$meetingObj->deleteMeeting("2019-07-14 23:40:00", "just a name", "dtargaryen@gmail.com", "ICT30001", "Semester 2", "2018");

	// update to do
	*/

	// ## Meeting Attendees ##

	/*
	$attendeeObj = new MeetingAttendee;

	echo "adding attendee<br>";
	$attendeeObj->addAttendee("astark@gmail.com", "2019-07-14 23:40:00", "just a name", "dtargaryen@gmail.com", "ICT30001", "Semester 2", "2018"); //works

	echo "getting attendee<br>";
	print_r($attendeeObj->getAttendee("1")); // get by attendee ID

	// search attendee
	echo "searchQuery = 'just a name'<br>";
	$searchQuery = "just a name"; // search bar value may contain Team name
	print("<pre>".print_r($attendeeObj->searchAttendee("%{$searchQuery}%"), 1)."</pre>"); // print everything
	echo '<br><br>';
	*/
	
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Stylesheets -->
		<?php include "styles/stylesheet.php"; ?>
  </head>

  <body class="loggedin">
		<?php include "views/header.php"; ?>
  
		<div class="content">
			<h2>Welcome, <?php echo $_SESSION['loggedUser']->fName?></h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
			<div>
				<p>Welcome</p>
			</div>
		</div>
  </body>
  <?php include "views/footer.php"; ?>  
</html>
