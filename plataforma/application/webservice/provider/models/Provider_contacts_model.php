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
    public function getprovidercontacts($provider_id = '',$user_id ='')
    {
        try
        {
            $result_arr = array();

            $this->db->from("distributor_provider_contact AS dpc");
            $this->db->select("dpc.iDistributorProviderContactId AS dpc_provider_contact_id");
            $this->db->select("dpc.vRoleProviderContact AS dpc_role_provider_contact");
            $this->db->select("dpc.vNameProviderContact AS dpc_name_provider_contact");
            $this->db->select("dpc.vEmailProviderContact AS dpc_email_provider_contact");
            $this->db->select("dpc.vPhoneProviderContact AS dpc_phone_provider_contact");
            
             if (isset($user_id) && $user_id != "")    
                $this->db->where("dpc.iUsersId =", $user_id ); 
           
             if (isset($provider_id) && $provider_id != "")           
                $this->db->where("dpc.iProviderId =", $provider_id);      

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        
        return $return_arr;
    }
}
