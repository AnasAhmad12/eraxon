<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Eraxon_assets_custom_fields_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_values($id)
    {
        if ($id) {
            $this->db->where('assets_custom_field_id', $id);
            $this->db->order_by('value_order');
            $custom_field_values = $this->db->get(db_prefix() . 'assets_custom_field_values')->result_array();
            return $custom_field_values;
        }
        return [];
    }

    public function get_category_value($id)
    {
        if ($id) {
            $this->db->where('id', $id);
            $category = $this->db->get(db_prefix() . 'assets_custom_field')->result();
            $this->db->where('assets_category_id',$category[0]->assets_category_id);
            $data = $this->db->get(db_prefix() . 'assets_categories')->result();
            return $data;
        }
        return [];
    }

    public function add_custom_field($data)
    {
        $this->db->insert(db_prefix() . 'assets_custom_field', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Variation Added [ ID:' . $insert_id . ', ' . $data['name'] . ' ]');

            return $insert_id;
        }

        return false;
    }

    public function add_variation_values($id, $values)
    {
        foreach ($values as $value) {
            $data = [
                'assets_custom_field_id' => $id,
                'value' => $value['value'],
                'value_order' => $value['order'],
                'description' => $value['description'],
            ];
            $this->db->insert(db_prefix() . 'assets_custom_field_values', $data);
        }
    }

    public function edit_variation_values($id, $values)
    {
        foreach ($values as $value) {
            $data = [
                'assets_custom_field_id' => $id,
                'value' => $value['value'],
                'description' => $value['description'],
                'value_order' => $value['order'],
            ];
            $this->db->where('value', $value['value']);
            $variation_value = $this->db->get(db_prefix() . 'assets_custom_field_values')->row();
            if ($variation_value) {
                $this->db->where('id', $variation_value->id);
                $this->db->update(db_prefix() . 'assets_custom_field_values', $data);
            } else {
                $this->db->insert(db_prefix() . 'assets_custom_field_values', $data);
            }
        }

        $this->db->where('assets_custom_field_id', $id);
        $variation_values = $this->db->get(db_prefix() . 'assets_custom_field_values')->result_array();
        foreach ($variation_values as $variation_value) {
            $variation_value_exist = false;
            foreach ($values as $value) {
                if ($value['value'] == $variation_value['value']) {
                    $variation_value_exist = true;
                }
            }
            if (!$variation_value_exist) {
                $this->db->where('id', $variation_value['id']);
                $this->db->delete(db_prefix() . 'assets_custom_field_values');
            }
        }
    }

    public function get_custom_field_by_category($id){
        $this->db->where('assets_category_id',$id);
        $cf_id = $this->db->get(db_prefix() . 'assets_custom_field')->result()[0]->id;
        if($cf_id){
            return $this->get($cf_id,true);
        }
        else{
            return [];
        }


    }

    public function get($id = false, $values = false)
    {
        if ($id) {
            $this->db->where('id', $id);
            $variation = $this->db->get(db_prefix() . 'assets_custom_field')->row();

            if ($values) {
                $this->db->where('assets_custom_field_id', $id);
                $this->db->order_by('value_order');
                $variation->values = $this->db->get(db_prefix() . 'assets_custom_field_values')->result_array();
            }
            return $variation;
        }
        $variations = $this->db->get(db_prefix() . 'assets_custom_field')->result_array();
        if ($values) {
            foreach ($variations as $variation) {
                $this->db->where('assets_custom_field_id', $variation->id);
                $this->db->order_by('value_order');
                $variation->values = $this->db->get(db_prefix() . 'assets_custom_field_values')->result_array();
            }
        }

        return $variations;
    }

    public function edit($data, $id)
    {
        $variation = $this->get($id);
        $this->db->where('id', $id);
        $res = $this->db->update(db_prefix() . 'assets_custom_field', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Custom Field Details updated[ ID: ' . $id . ', ' . $variation->name . ' ]');
        }
        if ($res) {
            return true;
        }

        return false;
    }

    public function delete($id)
    {
        $variation  = $this->get($id);
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $result = $this->db->delete(db_prefix() . 'assets_custom_field');
        log_activity('Custom Field Deleted[ ID: ' . $id . ', '. $variation->name . ' ]');

        if (!empty($id)) {
            $this->db->where('assets_custom_field_id', $id);
        }
        $result = $this->db->delete(db_prefix() . 'assets_custom_field_values');

        return $result;
    }
}
