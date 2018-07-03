<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(!function_exists("setupDGEnviroment")) {
	include_once __DIR__."/api.php";
	
	function checkEnviroment() {
		if(!isset($_SESSION['ENV_CHECK_DONE'])) {
			$reqModules=getConfig("REQUIRED_MODULES");
			if(strlen($reqModules)>0) {
				$reqModules=explode(",",$reqModules);
				$reqModules=array_flip(array_unique($reqModules));
				
				$arrError=[];
				foreach($reqModules as $m=>$s) {
					$s=checkModule($m);
					if(!$s) {
						$arrError[]=$m;
					}
					$reqModules[$m]=($s===false)?false:true;
				}
				if(count($arrError)>0) {
					echo "<h3>Error finding the below modules, please install them first.</h3><ul>";
					foreach($arrError as $m) {
						echo "<li>{$m}</li>";
					}
					echo "</ul>";
					exit();
				} else {
					$_SESSION['ENV_CHECK_DONE']=true;
				}
			} else {
				$_SESSION['ENV_CHECK_DONE']=true;
			}
		}
	}
	
	function setupDGEnviroment() {
		if(!defined("ADMIN_PRIVILEGE_RANGE")) define("ADMIN_PRIVILEGE_RANGE",5);
		
    if(defined("PAGE") && in_array(PAGE,["welcome","login","logout","logout.php"])) {
      return true;
    }
    if(defined("SERVICE_ROOT")) {
      return true;
    }
    
		checkEnviroment();
		
		if(isset($_SESSION['SESS_PRIVILEGE_ID'])) {
			$_SESSION['SESS_ACCESS_LEVEL']=$_SESSION['SESS_PRIVILEGE_ID'];
			
			if(!isset($_SESSION['SESS_GROUP_NAME']) || strlen($_SESSION['SESS_GROUP_NAME'])<=0) {
				$_SESSION['SESS_GROUP_NAME']="hq";
			}
			
			checkGenericPermissions();
			
			//Check Company
			if(!isset($_SESSION["COMP_NAME_DATE"]) || (time()-$_SESSION["COMP_NAME_DATE"])>3000) {
				$companyData=_db()->_selectQ("my_company","*",["branch_type"=>"HQ"])->_GET();
				if(count($companyData)>0) {
					foreach($companyData[0] as $f=>$v) {
						if(!in_array($f,["setup_params"])) {
							$_SESSION["COMP_".strtoupper($f)]=$v;
						}
					}
					$setupParams=$companyData[0]['setup_params'];
					if(strlen($setupParams)>2) {
						$setupParams=json_decode($setupParams,true);
						foreach($setupParams as $a=>$b) {
							$_SESSION["PARAMS_".strtoupper($a)]=$b;
						}
					}
				} else {
					//do setup
					echo "<h1 align=center>Company Setup Incomplete. Pleases complete that first.</h1>";
					header("Location:"._link("welcome"));
					exit();
				}
				
				loadBizSettings();
				
				$_SESSION["COMP_NAME_DATE"]=time();
			}
			
			//Check Profile
			if(!isset($_SESSION["SESS_PROFILE_ID"]) || (time()-$_SESSION["COMP_NAME_DATE"])>3000) {
			    $profileData=_db()->_selectQ("profiletbl","*",["loginid"=>$_SESSION['SESS_USER_ID']])->_GET();
        		if(count($profileData)>0) {
        		    $profileData=$profileData[0];
        		} else {
        		    //Create a userid
        		    $profileData=[
        		            "guid"=>$_SESSION['SESS_GUID'],
        		            "loginid"=>$_SESSION['SESS_USER_ID'],
        		            "groupuid"=>$_SESSION['SESS_GROUP_NAME'],
        		            "profile_code"=>$_SESSION['COMP_COM_CODE']."/".str_pad("".rand(0,999999),6, "0", STR_PAD_LEFT),
        		            "full_name"=>$_SESSION['SESS_USER_NAME'],
        		            "organization"=>$_SESSION['COMP_NAME'],
        		            "designation"=>"recruit",
        		            "type"=>"employee",
        		            "subtype"=>"active",
        		            "email1"=>$_SESSION['SESS_USER_EMAIL'],
        		            "mobile"=>$_SESSION['SESS_USER_CELL'],
        		            "state"=>$_SESSION['COMP_STATE_CODE'],
        		            "country"=>$_SESSION['SESS_USER_COUNTRY'],
        		            "zipcode"=>$_SESSION['SESS_USER_ZIPCODE'],
        		            "avatar"=>$_SESSION['SESS_USER_AVATAR'],
        		            
        		            "privilegeid"=>$_SESSION['SESS_PRIVILEGE_ID'],
        		            "branch"=>1,
        		            "access_level"=>$_SESSION['SESS_ACCESS_LEVEL'],
        		            
        		            "created_by"=>$_SESSION['SESS_USER_ID'],
        		            "edited_by"=>$_SESSION['SESS_USER_ID'],
        		            "created_on"=>date("Y-m-d H:i:s"),
        		            "edited_on"=>date("Y-m-d H:i:s"),
        		        ];
        		    $a=_db()->_insertQ1("profiletbl",$profileData)->_RUN();
        		    
        		    if(!$a) {
        		        echo "<h1 align=center>Error Setting Up Your Profile. Please contact admin.</h1>";
					          exit();
        		    }
        		    $profileData['id']=_db()->get_insertID();
        		}
        		
        		$_SESSION["SESS_PROFILE_ID"]=$profileData['id'];
    		    $_SESSION["SESS_BRANCH_ID"]=$profileData['branch'];
    		    $_SESSION["SESS_PROFILE_CODE"]=$profileData['profile_code'];
    		    $_SESSION["SESS_PROFILE_DESIGNATION"]=$profileData['designation'];
    		    $_SESSION["SESS_USER_NAME"]=$profileData['full_name'];
    		    $_SESSION["SESS_PROFILE_TYPE"]=$profileData['type'];
    		    $_SESSION["SESS_PROFILE_SUBTYPE"]=$profileData['subtype'];
			}
			
			//Check Branch 
			if(!isset($_SESSION["SESS_BRANCH_NAME"]) || (time()-$_SESSION["COMP_NAME_DATE"])>300) {
			    $branchData=_db()->_selectQ("my_branches","*",["id"=>$_SESSION["SESS_BRANCH_ID"]])->_GET();
			    
			    if(count($branchData)>0) {
        		    $branchData=$branchData[0];
        		} elseif($_SESSION["SESS_BRANCH_ID"]==1) {
        		    //Create a branch
        		    $branchData=[
        		            "id"=>1,
        		            "guid"=>$_SESSION['SESS_GUID'],
                            "groupuid"=>$_SESSION['SESS_GROUP_NAME'],
                            "branch_code" => $_SESSION['COMP_COM_CODE']."/HQ",
                            "company_id" => $_SESSION['COMP_ID'],
                            "name" => $_SESSION['COMP_NAME']." - HQ",
                            "category" => "",
                            "type" => "HQ",
                            "mail" => $_SESSION['COMP_EMAIL'],
                            "landline" => $_SESSION['COMP_LANDLINE'],
                            "address" => $_SESSION['COMP_ADDRESS'],
                            "region" => $_SESSION['COMP_STATE_CODE'],
                            "country" => $_SESSION['COMP_COUNTRY'],
                            "zipcode" => $_SESSION['COMP_ZIPCODE'],
                            
                            
                            "privilegeid"=>$_SESSION['SESS_PRIVILEGE_ID'],
                            "access_level" => 1000,
                            
                            "created_by"=>$_SESSION['SESS_USER_ID'],
                            "edited_by"=>$_SESSION['SESS_USER_ID'],
                            "created_on"=>date("Y-m-d H:i:s"),
                            "edited_on"=>date("Y-m-d H:i:s"),

        		        ];
        		        
        		    $a=_db()->_insertQ1("my_branches",$branchData)->_RUN();
        		    
        		    if(!$a) {
        		        echo "<h1 align=center>Error Setting Up First Branch. Please contact admin.</h1>";
					          exit();
        		    }
        		    $branchData['id']=_db()->get_insertID();
        		} else {
        		    echo "<h1 align=center>Error, Selected branch does not exist. Please contact admin.</h1>";
				    		exit();
        		}
        		
        		$_SESSION["SESS_BRANCH_NAME"]=$branchData['name'];
        		$_SESSION["SESS_BRANCH_CODE"]=$branchData['branch_code'];
			}
		}
	}
	
	function checkGenericPermissions() {
		if(isset($_SESSION["ROLEMODEL"])) unset($_SESSION["ROLEMODEL"]);
		
		if($_SESSION['SESS_PRIVILEGE_ID']<=ADMIN_PRIVILEGE_RANGE) {
			return true;
		}
			
		if(defined("SERVICE_ROOT")) {
			return true;
		} else {
			$slug=_slug("pg/mod/type/subtype");
			if(in_array($slug['pg'],["favicon.ico"])) {
				return true;
			}
			if(in_array($slug['pg'],["welcome"])) {
				return false;
			}
			if(!in_array($slug['pg'],["modules","popup","favicon.ico"])) {
				return true;
			}
			if(in_array($slug['mod'],["myProfile","mySettings"])) {//"myAccounts",
				return true;
			}
			if(strlen($slug['type'])>0) {
                $xtype=current(explode(".",$slug['type']));
                if(in_array($xtype,["my"])) {
                  return true;
                }
            }
			
// 			printArray($slug);echo $_SESSION['SESS_GUID'];
			$access=false;
			$errorMsg="Current Module/resource/URI";
			if(strlen($slug['type'])>0) {
				$typeArr=explode(".",$slug['type']);
				if(in_array($typeArr[0],["my","me"])) {
					return true;
				}
				
				$roleStr=$slug['type'];
				$errorMsg="{$typeArr[0]}>{$roleStr} {$slug['mod']}";
				
				if(in_array($slug['mod'],["reports"])) {
					// $roleStr="Allow viewing - {$slug['type']}";
					$access=checkUserRoles($typeArr[0],$roleStr,"ACCESS");
				} elseif(in_array($slug['mod'],["infoview"])) {
					// $roleStr="Allow detailing - {$slug['type']}";
					$access=checkUserRoles($typeArr[0],$roleStr,"DETAILS");
				} elseif(in_array($slug['mod'],["forms"])) {
					if($slug['subtype']=="new" || $slug['subtype']=="create") {
					// 	$roleStr="Allow creating - {$slug['type']}";
						$access=checkUserRoles($typeArr[0],$roleStr,"NEW");
					} else {
					// 	$roleStr="Allow updating - {$slug['type']}";
						$access=checkUserRoles($typeArr[0],$roleStr,"EDIT");
					}
				} else {
					$module=explode(".",$slug['mod']);
					if(count($module)==1) {
						$roleStr="{$slug['mod']}.MAIN";
					} else {
						$roleStr=$slug['mod'];
					}
					$typeArr[0]=$module[0];
					
					if(in_array(strtolower($slug['type']),["new","edit","delete","update"])) {
						$errorMsg="{$typeArr[0]}>$roleStr>{$slug['type']}";
						$access=checkUserRoles($typeArr[0],$roleStr,strtoupper($slug['type']));
					} elseif(strlen($slug['subtype'])>0 && strlen($slug['subtype'])<20) {
						$errorMsg="{$typeArr[0]}>$roleStr>{$slug['subtype']}";
						$access=checkUserRoles($typeArr[0],$roleStr,strtoupper($slug['subtype']));
					} else {
						$errorMsg="{$typeArr[0]}>$roleStr>access";
						$access=checkUserRoles($typeArr[0],$roleStr,"ACCESS");
					}
				}
			} else {
				$module=explode(".",$slug['mod']);
				if(count($module)==1) {
					if(strlen($slug['type'])>0) {
						$roleStr=$slug['type'];
					} else {
						$roleStr="MAIN";
					}
				} else {
					$roleStr=$slug['mod'];
				}
				$errorMsg="{$module[0]}>$roleStr";
				$access=checkUserRoles($module[0],$roleStr,"ACCESS");
			}
// 			printArray([$slug]);
			if($access) {
				return true;
			} else {
				trigger_logikserror("Sorry, <strong>{$errorMsg}</strong> is not accessible to you. Contact Admin!",E_LOGIKS_ERROR,401);
			}
		}
	}
	
	setupDGEnviroment();
	updateBizEnviroment();
}
?>
