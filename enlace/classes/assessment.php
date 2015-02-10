<?php

class Assessment {
	public $assessment_id;
	
	const INTAKE_TYPE = 1;
	const IMPACT_TYPE = 2;
	
	/**
	 * Enlace Assessment empty constructor.
	 *
	 * @return Empty Assessment object.
	 */
	public function __construct() {
		
	}
	
	/**
	 * Enlace Assessment constructor from database data.
	 *
	 * @return Assessment object.
	 */
	static function createFromDatabase($data) {
		$assessment = new self;
		$assessment->from_db($data);
		return $assessment;
	}
	
	/**
	* Load survey by survey id.
	*
	* @param int assessment_id The ID of the assessment that is being loaded.
	* @return void
	*/
	public function load_with_assessment_id($assessment_id) {
		include "../include/dbconnopen.php";
		$assessment_id_sqlsafe = mysqli_real_escape_string($cnnEnlace, $assessment_id);
		$this->$assessment_id = $assessment_id_sqlsafe;
		$get_assessment_info = "SELECT * FROM Assessments WHERE Assessment_ID='".$this->$assessment_id."'";
		$assessment_info = mysqli_query($cnnEnlace, $get_assessment_info);
		$this->from_db(mysqli_fetch_array($assessment_info));
		include "../include/dbconnclose.php";
		
		// Now assign the survey data as attributes on the class.
		$this->participant_id = $temp_survey["Participant_ID"];
		$this->baseline_id = $temp_survey["Baseline_ID"];
		$this->caring_id = $temp_survey["Caring_ID"];
		$this->future_id = $temp_survey["Future_ID"];
		$this->violance_id = $temp_survey["Violence_ID"];
		$this->pre_post = $temp_survey["Pre_Post"];
		$this->date_logged = $temp_survey["Date_Logged"];
		$this->session_id = $temp_survey["Session_ID"];
	}
	
	/**
	* Assigns data from the database to a Assessment object
	*
	* @param Array $data An associative Array object with the data from the database.
	* @return void
	*/
	public function from_db($data) {
		$this->participant_id = $data["Participant_ID"];
		$this->baseline_id = $data["Baseline_ID"];
		$this->caring_id = $data["Caring_ID"];
		$this->future_id = $data["Future_ID"];
		$this->violance_id = $data["Violence_ID"];
		$this->pre_post = $data["Pre_Post"];
		$this->date_logged = $data["Date_Logged"];
		$this->session_id = $data["Session_ID"];
	}
	
	/**
	* Saves the Assessment back to the database, if no $assessment_id is set a new Assessment will be made.
	*
	* @return void
	*/
	public function save() {
		if ($this->assessment_id) {
			$update_assessment = "UPDATE Assessments SET Participant_ID='".$this->participant_id."', Baseline_ID='".$this->baseline_id."',
								 Caring_ID='".$this->caring_id."', Future_ID='".$this->future_id."', Violence_ID='".$this->violance_id."',
								 Pre_Post='".$this->pre_post."', Date_Logged='".$this->date_logged."', Session_ID='".$this->session_id."'
								 WHERE Assessment_ID='".$assessment_id."'";
			
			include "../include/dbconnopen.php";
			mysqli_query($cnnEnlace, $update_assessment);
			include "../include/dbconnclose.php";
		} else {
			$create_assessment = "INSERT INTO Assessments (Participant_ID, Baseline_ID, Caring_ID, Future_ID, Violence_ID, Pre_Post, Date_Logged,
								  Session_ID) VALUES ('".$this->participant_id."', ".$this->baseline_id."', '".$this->caring_id."','
								  ".$this->future_id."', '".$this->violance_id."', '".$this->pre_post."', '".$this->date_logged."', '
								  ".$this->session_id."')";
			include "../include/dbconnopen.php";
			mysqli_query($cnnEnlace, $create_assessment);
			include "../include/dbconnclose.php";
		}
	}
	
}


?>