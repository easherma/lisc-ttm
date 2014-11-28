<?php
include "../../header.php";
include "../header.php";
?>

<!--
This file is linked as "Physical change over time" and refers to the health data
to show the average BMI of all participants over time. 
-->

<script type="text/javascript">
    $(document).ready(function() {
        $('#data_selector').addClass('selected');
        $('#adult_selector').addClass('selected');
        for (var i = 0; i < 50; i++) {
            $('#names_of_people_measured_' + i).hide();
        }
    });
</script>
<div class="content_wide">
    <table width="40%" align="center" id="health_survey_menu">
        <tr>
            <td class="menu_item"><a href="health_report_youth.php" id="youth_selector">Youth Health Data</a></td>
            <td class="menu_item"><a href="health_report.php" id="adult_selector">Adult Health Data</a></td>
        </tr>

    </table>
    <p></p>
    <h3>Health Data Over Time (Adults)</h3><br/>

    <table class="all_projects">
        <tr>
            <th>Measurement (over time)</th>
            <th>Average BMI</th>
            <th>Number of people measured</th>
        </tr>
        <?php
        /* Counts the maximum number of times someone has had their BMI entered
         * in order to create the correct number of rows in the table.
         */

        $count_measurements_sqlsafe = "SELECT User_ID, count(User_ID) as userCount FROM User_Health_Data GROUP BY User_ID ORDER BY 2 DESC LIMIT 1";
        include "../include/dbconnopen.php";
        $measurements = mysqli_query($cnnBickerdike, $count_measurements_sqlsafe);
        while ($count_bmis = mysqli_fetch_array($measurements)) {
            $count = $count_bmis['userCount'];
        }
        include "../include/dbconnclose.php";
        for ($i = 1; $i <= $count; $i++) {
            ?>
            <tr>
                <td class="all_projects">BMI Measurement #<?php echo $i; ?></td>
                <td class="all_projects">
                    <?php
                    /* Now I understand what the User_Count does!  Each time someone's BMI 
                     * is measured, it is recorded with a count so that this can track them over time.
                     * Thus, this report doesn't actually track changes in all participants over one time scale.
                     * Rather, it shows change in each person over the course of their
                     * BMI measurement and recording.  
                     */
                    /*
                     * This is the average of all the people who had their BMI measured for the nth time,
                     * not all the people who had their BMI measured in August (for example).
                     */

                    $get_average_bmi_sqlsafe = "SELECT AVG(BMI) FROM User_Health_Data INNER JOIN Users
                    ON User_Health_Data.User_ID=Users.User_ID
                    WHERE User_Count='" . $i . "'
                    AND Users.Child !=1;";
                    include "../include/dbconnopen.php";
                    $average_bmi = mysqli_query($cnnBickerdike, $get_average_bmi_sqlsafe);
                    while ($bmi = mysqli_fetch_array($average_bmi)) {
                        echo round($bmi['AVG(BMI)'], 2);
                    }
                    include "../include/dbconnclose.php";
                    ?>
                </td>
                <td class="all_projects">
                    <!--
                    Since the process is to count everyone individually, people will be ahead or behind
                    each other, meaning that the average isn't terribly meaningful.  To underscore that, 
                    we include the number of people measured each time.
                    -->
                    <?php
                    $get_user_count_sqlsafe = "SELECT * FROM User_Health_Data INNER JOIN Users
                    ON User_Health_Data.User_ID=Users.User_ID
                    WHERE User_Count='" . $i . "'
                    AND Users.Child !=1;";
                    // echo $get_user_count_sqlsafe;
                    include "../include/dbconnopen.php";
                    $measurements = mysqli_query($cnnBickerdike, $get_user_count_sqlsafe);
                    $num_users_measured = mysqli_num_rows($measurements);
                    include "../include/dbconnclose.php";
                    echo $num_users_measured;
                    ?>
                    <a href="javascript:;" onclick="$('#names_of_people_measured_<?php echo $i ?>').toggle();">Show/hide names</a>
                    <div id="names_of_people_measured_<?php echo $i ?>">
                        <?php
                        while ($person = mysqli_fetch_array($measurements)) {
                            ?><a href="../users/user_profile.php?id=<?php echo $person['User_ID']; ?>">
                                <?php echo $person['First_Name'] . " " . $person['Last_Name']; ?></a><br><?php
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <?php
        }
        ?>



    </table>

    <!--
    Created a file for download.
    -->

    <p>&nbsp;</p>
    <?php
    $infile = "../data/downloads/health_data.csv";
    $fp = fopen($infile, "w") or die('can\'t open file');
    $title_array = array("ID", "User ID", "BMI", "Date", "First Name",
        "Last Name", "Adult", "Parent", "Child");
    fputcsv($fp, $title_array);
    $get_money_sqlsafe = "SELECT * FROM User_Health_Data 
INNER JOIN Users
ON User_Health_Data.User_ID=Users.User_ID;";
    include "../include/dbconnopen.php";
    $money_info = mysqli_query($cnnBickerdike, $get_money_sqlsafe);
    while ($money = mysqli_fetch_array($money_info)) {
        $enter_array = array($money['User_Health_Data_ID'], $money['User_ID'], $money['BMI'], $money['Date'], $money['First_Name'],
            $money['Last_Name'], $money['Adult'], $money['Parent'], $money['Child']);
        fputcsv($fp, $enter_array);
    }
    include "../include/dbconnclose.php";
    fclose($fp);
    ?>

    <a class="download" href="<?php echo $infile; ?>">Download the CSV file of all health data.</a><br>
    <p class="helptext">This download includes all health data, so it is not organized the same way as the table above.</p>

</div>
<?php include "../../footer.php"; ?>