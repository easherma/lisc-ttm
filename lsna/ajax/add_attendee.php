<?php
/* Add attendance. */
include "../include/dbconnopen.php";
$program_date_id_sqlsafe=mysqli_real_escape_string($cnnLSNA, $_POST['program_date_id']);
$user_id_sqlsafe=mysqli_real_escape_string($cnnLSNA, $_POST['user_id']);
$add_attendee_to_date = "INSERT INTO Subcategory_Attendance (
                            Subcategory_Date,
                            Participant_ID) VALUES (
                            '". $program_date_id_sqlsafe."',
                            '". $user_id_sqlsafe."'
                            )";
mysqli_query($cnnLSNA, $add_attendee_to_date);
include "../include/dbconnclose.php";

?>
