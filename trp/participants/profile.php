<?php
include "../../header.php";
include "../header.php";
include "../include/datepicker_simple.php";
/* all information about the given participant */
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#participants_selector').addClass('selected');
        $('.basic_info_edit').hide();
        $('.add_family').hide();
        $('.hide_college_data').hide();
    });
</script>

<?php
$participant_query = "SELECT * FROM Participants WHERE Participant_ID='" . $_GET['id'] . "'";
include "../include/dbconnopen.php";
$get_participant = mysqli_query($cnnTRP, $participant_query);
$parti = mysqli_fetch_array($get_participant);
$date_formatted = explode('-', $parti['DOB']);
$DOB = $date_formatted[1] . "/" . $date_formatted[2] . "/" . $date_formatted[0];

/* program access determines whether the logged-in user can see program-specific information about this person.
 * The Gads Hill users may not be able to see results from museum surveys, for example. */
$get_program_access = "SELECT Program_Access FROM Users_Privileges INNER JOIN Users ON Users.User_Id=Users_Privileges.User_ID
            WHERE User_Email=" . stripslashes($_COOKIE['user']) . " AND Privilege_ID=4";
// echo $get_program_access;
include ($_SERVER['DOCUMENT_ROOT'] . "/include/dbconnopen.php");
$program_access = mysqli_query($cnnLISC, $get_program_access);
$prog_access = mysqli_fetch_row($program_access);
$access = $prog_access[0];
?>
<div class="content_block" id="participant_profile">
    <h3>Participant Profile - <?php echo $parti['First_Name'] . " " . $parti['Last_Name']; ?>
    </h3><hr/><br/>

    <table class="profile_table">
        <tr>
            <td width="40%" rowspan="2"> <!--Basic Info-->
                <table class="inner_table" style="border: 2px solid #696969;">
                    <tr>
                        <td><strong>Database ID: </strong></td>
                        <td><?php echo $parti['Participant_ID']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Name: </strong></td>
                        <td>
                            <span class="basic_info_show"><?php echo $parti['First_Name'] . " " . $parti['Last_Name']; ?></span>
                            <input class="basic_info_edit" id="first_name_edit" value="<?php echo $parti['First_Name']; ?>" style="width:100px;"/>&nbsp;
                            <input class="basic_info_edit" id="last_name_edit" value="<?php echo $parti['Last_Name']; ?>" style="width:100px;"/>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Address: </strong></td>
                        <td>
                            <span class="basic_info_show"><?php echo $parti['Address_Street_Num'] . " " . $parti['Address_Street_Direction'] . " " . $parti['Address_Street_Name'] . " " . $parti['Address_Street_Type'] . "<br/>" . $parti['Address_City'] . ", " . $parti['Address_State'] . " " . $parti['Address_Zipcode']; ?></span>
                            <div class="basic_info_edit">
                                <input id="st_num_edit" value="<?php echo $parti['Address_Street_Num']; ?>" style="width:40px;"/> <input id="st_dir_edit" value="<?php echo $parti['Address_Street_Direction']; ?>" style="width:20px;"/> <input id="st_name_edit" value="<?php echo $parti['Address_Street_Name']; ?>" style="width:100px;"/> <input id="st_type_edit" value="<?php echo $parti['Address_Street_Type']; ?>" style="width:35px;"/> <br/>
                                <input id="city_edit" value="<?php echo $parti['Address_City']; ?>" style="width:100px;"/> <input id="state_edit" value="<?php echo $parti['Address_State']; ?>" style="width:20px;"/> <input id="zip_edit" value="<?php echo $parti['Address_Zipcode']; ?>" style="width:40px;"/> <br/>
                                <span class="helptext">e.g. 1818 S Paulina St<br/>Chicago, IL 60608</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Phone Number: </strong></td>
                        <td>
                            <span class="basic_info_show"><?php echo $parti['Phone']; ?></span>
                            <input class="basic_info_edit" id="phone_edit" value="<?php echo $parti['Phone']; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>E-mail Address: </strong></td>
                        <td>
                            <span class="basic_info_show"><?php echo $parti['Email']; ?></span>
                            <input class="basic_info_edit" id="email_edit" value="<?php echo $parti['Email']; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Date of Birth: </strong></td>
                        <td>
                            <span class="basic_info_show"><?php echo $DOB; ?></span>
                            <input class="basic_info_edit hasDatepickers" id="dob_edit" value="<?php echo $DOB; ?>"/>
                            <span class="basic_info_edit helptext">(MM/DD/YYYY)</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Gender: </strong></td>
                        <td>
                            <span class="basic_info_show"><?php
if ($parti['Gender'] == 'm') {
    echo "Male";
} else if ($parti['Gender'] == 'f') {
    echo "Female";
};
?></span>
                            <select class="basic_info_edit" id="gender_edit"/>
                    <option value="">-------</option>
                    <option value="m" <?php echo($parti['Gender'] == 'm' ? 'selected="selected"' : null); ?>>Male</option>
                    <option value="f" <?php echo($parti['Gender'] == 'f' ? 'selected="selected"' : null); ?>>Female</option>
                    </select>
            </td>
        </tr>
        <tr>
            <td><strong>CPS ID: </strong></td>
            <td>
                <span class="basic_info_show"><?php echo $parti['CPS_ID']; ?></span>
                <input type="text" class="basic_info_edit" value="<?php echo $parti['CPS_ID']; ?>" id="cps_id_edit" />
            </td>
        </tr>
        <tr>
            <td colspan="2"><a href="javascript:;" class="basic_info_show no_view" onclick="
                    $('.basic_info_show').toggle();
                    $('.basic_info_edit').toggle();
                               " style="margin-left:55px;">Edit...</a>
                <a href="javascript:;" class="basic_info_edit" onclick="
                        $.post(
                                '../ajax/edit_participant.php',
                                {
                                    id: '<?php echo $parti['Participant_ID']; ?>',
                                    name: document.getElementById('first_name_edit').value,
                                    surname: document.getElementById('last_name_edit').value,
                                    address_num: document.getElementById('st_num_edit').value,
                                    address_dir: document.getElementById('st_dir_edit').value,
                                    address_name: document.getElementById('st_name_edit').value,
                                    address_type: document.getElementById('st_type_edit').value,
                                    city: document.getElementById('city_edit').value,
                                    state: document.getElementById('state_edit').value,
                                    zip: document.getElementById('zip_edit').value,
                                    phone: document.getElementById('phone_edit').value,
                                    email: document.getElementById('email_edit').value,
                                    dob: document.getElementById('dob_edit').value,
                                    gender: document.getElementById('gender_edit').value,
                                    cps_id: document.getElementById('cps_id_edit').value
                                },
                        function(response) {
                            window.location = 'profile.php?id=<?php echo $parti['Participant_ID'] ?>';
                        }
                        )" style="margin-left:55px;">Save!</a>

        </tr>
    </table>
</td>
<td colspan="2">

    <!-- list of events (both in and out of campaigns) that this person attended: -->
    <h4>Event Attendance</h4>
    <table id="event_attendance" style="margin-left:auto;margin-right:auto;">
        <tr style="font-size:.9em;">
            <th>Date</th>
            <th>Event Name</th>
            <th></th>
        </tr>
        <?php
        $event_attendance = "SELECT * FROM Events INNER JOIN Events_Participants ON Events.Event_ID=Events_Participants.Event_ID WHERE Events_Participants.Participant_ID='" . $parti['Participant_ID'] . "' ORDER BY Events.Event_Date";
        $events = mysqli_query($cnnTRP, $event_attendance);
        while ($event = mysqli_fetch_array($events)) {
            $date_formatted = explode('-', $event['Event_Date']);
            $date = $date_formatted[1] . "/" . $date_formatted[2] . "/" . $date_formatted[0];
            ?>
            <tr>
                <td><?php echo $date; ?></td>
                <td><a href="../engagement/engagement.php?event=<?php echo $event['Event_ID']; ?>"><?php echo $event['Event_Name']; ?></a></td>

                <!-- Clicking "remove" here means deleting this person's attendance at this event: -->
                <td><a href="javascript:;" class="helptext hide_on_view" onclick="
                        $.post(
                                '../ajax/add_attendee.php',
                                {
                                    action: 'remove',
                                    id: '<?php echo $event['Events_Participants_ID']; ?>'
                                },
                        function(response) {
                            //document.write(response);
                            window.location = 'profile.php?id=<?php echo $parti['Participant_ID'] ?>';
                        }
                        )">Remove...</a></td>
            </tr>
            <?php
        }
        ?>
        <!--- add to a new event: -->
        <tr class="no_view"><td><span class="helptext">Add to event:</span></td>
            <td><select id="add_to_event">
                    <option value="">-----------</option>
                    <?php
                    $all_events = "SELECT * FROM Events ORDER BY Event_Date DESC";
                    $select_events = mysqli_query($cnnTRP, $all_events);
                    while ($select_event = mysqli_fetch_array($select_events)) {
                        $date_formatted = explode('-', $select_event['Event_Date']);
                        $date = $date_formatted[1] . "/" . $date_formatted[2] . "/" . $date_formatted[0];
                        ?>
                        <option value="<?php echo $select_event['Event_ID']; ?>"><?php echo $date . ": " . $select_event['Event_Name']; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
            <td><a href="javascript:;" class="helptext"  onclick="
                    $.post(
                            '../ajax/add_attendee.php',
                            {
                                action: 'add',
                                event: document.getElementById('add_to_event').value,
                                person: '<?php echo $parti['Participant_ID'] ?>'
                            },
                    function(response) {
                        //document.write(response);
                        window.location = 'profile.php?id=<?php echo $parti['Participant_ID'] ?>';
                    }
                    )">Add...</a></td>
        </tr>
    </table>
</td>
</tr>
<tr>
    <td>

        <!-- parent/child links: -->
        <h4>Family Members</h4>
        <table width="100%">
            <tr style="font-size:.9em;">
                <th>Name</th>
                <th>Relationship</th>
            </tr>
            <?php
            $get_parents = "SELECT * FROM Participants INNER JOIN Parents_Children ON Participants.Participant_ID=Parents_Children.Parent_ID WHERE Parents_Children.Child_ID='" . $parti['Participant_ID'] . "'";
            $parents = mysqli_query($cnnTRP, $get_parents);
            while ($parent = mysqli_fetch_array($parents)) {
                ?>
                <!-- show this person's parent(s) -->
                <tr>
                    <td><a href="profile.php?id=<?php echo $parent['Participant_ID']; ?>"><?php echo $parent['First_Name'] . " " . $parent['Last_Name']; ?></a></td>
                    <td>Parent</td>
                </tr>
                <?php
            }
            $get_children = "SELECT * FROM Participants INNER JOIN Parents_Children ON Participants.Participant_ID=Parents_Children.Child_ID WHERE Parents_Children.Parent_ID='" . $parti['Participant_ID'] . "'";
            $children = mysqli_query($cnnTRP, $get_children);
            while ($child = mysqli_fetch_array($children)) {
                ?>
                <!-- show this person's child(ren) -->
                <tr>
                    <td><a href="profile.php?id=<?php echo $child['Participant_ID']; ?>"><?php echo $child['First_Name'] . " " . $child['Last_Name']; ?></a></td>
                    <td>Child</td>
                </tr>
                <?php
            }
            ?>
        </table>
        <!-- add a parent or child: -->
        <a class="helptext no_view" href="javascript:;" onclick="
                $('.add_family').slideToggle();
           ">Add family member...</a>
        <div class="add_family">
            <!-- search for users -->
            <table>
                <tr>
                    <td width="20%">First Name:</td><td><input type="text" id="find_fam_firstname" style="width:100px;"/></td>
                    <td>DOB:</td><td><input type="text" id="find_fam_dob" style="width:70px;" /></td></td>
                </tr>
                <tr>
                    <td>Last Name</td><td><input type="text" id="find_fam_lastname" style="width:100px;" />
                    <td colspan="2" style="text-align:center;"><input type="button" value="Search" onclick="
                            $.post(
                                    '../ajax/search_users.php',
                                    {
                                        first: document.getElementById('find_fam_firstname').value,
                                        last: document.getElementById('find_fam_lastname').value,
                                        dob: document.getElementById('find_fam_dob').value,
                                        family_search: '1',
                                        current_user: <?php echo $parti['Participant_ID']; ?>
                                    },
                            function(response) {
                                //document.write(response);
                                document.getElementById('find_fam_results').innerHTML = response;
                            }
                            )"/>
                </tr>
            </table>

            <!-- results show up here, and the family member is chosen from the dropdown and then added as either
            parent or child.-->
            <div id="find_fam_results"></div>
            <br/>
        </div>
    </td>
    <td>

        <!-- CPS consent needs to be tracked by year.  that is done here. -->
        <h4>Consent Forms</h4>
        <table class="inner_table">
            <tr style="font-size:.9em;"><th>School Year</th><th colspan="2">Consent Form Received</th></tr>
            <?php
            //get existing records
            $all_consent = "SELECT * FROM Participants_Consent WHERE Participant_ID='" . $parti['Participant_ID'] . "' ORDER BY School_Year";
            //echo $all_consent;
            include "../include/dbconnopen.php";
            $consents_given = mysqli_query($cnnTRP, $all_consent);
            while ($consent = mysqli_fetch_row($consents_given)) {
                ?>
                <tr><td><?php $years = str_split($consent[2], 2);
                echo '20' . $years[0] . '-20' . $years[1];
                ?></td><td><?php if ($consent[3] == 1) {
                    echo 'Yes';
                } else {
                    echo 'No';
                } ?></td>

                    <td><?php
                        $get_uploads = "SELECT Upload_Id, File_Name FROM Programs_Uploads WHERE Participant_ID='" . $parti['Participant_ID'] . "'
                            AND Year=$consent[2]";
                        $result = mysqli_query($cnnTRP, $get_uploads);
                        if (mysqli_num_rows($result) == 0) {
                            echo "No form has been uploaded <br>";
                        } else {
                            while (list($id, $name) = mysqli_fetch_array($result)) {
                                ?>

                                <a href="/trp/ajax/download.php?id=<?php echo $id; ?>"><?php echo $name; ?></a> <br>
            <?php
        }
    }
    ?>
                        <!-- once a consent year has been added, we can also upload and save the form itself: -->
                        <form id="file_upload_form" action="/trp/ajax/upload_file.php" method="post" enctype="multipart/form-data" class="no_view">
                            <input type="file" name="file" id="file" style="font-size:.7em; padding-top:4px;"/> 
                            <input type="hidden" name="person_id" value="<?php echo $parti['Participant_ID']; ?>">
                            <input type="hidden" name="year" value="<?php echo $consent[2]; ?>">
                            <br />
                            <input type="submit" name="submit" value="Upload" style="font-size:.7em; padding-top:4px;"/>
                        </form>

                    </td></tr>
    <?php
}
// include "../include/dbconnclose.php";
?>
            <tr class="no_view"><!--Add new record-->
                <td><select id="school_year_consent_new">
                        <option value="">-----</option>
                        <option value="1213">2012-2013</option>
                        <option value="1314">2013-2014</option> 
                        <option value="1415">2014-2015</option>
                        <option value="1516">2015-2016</option>
                    </select></td>
                <td><input type="checkbox" id="form_consent_new"></td>
                <td><input type="button" value="Save" onclick="
                        if (document.getElementById('form_consent_new').checked == true) {
                            var consent_given = 1;
                        }
                        else {
                            var consent_given = 0;
                        }
                        $.post(
                                '../ajax/save_consent.php',
                                {
                                    participant: '<?php echo $parti['Participant_ID'] ?>',
                                    year: document.getElementById('school_year_consent_new').value,
                                    form: consent_given
                                },
                        function(response) {
                            //document.write(response);
                            window.location = 'profile.php?id=<?php echo $parti['Participant_ID']; ?>';
                        }
                        )"></td>
                <td>




                </td>
            </tr>
        </table>
    </td>
</tr>


<tr>
    <td colspan="3"><!--Program Participation: separate sections for each program, since the info being tracked is so different...
        Allow them to add people to programs here, too:
        
        Note that the program_access variable at the top determines which of the programs a given user will see:
        -->


        <?php
        $get_programs = "SELECT * FROM Programs INNER JOIN Participants_Programs ON 
                                            Programs.Program_ID=Participants_Programs.Program_ID WHERE 
                                            Participants_Programs.Participant_ID='" . $parti['Participant_ID'] . "' ORDER BY Programs.Program_Name";
        $programs = mysqli_query($cnnTRP, $get_programs);
        if (mysqli_num_rows($programs) < 1) {
            echo "<h4>This participant is not involved in any programs.</h4>";
        }
        while ($program = mysqli_fetch_array($programs)) {
            ?>
            <h4><?php echo $program['Program_Name']; ?></h4>
                            <?php
                            //Early Childhood Education
                            if ($program['Program_ID'] == 1 && ($access == 'a' || $access == 1)) {
                                ?>
                <div class="program_details">
                    <table width="100%">
                        <tr><td><h5>Attendance</h5>
                                <span class="helptext"><a href="javascript:;" onclick="$('#toggler_attendance_1').toggle();">Show/hide dates that this person attended this program.</a></span><br>
                                <?php
                                //get dates for this project, then attendance for this person
                                $get_attendance = "SELECT MONTH(Date), DAY(Date), YEAR(Date) FROM Program_Attendance INNER JOIN Program_Dates ON 
                                                Program_Attendance.Date_ID=Program_Dates.Date_ID
                                                WHERE Program_ID='" . $program['Program_ID'] . "' AND Participant_ID='" .
                                        $parti['Participant_ID'] . "'";
                                // echo $get_attendance;
                                $attendance_dates = mysqli_query($cnnTRP, $get_attendance);
                                ?><div id="toggler_attendance_1"><?php
                                while ($date = mysqli_fetch_row($attendance_dates)) {
                                    echo $date[0] . '/' . $date[1] . '/' . $date[2] . "<br>";
                                }
                                ?>
                                </div><br/><br/></td><td rowspan="2"><h5>GOLD Scores</h5>
                                <?php
                                //add student to a classroom for GOLD averages
                                ?>
                                <span class="helptext">Add participant to a classroom to show classroom averages.</span><br>
                                Classroom: <?php $get_classroom="SELECT Classroom_ID FROM Gold_Classrooms WHERE Student_ID='" . $parti['Participant_ID'] . "'";
                                    include "../include/dbconnopen.php";
                                $classroom=mysqli_query($cnnTRP, $get_classroom);
                                $class_chosen=mysqli_fetch_row($classroom);
                                echo $class_chosen[0];
                                    include "../include/dbconnclose.php";?>
                                <br> <span class="helptext"> Add or edit a classroom here: </span> <select id="add_new_class">
                                    <?php
                                    include "../include/dbconnopen.php";
                                    $get_classrooms="SELECT Classroom_ID FROM Class_Avg_Gold_Scores GROUP BY Classroom_ID";
                                    $classrooms=mysqli_query($cnnTRP, $get_classrooms);
                                    while ($class=mysqli_fetch_row($classrooms)){
                                        ?>
                                    <option><?php echo $class[0];?></option>
                                            <?php
                                    }
                                    include "../include/dbconnclose.php";
                                    ?>
                                </select><input type="button" value="Save" onclick="$.post(
                                       '../ajax/edit_gold_avgs.php',
                                   {
                                       action: 'link_student',
                                       classroom: document.getElementById('add_new_class').value,
                                       student: '<?php echo $parti['Participant_ID'];?>'
                                   },
                                   function(response){
                                      // document.write(response);
                                      window.location = 'profile.php?id=<?php echo $parti['Participant_ID']; ?>';
                                   });">
                                    <?php 
                                //get class avgs based on classroom above
                                $avg_query="SELECT * FROM Class_Avg_Gold_Scores WHERE Classroom_ID='$class_chosen[0]'";
                               // echo $avg_query;
                                include "../include/dbconnopen.php";
                                $avgs=mysqli_query($cnnTRP, $avg_query);
                                while ($avg=mysqli_fetch_array($avgs)){
                                    if ($avg['Test_Year']==1){
                                        //then we're in the first year
                                        if ($avg['Test_Time']==1){
                                            //then this is a pre-test
                                            if ($avg['Question_ID']==1){
                                                $q1_y1_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==2){
                                                $q2_y1_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==3){
                                                $q3_y1_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==4){
                                                $q4_y1_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==5){
                                                $q5_y1_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==6){
                                                $q6_y1_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==7){
                                                $q7_y1_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==8){
                                                $q8_y1_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==9){
                                                $q9_y1_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==10){
                                                $q10_y1_pre=$avg['Class_Avg'];
                                            }
                                        }
                                        if ($avg['Test_Time']==2){
                                            //then this is a mid-test
                                            if ($avg['Question_ID']==1){
                                                $q1_y1_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==2){
                                                $q2_y1_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==3){
                                                $q3_y1_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==4){
                                                $q4_y1_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==5){
                                                $q5_y1_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==6){
                                                $q6_y1_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==7){
                                                $q7_y1_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==8){
                                                $q8_y1_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==9){
                                                $q9_y1_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==10){
                                                $q10_y1_mid=$avg['Class_Avg'];
                                            }
                                        }
                                        if ($avg['Test_Time']==3){
                                            //then this is a post-test
                                            if ($avg['Question_ID']==1){
                                                $q1_y1_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==2){
                                                $q2_y1_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==3){
                                                $q3_y1_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==4){
                                                $q4_y1_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==5){
                                                $q5_y1_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==6){
                                                $q6_y1_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==7){
                                                $q7_y1_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==8){
                                                $q8_y1_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==9){
                                                $q9_y1_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==10){
                                                $q10_y1_post=$avg['Class_Avg'];
                                            }
                                        }
                                    }
                                    elseif ($avg['Test_Year']==2){
                                        //then we're in the second year
                                        if ($avg['Test_Time']==1){
                                            //then this is a pre-test
                                            if ($avg['Question_ID']==1){
                                                $q1_y2_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==2){
                                                $q2_y2_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==3){
                                                $q3_y2_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==4){
                                                $q4_y2_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==5){
                                                $q5_y2_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==6){
                                                $q6_y2_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==7){
                                                $q7_y2_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==8){
                                                $q8_y2_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==9){
                                                $q9_y2_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==10){
                                                $q10_y2_pre=$avg['Class_Avg'];
                                            }
                                        }
                                        if ($avg['Test_Time']==2){
                                            //then this is a mid-test
                                            if ($avg['Question_ID']==1){
                                                $q1_y2_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==2){
                                                $q2_y2_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==3){
                                                $q3_y2_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==4){
                                                $q4_y2_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==5){
                                                $q5_y2_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==6){
                                                $q6_y2_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==7){
                                                $q7_y2_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==8){
                                                $q8_y2_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==9){
                                                $q9_y2_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==10){
                                                $q10_y2_mid=$avg['Class_Avg'];
                                            }
                                        }
                                        if ($avg['Test_Time']==3){
                                            //then this is a post-test
                                            if ($avg['Question_ID']==1){
                                                $q1_y2_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==2){
                                                $q2_y2_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==3){
                                                $q3_y2_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==4){
                                                $q4_y2_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==5){
                                                $q5_y2_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==6){
                                                $q6_y2_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==7){
                                                $q7_y2_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==8){
                                                $q8_y2_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==9){
                                                $q9_y2_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==10){
                                                $q10_y2_post=$avg['Class_Avg'];
                                            }
                                        }
                                    }
                                    if ($avg['Test_Year']==3){
                                        //then we're in the third year
                                        if ($avg['Test_Time']==1){
                                            //then this is a pre-test
                                            if ($avg['Question_ID']==1){
                                                $q1_y3_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==2){
                                                $q2_y3_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==3){
                                                $q3_y3_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==4){
                                                $q4_y3_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==5){
                                                $q5_y3_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==6){
                                                $q6_y3_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==7){
                                                $q7_y3_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==8){
                                                $q8_y3_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==9){
                                                $q9_y3_pre=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==10){
                                                $q10_y3_pre=$avg['Class_Avg'];
                                            }
                                        }
                                        if ($avg['Test_Time']==2){
                                            //then this is a mid-test
                                            if ($avg['Question_ID']==1){
                                                $q1_y3_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==2){
                                                $q2_y3_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==3){
                                                $q3_y3_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==4){
                                                $q4_y3_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==5){
                                                $q5_y3_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==6){
                                                $q6_y3_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==7){
                                                $q7_y3_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==8){
                                                $q8_y3_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==9){
                                                $q9_y3_mid=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==10){
                                                $q10_y3_mid=$avg['Class_Avg'];
                                            }
                                        }
                                        if ($avg['Test_Time']==3){
                                            //then this is a post-test
                                            if ($avg['Question_ID']==1){
                                                $q1_y3_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==2){
                                                $q2_y3_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==3){
                                                $q3_y3_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==4){
                                                $q4_y3_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==5){
                                                $q5_y3_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==6){
                                                $q6_y3_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==7){
                                                $q7_y3_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==8){
                                                $q8_y3_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==9){
                                                $q9_y3_post=$avg['Class_Avg'];
                                            }
                                            elseif ($avg['Question_ID']==10){
                                                $q10_y3_post=$avg['Class_Avg'];
                                            }
                                        }
                                    }
                                }
                                include "../include/dbconnclose.php";
                                
                                //get all gold score info to put in boxes below
                                include "../include/dbconnopen.php";
                                $get_year_1_a = "SELECT * FROM Gold_Score_Totals WHERE Participant='" . $parti['Participant_ID'] . "'
                                                            AND Year=1 AND Test_Time=1 ORDER BY Date_Logged DESC";
                                $year_1_a = mysqli_query($cnnTRP, $get_year_1_a);
                                $year1a = mysqli_fetch_array($year_1_a);
                                $get_year_1_b = "SELECT * FROM Gold_Score_Totals WHERE Participant='" . $parti['Participant_ID'] . "'
                                                            AND Year=1 AND Test_Time=2 ORDER BY Date_Logged DESC";
                                $year_1_b = mysqli_query($cnnTRP, $get_year_1_b);
                                $year1b = mysqli_fetch_array($year_1_b);
                                $get_year_1_c = "SELECT * FROM Gold_Score_Totals WHERE Participant='" . $parti['Participant_ID'] . "'
                                                            AND Year=1 AND Test_Time=3 ORDER BY Date_Logged DESC";
                                $year_1_c = mysqli_query($cnnTRP, $get_year_1_c);
                                $year1c = mysqli_fetch_array($year_1_c);

                                $get_year_2 = "SELECT * FROM Gold_Score_Totals WHERE Participant='" . $parti['Participant_ID'] . "'
                                                            AND Year=2 AND Test_Time=1 ORDER BY Date_Logged DESC";
                                $year_2 = mysqli_query($cnnTRP, $get_year_2);
                                $year2a = mysqli_fetch_array($year_2);
                                $get_year_2 = "SELECT * FROM Gold_Score_Totals WHERE Participant='" . $parti['Participant_ID'] . "'
                                                            AND Year=2 AND Test_Time=2 ORDER BY Date_Logged DESC";
                                //echo $get_year_2;
                                $year_2b = mysqli_query($cnnTRP, $get_year_2);
                                $year2b = mysqli_fetch_array($year_2b);
                                $get_year_2 = "SELECT * FROM Gold_Score_Totals WHERE Participant='" . $parti['Participant_ID'] . "'
                                                            AND Year=2 AND Test_Time=3 ORDER BY Date_Logged DESC";
                                $year_2 = mysqli_query($cnnTRP, $get_year_2);
                                $year2c = mysqli_fetch_array($year_2);

                                $get_year_3 = "SELECT * FROM Gold_Score_Totals WHERE Participant='" . $parti['Participant_ID'] . "'
                                                            AND Year=3 AND Test_Time=1 ORDER BY Date_Logged DESC";
                                $year_3 = mysqli_query($cnnTRP, $get_year_3);
                                $year3a = mysqli_fetch_array($year_3);
                                $get_year_3 = "SELECT * FROM Gold_Score_Totals WHERE Participant='" . $parti['Participant_ID'] . "'
                                                            AND Year=3 AND Test_Time=2 ORDER BY Date_Logged DESC";
                                $year_3 = mysqli_query($cnnTRP, $get_year_3);
                                $year3b = mysqli_fetch_array($year_3);
                                $get_year_3 = "SELECT * FROM Gold_Score_Totals WHERE Participant='" . $parti['Participant_ID'] . "'
                                                            AND Year=3 AND Test_Time=3 ORDER BY Date_Logged DESC";
                                $year_3 = mysqli_query($cnnTRP, $get_year_3);
                                $year3c = mysqli_fetch_array($year_3);
                                
                                include "../include/dbconnclose.php";
                                ?>
                                <table id="gold_scores_table">
                                    <tr style="font-size:1.05em;">
                                        <th ></th><th>Year 1</th><th>Class Averages</th><th>Year 2</th><th>Class Averages</th><th>Year 3</th><th>Class Averages</th>
                                    </tr>
                                    <tr>
                                        <th>Survey Date:</th><th>Pre: <input class="date_field hasDatepickers" id="gold_date1_a" value="<?php echo $year1a['Survey_Date']; ?>"/><br>
                                            Mid: <input class="date_field hasDatepickers" id="gold_date1_b" value="<?php echo $year1b['Survey_Date']; ?>"/> <br>
                                            Post: <input class="date_field hasDatepickers" id="gold_date1_c" value="<?php echo $year1c['Survey_Date']; ?>"/></th>
                                        <th></th>
                                        <th>Pre: <input class="date_field hasDatepickers" id="gold_date2_a" value="<?php echo $year2a['Survey_Date']; ?>"/><br>
                                        Mid: <input class="date_field hasDatepickers" id="gold_date2_b" value="<?php echo $year2b['Survey_Date']; ?>"/><br>
                                            Post: <input class="date_field hasDatepickers" id="gold_date2_c" value="<?php echo $year2c['Survey_Date']; ?>"/></th> <th></th>
                                        <th>Pre: <input class="date_field hasDatepickers" id="gold_date3_a" value="<?php echo $year3a['Survey_Date']; ?>"/><br>
                                        Mid: <input class="date_field hasDatepickers" id="gold_date3_b" value="<?php echo $year3b['Survey_Date']; ?>"/><br>
                                            Post: <input class="date_field hasDatepickers" id="gold_date3_c" value="<?php echo $year3c['Survey_Date']; ?>"/></th> <th></th>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="gold_category">Social-Emotional</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total score:</strong></td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="se_year1_a" value="<?php echo $year1a['Social_Emotional']; ?>"/><br>
                                        Mid: <input class="gold_score" id="se_year1_b" value="<?php echo $year1b['Social_Emotional']; ?>"/><br>
                                            Post: <input class="gold_score" id="se_year1_c" value="<?php echo $year1c['Social_Emotional']; ?>"/></td>
                                        <td><?php 
                                        echo $q1_y1_pre . "<br>" . $q1_y1_mid . "<br>" . $q1_y1_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="se_year2_a"  value="<?php echo $year2a['Social_Emotional']; ?>"/><br>
                                        Mid: <input class="gold_score" id="se_year2_b" value="<?php echo $year2b['Social_Emotional']; ?>"/><br>
                                            Post: <input class="gold_score" id="se_year2_c" value="<?php echo $year2c['Social_Emotional']; ?>"/></td>
                                        <td><?php 
                                        echo $q1_y2_pre . "<br>" . $q1_y2_mid . "<br>" . $q1_y2_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="se_year3_a" value="<?php echo $year3a['Social_Emotional']; ?>"/><br>
                                        Mid: <input class="gold_score" id="se_year3_b" value="<?php echo $year3b['Social_Emotional']; ?>"/><br>
                                            Post: <input class="gold_score" id="se_year3_c" value="<?php echo $year3c['Social_Emotional']; ?>"/></td>
                                        <td><?php 
                                        echo $q1_y3_pre . "<br>" . $q1_y3_mid . "<br>" . $q1_y3_post;
                                        ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="gold_category">Physical</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total score:</strong></td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="phys_year1_a" value="<?php echo $year1a['Physical']; ?>"/><br>
                                            Mid:<input class="gold_score" id="phys_year1_b" value="<?php echo $year1b['Physical']; ?>"/> <br>
                                        Post: <input class="gold_score" id="phys_year1_c" value="<?php echo $year1c['Physical']; ?>"/></td>
                                        <td><?php 
                                        echo $q2_y1_pre . "<br>" .
                                             $q2_y1_mid . "<br>" .
                                             $q2_y1_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="phys_year2_a" value="<?php echo $year2a['Physical']; ?>"/><br>
                                            Mid: <input class="gold_score" id="phys_year2_b" value="<?php echo $year2b['Physical']; ?>"/><br>
                                        Post: <input class="gold_score" id="phys_year2_c" value="<?php echo $year2c['Physical']; ?>"/></td>
                                        <td><?php 
                                        echo $q2_y2_pre . "<br>" .
                                             $q2_y2_mid . "<br>" .
                                             $q2_y2_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="phys_year3_a" value="<?php echo $year3a['Physical']; ?>"/><br>
                                            Mid: <input class="gold_score" id="phys_year3_b" value="<?php echo $year3b['Physical']; ?>"/><br>
                                        Post: <input class="gold_score" id="phys_year3_c" value="<?php echo $year3c['Physical']; ?>"/></td>
                                        <td><?php 
                                        echo $q2_y3_pre . "<br>" .
                                             $q2_y3_mid . "<br>" .
                                             $q2_y3_post;
                                        ?>
                                        </td>
                                    </tr>				
                                    <tr>
                                        <td colspan="7" class="gold_category">Language</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total score:</strong></td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="lang_year1_a" value="<?php echo $year1a['Language']; ?>"/><br>
                                            Mid: <input class="gold_score" id="lang_year1_b" value="<?php echo $year1b['Language']; ?>"/><br>
                                        Post: <input class="gold_score" id="lang_year1_c" value="<?php echo $year1c['Language']; ?>"/></td>
                                        <td><?php 
                                        echo $q3_y1_pre . "<br>" .
                                             $q3_y1_mid . "<br>" .
                                             $q3_y1_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="lang_year2_a" value="<?php echo $year2a['Language']; ?>"/><br>
                                            Mid: <input class="gold_score" id="lang_year2_b" value="<?php echo $year2b['Language']; ?>"/><br>
                                        Post: <input class="gold_score" id="lang_year2_c" value="<?php echo $year2c['Language']; ?>"/></td>
                                        <td><?php 
                                        echo $q3_y2_pre . "<br>" .
                                             $q3_y2_mid . "<br>" .
                                             $q3_y2_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="lang_year3_a" value="<?php echo $year3a['Language']; ?>"/><br>
                                            Mid: <input class="gold_score" id="lang_year3_b" value="<?php echo $year3b['Language']; ?>"/><br>
                                        Post: <input class="gold_score" id="lang_year3_c" value="<?php echo $year3c['Language']; ?>"/></td>
                                        <td><?php 
                                        echo $q3_y3_pre . "<br>" .
                                             $q3_y3_mid . "<br>" .
                                             $q3_y3_post;
                                        ?>
                                        </td>
                                    </tr>				
                                    <tr>
                                        <td colspan="7" class="gold_category">Cognitive</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total score:</strong></td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="cog_year1_a" value="<?php echo $year1a['Cognitive']; ?>"/><br>
                                            Mid: <input class="gold_score" id="cog_year1_b" value="<?php echo $year1b['Cognitive']; ?>"/><br>
                                        Post: <input class="gold_score" id="cog_year1_c" value="<?php echo $year1c['Cognitive']; ?>"/></td>
                                        <td><?php 
                                        echo $q4_y1_pre . "<br>" .
                                             $q4_y1_mid . "<br>" .
                                             $q4_y1_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="cog_year2_a" value="<?php echo $year2a['Cognitive']; ?>"/><br>
                                            Mid: <input class="gold_score" id="cog_year2_b" value="<?php echo $year2b['Cognitive']; ?>"/><br>
                                        Post: <input class="gold_score" id="cog_year2_c" value="<?php echo $year2c['Cognitive']; ?>"/></td>
                                        <td><?php 
                                        echo $q4_y2_pre . "<br>" .
                                             $q4_y2_mid . "<br>" .
                                             $q4_y2_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="cog_year3_a" value="<?php echo $year3a['Cognitive']; ?>"/><br>
                                            Mid: <input class="gold_score" id="cog_year3_b" value="<?php echo $year3b['Cognitive']; ?>"/><br>
                                        Post: <input class="gold_score" id="cog_year3_c" value="<?php echo $year3c['Cognitive']; ?>"/></td><td><?php 
                                        echo $q4_y3_pre . "<br>" .
                                             $q4_y3_mid . "<br>" .
                                             $q4_y3_post;
                                        ?>
                                        </td>
                                    </tr>				
                                    <tr>
                                        <td colspan="7" class="gold_category">Literacy</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total score:</strong></td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="lit_year1_a" value="<?php echo $year1a['Literacy']; ?>"/><br>
                                            Mid: <input class="gold_score" id="lit_year1_b" value="<?php echo $year1b['Literacy']; ?>"/><br>
                                        Post: <input class="gold_score" id="lit_year1_c" value="<?php echo $year1c['Literacy']; ?>"/></td><td><?php 
                                        echo $q5_y1_pre . "<br>" .
                                             $q5_y1_mid . "<br>" .
                                             $q5_y1_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="lit_year2_a" value="<?php echo $year2a['Literacy']; ?>"/><br>
                                            Mid: <input class="gold_score" id="lit_year2_b" value="<?php echo $year2b['Literacy']; ?>"/><br>
                                        Post: <input class="gold_score" id="lit_year2_c" value="<?php echo $year2c['Literacy']; ?>"/></td><td><?php 
                                        echo $q5_y2_pre . "<br>" .
                                             $q5_y2_mid . "<br>" .
                                             $q5_y2_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="lit_year3_a" value="<?php echo $year3a['Literacy']; ?>"/><br>
                                            Mid: <input class="gold_score" id="lit_year3_b" value="<?php echo $year3b['Literacy']; ?>"/><br>
                                        Post: <input class="gold_score" id="lit_year3_c" value="<?php echo $year3c['Literacy']; ?>"/></td><td><?php 
                                        echo $q5_y3_pre . "<br>" .
                                             $q5_y3_mid . "<br>" .
                                             $q5_y3_post;
                                        ?>
                                        </td>
                                    </tr>	
                                    <tr>
                                        <td colspan="7" class="gold_category">Mathematics</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total score:</strong></td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="math_year1_a" value="<?php echo $year1a['Mathematics']; ?>"/><br>
                                            Mid: <input class="gold_score" id="math_year1_b" value="<?php echo $year1b['Mathematics']; ?>"/><br>
                                        Post: <input class="gold_score" id="math_year1_c" value="<?php echo $year1c['Mathematics']; ?>"/></td><td><?php 
                                        echo $q6_y1_pre . "<br>" .
                                             $q6_y1_mid . "<br>" .
                                             $q6_y1_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="math_year2_a" value="<?php echo $year2a['Mathematics']; ?>"/><br>
                                            Mid: <input class="gold_score" id="math_year2_b" value="<?php echo $year2b['Mathematics']; ?>"/><br>
                                        Post: <input class="gold_score" id="math_year2_c" value="<?php echo $year2c['Mathematics']; ?>"/></td><td><?php 
                                        echo $q6_y2_pre . "<br>" .
                                             $q6_y2_mid . "<br>" .
                                             $q6_y2_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">Pre: <input class="gold_score" id="math_year3_a" value="<?php echo $year3a['Mathematics']; ?>"/><br>
                                            Mid: <input class="gold_score" id="math_year3_b" value="<?php echo $year3b['Mathematics']; ?>"/><br>
                                        Post: <input class="gold_score" id="math_year3_c" value="<?php echo $year3c['Mathematics']; ?>"/></td><td><?php 
                                        echo $q6_y3_pre . "<br>" .
                                             $q6_y3_mid . "<br>" .
                                             $q6_y3_post;
                                        ?>
                                        </td>
                                    </tr>	
                                    <tr>
                                        <td colspan="7" class="gold_category">Science and Technology</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total score:</strong></td>
                                        <td class="gold_score_cell">
                                            Pre: <select id="sci_year1_a">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year1a['Science_Tech'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year1a['Science_Tech'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year1a['Science_Tech'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                            Mid: <select id="sci_year1_b">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year1b['Science_Tech'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year1b['Science_Tech'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year1b['Science_Tech'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                            Post: <select id="sci_year1_c">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year1c['Science_Tech'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year1c['Science_Tech'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year1c['Science_Tech'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        </td><td><?php 
                                        echo $q7_y1_pre . "<br>" .
                                             $q7_y1_mid . "<br>" .
                                             $q7_y1_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">
                                        Pre: <select id="sci_year2_a">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year2a['Science_Tech'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year2a['Science_Tech'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year2a['Science_Tech'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Mid: <select id="sci_year2_b">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year2b['Science_Tech'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year2b['Science_Tech'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year2b['Science_Tech'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Post: <select id="sci_year2_c">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year2c['Science_Tech'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year2c['Science_Tech'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year2c['Science_Tech'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select></td><td><?php 
                                        echo $q7_y2_pre . "<br>" .
                                             $q7_y2_mid . "<br>" .
                                             $q7_y2_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">
                                        Pre: <select id="sci_year3_a">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year3a['Science_Tech'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year3a['Science_Tech'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year3a['Science_Tech'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Mid: <select id="sci_year3_b">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year3b['Science_Tech'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year3b['Science_Tech'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year3b['Science_Tech'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Post: <select id="sci_year3_c">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year3c['Science_Tech'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year3c['Science_Tech'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year3c['Science_Tech'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select></td><td><?php 
                                        echo $q7_y3_pre . "<br>" .
                                             $q7_y3_mid . "<br>" .
                                             $q7_y3_post;
                                        ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="gold_category">Social Studies</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total score:</strong></td>
                                        <td class="gold_score_cell">
                                        Pre: <select id="soc_year1_a">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year1a['Social_Studies'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year1a['Social_Studies'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year1a['Social_Studies'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Mid: <select id="soc_year1_b">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year1b['Social_Studies'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year1b['Social_Studies'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year1b['Social_Studies'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Post: <select id="soc_year1_c">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year1c['Social_Studies'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year1c['Social_Studies'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year1c['Social_Studies'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select></td><td><?php 
                                        echo $q8_y1_pre . "<br>" .
                                             $q8_y1_mid . "<br>" .
                                             $q8_y1_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">
                                        Pre: <select id="soc_year2_a">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year2a['Social_Studies'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year2a['Social_Studies'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year2a['Social_Studies'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Mid: <select id="soc_year2_b">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year2b['Social_Studies'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year2b['Social_Studies'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year2b['Social_Studies'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Post: <select id="soc_year2_c">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year2c['Social_Studies'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year2c['Social_Studies'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year2c['Social_Studies'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select></td><td><?php 
                                        echo $q8_y2_pre . "<br>" .
                                             $q8_y2_mid . "<br>" .
                                             $q8_y2_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">
                                        Pre: <select id="soc_year3_a">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year3a['Social_Studies'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year3a['Social_Studies'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year3a['Social_Studies'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Mid: <select id="soc_year3_b">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year3b['Social_Studies'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year3b['Social_Studies'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year3b['Social_Studies'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Post: <select id="soc_year3_c">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year3c['Social_Studies'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year3c['Social_Studies'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year3c['Social_Studies'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select></td><td><?php 
                                        echo $q8_y3_pre . "<br>" .
                                             $q8_y3_mid . "<br>" .
                                             $q8_y3_post;
                                        ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="gold_category">Creative Arts Expression</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total score:</strong></td>
                                        <td class="gold_score_cell">
                                        Pre: <select id="arts_year1_a">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year1a['Creative_Arts'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year1a['Creative_Arts'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year1a['Creative_Arts'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Mid: <select id="arts_year1_b">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year1b['Creative_Arts'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year1b['Creative_Arts'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year1b['Creative_Arts'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Post: <select id="arts_year1_c">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year1c['Creative_Arts'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year1c['Creative_Arts'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year1c['Creative_Arts'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select></td><td><?php 
                                        echo $q9_y1_pre . "<br>" .
                                             $q9_y1_mid . "<br>" .
                                             $q9_y1_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">
                                        Pre: <select id="arts_year2_a">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year2a['Creative_Arts'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year2a['Creative_Arts'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year2a['Creative_Arts'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Mid: <select id="arts_year2_b">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year2b['Creative_Arts'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year2b['Creative_Arts'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year2b['Creative_Arts'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Post: <select id="arts_year2_c">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year2c['Creative_Arts'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year2c['Creative_Arts'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year2c['Creative_Arts'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select></td><td><?php 
                                        echo $q9_y2_pre . "<br>" .
                                             $q9_y2_mid . "<br>" .
                                             $q9_y2_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">
                                        Pre: <select id="arts_year3_a">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year3a['Creative_Arts'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year3a['Creative_Arts'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year3a['Creative_Arts'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Mid: <select id="arts_year3_b">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year3b['Creative_Arts'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year3b['Creative_Arts'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year3b['Creative_Arts'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Post: <select id="arts_year3_c">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year3c['Creative_Arts'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year3c['Creative_Arts'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year3c['Creative_Arts'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select></td><td><?php 
                                        echo $q9_y3_pre . "<br>" .
                                             $q9_y3_mid . "<br>" .
                                             $q9_y3_post;
                                        ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="gold_category">English Language Development</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total score:</strong></td>
                                        <td class="gold_score_cell">
                                        Pre: <select id="english_year1_a">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year1a['English'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year1a['English'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year1a['English'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Mid: <select id="english_year1_b">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year1b['English'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year1b['English'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year1b['English'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Post: <select id="english_year1_c">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year1c['English'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year1c['English'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year1c['English'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select></td>
                                            <td><?php 
                                        echo $q10_y1_pre . "<br>" .
                                             $q10_y1_mid . "<br>" .
                                             $q10_y1_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">
                                        Pre: <select id="english_year2_a">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year2a['English'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year2a['English'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year2a['English'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Mid: <select id="english_year2_b">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year2b['English'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year2b['English'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year2b['English'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Post: <select id="english_year2_c">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year2c['English'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year2c['English'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year2c['English'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select></td><td><?php 
                                        echo $q10_y2_pre . "<br>" .
                                             $q10_y2_mid . "<br>" .
                                             $q10_y2_post;
                                        ?>
                                        </td>
                                        <td class="gold_score_cell">
                                        Pre: <select id="english_year3_a">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year3a['English'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year3a['English'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year3a['English'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Mid: <select id="english_year3_b">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year3b['English'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year3b['English'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year3b['English'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select>
                                        Post: <select id="english_year3_c">
                                                <option value="">-----</option>
                                                <option value="1" <?php echo ($year3c['English'] == '1' ? 'selected=="selected"' : null) ?>>No Evidence Yet</option>
                                                <option value="2" <?php echo ($year3c['English'] == '2' ? 'selected=="selected"' : null) ?>>Emerging</option>
                                                <option value="3" <?php echo ($year3c['English'] == '3' ? 'selected=="selected"' : null) ?>>Meets Program Expectation</option>
                                            </select></td><td><?php 
                                        echo $q10_y3_pre . "<br>" .
                                             $q10_y3_mid . "<br>" .
                                             $q10_y3_post;
                                        ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- save GOLD scores -->
                                        <td colspan="7" style="text-align:center;"><input type="button" value="Save" onclick="
                                                $.post(
                                                        '../ajax/save_gold_scores.php',
                                                        {
                                                            person: '<?php echo $parti['Participant_ID']; ?>',
                                                            social1_a: document.getElementById('se_year1_a').value,
                                                            social1_b: document.getElementById('se_year1_b').value,
                                                            social1_c: document.getElementById('se_year1_c').value,
                                                            physical1_a: document.getElementById('phys_year1_a').value,
                                                            physical1_b: document.getElementById('phys_year1_b').value,
                                                            physical1_c: document.getElementById('phys_year1_c').value,
                                                            language1_a: document.getElementById('lang_year1_a').value,
                                                            language1_b: document.getElementById('lang_year1_b').value,
                                                            language1_c: document.getElementById('lang_year1_c').value,
                                                            cognitive1_a: document.getElementById('cog_year1_a').value,
                                                            cognitive1_b: document.getElementById('cog_year1_b').value,
                                                            cognitive1_c: document.getElementById('cog_year1_c').value,
                                                           literacy1_a: document.getElementById('lit_year1_a').value,
                                                            literacy1_b: document.getElementById('lit_year1_b').value,
                                                            literacy1_c: document.getElementById('lit_year1_c').value,
                                                            math1_a: document.getElementById('math_year1_a').value,
                                                            math1_b: document.getElementById('math_year1_b').value,
                                                            math1_c: document.getElementById('math_year1_c').value,
                                                            science1_a: document.getElementById('sci_year1_a').value,
                                                            science1_b: document.getElementById('sci_year1_b').value,
                                                            science1_c: document.getElementById('sci_year1_c').value,
                                                            socstud1_a: document.getElementById('soc_year1_a').value,
                                                            socstud1_b: document.getElementById('soc_year1_b').value,
                                                            socstud1_c: document.getElementById('soc_year1_c').value,
                                                            creative1_a: document.getElementById('arts_year1_a').value,
                                                            creative1_b: document.getElementById('arts_year1_b').value,
                                                            creative1_c: document.getElementById('arts_year1_c').value,
                                                            english1_a: document.getElementById('english_year1_a').value,
                                                            english1_b: document.getElementById('english_year1_b').value,
                                                            english1_c: document.getElementById('english_year1_c').value,
                                                            date1_a: document.getElementById('gold_date1_a').value,
                                                            date1_b: document.getElementById('gold_date1_b').value,
                                                            date1_c: document.getElementById('gold_date1_c').value,
                                                            social2_a: document.getElementById('se_year2_a').value,
                                                            social2_b: document.getElementById('se_year2_b').value,
                                                            social2_c: document.getElementById('se_year2_c').value,
                                                            physical2_a: document.getElementById('phys_year2_a').value,
                                                            physical2_b: document.getElementById('phys_year2_b').value,
                                                            physical2_c: document.getElementById('phys_year2_c').value,
                                                            language2_a: document.getElementById('lang_year2_a').value,
                                                            language2_b: document.getElementById('lang_year2_b').value,
                                                            language2_c: document.getElementById('lang_year2_c').value,
                                                            cognitive2_a: document.getElementById('cog_year2_a').value,
                                                            cognitive2_b: document.getElementById('cog_year2_b').value,
                                                            cognitive2_c: document.getElementById('cog_year2_c').value,
                                                            literacy2_a: document.getElementById('lit_year2_a').value,
                                                            literacy2_b: document.getElementById('lit_year2_b').value,
                                                            literacy2_c: document.getElementById('lit_year2_c').value,
                                                            math2_a: document.getElementById('math_year2_a').value,
                                                            math2_b: document.getElementById('math_year2_b').value,
                                                            math2_c: document.getElementById('math_year2_c').value,
                                                            science2_a: document.getElementById('sci_year2_a').value,
                                                            science2_b: document.getElementById('sci_year2_b').value,
                                                            science2_c: document.getElementById('sci_year2_c').value,
                                                            socstud2_a: document.getElementById('soc_year2_a').value,
                                                            socstud2_b: document.getElementById('soc_year2_b').value,
                                                            socstud2_c: document.getElementById('soc_year2_c').value,
                                                            creative2_a: document.getElementById('arts_year2_a').value,
                                                            creative2_b: document.getElementById('arts_year2_b').value,
                                                            creative2_c: document.getElementById('arts_year2_c').value,
                                                            english2_a: document.getElementById('english_year2_a').value,
                                                            english2_b: document.getElementById('english_year2_b').value,
                                                            english2_c: document.getElementById('english_year2_c').value,
                                                            date2_a: document.getElementById('gold_date2_a').value,
                                                            date2_b: document.getElementById('gold_date2_b').value,
                                                            date2_c: document.getElementById('gold_date2_c').value,
                                                            social3_a: document.getElementById('se_year3_a').value,
                                                            social3_b: document.getElementById('se_year3_b').value,
                                                            social3_c: document.getElementById('se_year3_c').value,
                                                            physical3_a: document.getElementById('phys_year3_a').value,
                                                            physical3_b: document.getElementById('phys_year3_b').value,
                                                            physical3_c: document.getElementById('phys_year3_c').value,
                                                            language3_a: document.getElementById('lang_year3_a').value,
                                                            language3_b: document.getElementById('lang_year3_b').value,
                                                            language3_c: document.getElementById('lang_year3_c').value,
                                                            cognitive3_a: document.getElementById('cog_year3_a').value,
                                                            cognitive3_b: document.getElementById('cog_year3_b').value,
                                                            cognitive3_c: document.getElementById('cog_year3_c').value,
                                                            literacy3_a: document.getElementById('lit_year3_a').value,
                                                            literacy3_b: document.getElementById('lit_year3_b').value,
                                                            literacy3_c: document.getElementById('lit_year3_c').value,
                                                            math3_a: document.getElementById('math_year3_a').value,
                                                            math3_b: document.getElementById('math_year3_b').value,
                                                            math3_c: document.getElementById('math_year3_c').value,
                                                            science3_a: document.getElementById('sci_year3_a').value,
                                                            science3_b: document.getElementById('sci_year3_b').value,
                                                            science3_c: document.getElementById('sci_year3_c').value,
                                                            socstud3_a: document.getElementById('soc_year3_a').value,
                                                            socstud3_b: document.getElementById('soc_year3_b').value,
                                                            socstud3_c: document.getElementById('soc_year3_c').value,
                                                            creative3_a: document.getElementById('arts_year3_a').value,
                                                            creative3_b: document.getElementById('arts_year3_b').value,
                                                            creative3_c: document.getElementById('arts_year3_c').value,
                                                            english3_a: document.getElementById('english_year3_a').value,
                                                            english3_b: document.getElementById('english_year3_b').value,
                                                            english3_c: document.getElementById('english_year3_c').value,
                                                            date3_a: document.getElementById('gold_date3_a').value,
                                                            date3_b: document.getElementById('gold_date3_b').value,
                                                            date3_c: document.getElementById('gold_date3_c').value
                                                                    //date: document.getElementById('')
                                                        },
                                                function(response) {
                                                    //document.write(response);
                                                    //alert('test');
                                                    window.location = 'profile.php?id=<?php echo $parti['Participant_ID']; ?>';
                                                }
                                                )
                                                                                          "/></td>
                                    </tr>
                                </table></td></tr>
                        <!-- early child hood notes. -->
                        <tr><td><h5>Notes</h5>
                                <textarea onblur="$.post(
                                                '../ajax/add_participant_to_program.php',
                                                {
                                                    action: 'save_note',
                                                    note: this.value,
                                                    id: '<?php echo $program['Participant_Program_ID']; ?>'
                                                },
                                        function(response) {
                                            // document.write(response);
                                            window.location = 'profile.php?id=<?php echo $parti['Participant_ID']; ?>';
                                        })"><?php echo $program['Notes'] ?></textarea>
                            </td></tr>
                    </table>



                </div>
        <?php
        //Middle School to High School teacher exchange
    } else if (($program['Program_ID'] == 2 || $program['Program_ID'] == 4) && ($access == 'a' || $access == 2 || $access == 4)) {
        $get_transition_info = "SELECT * FROM Explore_Scores WHERE Participant_ID=" . $parti['Participant_ID'] .
                " AND Program_ID='" . $program['Program_ID'] . "'";
        include "../include/dbconnopen.php";
        $transit = mysqli_query($cnnTRP, $get_transition_info);
        $transition_info = mysqli_fetch_array($transit);
        ?>
                <div class="program_details">
                    <h5>Attendance</h5>
                    <!-- the attendance area is empty for now, pending how they want to include attendance. -->
                    <br/>
                    <h5>Explore Scores</h5>
                    <span class="helptext">Adding new explore scores will overwrite the old ones, so be sure
                        to include all the available information.  Editing explore scores in one program will affect them in all
                        programs this student is participating in.</span>
                    <table class="grades_table">
                        <tr>
                            <th>School</th>
                            <th>School Year</th>
                            <th>Explore Score Pre</th>
                            <th>Explore Score Mid</th>
                            <th>Explore Score Post</th>
                            <th>Explore Score 9th Grade Fall</th>
                            <th>ISAT: Math</th>
                            <th>ISAT: Reading</th>
                            <th>ISAT: Total</th>
                            <th></th>
                        </tr>
                                <?php
                                /* show explore scores.  note that only one explore score line will be saved for any person,
                                 * so changes below will overwrite this line, not add another line. */
                                $show_explore = "SELECT * FROM Explore_Scores WHERE Participant_ID=" . $parti['Participant_ID'];
                                $explore = mysqli_query($cnnTRP, $show_explore);
                                while ($ex = mysqli_fetch_array($explore)) {
                                    ?><tr>
                                <td><?php
                        //get school
                        $this_school = "SELECT School_Name FROM Schools WHERE School_ID=" . $ex['School'];
                        $school = mysqli_query($cnnTRP, $this_school);
                        $show_school = mysqli_fetch_row($school);
                        echo $show_school[0];
                        ?></td>
                                <td><?php
                        if ($ex['School_Year'] == '1213') {
                            echo '2012-2013';
                        } else if ($ex['School_Year'] == '1314') {
                            echo '2013-2014';
                        } else if ($ex['School_Year'] == '1415') {
                            echo '2014-2015';
                        } else if ($ex['School_Year'] == '1516') {
                            echo '2015-2016';
                        }
                                    ?></td>
                                <td><?php echo $ex['Explore_Score_Pre']; ?></td>
                                <td><?php echo $ex['Explore_Score_Mid']; ?></td>
                                <td><?php echo $ex['Explore_Score_Post']; ?></td>
                                <td><?php echo $ex['Explore_Score_Fall']; ?></td>
                                <td><?php echo $ex['Reading_ISAT']; ?></td>
                                <td><?php echo $ex['Math_ISAT']; ?></td>
                                <td><?php echo $ex['Reading_ISAT'] + $ex['Math_ISAT']; ?></td>
                            </tr><?php
                        }
                        //include "../include/dbconnclose.php";
                        ?>
                        <tr><td><select id="school_new_<?php echo $program['Program_ID']; ?>">
        <?php
        $select_schools = "SELECT * FROM Schools ORDER BY School_Name";
        include "../include/dbconnopen.php";
        $schools = mysqli_query($cnnTRP, $select_schools);
        while ($school = mysqli_fetch_row($schools)) {
            ?>"
                                        <option value="<?php echo $school[0]; ?>"><?php echo $school[1]; ?></option>
                                    <?php
                                }
                                ?>
                                </select></td>
                            <td><select id="ex_school_year_new_<?php echo $program['Program_ID']; ?>">
                                    <option value="">-----</option>
                                    <option value="1213">2012-2013</option>
                                    <option value="1314">2013-2014</option>
                                    <option value="1415">2014-2015</option>
                                    <option value="1516">2015-2016</option>
                                </select></td>
                            <td><input type="text" id="pre_new_<?php echo $program['Program_ID']; ?>" style="width:20px;" /></td>
                            <td><input type="text" id="mid_new_<?php echo $program['Program_ID']; ?>" style="width:20px;" /></td>
                            <td><input type="text" id="post_new_<?php echo $program['Program_ID']; ?>" style="width:20px;" /></td>
                            <td><input type="text" id="fall_new_<?php echo $program['Program_ID']; ?>" style="width:20px;" /></td>
                            <td><input type="text" id="reading_isat_new_<?php echo $program['Program_ID']; ?>" style="width:20px;" /></td>
                            <td><input type="text" id="math_isat_new_<?php echo $program['Program_ID']; ?>" style="width:20px;" /></td>
                            <td></td>
                            <td><a href="javascript:;" onclick="$.post(
                                            '../ajax/save_transition_info.php',
                                            {
                                                action: 'explore',
                                                person: '<?php echo $parti['Participant_ID']; ?>',
                                                pre: document.getElementById('pre_new_<?php echo $program['Program_ID']; ?>').value,
                                                mid: document.getElementById('mid_new_<?php echo $program['Program_ID']; ?>').value,
                                                post: document.getElementById('post_new_<?php echo $program['Program_ID']; ?>').value,
                                                fall: document.getElementById('fall_new_<?php echo $program['Program_ID']; ?>').value,
                                                reading: document.getElementById('reading_isat_new_<?php echo $program['Program_ID']; ?>').value,
                                                math: document.getElementById('math_isat_new_<?php echo $program['Program_ID']; ?>').value,
                                                program: '<?php echo $program['Program_ID']; ?>',
                                                school: document.getElementById('school_new_<?php echo $program['Program_ID'] ?>').value,
                                                school_year: document.getElementById('ex_school_year_new_<?php echo $program['Program_ID']; ?>').value
                                            },
                                    function(response) {
                                        //document.write(response);
                                        window.location = 'profile.php?id=<?php echo $parti['Participant_ID']; ?>';
                                    }
                                    )">Save...</a></td>
                        </tr>
                    </table>
                    <br/><br/>

                    <!-- add and display academic information: -->
                    <h5>Academic Information</h5>
                    <?php
                    if ($program['Program_ID'] == 2 ){
                    /* get the classroom information */
                    $exchange_rooms="SELECT * FROM Teacher_Exchange_Rooms WHERE Participant_ID='".$parti['Participant_ID']."'";
                    //echo $exchange_rooms;
                    include "../include/dbconnopen.php";
                    $rooms=mysqli_query($cnnTRP, $exchange_rooms);
                    $room_info=mysqli_fetch_array($rooms);
                   // include "../include/dbconnclose.php";
                    ?>
                    <table class='grades_table'>
                        <tr><th>Classroom Number:</th><td><input type='text' value="<?php echo $room_info['Classroom']?>" id='classroom_<?php echo $parti['Participant_ID']; ?>'></td></tr>
                        <tr><th>Teacher name:</th><td><input type='text' value="<?php echo $room_info['Home_Teacher']?>" id='teacher_name_<?php echo $parti['Participant_ID']; ?>'></td></tr>
                        <tr><th>Exchange Teacher name:</th><td><input type='text' value="<?php echo $room_info['Exchange_Teacher']?>" id='exch_teach_name_<?php echo $parti['Participant_ID']; ?>'></td></tr>
                        <tr><td colspan='2'><input type='submit' value='Save' onclick=" 
                                                   $.post(
                                                        '../ajax/save_transition_info.php',
                                                {
                                                    action: 'room',
                                                    student: <?php echo $parti['Participant_ID']; ?>,
                                                    classroom: document.getElementById('classroom_<?php echo $parti['Participant_ID']; ?>').value,
                                                    teacher: document.getElementById('teacher_name_<?php echo $parti['Participant_ID']; ?>').value,
                                                    exchange_teacher: document.getElementById('exch_teach_name_<?php echo $parti['Participant_ID']; ?>').value
                                                },
                                    function(response) {
                                      // document.write(response);
                                        window.location = 'profile.php?id=<?php echo $parti['Participant_ID']; ?>';
                                    }
                                                        
                                                        )"></td></tr>
                    </table>
                    
                    <?php }?>
                    
                    <table class="grades_table">
                        <tr>
                            <th>School</th>
                            <th>School Year</th>
                            <th>Qtr.</th>
                            <th>GPA</th>
                            <th>Math Grade</th>
                            <th>Reading Grade</th>
                            <th>Grade in School</th>
                            <th></th>
                        </tr>
        <?php
        $get_grades = "SELECT * FROM Academic_Info WHERE Participant_ID='" . $parti['Participant_ID'] . "'  ORDER BY School_Year,Quarter";
        //echo $get_grades;
        $all_grades = mysqli_query($cnnTRP, $get_grades);
        while ($grades = mysqli_fetch_array($all_grades)) {
            ?>
                            <tr><td><?php
                //get school
                $this_school = "SELECT School_Name FROM Schools WHERE School_ID=" . $grades['School'];
                $school = mysqli_query($cnnTRP, $this_school);
                $show_school = mysqli_fetch_row($school);
                echo $show_school[0];
            ?></td>
                                <td><?php
                            if ($grades['School_Year'] != '') {//echo $grades['School_Year'] . "<br>";
                                $years = str_split($grades['School_Year'], 2);
                                echo '20' . $years[0] . '-20' . $years[1];
                            }
            ?></td>
                                <td><?php echo $grades['Quarter']; ?></td>
                                <td><?php echo $grades['GPA']; ?></td>
                                <td><?php echo $grades['Math_Grade']; ?></td>
                                <td><?php echo $grades['Lang_Grade']; ?></td>
                                <td><?php echo $grades['Grade_Level']; ?></td>

                            </tr>
            <?php
        }
        ?>
                        <!-- add new academic info: -->
                        <tr><td><select id="aca_school_new_<?php echo $program['Program_ID']; ?>">
        <?php
        $select_schools = "SELECT * FROM Schools ORDER BY School_Name";
        include "../include/dbconnopen.php";
        $schools = mysqli_query($cnnTRP, $select_schools);
        while ($school = mysqli_fetch_row($schools)) {
            ?>"
                                        <option value="<?php echo $school[0]; ?>"><?php echo $school[1]; ?></option>
                                <?php
                            }
                            ?>
                                </select></td>
                            <td><select id="year_new_<?php echo $program['Program_ID']; ?>" /> 
                        <option value="">-----</option>
                        <option value="1213">2012-2013</option>
                        <option value="1314">2013-2014</option>
                        <option value="1415">2014-2015</option>
                        <option value="1516">2015-2016</option>
                        </select></td>
                        <td><input type="text" id="quarter_new_<?php echo $program['Program_ID']; ?>" style="width: 20px;" /></td>
                        <td><input type="text" id="gpa_new_<?php echo $program['Program_ID']; ?>" style="width: 20px;" /></td>
                        <td><input type="text" id="math_grade_new_<?php echo $program['Program_ID']; ?>" style="width: 20px;" /></td>
                        <td><input type="text" id="lang_grade_new_<?php echo $program['Program_ID']; ?>" style="width: 20px;" /></td>
                        <td><input type="text" id="school_grade_<?php echo $program['Program_ID']; ?>" style="width:20px;"></td>

        <!--								<td><input type="text" id="isat_new" style="width: 30px;" /></td>
        <td><input type="text" id="isat_math_new" style="width: 30px;" /></td>
        <td><input type="text" id="isat_lang_new" style="width: 30px;" /></td>-->
                        <td><a class="helptext" href="javascript:;" onclick="
                                $.post(
                                        '../ajax/add_aca_info.php',
                                        {
                                            program: 2,
                                            participant: <?php echo $parti['Participant_ID']; ?>,
                                            year: document.getElementById('year_new_<?php echo $program['Program_ID']; ?>').value,
                                            quarter: document.getElementById('quarter_new_<?php echo $program['Program_ID']; ?>').value,
                                            gpa: document.getElementById('gpa_new_<?php echo $program['Program_ID']; ?>').value,
                                            math: document.getElementById('math_grade_new_<?php echo $program['Program_ID']; ?>').value,
                                            lang: document.getElementById('lang_grade_new_<?php echo $program['Program_ID']; ?>').value,
                                            grade: document.getElementById('school_grade_<?php echo $program['Program_ID']; ?>').value,
                                            //isat: document.getElementById('isat_new_<?php echo $program['Program_ID']; ?>').value,
                                            //isat_math: document.getElementById('isat_math_new_<?php echo $program['Program_ID']; ?>').value,
                                            school: document.getElementById('aca_school_new_<?php echo $program['Program_ID']; ?>').value
                                        },
                                function(response) {
                                    //  document.write(response);
                                    window.location = 'profile.php?id=<?php echo $parti['Participant_ID']; ?>';
                                }
                                )">Add new...</td>
                        </tr>
                    </table>
                    <br/><br/>

                    <!-- show and add disciplinary records: -->
                    <h5>Discipline Records</h5>
                    <table class="grades_table">
                        <tr><th>School</th>
                            <th>School Year</th>
                            <th>Grade</th>
                            <th>Quarter</th>
                            <th>Number of Tardies</th>
                            <th>Excused Absences</th>
                            <th>Unexcused Absences</th>
                            <th>In School Suspensions</th>
                            <th>Out of School Suspensions</th>
                            <th>Referrals</th>
                            <th></th>
                        </tr>
                                    <?php
                                    $show_discipline = "SELECT * FROM MS_to_HS_Over_Time WHERE Participant_ID=" . $parti['Participant_ID'];

                                    $explore = mysqli_query($cnnTRP, $show_discipline);
                                    while ($exp = mysqli_fetch_array($explore)) {
                                        ?><tr><td><?php
                                        //get school
                                        $this_school = "SELECT School_Name FROM Schools WHERE School_ID=" . $exp['School_ID'];
                                        $school = mysqli_query($cnnTRP, $this_school);
                                        $show_school = mysqli_fetch_row($school);
                                        echo $show_school[0];
                                        ?></td>
                                <td><?php echo $exp['School_Year'] ?></td>
                                <td><?php echo $exp['Grade']; ?></td>
                                <td><?php echo $exp['Quarter']; ?></td>
                                <td><?php echo $exp['School_Tardies']; ?></td>
                                <td><?php echo $exp['School_Absences_Excused']; ?></td>
                                <td><?php echo $exp['School_Absences_Unexcused']; ?></td>
                                <td><?php echo $exp['In_School_Suspensions']; ?></td>
                                <td><?php echo $exp['Out_School_Suspensions']; ?></td>
                                <td><?php echo $exp['Discipline_Referrals']; ?></td>
                                <td></td>
                            </tr><?php }
                                    ?>
                        <tr><td><select id="dis_school_new_<?php echo $program['Program_ID']; ?>">
                                <?php
                                $select_schools = "SELECT * FROM Schools ORDER BY School_Name";
                                include "../include/dbconnopen.php";
                                $schools = mysqli_query($cnnTRP, $select_schools);
                                while ($school = mysqli_fetch_row($schools)) {
                                    ?>"
                                        <option value="<?php echo $school[0]; ?>"><?php echo $school[1]; ?></option>
                                    <?php
                                }
                                ?>
                                </select></td><td><select id="dis_year_new_<?php echo $program['Program_ID']; ?>">
                                    <option value="">-----</option>
                                    <option>2012-13</option>
                                    <option>2013-14</option>
                                    <option>2014-15</option>
                                    <option>2015-16</option>
                                </select></td>
                            <td><input type="text" id="dis_grade_new_<?php echo $program['Program_ID']; ?>" style="width: 20px;" /></td>
                            <td><input type="text" id="dis_quarter_new_<?php echo $program['Program_ID']; ?>" style="width: 20px;" /></td>
                            <td><input type="text" id="tardies_new_<?php echo $program['Program_ID']; ?>" style="width: 20px;" /></td>
                            <td><input type="text" id="excused_new_<?php echo $program['Program_ID']; ?>" style="width: 20px;" /></td>
                            <td><input type="text" id="unexcused_new_<?php echo $program['Program_ID']; ?>" style="width: 20px;" /></td>
                            <td><input type="text" id="in_school_new_<?php echo $program['Program_ID']; ?>" style="width:20px;"></td>
                            <td><input type="text" id="out_school_new_<?php echo $program['Program_ID']; ?>" style="width: 30px;" /></td>
                            <td><input type="text" id="referrals_new_<?php echo $program['Program_ID']; ?>" style="width: 30px;" /></td>
                            <td><a class="helptext" href="javascript:;" onclick="
                                    $.post(
                                            '../ajax/save_transition_info.php',
                                            {
                                                action: 'discipline',
                                                participant: <?php echo $parti['Participant_ID']; ?>,
                                                year: document.getElementById('dis_year_new_<?php echo $program['Program_ID']; ?>').value,
                                                grade: document.getElementById('dis_grade_new_<?php echo $program['Program_ID']; ?>').value,
                                                quarter: document.getElementById('dis_quarter_new_<?php echo $program['Program_ID']; ?>').value,
                                                tardy: document.getElementById('tardies_new_<?php echo $program['Program_ID']; ?>').value,
                                                excused: document.getElementById('excused_new_<?php echo $program['Program_ID']; ?>').value,
                                                unexcused: document.getElementById('unexcused_new_<?php echo $program['Program_ID']; ?>').value,
                                                in_sus: document.getElementById('in_school_new_<?php echo $program['Program_ID']; ?>').value,
                                                out_sus: document.getElementById('out_school_new_<?php echo $program['Program_ID']; ?>').value,
                                                referrals: document.getElementById('referrals_new_<?php echo $program['Program_ID']; ?>').value,
                                                program: '<?php echo $program['Program_ID']; ?>',
                                                school: document.getElementById('dis_school_new_<?php echo $program['Program_ID']; ?>').value
                                            },
                                    function(response) {
                                        //    document.write(response);
                                        window.location = 'profile.php?id=<?php echo $parti['Participant_ID']; ?>';
                                    }
                                    )">Add new...</td>
                        </tr>
                    </table>
                    <br/><br/>
                </div>
        <?php
        //New Horizons/Gads Hill tutoring
    } else if ($program['Program_ID'] == 3 && ($access == 'a' || $access == 3)) {
        ?>
                <div class="program_details">
                    <h5>Attendance</h5>
                    <span class="helptext"><a href="javascript:;" onclick="$('#toggler_attendance_1').toggle();">Show/hide dates that this person attended this program.</a></span><br>
        <?php
        //get dates for this project, then attendance for this person
        $get_attendance = "SELECT MONTH(Date), DAY(Date), YEAR(Date) FROM Program_Attendance INNER JOIN Program_Dates ON 
                                                Program_Attendance.Date_ID=Program_Dates.Date_ID
                                                WHERE Program_ID='" . $program['Program_ID'] . "' AND Participant_ID='" .
                $parti['Participant_ID'] . "'";
        // echo $get_attendance;
        $attendance_dates = mysqli_query($cnnTRP, $get_attendance);
        ?><div id="toggler_attendance_1"><?php
                while ($date = mysqli_fetch_row($attendance_dates)) {
                    echo $date[0] . '/' . $date[1] . '/' . $date[2] . "<br>";
                }
                ?>
                    </div><br/>
                    <h5>Demographic Information</h5>
                    <br/><br/>
                    <h5>Academic Information</h5>
        <?php //echo $get_grades; ?>
                    <table class="grades_table">
                        <tr>
                            <th>School Year</th>
                            <th>Qtr.</th>
                            <th>GPA</th>
                            <th>Math Grade</th>
                            <th>Reading Grade</th>
                            <th>ISAT: Total</th>
                            <th>ISAT: Math</th>
                            <th>ISAT: Reading</th>
                            <th></th>
                        </tr>
        <?php
        $get_grades = "SELECT * FROM Academic_Info WHERE 
                                                            Participant_ID='" . $parti['Participant_ID'] . "'  ORDER BY School_Year, Quarter";

        $all_grades = mysqli_query($cnnTRP, $get_grades);
        while ($grades = mysqli_fetch_array($all_grades)) {
            ?>
                            <tr>
                                <td><?php echo $grades['School_Year']; ?></td>
                                <td><?php echo $grades['Quarter']; ?></td>
                                <td><?php echo $grades['GPA']; ?></td>
                                <td><?php echo $grades['Math_Grade']; ?></td>
                                <td><?php echo $grades['Lang_Grade']; ?></td>
                                <td><?php echo $grades['ISAT_Total']; ?></td>
                                <td><?php echo $grades['ISAT_Math']; ?></td>
                                <td colspan="2"><?php echo $grades['ISAT_Lang']; ?></td>
                            </tr>
                                    <?php
                                }
                                ?>
                        <tr>
                            <td><select id="gads_year_new"><option value="">-----</option>
                                    <option>2012-13</option>
                                    <option>2013-14</option>
                                    <option>2014-15</option>
                                    <option>2015-16</option></select></td>
                            <td><input type="text" id="gads_quarter_new" style="width: 20px;" /></td>
                            <td><input type="text" id="gads_gpa_new" style="width: 20px;" /></td>
                            <td><input type="text" id="gads_math_grade_new" style="width: 20px;" /></td>
                            <td><input type="text" id="gads_lang_grade_new" style="width: 20px;" /></td>
                            <td><input type="text" id="gads_isat_new" style="width: 30px;" /></td>
                            <td><input type="text" id="gads_isat_math_new" style="width: 30px;" /></td>
                            <td><input type="text" id="gads_isat_lang_new" style="width: 30px;" /></td>
                            <td><a class="helptext" href="javascript:;" onclick="
                                    $.post(
                                            '../ajax/add_aca_info.php',
                                            {
                                                program: 3,
                                                participant: <?php echo $parti['Participant_ID']; ?>,
                                                year: document.getElementById('gads_year_new').value,
                                                quarter: document.getElementById('gads_quarter_new').value,
                                                gpa: document.getElementById('gads_gpa_new').value,
                                                math: document.getElementById('gads_math_grade_new').value,
                                                lang: document.getElementById('gads_lang_grade_new').value,
                                                isat: document.getElementById('gads_isat_new').value,
                                                isat_math: document.getElementById('gads_isat_math_new').value,
                                                isat_lang: document.getElementById('gads_isat_lang_new').value,
                                                school: 6
                                            },
                                    function(response) {
                                        document.write(response);
                                        //window.location = 'profile.php?id=<?php echo $parti['Participant_ID']; ?>';
                                    }
                                    )
                                   ">Add new...</td>
                        </tr>
                    </table>
                    <br/><br/>
                    <h5>Parent Surveys</h5>
                    <!-- as explained on the survey page, I think the survey is linked to the child's profile,
                    not the parent's.  I don't think most parents will have a profile in the system.-->
        <?php
        $get_surveys = "SELECT Date_Surveyed, Gads_Hill_Parent_Survey_ID FROM Gads_Hill_Parent_Survey WHERE Child_ID=" . $parti['Participant_ID'];
        $all_surveys = mysqli_query($cnnTRP, $get_surveys);
        ?><ul><?php while ($survey = mysqli_fetch_row($all_surveys)) {
            ?>
                            <li><a href="view_parent_survey.php?id=<?php echo $survey[1]; ?>&origin=<?php echo $parti['Participant_ID']; ?>"><?php echo $survey[0] ?></a></li>
                        <?php
                    }
                    ?></ul>
                    <a href="new_gh_parent_survey.php?origin=<?php echo $parti['Participant_ID'] ?>">Add a New Parent Survey</a>
                    <br/><br/>
                    <h5>Developmental Assets Profile</h5>
                    <br/><br/>
                </div>
        <?php
        //Elev8 shows the same information as the middle school to high school transition, so this 
        //option for the if is no longer relevant.
    } else if ($program['Program_ID'] == 4 && ($access == 'a' || $access == 4)) {

        //NMMA Artist in Residency
    } else if ($program['Program_ID'] == 5 && ($access == 'a' || $access == 5)) {
        ?>
                <div class="program_details">
                    <h5>Attendance</h5>
                    <span class="helptext"><a href="javascript:;" onclick="$('#toggler_attendance_1').toggle();">Show/hide dates that this person attended this program.</a></span><br>
                        <?php
                        //get dates for this project, then attendance for this person
                        $get_attendance = "SELECT MONTH(Date), DAY(Date), YEAR(Date) FROM Program_Attendance INNER JOIN Program_Dates ON 
                                                Program_Attendance.Date_ID=Program_Dates.Date_ID
                                                WHERE Program_ID='" . $program['Program_ID'] . "' AND Participant_ID='" .
                                $parti['Participant_ID'] . "'";
                        // echo $get_attendance;
                        $attendance_dates = mysqli_query($cnnTRP, $get_attendance);
                        ?><div id="toggler_attendance_1"><?php
                        while ($date = mysqli_fetch_row($attendance_dates)) {
                            echo $date[0] . '/' . $date[1] . '/' . $date[2] . "<br>";
                        }
                        ?>
                    </div>
                    <br/><br/>
                    <!-- two kinds of nmma surveys.  Links take the user to the view_nmma_survey page. -->
                    <h5>Surveys  <span><a href="nmma_survey.php?participant=<?php echo $parti['Participant_ID']; ?>" class="helptext">--Add a new survey</a></span></h5>
                    <table>
                        <tr>
                            <td colspan="2"><strong>Traditions Surveys</strong></td>
                        </tr>
                        <?php
                        $get_trad_surveys = "SELECT * FROM NMMA_Traditions_Survey WHERE Participant_ID='" . $parti['Participant_ID'] . "' ORDER BY Date";
                        $trad_surveys = mysqli_query($cnnTRP, $get_trad_surveys);
                        while ($trad_survey = mysqli_fetch_array($trad_surveys)) {
                            $date_formatted = explode('-', $trad_survey['Date']);
                            $survey_date = $date_formatted[1] . "/" . $date_formatted[2] . "/" . $date_formatted[0];
                            ?>
                            <tr>
                                <td><a href="view_nmma_survey.php?type=traditions&id=<?php echo $trad_survey['NMMA_Traditions_Survey_ID'] ?>&participant=<?php echo $parti['Participant_ID'] ?>">
            <?php echo $survey_date; ?></a></td>
                                <td><?php echo $trad_survey['Pre_Post']; ?></td>
                            </tr>
            <?php
        }
        ?>
                        <tr>
                            <td colspan="2"><strong>Identity Surveys</strong></td>
                        </tr>
        <?php
        $get_id_surveys = "SELECT * FROM NMMA_Identity_Survey WHERE Participant_ID='" . $parti['Participant_ID'] . "' ORDER BY Date";
        $id_surveys = mysqli_query($cnnTRP, $get_id_surveys);
        while ($id_survey = mysqli_fetch_array($id_surveys)) {
            $date_formatted = explode('-', $id_survey['Date']);
            $survey_date = $date_formatted[1] . "/" . $date_formatted[2] . "/" . $date_formatted[0];
            ?>
                            <tr>
                                <td><a href="view_nmma_survey.php?type=identity&id=<?php echo $id_survey['NMMA_Identity_Survey_ID'] ?>&participant=<?php echo $parti['Participant_ID'] ?>">
                            <?php echo $survey_date; ?></a></td>
                                <td><?php echo $id_survey['Pre_Post']; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <br/><br/>

                    <!-- same academic information that is shown elsewhere.  Changes made here will also show up in
                    other programs for this person. -->
                    <h5>Academic Information</h5>
                    <table class="grades_table">
                        <tr>
                            <th>School Year</th>
                            <th>Qtr.</th>
                            <th>GPA</th>
                            <th>Math Grade</th>
                            <th>Reading Grade</th>
                            <th>ISAT: Total</th>
                            <th>ISAT: Math</th>
                            <th>ISAT: Reading</th>
                            <th></th>
                        </tr>
        <?php
        $get_grades = "SELECT * FROM Academic_Info WHERE Participant_ID='" . $parti['Participant_ID'] . "' AND Program_ID='5' ORDER BY School_Year,Quarter";
        $all_grades = mysqli_query($cnnTRP, $get_grades);
        while ($grades = mysqli_fetch_array($all_grades)) {
            ?>
                            <tr>
                                <td><?php echo $grades['School_Year']; ?></td>
                                <td><?php echo $grades['Quarter']; ?></td>
                                <td><?php echo $grades['GPA']; ?></td>
                                <td><?php echo $grades['Math_Grade']; ?></td>
                                <td><?php echo $grades['Lang_Grade']; ?></td>
                                <td><?php echo $grades['ISAT_Total']; ?></td>
                                <td><?php echo $grades['ISAT_Math']; ?></td>
                                <td colspan="2"><?php echo $grades['ISAT_Reading']; ?></td>
                            </tr>
                                    <?php
                                }
                                ?>
                        <tr>
                            <td><select id="art_year_new">
                                    <option value="">-----</option>
                                    <option>2012-13</option>
                                    <option>2013-14</option>
                                    <option>2014-15</option>
                                    <option>2015-16</option>
                                </select></td>
                            <td><input type="text" id="art_quarter_new" style="width: 20px;" /></td>
                            <td><input type="text" id="art_gpa_new" style="width: 20px;" /></td>
                            <td><input type="text" id="art_math_grade_new" style="width: 20px;" /></td>
                            <td><input type="text" id="art_lang_grade_new" style="width: 20px;" /></td>
                            <td><input type="text" id="art_isat_new" style="width: 30px;" /></td>
                            <td><input type="text" id="art_isat_math_new" style="width: 30px;" /></td>
                            <td><input type="text" id="art_isat_lang_new" style="width: 30px;" /></td>
                            <td><a class="helptext" href="javascript:;" onclick="
                                    $.post(
                                            '../ajax/add_aca_info.php',
                                            {
                                                program: 5,
                                                participant: <?php echo $parti['Participant_ID']; ?>,
                                                year: document.getElementById('art_year_new').value,
                                                quarter: document.getElementById('art_quarter_new').value,
                                                gpa: document.getElementById('art_gpa_new').value,
                                                math: document.getElementById('art_math_grade_new').value,
                                                lang: document.getElementById('art_lang_grade_new').value,
                                                isat: document.getElementById('art_isat_new').value,
                                                isat_math: document.getElementById('art_isat_math_new').value,
                                                isat_lang: document.getElementById('art_isat_lang_new').value,
                                                school: 6
                                            },
                                    function(response) {
                                        // document.write(response);
                                        window.location = 'profile.php?id=<?php echo $parti['Participant_ID']; ?>';
                                    }
                                    )
                                   ">Add new...</td>
                        </tr>
                    </table>
                    <br/><br/>
                </div>
                                <?php
                            }
            else if ($program['Program_ID'] == 6 && ($access == 'a' || $access == 6)) {

function la_casa_display_data_gen_html($result_row_item, $class_string, $array_of_options){
    $result = "<span class = " . $class_string . ">";
    if (array_key_exists( $result_row_item, $array_of_options)){
        $result .= $array_of_options[$result_row_item];
    }
    else{
        $result .= $result_row_item;
    }
    $result .= "</span>";
    return $result;
}

function la_casa_edit_data_gen_html($array_of_options, $existing_value, $id_string, $class_string){
    $result = "<select id = " . $id_string . " class = " . $class_string . "><option value = 0>----</option>";
    foreach ($array_of_options as $val => $display){
        $result .= "<option value = " . $val . " " . ($existing_value == $val ? 'selected="selected"' : null) . ">" . $display . " </option>";
}
    $result .= "</select>";
    return $result;
}
               ?> 
            <div class="program_details">
            <h4>La Casa Information</h4>
<table class="inner_table">
<caption>Constant Data</caption>
<?php
$column_array = array("Household Size", "Parent1 AGI", "Parent2 AGI", "Student AGI", "ACT Score", "High School GPA", "Dependency Status", "Father's Highest Level of Education", "Mother's Highest Level of Education", "Student's Aspiration", "First Generation College Student?", "Hometown", "High School");
$find_constant_la_casa_sqlsafe = "SELECT Household_Size, Parent1_AGI, Parent2_AGI, Student_AGI, ACT_Score, High_School_GPA, Dependency_Status, Father_Highest_Level_Education, Mother_Highest_Level_Education, Student_Aspiration, First_Generation_College_Student, Student_Hometown, Student_High_School FROM La_Casa_Basics WHERE Participant_ID_Students = '" . mysqli_real_escape_string($cnnTRP, $parti['Participant_ID']) . "'";
$constant_data=mysqli_query($cnnTRP, $find_constant_la_casa_sqlsafe);
$constant=mysqli_fetch_row($constant_data);
$editable_class = "constant_data_editable_" . $constant[5];
$display_class = "constant_data_display_" . $constant[5];
foreach ($column_array as $key => $value){
?>
                    <tr>
                    <td><strong>
<?php echo $value; ?>
                    </strong></td>
                    <td>
<?php echo $constant[$key]; ?>
                    </td>
                    </tr>
<?php
}
?>
</table>
            <table class="inner_table">
                <caption>College Data</caption>
                <tr><th>Year</th><th>School Name</th><th>Term Type (Semester or Quarter)</th>
                    <th>Term (Fall, Winter, Spring, Summer)</th>
                    <th>Credits Earned</th><th>Major</th><th>GPA</th><th>Match Level</th>
                </tr>
                <?php

                include "../include/dbconnopen.php";
                $find_college_data_sqlsafe="SELECT College_Name, Term_Type, Term, School_Year, Credits, Student_ID, Major, College_GPA, College_Match FROM La_Casa_Basics LEFT JOIN Colleges ON La_Casa_Basics.College_ID=Colleges.College_ID WHERE Participant_ID_Students = '" . mysqli_real_escape_string($cnnTRP, $parti['Participant_ID']) . "'";


                $college_data=mysqli_query($cnnTRP, $find_college_data_sqlsafe);
                $total_credits = 0;
                while ($coldat=mysqli_fetch_row($college_data)){
$editable_class = "college_data_editable_" . $coldat[5];
$display_class = "college_data_display_" . $coldat[5];
                    $total_credits += $coldat[4];
                    ?>
                    <tr>
                    <td>
<?php
$college_year_array = array(2014 => '2014', 2015 => '2015', 2016 => '2016', 2017 => '2017');
echo la_casa_display_data_gen_html($coldat[3], $display_class, $college_year_array);
echo la_casa_edit_data_gen_html($college_year_array, $coldat[3], "college_year_id", $editable_class);
?>
                    </td>
                    <td>
<?php
$get_college_list_sqlsafe = "SELECT * FROM Colleges";
$college_list = mysqli_query($cnnTRP, $get_college_list_sqlsafe);
$college_array = array();
while ($college = mysqli_fetch_row($college_list)){
    $college_array[$college[0]] = $college[1];
}
echo la_casa_display_data_gen_html($coldat[0], $display_class, $college_array);
echo la_casa_edit_data_gen_html($college_array, $coldat[0], "college_id", $editable_class);
?>
                    </td>
                    <td>
<?php
$term_array = array(1 => 'Semester', 2 => 'Quarter');
echo la_casa_display_data_gen_html($coldat[1], $display_class, $term_array);
echo la_casa_edit_data_gen_html($term_array, $coldat[1], "term_type", $editable_class);
?>
                    </td>
                    <td>
<?php
$season_array = array(1 => 'Fall', 2 => 'Winter', 3 => 'Spring', 4 => 'Summer');
echo la_casa_display_data_gen_html($coldat[2], $display_class, $season_array);
echo la_casa_edit_data_gen_html($season_array, $coldat[2], "term_id", $editable_class);
?>
                    </td>
                    <td>
<?php
echo la_casa_display_data_gen_html($coldat[4], $display_class);
?>
<input id = "credits" class = "<?php echo $editable_class; ?>" value = "<?php echo $coldat[4]; ?>">
                    </td>
                    <td>
<?php
$get_major_list_sqlsafe = "SELECT DISTINCT Major FROM La_Casa_Basics";
$major_list = mysqli_query($cnnTRP, $get_major_list_sqlsafe);
$major_array = array();
while ($major = mysqli_fetch_row($major_list)){
    $major_array[] = $major[0];
}
echo la_casa_display_data_gen_html($coldat[6], $display_class, $major_array);
echo la_casa_edit_data_gen_html($major_array, $coldat[6], "major_id", $editable_class);
?>
                    </td>
                    <td>
<?php
echo la_casa_display_data_gen_html($coldat[7], $display_class);
?>
<input id = "credits" class = "<?php echo $editable_class; ?>" value = "<?php echo $coldat[7]; ?>">
                    </td>
                    <td>
<?php
$match_array = array(1 => 'Above Match', 2 => 'Match', 3 => 'Below Match');
echo la_casa_display_data_gen_html($coldat[8], $display_class, $match_array);
echo la_casa_edit_data_gen_html($match_array, $coldat[8], "match_id", $editable_class);
?>
                    </td>
                    <td>
<input type = "button" value = "Edit" onclick = "$('.<?php echo $display_class ?>').toggle();
$('.<?php echo $editable_class; ?>').toggle();">
                    <input type = "button" class = "<?php echo $editable_class; ?>" value = "Save"
                    onclick = "
                    $.post(
                        '../ajax/save_la_casa_info.php',
                        {
                          action: 'edit',
                                subject: 'college',
                                college_id: document.getElementById('college_id').value,
                                term_type: document.getElementById('term_type').value,
                                term_id: document.getElementById('term_id').value,
                                school_year: document.getElementById('college_year_id').value,
                                credits: document.getElementById('credits').value,
                                id: '<?php echo $coldat[5]; ?>' 
                                }, 
                function(response) {
                    document.write(response);
                }
                        );">
                    </td>
                </tr>
                        <?php
                }
                ?>
                <tr>
                    <td>
<?php
echo la_casa_edit_data_gen_html($college_year_array, 0, "new_college_year_id", $editable_class);
?>
                    </td>
                    <td>
<?php
echo la_casa_edit_data_gen_html($college_array, 0, "new_college_id", $editable_class);
?>
                    </td>
                    <td>
<?php
echo la_casa_edit_data_gen_html($term_array, 0, "new_term_type", $editable_class);
?>
                    </td>
                    <td>
<?php
echo la_casa_edit_data_gen_html($season_array, 0, "new_term_id", $editable_class);
?>
                    </td>
                    <td>
<input type="text" id="new_credits">
                    </td>
                    <td>
<input type="button" value="Add New" onclick=" 
$.post(
    '../ajax/save_la_casa_info.php',
    {
      action: 'new',
            subject: 'college',
            college_id: document.getElementById('new_college_id').value,
            term_type: document.getElementById('new_term_type').value,
            term_id: document.getElementById('new_term_id').value,
            school_year: document.getElementById('new_college_year_id').value,
            credits: document.getElementById('new_credits').value,
            person: '<?php echo $parti['Participant_ID']; ?>'
            }, 
    function(response) {
        document.write(response);
    }
);">
                    </td>
                </tr>
                <tr>
                     <td colspan="4">Total Credits earned: </td>
                     <td><?php echo $total_credits; ?></td>
                </tr>
            </table>
    
            <p></p>

            <table class="inner_table">
                <caption>Loans Table</caption>
                <tr>
                    <th>Year</th>
                    <th>Number of Loan Applications</th>
                    <th>Application Volume ($)</th>
                    <th>Loans Received Volume ($)</th>
                </tr>
<?php
$find_loan_data_sqlsafe = "SELECT School_Year, Loan_Applications, Loan_Volume, Loans_Received, Student_ID FROM La_Casa_Basics WHERE Participant_ID_Students = '" . mysqli_real_escape_string($cnnTRP, $parti['Participant_ID']) . "'";
                $loan_data=mysqli_query($cnnTRP, $find_loan_data_sqlsafe);
                $total_loans = 0;

                while ($loandata=mysqli_fetch_row($loan_data)){
                    $total_loans += $loandata[3];
                    ?>
                <tr><td><span class = "hide_loans_data_<?php echo $loandata[4]; ?>"><?php echo $loandata[0]; ?></span>
 <select id = "loans_year_id" class = "hide_college_data show_loans_edit_<?php echo $loandata[4]; ?>">
                              <option value = "0">-----</option>
                              <option value = "2014"> 2014 </option>
                              <option value = "2015"> 2015 </option>
                              <option value = "2016"> 2016 </option>
                              <option value = "2017"> 2017 </option>
                              </select></td>
                    <td><span class = "hide_loans_data_<?php echo $loandata[4]; ?>"><?php echo $loandata[1]; ?></span>
<input type="text" class = "hide_college_data show_loans_edit_<?php echo $loandata[4]; ?>" id="loan_apps"></td>
                    <td><span class = "hide_loans_data_<?php echo $loandata[4]; ?>"><?php echo $loandata[2]; ?></span>
<input type="text" class = "hide_college_data show_loans_edit_<?php echo $loandata[4]; ?>" id="loan_volume"></td>
                    <td><span class = "hide_loans_data_<?php echo $loandata[4]; ?>"><?php echo $loandata[3]; ?></span>
<input type="text" class = "hide_college_data show_loans_edit_<?php echo $loandata[4]; ?>" id="loans_received"></td>
                    <td><input type = "button" value = "Edit" onclick = "$('.show_loans_edit_<?php echo $loandata[4]; ?>').toggle();
$('.hide_loans_data_<?php echo $loandata[4]; ?>').toggle();">
                    <input type = "button" class = "hide_college_data show_loans_edit_<?php echo $loandata[4]; ?>" value = "Save"
                    onclick = "
                                                                                                   alert('testing');
                    $.post(
                        '../ajax/save_la_casa_info.php',
                        {
                          action: 'edit',
                                subject: 'loans',
                                school_year: document.getElementById('loans_year_id').value,
                                loan_apps: document.getElementById('loan_apps').value,
                                loan_volume: document.getElementById('loan_volume').value,
                                loans_received: document.getElementById('loans_received').value,
                                id: '<?php echo $loandata[4]; ?>' 
                                }, 
                function(response) {
                    document.write(response);
                }
                        );">
                    </td>

                </tr>
                        <?php
                }
?>
                <tr><td>
 <select id = "new_school_year" class = "show_college_edit_<?php echo $loandata[4]; ?>">
                              <option value = "0">-----</option>
                              <option value = "2014"> 2014 </option>
                              <option value = "2015"> 2015 </option>
                              <option value = "2016"> 2016 </option>
                              <option value = "2017"> 2017 </option>
                              </select></td>
                    <td>
<input type="text" class = "show_loans_edit_<?php echo $loandata[4]; ?>" id="new_loan_apps"></td>
                    <td>
<input type="text" class = "show_loans_edit_<?php echo $loandata[4]; ?>" id="new_loan_volume"></td>
                    <td>
<input type="text" class = "show_loans_edit_<?php echo $loandata[4]; ?>" id="new_loans_received"></td>
                 <td>   <input type = "button" value  = "Add New"
                    onclick = "
                    $.post(
                        '../ajax/save_la_casa_info.php',
                        {
                          action: 'new',
                                subject: 'college',
                                school_year: document.getElementById('new_school_year').value,
                                loan_apps: document.getElementById('new_loan_apps').value,
                                loan_volume: document.getElementById('new_loan_volume').value,
                                loans_received: document.getElementById('new_loans_received').value,
                                person: '<?php echo $parti['Participant_ID']; ?>'
                                }, 
                function(response) {
                    document.write(response);
                }
                        );">
                    </td>

                </tr>
                <tr>
                     <td colspan="3">Total Loans received: </td>
                     <td><?php echo $total_loans; ?></td>
                </tr>

</table>


            </div>
            <?php 
            }
                            ?>
                            <?php
                        }
                        ?>

        <!-- and a dropdown menu of all programs that this participant might join. -->
        <br/><strong>Add to a new program:</strong>
        <select id="all_programs_add" class="no_view">
            <option value="">-----</option>
<?php
$get_programs = "SELECT * FROM Programs";
include "../include/dbconnopen.php";
$programs = mysqli_query($cnnTRP, $get_programs);
while ($prog = mysqli_fetch_row($programs)) {
    ?>
    <option value="<?php echo $prog[0]; ?>"><?php echo $prog[1]; ?></option>
    <?php
}
include "../include/dbconnopen.php";
?>
        </select><input type="button" value="Add to Program" class="no_view" onclick="
                    $.post(
                        '../ajax/add_participant_to_program.php',
                        {
                            program_id: document.getElementById('all_programs_add').value,
                            participant: '<?php echo $parti['Participant_ID']; ?>'
                            }, 
                function(response) {
                    window.location = 'profile.php?id=<?php echo $parti['Participant_ID']; ?>';
                }
                )">
    </td>
</tr>

</table>

<br/><br/>
</div>
<?php
//include "new_gh_parent_survey.php";
//include "../include/dbconnclose.php";
include "../../footer.php";
?>
