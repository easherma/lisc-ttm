<?php
include $_SERVER['DOCUMENT_ROOT'] . "/include/dbconnopen.php";
include $_SERVER['DOCUMENT_ROOT'] . "/core/include/setup_user.php";

user_enforce_has_access($Enlace_id);

/*
 * Campaign class.  didn't end up using this for much (just getting the campaign name...)
 * but it's here in case more functions are needed.
 */
class Campaign
{
    public function  __construct()
    {
        
    }
    
     public function load_with_id($campaign_id)
    {
        include "../include/dbconnopen.php";
        $this->campaign_id = mysqli_real_escape_string($cnnEnlace, $campaign_id);
        $camp_query = "SELECT * FROM Campaigns WHERE Campaign_ID= '" . $this->campaign_id . "'";
        $campaign_info = mysqli_query($cnnEnlace, $camp_query);
        
        //set public variables
        $campaign_info_temp = mysqli_fetch_array($campaign_info);
        
        $this->name = $campaign_info_temp['Campaign_Name'];
    }
}
?>
