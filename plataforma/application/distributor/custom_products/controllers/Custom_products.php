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
class Custom_products extends Cit_Controller
{
	public function __construct()
    {
        parent::__construct();   
        $this->_request_params();
    }

    public function _request_params()
    {
        $this->get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
        $this->post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
        $this->params_arr = array_merge($this->get_arr, $this->post_arr);
        return $this->params_arr;
    }
    /*load grid view tpl*/
    public function grid_view(){
    	$this->loadView("grid_view");	
    }
    /*product details view*/
    public function detail_view(){
        $post_arr = $this->params_arr;
        $product_id = $post_arr['id'];
        $distributor_id = $this->session->userdata('iAdminId');
        $params = array();
        $params['product_id'] = $product_id;
        $params['distributor_id'] = $distributor_id;
        $product_data = $this->cit_api_model->callAPI("distributor_product_details", $params);

        $product_success = $product_data['settings']['success'];
        if($product_success==1){
            $color_arr = array();
            foreach ($product_data['data']['getcolordata'] as $key => $value) {
                $color_arr[$key] = $value['vColorName'];
            }
            $colors = implode(',', $color_arr);
            $this->smarty->assign("product_details",$product_data['data']['getproductdetail'][0]);
            $this->smarty->assign("colors",$colors);
            $this->smarty->assign("product_printing_data",$product_data['data']['getproviderprintingnotes'][0]);
        }
        $single_product_images_row = $this->general->get_single_product_images($product_data['data']['getproductdetail'][0]['pm_product_parent_code'],$product_data['data']['getproductdetail'][0]['pm_provider_id']);
        $images_arr = array();
        foreach ($single_product_images_row as $key => $value) {
            if($value['eImageType']=='Online'){
                $images_arr[$key]['image_url'] = $value['image_online'];
            } else {
                $path = $this->config->item('upload_path');
                if(file_exists($path."product_images/".$value['image_name'])){
                    $images_arr[$key]['image_url'] = $this->config->item('upload_url')."product_images/".$value['image_name'];
                } else {
                    $images_arr[$key]['image_url'] = $this->config->item('images_url')."noimage.gif"; 
                }
            }
            $images_arr[$key]['product_id'] = base64_encode($value['product_id']);
            $images_arr[$key]['pid'] = $value['product_id'];
            $images_arr[$key]['color'] = $value['vColorName'];
        }
        $colors_new_arr = array();
        foreach ($single_product_images_row as $key => $value) {
            $colors_new_arr[$value['product_id']] = $value['vColorName'];
        }
        $colors_new = implode(',', $colors_new_arr);
        $this->smarty->assign("all_colors_arr",$colors_new_arr);
        $this->smarty->assign("all_colors",$colors_new);
        $this->smarty->assign("single_product_images_row",$images_arr);


        $provider_id = $product_data['data']['getproductdetail'][0]['pm_provider_id'];
        $inp_params = array();
        $inp_params['provider_id'] = $provider_id;
        $provider_data = $this->cit_api_model->callAPI("distributor_provider_details", $inp_params);
        $provider_success = $provider_data['settings']['success'];
        if($provider_success==1){
            $this->smarty->assign("provider_details",$provider_data['data']['getproviderdetails_v1'][0]);
            $this->smarty->assign("provider_contact_details",$provider_data['data']['getprovidercontacts']);
            $this->smarty->assign("provider_reff_details",$provider_data['data']['get_provider_reff']);
            $this->smarty->assign("provider_policies",$provider_data['data']['getproviderpolicies']);
        }
        $live_subscription = $this->session->userdata('live_subscription_detail');
        $packages_allowed = array();
        foreach ($live_subscription as $key => $value) {
            $packages_allowed[$key]['package'] = $value['vPackageCode'];
        }
        $live_package_code = $packages_allowed[0]['package'];
        $packages_arr = array('FREE','PREMIUM','WEB_PROMOTIONAL_PREMIUM','PARTNER_FREE');
        $free_arr = array('FREE');
        $is_free_static_price = $this->config->item('IS_FREE_STATIC_PRICE');
        if(in_array($live_package_code, $free_arr) && strtolower($is_free_static_price)=='yes' && strtolower($product_data['data']['getproductdetail'][0]['pm_product_type'])=='default'){
            $show_false_price = true;
        } else {
            $show_false_price = false;
        }

        $profit = $this->session->userdata('fDistibutorProfitDiscount');
        $scale_pricing_data = $this->general->getScalePricingQuery($product_id,$distributor_id,false,$profit,$show_false_price);

        if(in_array($live_package_code, $packages_arr)){
            $show_virtual_sample = true;
            $crm = true;
        } else {
            $show_virtual_sample = false;
            $crm = false;
        }
        $this->smarty->assign("show_virtual_sample",$show_virtual_sample);
        $this->smarty->assign("crm",$crm);
        $this->smarty->assign("show_false_price",$show_false_price);
        $this->smarty->assign("scale_pricing_data",$scale_pricing_data);

        $input_params = array();
        $input_params['distributor_id'] = $distributor_id;
        $settings_data = $this->cit_api_model->callAPI("get_distributor_settings", $input_params);
        $settings_success = $settings_data['settings']['success'];
        
        if($settings_success==1){
            $this->smarty->assign("settings_details",$settings_data['data']['getdistributorsettings'][0]);
            $this->smarty->assign("terms_data",$settings_data['data']['gettermsdata']);
        }
        $this->smarty->assign("provider_id",$provider_id);
        $this->smarty->assign("live_package_code",$live_package_code);
        $this->smarty->assign("distributor_id",$distributor_id);
		
		$this->db->select("pc.vEmail, dpm.iDistributorProviderMappingId, us.vCompanyName, us.vLegalCompanyName, us.vVerifyWeb, dpm.iProviderId, dpm.vProviderName, dpm.vUniqueID, dpm.fProviderDiscount, dpm.fProviderProfit, dpm.iSeqNo, dpm.dAddedDate, dpm.dModifiedDate, dpm.eProviderType, dpm.eSortType");
		$this->db->from("distributor_provider_mapping as dpm ");
		$this->db->join("users as us","dpm.iProviderId = us.iUsersId", "INNER");
		$this->db->where("dpm.iProviderId",$provider_id);
		$this->db->where("dpm.iDistributorUserId",$distributor_id);
		$result_obj = $this->db->get();
		$dataMap = is_object($result_obj) ? $result_obj->result_array() : array();
		
		$this->smarty->assign("dataMap",$dataMap);
		
		$res_Email = $this->getExtraEmail($provider_id);
		
		$CopyEmail = $res_Email[0]['eCopyEmail'];
		$CC = $res_Cc[0]['vAlternateEmail'];
		
		$this->smarty->assign("copy_email",$CopyEmail);
	
        $this->loadView("product_detail_index");   
    }
	
	
	
      /*provider details view*/
    public function provider_detail_view(){
        $post_arr = $this->params_arr;
        $provider_id = $post_arr['id'];
		$provider_contact = $post_arr['provider_contact'];
		$vista = $post_arr['vista'];
        $distributor_id = $this->session->userdata('iAdminId');
        $inp_params = array();
        $inp_params['provider_id'] = $provider_id;
        $provider_data = $this->cit_api_model->callAPI("distributor_provider_details", $inp_params);
        $provider_success = $provider_data['settings']['success'];
        $live_package_code = $this->session->userdata('live_subscription_detail')[0]['vPackageCode'];
        if($provider_success==1){
            $this->smarty->assign("provider_details",$provider_data['data']['getproviderdetails_v1'][0]);
            $this->smarty->assign("provider_contact_details",$provider_data['data']['getprovidercontacts']);
            $this->smarty->assign("provider_reff_details",$provider_data['data']['get_provider_reff']);
            $this->smarty->assign("provider_policies",$provider_data['data']['getproviderpolicies']);
        }

        $inp_params = array();
        $inp_params['provider_id'] = $provider_id;
        $provider_settings_data = $this->cit_api_model->callAPI("get_provider_settings", $inp_params);
        $provider_settings_success = $provider_settings_data['settings']['success'];
        if($provider_settings_success==1){
            $this->smarty->assign("provider_settings_data",$provider_settings_data['data'][0]);
        }

        $this->smarty->assign("provider_id",$provider_id);
        $this->smarty->assign("live_package_code",$live_package_code);
        $this->smarty->assign("distributor_id",$distributor_id);
		
		$this->db->select("dpm.iDistributorProviderMappingId");
		$this->db->select("us.vCompanyName");
		$this->db->select("us.vLegalCompanyName");
		$this->db->select("us.vName");
		$this->db->select("dpm.vUniqueID");
		$this->db->select("dpm.iProviderId");
		$this->db->select("dpm.vProviderName");
		$this->db->select("dpc.vEmailProviderContact");
		$this->db->select("dpc.iDistributorProviderContactId");
		$this->db->select("dpm.fProviderDiscount");
		$this->db->select("dpm.fProviderProfit");
		$this->db->select("dpm.iSeqNo");
		$this->db->select("dpm.dAddedDate");
		$this->db->select("dpm.dModifiedDate");
		$this->db->select("dpm.eProviderType");
		$this->db->select("dpm.eSortType");
		$this->db->from("distributor_provider_mapping as dpm ");
		$this->db->join("users as us","dpm.iProviderId = us.iUsersId", "INNER");
		$this->db->join("distributor_provider_contact as dpc","dpc.iProviderId = us.iUsersId", "LEFT");
		$this->db->where("dpm.iProviderId",$provider_id);
		$this->db->where("dpm.iDistributorUserId",$distributor_id);
		//$this->db->where("dpc.iDistributorProviderContactId",$provider_contact);
		$this->db->limit(1);
		$result_obj = $this->db->get();		
		$dataMap = is_object($result_obj) ? $result_obj->result_array() : array();
		if(count($dataMap)==0){
			$this->db->select();
			$this->db->from("users as us");
			$this->db->where("us.iUsersId",$provider_id);
			$result_obj = $this->db->get();		
			$provider = is_object($result_obj) ? $result_obj->result_array() : array();
			$dataMap=array(
				[
				'iDistributorProviderMappingId'=>0,
				'vCompanyName'=>$provider[0]['vCompanyName'],
				'vLegalCompanyName'=>$provider[0]['vLegalCompanyName'],
				'vName'=>$provider[0]['vName'],
				'UserId'=>$provider[0]['vUniqueID'],
				'vEmail'=>$provider[0]['vEmail'],
				'iProviderId'=>$provider_id,
				'vEmailProviderContact'=>$provider[0]['vEmailProviderContact'],
				'iDistributorProviderContactId'=>$provider[0]['iDistributorProviderContactId'],
				'vProviderName'=>$provider[0]['vCompanyName'],
				'vUniqueID'=>$provider[0]['vUniqueID'],
				'fProviderDiscount'=>0.00,
				'fProviderProfit'=>100.00,
				'iSeqNo'=>1,
				'dAddedDate'=>date("Y-m-d h:i:s"),
				'dModifiedDate'=>date("Y-m-d h:i:s"),
				'eProviderType'=>'Default',
				'eSortType'=>'Default',
				'mod'=>'insert',
				]
						   );
			/*echo "<pre>";
			print_r($provider);
			die;*/
		}
		$this->smarty->assign("vista",$vista);
		$this->smarty->assign("dataMap",$dataMap);
		
		$res_Email = $this->getExtraEmail($provider_id);
		
		$CopyEmail = $res_Email[0]['eCopyEmail'];
		$CC = $res_Cc[0]['vAlternateEmail'];
		
		$this->smarty->assign("copy_email",$CopyEmail);
		
		$distr_detail = $this->getDistrDetail($distributor_id );
		$this->smarty->assign("distr_detail",$distr_detail);
		
        $this->loadView("provider_detail_index");
		
    }

    
    /*search popup in product listing: search product*/
    public function search_product($action=''){  
        $get= $this->input->get();
        if($action!=''){
            $this->smarty->assign("action",$action);
        } else {
            $action = $this->config->item('admin_url');
            $this->smarty->assign("action",$action);
        }
        return $this->parser->parse('search_product.tpl',$get,true);     
    }

    /*search popup in product listing: get quotation type*/
    public function select_quotation_type(){  
        $get= $this->input->get();
        return $this->parser->parse('select_quotation_type.tpl',$get,true);     
    }

    /*search popup in product listing: search provider*/
    public function search_provider($action=''){   
        $get= $this->input->get();
        if($action!=''){
            $this->smarty->assign("action",$action);
        } else {
            $action = $this->config->item('admin_url');
            $this->smarty->assign("action",$action);
        }
        return $this->parser->parse('search_provider.tpl',$get,true);       
    }


    /*search popup in product listing: get category listing*/
    public function get_categories(){
        $keyword = $this->input->get('q');
        $ids = $this->input->get('ids');
        $whr = '';
        if($ids!='')
        $whr= " AND c.iCategoryMasterId  IN(".$ids.") ";
        elseif($keyword!='')
        $whr= " AND c.vCategoryName   LIKE '%".$keyword."%' ";    

        $query = $this->db->query("select c.vCategoryName as catname,coalesce(dcm.vCategoryName,c.vCategoryName) as cname
        ,sub.vCategoryName as parentname
        , IFnull(dcm.iSeqNo,'-') as seqno,c.eCategoryType
        ,IF(dcm.eDisplayInLeftMenu='','Yes',dcm.eDisplayInLeftMenu) as leftmenu
        ,coalesce(dcm.eStatus,c.eStatus) as finalstatus2
        ,c.iCategoryMasterId,c.iParentId,c.eStatus as cstatus,dcm.iDistributorCategoryTransId,dcm.eStatus as dstatus,dcm.eDisplayInLeftMenu
        ,dcm.iDistributorUserId
        ,(select count(p.iProductMasterId) from product_master as p 
        where (p.iCategoryId=c.iCategoryMasterId or p.iSubCategoryId=c.iCategoryMasterId)  
        and p.eStatus='Active') as totproduct
        from category_master as c
        left join category_master as sub on sub.iCategoryMasterId=c.iParentId and sub.iParentId=0
        left join distributor_category_trans as dcm on dcm.iCategoryMasterId=c.iCategoryMasterId and dcm.iDistributorUserId='".$this->session->userdata('iAdminId')."'
        where 
        c.eStatus='Active' and c.vCategoryName!='' ".$whr."
        and  ((c.iDistributorUserId='".$this->session->userdata('iAdminId')."' and c.eCategoryType='Custom') or c.eCategoryType='Default')
        order by c.eCategoryType asc,dcm.iSeqNo");
        $res = $query->result();
        $arra = array();

        foreach ($res as $key => $value) {
            if($value->iParentId==0){
                $arra[]=array("id"=>$value->iCategoryMasterId,"val"=>$value->catname,"value"=>$value->catname);
            }
        }  
        echo json_encode($arra);exit;
    }


    /*search popup in product listing: get sub category listing*/
    public function get_sub_categories(){
        $keyword = $this->input->get('q');
        $parent_cats = $this->input->get('cat');
        $ids = $this->input->get('ids');
        $whr = '';
        if($ids!=''){
            $whr= " AND c.iCategoryMasterId  IN(".$ids.") ";
        } elseif($keyword!='') {
            $whr= " AND c.vCategoryName   LIKE '%".$keyword."%' ";   
        }

        if ($parent_cats!='' && $parent_cats!=0) {
            $whr .= " AND c.iParentId  IN(".$parent_cats.") ";
        } 

        $query = $this->db->query("select c.vCategoryName as catname,coalesce(dcm.vCategoryName,c.vCategoryName) as cname
        ,sub.vCategoryName as parentname
        , IFnull(dcm.iSeqNo,'-') as seqno,c.eCategoryType
        ,IF(dcm.eDisplayInLeftMenu='','Yes',dcm.eDisplayInLeftMenu) as leftmenu
        ,coalesce(dcm.eStatus,c.eStatus) as finalstatus2
        ,c.iCategoryMasterId,c.iParentId,c.eStatus as cstatus,dcm.iDistributorCategoryTransId,dcm.eStatus as dstatus,dcm.eDisplayInLeftMenu
        ,dcm.iDistributorUserId
        ,(select count(p.iProductMasterId) from product_master as p 
        where (p.iCategoryId=c.iCategoryMasterId or p.iSubCategoryId=c.iCategoryMasterId)  
        and p.eStatus='Active') as totproduct
        from category_master as c
        left join category_master as sub on sub.iCategoryMasterId=c.iParentId and sub.iParentId=0
        left join distributor_category_trans as dcm on dcm.iCategoryMasterId=c.iCategoryMasterId and dcm.iDistributorUserId='".$this->session->userdata('iAdminId')."'
        where 
        c.eStatus='Active' and c.vCategoryName!='' ".$whr."
        and  ((c.iDistributorUserId='".$this->session->userdata('iAdminId')."' and c.eCategoryType='Custom') or c.eCategoryType='Default')
        order by c.eCategoryType asc,dcm.iSeqNo");
        $res = $query->result();
        $arra = array();

        foreach ($res as $key => $value) {
            if($value->iParentId>0){
                $arra[]=array("id"=>$value->iCategoryMasterId,"val"=>$value->catname,"value"=>$value->catname);
            }
        }  
        echo json_encode($arra);exit;
    }


    /*search popup in product listing: get color listing*/
    public function get_colors(){
        $keyword = $this->input->get('q');
        $ids = $this->input->get('ids');


        if($ids!='')        
        $this->db->where_in('iColorMasterId',explode(",",$ids));
        
        $this->db->select("vColorName");
        $this->db->select("iColorMasterId");
        if($keyword!=''){
            $this->db->like('vColorName',$keyword);    
        }
        
        $this->db->where('eStatus','Active');
        $res = $this->db->get('color_master')->result_array();        
        $arra = array();
        foreach ($res as $key => $value) {
        $arra[]=array("id"=>$value['iColorMasterId'],"val"=>$value['vColorName']);
        }  
        echo json_encode($arra);exit;
    }

    /*search popup in product listing: get supplier listing*/
    public function get_supplier(){
        $keyword = $this->input->get('q');
        $ids = $this->input->get('ids');

         $whr = '';
        if($ids!='')
        $whr= " u.iUsersId  IN(".$ids.")  AND";
        elseif($keyword!='')
        $whr= " u.vLegalCompanyName LIKE '%".$keyword."%' AND ";  

        $live_package_code = $this->session->userdata('live_subscription_detail')[0]['vPackageCode'];
        $free_restrctions = $this->config->item('FREE_RESTRICTIONS');    
        if(strtolower($live_package_code)=='free' && strtolower(trim($free_restrctions))=='on'){
            $allowed_provider_ids = $this->config->item('FREE_PROVIDERS');
            $whr= " u.iUsersId IN  (". $allowed_provider_ids.") AND ";
        }

        $query = $this->db->query("SELECT `u`.`iUsersId` AS `iUsersId`, `u`.`iUsersId` AS `u_users_id`, `u`.`vLegalCompanyName` AS `u_name`, `u`.`vUniqueID` AS `u_unique_id`, IF(ups.edistributerdiscount = 'On', IF(dpm.iDistributorProviderMappingId > 0, `dpm`.`fProviderDiscount`, ups.fDistributerDiscountPer), 0) AS dpm_provider_discount, (COALESCE(`dpm`.`fProviderProfit`, 0)) AS dpm_provider_profit, `u`.`eProviderType` AS `u_provider_type`, `u`.`iDistributorUserId` AS `u_distributor_user_id`, `dpm`.`iDistributorProviderMappingId` AS `dpm_distributor_provider_mapping_id`, ('test') AS tab_action, `dpm`.`iDistributorUserId` AS `dpm_distributor_user_id` FROM `users` AS `u` LEFT JOIN `distributor_provider_mapping` AS `dpm` ON `dpm`.`iProviderId` = `u`.`iUsersId` AND `dpm`.`iDistributorUserId` = '".$this->session->userdata('iAdminId')."' LEFT JOIN `user_provider_settings` AS `ups` ON `ups`.`iUserId` = `u`.`iUsersId` WHERE  ". $whr."    u.iUserType = '2' AND u.eStatus ='Active' AND u.eIsEmailVerified ='Yes' AND `u`.`iSysRecDeleted` <> 1 ORDER BY `u_name` ASC LIMIT 20");

        $res = $query->result();
        $arra = array();
        foreach ($res as $key => $value) {
        $arra[]=array("id"=>$value->iUsersId,"val"=>$value->u_name);
        }  
        echo json_encode($arra);exit;
    }


    /*submits form of distributor provider contact*/
	public function submit_contact_form(){
        $distributor_id = $this->session->userdata('iAdminId');
		$post_arr = $this->params_arr;
        $params = array();
        $params['provider_id'] = $post_arr['provider_id'];
        $params['distributor_id'] = $post_arr['distributor_id'];
		$params['provider_email'] = $post_arr['provider_email'];
		$params['contact_name'] = $post_arr['contact_name'];
        $params['name'] = $post_arr['name'];
        $params['subject'] = $post_arr['subject'];
        $params['email'] = $post_arr['email'];
        $params['contact_no'] = $post_arr['contact_no'];
        $params['message'] = $post_arr['note'];
        $ret_arr = array();
		$this->sendEmailProvider(
						$this->session->userdata('vEmail'),
						$this->session->userdata('vCompanyName'),
						$params['provider_email'],
						$params['contact_name'],
						$params['subject'] = $post_arr['subject'],
						$params['name'] = $post_arr['name'],
						$params['contact_no'] = $post_arr['contact_no'],
						$params['message'] = $post_arr['note'],
						"Proveedor");
        $ret_arr['success'] = true;
        $ret_arr['message'] = "¡Mensaje enviado al proveedor!";
        echo json_encode($ret_arr); exit;
    }
	
	public function sendEmailProvider($de,$deName,$para,$contact_name,$titulo,$name,$contact_no,$body,$nombrepara){
		$html .='<h3>¡Buen día!</h3>';
		$html .='<p>Un distribuidor solicita información desde la Plataforma de Alyzta.</p>';
		$html .='<p>Datos del Distribuidor: '.$de.' / '.$name.' / '.$deName.' / '.$contact_no.'</p>';
		$html .='<p>Mensaje:<br>'.$body.'</p>';
		$this->load->library('email');
        $this->email->clear(TRUE);
        $this->email->from($de, $deName); 
        $this->email->to($para);  //$this->email->to($value['vEmail']);  
        $this->email->subject($titulo);                   
        $this->email->message($html, $body);
        $this->email->send();
	}
    /*public function submit_contact_form(){
        $post_arr = $this->params_arr;
        $params = array();
        $params['provider_id'] = $post_arr['provider_id'];
        $params['distributor_id'] = $post_arr['distributor_id'];
        $params['name'] = $post_arr['name'];
        $params['subject'] = $post_arr['subject'];
        $params['email'] = $post_arr['email'];
        $params['contact_no'] = $post_arr['contact_no'];
        $params['message'] = $post_arr['note'];
		
		if($post_arr['copy_email'] == 'Yes'){
			$data = $this->getCC($post_arr['provider_id']);
			$CC = $data[0]['vAlternateEmail'];
			$params['cc'] = $CC;
		}
		
        $contact_data = $this->cit_api_model->callAPI("add_distributor_contact_data", $params);
        $ret_arr = array();
        $ret_arr['success'] = true;
        $ret_arr['message'] = "¡Mensaje enviado al proveedor!";
        $ret_arr['inserted_id'] = $contact_data['data'][0]['insert_id'];
        echo json_encode($ret_arr); exit;
    }*/

    /*search popup in product listing: get state lisitng*/
    public function getStates(){
        $keyword = $this->input->get('q');
        $ids = $this->input->get('ids');
        $this->db->select('iStateId,vState');
        $this->db->from('mod_state');
        if($ids!='')        
        $this->db->where_in('iStateId',explode(",",$ids));
        $this->db->where('iCountryId','138');
        $this->db->like('vState',$keyword);
        $states = $this->db->get()->result_array();
        $arra = array();
        foreach ($states as $key => $value) {
            $arra[]=array("id"=>$value['iStateId'],"val"=>$value['vState']);
       }
       echo json_encode($arra);exit;
    }

    /*search popup in product listing: get provider type listing*/
    public function getProviderTypes(){
        $keyword = $this->input->get('q');
        $ids = $this->input->get('ids');
        $this->db->select('iSiteTypeMasterId,vTitle');
        $this->db->from('site_type_master');

         if($ids!='')        
        $this->db->where_in('iSiteTypeMasterId',explode(",",$ids));

        $this->db->where('eType','TypeOfProvider');
        $this->db->like('vTitle',$keyword);
        $types = $this->db->get()->result_array();
        $arra = array();
        foreach ($types as $key => $value) {
            $arra[]=array("id"=>$value['iSiteTypeMasterId'],"val"=>$value['vTitle']);
       }
       echo json_encode($arra);exit;
    }



    /*search popup in product listing: get rating section*/
    function reload_rating_view(){
        $provider_id = $this->params_arr['provider_id'];        
        echo $this->general->get_rating($provider_id);
        exit;
    }

    /*search popup in product listing: send request for URL*/
    public function requestUrlToAdmin(){
        $distributor_id = $this->session->userdata('iAdminId');
        $params_arr = array();
        $params_arr['distributor_id'] = $distributor_id;
        $request_data = $this->cit_api_model->callAPI("send_url_request", $params_arr);
        $request_success = $request_data['settings']['success'];
        $request_message = $request_data['settings']['message'];
        $ret_arr = array();
		$this->emailImportRequest(
						$this->session->userdata('vEmail'),
						$this->session->userdata('vCompanyName'),
						'soporte@alyzta.com',
						'Solicitud Importación de Productos',
						"usuario test");
        $ret_arr['success'] = $request_success;
        $ret_arr['message'] = $request_message;
        echo json_encode($ret_arr); exit;
    }
	
	public function emailImportRequest($de,$deName,$para,$titulo,$nombrepara){
		$html .='<h1>'.$titulo.'</h1>';
		$html .='<p>El usuario: '.$de.' - '.$deName.' ha solicitado la <b>Importación de Productos</b>.</p>';
		$html .='<p>Por favor, acepta la petición en la plataforma de Administrador.</p>';
		$this->load->library('email');
        $this->email->clear(TRUE);
        $this->email->from($de, $deName); 
        $this->email->to($para);  //$this->email->to($value['vEmail']);  
        $this->email->subject($titulo);                   
        $this->email->message($html);
        $this->email->send();
	}

    /*search popup in product listing: check URl request status*/
    public function checkRequestStatus(){
        $this->db->select('eStatus');
        $this->db->from('distributor_import_request_master');
        $this->db->where('iDistributorUserId',$this->session->userdata('iAdminId'));
        $data = $this->db->get()->row_array();
        $ret_arr = array();
        if($data['eStatus']=='Inactive'){
            $status = 'Pendiente';
        } elseif($data['eStatus']=='Active') {
            $status = 'Aprobado';
        } else {
            $status = 'Rechazado';
        }
        if(!empty($data) && count($data)>0){
            $ret_arr['success'] = true;
            $ret_arr['status'] = $status;
        } else {
            $ret_arr['success'] = false;
            $ret_arr['status'] = $status;
        }
        echo json_encode($ret_arr); exit;

    }

    /*search popup in product listing: select custom type category*/
    public function select_custom_category(){  
        $get= $this->input->get();
        return $this->parser->parse('select_custom_category.tpl',$get,true);     
    }

    /*search popup in product listing: add to custom category*/
    public function add_to_custom_categories(){
        $product_ids = $this->params_arr['product_ids'];
        $distributor_id = $this->session->userdata('iAdminId');
        $params_arr = array();
        $params_arr['distributor_id'] = $distributor_id;
        $custom_category = $this->cit_api_model->callAPI("custom_category_listing", $params_arr);
        $this->smarty->assign('selected_product_ids',$product_ids);
        $this->smarty->assign('custom_category',$custom_category['data']);
        echo $this->parser->parse('add_to_custom_categories.tpl',array(),true); exit;
    }

    /*search popup in product listing: save category data in database*/
    public function save_category_data(){
        $params = array();
        $params['product_ids'] = implode(',',$this->params_arr['selected_product']);
        $params['distributor_id'] = $this->session->userdata('iDistributorUserId');
        $params['category_id'] = implode(',',$this->params_arr['categories_id']);
       
        $custom_data = $this->cit_api_model->callAPI('add_product_to_custom_category',$params);
        if($custom_data['settings']['success']==1)
        {
            $ret['success'] = true;
            $ret['message'] = "¡El producto se agregó correctamente!";
        }
        else
        {
            $ret['success'] = false;
            $ret['message'] = "Error al agregar los productos...";
        }
        
       echo json_encode($ret); exit;
    }
	
	
	/*
	 *$contact_data = $this->cit_api_model->callAPI("add_distributor_contact_data", $params);
        $ret_arr = array();
        $ret_arr['success'] = true;
        $ret_arr['message'] = "Proveedor contactado con éxito";
        $ret_arr['inserted_id'] = $contact_data['data'][0]['insert_id'];
*/
	
	public function update_pro(){
		$Params = $this->_request_params();
		
		if ($Params['eProviderType'] == "Default"){
			$data = Array();
			$data['iDistributorProviderMappingId'] = $Params['iDistributorProviderMappingId'];
			$data['vProviderName'] = $Params['Name'];
			$data['vUniqueID'] = $Params['vUniqueID'];
			$data['fProviderDiscount'] = $Params['fProviderDiscount'];
			$data['fProviderProfit'] = $Params['fProviderProfit'];
			$data['iSeqNo'] = $Params['iSeqNo'];
			$data['dModifiedDate'] = date('Y-m-d H:i:s');
			//$this->db->select();
			if(empty( $data["iDistributorProviderMappingId"])&&$Params['mod']=='insert'){
				$this->db->insert("distributor_provider_mapping",[																  
					'iSysRecDeleted'=>0,
					'vProviderName'=>$Params['Name'],
					'vUniqueID'=>$Params['vUniqueID'],
					'fProviderDiscount'=>$Params['fProviderDiscount'],
					'fProviderProfit'=>$Params['fProviderProfit'],
					'iSeqNo'=>$Params['iSeqNo'],
					'vSelectedCategoryID'=>'',
					'dAddedDate'=>date('Y-m-d H:i:s'),
					'dModifiedDate'=>date('Y-m-d H:i:s'),
					'iProviderId'=>$Params['iProviderId'],
					'iDistributorUserId'=>$Params['iDistributorId'],
					'eProviderType'=>'Default',
					'eSortType'=>'Default'											  
																  ]);
			}else{
				$this->db->where("iDistributorProviderMappingId", $data["iDistributorProviderMappingId"]);
				$this->db->update("distributor_provider_mapping", $data);
			}
			
		}
		if ($Params['eProviderType'] == "Custom"){
			
			$dataMap = Array();
			$dataMap['iDistributorProviderMappingId'] = $Params['iDistributorProviderMappingId'];
			$dataMap['fProviderDiscount'] = $Params['fProviderDiscount'];
			$dataMap['fProviderProfit'] = $Params['fProviderProfit'];
			$dataMap['iSeqNo'] = $Params['iSeqNo'];
			$dataMap['dModifiedDate'] = date('Y-m-d H:i:s');
			$dataMap['vProviderName'] =  $Params['vProviderName'];
			$dataMap['vUniqueID'] = $Params['vUniqueID'];
					 
			$dataUs = Array();
			$dataUs['iUsersId'] =  $Params['iProviderId'];
			$dataUs['vCompanyName'] =  $Params['Name'];
			$dataUs['vName'] =  $Params['vProviderName'];
			$dataUs['vUniqueID'] =  $Params['vUniqueID'];
			
			//$this->db->select();
			$this->db->where("iUsersId", $dataUs["iUsersId"]);
			$this->db->update("users", $dataUs);
			
			//$this->db->select();
			$this->db->where("iDistributorProviderMappingId", $dataMap["iDistributorProviderMappingId"]);
			$this->db->update("distributor_provider_mapping", $dataMap);
			
		}
		$this->orden($Params);
	}
	
	public function orden($params){
		$this->db->select()
		->from("distributor_provider_mapping")
		->where("iDistributorUserId",$params['iDistributorId'])
		->where("iSeqNo >=".$params['iSeqNo'])
		->where_not_in("iDistributorProviderMappingId",array($params['iDistributorProviderMappingId']));
		$result_obj = $this->db->get();
		$res = is_object($result_obj) ? $result_obj->result_array() : array();
		/*echo "<pre>";
		print_r($this->db->last_query());
		die;*/
		if(!empty($res)){
			$subir=$params['iSeqNo'];
			foreach($res as $re){
				$subir++;
				 $this->db->where("iDistributorProviderMappingId", $re["iDistributorProviderMappingId"]);
				 $this->db->update("distributor_provider_mapping", ['iSeqNo'=>$subir]);
			}		 
		} 
	}
	public function	getExtraEmail($data = ''){
		$this->db->select("eCopyEmail");
		$this->db->from("user_provider_settings");
		$this->db->where("iUserId",$data);
		$result_obj = $this->db->get();
		$res = is_object($result_obj) ? $result_obj->result_array() : array();
		return $res;
	}
	public function	getCC($data = ''){
		$this->db->select("vAlternateEmail");
		$this->db->from("users");
		$this->db->where("iUsersId",$data);
		$result_obj = $this->db->get();
		$res = is_object($result_obj) ? $result_obj->result_array() : array();
		return $res;
	}
	public function	getDistrDetail($data){
		$this->db->select();
		$this->db->from("user_distributor_setting");
		$this->db->where("iUserId",$data);
		$result_obj = $this->db->get();
		$res = is_object($result_obj) ? $result_obj->result_array() : array();
		return $res;
	}
	
}