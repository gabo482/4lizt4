<form class="form-horizontal" name="MySuppInfoFormContact" id="MySuppInfoFormContact" method="post" action="custom_products/custom_products/submit_contact_form">
	<h4><%lang('LBL_DISTRIBUTOR_PANEL_CUSTOM_PRODUCTS_CONTACT_SUPPLIER_FOR_CONTACT_SUPPLIER')%></h4>
	<div class="section">
		<div class="form-horizontal labels-left">
			<div class="tab-row">
				<div class="col-sm-8">				
					<div class="form-group">
						<label class="col-sm-2"><span class="required">*</span>Para:</label>
						<div class="col-sm-10">					
							<select name="provider_email" id="provider_email" class="chosen-select" >
										<%if !isset($product_details) %>
											<%if $provider_details['u_alternate_email'] neq ''%>
													<option selected value="<%$provider_details['u_alternate_email']%>"><%$provider_details['u_alternate_email']%>&#62;</option>
											<%/if%>
										<%/if%>
										<%if count($provider_contact_details) gt 0%>
													<%foreach from=$provider_contact_details item=row%>
														<option selected value="<%$row['pcm_provider_contacts_id']%>"><%$row['pcm_name']%>&#60;<%$row['pcm_email']%>&#62;</option>
														 
													<%/foreach%>
													<%else%>
													<option value="none" selected disabled hidden> No hay contactos registrados
										 <%/if%>													
							</select>
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
		
		
		
		
		
	</div>
	
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
            $('#MySuppInfoFormContact').submit();
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
					console.log('test', data)
		                if(data.success)
		                {
		                	Project.hide_adaxloading_div('#gmf_autocomplete_p_location');
		                	Project.setMessage("¡Mensaje enviado correctamente!",1);
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