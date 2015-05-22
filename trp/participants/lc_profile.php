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

user_enforce_has_access($TRP_id);

?>
<?php
include "../../header.php";
include "../header.php";
/* La Casa information about a given participant */

include "../classes/lc_participant.php";
include "construction_functions.php";


$participant = new Participant();
$participant->get_basic_info($_GET['id']);



?>
<head>
<script type="text/javascript">
    function saveTerms(row){
        alert(row);
        $.post(
            '../ajax/save_la_casa_info.php',
    {
        
    }
        )
    }
    $(document).ready(function() {
        $('.basic_info_edit').hide();
        $('.edit_term').hide();

    });
</script>
</head>
<body>

    <h3>Participant Profile - <?php echo $participant->first_name . " " . $participant->last_name; ?>
    </h3><hr/><br/>

        <table class="profile_table">
    <tr>
    <td width="40%">
    <table class="inner_table" style="border: 2px solid #696969;">
    <tr>
    <td><strong>Database ID: </strong></td>
    <td><?php echo $participant->participant_id; ?></td>
</tr>
<tr>
<td><strong>Name: </strong></td>
<td>
<span class="basic_info_show"><?php echo $participant->first_name . " " . $participant->last_name; ?></span>
<input class="basic_info_edit" id="first_name_edit" value="<?php echo $participant->first_name; ?>" style="width:100px;"/>&nbsp;
<input class="basic_info_edit" id="last_name_edit" value="<?php echo $participant->last_name; ?>" style="width:100px;"/>
    </td>
    </tr>
    <tr>
    <td><strong>Address: </strong></td>
    <td>
    <span class="basic_info_show"><?php echo $participant->address_street_num . " " . $participant->address_street_direction . " " . $participant->address_street_name . " " . $participant->address_street_type . "<br/>" . $participant->address_city . ", " . $participant->address_state . " " . $participant->address_zipcode; ?></span>
<div class="basic_info_edit">
    <input id="st_num_edit" value="<?php echo $participant->address_street_num; ?>" style="width:40px;"/> <input id="st_dir_edit" value="<?php echo $participant->address_street_direction; ?>" style="width:20px;"/> <input id="st_name_edit" value="<?php echo $participant->address_street_name; ?>" style="width:100px;"/> <input id="st_type_edit" value="<?php echo $participant->address_street_type; ?>" style="width:35px;"/> <br/>
    <input id="city_edit" value="<?php echo $participant->address_city; ?>" style="width:100px;"/> <input id="state_edit" value="<?php echo $participant->address_state; ?>" style="width:20px;"/> <input id="zip_edit" value="<?php echo $participant->address_zipcode; ?>" style="width:40px;"/> <br/>
    <span class="helptext">e.g. 1818 S Paulina St<br/>Chicago, IL 60608</span>
    </div>
    </td>
    </tr>
    <tr>
    <td><strong>Phone Number: </strong></td>
    <td>
    <span class="basic_info_show"><?php echo $participant->phone; ?></span>
<input class="basic_info_edit" id="phone_edit" value="<?php echo $participant->phone; ?>"/>
    </td>
    </tr>
    <tr>
    <td><strong>E-mail Address: </strong></td>
    <td>
    <span class="basic_info_show"><?php echo $participant->email; ?></span>
<input class="basic_info_edit" id="email_edit" value="<?php echo $participant->email; ?>"/>
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
if ($participant->gender == 'm') {
    echo "Male";
} else if ($participant->gender == 'f') {
    echo "Female";
};
?></span>
                            <select class="basic_info_edit" id="gender_edit"/>
                    <option value="">-------</option>
                    <option value="m" <?php echo($participant->gender == 'm' ? 'selected="selected"' : null); ?>>Male</option>
                    <option value="f" <?php echo($participant->gender == 'f' ? 'selected="selected"' : null); ?>>Female</option>
                    </select>
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
                                    id: '<?php echo $participant->participant_id; ?>',
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
                                    gender: document.getElementById('gender_edit').value
                                },
                        function(response) {
                            window.location = 'lc_profile.php?id=<?php echo $participant->participant_id ?>';
                        }
                        )" style="margin-left:55px;">Save!</a>

        </tr>
    </table>
    </td>
    <td width="10%">
    </td>
    <td width="40%">
    <?php
    $contact_array = $participant->get_emergency_contacts($_GET['id']);
    ?>
    <table class="inner_table" style="border: 2px solid #696969;">
    <tr>
    <th><strong>Name </strong></th>
    <th><strong>Phone </strong></th>
    <th><strong>Relationship </strong></th>
    </tr>
    <?php
         foreach ($contact_array as $contact) {
             $display_class = "display_term_" . $term_array['term_id'];
             $this_row_class =  $contact['contact_id'];
             $edit_class = "edit_term " . $this_row_class;
    ?>
    <tr>
        <td><?php echo $contact['first_name'] . " " . $contact['last_name']; 
            echo la_casa_edit_data_gen_input($contact['first_name'], 'ec_first_name_edit_'. $contact['contact_id'], $edit_class);
            echo la_casa_edit_data_gen_input($contact['last_name'], 'ec_last_name_edit_'. $contact['contact_id'], $edit_class);
        ?>
        </td>
        <td><?php echo $contact['phone'];
            echo la_casa_edit_data_gen_input($contact['phone'], 'ec_phone_edit_' . $contact['contact_id'], $edit_class);
        ?> </td>
        <td><?php echo $contact['relationship']; 
            echo la_casa_edit_data_gen_input($contact['relationship'], 'ec_relationship_edit_' . $contact['contact_id'], $edit_class);
        ?> </td>
        <td> 
        <input type="button" value="Edit"
            onclick="$('.edit_term.<?php echo $this_row_class; ?>').toggle();">
        <input type="button" value="Save" class="<?php echo $edit_class; ?>"
             onclick="
                $.post(
                '../ajax/save_la_casa_info.php',
                {
                    subject: 'emergency',
                    action: 'edit',
                    first_name: document.getElementById('ec_first_name_edit_<?php echo $contact['contact_id']; ?>').value,
                    last_name: document.getElementById('ec_last_name_edit_<?php echo $contact['contact_id']; ?>').value,
                    phone: document.getElementById('ec_phone_edit_<?php echo $contact['contact_id']; ?>').value,
                    relationship: document.getElementById('ec_relationship_edit_<?php echo $contact['contact_id']; ?>').value,
                    contact_id: '<?php echo $contact['contact_id']; ?>'
                },
                function (response){
                    window.location = 'lc_profile.php?id=<?php echo $participant->participant_id; ?>';
                });">
        </td>
    </tr>
<?php             
         }
?>
    </table>    
    </td>
    </tr>

<tr>
<td colspan = 3>
    <table class="inner_table" style="border: 2px solid #696969;">
    <tr>
        <th>College Name</th>
        <th>School Year/<br /> Term Type/ <br /> Term</th>
        <th>Credits</th>
        <th>Major/ <br/ > Minor</th>
        <th>Expected Match/ <br /> Actual Match</th>
        <th>College GPA</th>
        <th>Internship Status/ <br /> Intern Hours</th>
        <th>Dropped Classes/ <br /> Dropped Credits</th>
        <th></th>
    </tr>
    <?php 
    $college_info_array = $participant->get_college_info($participant->participant_id);

    foreach ($college_info_array as $term_array){
        $display_class = "display_term_" . $term_array['term_id'];
        $this_row_class =  $term_array['term_id'];
        $edit_class = "edit_term " . $this_row_class;
        ?>
        <tr>
        <td><span class="<?php echo $display_class; ?>"><?php echo $term_array['college_name']; ?></span>
        <span class="<?php echo edit_class; ?>">
        <?php echo la_casa_edit_data_gen_selector($college_array, $term_array['college_id'], 'edit_college_' . $term_array['term_id'], $edit_class); ?>
        </span>
        </td>
        <td><span class="<?php echo $display_class; ?>"><?php echo $term_array['school_year'] . "/ " . $term_array['term_type'] . "/ " . $term_array['term']; ?></span>
        <span class="<?php echo $edit_class;?>">
        <?php
        echo la_casa_edit_data_gen_selector($school_year_array, 
            $term_array['school_year'], 
            'edit_year_' . $term_array['term_id'], 
            $this_row_class); 
        echo la_casa_edit_data_gen_selector($term_type_array,
            $term_array['term_type'],
            'edit_term_' . $term_array['term_id'],
            $this_row_class); 
        echo la_casa_edit_data_gen_selector($season_array,
            $term_array['term'],
            'edit_season_' . $term_array['term_id'],
            $this_row_class); 
        ?>
        </span>
        </td>
        <td><span class="<?php echo $display_class; ?>"><?php echo $term_array['credits']; ?></span>
        <span class="<?php echo $edit_class;?>">
        <?php
        echo la_casa_edit_data_gen_input($term_array['credits'],
            'edit_credits_' . $term_array['term_id'],
            $this_row_class); 
        ?>
        </span>

        </td>
        <td><span class="<?php echo $display_class; ?>"><?php echo $term_array['major'] . "/ " .  $term_array['minor']; ?></span>
        <span class="<?php echo $edit_class;?>">
        <?php
        echo la_casa_edit_data_gen_selector($major_array,
            $term_array['major'],
            'edit_major_' . $term_array['term_id'],
            $this_row_class); 
        echo la_casa_edit_data_gen_selector($minor_array,
            $term_array['minor'],
            'edit_minor_' . $term_array['term_id'],
            $this_row_class); 
        ?>
        </span>
        </td>
        <td><span class="<?php echo $display_class; ?>"><?php echo $term_array['expected_match'] . "/ " . $term_array['actual_match']; ?></span></td>
        <td><span class="<?php echo $display_class; ?>"><?php echo $term_array['college_gpa']; ?></span>
        <span class="<?php echo $edit_class;?>">
        <?php
        echo la_casa_edit_data_gen_input($term_array['college_gpa'],
            'edit_gpa_' . $term_array['term_id'],
            $this_row_class); 
        ?>
        </span>
        </td>
        <td><span class="<?php echo $display_class; ?>"><?php echo $term_array['internship_status'] . "/ " . $term_array['intern_hours']; ?></span>
        <span class="<?php echo $edit_class;?>">
        <?php
        echo la_casa_edit_data_gen_selector($yn_array,
            $term_array['internship_status'],
            'edit_internship_' . $term_array['term_id'],
            $this_row_class); 
        echo "<br />";
        echo la_casa_edit_data_gen_input($term_array['intern_hours'],
            'edit_intern_hours_' . $term_array['term_id'],
            $this_row_class); 
        ?>
        </span>
        </td>
        <td><span class="<?php echo $display_class; ?>"><?php echo $term_array['dropped_classes'] . "/ " . $term_array['dropped_credits']; ?></span>
        <span class="<?php echo $edit_class;?>">
        <?php
        echo la_casa_edit_data_gen_selector($yn_array,
            $term_array['dropped_classes'],
            'edit_dropped_' . $term_array['term_id'],
            $this_row_class); 
        echo "<br />";
        echo la_casa_edit_data_gen_input($term_array['dropped_credits'],
            'edit_dropped_credits_' . $term_array['term_id'],
            $this_row_class); 
        ?>
        </span>
        </td>
        <td>
        <input type="button" value="Edit" id="edit_term_<?php echo $term_array['term_id']; ?>"
            onclick="$('.edit_term.<?php echo $this_row_class; ?>').toggle();">
        <input type="button" value="Save" class="<?php echo $edit_class;?>"
            onclick="
                $.post(
                    '../ajax/save_la_casa_info.php',
                {
                    action: 'edit',
                    subject: 'college',
                    id: '<?php echo $term_array['term_id']; ?>',
                    college_id: document.getElementById('edit_college_<?php echo $term_array['term_id']; ?>').value,
                    term_type: document.getElementById('edit_season_<?php echo $term_array['term_id']; ?>').value,
                    term: document.getElementById('edit_term_<?php echo $term_array['term_id']; ?>').value,
                    school_year: document.getElementById('edit_year_<?php echo $term_array['term_id']; ?>').value,
                    credits: document.getElementById('edit_credits_<?php echo $term_array['term_id']; ?>').value,
                    major: document.getElementById('edit_major_<?php echo $term_array['term_id']; ?>').value,
                    minor: document.getElementById('edit_minor_<?php echo $term_array['term_id']; ?>').value,
                    expected_match: '',
                    actual_match: '',
                    gpa: document.getElementById('edit_gpa_<?php echo $term_array['term_id']; ?>').value,
                    internship_status: document.getElementById('edit_internship_<?php echo $term_array['term_id']; ?>').value,
                    intern_hours: document.getElementById('edit_intern_hours_<?php echo $term_array['term_id']; ?>').value,
                    dropped_classes: document.getElementById('edit_dropped_<?php echo $term_array['term_id']; ?>').value,
                    dropped_credits: document.getElementById('edit_dropped_credits_<?php echo $term_array['term_id']; ?>').value

                },
                function (response) {
                    window.location = 'lc_profile.php?id=<?php echo $participant->participant_id; ?>';
                }
                )
            ">

        </td>
        </tr>
        <?php
    }
        $edit_class = "edit_term new";
    ?>

    <tr>
            <td>
        <span class="<?php echo $edit_class;?>">
        <?php
        echo la_casa_edit_data_gen_selector($college_array, 
            $term_array['college_id'], 
            'edit_college_new', 
            $this_row_class); 
        ?>
        </span>
        </td>
        <td>
        <span class="<?php echo $edit_class;?>">
        <?php
        echo la_casa_edit_data_gen_selector($school_year_array, 
            $term_array['school_year'], 
            'edit_year_new', 
            $this_row_class); 
        echo la_casa_edit_data_gen_selector($term_type_array,
            $term_array['term_type'],
            'edit_term_new',
            $this_row_class); 
        echo la_casa_edit_data_gen_selector($season_array,
            $term_array['term'],
            'edit_season_new',
            $this_row_class); 
        ?>
        </span>
        </td>
        <td>
        <span class="<?php echo $edit_class;?>">
        <?php
        echo la_casa_edit_data_gen_input($term_array['credits'],
            'edit_credits_new',
            $this_row_class); 
        ?>
        </span>

        </td>
        <td>
        <span class="<?php echo $edit_class;?>">
        <?php
        echo la_casa_edit_data_gen_selector($major_array,
            $term_array['major'],
            'edit_major_new',
            $this_row_class); 
        echo la_casa_edit_data_gen_selector($minor_array,
            $term_array['minor'],
            'edit_minor_new',
            $this_row_class); 
        ?>
        </span>
        </td>
        <td>
        <td>
        <span class="<?php echo $edit_class;?>">
        <?php
        echo la_casa_edit_data_gen_input($term_array['college_gpa'],
            'edit_gpa_new',
            $this_row_class); 
        ?>
        </span>
        </td>
        <td>
        <span class="<?php echo $edit_class;?>">
        <?php
        echo la_casa_edit_data_gen_selector($yn_array,
            $term_array['internship_status'],
            'edit_internship_new',
            $this_row_class); 
        echo "<br />";
        echo la_casa_edit_data_gen_input($term_array['intern_hours'],
            'edit_intern_hours_new',
            $this_row_class); 
        ?>
        </span>
        </td>
        <td>
        <span class="<?php echo $edit_class;?>">
        <?php
        echo la_casa_edit_data_gen_selector($yn_array,
            $term_array['dropped_classes'],
            'edit_dropped_new',
            $this_row_class); 
        echo "<br />";
        echo la_casa_edit_data_gen_input($term_array['dropped_credits'],
            'edit_dropped_credits_new',
            $this_row_class); 
        ?>
        </span>
        </td>
        <td>
        <input type="button" value="Add New" id="edit_term_new"
            onclick="$('.edit_term.new').toggle();">
        <input type="button" value="Save" class="<?php echo $edit_class;?>"
            onclick="
                $.post(
                    '../ajax/save_la_casa_info.php',
                {
                    action: 'new',
                    subject: 'college',
                    id: '<?php echo $participant->participant_id; ?>',
                    college_id: document.getElementById('edit_college_new').value,
                    term_type: document.getElementById('edit_season_new').value,
                    term_id: document.getElementById('edit_term_new').value,
                    school_year: document.getElementById('edit_year_new').value,
                    credits: document.getElementById('edit_credits_new').value,
                    major: document.getElementById('edit_major_new').value,
                    minor: document.getElementById('edit_minor_new').value,
                    expected_match: '',
                    actual_match: '',
                    gpa: document.getElementById('edit_gpa_new').value,
                    internship_status: document.getElementById('edit_internship_new').value,
                    intern_hours: document.getElementById('edit_intern_hours_new').value,
                    dropped_classes: document.getElementById('edit_dropped_new').value,
                    dropped_credits: document.getElementById('edit_dropped_credits_new').value

                },
                function (response) {
document.write(response); //testing
//                    window.location = 'lc_profile.php?id=<?php echo $participant->participant_id; ?>';
                }
                )
            ">

        </td>
    </tr>
    </table>

</td>
</tr>
    <tr>
    <td width="50%">
    <table class="inner_table" style="border: 2px solid #696969;">
    <?php
    $participant->get_constant_info($participant->participant_id);
    ?>
    <tr>
    <td><strong>Cohort </strong></td>
    <td> <?php echo $participant->cohort; 
    echo la_casa_edit_data_gen_selector($cohort_array, $participant->cohort, 'edit_cohort', 'edit_term constant');
    ?>
    </td>
    <tr>
    <td><strong>Status </strong></td>
    <td> <?php echo $participant->status;
echo la_casa_edit_data_gen_selector($status_array, $participant->status, 'status_edit', 'edit_term constant'); ?> </td>
    </tr>
    <tr>
    <td><strong>Handbook </strong></td>
    <td> <?php echo  $participant->handbook;
    echo la_casa_edit_data_gen_input($participant->handbook, 'handbook_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Floor </strong></td>
    <td> <?php echo  $participant->floor;
    echo la_casa_edit_data_gen_input($participant->floor, 'floor_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Pod </strong></td>
    <td> <?php echo  $participant->pod;
    echo la_casa_edit_data_gen_input($participant->pod, 'pod_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Room Number </strong></td>
    <td> <?php echo  $participant->room_number;
    echo la_casa_edit_data_gen_input($participant->room_number, 'room_number_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Key Card Number </strong></td>
    <td> <?php echo  $participant->key_card_number;
    echo la_casa_edit_data_gen_input($participant->key_card_number, 'key_card_number_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Transcript Submitted </strong></td>
    <td> <?php echo  $participant->transcript_submitted;
echo la_casa_edit_data_gen_selector($yn_array, $participant->transcript_submitted, 'transcript_submitted_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Service Hours Submitted </strong></td>
    <td> <?php echo  $participant->service_hours_submitted;
echo la_casa_edit_data_gen_selector($yn_array, $participant->service_hours_submitted, 'service_hours_submitted_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Lcrc Username </strong></td>
    <td> <?php echo  $participant->lcrc_username;
    echo la_casa_edit_data_gen_input($participant->lcrc_username, 'lcrc_username_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Lcrc Password </strong></td>
    <td> <?php echo  $participant->lcrc_password;
    echo la_casa_edit_data_gen_input($participant->lcrc_password, 'lcrc_password_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Lcrc Print Code </strong></td>
    <td> <?php echo  $participant->lcrc_print_code;
    echo la_casa_edit_data_gen_input($participant->lcrc_print_code, 'lcrc_print_code_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Roommate </strong></td>
    <td> <?php echo  $participant->roommate; ?></td>
    </tr>
    <tr>
    <td><strong>Application Received </strong></td>
    <td> <?php echo  $participant->application_received;
echo la_casa_edit_data_gen_input($participant->application_received, 'application_received_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Application Completed </strong></td>
    <td> <?php echo  $participant->application_completed;
echo la_casa_edit_data_gen_input($participant->application_completed, 'application_completed_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Household Size </strong></td>
    <td> <?php echo  $participant->household_size;
    echo la_casa_edit_data_gen_input($participant->household_size, 'household_size_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Parent1 AGI </strong></td>
    <td> <?php echo  $participant->parent1_agi;
    echo la_casa_edit_data_gen_input($participant->parent1_agi, 'parent1_agi_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Parent2 AGI </strong></td>
    <td> <?php echo  $participant->parent2_agi;
    echo la_casa_edit_data_gen_input($participant->parent2_agi, 'parent2_agi_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Student AGI </strong></td>
    <td> <?php echo  $participant->student_agi;
    echo la_casa_edit_data_gen_input($participant->student_agi, 'student_agi_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Act Score </strong></td>
    <td> <?php echo  $participant->act_score;
    echo la_casa_edit_data_gen_input($participant->act_score, 'act_score_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>High School GPA </strong></td>
    <td> <?php echo  $participant->high_school_gpa;
    echo la_casa_edit_data_gen_input($participant->high_school_gpa, 'high_school_gpa_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Dependency Status </strong></td>
    <td> <?php echo  $participant->dependency_status;
echo la_casa_edit_data_gen_selector($yn_status, $participant->dependency_status, 'dependency_status_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>HS GPA Weighted </strong></td>
    <td> <?php echo  $participant->hs_gpa_weighted;
    echo la_casa_edit_data_gen_input($participant->hs_gpa_weighted, 'hs_gpa_weighted_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Expected Graduation Year </strong></td>
    <td> <?php echo  $participant->expected_graduation_year;
    echo la_casa_edit_data_gen_input($participant->expected_graduation_year, 'expected_graduation_year_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>College Grade Level </strong></td>
    <td> <?php echo  $participant->college_grade_level;
echo la_casa_edit_data_gen_selector($grade_array, $participant->college_grade_level, 'college_grade_level_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Reason Leave </strong></td>
    <td> <?php echo  $participant->reason_leave;
    echo la_casa_edit_data_gen_input($participant->reason_leave, 'reason_leave_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Reason Stay </strong></td>
    <td> <?php echo  $participant->reason_stay;
    echo la_casa_edit_data_gen_input($participant->reason_stay, 'reason_stay_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Father Highest Level Education </strong></td>
    <td> <?php echo  $participant->father_highest_level_education;
echo la_casa_edit_data_gen_selector($education_levels_array, $participant->father_highest_level_education, 'father_highest_level_education_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Mother Highest Level Education </strong></td>
    <td> <?php echo  $participant->mother_highest_level_education;
    echo la_casa_edit_data_gen_selector($education_levels_array, $participant->mother_highest_level_education, 'mother_highest_level_education_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Student Aspiration </strong></td>
    <td> <?php echo  $participant->student_aspiration;
    echo la_casa_edit_data_gen_selector($education_levels_array, $participant->student_aspiration, 'student_aspiration_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>First Generation College Student </strong></td>
    <td> <?php echo  $participant->first_generation_college_student;
echo la_casa_edit_data_gen_selector($yn_array, $participant->first_generation_college_student, 'first_generation_college_student_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Student_high_school </strong></td>
    <td> <?php echo  $participant->student_high_school;
    echo la_casa_edit_data_gen_input($participant->student_high_school, 'student_high_school_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>AMI </strong></td>
    <td> <?php echo  $participant->ami;
    echo la_casa_edit_data_gen_input($participant->ami, 'ami_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Move_in_date </strong></td>
    <td> <?php echo  $participant->move_in_date;
    echo la_casa_edit_data_gen_input($participant->move_in_date, 'move_in_date_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Move_out_date </strong></td>
    <td> <?php echo  $participant->move_out_date;
    echo la_casa_edit_data_gen_input($participant->move_out_date, 'move_out_date_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Mid_twenties </strong></td>
    <td> <?php echo  $participant->mid_twenties;
echo la_casa_edit_data_gen_selector($yn_array, $participant->mid_twenties, 'mid_twenties_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Masters_degree </strong></td>
    <td> <?php echo  $participant->masters_degree;
echo la_casa_edit_data_gen_selector($yn_array, $participant->masters_degree, 'masters_degree_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Married </strong></td>
    <td> <?php echo  $participant->married;
echo la_casa_edit_data_gen_selector($yn_array, $participant->married, 'married_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Military </strong></td>
    <td> <?php echo  $participant->military;
echo la_casa_edit_data_gen_selector($yn_array, $participant->military, 'military_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Has_children </strong></td>
    <td> <?php echo  $participant->has_children;
echo la_casa_edit_data_gen_selector($yn_array, $participant->has_children, 'has_children_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Homeless </strong></td>
    <td> <?php echo  $participant->homeless;
echo la_casa_edit_data_gen_selector($yn_array, $participant->homeless, 'homeless_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Self_sustaining </strong></td>
    <td> <?php echo  $participant->self_sustaining;
echo la_casa_edit_data_gen_selector($yn_array, $participant->self_sustaining, 'self_sustaining_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Tax_exemptions </strong></td>
    <td> <?php echo  $participant->tax_exemptions;
    echo la_casa_edit_data_gen_input($participant->tax_exemptions, 'tax_exemptions_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Household_size_trp </strong></td>
    <td> <?php echo  $participant->household_size_trp;
    echo la_casa_edit_data_gen_input($participant->household_size_trp, 'household_size_trp_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Tuition </strong></td>
    <td> <?php echo  $participant->tuition;
    echo la_casa_edit_data_gen_input($participant->tuition, 'tuition_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Mandatory_fees </strong></td>
    <td> <?php echo  $participant->mandatory_fees;
    echo la_casa_edit_data_gen_input($participant->mandatory_fees, 'mandatory_fees_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>College_cost </strong></td>
    <td> <?php echo  $participant->college_cost;
    echo la_casa_edit_data_gen_input($participant->college_cost, 'college_cost_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Savings </strong></td>
    <td> <?php echo  $participant->savings;
    echo la_casa_edit_data_gen_input($participant->savings, 'savings_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Family_help </strong></td>
    <td> <?php echo  $participant->family_help;
    echo la_casa_edit_data_gen_input($participant->family_help, 'family_help_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Lc_scholarship </strong></td>
    <td> <?php echo  $participant->lc_scholarship;
    echo la_casa_edit_data_gen_input($participant->lc_scholarship, 'lc_scholarship_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Application_source </strong></td>
    <td> <?php echo  $participant->application_source;
    echo la_casa_edit_data_gen_input($participant->application_source, 'application_source_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Notes </strong></td>
    <td> <?php echo  $participant->notes;
    echo la_casa_edit_data_gen_input($participant->notes, 'notes_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Email_pack </strong></td>
    <td> <?php echo  $participant->email_pack;
echo la_casa_edit_data_gen_selector($yn_array, $participant->email_pack, 'email_pack_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Email_orientation </strong></td>
    <td> <?php echo  $participant->email_orientation;
echo la_casa_edit_data_gen_selector($yn_array, $participant->email_orientation, 'email_orientation_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Email_roommate </strong></td>
    <td> <?php echo  $participant->email_roommate;
echo la_casa_edit_data_gen_selector($yn_array, $participant->email_roommate, 'email_roommate_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Move_in_time </strong></td>
    <td> <?php echo  $participant->move_in_time;
    echo la_casa_edit_data_gen_input($participant->move_in_time, 'move_in_time_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Move_in_registration </strong></td>
    <td> <?php echo  $participant->move_in_registration;
    echo la_casa_edit_data_gen_input($participant->move_in_registration, 'move_in_registration_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Move_in_address </strong></td>
    <td> <?php echo  $participant->move_in_address;
    echo la_casa_edit_data_gen_input($participant->move_in_address, 'move_in_address_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Move_in_note </strong></td>
    <td> <?php echo  $participant->move_in_note;
    echo la_casa_edit_data_gen_input($participant->move_in_note, 'move_in_note_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Orientation_date </strong></td>
    <td> <?php echo  $participant->orientation_date;
    echo la_casa_edit_data_gen_input($participant->orientation_date, 'orientation_date_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td><strong>Orientation_time </strong></td>
    <td> <?php echo  $participant->orientation_time;
    echo la_casa_edit_data_gen_input($participant->orientation_time, 'orientation_time_edit', 'edit_term constant'); ?></td>
    </tr>
    <tr>
    <td>
    <input type="button" value="Edit" onclick="$('.edit_term.constant').toggle()">
    <input type="button" value="Save" onclick="
        $.post(
            '../ajax/save_la_casa_info.php',
            {
                action: 'edit',
                subject: 'constant',
                id: '<?php echo $participant->participant_id; ?>',
                cohort: document.getElementById('edit_cohort').value,
                status: document.getElementById('status_edit').value,
                handbook: document.getElementById('handbook_edit').value,
                floor: document.getElementById('floor_edit').value,
                pod: document.getElementById('pod_edit').value,
                room_number: document.getElementById('room_number_edit').value,
                key_card_number: document.getElementById('key_card_number_edit').value,
                transcript_submitted: document.getElementById('transcript_submitted_edit').value,
                service_hours_submitted: document.getElementById('service_hours_submitted_edit').value,
                lcrc_username: document.getElementById('lcrc_username_edit').value,
                lcrc_password: document.getElementById('lcrc_password_edit').value,
                lcrc_print_code: document.getElementById('lcrc_print_code_edit').value,
                application_received: document.getElementById('application_received_edit').value,
                application_completed: document.getElementById('application_completed_edit').value,
                household_size: document.getElementById('household_size_edit').value,
                parent1_agi: document.getElementById('parent1_agi_edit').value,
                parent2_agi: document.getElementById('parent2_agi_edit').value,
                student_agi: document.getElementById('student_agi_edit').value,
                act_score: document.getElementById('act_score_edit').value,
                high_school_gpa: document.getElementById('high_school_gpa_edit').value,
                dependency_status: document.getElementById('dependency_status_edit').value,
                hs_gpa_weighted: document.getElementById('hs_gpa_weighted_edit').value,
                expected_graduation_year: document.getElementById('expected_graduation_year_edit').value,
                college_grade_level: document.getElementById('college_grade_level_edit').value,
                reason_leave: document.getElementById('reason_leave_edit').value,
                reason_stay: document.getElementById('reason_stay_edit').value,
                father_highest_level_education: document.getElementById('father_highest_level_education_edit').value,
                mother_highest_level_education: document.getElementById('mother_highest_level_education_edit').value,
                student_aspiration: document.getElementById('student_aspiration_edit').value,
                first_generation_college_student: document.getElementById('first_generation_college_student_edit').value,
                student_high_school: document.getElementById('student_high_school_edit').value,
                ami: document.getElementById('ami_edit').value,
                move_in_date: document.getElementById('move_in_date_edit').value,
                move_out_date: document.getElementById('move_out_date_edit').value,
                mid_twenties: document.getElementById('mid_twenties_edit').value,
                masters_degree: document.getElementById('masters_degree_edit').value,
                married: document.getElementById('married_edit').value,
                military: document.getElementById('military_edit').value,
                has_children: document.getElementById('has_children_edit').value,
                homeless: document.getElementById('homeless_edit').value,
                self_sustaining: document.getElementById('self_sustaining_edit').value,
                tax_exemptions: document.getElementById('tax_exemptions_edit').value,
                household_size_trp: document.getElementById('household_size_trp_edit').value,
                tuition: document.getElementById('tuition_edit').value,
                mandatory_fees: document.getElementById('mandatory_fees_edit').value,
                college_cost: document.getElementById('college_cost_edit').value,
                savings: document.getElementById('savings_edit').value,
                family_help: document.getElementById('family_help_edit').value,
                lc_scholarship: document.getElementById('lc_scholarship_edit').value,
                application_source: document.getElementById('application_source_edit').value,
                notes: document.getElementById('notes_edit').value,
                email_pack: document.getElementById('email_pack_edit').value,
                email_orientation: document.getElementById('email_orientation_edit').value,
                email_roommate: document.getElementById('email_roommate_edit').value,
                move_in_time: document.getElementById('move_in_time_edit').value,
                move_in_registration: document.getElementById('move_in_registration_edit').value,
                move_in_address: document.getElementById('move_in_address_edit').value,
                move_in_note: document.getElementById('move_in_note_edit').value,
                orientation_date: document.getElementById('orientation_date_edit').value,
                orientation_time: document.getElementById('orientation_time_edit').value
            },
            function (response){
                window.location = 'lc_profile.php?id=<?php echo $participant->participant_id ?>';
            }
);
">
    </td>
    </tr>
    </table>
    </td>

    <td width="10%">
    </td>

    <td width="40%">
        <table class="inner_table" style="border: 2px solid #696969;">
        <tr>
            <th>Loan Type</th>
        <th>Loan Amount</th>
        <th>Term</th>
        </tr>
        <?php
            $loan_info = $participant->get_loans_info($participant->participant_id);
        foreach ($loan_info as $loan){
            ?>
            <tr>
            <td><strong><?php echo $loan['type']; ?></strong></td>
            <td><?php echo $loan['amount']; ?></td>
            <td><?php echo $loan['term']; ?></td>
            </tr>
            <?php
        }
        ?>
        </table>        
    </td>
    </tr>
<tr>

</tr>
    </table>

</body>
