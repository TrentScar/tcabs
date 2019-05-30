<?php
	require_once("../classes.php");
	session_start();
	if (!isset($_SESSION['logged_in'])) {
		header('Location: /tcabs/login.php');
		exit();
	} else {

		// check if user has permission to access the page
		if(!$_SESSION['loggedUser']->uRoles['admin']) {
			header('Location: /tcabs/dashboard.php');
		} else {
		
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				if(isset($_POST['submit'])) {
					$unitObj = new Unit;
					if($_POST['submit'] === "addUnit") {
						try {
							$unitObj->registerUnit($_POST['unitCode'], $_POST['unitName'], $_POST['unitFaculty']);
						} catch(mysqli_sql_exception $e) {
							echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
						}
					} else if($_POST['submit'] === "bulkAddUnits") {
				
					} else if($_POST['submit'] === "search") {
						if($_POST['searchQuery'] == null) {
							echo "<script type='text/javascript'>alert('Search Box empty');</script>";
						} else {
							try {
								$searchResults = $unitObj->searchUnit("%{$_POST['searchQuery']}%");
								if($searchResults == null) {
									echo "<script type='text/javascript'>alert('Oops nothing found!');</script>";
								}
							} catch(mysqli_sql_exception $e) {
								echo $e->getMessage();
								exit();
								echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
							}
						}
					} else if($_POST['submit'] == 'editUnit') {

						$unitObj = new Unit;
						$unitObj->unitCode = $_POST['unitCode'];
						$unitObj->unitName = $_POST['unitName'];
						$unitObj->unitFaculty = $_POST['unitFaculty'];

						try {
							$unitObj->updateUnit($unitObj);
							echo "<script type='text/javascript'>alert('Unit updated successfully!');</script>";
						} catch(mysqli_sql_exception $e) {
							echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
						}
					}
				}
			

					if(isset($_POST['update'])) {
					}

				 if(isset($_POST['delete'])) {
						
			/* Switch off auto commit to allow transactions*/
		mysqli_autocommit($conn, FALSE);
		$query_success = TRUE;
	
			
			
			// Delete the Unit
			$stmt = $GLOBALS['conn']->prepare("CALL TCABSDeleteUnit(?)");
			$stmt->bind_param("s", $_POST['delete']);
			
			try {
				$stmt->execute();
				
				mysqli_commit($conn);
				echo "<script type='text/javascript'>alert('Unit deleted successfully');</script>";
			} catch(mysqli_sql_exception $e) {
				echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
				mysqli_rollback($conn);
			}

					}
				
		
		}
			if(isset($_GET['status']) && $_GET['status'] == "succ") {
				echo "<script type='text/javascript'>alert('CSV Import Success!');</script>";
			}
			
			if(isset($_GET['status']) && $_GET['status'] == "invalid_file") {
				echo "<script type='text/javascript'>alert('CSV Import Failed. Invalid File');</script>";
			}
			
			
			if(isset($_GET['status']) && $_GET['status'] == "err") {
				echo "<script type='text/javascript'>alert('CSV Import Error. Please check');</script>";
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
			<h2>Manage Units</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
		<div>

		<?php 
			//show tabs if not in update mode
			if(!isset($_POST['update'])) {
		?>

		<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addUnit') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active';} ?>" data-toggle="tab" href="#home">Add</a>
  		</li>
  		<li class="nav-item>">
				<a class="nav-link <?php if(isset($_POST['submit']) && $_POST['submit'] == 'bulkAddUnits') { echo 'active';} ?>" data-toggle="tab" href="#menu1">Bulk Import via CSV</a>
  		</li>
    	<li class="nav-item">
    		<a class="nav-link <?php if((isset($_POST['submit']) && $_POST['submit'] == 'search') || $_POST['submit']== 'editUnit') { echo 'active';} ?>" data-toggle="tab" href="#menu2">Update/Delete</a>
  		</li>
		</ul>

		<?php } ?>

		<!-- Tab panes -->
		<div class="tab-content">

			<!-- Edit Unit only when Update button pressed -->
			<?php
				if(isset($_POST['update'])) {
					$unitObj = new Unit;
					$searchResults = $unitObj->searchUnit("%{$_POST['update']}%");
					foreach($searchResults as $key => $value) {
						$name = $value['unitCode'];
						 $email = $value['unitName'];
			?>
			
			<div>
				<form action="registerUnits.php" method="post" class="was-validated"><br>
					<p class="h4 mb-4 text-center">Edit Unit (<?php echo ($_POST['update']); ?>)</p>
					<input type="text" id="unitCode" name="unitCode" class="form-control" value="<?php echo $value['unitCode']; ?>"  readonly><br>
					<input type="text" id="unitName" name="unitName" class="form-control" value="<?php echo $value['unitName']; ?>" required><br>
					<select class="browser-default custom-select" id="unitFaculty" name="unitFaculty" required>
						<option selected hidden value="<?php echo $value['gender']; ?>"><?php echo $value['unitFaculty']; ?></option>
						<option value="FBL">Faculty of Business and Law</option>
						<option value="FHAD">Faculty of Health, Arts and Design</option>
						<option value="FSET">Faculty of Science, Engineering and Technology</option>
					</select><br><br>

					<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="editUnit">Edit Unit</button>
				</form>
			</div>
			<?php  } ?>
			<?php  } ?>


			<!-- Tab 1 -->
  		<div class="tab-pane container <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addUnit') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active show';} ?>" id="home">
				<form action="registerUnits.php" method ="post" class="was-validated"><br/>
  	  		<p class="h4 mb-4 text-center">Add Unit into TCABS</p>
					<input type="text" id="unitCode" name="unitCode" class="form-control" placeholder="Unit Code" required><br>
 	   			<input type="text" id="unitName" name="unitName" class="form-control" placeholder="Unit Name" required><br>
					<select class="browser-default custom-select" id="unitFaculty" name="unitFaculty" required>
 	  				<option value="" disabled="" selected="">Select Faculty</option>
 	    			<option value="FBL">Faculty of Business and Law</option>
 	    			<option value="FHAD">Faculty of Health, Arts and Design</option>
 	   		 		<option value="FSET">Faculty of Science, Engineering and Technology</option>
 	  			</select><br><br>
  				<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="addUnit">Add Unit</button>
				</form>
			</div>

			<!-- Tab 2 -->
  		<div class="tab-pane container fade <?php if(isset($_POST['submit']) && $_POST['submit'] == 'bulkAddUnits') { echo 'active show';} ?>" id="menu1">
		<br>
  	  	<p class="h4 mb-4 text-center">Bulk Import via CSV</p>
		<div class="col-mb-4 text-center"> 
		<a class="btn btn-outline-info" href="../csv/units.csv" role="button">CSV Template Download</a>
		</div>
		<br><br>
  			<div class="form-group">
      <div class="custom-file">
	  <form action="importCSVUnits.php" method="post" enctype="multipart/form-data">
    <input type="file" class="custom-file-input" id="file" name="file" required>
    <label class="custom-file-label" for="csvFile">Choose file</label>

  </div>

<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>
  			</div>
  			<button class="btn btn-info my-4 btn-block" type="submit" name="importSubmit" value="IMPORT">Import</button>
			</form>
		</div>

			<!-- Tab 3 -->
  		<div class="tab-pane container fade <?php if((isset($_POST['submit']) && $_POST['submit'] == 'search') || $_POST['submit']== 'editUnit') { echo 'active show';} ?>" id="menu2">
				<form method="POST" class="was-validated"><br/>
  	 		 	<p class="h4 mb-4 text-center">Update/Delete Unit</p>
					<div class="search-box">
						<input type="text" id="searchQuery" name="searchQuery" class="form-control" placeholder="Enter Unit Name or Code" required>
						<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="search">Search</button>
						<div class="result"></div>
					</div>
				</form><br>

				<!-- Show Search Results -->
			<?php 
				if(isset($_POST['submit']) && $_POST['submit'] == 'search') {
			?>		

			<div>
				<form action="registerUnits.php" method="post">
					<table style="width: 100%;">
						<tr>
    					<th style="width: 40%;">Unit Code</th>
							<th style="width: 35%;">Unit Name</th>
							<th style="width: 15%;"></th>
    					<th style="width: 15%;"></th>
    				</tr>

						<?php 

							foreach($searchResults as $key => $value) {
								$name = $value['unitCode'];
								$email = $value['unitName'];
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
