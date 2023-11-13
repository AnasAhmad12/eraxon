<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Reports_products_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('staff_model');
       
    }

    public function chart_orders_of_the_week()
    {
        $qry='SELECT DAYNAME(order_date),
                SUM(total) AS total_sales, order_type
                FROM '.db_prefix().'order_master
                where `order_date` 
                BETWEEN (select date_sub(CURDATE(),INTERVAL 1 WEEK)) 
                AND CURDATE()
                GROUP BY  order_type';
        $query      = $this->db->query($qry);
        $array      = $query->result_array();
        $chart_data = [];
        $days       = [];
        $final      = [];
        foreach ($array as $w => $n) {
            $chart_data[$array[$w]['order_type']][$array[$w]['DAYNAME(order_date)']] = (int) $array[$w]['total_sales'];
        }
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $i    = 0;
        foreach ($chart_data as $product => $value) {
            $final[$i]['name'] = $product;
            foreach ($days as $day) {
                $final[$i]['data'][] = $value[$day] ?? 0;
            }
            ++$i;
        }
        $week_chart['days']                     =$days;
        (!empty($final)) ? $week_chart['series']=$final : $week_chart['series']=null;
        $week_chart['series']                   =$final;

        return $week_chart;
    }

    public function chart_orders_per_month()
    {
        $qry='SELECT CAST(MONTHNAME(order_date) AS CHAR(3)),
                SUM(total) AS total_sales, order_type
                FROM '.db_prefix().'order_master
                where `order_date` 
                BETWEEN (select date_sub(CURDATE(),INTERVAL 1 MONTH)) 
                AND CURDATE()
                GROUP BY order_type';
        $query      = $this->db->query($qry);
        $array      = $query->result_array();
        $chart_data = [];
        $months     = [];
        foreach ($array as $w => $n) {
            $chart_data[$array[$w]['order_type']][$array[$w]['CAST(MONTHNAME(order_date) AS CHAR(3))']] = (int) $array[$w]['total_sales'];
        }
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $i      = 0;
        foreach ($chart_data as $product => $value) {
            $final[$i]['name'] = $product;
            foreach ($months as $day) {
                $final[$i]['data'][] = $value[$day] ?? 0;
            }
            ++$i;
        }
        $month_chart['months']                   =$months;
        (!empty($final)) ? $month_chart['series']=$final : $month_chart['series']=null;

        return $month_chart;
    }

    public function chart_orders_per_year()
    {
        $qry='SELECT YEAR(order_date),
                SUM(total) AS total_sales, order_type
                FROM '.db_prefix().'order_master 
                where `order_date` 
                BETWEEN (select date_sub(CURDATE(),INTERVAL 1 YEAR)) 
                AND CURDATE()
                GROUP BY order_type';
        $query      = $this->db->query($qry);
        $array      = $query->result_array();
        $chart_data = [];
        $years      = [];
        foreach ($array as $w => $n) {
            $chart_data[$array[$w]['order_type']][$array[$w]['YEAR(order_date)']] = (int) $array[$w]['total_sales'];
        }
        $years = range(date('Y'), 2019);
        $i     = 0;
        foreach ($chart_data as $product => $value) {
            $final[$i]['name'] = $product;
            foreach ($years as $day) {
                $final[$i]['data'][] = $value[$day] ?? 0;
            }
            ++$i;
        }
        $year_chart['years']                    =$years;
        (!empty($final)) ? $year_chart['series']=$final : $year_chart['series']=null;

        return $year_chart;
    }

    public function chart_custom_date_range($selected_products, $from, $to)
    {
        $qry='SELECT order_date,
                SUM(qty) AS total_sales, product_name
                FROM '.db_prefix().'order_master
                join '.db_prefix().'order_items on '.db_prefix().'order_items.order_id = '.db_prefix().'order_master.id 
                join '.db_prefix().'product_master on '.db_prefix().'order_items.product_id = '.db_prefix().'product_master.id 
                where (
                (`order_date` BETWEEN "'.$from.'" AND "'.$to.'")
                AND
                product_name IN ("'.$selected_products.'")
                )
                GROUP BY order_date, product_id
                ORDER BY order_date';
        $query           = $this->db->query($qry);
        $array           = $query->result_array();
        $chart_data      = [];
        $date_range      = [];
        foreach ($array as $w => $n) {
            if (!in_array($array[$w]['order_date'], $date_range)) {
                $date_range[] = $array[$w]['order_date'];
            }
            $chart_data[$array[$w]['product_name']][$array[$w]['order_date']] = (int) $array[$w]['total_sales'];
        }
        $i = 0;
        foreach ($chart_data as $product => $value) {
            $final[$i]['name'] = $product;
            foreach ($date_range as $day) {
                $final[$i]['data'][] = $value[$day] ?? 0;
            }
            ++$i;
        }
        $year_chart['date_range']               =$date_range;
        (!empty($final)) ? $year_chart['series']=$final : $year_chart['series']=null;

        return $year_chart;
    }

public function year_recieveables(){
        

        $table_data = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        foreach ($months as $m) {
            $total_sales_month = $this->db
                ->select_sum('total')
                ->select('YEAR(order_date) as year')
                ->from(db_prefix() . 'order_master')
                ->where('MONTH(order_date)', date('m', strtotime($m))) 
                ->where('status', 2)
                ->where('(order_type = "POS-Wallet" OR order_type = "KIOSK")', null, false)
                ->group_by('YEAR(order_date)') 
                ->get()
                ->result();

            $month = $m . " " . $total_sales_month[0]->year;
            $totalSales = (int) $total_sales_month[0]->total;
            if((int) $total_sales_month[0]->total!==0)
                $table_data[$month] = $totalSales;
        }


        return $table_data;
    }
     public function staff_kiosk_report($staff_id,$from,$to){

        $qry = 'SELECT id, order_date, total
        FROM ' . db_prefix() . 'order_master
        WHERE `order_date` BETWEEN "' . $from . '" AND "' . $to . '"
        AND `status` = 2
        AND `clientid` = ' . $staff_id . '
        AND (`order_type` = "KIOSK" OR `order_type` = "POS-Wallet")';

        $query           = $this->db->query($qry);
        
        if(!$query){
            return  $this->db->error();
        }
        else{
            $array           = $query->result_array();

            return $array;
        }
       
    }

      public function staff_kiosk_overall_report($role_id,$from,$to){

        $all_staff = $this->staff_model->get('',array('role'=>6,'active'=>1));
        
        $data = array();
        foreach($all_staff as $staff)
        {
            $qry = 'SELECT SUM(`total`) as total
            FROM ' . db_prefix() . 'order_master
            WHERE `order_date` BETWEEN "' . $from . '" AND "' . $to . '"
            AND `status` = 2
            AND `clientid` = ' . $staff['staffid'] . '
            AND (`order_type` = "KIOSK" OR `order_type` = "POS-Wallet")';

            $query           = $this->db->query($qry);
            $array           = $query->result_array();
            $total = $array[0]['total'];
            if($total == NULL || $total == null)
            {
                $total = 0;
            }
                $row = array(
                'name' => $staff['full_name'].' ('.get_custom_field_value($staff['staffid'],'staff_pseudo','staff',true).')',
                'total'=>  $total,
                 );

                $data[] = $row;
            
            

        }
        
        
        return $data;
       
    }
    public function purchase_report($from_date,$to_date){
       
        $this->db->select('*');
        $this->db->from(db_prefix().'product_purchases');
        $this->db->where('date >=', $from_date);
        $this->db->where('date <=', $to_date);
        $this->db->where('payment_status','Approved');
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }
}

