<?php
defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Description of Partialviews Controller
 * @category ecommerce
 * @package Partialviews
 * @subpackage controllers
 * @module Partialviews
 * @class Partialviews.php
 * @path application\ecommerce\Partialviews\controllers\Partialviews.php
 * @version 4.0
 * @author CIT Dev Team
 * @since 01.08.2016
 */
class Quotation extends Cit_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->_request_params();
        $this->distributor_id = $this->session->userdata('iDistributorUserId');
        $this->salesman_id = $this->session->userdata('iUsersId');
        $this->load->model('model_custom_quotation');
        $this->load->model('model_quotation');

    }

    public function _request_params()
    {
        $this->get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
        $this->post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
        $this->params_arr = array_merge($this->get_arr, $this->post_arr);
        return $this->params_arr;
    }

    /*quotation form*/
    public function add($return = false){
        $mode = $this->params_arr['Mode']?$this->params_arr['Mode']:'Add';
        $inquiry_id = $this->params_arr['id'];
        $params = array();
        $params['distributor_id'] = $this->distributor_id;
        $params['product_inquiry_id'] = $inquiry_id;

        $quote_data = $this->cit_api_model->callAPI('get_product_inquiry_data',$params);
      // print_r($quote_data);
      // die;
        if($return){
            return $quote_data;
        }
        if($mode=='Update' || $mode=='Requote'){
            $this->smarty->assign("quote_data",$quote_data['data']['getleaddata'][0]);
            $this->smarty->assign('product_list',$quote_data['data']['getleadproducts']);
            $this->smarty->assign( "id",$inquiry_id);
            $this->productListFromQuote(true);
        }
        $first_time = false;
        if($quote_data['data']['getleaddata'][0]['lm_terms_conditions']=='' && $quote_data['data']['getleaddata'][0]['lm_delivery_time']=='' && $quote_data['data']['getleaddata'][0]['lm_payment_conditions']=='' && $quote_data['data']['getleaddata'][0]['lm_customer_comments']==''){
            $first_time = true;
        }

        $data = $this->model_custom_quotation->getDistributorSettingsData($this->distributor_id);
        $customer = $this->model_quotation->selectQuotationCustomers($this->distributor_id,$params['product_inquiry_id']);
        $customer_contact = $this->model_quotation->selectQuotationCustomers($this->distributor_id);
        $this->smarty->assign("hd_settings_data",$data[0]);
        $this->smarty->assign("mode",$mode);
        $this->smarty->assign("first_time",$first_time);
        $this->smarty->assign("customer_contact",$customer_contact);
        $this->smarty->assign("customer",$customer);
        $this->loadView('quotation_add');
    }


    /*save quotation data*/
    public function saveQuotationData(){
        $ret_arr = array();
        $params = array();
        $params['distributor_id'] = $this->distributor_id;
        $params['sales_person_id'] = $this->salesman_id;
        $params['sales_executive_name'] = $this->params_arr['cq_sales_executive'];
        $params['company_name'] = $this->params_arr['cq_company_name'];
        $params['contact_name'] = $this->params_arr['cq_lead_contact_name'];
        $params['contact_email'] = $this->params_arr['cq_lead_contact_email'];
        $params['mode'] = $this->params_arr['mode'];
        $params['qid'] = $this->params_arr['qid'];
        $params['requote'] = $this->params_arr['requote']?$this->params_arr['requote']:'';
        $params['old_uniq_id'] = $this->params_arr['old_uniq_id']?$this->params_arr['old_uniq_id']:'';
        /*print_r($this->params_arr);
        die;*/
        if($this->params_arr['type_contact'] == 'new_contact' && $this->params_arr['save_contact'] == 'si'){
            $no_repeat = $this->db->select()->from('distributor_customer_contact')->where('vEmailCustomerContact', $this->params_arr['cq_lead_contact_email'])->count_all_results();
            /*print_r($no_repeat);
            die;*/
            if($no_repeat > 0){
                $ret_arr['success'] = false;
                $ret_arr['message'] = "Este correo ya está registrado...";
                echo json_encode($ret_arr);
                exit;
            }
            $this->db->insert('distributor_customer_contact',
            [
            'iUsersId' => $this->distributor_id,
            'vCompanyNameCustomerContact' => $this->params_arr['cq_company_name'],
            'vNameCustomerContact' => $this->params_arr['cq_lead_contact_name'],
            'vEmailCustomerContact' => $this->params_arr['cq_lead_contact_email'],
            'dAddedDate' => date('Y-m-d h:i:s')
            /*'vLegalCompanyNameCustomerContact' =>,
            'vRfcCustomerContact' =>,
            'vPhoneCustomerContact' =>*/
            ]);
        }
        //die;
        $quotation_data = $this->cit_api_model->callAPI('add_new_quotation',$params);
        if($this->params_arr['mode']=='Update'){
            $quote_id = $this->params_arr['qid'];
        } else {
            $quote_id = $quotation_data['data'][0]['insert_id'];
        }
        $ret_arr['success'] = true;
        $ret_arr['message'] = "¡Los datos se actualizaron correctamente!";
        $ret_arr['url'] = $this->config->item('admin_url')."#quotation/quotation/add|Mode|Update|id|".$quote_id."|tab|2";
        if($this->params_arr['requote']=='true'){
            $quote_id = $quotation_data['data']['new_quote_id'];
            $ret_arr['requote_url'] = $this->config->item('admin_url')."#quotation/quotation/add|Mode|Update|id|".$quote_id."|tab|1";
        }
        echo json_encode($ret_arr); exit;
    }


    /*product form second tab*/
    public function product_form(){
        $product_id = $this->input->get('pid');
        $quotation_id = $this->input->get('qid');
        $inquery_id = $this->input->get('nid');
        $params = array();
        $params['product_id'] = $product_id;
        $params['quotation_id'] = $quotation_id;
        $params['inquery_id'] = $inquery_id;
        $quote_product_data = $this->cit_api_model->callAPI('get_product_details',$params);

        $this->smarty->assign("product_list",$quote_product_data['data'][0]);
        $this->smarty->assign("product_id",$product_id);
        $this->smarty->assign("inquery_id",$inquery_id);
        $this->loadView("product_form");
    }


    /*get list of products matching search criteria*/
    public function getSearchedProductsList(){

        $params_arr = $this->params_arr;
        $free_restrctions = $this->config->item('FREE_RESTRICTIONS');
        $partner_free_restrctions = $this->config->item('PARTNER_ACCOUNT_RESTRICTIONS');
        $ids = $this->input->get('ids');
        $nid = $this->input->get("nid");

        $keyword = $params_arr['q'];
        $live_package_code = $this->session->userdata('live_subscription_detail')[0]['vPackageCode'];
        $d_code =$this->session->userdata('vUCode');
       	if($this->session->userdata('iDistributorUserId')>0||$this->session->userdata('iDistributorUserId')!=''){
        $user=$this->session->userdata('iDistributorUserId');
        }else{
        $user=$this->session->userdata('iUsersId');
        }
        $this->db->select()->from("user_distributor_setting")->where("iUserId",$this->session->userdata('iAdminId'));
        $code=$this->db->get()->result_array();

	$this->db->select("pm.*,cm.vCategoryName,ccm.vColorName,TO_BASE64(CONCAT(u.vUCode,'-',pm.vPCode,'-','".$d_code."')) as customCode");

      if($nid !=''){
             $this->db->select($nid." as nid");
        }
        $this->db->from('product_master as pm');
        $this->db->join('users as u','pm.iProviderId=u.iUsersId','INNER');
        $this->db->join('category_master as cm','cm.iCategoryMasterId=pm.iCategoryId','LEFT');
        $this->db->join('color_master as ccm','ccm.iColorMasterId=pm.iColorId','LEFT');
        $this->db->join("distributor_provider_mapping as dpm","dpm.iProviderId=pm.iProviderId and dpm.iDistributorUserId=".$user,"left");
        $this->db->where('pm.eStatus','Active');
        $this->db->where('u.eStatus','Active');
        if(strtolower($live_package_code)=='free'  && strtolower(trim($free_restrctions)) == 'on'){
            $allowed_provider_ids = $this->config->item('FREE_PROVIDERS');
            $allowed_provider_ids_arr = explode(',', $allowed_provider_ids);
            $this->db->where_in('pm.iProviderId',$allowed_provider_ids_arr);
            $this->db->where('pm.iProductMasterId IN (SELECT fapl.iProductMasterId FROM free_account_product_list as fapl WHERE fapl.eStatus="Active")');
        }
        if(strtolower($live_package_code)=='partner_free'  && strtolower(trim($partner_free_restrctions)) == 'on'){
            $allowed_provider_ids = $this->config->item('FREE_PROVIDERS');
            $allowed_provider_ids_arr = explode(',', $allowed_provider_ids);
            $this->db->where_in('pm.iProviderId',$allowed_provider_ids_arr);
            $this->db->where('pm.iProductMasterId IN (SELECT fapl.iProductMasterId FROM free_account_product_list as fapl WHERE fapl.eStatus="Active")');
        }
        if($ids !=''){
             $this->db->where_in('pm.iProductMasterId',$ids);
        }

        $pcode = base64_decode($keyword);
        $c = explode('-',$pcode);
        if($code[0]['eUseCustomCodesForProduct']=='Yes'&&$keyword != ''){
         $this->db->where("  ( pm.vProductParentCode LIKE '%".$keyword."%'  OR  pm.vProviderUniqueKey LIKE '%".$keyword."%'  OR  TO_BASE64(CONCAT(u.vUCode,'-',pm.vPCode,'-','".$d_code."')) LIKE '%".$keyword."%' ) ", FALSE, FALSE);
        }
        else if ($keyword != '')
        {
            $this->db->where("  ( pm.vProductParentCode LIKE '%".$keyword."%'  OR  pm.vProviderUniqueKey LIKE '%".$keyword."%' ) ", FALSE, FALSE);
        }
        $this->db->limit(24);
        $data = $this->db->get()->result();
        /*echo"<pre>";
         print_r($this->db->last_query());
        die;*/
        $arra = array();
        foreach ($data as $key => $value) {
            if($code[0]['eUseCustomCodesForProduct']=='Yes'){
                $arra[]=array("id"=>$value->iProductMasterId,"val"=>$value->vProviderUniqueKey.' - ('.$value->customCode.')');
            }else{
                $arra[]=array("id"=>$value->iProductMasterId,"val"=>$value->vProviderUniqueKey);
            }

        }

        echo json_encode($arra);exit;
    }

    /*get detail of selected product*/
    public function getProductDetail($pid=array(),$is_get_data=false){
        $post_arr = $this->input->post();
        $product_id = $post_arr['id'];
        $this->db->select("pm.*,cm.vColorName,mm.vMaterialName,ups.tOfferPrintingNote,u.vUCode");
        $this->db->from("product_master as pm");
        $this->db->join('color_master as cm', 'cm.iColorMasterId = pm.iColorId');
        $this->db->join('users as u', 'pm.iProviderId = u.iUsersId');
        $this->db->join('user_provider_settings as ups','pm.iProviderId=ups.iUserId','LEFT');
        $this->db->join('material_master as mm', 'mm.iMaterialMasterId = pm.iMaterialId',"left");
        if(empty($pid)){
            $this->db->where("pm.iProductMasterId",$product_id);
        } else {
            $this->db->where_in("pm.iProductMasterId",$pid);
        }
        $this->db->where("pm.eStatus",'Active');
        $data = $this->db->get()->result_array();


        $this->db->select('uds.eUseCustomCodesForProduct,u.vUCode');
        $this->db->from('user_distributor_setting as uds');
        $this->db->from('users as u','uds.iUserId=u.iUsersId');
        $this->db->where('uds.iUserId',$this->distributor_id);
        $this->db->where('u.iUsersId',$this->distributor_id);
        $dist_data = $this->db->get()->row_array();

        $is_custom_code = $dist_data['eUseCustomCodesForProduct'];
        $u_code = $dist_data['vUCode'];
        if($is_custom_code=='Yes'){
            $custom_code = $data[0]['vUCode']."-".$data[0]['vPCode'].'-'.$u_code;
            $custom_code = str_replace("=", "",base64_encode($custom_code));
        } else {
            $custom_code = $data[0]['vProviderUniqueKey'];
        }

        $data[0]['custom_code'] = $custom_code;
        if($is_get_data){
            return $data;
        }else{
            $ret_ara = array();
            $ret_ara['success'] = true;
            $ret_ara['data'] = $data;
            echo json_encode($ret_ara); exit;
        }
    }

    /*get scale price of selected product*/
    public function getScalePriceRow(){
        $post_arr = $this->input->post();
        $product_id = $post_arr['id'];
        $qty = $post_arr['qty'];
        if($product_id==''){
            $product_id = 0;
        }
        if($qty==''){
            $qty=0;
        }
        $scale_pricing_data = $this->general->getScalePricingQuery($product_id,$this->session->userdata('iAdminId'),true,$this->session->userdata('fDistibutorProfitDiscount'));
        $scale_row = array();
        foreach ($scale_pricing_data as $key => $scale) {
          if($qty==$scale['iMaxQunatity']){
            $scale_row = $scale;
            break;
          }
          if($qty<$scale['iMaxQunatity']){
            $scale_row = $scale_pricing_data[$key-1];
            break;
          }
          if($qty>$scale['iMaxQunatity']){
            if(isset($scale_pricing_data[$key+1])){
                $scale_row = $scale_pricing_data[$key+1];
            } else {
                $scale_row = $scale;
            }
          }
        }
        if(count($scale_row) == 0 && count($scale_pricing_data)>0){
            $scale_row = $scale_pricing_data[0];
        }
        $return_arr = array();
        $return_arr['success'] = true;
        $return_arr['data'] = $scale_row;
        echo json_encode($return_arr); exit;
    }

    /*uplaod image for product to use in quotation*/
    public function upload_image_file($product_id)
    {
        $dir_path = $this->config->item('upload_path').'quotation_files/';
        $dir_url = $this->config->item('upload_url').'quotation_files/';
        if($product_id!=''){
            $dir_path = $this->config->item('upload_path').'quotation_files/'.$product_id;
            $dir_url = $this->config->item('upload_url').'quotation_files/'.$product_id.'/';
            if(!file_exists($dir_path)){
                $dir = mkdir($dir_path);
            }
        }
        $config = array(
            'upload_path'   => $dir_path,
            'allowed_types' => 'jpg|jpeg|gif|png|pdf',
            'overwrite'     => 1,
        );
        $this->load->library('upload', $config);
        $images = array();
        $files = $_FILES['cq_product_image_file'];
        /*$_FILES['cq_product_image_file']['name']= $files['name'];*/
        $files['name']=str_replace(' ','_',$files['name']);
        $_FILES['cq_product_image_file']['name'] = $files['name'];
        /*$cadena =str_replace(' ', '', $cadena);*/
        $_FILES['cq_product_image_file']['type']= $files['type'];
        $_FILES['cq_product_image_file']['tmp_name']= $files['tmp_name'];
        $_FILES['cq_product_image_file']['error']= $files['error'];
        $_FILES['cq_product_image_file']['size']= $files['size'];
        $config['file_name'] = uniqid().'cq_product_image_file_'.time().'_'.$files['name'];
        $images['files']['cq_product_image_file'][] = array($config['file_name'],$files['name']);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('cq_product_image_file')) {
            $uploadData = $this->upload->data();
        }
        $ret_arr = array();
        $ret_arr['url'] = $dir_url.$images['files']['cq_product_image_file'][0][0];
        $ret_arr['image_type'] = "Local";
        return $ret_arr;
    }

    /*save product data in database*/
    public function saveProductData(){
        $product_arr = array();
        $product_id = 0;
        $quote_id = $this->params_arr['qid'];
        $nid = $this->params_arr['nid'];

        $product_arr['product_type'] = $this->params_arr['cc_sh_cq_product_type'];
        if($this->params_arr['cc_sh_cq_product_type']=='Existing'){
            $product_id = $this->params_arr['existing_product_id'];
            $product_arr['product_id'] = $this->params_arr['existing_product_id'];
            $product_arr['product_code'] = $this->params_arr['existing_product_code'];
        } else {
            $product_id  = $this->params_arr['custom_product_id'];
            $product_arr['product_id'] = $this->params_arr['custom_product_id'];
            $product_arr['product_code'] = $this->params_arr['cq_product_code_text'];
            $product_arr['new_product_id'] = $this->params_arr['custom_product_id'];
        }
        $files_arr = $_FILES;
        if(!empty($files_arr) && $files_arr['cq_product_image_file']['error']==0){
            $upload_data = array();
            $upload_data = $this->upload_image_file($product_id);
            $product_arr['image_type'] = $upload_data['image_type'];
            $product_arr['image_url'] = $upload_data['url'];
        } else {
            $product_arr['image_type'] = $this->params_arr['cq_product_image_type'];
            $product_arr['image_url'] = $this->params_arr['cq_product_image_url'];
        }
        $product_arr['parent_code'] = $this->params_arr['cq_our_parent_code'];
        $product_arr['description'] = $this->params_arr['cq_product_description'];
        $product_arr['color'] = $this->params_arr['cq_product_color'];
        $product_arr['material'] = $this->params_arr['cq_product_material'];
        $product_arr['measures'] = $this->params_arr['cq_product_measures'];
        $product_arr['printing_technique'] = $this->params_arr['cq_product_printing_technique'];
        $product_arr['comment'] = $this->params_arr['cq_product_comment'];
        $product_arr['quantity'] = $this->params_arr['cq_product_quantity'];
        $qty = $this->params_arr['cq_product_quantity'];
        $profit = $this->session->userdata('fDistibutorProfitDiscount');

        $selling_price = $this->params_arr['cq_product_selling_price'];
        $extra_cost = $this->params_arr['cq_product_extra_cost'];
        $total_price = $selling_price + $extra_cost;
        $grand_total_price = ($qty * $total_price);

        $product_arr['selling_price'] = $selling_price;
        $product_arr['extra_cost'] = $extra_cost;
        $product_arr['total_price'] = $total_price;
        $product_arr['provider_id'] = $this->params_arr['cq_provider_id'];


        $product_arr['grand_total_price'] = $grand_total_price;
        $product_arr['extra_charges_comment'] = $this->params_arr['cq_product_extra_charges_comment'];
        $product_arr['added_date'] = $this->params_arr['cq_added_date'];
        $product_arr['quote_id'] = $quote_id;
        $product_arr['nid'] = $nid;
        $product_arr['mode'] = $this->params_arr['mode'];
        //echo "<pre>";
        //print_r($product_arr);
        //wdie;
        $quotation_product_data = $this->cit_api_model->callAPI('add_new_product',$product_arr);
        echo "<script>parent.jQuery.fancybox.close();parent.Project.setMessage('¡Producto actualizado correctamente!',1);window.parent.reload_table();</script>";
        exit;
    }

    /*update comment and condition section third tab in quotation module*/
    public function updateCommentConditions(){
        $params = array();
        $params['quote_id'] = $this->params_arr['qid'];
        $params['delivery_time'] = $this->params_arr['cq_lead_delivery_time'];
        $params['payment_conditions'] = $this->params_arr['cq_lead_payment_condition'];
        $params['product_policy'] = $this->params_arr['cq_terms_conditions'];
        $params['customer_comments'] = $this->params_arr['cq_customer_comments'];
        $comments_data = $this->cit_api_model->callAPI('update_quote_comments_section',$params);
        $ret_arr = array();
        if($comments_data['settings']['success']==1){
            $ret_arr['success'] = true;
        } else {
            $ret_arr['success'] = false;
        }
        $send = $this->params_arr['is_send'];
        $email_ret_arr = array();
        if($send=='true'){
            $email_ret_arr = $this->send_email_to_all($this->distributor_id,$this->params_arr['qid'],"save_send");
        }
        $ret_arr['message'] = $email_ret_arr['message']?$email_ret_arr['message']:"¡Cotización creada correctamente!";
        $ret_arr['success'] = $email_ret_arr['success']?$email_ret_arr['success']:"true";
        if($this->session->userdata('verifiedUser')=='No'){
            $ret_arr['url'] = $this->config->item('admin_url')."#user/setting_new/newInit";
        }else{
            $ret_arr['url'] = $this->config->item('admin_url')."#crm/product_inquiry_v1/index";
        }

        echo json_encode($ret_arr); exit;
    }

    /*delete product from quotation*/
    public function deleteProduct(){
        $params = array();
        $params['quotation_id'] = $this->params_arr['qid'];
        $params['product_id'] = $this->params_arr['pid'];
        $params['lpi_lead_product_item_id'] = $this->params_arr['rowid'];
        $deleted_product = $this->cit_api_model->callAPI('delete_product',$params);
        $ret_arr = array();
        if($deleted_product['settings']['success']==1){
            $ret_arr['success'] = true;
        } else {
            $ret_arr['success'] = false;
        }
        $ret_arr['message'] = $deleted_product['settings']['message'];
        echo json_encode($ret_arr); exit;
    }
    /*reset product listing in quotation module*/
    public function reset_product_table(){
        $this->loadView('reset_product_table');
    }
    /*load product listing in quotation module*/
    public function load_product_table(){
        $quote_data = $this->add(true);
        $this->smarty->assign("quote_data",$quote_data['data']['getleaddata'][0]);
        $this->smarty->assign('product_list',$quote_data['data']['getleadproducts']);
        $this->smarty->assign("id",$this->params_arr['id']);
        $this->loadView('product_table');
    }
    /*update number of times user has opened VS tool */
    public function update_virtual_sample_count(){
        $quote_data = $this->general->getQuoteCount($this->distributor_id,$inserted_lead_id);
        $live_package_code = $this->session->userdata('live_subscription_detail')[0]['vPackageCode'];
        $allowed_quote_count = $this->config->item('SAMPLE_PER_DAY_FREE_TRIAL');
        $allow_send_mail = true; //Change it to false if want to add restrictions
        $current_date = date('Y-m-d H:i:s');
        /*if(strtolower($live_package_code)=='free'){
            if(!empty($quote_data)){
                $quote_sent_date = $quote_data['dtAddedDate'];
                $sent_count = $quote_data['iSampleCount'];
                if($sent_count<$allowed_quote_count){
                    $allow_send_mail = true;
                }
            } else {
                $allow_send_mail = true;
            }
        } else {
            $allow_send_mail = true;
        }*/
        if($allow_send_mail==true){
            $success = true;
            $message = "muestra virtual";
            /*Update Quote Last send date*/
              /*  $q_count = 0;
                $q_count = $quote_data['iSampleCount']?($quote_data['iSampleCount']+1):($q_count+1);
                $data_arr = array(
                    'dtAddedDate'=>$current_date,
                    'iSampleCount'=>$q_count,
                    'iDistributorUserId'=>$this->distributor_id,
                    'iQuotationId'=>0
                );
                if(!empty($quote_data)){
                    $where = array('iDistributorUserId'=>$this->distributor_id);
                    $this->db->where($where);
                    $this->db->update("distributor_quote_transaction",$data_arr);
                } else {
                    $this->db->insert("distributor_quote_transaction",$data_arr);
                }*/
            /*Update Quote Last send date*/
        } else {
            $success = false;
            $message = "En el paquete gratuito puede enviar solo ".$allowed_quote_count." muestra virtual por día";
        }
        $ret_arr = array();
        $ret_arr['success'] = $success;
        $ret_arr['message'] = $message;
        echo json_encode($ret_arr); exit;
    }
    /*send email to all after saving of quotation form*/
    public function send_email_to_all($distributor_id='',$inserted_lead_id='',$mail_mode=''){
        //$distributor_id=2207;
        /*Get Quote Count */

        $quote_data = $this->general->getQuoteCount($distributor_id,$inserted_lead_id);

        $allowed_quote_count = $this->config->item('QUOTE_PER_DAY_FREE_TRIAL');
        $allow_send_mail = true; //change it to false if want to set restrictions
        $current_date = date('Y-m-d H:i:s');
        $live_package_code = $this->session->userdata('live_subscription_detail')[0]['vPackageCode'];
        if(strtolower($live_package_code)=='free'){
            if(!empty($quote_data)){
                $quote_sent_date = $quote_data['dtAddedDate'];
                $sent_count = $quote_data['iQuoteCount'];
                if($sent_count<$allowed_quote_count){
                    $allow_send_mail = true;
                }
            } else {
                $allow_send_mail = true;
            }
        } else {
            $allow_send_mail = true;
        }
        $success = "false";
        $message = "something went wrong";
        $params_arr = array();
        $params_arr['quotation_id'] = $inserted_lead_id;
        $params_arr['distributor_id'] = $distributor_id;

        $post_arr = $this->cit_api_model->callAPI('get_lead_data_for_quotation',$params_arr);

        $dist_email_temps = $this->model_custom_quotation->getDistributorEmailTemplates($distributor_id);

        /*Generate PDF*/
           $save_dist = $this->general->generate_pdf_portions($inserted_lead_id,$distributor_id,'pdf','Distributor','quotation');
           $save_cust = $this->general->generate_pdf_portions($inserted_lead_id,$distributor_id,'pdf','Customer','quotation');
        /*Generate PDF*/

        $cust_arr = array();
        $cust_arr[0]['filename'] = $save_cust['pdf_path'];
        $cust_arr[0]['position'] = 'attachment';
        $cust_arr[0]['newname'] = 'Quotation.pdf';

        $dist_arr = array();
        $dist_arr[0]['filename'] = $save_dist['pdf_path'];
        $dist_arr[0]['position'] = 'attachment';
        $dist_arr[0]['newname'] = 'Quotation.pdf';

        $params = array();
        $params['distributor_id'] = $distributor_id;

        $dist_company_details = $this->cit_api_model->callAPI('distributor_details',$params);

        if($dist_company_details['settings']['success']==1){
            $dist_company_details = $dist_company_details['data'][0];
        }
        $input_params=array();
        $input_params['contact_us_eamil_templates']=$dist_email_temps;
        $input_params['complete_inquiry']="";//$ret_data_dist;
        $input_params['complete_inquiry_customer']="";//$ret_data_cust;
        $input_params['unique_id']=$post_arr['data'][0]['lm_lead_unique_id'];
        $input_params['customer_name']= $post_arr['data'][0]['lm_customer_name'];
        $input_params['distributor_name']= $this->session->userdata('vName');

        $input_params['customer_email'] = $post_arr['data'][0]['lm_email'];

        $data = $this->getExtraEmail($input_params['customer_email']);

         if($data[0]['eCopyEmail'] == 'Yes'){
			$CC = $data[0]['vAlternateEmail'];
			 $input_params['costumer_cc_email'] = $CC;
		}else{
            $input_params['costumer_cc_email'] = '';
        }

        $input_params['company_name']= $post_arr['data'][0]['lm_company_name'];
        $input_params['distributor_sales_person']= $post_arr['data'][0]['lm_assigned_sales_person_name'];
        $input_params['email'] = $this->session->userdata('vEmail');

        $input_params['distributor_logo'] = $dist_company_details['u_company_logo'];
        $input_params['distributor_company_email'] = $dist_company_details['u_company_email'];
        $input_params['distributor_company_name'] = $dist_company_details['u_company_name'];
        $input_params['distributor_company_contact_number'] = $dist_company_details['u_contact_number'];
        $input_params['distributor_name'] = $dist_company_details['u_name'];
        $input_params['customer_pdf']=$cust_arr;
        $input_params['distributor_pdf']=$dist_arr;

        if($allow_send_mail==true){
            $success = "true";
            $message = "¡Cotización enviada correctamente!";
            /*Update Quote Last send date*/
                $q_count = 0;
                $q_count = $quote_data['iQuoteCount']?($quote_data['iQuoteCount']+1):($q_count+1);
                $data_arr = array(
                    'dtAddedDate'=>$current_date,
                    'iQuoteCount'=>$q_count,
                    'iDistributorUserId'=>$distributor_id,
                    'iQuotationId'=>$inserted_lead_id
                );
                if(!empty($quote_data)){
                    $where = array('iDistributorUserId'=>$distributor_id);
                    $this->db->where($where);
                    $this->db->update("distributor_quote_transaction",$data_arr);
                } else {
                    $this->db->insert("distributor_quote_transaction",$data_arr);
                }
               /*echo "<pre>";
         print_r($input_params);
        die;*/
             /*Update Quote Last send date*/
            $this->general->send_distributor_email($input_params);
        } else {
            $success = "false";
            $message = "En el paquete gratuito puede enviar solo ".$this->config->item('QUOTE_PER_DAY_FREE_TRIAL')." cotización por día";
        }
        $return_arr = array();
        $return_arr['success'] = $success;
        $return_arr['message'] = $message;
        if($mail_mode=='Direct'){
            $return_arr['verified']=$this->session->userdata('verifiedUser');
            $return_arr['url']=$this->config->item('site_url')."distributor/#user/setting_new/newInit#user/setting_new/newInit";
            echo json_encode($return_arr); exit;
        } elseif($mail_mode=='save_send'){
            return $return_arr;
        }
    }
    public function	getExtraEmail($data = ''){
		$this->db->select("us.iUsersId, us.vAlternateEmail, ups.eCopyEmail");
		$this->db->from("users as us");
        $this->db->join("user_provider_settings as ups","ups.iUserId = us.iUsersId");
		$this->db->where("us.vEmail",$data);
        $this->db->where("us.iUserType",2);
		$result_obj = $this->db->get();
		$res = is_object($result_obj) ? $result_obj->result_array() : array();
		return $res;
	}


}
