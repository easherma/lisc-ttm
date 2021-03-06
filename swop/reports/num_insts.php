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
include "reports_menu.php";

?>

<!--- Not often used.  Counts the number of institutions in the system. -->

<h4>Number of Institutions</h4>
<br/>
<table class="all_projects">
    <tr><th>Quarter</th><th>Number of Institutions</th></tr>
    <?php
    //find current quarter, then back up from there
    date_default_timezone_set('America/Chicago');
    $this_year=date('Y');
    $this_month=date('m');
    if ($this_month>=1 && $this_month<=3){ $this_qtr=1; }
    elseif ($this_month>=4 && $this_month<=6){ $this_qtr=2;}
    elseif ($this_month>=7 && $this_month<=9){ $this_qtr=3; }
    elseif ($this_month>=10 && $this_month<=12){ $this_qtr=4; }
    
    /* show this year and previous 2 years. */
    for ($i=0; $i<3; $i++){
    $year_shown=$this_year-$i;
    for ($j=$this_qtr; $j>0; $j--){
        if ($j==1){ $end_of_quarter='03-31';}
        elseif ($j==2){$end_of_quarter='06-30';}
        elseif ($j==3){ $end_of_quarter='09-30';}
        elseif ($j==4){ $end_of_quarter='12-31';}
    ?>
    <tr><td class="all_projects"><?echo $year_shown?> - Quarter <?echo $j?></td><td class="all_projects">
        <?php
        /* count institutions at a given time. */
        $count_insts_sqlsafe = "SELECT COUNT(*) FROM Institutions WHERE Date_Added<='$year_shown-$end_of_quarter'";
    //echo $count_insts_sqlsafe;
    include "../include/dbconnopen.php";
    $inst_ct=mysqli_query($cnnSWOP, $count_insts_sqlsafe);
    $inst=mysqli_fetch_row($inst_ct);
    echo $inst[0];
    include "../include/dbconnclose.php";
    ?></td>
</tr>
    <?php
    }
    $this_qtr=4;
    }
?>
</table>
<br/><br/>

<?php
	include "../../footer.php";
close_all_dbconn();
?>