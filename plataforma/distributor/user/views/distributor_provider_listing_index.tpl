<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-list-container">
    <%include file="distributor_provider_listing_index_strip.tpl"%>
    <div class="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing box gradient">
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Middle Content -->
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
                
    el_grid_settings['hide_add_btn'] = '';
    el_grid_settings['hide_del_btn'] = '';
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
    
    el_grid_settings['default_sort'] = 'dpm_seq_no';
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
    el_grid_settings['buttons_arr'] = [{
        "name": "add",
        "type": "default",
        "text": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_UNHIDE_SELECTED')%>",
        "title": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_UNHIDE_SELECTED')%>",
        "icon": "icomoon-icon-plus-2",
        "icon_only": "Yes"
    },
    {
        "name": "del",
        "type": "default",
        "text": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_DELETE')%>",
        "title": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_DELETE_SELECTED_ROW')%>",
        "icon": "icomoon-icon-remove-6",
        "icon_only": "No"
    },
    {
        "name": "custom_btn_1",
        "type": "custom",
        "text": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_ADD_PROVIDER')%>",
        "title": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_ADD_PROVIDER')%>",
        "icon": "icon14 icomoon-icon-plus-2",
        "icon_only": "No",
        "confirm": {
            "type": "module",
            "module": "<%$this->general->getAdminEncodeURL('user\/distributor_provider_listing')%>",
            "url": "<%$this->config->item('admin_url')%>#<%$this->general->getAdminEncodeURL('distributor\/distriburtor_provider\/add')%>|hideCtrl|true|mode|<%$this->general->getAdminEncodeURL('Add')%>",
            "id": "id",
            "open": "popup",
            "width": "300",
            "height": "150",
            "params": {
                "loadGrid": "list2"
            }
        }
    },
    {
        "name": "status_active",
        "type": "default",
        "text": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_REMOVE_SELECTED')%>",
        "title": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_REMOVE_SELECTED')%>",
        "icon": "icomoon-icon-checkmark",
        "icon_only": "No"
    },
    {
        "name": "status_inactive",
        "type": "default",
        "text": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_INACTIVE')%>",
        "title": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_INACTIVE')%>",
        "icon": "icomoon-icon-blocked",
        "icon_only": "No"
    },
    {
        "name": "status_pending",
        "type": "default",
        "text": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_PENDING')%>",
        "title": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_PENDING')%>",
        "icon": "silk-icon-popout",
        "icon_only": "No"
    },
    {
        "name": "status_rejected",
        "type": "default",
        "text": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_REJECTED')%>",
        "title": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_REJECTED')%>",
        "icon": "silk-icon-popout",
        "icon_only": "No"
    },
    {
        "name": "search",
        "type": "default",
        "text": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_SEARCH')%>",
        "title": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_ADVANCE_SEARCH')%>",
        "icon": "icomoon-icon-search-3",
        "icon_only": "No"
    },
    {
        "name": "refresh",
        "type": "default",
        "text": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_SHOW_ALL')%>",
        "title": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_SHOW_ALL_LISTING_RECORDS')%>",
        "icon": "icomoon-icon-loop-2",
        "icon_only": "No"
    },
    {
        "name": "columns",
        "type": "default",
        "text": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_COLUMNS')%>",
        "title": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_HIDE_C47SHOW_COLUMNS')%>",
        "icon": "silk-icon-columns",
        "icon_only": "No"
    },
    {
        "name": "export",
        "type": "default",
        "text": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_EXPORT')%>",
        "title": "<%$this->lang->line('DISTRIBUTOR_PROVIDER_LISTING_EXPORT')%>",
        "icon": "icomoon-icon-out",
        "icon_only": "No"
    }];
    el_grid_settings['callbacks'] = [];
    el_grid_settings['message_arr'] = {
    };
    
    js_col_name_json = [{
        "name": "dpm_seq_no",
        "label": "<%$list_config['dpm_seq_no']['label_lang']%>"
    },
    {
        "name": "u_company_name",
        "label": "<%$list_config['u_company_name']['label_lang']%>"
    },
    {
        "name": "u_name",
        "label": "<%$list_config['u_name']['label_lang']%>"
    },
    {
        "name": "u_unique_id",
        "label": "<%$list_config['u_unique_id']['label_lang']%>"
    },
    {
        "name": "u_provider_type",
        "label": "<%$list_config['u_provider_type']['label_lang']%>"
    },
    {
        "name": "dpm_provider_discount",
        "label": "<%$list_config['dpm_provider_discount']['label_lang']%>"
    },
    {
        "name": "dpm_provider_profit",
        "label": "<%$list_config['dpm_provider_profit']['label_lang']%>"
    },
    {
        "name": "sys_custom_field_2",
        "label": "<%$list_config['sys_custom_field_2']['label_lang']%>"
    },
    {
        "name": "show_hide",
        "label": "<%$list_config['show_hide']['label_lang']%>"
    },
    {
        "name": "tab_action",
        "label": "<%$list_config['tab_action']['label_lang']%>"
    },
    {
        "name": "u_distributor_user_id",
        "label": "<%$list_config['u_distributor_user_id']['label_lang']%>"
    },
    {
        "name": "dpm_distributor_provider_mapping_id",
        "label": "<%$list_config['dpm_distributor_provider_mapping_id']['label_lang']%>"
    },
    {
        "name": "phfd_provider_hide_for_distributor_id",
        "label": "<%$list_config['phfd_provider_hide_for_distributor_id']['label_lang']%>"
    }];
    
    js_col_model_json = [{
        "name": "dpm_seq_no",
        "index": "dpm_seq_no",
        "label": "<%$list_config['dpm_seq_no']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['dpm_seq_no']['width']%>",
        "search": <%if $list_config['dpm_seq_no']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['dpm_seq_no']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['dpm_seq_no']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['dpm_seq_no']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['dpm_seq_no']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['dpm_seq_no']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_listing",
                "aria-unique-name": "dpm_seq_no",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['dpm_seq_no']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_listing",
            "aria-unique-name": "dpm_seq_no",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['dpm_seq_no']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "u_company_name",
        "index": "u_company_name",
        "label": "<%$list_config['u_company_name']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['u_company_name']['width']%>",
        "search": <%if $list_config['u_company_name']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['u_company_name']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['u_company_name']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['u_company_name']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['u_company_name']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['u_company_name']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_listing",
                "aria-unique-name": "u_company_name",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['u_company_name']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_listing",
            "aria-unique-name": "u_company_name",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['u_company_name']['default']%>",
        "filterSopt": "bw",
        "formatter": "myDesFormatter"
    },
    {
        "name": "u_name",
        "index": "u_name",
        "label": "<%$list_config['u_name']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['u_name']['width']%>",
        "search": <%if $list_config['u_name']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['u_name']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['u_name']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['u_name']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['u_name']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['u_name']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_listing",
                "aria-unique-name": "u_name",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['u_name']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_listing",
            "aria-unique-name": "u_name",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['u_name']['default']%>",
        "filterSopt": "bw",
        "formatter": "myDesFormatter"
    },
    {
        "name": "u_unique_id",
        "index": "u_unique_id",
        "label": "<%$list_config['u_unique_id']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['u_unique_id']['width']%>",
        "search": <%if $list_config['u_unique_id']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['u_unique_id']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['u_unique_id']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['u_unique_id']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['u_unique_id']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['u_unique_id']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_listing",
                "aria-unique-name": "u_unique_id",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['u_unique_id']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_listing",
            "aria-unique-name": "u_unique_id",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['u_unique_id']['default']%>",
        "filterSopt": "bw",
        "formatter": "myDesFormatter"
    },
    {
        "name": "u_provider_type",
        "index": "u_provider_type",
        "label": "<%$list_config['u_provider_type']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['u_provider_type']['width']%>",
        "search": <%if $list_config['u_provider_type']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['u_provider_type']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['u_provider_type']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['u_provider_type']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['u_provider_type']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['u_provider_type']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "select",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_listing",
                "aria-unique-name": "u_provider_type",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "searchhidden": <%if $list_config['u_provider_type']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataUrl": <%if $count_arr["u_provider_type"]["json"] eq "Yes" %>false<%else%>'<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=u_provider_type&mode=<%$mod_enc_mode["Search"]%>&rformat=html<%$extra_qstr%>'<%/if%>,
            "value": <%if $count_arr["u_provider_type"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["u_provider_type"]["data"]|@addslashes%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['u_provider_type']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["u_provider_type"]["ajax"] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_listing",
            "aria-unique-name": "u_provider_type",
            "dataUrl": '<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=u_provider_type&mode=<%$mod_enc_mode["Update"]%>&rformat=html<%$extra_qstr%>',
            "dataInit": <%if $count_arr['u_provider_type']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["u_provider_type"] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": "<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'DISTRIBUTOR_PROVIDER_LISTING_TYPE')%>",
            "class": "inline-edit-row chosen-select"
        },
        "ctrl_type": "dropdown",
        "default_value": "<%$list_config['u_provider_type']['default']%>",
        "filterSopt": "in",
        "stype": "select"
    },
    {
        "name": "dpm_provider_discount",
        "index": "dpm_provider_discount",
        "label": "<%$list_config['dpm_provider_discount']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['dpm_provider_discount']['width']%>",
        "search": <%if $list_config['dpm_provider_discount']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['dpm_provider_discount']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['dpm_provider_discount']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['dpm_provider_discount']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['dpm_provider_discount']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['dpm_provider_discount']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_listing",
                "aria-unique-name": "dpm_provider_discount",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['dpm_provider_discount']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_listing",
            "aria-unique-name": "dpm_provider_discount",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['dpm_provider_discount']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "dpm_provider_profit",
        "index": "dpm_provider_profit",
        "label": "<%$list_config['dpm_provider_profit']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['dpm_provider_profit']['width']%>",
        "search": <%if $list_config['dpm_provider_profit']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['dpm_provider_profit']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['dpm_provider_profit']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['dpm_provider_profit']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['dpm_provider_profit']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['dpm_provider_profit']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_listing",
                "aria-unique-name": "dpm_provider_profit",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['dpm_provider_profit']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_listing",
            "aria-unique-name": "dpm_provider_profit",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['dpm_provider_profit']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "sys_custom_field_2",
        "index": "sys_custom_field_2",
        "label": "<%$list_config['sys_custom_field_2']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['sys_custom_field_2']['width']%>",
        "search": <%if $list_config['sys_custom_field_2']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['sys_custom_field_2']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['sys_custom_field_2']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['sys_custom_field_2']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['sys_custom_field_2']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['sys_custom_field_2']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_listing",
                "aria-unique-name": "sys_custom_field_2",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['sys_custom_field_2']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_listing",
            "aria-unique-name": "sys_custom_field_2",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['sys_custom_field_2']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "show_hide",
        "index": "show_hide",
        "label": "<%$list_config['show_hide']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['show_hide']['width']%>",
        "search": <%if $list_config['show_hide']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['show_hide']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['show_hide']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['show_hide']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['show_hide']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['show_hide']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_listing",
                "aria-unique-name": "show_hide",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['show_hide']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_listing",
            "aria-unique-name": "show_hide",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['show_hide']['default']%>",
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
                "aria-module-name": "distributor_provider_listing",
                "aria-unique-name": "tab_action",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['tab_action']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_listing",
            "aria-unique-name": "tab_action",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['tab_action']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "u_distributor_user_id",
        "index": "u_distributor_user_id",
        "label": "<%$list_config['u_distributor_user_id']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['u_distributor_user_id']['width']%>",
        "search": <%if $list_config['u_distributor_user_id']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['u_distributor_user_id']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['u_distributor_user_id']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['u_distributor_user_id']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['u_distributor_user_id']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['u_distributor_user_id']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "select",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_listing",
                "aria-unique-name": "u_distributor_user_id",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "searchhidden": <%if $list_config['u_distributor_user_id']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataUrl": <%if $count_arr["u_distributor_user_id"]["json"] eq "Yes" %>false<%else%>'<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=u_distributor_user_id&mode=<%$mod_enc_mode["Search"]%>&rformat=html<%$extra_qstr%>'<%/if%>,
            "value": <%if $count_arr["u_distributor_user_id"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["u_distributor_user_id"]["data"]|@addslashes%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['u_distributor_user_id']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["u_distributor_user_id"]["ajax"] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_listing",
            "aria-unique-name": "u_distributor_user_id",
            "dataUrl": '<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=u_distributor_user_id&mode=<%$mod_enc_mode["Update"]%>&rformat=html<%$extra_qstr%>',
            "dataInit": <%if $count_arr['u_distributor_user_id']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["u_distributor_user_id"] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": "<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'DISTRIBUTOR_PROVIDER_LISTING_DISTRIBUTOR_USER_ID')%>",
            "class": "inline-edit-row chosen-select"
        },
        "ctrl_type": "dropdown",
        "default_value": "<%$list_config['u_distributor_user_id']['default']%>",
        "filterSopt": "in",
        "stype": "select"
    },
    {
        "name": "dpm_distributor_provider_mapping_id",
        "index": "dpm_distributor_provider_mapping_id",
        "label": "<%$list_config['dpm_distributor_provider_mapping_id']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['dpm_distributor_provider_mapping_id']['width']%>",
        "search": <%if $list_config['dpm_distributor_provider_mapping_id']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['dpm_distributor_provider_mapping_id']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['dpm_distributor_provider_mapping_id']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['dpm_distributor_provider_mapping_id']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['dpm_distributor_provider_mapping_id']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['dpm_distributor_provider_mapping_id']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_listing",
                "aria-unique-name": "dpm_distributor_provider_mapping_id",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['dpm_distributor_provider_mapping_id']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_listing",
            "aria-unique-name": "dpm_distributor_provider_mapping_id",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['dpm_distributor_provider_mapping_id']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "phfd_provider_hide_for_distributor_id",
        "index": "phfd_provider_hide_for_distributor_id",
        "label": "<%$list_config['phfd_provider_hide_for_distributor_id']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['phfd_provider_hide_for_distributor_id']['width']%>",
        "search": <%if $list_config['phfd_provider_hide_for_distributor_id']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['phfd_provider_hide_for_distributor_id']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['phfd_provider_hide_for_distributor_id']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['phfd_provider_hide_for_distributor_id']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['phfd_provider_hide_for_distributor_id']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['phfd_provider_hide_for_distributor_id']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "distributor_provider_listing",
                "aria-unique-name": "phfd_provider_hide_for_distributor_id",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['phfd_provider_hide_for_distributor_id']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "distributor_provider_listing",
            "aria-unique-name": "phfd_provider_hide_for_distributor_id",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['phfd_provider_hide_for_distributor_id']['default']%>",
        "filterSopt": "bw"
    }];
         
    initMainGridListing();
    createTooltipHeading();
    callSwitchToParent();
<%/javascript%>
<script>
    
    $(document).ready(function(){
        $(document).on("change",".input-dpm_order_sequ",function(e){
			var params={};
			var datos=$(this).attr("item-info");
			datos=datos.split("_");
			params.dpm_order_sequ=$(this).val();
            params.name="dpm_order_sequ";
			params.value=$(this).val();
			params.id=datos[2];
			params.oper='edit';
			//console.log("hola");
			$.ajax({
                type:'POST',
                url: admin_url + "user/distributor_provider_listing/inlineEditAction",
                data:params,
                success:function(res){
                    $("#var_msg_cnt").show();
                    $("#err_msg_cnt").html("Orden Modificado").addClass("alert-success");
                    setTimeout(function(){$("#var_msg_cnt").hide();},2000);				
                },
                error: function(data){
                    console.log(data);
                }
            });
		});
        $(document).on("keypress",".input-dpm_order_sequ",function(e){
            var code = (e.keyCode ? e.keyCode : e.which);
            if(code==13){
                var params={};
				var datos=$(this).attr("item-info");
				datos=datos.split("_");
				params.dpm_order_sequ=$(this).val();
				params.name="dpm_order_sequ";
				params.value=$(this).val();
				params.id=datos[2];
				params.oper='edit';
				if ($(this).val()!=datos[0]) {
				//console.log("hola2");
                    $.ajax({
                        type:'POST',
                        url: admin_url + "user/distributor_provider_listing/inlineEditAction",
                        data:params,
                        success:function(res){
                            $("#var_msg_cnt").show();
							$("#err_msg_cnt").html("Orden Modificado").addClass("alert-success");
							setTimeout(function(){$("#var_msg_cnt").hide();},2000);				
                        },
                        error: function(data){
                            console.log(data);
                        }
                    });
                }    
            }
		});    
    });
</script>
<%$this->js->add_js("admin/custom/addRemoveProvider.js")%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 