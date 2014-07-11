<?
/* add notes to the program-participant link.
 * 
 * add a new person to a program. */

if ($_POST['action']=='save_note'){
    $update_note_sqlsafe="UPDATE Participants_Programs SET Notes='" . mysqli_real_escape_string($_POST['note']) . "' WHERE Participant_Program_ID='" . mysqli_real_escape_string($_POST['id'] . "'";
    echo $update_note;
    include "../include/dbconnopen.php";
    mysqli_query($cnnTRP, $update_note_sqlsafe);
    include "../include/dbconnclose.php";
}
else{
	$add_participant_sqlsafe = "INSERT INTO Participants_Programs (
		Program_ID,
		Participant_ID
	) VALUES (
		'" . mysqli_real_escape_string($_POST['program_id']) . "',
		'" . mysqli_real_escape_string($_POST['participant']) . "'
	)";
	include "../include/dbconnopen.php";
	mysqli_query($cnnTRP, $add_participant_sqlsafe);
	include "../include/dbconnclose.php";
}
?>