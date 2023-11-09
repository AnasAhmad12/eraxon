<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Eraxon_assets_category_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('assets_category_id', $id);
            $category = $this->db->get(db_prefix().'assets_categories')->row();

            return $category;
        }
        $categories = $this->db->get(db_prefix().'assets_categories')->result_array();

        return $categories;
    }

    public function get_category_for_custom_fields()
    {
        $this->db->select(db_prefix().'assets_categories.*');
        $this->db->from(db_prefix().'assets_categories');
        $this->db->join(db_prefix().'assets_custom_field', db_prefix().'assets_custom_field.assets_category_id ='.db_prefix().'assets_categories.assets_category_id','left');
        $this->db->where(db_prefix().'assets_custom_field.assets_category_id',null);
        $categories = $this->db->get()->result_array();

        return json_encode($categories);
    }


    
    public function add($data)
    {
        $this->db->insert(db_prefix().'assets_categories', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Assets Category Added[ID:'.$insert_id.', '.$data['assets_category_name'].', Staff id '.get_staff_user_id().']');

            return $insert_id;
        }

        return false;
    }

    public function edit($data)
    {
        $this->db->where('assets_category_id', $data['assets_category_id']);
        $res = $this->db->update(db_prefix().'assets_categories', [
            'assets_category_name'        => $data['assets_category_name'],
            'assets_category_description' => $data['assets_category_description'],
        ]);
        if ($this->db->affected_rows() > 0) {
            log_activity('Assets Category updated[ID:'.$data['assets_category_id'].', '.$data['assets_category_name'].', Staff id '.get_staff_user_id().']');
        }

        return $res;
        
    }
    public function delete($id)
    {
        $original_category = $this->get($id);
        $this->db->where('assets_category_id', $id);
        $this->db->delete(db_prefix().'assets_categories');
        if ($this->db->affected_rows() > 0) {
            log_activity('Assets Category deleted[ID:'.$id.', '.$original_category->assets_category_name.', Staff id '.get_staff_user_id().']');
            return true;
        }

        return false;
    }
}
