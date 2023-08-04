<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Extended General Library
 *
 * @category libraries
 *
 * @package libraries
 *
 * @module General
 *
 * @class Cit_general.php
 *
 * @path application\libraries\Cit_general.php
 *
 * @version 4.0
 *
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
include_once (APPPATH.'libraries'.DS.'General.php');

class Cit_general extends General
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Code will be generated dynamically
     * Please do not write or change the content below this line
     * Five hashes must be there on either side of string.
     */
    #####GENERATED_CUSTOM_FUNCTION_START#####

    public function getCountryName($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $this->CI->db->select("vCountry");
        $this->CI->db->from("mod_country");
        $this->CI->db->where("iCountryId = '138'");
        $res = $this->CI->db->get()->result_array();
        $ret_val = $res[0]['vCountry'];
        return $ret_val;
    }

    public function getCategoryType($value = '', $mode = '', $id = '', $data = array(), $parent_src = '')
    {
        $return_arr = array();
        $return_arr = array(
            array(
                "id" => "1",
                "val" => "PrivacyPolicy",
            ),
            array(
                "id" => "2",
                "val" => "HowItWorks",
            )
        );
        return $return_arr;
    }

    public function getSubstringValue($value = '', $id = '', $data = array())
    {
        $limit = '100';
        if (strlen($value) > $limit)
        {
            $ret_val = substr($value, 0, $limit).'...';
        }
        else
        {
            $ret_val = $value;
        }

        return $ret_val;
    }

    public function getTemplateImageandName($value = '', $mode = '', $id = '', $data = array(), $parent_src = '')
    {
        $this->CI->db->select("iWebsiteTemplateMasterId,vTemplateName,vTemplateImage");
        $this->CI->db->from("website_template_master");
        $this->CI->db->where("eType = 'Website'");
        $res = $this->CI->db->get()->result_array();
        $images_path = $this->CI->config->item('site_url').'public/upload/template_images';
        $ret_arr = array();
        foreach ($res as $key => $value)
        {
            $id = $value['iWebsiteTemplateMasterId'];
            $ret_arr[$key]['id'] = $id;
            $ret_arr[$key]['val'] = $value['vTemplateName'];
        }
        return $ret_arr;
    }

    public function getProductImageandName($value = '', $mode = '', $id = '', $data = array(), $parent_src = '')
    {
        $this->CI->db->select("iWebsiteTemplateMasterId,vTemplateName,vTemplateImage");
        $this->CI->db->from("website_template_master");
        $this->CI->db->where("eType = 'Product'");
        $res = $this->CI->db->get()->result_array();
        $images_path = $this->CI->config->item('site_url').'public/upload/template_images';
        $ret_arr = array();
        foreach ($res as $key => $value)
        {
            $id = $value['iWebsiteTemplateMasterId'];
            $ret_arr[$key]['id'] = $id;
            $ret_arr[$key]['val'] = $value['vTemplateName'];
        }
        return $ret_arr;
    }

    public function getUserName($value = '', $id = '', $data = array())
    {
        $this->CI->db->select("vName");
        $this->CI->db->from("users");
        $this->CI->db->join('payment_live_log', 'users.iUsersId = payment_live_log.iLoggedUserID');
        $this->CI->db->where("payment_live_log.iLoggedUserID = '$id'");
        $res = $this->CI->db->get()->result_array();
        $ret_val = $res[0]['vName'];
        return $ret_val;
    }

    public function generateCode($input_params = '', $index_val = '')
    {
        $length = 8;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $caps_characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $caps_charactersLength = strlen($caps_characters);
        $randomString = '';
        $length = $length-2;
        // Generate two character random string of caps characters
        for ($i = 0; $i < 2; $i++)
        {
            $randomString .= $caps_characters[rand(0, $caps_charactersLength-1)];
        }

        // Generate random string from alphanumeric characters
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength-1)];
        }
        return $randomString;
    }

    public function gererateResetLink($input_params = '', $index_val = '')
    {
        $user_id = $input_params['get_user_by_email'][0]['u_users_id'];
        $ret_val = urlencode(base64_encode($input_params['activate_code'].'###'.$user_id.'###'.time()));
        return $ret_val;
    }

    public function get_number_dropdown($value = '', $mode = '', $id = '', $data = array(), $parent_src = '')
    {
        $numcombo = array();
        for ($i = 1; $i < 51; $i++)
        {
            $numcombo[] = array(
                "id" => number_format(floatval($i), 2), "val" => $i);
        }
        return $numcombo;
    }

    public function get_rating($provider_id = 0)
    {
        if ($provider_id == 0) {
            $id = $this->CI->session->userdata('iAdminId');
        } else {
            $id = $provider_id;
        }
        $this->CI->db->select('round(avg(iProductDetails)) as Productdetails');
        $this->CI->db->select('round(avg(iCustomerService)) as Customerservice');
        $this->CI->db->select('round(avg(iProblemSolution)) as Problemsolution');
        $this->CI->db->select('round(avg(iDelivery)) as Delivery');
        $this->CI->db->select('round(avg(iProductQuality)) as Productquality');
        $this->CI->db->select('round(avg(iDecorationQualit)) as Decorationquality');
        $this->CI->db->select('round(avg(fAvgRating)) as avg_rating');
        $this->CI->db->select('round(count(iDistributorId))as rating');
        $this->CI->db->where('iProviderId', $id);
        $query = $this->CI->db->get('provider_rating');
        $html = array();
        $product_details = $query->result_array()[0]['Productdetails'];
        $customer_service = $query->result_array()[0]['Customerservice'];
        $problem_solution = $query->result_array()[0]['Problemsolution'];
        $delivery = $query->result_array()[0]['Delivery'];
        $product_quality = $query->result_array()[0]['Productquality'];
        $decoration_quality = $query->result_array()[0]['Decorationquality'];
        $avg = $query->result_array()[0]['avg_rating'];
        $rating = $query->result_array()[0]['rating'];

        $html = '<div><h4><%lang("LBL_FRONT_PROVIDER_DASHBOARD_PRODUCT_DETAILS")%>:-<span>'.$product_details.'</span><h4></div>';

        $html = '<div><h4><%lang("LBL_FRONT_PROVIDER_DASHBOARD_CUSTOMER_SERVICE")%>:-<span>'.$customer_service.'</span><h4></div>';

        $html .= '<div><h4><%lang("LBL_FRONT_PROVIDER_DASHBOARD_PROBLEM_SOLUTION")%>:-<span>'.$problem_solution.'</span></h4></div>';

        $html .= '<div><h4><%lang("LBL_FRONT_PROVIDER_DASHBOARD_DELIEVERY")%>:- <span>       '.$delivery.'</span></h4></div>';

        $html .= '<div><h4><%lang("LBL_FRONT_PROVIDER_DASHBOARD_PRODUCT_QUALITY")%>:-<span>  '.$product_quality.'</span></h4></div>';

        $html .= '<div><h4><%lang("LBL_FRONT_PROVIDER_DASHBOARD_DECORATION_QUALITY")%>:-<span>'.$decoration_quality.'</span></h4></div>';

        $html .= '<div><h4><%lang("LBL_FRONT_PROVIDER_DASHBOARD_AVERAGE_RATING")%>:-<span>    '.$avg.'</span></h4></div>';

        $html .= '<div class="rating">
<span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span>
</div>';
        $html .= ''.$avg.'/5('.$rating.' ratings)';

        return '<div class="main-wrap-customer clearfix">
        <div class="avg-rating-wp">
<h2>'.$this->CI->lang->line("LBL_FRONT_PROVIDER_DASHBOARD_AVERAGE_RATING").'</h2>
 <div class="rating-sec">
	 <span>'.intval($avg).'/5 </span>
	</div>
    </div>
    <div class="pars-rating-wp">
<ul>
	<li>
	 <strong>'.intval($product_details).'/5 </strong>
		<span>'.$this->CI->lang->line("LBL_FRONT_PROVIDER_DASHBOARD_PRODUCT_DETAILS").'</span>
	</li>
 	<li>
	 <strong>'.intval($customer_service).'/5 </strong>
		<span>'.$this->CI->lang->line("LBL_FRONT_PROVIDER_DASHBOARD_CUSTOMER_SERVICE").'</span>
	</li>
	<li> <strong>'.intval($problem_solution).'/5 </strong>
		<span>'.$this->CI->lang->line("LBL_FRONT_PROVIDER_DASHBOARD_PROBLEM_SOLUTION").'</span></li>
	<li> <strong>'.intval($delivery).'/5 </strong>
		<span>'.$this->CI->lang->line("LBL_FRONT_PROVIDER_DASHBOARD_DELIEVERY").'</span></li>
	<li> <strong>'.intval($decoration_quality).'/5 </strong>
		<span>'.$this->CI->lang->line("LBL_FRONT_PROVIDER_DASHBOARD_DECORATION_QUALITY").'</span></li>
	<li> <strong>'.intval($product_quality).'/5 </strong>
		<span>'.$this->CI->lang->line("LBL_FRONT_PROVIDER_DASHBOARD_PRODUCT_QUALITY").'</span></li>
</ul>
</div>
</div>';
    }

    public function manage_dashboard_category_list($input_params = array())
    {
        $return_arr = array();

        $tmp = array();

        foreach ($input_params['dashboard_get_all_products'] as $row)
        {

            $tmp[] = array(
                'Category_Name' => $row['cm_category_name_1'],
                'total_products' => $row['totproduct'],
            );
        }

        return $tmp;
    }

    public function generateActivationLink($input_params = array())
    {
        $return_arr['activation_link'] = "<a href='".$this->CI->config->item('site_url')."provider/provider/emailActivation/".$input_params['activation_code']."'>Click here to activate</a>";

        return $return_arr;
    }

    public function getDistributorActivationLink($input_params = array())
    {
        $activation_code = $input_params['u_activation_code'];

        $return_arr['activationlink'] = $this->CI->config->item('site_url')."email-activation/".$activation_code;

        $return_arr['ticket_no'] = str_pad($input_params['distributor_id'], 7, "0", STR_PAD_LEFT);

        $website_code = preg_replace("/[^A-Za-z0-9 ]/", '', $input_params['company_name']);
        $website_code = str_replace(" ", "", $website_code);
        foreach ($this->CI->config->item('domain_invalid_array') as $value)
        {
            if (strpos($website_code, $value) == 0 && is_numeric(strpos($website_code, $value)))
            {
                $website_code = "my".$website_code;
            }
        }
        $return_arr['website_code'] = $website_code;

        $this->CI->db->select("COUNT(iUserDistributorSettingId) as ct");
        $this->CI->db->where("vCustomDomainURL", $website_code);
        $result = $this->CI->db->get('user_distributor_setting')->result_array();
        if ($result[0]['ct'] > 0)
        {
            $return_arr['website_code'] = $return_arr['website_code'].$result[0]['ct'];
        }

        return $return_arr;
    }

    public function generateDistributorCode($value = '', $data_arr = array())
    {
        $distributor_id = $data_arr['distributor_id'];
        $length = 6;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $caps_characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $caps_charactersLength = strlen($caps_characters);

        $randomString = '';
        $length = $length-2;

        // Generate two character random string of caps characters
        for ($i = 0; $i < 2; $i++)
        {
            $randomString .= $caps_characters[rand(0, $caps_charactersLength-1)];
        }

        // Generate random string from alphanumeric characters
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength-1)];
        }

        $randomString .= $distributor_id;

        return $randomString;
    }

    public function generateInquiryTicketNumber($input_params = array())
    {
        $return_array = array();
        $return_array[0]['ticket_no'] = str_pad((10786+$input_params['insert_id']), 7, "0", STR_PAD_LEFT);
        return $return_array;
    }

    public function getData($value = '', $mode = '', $id = '', $data = array(), $parent_src = '')
    {
        $return_arr = array();
        $this->CI->db->select("vCompanyName as provider");
        $this->CI->db->where('iUsersId', $id['pm_provider_id']);
        $query = $this->CI->db->get('users');
        $providername = $query->first_row('array');
        $return_arr = $providername['provider'];
        return $return_arr;
    }

    public function generatePageCode($mode = '', $id = '', $parID = '')
    {
        $page_code = $this->CI->input->post('mps_page_code');
        $mps_category_name = $this->CI->input->post('mps_category_name');
        if ($page_code == '')
        {
            $page_code = str_replace(" ", "-", $mps_category_name);
            $page_code = str_replace("(", "", $page_code);
            $page_code = str_replace("&", "-", $page_code);
            $page_code = str_replace("#", "", $page_code);
            $page_code = str_replace("(", "", $page_code);
            $page_code = str_replace(")", "", $page_code);
            $page_code = str_replace("'", "", $page_code);
            $page_code = str_replace('"', "", $page_code);
            $page_code = str_replace("`", "", $page_code);

            $this->CI->db->where('iPageId', $id);
            $this->CI->db->update('mod_page_settings', array('vPageCode' => $page_code));
        }

        $ret = array();

        $ret['success'] = true;
        $ret['message'] = "Done";
        return $ret;
    }

    public function getWebsiteImage($value = '', $mode = '', $id = '', $data = array(), $parent_src = '')
    {
        $combo = array();
        $image_path = $this->CI->config->item('site_url');
        $this->CI->db->select('iWebsiteTemplateMasterId,vTemplateName,vTemplateImage');
        $this->CI->db->where('eStatus', 'Active');
        $this->CI->db->where('eType', 'Website');
        $query = $this->CI->db->get('website_template_master');
        $data = $query->result();
        foreach ($data as $key => $value)
        {
            $relpath = "public/upload/template_images/".$value->vTemplateImage;
            if (is_file($relpath))
            {
                $img_url = $image_path.$relpath.'?'.date('Ymd');
                $full_img_path = $image_path."WS/image_resize/?pic=".base64_encode($img_url)."&height=200&width=200";
            }
            else
            {
                $full_img_path = $img_url = $this->getNoImageURL();
            }

            $this->CI->db->select('vImage');
            $this->CI->db->where('iWebsiteTemplateMasterId', $value->iWebsiteTemplateMasterId);
            $query = $this->CI->db->get('template_image');
            $data_images = $query->result();
            $gallery_img_array = array();
            foreach ($data_images as $index => $row)
            {
                $relpath = "public/upload/template_images/".$row->vImage;
                if (is_file($relpath))
                {
                    $img_url = $image_path.$relpath;
                }
                else
                {
                    $full_img_path = $img_url = $this->getNoImageURL();
                }
                $gallery_img_array[] = " <a  title='".$value->vTemplateName."' href='".$img_url."'  class='fancybox-thumbs' rel='".$value->vTemplateName."'></a>";
            }

            $combo[] = array(
                "id" => $value->iWebsiteTemplateMasterId,
                "val" => "<span class='gallery_web_template'>
                <a   href='".$img_url."' rel='".$value->vTemplateName."'   attr-val='".$value->vTemplateName."'>
                <img style='border-bottom: 1px solid #ccc; margin-bottom:10px;' src='".$full_img_path."' class=''>
                </a>
                ".implode(" ",
                $gallery_img_array)."
                </span>"."<br>".$value->vTemplateName,
            );
        }
        return $combo;
    }

    public function getValidOrder($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        return $ret_val;
    }

    public function get_provider_name_1($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        return $data['dpm_provider_name'];
    }

    public function get_provider_unique_1($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        return $data['dpm_unique_id'];
    }

    public function getCategoryName($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        return $data['cm_category_name'];
    }

    public function getParentCategoryName($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        if ($data['cm_parent_id'] > 0)
        {
            $this->CI->db->select('vCategoryName');
            $this->CI->db->from('category_master');
            $this->CI->db->where('category_master.iCategoryMasterId', $data['cm_parent_id']);
            $name = $this->CI->db->get()->result_array();
            return $name[0]['vCategoryName'];
        }
        else
        {
            return "N/A";
        }
    }

    public function calculate_package_summary(&$input_params = array())
    {
        $return_arr = array();
        $return_arr[0]['sub_total'] = 0;
        $return_arr[0]['order_total'] = 0;
        $return_arr[0]['total_users_count'] = 0;
        $return_arr[0]['all_packages_list'] = array();
        $package_db_detail = array();
        foreach ($input_params['get_package_details'] as $row)
        {
            $package_db_detail[$row['subs_pack_id']] = $row;
        }
        foreach ($input_params['package_arr'] as $index => $row)
        {
            $price = $package_db_detail[$row['package_id']]['spm_ff_price_for_year_1'];
            $price_per_ad = 0.0;
            if ($row['no_of_user'] > 1 && $row['no_of_user'] <= 5)
            {
                if ($row['package_duration'] == 1)
                {
                    $price_per_ad = $package_db_detail[$row['package_id']]['spm_price_for_user_5_1'];
                }
                else
                {
                    $price_per_ad = $package_db_detail[$row['package_id']]['spm_pricefor5users12months'];
                }
            }
            elseif ($row['no_of_user'] > 5)
            {
                if ($row['package_duration'] == 1)
                {
                    $price_per_ad = $package_db_detail[$row['package_id']]['spm_price_for_user_5_plus_1'];
                }
                else
                {
                    $price_per_ad = $package_db_detail[$row['package_id']]['spm_pricefor5plus_users12months'];
                }
            }
            elseif ($row['no_of_user'] == 1)
            {
                if ($row['package_duration'] == 1)
                {
                    $price_per_ad = $package_db_detail[$row['package_id']]['spm_price_for_user_1'];
                }
                else
                {
                    $price_per_ad = $package_db_detail[$row['package_id']]['spm_pricefor1_users12_months'];
                }
            }
            if ($row['package_duration'] == 1)
            {

                $price = $package_db_detail[$row['package_id']]['spm_price_for_month_1'];
            }
            $add_user_total_price = ($price_per_ad*$row['no_of_user']);
            $tmp_sub_total = $price+$add_user_total_price;
            $package_db_detail[$row['package_id']]['iPackageDurationInMonth'] = $row['package_duration'];
            $package_db_detail[$row['package_id']]['iUserPurchasedCount'] = $row['no_of_user'];
            $package_db_detail[$row['package_id']]['fPackagePrice'] = $price;
            $package_db_detail[$row['package_id']]['fAdditionalUserPrice'] = $price_per_ad;
            $package_db_detail[$row['package_id']]['fAdditionalUserTotalPrice'] = $add_user_total_price;
            $package_db_detail[$row['package_id']]['fOrderTotal'] = $tmp_sub_total;
            $package_db_detail[$row['package_id']]['fOrderSubTotal'] = $tmp_sub_total;
            $input_params['package_arr'][$index]['item_sub_toatl'] = $tmp_sub_total;
            $return_arr[0]['sub_total'] += $tmp_sub_total;
            $return_arr[0]['order_total'] += $tmp_sub_total;
            $return_arr[0]['duration_string'] = $row['package_duration']." ".$row['duration_type'];

            $return_arr[0]['total_users_count'] += $row['no_of_user'];
            $return_arr[0]['all_packages_list'][] = $package_db_detail[$row['package_id']]['spm_package_type_id_1'];
        }
        $input_params['get_package_details'] = array();
        foreach ($package_db_detail as $row)
        {
            $input_params['get_package_details'][] = $row;
        }
        $return_arr[0]['all_packages_list'] = implode(",", $return_arr[0]['all_packages_list']);
        return $return_arr;
    }

    public function generate_order_number($input_params = array())
    {
        $return_arr = array();

        $return_arr[0]['order_number'] = "ANP-".date("md").$input_params['order_id'].'ORD';
        $return_arr[0]['fisical_order_number'] = "ANP-".date("md").$input_params['order_id'].'FIS';

        return $return_arr;
    }

    public function callbackProviderApprove($status = '', $id = '')
    {
        $this->CI->db->select('eStatus');
        $this->CI->db->where('iUsersId', $id);
        $data = $this->CI->db->get('users')->first_row();
        if ($data->eStatus == 'Active')
        {
            $this->generateProviderKey('update', $id);
        }

        $return = array();

        $return['success'] = 1;
        return $return;
    }

    public function getMetaData($page_code = '')
    {
        $this->CI->load->model('tools/staticpages');
        $fields = array(
            "vPageTitle",
            "vPageCode",
            "vContent",
            "tMetaTitle",
            "tMetaKeyword",
            "tMetaDesc",
            "vTopBannerImage",
        );
        $render_arr = array();
        $req_lang = $this->CI->input->get('lang', TRUE);
        if (!is_null($req_lang) && !empty($req_lang))
        {
            $lang = strtolower($req_lang);
        }
        elseif (!is_null($arg_lang) && !empty($arg_lang))
        {
            $lang = strtolower($arg_lang);
        }
        else
        {
            $lang = "en";
            if ($this->CI->config->item('MULTI_LINGUAL_PROJECT') == "Yes")
            {
                $sess_lang = $this->CI->session->userdata('sess_lang_id');
                if (!is_null($sess_lang) && !empty($sess_lang))
                {
                    $lang = strtolower($sess_lang);
                }
            }
        }
        if ($lang == "en")
        {
            $page_details = $this->CI->staticpages->getStaticPageData($page_code, $fields);
        }
        else
        {
            $lang_fields = $this->CI->staticpages->getLangTableFields();
            if (is_array($lang_fields) && count($lang_fields) > 0)
            {
                $lang_arr = array();
                foreach ($fields as $key => $val)
                {
                    if (in_array($val, $lang_fields))
                    {
                        $lang_arr[] = "mps_lang.".$val;
                    }
                    else
                    {
                        $lang_arr[] = "mps.".$val;
                    }
                }
                $page_details = $this->CI->staticpages->getStaticPageLangData($lang, $page_code, $lang_arr);
            }
            if (!is_array($page_details) || count($page_details) == 0)
            {
                $page_details = $this->CI->staticpages->getStaticPageData($page_code, $fields);
            }
        }
        $render_arr = array(
            "display_lang" => $lang,
            "page_code" => $page_code,
            "page_title" => $page_details[0]["vPageTitle"],
            "page_content" => $page_details[0]["vContent"],
            "title" => $page_details[0]["vPageTitle"],
            "back_img" => $this->CI->config->item('banner_image_url').$page_details[0]["vTopBannerImage"],
            "meta_info" => array(
                "title" => $page_details[0]["tMetaTitle"],
                "description" => $page_details[0]["tMetaDesc"],
                "keywords" => $page_details[0]["tMetaKeyword"],
            )
        );
        $this->CI->smarty->assign($render_arr);
    }

    public function SaleorderId1($mode = '', $id = '', $parID = '')
    {
        $itemid = array();
        $itemid = $_POST['child']['sales_package']['uspt_user_items_id'];
        $this->CI->db->select('iOrderId');
        $this->CI->db->select('iUserMembershipPackagesItemsId');
        $this->CI->db->where_in('iUserMembershipPackagesItemsId', $itemid);
        $query = $this->CI->db->get('user_membership_packages_items');
        $order = $query->result();
        foreach ($order as $key => $value)
        {
            $this->CI->db->set('iOrderId', $value->iOrderId);
            $this->CI->db->where('iUserMembershipPackagesItemsId', $value->iUserMembershipPackagesItemsId);
            $this->CI->db->update('user_sales_package_trans');
        }
        $post_arr = $this->CI->input->post();
        if ($mode == 'Add')
        {
            if ($post_arr['u_sales_person_role'] == 'Salesman' || $post_arr['u_sales_person_role'] == 'Admin')
            {
                $input_params['salesman_mail'] = $post_arr['u_email'];
                $input_params['salesman_name'] = $post_arr['u_name'];
                $input_params['salesman_role'] = $post_arr['u_sales_person_role'];
                $input_params['SYSTEM.COMPANY_NAME'] = $this->CI->config->item('COMPANY_NAME');
                $input_params['SYSTEM.site_url'] = $this->CI->config->item('site_url');
                $input_params['SYSTEM.COMPANY_EMAIL'] = $this->CI->config->item('COMPANY_EMAIL');
                $input_params['SYSTEM.COMPANY_URL'] = $this->CI->config->item('admin_url');
                $this->CI->db->select('vEmail,vName');
                $this->CI->db->from('users');
                $this->CI->db->where('iUsersId', $post_arr['u_distributor_user_id']);
                $dist_id = $this->CI->db->get()->row_array();
                $input_params['distributor_mail'] = $dist_id['vEmail'];
                $input_params['distributor_name'] = $dist_id['vName'];
                $dist_template = $this->get_dist_email_template('ADD_SALESMAN', $post_arr['u_distributor_user_id']);
                $input_params['contact_us_eamil_templates'] = $dist_template;
                $this->send_distributor_email($input_params);
                $salesman_template = $this->get_dist_email_template('SALESMAN', $post_arr['u_distributor_user_id']);
                $input_params['contact_us_eamil_templates'] = $salesman_template;
                $this->send_distributor_email($input_params);
                $admin_id = $post_arr['u_admin_id'];
                if ($admin_id > 0)
                {
                    $this->CI->db->select('vEmail,vName,iDistributorUserId,iUserType');
                    $this->CI->db->from('users');
                    $this->CI->db->where('iUsersId', $admin_id);
                    $admin_arr = $this->CI->db->get()->row_array();
                    $input_params['admin_mail'] = $admin_arr['vEmail'];
                    $input_params['distributor_name'] = $dist_id['vName'];
                    $admin_template = $this->get_dist_email_template('ADD_SALESMAN_ADMIN', $post_arr['u_distributor_user_id']);
                    $input_params['contact_us_eamil_templates'] = $admin_template;
                    $this->send_distributor_email($input_params);
                }
            }
        }
        $return = array();
        $return['success'] = 1;
        return $return;
    }

    public function makeEntryCategoryMaster($mode = '', $id = '', $parID = '')
    {
        $return = array();
        if ($dct_provider_id > 0)
        {
            $return['success'] = 1;
        }
        else
        {
            $mode = $this->CI->input->post('mode');
            $dct_distributor_user_id = $this->CI->session->userdata('iAdminId');
            $dct_category_master_id = $this->CI->input->post('dct_category_master_id');
            $category_name = $this->CI->input->post('dct_category_name');

            $update_insert = array();
            $update_insert['vCategoryName'] = $category_name;
            $update_insert['iAddedByUserId'] = $dct_distributor_user_id;
            $update_insert['iDistributorUserId'] = $dct_distributor_user_id;
            $update_insert['dAddedDate'] = date("Y-m-d H:i:s");
            $update_insert['tMetaDesc'] = $category_name;
            $update_insert['tMetaKeyword'] = $category_name;
            $update_insert['vMetaTitle'] = $category_name;
            $update_insert['tDescription'] = $category_name;
            $update_insert['iUserType'] = 1;
            if ($dct_category_master_id > '0')
            {
                $this->CI->db->where('iCategoryMasterId', $dct_category_master_id);
                $this->CI->db->where('iDistributorUserId', $dct_distributor_user_id);
                $this->CI->db->where('eCategoryType', 'Custom');
                $this->CI->db->update('category_master', $update_insert);
            }
            else
            {
                $update_insert['eCategoryType'] = 'Custom';
                $this->CI->db->insert('category_master', $update_insert);
                $cmid = $this->CI->db->insert_id();

                $this->CI->db->where('iDistributorCategoryTransId', $id);
                $this->CI->db->update('distributor_category_trans', array("iCategoryMasterId" => $cmid));
            }
        }
        $return['success'] = 1;
        return $return;
    }

    public function beforeDeleteDistCategory($mode = '', $id = '', $parID = '')
    {
        $oper = $this->CI->input->post('oper');
        $id = $this->CI->input->post('id');
        $this->CI->db->select("*");

        $this->CI->db->where('iDistributorCategoryTransId', $id);
        $this->CI->db->where('iDistributorUserId', $this->CI->session->userdata('iAdminId'));
        $data = $this->CI->db->get('distributor_category_trans')->first_row();
        if ($data->iCategoryMasterId > 0)
        {
            $this->CI->db->where('iCategoryMasterId', $data->iCategoryMasterId);
            $this->CI->db->where('iDistributorUserId', $data->iDistributorUserId);
            $this->CI->db->where('iUserType', 1);
            $this->CI->db->delete('category_master');
        }

        $return['success'] = 1;
        return $return;
    }

    public function insertCategories($mode = '', $id = '', $parID = '')
    {
        $post_arr = $_POST;
        if ($mode == "Add")
        {
            $this->CI->db->query("update distributor_provider_mapping as dpm,users as u set 						dpm.iProviderId=u.iUsersId where dpm.iDistributorProviderMappingId=".$id." and 						dpm.iDistributorProviderMappingId=u.iDistributorProviderMappingId;");
        }
        $dpm_default_categories = $post_arr['dpm_default_categories'];
        $dpm_default_categories = implode(',', $dpm_default_categories);
        $data = array(
            'vSelectedCategoryID' => $dpm_default_categories,
        );
        $this->CI->db->where('iDistributorProviderMappingId', $id);
        $this->CI->db->update('distributor_provider_mapping', $data);
        $ret_arr = array();
        $ret_arr['success'] = true;
        return $ret_arr;
    }

    public function getMultipleCategoriesName($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        /*Keep this code if we want to display selected categories in multiselect dropdown then uncomment below line*/
        //return $data['dpm_selected_category_id'];
    }

    public function addbtnforfiscaladdress($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $button .= '<a class="btn btn_info custom-view-btn view_btn" title="Fiscal-details" href="'.$this->CI->config->item('admin_url').'#crm/fiscal_details/index|lc_parent_id|'.$id.'|"><i class="fa fa-dollar"></i></a>';
        $button .= "</div>";
        return $button;
    }

    public function gettaskUniqueID($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        if ($value == '')
        {
            $uniqueId = uniqid();
            return $uniqueId;
        }
        else
        {
            return $value;
        }
    }

    public function createUniqueId($mode = '', $id = '', $parID = '')
    {
        //
        $length = 10;
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength-1)];
        }
        $unique_id = "PRO".date("Ym")."_".$randomString;
        $data = array(
            "vLeadUniqueID" => $unique_id,
        );
        $this->CI->db->where('iLeadMasterId', $id);
        $this->CI->db->update('lead_master', $data);
    }

    public function saveTabData($mode = '', $id = '', $parID = '')
    {
        $postArr = $this->CI->input->post();
        if(!empty($postArr['child']['distributor_verification_reference'])){
            $child_data_arr = $postArr['child']['distributor_verification_reference'];
            $dist_reference_arr = array();
            foreach ($child_data_arr as $key => $value) {
                foreach ($value as $ikey => $ivalue) {
                    if($key=='dvr_company_name'){
                        $dist_reference_arr[$ikey]['vCompanyName'] = $ivalue;
                    } elseif ($key=='dvr_phone_no') {
                       $dist_reference_arr[$ikey]['vPhoneNo'] = $ivalue;
                    } elseif ($key=='dvr_contact_no') {
                       $dist_reference_arr[$ikey]['vContactNo'] = $ivalue;
                    } elseif ($key=='dvr_distributor_reference_id') {
                       $dist_reference_arr[$ikey]['iDistributorVerificationRefrencesId'] = $ivalue;
                    }
                    $dist_reference_arr[$ikey]['iDistributorUserId'] = $this->CI->session->userdata('iAdminId');
                    $dist_reference_arr[$ikey]['dtAddedDate'] = date('Y-m-d H:i:s');
                    $dist_reference_arr[$ikey]['eStatus'] = 'Active';
                    $dist_reference_arr[$ikey]['iUserVerificationId'] = $postArr['child']['distributor_request_verification']['id'][0];
                }
            }
            $this->addViewDistributorReferences($dist_reference_arr,false);

            /*Send Email Notification*/
                $this->sendVerificationEmailNotification();
            /*Send Email Notification*/
        }
        if ($postArr['uds_distibutor_profit_preference'] == 'Default' || $postArr['uds_distibutor_profit_preference'] == 'Hide')
        {
            $this->CI->session->set_userdata('fDistibutorProfitDiscount', $this->CI->config->item('DEFAULT_PROFIT'));
            $this->CI->session->set_userdata('vDistibutorProfitPreference', $postArr['uds_distibutor_profit_preference']);
        }
        elseif ($postArr['uds_distibutor_profit_preference'] == 'Custom')
        {
            $this->CI->session->set_userdata('fDistibutorProfitDiscount', $postArr['uds_distibutor_profit_discount']);
            $this->CI->session->set_userdata('vDistibutorProfitPreference', 'Custom');
        }

        $steps = array();
        $steps['current_step_template'] = 1;
        $steps['current_step_company'] = 2;
        $steps['current_step_quote'] = 3;
        $steps['current_step_provider_select'] = 4;
        $steps['current_step_provider_settings'] = 5;
        $steps['current_step_website'] = 6;
        $steps['current_step_custom_tags'] = 7;
        $steps['current_step_distributorverification'] = 8;

        foreach ($steps as $key => $number)
        {
            if ($postArr[$key] == $number)
            {
                $iStepCompleted = $number;
            }
        }
        if ($iStepCompleted == 1)
        {
            $this->makeRandomBanners();
        }
        if ($iStepCompleted == 5)
        {
            $iStepCompleted = 6;
        }
        else
        if ($iStepCompleted == 6)
        {
            $iStepCompleted = 7;
        }

        $step_of_session = $this->CI->session->userdata('steps_completed');
        if ($iStepCompleted >= $step_of_session)
        {
            $this->CI->session->set_userdata('steps_completed', $iStepCompleted);
        }

        // update dist_setting  table
        $where = array(
            'iUserDistributorSettingId' => $id,
            "iStepCompleted<=" => $iStepCompleted,
        );
        $data = array(
            "iStepCompleted" => $iStepCompleted,
        );
        $this->CI->db->where($where);
        $this->CI->db->update('user_distributor_setting', $data);
        //---
        $ret_arr = array();
        $ret_arr['success'] = true;
        $ret_arr['message'] = "Record updated successfully";
        return $ret_arr;
    }

    public function getImageOfProductTemplate($value = '', $mode = '', $id = '', $data = array(), $parent_src = '')
    {
        //
        $combo = array();
        $image_path = $this->CI->config->item(base_url);
        $this->CI->db->select('iWebsiteTemplateMasterId,vTemplateName,vTemplateImage');
        $this->CI->db->where('eStatus', 'Active');
        $this->CI->db->where('eType', 'Product');
        $this->CI->db->limit(3);
        $query = $this->CI->db->get('website_template_master');
        $data = $query->result();
        foreach ($data as $key => $value)
        {
            $relpath = "public/upload/template_images/".$value->vTemplateImage;
            if (is_file($relpath))
            {
                $img_url = $this->CI->config->item('site_url').$relpath;
                $full_img_path = $this->CI->config->item('site_url')."WS/image_resize/?pic=".base64_encode($img_url)."&height=200&width=200";
            }
            else
            {
                $full_img_path = $img_url = $this->getNoImageURL();
            }
            $combo[] = array(
                "id" => $value->iWebsiteTemplateMasterId,
                "val" => "<a hijacked='yes' title='".$value->vTemplateName."' href='".$img_url."' class='fancybox-image'>
                <img src='".$full_img_path."' class=''></a>"."<br>".$value->vTemplateName,
            );
        }
        return $combo;
    }

    public function getScalePricing($product_id = 5000, $distributor_id = 7, $parse = false)
    {
        $query = $this->CI->db->query("call ProductPriceScaleAmount(".$product_id.",".$distributor_id.")");
        $data = $query->result();
        $this->CI->db->close();
        if ($parse == true)
        {
            return $data;
        }
        else
        {
            $this->CI->smarty->assign("scale_price", $data);
            return $this->CI->parser->parse('scale_price', array(), true);
        }
    }

    public function admin_providerRatings($provider_id, $distributor_id)
    {
        $this->CI->db->select("*");
        $this->CI->db->where("iDistributorId", $distributor_id);
        $this->CI->db->where("iProviderId", $provider_id);
        $result = $this->CI->db->get("provider_rating")->first_row();
        $this->CI->smarty->assign('stars', $result);
        $this->CI->smarty->assign('provider_id', $provider_id);
        $this->CI->smarty->assign('distributor_id', $distributor_id);
        return $this->CI->parser->parse('provider_rating', array(), true);
    }

    public function getProviderListing($pars = false)
    {
        $did = $this->CI->session->userdata('iAdminId');
        $query = "select u.iUsersId,u.fAvgRating,u.vName,u.vUniqueID,u.vCompanyName,u.vLegalCompanyName,u.iUserType,u.iDistributorUserId,u.eProviderType,u.vEmail,u.eStatus,dcm.iSeqNo,dcm.eProviderType,(select count(p.iProductMasterId) from product_master as p where (p.iProviderId=u.iUsersId) and p.eStatus='Active') as totproduct from users as u inner join distributor_provider_mapping as dcm on dcm.iProviderId=u.iUsersId and dcm.iDistributorUserId=".$did." where u.vUniqueID!='' and u.iUserType=2 and u.iDistributorUserId in (0) and dcm.iDistributorUserId=".$did." and u.eProviderType='Default' and dcm.eSortType='Top' and u.eStatus='Active' order by u.eProviderType asc,dcm.iSeqNo";

        $provider_data = $this->CI->db->query($query)->result_array();
        $this->CI->smarty->assign("top_five", $provider_data);
        if ($pars)
        {
            return $this->CI->parser->parse('setting_new_cit_provider_table', array(), true);
        }
        else
        {
            return $this->CI->parser->parse('setting_new_cit_provider_initial_listing', array(), true);
        }
    }

    public function getSessionValue($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $sess_data = $this->CI->session->all_userdata();
        $steps_completed = $sess_data['steps_completed'];

        return $steps_completed;
    }

    public function generateProviderKey($mode = '', $id = '', $parID = '')
    {
        $provider_key = 'PRO'.$id;
        $this->CI->db->where("iUsersId", $id);
        $this->CI->db->where("iUserType", '2');
        $this->CI->db->update("users", array("vUniqueID" => $provider_key));
        $ret = array();
        $ret['success'] = true;
        return $ret;
    }

    public function getButtons($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        if (!($data['dpm_distributor_provider_mapping_id'] > 0 && $data['u_provider_type'] == 'Custom'))
        {
            $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#custom_products/custom_products/provider_detail_view|id|".$data['u_users_id']."' title='Ver'><i class='icon18 iconic-icon-eye'></i></a>";
        }
        if ($data['dpm_distributor_provider_mapping_id'] > 0 && $data['u_provider_type'] == 'Custom')
        {
            $button .= "<a hijacked='yes' href='".$this->CI->config->item('admin_url')."#product/distributor_vendor_product_import/add|mode|Add|providerid|".$data['u_users_id']."| hideCtrl|true' class='btn btn_info custom-view-btn upload_button cut_btn_small fancybox-popup' name='btn_upload' id='btn_upload' data-id='".$data['u_users_id']."' title='Subir'> <i class ='silk-icon-upload'></i></a>";
        }
        $button .= '<a class="btn btn_info custom-view-btn view_btn" href="'.$this->CI->config->item('admin_url').'#product/distributor_products/index|supplier|'.$data['u_users_id'].'|" title="Productos"><i class="icomoon-icon-gift"></i></a>';
        if ($data['dpm_distributor_provider_mapping_id'] > 0)
        {

            $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn settings_btn' href='".$this->CI->config->item('admin_url')."#distributor/distriburtor_provider/add|mode|Update|id|".$data['dpm_distributor_provider_mapping_id']."' title='Configuración'><i class='entypo-icon-settings'></i></a>";
        }
        else
        {
            $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn settings_btn' href='".$this->CI->config->item('admin_url')."#user/distributor_provider_listing/addProvider|provider_id|".$data['u_users_id']."|add_to_redirect|true' title='Configuración'><i class='entypo-icon-settings'></i></a>";
        }
        if ($data['dpm_distributor_provider_mapping_id'] > 0 && $data['u_provider_type'] == 'Custom')
        {
            $button .= "<a hijacked='yes' href='".$this->CI->config->item('admin_url')."#custom_products/custom_products/provider_detail_view|id|".$data['u_users_id']."|vista|propios' class='btn btn_info custom-view-btn settings_btn' title='Contactar Proveedor'> <i class ='entypo-icon-email'></i></a>";
        }
        $button .= "</div>";
        return $button;
    }
    
    
    
    public function getButtonContact($value = '', $id = '', $data = array())
    {
        /*print_r($data);
        die;*/
        $button = "<div class='my_custom_row_btns'>";
        if (!($data['dpm_distributor_provider_mapping_id'] > 0 && $data['u_provider_type'] == 'Custom'))
        {
            $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#custom_products/custom_products/provider_detail_view|id|".$data['dpm_provider_id']."|vista|propios|provider_contact|".$data['iDistributorProviderContactId']."' title='Contactar Proveedor'><i class='entypo-icon-email'></i></a>";
        }
        $button .= "</div>";
        return $button;
    }
    
    
    public function getProviderContact($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        if (!($data['dpm_distributor_provider_mapping_id'] > 0 && $data['u_provider_type'] == 'Custom'))
        {
            $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#custom_products/custom_products/provider_detail_view|id|".$data['dpc_provider_id']."|vista|propios|provider_contact|".$data['iDistributorProviderContactId']."' title='Contactar Proveedor'><i class='entypo-icon-email'></i></a>";
        }
        $button .= "</div>";
        return $button;
    }
    
    
    public function sendQuotationCustomer($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        if (!($data['dcc_distributor_customer_contact_id'] > 0 && $data['u_provider_type'] == 'Custom'))
        {
            $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#quotation/quotation/add|id|".$data['dcc_distributor_customer_contact_id']."' title='Enviar Cotización'><i class='entypo-icon-email'></i></a>";
        }
        if (!($data['dcc_distributor_customer_contact_id'] > 0 && $data['u_provider_type'] == 'Custom'))
        {
            $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#user/distributor_customer_contact_notes/add|mode|Add|id|".$data['dcc_distributor_customer_contact_id']."' title='Agregar Notas de Seguimiento'><i class='silk-icon-notes'></i></a>";
        }
        $button .= "</div>";
        return $button;
    }
    /*public function sendQuotationCustomer($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        if (!($data['dcc_distributor_customer_contact_id'] > 0 && $data['u_provider_type'] == 'Custom'))
        {
            $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#crm/product_inquiry_v1|id|".$data['dcc_distributor_customer_contact_id']."' title='Enviar Cotización'><i class='entypo-icon-email'></i></a>";
        }
        $button .= "</div>";
        return $button;
    }*/
    
    
    

    public function getProviderId($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $maping_id = $data['dpct_distributor_provider_mapping_id'];
        $this->CI->db->select('iProviderId');
        $this->CI->db->from('distributor_provider_mapping');
        $this->CI->db->where('iDistributorProviderMappingId', $maping_id);
        $provider_id = $this->CI->db->get()->row_array();
        return $provider_id['iProviderId'];
    }

    public function getProductCategoryType($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        if ($mode == 'Add')
        {
            return 'Custom';
        }

        $this->CI->db->select('cm.eCategoryType');
        $this->CI->db->where('cm.iCategoryMasterId', $data['dct_category_master_id']);
        $result = $this->CI->db->get('category_master as cm')->first_row();
        if (count($result) > 0)
        {
            return $result->eCategoryType;
        }
        else
        {
            return 'Custom';
        }
    }

    public function getDefaultProviderCategories($value = '', $mode = '', $id = '', $data = array(), $parent_src = '')
    {
        $pid = $data['dpct_provider_id'] > 0 ? $data['dpct_provider_id'] : $data['dpm_provider_id'];

        $result = $this->CI->db->query("call getCategoryForDistributorProvider(".$pid.",0)");
        $data = $result->result_array();
        $return_arr = array();
        foreach ($data as $row)
        {
            $return_arr[] = array(
                'id' => $row['iCategoryMasterId'],
                'val' => $row['vCategoryName'],
            );
        }
        return $return_arr;
    }

    public function getDistributorMaxDiscount($value = '', $mode = '', $id = '', $data = array(), $parent_src = '')
    {
        $provider_id = $data['dpm_provider_id'];
        $this->CI->db->select('eDistributerDiscount,fDistributerDiscountPer');
        $this->CI->db->from('user_provider_settings');
        $this->CI->db->where('iUserId', $provider_id);
        $provider_data = $this->CI->db->get()->row_array();
        $is_discount = $provider_data['eDistributerDiscount'];
        $max_discount = $provider_data['fDistributerDiscountPer'];
        $return_arr = array();
        $return_arr[] = array(
            'id' => '0.00',
            'val' => '0.00',
        );
        if ($is_discount == 'On')
        {
            for ($i = 1; $i <= $max_discount; $i++)
            {
                $return_arr[] = array(
                    'id' => number_format($i,
                    2),
                    'val' => number_format($i,
                    2)
                );
            }
        }
        return $return_arr;
    }

    public function customDefaultStyle($value = '', $id = '', $data = array())
    {
        return $value;
    }

    public function getDistibutorProfit($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $this->CI->db->select('vDistibutorProfitPreference');
        $this->CI->db->where('iUserId', $this->CI->session->userdata('iAdminId'));
        $result = $this->CI->db->get('user_distributor_setting')->first_row();
        $profit = 0.00;
        $readonly = '';
        if ($result->vDistibutorProfitPreference == 'Custom')
        {
            $profit = $value;
        }
        else
        {
            $readonly = 'readonly';
            $profit = $this->CI->config->item('DEFAULT_PROFIT');
        }
        $lbl = $this->CI->lang->line('GENERIC_ENTER_PROFIT');
        return '<div class="form-right-div  input-append text-append-prepend " ><input '.$readonly.' type="text" placeholder="'.$lbl.'" value="'.$profit.'" name="dpm_provider_profit" id="dpm_provider_profit" title="Provider Profit" class="frm-size-medium ctrl-append-prepend" style="undefined;width: 67.2% !important;"><span class="add-on text-addon" style="height: 28px; line-height: 28px;">%</span></div>';
    }

    public function truncatepara($value = '', $id = '', $data = array())
    {
        return utf8_encode(substr($value, 0, 25))."...";
    }

    public function getDefaultProfitByAdmin($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        //
        return $this->CI->config->item('DEFAULT_PROFIT');
    }

    public function getMessage($value = '', $id = '', $data = array())
    {
        $html = '<a href="" data-toggle="modal" data-target="#myModal_'.$id.'">View Message</a> ';
        $html .= '<div class="modal fade" id="myModal_'.$id.'" role="dialog">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title ">Message</h4>
    <div class="modal-dialog">
      <div class="modal-content">
       <h5><b style="font-style: italic;font-size:15px;text-align:center;color:black">'.$value.'</b></h5>
      </div>
    </div>
  </div>';
        return $html;
    }

    public function hideCheckboxForDefaultRecords($render_arr = array())
    {
        $this->CI->db->select('iDistributorPageMasterId');
        $this->CI->db->where('iDistributorUserId', $this->CI->session->userdata('iAdminId'));
        $this->CI->db->where('ePageType', 'Default');
        $result = $this->CI->db->get('distributor_page_master')->result_array();
        $final_arr = array();
        foreach ($result as $records)
        {
            $final_arr[] = $records['iDistributorPageMasterId'];
        }
        $render_arr['hide_admin_rec'] = $final_arr;
        return $render_arr;
    }

    public function checkDefaultDelete($mode = '', $id = '', $parID = '')
    {
        $ret_arr = array();
        $this->CI->db->select('vPageCode');
        $this->CI->db->where('iDistributorPageMasterId', $id);
        $code = $this->CI->db->get('distributor_page_master')->first_row();
        if ($code->vPageCode == 'CONTACT_US')
        {
            $ret_arr['success'] = false;
            $ret_arr['message'] = "This record can not be deleted.";
            return $ret_arr;
        }
        $data = $this->CI->general->hideCheckboxForDefaultRecords();
        if (count($data['hide_admin_rec']) > 0)
        {
            foreach ($data['hide_admin_rec'] as $key => $val)
            {
                if ($id == $val)
                {
                    $ret_arr['success'] = false;
                    $ret_arr['message'] = "This record can not be deleted.";
                    break;
                }
                else
                {
                    $ret_arr['success'] = true;
                    $ret_arr['message'] = "Record deleted successfully.";
                }
            }
        }
        else
        {
            $ret_arr['success'] = true;
            $ret_arr['message'] = "Record deleted successfully.";
        }
        return $ret_arr;
    }

    public function getColorData($input_params = array())
    {
        $query = $this->CI->db->query("select cm.vProviderUniqueKey,cm.iProductMasterId as cm_product_id,cm.iProviderId,ccm.vColorName,pm.vProductParentCode,pm.iProviderId,pm.iProductMasterId as pm_product_id
from product_master as pm
left join product_master as cm on cm.vProductParentCode=pm.vProductParentCode and cm.iProviderId=pm.iProviderId
and cm.eStatus='Active'
left join color_master as ccm on ccm.iColorMasterId=cm.iColorId
where pm.iProductMasterId=".$input_params['product_id']."
group by cm.vProviderUniqueKey");
        $color_data = $query->result_array();
        return $color_data;
    }

    public function getPopularProductImage($value = '', $id = '', $data = array())
    {
        $img_type = $data['pm_image_type'];
        $img_tag = "<div class='noimage-icon-small'></div>";
        $url = $this->CI->config->item('upload_url')."product_images/";
        $path = $this->CI->config->item('upload_path')."product_images/";
        if ($img_type == 'Online')
        {
            $img_tag = '<a href="'.$value.'" class="fancybox-image"><img src="'.$value.'" alt="" class="online_image" /></a>';
        }
        else
        if ($img_type == 'Local')
        {
            if ($data['pm_multi_image_name'] != '')
            {
                if (file_exists($path.$data['pm_multi_image_name']))
                {
                    $img_tag = '<a href="'.$url.$data['pm_multi_image_name'].'" class="fancybox-image"><img src="'.$url.$data['pm_multi_image_name'].'" alt="" class="online_image" /></a>';
                }
            }
            else
            if ($data['pm_image'] != '')
            {
                if (file_exists($path.$data['pm_image']))
                {
                    $img_tag = '<a href="'.$url.$data['pm_image'].'" class="fancybox-image"><img src="'.$url.$data['pm_image'].'" alt="" class="online_image" /></a>';
                }
            }
        }
        return $img_tag;
    }

    public function savePopularProducts($mode = '', $id = '', $parID = '')
    {
        if ($mode != 'Update')
        {
            $post_arr = $this->CI->input->post();
            if ($post_arr['product_ids'] != '')
            {
                $products = explode(',', $post_arr['product_ids']);
            }
            else
            {
                $products = array();
            }
            if ($post_arr['category_ids'] != '')
            {
                $categories = explode(',', $post_arr['category_ids']);
            }
            else
            {
                $categories = array();
            }
            if (!empty($products) && !empty($categories))
            {
                $merged_data = array_combine($products, $categories);
            }
            else
            {
                $merged_data = array();
            }
            $mydata = array();

            foreach ($merged_data as $key => $value)
            {
                $this->CI->db->select('COUNT(iProductId) as total_prods');
                $this->CI->db->from('distributor_popular_product_trans');
                $this->CI->db->where('iProductId', $key);
                $this->CI->db->where('iCategoryId', $value);
                $this->CI->db->where('iDistributorUserId', $this->CI->session->userdata('iAdminId'));
                $existing_prod = $this->CI->db->get()->row_array();
                if ($existing_prod['total_prods'] == 0)
                {
                    $mydata[] = array(
                        'iProductId' => $key,
                        'iCategoryId' => $value,
                        'iDistributorUserId' => $post_arr['dppt_distributor_user_id'],
                        'iTypeFor' => $post_arr['dppt_type_for'],
                        'dAddedDate' => date('Y-m-d H:i:s'),
                    );
                }
                else
                {
                    $mydata = array();
                }
            }
            $return_arr = array();
            if (!empty($mydata) && count($mydata) > 0)
            {
                $inserted_data = $this->CI->db->insert_batch('distributor_popular_product_trans', $mydata);
                $return_arr['success'] = true;
                $return_arr['message'] = "Producto insertado correctamente";
                $return_arr['insert_status'] = $inserted_data;
            }
            else
            {
                $inserted_data = array();
                $return_arr['success'] = false;
                $return_arr['message'] = "Error al insertar el producto";
                $return_arr['insert_status'] = $inserted_data;
            }
        }
        else
        {
            $return_arr['success'] = true;
            $return_arr['message'] = "Producto insertado correctamente";
        }
        return $return_arr;
    }

    public function addCategoryInDistributor($mode = '', $id = '', $parID = '')
    {
        $userid = $this->CI->session->userdata('iAdminId');
        $categoryname = $_POST['cm_category_name'];
        $data = array(
            'vCategoryName' => $_POST['cm_category_name'],
            'eStatus' => $_POST['dcm_status'],
        );
        $this->CI->db->where('iCategoryMasterId', $id);
        $this->CI->db->where('iDistributorUserId', $userid);
        $this->CI->db->where('eCategoryType', 'Custom');
        $this->CI->db->update('category_master', $data);
        $no = $_POST['dcm_seq_no'];
        $this->CI->db->select('*');
        $this->CI->db->where('iCategoryMasterId', $id);
        $this->CI->db->where('iDistributorUserId', $userid);
        $query = $this->CI->db->get('distributor_category_trans');
        $result = $query->num_rows();
        if ($result < 1)
        {
            $data = array(
                'vCategoryName' => $categoryname,
                'iCategoryMasterId' => $id,
                'iSeqNo' => $no,
                'iProviderId' => 0,
                'iDistributorUserId' => $userid,
                'dAddedDate' => date('Y-m-d H:i:s'),
                'eDisplayInLeftMenu' => $_POST['dcm_display_in_left_menu'],
                'eStatus' => $_POST['dcm_status'],
            );

            $this->CI->db->insert('distributor_category_trans', $data);
        }
        else
        {
            $data = array(
                'vCategoryName' => $_POST['cm_category_name'],
                'eStatus' => $_POST['dcm_status'],
                'eDisplayInLeftMenu' => $_POST['dcm_display_in_left_menu'],
                'dModifiedDate' => date('Y-m-d H:i:s'),
                'iSeqNo' => $_POST['dcm_seq_no'],
            );
            $this->CI->db->where('iCategoryMasterId', $id);
            $this->CI->db->where('iDistributorUserId', $userid);
            $this->CI->db->update('distributor_category_trans', $data);
        }
        $ret_arr = array();
        $ret_arr['success'] = true;
        $ret_arr['message'] = "Category added successfully";
        return $ret_arr;
    }

    public function createLeadUbiqueId($input_params = array())
    {
        $dist_id = $input_params['distributor_id'];
        $this->CI->db->select('COUNT("lm.iLeadMasterId") as total_inq');
        $this->CI->db->from('lead_master as lm');
        $this->CI->db->where('lm.vLeadStageID', "INQUIERY");
        $this->CI->db->where('lm.iDistributorUserId', $dist_id);
        $count = $this->CI->db->get()->row_array();
        $count = $count['total_inq']+1;
        $unique_id = "INQ-".str_pad($count, 7, 0, STR_PAD_LEFT);
        $ret_arr = array();
        $ret_arr['unique_id'] = $unique_id;
        return $ret_arr;
    }

    public function getImageURL($input_params = array())
    {
        $img_type = $input_params['pm_image_type'];
        $path = $this->CI->config->item('upload_url')."product_images/";
        $url = "";
        if ($img_type == 'Online')
        {
            $url = $input_params['pm_image_url'];
        }
        else
        {
            $url = $input_params['pm_image'];
        }
        $return_arr = array();
        $return_arr['success'] = true;
        $return_arr['product_image_url'] = $url;
        $return_arr['lead_id'] = "00000".$input_params['insert_lead_data'][0]['insert_id'];
        return $return_arr;
    }

    public function getProductImage($value = '', $id = '', $data = array())
    {
        if ($value == 'Local')
        {
            $url = $this->CI->config->item('upload_url').DS."product_images".DS.$data['pm_image'];
            $path = $this->CI->config->item('upload_path').DS."product_images".DS.$data['pm_image'];
            if (file_exists($path))
            {
                if ($this->CI->config->item("cdn_activate") == TRUE)
                {
                    $ret_url = str_replace($this->CI->config->item('site_url'), $this->CI->config->item('cdn_http_url'), $url);
                }
                else
                {
                    $ret_url = $url;
                }
                return $ret_url;
            }
            else
            {
                $noimg = $this->getNoImageURL();
                return $this->CI->config->item('site_url').DS.'WS'.DS.'image_resize?pic='.base64_encode($noimg)."&width=167&height=111";
            }
        }
        else
        {
            if ($data['pm_image_url'] != '')
            {
                return $data['pm_image_url'];
            }
            else
            {
                $noimg = $this->getNoImageURL();
                return $this->CI->config->item('site_url').DS.'WS'.DS.'image_resize?pic='.base64_encode($noimg)."&width=167&height=111";
            }
        }
    }

    public function truncate_long_product_desc($value = '', $id = '', $data = array())
    {
        return substr($value, 0, 18)."..."; //removed utf8_encoding() from here for issue in description special character.
    }

    public function getProductImageHtml($value = '', $id = '', $data = array())
    {
        if ($value == 'Local')
        {
            $url = $this->CI->config->item('upload_url')."product_images".DS.$data['pm_image'];
            $path = $this->CI->config->item('upload_path')."product_images".DS.$data['pm_image'];
            $ret_url = '';
            if (file_exists($path))
            {
                if ($this->CI->config->item("cdn_activate") == FALSE)
                {
                    $ret_url = str_replace($this->CI->config->item('site_url'), $this->CI->config->item('cdn_http_url'), $url);
                }
                else
                {
                    $ret_url = $url;
                }
            }
            else
            {
                $noimg = $this->getNoImageURL();
                $ret_url = $this->CI->config->item('site_url').DS.'WS'.DS.'image_resize?pic='.base64_encode($noimg)."&width=167&height=111";
            }
        }
        else
        {
            if ($data['pm_image_url'] != '')
            {
                $ret_url = $data['pm_image_url'];
            }
            else
            {
                $noimg = $this->getNoImageURL();
                $ret_url = $this->CI->config->item('site_url').DS.'WS'.DS.'image_resize?pic='.base64_encode($noimg)."&width=167&height=111";
            }
        }
        return "<a href='".$ret_url."' class='fancybox-image'><img src='".$ret_url."'   class='online_image'></a>";
    }

    public function getProductDetailUrl($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a hijacked='yes' href='".$this->CI->config->item('admin_url')."#custom_products/custom_products/detail_view|id|".$data['custom_pm_product_id']."'>$value</a>";
        $button .= "</div>";
        return $button;
    }

    public function getViewButton($value = '', $id = '', $data = array())
    {
        $product_id = $data['iProductMasterId'];
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a title='view' class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#custom_products/custom_products/detail_view|id|".$product_id."'><i class='icon18 iconic-icon-eye'></i></a>";
        $button .= "</div>";
        return $button;
    }

    public function getScalePricingQuery($product_id = 5000, $distributor_id = 7, $parse = false, $profit = '',$show_false_price=false)
    {
        $live_package_code = $this->CI->session->userdata('live_subscription_detail')[0]['vPackageCode'] ? $this->CI->session->userdata('live_subscription_detail')[0]['vPackageCode'] : ecom_cnf('vPackageCode');
        $free_restrctions = $this->CI->config->item('FREE_RESTRICTIONS');
        $is_free_static_price = $this->CI->config->item('IS_FREE_STATIC_PRICE');
        $partner_free_restrctions = $this->CI->config->item('PARTNER_ACCOUNT_RESTRICTIONS');
       
        
        if (strtolower($live_package_code) == 'free' && strtolower(trim($free_restrctions)) == 'on')
        {
            $query = $this->CI->db->query("select p.iProductMasterId,`dm`.`fProviderProfit`,prd.iMaxQunatity,
             @v_fDistibutorProfitDiscount,
             @fProviderDiscount:=If(dm.fProviderDiscount>0,dm.fProviderDiscount,0) as v_fProviderDiscount,
             @v_fDistibutorProfitDiscount:=IF(`dm`.`fProviderProfit`>0,`dm`.`fProviderProfit`,".$profit.") as profit_applied,
             @after_discount_price:=ROUND((prd.fProductPrice - ((prd.fProductPrice * @fProviderDiscount)/100)),2) as after_discount_price_tmp,
            ROUND(ROUND((prd.fProductPrice - ((prd.fProductPrice * @fProviderDiscount)/100)),2)+ROUND((ROUND((prd.fProductPrice - ((prd.fProductPrice * @fProviderDiscount)/100)),2)*150)/100,2),2) as after_discount_price,
            prd.fProductPrice,@after_discount_price,@v_fDistibutorProfitDiscount,
            ROUND((@after_discount_price + ((@after_discount_price * @v_fDistibutorProfitDiscount)/100)),2),
            @extra_price:=IF(ROUND((@after_discount_price + ((@after_discount_price * @v_fDistibutorProfitDiscount)/100)),2)>0,ROUND((ROUND((@after_discount_price + ((@after_discount_price * @v_fDistibutorProfitDiscount)/100)),2)*150)/100,2),0.00),
            ROUND((@after_discount_price + ((@after_discount_price * @v_fDistibutorProfitDiscount)/100))+@extra_price,2) as selling_price,
            ROUND(((@after_discount_price * @v_fDistibutorProfitDiscount)/100),2) as profit_temp,
            ROUND(ROUND(((@after_discount_price * @v_fDistibutorProfitDiscount)/100),2)+ROUND((ROUND(((@after_discount_price * @v_fDistibutorProfitDiscount)/100),2)*150)/100,2),2) as profit,
            prd.*,p.iProviderId,p.fPrice
            from product_master as p
            left join product_price_scale as prd on p.iProductMasterId=prd.iProductId
            left join (distributor_provider_mapping  as dm) on  p.iProviderId=dm.iProviderId and dm.iDistributorUserId=".$distributor_id."
            where p.iProductMasterId=".$product_id."  group by prd.iProductId,prd.iMaxQunatity  order by prd.iMaxQunatity asc; ");
        }
        else
        //if (strtolower($live_package_code) == 'free' && strtolower(trim($is_free_static_price)) == 'yes' && $show_false_price==true)
       if($this->CI->session->userdata('verifiedUser')=='No' ||ecom_cnf('verifyAccount') =='No')
        {
            //echo "hola";
            $query = $this->CI->db->query("select p.iProductMasterId,`dm`.`fProviderProfit`,prd.iMaxQunatity,@v_fDistibutorProfitDiscount
            ,@fProviderDiscount:=If(dm.fProviderDiscount>0,dm.fProviderDiscount,0) as v_fProviderDiscount,
            @v_fDistibutorProfitDiscount:=IF(`dm`.`fProviderProfit`>0,0,0) as profit_applied,
            @after_discount_price:=ROUND((prd.fProductPrice - ((prd.fProductPrice * 0))),2) as after_discount_price,
            prd.fProductPrice,@after_discount_price,@v_fDistibutorProfitDiscount,
            ROUND((0 + ((0 * 0)+0.00)),2) as selling_price,
            ROUND(((0 * 0)),2) as profit
            , prd.*,p.iProviderId,p.fPrice
            from product_master as p
            left join product_price_scale as prd on p.iProductMasterId=prd.iProductId
            left join (distributor_provider_mapping  as dm) on  p.iProviderId=dm.iProviderId and dm.iDistributorUserId=".$distributor_id."
            where p.iProductMasterId=".$product_id."  group by prd.iProductId,prd.iMaxQunatity  order by prd.iMaxQunatity asc;");
        }
        else
        if (strtolower($live_package_code) == 'free' && strtolower(trim($is_free_static_price)) == 'yes' && $show_false_price==false)
        {
            $query = $this->CI->db->query("select p.iProductMasterId,`dm`.`fProviderProfit`,prd.iMaxQunatity,@v_fDistibutorProfitDiscount
            ,@fProviderDiscount:=If(dm.fProviderDiscount>0,dm.fProviderDiscount,0) as v_fProviderDiscount,
            @v_fDistibutorProfitDiscount:=IF(`dm`.`fProviderProfit`>0,`dm`.`fProviderProfit`,".$profit.") as profit_applied,
            @after_discount_price:=ROUND((prd.fProductPrice - ((prd.fProductPrice * @fProviderDiscount)/100)),2) as after_discount_price,
            prd.fProductPrice,@after_discount_price,@v_fDistibutorProfitDiscount,
            ROUND((@after_discount_price + ((@after_discount_price * @v_fDistibutorProfitDiscount)/100)),2) as selling_price,
            ROUND(((@after_discount_price * @v_fDistibutorProfitDiscount)/100),2) as profit
            , prd.*,p.iProviderId,p.fPrice
            from product_master as p
            left join product_price_scale as prd on p.iProductMasterId=prd.iProductId
            left join (distributor_provider_mapping  as dm) on  p.iProviderId=dm.iProviderId and dm.iDistributorUserId=".$distributor_id."
            where p.iProductMasterId=".$product_id." group by prd.iProductId,prd.iMaxQunatity order by prd.iMaxQunatity asc;");
        }
        else
        if (strtolower($live_package_code) == 'partner_free' && strtolower(trim($partner_free_restrctions)) == 'on')
        {
            $query = $this->CI->db->query("select p.iProductMasterId,`dm`.`fProviderProfit`,prd.iMaxQunatity,@v_fDistibutorProfitDiscount
            ,@fProviderDiscount:=If(dm.fProviderDiscount>0,dm.fProviderDiscount,0) as v_fProviderDiscount,
            @v_fDistibutorProfitDiscount:=IF(`dm`.`fProviderProfit`>0,`dm`.`fProviderProfit`,".$profit.") as profit_applied,
            @after_discount_price:=ROUND((prd.fProductPrice - ((prd.fProductPrice * @fProviderDiscount)/100)),2) as after_discount_price_tmp,
            ROUND(ROUND((prd.fProductPrice - ((prd.fProductPrice * @fProviderDiscount)/100)),2)+ROUND((ROUND((prd.fProductPrice - ((prd.fProductPrice * @fProviderDiscount)/100)),2)*150)/100,2),2) as after_discount_price,
            prd.fProductPrice,@after_discount_price,@v_fDistibutorProfitDiscount,
            ROUND((@after_discount_price + ((@after_discount_price * @v_fDistibutorProfitDiscount)/100)),2),
            @extra_price:=IF(ROUND((@after_discount_price + ((@after_discount_price * @v_fDistibutorProfitDiscount)/100)),2)>0,ROUND((ROUND((@after_discount_price + ((@after_discount_price * @v_fDistibutorProfitDiscount)/100)),2)*150)/100,2),0.00),
            ROUND((@after_discount_price + ((@after_discount_price * @v_fDistibutorProfitDiscount)/100))+@extra_price,2) as selling_price,
            ROUND(((@after_discount_price * @v_fDistibutorProfitDiscount)/100),2) as profit_temp,
            ROUND(ROUND(((@after_discount_price * @v_fDistibutorProfitDiscount)/100),2)+ROUND((ROUND(((@after_discount_price * @v_fDistibutorProfitDiscount)/100),2)*150)/100,2),2) as profit,
            prd.*,p.iProviderId,p.fPrice
            from product_master as p
            left join product_price_scale as prd on p.iProductMasterId=prd.iProductId
            left join (distributor_provider_mapping  as dm) on  p.iProviderId=dm.iProviderId and dm.iDistributorUserId=".$distributor_id."
            where p.iProductMasterId=".$product_id."  group by prd.iProductId,prd.iMaxQunatity  order by prd.iMaxQunatity asc;");
        }
        else
        {
            
            $query = $this->CI->db->query("select p.iProductMasterId,`dm`.`fProviderProfit`,prd.iMaxQunatity,@v_fDistibutorProfitDiscount
            ,@fProviderDiscount:=If(dm.fProviderDiscount>0,dm.fProviderDiscount,0) as v_fProviderDiscount,
            @v_fDistibutorProfitDiscount:=IF(`dm`.`fProviderProfit`>0,`dm`.`fProviderProfit`,".$profit.") as profit_applied,
            @after_discount_price:=ROUND((prd.fProductPrice - ((prd.fProductPrice * @fProviderDiscount)/100)),2) as after_discount_price,
            prd.fProductPrice,@after_discount_price,@v_fDistibutorProfitDiscount,
            ROUND((@after_discount_price + ((@after_discount_price * @v_fDistibutorProfitDiscount)/100)),2) as selling_price,
            ROUND(((@after_discount_price * @v_fDistibutorProfitDiscount)/100),2) as profit
            , prd.*,p.iProviderId,p.fPrice
            from product_master as p
            left join product_price_scale as prd on p.iProductMasterId=prd.iProductId
            left join (distributor_provider_mapping  as dm) on  p.iProviderId=dm.iProviderId and dm.iDistributorUserId=".$distributor_id."
            where p.iProductMasterId=".$product_id." group by prd.iProductId,prd.iMaxQunatity order by prd.iMaxQunatity asc;");
            //echo $this->CI->db->last_query();
        }
        $data = $query->result_array();
        /*echo "<pre>";
        print_r($this->CI->db->last_query());
        die;*/
        return $data;
    }

    public function splitCategories($mode = '', $id = '', $parID = '')
    {
        $cat_arr = explode(",", $this->CI->input->get_post('cg_grouping_category_id'));
        $batch_insert = array();
        foreach ($cat_arr as $id)
        {
            $batch_insert[] = array(
                'iMainCategoryId' => $this->CI->input->get_post('cg_main_category_id'),
                'iGroupingCategoryId' => $id,
                'dtAddedDate' => $this->CI->input->get_post('cg_added_date')
            );
        }
        $this->CI->db->insert_batch('category_grouping', $batch_insert);
        $return_arr = array();
        $return_arr['success'] = true;
        return $return_arr;
    }

    public function saveCustomCategoryProduucts($mode = '', $id = '', $parID = '')
    {
        $post_arr = $this->CI->input->post();
        if (trim($post_arr['product_listing_ids']) != '')
        {
            $products = explode(',', $post_arr['product_listing_ids']);
        }
        $this->CI->db->select('iProductId');
        $this->CI->db->from('custom_category_product_mapping');
        $this->CI->db->where('iCategoryMasterId', $post_arr['ccpm_category_master_id']);
        $this->CI->db->where('iDistributorUserId', $post_arr['ccpm_distributor_user_id']);
        $this->CI->db->where_in('iProductId', $products);
        $data = $this->CI->db->get()->result_array();
        $new_ara = array();
        foreach ($data as $key => $value)
        {
            if (($key = array_search($value['iProductId'], $products)) !== false)
            {
                unset($products[$key]);
            }
        }
        $return_arr = array();
        if (!empty($products))
        {
            $mydata = array();
            foreach ($products as $key => $value)
            {
                $mydata[] = array(
                    'iProductId' => $value,
                    'iCategoryMasterId' => $post_arr['ccpm_category_master_id'],
                    'iDistributorUserId' => $post_arr['ccpm_distributor_user_id'],
                    'dAddedDate' => date('Y-m-d H:i:s'),
                );
            }
            $inserted_data = $this->CI->db->insert_batch('custom_category_product_mapping', $mydata);
            $return_arr['success'] = true;
            $return_arr['message'] = $this->CI->lang->line('LBL_PRODUCT_ADDED_SUCCESSFULLY');
            $return_arr['insert_status'] = $inserted_data;
        }
        else
        {

            $return_arr['success'] = false;
            $return_arr['message'] = $this->CI->lang->line('LBL_PRODUCT_ALREADY_EXIST_IN_THIS_CATEGORY');
            $return_arr['insert_status'] = 'fail';
        }
        return $return_arr;
    }

    public function getImageTagForCustomProduct($value = '', $id = '', $data = array())
    {
        $img_type = $data['pm_image_type'];
        $img_tag = "<div class='noimage-icon-small'></div>";
        $path = $this->CI->config->item('upload_url')."product_images/";
        if ($img_type == 'Online')
        {
            $img_tag = '<a href="'.$data['pm_image_url'].'" class="fancybox-image"><img src="'.$data['pm_image_url'].'" alt="" class="online_image" style="height:30px;width:30px;"/></a>';
        }
        else
        if ($img_type == 'Local')
        {
            if ($data['pm_image'] != '')
            {
                if (file_exists($path.$data['pm_image']))
                {
                    $img_tag = '<a href="'.$path.$data['pm_image'].'" class="fancybox-image"><img src="'.$path.$data['pm_image'].'" alt="" class="local_image" /></a>';
                }
            }
        }
        return $img_tag;
    }

    public function getViewButtonForCustom_v1($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $button .= '<a hijacked="yes" class="btn btn_info custom-view-btn settings_btn" href="'.$this->CI->config->item('admin_url').'#product/category_listing_v1/add|mode|Update|id|'.$data['cm_category_master_id'].'|" title="Settings"><i class="entypo-icon-settings"></i></a>';
        if ($data['cm_parent_id'] > 0)
        {
            $cat_name = "sub_category";
        }
        else
        {
            $cat_name = "category";
        }
        if ($data['cm_category_type'] == 'Custom')
        {
            $button .= '<a target="_blank" class="btn btn_info custom-view-btn view_btn" href="'.$this->CI->config->item('admin_url').'#product/products_v1_v1/index|'.$cat_name.'|'.$data['cm_category_master_id'].'|is_custom_category|1" title="Products"><i class="icomoon-icon-gift"></i></a>';
            $button .= '<a class="btn btn_info custom-view-btn remove_category_button" href="javascript://" data-href="'.$this->CI->config->item('admin_url').'product/category_listing_v1/delete_custom" data-cat_id="'.$data['cm_category_master_id'].'" title="Delete"><i class="ui-icon ui-icon-trash"></i></a>';
            $button .= '<a hijacked="yes" class="btn btn_info custom-view-btn add_product_btn fancybox-popup" href="'.$this->CI->config->item('admin_url').'#distributor/distributor_custom_category_product_mapping/add|mode|Add|cat_id|'.$data['cm_category_master_id'].'|hideCtrl|true|loadGrid|list2" title="Settings"><i class="icomoon-icon-plus-2"></i></a>';
        }
        else
        {
            $button .= '<a target="_blank" class="btn btn_info custom-view-btn view_btn" href="'.$this->CI->config->item('admin_url').'#product/products_v1_v1/index|'.$cat_name.'|'.$data['cm_category_master_id'].'|" title="Products"><i class="icomoon-icon-gift"></i></a>';
        }

        $button .= "</div>";
        return $button;
    }

    public function getViewButtonForPrintShopContact($value = '', $id = '', $data = array())
    {
        $inquiry_id = $data['iPrintShopContactMasterId'];
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a title='Ver' class='btn btn_info custom-view-btn view_btn fancybox-hash-iframe' href='".$this->CI->config->item('admin_url')."#printshop/print_shop_inquiry/add|mode|View|id|".$inquiry_id."|hideCtrl|true|width|75%|height|75%'><i class=' icon18 iconic-icon-eye'></i></a>";
        $button .= "</div>";
        return $button;
    }

    public function getViewButtonForDistributorContact($value = '', $id = '', $data = array())
    {
        $inquiry_id = $data['iDistributorContactusMasterId'];
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a title='Ver' class='btn btn_info custom-view-btn view_btn fancybox-hash-iframe' href='".$this->CI->config->item('admin_url')."#distributor/distributor_contact_us/add|mode|View|id|".$inquiry_id."|hideCtrl|true|width|75%|height|75%'><i class='icon18 iconic-icon-eye'></i></a>";
        $button .= "</div>";
        return $button;
    }

    public function cust_num_format($value = '', $id = '', $data = array())
    {
        $num_format = number_format($value, 2);

        return $num_format;
    }

    public function custDateTimeFormat($datetime = '')
    {
        if (trim($datetime) == '')
        {
            return '';
        }
        elseif ($datetime == "0000-00-00" || $datetime == "0000-00-00 00:00:00")
        {
            return '';
        }

        $datetime = date_create($datetime);
        return date_format($datetime, "M d, Y g:i a");
    }

    public function mapProviderWithUsers($mode = '', $id = '', $parID = '')
    {
        $get_arr = is_array($this->CI->input->get(NULL, TRUE)) ? $this->CI->input->get(NULL, TRUE) : array();
        $post_arr = is_array($this->CI->input->post(NULL, TRUE)) ? $this->CI->input->post(NULL, TRUE) : array();
        $params_arr = array_merge($get_arr, $post_arr);

        $distributor_id = $this->CI->session->userdata('iAdminId');

        $insert_update_arr = array(
            'vName' => $params_arr['dpm_provider_name'],
            'vUniqueID' => strtoupper($params_arr['dpm_unique_id']),
            'iDistributorProviderMappingId' => $id,
            'eProviderType' => 'Custom',
            'eStatus' => 'Active',
            'iUserType' => '2',
            'eIsEmailVerified' => 'Yes',
            'vCompanyName' => $params_arr['company_name'],
            'vEmail' => strtoupper($params_arr['dpm_unique_id'])."@alyzta.com",
            'dModifiedDate' => date('Y-m-d h:i:s'),
            'iDistributorUserId' => $distributor_id,
        );
        
        $this->CI->db->select('iUsersId');
        $this->CI->db->select('vName');
        $this->CI->db->select('vUniqueID');
        $this->CI->db->where('iDistributorProviderMappingId', $id);
        $user_tb_data = $this->CI->db->get('users')->result_array();         
            //$user_tb_data=[];
        if (is_array($user_tb_data) && count($user_tb_data) > 0)
        {
            if ($params_arr['dpm_provider_name'] != $user_tb_data[0]['vName'] || $params_arr['dpm_unique_id'] != $user_tb_data[0]['vUniqueID'])
            {

                $this->CI->db->where('iUsersId', $user_tb_data[0]['iUsersId']);
                $this->CI->db->where('iDistributorProviderMappingId', $id);
                $this->CI->db->update('users', $insert_update_arr);
            }
        }
        else
        {
            if (strtolower($mode) == 'add')
            {
              
                $insert_update_arr['dAddedDate'] = date('Y-m-d h:i:s');
                $this->CI->db->insert('users', $insert_update_arr);
                $provider_id = $this->CI->db->insert_id();
                //$provider_id=2686;                
                $this->CI->db->where('iDistributorProviderMappingId', $id);
                $this->CI->db->update('distributor_provider_mapping', array('iProviderId' => $provider_id));
                
            }
        }
        if ($params_arr['dpm_seq_no'] <= 5)
        {
            $this->CI->db->where('iDistributorProviderMappingId', $id);
            $this->CI->db->update('distributor_provider_mapping', array('eSortType' => 'Top'));
        }

        $this->CI->db->where('iDistributorProviderMappingId!=', $id);
        $this->CI->db->where('iDistributorUserId', $distributor_id);
        $this->CI->db->where('iSeqNo>=', $params_arr['dpm_seq_no']);
        $this->CI->db->set('iSeqNo', 'iSeqNo+1', FALSE);
        $this->CI->db->update('distributor_provider_mapping');
        if ($post_arr['dpm_provider_type'] == 'Custom')
        {
            $params = array();
            $params['iUserId'] = $provider_id;
            $params['vPaymentAcceptedID'] = '';
            $params['eDistributerDiscount'] = 'Off';
            $params['dModifiedDate'] = date('Y-m-d h:i:s');
            $params['iCompanySizeID'] = 0;
            $params['iCompanyAgeID'] = 0;
            $params['vTypeOfProviderID'] = 19;
            $params['eOfferPrinting'] = 'No';
            $params['iPrintingOptionsID'] = 0;
            $params['ePrintingMachineAvailable'] = 'No';
            $params['vPrintingMachineAvailableNotes'] = '';
            $params['tOfferDescForDistributor'] = '';
            $params['vOtherPrintingOptions'] = '';
            $params['vOtherTypeOfProvider'] = '';
            $params['vInvoiceDocument'] = '';
            $params['vRFCDocument'] = '';
            $params['vProofAddressDocument'] = '';
            $params['iHearAboutUsID'] = 0;
            $params['vOtherHearAboutUs'] = '';
            $params['tOfferPrintingNote'] = '';
            $params['tOtherPrintingNote'] = '';
            $params['tProductionTime'] = '';
            $params['vGoogleDriveUrl'] = '';
            $params['vGoogleSheetUrl'] = '';
            $this->CI->db->insert('user_provider_settings', $params);
            $provider_setting_id = $this->CI->db->insert_id();
        }

        $return = array();
        $return['success'] = 1;
        $return['message'] = "Custom provider added successfully";
        return $return;
    }

    public function custDateFormat($datetime = '')
    {
        if (trim($datetime) == '')
        {
            return '';
        }
        elseif ($datetime == "0000-00-00" || $datetime == "0000-00-00 00:00:00")
        {
            return '';
        }

        $datetime = date_create($datetime);
        return date_format($datetime, "M d, Y");
    }

    public function checkCustomProviderKey($mode = '', $id = '', $parID = '')
    {
        $get_arr = is_array($this->CI->input->get(NULL, TRUE)) ? $this->CI->input->get(NULL, TRUE) : array();
        $post_arr = is_array($this->CI->input->post(NULL, TRUE)) ? $this->CI->input->post(NULL, TRUE) : array();
        $params_arr = array_merge($get_arr, $post_arr);
        $return = array(
            'success' => 1,
            "message" => 'unique code available',
        );

        if (strtolower($mode) == 'update')
        {
            $this->CI->db->select('iProviderId');
            $this->CI->db->where('iDistributorProviderMappingId', $id);
            $user_tb_data = $this->CI->db->get('distributor_provider_mapping')->result_array();
        }

        $this->CI->db->select('iUsersId');
        $this->CI->db->where('vUniqueID', $params_arr['dpm_unique_id']);
        if (is_array($user_tb_data) && count($user_tb_data) > 0)
        {

            $this->CI->db->where_not_in('iUsersId', array($user_tb_data[0]['iProviderId']));
        }

        $user_tb_data = $this->CI->db->get('users')->result_array();
        
        if (is_array($user_tb_data) && count($user_tb_data) > 0)
        {
            $return['success'] = 0;
            $return['message'] = "Duplicate Unique key";
        }

        return $return;
    }

    public function productSearchHelper($input_params = array())
    {
        $return = array();
        //$return[0]['extra_cond'][] = '1=1';
        $pp = $this->CI->session->userdata('per_page_prod_limit');
        $limit = 48;
        if ($input_params['custom_rec_limit'] != '')
        {
            $limit = intval($input_params['custom_rec_limit']) > 0 ? intval($input_params['custom_rec_limit']) : 48;
        }
        else
        {
            $limit = intval($pp) > 0 ? $pp : 48;
        }
        $return[0]['rec_limit'] = $limit > 0 ? $limit : 48;
        $live_package_code = ecom_cnf('vPackageCode');
        $free_restrctions = $this->CI->config->item('FREE_RESTRICTIONS');
        $partner_free_restrctions = $this->CI->config->item('PARTNER_ACCOUNT_RESTRICTIONS');
        $return[0]['dpptjoincondition'] = '1=1';
        $return[0]['groupby'] = 'pm.vProductParentCode';
        $return[0]['having'] = '1=1';
        if ($input_params['section_type'] != '')
        {
            $return[0]['extra_cond'] = '1=1';
            if ($input_params['section_type'] == '0')
            {
                $return[0]['rec_limit'] = 24;
            }
            elseif ($input_params['section_type'] == '1')
            {
                $return[0]['rec_limit'] = 1;
            }
            elseif ($input_params['section_type'] == '2')
            {
                $return[0]['rec_limit'] = 24;
                $return[0]['groupby'] = 'pm.iCategoryId';
            }
            $return[0]['dpptjoincondition'] = "dppt.iTypeFor= '".$input_params['section_type']."'";
            $this->CI->db->select("COUNT('iDistributorPopularProductTransId') as count");
            $this->CI->db->where('iDistributorUserId', $input_params['distributor_id']);
            $this->CI->db->where('iTypeFor', $input_params['section_type']);
            $popp = $this->CI->db->get('distributor_popular_product_trans')->first_row();
            if ($popp->count > 0)
            {
                $return[0]['extra_cond'] = '( dppt.iDistributorPopularProductTransId>0 AND dppt.iTypeFor='.$input_params['section_type'].'  AND dppt.eHide ="No")';
            }
            if (strtolower($live_package_code) == 'free' && strtolower(trim($free_restrctions)) == 'on')
            {
                $allowed_provider_ids = $this->CI->config->item('FREE_PROVIDERS');
                $return[0]['extra_cond'] = "pm.iProviderId IN (".$allowed_provider_ids.") AND pm.iProductMasterId IN (SELECT iProductMasterId FROM free_account_product_list WHERE eStatus='Active')";
            }
            if (strtolower($live_package_code) == 'partner_free' && strtolower(trim($partner_free_restrctions)) == 'on')
            {
                $allowed_provider_ids = $this->CI->config->item('FREE_PROVIDERS');
                $return[0]['extra_cond'] = "pm.iProviderId IN (".$allowed_provider_ids.") AND pm.iProductMasterId IN (SELECT iProductMasterId FROM free_account_product_list WHERE eStatus='Active')";
            }
            $return[0]['order_by'] = 'dppt_seq_no ASC';
            return $return;
        }
        if ($input_params['keyword'] != '')
        {
            $get_keyword_arr = preg_split('/ /', $input_params['keyword'], -1, PREG_SPLIT_NO_EMPTY);
            if(is_array($get_keyword_arr) && count($get_keyword_arr) == 1){
                if ($input_params['is_custom_code'] == 'Yes')
                {
                    $pcode = base64_decode($input_params['keyword']."=");
                    if ($pcode!='') {
                        $return[0]['extra_cond'][] = " (CONCAT(u.vUCode,'-',pm.vPCode,'-','".$input_params['u_ucode']."') LIKE '%".$pcode."%' OR pm.tLongDescription LIKE('%".$input_params['keyword']."%') OR dpm.vProviderName LIKE('%".$input_params['keyword']."%')  OR mm.vMaterialName LIKE('%".$input_params['keyword']."%') OR cm.vCategoryName LIKE('%".$input_params['keyword']."%') OR cm1.vCategoryName LIKE('%".$input_params['keyword']."%')  OR cm2.vColorName LIKE('%".$input_params['keyword']."%'))";
                    }else{
                        //CONCAT(u.vUCode,'-',pm.vPCode,'-','".$input_params['u_ucode']."') LIKE '%".$pcode."%' OR
                        $return[0]['extra_cond'][] = " ( pm.tLongDescription LIKE('%".$input_params['keyword']."%') OR dpm.vProviderName LIKE('%".$input_params['keyword']."%')  OR mm.vMaterialName LIKE('%".$input_params['keyword']."%') OR cm.vCategoryName LIKE('%".$input_params['keyword']."%') OR cm1.vCategoryName LIKE('%".$input_params['keyword']."%')  OR cm2.vColorName LIKE('%".$input_params['keyword']."%'))";
                    }
                }
                else
                {
                    $return[0]['extra_cond'][] = "( pm.tLongDescription LIKE('%".$input_params['keyword']."%') OR pm.vProductParentCode LIKE('%".$input_params['keyword']."%') OR dpm.vProviderName LIKE('%".$input_params['keyword']."%') OR pm.vProviderUniqueKey LIKE('%".$input_params['keyword']."%') OR mm.vMaterialName LIKE('%".$input_params['keyword']."%') OR cm.vCategoryName LIKE('%".$input_params['keyword']."%') OR cm1.vCategoryName LIKE('%".$input_params['keyword']."%')  OR cm2.vColorName LIKE('%".$input_params['keyword']."%'))";
                }
            } else {
                foreach ($get_keyword_arr as $key => $value) {
                    $keyword= trim($value);
                    if ($input_params['is_custom_code'] == 'Yes')
                    {
                        $pcode = base64_decode($keyword."=");
                        if ($pcode!='') {
                            $tmp = " (CONCAT(u.vUCode,'-',pm.vPCode,'-','".$input_params['u_ucode']."') LIKE '%".$pcode."%' OR pm.tLongDescription LIKE('%".$keyword."%') OR dpm.vProviderName LIKE('%".$keyword."%') OR mm.vMaterialName LIKE('%".$keyword."%') OR cm.vCategoryName LIKE('%".$keyword."%') OR cm1.vCategoryName LIKE('%".$keyword."%')  OR cm2.vColorName LIKE('%".$keyword."%'))";
                        }else{
                           $tmp = " (pm.tLongDescription LIKE('%".$keyword."%') OR dpm.vProviderName LIKE('%".$keyword."%') OR mm.vMaterialName LIKE('%".$keyword."%') OR cm.vCategoryName LIKE('%".$keyword."%') OR cm1.vCategoryName LIKE('%".$keyword."%')  OR cm2.vColorName LIKE('%".$keyword."%'))"; 
                        }
                    }
                    else
                    {
                        $tmp = "( pm.tLongDescription LIKE('%".$keyword."%') OR              pm.vProductParentCode LIKE('%".$keyword."%') OR dpm.vProviderName LIKE('%".$keyword."%') OR pm.vProviderUniqueKey LIKE('%".$keyword."%')  OR mm.vMaterialName LIKE('%".$keyword."%') OR cm.vCategoryName LIKE('%".$keyword."%') OR cm1.vCategoryName LIKE('%".$keyword."%')  OR cm2.vColorName LIKE('%".$keyword."%') )";
                    }
                    $return[0]['extra_cond'][] = $tmp;   
                    $tmp = '';
                }
            }
        }
        if (is_array($input_params['category_ids']) && count($input_params['category_ids']) > 0)
        {
            $catids = implode(",", $input_params['category_ids']);
            $return[0]['extra_cond'][] = "( ccpm.iCategoryMasterId IN(".$catids.") OR  pm.iCategoryId IN(".$catids.") OR pm.iSubCategoryId IN (".$catids.") )";
        }
        if (strtolower($live_package_code) == 'free' && strtolower(trim($free_restrctions)) == 'on')
        {
            $allowed_provider_ids = $this->CI->config->item('FREE_PROVIDERS');
            $return[0]['extra_cond'][] = "pm.iProviderId IN (".$allowed_provider_ids.")";
            $return[0]['extra_cond'][] = "pm.iProductMasterId IN (SELECT iProductMasterId FROM free_account_product_list WHERE eStatus='Active')";
        }
        if (strtolower($live_package_code) == 'partner_free' && strtolower(trim($partner_free_restrctions)) == 'on')
        {
            $allowed_provider_ids = $this->CI->config->item('FREE_PROVIDERS');
            $return[0]['extra_cond'][] = "pm.iProviderId IN (".$allowed_provider_ids.")";
            $return[0]['extra_cond'][] = "pm.iProductMasterId IN (SELECT iProductMasterId FROM free_account_product_list WHERE eStatus='Active')";
        }
        if ($input_params['product_code'] != '')
        {
            $pcode = base64_decode($input_params['product_code']."=");
            if ($input_params['is_custom_code'] == 'Yes')
            {
                $return[0]['extra_cond'][] = " CONCAT(u.vUCode,'-',pm.vPCode,'-','".$input_params['u_ucode']."') LIKE '%".$pcode."%' ";
            }
            else
            {
                $return[0]['extra_cond'][] = " ( pm.vProviderUniqueKey LIKE '%".$input_params['product_code']."%' OR pm.vProductParentCode LIKE '%".$input_params['product_code']."%') ";
            }
        }
        $return[0]['extra_cond'] = implode(" AND ", $return[0]['extra_cond']);
        if ($input_params['sort_by'] == 'price')
        {
            if ($input_params['sort_dir'] == 'desc')
            {
                $return[0]['order_by'] = ' after_profit_max_price  desc';
            }
            else
            {
                $return[0]['order_by'] = ' after_profit_min_price  asc';
            }
            $return[0]['order_by'] .= ',dpm_seq_no ASC';
        }
        elseif ($input_params['sort_by'] == 'name')
        {
            if ($input_params['sort_dir'] == 'desc')
            {
                $return[0]['order_by'] = ' vProductParentCode  desc';
            }
            else
            {
                $return[0]['order_by'] = ' vProductParentCode  asc';
            }
            $return[0]['order_by'] .= ',dpm_seq_no ASC';
        }
        else
        {
            $return[0]['order_by'] = 'dpm_seq_no ASC';
        }
        if ($input_params['lowest_price'] == '')
        {
            $input_params['lowest_price'] = 0;
        }
        if ($input_params['highest_price'] == '')
        {
            $input_params['highest_price'] = 1000000000;
        }
        $having = array();
        if ((isset($input_params['lowest_price']) && $input_params['lowest_price'] >= 0) && (isset($input_params['highest_price']) && $input_params['highest_price'] > 0))
        {
            $having[] = '
                (after_profit_min_price <= '.$input_params['lowest_price'].' and after_profit_min_price <= '.$input_params['highest_price'].') and (after_profit_max_price >= '.$input_params['highest_price'].') or

                (after_profit_min_price >= '.$input_params['lowest_price'].' and after_profit_min_price <= '.$input_params['highest_price'].') and (after_profit_max_price >= '.$input_params['highest_price'].') or

               (after_profit_max_price <= '.$input_params['highest_price'].' and after_profit_max_price >= '.$input_params['lowest_price'].') and (after_profit_min_price <= '.$input_params['lowest_price'].') or

               (after_profit_min_price >= '.$input_params['lowest_price'].' and after_profit_max_price >= '.$input_params['lowest_price'].') and (after_profit_max_price <= '.$input_params['highest_price'].' and after_profit_min_price <= '.$input_params['highest_price'].')
                ';
        }
        elseif ($input_params['lowest_price'] >= 0 && $input_params['lowest_price'] != '')
        {
            $having[] = 'after_profit_min_price <= '.$input_params['lowest_price'].' ';
        }
        elseif ($input_params['highest_price'] >= 0 && $input_params['highest_price'] != '')
        {
            $having[] = 'after_profit_max_price <= '.$input_params['highest_price'].' ';
        }
        if (count($having) > 0)
        {
            $return[0]['having'] = implode(" AND ", $having);
        }
        return $return;
    }

    public function getComapnyName($id = '')
    {
        $this->CI->db->select('vCompanyName');
        $this->CI->db->where('iLeadCompanyId', $id);
        $query = $this->CI->db->get('lead_company');
        $companyname = $query->result();

        return $companyname[0]->vCompanyName;
    }

    public function getViewButtonForProviders($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#custom_products/custom_products/provider_detail_view|id|".$data['iUsersId']."'><i class='icon18 iconic-icon-eye'></i></a>";
        if ($data['ups_google_drive_url'] != '')
        {
            $button .= "<a class='btn btn_info custom-view-btn gdrive_btn' target='_blank' href='".$data['ups_google_drive_url']."'><i class='gdrive'></i></a>";
        }
        if ($data['ups_google_sheet_url'] != '')
        {
            $button .= "<a class='btn btn_info custom-view-btn gsheet_btn' target='_blank' href='".$data['ups_google_sheet_url']."'><i class='gsheet'></i></a>";
        }
        $button .= "</div>";
        return $button;
    }

    public function getScalePricingQueryProvider($product_id = 5000, $distributor_id = 7, $parse = false)
    {
        $query = $this->CI->db->query("select p.iProductMasterId, prd.fTotalPrice,p.ePrinting as offer_printing, prd.*, p.iProviderId,p.fPrice,prd.fProductPrice as selling_price from product_master as p left join product_price_scale as prd on p.iProductMasterId=prd.iProductId left join (distributor_provider_mapping  as dm) on  p.iProviderId=dm.iProviderId and dm.iDistributorUserId=".$distributor_id." where p.iProductMasterId=".$product_id." order by prd.iMaxQunatity asc");
        $data = $query->result_array();
        return $data;
    }

    public function getEditBtnForWebsiteTemplate($value = '', $id = '', $data = array())
    {
        $template_id = $data['iWebsiteTemplateMasterId'];
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a class='btn btn_info custom-view-btn settings_btn' href='".$this->CI->config->item('admin_url')."#master/websites_templates/add|mode|Update|id|".$template_id."'><i class='entypo-icon-settings'></i></a>";
        $button .= "</div>";
        return $button;
    }

    public function importInprogress()
    {
        $type = $this->CI->session->userdata('vType');
        $id = $this->CI->session->userdata('iAdminId');
        if ($type == 'distributor')
        {
            $distributorid = $this->CI->session->userdata('iAdminId');
        }
        $data = array();
        $this->CI->db->select('vpcm.*,COUNT(cpm.iVendorProductCsvMasterId) as error_count');
        $this->CI->db->from('vendor_product_csv_master as vpcm');
        $this->CI->db->join('copy_product_master as cpm', 'vpcm.iVendorProductCsvMasterId=cpm.iVendorProductCsvMasterId', "left");
        $this->CI->db->where('vpcm.iProgressStatus', 0);
        if ($type == 'distributor')
        {
            $this->CI->db->where('vpcm.iDistributorUserId', $distributorid);
        }
        else
        {
            $this->CI->db->where('vpcm.iVendorId', $id);
        }
        $query = $this->CI->db->get();
        $data = $query->result_array();
        if ($data[0]['iVendorProductCsvMasterId'] > 0)
        {
            return "<div class='row-fluid'>
                    <div class='span3'><b>".$this->CI->lang->line('IMPORT_CURRENT_FILE').":</b><br/>".$data[0]['vUploadFileName']."</div>
                    <div class='span3'><b>".$this->CI->lang->line('IMPORT_PRODUCT_DATA_AVAILABLE').":<b><br/>".$data[0]['iTotalRecordFound']."</div>
                    <div class='span3'><b>".$this->CI->lang->line('IMPORT_PRODUCT_TOTAL_DATA_PROCESSED').":</b><br/>".$data[0]['iTotalRecordProcessesd']."</div>
                    <div class='span3'><b>".$this->CI->lang->line('IMPORT_PRODUCT_ERROR_TOTAL').":</b><br/>".$data[0]['error_count']."</div>
                    </div>
                     <div style='margin-top:10px;' class='alert alert-info fade in alert-dismissable my_alert'><b>".$this->CI->lang->line('IMPORT_PRODUCT_NOTE')."</b></div>

                     <style>
                     #cc_sh_vpcm_vendor_id{display:none !important;}
					.upload-form{display:none;}
                     #action_btn_container{display:none;}
                    </style>
                    ";
        }
        else
        {
            return "<style>#cc_sh_sys_static_field_3{display:none;}</style>";
        }
    }

    public function get_session_providerid($input_params = array())
    {
        $return_arr[0]['provider_id'] = $this->CI->session->userdata('iAdminId');
        return $return_arr;
    }

    public function getBannerImage(&$input_params = array())
    {
        $admin_banner = array();
        $distributor_banner = array();
        foreach ($input_params['get_all_banner'] as $val)
        {
            if ($val['dbm_distributor_user_id'] > 0)
            {

                $distributor_banner[] = $val;
            }

            else
            {
                $admin_banner[] = $val;
            }
        }
        if (count($distributor_banner) > 0)
        {
            $input_params['get_all_banner'] = $distributor_banner;
        }
        elseif (count($admin_banner) > 0)
        {
            $input_params['get_all_banner'] = $admin_banner;
            shuffle($input_params['get_all_banner']);
        }
    }

    public function get_ecom_link($distributor_id = '')
    {
        $this->CI->db->select('vCustomDomainURL');
        $this->CI->db->from('user_distributor_setting');
        $this->CI->db->where('iUserId', $distributor_id);
        $data = $this->CI->db->get()->row_array();
        $prefix = '';
        $url = $this->CI->config->item('site_url').$prefix.$data['vCustomDomainURL'];
        return $url;
    }

    public function seq_display($value = '', $id = '', $data = array())
    {
        if ($value == '99999') return '--';
        else return $value;
    }

    public function get_provider_or_catname($provider_id = 0, $cat_id = 0)
    {
        if ($provider_id > 0)
        {
            $this->CI->db->select('GROUP_CONCAT(vLegalCompanyName) as vName');
            $this->CI->db->where_in('iUsersId', explode(",", $provider_id));
            $data = $this->CI->db->get('users')->first_row();
            return " Of ".$this->CI->lang->line('GENERIC_PROVIDERS').": ".$data->vName;
        }
        if ($cat_id > 0)
        {
            $this->CI->db->select('GROUP_CONCAT(vCategoryName) as vCategoryName');
            $this->CI->db->where_in('iCategoryMasterId', explode(",", $cat_id));
            $data = $this->CI->db->get('category_master')->first_row();
            return " Of ".$this->CI->lang->line('LBL_FRONT_SEARCH_PRODUCT_MODAL_CATEGORY').": ".$data->vCategoryName;
        }

        return '';
    }

    public function getProviderProfit($value = '', $id = '', $data = array())
    {
        if ($value == 0)
        {

            $pd = $this->CI->session->userdata('fDistibutorProfitDiscount');
            $pp = $this->CI->session->userdata('vDistibutorProfitPreference');
            if ($pp == 'Default' || $pp == 'Hide')
            {
                $value = number_format($this->CI->config->item('DEFAULT_PROFIT'), 2);
            }
            else
            {
                $value = number_format($this->CI->session->userdata('fDistibutorProfitDiscount'), 2);
            }
        }
        return $value;
    }

    public function get_content_message($input_params = array())
    {
        $return = array();
        $return[0]['email_body'] = '';
        $return[0]['email_subject'] = '';
        $this->CI->db->select("*");
        $this->CI->db->where('vEmailCode', $input_params['email_type']);
        $this->CI->db->where('iDistributorUserId', $input_params['distributor_id']);
        $result = $this->CI->db->get('distributor_email_template')->first_row();

        $return[0]['email_body'] = $result->tEmailMessage;
        $return[0]['email_subject'] = $result->vEmailSubject;
        $return[0]['vFromName'] = $result->vFromName;
        $return[0]['vFromEmail'] = $result->vFromEmail;
        $return[0]['vCcEmail'] = $result->vCcEmail;
        $return[0]['vBccEmail'] = $result->vBccEmail;

        foreach ($input_params as $index => $value)
        {

            $return[0]['email_body'] = str_replace("{".$index."}", $value, $return[0]['email_body']);
            $return[0]['email_subject'] = str_replace("{".$index."}", $value, $return[0]['email_subject']);

            $return[0]['email_body'] = str_replace("#".$index."#", $value, $return[0]['email_body']);
            $return[0]['email_subject'] = str_replace("#".$index."#", $value, $return[0]['email_subject']);
        }

        return $return;
    }

    public function get_extra_condition_for_provider($input_params = array())
    {
        $return_arr[0]['extra_cond'] = '1=1';
        return $return_arr;
    }

    public function getLeadId($value = '', $data_arr = array())
    {
        $value = str_pad($value, 7, "0", STR_PAD_LEFT);
        return $value;
    }

    public function getProviderCompanyName($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $this->CI->db->select('vCompanyName');
        $this->CI->db->where('iUsersId', $data['dpm_provider_id']);
        $data = $this->CI->db->get('users')->first_row();
        return $data->vCompanyName;
    }

    public function distributor_registration_helper($input_params = array())
    {
        $return_arr = array();
        $return_arr[0]['user_status'] = 'Active';
        $return_arr[0]['is_free_user'] = 'no';
        $return_arr[0]['email_veri_status'] = 'Yes';
        $return_arr[0]['is_premium_free_trial'] = 'No';
        $package_arr = json_decode($input_params['package_arr'], true);
        if ($input_params['package_arr'] == 'free')
        {
            $return_arr[0]['user_status'] = 'Pending';
            $return_arr[0]['is_free_user'] = 'yes';
            $return_arr[0]['email_veri_status'] = 'No';
            $return_arr[0]['is_premium_free_trial'] = 'No';
        }

        $this->CI->db->select('vPlanCode,vZohoPlanCode');
        $this->CI->db->from('subscription_master');
        $this->CI->db->where('iSubscriptionMasterId', $package_arr['selected_package']);
        $pkg_data = $this->CI->db->get()->row_array();
        if (strtolower($pkg_data['vPlanCode']) == 'free')
        {
            $return_arr[0]['user_status'] = 'Pending';
            $return_arr[0]['is_free_user'] = 'yes';
            $return_arr[0]['email_veri_status'] = 'No';
            $return_arr[0]['is_premium_free_trial'] = 'No';
        }
        elseif (strtolower($pkg_data['vZohoPlanCode']) == 'web_premium_free')
        {
            $return_arr[0]['user_status'] = 'Pending';
            $return_arr[0]['is_free_user'] = 'yes';
            $return_arr[0]['email_veri_status'] = 'No';
            $return_arr[0]['is_premium_free_trial'] = 'Yes';
        }

        return $return_arr;
    }

    public function generate_custom_product_id()
    {
        return hexdec(substr(uniqid(), 0, 6));
    }

    
     public function getIproductId(){
        $this->CI->db->select(" getSequence ('lead_product_items') as iProductId");            
        $data = $this->CI->db->get()->row_array();  
        $iProductId =$data['iProductId'];       
		return $iProductId;
       // return hexdec(substr(uniqid(), 0, 6));
     }
    
    
    
    public function provider_contact_email($to = '', $subject = '', $body = '', $from = '', $from_name = '', $cc = '', $bcc = '', $attach = array()){
        $this->CISendMail($to, $subject, $body, $from, $from_name, $cc, $bcc, $attach);
    }
    
    
    
    
    public function send_distributor_email($input_params = array())
    {
        /*echo "<pre>";
        print_r($input_params);
        die;*/
        
        $ddrep = array();
        
        if (ecom_cnf('iUsersId') > 0)
        {
            /*echo "<pre>";
        print_r("asdfasdfasd");
        die;*/
            $ddrep['distributor_logo'] = ecom_cnf('vCompanyLogo');
            $ddrep['distributor_company_email'] = ecom_cnf('vCompanyEmail');
            $ddrep['distributor_company_name'] = ecom_cnf('vCompanyName');
            $ddrep['distributor_company_contact_number'] = ecom_cnf('vCompamyContactNumber');
            $ddrep['distributor_name'] = ecom_cnf('vName');
            
        }
        /*echo "<pre>";
        print_r($ddrep);
        die;*/
        
        /*Set ENV Var to change SMTP Condition*/
        $_ENV['dist_smtp'] = true;
        $_ENV['dist_smtp_uid'] = ecom_cnf('iUsersId') ? ecom_cnf('iUsersId') : $this->CI->session->userdata('iAdminId');
        
        /*Set ENV Var to change SMTP Condition*/
        foreach ($input_params['contact_us_eamil_templates'] as $row)
        {

            foreach ($ddrep as $key => $value)
            {
                $row['det_email_message'] = str_replace("#".$key."#", $value, $row['det_email_message']);
                $row['det_email_subject'] = str_replace("#".$key."#", $value, $row['det_email_subject']);
            }

            foreach ($input_params as $key => $value)
            {
                $row['det_email_message'] = str_replace("#".$key."#", $value, $row['det_email_message']);
                $row['det_email_subject'] = str_replace("#".$key."#", $value, $row['det_email_subject']);
            }
            switch ($row['det_email_code'])
            {
                
                case 'CONTACT_US':
                return    $this->CISendMail(ecom_cnf('vCompanyEmail'), $row['det_email_subject'].":".$input_params['subject'], $row['det_email_message'], $input_params['email'], $input_params['name'], '', $row['det_bcc_email']);
                    break;
                case 'CONTACT_US_REPONSE':
                    $row['det_from_email'] = ecom_cnf('vCompanyEmail');
                    $row['det_from_name'] = ecom_cnf('vLegalCompanyName');
                 return   $this->CISendMail($input_params['email'], $row['det_email_subject'], $row['det_email_message'], $row['det_from_email'], $row['det_from_name'], '', $row['det_bcc_email']);
                    break;
                case 'PRODUCT_INQUIRY':
                    $row['det_from_email'] = ecom_cnf('vCompanyEmail');
                    $row['det_from_name'] = ecom_cnf('vLegalCompanyName');
                 return   $this->CISendMail($input_params['email'], $row['det_email_subject'], $row['det_email_message'], $input_params['customer_email'], $input_params['customer_name'], '', $row['det_bcc_email'], $input_params['distributor_pdf']);
                    break;
                case 'PRODUCT_INQUIRY_RESPONSE':
                    $row['det_from_email'] = ecom_cnf('vCompanyEmail');
                    $row['det_from_name'] = ecom_cnf('vLegalCompanyName');
                  return  $this->CISendMail($input_params['customer_email'], $row['det_email_subject'], $row['det_email_message'], $row['det_from_email'], $row['det_from_name'], $input_params['costumer_cc_email'], $row['det_bcc_email'], $input_params['customer_pdf']);
                    break;
                case 'PRODUCT_QUOTATION_TO_CUSTOMER':
                    $row['det_from_email'] = $this->CI->session->userdata('vEmail');
                    $row['det_from_name'] =$this->CI->session->userdata('vName')." de ". $this->CI->session->userdata('vLegalCompanyName');

                  return  $this->CISendMail($input_params['customer_email'], $row['det_email_subject'], $row['det_email_message'], $row['det_from_email'], $row['det_from_name'], $input_params['costumer_cc_email'], $row['det_bcc_email'], $input_params['customer_pdf']);
                    break;
                case 'PRODUCT_QUOTATION_TO_DISTRIBUTOR':
                    $row['det_from_email'] = $this->CI->session->userdata('vCompanyEmail');
                    $row['det_from_name'] = $this->CI->session->userdata('vLegalCompanyName');
                  return  $this->CISendMail($input_params['email'], $row['det_email_subject'], $row['det_email_message'], $input_params['customer_email'], $input_params['customer_name'], '', $row['det_bcc_email'], $input_params['distributor_pdf']);
                    break;
                case 'ADD_SALESMAN':
                    $row['det_from_email'] = $this->CI->session->userdata('vCompanyEmail');
                    $row['det_from_name'] = $this->CI->session->userdata('vLegalCompanyName');
                  return  $this->CISendMail($input_params['distributor_mail'], $row['det_email_subject'], $row['det_email_message'], $row['det_from_email'], $row['det_from_name'], '', $row['det_bcc_email']);
                    break;
                case 'ADD_SALESMAN_ADMIN':
                    $row['det_from_email'] = $this->CI->session->userdata('vCompanyEmail');
                    $row['det_from_name'] = $this->CI->session->userdata('vLegalCompanyName');
                   return $this->CISendMail($input_params['admin_mail'], $row['det_email_subject'], $row['det_email_message'], $row['det_from_email'], $row['det_from_name'], '', $row['det_bcc_email']);
                    break;
                case 'SALESMAN':
                    $row['det_from_email'] = $this->CI->session->userdata('vCompanyEmail');
                    $row['det_from_name'] = $this->CI->session->userdata('vLegalCompanyName');
                   return $this->CISendMail($input_params['salesman_mail'], $row['det_email_subject'], $row['det_email_message'], $row['det_from_email'], $row['det_from_name'], '', $row['det_bcc_email']);
                    break;
                case 'TEST_SMTP_MAIL':
                    $sentmail = $this->CISendMail($input_params['email'], $row['det_email_subject'], $row['det_email_message'], $input_params['from_email'], $input_params['distributor_company_name']);
                    return $sentmail;
                    break;
            }
        }
    }

    public function send_product_inquiry_email($input_params = array())
    {
        $pro_inquiry = array();
        $pro_inquiry['distributor_name'] = ecom_cnf('vName');
        $pro_inquiry['distributor_company_name'] = ecom_cnf('vCompanyName');
        $pro_inquiry['distributor_company_email'] = ecom_cnf('vCompanyEmail');
        $pro_inquiry['distributor_company_contact_number'] = ecom_cnf('vCompamyContactNumber');
        $pro_inquiry['distributor_logo'] = ecom_cnf('vCompamyLogo');

        foreach ($input_params['product_inquiry_email'] as $row)
        {

            foreach ($pro_inquiry as $key => $value)
            {
                $row['det_email_message'] = str_replace("#".$key."#", $value, $row['det_email_message']);
                $row['det_email_subject'] = str_replace("#".$key."#", $value, $row['det_email_subject']);
            }

            foreach ($input_params as $key => $value)
            {
                $row['det_email_message'] = str_replace("#".$key."#", $value, $row['det_email_message']);
                $row['det_email_subject'] = str_replace("#".$key."#", $value, $row['det_email_subject']);
            }
            switch ($row['det_email_code'])
            {
                case 'PRODUCT_INQUIRY':
                    $this->CISendMail($input_params['unique_id'], ecom_cnf('vCompanyEmail'), $row['det_email_subject'].":".$input_params['subject'], $row['det_email_message'], $row['det_from_email'], $row['det_from_name']);
                    break;
                case 'PRODUCT_INQUIRY_RESPONSE':
                    $this->CISendMail($input_params['email'], $row['det_email_subject'], $row['det_email_message'], $row['det_from_email'], $row['det_from_name']);
                    break;
            }
        }

        return $return_arr;
    }

    public function get_distributor_settings($distributor_id = 0)
    {
        $this->CI->db->select('*');
        $this->CI->db->where('iUserId', $distributor_id);
        $result = $this->CI->db->get('user_distributor_setting')->result_array();
        return $result[0];
    }

    public function get_distributor_terms_master($distributor_id = 0)
    {
        $this->CI->db->select('*');
        $this->CI->db->where('iDistributorUserId', $distributor_id);
        $this->CI->db->order_by('iSeqNo', 'ASC');
        $result = $this->CI->db->get('distributor_terms_master')->result_array();
        return $result[0];
    }

    public function getEditButtonForQuotation($value = '', $id = '', $data = array())
    {
        $quotation_id = $data['iLeadMasterId'];
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a title='Duplicar' class='btn btn_info custom-view-btn edit_btn requote_btn' data-qid='".$quotation_id."' data-old_uniq_id='".$data['lm_lead_unique_id']."' href='javascript://' data-href='".$this->CI->config->item('admin_url')."#quotation/quotation/add|mode|Update|id|".$quotation_id."|tab|1|requote|true'><i class='icomoon-icon-copy'></i></a>";
        if ($data['lm_lead_stage_id'] != 'Draft')
        {
            $button .= "<a title='Editar' class='btn btn_info custom-view-btn edit_btn' href='".$this->CI->config->item('admin_url')."#quotation/quotation/add|Mode|Update|id|".$quotation_id."|tab|1'><i class='minia-icon-pencil-2'></i></a>";
            $button .= "<a title='Enviar por Email' class='btn btn_info custom-view-btn edit_btn btn_send_mail' id='btn_send_mail' data-href='".$this->CI->config->item('admin_url')."#quotation/quotation/send_email_to_all/".$this->CI->session->userdata('iAdminId')."/".$quotation_id."/Direct' data-id='".$quotation_id."' data-uid='".$this->CI->session->userdata('iAdminId')."'><i class='fa fa-send'></i></a>";
        }
        else
        {
            $button .= "<a title='Editar' class='btn btn_info custom-view-btn edit_btn' href='".$this->CI->config->item('admin_url')."#quotation/quotation/add|Mode|Update|id|".$quotation_id."|tab|1'><i class='minia-icon-pencil-2'></i></a>";
        }
        $button .= "</div>";

        return $button;
    }

    public function getLeadActionButtons($value = '', $id = '', $data = array())
    {
        $lead_id = $data['iLeadContactsId'];
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a title='".$this->CI->lang->line('LBL_LEADS_BTN_ADD_OPPRTUNITY')."' class='btn btn_info custom-view-btn edit_btn' href='".$this->CI->config->item('admin_url')."#custom_quotation/Custom_quotation/edit_quotation_form|mode|Edit|id|".$lead_id."'><i class='icomoon-icon-box-add'></i></a>";
        $button .= "<a title='".$this->CI->lang->line('LBL_LEADS_BTN_ADD_TO_ORDER')."' class='btn btn_info custom-view-btn edit_btn' href='".$this->CI->config->item('admin_url')."#custom_quotation/Custom_quotation/edit_quotation_form|mode|Edit|id|".$lead_id."'><i class='icomoon-icon-cart-add'></i></a>";
        $button .= "</div>";

        return $button;
    }

    public function addLeadBtn($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $button .= '<a class="btn btn_info custom-view-btn view_btn" title="Leads" href="'.$this->CI->config->item('admin_url').'#crm/lead_inquiry/index|lc_parent_id|'.$id.'|"><i class="fa fa-newspaper-o"></i></a>';
        $button .= "</div>";
        return $button;
    }

    public function generateLeadId($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        //
        $length = 10;
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength-1)];
        }
        $unique_id = "LEAD".date("Ym")."_".$randomString;
        return $unique_id;
    }

    public function getLoginBtn($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns' >";
        $button .= '<a class="btn btn_info custom-view-btn view_btn tooltip-hd" rel="tooltip" title="'.$this->CI->lang->line("LBL_DISTRIBUTOR_BTN_SWITCH_ACC").'" data-original-title="'.$this->CI->lang->line("LBL_DISTRIBUTOR_BTN_SWITCH_ACC").'" href="'.$this->CI->config->item('admin_url').'user/login/switch_account?sales_person='.$id.'"><i class="fa fa-toggle-on"></i>  '.$this->CI->lang->line("LBL_DISTRIBUTOR_BTN_SWITCH_ACC").'</a>';
        $button .= "</div>";
        return $button;
    }

    public function get_sitetype_masterdata($value = '', $data_arr = array())
    {
        if ($value == '')
        {
            return '';
        }
        $this->CI->db->SELECT('GROUP_CONCAT(vTitle SEPARATOR ", ") as title');
        $this->CI->db->where_in('iSiteTypeMasterId', explode(',', $value));
        $result = $this->CI->db->get('site_type_master')->first_row();
        return $result->title;
    }

    public function getNameOfCompany($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $lead_company_id = $data['tm_lead_company_id'];
        $this->CI->db->select('lc.vCompanyName');
        $this->CI->db->from('lead_company as lc');
        $this->CI->db->where('iLeadCompanyId', $lead_company_id);
        $company_name = $this->CI->db->get()->row_array();
        return $company_name['vCompanyName'];
    }

    public function getCustomTagForLead()
    {
        $post_arr = $this->CI->input->get();
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $this->CI->db->select('dfm.iDistributorFieldMasterId');
        $this->CI->db->select('dfm.vFieldName');
        $this->CI->db->select('dfm.eFieldType');
        $this->CI->db->select('dfm.vOption1,dfm.vOption2,dfm.vOption3,dfm.vOption4,dfm.vOption5');
        if ($post_arr['mode'] == 'Update')
        {
            $this->CI->db->select('lcc.vValues as selectedvalue');
        }
        else
        {
            $this->CI->db->select(" '' as selectedvalue");
        }

        $this->CI->db->from('distributor_field_master as dfm');
        if ($post_arr['mode'] == 'Update')
        {
            $this->CI->db->join('lead_contacts_custom_input as lcc', 'lcc.iCustomeTagId=dfm.iDistributorFieldMasterId AND lcc.iLeadContactsId="'.$post_arr['id'].'"', 'LEFT');
        }
        $this->CI->db->where('dfm.iDistributorUserId', $distributor_id);
        $this->CI->db->where('dfm.eStatus', 'Active');
        $custom_tags = $this->CI->db->get()->result_array();
        return $custom_tags;
    }

    public function saveCustomTagData($mode = '', $id = '', $parID = '')
    {
        $post_arr = $this->CI->input->post();
        $custom_tags = json_decode(base64_decode($post_arr['custom_tags']));
        foreach ($custom_tags as $items)
        {

            $custom_tag_data = array();
            $custom_tag_data['iLeadContactsId'] = $id;
            $custom_tag_data['iCustomeTagId'] = $items->iDistributorFieldMasterId;
            $custom_tag_data['vName'] = $items->vFieldName;
            $custom_tag_data['vValues'] = $post_arr['lc_custom_field_'.$items->iDistributorFieldMasterId];
            $this->CI->db->select('lcci.iLeadContactsCustomInputId');
            $this->CI->db->from('lead_contacts_custom_input as lcci');
            $this->CI->db->where('lcci.iLeadContactsId', $id);
            $this->CI->db->where('lcci.iCustomeTagId', $items->iDistributorFieldMasterId);
            $lead_custom_input_data = $this->CI->db->get()->result_array();
            $ret_arr = array();
            if (count($lead_custom_input_data) > 0)
            {
                $this->CI->db->where('iLeadContactsId', $post_arr['id']);
                $this->CI->db->where('iCustomeTagId', $items->iDistributorFieldMasterId);
                $this->CI->db->update('lead_contacts_custom_input', $custom_tag_data);
                $ret_arr['success'] = true;
                $ret_arr['message'] = "Lead updated successfully";
            }
            else
            {
                $ret_arr['success'] = true;
                $ret_arr['message'] = "Lead added successfully";
                $this->CI->db->insert('lead_contacts_custom_input', $custom_tag_data);
            }

            return $ret_arr;
        }
    }

    public function copyImagewithFolder($input_params = array())
    {
        $dist_id = $input_params['distributor_id'];
        $admin_banner_path = $this->CI->config->item('upload_path').'banner_image/0/';
        $banner_path = $this->CI->config->item('upload_path').'banner_image/'.$dist_id.'/';
        $banner_name = array();
        foreach ($input_params['getadminbanners'] as $key => $value)
        {
            if (!file_exists($banner_path))
            {
                mkdir($banner_path);
            }
            //$imagePath = $admin_banner_path.$value['dbm_image'];
            $dest_path = $banner_path.$value['dbm_image'];
            /*if (file_exists($imagePath))
            {
                $copied = copy($imagePath, $dest_path);
            }*/
        }
        $ret_arr = array();
        $ret_arr['success'] = true;
        $ret_arr['message'] = "Data added successfully";
        return $ret_arr;
    }

    public function generateQuotationUniqueId($value = '', $data_arr = array())
    {
        $length = 10;
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength-1)];
        }
        $unique_id = "QUOTE".date("Ym")."_".$randomString;
        $ret_arr = array();
        $ret_arr['unique_id'] = $unique_id;

        return $unique_id;
    }

    public function get_provider_default_policies($input_params = array())
    {
        $this->generateProviderPin("Update", $input_params['provider_id'], 0);

        $return_arr[] = array(
            "name" => "Precios",
            "description" => "Todos los precios son mas IVA. Precios sujetos a cambio sin previo aviso",
        );
        $return_arr[] = array(
            "name" => "Entregas",
            "description" => "Entregas en nuestro domicilio. Preguntar por envíos a domicilio",
        );
        $return_arr[] = array(
            "name" => "Garantías",
            "description" => "",
        );
        $return_arr[] = array(
            "name" => "Otros",
            "description" => "",
        );

        return $return_arr;
    }

    public function getMultipleProviderType($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        return $data['ups_type_of_provider_id'];
    }

    public function insertMultipleProviderType($mode = '', $id = '', $parID = '')
    {
        //

    }

    public function json_decode_custom($value = '', $data_arr = array())
    {
        return json_decode($value);
    }

    public function convertTitleToUrl($mode = '', $id = '', $parID = '')
    {
        $post_arr = $this->CI->input->post();
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $title = $post_arr['dpm_page_title'];
        $title = strtolower($title);
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $title);
        $string = preg_replace("/[\s-]+/", " ", $string);
        $string = preg_replace("/[\s_]/", "-", $string);
        $url = $string.".html";
        $data = array(
            "vUrl" => $url,
        );
        $where = array(
            'iDistributorPageMasterId' => $id,
            "iDistributorUserId" => $distributor_id,
        );
        $this->CI->db->where($where);
        $this->CI->db->update("distributor_page_master", $data);
        $ret_arr = array();
        $ret_arr['success'] = true;
        $ret_arr['message'] = "Page created successfully";
        return $ret_arr;
    }

    public function saveShowrooms($mode = '', $id = '', $parID = '')
    {
        $post_arr = $this->CI->input->post();
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $selected_categories = $post_arr['dppt_category_id'];
        $new_arr = array();
        $error_count = 0;
        $has_error = true;
        $message = "No podemos encontrar productos para la categoría seleccionada.";
        if (is_array($selected_categories) && count($selected_categories) > 0)
        {

            foreach ($selected_categories as $key => $val)
            {

                $this->CI->db->select('dppt.iCategoryId as cid');
                $this->CI->db->from('distributor_popular_product_trans as dppt');
                $this->CI->db->where('dppt.iCategoryId', $val);
                $this->CI->db->where('dppt.iDistributorUserId', $distributor_id);
                $this->CI->db->where('dppt.iTypeFor', '2');
                $existing_showroom = $this->CI->db->get()->row_array();
                if (!empty($existing_showroom))
                {
                    $has_error = true;
                    $message = "Showroom ya existe";
                }
                else
                {
                    $this->CI->db->select('pm.iProductMasterId as pid');
                    $this->CI->db->from('product_master as pm');
                    $this->CI->db->where('pm.iCategoryId', $val);
                    $this->CI->db->or_where('pm.iSubCategoryId', $val);
                    $this->CI->db->where('pm.eStatus', 'Active');
                    $this->CI->db->limit(1);
                    $this->CI->db->order_by("rand()");

                    $new_arr = $this->CI->db->get()->row_array();
                    if (!count($new_arr))
                    {

                        $this->CI->db->select('cpm.iProductId as pid');
                        $this->CI->db->from('custom_category_product_mapping as cpm');
                        $this->CI->db->where('cpm.iCategoryMasterId', $val);
                        $this->CI->db->where('cpm.iDistributorUserId', $this->CI->session->userdata('iAdminId'));
                        $this->CI->db->where('cpm.eStatus', 'Active');
                        $this->CI->db->order_by("rand()");
                        $this->CI->db->limit(1);
                        $new_arr = $this->CI->db->get()->row_array();
                    }
                    if (count($new_arr) > 0)
                    {
                        $has_error = false;
                        $mydata = array(
                            'iProductId' => $new_arr['pid'],
                            'iCategoryId' => $val,
                            'iDistributorUserId' => $this->CI->session->userdata('iAdminId'),
                            'iTypeFor' => $post_arr['dppt_type_for'],
                            'dAddedDate' => date('Y-m-d H:i:s'),
                        );
                        $inserted_data = $this->CI->db->insert('distributor_popular_product_trans', $mydata);
                        $message = "Showroom agregado con éxito";
                    }
                    else
                    {
                        $error_count++;
                        $message = "Oops! no podemos encontrar productos para ".$error_count." categoría";
                    }
                }
            }
        }
        if ($has_error)
        {
            $return_arr['success'] = false;
            $return_arr['message'] = $message;
        }
        else
        {
            $return_arr['success'] = true;
            if ($error_count > 0)
            {
                $return_arr['message'] = $message;
            }
            else
            {
                $return_arr['message'] = $message;
            }
        }
        return $return_arr;
    }

    public function getGoogleUrls($provider_id = '')
    {
        $this->CI->db->select('ups.vGoogleDriveUrl,ups.vGoogleSheetUrl');
        $this->CI->db->from('user_provider_settings as ups');
        $this->CI->db->where('ups.iUserId', $provider_id);
        $ids = $this->CI->db->get()->row_array();
        $drive_url = $ids['vGoogleDriveUrl'];
        $sheet_url = $ids['vGoogleSheetUrl'];
        $ret_arr = array();
        $ret_arr['success'] = true;
        $ret_arr['drive_url'] = $drive_url;
        $ret_arr['sheet_url'] = $sheet_url;
        return $ret_arr;
    }

    public function checkForAtlstOneBlockEnable($mode = '', $id = '', $parID = '')
    {
        $ret = array();
        $ret['success'] = 1;
        if (!($_POST['hpsm_banner'] == 'Show' || $_POST['hpsm_popular_product'] == 'Show' || $_POST['hpsm_static_content'] == 'Show' || $_POST['hpsm_showroom'] == 'Show'))
        {
            $ret['success'] = 0;
            $ret['message'] = "¡Lo siento! Tiene que seleccionar al menos un elemento para mostrar.";
        }

        return $ret;
    }

    public function getCategoryRequestButtons($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $button .= '<a class="btn btn_info custom-view-btn view_btn approve_btn fancybox-popup" href="'.$this->CI->config->item("admin_url").'#misc/category_request_master/add|mode|Update|id|'.$data['iCategoryRequestMasterId'].'|hideCtrl|true" data-id="'.$data['iCategoryRequestMasterId'].'" title="Approve"><i class="icomoon-icon-checkmark-2"></i></a>';
        $button .= "</div>";
        return $button;
    }

    public function CheckExistingCategory($value = '', $mode = '', $id = '', $data = array(), $parent_src = '')
    {
        $this->CI->db->select('crm.iCategoryRequestMasterId,crm.vCategoryName');
        $this->CI->db->from('category_request_master as crm');
        if ($mode == 'Update')
        {
            $this->CI->db->where('crm.iCategoryRequestMasterId!=', $id);
        }
        $this->CI->db->where('crm.iProviderId', $this->CI->session->userdata('iAdminId'));
        $data = $this->CI->db->get()->result_array();

        $parent_category = array();
        foreach ($data as $key => $val)
        {
            $parent_category[] = array(
                "id" => $val['iCategoryRequestMasterId'],
                "val" => $val['vCategoryName'],
            );
        }
        return $parent_category;
    }

    public function getViewButtonForProvidersInquiry($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#custom_products/custom_products/provider_detail_view|id|".$data['iUsersId']."|module_type|inquiry'><i class='icon18 iconic-icon-eye'></i></a>";
        $button .= "</div>";
        return $button;
    }

    public function getViewButtonForErrorCount($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a class='btn btn_info custom-view-btn delete_btn' href='".$this->CI->config->item('admin_url')."#custom_products/error_reports/index|id|".$data['iVendorProductCsvMasterId']."'>".$data['cpm_vendor_product_csv_master_id']."</a>";
        $button .= "</div>";
        return $button;
    }

    public function getcustomcode($id)
    {
        $this->CI->db->select("vCode");
        $this->CI->db->where("iCustomCodeId", $id);
        $data = $this->CI->db->get("custom_code")->first_row();
        if (isset($data->vCode))
        {
            return $data->vCode;
        }
        else
        {
            return $id;
        }
    }

    public function get_product_error_button()
    {
        $provider_id = $this->CI->session->userdata('iAdminId');
        $this->CI->db->select("COUNT('cpm.iProductMasterId') as error_count");
        $this->CI->db->from('copy_product_master as cpm');
        $this->CI->db->where('cpm.iVendorProductCsvMasterId=(select MAX(csv.iVendorProductCsvMasterId)  from vendor_product_csv_master as csv WHERE csv.iVendorId ='.$provider_id.' )');
        $error_count = $this->CI->db->get()->first_row();
        if ($error_count->error_count > 0)
        {
            return '<a href="'.$this->CI->config->item('admin_url').'#custom_products/error_reports" class="provider_product_error"><div> Hay algún error de datos en su catálogo de productos. Haga clic aquí para revisar errores.</div></a>';
        }
        else
        {
            return "";
        }
    }

    public function product_min_max_price($mode = '', $id = '', $parID = '')
    {
        return true;
    }

    public function get_error_product_image($row)
    {
        $imgname = $row['cpm_image'];
        $image_url = $this->getNoImageURL();
        $imgpath = $this->CI->config->item('upload_path').'product_images/'.$imgname;
        if ($imgname != '' && file_exists($imgpath))
        {
            $image_url = $this->CI->config->item('upload_url').'product_images/'.$imgname;
        }
        $param = array();
        $param['image'] = $image_url;
        $param['button_text'] = '<i class="fa fa-upload"></i>';
        $param['pid'] = $row['cpm_product_master_id'];
        return $this->form_bs_fileinput('vProductImage', $param);
    }

    public function form_bs_fileinput($name = '', $param = array())
    {
        $html = '';
        $html .= '<div class="fileinput fileinput-new clearfix" data-provides="fileinput" style="width:90px;" >
                <div class="pull-left fileinput-new thumbnail">';
        if ($param['image'] == '')
        {
            $html .= '<img   src="http://www.placehold.it/50x50/EFEFEF/AAAAAA&amp;text=no+image" alt="">';
        }
        else
        {
            $html .= '<img  style="width: 100px;" src="'.$param['image'].'?p='.time().'" alt="">';
        }
        $html .= '</div>
                <div class="pull-left fileinput-preview fileinput-exists thumbnail">
                </div>
                <div class="pull-left erruploadbutton" >
                <span class="btn btn-success btn-file btn-xs">
                <span class="fileinput-new">'.$param['button_text'].'</span><span class="fileinput-exists"><i class="fa fa-upload"></i></span>
                <input type="file" class="productimg" data-pid="'.$param['pid'].'" accept="jpg|png" id="fileInput_'.$param['pid'].'" name="'.$name.'"></span></div></div>';
        return $html;
    }

    public function makeRandomBanners()
    {
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $this->CI->db->select("iDistributorBannerMasterId");
        $this->CI->db->where('iDistributorUserId', $distributor_id);
        $this->CI->db->order_by('rand()');
        $result = $this->CI->db->get('distributor_banner_master')->result_array();
        array_rand($result);
        foreach ($result as $seq => $list)
        {
            if ($list['iDistributorBannerMasterId'] > 0)
            {
                $this->CI->db->where('iDistributorBannerMasterId', $list['iDistributorBannerMasterId']);
                $this->CI->db->update('distributor_banner_master', array('iSeqNo' => $seq));
            }
        }
        return true;
    }

    public function saveCategoryCSV($input_params = array())
    {
        $list = $input_params['sub_categories'];
        if (count($list) > 0)
        {
            $file = fopen("public/upload/844e3d4deda2dc47b85e48686b422f2f.csv", "w");
            foreach ($list as $key => $line)
            {
                if ($key == 0)
                {
                    fputcsv($file, array("Category Name", "SubCategory Name"));
                }

                fputcsv($file, $line);
            }

            fclose($file);
        }
    }

    public function getDistributorGoogleUrls($distributor_id = '')
    {
        $this->CI->db->select('uds.vGoogleDriveUrl,uds.vGoogleSheetUrl');
        $this->CI->db->from('distributor_import_request_master as uds');
        $this->CI->db->where('uds.iDistributorUserId', $distributor_id);
        $ids = $this->CI->db->get()->row_array();
        $drive_url = $ids['vGoogleDriveUrl'];
        $sheet_url = $ids['vGoogleSheetUrl'];
        $ret_arr = array();
        $ret_arr['success'] = true;
        $ret_arr['drive_url'] = $drive_url;
        $ret_arr['sheet_url'] = $sheet_url;
        return $ret_arr;
    }

    public function getApproveBtnForImportRequest($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $button .= '<a class="btn btn_info custom-view-btn view_btn approve_btn fancybox-popup" href="'.$this->CI->config->item("admin_url").'#product/import_url_request_master/add|mode|Update|id|'.$data['iDistributorImportRequestMasterId'].'|hideCtrl|true" data-id="'.$data['iCategoryRequestMasterId'].'" title="Approve"><i class="icomoon-icon-checkmark-2"></i></a>';
        $button .= "</div>";
        return $button;
    }

    public function removeLeadProducts($mode = '', $id = '', $parID = '')
    {
        foreach ($id as $key => $pid)
        {
            $where = array(
                'iLeadMasterId' => $pid,
            );
            $this->CI->db->where($where);
            $this->CI->db->delete('lead_product_items');
        }
        $return_arr = array();
        $return_arr['success'] = true;
        $return_arr['message'] = "Products deleted successfully";
        return $return_arr;
    }

    public function product_image()
    {
        return true;
    }

    public function get_product_image_preview($data = array())
    {
        $html = '<div class="product_preview">';
        $path = $this->CI->config->item('upload_path')."product_images/";
        $url = $this->CI->config->item('upload_url')."product_images/";
        if ($data['pm_image_type'] == 'Online')
        {
            if ($data['pm_image_url'] != '')
            {
                $html .= "<img class='product_preview_image' id='product_preview_image' src='".$data['pm_image_url']."' />";
            }
            else
            {
                $html .= "<img class='product_preview_image' id='product_preview_image' src='".$this->CI->config->item('images_url')."noimage.gif' />";
            }
        }
        else
        if ($data['pm_image_type'] == 'Local')
        {
            if ($data['pm_image'] != '' && file_exists($path.$data['pm_image']))
            {
                $html .= "<img class='product_preview_image' id='product_preview_image' src='".$url.$data['pm_image']."' />";
            }
            else
            {
                $html .= "<img class='product_preview_image' id='product_preview_image' src='".$this->CI->config->item('images_url')."noimage.gif' />";
            }
        }
        else
        {
            $html .= "<img class='product_preview_image' id='product_preview_image' src='".$this->CI->config->item('images_url')."noimage.gif' />";
        }
        $html .= "</div>";

        return $html;
    }

    public function getCategoryNameFromId($id = 0)
    {
        $this->CI->db->select('cm.vCategoryName');
        $this->CI->db->from('category_master as cm');
        $this->CI->db->where('cm.iCategoryMasterId', $id);
        $data = $this->CI->db->get()->row_array();
        return $data['vCategoryName'];
    }

    public function get_single_product_images($product_code = '', $provider_id = 0)
    {
        $data = array();
        if ($product_code != '')
        {
            $this->CI->db->select('pm.eImageType, pm.vImageUrl as image_online,
                                  pm.vImage as image_name,
                                  pm.iProductMasterId as product_id,
                                  cm.vColorName');
            $this->CI->db->from('product_master as pm');
            $this->CI->db->join('color_master as cm', "pm.iColorId=cm.iColorMasterId",'left');
            $this->CI->db->where('pm.vProductParentCode', $product_code);
            $this->CI->db->where('pm.iProviderId', $provider_id);
            $this->CI->db->where('pm.eStatus', "Active");
            $data = $this->CI->db->get()->result_array();
        }
        return $data;
    }

    public function generate_pdf_portions($inquiry_id = 0, $distributor_id = 0, $type = '', $user_type = '', $module = '')
    {
        $params = array();
        $params['product_inquiry_id'] = $inquiry_id;
        $params['distributor_id'] = $distributor_id;
        $lead_data = $this->CI->cit_api_model->callAPI("get_product_inquiry_data", $params);
        $settings_data = $this->get_distributor_settings($distributor_id);
        $sess_data = $this->CI->session->all_userdata();
        /*echo "<pre>";
    print_r($params);
    die;*/
        $is_hide = $settings_data['vDistibutorProfitPreference'];
        if ($is_hide == 'Hide' && $user_type == 'Customer')
        {
            $hide = 'Yes';
        }
        else
        {
            $hide = 'No';
        }
        $hide = 'No';
        $params = array();
        $params['distributor_id'] = $distributor_id;
        $dist_company_details = $this->CI->cit_api_model->callAPI('distributor_details', $params);
        $sub_total = $lead_data['data']['getleaddata'][0]['lm_sub_total_price'];
        $vat_applied = $this->CI->config->item('ECOM_VAT_APPLIED');
        $vat_applied_amt = ($sub_total*$vat_applied)/100;
        $tot_price = $sub_total+$vat_applied_amt;

        $this->CI->smarty->assign("sub_total", $sub_total);
        $this->CI->smarty->assign("vat_applied_amt", $vat_applied_amt);
        $this->CI->smarty->assign("tot_price", $tot_price);
        $this->CI->smarty->assign("is_hide", $hide);
        $this->CI->smarty->assign("file_type", $file_type);

        $this->CI->smarty->assign("lead_data", $lead_data['data']['getleaddata'][0]);
        $this->CI->smarty->assign("settings_data", $settings_data);
        $this->CI->smarty->assign("sess_data", $sess_data);
        $this->CI->smarty->assign("terms_data", $terms_arr);
        $this->CI->smarty->assign("product_inquiry_id", $product_inquiry_id);
        $this->CI->smarty->assign("dist_company_details", $dist_company_details['data'][0]);
        $this->CI->smarty->assign("lead_products", $lead_data['data']['getleadproducts']);
        $this->CI->smarty->assign("type", $type);
        $this->CI->smarty->assign("module", $module);
        $this->CI->smarty->assign("lead_product_printing_data", $lead_data['data']['getleadproductprintingdata']);
        $this->CI->load->library('parser');
        $this->CI->smarty->addTemplateDir(APPPATH.'distributor/crm/views/');
        $this->CI->parser->addTemplateLocation(APPPATH.'distributor/crm/views/');

        $header = $this->CI->parser->parse('pdf_quotation_header', array(), true);
        $footer = $this->CI->parser->parse('pdf_quotation_footer', array(), true);
        $top = $this->CI->parser->parse('pdf_quotation_topdata', array(), true);
        $middle = $this->CI->parser->parse('pdf_quotation_middledata', array(), true);
        $bottom = $this->CI->parser->parse('pdf_quotation_bottomdata', array(), true);
        if ($module == 'crm')
        {
            if ($type == 'pdf')
            {
                $this->downloadPdf($header, $footer, $top, $middle, $bottom, $lead_data['data']['getleaddata'][0]['lm_lead_unique_id']);
            }
        }
        else
        if ($module == 'quotation')
        {
            if ($type == 'pdf')
            {
                $ret_arr = $this->savePdf($header, $footer, $top, $middle, $bottom, $inquiry_id, $user_type);
                return $ret_arr;
            }
        }
    }

    public function downloadPdf($header = '', $footer = '', $top = '', $middle = '', $bottom = '', $uniq_id = '')
    {
        $this->CI->load->library('m_pdf');
        $mpdf = new mPDF('c');
        $mpdf->SetHeader($header);
        $mpdf->setFooter($footer);
        $mpdf->WriteHTML($top);
        //$mpdf->AddPage();
        $mpdf->WriteHTML($middle);
        //$mpdf->AddPage();
        $mpdf->WriteHTML($bottom);
        $mpdf->Output("quotation-".$uniq_id.".pdf", 'D');
        unset($mpdf);
        exit;
    }

    public function savePdf($header = '', $footer = '', $top = '', $middle = '', $bottom = '', $lead_id = '', $u_type = '')
    {
        $this->CI->load->library('m_pdf');
        $dir_path = $this->CI->config->item('upload_path').'pdf_quotation/';
        if (!file_exists($dir_path))
        {
            $dir = mkdir($dir_path);
            chmod($dir, 0777);
        }
        if ($lead_id != '')
        {
            $dir_path = $this->CI->config->item('upload_path').'pdf_quotation/'.$lead_id;
            if (!file_exists($dir_path))
            {
                $dir = mkdir($dir_path);
            }
        }
        $live_package_code = $this->CI->session->userdata('live_subscription_detail')[0]['vPackageCode'] ? $this->CI->session->userdata('live_subscription_detail')[0]['vPackageCode'] : ecom_cnf('vPackageCode');
        $mpdf = new mPDF('c');
        $watermark_img = $this->CI->config->item('images_url')."watermark.png";
        if (strtolower($live_package_code) == 'free')
        {
            $mpdf->SetWatermarkImage($watermark_img);
            $mpdf->showWatermarkImage = true;
        }
        $mpdf->autoPageBreak = true;
        //$mpdf->SetColumns(5);
        //$mpdf->shrink_tables_to_fit = 1;
        //$mpdf->AddPage('P');
        $mpdf->SetHeader($header);        
        $mpdf->setFooter($footer);
        //$mpdf->AddPage('P');
        $mpdf->WriteHTML($top);
        //$mpdf->AddPage('P');
        $mpdf->WriteHTML($middle);
        //$mpdf->AddPage('P');
        $mpdf->WriteHTML($bottom);
        $mpdf->Output($dir_path.'/'."quotation-".$u_type."-".$lead_id.".pdf", 'F');
        unset($mpdf);
        // this is the magic
        unset($tpl_file);
        // this is the magic
        $ret_arr = array();
        $ret_arr['success'] = 'true';
        $ret_arr['message'] = "Pdf saved successfully";
        $ret_arr['user_type'] = $u_type;
        $ret_arr['pdf_path'] = $dir_path.'/'."quotation-".$u_type."-".$lead_id.".pdf";
        return $ret_arr;
    }

    public function getQuotationSeqNo($value = '', $id = '', $data = array())
    {
        //

    }

    public function createQuotationUniqueId($data = array(), $id = 0, $mode = 'Add', $q_type = '', $update = false)
    {
        $number = 1;
        $customer_name = $data['vCustomerName'] ? $data['vCustomerName'] : 'CUST';
        $company_name = $data['vCompanyName'] ? $data['vCompanyName'] : 'COMP';
        $sales_person_name = $data['vAssignedSalesPersonName'] ? $data['vAssignedSalesPersonName'] : 'SALS';

        $sales_person_name = strtoupper(substr($sales_person_name, 0, 2));
        $customer_name = strtoupper(substr($customer_name, 0, 2));
        $company_name = strtoupper(substr($company_name, 0, 2));

        $this->CI->db->select("vLeadUniqueId");
        $this->CI->db->from("lead_master");
        $this->CI->db->where_in("vLeadStageId", array('LEAD_QUOTATION', 'DRAFT'));
        $this->CI->db->where("iDistributorUserId", $this->CI->session->userdata('iAdminId'));
        $this->CI->db->where("MONTH(dAddedDate) = MONTH(NOW())");
        $this->CI->db->where("YEAR(dAddedDate) = YEAR(NOW())");
        $this->CI->db->where("iLeadMasterId!=", $id);
        $this->CI->db->where("vLeadUniqueId!=", "");
        $this->CI->db->order_by('iLeadMasterId', 'DESC');
        $this->CI->db->limit(1);
        $lead_data = $this->CI->db->get()->first_row();
        if (count($lead_data) > 0)
        {
            $lastqnum = $lead_data->vLeadUniqueId;
            $lastqnum = explode("/", $lastqnum);
            $number = intval(array_pop($lastqnum))+1;
        }

        $number = str_pad($number, 4, "0", STR_PAD_LEFT);
        if ($mode == 'Update')
        {
            if ($q_type == 'DRAFT')
            {
                $lead_uniq_id = $data['vLeadUniqueId'];
                $lead_arr = explode('/', $lead_uniq_id);
                $lead_arr_new[0] = $company_name;
                $lead_arr_new[1] = $sales_person_name;
                $lead_arr_new[2] = $lead_arr[2];
                $unique_id = implode('/', $lead_arr_new);
            }
            else
            {
                $lead_uniq_id = $data['vLeadUniqueId'];
                $lead_arr = explode('/', $lead_uniq_id);
                $lead_arr_new[0] = $company_name;
                $lead_arr_new[1] = $sales_person_name;
                $lead_arr_new[2] = $lead_arr[2];
                $unique_id = implode('/', $lead_arr_new);
            }
            if ($update)
            {
                if ($id > 0)
                {
                    $this->CI->db->where('iLeadMasterId', $id);
                    $this->CI->db->update('lead_master', array('vLeadUniqueId' => $unique_id));
                }
            }
        }
        else
        {
            $unique_id = $company_name."/".$sales_person_name."/".$number;
            if ($update)
            {
                if ($id > 0)
                {
                    $this->CI->db->where('iLeadMasterId', $id);
                    $this->CI->db->update('lead_master', array('vLeadUniqueId' => $unique_id));
                }
            }
        }
        $ret_arr = array();
        $ret_arr['unique_id'] = $unique_id;
        return $ret_arr;
    }

    public function customSeqNumber($value = '', $id = '', $data = array(), $index = 0)
    {
        $page = $this->CI->input->get_post('page') == 0 ? 1 : $this->CI->input->get_post('page');
        $rows = $this->CI->input->get_post('rows');
        return ($index+1)+(($page-1)*$rows);
    }

    public function getShowHideButton($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $hide_status = "";
        if ($data['phfd_provider_hide_for_distributor_id'] > 0)
        {
            $hide_status = "hide-inactive";
        }
        else
        {
            $hide_status = "hide-active";
        }
        if ($hide_status == 'hide-active')
        {
            $icon_class = "fa fa-eye";
            $icon_title = $this->CI->lang->line("ICON_EXCLUDE_PRODUCTS");
        }
        else
        {
            $icon_class = "fa fa-eye-slash";
            $icon_title = $this->CI->lang->line("ICON_INCLUDE_PRODUCTS");
        }
        $button .= "<a style='margin-left:30px;' id='hide-unhide-".$data['u_users_id']."' hijacked='yes' class='btn btn_info custom-view-btn btn_hide_toggle ".$hide_status."' href='javascript://'   data-toggle='yes' data-id='".$data['u_users_id']."' title='".$icon_title."'><i class='".$icon_class."'></i></a>";
        $button .= "</div>";
        return $button;
    }

    public function getSeqNoInput($value = '', $id = '', $data = array())
    {
        $html = "<div class='seq_no_input_container'>";
        $html .= "<input type='number' min='0' name='dppt_seq_no_input' id='dppt_seq_no_input-".$id."' class='input_dppt_seq_no' value='".$value."' style='width:40%;' /> <a href='javascript://' name='btn_save_seq_no' id='btn_save_seq_no' class='btn btn_info custom-view-btn save_seq_no' data-seqno='".$value."' data-transid='".$data['dppt_distributor_popular_product_trans_id']."'><i class='fa fa-check'></i></a>";
        $html .= "</div>";
        return $html;
    }

    public function getPopularProductButtons($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $hide_status = "";
        if ($data['dppt_hide'] == 'No')
        {
            $hide_status = "hide-active";
        }
        else
        {
            $hide_status = "hide-inactive";
        }
        if ($hide_status == 'hide-active')
        {
            $icon_class = "fa fa-eye";
            $icon_title = $this->CI->lang->line("HIDE_POPULAR_PRODUCT");
        }
        else
        {
            $icon_class = "fa fa-eye-slash";
            $icon_title = $this->CI->lang->line("UNHIDE_POPULAR_PRODUCT");
        }
        $button .= "<a style='margin-left:30px;' id='hide-unhide-".$data['dppt_distributor_popular_product_trans_id']."' hijacked='yes' class='btn btn_info custom-view-btn btn_hide_popular_products ".$hide_status."' href='javascript://' data-hide='".$data['dppt_hide']."' data-id='".$data['dppt_distributor_popular_product_trans_id']."' title='".$icon_title."'><i class='".$icon_class."'></i></a>";
        $button .= "</div>";
        return $button;
    }

    public function get_product_multi_image_preview($data = array())
    {
        $html = '<div class="multi_color_product_preview">';
        $path = $this->CI->config->item('upload_path')."product_images/";
        $url = $this->CI->config->item('upload_url')."product_images/";
        if ($data['pm_image_type'] == 'Local')
        {
            if ($data['pm_multi_image_name'] != '' && file_exists($path.$data['pm_multi_image_name']))
            {
                $html .= "<img class='multi_color_product_preview_image' id='multi_color_product_preview_image' src='".$url.$data['pm_multi_image_name']."' />";
            }
            else
            {
                $html .= "<img class='multi_color_product_preview_image' id='multi_color_product_preview_image' src='".$this->CI->config->item('images_url')."noimage.gif' />";
            }
        }
        else
        {
            if ($data['pm_multi_image_name'] != '' && file_exists($path.$data['pm_multi_image_name']))
            {
                $html .= "<img class='multi_color_product_preview_image' id='multi_color_product_preview_image' src='".$url.$data['pm_multi_image_name']."' />";
            }
            else
            {
                $html .= "<img class='multi_color_product_preview_image' id='multi_color_product_preview_image' src='".$this->CI->config->item('images_url')."noimage.gif' />";
            }
        }
        $html .= "</div>";

        return $html;
    }

    public function copyMultiImageForSameProducts($mode = '', $id = '', $parID = '')
    {
        $post_arr = $this->CI->input->post();
        $ret_arr = array();
        $ret_arr['success'] = true;
        $ret_arr['message'] = "Product updated successfully";
        if ($post_arr['pm_multi_image_name'] != '')
        {
            $this->CI->db->select('iProductMasterId as product_id');
            $this->CI->db->from('product_master');
            $this->CI->db->where('iProviderId', $this->CI->session->userdata('iAdminId'));
            $this->CI->db->where('lower(vProductParentCode)', strtolower($post_arr['pm_product_parent_code']));
            $this->CI->db->where('eStatus', "Active");
            $data = $this->CI->db->get()->result_array();
            if (!empty($data) && count($data) > 0)
            {
                $product_ids = array();
                foreach ($data as $key => $value)
                {
                    $product_ids[] = $value['product_id'];
                }
            }
            if (!empty($product_ids) && count($product_ids) > 0)
            {
                $update_data = array(
                    "vMultiImageName" => $post_arr['pm_multi_image_name'],
                );
                $this->CI->db->where_in('iProductMasterId', $product_ids);
                $this->CI->db->update("product_master", $update_data);
                $ret_arr['stats'] = 0;
            }
            $ret_arr['stats'] = -1;
        }
        $ret_arr['stats'] = -2;

        $this->CI->db->select('cm.iParentId');
        $this->CI->db->from('category_master as cm');
        $this->CI->db->where('cm.iCategoryMasterId', $post_arr['pm_sub_category_id']);
        $parent_cat_data = $this->CI->db->get()->row_array();
        if ($parent_cat_data['iParentId'] > 0)
        {
            $update_arr = array(
                'iCategoryId' => $parent_cat_data['iParentId'],
            );
        }
        else
        {
            $update_arr = array(
                'iCategoryId' => $post_arr['pm_sub_category_id'],
            );
        }
        $where = array(
            'iSubCategoryId' => $post_arr['pm_sub_category_id'],
        );
        $this->CI->db->where($where);
        $this->CI->db->update('product_master', $update_arr);
        return $ret_arr;
    }

    public function getDefaultdeliverytime($value = '', $data_arr = array())
    {
        $dist_id = $this->CI->session->userdata('iAdminId');
        $this->CI->db->select('uds.vDeliveryTime');
        $this->CI->db->from('user_distributor_setting as uds');
        $this->CI->db->where('uds.iUserId',$dist_id);
        $uds_data = $this->CI->db->get()->row_array();
        if(!empty($uds_data)){
            return $uds_data['vDeliveryTime'];
        } else {
            return $this->CI->config->item('DELIVERY_TIME');
        }
    }

    public function getDefaultPaymentConditions($value = '', $data_arr = array())
    {
        $dist_id = $this->CI->session->userdata('iAdminId');
        $this->CI->db->select('uds.tPaymentConditions');
        $this->CI->db->from('user_distributor_setting as uds');
        $this->CI->db->where('uds.iUserId',$dist_id);
        $uds_data = $this->CI->db->get()->row_array();
        if(!empty($uds_data)){
            return $uds_data['tPaymentConditions'];
        } else {
            return $this->CI->config->item('PAYMENT_CONDITIONS');
        }
    }

    public function getDefaultTerms($value = '', $data_arr = array())
    {
        $dist_id = $this->CI->session->userdata('iAdminId');
        $this->CI->db->select('uds.tProductPolicies');
        $this->CI->db->from('user_distributor_setting as uds');
        $this->CI->db->where('uds.iUserId',$dist_id);
        $uds_data = $this->CI->db->get()->row_array();
        if(!empty($uds_data)){
            return $uds_data['tProductPolicies'];
        } else {
            return $this->CI->config->item('PRODUCT_POLICIES');
        }
    }

    public function getQuotationId($input_params = '', $index_val = '')
    {
        $quotation_id = 0;
        if ($input_params['lead_id'] > 0)
        {
            $quotation_id = $input_params['lead_id'];
        }
        else
        {
            $quotation_id = $input_params['insert_draft'][0]['insert_id1'];
        }
        return $quotation_id;
    }

    public function getQuoteImage($value = '', $data_arr = array())
    {
        if ($data_arr['pm_image_type'] == 'Online')
        {
            return $data_arr['pm_image_vurl'];
        }
        else
        {
            return $data_arr['pm_image'];
        }
    }

    public function updateQuotationUniqueId($input_params = array())
    {
        $data = array();
        $data['vCustomerName'] = $input_params['contact_name'];
        $data['vCompanyName'] = $input_params['company_name'];
        $data['vAssignedSalesPersonName'] = $input_params['sales_person'];
        $this->createQuotationUniqueId($data, $input_params['insert_draft'][0]['insert_id1'], 'Add', '', true);
    }

    public function getLastQuotation($distributor_id = 0, $salesman_id = 0)
    {
        $data = array();
        if ($distributor_id > 0 && $salesman_id > 0)
        {
            $this->CI->db->select('lm.*');
            $this->CI->db->from('lead_master as lm');
            $this->CI->db->where('lm.iAssignedSalesUserId', $salesman_id);
            $this->CI->db->where('lm.iDistributorUserId', $distributor_id);
            $this->CI->db->where('lm.dModifiedDate = (SELECT MAX(dModifiedDate) from lead_master where iAssignedSalesUserId ='.$salesman_id.' AND iDistributorUserId ='.$distributor_id.' AND vLeadStageID = "LEAD_QUOTATION" )');
            $this->CI->db->where_in('lm.vLeadStageID', array('LEAD_QUOTATION'));
            $this->CI->db->limit(1);
            $data = $this->CI->db->get()->result_array();
        }
        return $data;
    }

    public function getAllQuotations($distributor_id = 0, $salesman_id = 0)
    {
        $ret_arr = array();
        if ($distributor_id > 0 && $salesman_id > 0)
        {
            $this->CI->db->select('lm.iLeadMasterId as lead_id,lm.vLeadUniqueID as lead_unique_id,lm.vCompanyName as company_name');
            $this->CI->db->from('lead_master as lm');
            $this->CI->db->where('lm.iAssignedSalesUserId', $salesman_id);
            $this->CI->db->where('lm.iDistributorUserId', $distributor_id);
            $this->CI->db->where_in('lm.vLeadStageID', array('LEAD_QUOTATION','DRAFT'));
            $data = $this->CI->db->get()->result_array();
            if (!empty($data) && count($data) > 0)
            {
                $ret_arr['success'] = true;
                $ret_arr['data'] = $data;
            }
            else
            {
                $ret_arr['success'] = true;
                $ret_arr['data'] = array();
            }
        }
        return $ret_arr;
    }
    
    
    
    public function getCustomerContact($distributor_id = 0, $salesman_id = 0)
    {
        $ret_arr = array();
        if ($distributor_id > 0 && $salesman_id > 0)
        {
            $this->CI->db->select('dcc.*');
            $this->CI->db->from('distributor_customer_contact as dcc');
            //$this->CI->db->where('lm.iAssignedSalesUserId', $salesman_id);
            //$this->CI->db->where('dcc.iDistributorUserId', $distributor_id);
            //$this->CI->db->where_in('lm.vLeadStageID', array('LEAD_QUOTATION','DRAFT'));
            $data = $this->CI->db->get()->result_array();
            if (!empty($data) && count($data) > 0)
            {
                $ret_arr['success'] = true;
                $ret_arr['data'] = $data;
            }
            else
            {
                $ret_arr['success'] = true;
                $ret_arr['data'] = array();
            }
        }
        return $ret_arr;
    }
    
    
    

    public function calculate_total_price($value = '', $data_arr = array())
    {
        $vat_percent = $this->CI->config->item('ECOM_VAT_APPLIED');
        $sub_total = $value['sub_total'] ? $value['sub_total'] : 0.00;
        $vat_applied_amt = ($sub_total*$vat_percent)/100;
        $vat_applied_amt = $vat_applied_amt ? number_format($vat_applied_amt) : 0.00;
        $total_amount = $sub_total+$vat_applied_amt;
        $total_amount = $total_amount ? number_format($total_amount) : 0.00;
        $ret_arr = array();
        $ret_arr['vat_percent'] = $vat_percent;
        $ret_arr['vat_amount'] = $vat_applied_amt;
        $ret_arr['total_amount'] = $total_amount;
        return $ret_arr;
    }

    public function getVatPercentage($value = '', $data_arr = array())
    {
        if ($data_arr['existing_vat'] > 0)
        {
            return $data_arr['existing_vat'];
        }
        else
        {
            return $this->CI->config->item('ECOM_VAT_APPLIED');
        }
    }

    public function getVatAmount($value = '', $data_arr = array())
    {
        $sub_total = $data_arr['sub_total'];
        $vat_amount = (($sub_total*$data_arr['vat_applied'])/100);
        return $vat_amount;
    }

    public function getTotalAmount($value = '', $data_arr = array())
    {
        $total_amount = $data_arr['sub_total']+$data_arr['vat_amount'];
        return $total_amount;
    }

    public function getSubTotal($value = '', $data_arr = array())
    {
        $sub_total = $data_arr['lpi_product_qty']*$data_arr['lpi_product_price'];
        return $sub_total;
    }

    public function calculateFinalPrices($input_params = array())
    {
        $ret_arr = array();
        $my_arr = array();
        $total_products_added = $input_params['getleadproductcount'][0]['total_products_added'];
        foreach ($input_params['getproductprices'] as $key => $value)
        {
            $my_arr['final_sub_total'] += $value['sub_total'];
            $my_arr['final_vat_percent'] = $value['vat_applied'];
        }
        $vat_amount = ($my_arr['final_sub_total']*$my_arr['final_vat_percent'])/100;
        $ret_arr['final_vat_percent'] = $my_arr['final_vat_percent'];
        $ret_arr['final_sub_total'] = $my_arr['final_sub_total'];
        $ret_arr['final_vat_amount'] = $vat_amount;
        $ret_arr['final_total_amount'] = $my_arr['final_sub_total']+$vat_amount;
        $ret_arr['final_total_products_added'] = $total_products_added;
        return $ret_arr;
    }

    public function updateQuantityForSameProduct($value = '', $data_arr = array())
    {
        $new_qty = $value+$data_arr['pps_max_qunatity_1'];
        return $new_qty;
    }

    public function updateQuantityForSameProduct_v1($value = '', $data_arr = array())
    {
        $new_qty = $value+$data_arr['quantity'];
        return $new_qty;
    }

    public function getTotalQuantityPriceProduct($input_params = array())
    {
        $product_quantity = $input_params['quantity']+$input_params['get_quantity'][0]['product_qty'];

        $product_price = $input_params['selling_price']+$input_params['get_quantity'][0]['product_price'];
        $product_price_total = $input_params['grand_total_price']+$input_params['get_quantity'][0]['total_price_1'];

        $return_arr[0]['product_quantity'] = $product_quantity;
        $return_arr[0]['productprice'] = $product_price;
        $return_arr[0]['product_price_total'] = $product_price_total;
        return $return_arr;
    }

    public function validateProductCSV($mode = '', $id = '', $parID = '')
    {
        $return_arr['success'] = true;
        $return_arr['message'] = "Your csv has been uploaded successfully.";
        $err_msg = "Por favor, vuelva a revisar su encabezado csv, el encabezado debe ser el mismo dado URL de la hoja google";
        $req_col = $this->CI->config->item('CSVCOLNAMES');
        $req_col = explode(",", $req_col);
        $media_file = $this->CI->input->post('vpcm_upload_file_name');
        if ($media_file != '')
        {
            $csv_path = "public/upload/__temp/".$media_file;
            $file = fopen($csv_path, "r");
            $i = 0;
            $first_row = array();
            while (!feof($file))
            {
                $row = fgetcsv($file);
                if ($i == 0)
                {
                    foreach ($row as $colname)
                    {
                        $first_row[] = trim($colname);
                    }
                    break;
                }
            }
            if (count($first_row) == count($req_col))
            {
                $new_array = array_intersect($req_col, $first_row);
                if (count($new_array) != count($req_col))
                {
                    $return_arr['success'] = false;
                    $return_arr['message'] = $err_msg;
                }
            }
            else
            {
                $return_arr['success'] = false;
                $return_arr['message'] = $err_msg;
            }
        }

        return $return_arr;
    }

    public function requoteQuotation($input_params = array())
    {
        $ret_arr = array();
        $ret_arr['success'] = true;
        $ret_arr['new_quote_id'] = 0;
        $new_quote_id = 0;
        $qid = $input_params['qid'] ? $input_params['qid'] : 0;
        if (!($qid > 0))
        {
            $ret_arr['success'] = false;
            $ret_arr['new_quote_id'] = 0;
            return $ret_arr;
        }

        $select = $this->CI->db->select('iAssignedSalesUserId,iDistributorUserId,iLeadCompanyId,vCustomerName,vEmail,vContactNumber,vContactNumberExt,vMobileNumber,vFunction,iLeadImportanceTypeID,iCountryId,iStateId,vCity,vCompanyName,vCompanyLogo,tCustomerComments,iTotalProductAdded,fSubTotalPrice,fDiscountPrice,fVatTotalPrice,fVatPercentage,fDiscountPercentage,fTotalPrice,NOW() as dAddedDate,NOW() as dModifiedDate,iAddedByUserType,iAddedByUserId,vLeadStageID,vLeadStatusID,vAssignedSalesPersonName,vDeliveryTime,vPaymentConditions,vBankAccountNumber,vBankCLABETransfers,vBankBranchOffice,vTermsConditions')->where('iLeadMasterId', $qid)->get('lead_master');
        $requote_data = $select->row_array();
        if ($select->num_rows() == 1)
        {
            $this->CI->db->insert('lead_master', $requote_data);
            $new_quote_id = $this->CI->db->insert_id();
            $data_arr = array();
            $data_arr['vCustomerName'] = $requote_data['vCustomerName'];
            $data_arr['vCompanyName'] = $requote_data['vCompanyName'];
            $data_arr['vAssignedSalesPersonName'] = $requote_data['vAssignedSalesPersonName'];
            $this->createQuotationUniqueId($data_arr, $new_quote_id, '', '', true);
            if ($new_quote_id > 0)
            {
                $prod_arr = $this->CI->db->select($new_quote_id.' as iLeadMasterId ,iProductId,vProductName,vProductParentCode,vProviderUniqueKey,vProductImageURL,vColorName,vMaterialName,vTechniqueIncluded,vMeasurement,iProductQty,fProductPrintingUnitPrice,fProductUnitPrice,fProductPrintingTotalPrice,fExtraCostPrice,fProductPrice,fTotalPrice,dFirmDeliveryDate,vProductComments,now() as dAddedDate,now() as dModifiedDate,tDescription,tProductsComments,tExtraChargesComments,iProviderId,eProductType')->where('iLeadMasterId', $qid)->get('lead_product_items')->result_array();
                if (count($prod_arr) > 0)
                {
                    $this->CI->db->insert_batch("lead_product_items", $prod_arr);
                }
            }
        }
        else
        {
            $ret_arr['success'] = false;
            $ret_arr['new_quote_id'] = 0;
            return $ret_arr;
        }

        $ret_arr['success'] = true;
        $ret_arr['new_quote_id'] = $new_quote_id;
        return $ret_arr;
    }

    public function EditQuotationUniqueId($input_params = array())
    {
        $data = array();
        $data['vCustomerName'] = $input_params['contact_name'];
        $data['vCompanyName'] = $input_params['company_name'];
        $data['vAssignedSalesPersonName'] = $input_params['sales_executive_name'];
        $data['vLeadUniqueId'] = $input_params['old_uniq_id'];
        $this->createQuotationUniqueId($data, $input_params['qid'], '', '', true);
    }

    public function UpdateProviderSetting($mode = '', $id = '', $parID = '')
    {
        $this->CI->db->select('*');
        $this->CI->db->where('iUserDistributorSettingId', $id);
        $data = $this->CI->db->get('user_distributor_setting')->first_row();

        $this->CI->db->select('iProviderId');
        $this->CI->db->where('iDistributoruserId', $data->iUserId);
        $this->CI->db->where('eProviderType', 'Custom');
        $providers = $this->CI->db->get('distributor_provider_mapping')->result_array();

        foreach ($providers as $key => $value)
        {

            $params['tOfferPrintingNote'] = $data->tOfferPrintingNote;
            $params['tOtherPrintingNote'] = $data->tOtherPrintingNote;
            $params['tProductionTime'] = $data->tProductionTime;
            $this->CI->db->where('iUserId', $value['iProviderId']);
            $this->CI->db->update('user_provider_settings', $params);
        }

        $return_arr['success'] = true;
        $return_arr['message'] = "Impresión archivada actualizada con éxito";
        return $return_arr;
    }

    public function getFinalSubTotalPrice($value = '', $data_arr = array())
    {
        $sub_total_price = $data_arr['getoldprices'][0]['lm_sub_total_price'];
        return $sub_total_price;
    }

    public function getFinalVatPrice($value = '', $data_arr = array())
    {
        $vat_price = $value+$data_arr['getoldprices'][0]['lm_vat_total_price'];

        return $vat_price;
    }

    public function getFinalTotalPrice($value = '', $data_arr = array())
    {
        $old_total = $data_arr['getoldprices'][0]['lm_total_price'];
        $total_price = ($value+$old_total);
        return $total_price;
    }

    public function draftUpdateUniqueId($input_params = array())
    {
        $data = array();
        $data['vCustomerName'] = $input_params['contact_name'];
        $data['vCompanyName'] = $input_params['company_name'];
        $data['vAssignedSalesPersonName'] = $input_params['sales_executive_name'];
        $this->createQuotationUniqueId($data, $input_params['insert_quotation_draft'][0]['insert_id'], 'Add', '', true);
    }

    public function getProviderCompanyNameForImport($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $this->CI->db->select('vCompanyName');
        $this->CI->db->where('iUsersId', $data['vpcm_vendor_id']);
        $data = $this->CI->db->get('users')->first_row();
        return $data->vCompanyName;
        return $ret_val;
    }

    public function addOnRange($value = '', $data_arr = array())
    {
        $addon_list = array();
        if ($value != '')
        {
            $value = explode("|", $value);
            foreach ($value as $item)
            {
                $t = explode("=", $item);
                $r = explode("-", $t[0]);
                $addon_list[] = array(
                    'addon_price' => $t[1],
                    'min_range' => $r[0],
                    'max_range' => $r[1],
                );
            }
        }
        return $addon_list;
    }

    public function getDownloadButton($value = '', $id = '', $data = array())
    {
        $path = "product_csv/".$data['vpcm_vendor_id']."/".$data['vpcm_upload_file_name'];
        $download_icon = "<i class='fa fa-download' aria-hidden='true' style='font-size:20px;' ></i>";
        $download_url = $this->CI->config->item('upload_url').$path;
        $download_path = $this->CI->config->item('upload_path').$path;

        $getButton = "<a  class='btn btn-info pro_import_dwnload_btn' href=".$download_url.">".$download_icon."</a>";
        if (file_exists($download_path))
        {
            return $getButton;
        }
        else
        {
            return "N/A";
        }
    }

    public function getViewButtonForCustom($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";

        $button .= '<a hijacked="yes" class="btn btn_info custom-view-btn settings_btn" href="'.$this->CI->config->item('admin_url').'#product/distributor_category_listing/add|mode|Update|id|'.$data['cm_category_master_id'].'|" title="Configuraci&oacute;n"><i class="entypo-icon-settings"></i></a>';
        if ($data['cm_category_type'] == 'Custom')
        {
            $button .= '<a class="btn btn_info custom-view-btn view_btn" href="'.$this->CI->config->item('admin_url').'#product/distributor_products/index|category|'.$data['cm_category_master_id'].'|is_custom_category|1" title="Productos"><i class="icomoon-icon-gift"></i></a>';
            $button .= '<a class="btn btn_info custom-view-btn remove_category_button" href="javascript://" data-href="'.$this->CI->config->item('admin_url').'product/distributor_category_listing/delete_custom" data-cat_id="'.$data['cm_category_master_id'].'" title="Borrar"><i class="ui-icon ui-icon-trash"></i></a>';
            $button .= '<a hijacked="yes" class="btn btn_info custom-view-btn add_product_btn fancybox-popup" href="'.$this->CI->config->item('admin_url').'#distributor/distributor_custom_category_product_mapping/add|mode|Add|cat_id|'.$data['cm_category_master_id'].'|hideCtrl|true|loadGrid|list2" title="A&ntilde;adir Productos"><i class="icomoon-icon-plus-2"></i></a>';
        }
        else
        {
            $button .= '<a target="_blank" class="btn btn_info custom-view-btn view_btn" href="'.$this->CI->config->item('admin_url').'#product/distributor_products/index|category|'.$data['cm_category_master_id'].'|" title="Products"><i class="icomoon-icon-gift"></i></a>';
        }

        $button .= "</div>";
        return $button;
    }

    public function getDistributorProductImportDownloadButton($value = '', $id = '', $data = array())
    {
        $path = "product_csv/".$data['vpcm_vendor_id']."/".$data['vpcm_upload_file_name'];
        $download_icon = "<i class='fa fa-download'></i>";
        $download_url = $this->CI->config->item('upload_url').$path;
        $download_path = $this->CI->config->item('upload_path').$path;
        $getButton = "<a class='btn btn-info pro_import_dwnload_btn' href=".$download_url."><i class='fa fa-download' aria-hidden='true' style='font-size:20px;'></i></a>";
        if (file_exists($download_path))
        {
            return $getButton;
        }
        else
        {
            return "N/A";
        }
    }

    public function getButtonsForV1($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        if (!($data['dpm_distributor_provider_mapping_id'] > 0 && $data['u_provider_type'] == 'Custom'))
        {
            $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#custom_products/custom_products/provider_detail_view|id|".$data['u_users_id']."' title='Ver'><i class='icon18 iconic-icon-eye'></i></a>";
        }

        $button .= '<a class="btn btn_info custom-view-btn view_btn" href="'.$this->CI->config->item('admin_url').'#product/products_v1_v1/index|supplier|'.$data['u_users_id'].'|" title="Productos"><i class="icomoon-icon-gift"></i></a>';
        if ($data['dpm_distributor_provider_mapping_id'] > 0)
        {

            $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn settings_btn' href='".$this->CI->config->item('admin_url')."#distributor/distriburtor_provider/add|mode|Update|id|".$data['dpm_distributor_provider_mapping_id']."' title='Configuración'><i class='entypo-icon-settings'></i></a>";
        }
        else
        {
            $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn settings_btn' href='".$this->CI->config->item('admin_url')."#user/distributor_provider_listing_v1/addProvider|provider_id|".$data['u_users_id']."|add_to_redirect|true' title='Configuración'><i class='entypo-icon-settings'></i></a>";
        }

        $button .= "</div>";
        return $button;
    }

    public function start_date($value = '', $id = '', $data = array())
    {
        if ($data['ds_payment_status'] == 'Pending')
        {
            $ret_val = 'Pending';
        }
        else
        {
            $ret_val = $dat['ds_start_date'];
        }
        return $ret_val;
    }

    public function end_date($value = '', $id = '', $data = array())
    {
        if ($data['ds_payment_status'] == 'Pending')
        {
            $ret_val = 'Pending';
        }
        else
        {
            $ret_val = $dat['ds_end_date'];
        }
        return $ret_val;
    }

    public function add_button($value = '', $id = '', $data = array())
    {
        $getButton = array();
        if ($data['sm_plan_code'] != 'FREE')
        {
            $getButton = '<a class="btn btn_info custom-view-btn view_btn" href="'.$this->CI->config->item('admin_url').'#distributor/distributor_subscription/zoho_update|id|'.$data['iDistributorSubscriptionId'].'" title="Update"><i class="icomoon-icon-pencil-2"></i></a>';
        }
        else
        {
            $getButton = '<a class="btn btn_info custom-view-btn view_btn" href="'.$this->CI->config->item('admin_url').'#distributor/distributor_subscription/zoho_add|id|" title="Update"><i class="icomoon-icon-pencil-2"></i></a>';
        }
        return $getButton;
    }

    public function getSubscriptionPackageData($package_id = 0)
    {
        $ret_arr = array();
        if ($package_id > 0)
        {
            $this->CI->db->select('sm.vZohoPlanCode,sm.vPlanCode,sm.eDurationType,sm.iDuration,sm.fPrice,sm.eHasAddon,sam.iSubscriptionAddonMasterId,sam.vZohoAddonCode,sam.tRange,sm.vPlanDesc,sm.vPlanName,sm.vDisplayName');
            $this->CI->db->from('subscription_master as sm');
            $this->CI->db->join('subscription_addon_master as sam', "sm.iSubscriptionMasterId=sam.iSubscriptionMasterId", "left");
            $this->CI->db->where('sm.iSubscriptionMasterId', $package_id);
            $package_data = $this->CI->db->get()->result_array();
            $ret_arr['data'] = $package_data[0];
        }
        return $ret_arr;
    }

    public function subscriptionStatus($value = '', $id = '', $data = array())
    {
        if ($data['sm_plan_code'] == 'FREE')
        {
            return "<span class='badges live'>FREE</span>";
        }
        if ($value == 'Live')
        {
            return "<span class='badges live'>Live</span>";
        }
        else
        {
            return "<span class='badges otherspbsstatus'>".$value."</span>";
        }
    }

    public function getViewButtonForErrorCountForDashboard($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a class='btn btn_info custom-view-btn delete_btn' href='".$this->CI->config->item('admin_url')."#custom_products/error_reports/index|id|".$data['iVendorProductCsvMasterId']."'>".$value."</a>";
        $button .= "</div>";
        return $button;
    }

    public function getErrorSttaus($value = '', $id = '', $data = array())
    {
        if ($value == -1)
        {
            $ret_val = "<span style='
    color:red;'>Error</span>";
        }
        else
        if ($value == 0)
        {
            $ret_val = "<span style='
    color:blue'>Pending</span>";
        }
        else
        {
            $ret_val = "<span style='
    color:green'>Completed</span>";
        }
        return $ret_val;
    }

    public function generateQuoteEditLink($value = '', $id = '', $data = array())
    {
        $url = "javascript://";
        if ($data['lm_lead_stage_id'] == 'Draft')
        {
            $url = $this->CI->config->item('admin_url')."#quotation/quotation/add|Mode|Update|id|".$data['lm_lead_master_id']."|tab|1";
        }
        else
        {
            $url = $this->CI->config->item('admin_url')."#crm/product_inquiry_v1/getProductDetails|mode|View|id|".$data['lm_lead_master_id'];
        }
        $link = "<a href='".$url."'>".$data['lm_lead_unique_id']."</a>";
        return $link;
    }

    public function checkCategoryrequestExists($mode = '', $id = '', $parID = '')
    {
        $this->CI->db->select('iCategoryRequestMasterId');
        $this->CI->db->where('vCategoryName', $_POST['crm_category_name']);
        $category_data = $this->CI->db->get('category_request_master')->first_row();
        $data = array();
        if (count($category_data) > 0)
        {
            $data['success'] = False;
            $data['message'] = 'This '.$_POST['crm_category_name'].' category is alraedy exists.Please check into the category Listing';
        }
        return $data;
    }

    public function getListOfCategoriesForErrorHistory()
    {
        $this->CI->db->select('cm.iCategoryMasterId,cm.vCategoryName');
        $this->CI->db->from('category_master as cm');
        $this->CI->db->join("category_master as cm2", "cm2.iParentId=cm.iCategoryMasterId", 'left');
        $this->CI->db->where('cm.eCategoryType', "Default");
        $this->CI->db->where('cm.eStatus', 'Active');
        $this->CI->db->where('(cm2.iCategoryMasterId = 0 OR cm2.iCategoryMasterId IS NULL)');
        $this->CI->db->group_by('cm.iCategoryMasterId');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    public function saveSubscriptionFeatures($mode = '', $id = '', $parID = '')
    {
        //
        $post_arr = $this->CI->input->post();
        $subscription_packages = $post_arr['sf_subscription_code'];
        $data_arr = array();
        $valid = false;
        foreach ($subscription_packages as $key => $value)
        {
            if ($mode == 'Add')
            {
                $data_arr['iMenuId'] = $post_arr['sf_menu_id'];
                $data_arr['dAddedDate'] = $post_arr['sf_added_date'];
                $data_arr['vSubscriptionCode'] = $value;
                $data_arr['eStatus'] = $post_arr['sf_status'];
                $insert_data = $this->CI->db->insert('subscription_features', $data_arr);
                $valid = true;
            }
            else
            {
                $valid = true;
            }
        }
        $ret_arr = array();
        if ($valid == true)
        {
            $ret_arr['success'] = true;
        }
        else
        {
            $ret_arr['success'] = false;
        }

        return $ret_arr;
    }

    public function getScaleDataForProduct($input_params = array())
    {
        $qty = $input_params['pps_max_qunatity_1'];
        $profit = floatval($this->CI->session->userdata('fDistibutorProfitDiscount'));
        $scale_pricing_data = $this->CI->general->getScalePricingQuery($input_params['pm_product_master_id'], $input_params['distributor_id'], false, $profit);
        $scale_row = array();
        foreach ($scale_pricing_data as $key => $scale)
        {
            if ($qty == $scale['iMaxQunatity'])
            {
                $scale_row = $scale;
                break;
            }
            if ($qty < $scale['iMaxQunatity'])
            {
                $scale_row = $scale_pricing_data[$key-1];
                break;
            }
            if ($qty > $scale['iMaxQunatity'])
            {
                if (isset($scale_pricing_data[$key+1]))
                {
                    $scale_row = $scale_pricing_data[$key+1];
                }
                else
                {
                    $scale_row = $scale;
                }
            }
        }
        if (count($scale_row) == 0 && count($scale_pricing_data) > 0)
        {
            $scale_row = $scale_pricing_data[0];
        }
        $ret = array();
        $ret[0]['pps_product_price_1'] = $scale_row['selling_price'];
        $ret[0]['pps_total_price_1'] = $scale_row['selling_price'];
        return $ret;
    }

    public function updateTotalPrice($value = '', $data_arr = array())
    {
        $new_price = $value+($data_arr['pps_max_qunatity_1']*$data_arr['pps_total_price_1']);
        return $new_price;
    }

    public function checkModuleAccess($module_id = 0, $packages = array())
    {
        $this->CI->db->select('sm.*');
        $this->CI->db->from('subscription_features as sm');
        $this->CI->db->where('sm.iMenuId', $module_id);
        $this->CI->db->where('sm.eStatus', 'Active');
        $this->CI->db->where_in('sm.vSubscriptionCode', $packages);
        $data = $this->CI->db->get()->result_array();
        if (!empty($data) && count($data) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getMyPackages()
    {
        $live_packages_arr = $this->CI->session->userdata('live_subscription_detail');
        if (!empty($live_packages_arr) && count($live_packages_arr) > 0)
        {
            $active_packages_arr = array();
            foreach ($live_packages_arr as $key => $value)
            {
                $active_packages_arr[$key] = $value['vPackageCode'];
            }
            return $active_packages_arr;
        }
        else
        {
            return array("FREE");
        }
    }

    public function calculateTotalAmount($value = '', $data_arr = array())
    {
        $total_amount = $data_arr['lm_sub_total_price']+$data_arr['lm_vat_total_price'];
        return $total_amount;
    }

    public function getDistributorSubscriptionData($distributor_subscription_id = 0)
    {
        $ret_arr = array();
        if ($distributor_subscription_id > 0)
        {
            $this->CI->db->select('dsa.*,ds.*,dsa.dtStartDate as addon_start_date,dsa.dtEndDate as addon_end_date');
            $this->CI->db->from('distributor_subscription as ds');
            $this->CI->db->join('distributor_subscription_addons as dsa', "dsa.iDistributorSubscriptionId=ds.iDistributorSubscriptionId", "left");
            $this->CI->db->where('ds.iDistributorSubscriptionId', $distributor_subscription_id);
            $data = $this->CI->db->get()->row_array();
            $ret_arr['data'] = $data;
        }
        return $ret_arr;
    }

    public function getMonthlySubscriptionList($current_package_seq_no = 0, $plan_code = '')
    {
        $this->CI->db->select('sm.*,sam.*');
        $this->CI->db->from('subscription_master as sm');
        $this->CI->db->join('subscription_addon_master as sam', "sam.iSubscriptionMasterId=sm.iSubscriptionMasterId", "left");
        $this->CI->db->where('sm.vPlanCode!="FREE"');
        $this->CI->db->where('sm.vPlanCode!="WEB_PROMOTIONAL"');
        $this->CI->db->where('sm.eDurationType', "Monthly");
        $this->CI->db->where('sm.eStatus', "Active");
        if ($current_package_seq_no > 0 && $plan_code != 'WEB_PROMOTIONAL' && $plan_code != 'FREE')
        {
            $this->CI->db->where('sm.iSeq<=', $current_package_seq_no);
        }
        if ($plan_code == 'PREMIUM')
        {
            $this->CI->db->where('sm.vPlanCode!=', 'WEB_PROMOTIONAL_BASICS');
        }
        $this->CI->db->order_by('sm.iSeq', "ASC");
        $this->CI->db->group_by('sm.vPlanCode');
        $data = $this->CI->db->get()->result_array();
        return $data;
    }

    public function getYearlySubscriptionList($current_package_seq_no = 0, $plan_code = '')
    {
        $this->CI->db->select('sm.*,sam.*');
        $this->CI->db->from('subscription_master as sm');
        $this->CI->db->join('subscription_addon_master as sam', "sam.iSubscriptionMasterId=sm.iSubscriptionMasterId", "left");
        $this->CI->db->where('sm.vPlanCode!="FREE"');
        $this->CI->db->where('sm.vPlanCode!="WEB_PROMOTIONAL"');
        $this->CI->db->where('sm.eDurationType', "Yearly");
        $this->CI->db->where('sm.eStatus', "Active");
        if ($current_package_seq_no > 0 && $plan_code != 'WEB_PROMOTIONAL' && $plan_code != 'FREE')
        {
            $this->CI->db->where('sm.iSeq<=', $current_package_seq_no);
        }
        if ($plan_code == 'PREMIUM')
        {
            $this->CI->db->where('sm.vPlanCode!=', 'WEB_PROMOTIONAL_BASICS');
        }
        $this->CI->db->order_by('sm.iSeq', "ASC");
        $this->CI->db->group_by('sm.vPlanCode');
        $data = $this->CI->db->get()->result_array();
        return $data;
    }

    public function getUserSubscriptionDetails($value = '', $id = '', $data = array())
    {
        $html = "<div class='package_details'>";
        $html .= "<b>".$value."</b><br/>";
        $html .= "</div>";
        return $html;
    }

    public function add_distributor_new_subscription_data($data = array(), $id = 0)
    {
        $insert_arr = array(
            'vZohoCustomerId' => $data['zoho_customer_id'],
            'iDistributorId' => $data['distributor_id'],
            'iPackageId' => $data['package_id'],
            'vPackageCode' => $data['package_code'],
            'tZohoHostedPageUrl' => $data['zoho_hosted_page_url'],
            'ePaymentStatus' => 'Pending',
            'vZohoReferenceCode' => $data['zoho_reference_code'],
            'vZohoPlanCode' => $data['zoho_plan_code'],
        );
        $this->CI->db->where('iDistributorSubscriptionId', $id);
        $this->CI->db->update('distributor_subscription', $insert_arr);
        $insert_id = $this->CI->db->affected_rows();
        return intval($insert_id);
    }

    public function getFilteredMonthlySubscriptionList()
    {
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $this->CI->db->select('sm.*,sam.*');
        $this->CI->db->from('subscription_master as sm');
        $this->CI->db->join('subscription_addon_master as sam', "sam.iSubscriptionMasterId=sm.iSubscriptionMasterId", "left");
        $this->CI->db->where('sm.vPlanCode!="FREE"');
        $this->CI->db->where('sm.vPlanCode!="WEB_PROMOTIONAL"');
        $this->CI->db->where('sm.eDurationType', "Monthly");
        $this->CI->db->where('sm.eStatus', "Active");
        $this->CI->db->where('sm.iSubscriptionMasterId NOT IN (SELECT iPackageId FROM distributor_subscription WHERE iDistributorId="'.$distributor_id.' AND NOW() between dtStartDate and dtEndDate") ');
        $this->CI->db->group_by('sm.vPlanCode');
        $data = $this->CI->db->get()->result_array();
        return $data;
    }

    public function getFilteredYearlySubscriptionList()
    {
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $this->CI->db->select('sm.*,sam.*');
        $this->CI->db->from('subscription_master as sm');
        $this->CI->db->join('subscription_addon_master as sam', "sam.iSubscriptionMasterId=sm.iSubscriptionMasterId", "left");
        $this->CI->db->where('sm.vPlanCode!="FREE"');
        $this->CI->db->where('sm.vPlanCode!="WEB_PROMOTIONAL"');
        $this->CI->db->where('sm.eDurationType', "Yearly");
        $this->CI->db->where('sm.eStatus', "Active");
        $this->CI->db->where('sm.iSubscriptionMasterId NOT IN (SELECT iPackageId FROM distributor_subscription WHERE iDistributorId="'.$distributor_id.' AND NOW() between dtStartDate and dtEndDate") ');
        $this->CI->db->group_by('sm.vPlanCode');
        $data = $this->CI->db->get()->result_array();
        return $data;
    }

    public function checkPlanAccess($tab_code = '')
    {
        $live_subscription_arr = $this->CI->session->userdata('live_subscription_detail');
        $pkg_code_arr = array();
        foreach ($live_subscription_arr as $key => $value)
        {
            $pkg_code_arr[$key] = $value['vPackageCode'];
        }
        $has_access = true;
        foreach ($pkg_code_arr as $key => $value)
        {
            $this->CI->db->select('tps.iTabsPackageTransId');
            $this->CI->db->from('tabs_package_trans as tps');
            $this->CI->db->where('tps.vPackageCode', $value);
            $this->CI->db->where('tps.eStatus', 'Active');
            $this->CI->db->where("tps.tTabCode", $tab_code);
            $data[] = $this->CI->db->get()->row_array();
        }
        $send_data = array();
        foreach ($data as $key => $value)
        {
            if (!empty($value) && isset($value['iTabsPackageTransId']) && $value['iTabsPackageTransId'] != '')
            {
                $send_data[$key] = $value['iTabsPackageTransId'];
            }
        }
        if (!empty($send_data))
        {
            $has_access = true;
        }
        else
        {
            $has_access = false;
        }
        return $has_access;
    }

    public function getStatusButton($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $hide_status = "";
        if ($data['u_status'] != 'Active')
        {
            $hide_status = "hide-inactive";
        }
        else
        {
            $hide_status = "hide-active";
        }
        if ($hide_status == 'hide-active')
        {
            $icon_class = "fa fa-eye";
            $icon_title = $this->CI->lang->line("INACTIVATE_USER");
        }
        else
        {
            $icon_class = "fa fa-eye-slash";
            $icon_title = $this->CI->lang->line("ACTIVATE_USER");
        }
        if ($data['u_sales_person_role'] != 'SuperAdmin')
        {
            $button .= "<a style='margin-left:30px;' id='change-status-".$data['u_users_id']."' hijacked='yes' class='btn btn_info custom-view-btn btn_hide_toggle ".$hide_status."' href='javascript://'   data-toggle='yes' data-id='".$data['u_users_id']."' title='".$icon_title."'><i class='".$icon_class."'></i></a>";
        }
        else
        {
            $button .= "<span style='margin-left:30px;'>N/A</span>";
        }
        $button .= "</div>";
        return $button;
    }

    public function deactivate_salespersons()
    {
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $data = array(
            'eStatus' => 'Inactive',
        );
        $where = array(
            'iDistributorUserId' => $distributor_id,
            'iUserType' => '3',
        );
        $this->CI->db->where($where);
        $this->CI->db->update('users', $data);
        return ($this->CI->db->affected_rows() > 0) ? true : false;
    }

    public function addDateOnAddMode($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        if ($mode == 'Add')
        {
            return date('Y-m-d h:i:s');
        }
        else
        {
            return $value;
        }
    }

    public function deactivate_salespersons_of_distributor($input_params = array())
    {
        $addon_qty = intval($input_params['quantity']);
        $old_addon_qty = intval($input_params['check_addons_exist'][0]['dsa_addon_users_qty']);
        $distributor_id = $input_params['select_distributor_subscription'][0]['ds_distributor_id'];
        if ($addon_qty < $old_addon_qty)
        {
            $data = array(
                'eStatus' => 'Inactive',
            );
            $where = array(
                'iDistributorUserId' => $distributor_id,
                'iUserType' => '3',
            );
            $this->CI->db->where($where);
            $this->CI->db->update('users', $data);
            return ($this->CI->db->affected_rows() > 0) ? true : false;
        }
        else
        {
            return true;
        }
    }

    public function deactivate_salespersons_of_distributor_add($input_params = array())
    {
        $addon_qty = intval($input_params['quantity']);
        $old_addon_qty = intval($input_params['check_addons_exist'][0]['dsa_addon_users_qty']);
        if ($addon_qty < $old_addon_qty)
        {
            $distributor_id = $input_params['select_distributor_subscription'][0]['ds_distributor_id'];
            $data = array(
                'eStatus' => 'Inactive',
            );
            $where = array(
                'iDistributorUserId' => $distributor_id,
                'iUserType' => '3',
            );
            $this->CI->db->where($where);
            $this->CI->db->update('users', $data);
            return ($this->CI->db->affected_rows() > 0) ? true : false;
        }
        else
        {
            return true;
        }
    }

    public function getCategoryList($value = '', $mode = '', $id = '', $data = array(), $parent_src = '')
    {
        //
        $this->CI->db->select('cm.iCategoryMasterId as id,cm.vCategoryName as val');
        $this->CI->db->from('category_master as cm');
        $this->CI->db->join("category_master as cm2", "cm2.iParentId=cm.iCategoryMasterId", 'left');
        $this->CI->db->where('cm.eCategoryType', "Default");
        $this->CI->db->where('cm.eStatus', 'Active');
        $this->CI->db->where('(cm2.iCategoryMasterId = 0 OR cm2.iCategoryMasterId IS NULL)');
        $this->CI->db->group_by('cm.iCategoryMasterId');
        $this->CI->db->order_by('cm.vCategoryName ASC');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    public function generateAuthToken()
    {
        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $random_string = '';
        for ($i = 0; $i < $length; $i++)
        {
            $random_string .= $characters[rand(0, $characters_length-1)];
        }
        return $random_string;
    }

    public function saveGenericInvoiceData($mode = '', $id = '', $parID = '')
    {
        $post_arr = $this->CI->input->post();
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $custom_fields = array();
        $state_country = $this->getStateCountryNames($post_arr['uid_country_id'], $post_arr['uid_state_id']);
        if ($post_arr['u_invoice_type'] == 'Generic')
        {
            $this->CI->db->select('uid.iUserInvoiceDetailsId');
            $this->CI->db->from('user_invoice_details as uid');
            $this->CI->db->where('uid.iDistributorUserId', $distributor_id);
            $data = $this->CI->db->get()->row_array();
            if (!empty($data) && $data['iUserInvoiceDetailsId'] != '')
            {
                $update_arr = array(
                    'vRFC' => 'XAXX010101000',
                );
                $where = array(
                    'iDistributorUserId' => $distributor_id,
                );
                $this->CI->db->where($where);
                $this->CI->db->update('user_invoice_details', $update_arr);
            }
            else
            {
                $insert_arr = array(
                    'iDistributorUserId' => $distributor_id,
                    'vRFC' => 'XAXX010101000',
                    'iCountryId' => '138',
                );
                $this->CI->db->insert('user_invoice_details', $insert_arr);
            }
            $custom_fields = array(
                array(
                    'label' => 'RFC',
                    'value' => 'XAXX010101000',
                ),
                array(
                    'label' => 'Correo electrónico de la empresa',
                    'value' => '',
                ),
            );
        }
        else
        {
            $billing_address = array();
            $billing_address['street'] = $post_arr['uid_street'] ? $post_arr['uid_street'] : '';
            $billing_address['city'] = $post_arr['uid_city'] ? $post_arr['uid_city'] : '';
            $billing_address['state'] = $state_country['vState'] ? $state_country['vState'] : '';
            $billing_address['zip'] = $post_arr['uid_zip_code'] ? $post_arr['uid_zip_code'] : '';
            $billing_address['country'] = $state_country['vCountry'] ? $state_country['vCountry'] : '';

            $custom_fields = array(
                array(
                    'label' => 'RFC',
                    'value' => $post_arr['uid_rfc'],
                ),
                array(
                    'label' => 'Correo electrónico de la empresa',
                    'value' => $post_arr['u_company_email'],
                ),
            );
        }
        if (!is_array($billing_address))
        {
            $billing_address = array();
        }
        $json_custom_fileds = json_encode($custom_fields);
        $json_billing_address = json_encode($billing_address);
        $this->CI->load->library('zohosubscription');
        $customer_arr = array();
        $customer_arra['display_name'] = $post_arr['u_name'];
        $customer_arra['first_name'] = $post_arr['u_name'];
        $customer_arra['email'] = $post_arr['u_email'];
        $customer_arra['company_name'] = $post_arr['uid_company_name'] ? $post_arr['uid_company_name'] : '';
        $customer_arra['phone'] = $post_arr['u_compamy_contact_number'];
        $customer_arra['mobile'] = $post_arr['u_contact_number'];
        $customer_arra['billing_address'] = $json_billing_address;
        $customer_arra['custom_fields'] = $json_custom_fileds;
        $zdata = $this->CI->zohosubscription->update_customer($customer_arra, $post_arr['u_zoho_customer_id']);
        $ret = array();
        $ret['success'] = true;
        $ret['message'] = "perfil actualizado con éxito";
        return $ret;
    }

    public function getStateCountryNames($country_id = '', $state_id = '')
    {
        $this->CI->db->select('mc.vCountry,ms.vState');
        $this->CI->db->from('mod_country as mc');
        $this->CI->db->join('mod_state as ms', 'ms.iCountryId=mc.iCountryId');
        $this->CI->db->where('mc.iCountryId', $country_id);
        $this->CI->db->where('ms.iStateId', $state_id);
        $data = $this->CI->db->get()->row_array();
        return $data;
    }

    
    public function getDistributorCategories($value = '', $mode = '', $id = '', $data = array(), $parent_src = '')
    {
        $parmas = array();
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $parent_categories_data = $this->getEcommerceCategoriesForDistributor("parent", $distributor_id,"");
        $category_id_arr = array();
         
        /*foreach ($parent_categories_data as $key => $value)
        {
            $category_id_arr[] = $value['iCategoryMasterId'];
        }
        $parent_category_ids = implode("','", $category_id_arr);
        // pr($parent_category_ids); exit;
        $sub_categories_data = $this->getEcommerceCategoriesForDistributor("sub", '', $parent_category_ids, $distributor_id);*/
        //$mydata = array();
        //$mydata = array_merge($parent_categories_data, $sub_categories_data);
        $mydata=$parent_categories_data;
        $combo_arr = array();
        foreach ($mydata as $key => $value)
        {
            $combo_arr[$key]['val'] = ucfirst($value['cm_category_name']);
            $combo_arr[$key]['id'] = $value['iCategoryMasterId'];
        }

        asort($combo_arr);
        return $combo_arr;
    }

    public function getEcommerceCategoriesForDistributor($type='', $dist_id = 0,$parent_categories = '')
    { 
        if ($type == "parent")
        {
            $parent_sql = " and c.iParentId in (0) ";
        }
        if ($type == "sub")
        {
            $parent_sql = " and c.iParentId in ('".$parent_categories."') ";
        }
        $live_package_code = ecom_cnf('vPackageCode') ? ecom_cnf('vPackageCode') : $this->CI->session->userdata('live_subscription_detail')[0]['vPackageCode'];
        $free_restrctions = $this->CI->config->item('FREE_RESTRICTIONS');
        $partner_free_restrctions = $this->CI->config->item('PARTNER_ACCOUNT_RESTRICTIONS');
        if (strtolower($live_package_code) == 'free' && strtolower(trim($free_restrctions)) == 'on')
        {
            $q = $this->CI->db->query("select c.vCategoryName as cm_category_name, c.vCategoryName as catname,c.eCategoryType,c.iCategoryMasterId,c.iParentId
              from category_master as c left join distributor_category_trans as dcm on dcm.iCategoryMasterId=c.iCategoryMasterId and dcm.iDistributorUserId='".$dist_id."'
              where COALESCE(dcm.eStatus,'Active') = 'Active' and c.eStatus='Active' and c.vCategoryName!=''  AND ((dcm.iDistributorUserId='".$dist_id."' AND c.eCategoryType='Custom') OR c.eCategoryType='Default') $parent_sql order by  c.eCategoryType ASC, c.vCategoryName asc, dcm.eDisplayInLeftMenu desc, dcm.iSeqNo ASC  $limit_sql");
        }
        else
        if (strtolower($live_package_code) == 'partner_free' && strtolower(trim($partner_free_restrctions)) == 'on')
        {
            $q = $this->CI->db->query("select c.vCategoryName as cm_category_name, c.vCategoryName as catname,c.eCategoryType,c.iCategoryMasterId,c.iParentId from category_master as c
              left join distributor_category_trans as dcm on dcm.iCategoryMasterId=c.iCategoryMasterId and dcm.iDistributorUserId='".$dist_id."' where COALESCE(dcm.eStatus,'Active') = 'Active' and c.eStatus='Active' and c.vCategoryName!=''  AND ((dcm.iDistributorUserId='".$dist_id."' AND c.eCategoryType='Custom') OR c.eCategoryType='Default') $parent_sql  order by  c.eCategoryType ASC, c.vCategoryName asc, dcm.eDisplayInLeftMenu desc, dcm.iSeqNo ASC  $limit_sql");
        }
        else
        {
            $q = $this->CI->db->query("select c.vCategoryName as cm_category_name, c.vCategoryName as catname,c.eCategoryType,c.iCategoryMasterId,c.iParentId from category_master as c
              left join distributor_category_trans as dcm on dcm.iCategoryMasterId=c.iCategoryMasterId and dcm.iDistributorUserId='".$dist_id."' where COALESCE(dcm.eStatus,'Active') = 'Active' and c.eStatus='Active' and c.vCategoryName!=''  AND ((dcm.iDistributorUserId='".$dist_id."' AND c.eCategoryType='Custom') OR c.eCategoryType='Default') $parent_sql order by  c.eCategoryType ASC, c.vCategoryName asc, dcm.eDisplayInLeftMenu desc, dcm.iSeqNo ASC  $limit_sql");
        }
        $parent_categories = $q->result_array();
        return $parent_categories;
    }

    public function getSalesPersonRole($value = '', $id = '', $data = array())
    {
        $html = '';
        if ($data['u_sales_person_role'] == 'Salesman')
        {
            $html = '<span class="salesman_role '.strtolower($data['u_sales_person_role']).'"><i class="fa fa-user"></i> '.$value.'</span>';
        }
        elseif ($data['u_sales_person_role'] == 'Admin')
        {
            $html = '<span class="salesman_role '.strtolower($data['u_sales_person_role']).'"><i class="fa fa-key"></i> '.$value.'</span>';
        }
        elseif ($data['u_sales_person_role'] == 'SuperAdmin')
        {
            $html = '<span class="salesman_role '.strtolower($data['u_sales_person_role']).'"><i class="fa fa-lock"></i> '.$value.'</span>';
        }
        else
        {
            $html = '<span class="salesman_role '.strtolower($data['u_sales_person_role']).'">'.$value.'</span>';
        }
        return $html;
    }

    public function checkAddonArrayExists($input_params = array())
    {
        $ret_arr = array();
        if (is_array($input_params['addons']) && count($input_params['addons']) > 0 && isset($input_params['addons'][0]['addon_id']))
        {
            $ret_arr[0]['success'] = 1;
        }
        else
        {
            $ret_arr[0]['success'] = 0;
        }
        return $ret_arr;
    }

    public function checkHistoryData($distributor_id = 0, $salesman_id = 0)
    {
        $this->CI->db->select('eStatus');
        $this->CI->db->from('distributor_import_request_master');
        $this->CI->db->where('iDistributorUserId', $distributor_id);
        $data = $this->CI->db->get()->row_array();
        return $data;
    }

    public function checkCustomCode($input_params = array())
    {
        $this->CI->db->select('uds.eUseCustomCodesForProduct,u.vUCode');
        $this->CI->db->from('user_distributor_setting as uds');
        $this->CI->db->from('users as u', 'uds.iUserId=u.iUsersId');
        $this->CI->db->where('uds.iUserId', $input_params['distributor_id']);
        $this->CI->db->where('u.iUsersId', $input_params['distributor_id']);
        $dist_data = $this->CI->db->get()->row_array();
        $u_code = $dist_data['vUCode'];
        if ($dist_data['eUseCustomCodesForProduct'] == 'Yes')
        {
            $custom_code = $input_params['u_u_code']."-".$input_params['pm_p_code'].'-'.$u_code;
            $custom_code = str_replace("=", "", base64_encode($custom_code));
            $ret_arr[0]['custom_code'] = $custom_code;
        }
        else
        {
            $custom_code = $input_params['pm_provider_unique_key'];
            $ret_arr[0]['custom_code'] = $custom_code;
        }
        return $ret_arr;
    }

    public function getSalesManName($value = '', $id = '', $data = array())
    {
        $html = '';
        if ($data['u_sales_person_role'] == 'SuperAdmin')
        {
            $html .= '<span class="user_name">'.$value.'</span>';
        }
        else
        {
            $url = $this->CI->config->item('admin_url')."#user/salepersons/add|mode|Update|id|".$data['u_users_id'];
            $html .= '<a href="'.$url.'" name="edit_user" id="edit_user">'.$value.' </a>';
        }
        return $html;
    }

    public function getMenuId($uniqueCode = '')
    {
        $this->CI->db->select('mam.iAdminMenuId');
        $this->CI->db->from('mod_admin_menu as mam');
        $this->CI->db->where('vUniqueMenuCode', $uniqueCode);
        $data = $this->CI->db->get()->row_array();
        return $data['iAdminMenuId'];
    }

    public function deactivate_salespersons_of_distributor_after_removing_addon($input_params = array())
    {
        $distributor_id = $input_params['ds_distributor_id'];
        $data = array(
            'eStatus' => 'Inactive',
        );
        $where = array(
            'iDistributorUserId' => $distributor_id,
            'iUserType' => '3',
        );
        $this->CI->db->where($where);
        $this->CI->db->update('users', $data);
        return ($this->CI->db->affected_rows() > 0) ? true : false;
    }

    public function updateSalesmanStatus($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        if ($mode == 'Add')
        {
            return 'Active';
        }
        else
        {
            return $value;
        }
    }

    public function getSalesmanOfDistributor($distributor_id=0)
    {
        $this->CI->db->select('iUsersId');
        $this->CI->db->from('users');
        $this->CI->db->where('iUserType', '3');
        $this->CI->db->where('iDistributorUserId', $distributor_id);
        $this->CI->db->where('eStatus', 'Active');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    public function getPackageDataFromCode($package_code = '')
    {
        $this->CI->db->select('sm.*');
        $this->CI->db->select('sam.*');
        $this->CI->db->from('subscription_master as sm');
        $this->CI->db->join('subscription_addon_master as sam', 'sam.iSubscriptionMasterId=sm.iSubscriptionMasterId', 'left');
        $this->CI->db->where('sm.vZohoPlanCode', $package_code);
        $package_data = $this->CI->db->get()->result_array();
        return $package_data;
    }

    public function getDataToUpdateInZoho($new_qty = 0, $old_qty = 0, $new_package_code = '', $old_package_code = '')
    {
        $new_package_data = $this->getPackageDataFromCode($new_package_code);
        $current_package_data = $this->getPackageDataFromCode($old_package_code);
        $updated_data = array();
        if ($new_package_data[0]['iSeq'] < $current_package_data[0]['iSeq'])
        {
            $updated_data['package_name'] = $new_package_data[0]['vPlanCode'];
        }
        else
        {
            $updated_data['package_name'] = $current_package_data[0]['vPlanCode'];
        }
        if ($new_qty > $old_qty)
        {
            $updated_data['no_of_users'] = $new_qty;
        }
        else
        {
            $updated_data['no_of_users'] = $old_qty;
        }
        if ($new_package_data[0]['iDuration'] > $current_package_data[0]['iDuration'])
        {
            $updated_data['package_type'] = $new_package_data[0]['iDuration'];
        }
        else
        {
            $updated_data['package_type'] = $current_package_data[0]['iDuration'];
        }
        $duration = '';
        if ($updated_data['package_type'] == 1)
        {
            $duration = "_MONTHLY";
        }
        elseif ($updated_data['package_type'] == 12)
        {
            $duration = "_YEARLY";
        }
        $updated_data['package_code'] = $updated_data['package_name'].$duration;
        return $updated_data;
    }

    public function checkUpdatedSubscriptionStatus($new_package_code = '', $new_qty = 0, $current_package_data_code = '', $old_qty = 0)
    {
        $new_package_data = $this->getPackageDataFromCode($new_package_code);
        $current_package_data = $this->getPackageDataFromCode($current_package_data_code);
        $subscription_status = '';
        if ($new_package_data[0]['iDuration'] > $current_package_data[0]['iDuration'] && $new_package_data[0]['iSeq'] < $current_package_data[0]['iSeq'] && $new_qty > $old_qty)
        {
            $subscription_status = 'upgrade';
        }
        elseif ($new_package_data[0]['iDuration'] < $current_package_data[0]['iDuration'] && $new_package_data[0]['iSeq'] > $current_package_data[0]['iSeq'] && $new_qty < $old_qty)
        {
            $subscription_status = 'downgrade';
        }
        elseif ($new_package_data[0]['iDuration'] == $current_package_data[0]['iDuration'] && $new_package_data[0]['iSeq'] == $current_package_data[0]['iSeq'] && $new_qty == $old_qty)
        {
            $subscription_status = 'nochange';
        }
        elseif ($new_package_data[0]['iDuration'] > $current_package_data[0]['iDuration'] && $new_package_data[0]['iSeq'] == $current_package_data[0]['iSeq'] && $new_qty == $old_qty)
        {
            $subscription_status = 'upgrade';
        }
        elseif ($new_package_data[0]['iSeq'] < $current_package_data[0]['iSeq'] && $new_package_data[0]['iDuration'] == $current_package_data[0]['iDuration'] && $new_qty == $old_qty)
        {
            $subscription_status = 'upgrade';
        }
        elseif ($new_qty > $old_qty && $new_package_data[0]['iDuration'] == $current_package_data[0]['iDuration'] && $new_package_data[0]['iSeq'] == $current_package_data[0]['iSeq'])
        {
            $subscription_status = 'upgrade';
        }
        elseif ($new_package_data[0]['iDuration'] < $current_package_data[0]['iDuration'] && $new_package_data[0]['iSeq'] == $current_package_data[0]['iSeq'] && $new_qty == $old_qty)
        {
            $subscription_status = 'downgrade';
        }
        elseif ($new_package_data[0]['iSeq'] > $current_package_data[0]['iSeq'] && $new_package_data[0]['iDuration'] == $current_package_data[0]['iDuration'] && $new_qty == $old_qty)
        {
            $subscription_status = 'downgrade';
        }
        elseif ($new_qty < $old_qty && $new_package_data[0]['iDuration'] == $current_package_data[0]['iDuration'] && $new_package_data[0]['iSeq'] == $current_package_data[0]['iSeq'])
        {
            $subscription_status = 'downgrade';
        }
        elseif ($new_package_data[0]['iDuration'] > $current_package_data[0]['iDuration'] && $new_package_data[0]['iSeq'] < $current_package_data[0]['iSeq'] && $new_qty == $old_qty)
        {
            $subscription_status = 'upgrade';
        }
        elseif ($new_package_data[0]['iDuration'] > $current_package_data[0]['iDuration'] && $new_qty > $old_qty && $new_package_data[0]['iSeq'] == $current_package_data[0]['iSeq'])
        {
            $subscription_status = 'upgrade';
        }
        elseif ($new_qty > $old_qty && $new_package_data[0]['iSeq'] < $current_package_data[0]['iSeq'] && $new_package_data[0]['iDuration'] == $current_package_data[0]['iDuration'])
        {
            $subscription_status = 'upgrade';
        }
        elseif ($new_package_data[0]['iDuration'] < $current_package_data[0]['iDuration'] && $new_package_data[0]['iSeq'] > $current_package_data[0]['iSeq'] && $new_qty == $old_qty)
        {
            $subscription_status = 'downgrade';
        }
        elseif ($new_package_data[0]['iDuration'] < $current_package_data[0]['iDuration'] && $new_qty < $old_qty && $new_package_data[0]['iSeq'] == $current_package_data[0]['iSeq'])
        {
            $subscription_status = 'downgrade';
        }
        elseif ($new_qty < $old_qty && $new_package_data[0]['iSeq'] > $current_package_data[0]['iSeq'] && $new_package_data[0]['iDuration'] == $current_package_data[0]['iDuration'])
        {
            $subscription_status = 'downgrade';
        }
        elseif (strtolower($current_package_data[0]['vZohoPlanCode']) == 'web_premium_free' && strtolower($new_package_data[0]['vPlanCode']) == 'web_promotional_premium')
        {
            $subscription_status = 'upgrade';
        }
        else
        {
            $subscription_status = 'schedule';
        }
        return $subscription_status;
    }

    public function addScheduleSubscription($post_arr = array(), $package_code = '', $schedule_date = '', $zohosubscription_id = 0, $reference_code = '')
    {
        $this->CI->db->select('ss.iScheduleSubscriptionId');
        $this->CI->db->from('schedule_subscription as ss');
        $this->CI->db->where('ss.iSubscriptionId', $post_arr['id']);
        $prev_scheduled_subscription = $this->CI->db->get()->row_array();
        $data_arr = array();
        $duration = $post_arr['package_type'] == 1 ? 'Monthly' : 'Yearly';
        $data_arr['vPackageCode'] = $package_code;
        $data_arr['ePackageDuration'] = $duration;
        $data_arr['iPackageDuration'] = $post_arr['package_type'];
        $data_arr['vAddonQty'] = $post_arr['no_of_users'];
        $data_arr['iSubscriptionId'] = $post_arr['id'];
        $data_arr['dAddedDate'] = date('Y-m-d h:i:s');
        $data_arr['dScheduleDate'] = $schedule_date;
        $data_arr['eAutoRenew'] = $post_arr['auto_renew'] ? $post_arr['auto_renew'] : false;
        $data_arr['eStatus'] = "Pending";
        $data_arr['vZohoSubscriptionId'] = $zohosubscription_id;
        $data_arr['vZohoReferenceCode'] = $reference_code;
        $schedule_id = 0;
        if ($prev_scheduled_subscription['iScheduleSubscriptionId'] != '')
        {
            $where = array(
                'iScheduleSubscriptionId' => $prev_scheduled_subscription['iScheduleSubscriptionId'],
            );
            $this->CI->db->where($where);
            $this->CI->db->update("schedule_subscription", $data_arr);
            $schedule_id = $prev_scheduled_subscription['iScheduleSubscriptionId'];
        }
        else
        {
            $this->CI->db->insert("schedule_subscription", $data_arr);
            $schedule_id = $this->CI->db->insert_id();
        }
        return $schedule_id;
    }

    public function getDowngradableMonthlySubscriptionList($current_package_seq_no = 0, $plan_code = '')
    {
        $this->CI->db->select('sm.*,sam.*');
        $this->CI->db->from('subscription_master as sm');
        $this->CI->db->join('subscription_addon_master as sam', "sam.iSubscriptionMasterId=sm.iSubscriptionMasterId", "left");
        $this->CI->db->where('sm.vPlanCode!="FREE"');
        $this->CI->db->where('sm.vPlanCode!="WEB_PROMOTIONAL"');
        $this->CI->db->where('sm.eDurationType', "Monthly");
        $this->CI->db->where('sm.eStatus', "Active");
        if ($current_package_seq_no > 0 && $plan_code != 'WEB_PROMOTIONAL' && $plan_code != 'FREE')
        {
            $this->CI->db->where('sm.iSeq>=', $current_package_seq_no);
        }
        $this->CI->db->order_by('sm.iSeq', "ASC");
        $this->CI->db->group_by('sm.vPlanCode');
        $data = $this->CI->db->get()->result_array();
        return $data;
    }

    public function getDowngradableYearlySubscriptionList($current_package_seq_no = 0, $plan_code = '')
    {
        $this->CI->db->select('sm.*,sam.*');
        $this->CI->db->from('subscription_master as sm');
        $this->CI->db->join('subscription_addon_master as sam', "sam.iSubscriptionMasterId=sm.iSubscriptionMasterId", "left");
        $this->CI->db->where('sm.vPlanCode!="FREE"');
        $this->CI->db->where('sm.vPlanCode!="WEB_PROMOTIONAL"');
        $this->CI->db->where('sm.eDurationType', "Yearly");
        $this->CI->db->where('sm.eStatus', "Active");
        if ($current_package_seq_no > 0 && $plan_code != 'WEB_PROMOTIONAL' && $plan_code != 'FREE')
        {
            $this->CI->db->where('sm.iSeq>=', $current_package_seq_no);
        }
        $this->CI->db->order_by('sm.iSeq', "ASC");
        $this->CI->db->group_by('sm.vPlanCode');
        $data = $this->CI->db->get()->result_array();
        return $data;
    }

    public function getDowngradedData($subscription_id = 0, $zoho_subs_id = 0)
    {
        $this->CI->db->select('ss.*');
        $this->CI->db->from('schedule_subscription as ss');
        $this->CI->db->where('ss.iSubscriptionId', $subscription_id);
        $this->CI->db->where('ss.vZohoSubscriptionId', $zoho_subs_id);
        $this->CI->db->where('ss.dScheduleDate>=NOW()');
        $data = $this->CI->db->get()->row_array();
        return $data;
    }

    public function calculateUserPriceFromRange($range = array(), $qty = 0)
    {
        $ret_arr = array();
        $range = explode('|', $range);
        $price = 0;
        $user_total_price = 0;
        if (is_array($range) && count($range) > 0)
        {
            foreach ($range as $key => $value)
            {
                $range1 = explode('=', $value);
                $values = explode('-', $range1[0]);
                if ($qty >= $values[0] && $qty <= $values[1])
                {
                    $price = $range1[1];
                    $user_total_price = $qty*$price;
                }
            }
        }
        $ret_arr['price'] = $price;
        $ret_arr['user_total_price'] = $user_total_price;
        return $ret_arr;
    }

    public function update_invoice_data($input_params = array())
    {
        $invoice_id = $input_params['NEW_iInvoiceId'];
        $invoice_data = json_decode($input_params['NEW_tInvoiceJson'], true);
        $ret_arr = array();
        $where = array(
            'iInvoiceId' => $invoice_id,
        );
        $data = array(
            'eStatus' => 'Inprogress',
        );

        $this->CI->db->where($where);
        $this->CI->db->update('invoice_update_master', $data);

        $this->CI->load->library('zohobooks');
        $zdata = $this->CI->zohobooks->update_invoice($invoice_data, $invoice_id);
        if (!empty($zdata))
        {
            $where = array(
                'iInvoiceId' => $invoice_id,
            );
            $data = array(
                'eStatus' => 'Completed',
                'tInvoiceJson' => json_encode($zdata)
            );
            $this->CI->db->where($where);
            $this->CI->db->update('invoice_update_master', $data);
            $ret_arr['success'] = 'true';
        }
        else
        {
            $ret_arr['success'] = 'false';
        }
        return $ret_arr;
    }

    public function schedule_invoice($arr = array(), $invoice_id = 0)
    {
        $data_arr = array(
            'iInvoiceId' => $invoice_id,
            'tInvoiceJson' => json_encode($arr),
            'dAddedDate' => date('Y-m-d h:i:s')
        );
        $this->CI->db->insert('invoice_update_master', $data_arr);
        $id = $this->CI->db->insert_id() > 0 ? $this->CI->db->insert_id() : 0;
        $ret_arr = array();
        $ret_arr['success'] = true;
        $ret_arr['id'] = $id;
        return $ret_arr;
    }

    public function get_dist_unique_code($value = '', $data_arr = array())
    {
        $unique_id = 'DIST-'.str_pad($data_arr['distributor_id'], 7, "0", STR_PAD_LEFT);
        return $unique_id;
    }

    public function get_dist_subs_data($distributor_id = 0)
    {
        $this->CI->db->select('ds.*');
        $this->CI->db->from('distributor_subscription as ds');
        $this->CI->db->where('ds.iDistributorId', $distributor_id);
        $this->CI->db->limit(1);
        $data = $this->CI->db->get()->row_array();
        return $data;
    }

    public function displayViewButton($value = '', $id = '', $data = array())
    {
        $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn view_btn fancybox-popup' href='".$this->CI->config->item('admin_url')."#distributor/distributor_subscription_v2/add|mode|View|id|".$id."' title='Ver'><i class='icon18 iconic-icon-eye'></i></a>";
        return $button;
    }

    public function paymentStatus($value = '', $id = '', $data = array())
    {
        $status = strtolower($value);
        $html = '<span class="payment-status payment-'.$status.'" >'.$value.'</span>';

        return $html;
    }

    public function formatedDateTime($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $datetime = $value;
        if (trim($datetime) == '')
        {
            return '';
        }
        elseif ($datetime == "0000-00-00" || $datetime == "0000-00-00 00:00:00")
        {
            return '';
        }

        $datetime = date_create($datetime);
        $value = date_format($datetime, "M d, Y g:i a");
        return $value;
    }

    public function displayViewButtonV1($value = '', $id = '', $data = array())
    {
        $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn view_btn fancybox-popup' href='".$this->CI->config->item('admin_url')."#distributor/distributor_subscription_remaining/add|mode|View|id|".$id."' title='Ver'><i class='icon18 iconic-icon-eye'></i></a>";
        return $button;
    }

    public function showSubscriptionDetails($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        if ($value == "") {
            $value = 'N/A';
        }
        return $value;
    }

    public function sync_plans()
    {
        $this->setFrontModulePath();
        $this->CI->load->module('suppliers/providerimport');
        $data = $this->CI->providerimport->sync_plans(true);
        $this->unsetFrontModulePath();
        $this->setAdminModulePath();
        $ret_arr = array();
        $ret_arr['success'] = $data['success'] ? $data['success'] : true;
        $ret_arr['message'] = $data['message'] ? $data['message'] : '';
        return $ret_arr;
    }

    public function generateProviderPin($input_params = array())
    {
        $return_arr = array();
        $return_arr[0]['pin'] = "2000".str_pad($input_params['provider_id'], 4, "0", STR_PAD_LEFT);
        return $return_arr;
    }

    public function generateDistributorPin($input_params = array())
    {
        $return_arr = array();
        $dist_id = $input_params['distributor_id'] ? $input_params['distributor_id'] : $this->CI->session->userdata('iAdminId');
        $return_arr[0]['pin'] = "1000".str_pad($dist_id, 4, "0", STR_PAD_LEFT);
        return $return_arr;
    }

    public function get_plan_id($plan_code = '')
    {
        $this->CI->db->select('iSubscriptionMasterId');
        $this->CI->db->from('subscription_master');
        $this->CI->db->where('vZohoPlanCode', $plan_code);
        $data = $this->CI->db->get()->row_array();
        return $data['iSubscriptionMasterId'] > 0 ? $data['iSubscriptionMasterId'] : 0;
    }

    public function getAdminId($mode = '', $id = '', $parID = '')
    {
        $ret_arr = array();
        $ret_arr['success'] = true;
        if ($mode == 'Add')
        {
            $admin_id = $this->CI->session->userdata('iUsersId');
            $this->CI->db->select('vEmail,iDistributorUserId,vName');
            $this->CI->db->from('users');
            $this->CI->db->where('iUsersId', $admin_id);
            $this->CI->db->where('eStatus', 'Active');
            $this->CI->db->where('iDistributorUserId >', '0');
            $this->CI->db->where('iUserType', '3');
            $this->CI->db->where('eSalesPersonRole', 'Admin');
            $result_arr = $this->CI->db->get()->row_array();
            $ret_arr['success'] = true;
            if (!empty($result_arr))
            {
                $_POST['u_admin_id'] = $admin_id;
                return $ret_arr;
            }
        }
        return $ret_arr;
    }

    public function getProviderLegalCompanyName($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $this->CI->db->select('vLegalCompanyName');
        $this->CI->db->where('iUsersId', $data['dpm_provider_id']);
        $data = $this->CI->db->get('users')->first_row();
        return $data->vLegalCompanyName;
    }

    public function get_dist_email_template($email_code = '', $distributor_id = 0)
    {
        $this->CI->db->select('vEmailSubject as  det_email_subject');
        $this->CI->db->select('tEmailMessage as  det_email_message');
        $this->CI->db->select('vFromName as  det_from_name');
        $this->CI->db->select('vBccEmail as  det_bcc_email');
        $this->CI->db->select('vFromEmail as  det_from_email');
        $this->CI->db->select('vEmailCode as  det_email_code');
        $this->CI->db->where("vEmailCode", $email_code);
        $this->CI->db->where("iDistributoruserId", $distributor_id);
        $result = $this->CI->db->get('distributor_email_template')->result_array();
        return $result;
    }

    public function getDistPin($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        return $data['u_pin_number'];
    }

    public function getActiveButton($value = '', $id = '', $data = array())
    {
        if ($data['am_status'] == 'Inactive')
        {
            $button = "<div class='my_custom_row_btns'>";
            $button .= "<a class='btn btn_info custom-view-btn fa fa-toggle-on data-activate ".$data['iAffiliateId']."' data-dist='".$data['am_user_id']."' data-id = '".$data['iAffiliateId']."' data-mail='".$data['am_email']."' data-type='".$data['am_sales_person_category']."' title='Active' href='javascript://'></a>";
            $button .= "</div>";
        }
        else
        {
            $button = "<div class='my_custom_row_btns'>";
            $button .= "<a class='btn btn_info custom-view-btn fa fa-ban data-deactive ".$data['iAffiliateId']."' data-id = '".$data['iAffiliateId']."' data-mail='".$data['am_email']."' data-dist='".$data['am_user_id']."' data-type='".$data['am_sales_person_category']."' title='Deactive'  style='background:#e4010b;' href='javascript://'></a>";
            $button .= "</div>";
        }
        return $button;
    }

    public function generateFreeDistributorPin($input_params = array())
    {
        $pkg_arr = json_decode($input_params['package_arr'], true);
        $pkg_data = $this->getDistPackageData($pkg_arr['selected_package']);
        $return_arr = array();
        $dist_id = $input_params['distributor_id'] ? $input_params['distributor_id'] : $this->CI->session->userdata('iAdminId');
        if (strtolower($pkg_data['vZohoPlanCode']) == 'web_premium_free')
        {
            $return_arr[0]['free_pin'] = "1000".str_pad($dist_id, 4, "0", STR_PAD_LEFT);
        }
        else
        {
            $return_arr[0]['free_pin'] = "3000".str_pad($dist_id, 4, "0", STR_PAD_LEFT);
        }
        return $return_arr;
    }

    public function update_subscription_data_for_free_user($subscription_data = array(), $distributor_id = 0)
    {
        $update_arr = array(
            'vZohoReferenceCode' => $subscription_data['subscription']['reference_id'],
            'vZohoCustomerId' => $subscription_data['subscription']['customer']['customer_id'],
            'vZohoSubscriptionId' => $subscription_data['subscription']['subscription_id'],
        );
        $where = array(
            'iDistributorId' => $distributor_id,
        );
        $this->CI->db->where($where);
        $this->CI->db->update('distributor_subscription', $update_arr);
        return true;
    }

    public function getCurrentDistSubscriptionData($subscription_id = 0)
    {
        $this->CI->db->select('ds.*');
        $this->CI->db->from('distributor_subscription as ds');
        $this->CI->db->where('ds.vZohoSubscriptionId', $subscription_id);
        $data = $this->CI->db->get()->row_array();
        return $data;
    }

    public function update_user_pin_no($distributor_id = 0)
    {
        $new_pin = "1000".str_pad($distributor_id, 4, "0", STR_PAD_LEFT);
        $update_data = array(
            'iPinNumber' => $new_pin,
        );
        $where = array(
            'iUsersId' => $distributor_id,
        );
        $this->CI->db->where($where);
        $this->CI->db->update('users', $update_data);
        return true;
    }

    public function get_distributor_affiliate_data($distributor_id = 0)
    {
        $this->CI->db->select('adm.*,CONCAT(am.vName," ",am.vLastNames) as saleperson_name');
        $this->CI->db->from('affiliate_distributor_mapping as adm');
        $this->CI->db->join('affiliate_master as am', 'am.iAffiliateId=adm.iAffiliateId');
        $this->CI->db->where('adm.iDistributorUserId', $distributor_id);
        $data = $this->CI->db->get()->row_array();
        return $data;
    }

    public function generateAffiliateCode($mode = '', $id = '', $parID = '')
    {
        if ($mode == 'Add')
        {
            $this->CI->load->model('cit_api_model');
            $affiliate_code = "5000".str_pad($id, 4, "0", STR_PAD_LEFT);
            ;
            $affiliate_url = $this->CI->config->item("site_url").'partner/'.$affiliate_code;
            $distributer_url = $this->CI->config->item("site_url").'pricing/'.$affiliate_code;
            $params_arr_update = array(
                'affiliate_code' => $affiliate_code,
                'affiliate_url' => $affiliate_url,
                'distributor_url' => $distributer_url,
                'affiliate_id' => $id,
            );
            $data = $this->CI->cit_api_model->callAPI('affiliate_update', $params_arr_update);
            $ret_arr['success'] = true;
            return $ret_arr;
        }
    }

    public function sendAffiliatePassword($mode = '', $id = '', $parID = '')
    {
        if ($mode == 'Add')
        {
            $data = $this->CI->input->post();
            $data_arr = array(
                'vPassword' => md5('alyzta-affiliate-123')
            );
            $this->CI->db->where('iAffiliateId', $id);
            $this->CI->db->update('affiliate_master', $data_arr);
            $params = array(
                'password' => 'alyzta-affiliate-123',
                'email' => $data['am_email'],
            );
            $this->CI->load->model('cit_api_model');
            $value = $this->CI->cit_api_model->callApi('generate_password', $params);
            $this->CI->db->select('*');
            $this->CI->db->from('affiliate_master');
            $this->CI->db->where('iAffiliateId', $id);
            $result = $this->CI->db->get()->row_array();
            $this->insertDistribution($result);
        }
        $ret_arr['success'] = true;
        $ret_arr['message'] = 'Affiliate user added successfully';
        return $ret_arr;
    }

    public function insertDistribution($data = array())
    {
        $user_arr = array(
            'vName' => $data['vName'],
            'vContactNumber' => $data['vMobileNumber'],
            'vLastName' => $data['vLastNames'],
            'vCompanyName' => $data['vCompanyName'],
            'vLegalCompanyName' => $data['vCompanyName'],
            'vRFCTaxID' => $data['vRfcNumber'],
            'vEmail' => $data['vEmail'],
            'vPassword' => $data['vPassword'],
            'dAddedDate' => date('Y-m-d H:i:s'),
            'iUserType' => 1,
            'eIsEmailVerified' => 'Yes',
            'eSalesPersonRole' => 'SuperAdmin',
            'vInvoiceType' => 'Generic',
            'eHaveConfig' => 'No',
            'iCountryId' => 138,
            'eStatus' => 'Active',
        );
        $this->CI->db->insert('users', $user_arr);
        $u_insert_id = $this->CI->db->insert_id();
        $unique_code = "DIST-0000".$u_insert_id;
        $pin_number = '30000'.$u_insert_id;
        $update_arr = array(
            'iDistributorUserId' => $u_insert_id,
            'vUniqueID' => $unique_code,
            'iPinNumber' => $pin_number,
        );
        $this->CI->db->where('iUsersId', $u_insert_id);
        $this->CI->db->update('users', $update_arr);
        $dist_arr = array(
            'iDistributorId' => $u_insert_id,
            'iPackageId' => 16,
            'vPackageCode' => 'PARTNER_FREE',
            'dtStartDate' => date('Y:m:d'),
            'dtEndDate' => date('Y-m-d',
            strtotime('+2000 years')
        ), 'ePaymentStatus' => 'FREE', 'vZohoPlanCode' => 'PARTNER_FREE', 'iSubscriptionQuantity' => 1);
        $this->CI->db->insert('distributor_subscription', $dist_arr);
        $affiliate_arr = array(
            'iUserId' => $u_insert_id,
        );
        $this->CI->db->where('iAffiliateId', $data['iAffiliateId']);
        $this->CI->db->update('affiliate_master', $affiliate_arr);
    }

    public function update_user_invoice_data($params_arr = array())
    {
        $update_data = array(
            'vRFCTaxID' => $params_arr['rfc'],
            'vInvoiceType' => $params_arr['invoice_type'],
            'vInvoiceUse' => $params_arr['invoice_use'],
        );
        $where = array(
            'iUsersId' => $params_arr['distributor_id'],
        );
        $this->CI->db->where($where);
        $this->CI->db->update('users', $update_data);
        return true;
    }

    public function calculateAffiliateCommission($input_params = array())
    {
        $ret_array[0] = array(
            'commision_percentage' => 0,
            'commision' => 0,
            'balance' => 0,
            'parent_commision_percentage' => 0,
            'parent_commision_apply' => 0,
            'parent_balance' => 0,
            'commision_price' => 0,
        );
        $vat_price = 0;
        $commision_price = 0;
        $commision = 0;
        $parent_commision = 0;
        $payment_status = $input_params['payment_status'];
        $affiliate_status = $input_params['affiliate_subscription_partner'][0]['am_status'];
        $sales_person_category = $input_params['affiliate_subscription_partner'][0]['am_sales_person_category'];
        $is_upgrade = $input_params['affiliate_subscription_partner'][0]['adm_is_upgrade'];
        $purchase_date = $input_params['affiliate_subscription_partner'][0]['adm_package_purchase_date'];
        $package_type = $input_params['getplancodefromzohocode'][0]['sm_duration_type'];
        $commision_price = $input_params['total_price'];
        if (strtolower($payment_status) == 'paid' and $affiliate_status == 'Active')
        {
            if ($sales_person_category == 'Inhouse')
            {
                if ($package_type == 'Monthly')
                {
                    $commision_percentage = $this->CI->config->item('AFFILIATE_INHOUSE_COMMISION_MONTHLY');
                    $commision = (float) ($commision_price*$commision_percentage/100);
                }
                if ($package_type == 'Yearly')
                {
                    $commision_percentage = $this->CI->config->item('AFFILIATE_INHOUSE_COMMISION_YEARLY');
                    $commision = (float) ($commision_price*$commision_percentage/100);
                }
            }
            if ($sales_person_category == 'External')
            {
                if ($package_type == 'Monthly')
                {
                    $commision_percentage = $this->CI->config->item('AFFILIATE_EXTERNAL_COMMISION_MONTHLY');
                    $parent_percentage = $this->CI->config->item('AFFILIATE_PARENT_EXTERNAL_COMMISION_MONTHLY');
                    $commision = (float) ($commision_price*$commision_percentage/100);
                    $parent_commision = (float) ($commision_price*$parent_percentage/100);
                }
                if ($package_type == 'Yearly')
                {
                    $commision_percentage = $this->CI->config->item('AFFILIATE_EXTERNAL_COMMISION_YEARLY');
                    $parent_percentage = $this->CI->config->item('AFFILIATE_PARENT_EXTERNAL_COMMISION_YEARLY');
                    $commision = (float) ($commision_price*$commision_percentage/100);
                    $parent_commision = (float) ($commision_price*$parent_percentage/100);
                }
            }
        }
        if ($input_params['total_commision_credit'][0]['pc_commision'])
        {
            $credit = $input_params['total_commision_credit'][0]['pc_commision']-$input_params['total_commision_debit'][0]['pc_commision_1'];
        }
        else
        {
            $credit = 0;
        }
        $balance = $credit+$commision;
        if ($input_params['total_commision_credit_parent'][0]['pc_commision_3'])
        {
            $credit_parent = $input_params['total_commision_credit_parent'][0]['pc_commision_3']-$input_params['total_commision_debit_parent'][0]['pc_commision_4'];
        }
        else
        {
            $credit_parent = 0;
        }
        $parent_balance = $credit_parent+$parent_commision;
        if ($is_upgrade == 1 && $purchase_date != '')
        {
            $date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' -6 months'));
            if (($purchase_date > $date) && ($purchase_date < date('Y-m-d H:i:s')))
            {
                $ret_array[0] = array(
                    'commision_percentage' => $commision_percentage,
                    'commision' => $this->gettwodecimal($commision),
                    'balance' => $this->gettwodecimal($balance),
                    'parent_commision_percentage' => $parent_percentage,
                    'parent_commision_apply' => $this->gettwodecimal($parent_commision),
                    'parent_balance' => $this->gettwodecimal($parent_balance),
                    'commision_price' => $this->gettwodecimal($commision_price)
                );
            }
        }
        else
        {
            $ret_array[0] = array(
                'commision_percentage' => $commision_percentage,
                'commision' => $this->gettwodecimal($commision),
                'balance' => $this->gettwodecimal($balance),
                'parent_commision_percentage' => $parent_percentage,
                'parent_commision_apply' => $this->gettwodecimal($parent_commision),
                'parent_balance' => $this->gettwodecimal($parent_balance),
                'commision_price' => $this->gettwodecimal($commision_price)
            );
        }
        return $ret_array;
    }

    public function get_distributor_affiliate_data_update()
    {
        $this->CI->db->select('adm.iAffiliateId,adm.vAffiliateCode,am.iParentId');
        $this->CI->db->from('affiliate_distributor_mapping as adm');
        $this->CI->db->join('affiliate_master as am', 'am.iAffiliateId=adm.iAffiliateId');
        $this->CI->db->where('adm.iDistributorUserId', $distributor_id);
        $data = $this->CI->db->get()->row_array();
        return $data;
    }

    public function get_selected_package_id($input_params = array())
    {
        $this->CI->db->select('iSubscriptionMasterId');
        $this->CI->db->from('subscription_master');
        $this->CI->db->where('vZohoPlanCode', $input_params['package_id']);
        $pkg_data = $this->CI->db->get()->row_array();

        $this->CI->db->select('iSubscriptionMasterId');
        $this->CI->db->from('subscription_master');
        $this->CI->db->where('vZohoPlanCode', 'FREE07');
        $pkg_data_def = $this->CI->db->get()->row_array();

        $free_package_id = $pkg_data['iSubscriptionMasterId'] ? $pkg_data['iSubscriptionMasterId'] : $pkg_data_def['iSubscriptionMasterId'];
        $ret_arr = array();
        $ret_arr['free_package_id'] = $free_package_id;
        return $ret_arr;
    }

    public function getEmailResetButton($value = '', $id = '', $data = array())
    {
        $html = "<div class='my_custom_row_btns'>";
        $html .= "<a class='btn btn_info custom-view-btn view_btn reset_btn' data-id='".$data['det_distributor_email_template_id']."' data-code='".$data['det_email_code']."' title='Reiniciar'><i class='icomoon-icon-undo'></i></a>";
        $html .= "</div>";
        return $html;
    }

    public function wallet_filter($input_params = array())
    {
        if ($input_params['duration'] == 'Today')
        {
            $cond = "DATE(pc.dtAddedDate)=CURDATE()";
        }
        else
        if ($input_params['duration'] == '1 Week')
        {
            $cond = "pc.dtAddedDate >= DATE_SUB( CURDATE( ) , INTERVAL 7
DAY )";
        }
        else
        if ($input_params['duration'] == '2 Week')
        {
            $cond = "pc.dtAddedDate >= DATE_SUB( CURDATE( ) , INTERVAL 15
DAY )";
        }
        else
        if ($input_params['duration'] == '1 Month')
        {
            $cond = "pc.dtAddedDate >= DATE_SUB( CURDATE( ) , INTERVAL 1
MONTH )";
        }
        else
        if ($input_params['duration'] == '3 Months')
        {
            $cond = "pc.dtAddedDate >= DATE_SUB( CURDATE( ) , INTERVAL 3
MONTH )";
        }
        else
        if ($input_params['duration'] == '6 Months')
        {
            $cond = "pc.dtAddedDate >= DATE_SUB( CURDATE( ) , INTERVAL 6
MONTH )";
        }
        else
        if ($input_params['duration'] == 'Custom Date')
        {
            $cond = "DATE(pc.dtAddedDate) BETWEEN '".$input_params['start_date']."' AND '".$input_params['end_date']."'";
        }
        else
        {
            $cond = '1=1';
        }
        $cond .= " AND `pc`.`eStatus`='Active' AND ((`pc`.`iAffiliateId` =".$input_params['affiliate_id']."  AND `pc`.`ePartnerCommision`='No') OR (`pc`.`iParentId` =".$input_params['affiliate_id']." AND `pc`.`ePartnerCommision`='Yes'))";
        $return_arr['extra_cond'] = $cond;

        return $return_arr;
    }

    public function affiliate_dashboard_data_calculate($input_params = array())
    {
        $new_sales = 0;
        $updated_sales = 0;
        $commision = 0;
        $total_sales = 0;
        if (isset($input_params['affiliate_commision_data']))
        {
            $data = $input_params['affiliate_commision_data'];
        }
        else
        {
            $data = $input_params['affiliate_commision_data_v1'];
        }
        foreach ($data as $value)
        {
            if ($value['pc_sales_type'] == 'New')
            {
                $new_sales = (float) $new_sales+$value['pc_package_price'];
            }
            else
            {
                $updated_sales = (float) $updated_sales+$value['pc_package_price'];
            }
            $commision = (float) $commision+$value['pc_commision'];
        }
        $total_sales = (float) $new_sales+$updated_sales;
        $return_arr[0] = array(
            'new_sales' => $new_sales,
            'upgrade_sales' => $updated_sales,
            'total_sales' => $total_sales,
            'commision' => $commision,
        );
        return $return_arr;
    }

    public function associate_dashboard_data_calculate($input_params = array())
    {
        if ($input_params['associates_commision_data'])
        {
            $data_check = $input_params['associates_commision_data'];
        }
        else
        {
            $data_check = $input_params['associates_commision_data_v1'];
        }
        for ($i = 0; $i < count($data_check) ;
        $i++)
        {
            $new_sales = 0;
            $updated_sales = 0;
            $commision = 0;
            $total_sales = 0;
            for ($j = 0; $j < count($data_check) ;
            $j++)
            {
                if ($data_check[$j]['pc_affiliate_id'] == $data_check[$i]['pc_affiliate_id'])
                {
                    if ($data_check[$j]['pc_sales_type_1'] == 'New')
                    {
                        $new_sales = (float) $new_sales+$data_check[$j]['pc_package_price_1'];
                    }
                    else
                    if ($data_check[$j]['pc_sales_type_1'] == 'Upgrade')
                    {
                        $updated_sales = (float) $updated_sales+$data_check[$j]['pc_package_price_1'];
                    }
                    $commision = (float) $commision+$data_check[$j]['pc_commision_1'];
                }
            }
            $total_sales = (float) $new_sales+$updated_sales;
            $return_arr[$data_check[$i]['pc_affiliate_id']] = array(
                'first_name' => $data_check[$i]['am_name_1'],
                'last_name' => $data_check[$i]['am_last_names_1'],
                'new_sales' => $new_sales,
                'upgrade_sales' => $updated_sales,
                'total_sales' => $total_sales,
                'commision' => $commision,
            );
        }
        $return_arr = array_values($return_arr);
        return $return_arr;
    }

    public function dashboard_commision_filter($input_params = array())
    {
        if (isset($input_params['current_month']) && isset($input_params['current_year']))
        {
            $cond = "YEAR(pc.dtAddedDate)=".$input_params['current_year']." AND MONTH(pc.dtAddedDate)=".$input_params['current_month'];
        }
        $return_arr['extra_cond'] = $cond;
        return $return_arr;
    }

    public function get_general_data($table_name = '', $fields = array(), $where = array())
    {
        $this->CI->db->select($fields);
        $this->CI->db->from($table_name);
        $this->CI->db->where($where);
        $data = $this->CI->db->get()->result_array();
        return $data;
    }

    public function update_general($table_name = '', $data_arr = array(), $where = array())
    {
        $this->CI->db->where($where);
        $this->CI->db->update($table_name, $data_arr);
        return ($this->CI->db->affected_rows() > 0) ? true : false;
    }

    public function addNewLead($params = array(), $owner_data = array())
    {
        $this->CI->load->library('zohocrmv2');
        $new_lead_data = $this->CI->zohocrmv2->addLeadCurl($params, $owner_data);
        return $new_lead_data;
    }

    public function addNewOpportunity($params = array(), $stage = '', $lead_arr = array())
    {
        $this->CI->load->library('zohocrmv2');
        $new_lead_data = $this->CI->zohocrmv2->addOpportunityCurl($params, $stage, $lead_arr);
        return $new_lead_data;
    }

    public function updateRecord($params = array(), $lead_data = array(), $stage = '', $update_arr = array(), $estado = '')
    {
        $this->CI->load->library('zohocrmv2');
        $new_lead_data = $this->CI->zohocrmv2->updateOpportunityCurl($params, $lead_data, $stage, $update_arr, $estado);
        return $new_lead_data;
    }

    public function checkLeadStatus($data = array())
    {
        $this->CI->load->library('zohocrmv2');
        $search_data = $this->CI->zohocrmv2->searchLeadCurl($data);
        $ret_arr = array();
        if (isset($search_data['data']) && !empty($search_data['data']))
        {
            $ret_arr['lead_status'] = true;
            $ret_arr['lead_data'] = $search_data['data'][0];
        }
        else
        {
            $ret_arr['lead_status'] = false;
            $ret_arr['lead_data'] = $search_data['data'] ? $search_data['data'][0] : "No response";
        }
        return $ret_arr;
    }

    public function checkContactsStatus($data = array())
    {
        $this->CI->load->library('zohocrmv2');
        $search_data = $this->CI->zohocrmv2->searchContactCurl($data);
        $ret_arr = array();
        if (isset($search_data['data']) && !empty($search_data['data']))
        {
            $ret_arr['lead_status'] = true;
            $ret_arr['lead_data'] = $search_data['data'][0];
        }
        else
        {
            $ret_arr['lead_status'] = false;
            $ret_arr['lead_data'] = $search_data['data'] ? $search_data['data'][0] : "No response";
        }
        return $ret_arr;
    }

    public function checkOpprtunityStatus($data = array())
    {
        $this->CI->load->library('zohocrmv2');
        $search_data = $this->CI->zohocrmv2->searchOpportunityCurl($data);
        $ret_arr = array();
        if (isset($search_data['data']) && !empty($search_data['data']))
        {
            $ret_arr['lead_status'] = true;
            $ret_arr['lead_data'] = $search_data['data'][0];
        }
        else
        {
            $ret_arr['lead_status'] = false;
            $ret_arr['lead_data'] = $search_data['data'] ? $search_data['data'][0] : "No response";
        }
        return $ret_arr;
    }

    public function convertLead($post_data = array(), $lead_data = array(), $stage = '', $plan_type = '', $package_data = array())
    {
        $this->CI->load->library('zohocrmv2');
        $lead_converted_data = $this->CI->zohocrmv2->convertLeadCurl($post_data, $lead_data, $stage, $plan_type, $package_data);
        return $lead_converted_data;
    }

    public function getInhouseAffiliateData($post_data = array(), $lead_data = array())
    {
        $this->CI->db->select('am.*');
        $this->CI->db->from('affiliate_master as am');
        $this->CI->db->where('am.vCrmId', $lead_data['owner_id']);
        $this->CI->db->where('am.eSalesPersonCategory', 'Inhouse');
        $affiliate_data = $this->CI->db->get()->row_array();
        return $affiliate_data;
    }

    public function getCrmLeadDataById($lead_id = 0)
    {
        $this->CI->load->library('zohocrmv2');
        $lead_converted_data = $this->CI->zohocrmv2->getCrmDataByIdcurl($lead_id);
        $ret_arr = array();
        if (isset($lead_converted_data['data'][0]) && !empty($lead_converted_data['data'][0]))
        {
            $ret_arr['lead_status'] = true;
            $ret_arr['lead_data'] = $lead_converted_data['data'][0];
        }
        else
        {
            $ret_arr['lead_status'] = false;
            $ret_arr['lead_data'] = $lead_converted_data['data'][0] ? $lead_converted_data['data'][0] : array();
        }
        return $ret_arr;
    }

    public function getInhouseSalesmanFromZoho($user_data = array())
    {
        foreach ($user_data['users']['user'] as $key => $value)
        {
            $this->CI->db->select('vEmail');
            $this->CI->db->from('affiliate_master');
            $this->CI->db->where('vEmail', $value['email']);
            $result = $this->CI->db->get()->result_array();
            if (empty($result))
            {
                $name = explode(' ', $value['content']);
                $params = array(
                    'eSalesPersonCategory' => 'Inhouse',
                    'eStatus' => 'Active',
                    'eAdminStatus' => 'Active',
                    'ePartnerStatus' => 'Active',
                    'dtAddedDate' => date('Y-m-d H:i:s'),
                    'iModifiedByUserId' => date('Y-m-d H:i:s'),
                    'vEmail' => $value['email'],
                    'vName' => $name[0] ? $name[0] : '',
                    'vLastNames' => $name[1] ? $name[1] : '',
                    'vZuId' => $value['zuid'],
                    'vCrmId' => $value['id'],
                    'vCrmRole' => $value['role'],
                    'vPassword' => md5('alyzta-affiliate-123')
                );
                $this->CI->db->insert('affiliate_master', $params);
                $insert_id = $this->CI->db->insert_id();
                $affiliate_code = "5000".str_pad($insert_id, 4, "0", STR_PAD_LEFT);
                $affiliate_url = $this->CI->config->item("site_url").'partner/'.$affiliate_code;
                $distributer_url = $this->CI->config->item("site_url").'pricing/'.$affiliate_code;
                $params_arr_update = array(
                    'vAffiliateCode' => $affiliate_code,
                    'vAffiliateUrl' => $affiliate_url,
                    'vDistributerUrl' => $distributer_url,
                );
                $where = 'iAffiliateId='.$insert_id;
                $this->CI->db->update('affiliate_master', $params_arr_update, $where);
                $this->sendAffiliateMail($params, $params_arr_update);
            }
            $this->CI->db->select('vEmail');
            $this->CI->db->from('users');
            $this->CI->db->where('vEmail', $value['email']);
            $result_dist = $this->CI->db->get()->result_array();
            if (empty($result_dist))
            {
                $params_dist = array(
                    'eStatus' => 'Active',
                    'vEmail' => $value['email'],
                    'vName' => $name[0],
                    'vLastName' => $name[1],
                    'vPassword' => md5('alyzta-affiliate-123'),
                    'iPinNumber' => $affiliate_code,
                    'iUserType' => 1,
                    'dAddedDate' => date('Y-m-d H:i:s'),
                    'eSalesPersonRole' => 'Admin',
                    'eIsEmailVerified' => 'Yes',
                    'vPassword' => md5('alyzta-affiliate-123')
                );
                $this->CI->db->insert('users', $params_dist);
                $dist_id = $this->CI->db->insert_id();
                $params_dist_sub = array(
                    'iDistributorId' => $dist_id,
                    'iPackageId' => 16,
                    'vPackageCode' => 'PARTNER_FREE',
                    'dtStartDate' => date('Y-m-d H:i:s'),
                    'dtEndDate' => date('Y-m-d H:i:s',
                    strtotime('+2000 years')
                ), 'ePaymentStatus' => 'Free', 'vZohoPlanCode' => 'PARTNER_FREE', 'eAutoRenew' => 'Yes');
                $this->CI->db->insert('distributor_subscription', $params_dist_sub);
                $affiliate_arr = array(
                    'iUserId' => $dist_id,
                );
                $this->CI->db->where('iAffiliateId', $insert_id);
                $this->CI->db->update('affiliate_master', $affiliate_arr);
            }
        }
    }

    public function getAffiliateType($aff_code = '')
    {
        $this->CI->db->select('am.*');
        $this->CI->db->from('affiliate_master as am');
        $this->CI->db->where('am.vAffiliateCode', $aff_code);
        $data = $this->CI->db->get()->row_array();
        return $data;
    }

    public function getCouponSelector($value = '', $id = '', $data = array())
    {
        $this->CI->db->select('cm.*');
        $this->CI->db->from('coupon_master as cm');
        $this->CI->db->where('cm.eStatus', 'Active');
        $coupon_data = $this->CI->db->get()->result_array();
        $html = "<div class='coupon-container'>";
        $html .= "<div class='coupon-button-container'>";
        if ($data['am_coupon_id'] > 0 && $data['cm_coupon_code'] != '')
        {
            $html .= "<a href='javascript://' name='btn_edit_coupon' class='wp_edit_coupon' data-id='".$id."' data-mode='Update' data-coupon='".$data['cm_coupon_code']."'><i class='fa fa-edit hd-couponicon'></i> ".$data['cm_coupon_code']."</a></div>";
        }
        else
        {
            $html .= "<a href='javascript://' name='btn_add_coupon' class='wp_add_coupon' data-id='".$id."' data-mode='Add'><i class='fa fa-plus hd-couponicon'></i> Agregar cupón</a></div>";
        }
        $html .= "<div class='coupon-action-container' style='display:none;'>";
        $html .= "<select class='sel-coupon'>";
        $html .= "<option selected='' disabled=''>Elige un cupón</option>";
        $html .= "<option value='0'>Eliminar cupón</option>";
        foreach ($coupon_data as $key => $value)
        {
            $select = "";
            if ($data['am_coupon_id'] == $value['iCouponMasterId'])
            {
                $select = "selected='selected'";
            }
            $html .= "<option value='".$value['iCouponMasterId']."' ".$select.">".$value['vCouponCode']."</option>";
        }
        $html .= "</select></div>";
        $html .= "</div>";
        return $html;
    }

    public function sendAffiliateMail($aff_data = array(), $aff_updated_data = array())
    {
        if ($aff_data['eSalesPersonCategory'] == 'Inhouse')
        {
            $send_mail_arr = array(
                'vName' => $aff_data['vName'],
                'vEmail' => $aff_data['vEmail'],
                'vUserEmail' => $aff_data['vEmail'],
                'vUserName' => $aff_data['vEmail'],
                'vPassword' => 'alyzta-affiliate-123',
                'distributor_url' => $aff_updated_data['vDistributerUrl'],
            );
            $this->sendMail($send_mail_arr, 'AFFILIATE_V1');
        }
        return true;
    }

    public function getCurrentUserId($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        if (strpos($field_name, 'added_by') !== false && $mode == 'Update')
        {
            return $value;
        }
        $iAdminid = $this->CI->session->userdata("iAdminId");
        return $iAdminid;
    }

    public function SendMailToCheckSMTP($mode = '', $id = '', $parID = '')
    {
        $post_arr = $this->CI->input->post();
        //print_r ($post_arr);
        //die;
        $this->CI->db->select('dsm.iDistributorSmtpMasterId');
        $this->CI->db->from('distributor_smtp_master as dsm');
        $this->CI->db->where('dsm.iDistributorUserId', $this->CI->session->userdata('iAdminId'));
        $this->CI->db->where('dsm.eStatus!="Verified"');
        $old_smtp_data = $this->CI->db->get()->row_array();
        if (!empty($old_smtp_data))
        {
            $update_arr = array(
                'eStatus' => 'Pending',
            );
            $where = array(
                'iDistributorSmtpMasterId' => $old_smtp_data['iDistributorSmtpMasterId'],
            );
            $this->CI->db->where($where);
            $this->CI->db->update('distributor_smtp_master', $update_arr);
        }
        $this->CI->load->model('cit_api_model');
        $this->CI->db->select('vEmailSubject as  det_email_subject');
        $this->CI->db->select('tEmailMessage as  det_email_message');
        $this->CI->db->select('vFromName as  det_from_name');
        $this->CI->db->select('vBccEmail as  det_bcc_email');
        $this->CI->db->select('vFromEmail as  det_from_email');
        $this->CI->db->select('vEmailCode as  det_email_code');
        $this->CI->db->where("vEmailCode", 'TEST_SMTP_MAIL');
        $this->CI->db->where("iDistributorUserId", $this->CI->session->userdata('iAdminId'));
        $dist_email_temps = $this->CI->db->get('distributor_email_template')->result_array();
        $params = array();
        $params['distributor_id'] = $this->CI->session->userdata('iAdminId');
        $dist_company_details = $this->CI->cit_api_model->callAPI('distributor_details', $params);
        if ($dist_company_details['settings']['success'] == 1)
        {
            $dist_company_details = $dist_company_details['data'][0];
        }
        $input_params = array();
        $input_params['contact_us_eamil_templates'] = $dist_email_temps;
        $input_params['distributor_name'] = $this->CI->session->userdata('vName');
        $input_params['email'] = $this->CI->session->userdata('vEmail');
        $input_params['distributor_logo'] = $dist_company_details['u_company_logo'];
        $input_params['distributor_company_email'] = $dist_company_details['u_company_email'];
        $input_params['from_email'] = $this->CI->input->post('dsm_smtp_email');
        $input_params['distributor_company_name'] = $dist_company_details['u_company_name'];
        $input_params['distributor_company_contact_number'] = $dist_company_details['u_contact_number'];
        $input_params['distributor_name'] = $dist_company_details['u_name'];
        $sent_mail = false;
        //if($post_arr['dsm_visibility_status']=='Active'){
            $sent_mail = $this->send_distributor_email($input_params);
        //}
        $success = false;
        $message = "Algo salió mal";
        if($post_arr['dsm_visibility_status']=='Active'){
            if ($sent_mail == true)
            {
                $update_arr = array(
                    'eStatus' => 'Verified',
                );
                $success = true;
                $message = "La configuración de SMTP se configuró correctamente. Recibirá un correo electrónico de prueba desde su dirección de correo electrónico configurada.";
            }
            else
            {
                $success = false;
                $message = "La configuración de SMTP falló. Verifique sus datos o consulte con su proveedor de servicios.";
                $update_arr = array(
                    'eStatus' => 'Failed',
                    'eVisibilityStatus'=>'Inactive'
                );
            }
            $where = array(
                'iDistributorUserId' => $this->CI->session->userdata('iAdminId')
            );
            $this->CI->db->where($where);
            $this->CI->db->update('distributor_smtp_master', $update_arr);
        } else {
            $success = true;
            $message = 'Tus configuraciones están guardadas Para hacer uso de él, márquelo como "Activo"';
        }
        $ret_arr = array();
        $ret_arr['success'] = $success;
        $ret_arr['message'] = $message;
        return $ret_arr;
    }

    public function getUserAgentDetails()
    {
        $send_arr = array();
        $ret_arr = array();
        $iPod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
        if ($iPad || $iPhone || $iPod)
        {
            $mobile_os = 'IOS';
        }
        else
        if ($android)
        {
            $mobile_os = 'ANDROID';
        }
        else
        {
            $mobile_os = 'PC';
        }
        $ret_arr['mobile_os'] = $mobile_os;
        $this->CI->load->library('user_agent');
        if ($this->CI->agent->is_robot())
        {
            $ret_arr['is_robot'] = 'Yes';
        }
        else
        {
            $ret_arr['is_robot'] = 'No';
        }
        if ($this->CI->agent->is_mobile())
        {
            $ret_arr['platform'] = $this->CI->agent->platform();
            $ret_arr['user_agent'] = 'Mobile';
        }
        else
        {
            $ret_arr['platform'] = $this->CI->agent->platform();
            $ret_arr['user_agent'] = 'Web';
        }
        if ($this->CI->agent->is_referral())
        {
            $ret_arr['referer_name'] = $this->CI->agent->referrer();
        }
        if ($this->CI->agent->is_browser())
        {
            $ret_arr['agent'] = $this->CI->agent->browser().' '.$this->CI->agent->version().'('.$this->CI->agent->agent_string().')';
        }
        elseif ($this->CI->agent->is_robot())
        {
            $ret_arr['agent'] = $this->CI->agent->robot();
        }
        elseif ($this->CI->agent->is_mobile())
        {
            $ret_arr['agent'] = $this->CI->agent->mobile();
        }
        else
        {
            $ret_arr['agent'] = 'Unidentified User Agent';
        }

        $send_arr[0]['agent_info'] = json_encode($ret_arr);
        return $send_arr;
    }

    public function getDaysAgo($input = '')
    {
        $msg = '';
        $last_update = trim($input);
        $server_date_time = date('Y-m-d H:i:s');
        if ($this->validateDate($last_update, 'Y-m-d H:i:s'))
        {
            $last_update = date('Y-m-d H:i:s', strtotime($last_update));
            $date1 = date_create($server_date_time);
            $date2 = date_create($last_update);
            $log = date_diff($date1, $date2);
            if (is_object($log))
            {
                if ($log->days < 1)
                {
                    if ($log->h < 1)
                    {
                        if ($log->i < 1)
                        {
                            $msg = $log->s.' sec'.(($log->s > 1) ? 's' : '').' ago';
                        }
                        else
                        {
                            $msg = $log->i.' min'.(($log->i > 1) ? 's' : '').' ago';
                        }
                    }
                    else
                    {
                        $msg = $log->h.' hour'.(($log->h > 1) ? 's' : '').' ago';
                    }
                }
                elseif ($log->days <= 31 && $log->m == 0)
                {
                    $msg = $log->days.' day'.(($log->days > 1) ? 's' : '').' ago';
                }
                elseif ($log->m > 0 && $log->y == 0)
                {
                    $msg = $log->m.' month'.(($log->m > 1) ? 's' : '').' ago';
                }
                else
                {
                    $msg = $log->y.' year'.(($log->y > 1) ? 's' : '').' ago';
                }
            }
        }
        return $msg;
    }

    public function getEntityUrl($entity, $id, $mode)
    {
        $admin_url = $this->CI->config->item('admin_url');
        $mode = trim($mode);
        $id = trim($id);
        switch ($entity)
        {
            case 'users':
                $url = $admin_url."#user/admin/add|mode|".$mode."|id|".$id;
                break;
            case 'distributor':
                $url = $admin_url."#user/distributor/add|mode|".$mode."|id|".$id;
                break;
            default:
                $url = 'javascript://';
        }
        return $url;
    }

    public function validateDate($date = '', $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d AND $d->format($format) == $date;
    }

    public function transaction_filter($input_params = array())
    {
        if ($input_params['duration'] == 'Today')
        {
            $cond = "DATE(pc.dtAddedDate)=CURDATE()";
        }
        else
        if ($input_params['duration'] == '1 Week')
        {
            $cond = "pc.dtAddedDate >= DATE_SUB( CURDATE( ) , INTERVAL 7
DAY )";
        }
        else
        if ($input_params['duration'] == '2 Week')
        {
            $cond = "pc.dtAddedDate >= DATE_SUB( CURDATE( ) , INTERVAL 15
DAY )";
        }
        else
        if ($input_params['duration'] == '1 Month')
        {
            $cond = "pc.dtAddedDate >= DATE_SUB( CURDATE( ) , INTERVAL 1
MONTH )";
        }
        else
        if ($input_params['duration'] == '3 Months')
        {
            $cond = "pc.dtAddedDate >= DATE_SUB( CURDATE( ) , INTERVAL 3
MONTH )";
        }
        else
        if ($input_params['duration'] == '6 Months')
        {
            $cond = "pc.dtAddedDate >= DATE_SUB( CURDATE( ) , INTERVAL 6
MONTH )";
        }
        else
        if ($input_params['duration'] == 'Custom Date')
        {
            $cond = "DATE(pc.dtAddedDate) BETWEEN '".$input_params['start_date']."' AND '".$input_params['end_date']."'";
        }
        else
        {
            $cond = '1=1';
        }
        $cond .= " AND `pc`.`iAffiliateId` =".$input_params['affiliate_id'];
        $return_arr['extra_cond'] = $cond;

        return $return_arr;
    }

    public function getAffilateActionButtons($value = '', $id = '', $data = array())
    {
        $status = $data['aw_status'];
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn view_btn fancybox-popup' href='".$this->CI->config->item('admin_url')."#misc/affiliate_withdraw/add|mode|View|id|".$id."' title='Affliate View'><i class='icon18 iconic-icon-eye'></i></a>";
        if ($status == 'Pending' || $status == 'Inprogress' || $status == 'Inactive')
        {
            $button .= '<a hijacked="yes" class="btn btn_info custom-view-btn view_btn fancybox-popup" href="'.$this->CI->config->item('admin_url').'#misc/affiliate_with_draw_status_change/add|mode|Update|id|'.$id.'|hideCtrl|true|loadGrid|list2" title="Affliate Status Change"><i class="icomoon-icon-checkmark-2"></i></a>';
        }
        if ($status == 'Pending' || $status == 'Inprogress')
        {
            $button .= '<a class="btn btn_info custom-view-btn view_btn btn_inactive" date-id=
'.$id.' title="Affliate Mark In Active"><i class="icomoon-icon-cancel-3"></i></a>';
            $button .= "</div>";
        }

        return $button;
    }

    public function getWithDrawStatus($value = '', $id = '', $data = array())
    {
        $status = strtolower($value);
        $html = '<span class="withdraw-status withdraw-'.$status.'" >'.$value.'</span>';
        return $html;
    }

    public function getQuoteCount($dist_id = 0, $quote_id = 0)
    {
        $this->CI->db->select('dqt.dtAddedDate,dqt.iQuoteCount,dqt.iSampleCount');
        $this->CI->db->from('distributor_quote_transaction as dqt');
        $this->CI->db->where('dqt.iDistributorUserId', $dist_id);
        $dist_quote_trans = $this->CI->db->get()->row_array();
        return $dist_quote_trans;
    }

    public function getDefaultStatus($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $status = $data['aw_status'];
        if ($status == 'Inprogress')
        {
            $status = 'Inprogress';
        }
        else
        {
            $status = 'Active';
        }
        return $status;
    }

    public function getNumberFormat($input = '')
    {
        $number = trim($input);
        if ($number == '' || $number <= 0)
        {
            return '0';
        }
        else
        {
            $number = number_format($number, 2);
            return $number;
        }
    }

    public function getZohoCrmOwnerName()
    {
        $this->CI->load->library('zohocrmv2');
        $all_users = $this->CI->zohocrmv2->crmInhousecurl();
        $owner_data = array();
        foreach ($all_users['users'] as $key => $value)
        {
            if (strtolower(trim($value['email'])) == strtolower(trim($this->CI->config->item('CRM_OWNER_EMAIL'))))
            {
                $owner_data = $value;
            }
        }
        return $owner_data;
    }

    public function getCurrentSMTPStatus($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $status = '';
        $text = '';
        if (strtolower($value) == 'pending')
        {
            $text = 'Pendiente';
        }
        else
        if (strtolower($value) == 'failed')
        {
            $text = 'Ha fallado';
        }
        else
        if (strtolower($value) == 'verified')
        {
            $text = 'Verificado';
        }
        else
        {
            $text = $value;
        }
        $status = '<div class="smtp-status-'.strtolower($value).'-container"><span class="smtp-status-text">'.$text.'</span></div>';
        return $status;
    }

    public function check_is_premium($name = array())
    {
        $this->CI->db->select('mam.iAdminMenuId,mam.vUniqueMenuCode,sf.vSubscriptionCode');
        $this->CI->db->from('mod_admin_menu as mam');
        $this->CI->db->join('subscription_features as sf','mam.iAdminMenuId=sf.iMenuId');
        $this->CI->db->where('mam.vUniqueMenuCode',$name['code']);
        $this->CI->db->where('sf.eStatus','Active');
        $this->CI->db->where('(sf.vSubscriptionCode = "PREMIUM" OR sf.vSubscriptionCode = "WEB_PROMOTIONAL_PREMIUM")');
        $features = $this->CI->db->get()->result_array();
        $is_premium = false;
        if (!empty($features) AND count($features) >= 1)
        {
            $is_premium = true;
        }
        return $is_premium;
    }

    public function getProductsForVirtualSample()
    {
        //

    }

    public function getPrintingStatus($value = '', $id = '', $data = array())
    {
        $status = strtolower($value);
        $html = '<span class="printing-status printing-'.$status.'" >'.$value.'</span>';
        return $html;
    }

    public function getViewButtonForPrintShop($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#user/print_shop_request/printshop_detail_view|id|".$data['iUsersId']."'><i class='icon18 iconic-icon-eye'></i></a>";
        $button .= "</div>";
        return $button;
    }

    public function generatePrintShopPin($input_params = array())
    {
        $return_arr = array();
        $return_arr[0]['pin'] = "6000".str_pad($input_params['provider_id'], 4, "0", STR_PAD_LEFT);
        return $return_arr;
    }

    public function get_print_shop_default_policies($input_params = array())
    {
        $this->generatePrintShopPin("Update", $input_params['provider_id'], 0);

        $return_arr[] = array(
            "name" => "Precios",
            "description" => "Todos los precios son mas IVA. Precios sujetos a cambio sin previo aviso",
        );
        $return_arr[] = array(
            "name" => "Entregas",
            "description" => "Entregas en nuestro domicilio. Preguntar por envíos a domicilio",
        );
        $return_arr[] = array(
            "name" => "Garantías",
            "description" => "",
        );
        $return_arr[] = array(
            "name" => "Otros",
            "description" => "",
        );

        return $return_arr;
    }

    public function saveInvoiceStampingData($inv_stmpg_data = array(), $api_resp = array(), $status = '')
    {
        $this->CI->db->select('iDistributorSubscriptionInvoiceId');
        $this->CI->db->from('distributor_subscription_invoice');
        $this->CI->db->where('vZohoInvoiceId', $zoho_invoice_id);
        $data = $this->CI->db->get()->row_array();
        $success = false;
        $where = array();
        $iDistributorSubscriptionInvoiceId = 0;
        if (!empty($data))
        {
            $iDistributorSubscriptionInvoiceId = $data['iDistributorSubscriptionInvoiceId'];
        }
        $where = array(
            'iDistributorSubscriptionInvoiceId',
            $iDistributorSubscriptionInvoiceId,
        );
        if (!empty($inv_stmpg_data))
        {
            $update_arr = array(
                'vResponseXmlId' => $inv_stmpg_data['response_xml_id'],
                'vResponsePdfId' => $inv_stmpg_data['response_pdf_id'],
                'vZohoStampingStatus' => $status,
            );
            $this->CI->db->where($where);
            $this->CI->db->update("distributor_subscription_invoice", $update_arr);
            $success = true;
        }
        return $success;
    }

    public function getViewButtonForProviderContact($value = '', $id = '', $data = array())
    {
        $inquiry_id = $data['iDistributorContactusMasterId'];
        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a title='Ver' class='btn btn_info custom-view-btn view_btn fancybox-hash-iframe' href='".$this->CI->config->item('admin_url')."#distributor/provider_contact_us/add|mode|View|id|".$inquiry_id."|hideCtrl|true|width|75%|height|75%'><i class=' icon18 iconic-icon-eye'></i></a>";
        $button .= "</div>";
        return $button;
    }

    public function get_printshop_default_policies($input_params = array())
    {
        $this->generatePrintshopPin("Update", $input_params['provider_id'], 0);

        $return_arr[] = array(
            "name" => "Precios",
            "description" => "Todos los precios son mas IVA. Precios sujetos a cambio sin previo aviso",
        );
        $return_arr[] = array(
            "name" => "Entregas",
            "description" => "Entregas en nuestro domicilio. Preguntar por envíos a domicilio",
        );
        $return_arr[] = array(
            "name" => "Garantías",
            "description" => "",
        );
        $return_arr[] = array(
            "name" => "Otros",
            "description" => "",
        );

        return $return_arr;
    }

    public function hideActionColumn($data = array())
    {
        $data['child_assoc_access']['distributor_references']['actions'] = 0;
        return $data;
    }

    public function getDistributorVerificationActionButton($value = '', $id = '', $data = array())
    {
        $html = '';
        $html .= '<div class="my_custom_row_btns"><a class="btn btn_info custom-view-btn view_btn approve_verification_btn" href="javascript://" data-id="'.$id.'" data-userid="'.$data['udv_distributor_user_id'].'" title="Approve"><i class="icomoon-icon-checkmark-2"></i></a></div>';
        return $html;
    }

    public function getUserIdForVerification($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $user_id = 0;
        $is_admin = $this->CI->config->item('is_admin');
        if ($is_admin == true)
        {
            $user_id = $value;
        }
        else
        {
            $user_id = $this->CI->session->userdata('iAdminId');
        }
        return $user_id;
    }

    public function getAffiliateCurrentBalance($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $affiliate_id = $data['pc_affiliate_id'];
        $this->CI->db->select('SUM(vCommision) as credit');
        $this->CI->db->from('partner_commision');
        $this->CI->db->where('eStatus', 'Active');
        $this->CI->db->where('iAffiliateId', $affiliate_id);
        $this->CI->db->where('eTransactionType', 'Credit');
        $obj_data = $this->CI->db->get();
        $cr_data = is_object($obj_data) ? $obj_data->result_array() : array();
        $this->CI->db->select('SUM(vCommision) as debit');
        $this->CI->db->from('partner_commision');
        $this->CI->db->where('eStatus', 'Active');
        $this->CI->db->where('iAffiliateId', $affiliate_id);
        $this->CI->db->where('eTransactionType', 'Debit');
        $obj_data = $this->CI->db->get();
        $db_data = is_object($obj_data) ? $obj_data->result_array() : array();
        $balance = 0;
        $credit_amt = $cr_data[0]['credit'] ? $cr_data[0]['credit'] : 0;
        $debit_amt = $db_data[0]['debit'] ? $db_data[0]['debit'] : 0;
        if ($credit_amt > 0 || $debit_amt > 0)
        {
            $balance = $credit_amt-$debit_amt;
        }
        return $balance;
    }

    public function updateAffiliateBalance($mode = '', $id = '', $parID = '')
    {
        $post_arr = $this->CI->input->post();
        $balance = 0;
        $current_balance = $post_arr['current_balance'];
        $amount = $post_arr['pc_commision'];
        if (strtolower($post_arr['pc_transaction_type']) == 'credit')
        {
            $balance = $current_balance+$amount;
        }
        else
        {
            $balance = $current_balance-$amount;
        }
        $_POST['pc_balance'] = $balance;
        $return_arr = array();
        $return_arr['success'] = true;
        return $return_arr;
    }

    public function getVerificationNoticeText()
    {
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $note_html = '';
        $this->CI->db->select('udv.eVerificationStatus');
        $this->CI->db->from('user_distributor_verification as udv');
        $this->CI->db->where('udv.iDistributorUserId', $distributor_id);
        $data = $this->CI->db->get()->row_array();
        if (empty($data) || $data['eVerificationStatus'] == 'No')
        {
            $verify_url = $this->CI->config->item('distributor_url')."distributor/subscriptions/index";
            $note_html = "Debido a que esta es una plataforma de Prueba, los precios de los productos NO son reales, para ver precios reales <a href='".$verify_url."'>modifica tu suscripción aqui</a>";
        }
        $note_html = "";
        return $note_html;
    }

    public function getVerificationNoticeTextSingle()
    {
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $text = '';
        $this->CI->db->select('udv.eVerificationStatus');
        $this->CI->db->from('user_distributor_verification as udv');
        $this->CI->db->where('udv.iDistributorUserId', $distributor_id);
        $data = $this->CI->db->get()->row_array();
        if (empty($data) || $data['eVerificationStatus'] == 'No')
        {
            $verify_url = $this->CI->config->item('distributor_url')."master/distributor_verification/index";
            $note_html = "Recuerda que debes ​verificar que eres un distribuidor antes usar todas las características de tu cuenta, <a href='".$verify_url."'>Haz clic aquí</a>";
            $text = '<div class="top-notice"><div class="container"><div class="row"><div class="col-lg-12"><div class="top-rad-part"><div class="top-hg-est"><span class="free-package-note">'.$note_html.'</span></div></div></div></div></div></div>';
        }
        $text = "";
        return $text;
    }

    public function is_verification_text()
    {
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $text = '';
        $this->CI->db->select('udv.eVerificationStatus');
        $this->CI->db->from('user_distributor_verification as udv');
        $this->CI->db->where('udv.iDistributorUserId', $distributor_id);
        $data = $this->CI->db->get()->row_array();
        if (empty($data) || $data['eVerificationStatus'] == 'No')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getInvoiceXmlFile($value = '', $id = '', $data = array())
    {
        if (strtolower($data['u_invoice_type']) == 'mexican')
        {
            if ($value != '')
            {
                $path = "invoice_stamping/".$data['dsi_zoho_invoice_id'].".xml";
                $download_icon = "<i class='fa fa-download'></i>";
                $download_url = $this->CI->config->item('upload_url').$path;
                $download_path = $this->CI->config->item('upload_path').$path;
                $getButton = "<a class='btn btn-info pro_import_dwnload_btn' href=".$download_url." download><i class='fa fa-download' aria-hidden='true' style='font-size:20px;'></i></a>";
                if (file_exists($download_path))
                {
                    return $getButton;
                }
                else
                {
                    return "N/A";
                }
            }
        }
        return "N/A";
    }

    public function getInvoicePdfFile($value = '', $id = '', $data = array())
    {
        if (strtolower($data['u_invoice_type']) == 'mexican')
        {
            if ($value != '')
            {
                $path = "invoice_stamping/".$data['dsi_zoho_invoice_id'].".pdf";
                $download_icon = "<i class='fa fa-download'></i>";
                $download_url = $this->CI->config->item('upload_url').$path;
                $download_path = $this->CI->config->item('upload_path').$path;
                $getButton = "<a class='btn btn-info pro_import_dwnload_btn' href=".$download_url." download><i class='fa fa-download' aria-hidden='true' style='font-size:20px;'></i></a>";
                if (file_exists($download_path))
                {
                    return $getButton;
                }
                else
                {
                    return "N/A";
                }
            }
        }
        return "N/A";
    }

    public function getNewSalesmanData($post_arr = array(), $all_salesman = array())
    {
        $new_id = 0;
        $new_name = '';
        if(!empty($all_salesman)){
            foreach ($all_salesman['salespersons'] as $key => $value) {
                if(strtolower($post_arr['owner_name'])==strtolower($value['salesperson_name'])){
                    $new_id = $value['salesperson_id'];
                    $new_name = $value['salesperson_name'];
                }
            }
        }
        $ret_arr = array();
        $ret_arr['new_id'] = $new_id;
        $ret_arr['new_name'] = $new_name;
        return $ret_arr;
    }

    public function getPrintShopButtons($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";

        $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#user/print_shop_request/printshop_detail_view|id|".$data['u_users_id']."' title='Print Shop Dedtails'><i class='icon18 iconic-icon-eye'></i></a>";

        $button .= '<a class="btn btn_info custom-view-btn view_btn" href="'.$this->CI->config->item('admin_url').'#printshop/print_shop_techniques/index|pid|'.$data['u_users_id'].'" title="Printing Techinques"><i class="icomoon-icon-gift"></i></a>';

        $button .= "</div>";
        return $button;
    }

    public function getRelationalScaleStatus()
    {
        return 2;
    }

    public function getDistributorManagementEditBtn($value = '', $id = '', $data = array())
    {
        $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#misc/distributor_management/add|mode|Update|id|".$id."' title='Edit'><i class='icomoon-icon-pencil-2'></i></a>";
        return $button;
    }

    public function manageAffiliateBalanceLeisure($mode = '', $id = '', $parID = '')
    {
        if (strtolower($mode) == 'add')
        {
            $ret_arr = $this->updateAffiliateBalance($mode, $id, $parID);
        }
        elseif (strtolower($mode) == 'update')
        {
            $ret_arr = $this->updateAffiliateBalanceLeisure($mode, $id, $parID);
        }
        return $ret_arr;
    }

    public function updateAffiliateBalanceLeisure($mode = '', $id = '', $parID = '')
    {
        $post_arr = $this->CI->input->post();
        $this->CI->load->library('salescommission');
        $amount = $this->CI->salescommission->validateTransaction($id, $post_arr, $mode);
        $_POST['pc_balance'] = $amount;
        $ret_arr['success'] = true;
        $ret_arr['message'] = "true";
        return $ret_arr;
    }

    public function checkValidPopupDate($mode = '', $id = '', $parID = '')
    {
        if ($mode == 'Add')
        {
            $post_arr = $this->CI->input->post();
            $start_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $post_arr['pm_start_date'])));
            $end_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $post_arr['pm_end_date'])));
            $this->CI->db->select('pm.*');
            $this->CI->db->from('popup_management as pm');
            $this->CI->db->where('"'.$start_date.'" BETWEEN DATE(pm.dtStartDate) AND DATE(pm.dtEndDate)', false, false);
            $this->CI->db->or_where('"'.$end_date.'" BETWEEN DATE(pm.dtStartDate) AND DATE(pm.dtEndDate)', false, false);
            $popup_data = $this->CI->db->get();
            $ret_arr = array();
            if (!empty($popup_data->result_array()))
            {
                $ret_arr['success'] = false;
                $ret_arr['message'] = "Popup ya está programado en estos días";
            }
            else
            {
                $ret_arr['success'] = true;
            }
        }
        else
        {
            $ret_arr['success'] = true;
        }
        return $ret_arr;
    }

    public function getAffiliateCategoryType($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $this->CI->db->select('eSalesPersonCategory');
        $this->CI->db->from('affiliate_master');
        $this->CI->db->where('iAffiliateId', $data['pc_affiliate_id']);
        $aff_data = $this->CI->db->get()->row_array();
        return $aff_data['eSalesPersonCategory'];
    }

    public function getHomePagePopup()
    {
        /*Home page Popup*/
        $input_params = array();
        $input_params['page_index'] = 1;
        $popup_data = $this->CI->cit_api_model->callAPI('get_homepage_popup_data', $input_params);
        $home_popup_data = array();
        if ($popup_data['settings']['success'] == 1)
        {
            $home_popup_data = $popup_data['data'][0];
        }
        return $home_popup_data;
    }

    public function getStandardSubscriptionInvoice($value = '', $id = '', $data = array())
    {
        if (strtolower($data['u_invoice_type']) == 'mexican')
        {
            $path = "zs_pdf/".$data['dsi_zoho_invoice_id'].".pdf";
            $download_icon = "<i class='fa fa-download'></i>";
            $download_url = $this->CI->config->item('upload_url').$path;
            $download_path = $this->CI->config->item('upload_path').$path;
            $getButton = "<a class='btn btn-info pro_import_dwnload_btn' href=".$download_url." download><i class='fa fa-download' aria-hidden='true' style='font-size:20px;'></i></a>";
            if (file_exists($download_path))
            {
                return $getButton;
            }
            else
            {
                return "N/A";
            }
        }
        return "N/A";
    }

    public function getProviderUniqueId($value = '', $data_arr = array())
    {
        return $provider_key = 'PRO'.$data_arr['provider_id'];
    }

    public function search_connection($input_params = array())
    {
        if ($input_params['search_param'] != '')
        {
            $search_param = $input_params['search_param'];
            $cond = "(u.vName like '%".$search_param."%' or u.vLastName like '%".$search_param."%' or pc.vPackageName  like '%".$search_param."%' or dsa.iAddonUsersQty like '%".$search_param."%')";
        }
        else
        {
            $cond = '1=1';
        }
        $return_arr['extra_cond'] = $cond;
        return $return_arr;
    }

    public function getAffiliateBalance($value = '', $id = '', $data = array())
    {
        $params = array(
            'affiliate_id' => $id,
        );
        $wallet_data = $this->CI->cit_api_model->callAPI('get_wallet_data', $params);
        return format_price($wallet_data['data'][0]['pc_balance']);
    }

    public function getTransactionHistoryButon($value = '', $id = '', $data = array())
    {
        $button .= "<a hijacked='yes' class='btn btn_info custom-view-btn view_btn' href='".$this->CI->config->item('admin_url')."#misc/affiliate_transaction_history/index|salesman_id|".$id."' title='Edit'><i class='icomoon-icon-eye'></i></a>";
        return $button;
    }

    public function getDistUserData($dist_user_id=0){
        $this->CI->db->select('u.*,md.vEmail as salmanMail');
        $this->CI->db->from('users as u');
        $this->CI->db->join('mod_admin as md',"md.iAdminId=u.iAdminId","left");
        $this->CI->db->where('u.iUsersId',$dist_user_id);
        $dist_user_data = $this->CI->db->get()->row_array();
        return $dist_user_data;
    }

    public function getAffiliateData($aff_zcrm_id=0){
        $this->CI->db->select('am.*');
        $this->CI->db->from('affiliate_master as am');
        $this->CI->db->where('am.vCrmId',$aff_zcrm_id);
        $aff_user_data = $this->CI->db->get()->row_array();
        return $aff_user_data;
    }

    public function getAffiliateMappingData($dist_id=0){
        $this->CI->db->select('adm.*');
        $this->CI->db->from('affiliate_distributor_mapping as adm');
        $this->CI->db->where('adm.iDistributorUserId',$dist_id);
        $aff_mapping_user_data = $this->CI->db->get()->row_array();
        return $aff_mapping_user_data;
    }

    public function returnProductCount($value = '', $id = '', $data = array())
    {
        if($value>0){
            return $value;
        } else {
            return "Cargando <br/>Productos";
        }
    }

    public function getAllAffiliateData($affiliate_id = 0)
    {
        $this->CI->db->select('am.*');
        $this->CI->db->from('affiliate_master as am');
        $this->CI->db->where('am.iAffiliateId', $affiliate_id);
        $aff_user_data = $this->CI->db->get()->row_array();
        return $aff_user_data;
    }

    public function getTrimedDescription($value = '', $id = '', $data = array())
    {
        return $value = strlen($value) > 15 ? substr($value, 0, 15)."..." : $value;
    }

    public function getDistPackageData($package_id=0){
        $this->CI->db->select('sm.*');
        $this->CI->db->from('subscription_master as sm');
        $this->CI->db->where('sm.iSubscriptionMasterId', $package_id);
        $package_data = $this->CI->db->get()->row_array();
        return $package_data;
    }


    public function checkProviderProductExists($provider_id=0,$product_code=''){
        $this->CI->db->select('pm.*');
        $this->CI->db->from('product_master as pm');
        $this->CI->db->where('pm.vProviderUniqueKey', $product_code);
        $this->CI->db->where('pm.iProviderId', $provider_id);
        $product_data = $this->CI->db->get()->row_array();
        return $product_data;
    }

    public function getProviderAffiliateListing()
    {
        $email_arr = array(
            'proveedores2@alyzta.com',
            'proveedores3@alyzta.com',
        );
        $this->CI->db->select('am.*');
        $this->CI->db->from('affiliate_master as am');
        $this->CI->db->where_in('am.vEmail', $email_arr);
        $aff_data = $this->CI->db->get()->result_array();
        return $aff_data;
    }

    public function getUcode($value = '', $data_arr = array())
    {
        if ($value != "")
        {
            $this->CI->db->select('vCode');
            $this->CI->db->where('iCustomCodeId', $value);
            $result = $this->CI->db->get('custom_code')->result_array();
            $ret_val = $result[0]['vCode'];
        }

        return $ret_val;
    }

    public function check_for_display_popup($input_params = array())
    {
        $start_date = date('Y-m-d', strtotime($input_params['admin_popup_start_date']));
        $end_date = date('Y-m-d', strtotime($input_params['admin_popup_end_date']));
        $today = date('Y-m-d', time());
        $count = $input_params['admin_popup_count'];
        $cookie_popup_count = $input_params['cookie_popup_count'];
        $cur_timestamp = time();
        if ($today == $start_date && $today == $end_date)
        {
            $start_time = strtotime($input_params['admin_popup_start_date']);
            $end_time = strtotime($input_params['admin_popup_end_date']);
        }
        if ($today == $start_date)
        {
            $start_time = strtotime($input_params['admin_popup_start_date']);
            $end_time = strtotime($start_date." 23:23:59");
        }
        if ($today != $start_date && $today == $end_date)
        {
            $today = date('Y-m-d', time());
            $start_time = strtotime($today." 00:00:00");
            $end_time = strtotime($input_params['admin_popup_end_date']);
        }
        if ($today != $start_date && $today != $end_date)
        {
            $today = date('Y-m-d', time());
            $start_time = strtotime($today." 00:00:00");
            $end_time = strtotime($today." 23:23:59");
        }

        $time_diff = $end_time-$start_time;
        $interval = $time_diff/$count;

        $show_popup = array();
        for ($i = 1; $i <= $count+1; $i++)
        {
            if ($i == 1)
            {
                $show_popup[$i] = $start_time;
            }
            else
            {
                $show_popup[$i] = $show_popup[$i-1]+$interval;
            }
        }

        $cookie_popup_count_code = 0;
        foreach ($show_popup as $key => $value)
        {
            if ($cur_timestamp >= $show_popup[$key] && $cur_timestamp <= $show_popup[$key+1])
            {
                if ($cookie_popup_count <= $cookie_popup_count_code)
                {
                    $popup_show = 1;
                    $cookie_popup_count_from_api = $cookie_popup_count_code+1;
                    break;
                }
            }
            else
            {
                $popup_show = 0;
                $cookie_popup_count_from_api = $cookie_popup_count;
            }
            $cookie_popup_count_code = $cookie_popup_count_code+1;
        }

        $return_arr[0]['popup_display'] = $popup_show;
        $return_arr[0]['cookie_popup_count_code'] = $cookie_popup_count_from_api;

        return $return_arr;
    }

    public function getAdminPagePopup()
    {
        $input_params = array();
        $input_params['page_index'] = 1;
        $input_params['user_type'] = $_SESSION['cit_distributor_iUserType'];
        $popup_data = $this->CI->cit_api_model->callAPI('get_adminpage_popup_data', $input_params);
        $admin_popup_data = array();
        if ($popup_data['settings']['success'] == 1)
        {
            $admin_popup_data = $popup_data['data'][0];
        }
        return $admin_popup_data;
    }

    public function getProvicerPagePopup()
    {
        $input_params = array();
        $input_params['page_index'] = 1;
        $input_params['user_type'] = $_SESSION['cit_provider_iUserType'];
        $popup_data = $this->CI->cit_api_model->callAPI('get_adminpage_popup_data', $input_params);
        $admin_popup_data = array();
        if ($popup_data['settings']['success'] == 1)
        {
            $admin_popup_data = $popup_data['data'][0];
        }
        return $admin_popup_data;
    }

    public function sendCommissionMail($url = '')
    {
        $admin_email = $this->CI->config->item('AFFILIATE_COMMISSION_OWNER_CHANGE_EMAIL');
        $send_mail_arr = array(
            'vEmail' => $admin_email,
            'admin_url' => $url,
        );
        $this->sendMail($send_mail_arr, 'AFFILIATE_COMMISSION_CHANGE');
    }

    public function getDistributorEcomSiteUrl($value = '', $id = '', $data = array())
    {
       $url = '-';
        //if($data["uds_step_user"] >= 7 || $data["uds_verify_Web"]==1){
            $url = 'Sin URL';
            if (strtolower($this->CI->config->item('IS_LOCAL_DOMAIN')) == 'yes' && $data['uds_custom_domain_url'] != ''){
                $url = '<a title="" href="'.$this->CI->config->item('site_url').$data['uds_custom_domain_url'].'" target="_blank" class="local">link a url</a>';
            }else{
                $url = '<a title="" href="http://'.$data['uds_client_domain_url'].$data['uds_custom_domain_url'].'" target="_blank" class="live">'.$data['uds_client_domain_url'].$data['uds_custom_domain_url'].'</a>';
            }
       //}
       
        /*if (strstr($_SERVER['REMOTE_ADDR'], '180.211')) {
            echo $url; exit;
        }*/
        return $url;
    }

    public function addActiveButton($value = '', $id = '', $data = array())
    {
        $hide_status = "";
        if ($data['dbc_status'] == 'Active')
        {
            $hide_status = "hide-active";
        }
        else
        {
            $hide_status = "hide-inactive";
        }
        if ($hide_status == 'hide-active')
        {
            $icon_class = "fa fa-eye";
            $icon_title = 'Inactive';
        }
        else
        {
            $icon_class = "fa fa-eye-slash";
            $icon_title = 'Active';
        }

        $button = "<div class='my_custom_row_btns'>";
        $button .= "<a style='margin-left:30px;' id='hide-unhide-".$data['dppt_distributor_popular_product_trans_id']."' hijacked='yes' class='dist_cat_show_hide btn btn_info custom-view-btn btn_hide_popular_products ".$hide_status."' href='javascript://'   title='".$icon_title."' data-id='".$data['iDistributorBannerCategoriesId']."' data-status='".$icon_title."'><i class='".$icon_class."'></i></a>";
        $button .= "</div>";
        return $button;
    }

    public function addBannerButton($value = '', $id = '', $data = array())
    {
        $hide_status = "";
        if ($data['dbm_status'] == 'Active')
        {
            $hide_status = "hide-active";
        }
        else
        {
            $hide_status = "hide-inactive";
        }
        if ($hide_status == 'hide-active')
        {
            $icon_class = "fa fa-eye";
            $icon_title = 'Inactive';
        }
        else
        {
            $icon_class = "fa fa-eye-slash";
            $icon_title = 'Active';
        }

        $button = "<div class='my_custom_row_btns banner_button'>";
        $button .= "<a style='margin-left:30px;' id='hide-unhide-".$data['iDistributorBannerMasterId']."' hijacked='yes' class='dist_banner_show_hide btn btn_info custom-view-btn btn_hide_popular_products ".$hide_status."' href='javascript://'   title='".$icon_title."'  data-id='".$data['iDistributorBannerMasterId']."' data-status='".$icon_title."'><i class='".$icon_class."'></i></a>";
        $button .= "</div>";
        return $button;
    }

    public function getAdminTutorials($type)
    {
        $input_params = array();
        $input_params['page_index'] = 1;
        $input_params['usertype'] = $type;
        $popup_data = $this->CI->cit_api_model->callAPI('get_tutoriallist_by_usertype', $input_params);
        $admin_tutorials = array();
        if ($popup_data['settings']['success'] == 1)
        {
            $admin_tutorials = $popup_data['data'];
        }
        return $admin_tutorials;
    }

    public function copyImagewithFolderInNotification($input_params = array())
    {
        $admin_banner_path = $this->CI->config->item('upload_path').'banner_image/0/';

        $banner_name = array();
        foreach ($input_params['get_distributor_users'] as $key => $value)
        {
            $dist_id = $value['u_users_id'];
            $banner_path = $this->CI->config->item('upload_path').'banner_image/'.$dist_id.'/';
            if (!file_exists($banner_path))
            {
                mkdir($banner_path);
            }
            $imagePath = $admin_banner_path.$value['dbm_image'];
            $dest_path = $banner_path.$value['dbm_image'];
            $copied = copy($imagePath, $dest_path);
        }
        $ret_arr = array();
        $ret_arr['success'] = true;
        $ret_arr['message'] = "Data added successfully";
        return $ret_arr;
    }

    public function getaffiliatePagePopup()
    {
        $input_params = array();
        $input_params['page_index'] = 1;
        $input_params['user_type'] = "3";
        $popup_data = $this->CI->cit_api_model->callAPI('get_adminpage_popup_data', $input_params);
        $admin_popup_data = array();
        if ($popup_data['settings']['success'] == 1)
        {
            $admin_popup_data = $popup_data['data'][0];
        }
        return $admin_popup_data;
    }

    public function getEditButtonColorRequests($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        if (strtolower($data['cr_status']) == 'pending')
        {

            $button .= "<a title='Aprobar' class='btn btn_info custom-view-btn view_btn approve_btn  color_request_approve' data-id=".$id.">    <i class='icomoon-icon-checkmark-2'></i></a>";

            $button .= "<a title='Cancelar' class='btn btn_info custom-view-btn view_btn fancybox-hash-iframe'  href='".$this->CI->config->item('admin_url')."#master/color_requests/add|mode|Update|id|".$id."|width|75%|height|75%|hideCtrl|true|loadGrid|list2|iframe|true' style='background:#e4010b'><i class='fa fa-times'></i></a></div>";
        }
        $button .= "</div>";

        return $button;
    }

    public function getEditButtonMaterialRequests($value = '', $id = '', $data = array())
    {
        $button = "<div class='my_custom_row_btns'>";
        if (strtolower($data['mr_status']) == 'pending')
        {
            $button .= "<a title='Aprobar' class='btn btn_info custom-view-btn view_btn approve_btn  material_request_approve' data-id=".$id.">    <i class='icomoon-icon-checkmark-2'></i></a>";

            $button .= "<a title='Cancelar' class='btn btn_info custom-view-btn view_btn fancybox-hash-iframe'  href='".$this->CI->config->item('admin_url')."#master/material_requests/add|mode|Update|id|".$id."|width|75%|height|75%|hideCtrl|true|loadGrid|list2|iframe|true' style='background:#e4010b'><i class='fa fa-times' ></i></a></div>";
        }
        else
        {
            $button .= "";
        }
        $button .= "</div>";

        return $button;
    }

    public function checkIfPaymentDueDate($subs_sess_arr=array()){
        $platform_extended_days = $this->CI->config->item('PLATFORM_EXTENDED_DAYS');
        $show_msg = false;
        $free_pkg_arr = array('FREE','FREE07','FREE15','PARTNER_FREE');
        $zoho_package_code = $subs_sess_arr[0]['vZohoPlanCode'];
        if(!empty($subs_sess_arr) && !in_array($zoho_package_code, $free_pkg_arr)){
            $end_date = date('Y-m-d',strtotime($subs_sess_arr[0]['dtEndDate']));
            $due_date = date('Y-m-d',strtotime($end_date.' + '.$platform_extended_days.' days'));
            $curr_date = date('Y-m-d');
            if($curr_date < $due_date && $curr_date >= $end_date){
                $show_msg = true;
            }
        }
        return $show_msg;
    }


    public function getPaymentDueDate($subs_sess_arr=array()){
        $platform_extended_days = $this->CI->config->item('PLATFORM_EXTENDED_DAYS');
        $show_msg = false;
        $end_date = $subs_sess_arr[0]['dtEndDate'];
        if(!empty($subs_sess_arr)){
            $due_date = date('F j, Y',strtotime($end_date.' + '.$platform_extended_days.' days'));
            return $due_date;
        }
        return $end_date;
    }

    public function searchProductIframe($input_params = array())
    {
        $return = array();
        $return[0]['extra_cond'][] = '1=1';
        $pp = $this->CI->session->userdata('per_page_prod_limit');
        $limit = 12;
        if ($input_params['custom_rec_limit'] != '')
        {
            $limit = intval($input_params['custom_rec_limit']) > 0 ? intval($input_params['custom_rec_limit']) : 12;
        }
        else
        {
            $limit = intval($pp) > 0 ? $pp : 12;
        }
        $return[0]['rec_limit'] = $limit > 0 ? $limit : 12;

        $return[0]['groupby'] = 'pm.vProductParentCode';
        if ($input_params['keyword'] != '')
        {
            $return[0]['extra_cond'][] = "( pm.tLongDescription LIKE('%".$input_params['keyword']."%') OR              pm.vProviderParentCode LIKE('%".$input_params['keyword']."%') OR pm.vProviderUniqueKey LIKE('%".$input_params['keyword']."%')  )";
        }
        if (is_array($input_params['category_ids']) && count($input_params['category_ids']) > 0)
        {
            $catids = implode(",", $input_params['category_ids']);
            $return[0]['extra_cond'][] = "(pm.iCategoryId IN(".$catids.") OR pm.iSubCategoryId IN (".$catids.") )";
        }
        if ($input_params['product_code'] != '')
        {
            $return[0]['extra_cond'][] = " ( pm.vProviderUniqueKey LIKE '%".$input_params['product_code']."%' OR pm.vProductParentCode LIKE '%".$input_params['product_code']."%') ";
        }
        if ($input_params['provider_id'] != '')
        {
            $return[0]['extra_cond'][] = " ( pm.iProviderId =".$input_params['provider_id'].")";
        }

        $return[0]['extra_cond'] = implode(" AND ", $return[0]['extra_cond']);
        if ($input_params['sort_by'] == 'name' && $input_params['sort_dir'] == 'desc')
        {
            $return[0]['order_by'] = 'pm.vProductParentCode DESC';
        }
        else
        if ($input_params['sort_by'] == 'name' && $input_params['sort_dir'] == 'asc')
        {
            $return[0]['order_by'] = 'pm.vProductParentCode ASC';
        }
        else
        if ($input_params['sort_by'] == 'date' && $input_params['sort_dir'] == 'asc')
        {
            $return[0]['order_by'] = 'pm.dAddedDate ASC';
        }
        else
        if ($input_params['sort_by'] == 'date' && $input_params['sort_dir'] == 'desc')
        {
            $return[0]['order_by'] = 'pm.dAddedDate DESC';
        }
        else
        {
            $return[0]['order_by'] = 'pm.dAddedDate DESC';
        }
        return $return;
    }

    public function getInvoicePaymentLink($value = '', $id = '', $data = array())
    {
        $html = 'N/A';
        if (strtolower($data['dsi_zoho_status']) == 'paid')
        {
            $html = '<b class="dist_invoice_paid">Pagado</b>';
        }
        else
        {
            $html = '<a href="'.$data['dsi_zoho_invoice_url'].'" target="_blank" class="dist_invoices_paynow_lnk blink"><i class="fa fa-external-link" aria-hidden="true"></i> Pague ahora</a>';
        }
        return $html;
    }

    public function getAllColors($keyword='')
    {
        $this->CI->db->select('cm.*');
        $this->CI->db->from('color_master as cm');
        $this->CI->db->where('cm.eStatus', 'Active');
        if($keyword!=''){
            $this->CI->db->where('cm.vColorName LIKE "%'.$keyword.'%"');
        }
        $color_data = $this->CI->db->get()->result_array();
        return $color_data;
    }

    public function WebsiteProviderList($value = '', $mode = '', $id = '', $data = array(), $parent_src = '')
    {
        if (strtolower($mode) == 'add')
        {
            $this->CI->db->select('u.iUsersId,u.vLegalCompanyName');
            $this->CI->db->from('users as u');
            $this->CI->db->join('provider_websites as pw', 'pw.iProviderId=u.iUsersId', 'left');
            $this->CI->db->where('u.iUserType', 2);
            $this->CI->db->where('u.eStatus', 'Active');
            $this->CI->db->where('u.eProviderType', 'Default');
            $this->CI->db->where('pw.iProviderId IS NULL');
            $obj_data = $this->CI->db->get();
            $all_providers = is_object($obj_data) ? $obj_data->result_array() : array();
        }
        else
        {
            $this->CI->db->select('u.iUsersId,u.vLegalCompanyName');
            $this->CI->db->from('users as u');
            $this->CI->db->where('u.iUserType', 2);
            $this->CI->db->where('u.eStatus', 'Active');
            $this->CI->db->where('u.eProviderType', 'Default');
            $obj_data = $this->CI->db->get();
            $all_providers = is_object($obj_data) ? $obj_data->result_array() : array();
        }
        $ret_arr = array();
        foreach ($all_providers as $key => $value)
        {
            $ret_arr[$key]['id'] = $value['iUsersId'];
            $ret_arr[$key]['val'] = $value['vLegalCompanyName'];
        }
        return $ret_arr;
    }

    public function getProviderWebsiteLink($value = '', $id = '', $data = array())
    {
        $html = 'N/A';
        if ($data['pw_websiteite_url'] != '')
        {
            $url = $this->CI->config->item('site_url')."virtualsample".DS.$data['pw_websiteite_url'];
            if(strtolower($this->CI->config->item('VIRTUAL_SAMPLE_DOMAIN_MODE'))=='live'){
                $url = $this->CI->config->item('VIRTUAL_SAMPLE_PROVIDER_DOMAIN').$data['pw_websiteite_url'];
            }
            $html = '<a class="btn btn-info ecom_link_btn tooltip-hd" rel="tooltip" data-original-title="Ir a comercio electrónico" href="'.$url.'" target="_blank"><i class="icomoon-icon-link"></i></a>';
        }
        return $html;
    }

    public function getUpdateAutoRenewButton($value = '', $id = '', $data = array())
    {
        $html = 'N/A';
        if ($data['ds_zoho_subscription_id'] != '')
        {
            $html = '<a class="btn btn-info ecom_link_btn tooltip-hd change_zoho_status" rel="tooltip" data-original-title="Cambiar modo de pago" title="Cambiar modo de pago" href="javascript://" data-subid="'.encrval($data['ds_zoho_subscription_id']).'" data-status="'.encrval($data['ds_auto_renew']).'" data-dsid="'.encrval($data['ds_distributor_subscription_id']).'" target="_blank"><i class="icomoon-icon-loop-2"></i></a>';
        }
        return $html;
    }

    public function getPaymentMode($value = '', $id = '', $data = array())
    {
        if (strtolower($data['ds_auto_renew']) == 'yes')
        {
            return "Online";
        }
        else
        if (strtolower($data['ds_auto_renew']) == 'no')
        {
            return "Offline";
        }
        else
        {
            return $data['ds_auto_renew'];
        }
    }

    public function getProviderCategories($type, $total_categories = '', $parent_categories = '', $provider_id = 0){
        if ($type == "parent")
        {
            $parent_sql = " and c.iParentId in (0) ";
        }
        if ($type == "sub")
        {
            $parent_sql = " and c.iParentId in ('".$parent_categories."') ";
        }
        $q = $this->CI->db->query("select c.vCategoryName as cm_category_name, c.vCategoryName as catname,c.eCategoryType,c.iCategoryMasterId,c.iParentId
              ,(SELECT count(p.iProductMasterId) FROM product_master as p
                  INNER JOIN users AS up ON up.iUsersId = p.iProviderId
                      WHERE ( p.iCategoryId=c.iCategoryMasterId OR p.iSubCategoryId=c.iCategoryMasterId)
                      AND p.eStatus = 'Active' AND up.eStatus = 'Active'  AND (p.eProductType ='Default') AND p.iProviderId = '".$provider_id."'  ) AS totproduct
              from category_master as c
              where c.eStatus='Active' and c.vCategoryName!=''  AND (c.eCategoryType='Default') $parent_sql HAVING totproduct > 0  order by  c.eCategoryType ASC, c.vCategoryName asc  $limit_sql");
        $parent_categories = $q->result_array();
        return $parent_categories;
    }

    public function checkZohoSubscriptionInvoice($subscription_arr = array())
    {
        $dist_id = $this->CI->session->userdata('iAdminId');
        $this->CI->db->select('dsi.*');
        $this->CI->db->from('distributor_subscription_invoice as dsi');
        $this->CI->db->where('dsi.iDistributorId', $dist_id);
        $this->CI->db->where('dsi.vZohoStatus!="Paid"');
        $obj_data = $this->CI->db->get();
        $all_invoices = is_object($obj_data) ? $obj_data->result_array() : array();
        $has_pending_invoices = false;
        if (!empty($all_invoices) && count($all_invoices) > 0)
        {
            $has_pending_invoices = true;
        }
        return $has_pending_invoices;
    }

    public function getAlProviderDetails($provider_id = 0)
    {
        $ret_arr = array();
        $this->CI->db->select('u.*,ups.*');
        $this->CI->db->from('users as u');
        $this->CI->db->join('user_provider_settings as ups', 'ups.iUserId=u.iUsersId', "left");
        $this->CI->db->where('u.iUsersId', $provider_id);
        $this->CI->db->where('u.eStatus', 'Active');
        $provider_details = $this->CI->db->get()->row_array();
        return $provider_details;
    }

    public function getAllBannersPerCategory($value = '', $id = '', $data = array())
    {
        $this->CI->db->select('dbm.*,dbc.*');
        $this->CI->db->from('distributor_banner_master as dbm');
        $this->CI->db->join('distributor_banner_categories as dbc', 'dbc.iDistributorBannerCategoriesId=dbm.iCategoriesId');
        $this->CI->db->where('dbm.iCategoriesId', $data['dbc_distributor_banner_categories_id']);
        $this->CI->db->where('dbm.iDistributorUserId', $this->CI->session->userdata('iAdminId'));
        $data = $this->CI->db->get()->result_array();
        return count($data);
    }

    public function getBaseIframeCss($sess_data = array(), $template = '')
    {
        $primary_color = $sess_data['vPrimaryColor'];
        $secondary_color = $sess_data['vSecondaryColor'];
        $loader = $sess_data['vLoaderGif'];
        $base_css = $this->CI->config->item('css_path').$template.'/css/iframe-base.css';
        $str = file_get_contents($base_css);
        $str = str_replace("#primarycolor", $primary_color, $str);
        $str = str_replace("#secondarycolor", "$secondary_color", $str);
        return $str;
    }

    public function getProviderWebsiteDetails($code = '')
    {
        $this->CI->db->select('pw.*,u.*');
        $this->CI->db->from('provider_websites as pw');
        $this->CI->db->join('users as u', 'u.iUsersId=pw.iProviderId', "left");
        $this->CI->db->where('pw.vWebsiteiteUrl', $code);
        $provider_details = $this->CI->db->get()->row_array();
        return $provider_details;
    }

    public function get_premium_selected_package_id($input_params = array())
    {
        $this->CI->db->select('iSubscriptionMasterId');
        $this->CI->db->from('subscription_master');
        $this->CI->db->where('vZohoPlanCode', $input_params['package_id']);
        $pkg_data = $this->CI->db->get()->row_array();

        $free_package_id = $pkg_data['iSubscriptionMasterId'] ? $pkg_data['iSubscriptionMasterId'] : 0;
        $ret_arr = array();
        $ret_arr['premium_free_package_id'] = $free_package_id;
        return $ret_arr;
    }

    public function addViewDistributorReferences($ref_arr = array(),$get_data=false){
        $ret_arr = array();
        $ret_arr['success'] = false;
        $ret_arr['message'] = "Operation Failed";
        $ret_arr['data'] = array();

        $distributor_id = $this->CI->session->userdata('iAdminId');

        $this->CI->db->select('dvr.*');
        $this->CI->db->from('distributor_verification_refrences as dvr');
        $this->CI->db->where('dvr.iDistributorUserId',$distributor_id);
        $obj_data = $this->CI->db->get();
        $ref_data = is_object($obj_data) ? $obj_data->result_array() : array();
        if(!empty($ref_arr)){
            $ref_arr = array_values($ref_arr); //reindexing
            if(!empty($ref_data)){
                foreach ($ref_arr as $key => $value) {
                    $where = array('iDistributoruserId'=>$distributor_id,'iDistributorVerificationRefrencesId'=>$value['iDistributorVerificationRefrencesId']);
                    $this->CI->db->where($where);
                    $this->CI->db->update('distributor_verification_refrences',$value);
                    $ret_arr['success'] = true;
                    $ret_arr['message'] = "Distributor references updated successfully";
                }
            } else {
                $this->CI->db->insert_batch('distributor_verification_refrences',$ref_arr);
                $ret_arr['success'] = true;
                $ret_arr['message'] = "Distributor references added successfully";
            }

        }
        if($get_data==true){
            $ret_arr['success'] = true;
            $ret_arr['message'] = "Distributor reference data found successfully";
            $ret_arr['data'] = $ref_data;
        }
        return $ret_arr;
    }

    public function isDistributorVerificationReqSent($flag=false){
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $this->CI->db->select('dv.*');
        $this->CI->db->from('distributor_verification as dv');
        $this->CI->db->where('dv.iDistributorUserId',$distributor_id);
        $this->CI->db->where("dv.eStatus!= 'Approved'");
        $verification_data = $this->CI->db->get()->row_array();
        
        if(!empty($verification_data)){
            if($flag==true){
                return true; 
            } else{
                return $verification_data;
            }
        } else {
            if($flag==true){
                return false; 
            } else{
                return $verification_data;
            }
        }
    }
public function isDistributorVerification($flag=false){
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $this->CI->db->select('dv.*');
        $this->CI->db->from('distributor_verification as dv');
        $this->CI->db->where('dv.iDistributorUserId',$distributor_id);
        $this->CI->db->where_in("dv.eStatus",array('Approved','Pending','Quick'));
        $verification_data = $this->CI->db->get()->row_array();
        
        if(!empty($verification_data)){
            if($flag==true){
                return 'true';
               // echo 'true';
            } else{
                return $verification_data;
            
            }
        } else {
            if($flag==true){
                return 'false';
                //echo 'false';
            } else{
                return $verification_data;
            
            }
        }
    }
    public function allowDistributorPanelAccess($mode = '', $id = '', $parID = '')
    {
        $post_arr = $this->CI->input->post(NULL,TRUE);
        $distributor_id = $post_arr['dv_distributor_user_id'];
        $dist_data = $this->getDistUserData($distributor_id);
        /*echo "<pre>";
        print_r($dist_data);
        die;*/
        if($post_arr['dv_status']=='Approved'){
            $send_mail_arr = array(
                'vEmail'=>$dist_data['vEmail'],
                'distributor_name' => $dist_data['vCompanyName'],
                'site_url' => $this->CI->config->item('site_url'),
                'vBccEmail'=>$dist_data['salmanMail'],
            );
            $send_mail = $this->sendMail($send_mail_arr, 'DISTRIBUTOR_VERIFICATION_RESPONSE');
        }
        $ret_arr = array();
        $ret_arr['success'] = true;
        return $ret_arr;
    }

    public function getVerificationType($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        if ($value == 'Quick')
        {
            $value = "Verificación Rápida";
        }
        elseif ($value == 'Approval')
        {
            $value = "Solicitar Aprobación";
        }
        else
        {
            $value = "N/A";
        }
        return $value;
    }

    public function getDistributorVerificationReferences($distributor_id ='')
    {
        if($distributor_id!=''){
            $this->CI->db->select('dvr.*');
            $this->CI->db->from('distributor_verification_refrences as dvr');
            $this->CI->db->where('dvr.iDistributorUserId',$distributor_id);
            $obj_data = $this->CI->db->get();
            $ref_list = is_object($obj_data) ? $obj_data->result_array() : array();
        } else {
            $ref_list = array();
        }
        return $ref_list;
    }

    public function sendVerificationEmailNotification()
    {
        $distributor_id = $this->CI->session->userdata('iAdminId');
        $dist_data = $this->getDistUserData($distributor_id);
        $this->CI->db->select('dv.*');
        $this->CI->db->from('distributor_verification as dv');
        $this->CI->db->where('dv.iDistributorUserId',$distributor_id);
        $verification_data = $this->CI->db->get()->row_array();
        if(empty($verification_data)){
            $verification_email = $this->CI->config->item('Distributor_Verification_Email_Notification');
            $send_mail_arr = array(
                'vEmail'=>$verification_email,
                'dist_name' => $dist_data['vCompanyName'],
                'user_email' => $dist_data['vEmail'],
                'dRequestedOn' => date('d-m-Y H:i:s a'),
                'site_url' => $this->CI->config->item('site_url'),
            );
            $send_mail = $this->sendMail($send_mail_arr, 'DISTRIBUTOR_VERIFICATION_REQUEST');
        }
        $ret_arr = array();
        $ret_arr['success'] = true;
        return $ret_arr;
    }
    
      public function getDistributorAccess($value = '', $id = '', $data = array()){

        if(isset($data['us_email'])){
            $mail=$data['us_email'];
            $uri='distributor';
        }else{
            $mail=$data['u_email'];
            $uri='provider';
        }
        $url = '<a title="Usar plataforma" href="'.$this->CI->config->item('site_url').$uri.'/user/login/entry_a?login_name='.$mail.'&passwd='.$data['us_md5_pass'].'" target="_blank" class="local">Usar plataforma</a>';
         return $url;
    }
    #####GENERATED_CUSTOM_FUNCTION_END#####
    public function addVerifyWeb($value = '', $id = '', $data = array()){
        $url="-";
        if(!empty($data["us_link_verify"])){
            $url='<a href="'.$data["us_link_verify"].'" target="_blank">'.$data["us_link_verify"].'</a>';    
        }
        return $url;
    }
    /**
     * [FunctionName description]
     * @param string $value [description]
     * @param string $id    [description]
     * @param array  $data  [description]
     */
    public function combineUrl($value='',$id = '', $data = array()){
        
        $url = '-';
        if ($data["uds_verify_Web"]==1) {
            $url='<a href="'.$data["uds_alyzta_url"].'" target="_blank">'.$data["uds_alyzta_url"].'</a>';
        }else{
            if($data["uds_step_user"] >= 7){
                $url='<a href="'.$data["uds_alyzta_url"].'" target="_blank">'.$data["uds_alyzta_url"].'</a>';
            }
        }
       return $url;
    }
   
    /**
     * [combineDateNote description]
     * @param  string $value [description]
     * @param  string $id    [description]
     * @param  array  $data  [description]
     * @return [type]        [description]
     */
    public function combineDateNote($value='',$id = '', $data = array()){
       $date='-';
       $note='';
       if (!empty($data['dp_datePayment'])) {
        if (!empty($data["dp_notePayment"])) {
         $note=substr($data["dp_notePayment"], 0,40).'...';
         $note=utf8_decode($note);
        }
        $date='<span title="'.$note.'">'.$data['dp_datePayment'].'</span>';
       }
       return $date;
    }
    
     public function moneyFormat($value='',$id = '', $data = array()){
        $money='-';
        if(!empty($data['dp_totalPayment'])){
            $money='$ '.number_format($data['dp_totalPayment']);
        }
        /*if(!empty($data['lm_quot'])){
            $money='$ '.number_format($data['lm_quot']);
        }*/
        return $money;
    }
    
    public function contact($value='',$id = '', $data = array()){
        if($data['dcm_contact'] == "0"){
            return '0';
        }else{
            return '<a href="'.$this->CI->config->item('site_url').'admin/#user/distributor_contactProvider/index|id='.$data['us_distributor_id'].'">'.$data['dcm_contact'].'</a>';
        }
    }
    
    public function quotes($value='',$id = '', $data = array()){
         if($data['lm_quotes'] == "0"){
            return '0';
        }else{
        return '<a href="'.$this->CI->config->item('site_url').'admin/#user/distributor_quotes/index|id='.$data['us_distributor_id'].'">'.$data['lm_quotes'].'</a>';
        }
    }
    
    public function extusers($value='',$id = '', $data = array()){
         if($data['us_extusers'] == "0"){
            return '0';
        }else{
        return '<a href="'.$this->CI->config->item('site_url').'admin/#user/distributor_extusers/index|id='.$data['us_distributor_id'].'">'.$data['us_extusers'].'</a>';
        }
    }
    
    public function quotesTotal($value='',$id = '', $data = array()){
         if($data['lm_quotesTotal'] == "0"){
            return '0';
        }else{
        return '<a href="'.$this->CI->config->item('site_url').'admin/#user/distributor_avgmonth/index|id='.$data['us_users_id'].'">'.$data['lm_quotesTotal'].'</a>';
        }
    }
    
    public function moneya($value='',$id = '', $data = array()){
        //if($data['lm_quot'] == "0"){
          //  return '0';
        //}else{
        //setlocale(LC_MONETARY, 'es_MX');
        //return '$ ' .money_format('%.2n',$data['lm_quot']);
        //}
        
         $money='-';
        if(!empty($data['lm_quot'])){
            $money='$ '.number_format($data['lm_quot'],2, '.', ',');
        }
         return $money;
    }
    
    public function moneyc($value='',$id = '', $data = array()){
        if($data['lm_quote'] == "0"){
            return '0';
        }else{
            return'$ '.number_format($data['lm_quote'],2, '.', ',');
        //return '$ ' .money_format('%.2n',$data['lm_quote']);
        }
    }
    
    public function moneye($value='',$id = '', $data = array()){
        if($data['lm_average'] == "0"){
            return '0';
        }else{
            return'$ '.number_format($data['lm_average'],2, '.', ',');
        //return '$ ' .money_format('%.2n',$data['lm_average']);
        }
    }
    
    
    
    public function utfN($value='',$id = '', $data = array()){
        return utf8_decode(ucwords(strtolower($data['us_name'])));
    }
    
    public function utfE($value='',$id = '', $data = array()){
        return utf8_decode(ucwords(strtolower($data['us_empresa'])));
    }
    public function utfNQ($value='',$id = '', $data = array()){
        return utf8_decode(ucwords(strtolower($data['lm_assignedSales'])));
    }
    
    public function utfSub($value='',$id = '', $data = array()){
        return utf8_decode($data['dcm_subject']);
    }
    public function utfMess($value='',$id = '', $data = array()){
        return "<div class='drop'>".utf8_decode($data['dcm_message'])."</div>";
    }
    
    public function changetext($value='',$id = '', $data = array()){
        if($data['lm_status'] == 'LEAD_QUOTATION'){
            return "Enviada";
        }
    }
    
    public function changetextExt($value='',$id = '', $data = array()){
        if($data['us_salesPersonRole'] == 'Salesman'){
            return "Vendedor";
        }else{
            return $data['us_salesPersonRole'];
        }
    }
    
     public function getProviderImageHtml($value = '', $id = '', $data = array())
    {
        
        if ($value == 'Local')
        {
            $url = $this->CI->config->item('upload_url')."provider_website_logo".DS.$data['us_user_id'].DS.$data['pw_logo_provider_list'];
            $path = $this->CI->config->item('upload_path')."provider_website_logo".DS.$data['us_user_id'].DS.$data['pw_logo_provider_list'];
            $ret_url = '';
            if (file_exists($path))
            {
                if ($this->CI->config->item("cdn_activate") == FALSE)
                {
                    $ret_url = str_replace($this->CI->config->item('site_url'), $this->CI->config->item('cdn_http_url'), $url);
                }
                else
                {
                    $ret_url = $url;
                }
            }
            else
            {
                $noimg = $this->getNoImageURL();
                $ret_url = $this->CI->config->item('site_url').DS.'WS'.DS.'image_resize?pic='.base64_encode($noimg)."&width=167&height=111";
            }
        }else{
            if ($data['pw_logo_provider_list'] != ''){
                $url = $this->CI->config->item('upload_url')."provider_website_logo".DS.$data['us_user_id'].DS.base64_encode($data['pw_logo_provider_list']);
                $path = $this->CI->config->item('upload_path')."provider_website_logo".DS.$data['us_user_id'].DS.base64_encode($data['pw_logo_provider_list']);
                $ret_url = '';
                if (file_exists($path)){
                    if ($this->CI->config->item("cdn_activate") == FALSE){
                        $ret_url = str_replace($this->CI->config->item('site_url'), $this->CI->config->item('cdn_http_url'), $url);
                    }else{
                        $ret_url = $url;
                    }
                }else{
                    $noimg = $this->getNoImageURL();
                    $ret_url = $this->CI->config->item('site_url').DS.'WS'.DS.'image_resize?pic='.base64_encode($noimg)."&width=200&height=200";
                }
            }else{
                $noimg = $this->getNoImageURL();
                $ret_url = $this->CI->config->item('site_url').DS.'WS'.DS.'image_resize?pic='.base64_encode($noimg)."&width=200&height=200";
            }
        }
        return "<a href='".$ret_url."' class='fancybox-image'><img src='".$ret_url."' style='max-width: 100%; width: 60px !important; max-height: 100%;'  class='online_image'></a>";
    }
    /**
     *
     *
     */
    public function checkProductByPrivider($value = '', $id = '', $data = array()){        
         $this->CI->db->select("count(iProductMasterId) as cuenta");
         $this->CI->db->from("product_master");
         $this->CI->db->where("iProviderId",$data['us_user_id']);
         $this->CI->db->where("iAddedByUserId",$data['us_user_id']);
          $data_res = $this->CI->db->get()->row_array();
          if($data_res['cuenta']>0){
            return $data['us_product_provider']='<a href="'.$this->CI->config->item('site_url').'distributor/#product/products_v1_v1/index|supplier|'.$data['us_product_provider'].'" hijacked="yes">Ver Productos</a>';
          }else{
            return $data['us_product_provider']='<div class="product">Sin productos</div>'; 
          }
    }
    /**
     *
     *
     *
     */
    
    public function checkAllCategories($value = '', $id = '', $data = array()){
        if($data["ups_varius_promotional"]=="Yes"){
           return $data["ups_total_categoies"]='Promocionales Varios';
        }else{
           return $data["ups_total_categoies"];
        }
    }
    /**
     *
     */
    public function getStatusProvider($value = '', $id = '', $data = array()){
       if(!empty($data['phfd_provider_hide_for_distributor_id'])){
            return '<a id="btn_'.$data['us_user_id'].'" class="btn btn_info custom-view-btn btn-status hide-inactive" status="on" href="JavaScript://" data-toggle="yes" title="Excluir Proveedores" hijacked="yes">
                        <i id="icon_'.$data['us_user_id'].'" class="fa fa-eye-slash"></i>
                    </a>';
        }else{
            return '<a id="btn_'.$data['us_user_id'].'" class="btn btn_info custom-view-btn btn-status hide-active" href="JavaScript://" status="off" data-toggle="yes" title="Excluir Proveedores" hijacked="yes">
                        <i id="icon_'.$data['us_user_id'].'" class="fa fa-eye"></i>
                    </a>';    
        }
        
    }
    /**
     *
     *
     *
     **/
    public function detalForProvider($value = '', $id = '', $data = array()){
        return '<a title="Ver '.utf8_decode($data['us_name_company']).'" style="margin: 0; padding: 0;" href="'.$this->CI->config->item('site_url').'distributor/#custom_products/provider_detail_view|id|'.$data['us_user_id'].'|vista|catalogo">
        '.utf8_decode($data['us_name_company']).'</a>';
    }
    /**
     *
     *
     */
    public function utfCategory($value='',$id = '', $data = array()){
        return utf8_decode($data['ups_provider_category']);
    }
    /**
     *
     *
     *
     */
    
    public function imgBackProvider($value = '', $id = '', $data = array()){
        $separa=explode(",",$data["ups_total_categoies"]);
        if(count($separa)>=3){
          return $data['img_category_by_provider'] = 'profile-promocionales-varios.jpg';    
        }else{
            $palabra=utf8_encode($separa[0]);
            $palabra=strtolower($palabra);
            $acentos=array('á','é','í','ó','ú','ñ');
            $sinacentos=array('a','e','i','o','u','n');
            $palabra=str_replace($acentos,$sinacentos,$palabra);
            $palabra=str_replace(" ","-",$palabra);
            return $data['img_category_by_provider'] = 'profile-'.$palabra.'.jpg';           
        }
    }
    /**
     *
     *
     */
    public function getActiveProvider($value = '', $id = '', $data = array()){
        if($data["phfd_provider_hide_for_distributor_id"]==''){
            return $data["pro_active_in_grid"]="on";
        }else{
            return $data["pro_active_in_grid"]="off";
        }
        
    }
    
    /**
     *
     *
     */
    public function inputOrderProvider($value = '', $id = '', $data = array()){
        $html = "<div class='dpm_order_sequ'>";
        $html .= "<input type='number' min='0' name='dpm_order_sequ' item-info='".$value."_".$data['us_user_id']."_".$data['dpm_distributor_provider_mapping_id']."' id='dpm_order_sequ-".$data['us_user_id']."' class='input-dpm_order_sequ' value='".$value."' style='width:40%;' /> ";
        $html .= "</div>";
        return $html;
    }

}

/* End of file Cit_General.php */
/* Location: ./application/libraries/Cit_General.php */
