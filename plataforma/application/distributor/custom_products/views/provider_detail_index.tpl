<div class="headingfix">
    <div class="heading" id="top_heading_fix" style="width: 1349px;"> 
        <h3 style="padding-left: 221px; width: 1044.72px;">
            <div class="screen-title inner-pages-title"><%lang('LBL_GENERAL_CUSTOM_PRODUCTS_PROVIDER_DETAIL_VIEW')%></div>
        </h3>
        <div class="header-right-btns"> 
            <div class="frm-back-to"> <a hijacked="yes" href="<%$this->config->item('admin_url')%>#user/distributor_provider_listing_v1/index" class="backlisting-link" title="Back To Setting New Listing">
            <span class="icon16 minia-icon-arrow-left"></span> </a> </div>
            <div class="clear"></div>
        </div>
        <span style="display:none;position:inherit;" id="ajax_lang_loader"><i class="fa fa-refresh fa-spin-light fa-2x fa-fw"></i></span> </div>
</div>
<div class="mytab-admin">
    <div class="mytab-admin-inner">
        <ul class="nav nav-tabs caustom-nav-tabs" id="myTab2">
            <li class="<%if $vista eq 'catalogo' %>active<%/if%>" id="main"><a href=""><%lang('LBL_DISTRIBUTOR_PANEL_CUSTOM_PRODUCTS_SUPPLIER_INFO_TAB')%></a></li>
            <li id="products"><a href=""><%lang('LBL_DISTRIBUTOR_PANEL_CUSTOM_PRODUCTS_PRODUCTS_TAB')%></a></li>
            <li id="pilicy"><a href=""><%lang('LBL_DISTRIBUTOR_PANEL_CUSTOM_PRODUCTS_SUPPLIER_POLICY_TAB')%></a></li>
            <li class="<%if $vista eq 'propios' %>active<%/if%>" id="my-supplier-info"><a href=""> <%lang('LBL_DISTRIBUTOR_PANEL_CUSTOM_PRODUCTS_CONTACT_SUPPLIER')%></a></li>            
        </ul>
        <div class="tab-content">        
            <div class="tab-pane " id="main-tab-custom"><%include file="supplier_details/supplier_details_tab.tpl"%></div>
             <div class="tab-pane " id="products-tab" ><%include file="product_details/provider_details_product_view.tpl"%></div>
            <div class="tab-pane " id="pilicy-tab" ><%include file="supplier_details/supplier_policy_tab.tpl"%></div>
            <div class="tab-pane " id="my-supplier-info-tab" ><%include file="supplier_details/supplier_form_tab.tpl"%></div>
        </div>
    </div>
   
</div>
<script>
$(document).ready(function() {
    var vista = '<%$vista%>';
    $( document ).tooltip();
    $("#products-tab").hide();
    $("#pilicy-tab").hide();
    if (vista == 'catalogo') {
        $("#my-supplier-info-tab").hide();
        $("#main-tab-custom").show();
    }else{
        $("#my-supplier-info-tab").show();
        $("#main-tab-custom").hide();
    } 
});
$('#myTab2 a').click(function (e) {
e.preventDefault();
$(this).tab('show');
});
$('#main').click(function (e) {
$("#main-tab-custom").show();
$("#products-tab").hide();
$("#pilicy-tab").hide();
$("#my-supplier-info-tab").hide();
});
$('#products').click(function (e) {
$("#main-tab-custom").hide();
$("#products-tab").show();
$("#pilicy-tab").hide();
$("#my-supplier-info-tab").hide();
});
$('#pilicy').click(function (e) {
$("#main-tab-custom").hide();
$("#products-tab").hide();
$("#pilicy-tab").show();
$("#my-supplier-info-tab").hide();
});
$('#my-supplier-info').click(function (e) {
$("#main-tab-custom").hide();
$("#products-tab").hide();
$("#pilicy-tab").hide();
$("#my-supplier-info-tab").show();
});
</script>
