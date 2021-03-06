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

user_enforce_has_access($Bickerdike_id);

include "../../header.php";
include "../header.php";
include "reports_menu.php";
?>

<!--
landing page for all attitude reports.

The baseline year allows users to choose which edition of the community wellness survey should be used for the
baseline of these results.
-->

<script type="text/javascript">
	$(document).ready(function(){
		$('#data_selector').addClass('selected');
		$('#adults_attitude_selector').addClass('selected');
	});
</script>

<div class="content_wide">
<h3>Report on Obesity Attitude</h3><br/><br/>
<form action="../ajax/attitude_report.php" method="post">
Choose baseline year:
<select name="year" id="baseline_year">
    <option value="">-----</option>
    <?
    include "../include/dbconnopen.php";
    $get_baseline_averages = mysqli_query($cnnBickerdike, "SELECT * FROM Community_Wellness_Survey_Aggregates");
    while ($baseline_averages = mysqli_fetch_array($get_baseline_averages)){
        ?><option value="<?echo $baseline_averages['Community_Wellness_Survey_ID'];?>"><?echo $baseline_averages['Date_Administered'];?></option><?
    }
    ?>
        <option value="avg">Aggregate Baseline</option>
</select><br>
Choose adult, parent, or youth results:
<select name="type" id="select_type">
    <option value="">-----</option>
    <option value="adult">Adults</option>
    <option value="parent">Parents</option>
    <option value="youth">Youth</option>
</select><br>
<input type="submit" value="OK">
</form>
<div id="show_report_results"></div>

<? include "../../footer.php"; ?>
