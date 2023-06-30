<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Users Model
 *
 * @category webservice
 *
 * @package user
 *
 * @subpackage models
 *
 * @module Users
 *
 * @class Users_model.php
 *
 * @path application\webservice\user\models\Users_model.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @since 12.10.2018
 */

class Users_model extends CI_Model
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
     * get_user_by_email method is used to execute database queries for Forgot Password API.
     * @created  | 29.01.2016
     * @modified Zubair Khan | 30.03.2017
     * @param string $email email is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_by_email($email = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.eStatus AS u_status");
            if (isset($email) && $email != "")
            {
                $this->db->where("u.vEmail =", $email);
            }

            $this->db->limit(1);

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

    /**
     * get_user_login_details method is used to execute database queries for User Login API.
     * @created Zubair Khan | 30.03.2017
     * @modified Dhananjay singh | 14.04.2017
     * @param string $email email is used to process query block.
     * @param string $password password is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_login_details($email = '', $password = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vImage AS u_image");
            $this->db->select("u.vContactNumber AS u_contact_number");
            $this->db->select("u.vUniqueID AS u_unique_id");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.iUserType AS u_user_type");
            if (isset($email) && $email != "")
            {
                $this->db->where("u.vEmail =", $email);
            }
            $this->db->where("u.vPassword = (MD5('".$password."'))", FALSE, FALSE);

            $this->db->limit(1);

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

    /**
     * last_login_date_update method is used to execute database queries for User Login API.
     * @created Zubair Khan | 30.03.2017
     * @modified Zubair Khan | 30.03.2017
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function last_login_date_update($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["u_users_id"]) && $where_arr["u_users_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["u_users_id"]);
            }

            $this->db->set($this->db->protect("dLastLoginDate"), $params_arr["_dlastlogindate"], FALSE);
            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * get_email_exists_or_not method is used to execute database queries for Email Exists Or Not API.
     * @created Narendra Sisodia | 30.03.2017
     * @modified Himanshu Dholakia | 04.04.2018
     * @param string $email email is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_email_exists_or_not($email = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            if (isset($email) && $email != "")
            {
                $this->db->where("u.vEmail =", $email);
            }

            $this->db->limit(1);

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

    /**
     * update_user_password method is used to execute database queries for Reset Password API.
     * @created Zubair Khan | 30.03.2017
     * @modified Zubair Khan | 30.03.2017
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_user_password($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["user_id"]);
            }
            if (isset($params_arr["password"]))
            {
                $this->db->set("vPassword", $params_arr["password"]);
            }
            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * create_user method is used to execute database queries for Provider Registeration API.
     * @created Narendra Sisodia | 30.03.2017
     * @modified Dhananjay singh | 19.01.2018
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function create_user($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["fax"]))
            {
                $this->db->set("vContactFaxNumber", $params_arr["fax"]);
            }
            if (isset($params_arr["phone"]))
            {
                $this->db->set("vContactNumber", $params_arr["phone"]);
            }
            if (isset($params_arr["business_hours"]))
            {
                $this->db->set("vBusinessHours", $params_arr["business_hours"]);
            }
            if (isset($params_arr["main_contact"]))
            {
                $this->db->set("vMainContact", $params_arr["main_contact"]);
            }
            if (isset($params_arr["company_name"]))
            {
                $this->db->set("vCompanyName", $params_arr["company_name"]);
            }
            if (isset($params_arr["legal_company_name"]))
            {
                $this->db->set("vLegalCompanyName", $params_arr["legal_company_name"]);
            }
            if (isset($params_arr["email"]))
            {
                $this->db->set("vEmail", $params_arr["email"]);
            }
            if (isset($params_arr["rfc_text_id"]))
            {
                $this->db->set("vRFCTaxID", $params_arr["rfc_text_id"]);
            }
            if (isset($params_arr["state_id"]))
            {
                $this->db->set("iStateId", $params_arr["state_id"]);
            }
            if (isset($params_arr["country_id"]))
            {
                $this->db->set("iCountryId", $params_arr["country_id"]);
            }
            if (isset($params_arr["street"]))
            {
                $this->db->set("vStreetName", $params_arr["street"]);
            }
            if (isset($params_arr["colony"]))
            {
                $this->db->set("vColonyName", $params_arr["colony"]);
            }
            $this->db->set("eStatus", $params_arr["_estatus"]);
            if (isset($params_arr["from_ip"]))
            {
                $this->db->set("vFromIP", $params_arr["from_ip"]);
            }
            if (isset($params_arr["user_type"]))
            {
                $this->db->set("iUserType", $params_arr["user_type"]);
            }
            if (isset($params_arr["legal_response"]))
            {
                $this->db->set("vName", $params_arr["legal_response"]);
            }
            $this->db->set($this->db->protect("vPassword"), $params_arr["password"], FALSE);
            if (isset($params_arr["activation_code"]))
            {
                $this->db->set("vActivationCode", $params_arr["activation_code"]);
            }
            $this->db->insert("users");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "insert_id";
            $result_arr[0][$result_param] = $insert_id;
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

    /**
     * check_user_email_exist method is used to execute database queries for Provider Registeration API.
     * @created Narendra Sisodia | 03.04.2017
     * @modified Narendra Sisodia | 03.04.2017
     * @param string $email email is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_user_email_exist($email = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS uc");

            $this->db->select("uc.iUsersId AS uc_users_id");
            if (isset($email) && $email != "")
            {
                $this->db->where("uc.vEmail =", $email);
            }

            $this->db->limit(1);

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

    /**
     * update_user_pin method is used to execute database queries for Provider Registeration API.
     * @created Dhananjay singh | 19.01.2018
     * @modified Himanshu Dholakia | 04.06.2018
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_user_pin($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["insert_id"]) && $where_arr["insert_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["insert_id"]);
            }
            if (isset($params_arr["pin"]))
            {
                $this->db->set("iPinNumber", $params_arr["pin"]);
            }
            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * check_user_exist method is used to execute database queries for Activate User API.
     * @created Narendra Sisodia | 31.03.2017
     * @modified Dhananjay singh | 02.06.2017
     * @param string $activation_code activation_code is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_user_exist($activation_code = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS users_id");
            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vCompanyName AS u_company_name");
            $this->db->select("(DATE_ADD(NOW(), INTERVAL 10 DAY)) AS expire_date", FALSE);
            $this->db->select("u.vEmail AS u_email");
            if (isset($activation_code) && $activation_code != "")
            {
                $this->db->where("u.vActivationCode =", $activation_code);
            }

            $this->db->limit(1);

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

    /**
     * update_user method is used to execute database queries for Activate User API.
     * @created Narendra Sisodia | 31.03.2017
     * @modified Himanshu Dholakia | 13.02.2018
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_user($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["users_id"]) && $where_arr["users_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["users_id"]);
            }

            $this->db->set("eIsEmailVerified", $params_arr["_eisemailverified"]);
            $this->db->set("eStatus", $params_arr["_estatus"]);
            if (isset($params_arr["activation_code"]))
            {
                $this->db->set("vActivationCode", $params_arr["activation_code"]);
            }
            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * query_1 method is used to execute database queries for Total Users API.
     * @created Snehabharati Waindeshkar | 03.04.2017
     * @modified Snehabharati Waindeshkar | 03.04.2017
     * @return array $return_arr returns response of query block.
     */
    public function query_1()
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

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

    /**
     * query_check_distributor_exists method is used to execute database queries for Distributor Registration API.
     * @created Pratik Kamble | 04.04.2017
     * @modified Himanshu Dholakia | 10.02.2018
     * @param string $distributor_email distributor_email is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function query_check_distributor_exists($distributor_email = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            $this->db->select("u.vEmail AS u_email");
            if (isset($distributor_email) && $distributor_email != "")
            {
                $this->db->where("u.vEmail =", $distributor_email);
            }

            $this->db->limit(1);

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

    /**
     * query_insert_distributor_data method is used to execute database queries for Distributor Registration API.
     * @created Pratik Kamble | 04.04.2017
     * @modified Himanshu Dholakia | 28.04.2018
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function query_insert_distributor_data($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["company_name"]))
            {
                $this->db->set("vCompanyName", $params_arr["company_name"]);
            }
            if (isset($params_arr["company_email"]))
            {
                $this->db->set("vCompanyEmail", $params_arr["company_email"]);
            }
            if (isset($params_arr["first_name"]))
            {
                $this->db->set("vName", $params_arr["first_name"]);
            }
            if (isset($params_arr["distributor_email"]))
            {
                $this->db->set("vEmail", $params_arr["distributor_email"]);
            }
            $this->db->set($this->db->protect("vPassword"), $params_arr["password"], FALSE);
            if (isset($params_arr["country"]))
            {
                $this->db->set("iCountryId", $params_arr["country"]);
            }
            if (isset($params_arr["state"]))
            {
                $this->db->set("iStateId", $params_arr["state"]);
            }
            if (isset($params_arr["city"]))
            {
                $this->db->set("vCity", $params_arr["city"]);
            }
            if (isset($params_arr["zipcode"]))
            {
                $this->db->set("vZipCode", $params_arr["zipcode"]);
            }
            $this->db->set("iUserType", $params_arr["_iusertype"]);
            if (isset($params_arr["contact"]))
            {
                $this->db->set("vContactNumber", $params_arr["contact"]);
            }
            if (isset($params_arr["user_status"]))
            {
                $this->db->set("eStatus", $params_arr["user_status"]);
            }
            $this->db->set($this->db->protect("vFromIP"), $params_arr["_vfromip"], FALSE);
            $this->db->set($this->db->protect("dAddedDate"), $params_arr["_daddeddate"], FALSE);
            if (isset($params_arr["contact"]))
            {
                $this->db->set("vCompamyContactNumber", $params_arr["contact"]);
            }
            if (isset($params_arr["email_veri_status"]))
            {
                $this->db->set("eIsEmailVerified", $params_arr["email_veri_status"]);
            }
            $this->db->set("eSalesPersonRole", $params_arr["_esalespersonrole"]);
            if (isset($params_arr["invoice_type"]))
            {
                $this->db->set("vInvoiceType", $params_arr["invoice_type"]);
            }
            if (isset($params_arr["last_name"]))
            {
                $this->db->set("vLastName", $params_arr["last_name"]);
            }
            if (isset($params_arr["commercial_company_name"]))
            {
                $this->db->set("vLegalCompanyName", $params_arr["commercial_company_name"]);
            }
            if (isset($params_arr["invoice_use"]))
            {
                $this->db->set("vInvoiceUse", $params_arr["invoice_use"]);
            }
            if (isset($params_arr["card_type"]))
            {
                $this->db->set("vCardType", $params_arr["card_type"]);
            }
            if (isset($params_arr["has_distributor_website"]))
            {
                $this->db->set("eWebPage", $params_arr["has_distributor_website"]);
            }
            if (isset($params_arr["distributor_website"]))
            {
                $this->db->set("vWebPageLink", $params_arr["distributor_website"]);
            }
            $this->db->insert("users");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "distributor_id";
            $result_arr[0][$result_param] = $insert_id;
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

    /**
     * query_update_activation_code method is used to execute database queries for Distributor Registration API.
     * @created Pratik Kamble | 04.04.2017
     * @modified Himanshu Dholakia | 12.10.2018
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function query_update_activation_code($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["distributor_id"]) && $where_arr["distributor_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["distributor_id"]);
            }

            $this->db->set("vActivationCode", $params_arr["_vactivationcode"]);
            if (isset($params_arr["u_users_id"]))
            {
                $this->db->set("vUniqueID", $params_arr["u_users_id"]);
            }
            if (isset($params_arr["distributor_id"]))
            {
                $this->db->set("vUCode", $params_arr["distributor_id"]);
            }
            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * query_get_activation_code method is used to execute database queries for Distributor Registration API.
     * @created Pratik Kamble | 04.04.2017
     * @modified Pratik Kamble | 04.04.2017
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function query_get_activation_code($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vActivationCode AS u_activation_code");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $distributor_id);
            }

            $this->db->limit(1);

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

    /**
     * update_pin method is used to execute database queries for Distributor Registration API.
     * @created Himanshu Dholakia | 29.01.2018
     * @modified Himanshu Dholakia | 12.10.2018
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_pin($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["distributor_id"]) && $where_arr["distributor_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["distributor_id"]);
            }
            if (isset($params_arr["pin"]))
            {
                $this->db->set("iPinNumber", $params_arr["pin"]);
            }
            if (isset($params_arr["distributor_id"]))
            {
                $this->db->set("iDistributorUserId", $params_arr["distributor_id"]);
            }
            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows1";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * update_free_pin method is used to execute database queries for Distributor Registration API.
     * @created Himanshu Dholakia | 29.01.2018
     * @modified Himanshu Dholakia | 12.10.2018
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_free_pin($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["distributor_id"]) && $where_arr["distributor_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["distributor_id"]);
            }
            if (isset($params_arr["free_pin"]))
            {
                $this->db->set("iPinNumber", $params_arr["free_pin"]);
            }
            if (isset($params_arr["distributor_id"]))
            {
                $this->db->set("iDistributorUserId", $params_arr["distributor_id"]);
            }
            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows2";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * check_user_email method is used to execute database queries for Provider Inquiry API.
     * @created Snehabharati Waindeshkar | 11.04.2017
     * @modified Snehabharati Waindeshkar | 11.04.2017
     * @param string $email email is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_user_email($email = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            $this->db->select("u.vEmail AS u_email");
            if (isset($email) && $email != "")
            {
                $this->db->where("u.vEmail =", $email);
            }

            $this->db->limit(1);

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

    /**
     * create_provider method is used to execute database queries for Provider Inquiry API.
     * @created Snehabharati Waindeshkar | 11.04.2017
     * @modified Himanshu Dholakia | 20.09.2018
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function create_provider($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["contact_responsible"]))
            {
                $this->db->set("vName", $params_arr["contact_responsible"]);
            }
            if (isset($params_arr["cell_phone"]))
            {
                $this->db->set("vContactNumber", $params_arr["cell_phone"]);
            }
            if (isset($params_arr["email"]))
            {
                $this->db->set("vEmail", $params_arr["email"]);
            }
            if (isset($params_arr["company_name"]))
            {
                $this->db->set("vCompanyName", $params_arr["company_name"]);
            }
            if (isset($params_arr["legal_company_name"]))
            {
                $this->db->set("vLegalCompanyName", $params_arr["legal_company_name"]);
            }
            if (isset($params_arr["rfc"]))
            {
                $this->db->set("vRFCTaxID", $params_arr["rfc"]);
            }
            if (isset($params_arr["web_page"]))
            {
                $this->db->set("vCompanyWebsite", $params_arr["web_page"]);
            }
            if (isset($params_arr["commercial_country_id"]))
            {
                $this->db->set("iCountryId", $params_arr["commercial_country_id"]);
            }
            if (isset($params_arr["commercial_state_id"]))
            {
                $this->db->set("iStateId", $params_arr["commercial_state_id"]);
            }
            if (isset($params_arr["commercial_city"]))
            {
                $this->db->set("vCity", $params_arr["commercial_city"]);
            }
            if (isset($params_arr["commercial_colony"]))
            {
                $this->db->set("vColonyName", $params_arr["commercial_colony"]);
            }
            if (isset($params_arr["commercial_postal_code"]))
            {
                $this->db->set("vZipCode", $params_arr["commercial_postal_code"]);
            }
            $this->db->set($this->db->protect("dAddedDate"), $params_arr["_daddeddate"], FALSE);
            $this->db->set($this->db->protect("dLastLoginDate"), $params_arr["_dlastlogindate"], FALSE);
            $this->db->set("iUserType", $params_arr["_iusertype"]);
            $this->db->set($this->db->protect("dModifiedDate"), $params_arr["_dmodifieddate"], FALSE);
            $this->db->set("eIsEmailVerified", $params_arr["_eisemailverified"]);
            if (isset($params_arr["fiscal_state_id"]))
            {
                $this->db->set("iFiscalStateId", $params_arr["fiscal_state_id"]);
            }
            if (isset($params_arr["fiscal_city"]))
            {
                $this->db->set("vFiscalCity", $params_arr["fiscal_city"]);
            }
            if (isset($params_arr["fiscal_colony"]))
            {
                $this->db->set("vFiscalColonyName", $params_arr["fiscal_colony"]);
            }
            if (isset($params_arr["fiscal_postal_code"]))
            {
                $this->db->set("vFiscalZipCode", $params_arr["fiscal_postal_code"]);
            }
            if (isset($params_arr["fiscal_country_id"]))
            {
                $this->db->set("iFiscalCountryId", $params_arr["fiscal_country_id"]);
            }
            if (isset($params_arr["fiscal_outside"]))
            {
                $this->db->set("vFiscalContactNumberExt", $params_arr["fiscal_outside"]);
            }
            if (isset($params_arr["fiscal_inside"]))
            {
                $this->db->set("vFiscalContactNumber", $params_arr["fiscal_inside"]);
            }
            if (isset($params_arr["fiscal_address"]))
            {
                $this->db->set("vFiscalStreetName", $params_arr["fiscal_address"]);
            }
            if (isset($params_arr["commercial_address"]))
            {
                $this->db->set("vStreetName", $params_arr["commercial_address"]);
            }
            if (isset($params_arr["commercial_inside"]))
            {
                $this->db->set("vCompamyContactNumber", $params_arr["commercial_inside"]);
            }
            if (isset($params_arr["commercial_outside"]))
            {
                $this->db->set("vCompamyContactNumberExt", $params_arr["commercial_outside"]);
            }
            $this->db->set("eStatus", $params_arr["_estatus"]);
            $this->db->set($this->db->protect("vPassword"), $params_arr["_vpassword"], FALSE);
            if (isset($params_arr["office_phone"]))
            {
                $this->db->set("vContactFaxNumber", $params_arr["office_phone"]);
            }
            if (isset($params_arr["contact_main_contact"]))
            {
                $this->db->set("vMainContact", $params_arr["contact_main_contact"]);
            }
            if (isset($params_arr["contact_alternate_email"]))
            {
                $this->db->set("vAlternateEmail", $params_arr["contact_alternate_email"]);
            }
            if (isset($params_arr["profile_image"]) && !empty($params_arr["profile_image"]))
            {
                $this->db->set("vImage", $params_arr["profile_image"]);
            }
            $this->db->insert("users");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "provider_id";
            $result_arr[0][$result_param] = $insert_id;
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

    /**
     * update_pro_pin method is used to execute database queries for Provider Inquiry API.
     * @created Himanshu Dholakia | 07.03.2018
     * @modified Himanshu Dholakia | 04.06.2018
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_pro_pin($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["provider_id"]) && $where_arr["provider_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["provider_id"]);
            }
            if (isset($params_arr["pin"]))
            {
                $this->db->set("iPinNumber", $params_arr["pin"]);
            }
            $this->db->set("vUniqueID", $params_arr["_vuniqueid"]);
            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * check_user method is used to execute database queries for Add Subscription Package Data API.
     * @created Himanshu Dholakia | 14.04.2017
     * @modified Dhananjay singh | 14.04.2017
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_user($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");
            $this->db->join("mod_country AS mc", "u.iCountryId = mc.iCountryId", "left");
            $this->db->join("mod_state AS ms", "u.iStateId = ms.iStateId", "left");

            $this->db->select("u.vName AS u_name");
            $this->db->select("u.iUserType AS u_user_type");
            $this->db->select("u.vUniqueID AS u_unique_id");
            $this->db->select("u.vContactNumber AS u_contact_number");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vCompanyName AS u_company_name");
            $this->db->select("u.vCompanyEmail AS u_company_email");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.iCountryId AS u_country_id");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.vStreetName AS u_street_name");
            $this->db->select("u.vColonyName AS u_colony_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("mc.vCountry AS mc_country");
            $this->db->select("ms.vState AS ms_state");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $distributor_id);
            }

            $this->db->limit(1);

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

    /**
     * update_provider_avg_rating method is used to execute database queries for Distributor Provider Give Star API.
     * @created Dhananjay singh | 22.04.2017
     * @modified Dhananjay singh | 22.04.2017
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_provider_avg_rating($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["provider_id"]) && $where_arr["provider_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["provider_id"]);
            }
            if (isset($params_arr["pr_avg_rating"]))
            {
                $this->db->set("fAvgRating", $params_arr["pr_avg_rating"]);
            }
            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows1";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * getproviderdata method is used to execute database queries for Distributor Add Provider API.
     * @created Himanshu Dholakia | 22.04.2017
     * @modified Dhananjay singh | 26.04.2017
     * @param string $provider_id provider_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function getproviderdata($provider_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vUniqueID AS u_unique_id");
            if (isset($provider_id) && $provider_id != "")
            {
                $this->db->where("u.iUsersId =", $provider_id);
            }

            $this->db->limit(1);

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

    /**
     * removeproviderfromusertable method is used to execute database queries for Distributor Remove Providers API.
     * @created Himanshu Dholakia | 25.04.2017
     * @modified Himanshu Dholakia | 25.04.2017
     * @param string $distributor_id distributor_id is used to process query block.
     * @param string $provider_id provider_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function removeproviderfromusertable($distributor_id = '', $provider_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->where_in("eProviderType", array('Custom'));
            $this->db->where("iUserType = (2)", FALSE, FALSE);
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("iDistributorUserId =", $distributor_id);
            }
            if (isset($provider_id) && $provider_id != "")
            {
                $this->db->where("iUsersId =", $provider_id);
            }
            $res = $this->db->delete("users");
            if (!$res)
            {
                throw new Exception("Failure in deletion.");
            }
            $affected_rows = $this->db->affected_rows();
            $result_param = "affected_rows1";
            $result_arr[0][$result_param] = $affected_rows;
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

    /**
     * get_user_detail method is used to execute database queries for Distributor Contact Us API.
     * @created Snehabharati Waindeshkar | 27.04.2017
     * @modified Snehabharati Waindeshkar | 01.06.2017
     * @param string $user_id user_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_detail($user_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vName AS u_name");
            if (isset($user_id) && $user_id != "")
            {
                $this->db->where("u.iUsersId =", $user_id);
            }

            $this->db->limit(1);

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

    /**
     * getproviders method is used to execute database queries for Distributor Get Advance Search Form Data API.
     * @created Himanshu Dholakia | 03.05.2017
     * @modified Dhananjay singh | 31.05.2017
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function getproviders($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");
            $join_condition = $this->db->protect("u.iUsersId")." = ".$this->db->protect("dpm.iProviderId")."  AND `dpm`.`iDistributorUserId`= '{%REQUESTY.distributor_id%}'";
            $this->db->join("distributor_provider_mapping AS dpm", $join_condition, "left", FALSE);
            $join_condition = $this->db->protect("u.iUsersId")." = ".$this->db->protect("phfd.iProviderId")."  AND `phfd`.`iDistributorId` = '{%REQUESTY.distributor_id%}'";
            $this->db->join("provider_hide_for_distributor AS phfd", $join_condition, "left", FALSE);

            $this->db->select("u.iUsersId AS u_users_id");
            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vUniqueID AS u_unique_id");
            $this->db->where("u.iUserType =", "2");
            $this->db->where_in("u.eStatus", array('Active'));
            $this->db->where_in("u.eIsEmailVerified", array('Yes'));
            $this->db->where("(phfd.iProviderHideForDistributorId IS NULL OR phfd.iProviderHideForDistributorId = '')", FALSE, FALSE);
            $this->db->where("( dpm.iDistributorUserId ='".$distributor_id."' OR (ISNULL(dpm.iDistributorUserId) AND u.eProviderType='Default') ) ", FALSE, FALSE);

            $this->db->order_by("dpm.iSeqNo", "asc");
            $this->db->order_by("u.eProviderType", "asc");

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

    /**
     * check_user_exists method is used to execute database queries for Add Cart Data API.
     * @created Himanshu Dholakia | 10.05.2017
     * @modified Himanshu Dholakia | 29.05.2017
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_user_exists($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vEmail AS u_email");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $distributor_id);
            }

            $this->db->limit(1);

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

    /**
     * getproviderdetails_v1 method is used to execute database queries for Distributor Provider Details API.
     * @created Himanshu Dholakia | 16.05.2017
     * @modified Himanshu Dholakia | 21.09.2018
     * @param string $provider_id provider_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function getproviderdetails_v1($provider_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");
            $this->db->join("user_provider_settings AS ups", "u.iUsersId = ups.iUserId", "left");
            $this->db->join("site_type_master AS stm", "ups.iCompanySizeID = stm.iSiteTypeMasterId", "left");
            $this->db->join("site_type_master AS stm1", "ups.iCompanyAgeID = stm1.iSiteTypeMasterId", "left");
            $this->db->join("mod_country AS mc", "u.iCountryId = mc.iCountryId", "left");
            $this->db->join("site_type_master AS stm2", "ups.iHearAboutUsID = stm2.iSiteTypeMasterId", "left");
            $this->db->join("mod_country AS mc1", "u.iFiscalCountryId = mc1.iCountryId", "left");
            $this->db->join("mod_state AS ms", "u.iStateId = ms.iStateId", "left");
            $this->db->join("mod_state AS ms1", "u.iFiscalStateId = ms1.iStateId", "left");

            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vUniqueID AS u_unique_id");
            $this->db->select("u.vContactNumber AS u_contact_number");
            $this->db->select("u.vContactFaxNumber AS u_contact_fax_number");
            $this->db->select("u.vBusinessHours AS u_business_hours");
            $this->db->select("u.vMainContact AS u_main_contact");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vCompanyName AS u_company_name");
            $this->db->select("u.vLegalCompanyName AS u_legal_company_name");
            $this->db->select("u.vRFCTaxID AS u_r_fctax_id");
            $this->db->select("u.vCompamyContactNumber AS u_compamy_contact_number");
            $this->db->select("u.vCompamyContactNumberExt AS u_compamy_contact_number_ext");
            $this->db->select("u.vCompanyLogo AS u_company_logo");
            $this->db->select("u.vCompanyEmail AS u_company_email");
            $this->db->select("u.vCompanyWebsite AS u_company_website");
            $this->db->select("u.vAlternateEmail AS u_alternate_email");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vStreetName AS u_street_name");
            $this->db->select("u.vColonyName AS u_colony_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.vImage AS u_image");
            $this->db->select("u.dAddedDate AS u_added_date");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.dLastLoginDate AS u_last_login_date");
            $this->db->select("u.vActivationCode AS u_activation_code");
            $this->db->select("u.vFiscalStreetName AS u_fiscal_street_name");
            $this->db->select("u.vFiscalContactNumber AS u_fiscal_contact_number");
            $this->db->select("u.vFiscalContactNumberExt AS u_fiscal_contact_number_ext");
            $this->db->select("u.vFiscalCity AS u_fiscal_city");
            $this->db->select("u.vFiscalColonyName AS u_fiscal_colony_name");
            $this->db->select("u.vFiscalZipCode AS u_fiscal_zip_code");
            $this->db->select("u.fAvgRating AS u_avg_rating");
            $this->db->select("u.eProviderType AS u_provider_type");
            $this->db->select("ups.iUserProviderSettingsId AS ups_user_provider_settings_id_1");
            $this->db->select("ups.eRushService AS ups_rush_service");
            $this->db->select("ups.eHaveFactory AS ups_have_factory");
            $this->db->select("ups.eDistributerDiscount AS ups_distributer_discount_1");
            $this->db->select("ups.fDistributerDiscountPer AS ups_distributer_discount_per_1");
            $this->db->select("ups.eOfferPrinting AS ups_offer_printing_1");
            $this->db->select("ups.iPrintingOptionsID AS ups_printing_options_id_1");
            $this->db->select("ups.ePrintingMachineAvailable AS ups_printing_machine_available_1");
            $this->db->select("u.iUsersId AS u_users_id");
            $this->db->select("ups.vPrintingMachineAvailableNotes AS ups_printing_machine_available_notes_1");
            $this->db->select("ups.tOfferDescForDistributor AS ups_offer_desc_for_distributor_1");
            $this->db->select("ups.vOtherPrintingOptions AS ups_other_printing_options_1");
            $this->db->select("ups.vOtherTypeOfProvider AS ups_other_type_of_provider");
            $this->db->select("ups.vInvoiceDocument AS ups_invoice_document");
            $this->db->select("ups.vRFCDocument AS ups_r_fcdocument");
            $this->db->select("ups.vProofAddressDocument AS ups_proof_address_document");
            $this->db->select("ups.vOtherHearAboutUs AS ups_other_hear_about_us");
            $this->db->select("stm.vTitle AS company_size");
            $this->db->select("stm1.vTitle AS company_age");
            $this->db->select("mc.vCountry AS mc_country");
            $this->db->select("stm2.vTitle AS hear_about_us");
            $this->db->select("ups.vTypeOfProviderID AS type_of_provider");
            $this->db->select("mc1.vCountry AS fiscal_country");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("ms.vState AS ms_state");
            $this->db->select("ms1.vState AS fiscal_state");
            $this->db->select("ups.vPaymentAcceptedID AS payment_method");
            $this->db->select("ups.iPrintingOptionsID AS printing_option");
            $this->db->select("ups.vGoogleDriveUrl AS ups_google_drive_url");
            $this->db->select("ups.vGoogleSheetUrl AS ups_google_sheet_url");
            if (isset($provider_id) && $provider_id != "")
            {
                $this->db->where("u.iUsersId =", $provider_id);
            }

            $this->db->limit(1);

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

    /**
     * getproviderdetails method is used to execute database queries for Add Distributor Contact Data API.
     * @created Himanshu Dholakia | 30.07.2018
     * @modified Himanshu Dholakia | 30.07.2018
     * @param string $provider_id provider_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function getproviderdetails($provider_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vCompanyName AS u_company_name");
            $this->db->select("u.vLegalCompanyName AS u_legal_company_name");
            $this->db->select("u.vEmail AS u_email");
            if (isset($provider_id) && $provider_id != "")
            {
                $this->db->where("u.iUsersId =", $provider_id);
            }

            $this->db->limit(1);

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

    /**
     * query_5 method is used to execute database queries for User Staticstics API.
     * @created Snehabharati Waindeshkar | 23.05.2017
     * @modified Snehabharati Waindeshkar | 23.05.2017
     * @return array $return_arr returns response of query block.
     */
    public function query_5()
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("(SELECT 'Admin' AS Actor ,(select count(iUsersId) FROM users where iUserType = '0' and eStatus='Active') as Active,(select count(iUsersId) FROM users where iUserType = '0' and eStatus='Inactive') as Inactive from users) AS Admin", FALSE);
            $this->db->select("(SELECT 'Distributor' AS Actor,(select count(iUsersId) FROM users where iUserType = '1' and eStatus='Active') as Active,(select count(iUsersId) FROM users where iUserType = '1' and eStatus='Inactive') as Inactive from users) AS Distributor", FALSE);
            $this->db->select("(".$this->db->escape("SELECT 'Provider' AS Actor,(select count(iUsersId) FROM users where iUserType = '3' and eStatus='Active') as Active,(select count(iUsersId) FROM users where iUserType = '3' and eStatus='Inactive') as Inactive from users").") AS provider", FALSE);
            $this->db->where_in("u.eStatus", array('Active'));

            $this->db->limit(1);

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

    /**
     * get_provider_detail method is used to execute database queries for send password to provider API.
     * @created Dhananjay singh | 25.05.2017
     * @modified Dhananjay singh | 25.05.2017
     * @param string $provider_id provider_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_provider_detail($provider_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vEmail AS u_email");
            if (isset($provider_id) && $provider_id != "")
            {
                $this->db->where("u.iUsersId =", $provider_id);
            }
            $this->db->where_in("u.eStatus", array('Active'));

            $this->db->limit(1);

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

    /**
     * get_distributor_details method is used to execute database queries for Distributor Details API.
     * @created Snehabharati Waindeshkar | 02.06.2017
     * @modified Himanshu Dholakia | 21.09.2018
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_distributor_details($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");
            $this->db->join("mod_country AS mc", "u.iCountryId = mc.iCountryId", "left");
            $this->db->join("mod_state AS ms", "u.iStateId = ms.iStateId", "left");

            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vUniqueID AS u_unique_id");
            $this->db->select("u.vContactNumber AS u_contact_number");
            $this->db->select("u.vCompanyName AS u_company_name");
            $this->db->select("u.vLegalCompanyName AS u_legal_company_name");
            $this->db->select("u.vRFCTaxID AS u_r_fctax_id");
            $this->db->select("u.vCompamyContactNumber AS u_compamy_contact_number");
            $this->db->select("u.vCompamyContactNumberExt AS u_compamy_contact_number_ext");
            $this->db->select("u.vCompanyLogo AS u_company_logo");
            $this->db->select("u.vCompanyEmail AS u_company_email");
            $this->db->select("u.vCompanyWebsite AS u_company_website");
            $this->db->select("mc.vCountry AS mc_country");
            $this->db->select("ms.vState AS ms_state");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.vStreetName AS u_street_name");
            $this->db->select("u.vColonyName AS u_colony_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("(u.vCompanyLogo) AS u_company_logo_full", FALSE);
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $distributor_id);
            }

            $this->db->limit(1);

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

    /**
     * getuser method is used to execute database queries for getQuotationRelatedData API.
     * @created Himanshu Dholakia | 14.06.2017
     * @modified Himanshu Dholakia | 14.06.2017
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function getuser($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $distributor_id);
            }

            $this->db->limit(1);

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

    /**
     * getsalesexecutive method is used to execute database queries for getQuotationRelatedData API.
     * @created Himanshu Dholakia | 14.06.2017
     * @modified Himanshu Dholakia | 14.06.2017
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function getsalesexecutive($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS sales_user_id");
            $this->db->select("u.vName AS sales_user_name");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iDistributorUserId =", $distributor_id);
            }
            $this->db->where("u.iUserType =", "3");
            $this->db->where_in("u.eStatus", array('Active'));

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

    /**
     * check_user_exists_or_not method is used to execute database queries for Add Quotation API.
     * @created Himanshu Dholakia | 15.06.2017
     * @modified Himanshu Dholakia | 15.06.2017
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_user_exists_or_not($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $distributor_id);
            }

            $this->db->limit(1);

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

    /**
     * check_user_existance method is used to execute database queries for getQuotations API.
     * @created Himanshu Dholakia | 15.06.2017
     * @modified Himanshu Dholakia | 15.06.2017
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_user_existance($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $distributor_id);
            }

            $this->db->limit(1);

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

    /**
     * get_userdetails method is used to execute database queries for Get Product Import Error Reports API.
     * @created Himanshu Dholakia | 23.06.2017
     * @modified Himanshu Dholakia | 23.06.2017
     * @param string $provider_id provider_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_userdetails($provider_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vName AS u_name");
            if (isset($provider_id) && $provider_id != "")
            {
                $this->db->where("u.iUsersId =", $provider_id);
            }

            $this->db->limit(1);

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

    /**
     * check_user_status method is used to execute database queries for send_url_request API.
     * @created Himanshu Dholakia | 10.08.2017
     * @modified Himanshu Dholakia | 21.09.2018
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_user_status($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");
            $this->db->join("user_distributor_setting AS uds", "u.iUsersId = uds.iUserId", "left");

            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vCompanyName AS u_company_name");
            $this->db->select("u.vCompanyLogo AS u_company_logo");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vContactNumber AS u_contact_number");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $distributor_id);
            }

            $this->db->limit(1);

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

    /**
     * getuserdetail method is used to execute database queries for Get Distributor Product Import Error Reports API.
     * @created Himanshu Dholakia | 11.08.2017
     * @modified Himanshu Dholakia | 11.08.2017
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function getuserdetail($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vName AS u_name");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $distributor_id);
            }

            $this->db->limit(1);

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

    /**
     * check_salesman_exist method is used to execute database queries for Add product in Quotation API.
     * @created Himanshu Dholakia | 13.09.2017
     * @modified Himanshu Dholakia | 13.09.2017
     * @param string $sales_man_id sales_man_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_salesman_exist($sales_man_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vName AS u_name");
            if (isset($sales_man_id) && $sales_man_id != "")
            {
                $this->db->where("u.iUsersId =", $sales_man_id);
            }

            $this->db->limit(1);

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

    /**
     * check_user_available method is used to execute database queries for Add New Quotation API.
     * @created Himanshu Dholakia | 14.09.2017
     * @modified Himanshu Dholakia | 14.09.2017
     * @param string $sales_person_id sales_person_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_user_available($sales_person_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vName AS u_name");
            if (isset($sales_person_id) && $sales_person_id != "")
            {
                $this->db->where("u.iUsersId =", $sales_person_id);
            }

            $this->db->limit(1);

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

    /**
     * check_dist_exists method is used to execute database queries for Add Distributor Subscription Data API.
     * @created Himanshu Dholakia | 18.09.2017
     * @modified Dhananjay singh | 20.09.2017
     * @param string $distributor_id distributor_id is used to process query block.
     * @param string $zoho_customer_id zoho_customer_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_dist_exists($distributor_id = '', $zoho_customer_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $distributor_id);
            }
            if (isset($zoho_customer_id) && $zoho_customer_id != "")
            {
                $this->db->where("u.vZohoCustomerId =", $zoho_customer_id);
            }

            $this->db->limit(1);

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

    /**
     * distributer_name method is used to execute database queries for update_distributor_subscription_data API.
     * @created Brijesh Parmar | 23.02.2018
     * @modified Brijesh Parmar | 23.02.2018
     * @param string $ds_distributor_id ds_distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function distributer_name($ds_distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vLastName AS u_last_name");
            if (isset($ds_distributor_id) && $ds_distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $ds_distributor_id);
            }

            $this->db->limit(1);

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

    /**
     * fetch_user_data method is used to execute database queries for Create Distributor Subscription Invoice API.
     * @created Raju Gundumenu | 20.09.2017
     * @modified Raju Gundumenu | 20.09.2017
     * @param string $zoho_customer_id zoho_customer_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function fetch_user_data($zoho_customer_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS users_id");
            if (isset($zoho_customer_id) && $zoho_customer_id != "")
            {
                $this->db->where("u.vZohoCustomerId =", $zoho_customer_id);
            }

            $this->db->limit(1);

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

    /**
     * check_dist method is used to execute database queries for Subscription Send Mail API.
     * @created Himanshu Dholakia | 22.09.2017
     * @modified Himanshu Dholakia | 22.09.2017
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_dist($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vZohoCustomerId AS u_zoho_customer_id");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vCompanyName AS u_company_name");
            $this->db->select("u.vPassword AS u_password");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.vZohoCustomerId =", $distributor_id);
            }

            $this->db->limit(1);

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

    /**
     * check_dist_exists_v1 method is used to execute database queries for Add Distributor New Subscription Data API.
     * @created CIT Dev Team
     * @modified ---
     * @param string $distributor_id distributor_id is used to process query block.
     * @param string $zoho_customer_id zoho_customer_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_dist_exists_v1($distributor_id = '', $zoho_customer_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $distributor_id);
            }
            if (isset($zoho_customer_id) && $zoho_customer_id != "")
            {
                $this->db->where("u.vZohoCustomerId =", $zoho_customer_id);
            }

            $this->db->limit(1);

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

    /**
     * check_dist_active method is used to execute database queries for Get Current Distributor Subscription API.
     * @created Himanshu Dholakia | 13.11.2017
     * @modified Himanshu Dholakia | 13.11.2017
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_dist_active($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.eSalesPersonRole AS u_sales_person_role");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $distributor_id);
            }

            $this->db->limit(1);

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

    /**
     * customer method is used to execute database queries for Get Customer Details API.
     * @created Himanshu Dholakia | 02.01.2018
     * @modified Himanshu Dholakia | 21.09.2018
     * @param string $customer_id customer_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function customer($customer_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vUniqueID AS u_unique_id");
            $this->db->select("u.vContactNumber AS u_contact_number");
            $this->db->select("u.vContactFaxNumber AS u_contact_fax_number");
            $this->db->select("u.vBusinessHours AS u_business_hours");
            $this->db->select("u.vMainContact AS u_main_contact");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vCompanyName AS u_company_name");
            $this->db->select("u.vLegalCompanyName AS u_legal_company_name");
            $this->db->select("u.vRFCTaxID AS u_r_fctax_id");
            $this->db->select("u.vCompamyContactNumber AS u_compamy_contact_number");
            $this->db->select("u.vCompamyContactNumberExt AS u_compamy_contact_number_ext");
            $this->db->select("u.vCompanyLogo AS u_company_logo");
            $this->db->select("u.vCompanyEmail AS u_company_email");
            $this->db->select("u.vCompanyWebsite AS u_company_website");
            $this->db->select("u.vAlternateEmail AS u_alternate_email");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vPassword AS u_password");
            $this->db->select("u.iCountryId AS u_country_id");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.vStreetName AS u_street_name");
            $this->db->select("u.vColonyName AS u_colony_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.vImage AS u_image");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.dAddedDate AS u_added_date");
            $this->db->select("u.dLastLoginDate AS u_last_login_date");
            $this->db->select("u.vActivationCode AS u_activation_code");
            $this->db->select("u.vFromIP AS u_from_ip");
            $this->db->select("u.iUserType AS u_user_type");
            $this->db->select("u.iDistributorUserId AS u_distributor_user_id");
            $this->db->select("u.dModifiedDate AS u_modified_date");
            $this->db->select("u.eIsEmailVerified AS u_is_email_verified");
            $this->db->select("u.vFiscalStreetName AS u_fiscal_street_name");
            $this->db->select("u.vFiscalContactNumber AS u_fiscal_contact_number");
            $this->db->select("u.vFiscalContactNumberExt AS u_fiscal_contact_number_ext");
            $this->db->select("u.iFiscalStateId AS u_fiscal_state_id");
            $this->db->select("u.vFiscalCity AS u_fiscal_city");
            $this->db->select("u.vFiscalColonyName AS u_fiscal_colony_name");
            $this->db->select("u.vFiscalZipCode AS u_fiscal_zip_code");
            $this->db->select("u.iFiscalCountryId AS u_fiscal_country_id");
            $this->db->select("u.fAvgRating AS u_avg_rating");
            $this->db->select("u.eProviderType AS u_provider_type");
            $this->db->select("u.iDistributorProviderMappingId AS u_distributor_provider_mapping_id");
            $this->db->select("u.vUCode AS u_u_code");
            $this->db->select("u.eSalesPersonRole AS u_sales_person_role");
            $this->db->select("u.vInvoiceType AS u_invoice_type");
            $this->db->select("u.vInvoiceUse AS u_invoice_use");
            $this->db->select("u.vCardType AS u_card_type");
            if (isset($customer_id) && $customer_id != "")
            {
                $this->db->where("u.vZohoCustomerId =", $customer_id);
            }
            $this->db->where_in("u.eStatus", array('Active'));

            $this->db->limit(1);

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

    /**
     * check_code method is used to execute database queries for Get All User Data API.
     * @created Himanshu Dholakia | 05.01.2018
     * @modified Himanshu Dholakia | 19.01.2018
     * @param string $user_code user_code is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_code($user_code = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            $this->db->select("u.iUserType AS u_user_type");
            if (isset($user_code) && $user_code != "")
            {
                $this->db->where("u.iPinNumber =", $user_code);
            }
            $this->db->where("".$user_code." > 0 and u.iPinNumber > 0", FALSE, FALSE);

            $this->db->limit(1);

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

    /**
     * get_user_data method is used to execute database queries for Get All User Data API.
     * @created Himanshu Dholakia | 05.01.2018
     * @modified Himanshu Dholakia | 19.01.2018
     * @return array $return_arr returns response of query block.
     */
    public function get_user_data()
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vUniqueID AS u_unique_id");
            $this->db->select("u.vContactNumber AS u_contact_number");
            $this->db->select("u.vContactFaxNumber AS u_contact_fax_number");
            $this->db->select("u.vBusinessHours AS u_business_hours");
            $this->db->select("u.vMainContact AS u_main_contact");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vCompanyName AS u_company_name");
            $this->db->select("u.vLegalCompanyName AS u_legal_company_name");
            $this->db->select("u.vRFCTaxID AS u_r_fctax_id");
            $this->db->select("u.vCompamyContactNumber AS u_compamy_contact_number");
            $this->db->select("u.vCompamyContactNumberExt AS u_compamy_contact_number_ext");
            $this->db->select("u.vCompanyEmail AS u_company_email");
            $this->db->select("u.vCompanyWebsite AS u_company_website");
            $this->db->select("u.vAlternateEmail AS u_alternate_email");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vPassword AS u_password");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.vStreetName AS u_street_name");
            $this->db->select("u.vColonyName AS u_colony_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.vActivationCode AS u_activation_code");
            $this->db->select("u.vFromIP AS u_from_ip");
            $this->db->select("u.eIsEmailVerified AS u_is_email_verified");
            $this->db->select("u.vFiscalStreetName AS u_fiscal_street_name");
            $this->db->select("u.vFiscalContactNumber AS u_fiscal_contact_number");
            $this->db->select("u.vFiscalContactNumberExt AS u_fiscal_contact_number_ext");
            $this->db->select("u.vFiscalCity AS u_fiscal_city");
            $this->db->select("u.vFiscalColonyName AS u_fiscal_colony_name");
            $this->db->select("u.vFiscalZipCode AS u_fiscal_zip_code");
            $this->db->select("u.iFiscalCountryId AS u_fiscal_country_id");
            $this->db->select("u.eProviderType AS u_provider_type");
            $this->db->select("u.vUCode AS u_u_code");
            $this->db->select("u.vZohoCustomerId AS u_zoho_customer_id");
            $this->db->select("u.eSalesPersonRole AS u_sales_person_role");
            $this->db->select("u.vInvoiceType AS u_invoice_type");
            $this->db->select("u.vInvoiceUse AS u_invoice_use");
            $this->db->select("u.vCardType AS u_card_type");
            $this->db->select("u.dAddedDate AS u_added_date");
            $this->db->select("u.iPinNumber AS u_pin_number");

            $this->db->limit(1);

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

    /**
     * get_salesman_or_admin_details method is used to execute database queries for Add salesman API.
     * @created Sai Chandra Malladi | 24.01.2018
     * @modified  | 25.01.2018
     * @param string $user_id user_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_salesman_or_admin_details($user_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vEmail AS salesman_email");
            $this->db->select("u.iDistributorUserId AS distributor_id");
            $this->db->select("u.iUserType AS user_type");
            $this->db->select("u.eSalesPersonRole AS sales_person_role");
            $this->db->select("u.vName AS salesman_name");
            if (isset($user_id) && $user_id != "")
            {
                $this->db->where("u.iUsersId =", $user_id);
            }
            $this->db->where_in("u.eStatus", array('Active'));
            $this->db->where("u.iDistributorUserId >", "0");
            $this->db->where("u.iUserType =", "3");

            $this->db->limit(1);

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

    /**
     * check_distributor_or_admin method is used to execute database queries for Add salesman API.
     * @created Sai Chandra Malladi | 24.01.2018
     * @modified Sai Chandra Malladi | 24.01.2018
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_distributor_or_admin($distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vEmail AS distributor_admin_email");
            $this->db->select("u.iDistributorUserId AS distributor_user_id");
            if (isset($distributor_id) && $distributor_id != "")
            {
                $this->db->where("u.iUsersId =", $distributor_id);
            }
            $this->db->where_in("u.eStatus", array('Active'));
            $this->db->where("u.iUserType =", "3");
            $this->db->where_in("u.eSalesPersonRole", array('Admin'));

            $this->db->limit(1);

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

    /**
     * get_distributor_mail_id method is used to execute database queries for Add salesman API.
     * @created Sai Chandra Malladi | 24.01.2018
     * @modified Sai Chandra Malladi | 24.01.2018
     * @param string $distributor_user_id distributor_user_id is used to process query block.
     * @param string $distributor_id distributor_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_distributor_mail_id($distributor_user_id = '', $distributor_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vEmail AS distributor_email");
            $this->db->select("u.vName AS distributor_name");
            $this->db->where("u.iUsersId = '".$distributor_user_id."' OR u.iUsersId = '".$distributor_id."'", FALSE, FALSE);

            $this->db->limit(1);

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

    /**
     * get_provider_details method is used to execute database queries for Add Partner Commission Admin API.
     * @created Akash Chopda | 22.03.2018
     * @modified Akash Chopda | 22.03.2018
     * @param string $provider_id provider_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_provider_details($provider_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vCompanyName AS provider_company_name");
            if (isset($provider_id) && $provider_id != "")
            {
                $this->db->where("u.iUsersId =", $provider_id);
            }

            $this->db->limit(1);

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

    /**
     * check_number_exist_or_not method is used to execute database queries for check_number_duplicacy API.
     * @created Himanshu Dholakia | 04.04.2018
     * @modified Himanshu Dholakia | 04.04.2018
     * @param string $number number is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_number_exist_or_not($number = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            if (isset($number) && $number != "")
            {
                $this->db->where("u.vContactNumber =", $number);
            }

            $this->db->limit(1);

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

    /**
     * check_company_name_exists method is used to execute database queries for check_company_duplicacy API.
     * @created Himanshu Dholakia | 04.04.2018
     * @modified Himanshu Dholakia | 04.04.2018
     * @param string $company company is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_company_name_exists($company = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            if (isset($company) && $company != "")
            {
                $this->db->where("u.vCompanyName =", $company);
            }

            $this->db->limit(1);

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

    /**
     * update_print_shop_status method is used to execute database queries for Approve Print Shop API.
     * @created Akash Chopda | 12.04.2018
     * @modified Brijesh Parmar | 12.06.2018
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_print_shop_status($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["user_id"]);
            }

            $this->db->set("eStatus", $params_arr["_estatus"]);
            $this->db->set($this->db->protect("dModifiedDate"), $params_arr["_dmodifieddate"], FALSE);
            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * check_printshop_exists method is used to execute database queries for Approve Print Shop API.
     * @created Himanshu Dholakia | 30.05.2018
     * @modified Brijesh Parmar | 12.06.2018
     * @param string $user_id user_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_printshop_exists($user_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vName AS u_name");
            if (isset($user_id) && $user_id != "")
            {
                $this->db->where("u.iUsersId =", $user_id);
            }

            $this->db->limit(1);

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

    /**
     * get_print_shop_details method is used to execute database queries for Print Shop Details API.
     * @created Akash Chopda | 13.04.2018
     * @modified Himanshu Dholakia | 21.09.2018
     * @param string $provider_id provider_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_print_shop_details($provider_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");
            $this->db->join("user_print_shop_settings AS pss", "u.iUsersId = pss.iUserId", "left");
            $this->db->join("site_type_master AS stm", "pss.iCompanySizeID = stm.iSiteTypeMasterId", "left");
            $this->db->join("site_type_master AS stm1", "pss.iCompanyAgeID = stm1.iSiteTypeMasterId", "left");
            $this->db->join("mod_country AS mc", "u.iCountryId = mc.iCountryId", "left");
            $this->db->join("mod_state AS ms", "u.iStateId = ms.iStateId", "left");
            $this->db->join("mod_state AS ms1", "u.iFiscalStateId = ms1.iStateId", "left");
            $this->db->join("mod_country AS mc1", "u.iFiscalCountryId = mc1.iCountryId", "left");
            $this->db->join("site_type_master AS stm2", "pss.iHearAboutUsID = stm2.iSiteTypeMasterId", "left");

            $this->db->select("u.iUsersId AS u_users_id");
            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vContactNumber AS u_contact_number");
            $this->db->select("u.vContactFaxNumber AS u_contact_fax_number");
            $this->db->select("u.vBusinessHours AS u_business_hours");
            $this->db->select("u.vMainContact AS u_main_contact");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vCompanyName AS u_company_name");
            $this->db->select("u.vLegalCompanyName AS u_legal_company_name");
            $this->db->select("u.vRFCTaxID AS u_r_fctax_id");
            $this->db->select("u.vCompamyContactNumber AS u_compamy_contact_number");
            $this->db->select("u.vCompamyContactNumberExt AS u_compamy_contact_number_ext");
            $this->db->select("u.vCompanyLogo AS u_company_logo");
            $this->db->select("u.vCompanyEmail AS u_company_email");
            $this->db->select("u.vCompanyWebsite AS u_company_website");
            $this->db->select("u.vAlternateEmail AS u_alternate_email");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.vColonyName AS u_colony_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.dAddedDate AS u_added_date");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.dLastLoginDate AS u_last_login_date");
            $this->db->select("u.vActivationCode AS u_activation_code");
            $this->db->select("u.vFiscalCity AS u_fiscal_city");
            $this->db->select("u.vFiscalColonyName AS u_fiscal_colony_name");
            $this->db->select("u.vFiscalZipCode AS u_fiscal_zip_code");
            $this->db->select("u.fAvgRating AS u_avg_rating");
            $this->db->select("u.eProviderType AS u_provider_type");
            $this->db->select("stm.vTitle AS company_size");
            $this->db->select("stm1.vTitle AS company_age");
            $this->db->select("mc.vCountry AS mc_country");
            $this->db->select("ms.vState AS ms_state");
            $this->db->select("ms1.vState AS fiscal_state");
            $this->db->select("u.vUniqueID AS u_unique_id");
            $this->db->select("pss.eOfferPrinting AS offer_printing");
            $this->db->select("pss.vPrintTechniques AS print_techniques");
            $this->db->select("u.vStreetName AS u_street_name");
            $this->db->select("u.vFiscalStreetName AS u_fiscal_street_name");
            $this->db->select("mc1.vCountry AS fiscal_country");
            $this->db->select("u.vFiscalContactNumber AS u_fiscal_contact_number");
            $this->db->select("u.vFiscalContactNumberExt AS u_fiscal_contact_number_ext");
            $this->db->select("pss.tOfferDescForPrintShop AS offer_desc_for_print_shop");
            $this->db->select("pss.vOtherHearAboutUs AS pss_other_hear_about_us");
            $this->db->select("stm2.vTitle AS hear_about_us");
            $this->db->select("pss.vInvoiceDocument AS ups_invoice_document");
            $this->db->select("pss.vRFCDocument AS ups_r_fcdocument");
            $this->db->select("pss.vProofAddressDocument AS ups_proof_address_document");
            if (isset($provider_id) && $provider_id != "")
            {
                $this->db->where("u.iUsersId =", $provider_id);
            }

            $this->db->limit(1);

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

    /**
     * check_user_email_print_shop method is used to execute database queries for Print Shop Enquiry API.
     * @created CIT Dev Team
     * @modified Akash Chopda | 14.04.2018
     * @param string $email email is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_user_email_print_shop($email = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            $this->db->select("u.vEmail AS u_email");
            if (isset($email) && $email != "")
            {
                $this->db->where("u.vEmail =", $email);
            }

            $this->db->limit(1);

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

    /**
     * create_print_shop method is used to execute database queries for Print Shop Enquiry API.
     * @created CIT Dev Team
     * @modified Akash Chopda | 14.04.2018
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function create_print_shop($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["contact_responsible"]))
            {
                $this->db->set("vName", $params_arr["contact_responsible"]);
            }
            if (isset($params_arr["cell_phone"]))
            {
                $this->db->set("vContactNumber", $params_arr["cell_phone"]);
            }
            if (isset($params_arr["email"]))
            {
                $this->db->set("vEmail", $params_arr["email"]);
            }
            if (isset($params_arr["company_name"]))
            {
                $this->db->set("vCompanyName", $params_arr["company_name"]);
            }
            if (isset($params_arr["legal_company_name"]))
            {
                $this->db->set("vLegalCompanyName", $params_arr["legal_company_name"]);
            }
            if (isset($params_arr["rfc"]))
            {
                $this->db->set("vRFCTaxID", $params_arr["rfc"]);
            }
            if (isset($params_arr["web_page"]))
            {
                $this->db->set("vCompanyWebsite", $params_arr["web_page"]);
            }
            if (isset($params_arr["commercial_country_id"]))
            {
                $this->db->set("iCountryId", $params_arr["commercial_country_id"]);
            }
            if (isset($params_arr["commercial_state_id"]))
            {
                $this->db->set("iStateId", $params_arr["commercial_state_id"]);
            }
            if (isset($params_arr["commercial_city"]))
            {
                $this->db->set("vCity", $params_arr["commercial_city"]);
            }
            if (isset($params_arr["commercial_colony"]))
            {
                $this->db->set("vColonyName", $params_arr["commercial_colony"]);
            }
            if (isset($params_arr["commercial_postal_code"]))
            {
                $this->db->set("vZipCode", $params_arr["commercial_postal_code"]);
            }
            $this->db->set($this->db->protect("dAddedDate"), $params_arr["_daddeddate"], FALSE);
            $this->db->set($this->db->protect("dLastLoginDate"), $params_arr["_dlastlogindate"], FALSE);
            $this->db->set("iUserType", $params_arr["_iusertype"]);
            $this->db->set($this->db->protect("dModifiedDate"), $params_arr["_dmodifieddate"], FALSE);
            $this->db->set("eIsEmailVerified", $params_arr["_eisemailverified"]);
            if (isset($params_arr["fiscal_state_id"]))
            {
                $this->db->set("iFiscalStateId", $params_arr["fiscal_state_id"]);
            }
            if (isset($params_arr["fiscal_city"]))
            {
                $this->db->set("vFiscalCity", $params_arr["fiscal_city"]);
            }
            if (isset($params_arr["fiscal_colony"]))
            {
                $this->db->set("vFiscalColonyName", $params_arr["fiscal_colony"]);
            }
            if (isset($params_arr["fiscal_postal_code"]))
            {
                $this->db->set("vFiscalZipCode", $params_arr["fiscal_postal_code"]);
            }
            if (isset($params_arr["fiscal_country_id"]))
            {
                $this->db->set("iFiscalCountryId", $params_arr["fiscal_country_id"]);
            }
            if (isset($params_arr["fiscal_outside"]))
            {
                $this->db->set("vFiscalContactNumberExt", $params_arr["fiscal_outside"]);
            }
            if (isset($params_arr["fiscal_inside"]))
            {
                $this->db->set("vFiscalContactNumber", $params_arr["fiscal_inside"]);
            }
            if (isset($params_arr["fiscal_address"]))
            {
                $this->db->set("vFiscalStreetName", $params_arr["fiscal_address"]);
            }
            if (isset($params_arr["commercial_address"]))
            {
                $this->db->set("vStreetName", $params_arr["commercial_address"]);
            }
            if (isset($params_arr["commercial_inside"]))
            {
                $this->db->set("vCompamyContactNumber", $params_arr["commercial_inside"]);
            }
            if (isset($params_arr["commercial_outside"]))
            {
                $this->db->set("vCompamyContactNumberExt", $params_arr["commercial_outside"]);
            }
            $this->db->set("eStatus", $params_arr["_estatus"]);
            $this->db->set($this->db->protect("vPassword"), $params_arr["_vpassword"], FALSE);
            if (isset($params_arr["office_phone"]))
            {
                $this->db->set("vContactFaxNumber", $params_arr["office_phone"]);
            }
            if (isset($params_arr["contact_main_contact"]))
            {
                $this->db->set("vMainContact", $params_arr["contact_main_contact"]);
            }
            if (isset($params_arr["contact_alternate_email"]))
            {
                $this->db->set("vAlternateEmail", $params_arr["contact_alternate_email"]);
            }
            $this->db->set("vFromIP", $params_arr["_vfromip"]);
            $this->db->insert("users");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "provider_id";
            $result_arr[0][$result_param] = $insert_id;
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

    /**
     * update_pro_pin_new method is used to execute database queries for Print Shop Enquiry API.
     * @created CIT Dev Team
     * @modified Akash Chopda | 14.04.2018
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_pro_pin_new($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["provider_id"]) && $where_arr["provider_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["provider_id"]);
            }
            if (isset($params_arr["pin"]))
            {
                $this->db->set("iPinNumber", $params_arr["pin"]);
            }
            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * update_print_shop_status_reject method is used to execute database queries for Reject Print Shop API.
     * @created CIT Dev Team
     * @modified Akash Chopda | 14.04.2018
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_print_shop_status_reject($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["user_id"]);
            }

            $this->db->set("eStatus", $params_arr["_estatus"]);
            $this->db->set($this->db->protect("dModifiedDate"), $params_arr["_dmodifieddate"], FALSE);
            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * check_user_email_v1 method is used to execute database queries for Provider Inquiry printshop API.
     * @created CIT Dev Team
     * @modified Brijesh Parmar | 28.04.2018
     * @param string $email email is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_user_email_v1($email = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUsersId AS u_users_id");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vPassword AS u_password");
            if (isset($email) && $email != "")
            {
                $this->db->where("u.vEmail =", $email);
            }

            $this->db->limit(1);

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

    /**
     * create_provider_v1 method is used to execute database queries for Provider Inquiry printshop API.
     * @created CIT Dev Team
     * @modified Brijesh Parmar | 28.04.2018
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function create_provider_v1($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["contact_responsible"]))
            {
                $this->db->set("vName", $params_arr["contact_responsible"]);
            }
            if (isset($params_arr["cell_phone"]))
            {
                $this->db->set("vContactNumber", $params_arr["cell_phone"]);
            }
            if (isset($params_arr["email"]))
            {
                $this->db->set("vEmail", $params_arr["email"]);
            }
            if (isset($params_arr["company_name"]))
            {
                $this->db->set("vCompanyName", $params_arr["company_name"]);
            }
            if (isset($params_arr["legal_company_name"]))
            {
                $this->db->set("vLegalCompanyName", $params_arr["legal_company_name"]);
            }
            if (isset($params_arr["rfc"]))
            {
                $this->db->set("vRFCTaxID", $params_arr["rfc"]);
            }
            if (isset($params_arr["web_page"]))
            {
                $this->db->set("vCompanyWebsite", $params_arr["web_page"]);
            }
            if (isset($params_arr["commercial_country_id"]))
            {
                $this->db->set("iCountryId", $params_arr["commercial_country_id"]);
            }
            if (isset($params_arr["commercial_state_id"]))
            {
                $this->db->set("iStateId", $params_arr["commercial_state_id"]);
            }
            if (isset($params_arr["commercial_city"]))
            {
                $this->db->set("vCity", $params_arr["commercial_city"]);
            }
            if (isset($params_arr["commercial_colony"]))
            {
                $this->db->set("vColonyName", $params_arr["commercial_colony"]);
            }
            if (isset($params_arr["commercial_postal_code"]))
            {
                $this->db->set("vZipCode", $params_arr["commercial_postal_code"]);
            }
            $this->db->set($this->db->protect("dAddedDate"), $params_arr["_daddeddate"], FALSE);
            $this->db->set($this->db->protect("dLastLoginDate"), $params_arr["_dlastlogindate"], FALSE);
            $this->db->set("iUserType", $params_arr["_iusertype"]);
            $this->db->set($this->db->protect("dModifiedDate"), $params_arr["_dmodifieddate"], FALSE);
            $this->db->set("eIsEmailVerified", $params_arr["_eisemailverified"]);
            if (isset($params_arr["fiscal_state_id"]))
            {
                $this->db->set("iFiscalStateId", $params_arr["fiscal_state_id"]);
            }
            if (isset($params_arr["fiscal_city"]))
            {
                $this->db->set("vFiscalCity", $params_arr["fiscal_city"]);
            }
            if (isset($params_arr["fiscal_colony"]))
            {
                $this->db->set("vFiscalColonyName", $params_arr["fiscal_colony"]);
            }
            if (isset($params_arr["fiscal_postal_code"]))
            {
                $this->db->set("vFiscalZipCode", $params_arr["fiscal_postal_code"]);
            }
            if (isset($params_arr["fiscal_country_id"]))
            {
                $this->db->set("iFiscalCountryId", $params_arr["fiscal_country_id"]);
            }
            if (isset($params_arr["fiscal_outside"]))
            {
                $this->db->set("vFiscalContactNumberExt", $params_arr["fiscal_outside"]);
            }
            if (isset($params_arr["fiscal_inside"]))
            {
                $this->db->set("vFiscalContactNumber", $params_arr["fiscal_inside"]);
            }
            if (isset($params_arr["fiscal_address"]))
            {
                $this->db->set("vFiscalStreetName", $params_arr["fiscal_address"]);
            }
            if (isset($params_arr["commercial_address"]))
            {
                $this->db->set("vStreetName", $params_arr["commercial_address"]);
            }
            if (isset($params_arr["commercial_inside"]))
            {
                $this->db->set("vCompamyContactNumber", $params_arr["commercial_inside"]);
            }
            if (isset($params_arr["commercial_outside"]))
            {
                $this->db->set("vCompamyContactNumberExt", $params_arr["commercial_outside"]);
            }
            $this->db->set("eStatus", $params_arr["_estatus"]);
            $this->db->set($this->db->protect("vPassword"), $params_arr["_vpassword"], FALSE);
            if (isset($params_arr["office_phone"]))
            {
                $this->db->set("vContactFaxNumber", $params_arr["office_phone"]);
            }
            if (isset($params_arr["contact_main_contact"]))
            {
                $this->db->set("vMainContact", $params_arr["contact_main_contact"]);
            }
            if (isset($params_arr["contact_alternate_email"]))
            {
                $this->db->set("vAlternateEmail", $params_arr["contact_alternate_email"]);
            }
            $this->db->insert("users");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "provider_id";
            $result_arr[0][$result_param] = $insert_id;
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

    /**
     * update_pro_pin_v1 method is used to execute database queries for Provider Inquiry printshop API.
     * @created CIT Dev Team
     * @modified Brijesh Parmar | 28.04.2018
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_pro_pin_v1($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["provider_id"]) && $where_arr["provider_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["provider_id"]);
            }
            if (isset($params_arr["pin"]))
            {
                $this->db->set("iPinNumber", $params_arr["pin"]);
            }
            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * get_user_list method is used to execute database queries for Get User API.
     * @created Himanshu Dholakia | 15.05.2018
     * @modified Himanshu Dholakia | 15.05.2018
     * @param string $user_id user_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_list($user_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vUniqueID AS u_unique_id");
            $this->db->select("u.vContactNumber AS u_contact_number");
            $this->db->select("u.vContactFaxNumber AS u_contact_fax_number");
            $this->db->select("u.vBusinessHours AS u_business_hours");
            if (isset($user_id) && $user_id != "")
            {
                $this->db->where("u.iUsersId =", $user_id);
            }

            $this->db->limit(1);

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

    /**
     * get_user_email method is used to execute database queries for cancel_color_request_send_mail API.
     * @created Devangi Nirmal | 11.07.2018
     * @modified Devangi Nirmal | 11.07.2018
     * @param string $user_id user_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_email($user_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vEmail AS u_email");
            if (isset($user_id) && $user_id != "")
            {
                $this->db->where("u.iUsersId =", $user_id);
            }

            $this->db->limit(1);

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

    /**
     * get_user_details method is used to execute database queries for cancel_material_request_send_mail API.
     * @created Devangi Nirmal | 11.07.2018
     * @modified Devangi Nirmal | 11.07.2018
     * @param string $user_id user_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_details($user_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vName AS u_name");
            $this->db->select("u.vEmail AS u_email");
            if (isset($user_id) && $user_id != "")
            {
                $this->db->where("u.iUsersId =", $user_id);
            }

            $this->db->limit(1);

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
