<div id="program_involvement">
    <h4>Extent of Involvement - All Programs and Campaigns</h4>
<?php
include "../include/datepicker.php";
if (isset($_POST['submit'])){
    //reformat
    $start_date_array = explode('-', $_POST['start_date']);
    $start_date = $start_date_array[2] . '-' . $start_date_array[0] . '-' . $start_date_array[1];
    $end_date_array = explode('-', $_POST['end_date']);
    $end_date = $end_date_array[2] . '-' . $end_date_array[0] . '-' . $end_date_array[1];
}
else{
    //default values
     $start_date = '2013-01-01';
     $end_date = '2013-12-31';
}


?>

    <!--
    Shows how many programs & campaigns people are involved in.
    -->
    <span class="helptext">This table provides a summary of the total number of different programs and campaigns that participants (and types of participants) have been involved in.</span><br/>
    <table class="program_involvement_table">
    <caption> Showing results from <?php $start_date_display=date_create($start_date);
echo date_format($start_date_display,"M d, Y");
 ?> to <?php $end_date_display=date_create($end_date);
echo date_format($end_date_display,"M d, Y"); ?> </caption>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="filterByDate">
     <tr><th>Start Date:</th>
     <th><input type = "text" name = "start_date" class = "hadDatepicker"></th>
     <th>End Date:</th>
     <th><input type = "text" name = "end_date" class = "hadDatepicker"></th>
     <th><input type = "submit" value = "Sort" name = "submit"></th>
</form
     </tr>

        <tr style="font-size:.9em;"><th>Number of Programs and Campaigns</th>
            <th>Number of Participants</th>
            <th>Number of Adults</th>
            <th>Number of Parent Mentors</th>
            <th>Number of Youth</th>
            <th>Number of Parent Mentor's Children</th>
        </tr>
        <?php
        //so here we count up the number of programs and campaigns in a while loop
        //first I'm going to get the highest number of programs/campaigns that anyone is involved in.
        $count_participants = "SELECT Participant_ID, COUNT(*) FROM Participants_Subcategories WHERE Date_Linked >= '$start_date' AND Date_Linked <= '$end_date' GROUP BY Participant_ID ORDER BY COUNT(*) DESC;";
        include "../include/dbconnopen.php";
        $ct_participants = mysqli_query($cnnLSNA, $count_participants);
        $top_num = mysqli_fetch_row($ct_participants);
        $most_programs = $top_num[1];
        $participants_array = array();
        $adults_array = array();
        $pm_array = array();
        $youth_array = array();
        $pm_children_array = array();
        for ($i = 1; $i < $most_programs + 1; $i++) {
            ?>
            <tr>
                <td style="background-color:lightgray;text-align:center;">
                    <?php echo $i; /* is the number of programs and campaigns (got the highest number, and thus the number of rows, above) */ ?>
                </td>
                <td>
                    <?php
                    $counter_num = 0;
                    $count_participants = "SELECT Participant_ID, COUNT(*) FROM Participants_Subcategories WHERE  Date_Linked >= '$start_date' AND Date_Linked <= '$end_date' GROUP BY Participant_ID ORDER BY COUNT(*) DESC;";
                    include "../include/dbconnopen.php";
                    $ct_participants = mysqli_query($cnnLSNA, $count_participants);
                    while ($count_partis = mysqli_fetch_row($ct_participants)) {
                        //echo $count_partis[1];
                        if ($count_partis[1] == $i) {
                            $counter_num++;
                        }
                    }
                    echo $counter_num; /* number of people who participated in this number of programs and campaigns */
                    $participants_array[$i] = $counter_num;
                    include "../include/dbconnclose.php";
                    ?>
                </td>
                <td>
                    <?php
                    /* number of adults who participated in this number of programs and campaigns */
                    $counter_num = 0;
                    $count_adults = "SELECT Participants_Subcategories.Participant_ID, COUNT(*) 
                    FROM Participants_Subcategories INNER JOIN Participants
                    ON Participants.Participant_Id=Participants_Subcategories.Participant_ID
                    WHERE (Is_Child IS NULL OR Is_Child=3 OR Is_Child=0)  AND Date_Linked >= '$start_date' AND Date_Linked <= '$end_date'
                    GROUP BY Participant_ID ORDER BY COUNT(*) DESC;";
                    include "../include/dbconnopen.php";
                    $ct_adults = mysqli_query($cnnLSNA, $count_adults);
                    while ($count_partis = mysqli_fetch_row($ct_adults)) {
                        //echo $count_partis[1];
                        if ($count_partis[1] == $i) {
                            $counter_num++;
                        }
                    }
                    echo $counter_num;
                    $adults_array[$i] = $counter_num;
                    include "../include/dbconnclose.php";
                    ?>
                </td>
                <td>
                    <?php
                    /* number of parent mentors who participated in this number of programs and campaigns */
                    $counter_num = 0;
                    /* $count_pms="SELECT Participants_Subcategories.Participant_ID, COUNT(*) 
                      FROM Participants_Subcategories INNER JOIN Participants
                      ON Participants.Participant_Id=Participants_Subcategories.Participant_ID
                      WHERE (Is_PM IS NOT NULL AND Is_PM=1)
                      GROUP BY Participant_ID ORDER BY COUNT(*) DESC;"; */
                    $count_pms = "SELECT Participants_Subcategories.Participant_ID, COUNT(*) FROM Participants_Subcategories 
                    INNER JOIN Participants ON Participants.Participant_Id=Participants_Subcategories.Participant_ID 
                    INNER JOIN (SELECT DISTINCT Participant_ID FROM Participants_Subcategories WHERE Subcategory_ID=19) as check_pm 
                    ON check_pm.Participant_ID=Participants_Subcategories.Participant_ID 
WHERE  Date_Linked >= '$start_date' AND Date_Linked <= '$end_date'
                    GROUP BY check_pm.Participant_ID ORDER BY COUNT(*) DESC;";
                    // echo $count_pms;
                    include "../include/dbconnopen.php";
                    $ct_pms = mysqli_query($cnnLSNA, $count_pms);
                    while ($count_partis = mysqli_fetch_row($ct_pms)) {
                        //echo $count_partis[1];
                        //$true_count=$count_partis[1]/2;
                        if ($count_partis[1] == $i) {
                            $counter_num++;
                        }
                    }
                    echo $counter_num;
                    $pm_array[$i] = $counter_num;
                    include "../include/dbconnclose.php";
                    ?>
                </td>
                <td>
                    <?php
                    /* number of children who participated in this number of programs and campaigns */
                    $counter_num = 0;
                    $count_youth = "SELECT Participants_Subcategories.Participant_ID, COUNT(*) 
                    FROM Participants_Subcategories INNER JOIN Participants
                    ON Participants.Participant_Id=Participants_Subcategories.Participant_ID
                    WHERE (Is_Child IS NOT NULL AND Is_Child=2)
AND  Date_Linked >= '$start_date' AND Date_Linked <= '$end_date'
                    GROUP BY Participant_ID ORDER BY COUNT(*) DESC;";
                    include "../include/dbconnopen.php";
                    $ct_youth = mysqli_query($cnnLSNA, $count_youth);
                    while ($count_partis = mysqli_fetch_row($ct_youth)) {
                        //echo $count_partis[1];
                        if ($count_partis[1] == $i) {
                            $counter_num++;
                        }
                    }
                    echo $counter_num;
                    $youth_array[$i] = $counter_num;
                    include "../include/dbconnclose.php";
                    ?>
                </td>
                <td>
                    <?php
                    /* number of parent mentor children who participated in this number of programs and campaigns */
                    $counter_num = 0;
                    //this takes account of children who might have more than one parent in the PM program
                    $count_youth = "SELECT DISTINCT(Child_ID), COUNT(Subcategory_ID)
                    FROM Participants_Subcategories INNER JOIN Parent_Mentor_Children
                    ON Parent_Mentor_Children.Child_Id=Participants_Subcategories.Participant_ID
WHERE  Date_Linked >= '$start_date' AND Date_Linked <= '$end_date'
                    GROUP BY Parent_Mentor_Children_Link_ID;";
                    include "../include/dbconnopen.php";
                    $ct_youth = mysqli_query($cnnLSNA, $count_youth);
                    while ($count_partis = mysqli_fetch_row($ct_youth)) {
                        //echo $count_partis[1];
                        if ($count_partis[1] == $i) {
                            $counter_num++;
                        }
                    }
                    echo $counter_num;
                    $pm_children_array[$i] = $counter_num;
                    include "../include/dbconnclose.php";
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td><span class="helptext">Total participants in the system:</span></td>
            <td>
                <?php
                $get_participants = "SELECT * FROM Participants";
                include "../include/dbconnopen.php";
                $participants = mysqli_query($cnnLSNA, $get_participants);
                $num_parti = mysqli_num_rows($participants);
                echo $num_parti;
                ?>
            </td>
            <td>
                <?php
                $get_adults = "SELECT * FROM Participants WHERE (Is_Child IS NULL OR Is_Child=3 OR Is_Child=0)";
                $adults = mysqli_query($cnnLSNA, $get_adults);
                $num_adults = mysqli_num_rows($adults);
                echo $num_adults;
                ?>
            </td>
            <td>
                <?php
                $get_pms = "SELECT DISTINCT Participant_ID FROM Participants_Subcategories WHERE Subcategory_ID='19' AND  Date_Linked >= '$start_date' AND Date_Linked <= '$end_date'";
                $pms = mysqli_query($cnnLSNA, $get_pms);
                $num_pms = mysqli_num_rows($pms);
                echo $num_pms;
                ?></td>
            <td>
                <?php
                $get_youth = "SELECT * FROM Participants WHERE Is_Child=2";
                $youth = mysqli_query($cnnLSNA, $get_youth);
                $num_youth = mysqli_num_rows($youth);
                echo $num_youth;
                ?>
            </td>
            <td>
                <?php
                $get_pm_children = "SELECT DISTINCT Child_ID FROM Parent_Mentor_Children;";
                $pm_children = mysqli_query($cnnLSNA, $get_pm_children);
                $num_pm_children = mysqli_num_rows($pm_children);
                echo $num_pm_children;
                ?>
            </td>
        </tr>
    </table>
    <br/><br/>
    <br/><br/> 

    <!--Shows how many of each type of person are involved in each program/campaign: -->

    <h4>Extent of Involvement By Program/Campaign</h4>
    <table class="program_involvement_table">	
        <tr>
            <th>Program/Campaign</th>
            <th>Number of Participants</th><!--Total -->
            <th>Number of Adults</th>
            <th>Number of Parent-Mentors</th>
            <th>Number of Youth</th>
            <th>Number of Parent Mentor's Children</th>
        </tr>
        <?php
        $get_programs = "SELECT * FROM Subcategories ORDER BY Subcategory_Name";
        include "../include/dbconnopen.php";
        $programs = mysqli_query($cnnLSNA, $get_programs);
        while ($program = mysqli_fetch_array($programs)) {
            ?>
            <tr>
                <!--For each subcategory (program or campaign), count the total number of people involved and then
                split them up by type of person.
                -->
                <td style="text-align:left;"><strong><?php echo $program['Subcategory_Name']; ?><strong></td>
                            <td>
                                <?php
                                $counter_num = 0;
                                $count_participants = "SELECT DISTINCT Participant_ID 
                    FROM Participants_Subcategories 
                    WHERE Subcategory_ID='" . $program['Subcategory_ID'] . "' AND  Date_Linked >= '$start_date' AND Date_Linked <= '$end_date';";
                                $ct_participants = mysqli_query($cnnLSNA, $count_participants);
                                $count_partis = mysqli_num_rows($ct_participants);
                                echo $count_partis;
                                //$participants_array[$i]=$counter_num;
                                ?>
                            </td>
                            <td>
                                <?php
                                $counter_num = 0;
                                $count_adults = "SELECT DISTINCT Participants_Subcategories.Participant_ID FROM Participants_Subcategories INNER JOIN Participants
                    ON Participants.Participant_Id=Participants_Subcategories.Participant_ID
                    WHERE (Is_Child IS NULL OR Is_Child=3 OR Is_Child=0)
AND  Date_Linked >= '$start_date' AND Date_Linked <= '$end_date'
                    AND Participants_Subcategories.Subcategory_ID='" . $program['Subcategory_ID'] . "';";
                                $ct_adults = mysqli_query($cnnLSNA, $count_adults);
                                $count_partis = mysqli_num_rows($ct_adults);
                                echo $count_partis;
                                $adults_array[$i] = $counter_num;
                                ?>
                            </td>
                            <td>
                                <?php
                                $counter_num = 0;
                                $count_pms = "SELECT COUNT(DISTINCT Participants_Subcategories.Participant_ID) FROM Participants_Subcategories 
                INNER JOIN Participants ON Participants.Participant_Id=Participants_Subcategories.Participant_ID 
                INNER JOIN (SELECT * FROM Participants_Subcategories WHERE Subcategory_ID=19) as check_pm ON check_pm.Participant_ID=Participants_Subcategories.Participant_ID
                WHERE Participants_Subcategories.Subcategory_ID='" . $program['Subcategory_ID'] . "' AND  Date_Linked >= '$start_date' AND Date_Linked <= '$end_date' ;";
                                // echo $count_pms;
                                $ct_pms = mysqli_query($cnnLSNA, $count_pms);
                                $count_partis = mysqli_fetch_row($ct_pms);
                                echo $count_partis[0];
                                $pm_array[$i] = $counter_num;
                                ?>
                            </td>
                            <td>
                                <?php
                                $counter_num = 0;
                                $count_youth = "SELECT COUNT(*) 
                    FROM Participants_Subcategories INNER JOIN Participants
                    ON Participants.Participant_Id=Participants_Subcategories.Participant_ID
                    WHERE (Is_Child IS NOT NULL AND Is_Child=2)
AND Date_Linked >= '$start_date' AND Date_Linked <= '$end_date'
                    AND Participants_Subcategories.Subcategory_ID='" . $program['Subcategory_ID'] . "';";
                                $ct_youth = mysqli_query($cnnLSNA, $count_youth);
                                $count_partis = mysqli_fetch_row($ct_youth);
                                echo $count_partis[0];
                                $youth_array[$i] = $counter_num;
                                ?>
                            </td>
                            <td>
                                <?php
                                $counter_num = 0;
                                //this takes account of children who might have more than one parent in the PM program
                                $count_children = "SELECT COUNT(*) FROM 
                    Participants_Subcategories INNER JOIN Parent_Mentor_Children 
                    ON Parent_Mentor_Children.Child_Id=Participants_Subcategories.Participant_ID 
                    WHERE Participants_Subcategories.Subcategory_Id='" . $program['Subcategory_ID'] . "'
AND  Date_Linked >= '$start_date' AND Date_Linked <= '$end_date'";
                                $ct_youth = mysqli_query($cnnLSNA, $count_children);
                                $count_partis = mysqli_fetch_row($ct_youth);
                                echo $count_partis[0];
                                $pm_children_array[$i] = $counter_num;
                                ?>
                            </td>
                            </tr>
                            <?php
                        }
                        include "../include/dbconnclose.php";
                        ?>
                        </table>
                        </div>

                        <!--This doesn't show up anywhere.  It was a way to show the above, but search for an individual program.-->

                        <div id="program_involvement_sorted">

                            First, search for the program you want to report on:
                            <table class="program_table">
                                <tr><td class="all_projects"><strong>Program/Campaign Name (or part of name):</strong></td>
                                    <td class="all_projects"><input type="text" id="name"></td></tr>
                                <tr><td class="all_projects"><strong>Issue Area:</strong></td>
                                    <td class="all_projects"><select id="type">
                                            <option value="">-----</option>
                                            <?php
                                            $program_query = "SELECT * FROM Categories ORDER BY Category_Name";
                                            include "../include/dbconnopen.php";
                                            $programs = mysqli_query($cnnLSNA, $program_query);
                                            while ($program = mysqli_fetch_array($programs)) {
                                                ?>
                                                <option value="<?php echo $program['Category_ID']; ?>"><?php echo $program['Category_Name']; ?></option>
                                                <?php
                                            }
                                            include "../include/dbconnclose.php";
                                            ?>
                                        </select></td></tr>
                                <tr><th colspan="2"><input type="button" value="Search" onclick="
                                        $.post(
                                                '../ajax/search_programs.php',
                                                {
                                                    name: document.getElementById('name').value,
                                                    type: document.getElementById('type').value,
                                                    report_search: 'dropdown'
                                                },
                                        function(response) {
                                            document.getElementById('show_results_program_search').innerHTML = response;
                                        }
                                        )"></th></tr>
                            </table>
                            <br/>
                            <div id="show_results_program_search"></div>
                        </div>