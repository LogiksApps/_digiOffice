<?php
$noFix=explode(",", getConfig("LOGIN_EXEMPT"));
$noFix[]="login";
$noFix[]="home";
$noFix[]="welcome";

if(!in_array(PAGE, $noFix)) {
	if($_SERVER['HTTP_REFERER']==null || strlen($_SERVER['HTTP_REFERER'])<=1) {
		header("Location:"._link(""));
		exit("This page is allowed within Application only.");
	}
	echo "<script>if(top==window) window.location='"._link("")."';</script>";
}
?>
