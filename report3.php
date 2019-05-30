<?php
	require_once("classes.php");
	session_start();
	if (!isset($_SESSION['logged_in'])) {
		header('Location: login.php');
	} else {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
		}
	}
if(isset($_POST['search']))
{
    $valueToSearch = $_POST['valueToSearch'];
    // search in all table columns
    // using concat mysql function
    $query = "SELECT unitCode,term,year,fName,lName From unitoffering INNER JOIN offeringproject ON unitoffering.unitOfferingID = offeringproject.UnitOfferingID INNER JOIN project ON offeringproject.ProjectName = project.ProjectName INNER JOIN teamprojects ON project.ProjectName = teamprojects.ProjectName INNER JOIN team ON teamprojects.TeamID = team.TeamID INNER JOIN offeringstaff ON team.OfferingStaffID = offeringstaff.OfferingStaffID INNER JOIN users ON offeringstaff.UserName = users.email WHERE CONCAT(`fName`,`lName`,`term`,`year`,`unitCode`) LIKE '%".$valueToSearch."%'";
    $search_result = filterTable($query);

}
 else {
   $query = "SELECT unitCode,term,year,fName,lName From unitoffering INNER JOIN offeringproject ON unitoffering.unitOfferingID = offeringproject.UnitOfferingID INNER JOIN project ON offeringproject.ProjectName = project.ProjectName INNER JOIN teamprojects ON project.ProjectName = teamprojects.ProjectName INNER JOIN team ON teamprojects.TeamID = team.TeamID INNER JOIN offeringstaff ON team.OfferingStaffID = offeringstaff.OfferingStaffID INNER JOIN users ON offeringstaff.UserName = users.email group by fName,lName";
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
			<h2>Generated Report</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>

			<div>
				<?php
				//Check the Users role to see if they have access to this
				$roleFound = FALSE;
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='admin') {
						$roleFound = TRUE;
					} else if($userType=='convenor') {
						$roleFound = TRUE;
				} }?>


				<?php
				//If they have the correct role to view the page
				if($roleFound == TRUE) { ?>

    <p class="h4 mb-4 text-center">List of registered supervisors</p>

    <body>
        <form action="report3.php" method="post">
            <input type="text" name="valueToSearch" placeholder="Search.."><br><br>
            <input type="submit" name="search" value="Filter"><br><br>

            <table>
                <tr>
                  <th>Unit Code</th>
                  <th>Unit Offering Period</th>
                  <th>Supervisor Name</th>

                </tr>

      <!-- populate table from mysql database -->
                <?php while($row = mysqli_fetch_array($search_result)):?>
                <tr>
                  <td><?php echo $row["unitCode"];?></td>
                  <td><?php echo $row["term"]; ?> - <?php echo $row["year"]; ?></td>
                  <td><?php echo $row["fName"]; ?> <?php echo $row["lName"]; ?></td>
                </tr>
                <?php endwhile;?>
            </table>
            <br>
            <br>
            <div class="btn-group btn-group-justified">
              <a href="report3.php" class="btn btn-primary">Clear Search</a>
            </div>
        </form>
    </body>
<?php } ?>

<?php
	//If they dont have correct permission
if ($roleFound == FALSE) { ?>

		<h2>Permission Denied</h2>
		<div>
		<p>Sorry, you do not have access to this page. Please contact your administrator.</p>
		</div>
	<?php  }  ?>
		</div>
	</div>
</body>
<?php include "views/footer.php"; ?>
</html>
