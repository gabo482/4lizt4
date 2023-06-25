<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%include file="distributor_provider_contact_index_strip.tpl"%>
<div class="<%$module_name%>">
    <div id="ajax_content_div" class="ajax-content-div top-frm-spacing box gradient">
            <input type="hidden" id="projmod" name="projmod" value="distributor_provider_contact" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Middle Content -->
            <style>#add_list2_top {display: none;}</style>
            <div id="scrollable_content" class="scrollable-content top-list-spacing">
                <div class="grid-data-container pad-calc-container">
                    <div class="top-list-tab-layout" id="top_list_grid_layout">
                    </div>
                    <table class="grid-table-view " width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <!-- Module Listing Block -->
                            <td id="grid_data_col" class="<%$rl_theme_arr['grid_search_toolbar']%>">
                                <div id="pager2"></div>
                                <table id="list2"></table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <input type="hidden" name="selAllRows" value="" id="selAllRows" />
    </div>
</div>
<!-- Module Listing Javascript -->
<%javascript%>
    $.jgrid.no_legacy_api = true; $.jgrid.useJSON = true;
    var el_grid_settings = {}, js_col_model_json = {}, js_col_name_json = {}; 
                    
    el_grid_settings['module_name'] = '<%$module_name%>';
    el_grid_settings['extra_hstr'] = '<%$extra_hstr%>';
    el_grid_settings['extra_qstr'] = '<%$extra_qstr%>';
    el_grid_settings['enc_location'] = '<%$enc_loc_module%>';
    el_grid_settings['par_module '] = '<%$this->general->getAdminEncodeURL($parMod)%>';
    el_grid_settings['par_data'] = '<%$this->general->getAdminEncodeURL($parID)%>';
    el_grid_settings['par_field'] = '<%$parField%>';
    el_grid_settings['par_type'] = 'parent';
    el_grid_settings['add_page_url'] = '<%$mod_enc_url["add"]%>'; 
    el_grid_settings['edit_page_url'] =  admin_url+'<%$mod_enc_url["inline_edit_action"]%>?<%$extra_qstr%>';
    el_grid_settings['listing_url'] = admin_url+'<%$mod_enc_url["listing"]%>?<%$extra_qstr%>';
    el_grid_settings['search_refresh_url'] = admin_url+'<%$mod_enc_url["get_left_search_content"]%>?<%$extra_qstr%>';
    el_grid_settings['search_autocomp_url'] = admin_url+'<%$mod_enc_url["get_search_auto_complete"]%>?<%$extra_qstr%>';
    el_grid_settings['ajax_data_url'] = admin_url+'<%$mod_enc_url["get_chosen_auto_complete"]%>?<%$extra_qstr%>';
    el_grid_settings['auto_complete_url'] = admin_url+'<%$mod_enc_url["get_token_auto_complete"]%>?<%$extra_qstr%>';
    el_grid_settings['export_url'] =  admin_url+'<%$mod_enc_url["export"]%>?<%$extra_qstr%>';
    el_grid_settings['subgrid_listing_url'] =  admin_url+'<%$mod_enc_url["get_subgrid_block"]%>?<%$extra_qstr%>';
    el_grid_settings['jparent_switchto_url'] = admin_url+'<%$parent_switch_cit["url"]%>?<%$extra_qstr%>';
    
    el_grid_settings['admin_rec_arr'] = $.parseJSON('<%$hide_admin_rec|@json_encode%>');;
    el_grid_settings['status_arr'] = $.parseJSON('<%$status_array|@json_encode%>');
    el_grid_settings['status_lang_arr'] = $.parseJSON('<%$status_label|@json_encode%>');
                
    el_grid_settings['hide_add_btn'] = '1';
    el_grid_settings['hide_del_btn'] = '1';
    el_grid_settings['hide_status_btn'] = '';
    el_grid_settings['hide_export_btn'] = '';
    el_grid_settings['hide_columns_btn'] = 'Yes';
    
    el_grid_settings['hide_advance_search'] = 'Yes';
    el_grid_settings['hide_search_tool'] = 'No';
    el_grid_settings['hide_multi_select'] = 'No';
    el_grid_settings['popup_add_form'] = 'No';
    el_grid_settings['popup_edit_form'] = 'No';
    el_grid_settings['popup_add_size'] = ['75%', '75%'];
    el_grid_settings['popup_edit_size'] = ['75%', '75%'];
    el_grid_settings['hide_paging_btn'] = 'No';
    el_grid_settings['hide_refresh_btn'] = 'No';
    el_grid_settings['group_search'] = '';
    
    el_grid_settings['permit_add_btn'] = '<%$add_access%>';
    el_grid_settings['permit_del_btn'] = '<%$del_access%>';
    el_grid_settings['permit_edit_btn'] = '<%$edit_access%>';
    el_grid_settings['permit_view_btn'] = '<%$view_access%>';
    el_grid_settings['permit_expo_btn'] = '<%$expo_access%>';
    
    el_grid_settings['default_sort'] = 'dpc_name_provider_contact';
    el_grid_settings['sort_order'] = 'asc';
    
    el_grid_settings['footer_row'] = 'No';
    el_grid_settings['grouping'] = 'No';
    el_grid_settings['group_attr'] = {};
    
    el_grid_settings['inline_add'] = 'No';
    el_grid_settings['rec_position'] = 'Top';
    el_grid_settings['auto_width'] = 'Yes';
    el_grid_settings['subgrid'] = 'No';
    el_grid_settings['colgrid'] = 'No';
    el_grid_settings['rating_allow'] = 'No';
    el_grid_settings['listview'] = 'list';
    el_grid_settings['filters_arr'] = $.parseJSON('<%$default_filters|@json_encode%>');
    el_grid_settings['top_filter'] = [];
    el_grid_settings['buttons_arr'] = [];
    el_grid_settings['callbacks'] = [];
    el_grid_settings['message_arr'] = {
    };
    
    js_col_name_json = [{
        "name": "dpc_provider_name",
        "label": "<%$list_config['dpc_provider_name']['label_lang']%>"
    },
    {
        "name": "dpc_role_provider_contact",
        "label": "<%$list_config['dpc_role_provider_contact']['label_lang']%>"
    },
    {
        "name": "dpc_name_provider_contact",
        "label": "<%$list_config['dpc_name_provider_contact']['label_lang']%>"
    },
    {
        "name": "dpc_email_provider_contact",
        "label": "<%$list_config['dpc_email_provider_contact']['label_lang']%>"
    },
    {
        "name": "dpc_phone_provider_contact",
        "label": "<%$list_config['dpc_phone_provider_contact']['label_lang']%>"
    },
    {
        "name": "tab_action",
        "label": "<%$list_config['tab_action']['label_lang']%>"
    }];
    
    js_col_model_json = [{
        "name": "dpc_provider_name",
        "index": "dpc_provider_name",
        "label": "<%$list_config['dpc_provider_name']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['dpc_provider_name']['width']%>",
        "search": <%if $list_config['dpc_provider_name']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['dpc_provider_name']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['dpc_provider_name']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['dpc_provider_name']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['dpc_provider_name']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_contact",
                "aria-unique-name": "dpc_provider_name",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['dpc_provider_name']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_contact",
            "aria-unique-name": "dpc_provider_name",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['dpc_provider_name']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "dpc_role_provider_contact",
        "index": "dpc_role_provider_contact",
        "label": "<%$list_config['dpc_role_provider_contact']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['dpc_role_provider_contact']['width']%>",
        "search": <%if $list_config['dpc_role_provider_contact']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['dpc_role_provider_contact']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['dpc_role_provider_contact']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['dpc_role_provider_contact']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['dpc_role_provider_contact']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_contact",
                "aria-unique-name": "dpc_role_provider_contact",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['dpc_role_provider_contact']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_contact",
            "aria-unique-name": "dpc_role_provider_contact",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['dpc_role_provider_contact']['default']%>",
        "filterSopt": "bw"
    },
	{
        "name": "dpc_name_provider_contact",
        "index": "dpc_name_provider_contact",
        "label": "<%$list_config['dpc_name_provider_contact']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['dpc_name_provider_contact']['width']%>",
        "search": <%if $list_config['dpc_name_provider_contact']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['dpc_name_provider_contact']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['dpc_name_provider_contact']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['dpc_name_provider_contact']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['dpc_name_provider_contact']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['dpc_name_provider_contact']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C47 ,"#FIELD#",js_lang_label.DISTRIBUTOR_PROVIDER_NAME_JS)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_contact",
                "aria-unique-name": "dpc_name_provider_contact",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['dpc_provider_name']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_contact",
            "aria-unique-name": "dpc_name_provider_contact",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['dpc_name_provider_contact']['default']%>",
        "filterSopt": "bw",
        "formatter": formatAdminModuleEditLink,
        "unformat": unformatAdminModuleEditLink
    },
    {
        "name": "dpc_email_provider_contact",
        "index": "dpc_email_provider_contact",
        "label": "<%$list_config['dpc_email_provider_contact']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['dpc_email_provider_contact']['width']%>",
        "search": <%if $list_config['dpc_email_provider_contact']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['dpc_email_provider_contact']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['dpc_email_provider_contact']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['dpc_email_provider_contact']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['dpc_email_provider_contact']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_contact",
                "aria-unique-name": "dpc_email_provider_contact",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['dpc_email_provider_contact']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_contact",
            "aria-unique-name": "dpc_email_provider_contact",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['dpc_email_provider_contact']['default']%>",
        "filterSopt": "bw"
    },
	{
        "name": "dpc_phone_provider_contact",
        "index": "dpc_phone_provider_contact",
        "label": "<%$list_config['dpc_phone_provider_contact']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['dpc_phone_provider_contact']['width']%>",
        "search": <%if $list_config['dpc_phone_provider_contact']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['dpc_phone_provider_contact']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['dpc_phone_provider_contact']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['dpc_phone_provider_contact']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['dpc_phone_provider_contact']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_contact",
                "aria-unique-name": "dpc_phone_provider_contact",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['dpc_phone_provider_contact']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_contact",
            "aria-unique-name": "dpc_phone_provider_contact",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['dpc_phone_provider_contact']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "tab_action",
        "index": "tab_action",
        "label": "<%$list_config['tab_action']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['tab_action']['width']%>",
        "search": <%if $list_config['tab_action']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['tab_action']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['tab_action']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['tab_action']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['tab_action']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['tab_action']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_contact",
                "aria-unique-name": "tab_action",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['tab_action']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_contact",
            "aria-unique-name": "tab_action",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['tab_action']['default']%>",
        "filterSopt": "bw"
    }];
         
    initMainGridListing();
    createTooltipHeading();
    callSwitchToParent();
<%/javascript%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%>
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%>