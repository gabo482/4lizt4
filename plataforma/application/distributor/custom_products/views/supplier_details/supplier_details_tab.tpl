<style>
    .btn-contact{
        background-color: #3ec6f0;
        border-radius: 20px;
        color: white;
        padding: 5px 25px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }
    .fa-plus{
        color: white;
    }
</style>


<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%$this->css->add_css("custom/distributorprovider.css")%>
<%if !isset($product_details) %>
<div class="tab-row row_3 col-sm-6">
    <div class="col-sm-12 detail_view_blocks" style="height:470px;">
        <h4>Configuración del Proveedor</h4>
            <form id="myForm" method="post">
            <%foreach $dataMap key=key item=item %>
                <input type="hidden" name="id" id="id" value="<%$item['iDistributorProviderMappingId']%>">
                <input type="hidden" name="dpm_Provider_Type" id="dpm_Provider_Type" value="<%$item['eProviderType']%>">
                <input type="hidden" name="dpm_modified_date" id="dpm_modified_date" value="<%$item['dModifiedDate']%>"  class='ignore-valid '  aria-date-format='yy-mm-dd'  aria-time-format='HH:mm:ss'  aria-format-type='datetime' >
                <input type="hidden" name="dpm_provider_id" id="dpm_provider_id" value="<%$item['iProviderId']%>">
                <input type="hidden" name="dpm_unique_id" id="dpm_unique_id" value="<%$item['vUniqueID']%>">
                <input type="hidden" name="dpm_mod" id="dpm_mod" value="<%$item['mod']%>"  >
            <%/foreach%>
                <div class="form-row row-fluid" id="cc_sh_company_name_default">
                    <div id="Alert"></div>
                    <label class="form-label span4">
                    <%foreach $dataMap key=key item=item %>
                    <%if $item['eProviderType'] eq "Default"%>
                        Nombre Comercial Proveedor
                    <%else%>
                        Razón Social <em>*</em> 
                    <%/if%>
                    <%/foreach%>
                    </label> 
                    <div class="form-right-div span8">
                    <%foreach $dataMap key=key item=item %>
                    <%if $item['eProviderType'] == "Default"%>
                        <strong> <%$item['vLegalCompanyName']%> </strong>
                        <input type="hidden" value="<%$item['vLegalCompanyName']%>" name="company_name" id="company_name"> 
                    <%else%>
                        <input type="text" placeholder="Escribe la Razón Social del Proveedor" value="<%$item['vCompanyName']%>" name="company_name" id="company_name" title="Razón Social" class="frm-size-medium" required> 
                    <%/if%>
                    <%/foreach%>
                    </div>
                    <div class="error-msg-form "><label class="error" id="company_name_defaultErr"></label></div>
                </div>
                <div class="form-row row-fluid" id="cc_sh_dpm_provider_discount">
                    <label class="form-label span4">
                        Descuento del proveedor
                    </label> 
                    <div class="form-right-div span8"> 
                        <div class="form-right-div  input-append text-append-prepend">
                             <input style=";width: 60% !important;" type="text" placeholder="" <%if $this->session->userdata('iUsersId') != $this->session->userdata('iAdminId')%>readonly<%/if%> value="<%$item['fProviderDiscount']%>" name="dpm_provider_discount" id="dpm_provider_discount" title="Descuento del proveedor" class="frm-size-medium">
                            <span class="add-on text-addon" style="height: 28px; line-height: 28px;">%</span>
                        </div> 
                    </div>
                    <div class="error-msg-form "><label class="error" id="dpm_provider_discountErr"></label></div>
                </div>
                <div class="form-row row-fluid" id="cc_sh_dpm_provider_profit">
                    <label class="form-label span4">
                        Utilidad del proveedor
                    </label> 
                    <div class="form-right-div span8">
                        <div class="form-right-div  input-append text-append-prepend "><input type="text" <%if $distr_detail[0]['vDistibutorProfitPreference'] == "Default" || $this->session->userdata('iUsersId') != $this->session->userdata('iAdminId')%> readonly="" <%/if%> placeholder="Entrar en beneficio" value="<%$item['fProviderProfit']%>" name="dpm_provider_profit" id="dpm_provider_profit" title="Provider Profit" class="frm-size-medium ctrl-append-prepend" style="undefined; width: 76% !important;"><span class="add-on text-addon">%</span></div>
                    </div>
                    <div class="error-msg-form "><label class="error" id="dpm_provider_profitErr"></label></div>
                </div>
                <div class="form-row row-fluid" id="cc_sh_dpm_seq_no">
                    <label class="form-label span4">
                        Orden de visualización <em>*</em> 
                    </label> 
                    <div class="form-right-div span8">
                        <input type="text" placeholder="Escriba el orden de visualización" value="<%$item['iSeqNo']%>" style="width: 57% !important;" name="dpm_seq_no" id="dpm_seq_no" title="Orden de visualización" class="frm-size-medium" required>
                    </div>
                    <div class="error-msg-form "><label class="error" id="dpm_seq_noErr"></label></div>
                </div>
                <input value="Guardar" name="ctrlupdate" type="submit" id="frmbtn_update" class="btn btn-info btn-lg custom-sub-btn">
            </form>
    </div>
</div>
<%/if%>
<!-- PROVIDER CONTACTS -->
<div class="tab-row row_4 col-sm-6">
    <div class="col-sm-12 detail_view_blocks provider-contact-blocks">
        <h4>
            <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_PROVIDER_CONTACTS')%>
                <a href="javascript://" class='btn btn-primary' id="addnewcontact"><i class="fa fa-plus"></i> <%lang('LBL_BTN_ADD_CONTACT')%></a>
        </h4>
        <table class="table table-striped" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody id='supplier_contact_container'>
                <%include file='supplier_details/supplier_contacts.tpl'%>
            </tbody>
        </table>
    </div>
</div>
<!-- PROVIDER CONTACTS -->    
<div class="tab-row row_3 col-sm-6" style="margin-left:-15px;">
    <div class="col-sm-12 detail_view_blocks">
    <h4>
        <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_COMPANY_DATA')%>
    <a href="<%$this->config->item('admin_url')%>#user/distributor_provider_contact/index|iUserId|<%$distributor_id%>|" class='btn btn-primary' id="addnewcontact" style="float:right;margin-top:-9px;"><i class="fa fa-eye"></i> <%lang('LBL_BTN_SEE_CONTACT_PROVIDER')%></a>  
    </h4>
    <table class="table table-striped" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
        <tr>
            <td class="table-title">
                <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_LEGAL_COMPANY_NAME')%>
            </td>
            <td>
            <%if $provider_details['u_legal_company_name'] neq ''%>
                <%$provider_details['u_legal_company_name']%>
            <%else%>
                --
            <%/if%>
            </td>
        </tr>
        <tr>
            <td class="table-title">
                <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_SUPPLIER_DETAILS_COMPANY')%>
            </td>
            <td>
            <%if $provider_details['u_company_name'] neq ''%>
                <%$provider_details['u_company_name']%>
            <%else%>
                --
            <%/if%>
            </td>
        </tr>
        <tr>
            <td class="table-title">
                <%lang('LBL_PROVIDER_PANEL_CUSTOM_TELEFONO')%>
            </td>
            <td>
            <%if $live_package_code|lower eq 'free'%>
                <a href="<%$this->config->item('admin_url')%>#distributor/subscriptions/index">Actualiza para ver</a>
            <%else%>
            <%if $provider_details['u_main_contact'] neq ''%>
                <%$provider_details['u_main_contact']%>
            <%else%>
                --
            <%/if%>
            <%/if%>
            </td>
        </tr>
        <tr>
            <td class="table-title">
                <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_SUPPLIER_DETAILS_EMAIL')%>
            </td>
            <td>
            <%if $live_package_code|lower eq 'free'%>
                <a href="<%$this->config->item('admin_url')%>#distributor/subscriptions/index">Actualiza para ver</a>
            <%else%>
            <%if $provider_details['u_alternate_email'] neq ''%>
                <%$provider_details['u_alternate_email']%>
            <%else%>
                --
            <%/if%>
            <%/if%>
            </td>
        </tr>
        <tr>
            <td class="table-title">
                <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_SUPPLIER_DETAILS_WEBSITE')%>
            </td>
            <td>
            <%if $live_package_code|lower eq 'free'%>
                <a href="<%$this->config->item('admin_url')%>#distributor/subscriptions/index">Actualiza para ver</a>
            <%else%>
            <%if $provider_details['u_company_website'] neq ''%>
                <a href="http://<%str_replace('https://','',str_replace('http://','',$provider_details['u_company_website']))%>" target="_blank">
                    <%$provider_details['u_company_website']%>
                </a>
            <%else%>
                --
            <%/if%>
            <%/if%>
            </td>
        </tr>
        </tbody>
    </table>
    </div>
</div>
<div class="col-sm-6 detail_view_blocks">
    <h4>
        <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_OTHER_DETAILS')%>
    </h4>
    <table class="table table-striped" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
            <tr>
                <td class="table-title">
                    <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_ALYZTA_USER_NO')%>
                </td>
                <td>
                <%if $provider_details['u_unique_id'] neq ''%>
                    <%$provider_details['u_unique_id']%>
                <%else%>
                    --
                <%/if%>
                </td>
            </tr>
            <tr>
                <td class="table-title">
                    <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_RFC_DOC')%>
                </td>
                <td>
                <%if $live_package_code|lower eq 'free'%>
                    <a href="<%$this->config->item('admin_url')%>#distributor/subscriptions/index">Actualiza para ver</a>
                <%else%>
                <%if $provider_details['u_r_fctax_id'] neq ''%>
                    <%$provider_details['u_r_fctax_id']%>
                <%else%>
                    --
                <%/if%>
                <%/if%>
                </td>
            </tr>
            <tr>
                <td class="table-title">
                    <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_SUPPLIER_DETAILS_SUPPLIER_TYPE')%>
                </td>
                <td>
                <%if $provider_details['type_of_provider'] neq ''%>
                    <%$provider_details['type_of_provider']%>
                <%else%>
                    --
                <%/if%>
                </td>
            </tr>
            <%assign var="type_provider" value=","|explode:$provider_details['type_of_provider']%>
            <%if ' Otro: Especifique'|in_array:$type_provider%> 
            <tr>
                <td class="table-title">
                    <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_SUPPLIER_DETAILS_OTHER_TYPE')%>
                </td>
                <td>
                    <%$provider_details['ups_other_type_of_provider']%>            
                </td>
            </tr>
            <%/if%>
            <tr>
                <td class="table-title">
                    <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_SUPPLIER_DESCRIPTION')%>
                </td>
                <td>
                <%if $provider_details['ups_offer_desc_for_distributor_1'] neq ''%>
                    <%$provider_details['ups_offer_desc_for_distributor_1']%>
                <%else%>
                    --
                <%/if%>
                </td>
            </tr>
            <tr>
                <td class="table-title">
                    <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_SUPPLIER_DETAILS_OFFER_PRINTING')%>
                </td>
                <td>
                <%if $provider_details['ups_offer_printing_1'] neq ''%>
                    <%$provider_details['ups_offer_printing_1']%>
                <%else%>
                    --
                <%/if%>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="col-sm-6 detail_view_blocks" style="margin-left:25px;">
    <h4>
        <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_ADDRESS_DETAILS_TAB_HEADING')%>
    </h4>
    <table class="table table-striped" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
        <tr>
            <td>
                <span class='table-title'><%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_SUPPLIER_DETAILS_SUPPLIER_ADDRESS')%></span>
                <br>
                <span class='address-style'>
                <%if $live_package_code|lower eq 'free'%>
                    <a href="<%$this->config->item('admin_url')%>#distributor/subscriptions/index">Actualiza para ver</a>
                <%else%>
                <%if $provider_details['u_street_name'] neq '' && $provider_details['u_colony_name'] neq '' &&  $provider_details['u_zip_code'] neq '' && $provider_details['u_city'] neq '' && $provider_details['ms_state'] neq '' && $provider_details['mc_country'] neq ''%>
                    <%$provider_details['u_street_name']%>
                    <br>
                    <%$provider_details['u_colony_name']%>, <%$provider_details['u_zip_code']%>
                    <br>
                    <%$provider_details['u_city']%>, <%$provider_details['ms_state']%><%if $provider_details['mc_country'] neq ''%>, <%$provider_details['mc_country']%><%/if%>
                    <br>
                    <span style="color:#000;"><%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_SUPPLIER_DETAILS_NUM_INTERIOR')|cat:":"%></span>
                <%if $provider_details['u_compamy_contact_number'] neq ''%><%$provider_details['u_compamy_contact_number']%>, <%/if%><span style="color:#000;"><%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_SUPPLIER_DETAILS_NUM_EXTERIOR')|cat:":"%></span><%if $provider_details['u_compamy_contact_number_ext'] neq ''%><%$provider_details['u_compamy_contact_number_ext']%><%/if%>
                <%else%>
                    --
                <%/if%>
                <%/if%>                    
                </span>
            </td>
        </tr>
        <tr>
            <td>
                <span class='table-title'><%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_SUPPLIER_DETAILS_FISCAL_ADDRESS')%></span>
                <br>
                <span class='address-style'>
                <%if $live_package_code|lower eq 'free'%>
                    <a href="<%$this->config->item('admin_url')%>#distributor/subscriptions/index">Actualiza para ver</a>
                <%else%>
                <%if $provider_details['u_fiscal_street_name'] neq '' && $provider_details['u_fiscal_colony_name'] neq '' &&  $provider_details['u_fiscal_zip_code'] neq '' && $provider_details['u_fiscal_city'] neq '' && $provider_details['fiscal_state'] neq ''%>
                    <%$provider_details['u_fiscal_street_name']%>
                    <br>
                    <%$provider_details['u_fiscal_colony_name']%>, <%$provider_details['u_fiscal_zip_code']%>
                    <br>
                    <%$provider_details['u_fiscal_city']%>, <%$provider_details['fiscal_state']%><%if $provider_details['fiscal_country'] neq ''%>, <%$provider_details['fiscal_country']%><%/if%>
                    <br>
                    <span style="color:#000;">
                        <%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_SUPPLIER_DETAILS_NUM_INTERIOR')|cat:":"%>
                    </span>
                    <%if $provider_details['u_fiscal_contact_number'] neq ''%><%$provider_details['u_fiscal_contact_number']%>, <%/if%><span style="color:#000;"><%lang('LBL_PROVIDER_PANEL_CUSTOM_PRODUCTS_SUPPLIER_DETAILS_NUM_EXTERIOR')|cat:":"%></span><%if $provider_details['u_fiscal_contact_number_ext'] neq ''%><%$provider_details['u_fiscal_contact_number_ext']%><%/if%>
                <%else%>
                    --
                <%/if%>
                <%/if%>
                </span>
            </td>
        </tr>
        </tbody>
    </table>
</div>


<script>
    $("#dpm_provider_profit").on('change', function () {
        if(parseInt($(this).val()) < 1){
            alert("¡La utilidad mínima es de 1%!");
            $(this).val("1.00");
        } else {
            var num = parseFloat($(this).val());
            var n = num.toFixed(2);
            var perc = n*20/100;
            $(this).val(n);
            $("#muestraUtilidad").html(n+"%");
            $("#resultUtilidad").html("("+perc+")");
            $("#totalUtil").html("$"+(perc+20));
        }
    });
$(document).ready(function() {
    $( document ).tooltip(); 
});
$('#myForm').submit( processForm );
function processForm( e ){
    $.post("<%$this->config->item('admin_url')%>custom_products/update_pro",{
            iDistributorProviderMappingId : $('input[name=id]').val(),
            Name : $('input[name=company_name]').val(),
            vProviderName : $('input[name=dpm_provider_name]').val(),
            vUniqueID : $('input[name=dpm_unique_id]').val(),
            fProviderDiscount : $('input[name=dpm_provider_discount]').val(),
            fProviderProfit : $('input[name=dpm_provider_profit]').val(),
            iSeqNo : $('input[name=dpm_seq_no]').val(),
            eProviderType : $('input[name=dpm_Provider_Type]').val(),
            iProviderId : $('input[name=dpm_provider_id]').val(),
            mod:$('input[name=dpm_mod]').val(),
            iDistributorId:'<%$distributor_id%>',
            
    },
    function(){
            $('#Alert').append("<div class='alert alert-success' id='success-alert'><button type='button' class='close' data-dismiss='alert'>x</button>¡Proveedor actualizado correctamente!</div>");
            $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
                $("#success-alert").slideUp(500);
                $('#Alert').empty();
                setTimeout(function(){
                    location.reload(); 
                    },1000);
                
            });
        }
    );
    
    e.preventDefault();
}
</script>
<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
