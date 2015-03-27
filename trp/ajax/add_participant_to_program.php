<?php
/* add notes to the program-participant link.
 * 
 * add a new person to a program. */

if ($_POST['action']=='save_note'){
	include "../include/dbconnopen.php";
        $update_note_sqlsafe="UPDATE Participants_Programs SET Notes='" . mysqli_real_escape_string($cnnTRP, $_POST['note']) . "' WHERE Participant_Program_ID='" . mysqli_real_escape_string($cnnTRP, $_POST['id']) . "'";
    echo $update_note;
    mysqli_query($cnnTRP, $update_note_sqlsafe);
    include "../include/dbconnclose.php";
}
else{
    include "../include/dbconnopen.php";
	$add_participant_sqlsafe = "INSERT INTO Participants_Programs (
		Program_ID,
		Participant_ID
	) VALUES (
		'" . mysqli_real_escape_string($cnnTRP, $_POST['program_id']) . "',
		'" . mysqli_real_escape_string($cnnTRP, $_POST['participant']) . "'
	)";
    echo $add_participant_sqlsafe; //testing output
	mysqli_query($cnnTRP, $add_participant_sqlsafe);
	include "../include/dbconnclose.php";
}
?>