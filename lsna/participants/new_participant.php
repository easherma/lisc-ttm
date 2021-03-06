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

include "../../header.php";
include "../header.php";
include "../include/datepicker.php";
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#participants_selector').addClass('selected');
        $("a.add_new").hover(function(){
            $(this).addClass("selected");
        }, function() {
            $(this).removeClass("selected");
        });
        $('#add_buttons').hide();
    });
$(document).ready(function() {
    $('#ajax_loader').hide();
});
            
$(document).ajaxStart(function() {
    $('#ajax_loader').fadeIn('slow');
});
            
$(document).ajaxStop(function() {
    $('#ajax_loader').fadeOut('slow');
});
</script>

<div id="new_participant_div" class="content_block">
    
	<h3>Add New Participant</h3><hr/><br/>
        <!--Link back to search/home page.-->
	<div style="text-align:center;"><a class="add_new" href="participants.php"><span class="add_new_button">Search Existing Participants</span></a></div><br/><br/>

<table id="new_participant_table">
	<tr>
		<td width="18%"><strong>First and Last Name: </strong></td>
		<td ><input type="text" id="first_name_new" />&nbsp;&nbsp;<input type="text" id="last_name_new" /></td>
		<td rowspan="3" width="16%"><strong>Role: </strong></td>
		<td rowspan="3">
				<?php      $get_roles = "SELECT * FROM Roles";
					include "../include/dbconnopen.php";
					$roles = mysqli_query($cnnLSNA, $get_roles);
					while ($role = mysqli_fetch_array($roles)) {
					?>
						<input type="checkbox" id="role_<?php echo $role['Role_ID']; ?>_new" name="role[]" value="<?php echo $role['Role_ID']; ?>"/><?php echo $role['Role_Title']; ?><br/>
					<?php }
					include "../include/dbconnclose.php"; ?>
		</td>
	</tr>
	<tr>
		<td ><strong>Date of Birth: </strong></td>
                <!--This expects mm-dd-yyyy format, as generated by the calendar. -->
		<td ><input type="text" id="dob_new" class="hadDatepicker" /></td>
	</tr>
	<tr>
		<td ><strong>Age:* </strong></td>
		<td ><input type="text" id="age_new" style="width:30px; margin-right:130px;" />
			<strong>Gender: </strong>
			<select id="gender_new">
				<option value="">--------</option>
				<option value="m">Male</option>
				<option value="f">Female</option>
			</select><br/>
			<span class="helptext">*Age will be calculated automatically if date of birth is entered.</span>
		</td>
	</tr>
	<tr>
		<td ><strong>Street Address: </strong></td>
		<td ><input type="text" id="street_address_num" style="width:40px"/>
                <select id="street_address_dir">
					<option value="">---</option>
					<option value="N">N</option>
					<option value="S">S</option>
					<option value="E">E</option>
					<option value="W">W</option>
				</select>
                <input type="text" id="street_address_name"  style="width:100px"/>
                <input type="text" id="street_address_type"  style="width:40px"/><br/>
				<span class="helptext">e.g. 2840 N Milwaukee Ave</span></td>
		<td ><strong>Education Level: </strong></td>
		<td ><select id="education_level_new">
				<option value="">----------</option>
				<option value="hs">High School</option>
				<option value="ged">GED</option>
				<option value="some_college">Some college</option>
				<option value="college">College graduate</option>
			</select>
		</td>
	</tr>
	<tr>
		<td ><strong>City, State and ZIP: </strong></td>
		<td ><input type="text" id="city_new" style="width:90px;"/>&nbsp;&nbsp;<input type="text" id="state_new" style="width:25px;" />&nbsp;&nbsp;<input type="text" id="zip_new" style="width:40px;" />
		</td>
		<td ><strong>Grade Level: </strong></td>
		<td><select id="grade_level_new">
				<option value="">--------</option>
				<option value="k">Kindergarten</option>
				<option value="1">1st Grade</option>
				<option value="2">2nd Grade</option>
				<option value="3">3rd Grade</option>
				<option value="4">4th Grade</option>
				<option value="5">5th Grade</option>
				<option value="6">6th Grade</option>
				<option value="7">7th Grade</option>
				<option value="8">8th Grade</option>
				<option value="9">9th Grade</option>
				<option value="10">10th Grade</option>
				<option value="11">11th Grade</option>
				<option value="12">12th Grade</option>
			</select>
		</td>
	</tr>
	<tr>
		<td ><strong>Daytime Phone: </strong><span class="helptext">Phone numbers must be in the format (xxx) xxx-xxxx</span></td>
		<td ><input type="text" id="day_phone_new" /></td>
		<td ><strong>Languages Spoken: </strong></td>
		<td ><select id="language">
                <option value="">-----</option>
                <option value="2">Only Spanish</option>
                <option value="1">Only English</option>
                <option value="both">Bilingual</option>
                <option value="3">Other</option>
            </select></td>
	</tr>
	<tr>
		<td ><strong>Evening Phone: </strong></td>
		<td ><input type="text" id="evening_phone_new" /></td>
		<td ><strong>Email Address: </strong></td>
		<td ><input type="text" id="email_new" /></td>
	</tr>
        <tr>
            <!-- This field is required, since it determines what will show up on the profile. -->
            <td><strong>Is this participant a child, youth, or adult? <span class="helptext">(required field)</span></strong></td>
                <td><select id="child_select">
                                <option value="">--------</option>
				<option value="1">Child</option>
				<option value="2">Youth</option>
				<option value="3">Adult</option>
                </select></td>
                <td><strong>Ward:
                    </strong></td><td><input type="text" id="ward_new"></td>
        </tr>
        <tr><td colspan="2">
                <!--The person may be linked to one or more institutions here. -->
                <a href="javascript:;" onclick="$('.hide_institutions').toggle()">Show/Hide Institutions List</a><br/>
                                            <span class="helptext" style="padding-left:30px;">Institutional affiliations can also be added on the Participant Profile.</span>
            </td>
            <td colspan="2"><input type="button" value="Save" onclick="
                //find out whether a participant is a child or not
                var is_child=document.getElementById('child_select').value;
                if (is_child==''){
                    alert('Please indicate whether or not this participant is a child.');
                    return false;
                }
                
        var roles = document.getElementsByName('role[]');
        var role_array= new Array();
        for (var k=0; k<roles.length; k++){
            if (roles[k].checked==true){
                role_array[k] = roles[k].value;
            }
        }
        var insts = document.getElementsByName('institution[]');
        var institution_array= new Array();
        for (var j=0; j<insts.length; j++){
            if (insts[j].checked==true){
                //alert(insts[j]);
                institution_array[j] = insts[j].value;
            }
        }
         //alert(institution_array);
        $.post(
            '../ajax/program_duplicate_check.php',
            {
                person: 1,
                first_name: document.getElementById('first_name_new').value,
		last_name: document.getElementById('last_name_new').value
            },
            function (response){
                /* check first whether a person with this name already exists in the database.  They can still
                 * add a person with the same name, but are warned before so doing.
                 * */
                if (response != ''){
                    var deduplicate = confirm(response);
                    if (deduplicate){
				$.post(
						'../ajax/add_participant.php',
						{
							first_name: document.getElementById('first_name_new').value,
							last_name: document.getElementById('last_name_new').value,
							role: role_array,
							dob: document.getElementById('dob_new').value,
							gender: document.getElementById('gender_new').value,
							age: document.getElementById('age_new').value,
							grade_level: document.getElementById('grade_level_new').value,
							address_num: document.getElementById('street_address_num').value,
                                                        address_dir: document.getElementById('street_address_dir').options[document.getElementById('street_address_dir').selectedIndex].value,
                                                        address_name: document.getElementById('street_address_name').value,
                                                        address_type: document.getElementById('street_address_type').value,
							education_level: document.getElementById('education_level_new').value,
							city: document.getElementById('city_new').value,
							state: document.getElementById('state_new').value,
							zip: document.getElementById('zip_new').value,
							email: document.getElementById('email_new').value,
							day_phone: document.getElementById('day_phone_new').value,
							evening_phone: document.getElementById('evening_phone_new').value,
                                                        lang: document.getElementById('language').options[document.getElementById('language').selectedIndex].value,
                                                        insts: institution_array,
                                                        //pm: document.getElementById('pm_select').options[document.getElementById('pm_select').selectedIndex].value,
                                                        ward: document.getElementById('ward_new').value,
                                                        child: document.getElementById('child_select').options[document.getElementById('child_select').selectedIndex].value
                                                },
						function (response){
							document.getElementById('confirmation').innerHTML = response;
						}
				).fail(failAlert);
                    }
                }
                     else{
                         //alert('not a duplicate');
                         $.post(
						'../ajax/add_participant.php',
						{
							first_name: document.getElementById('first_name_new').value,
							last_name: document.getElementById('last_name_new').value,
							role: role_array,
							dob: document.getElementById('dob_new').value,
							gender: document.getElementById('gender_new').value,
							age: document.getElementById('age_new').value,
							grade_level: document.getElementById('grade_level_new').value,
							address_num: document.getElementById('street_address_num').value,
                                                        address_dir: document.getElementById('street_address_dir').options[document.getElementById('street_address_dir').selectedIndex].value,
                                                        address_name: document.getElementById('street_address_name').value,
                                                        address_type: document.getElementById('street_address_type').value,
							education_level: document.getElementById('education_level_new').value,
							city: document.getElementById('city_new').value,
							state: document.getElementById('state_new').value,
							zip: document.getElementById('zip_new').value,
							email: document.getElementById('email_new').value,
							day_phone: document.getElementById('day_phone_new').value,
							evening_phone: document.getElementById('evening_phone_new').value,
                                                        lang: document.getElementById('language').options[document.getElementById('language').selectedIndex].value,
                                                        insts: institution_array,
                                                       // pm: document.getElementById('pm_select').options[document.getElementById('pm_select').selectedIndex].value,
                                                        ward: document.getElementById('ward_new').value,
                                                        child: document.getElementById('child_select').options[document.getElementById('child_select').selectedIndex].value
                                                },
						function (response){
                                                    //alert(response);
							document.getElementById('confirmation').innerHTML = response;
						}
				).fail(failAlert);
                    }
                }
               ).fail(failAlert);"/></td>
        </tr>
        <tr class="hide_institutions">
            <td colspan="4"><?php
					$get_roles = "SELECT * FROM Institutions";
					include "../include/dbconnopen.php";
					$roles = mysqli_query($cnnLSNA, $get_roles);
					while ($inst = mysqli_fetch_array($roles)) {
					?>
						<input type="checkbox" id="inst_<?php echo $inst['Institution_ID']; ?>_new" name="institution[]"
                                                       value="<?php echo $inst['Institution_ID']; ?>"/><?php echo $inst['Institution_Name']; ?><br/>
					<?php }
					include "../include/dbconnclose.php";
				?></td>
        </tr>
	<tr>
		<td colspan="4" class="blank"></td>
	</tr>

</table>
<div id="confirmation"></div>

<br/><br/>

</div>

<?php include "../../footer.php";?>