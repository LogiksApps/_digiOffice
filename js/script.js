$(function() {

  
});

function updateJobsList(uid) {
    //alert(uid);
    l=_service("list","jobs")+"&format=json";
    q="profile_id="+uid;
    $("#select[name=job_id]").html("<option value=''>Loading ...</option>");
    processAJAXPostQuery(l,q,function(txt) {
            json=$.parseJSON(txt);
            html="<option value=''>None</option>";
            $.each(json.Data,function(k,v) {
                    html+="<option value='"+v.jid+"'>"+v.title+" ("+v.job_year+")"+"</option>";
            });
            $("select[name=job_id]").html(html);
            $("select[name=task_id]").html("<option value=''>None</option>");
    });
}
function updateTaskList(jid) {
    //alert(jid);
        l=_service("list","tasks")+"&format=json";
        q="job_id="+jid;
        $("select[name=task_id]").html("<option value=''>Loading ...</option>");
        processAJAXPostQuery(l,q,function(txt) {
                json=$.parseJSON(txt);
                html="<option value=''>None</option>";
                $.each(json.Data,function(k,v) {
                        html+="<option value='"+v.tid+"'>"+v.task+" (#"+v.tid+", "+v.job+", "+v.job_year+")"+"</option>";
                });
                $("select[name=task_id]").html(html);
        });
}

function getInterviewDate(a,b){
   l=_service("test1","getInterviewDate")+"&format=json";
   q="resumeId="+a;
   processAJAXPostQuery(l , q , function(data){
       try {
            json=$.parseJSON(data);
			var str='' , str1= '';
			$.each(json.Data,function(k,v){
			     var date = new Date(v.interview_date);
			     date = ((date.getMonth() + 1) + '/' + date.getDate() + '/' +  date.getFullYear());
			     str1=date;
			});
			$('.field-interview_date').val(str1);
        } catch (e) {
            console.error("Error : while handling Data.");
        }
   });
}

/*----------Ended---------*/
/*
* @Author: Vijay Prajapati
* @Return   :
* @Description   : Convert the Lead Status
*/
function convertLead(){
   var id = $(".field-id").text();
   l=_service("leads","convertLead")+"&format=json";
   q="id="+id;
   processAJAXPostQuery(l , q , function(data){
        if(data.Data!=null && data.Data.msg!=null) {
            $(".modal,.modal-backdrop").detach();
            
            showLoader();
            lgksOverlayURL(_link("popup/forms/leads.lead_job/edit/"+md5(""+data.Data.refid)),"Edit Job",function(a) {
                hideLoader();
            });
            top.lgksToast(data.Data.msg);
        } else {
            top.lgksToast("Error occured");
        }
        
      return false;
   },"json");
}
/*
* @Author: Purva Naik
* @Return   :
* @Description   : Convert the Lead Status
*/
function convertLeadProject(){
   var id = $(".field-id").text();
   l=_service("leads","convertLeadProject")+"&format=json";
   q="id="+id;
   processAJAXPostQuery(l , q , function(data){
        if(data.Data!=null && data.Data.msg!=null) {
            $(".modal,.modal-backdrop").detach();
            
            showLoader();
            lgksOverlayURL(_link("popup/forms/leads.lead_project/edit/"+md5(""+data.Data.refid)),"Edit Project",function(a) {
                hideLoader();
            });
            top.lgksToast(data.Data.msg);
        } else {
            top.lgksToast("Error occured");
        }
      $(".modal,.modal-backdrop").detach();
      return false;
   },"json");
}
/*
* @Author: navnath@smartinfologiks.com
* @Return   :
* @Description   : close the Lead Status
*/
function closeLead(){
   var id = $(".field-id").text();
   l=_service("leads","closeLead")+"&format=json";
   q="id="+id;
   processAJAXPostQuery(l , q , function(data){
      top.lgksToast(data);
      $(".modal,.modal-backdrop").detach();
      return false;
   });
}

function getTaskName(a,b){
   l=_service("test1","getTaskName")+"&format=json";
   q="profileId="+a;

   processAJAXPostQuery(l , q , function(data){

       try {
            json=$.parseJSON(data);
            var str='<option selected=selected>Select Task</option>';
			$.each(json.Data,function(k,v){
			     str+="<option value='"+v.id+"'>"+v.name+"["+v.id+"]</option>";
			});

		    $('.field-task_id').html(str);
		    $('.field-task_id"').val(v);
        } catch (e) {
            console.error("Error : while handling Data.");
        }
   });
}
function accountBill(){
    showLoader();
    lgksOverlayURL(_link("popup/accountsBill/"),"Account Bill",function(a) {
        hideLoader();
    });
}

/*
* @Author: Rimpal Desai
* @Return   : 
* @Description   : accept warrenty claims
*/
function acceptWarranty(){
   var id = $(".field-id").text();
   var stockid = $(".field-inv_details_slno").text();
   if(stockid <1){top.lgksToast("Please Fill Serial No in Claim, then try again"); return false;}
   l=_service("warranty","acceptWarranty")+"&format=json";
   q="id="+id +"&stockid="+stockid;
   processAJAXPostQuery(l , q , function(data){
        if(data.Data!=null && data.Data.msg!=null) {
            $(".modal,.modal-backdrop").detach();
            if(data.Data.refid!=null && data.Data.refid!=''){
                showLoader();
                lgksOverlayURL(_link("popup/forms/warranty.accept_claim/edit/"+md5(""+data.Data.refid)),"Accept Claims",function(a) {
                    hideLoader();
                });
            }
            top.lgksToast(data.Data.msg);
        } else {
            top.lgksToast("Error occured");
        }
        
      return false;
   },"json");
}
/*
* @Author: Rimpal Desai
* @Return   : 
* @Description   : reject warrenty claims
*/
function rejectWarranty(){
   var id = $(".field-id").text();
   l=_service("warranty","rejectWarranty")+"&format=json";
   q="id="+id;
   processAJAXPostQuery(l , q , function(data){
        if(data.Data!=null && data.Data.msg!=null) {
            $(".modal,.modal-backdrop").detach();
            
            showLoader();
            lgksOverlayURL(_link("popup/forms/warranty.reject_claim/edit/"+md5(""+data.Data.refid)),"Reject Claims",function(a) {
                hideLoader();
            });
            top.lgksToast(data.Data.msg);
        } else {
            top.lgksToast("Error occured");
        }
        
      return false;
   },"json");
}
/*
* @Author: Rimpal Desai
* @Return   : 
* @Description   : Create job for accepted warrenty claims
*/
function createJOB(){
    var id = $(".field-id").text();
   l=_service("warranty","createJob")+"&format=json";
   q="id="+id;
   processAJAXPostQuery(l , q , function(data){
        if(data.Data!=null && data.Data.msg!=null) {
            $(".modal,.modal-backdrop").detach();
            if(data.Data.refid!=null && data.Data.refid!=''){
                showLoader();
                lgksOverlayURL(_link("popup/forms/warranty.claims_job/edit/"+md5(""+data.Data.refid)),"Edit Job",function(a) {
                    hideLoader();
                });
            }
            top.lgksToast(data.Data.msg);
        } else {
            top.lgksToast("Error occured");
        }
        
      return false;
   },"json");
}
/*
* @Author: Rimpal Desai
* @Return   : 
* @Description   : accept Accepted warrenty claims by technical person
*/
function accept_acceptedClaim(){
   var id = $(".field-id").text();
   l=_service("warranty","accept_acceptedClaim")+"&format=json";
   q="id="+id;
   processAJAXPostQuery(l , q , function(data){
        if(data.Data!=null && data.Data.msg!=null) {
            $(".modal,.modal-backdrop").detach();
            
            showLoader();
            lgksOverlayURL(_link("popup/forms/warranty.accept_acceptedClaim/edit/"+md5(""+data.Data.refid)),"Fast Forward",function(a) {
                hideLoader();
            });
            top.lgksToast(data.Data.msg);
        } else {
            top.lgksToast("Error occured");
        }
        
      return false;
   },"json");
}
/*
* @Author: Rimpal Desai
* @Return   : 
* @Description   : close Accepted warrenty claims by technical person
*/
function closeAcceptClaim(){
   var id = $(".field-id").text();
   l=_service("warranty","closeAcceptClaim")+"&format=json";
   q="id="+id;
   processAJAXPostQuery(l , q , function(data){
        if(data.Data!=null && data.Data.msg!=null) {
            $(".modal,.modal-backdrop").detach();
            
            if(data.Data.refid!=null && data.Data.refid!=''){
                showLoader();
                lgksOverlayURL(_link("popup/forms/warranty.acceptedClaimForClose/edit/"+md5(""+data.Data.refid)),"Close Claims",function(a) {
                    hideLoader();
                });
            }
            top.lgksToast(data.Data.msg);
        } else {
            top.lgksToast("Error occured");
        }
        
      return false;
   },"json");
}
/*
* @Author: Rimpal Desai
* @Return   : 
* @Description   : mass Accept auto rejected warrenty claims
*/
function massAccepClaims() {
    var idarray=[];
    $(".tableBody input[name=rowSelector]:checked",grid).each(function() {
        tr=$(this).closest("tr");
        warrantyid=tr.find("td.warranty_claims_id").text().trim();
        idarray.push(warrantyid);
    });
    if(idarray.length>0)
    {
        if(idarray.length == 1)
        {
            var stockid = tr.find("td.warranty_claims_inv_details_slno").text().trim();
            if(stockid <1){top.lgksToast("Please Fill Serial No in Claim, then try again"); return false;}
            l=_service("warranty","acceptWarranty")+"&format=json";
            q="id="+idarray +"&stockid="+stockid;
            processAJAXPostQuery(l , q , function(data){
                if(data.Data!=null && data.Data.msg!=null) {
                    if(data.Data.refid!=null && data.Data.refid!=''){
                        lgksOverlayURL(_link("popup/forms/warranty.accept_claim/edit/"+md5(""+data.Data.refid)),"Accept Claims",function(a) {
                        });
                    }
                    top.lgksToast(data.Data.msg);
                } else {
                    top.lgksToast("Error occured");
                }
           },"json");
        }else{
            var q = "warrantyIds="+idarray;
            // console.log(q);
                var l = _service('warranty','massAccepClaims')+"&format=json";
                processAJAXPostQuery(l , q , function(data){
                if(data.Data!=null && data.Data.msg!=null) {
                    lgksAlert(data.Data.msg);
                    rpt.reloadDataGrid();
                    // top.lgksToast(data.Data.msg);
                } else {
                    lgksAlert("Error occured");
                    // top.lgksToast("Error occured");
                }
           },"json");
        }   
    }else{
        lgksAlert("Please select Claims to Accept");
        // top.lgksToast("Please check Claims to Accept");
    }
}
/*
* @Author: Rimpal Desai
* @Return   : 
* @Description   : mass Reject auto rejected warrenty claims
*/
function massRejectClaims() {
    var idarray=[];
    $(".tableBody input[name=rowSelector]:checked",grid).each(function() {
        tr=$(this).closest("tr");
        warrantyid=tr.find("td.warranty_claims_id").text().trim();
        idarray.push(warrantyid);
    });
    if(idarray.length>0)
    {
        if(idarray.length == 1)
        {
            l=_service("warranty","rejectWarranty")+"&format=json";
            q="id="+idarray;
            processAJAXPostQuery(l , q , function(data){
            if(data.Data!=null && data.Data.msg!=null) {
                lgksOverlayURL(_link("popup/forms/warranty.reject_claim/edit/"+md5(""+data.Data.refid)),"Reject Claims",function(a) {
                });
                top.lgksToast(data.Data.msg);
            } else {
                top.lgksToast("Error occured");
            }
   },"json");
        }else{
            var q = "warrantyIds="+idarray;
            // console.log(q);
                var l = _service('warranty','massRejectClaims')+"&format=json";
                processAJAXPostQuery(l , q , function(data){
                if(data.Data!=null && data.Data.msg!=null) {
                    lgksAlert(data.Data.msg);
                    rpt.reloadDataGrid();
                    // top.lgksToast(data.Data.msg);
                } else {
                    lgksAlert("Error occured");
                    // top.lgksToast("Error occured");
                }
           },"json");
        }
    }else{
        lgksAlert("Please check Claims to Reject");
        // top.lgksToast("Please check Claims to Accept");
    }
     
}
function preSettlementClaim(){
   var id = $(".field-id").text();
   $(".modal,.modal-backdrop").detach();
    showLoader();
    lgksOverlayURL(_link("popup/forms/warranty.accept_acceptedClaim/edit/"+md5(""+id)),"Pre Settlement Claims",function(a) {
        hideLoader();
    });
}