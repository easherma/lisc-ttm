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
include $_SERVER['DOCUMENT_ROOT'] . "/include/dbconnopen.php";
include $_SERVER['DOCUMENT_ROOT'] . "/core/include/setup_user.php";

user_enforce_has_access($Enlace_id, 2);

include "../../header.php";
include "../header.php";?>

<div align="center" style="font-weight:bold; font-size: 24;">Thank you for uploading a file!</div> <br>
<?php
/*add a file to the database.*/

echo "Upload: " . $_FILES["file"]["name"] . "<br />";
echo "Type: " . $_FILES["file"]["type"] . "<br />";
echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";

/*make sure the filetype is allowed.*/
$allowedExts = array("pdf", "doc", "docx", "zip", "xls", "xlsx");
$extension = end(explode(".", $_FILES["file"]["name"]));
if (($_FILES["file"]["size"] < 1000000)
        && in_array($extension, $allowedExts)) {
    include ("../include/dbconnopen.php");
    
    /*if id is set correctly, then run the query.*/
    if (isset($_POST['event_id'])) {
        $fileName = $_FILES['file']['name'];
        $tmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        $file_open_temp = fopen($tmpName, 'r');
        $file_content = fread($file_open_temp, filesize($tmpName));
        //echo $file_content . "<br><br>";
        $file_content = mysqli_real_escape_string($cnnEnlace, $file_content);
        fclose($file_open_temp);

        /*not sure what this is about, but I suspect it prevents errors with slashes and other
         * special characters.
         */
        
        $fileName = mysqli_real_escape_string($cnnEnlace, $fileName);
          //  echo 'escaped';
        
        //echo $fileName . "<br>";
//echo "INSERT INTO Uploaded_Files (Observation_ID, File_Name, File_Size, File_Type, File_Content ) VALUES (" . $_COOKIE['session_id'] . ", '$fileName', '$fileSize', '$fileType', 'content')";
        
        /*add file to db.*/
        $query = "UPDATE Campaigns_Events SET Note_File_Name= '$fileName',
        Note_File_Size='$fileSize',
        Note_File_Type='$fileType',
        Note_File_Content='$file_content'
               WHERE Campaign_Event_ID='".$_POST['event_id']."'";
        //echo "<br>";
        //echo $query . "<br/>";
        mysqli_query($cnnEnlace, $query) or die('Error, query failed'); 
        //echo "<br>" . "error";
        //printf("Errormessage: %s\n", mysqli_error($cnnEnlace));
        include ("../include/dbconnclose.php");

        echo "<br>File $fileName uploaded<br>";
    } else {
        echo "<br>Please select an observation.";
    }
} 
else {
    echo "<div align='center' style='font-weight:bold; font-size: 24;'>Invalid File: This file is either too large or not an approved type.</div>";
}
?>
<br>
<a href="/enlace/campaigns/campaign_profile.php">Click here to return to the campaign profile.</a>