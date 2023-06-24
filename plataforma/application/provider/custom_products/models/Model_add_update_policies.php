<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Model_add_update_policies Model
 *
 * @category admin
 *
 * @package custom_products
 *
 * @subpackage models
 *
 * @module custom_products
 *
 * @class Model_add_update_policies.php
 *
 * @path application\provider\custom_products\models\Model_add_update_policies.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @since 12.06.2017
 */

class Model_add_update_policies extends CI_Model
{
    public $table_name;
    public $table_alias;
    public $primary_key;
    public $primary_alias;
    public $rec_per_page;

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->table_name = "provider_policies";
    }
    /*insert / update provider policies*/
    public function insert_update($post_arr=array()){
        $insert_update=array();        
        $insert_update['vName'] = $post_arr['name'];
        $insert_update['tDescription'] = $post_arr['description'];
        $insert_update['iUsersId'] = $this->session->userdata('iAdminId');
        if(intval($post_arr['id'])==0){
            $insert_update['dtAddedDate'] = date('Y-m-d H:i:s');
            $this->db->insert($this->table_name,$insert_update);
            $post_arr['id']=$this->db->insert_id();
        }else{
            $insert_update['dtModifiedDate'] = date('Y-m-d H:i:s');    
            $this->db->where('iProviderPoliciesId',$post_arr['id']);
            $this->db->update($this->table_name,$insert_update);
        }
        return $post_arr['id'];
    }
    /*remove provider policy*/
    public function remove_policy($id=''){
        $provider_id = $this->session->userdata('iAdminId');
        $this->db->where('iProviderPoliciesId',$id);
        $this->db->where('iUsersId',$provider_id);
        return $this->db->delete($this->table_name);
    }

    /*remove provider contact*/
    public function remove_contact($id=''){
        $provider_id = $this->session->userdata('iAdminId');
        $this->db->where('iProviderContactsId',$id);
        $this->db->where('iUsersId',$provider_id);
        return $this->db->delete("provider_contacts");
    }
    /*add provider contact*/
    public function add_contact($data=array()){
        $provider_id = $this->session->userdata('iAdminId');
        $insert_contact = array();
        $insert_contact['iUsersId'] = $provider_id;
        $insert_contact['vRole'] = $data['pcm_role'];
        $insert_contact['vName'] = $data['pcm_name'];
        $insert_contact['vEmail'] = $data['pcm_email'];
        $insert_contact['vContactNo'] = $data['pcm_contact_no'];
        $insert_contact['dAddedDate'] = date('Y-m-d H:i:s');
        $this->db->insert("provider_contacts",$insert_contact);
        $id=$this->db->insert_id();
        return $id;
    }



    
}