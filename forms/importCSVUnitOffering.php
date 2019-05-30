<!DOCTYPE html>

<?php
// Load the database configuration file
	require_once("../classes.php");
	session_start();
if(isset($_POST['importSubmit'])){
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){

            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Skip the first line
            fgetcsv($csvFile);

            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
                $unitCode   = $line[0];
				$convenor   = $line[1];
                $term  = $line[2];
				$year = $line[3];
				$censusDate  = $line[4];


                // Check whether unit already exists in the database 
                $prevQuery = "SELECT unitCode FROM unitoffering WHERE unitCode = '".$line[0]."' and term = '".$line[2]."' and year = '".$line[3]."'";
                $prevResult = $conn->query($prevQuery);

                if($prevResult->num_rows > 0){
                   	/* Switch off auto commit to allow transactions*/
		mysqli_autocommit($conn, FALSE);
		$query_success = TRUE;
	
			// echo $_POST['convenor'], $_POST['unitCode'], $_POST['teachingSemester'], $_POST['teachingYear'];
			
			// Get the unit info 
			
			
			// Edit the Unit Offering
			$stmt = $GLOBALS['conn']->prepare("CALL TCABSUNITOFFERINGEditOffering(?, ?, ?)");
			$stmt->bind_param("sss", $unitCode, $term, $year);
			
			// Add the Convenor to OfferingStaff table
			$stmt2 = $GLOBALS['conn']->prepare("CALL TCABSOFFERINGSTAFFEditOfferingStaff(?, ?, ?, ?)");
			$stmt2->bind_param("ssss", $convenor, $unitCode, $term, $year);
			
			// Add the Convenor
			$stmt3 = $GLOBALS['conn']->prepare("CALL TCABSUNITOFFERINGSetConvenor(?, ?, ?, ?)");
			$stmt3->bind_param("ssss", $convenor, $unitCode, $term, $year);
			
			// Add the CencusDate
			$stmt4 = $GLOBALS['conn']->prepare("CALL TCABSUNITOFFERINGSetCensusDate(?, ?, ?, ?)");
			$stmt4->bind_param("ssss", $unitCode, $term, $year, $censusDate);

			try {
				$stmt->execute();
				$stmt2->execute();
				$stmt3->execute();
				$stmt4->execute();
				mysqli_commit($conn);
				echo "<script type='text/javascript'>alert('Unit Offering edited successfully');</script>";
			} catch(mysqli_sql_exception $e) {
				echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
				mysqli_rollback($conn);
			}

			$stmt->close();
			
                }else{
		mysqli_autocommit($conn, FALSE);
		$query_success = TRUE;

					echo $convenor, $unitCode, $term, $year, $censusDate;
                   // Add the Unit Offering
			$stmt = $GLOBALS['conn']->prepare("CALL TCABSUNITOFFERINGAddNewOffering(?, ?, ?)");
			$stmt->bind_param("sss", $unitCode, $term, $year);
			
			// Add the Convenor to OfferingStaff table
			$stmt2 = $GLOBALS['conn']->prepare("CALL TCABSOFFERINGSTAFFAddOfferingStaff(?, ?, ?, ?)");
			$stmt2->bind_param("ssss", $convenor, $unitCode, $term, $year);
			
			// Add the Convenor
			$stmt3 = $GLOBALS['conn']->prepare("CALL TCABSUNITOFFERINGSetConvenor(?, ?, ?, ?)");
			$stmt3->bind_param("ssss", $convenor, $unitCode, $term, $year);
			
			// Add the CencusDate
			$stmt4 = $GLOBALS['conn']->prepare("CALL TCABSUNITOFFERINGSetCensusDate(?, ?, ?, ?)");
			$stmt4->bind_param("ssss", $unitCode, $term, $year, $censusDate);

			try {
				$stmt->execute();
				$stmt2->execute();
				$stmt3->execute();
				$stmt4->execute();
				mysqli_commit($conn);
				echo "<script type='text/javascript'>alert('Unit Offering added successfully');</script>";
			} catch(mysqli_sql_exception $e) {
				echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
				mysqli_rollback($conn);
			}

			$stmt->close();
                }
            }

            // Close opened CSV file
            fclose($csvFile);

            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
		$_SESSION['csvStatus'] == '1111';
    }
}

// Redirect to the listing page
header("Location: registerUnitsOfStudy.php".$qstring);
