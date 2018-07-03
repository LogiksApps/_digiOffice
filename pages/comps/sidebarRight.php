<?php
function getSidebarActiveTab($tabName) {
    if(!isset($_COOKIE['SIDEBAR_RIGHT_CURRENT'])) $_COOKIE['SIDEBAR_RIGHT_CURRENT']="sidebarToday";
    
    if($tabName==$_COOKIE['SIDEBAR_RIGHT_CURRENT']) return "active";
    else return "";
}
?>