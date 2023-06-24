<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Distributor Scripts Controller
 *
 * @category admin
 *
 * @package master
 *
 * @subpackage controllers
 *
 * @module Distributor Scripts
 *
 * @class Distributor_scripts.php
 *
 * @path application\admin\master\controllers\Distributor_scripts.php
 *
 * @version 1.0
 *
 * @author Alyzta Team
 *
 * @since 09.03.2022
 */

class Distributor_provider_contact extends Cit_Controller
{
    /**
     * __construct method is used to set controller preferences while controller object initialization.
     * @created Alyzta Team | 09.03.2022
     * @modified Alyzta Team | 09.03.2022
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
        $this->load->model('distributor_provider_contact_model');
        $this->_request_params();
        $this->folder_name = "custom_products";
        $this->module_name = "distributor_provider_contact";
        $this->mod_enc_url = $this->general->getGeneralEncryptList($this->folder_name, $this->module_name);
        $this->mod_enc_mode = $this->general->getCustomEncryptMode(TRUE);
        $this->module_config = array(
            'module_name' => $this->module_name,
            'folder_name' => $this->folder_name,
            'mod_enc_url' => $this->mod_enc_url,
            'mod_enc_mode' => $this->mod_enc_mode,
            'delete' => "No",
            'xeditable' => "No",
            'top_detail' => "No",
            "multi_lingual" => "No",
            "physical_data_remove" => "Yes",
            "workflow_modes" => array()
        );
        $this->dropdown_arr = array(
            "ds_status" => array(
                "type" => "enum",
                "default" => "Yes",
                "values" => array(
                    array(
                        'id' => 'Active',
                        'val' => $this->lang->line('CONFIG_DISTRIBUTOR_SCRIPTS_ACTIVE')
                    ),
                    array(
                        'id' => 'Inactive',
                        'val' => $this->lang->line('CONFIG_DISTRIBUTOR_SCRIPTS_INACTIVE')
                    )
                )
            )
        );
        $this->parMod = $this->params_arr["parMod"];
        $this->parID = $this->params_arr["parID"];
        $this->parRefer = array();
        $this->expRefer = array();

        $this->topRefer = array();
        $this->dropdown_limit = $this->config->item('ADMIN_DROPDOWN_LIMIT');
        $this->search_combo_limit = $this->config->item('ADMIN_SEARCH_COMBO_LIMIT');
        $this->switchto_limit = $this->config->item('ADMIN_SWITCH_DROPDOWN_LIMIT');
        $this->count_arr = array();
    }

    /**
     * _request_params method is used to set post/get/request params.
     */
    public function _request_params()
    {
        $this->get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
        $this->post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
        $this->params_arr = array_merge($this->get_arr, $this->post_arr);
        return $this->params_arr;
    }

    /**
     * index method is used to intialize grid listing page.
     */
    public function index()
    {
		//	print_r('test1');
		//	die();
        $params_arr = $this->params_arr;
        //print_r($this->params_arr);    
    	//die();
        $extra_qstr = $extra_hstr = '';
        list($list_access, $view_access, $add_access, $edit_access, $del_access, $expo_access) = $this->filter->getModuleWiseAccess("distributor_provider_contact", array("List", "View", "Add", "Update", "Delete", "Export"), TRUE, TRUE);
        try
        {
            if (!$list_access)
            {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            $enc_loc_module = $this->general->getMD5EncryptString("ListPrefer", "distributor_provider_contact");

            $status_array = array(
                'Active',
                'Inactive',
            );
            $status_label = array(
                'js_lang_label.CONFIG_DISTRIBUTOR_SCRIPTS_ACTIVE',
                'js_lang_label.CONFIG_DISTRIBUTOR_SCRIPTS_INACTIVE',
            );

            $list_config = $this->distributor_provider_contact_model->getListConfiguration();
            $this->processConfiguration($list_config, $add_access, $edit_access, TRUE);
            $this->general->trackModuleNavigation("Module", "List", "Viewed", $this->mod_enc_url["index"], "distributor_provider_contact");

            $extra_qstr .= $this->general->getRequestURLParams();
            $extra_hstr .= $this->general->getRequestHASHParams();
            $render_arr = array(

                'list_config' => $list_config,
                'count_arr' => $this->count_arr,
                'enc_loc_module' => $enc_loc_module,
                'status_array' => $status_array,
                'status_label' => $status_label,
                'view_access' => $view_access,
                'add_access' => $add_access,
                'edit_access' => $edit_access,
                'del_access' => $del_access,
                'expo_access' => $expo_access,
                'folder_name' => $this->folder_name,
                'module_name' => $this->module_name,
                'mod_enc_url' => $this->mod_enc_url,
                'mod_enc_mode' => $this->mod_enc_mode,
                //'extra_qstr' => $extra_qstr  [iProviderId], $this->params_arr
                'extra_qstr' => $extra_qstr,         
                //'extra_qstr' => "extra_cond =970",
                'extra_hstr' => $extra_hstr,
                'default_filters' => $this->distributor_provider_contact_model->default_filters,
            );
            $inp_params = array();
            $inp_params['provider_id'] =$params_arr['iProviderId'] ;
            $inp_params['user_id'] =  $this->session->userdata('iAdminId');
            $provider_data = $this->cit_api_model->callAPI("distributor_provider_details", $inp_params);           
            $this->smarty->assign("provider_details",$provider_data['data']['getproviderdetails_v1'][0]);
            $this->smarty->assign($render_arr);
            $this->loadView("distributor_provider_contact_index");       
        }
        catch(Exception $e)
        {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }
    
     public function listing()
    { 
        $params_arr = $this->params_arr;
        $page = $params_arr['page'];
        $rows = $params_arr['rows'];
        $sidx = $params_arr['sidx'];
        $sord = $params_arr['sord'];
        $sdef = $params_arr['sdef'];
        $filters = $params_arr['filters'];
   
        if (!trim($sidx) && !trim($sord))
        {
            $sdef = 'Yes';
        }
        if ($this->general->allowStripSlashes())
        {
            $filters = stripslashes($filters);
          
        }
        $filters = json_decode($filters, TRUE);
        $list_config = $this->distributor_provider_contact_model->getListConfiguration();
        $form_config = $this->distributor_provider_contact_model->getFormConfiguration();
        $extra_cond = $this->distributor_provider_contact_model->extra_cond;
        //$extra_cond = $id;
        //$groupby_cond = $this->distributor_provider_contact_model->groupby_cond;
        //$groupby_cond = $id;
        
        $having_cond = $this->distributor_provider_contact_model->having_cond;
        $orderby_cond = $this->distributor_provider_contact_model->orderby_cond;

        $data_config = array();
        $data_config['page'] = $page;
        $data_config['rows'] = $rows;
        $data_config['sidx'] = $sidx;
        $data_config['sord'] = $sord;
        $data_config['sdef'] = $sdef;
        $data_config['filters'] = $filters;
        $data_config['module_config'] = $this->module_config;
        $data_config['list_config'] = $list_config;
        $data_config['form_config'] = $form_config;
        $data_config['dropdown_arr'] = $this->dropdown_arr;         
       // $data_config['extra_cond'] = $params_arr['extra_cond'];
       // $data_config['extra_cond'] = $params_arr['iProviderId'];
        $data_config['iProviderId'] = $params_arr['iProviderId'];
        $data_config['iUserId'] = $params_arr['iUserId']; 
        $data_config['group_by'] = $groupby_cond;
        $data_config['having_cond'] = $having_cond;
        $data_config['order_by'] = $orderby_cond;
        $data_recs = $this->distributor_provider_contact_model->getListingData($data_config);      
        $data_recs['no_records_msg'] = $this->general->processMessageLabel('ACTION_NO_HOME_PAGE_POPUP_DATA_FOUND_C46_C46_C33');
        

       //print_r ($data_config);
       echo json_encode($data_recs);
   
        $this->skip_template_view();
    }
    
     /**
     * inlineEditAction method is used to save inline editing data records, status field updation,
     * delete records either from grid listing or update form, saving inline adding records from grid
     */
    public function inlineEditAction()
    {
        $params_arr = $this->params_arr;
        $operartor = $params_arr['oper'];
        $all_row_selected = $params_arr['AllRowSelected'];
        $primary_ids = explode(",", $params_arr['id']);
        $primary_ids = count($primary_ids) > 1 ? $primary_ids : $primary_ids[0];
        $filters = $params_arr['filters'];
        if ($this->general->allowStripSlashes())
        {
            $filters = stripslashes($filters);
        }
        $filters = json_decode($filters, TRUE);
        $extra_cond = '';
        $search_mode = $search_join = $search_alias = 'No';
        if ($all_row_selected == "true" && in_array($operartor, array("del", "status")))
        {
            $search_mode = ($operartor == "del") ? "Delete" : "Update";
            $search_join = $search_alias = "Yes";
            $config_arr['module_name'] = $this->module_name;
            $config_arr['list_config'] = $this->distributor_provider_contact_model->getListConfiguration();
            $config_arr['form_config'] = $this->distributor_provider_contact_model->getFormConfiguration();
            $config_arr['table_name'] = $this->distributor_provider_contact_model->table_name;
            $config_arr['table_alias'] = $this->distributor_provider_contact_model->table_alias;
            $filter_main = $this->filter->applyFilter($filters, $config_arr, $search_mode);
            $filter_left = $this->filter->applyLeftFilter($filters, $config_arr, $search_mode);
            $filter_range = $this->filter->applyRangeFilter($filters, $config_arr, $search_mode);
            if ($filter_main != "")
            {
                $extra_cond .= ($extra_cond != "") ? " AND (".$filter_main.")" : $filter_main;
            }
            if ($filter_left != "")
            {
                $extra_cond .= ($extra_cond != "") ? " AND (".$filter_left.")" : $filter_left;
            }
            if ($filter_range != "")
            {
                $extra_cond .= ($extra_cond != "") ? " AND (".$filter_range.")" : $filter_range;
            }
        }
        if ($search_alias == "Yes")
        {
            $primary_field = $this->distributor_provider_contact_model->table_alias.".".$this->distributor_provider_contact_model->primary_key;
        }
        else
        {
            $primary_field = $this->distributor_provider_contact_model->primary_key;
        }
        if (is_array($primary_ids))
        {
            $pk_condition = $this->db->protect($primary_field)." IN ('".implode("','", $primary_ids)."')";
        }
        elseif (intval($primary_ids) > 0)
        {
            $pk_condition = $this->db->protect($primary_field)." = ".$this->db->escape($primary_ids);
        }
        else
        {
            $pk_condition = FALSE;
        }
        if ($pk_condition)
        {
            $extra_cond .= ($extra_cond != "") ? " AND (".$pk_condition.")" : $pk_condition;
        }
        $data_arr = $save_data_arr = array();
        try
        {
            switch ($operartor)
            {
                case 'del':
                    $mode = "Delete";

                    $del_access = $this->filter->getModuleWiseAccess("distributor_provider_contact", "Delete", TRUE, TRUE);
                    if (!$del_access)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_DELETE_THESE_DETAILS_C46_C46_C33'));
                    }
                    if ($search_mode == "No" && $pk_condition == FALSE)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $params_arr = $this->_request_params();

                    $success = $this->distributor_provider_contact_model->delete($extra_cond, $search_alias, $search_join);
                    if (!$success)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $message = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_DELETED_SUCCESSFULLY_C46_C46_C33');
                    break;
                case 'edit':
                    $mode = "Update";
                    $edit_access = $this->filter->getModuleWiseAccess("distributor_provider_contact", "Update", TRUE, TRUE);
                    if (!$edit_access)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    $post_name = $params_arr['name'];
                    $post_val = is_array($params_arr['value']) ? implode(",", $params_arr['value']) : $params_arr['value'];

                    $list_config = $this->distributor_provider_contact_model->getListConfiguration($post_name);
                    $form_config = $this->distributor_provider_contact_model->getFormConfiguration($list_config['source_field']);
                    if (!is_array($form_config) || count($form_config) == 0)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FORM_CONFIGURING_NOT_DONE_C46_C46_C33'));
                    }
                    if (in_array($form_config['type'], array("date", "date_and_time", "time", 'phone_number')))
                    {
                        $post_val = $this->filter->formatActionData($post_val, $form_config);
                    }
                    if ($form_config["encrypt"] == "Yes")
                    {
                        $post_val = $this->general->encryptDataMethod($post_val, $form_config["enctype"]);
                    }
                    $field_name = $form_config['field_name'];
                    $unique_name = $form_config['name'];

                    $data_arr[$field_name] = $post_val;
                    $success = $this->distributor_provider_contact_model->update($data_arr, intval($primary_ids));
                    $message = $this->general->processMessageLabel('ACTION_RECORD_SUCCESSFULLY_UPDATED_C46_C46_C33');
                    if (!$success)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                    }
                    break;
                case 'status':
                    $mode = "Status";
                    $edit_access = $this->filter->getModuleWiseAccess("distributor_provider_contact", "Update", TRUE, TRUE);
                    if (!$edit_access)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    if ($search_mode == "No" && $pk_condition == FALSE)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $status_field = "eStatus";
                    if ($status_field == "")
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FORM_CONFIGURING_NOT_DONE_C46_C46_C33'));
                    }
                    if ($search_mode == "Yes" || $search_alias == "Yes")
                    {
                        $field_name = $this->distributor_provider_contact_model->table_alias.".eStatus";
                    }
                    else
                    {
                        $field_name = $status_field;
                    }
                    $data_arr[$field_name] = $params_arr['status'];
                    $success = $this->distributor_provider_contact_model->update($data_arr, $extra_cond, $search_alias, $search_join);
                    if (!$success)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_MODIFYING_THESE_RECORDS_C46_C46_C33'));
                    }
                    $message = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_MODIFIED_SUCCESSFULLY_C46_C46_C33');
                    break;
            }
            $ret_arr['success'] = "true";
            $ret_arr['message'] = $message;
        }
        catch(Exception $e)
        {
            $ret_arr['success'] = "false";
            $ret_arr['message'] = $e->getMessage();
        }
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }
    
    
    
    
    
    
    
}