<?php
if(!defined("ROOT")) exit("Direct Access To This Script Not Allowed");

$slug=_slug("moduleName/refData/refHash");
// printArray($slug);exit();
$pageName=current(explode("/",PAGE));

if($pageName=="modules") $pageName="modules";
else $pageName="popup";

$mSlug=explode("!",$slug['refData']);

if(isset($mSlug[1]) && strlen($mSlug[1])>0) $tabSlug=$mSlug[1];
else $tabSlug=false;

$slug['refData']=$mSlug[0];

$subSlug=explode("@",$slug['refData']);
if(count($subSlug)>1) {
    $slug['refHash']=$subSlug[1];
    $slug['refData']=$subSlug[0];
}

if(is_numeric($slug['refHash'])) $slug['refHash']=md5($slug['refHash']);

$infoview=str_replace(".","/",$slug['refData']);
$infoFile=APPROOT."misc/forms/{$infoview}.json";

if(file_exists($infoFile)) {
  if(is_numeric($slug['refHash'])) {
      $slug['refHash']=md5($slug['refHash']);
  }
  $url= _link("{$pageName}/infoview/{$slug['refData']}/{$slug['refHash']}");
  
  header("Location:{$url}");
} else {
  _log("UNILINK:MISSING:{$infoFile}","activity");
  echo "Required Module Not Found";
}
?>