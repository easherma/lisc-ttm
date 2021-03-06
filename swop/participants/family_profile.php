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
user_enforce_has_access($SWOP_id);

include "../../header.php";
include "../header.php";

/* shows household information */

$household_id=$_GET['household'];
include "../include/dbconnopen.php";
$household_id_sqlsafe=mysqli_real_escape_string($cnnSWOP, $household_id);
$get_household_info_sqlsafe="SELECT * FROM Households INNER JOIN Households_Participants ON Households.New_Household_ID=Households_Participants.Household_ID
    INNER JOIN Participants ON Households_Participants.Participant_ID=Participants.Participant_ID
    WHERE Households.New_Household_ID='$household_id_sqlsafe'";

//echo $get_household_info;
$household_info=mysqli_query($cnnSWOP, $get_household_info_sqlsafe);
$basic_household=mysqli_fetch_row($household_info);
$household_name=$basic_household[1];

?>

 <script type="text/javascript">
	$(document).ready(function(){
		
                $('#household_name_edit').hide();
		});
</script>

<h2>Household Profile: <?echo $household_name;?></h2>
<!-- Can change the name of the household if necessary: -->
<span class="helptext">(<a href="javascript:;" onclick="$('#household_name_edit').toggle();">Edit household name</a>)</span>
<div id="household_name_edit">
    New name: <input type="text" id="new_name"><br>
    <a href="javascript:;" onclick="
       $.post(
        '../ajax/new_household.php',
        {
            action: 'edit',
            id: '<?echo $basic_household[0];?>',
            name: document.getElementById('new_name').value
        },
        function (response){
            //document.write(response);
            window.location='family_profile.php?household=<?echo $basic_household[0];?>';
        }
   ).fail(failAlert);">Save</a>
</div>
<hr/><br/>

<!-- The head of household: -->
<table class="inner_table">
<tr><td><h4>Head of Household:</h4></td><td> <strong><?php
$get_head_query_sqlsafe="SELECT Name_First, Name_Last FROM Households_Participants
INNER JOIN Participants ON Households_Participants.Participant_ID=Participants.Participant_ID 
WHERE Head_of_Household=1 AND Household_ID=$household_id_sqlsafe;";
$head_name=mysqli_query($cnnSWOP, $get_head_query_sqlsafe);
$head=mysqli_fetch_row($head_name);
echo $head[0] . " " . $head[1];
?></strong></td><td></td></tr>

<!-- Participants linked to this household: -->
<tr><td><h4>Household Members</h4></td><td>
<?$household_info=mysqli_query($cnnSWOP, $get_household_info_sqlsafe);
while ($members=mysqli_fetch_array($household_info)){
    ?><a href="javascript:;" onclick="
                            $.post(
                            '../ajax/set_participant_id.php',
                            {
                                page: 'profile',
                                participant_id: '<?echo $members['Participant_ID'];?>'
                            },
                            function (response){
                                   var url = response;
                                   window.location = url;
                                }).fail(failAlert);"><?echo $members['Name_First'] ." ". $members['Name_Last'] ;?></a>
                                    <input type="button" value="Remove person from household" onclick="
                                           $.post(
                                                '../ajax/new_household.php',
                                                {
                                                    action: 'delete',
                                                    id: '<?echo $members['Households_Participants_ID'];?>'
                                                },
                                                function (response){
                                                   // document.write(response);
                                                   window.location='family_profile.php?household='+<?echo $household_id_sqlsafe;?>;
                                                }).fail(failAlert);"><br/>
                                    <? 
}
include "../include/dbconnclose.php";
?>
    </td><td></td></tr>

<!-- Each person's income (latest entry in the finance table) is combined to give the combined income. -->
<tr><td><h4>Combined Income</h4></td><td>
<?//get any household members who have already been entered in the pool
$get_pool_household_sqlsafe = "SELECT 
 Pool_Finances.Pool_Finance_ID, Pool_Finances.Participant_ID, Pool_Finances.Credit_Score, Pool_Finances.Income, Pool_Finances.Date_Logged,
 Participants.Name_First, Participants.Name_Last
FROM Pool_Finances INNER JOIN Households_Participants 
    ON Households_Participants.Participant_ID=Pool_Finances.Participant_ID 
INNER JOIN Participants 
    ON Households_Participants.Participant_ID=Participants.Participant_ID 
  INNER JOIN (
    SELECT Pool_Finance_ID,  Pool_Finances.Participant_ID, MAX(Date_Logged) AS maxdate FROM Pool_Finances GROUP BY Pool_Finances.Participant_ID
  ) ms ON  Pool_Finances.Participant_ID = ms.Participant_ID AND Date_Logged = maxdate
WHERE Households_Participants.Household_ID='$household_id_sqlsafe';";
//echo $get_pool_household;
include "../include/dbconnopen.php";
$pool_members=mysqli_query($cnnSWOP, $get_pool_household_sqlsafe);
$full_income=0;
while ($pools= mysqli_fetch_array($pool_members)){
    echo $pools['Name_First'] . " " . $pools['Name_Last'] . ": " . $pools['Income'] . "<br>";
    $full_income+=$pools['Income'];
}
include "../include/dbconnclose.php";
?></td><td>
<strong>Combined income: </strong><?echo $full_income;?></td></tr>

<!-- Again, combined assets for all household members: -->
<tr><td><h4>Combined Assets</h4></td><td>
<?//get any household members who have already been entered in the pool
$get_pool_household_sqlsafe = "SELECT 
 Pool_Finances.Pool_Finance_ID, Pool_Finances.Participant_ID, Pool_Finances.Credit_Score, Pool_Finances.Assets, Pool_Finances.Date_Logged,
 Participants.Name_First, Participants.Name_Last
FROM Pool_Finances INNER JOIN Households_Participants 
    ON Households_Participants.Participant_ID=Pool_Finances.Participant_ID 
INNER JOIN Participants 
    ON Households_Participants.Participant_ID=Participants.Participant_ID 
  INNER JOIN (
    SELECT Pool_Finance_ID,  Pool_Finances.Participant_ID, MAX(Date_Logged) AS maxdate FROM Pool_Finances GROUP BY Pool_Finances.Participant_ID
  ) ms ON  Pool_Finances.Participant_ID = ms.Participant_ID AND Date_Logged = maxdate
WHERE Households_Participants.Household_ID='$household_id_sqlsafe';";
//echo $get_pool_household;
include "../include/dbconnopen.php";
$pool_members=mysqli_query($cnnSWOP, $get_pool_household_sqlsafe);
$full_assets=0;
while ($pools= mysqli_fetch_array($pool_members)){
    echo $pools['Name_First'] . " " . $pools['Name_Last'] . ": " . $pools['Assets'] . "<br>";
    $full_assets+=$pools['Assets'];
}
include "../include/dbconnclose.php";
?></td><td>
<strong>Combined assets: </strong><?echo $full_assets;?></td></tr>
<!--<tr><td><h4>Housing Cost?</h4></td><td>
I'm not sure how to account for the fact<Br>that these people probably live together <br>- maybe just an input box here?
<input type="text" id="household_housing_cost"></td><td></td></tr>-->
</table>


<!-- progress from all household members. -->
<h4>Pool Pipeline Progress?</h4>
<?//want to account for all the activities that each family member has completed
?>
<table class="all_projects">
    <tr><th>Household Member Name</th><th>Benchmark Completed</th><th>Date</th></tr>
    <?//gotta make a great query...
    $get_progress_sqlsafe="SELECT * FROM Pool_Progress 
            INNER JOIN Pool_Benchmarks ON Pool_Benchmark_ID=Benchmark_Completed
            INNER JOIN Participants ON Pool_Progress.Participant_ID=Participants.Participant_ID
            INNER JOIN Households_Participants ON Pool_Progress.Participant_ID=Households_Participants.Participant_ID
            WHERE Household_ID=$household_id_sqlsafe
        ORDER BY Date_Completed DESC;";
    //echo $get_progress;
    include "../include/dbconnopen.php";
    $progress_passing=mysqli_query($cnnSWOP, $get_progress_sqlsafe);
    while ($progress=mysqli_fetch_array($progress_passing)){
        ?>
    <tr><td><?echo $progress['Name_First'] . " " . $progress['Name_Last'];?></td><td><?echo $progress['Benchmark_Name'];?></td>
        <td><?echo $progress['Date_Completed'];?></td></tr>
            <?
    }
    include "../include/dbconnclose.php";
    ?>
</table>
<br/><br/>
<?php
include "../../footer.php";
close_all_dbconn();
?>