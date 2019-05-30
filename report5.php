<?php
	require_once("classes.php");
	session_start();
	if (!isset($_SESSION['logged_in'])) {
		header('Location: login.php');
	} else {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
		}
	}
  if(isset($_POST['search'])) {
    $firsttable = TRUE;
    $valueToSearch = $_POST['valueToSearch'];
    // search in all table columns
    // using concat mysql function
    $query = "SELECT unitCode,term,year,team.TeamName,ProjectManager From unitoffering INNER JOIN offeringproject ON unitoffering.unitOfferingID = offeringproject.UnitOfferingID INNER JOIN project ON offeringproject.ProjectName = project.ProjectName INNER JOIN teamprojects ON project.ProjectName = teamprojects.ProjectName INNER JOIN team ON teamprojects.TeamID = team.TeamID WHERE CONCAT(`TeamName`,`term`,`year`,`unitCode`) LIKE '%".$valueToSearch."%'";
    $search_result = filterTable($query);
  }
  else if(isset($_POST['details'])) {
  $firsttable = FALSE;
  $TeamName = $_POST['details'];
    $query = "SELECT team.TeamName,fName,lName,email,pNum From team
    INNER JOIN teammember ON team.TeamID = teammember.TeamID
    INNER JOIN enrolment ON teammember.EnrolmentID = enrolment.enrolmentID
    INNER JOIN users ON enrolment.sUserName = users.email
    WHERE team.TeamName LIKE '".$TeamName."'";
    $search_result = filterTable($query);
}
 else {
    $firsttable = TRUE;
    $query = "SELECT unitCode,term,year,team.TeamName,ProjectManager From unitoffering INNER JOIN offeringproject ON unitoffering.unitOfferingID = offeringproject.UnitOfferingID INNER JOIN project ON offeringproject.ProjectName = project.ProjectName INNER JOIN teamprojects ON project.ProjectName = teamprojects.ProjectName INNER JOIN team ON teamprojects.TeamID = team.TeamID";
    $search_result = filterTable($query);
}

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
					if($userType=='convenor') {
						$roleFound = TRUE;
					} else if($userType=='supervisor') {
						$roleFound = TRUE;
				} }?>


				<?php
				//If they have the correct role to view the page

    if($roleFound == TRUE) {
  	if ($firsttable == TRUE) { ?>

    <p class="h4 mb-4 text-center">List of registered teams</p>

    <body>
        <form action="report5.php" method="post">
            <input type="text" name="valueToSearch" placeholder="Search.."><br><br>
            <input type="submit" name="search" value="Filter"><br><br>
            <table>
                <tr>
                  <th>Unit Code</th>
                  <th>Unit Offering Period</th>
                  <th>Team Name</th>
                  <th>Project Manager</th>
                  <th>Team Members</th>

                </tr>

      <!-- populate table from mysql database -->
                <?php while($row = mysqli_fetch_array($search_result)):?>
                <tr>
                  <td><?php echo $row["unitCode"];?></td>
                  <td><?php echo $row["term"]; ?> - <?php echo $row["year"]; ?></td>
                  <td><?php echo $row["TeamName"]; ?></td>
                  <td><?php echo $row["ProjectManager"]; ?></td>
                  <td><button type="submit" class="btn btn-primary" name="details" value="<?php echo $row['TeamName'];?>" >Details </button></td>
                </tr>
                <?php endwhile;?>
            </table>
            <br>
            <br>
            <div class="btn-group btn-group-justified">
              <a href="report5.php" class="btn btn-primary">Clear Search</a>
            </div>
        </form>

    </body>
<?php }
  	if ($firsttable == FALSE) { ?>

      <p class="h4 mb-4 text-center">List of registered teams</p>

      <body>
              <table>
                  <tr>
                    <th>Team Name</th>
                    <th>Student Name</th>
                    <th>Student Phone</th>
                    <th>Student Email</th>
                  </tr>

        <!-- populate table from mysql database -->
                  <?php while($row = mysqli_fetch_array($search_result)):?>
                  <tr>
                    <td><?php echo $row["TeamName"];?></td>
                    <td><?php echo $row["fName"]; ?> <?php echo $row["lName"]; ?></td>
                    <td><?php echo $row["pNum"]; ?></td>
                    <td><?php echo $row["email"]; ?></td>
                  </tr>
                  <?php endwhile;?>
              </table>
              <br>
              <br>
              <div class="btn-group btn-group-justified">
                <a href="report5.php" class="btn btn-primary">Clear Search</a>
              </div>
          </form>

      </body>

<?php }}
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
