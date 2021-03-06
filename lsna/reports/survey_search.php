<?php
/*
 *   TTM is a web application to manage data collected by community organizations.
 *   Copyright (C) 2014, 2015  Local Initiatives Support Corporation (lisc.org)
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/dbconnopen.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/core/include/setup_user.php");

user_enforce_has_access($LSNA_id);

/* create query and get results for the survey query search in survey_query.php */

include "../include/dbconnopen.php";
$grade_sqlsafe=mysqli_real_escape_string($cnnLSNA, $_POST['grade']);
$program_sqlsafe=mysqli_real_escape_string($cnnLSNA, $_POST['program']);
$year_sqlsafe=mysqli_real_escape_string($cnnLSNA, $_POST['year']);
$school_sqlsafe=mysqli_real_escape_string($cnnLSNA, $_POST['school']);
$time_sqlsafe=mysqli_real_escape_string($cnnLSNA, $_POST['time']);
$_sqlsafe=mysqli_real_escape_string($cnnLSNA, $_POST['']);
//first, check satisfaction surveys
if ($_POST['type']==1){
    //timing is irrelevant (all are post).  check for grade level and program
    if ($_POST['grade']==""){$grade="";}else{$grade=" AND Version='" .$grade_sqlsafe . "' ";}
    if ($_POST['program']==""){$program="";}else{$program=" AND Program_ID='" .$program_sqlsafe . "' ";}
    if ($_POST['year']==""){$year="";}else{$year=" AND YEAR(Date)='".$year_sqlsafe."'";}
    $survey_query="SELECT * FROM Satisfaction_Surveys WHERE Satisfaction_Survey_ID!='' " . $grade . $program . $year;
    $table="Satisfaction_Surveys";
}
/*then check teacher surveys*/
elseif($_POST['type']==2){
    /*won't work if no time selected or if mid selected*/
    if ($_POST['time']=="" || $_POST['time']==2){echo 'You must choose either pre or post for teacher surveys.<br>';
        $survey_query="SELECT * FROM PM_Teacher_Survey WHERE PM_Teacher_Survey_ID IS NULL";
    }
    /*if pre, then return all pre surveys (possibly with year and school restrictions)*/
    elseif($_POST['time']==1){
        if ($_POST['year']==""){$year="";}else{$year=" AND YEAR(Date_Entered)='".$year_sqlsafe."'";}
        if ($_POST['school']==""){$school="";}else{$school= " AND School_Name='".$school_sqlsafe."'";}
        $survey_query="SELECT * FROM PM_Teacher_Survey WHERE PM_Teacher_Survey_ID IS NOT NULL " . $year . $school;
        $table="PM_Teacher_Survey";
    }
    /*if post, then return all post surveys (possibly with year and school restrictions)*/
    elseif($_POST['time']==3){
        if ($_POST['year']==""){$year="";}else{$year=" AND YEAR(Date_Entered)='".$year_sqlsafe."'";}
        if ($_POST['school']==""){$school="";}else{$school= " AND School_Name='".$school_sqlsafe."'";}
        $survey_query="SELECT * FROM PM_Teacher_Survey_Post WHERE Post_Teacher_Survey_ID IS NOT NULL " . $year . $school;
        $table="PM_Teacher_Survey_Post";
    }
}
/* finally parent mentor surveys: */
elseif($_POST['type']==3){
    /*if search term filled in, then it is included in the query: */
    if ($_POST['time']==""){$time="";}else{$time=" AND Pre_Post='".$time_sqlsafe."' ";}
    if ($_POST['year']==""){$year="";}else{$year=" AND YEAR(Date)='".$year_sqlsafe."'";}
    if ($_POST['school']==""){$school="";}else{$school= " AND School='".$school_sqlsafe."'";}
    $survey_query="SELECT * FROM Parent_Mentor_Survey WHERE Parent_Mentor_Survey_ID IS NOT NULL " . $time . $year . $school;
    $table="Parent_Mentor_Survey";
}





//echo $survey_query . "<br>";
date_default_timezone_set('America/Chicago');
$infile="export_data/search_surveys_" . date('M-d-Y') . ".csv";
//echo $infile;
$fp=fopen($infile, "w") or die('can\'t open file');
/*get column names for the relevant table: */
$get_col_names="SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='ttm-lsna' AND `TABLE_NAME`='$table'";
/*production:
 * $get_col_names="SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='liscttm-lsna.chapinhall.org' AND `TABLE_NAME`='$table'";
 */
//echo $get_col_names;
include "../include/dbconnopen.php";
$cols=mysqli_query($cnnLSNA, $get_col_names);
/*use column names as the headings for the results file: */
$columns=array();
while ($col=mysqli_fetch_row($cols)){
    $columns[]=$col[0];
}
fputcsv($fp, $columns);
include "../include/dbconnopen.php";
$results=mysqli_query($cnnLSNA, $survey_query);
$num_results=mysqli_num_rows($results);
echo $num_results . " surveys found.<br>";
while ($survey=mysqli_fetch_row($results)){
     fputcsv ($fp, $survey);
}
fclose($fp);
include "../include/dbconnclose.php";

?>
<!--//show number of surveys found
//download results-->

<a href="<?echo $infile;?>">Download Results</a>
