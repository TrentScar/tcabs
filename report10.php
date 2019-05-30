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
    $query = "SELECT * FROM supervisormeeting INNER JOIN team ON supervisormeeting.TeamID = team.TeamID WHERE CONCAT(`TeamName`) LIKE '%".$valueToSearch."%'";
    $search_result = filterTable($query);

}
 else {
    $query = "SELECT * FROM supervisormeeting INNER JOIN team ON supervisormeeting.TeamID = team.TeamID";
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
					if($userType=='supervisor') {
						$roleFound = TRUE;
				?>
    <!--<div class="btn-group btn-group-justified">
      <a href="report.php" class="btn btn-primary">Overview</a>
      <a href="report1.php" class="btn btn-primary">1</a>
      <a href="report2.php" class="btn btn-primary">2</a>
      <a href="report3.php" class="btn btn-primary">3</a>
      <a href="report4.php" class="btn btn-primary">4</a>
      <a href="report5.php" class="btn btn-primary">5</a>
      <a href="report6.php" class="btn btn-primary">6</a>
      <a href="report7.php" class="btn btn-primary">7</a>
      <a href="report8.php" class="btn btn-primary">8</a>
      <a href="report9.php" class="btn btn-primary">9</a>
      <a href="report10.php" class="btn btn-primary">10</a>
    </div>-->
    <p class="h4 mb-4 text-center">Meeting summary</p>

    <body>
        <form action="report10.php" method="post">
            <input type="text" name="valueToSearch" placeholder="Search Team Name"><br><br>
            <input type="submit" name="search" value="Filter"><br><br>

            <table>
                <tr>
                  <th>Team Name</th>
                  <th>Project Manager</th>
                  <th>Start Time</th>
                  <th>End Time</th>
                  <th>Location</th>
                  <th>Agenda</th>
                  <th>Comments</th>
                </tr>

      <!-- populate table from mysql database -->
                <?php while($row = mysqli_fetch_array($search_result)):?>
                <tr>
                  <td><?php echo $row["TeamName"];?></td>
                  <td><?php echo $row["ProjectManager"]; ?></td>
                  <td><?php echo $row["StartTime"]; ?></td>
                  <td><?php echo $row["EndTime"]; ?></td>
                  <td><?php echo $row["Location"]; ?></td>
                  <td><?php echo $row["Agender"]; ?></td>
                  <td><?php echo $row["Comments"]; ?></td>
                </tr>
                <?php endwhile;?>
            </table>
            <br>
            <br>
            <div class="btn-group btn-group-justified">
              <a href="report10.php" class="btn btn-primary">Clear Search</a>
            </div>
        </form>
    </body>
  <?php  } }

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
