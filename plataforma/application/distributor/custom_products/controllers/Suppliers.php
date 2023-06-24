<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Suppliers extends Cit_Controller
{
    public function __construct() {
        parent::__construct();
        $this->_request_params();
        $this->load->model('model_add_update_policies');
    }

    public function _request_params()
    {
        $this->get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
        $this->post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
        $this->params_arr = array_merge($this->get_arr, $this->post_arr);
        return $this->params_arr;
    }

    /*update provider policies*/
    public function update_policies() {
        $emptyrow='';
        if($this->params_arr['id']==0){
            $emptyrow=$this->parser->parse("supplier_details/add_new_policy.tpl",array(),true); 
        }
        $rowid = $this->model_add_update_policies->insert_update($this->params_arr);        
        $return = array("success"=>true,"message"=>"Opertaion performed successfully","rowid"=>$rowid,"emptyrow"=>$emptyrow);
        echo json_encode($return);
        exit;
    }
    
    public function provider_contact() {
        $emptyrow='';
        if($this->params_arr['id']==0){
            $emptyrow=$this->parser->parse("supplier_details/add_new_contact.tpl",array(),true); 
        }
        $rowid = $this->model_add_update_policies->insert_update($this->params_arr);        
        $return = array("success"=>true,"message"=>"Opertaion performed successfully","rowid"=>$rowid,"emptyrow"=>$emptyrow);
        echo json_encode($return);
        exit;
    }

    /*remove provider policies*/
    public function remove_policies() {
        $this->params_arr['id'] = $this->params_arr['id']?$this->params_arr['id']:0;
        $this->model_add_update_policies->remove_policy($this->params_arr['id']);
        $return = array("success"=>true,"message"=>"Policy deleted successfully");
        echo json_encode($return);
        exit;
    }

    /*add provider contact*/
    public function add_contact($provider_id) {
        $emptyfrm=$this->parser->parse("supplier_details/add_new_contact.tpl",array('provider_id'=>$provider_id),true);
        echo $emptyfrm; exit;
    }
    
    /*update provider contact*/
    public function update_contact(){
               
        $param['pcm_role'] = $this->params_arr['inp_role'];
        $param['pcm_name'] = $this->params_arr['inp_name'];
        $param['pcm_email']= $this->params_arr['inp_email'];
        $param['pcm_contact_no'] = $this->params_arr['inp_telephone'];
        $param['dpm_provider_id'] = $this->params_arr['dpm_provider_id'];
        $param['dpm_user_id'] = $this->params_arr['dpm_user_id'];
        
         //echo "<pre>";
         //echo print_r($param);
         //die; 
        
        $contact_id = $this->model_add_update_policies->add_contact($param);    
        $this->smarty->assign('row',$param);       
        echo $this->parser->parse("supplier_details/supplier_contact_row.tpl",array(),true);
    }

    /*remove provider contact*/
    public function remove_contact() {
        $this->params_arr['id'] = $this->params_arr['id']?$this->params_arr['id']:0;
        $this->model_add_update_policies->remove_contact($this->params_arr['id']);
        /*
        echo "<pre>";
        echo $this->db->last_query();
        die;
        */        
        $empty_html = "<div class='norecordfound' id='nocontactfound'><i class='fa fa-users'></i> ¡No ha agregado todavía ningún contacto!</div>";
        $return = array("success"=>true,"message"=>"Contact deleted successfully","empty_data"=>$empty_html);
        echo json_encode($return);
        exit;
    }
    /*load provider contact*/
    public function load_contacts() {
           
        $inp_params = array();
        $inp_params['provider_id'] = $this->params_arr['provider_id']?$this->params_arr['provider_id']:0;
        $inp_params['user_id'] =$this->params_arr['user_id']?$this->params_arr['user_id']:0;             
        $provider_data = $this->cit_api_model->callAPI("distributor_provider_details", $inp_params);
        $this->smarty->assign("provider_contact_details",$provider_data['data']['getprovidercontacts']);
        echo $this->parser->parse("supplier_details/supplier_contact_list.tpl",array(),true);
    
        exit; 
    }
}