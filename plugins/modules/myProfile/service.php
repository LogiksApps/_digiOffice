<?php
if (!defined('ROOT')) exit('No direct script access allowed');
/**
 * author : snehalata.mane@smartinfologiks.com
 * 
 * */
if(isset($_REQUEST['action'])) {
    switch($_REQUEST['action']) {
      case 'updatePwd':
				$result= updatePwd();
    		printServiceMsg($result);
			break; 
    }
}


/**
 * Used to update password from users profile after login
 * @param 
 * @return
 */
 
 function updatePwd(){
     
    if(isset($_POST)){
        if(!empty($_POST['old']) && !empty($_POST['new']) && !empty($_POST['conf_pwd'])){
            loadHelpers("pwdhash");
            $old=trim($_POST['old']);
            $new=trim($_POST['new']);
            $conf_pwd=trim($_POST['conf_pwd']);
            $res=_db(true)->_selectQ(_dbTable("users", true),'pwd,pwd_salt',['userid'=>$_SESSION['SESS_USER_ID']])->_get();
            
            if(count($res)>0){
                $res_pwd = $res[0]['pwd'];
                $pwd_salt=$res[0]['pwd_salt'];
                 
                $old=trim($_POST['old']);
                $new=trim($_POST['new']);
                $conf_pwd=trim($_POST['conf_pwd']);
                
                $oldPWD = getPWDHash($old,$pwd_salt);
                if(is_array($oldPWD)) {
                    $old_pwdSalt=$oldPWD['salt'];
                    $old_pwdHash=$oldPWD['hash'];
                } else {
                    $old_pwdSalt="";
                    $old_pwdHash=$oldPWD;
                }
                
                if($old_pwdHash==$res_pwd){
                    
                    if($new==$conf_pwd){
                        $pwd=$_POST['new'];
                        if(isset($_POST['conf_pwd'])) unset($_POST['conf_pwd']);
                        
                        $result=updatePassword($pwd,$_SESSION['SESS_USER_ID'],SITENAME);
                        if($result){
                            $msg="Password changed successfully.";
                            return $msg;
                        }else{
                            
                            $msg=$result['error'];
                            return $msg;
                        }
                    }else{
                        $msg="Password and confirm password, dont match.";
                        return $msg;
                    }
                }else{
                    $msg="Old password doesn't match. Please use correct credentials.";
                    return $msg;
                }
            }else{
                $msg="User does not present in the system.";
                return $msg;
            }
        }else{
            $msg="Old password, new password and confirm password cannot be blank.";
            return $msg;
        }
    }
}

?>