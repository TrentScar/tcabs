<?php
	require_once("classes.php");
	session_start();
	if (!isset($_SESSION['logged_in'])) {
		header('Location: login.php');
		mysqli_query("CALL GenerateAccrualTasks()");
	} else {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
		}
	}
if(isset($_POST['search']))
{
    $valueToSearch = $_POST['valueToSearch'];
    // search in all table columns
    // using concat mysql function
	
	//$SQL = 'CALL GenerateAccrualTasks()';
	//mysqli_query($connection, "CALL GenerateAccrualTasks()")
	//$q = $BudgetObj->query($sql);

	
	$query = "select TeamName,ProjectName,Budget,Round(Total_Pay,2) as Expense_To_Date,Budget - Round(Total_Pay,2) as Remaining_Budget from team natural join teamProjects t1 inner join (select TeamProjectID, sum(Pay) as Total_Pay from ProjectCosts group by TeamProjectID) t2 on t1.TeamProjectID = t2.TeamProjectID natural join offeringStaff inner join unitoffering on offeringstaff.unitofferingid = unitoffering.unitofferingid where CONCAT(`teamName`,`unitCode`,`term`,`year`) LIKE '%".$valueToSearch."%'";
      
    $search_result = filterTable($query);

}
 else {
	//$BudgetObj = new Budget;
	//mysqli_query($connection, "CALL GenerateAccrualTasks()")
	//$SQL = 'CALL GenerateAccrualTasks()';
	//$q = $BudgetObj->query($sql);
   $query = "select TeamName,ProjectName,Budget,Round(Total_Pay,2) as Expense_To_Date,Budget - Round(Total_Pay,2) as Remaining_Budget from team natural join teamProjects t1 inner join (select TeamProjectID, sum(Pay) as Total_Pay from ProjectCosts group by TeamProjectID) t2 on t1.TeamProjectID = t2.TeamProjectID natural join offeringStaff inner join unitoffering on offeringstaff.unitofferingid = unitoffering.unitofferingid";
   $search_result = filterTable($query);
}

// function to connect and execute the query
function filterTable($query)
{
    $connect = mysqli_connect("localhost", "root", "", "tcabs");
    $filter_Result = mysqli_query($connect, $query);
    return $filter_Result;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <body class="loggedin">
		<?php include "styles/stylesheet.php"; ?>

			<body class="loggedin">
				<?php include "views/header.php"; ?>
			<div class="content">
			<h2>Generate Reports</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
			<div>
				<?php
				//Check the Users role to see if they have access to this
				$roleFound = FALSE;
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='convenor') {
						$roleFound = TRUE;
					} else if($userType=='supervisor') {
						$roleFound = TRUE;
				} }?>
				
				
			<div class="btn-group btn-group-justified">
				<?php
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='admin') { ?>
						<a href="report1.php" class="btn btn-primary">Registered Convenors</a>
				<?php }} ?>

				<?php
						$report2 = FALSE;
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='admin') {
						$report2 = TRUE;
				 	} else if($userType=='convenor') {
						$report2 = TRUE;
				 	} }
					if($report2 == TRUE) { ?>
						<a href="report2.php" class="btn btn-primary">Enrolled Students</a>
				<?php } else {}?>

				<?php
						$report3 = FALSE;
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='admin') {
						$report3 = TRUE;
					} else if($userType=='convenor') {
						$report3 = TRUE;
					} }
					if($report3 == TRUE) { ?>
						<a href="report3.php" class="btn btn-primary">Registered Supervisors</a>
				<?php } else {}?>

				<?php
						$report4 = FALSE;
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='convenor') {
						$report4 = TRUE;
					} else if($userType=='supervisor') {
						$report4 = TRUE;
					} }
					if($report4 == TRUE) { ?>
						<a href="report4.php" class="btn btn-primary">Registered Projects</a>
				<?php } else {}?>

				<?php
						$report5 = FALSE;
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='convenor') {
						$report5 = TRUE;
					} else if($userType=='supervisor') {
						$report5 = TRUE;
					} }
					if($report5 == TRUE) { ?>
						<a href="report5.php" class="btn btn-primary">Registered Teams</a>
				<?php } else {}?>

				<?php
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='supervisor') { ?>
						<a href="report6.php" class="btn btn-primary">Meeting Attendees</a>
				<?php }} ?>
				
				<?php
						$report7 = FALSE;
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='convenor') {
						$report7 = TRUE;
					} else if($userType=='supervisor') {
						$report7 = TRUE;
					} }
					if($report7 == TRUE) { ?>
						<a href="report7.php" class="btn btn-primary">Budget Overview</a>
				<?php } else {}?>

				<?php
						$report8 = FALSE;
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='convenor') {
						$report8 = TRUE;
					} else if($userType=='supervisor') {
						$report8 = TRUE;
					} }
					if($report8 == TRUE) { ?>
						<a href="report8.php" class="btn btn-primary">Team Overview</a>
				<?php } else {}?>
				
				<?php
						$report9 = FALSE;
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='convenor') {
						$report9 = TRUE;
					} else if($userType=='supervisor') {
						$report9 = TRUE;
					} }
					if($report9 == TRUE) { ?>
						<a href="report9.php" class="btn btn-primary">unit Overview</a>
				<?php } else {}?>

				<?php
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='supervisor') { ?>
						<a href="report10.php" class="btn btn-primary">Meeting Summary</a>
				<?php }} ?>
			</div>
    <br>
    <p class="h4 mb-4 text-center">7. unit budgets</p>

    <body>
        <form action="report7.php" method="post">
            <input type="text" name="valueToSearch" placeholder="Search.."><br><br>
            <input type="submit" name="search" value="Filter"><br><br>

            <table>
                <tr>
                  
                  <th>Team Name</th>
                  <th>Project Name</th>
				  <th>Budget</th>
                  <th>Budget used</th>
                  <th>Remaining Budget</th>
                </tr>

      <!-- populate table from mysql database -->
                <?php while($row = mysqli_fetch_array($search_result)):?>
                <tr>
                  
                  <td><?php echo $row["TeamName"];?></td>
                  <td><?php echo $row["ProjectName"]; ?></td>
				  <td><?php echo $row["Budget"]; ?></td>
                  <td><?php echo $row["Expense_To_Date"]; ?></td>
                  <td><?php echo $row["Remaining_Budget"]; ?></td>
                </tr>
                <?php endwhile;?>
            </table>
            <br>
            <br>
            <div class="btn-group btn-group-justified">
              <a href="report7.php" class="btn btn-primary">Clear Search</a>
            </div>
        </form>
    </body>
  <?php

	//If they dont have correct permission

	if ($roleFound == FALSE) { ?>
		
		<h2>Permission Denied</h2>
		<div>
		<p>Sorry, you do not have access to this page. Please contact your administrator.</p>
		</div>
	<?php } ?>
	
		</div>
		
	</div>
</body>
<?php include "views/footer.php"; ?>
</html>
