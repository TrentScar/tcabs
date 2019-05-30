<?php
	require_once("../classes.php");
	session_start();
	if (!isset($_SESSION['logged_in'])) {
		header('Location: /tcabs/login.php');
		exit();
	} else {
		// check if user has permission to access the page
		if(!$_SESSION['loggedUser']->uRoles['convenor']) {
			header('Location: /tcabs/dashboard.php');
		} else {
		
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				if(isset($_POST['submit'])) {
					//$unitObj = new Unit;
					if($_POST['submit'] === "addMember") {
						try {
							//$unitObj->registerUnit($_POST['unitCode'], $_POST['unitName'], $_POST['unitFaculty']);
						} catch(mysqli_sql_exception $e) {
							echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
						}
					} else if($_POST['submit'] === "bulkImport") {
				
					} else if($_POST['submit'] === "search") {
						//if($_POST['searchQuery'] == null) {
							//echo "<script type='text/javascript'>alert('Search Box empty');</script>";
						} else {
							try {
								//$searchResults = $unitObj->searchUnit("%{$_POST['searchQuery']}%");
								//if($searchResults == null) {
									//echo "<script type='text/javascript'>alert('Oops nothing found!');</script>";
								//}
							} catch(mysqli_sql_exception $e) {
								//echo $e->getMessage();
								//exit();
								//echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
							}
						}
					} else if($_POST['submit'] == 'editMember') {
						//$unitObj = new Unit;
						//$unitObj->unitCode = $_POST['unitCode'];
						//$unitObj->unitName = $_POST['unitName'];
						//$unitObj->unitFaculty = $_POST['unitFaculty'];
						try {
							//$unitObj->updateUnit($unitObj);
							//echo "<script type='text/javascript'>alert('Unit updated successfully!');</script>";
						} catch(mysqli_sql_exception $e) {
							//echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
						}
					} else if($_POST['submit'] == 'update') {
					} else  if($_POST['submit'] == 'delete') {
					}
				}
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Stylesheets -->
		<?php include "../styles/stylesheet.php"; ?>
  </head>

  <body class="loggedin">
		<?php include "../views/header.php"; ?>
		<div class="content">
			<h2>Team member Administration</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
		<div>

		<?php 
			//show tabs if not in update mode
			if(!isset($_POST['update'])) {
		?>

		<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addMember') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active';} ?>" data-toggle="tab" href="#home">Add</a>
  		</li>
  		<li class="nav-item>">
				<a class="nav-link <?php if(isset($_POST['submit']) && $_POST['submit'] == 'bulkImport') { echo 'active';} ?>" data-toggle="tab" href="#menu1">Bulk Import via CSV</a>
  		</li>
    	<li class="nav-item">
    		<a class="nav-link <?php if((isset($_POST['submit']) && $_POST['submit'] == 'search') || $_POST['submit']== 'editMember') { echo 'active';} ?>" data-toggle="tab" href="#menu2">Search</a>
  		</li>
		</ul>

		<?php } ?>

		<!-- Tab panes -->
		<div class="tab-content">

			<!-- Edit Unit only when Update button pressed -->
			<?php
				if(isset($_POST['update'])) {
					//$unitObj = new Unit;
					//$searchResults = $unitObj->searchUnit("%{$_POST['update']}%");
					foreach($searchResults as $key => $value) {
						//$name = $value['unitCode'];
						 //$email = $value['unitName'];
			?>
			
			<div>
				<form action="registerMembers.php" method="post" class="was-validated"><br>
					<p class="h4 mb-4 text-center">Edit Member (<?php echo ($_POST['update']); ?>)</p>
					<input type="text" id="tMemberID" name="tMemberID" class="form-control" value="<?php echo $value['tMemberID']; ?>"  required><br>
					<input type="text" id="enrolmentID" name="enrolmentID" class="form-control" value="<?php echo $value['enrolmentID']; ?>" required><br>
					<input type="text" id="teamID" name="teamID" class="form-control" value="<?php echo $value['teamID']; ?>"  required><br><br>
					<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="editMember">Edit Member</button>
				</form>
			</div>
			<?php  } ?>
			<?php  } ?>


			<!-- Tab 1 -->
  		<div class="tab-pane container <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addMember') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active show';} ?>" id="home">
				<form action="registerMembers.php" method ="post" class="was-validated"><br/>
  	  		<p class="h4 mb-4 text-center">Add Team Members to a Team</p>
					<input type="text" id="tMemberID" name="tMemberID" class="form-control" required><br>
					<input type="text" id="enrolmentID" name="enrolmentID" class="form-control" required><br>
					<input type="text" id="teamID" name="teamID" class="form-control" required><br><br>
  				<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="addMember">Add Member</button>
				</form>
			</div>

			<!-- Tab 2 -->
  		<div class="tab-pane container fade <?php if(isset($_POST['submit']) && $_POST['submit'] == 'bulkImport') { echo 'active show';} ?>" id="menu1">
				<form action="registerMembers.php" method ="post" class="was-validated"><br/>
  	  		<p class="h4 mb-4 text-center">Bulk Import</p>
  				<div class="form-group">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="csvFile" required>
							<label class="custom-file-label" for="csvFile">Choose file</label>
						</div>
  				</div>
  				<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="bulkImport">Add Members</button>
				</form>
			</div>

			<!-- Tab 3 -->
  		<div class="tab-pane container fade <?php if((isset($_POST['submit']) && $_POST['submit'] == 'search') || $_POST['submit']== 'editMember') { echo 'active show';} ?>" id="menu2">
				<form method="POST" class="was-validated"><br/>
  	 		 	<p class="h4 mb-4 text-center">Update/Delete Member</p>
					<div class="search-box">
						<input type="text" id="searchQuery" name="searchQuery" class="form-control" placeholder="Enter Member ID or Name" required>
						<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="search">Search</button>
						<div class="result"></div>
					</div>
				</form><br>

				<!-- Show Search Results -->
			<?php 
				if(isset($_POST['submit']) && $_POST['submit'] == 'search') {
			?>		

			<div>
				<form action="registerMembers.php" method="post">
					<table style="width: 100%;">
						<tr>
    					<th style="width: 40%;">Team Name</th>
							<th style="width: 35%;">EnrolmentID</th>
							<th style="width: 15%;"></th>
    					<th style="width: 15%;"></th>
    				</tr>

						<?php 
							foreach($searchResults as $key => $value) {
								//$name = $value['unitCode'];
								//$email = $value['unitName'];
						?>

						<tr style="border-top: 1px solid lightgrey;">
							<td><?php echo $name;?></td>
							<td><?php echo $email;?></td>
							<td><button type="submit" class="btn btn-primary" name="update" value="<?php echo $value['unitCode'];?>" >Update</button></td>
							<td><button type="submit" class="btn btn-danger" name="delete" value="<?php echo $value['unitCode'];?>" >Delete</button></td>
						</tr>

						<?php  }?>

					</table>
				</form><br>
			</div>
			<?php  }?>
			</div></div></div>
		</div>
	</body>
  <?php include "../views/footer.php";  ?>  

	<script>
		// Add the following code if you want the name of the file appear on select
		$(".custom-file-input").on("change", function() {
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});
	</script>

</html>