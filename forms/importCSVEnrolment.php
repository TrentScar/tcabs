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
                $sUserName = $line[1];
				$term  = $line[2];
				$year = $line[3];


                // Check whether enrolment already exists in the database 
                //$prevQuery = "SELECT sUserName FROM enrolment WHERE sUserName = '".$line[1]."' and term = '".$line[2]."' and year = '".$line[3]."'";
				
				$prevQuery = "SELECT enrolment.enrolmentID, unitoffering.unitOfferingID, unit.unitcode, unit.unitName, teachingperiod.term, teachingperiod.year, users.fName, users.lName, users.email
			from enrolment 
			left join unitoffering on unitoffering.unitOfferingID = enrolment.unitOfferingID 
			left join unit on unitoffering.unitCode = unit.unitCode 
			left join users on enrolment.sUserName = users.email 
			left join teachingperiod on unitoffering.term = teachingperiod.term and unitoffering.year = teachingperiod.year 
			where users.email= '".$line[1]."' and teachingperiod.term = '".$line[2]."' and teachingperiod.year = '".$line[3]."'";
			
			
                $prevResult = $conn->query($prevQuery);

                if($prevResult->num_rows > 0){
					//skip that line if already enroled
                   // $conn->query("UPDATE unit SET unitCode = '".$unitCode."', unitName = '".$unitName."', faculty = '".$faculty."' WHERE unitCode = '".$unitCode."'");
                }else{
                    // Insert member data in the database
			$stmt = $GLOBALS['conn']->prepare("CALL TCABSENROLMENTAddNewEnrolment(?, ?, ?, ?)");
			$stmt->bind_param("ssss", $sUserName, $unitCode, $term, $year);
			$stmt->execute();
                }
            }

            // Close opened CSV file
            fclose($csvFile);

            $qstring = '?status=succ';
			$_SESSION['csvStatus'] == '1111';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location: registerEnrolment.php".$qstring);

?>