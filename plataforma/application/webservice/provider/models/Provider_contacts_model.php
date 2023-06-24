<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Provider Contacts Model
 *
 * @category webservice
 *
 * @package provider
 *
 * @subpackage models
 *
 * @module Provider Contacts
 *
 * @class Provider_contacts_model.php
 *
 * @path application\webservice\provider\models\Provider_contacts_model.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @since 29.07.2017
 */

class Provider_contacts_model extends CI_Model
{
    public $default_lang = 'EN';

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('listing');
        $this->default_lang = $this->general->getLangRequestValue();
    }

    /**
     * getprovidercontacts method is used to execute database queries for Distributor Provider Details API.
     * @created Himanshu Dholakia | 16.05.2017
     * @modified Himanshu Dholakia | 21.06.2017
     * @param string $provider_id provider_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function getprovidercontacts($provider_id = '', $user_id ='')
    {
        try
        {
            $result_arr = array();

            $this->db->from("provider_contacts_1 AS pcm");
            $this->db->select("pcm.iUsersId AS dpm_provider_id"); //test=vgca iUsersId
            $this->db->select("pcm.iProviderContactsId AS pcm_provider_contacts_id");
            $this->db->select("pcm.vRole AS pcm_role");
            $this->db->select("pcm.vName AS pcm_name");
            $this->db->select("pcm.vEmail AS pcm_email");
            $this->db->select("pcm.vContactNo AS pcm_contact_no");
           
           
            if (isset($user_id) && $user_id != "")    
                $this->db->where("pcm.iUsersId =", $user_id ); 
           
             if (isset($provider_id) && $provider_id != "")           
                $this->db->where("pcm.iProviderId =", $provider_id);                
            
            //$this->db->order_by("pcm.dAddedDate", "desc");

            $result_obj = $this->db->get();
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            } 
            
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
    
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
}
