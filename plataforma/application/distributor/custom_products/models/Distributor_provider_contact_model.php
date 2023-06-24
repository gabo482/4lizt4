<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Distributor Scripts Model
 *
 * @category admin
 *
 * @package master
 *
 * @subpackage models
 *
 * @module Distributor Scripts
 *
 * @class Distributor_scripts_model.php
 *
 * @path application\admin\master\models\Distributor_scripts_model.php
 *
 * @version 1.0
 *
 * @author CIT Dev Team
 *
 * @date 09.03.2022
 */

class Distributor_provider_contact_model extends CI_Model
{
    public $table_name;
    public $table_alias;
    public $primary_key;
    public $primary_alias;
    public $grid_fields;
    public $join_tables;
    public $extra_cond;
    public $groupby_cond;
    public $orderby_cond;
    public $unique_type;
    public $unique_fields;
    public $switchto_fields;
    public $default_filters;
    public $search_config;
    public $relation_modules;
    public $deletion_modules;
    public $print_rec;
    public $multi_lingual;
    public $physical_data_remove;
    //
    public $listing_data;
    public $rec_per_page;
    public $message;

    /**
     * __construct method is used to set model preferences while model object initialization.
     * @created Alyzta team | 09.03.2022
     * @modified Alyzta team | 09.03.2022
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
       
       
        $this->module_name = "distributor_provider_contact";
        $this->table_name = "provider_contacts_1";
        $this->table_alias = "pcm";        
        $this->primary_key = "iProviderContactsId";        
        $this->primary_alias = "pcm_provider_contacts_id";
        $this->physical_data_remove = "Yes";           
        
        $this->grid_fields = array(                                    
            "vName"   ,"vRole" ,"vEmail" ,"vContactNo"      
        );
   /*
    *
    *     $this->db->select("pcm.vRole as vRole");
        $this->db->select("pcm.vName as vName");
        $this->db->select("pcm.vEmail as vEmail");
        $this->db->select("pcm.vContactNo as vContactNo");
        $this->table_name = "distributor_provider_contact";
        $this->table_alias = "dpc";
        $this->primary_key = "iDistributorProviderContactId";
        $this->primary_alias = "dpc_distributor_provider_contact_id";
        $this->physical_data_remove = "Yes";
        $this->grid_fields = array(
            "dpc_name_provider_contact"
        );
   */     
        $this->join_tables = array();
        $this->extra_cond = "";
        $this->groupby_cond = array();
        $this->having_cond = "";
      /*  $this->orderby_cond = array(
            
            array(
                "field" => "pcm.iProviderContactsId",
                "order" => "DESC",
            )
        );*/
        $this->unique_type = "OR";
        $this->unique_fields = array();
        $this->switchto_fields = array();
        $this->default_filters = array();
        $this->search_config = array();
        $this->relation_modules = array();
        $this->deletion_modules = array();
        $this->print_rec = "No";
        $this->multi_lingual = "No";

        $this->rec_per_page = $this->config->item('REC_LIMIT');
    }

    /**
     * insert method is used to insert data records to the database table.
     * @param array $data data array for insert into table.
     * @return numeric $insert_id returns last inserted id.
     */
    public function insert($data = array())
    {
        $this->db->insert($this->table_name, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * update method is used to update data records to the database table.
     * @param array $data data array for update into table.
     * @param string $where where is the query condition for updating.
     * @param string $alias alias is to keep aliases for query or not.
     * @param string $join join is to make joins while updating records.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function update($data = array(), $where = '', $alias = "No", $join = "No")
    {
        if ($alias == "Yes")
        {
            if ($join == "Yes")
            {
                $join_tbls = $this->addJoinTables("NR");
            }
            if (trim($join_tbls) != '')
            {
                $set_cond = array();
                foreach ($data as $key => $val)
                {
                    $set_cond[] = $this->db->protect($key)." = ".$this->db->escape($val);
                }
                if (is_numeric($where))
                {
                    $extra_cond = " WHERE ".$this->db->protect($this->table_alias.".".$this->primary_key)." = ".$this->db->escape($where);
                }
                elseif ($where)
                {
                    $extra_cond = " WHERE ".$where;
                }
                else
                {
                    return FALSE;
                }
                $update_query = "UPDATE ".$this->db->protect($this->table_name)." AS ".$this->db->protect($this->table_alias)." ".$join_tbls." SET ".implode(", ", $set_cond)." ".$extra_cond;
                $res = $this->db->query($update_query);
            }
            else
            {
                if (is_numeric($where))
                {
                    $this->db->where($this->table_alias.".".$this->primary_key, $where);
                }
                elseif ($where)
                {
                    $this->db->where($where, FALSE, FALSE);
                }
                else
                {
                    return FALSE;
                }
                $res = $this->db->update($this->table_name." AS ".$this->table_alias, $data);
            }
        }
        else
        {
            if (is_numeric($where))
            {
                $this->db->where($this->primary_key, $where);
            }
            elseif ($where)
            {
                $this->db->where($where, FALSE, FALSE);
            }
            else
            {
                return FALSE;
            }
            $res = $this->db->update($this->table_name, $data);
        }
        return $res;
    }

    /**
     * delete method is used to delete data records from the database table.
     * @param string $where where is the query condition for deletion.
     * @param string $alias alias is to keep aliases for query or not.
     * @param string $join join is to make joins while deleting records.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function delete($where = "", $alias = "No", $join = "No")
    {
        if ($this->config->item('PHYSICAL_RECORD_DELETE') && $this->physical_data_remove == 'No')
        {
            if ($alias == "Yes")
            {
                if (is_array($join['joins']) && count($join['joins']))
                {
                    $join_tbls = '';
                    if ($join['list'] == "Yes")
                    {
                        $join_tbls = $this->addJoinTables("NR");
                    }
                    $join_tbls .= ' '.$this->listing->addJoinTables($join['joins'], "NR");
                }
                elseif ($join == "Yes")
                {
                    $join_tbls = $this->addJoinTables("NR");
                }
                $data = $this->general->getPhysicalRecordUpdate($this->table_alias);
                if (trim($join_tbls) != '')
                {
                    $set_cond = array();
                    foreach ($data as $key => $val)
                    {
                        $set_cond[] = $this->db->protect($key)." = ".$this->db->escape($val);
                    }
                    if (is_numeric($where))
                    {
                        $extra_cond = " WHERE ".$this->db->protect($this->table_alias.".".$this->primary_key)." = ".$this->db->escape($where);
                    }
                    elseif ($where)
                    {
                        $extra_cond = " WHERE ".$where;
                    }
                    else
                    {
                        return FALSE;
                    }
                    $update_query = "UPDATE ".$this->db->protect($this->table_name)." AS ".$this->db->protect($this->table_alias)." ".$join_tbls." SET ".implode(", ", $set_cond)." ".$extra_cond;
                    $res = $this->db->query($update_query);
                }
                else
                {
                    if (is_numeric($where))
                    {
                        $this->db->where($this->table_alias.".".$this->primary_key, $where);
                    }
                    elseif ($where)
                    {
                        $this->db->where($where, FALSE, FALSE);
                    }
                    else
                    {
                        return FALSE;
                    }
                    $res = $this->db->update($this->table_name." AS ".$this->table_alias, $data);
                }
            }
            else
            {
                if (is_numeric($where))
                {
                    $this->db->where($this->primary_key, $where);
                }
                elseif ($where)
                {
                    $this->db->where($where, FALSE, FALSE);
                }
                else
                {
                    return FALSE;
                }
                $data = $this->general->getPhysicalRecordUpdate();
                $res = $this->db->update($this->table_name, $data);
            }
        }
        else
        {
            if ($alias == "Yes")
            {
                $del_query = "DELETE ".$this->db->protect($this->table_alias).".* FROM ".$this->db->protect($this->table_name)." AS ".$this->db->protect($this->table_alias);
                if (is_array($join['joins']) && count($join['joins']))
                {
                    if ($join['list'] == "Yes")
                    {
                        $del_query .= $this->addJoinTables("NR");
                    }
                    $del_query .= ' '.$this->listing->addJoinTables($join['joins'], "NR");
                }
                elseif ($join == "Yes")
                {
                    $del_query .= $this->addJoinTables("NR");
                }
                if (is_numeric($where))
                {
                    $del_query .= " WHERE ".$this->db->protect($this->table_alias).".".$this->db->protect($this->primary_key)." = ".$this->db->escape($where);
                }
                elseif ($where)
                {
                    $del_query .= " WHERE ".$where;
                }
                else
                {
                    return FALSE;
                }
                $res = $this->db->query($del_query);
            }
            else
            {
                if (is_numeric($where))
                {
                    $this->db->where($this->primary_key, $where);
                }
                elseif ($where)
                {
                    $this->db->where($where, FALSE, FALSE);
                }
                else
                {
                    return FALSE;
                }
                $res = $this->db->delete($this->table_name);
            }
        }
        return $res;
    }

    /**
     * getData method is used to get data records for this module.
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * @param string $fields fields are either array or string.
     * @param string $order_by order_by is to append order by condition.
     * @param string $group_by group_by is to append group by condition.
     * @param string $limit limit is to append limit condition.
     * @param string $join join is to make joins with relation tables.
     * @param boolean $having_cond having cond is the query condition for getting conditional data.
     * @param boolean $list list is to differ listing fields or form fields.
     * @return array $data_arr returns data records array.
     */
    public function getData($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $join = "No", $having_cond = '', $list = FALSE)
    {
        if (is_array($fields))
        {
            $this->listing->addSelectFields($fields);
        }
        elseif ($fields != "")
        {
            $this->db->select($fields);
        }
        elseif ($list == TRUE)
        {
            $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_key);
            if ($this->primary_alias != "")
            {
                $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_alias);
            }
            $this->db->select("dpc.vNameProviderContact AS dpc_name_provider_contact");
            $this->db->select("dpc.vRoleProviderContact AS dpc_role_provider_contact");
            $this->db->select("dpc.vEmailProviderContact AS dpc_email_provider_contact");
            $this->db->select("dpc.vPhoneProviderContact AS dpc_phone_provider_contact");
        }
        else
        {
            $this->db->select("dpc.iDistributorProviderId AS iDistributorProviderId");
            $this->db->select("dpc.iDistributorProviderContactId AS ds_distributor_provider_contact_id");
            $this->db->select("dpc.vNameProviderContact AS dpc_name_provider_contact");
            $this->db->select("dpc.vRoleProviderContact AS dpc_role_provider_contact");
				$this->db->select("dpc.vEmailProviderContact AS dpc_email_provider_contact");
            $this->db->select("dpc.vPhoneProviderContact AS dpc_phone_provider_contact");
        }

        $this->db->from($this->table_name." AS ".$this->table_alias);
        if (is_array($join) && is_array($join['joins']) && count($join['joins']) > 0)
        {
            $this->listing->addJoinTables($join['joins']);
        }
        else
        {


        }
        if (is_array($extra_cond) && count($extra_cond) > 0)
        {
            $this->listing->addWhereFields($extra_cond);
        }
        elseif (is_numeric($extra_cond))
        {
            $this->db->where($this->table_alias.".".$this->primary_key, intval($extra_cond));
        }
        elseif ($extra_cond)
        {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere($this->table_name, $this->table_alias, "AR");
        if ($group_by != "")
        {
            $this->db->group_by($group_by);
        }
        if ($having_cond != "")
        {
            $this->db->having($having_cond, FALSE, FALSE);
        }
        if ($order_by != "")
        {
            $this->db->order_by($order_by);
        }
        if ($limit != "")
        {
            if (is_numeric($limit))
            {
                $this->db->limit($limit);
            }
            else
            {
                list($offset, $limit) = explode(",", $limit);
                $this->db->limit($offset, $limit);
            }
        }
        $data_obj = $this->db->get();
        $data_arr = is_object($data_obj) ? $data_obj->result_array() : array();
        #echo $this->db->last_query();
        return $data_arr;
    }

    /**
     * getListingData method is used to get grid listing data records for this module.
     * @param array $config_arr config_arr for grid listing settigs.
     * @return array $listing_data returns data records array for grid.
     */
    public function getListingData($config_arr = array())
    {
        $page = $config_arr['page'];
        $rows = $config_arr['rows'];
        $sidx = $config_arr['sidx'];
        $sord = $config_arr['sord'];
        $sdef = $config_arr['sdef'];
        $filters = $config_arr['filters'];

        $extra_cond = $config_arr['extra_cond'];
        $group_by = $config_arr['group_by'];
        $having_cond = $config_arr['having_cond'];
        $order_by = $config_arr['order_by'];

        $page = ($page != '') ? $page : 1;
        $rec_per_page = (intval($rows) > 0) ? intval($rows) : $this->rec_per_page;
        $extra_cond = ($extra_cond != "") ? $extra_cond : "";

        $this->db->start_cache();
        $this->db->from($this->table_name." AS ".$this->table_alias);
        $this->addJoinTables("AR");
       // if ($extra_cond != "")
        //{
          // $this->db->where("pcm.iProviderId",$extra_cond);
        //}
        
          $this->db->where("pcm.iProviderId",$config_arr['iProviderId'], FALSE, FALSE);
          $this->db->where("pcm.iUsersId",$config_arr['iUserId'],false,false);
        
       //print_r($config_arr);
        
        
        $this->general->getPhysicalRecordWhere($this->table_name, $this->table_alias, "AR");
        if (is_array($group_by) && count($group_by) > 0)
        {
            $this->db->group_by($group_by);
        }
        if ($having_cond != "")
        {
            $this->db->having($having_cond, FALSE, FALSE);
        }
        $filter_config = array();
        $filter_config['module_config'] = $config_arr['module_config'];
        $filter_config['list_config'] = $config_arr['list_config'];
        $filter_config['form_config'] = $config_arr['form_config'];
        $filter_config['dropdown_arr'] = $config_arr['dropdown_arr'];
        $filter_config['search_config'] = $this->search_config;
        $filter_config['table_name'] = $this->table_name;
        $filter_config['table_alias'] = $this->table_alias;
        $filter_config['primary_key'] = $this->primary_key;
        $filter_config['grid_fields'] = $this->grid_fields;

        $filter_main = $this->filter->applyFilter($filters, $filter_config, "Select");
        $filter_left = $this->filter->applyLeftFilter($filters, $filter_config, "Select");
        $filter_range = $this->filter->applyRangeFilter($filters, $filter_config, "Select");
        
     
        $filter_main;
        $filter_left;
        $filter_range;
        if ($filter_main != "")
        {
            $this->db->where("(".$filter_main.")", FALSE, FALSE);
        }
        if ($filter_left != "")
        {
            $this->db->where("(".$filter_left.")", FALSE, FALSE);
        }
        if ($filter_range != "")
        {
            $this->db->where("(".$filter_range.")", FALSE, FALSE);
        }

        $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_key);
        if ($this->primary_alias != "")
        {
            $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_alias);
        }
        $this->db->select("pcm.vRole as vRole");
        $this->db->select("pcm.vName as vName");
        $this->db->select("pcm.vEmail as vEmail");
        $this->db->select("pcm.vContactNo as vContactNo");
        $this->db->stop_cache();
        if ((is_array($group_by) && count($group_by) > 0) || trim($having_cond) != "")
        {
            $this->db->select($this->table_alias.".".$this->primary_key);
            $total_records_arr = $this->db->get();
            $total_records = is_object($total_records_arr) ? $total_records_arr->num_rows() : 0;
        }
        else
        {
            $total_records = $this->db->count_all_results();
        }

        $total_pages = $this->listing->getTotalPages($total_records, $rec_per_page);
        if ($sdef == "Yes" && is_array($order_by) && count($order_by) > 0)
        {
            foreach ($order_by as $orK => $orV)
            {
                $sort_filed = $orV['field'];
                $sort_order = (strtolower($orV['order']) == "desc") ? "DESC" : "ASC";
                $this->db->order_by($sort_filed, $sort_order);
            }
        }
        if ($sidx != "")
        {
            $this->listing->addGridOrderBy($sidx, $sord, $config_arr['list_config']);
        }
        $limit_offset = $this->listing->getStartIndex($total_records, $page, $rec_per_page);
        $this->db->limit($rec_per_page, $limit_offset);
        $return_data_obj = $this->db->get();
        $return_data = is_object($return_data_obj) ? $return_data_obj->result_array() : array();
        $this->db->flush_cache();
        $listing_data = $this->listing->getDataForJqGrid($return_data, $filter_config, $page, $total_pages, $total_records);
        //$this->listing_data = $return_data;
        //echo $this->db->last_query();
        
        return $listing_data;
    }

    /**
     * getExportData method is used to get grid export data records for this module.
     * @param array $config_arr config_arr for grid export settigs.
     * @return array $export_data returns data records array for export.
     */
    public function getExportData($config_arr = array())
    {
        $page = $config_arr['page'];
        $rows = $config_arr['rows'];
        $rowlimit = $config_arr['rowlimit'];
        $sidx = $config_arr['sidx'];
        $sord = $config_arr['sord'];
        $sdef = $config_arr['sdef'];
        $filters = $config_arr['filters'];

        $extra_cond = $config_arr['extra_cond'];
        $group_by = $config_arr['group_by'];
        $having_cond = $config_arr['having_cond'];
        $order_by = $config_arr['order_by'];

        $page = ($page != '') ? $page : 1;
        $extra_cond = ($extra_cond != "") ? $extra_cond : "";

        $this->db->from($this->table_name." AS ".$this->table_alias);
        $this->addJoinTables("AR");
        if ($extra_cond != "")
        {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere($this->table_name, $this->table_alias, "AR");
        if (is_array($group_by) && count($group_by) > 0)
        {
            $this->db->group_by($group_by);
        }
        if ($having_cond != "")
        {
            $this->db->having($having_cond, FALSE, FALSE);
        }
        $filter_config = array();
        $filter_config['module_config'] = $config_arr['module_config'];
        $filter_config['list_config'] = $config_arr['list_config'];
        $filter_config['form_config'] = $config_arr['form_config'];
        $filter_config['dropdown_arr'] = $config_arr['dropdown_arr'];
        $filter_config['search_config'] = $this->search_config;
        $filter_config['table_name'] = $this->table_name;
        $filter_config['table_alias'] = $this->table_alias;
        $filter_config['primary_key'] = $this->primary_key;

        $filter_main = $this->filter->applyFilter($filters, $filter_config, "Select");
        $filter_left = $this->filter->applyLeftFilter($filters, $filter_config, "Select");
        $filter_range = $this->filter->applyRangeFilter($filters, $filter_config, "Select");
        if ($filter_main != "")
        {
            $this->db->where("(".$filter_main.")", FALSE, FALSE);
        }
        if ($filter_left != "")
        {
            $this->db->where("(".$filter_left.")", FALSE, FALSE);
        }
        if ($filter_range != "")
        {
            $this->db->where("(".$filter_range.")", FALSE, FALSE);
        }

        $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_key);
        if ($this->primary_alias != "")
        {
            $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_alias);
        }
        $this->db->select("dpc.vNameProviderContact AS dpc_name_provider_contact");
          
        if ($sdef == "Yes" && is_array($order_by) && count($order_by) > 0)
        {
            foreach ($order_by as $orK => $orV)
            {
                $sort_filed = $orV['field'];
                $sort_order = (strtolower($orV['order']) == "desc") ? "DESC" : "ASC";
                $this->db->order_by($sort_filed, $sort_order);
            }
        }
        if ($sidx != "")
        {
            $this->listing->addGridOrderBy($sidx, $sord, $config_arr['list_config']);
        }
        if ($rowlimit != "")
        {
            $offset = $rowlimit;
            $limit = ($rowlimit*$page-$rowlimit);
            $this->db->limit($offset, $limit);
        }
        $export_data_obj = $this->db->get();
        $export_data = is_object($export_data_obj) ? $export_data_obj->result_array() : array();
        #echo $this->db->last_query();
        return $export_data;
    }

    /**
     * addJoinTables method is used to make relation tables joins with main table.
     * @param string $type type is to get active record or join string.
     * @param boolean $allow_tables allow_table is to restrict some set of tables.
     * @return string $ret_joins returns relation tables join string.
     */
    public function addJoinTables($type = 'AR', $allow_tables = FALSE)
    {
        $join_tables = $this->join_tables;
        if (!is_array($join_tables) || count($join_tables) == 0)
        {
            return '';
        }
        $ret_joins = $this->listing->addJoinTables($join_tables, $type, $allow_tables);
        return $ret_joins;
    }

    /**
     * getListConfiguration method is used to get listing configuration array.
     * @param string $name name is to get specific field configuration.
     * @return array $config_arr returns listing configuration array.
     */
    public function getListConfiguration($name = "")
    {
        $list_config = array(
            "dpc_provider_name" => array(
                "name" => "dpc_provider_name",
                "table_name" => "distributor_provider_contact",
                "table_alias" => "dpc",
                "field_name" => "vProviderName",
                "source_field" => "dpc_provider_name",
                "display_query" => "dpc.vProviderName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "center",
                "label" => "Provider Name",
                "label_lang" => $this->lang->line('DISTRIBUTOR_PROVIDER_NAME'),
                "width" => 60,
                "search" => "No",
                "export" => "No",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "edit_link" => "Yes",
            ),
            "dpc_role_provider_contact" => array(
                "name" => "dpc_role_provider_contact",
                "table_name" => "distributor_provider_contact",
                "table_alias" => "dpc",
                "field_name" => "vRoleProviderContact",
                "source_field" => "dpc_role_provider_contact",
                "display_query" => "dpc.vRoleProviderContact",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "center",
                "label" => "Role Provider Contact",
                "label_lang" => $this->lang->line('DISTRIBUTOR_ROLE_PROVIDER_CONTACT'),
                "width" => 30,
                "search" => "No",
                "export" => "No",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "edit_link" => "Yes",
            ),
            "dpc_name_provider_contact" => array(
                "name" => "dpc_name_provider_contact",
                "table_name" => "distributor_provider_contact",
                "table_alias" => "dpc",
                "field_name" => "vNameProviderContact",
                "source_field" => "dpc_name_provider_contact",
                "display_query" => "dpc.vNameProviderContact",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "center",
                "label" => "Name Provider Contact",
                "label_lang" => $this->lang->line('DISTRIBUTOR_NAME_PROVIDER_CONTACT'),
                "width" => 30,
                "search" => "No",
                "export" => "No",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "edit_link" => "Yes",
            ),
            "dpc_email_provider_contact" => array(
                "name" => "dpc_email_provider_contact",
                "table_name" => "distributor_provider_contact",
                "table_alias" => "dpc",
                "field_name" => "vNameProviderContact",
                "source_field" => "dpc_email_provider_contact",
                "display_query" => "dpc.vNameProviderContact",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "center",
                "label" => "Email Provider Contact",
                "label_lang" => $this->lang->line('DISTRIBUTOR_EMAIL_PROVIDER_CONTACT'),
                "width" => 30,
                "search" => "No",
                "export" => "No",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "edit_link" => "Yes",
            ),
            "dpc_phone_provider_contact" => array(
                "name" => "dpc_phone_provider_contact",
                "table_name" => "distributor_provider_contact",
                "table_alias" => "dpc",
                "field_name" => "vPhoneProviderContact",
                "source_field" => "dpc_phone_provider_contact",
                "display_query" => "dpc.vPhoneProviderContact",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "center",
                "label" => "Phone Provider Contact",
                "label_lang" => $this->lang->line('DISTRIBUTOR_PHONE_PROVIDER_CONTACT'),
                "width" => 30,
                "search" => "No",
                "export" => "No",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "edit_link" => "Yes",
            ),
            "ds_added_date" => array(
                "name" => "ds_added_date",
                "table_name" => "distributor_scripts",
                "table_alias" => "ds",
                "field_name" => "dAddedDate",
                "source_field" => "ds_added_date",
                "display_query" => "ds.dAddedDate",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "center",
                "label" => "Added Date",
                "label_lang" => $this->lang->line('CONFIG_DISTRIBUTOR_SCRIPTS_ADDED_DATE'),
                "width" => 75,
                "search" => "No",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "Yes",
                "viewedit" => "Yes",
                "php_func" => "custDateFormat",
            ),
            "ds_status" => array(
                "name" => "ds_status",
                "table_name" => "distributor_scripts",
                "table_alias" => "ds",
                "field_name" => "eStatus",
                "source_field" => "ds_status",
                "display_query" => "ds.eStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_in" => "Both",
                "type" => "dropdown",
                "align" => "center",
                "label" => "Status",
                "label_lang" => $this->lang->line('CONFIG_DISTRIBUTOR_SCRIPTS_STATUS'),
                "width" => 75,
                "search" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "Yes",
                "viewedit" => "Yes",
                "default" => $this->filter->getDefaultValue("ds_status",
                "Text",
                "Active")
            )
        );

        $config_arr = array();
        if (is_array($name) && count($name) > 0)
        {
            $name_cnt = count($name);
            for ($i = 0; $i < $name_cnt; $i++)
            {
                $config_arr[$name[$i]] = $list_config[$name[$i]];
            }
        }
        elseif ($name != "" && is_string($name))
        {
            $config_arr = $list_config[$name];
        }
        else
        {
            $config_arr = $list_config;
        }
        return $config_arr;
    }

    /**
     * getFormConfiguration method is used to get form configuration array.
     * @param string $name name is to get specific field configuration.
     * @return array $config_arr returns form configuration array.
     */
    public function getFormConfiguration($name = "")
    {
        $form_config = array(
            "ds_domain_url" => array(
                "name" => "ds_domain_url",
                "table_name" => "distributor_scripts",
                "table_alias" => "ds",
                "field_name" => "tDomainUrl",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "type" => "dropdown",
                "label" => "Domain Url",
                "label_lang" => $this->lang->line('CONFIG_DISTRIBUTOR_SCRIPTS_DOMAIN')
            ),
            "ds_added_date" => array(
                "name" => "ds_added_date",
                "table_name" => "distributor_scripts",
                "table_alias" => "ds",
                "field_name" => "dAddedDate",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "type" => "textbox",
                "label" => "Added Date",
                "label_lang" => $this->lang->line('CONFIG_DISTRIBUTOR_SCRIPTS_ADDED_DATE'),
                "function" => "addDateOnAddMode",
                "functype" => "value",
            ),
            "ds_status" => array(
                "name" => "ds_status",
                "table_name" => "distributor_scripts",
                "table_alias" => "ds",
                "field_name" => "eStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "type" => "dropdown",
                "label" => "Status",
                "label_lang" => $this->lang->line('CONFIG_DISTRIBUTOR_SCRIPTS_STATUS'),
                "default" => $this->filter->getDefaultValue("ds_status",
                "Text",
                "Active"),
                "dfapply" => "addOnly",
            )
        );

        $config_arr = array();
        if (is_array($name) && count($name) > 0)
        {
            $name_cnt = count($name);
            for ($i = 0; $i < $name_cnt; $i++)
            {
                $config_arr[$name[$i]] = $form_config[$name[$i]];
            }
        }
        elseif ($name != "" && is_string($name))
        {
            $config_arr = $form_config[$name];
        }
        else
        {
            $config_arr = $form_config;
        }
        return $config_arr;
    }

    /**
     * checkRecordExists method is used to check duplication of records.
     * @param array $field_arr field_arr is having fields to check.
     * @param array $field_val field_val is having values of respective fields.
     * @param numeric $id id is to avoid current records.
     * @param string $mode mode is having either Add or Update.
     * @param string $con con is having either AND or OR.
     * @return boolean $exists returns either TRUE of FALSE.
     */
    public function checkRecordExists($field_arr = array(), $field_val = array(), $id = '', $mode = '', $con = 'AND')
    {
        $exists = FALSE;
        if (!is_array($field_arr) || count($field_arr) == 0)
        {
            return $exists;
        }
        foreach ((array) $field_arr as $key => $val)
        {
            $extra_cond_arr[] = $this->db->protect($this->table_alias.".".$field_arr[$key])." =  ".$this->db->escape(trim($field_val[$val]));
        }
        $extra_cond = "(".implode(" ".$con." ", $extra_cond_arr).")";
        if ($mode == "Add")
        {
            $data = $this->getData($extra_cond, "COUNT(*) AS tot");
            if ($data[0]['tot'] > 0)
            {
                $exists = TRUE;
            }
        }
        elseif ($mode == "Update")
        {
            $extra_cond = $this->db->protect($this->table_alias.".".$this->primary_key)." <> ".$this->db->escape($id)." AND ".$extra_cond;
            $data = $this->getData($extra_cond, "COUNT(*) AS tot");
            if ($data[0]['tot'] > 0)
            {
                $exists = TRUE;
            }
        }
        return $exists;
    }

    /**
     * getSwitchTo method is used to get switch to dropdown array.
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * @return array $switch_data returns data records array.
     */
    public function getSwitchTo($extra_cond = '', $type = 'records', $limit = '')
    {
        $switchto_fields = $this->switchto_fields;
        $switch_data = array();
        if (!is_array($switchto_fields) || count($switchto_fields) == 0)
        {
            if ($type == "count")
            {
                return count($switch_data);
            }
            else
            {
                return $switch_data;
            }
        }
        $fields_arr = array();
        $fields_arr[] = array(
            "field" => $this->table_alias.".".$this->primary_key." AS id",
        );
        $fields_arr[] = array(
            "field" => $this->db->concat($switchto_fields)." AS val",
            "escape" => TRUE,
        );
        if (trim($this->extra_cond) != "")
        {
            $extra_cond = (trim($extra_cond) != "") ? $extra_cond." AND ".$this->extra_cond : $this->extra_cond;
        }
        $switch_data = $this->getData($extra_cond, $fields_arr, "val ASC", "", $limit, "Yes");
        #echo $this->db->last_query();
        if ($type == "count")
        {
            return count($switch_data);
        }
        else
        {
            return $switch_data;
        }
    }
    
    
    
    public function selectDistribuidores(){
      $query = $this->db->query("SELECT users.vLegalCompanyName, users.vUniqueId, users.iDistributorUserId FROM promexcom.users LEFT JOIN promexcom.distributor_subscription ON distributor_subscription.iDistributorId = users.iUsersId LEFT JOIN promexcom.distributor_verification ON distributor_verification.iDistributorUserId = users.iUsersId LEFT JOIN promexcom.user_distributor_setting ON user_distributor_setting.iUserId = users.iUsersId LEFT JOIN promexcom.distributor_notes ON distributor_notes.idDistributor = users.iUsersId AND distributor_notes.typeNotes='Paid' and distributor_notes.statusNote=1 LEFT JOIN promexcom.mod_admin ON mod_admin.iAdminId = users.iAdminId WHERE users.iSysRecDeleted not like 1 AND users.eStatus IN ('Active', 'Pending') AND distributor_subscription.ePaymentStatus NOT IN ('Cancelled', 'Timeout') AND users.vEmail not like '%finshu.com' and users.vEmail not like '%eltalk@me.com' AND users.vEmail not like '%isaacshueke01@gmail.com' and users.vEmail not like '%redi.sing@gmail.com' AND users.vEmail not like '%mexibol.com' and users.vEmail not like '%mailinator%' AND users.vEmail not like '%hiddenbrains%' and users.vEmail not like '%alyzta.com' AND users.vEmail not like '%ventamkt.com' and users.vEmail not like '%mdocorp.com' AND users.vEmail not like '%lorenaa69@hotmail.com'and users.vEmail not like '%sistemas@dashtoys.com' AND users.vEmail not like '%bordartes@hotmail.com'and users.vEmail not like '%interpoljeans@gmail.com' AND users.vEmail not like '%alejandrocicap@gmail.com'and users.vEmail not like '%barrerajulieta@plasticospec.com' AND users.vEmail not like '%sistemassss@dashtoys.com'and users.vEmail not like '%ventasSSSS@dashtoys.com' AND users.vEmail not like '%info@dashtoys.com' and users.vEmail not like '%jtanyaramos@outlook.com' and users.vEmail not like '%sizaire_ph@hotmail.com' AND users.vEmail not like '%gerardo_andreu@hotmail.com' and users.vEmail not like '%prueba@hytjkyyjujyu.com' and users.vEmail not like '%licchang112@hotmail.com' AND users.vEmail not like '%hotmail.com.mx' and users.vEmail not like '%gamil.com' AND users.vEmail not like '%ricardo_licon@hotmail.com' and users.vEmail not like '%gamil.com' AND users.vEmail not like '%rik.koolen@hotmail.com' and users.vEmail not like '%sergio@lnk.com.mx' AND users.vEmail not like '%opciongraficaintegral@gmail.com' and users.vEmail not like '%creativoalyzta@gmail.com' AND users.vEmail not like '%ventas@promomaker.com.mx' AND users.iUserType = 1 AND distributor_subscription.vZohoPlanCode IN('WEB_PREMIUM_FREE', 'WEB_PREMIUM_FREE_3','WEB_PREMIUM_FREE_1_2','WEB_PREMIUM_FREE_6', 'WEB_PREMIUM_FREE_1', 'WEB_PROMOTIONAL_PREMIUM_YEARLY') AND distributor_subscription.vPackageCode = 'WEB_PROMOTIONAL_PREMIUM' AND distributor_subscription.iPackageId IN(18, 14) AND  (Date('2018-10-29') <= date(users.dAddedDate) and distributor_subscription.ePaymentStatus in('Free','Paid') or distributor_subscription.ePaymentStatus in ('Paid') and Date('2018-10-29') >= date(users.dAddedDate) or Date('2018-10-29') >= date(users.dAddedDate) and distributor_subscription.ePaymentStatus in ('Free') and distributor_verification.dtAddedDate is not null) ORDER BY users.iDistributorUserId");
      $result = $query->result_array();
      return $result;
    }
    
    
}
