<form class="form-horizontal" name="MySuppInfoFormContact" id="MySuppInfoFormContact" method="post" action="custom_products/custom_products/submit_contact_form">
	<h4><%lang('LBL_DISTRIBUTOR_PANEL_CUSTOM_PRODUCTS_CONTACT_SUPPLIER_FOR_CONTACT_SUPPLIER')%></h4>
	<hr>
	<div class="section">
	<input class="form-control" name="distributor_id" id="distributor_id" value="<%$this->session->userdata('iAdminId')%>" type="hidden">
	<input class="form-control" name="provider_id" id="provider_id" value="<%$dataMap[0]['iProviderId']%>" type="hidden">
	<input class="form-control" name="contact_name" id="contact_name" value="<%$dataMap[0]['vNameProviderContact']%>" type="hidden">
		<div class="form-horizontal labels-left">
			<div class="tab-row">
				<div class="col-sm-5">
					<div class="form-group">
						<label class="col-sm-2"><span class="required">*</span>Para:</label>
						<div class="col-sm-10">
							<%if $dataMap[0]['vEmailProviderContact'] neq ''%>
							
							<input class="form-control" name="provider_email" id="provider_email" value="<%$dataMap[0]['vEmailProviderContact']%>" type="text" readonly>
							<%else%>
							
							<input class="form-control" name="provider_email" id="provider_email" value="<%$provider_details['u_alternate_email']%>" type="text" readonly>
							<%/if%>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2"><span class="required">*</span><%lang('LBL_DISTRIBUTOR_PANEL_CUSTOM_PRODUCTS_CONTACT_SUPPLIER_FORM_NAME')%>:</label>
						<div class="col-sm-10">
							<input class="form-control" name="name" id="name" value="<%$this->session->userdata('vName')%> <%$this->session->userdata('vLastName')%>" type="text">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2"><span class="required">*</span><%lang('LBL_DISTRIBUTOR_PANEL_CUSTOM_PRODUCTS_CONTACT_SUPPLIER_FORM_SUBJECT')%>:</label>
						<div class="col-sm-10">
							<input class="form-control" name="subject" id="subject" value="" type="text">
						</div>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="form-group">
						<label class="col-sm-4"><span class="required">*</span><%lang('LBL_DISTRIBUTOR_PANEL_CUSTOM_PRODUCTS_CONTACT_SUPPLIER_FORM_CONTACT_NO')%>:</label>
						<div class="col-sm-8">
							<input class="form-control" name="contact_no" id="contact_no" value="<%$this->session->userdata('vContactNumber')%>" type="text">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="section">
		<label class="col-sm-4"><%lang('LBL_DISTRIBUTOR_PANEL_CUSTOM_PRODUCTS_CONTACT_SUPPLIER_FORM_MY_NOTE')%>:</label>
		<div class="col-sm-12">
		  <textarea class="form-control" rows="8" name="note" id="note"></textarea>
	 </div>
	</div>
	<div class="section">
	 <button type="button" id="btn_submit_contact_form" class="btn btn-info btn-lg custom-sub-btn"><%lang('LBL_DISTRIBUTOR_PANEL_CUSTOM_PRODUCTS_CONTACT_SUPPLIER_FORM_BTN_SUBMIT')%></button>
	</div>
</form>
<style type="text/css">
	.required{color:red;}
</style>

<script type="text/javascript">
function myFunction() {
  var x = document.getElementById("provider_email").readOnly;
  document.getElementById("demo").innerHTML = x;
} 
</script>

<%javascript%>
	$("#btn_submit_contact_form").off().on("click",function(){
		setContactValidate();
		submit_contact_inquiry();
	});
	function submit_contact_inquiry(){
		var vld = $('#MySuppInfoFormContact').valid();
        if (!vld) {
            return false;
        } else {
            //$('#MySuppInfoFormContact').submit();
			$.ajax({
		            type:'POST',
		            url: $('#MySuppInfoFormContact').attr("action"),
		            data:$('#MySuppInfoFormContact').serialize(),
		            dataType:'json',
		            success:function(data){
					console.log('Peticion Data', data)
		                if(data.success)
		                {
							alert(data.message)
		                }
		                else
		                {
		                     console.log(data);
		                }
		            },
		            error: function(data){
		                console.log(data);
		            }
		        });
        }
	}
	function setContactValidate(){
	var cont_frm_handler = $('#MySuppInfoFormContact');
	console.log(cont_frm_handler);
        $('#MySuppInfoFormContact').validate({
            rules: {
                'name' : {
                    required :true
                },
                'subject' : {
                    required :true
                },
                'email' : {
                    required :true,
                    email: true
                },
                'contact_no' : {
                    required :true,
                    digits: true,
                }
            },
            messages: {
                'name' : {
                    required : js_lang_label.LBL_CONTACT_PROVIDER_FROM_DISTRIBUTOR_FORM_PLEASE_ENTER_NAME,
                },
                'subject' : {
                    required : js_lang_label.LBL_CONTACT_PROVIDER_FROM_DISTRIBUTOR_FORM_PLEASE_ENTER_COMPANY_NAME,
                },
                'email' : {
                    required : js_lang_label.LBL_CONTACT_PROVIDER_FROM_DISTRIBUTOR_FORM_PLEASE_ENTER_EMAIL,
                    email: js_lang_label.LBL_CONTACT_PROVIDER_FROM_DISTRIBUTOR_FORM_PLEASE_ENTER_VALID_EMAIL
                },
                'contact_no' : {
                    required :js_lang_label.LBL_CONTACT_PROVIDER_FROM_DISTRIBUTOR_FORM_PLEASE_ENTER_MOBILE_NUMBER,
                    digits: js_lang_label.LBL_CONTACT_PROVIDER_FROM_DISTRIBUTOR_FORM_PLEASE_ENTER_ONLY_DIGITS
                }
            },
            errorPlacement: function(error, element) {
                var name = element.attr('name');
                $(error).css({'color':'#d9534f'});
                error.insertAfter($('[name="'+name+'"]'));
            },
            submitHandler:function(form){
            	Project.show_adaxloading_div('#gmf_autocomplete_p_location');
                $.ajax({
		            type:'POST',
		            url: cont_frm_handler.attr("action"),
		            data:cont_frm_handler.serialize(),
		            dataType:'json',
		            success:function(data){
		                if(data.success)
		                {
		                	Project.hide_adaxloading_div('#gmf_autocomplete_p_location');
		                	Project.setMessage("¡Mensaje enviado al proveedor!",1);
		                	$("#subject").val('');
		                	$("#note").val('');
		                    //window.location.reload();
		                    //window.location.replace(admin_url+"#product/distributor_products/index");
		                }
		                else
		                {
		                     console.log(data);
		                }
		            },
		            error: function(data){
		                console.log(data);
		            }
		        });
            }
        });
	}
<%/javascript%>