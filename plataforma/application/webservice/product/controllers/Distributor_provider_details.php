<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Distributor Provider Details Controller
 *
 * @category webservice
 *
 * @package product
 *
 * @subpackage controllers
 *
 * @module Distributor Provider Details
 *
 * @class Distributor_provider_details.php
 *
 * @path application\webservice\product\controllers\Distributor_provider_details.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @since 29.07.2017
 */

class Distributor_provider_details extends Cit_Controller
{
    public $settings_params;
    public $output_params;
    public $single_keys;
    public $multiple_keys;
    public $block_result;

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->settings_params = array();
        $this->output_params = array();
        $this->single_keys = array(
            "getproviderdetails_v1",
        );
        $this->multiple_keys = array(
            "getprovidercontacts",
            "getproviderpolicies",
            "get_provider_reff",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model("user/users_model");
        $this->load->model("provider/provider_contacts_model");
        $this->load->model("provider/provider_policies_model");
        $this->load->model("provider/provider_contact_master_model");
    }

    /**
     * rules_distributor_provider_details method is used to validate api input params.
     * @created Himanshu Dholakia | 16.05.2017
     * @modified Himanshu Dholakia | 29.07.2017
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_distributor_provider_details($request_arr = array())
    {
        $valid_arr = array(
            "provider_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "provider_id_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "distributor_provider_details");

        return $valid_res;
    }

    /**
     * start_distributor_provider_details method is used to initiate api execution flow.
     * @created Himanshu Dholakia | 16.05.2017
     * @modified Himanshu Dholakia | 29.07.2017
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_distributor_provider_details($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_distributor_provider_details($request_arr);
            if ($validation_res["success"] == "-5")
            {
                if ($inner_api === TRUE)
                {
                    return $validation_res;
                }
                else
                {
                    $this->wsresponse->sendValidationResponse($validation_res);
                }
            }
            $output_response = array();
            $input_params = $validation_res['input_params'];
            $output_array = $func_array = array();

            $input_params = $this->getproviderdetails_v1($input_params);

            $condition_res = $this->condition($input_params);
            if ($condition_res["success"])
            {

                $input_params = $this->getprovidercontacts($input_params);

                $input_params = $this->getproviderpolicies($input_params);

                $input_params = $this->get_provider_reff($input_params);

                $output_response = $this->product_master_finish_success_2($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->users_finish_success($input_params);
                return $output_response;
            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }

    /**
     * getproviderdetails_v1 method is used to process query block.
     * @created Himanshu Dholakia | 16.05.2017
     * @modified Himanshu Dholakia | 29.07.2017
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function getproviderdetails_v1($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $provider_id = isset($input_params["provider_id"]) ? $input_params["provider_id"] : "";
            $this->block_result = $this->users_model->getproviderdetails_v1($provider_id);
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
            $result_arr = $this->block_result["data"];
            if (is_array($result_arr) && count($result_arr) > 0)
            {
                $i = 0;
                foreach ($result_arr as $data_key => $data_arr)
                {

                    $data = $data_arr["u_company_logo"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["height"] = "50";
                    $image_arr["width"] = "50";
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["path"] = $this->general->getImageNestedFolders("company_logo");
                    $data = $this->general->get_image($image_arr);

                    $result_arr[$data_key]["u_company_logo"] = $data;

                    $data = $data_arr["ups_invoice_document"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $p_key = ($data_arr["u_users_id"] != "") ? $data_arr["u_users_id"] : $input_params["u_users_id"];
                    $image_arr["pk"] = $p_key;
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = $this->general->getImageNestedFolders("provider_documents");
                    $data = $this->general->get_image($image_arr);

                    $result_arr[$data_key]["ups_invoice_document"] = $data;

                    $data = $data_arr["ups_r_fcdocument"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $p_key = ($data_arr["u_users_id"] != "") ? $data_arr["u_users_id"] : $input_params["u_users_id"];
                    $image_arr["pk"] = $p_key;
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = $this->general->getImageNestedFolders("provider_documents");
                    $data = $this->general->get_image($image_arr);

                    $result_arr[$data_key]["ups_r_fcdocument"] = $data;

                    $data = $data_arr["ups_proof_address_document"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $p_key = ($data_arr["u_users_id"] != "") ? $data_arr["u_users_id"] : $input_params["u_users_id"];
                    $image_arr["pk"] = $p_key;
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = $this->general->getImageNestedFolders("provider_documents");
                    $data = $this->general->get_image($image_arr);

                    $result_arr[$data_key]["ups_proof_address_document"] = $data;

                    $data = $data_arr["type_of_provider"];
                    if (method_exists($this->general, "get_sitetype_masterdata"))
                    {
                        $data = $this->general->get_sitetype_masterdata($data, $result_arr[$data_key], $i, $input_params);
                    }
                    $result_arr[$data_key]["type_of_provider"] = $data;

                    $data = $data_arr["payment_method"];
                    if (method_exists($this->general, "get_sitetype_masterdata"))
                    {
                        $data = $this->general->get_sitetype_masterdata($data, $result_arr[$data_key], $i, $input_params);
                    }
                    $result_arr[$data_key]["payment_method"] = $data;

                    $data = $data_arr["printing_option"];
                    if (method_exists($this->general, "get_sitetype_masterdata"))
                    {
                        $data = $this->general->get_sitetype_masterdata($data, $result_arr[$data_key], $i, $input_params);
                    }
                    $result_arr[$data_key]["printing_option"] = $data;

                    $i++;
                }
                $this->block_result["data"] = $result_arr;
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["getproviderdetails_v1"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * condition method is used to process conditions.
     * @created Himanshu Dholakia | 16.05.2017
     * @modified Himanshu Dholakia | 16.05.2017
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function condition($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["getproviderdetails_v1"]) ? 0 : 1);
            $cc_ro_0 = 1;

            $cc_fr_0 = ($cc_lo_0 == $cc_ro_0) ? TRUE : FALSE;
            if (!$cc_fr_0)
            {
                throw new Exception("Some conditions does not match.");
            }
            $success = 1;
            $message = "Conditions matched.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        return $this->block_result;
    }

    /**
     * getprovidercontacts method is used to process query block.
     * @created Himanshu Dholakia | 16.05.2017
     * @modified Himanshu Dholakia | 21.06.2017
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function getprovidercontacts($input_params = array())
    {

        $this->block_result = array();
        try
        {
            $provider_id = isset($input_params["provider_id"]) ? $input_params["provider_id"] : "";
            $user_id = isset($input_params["user_id"]) ? $input_params["user_id"] : "";
            
            $provider_id = isset($input_params["provider_id"]) ? $input_params["provider_id"] : "";
            $this->block_result = $this->provider_contacts_model->getprovidercontacts($provider_id, $user_id );;
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["getprovidercontacts"] = $this->block_result["data"];

        return $input_params;
    }

    /**
     * getproviderpolicies method is used to process query block.
     * @created Himanshu Dholakia | 20.06.2017
     * @modified Himanshu Dholakia | 20.06.2017
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function getproviderpolicies($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $provider_id = isset($input_params["provider_id"]) ? $input_params["provider_id"] : "";
            $this->block_result = $this->provider_policies_model->getproviderpolicies($provider_id);
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["getproviderpolicies"] = $this->block_result["data"];

        return $input_params;
    }

    /**
     * get_provider_reff method is used to process query block.
     * @created Dhananjay singh | 28.07.2017
     * @modified Dhananjay singh | 28.07.2017
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_provider_reff($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $provider_id = isset($input_params["provider_id"]) ? $input_params["provider_id"] : "";
            $this->block_result = $this->provider_contact_master_model->get_provider_reff($provider_id);
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_provider_reff"] = $this->block_result["data"];

        return $input_params;
    }

    /**
     * product_master_finish_success_2 method is used to process finish flow.
     * @created Himanshu Dholakia | 16.05.2017
     * @modified Himanshu Dholakia | 29.07.2017
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function product_master_finish_success_2($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "product_master_finish_success_2",
        );
        $output_fields = array(
            'u_name',
            'u_unique_id',
            'u_contact_number',
            'u_contact_fax_number',
            'u_business_hours',
            'u_main_contact',
            'u_last_name',
            'u_company_name',
            'u_legal_company_name',
            'u_r_fctax_id',
            'u_compamy_contact_number',
            'u_compamy_contact_number_ext',
            'u_company_logo',
            'u_company_email',
            'u_company_website',
            'u_alternate_email',
            'u_email',
            'u_street_name',
            'u_colony_name',
            'u_zip_code',
            'u_image',
            'u_added_date',
            'u_status',
            'u_last_login_date',
            'u_activation_code',
            'u_fiscal_street_name',
            'u_fiscal_contact_number',
            'u_fiscal_contact_number_ext',
            'u_fiscal_city',
            'u_fiscal_colony_name',
            'u_fiscal_zip_code',
            'u_avg_rating',
            'u_provider_type',
            'ups_user_provider_settings_id_1',
            'ups_rush_service',
            'ups_have_factory',
            'ups_distributer_discount_1',
            'ups_distributer_discount_per_1',
            'ups_offer_printing_1',
            'ups_printing_options_id_1',
            'ups_printing_machine_available_1',
            'ups_printing_machine_available_notes_1',
            'ups_offer_desc_for_distributor_1',
            'ups_other_printing_options_1',
            'ups_other_type_of_provider',
            'ups_invoice_document',
            'ups_r_fcdocument',
            'ups_proof_address_document',
            'ups_other_hear_about_us',
            'company_size',
            'company_age',
            'mc_country',
            'hear_about_us',
            'type_of_provider',
            'fiscal_country',
            'u_city',
            'ms_state',
            'fiscal_state',
            'payment_method',
            'printing_option',
            'ups_google_drive_url',
            'ups_google_sheet_url',
            'pcm_provider_contacts_id',
            'pcm_role',
            'pcm_name',
            'pcm_email',
            'pcm_contact_no',
            'pp_provider_policies_id',
            'pp_name',
            'pp_description',
            'pp_added_date',
            'pp_modified_date',
            'pcm_provider_contact_master_id',
            'pcm_provider_id',
            'pcm_title',
            'pcm_name_1',
            'pcm_email_1',
            'pcm_phone',
            'pcm_added_date',
            'pcm_available_time',
            'pcm_contact_no_1',
            'dpc_provider_contact_id',
            'dpc_role_provider_contact',
            'dpc_name_provider_contact',
            'dpc_email_provider_contact',
            'dpc_phone_provider_contact',
        );
        $output_keys = array(
            'getproviderdetails_v1',
            'getprovidercontacts',
            'getproviderpolicies',
            'get_provider_reff',
        );
        $ouput_aliases = array(
            "pcm_name_1" => "pcm_name",
            "pcm_email_1" => "pcm_email",
            "pcm_contact_no_1" => "pcm_contact_no",
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "distributor_provider_details";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * users_finish_success method is used to process finish flow.
     * @created Himanshu Dholakia | 16.05.2017
     * @modified Himanshu Dholakia | 16.05.2017
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "users_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "distributor_provider_details";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
