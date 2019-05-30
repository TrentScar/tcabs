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
                $unitName  = $line[1];
                $faculty  = $line[2];


                // Check whether unit already exists in the database 
                $prevQuery = "SELECT unitCode FROM unit WHERE unitCode = '".$line[0]."'";
                $prevResult = $conn->query($prevQuery);

                if($prevResult->num_rows > 0){
                    // Update member data in the database
                    $conn->query("UPDATE unit SET unitCode = '".$unitCode."', unitName = '".$unitName."', faculty = '".$faculty."' WHERE unitCode = '".$unitCode."'");
                }else{
                    // Insert member data in the database
                    $conn->query("INSERT INTO unit (unitCode, unitName, faculty) VALUES ('".$unitCode."','".$unitName."','".$faculty."')");
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
header("Location: registerUnits.php".$qstring);

?>