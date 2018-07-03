<?php
if(!defined('ROOT')) exit('No direct script access allowed');

$user=getUserInfo($_SESSION['SESS_USER_ID'],true);
 //printArray($user);

// $data=_db(true)->_selectQ(_dbTable("users",true),"*")->_where(array(
// 				"blocked"=>'false',
// 				"userid"=>$_SESSION['SESS_USER_ID'],
// 			))->_GET();
// printArray($data);

loadModuleLib("forms","api");
echo _js("forms");
echo _css("forms");
?>
<div class="col-xs-12">
	<div class="row">
		<div class="col-sm-12">
			<h1 class="pull-left">
				<i class="fa fa-user"></i>
				<span>My Profile</span>
			</h1>
			<button class='btn btn-sm btn-primary pull-right' style='margin-top: 27px;' data-toggle="modal" data-target="#passwordModal">Reset Password</button>
		</div>
	</div>
	<br/>
	<div class="row">
		<div class="col-sm-2 col-lg-2">
          <div class="box">
            <div class="box-content img-content">
              <img class="img-responsive" src="<?=$user['avatarlink']?>">
            </div>
          </div>
        </div>
        <div class="col-sm-10 col-lg-10">
        	<?php
						unset($user['avatarlink']);
						$formConfig=findForm(__DIR__."/form.json");
					  $formConfig['data']=$user;
					  
        		printForm('update',$formConfig,true,["userid"=>$_SESSION['SESS_USER_ID']]);
        	?>
        </div>
    </div>
</div>
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formUpdatePwd" method="post" action="/">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="form-control-label">Old Password<span class="span-required">*</span></label>
                                <input type="password" name="old" class="form-control" Placeholder="Enter Your Old Password" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="form-control-label">New Password<span class="span-required">*</span></label>
                                <input type="password" name="new" id="new" class="form-control" Placeholder="Enter Your New Password" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="form-control-label">Re-enter Password<span class="span-required">*</span></label>
                                <input type="password" name="conf_pwd" id="conf_pwd" class="form-control" Placeholder="Confirm  Password" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-danger"  data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
echo _js("myProfile");
?>
<script>
$(function(){
	$("#formUpdatePwd").submit(function(e){
			e.preventDefault();
			lx=_service("myProfile","updatePwd")+"&format=text";
			q=$("#formUpdatePwd").serialize();

			processAJAXPostQuery(lx,q,function(data){
					lgksAlert(data);
					$("#formUpdatePwd")[0].reset();
					$('#passwordModal').modal('toggle');

			});
	});
});
</script>