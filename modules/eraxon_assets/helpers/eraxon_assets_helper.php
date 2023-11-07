
<?php

function get_custom_field_values($id)
{
    $CI = &get_instance();
    $custom_field_values = $CI->Eraxon_assets_custom_fields_model->get_values($id);
    $values = '';
    foreach ($custom_field_values as $variation_value)
    {
        $values .= '<span class="label label-danger">' . $variation_value['value'] . '</span>';
    }
    return $values;
}

function get_catogory_values($id)
{
    $CI = &get_instance();
    $custom_field_values = $CI->Eraxon_assets_custom_fields_model->get_category_value($id);
    $values = '';
    foreach ($custom_field_values as $c)
    {
        $values .= '<span class="label label-success">' . $c->assets_category_name . '</span>';
    }
    return $values;
}

function get_catogory_values_by_id($id)
{
    $CI = &get_instance();
    $category = $CI->Eraxon_assets_category_model->get($id);
    $values = '';
$values .= '<span class="label label-success">' . $category->assets_category_name . '</span>';
    
    return $values;
}



function handle_item_upload($item_id)
{
    $CI = &get_instance();
    if (isset($_FILES['item']['name']) && '' != $_FILES['item']['name']) {
        $path        = get_upload_path_by_type('items');
        $tmpFilePath = $_FILES['item']['tmp_name'];
        if (!empty($tmpFilePath) && '' != $tmpFilePath) {
            $path_parts  = pathinfo($_FILES['item']['name']);
            $extension   = $path_parts['extension'];
            $extension   = strtolower($extension);
            $filename    = 'item_'.$item_id.'.'.$extension;
            $newFilePath = $path.$filename;
            _maybe_create_upload_path($path);
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI->Eraxon_assets_items_model->edit_item(['item_image' => $filename], $item_id);

                return true;
            }
        }
    }

    return false;
}

function get_item_name_by_id($id){
    $CI = &get_instance();
    $item_name = $CI->Eraxon_assets_items_model->get_by_id_product($id)[0]->item_name;
    return $item_name;
}