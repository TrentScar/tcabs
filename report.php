<?php
	require_once("classes.php");
	session_start();
	if (!isset($_SESSION['logged_in'])) {
		header('Location: login.php');
	} else {

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

		}

	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<?php include "styles/stylesheet.php"; ?>
		<link rel="stylesheet" href="/tcabs/public/css/reports.css"></link>

  <body class="loggedin">

			<body class="loggedin">
				<?php include "views/header.php"; ?>
			<div class="content">
			<h2>Available reports to generate</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
			<div>
				<?php
				//Check the Users role to see if they have access to this
				$roleFound = FALSE;
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='admin') {
						$roleFound = TRUE;
				?>
		<div class="btn-group btn-group-justified">
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
		</div>
		<br>
    <p class="h4 mb-4 text-center">Overview of Available Reports</p>
		<table>
					<tr>
						<th>Number</th>
						<th>Description</th>
					</tr>
				<?php
					$conn = mysqli_connect("localhost", "root", "", "tcabs");
					if ($conn-> connect_error){
						die("Connection failed:". $conn-> connect_error);
					}

					$sql = "SELECT report_id, report_description from reports";
					$result = $conn-> query($sql);

					if($result-> num_rows> 0){
						while ($row = $result-> fetch_assoc()){
							echo "<tr><td>". $row["report_id"] ."</td><td>". $row["report_description"] ."</td></tr>";
							}
							echo "</table>";
					}
					else {
						echo "0 resuts";
					}
					$conn->close();
				?>
		</table>
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
