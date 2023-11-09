<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class Eraxon_quality extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('eraxon_quality_model');
    }

    public function qa_status()
    {
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $data = array(
                    'name' => $this->input->post('qaname'),
                    'isactive' => $this->input->post('isactive'),
                );
                $id = $this->eraxon_quality_model->add_qa_status($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', "QA Status"));

                }
            } else {
                $id = $this->input->post('id');
                $data = array(
                    'name' => $this->input->post('qaname'),
                    'isactive' => $this->input->post('isactive'),
                );
                $success = $this->eraxon_quality_model->update_qa_status($data, $id);
                set_alert('success', _l('updated_successfully', "QA Status"));
            }

        } else {

            $data['qa_status'] = $this->eraxon_quality_model->get_qa_status();
            $this->load->view('eraxon_quality/qa_status', $data);
        }
    }

    public function delete_qa_status($id)
    {
        $response = $this->eraxon_quality_model->delete_qa_status($id);

        if ($response == true) {
            set_alert('success', _l('deleted', "QA status deleted"));
        } else {
            set_alert('warning', _l('problem_deleting', 'Problem in deleting'));
        }
        redirect(admin_url('eraxon_quality/qa_status'));
    }


    public function qa_reviewer_status()
    {
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $data = array(
                    'name' => $this->input->post('qaname'),
                    'isactive' => $this->input->post('isactive'),
                );
                $id = $this->eraxon_quality_model->add_qar_status($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', "QA Reviewer Status"));

                }
            } else {
                $id = $this->input->post('id');
                $data = array(
                    'name' => $this->input->post('qaname'),
                    'isactive' => $this->input->post('isactive'),
                );
                $success = $this->eraxon_quality_model->update_qar_status($data, $id);
                set_alert('success', _l('updated_successfully', "QA Reviewer Status"));
            }

        } else {

            $data['qa_status'] = $this->eraxon_quality_model->get_qar_status();
            $this->load->view('eraxon_quality/qa_reviewer_status', $data);
        }

    }

    public function delete_qar_status($id)
    {
        $response = $this->eraxon_quality_model->delete_qar_status($id);

        if ($response == true) {
            set_alert('success', _l('deleted', "QA Reviewer status deleted"));
        } else {
            set_alert('warning', _l('problem_deleting', 'Problem in deleting'));
        }
        redirect(admin_url('eraxon_quality/qa_reviewer_status'));
    }

    public function manage_column($param = "")
    {
        if ($param == "add") {
            $data['campaign_types'] = $this->eraxon_quality_model->get_campaigns();
            $this->load->view('eraxon_quality/add_column', $data);

        } else if ($param == "save") {
            if ($this->input->post()) {
                if ($this->input->post('camp_type_id')) {
                    $camp_type_id = $this->input->post('camp_type_id');
                    $columns = $this->input->post('columns');
                    $slugs = $this->input->post('slugs');

                    $data = array();
                    $counter = 0;
                    foreach ($columns as $key => $col) {
                        if ($counter == 0) {
                            $column = array(
                                'title' => $col,
                                'data' => $slugs[$key],
                                'type' => 'date',
                                'dateFormat' => 'YYYY-MM-DD',
                                'correctFormat' => 'true',
                            );
                            $counter++;
                        } else {
                            $column = array(
                                'title' => $col,
                                'data' => $slugs[$key],
                                'type' => 'text',
                            );
                        }

                        $data[] = $column;
                    }

                    $post_data = array(
                        "camp_type_id" => $camp_type_id,
                        "column" => json_encode($data)
                    );


                    $response = $this->eraxon_quality_model->add_campaign_columns($post_data);
                    $response = [
                        'url' => admin_url('eraxon_quality/manage_column'),
                        "status" => 1
                    ];
                    echo json_encode($response);


                }
            }
        } else {
            $data['columns'] = $this->eraxon_quality_model->get_columns();
            $this->load->view('eraxon_quality/manage_column', $data);
        }
    }


    public function edit_column($id)
    {

        $data['campaign_types'] = $this->eraxon_quality_model->get_campaigns();
        $this->load->view('eraxon_quality/add_column', $data);



        //     if ($this->input->post()) 
        //     {
        //         if ($this->input->post('camp_type_id')) 
        //         {
        //             $camp_type_id = $this->input->post('camp_type_id');
        //             $columns = $this->input->post('columns');
        //             $slugs = $this->input->post('slugs');

        //             $data = array();
        //             $counter = 0;
        //             foreach($columns as $key => $col)
        //             {
        //                 if($counter == 0)
        //                 {
        //                     $column = array(
        //                     'title'=> $col,
        //                     'data'=> $slugs[$key],
        //                     'type'=> 'date',
        //                     'dateFormat'=> 'YYYY-MM-DD',
        //                     'correctFormat'=> 'true',
        //                     );
        //                     $counter++;
        //                 }else
        //                 {
        //                      $column = array(
        //                     'title'=> $col,
        //                     'data'=> $slugs[$key],
        //                     'type'=> 'text',
        //                     );
        //                 }

        //             $data[] = $column;    
        //             }

        //             $post_data=array(
        //                 "camp_type_id"=>$camp_type_id,
        //                 "column"=>json_encode($data)
        //             );


        //            $response= $this->eraxon_quality_model->add_campaign_columns($post_data);
        //           $response=[
        //             'url'=>admin_url('eraxon_quality/manage_column'),
        //             "status"=>1
        //           ];
        //             echo json_encode($response);


        //         }
        //     }

        // {
        //     $data['columns'] = $this->eraxon_quality_model->get_columns();
        //     $this->load->view('eraxon_quality/manage_column', $data);
        // }
    }

    public function qa_set_column()
    {
        $data['lead_types'] = $this->eraxon_quality_model->get_lead_type();
        $this->load->view('eraxon_quality/manage_column', $data);
    }

    public function get_campaign_sheet($id)
    {
        if ($this->input->post()) {
            $date = $this->input->post('filter_date');

        } else {
            $date = date("Y-m-d");
        }


        $camp_col = $this->eraxon_quality_model->get_campaign_columns($id);
        $leads = $this->eraxon_quality_model->get_leads($id, $date);
        $col_width = array();
        if ($leads) {
            for ($i = 0; $i < count($leads[0]); $i++) {
                $col_width[] = 120;
            }
        }
        $status_col = $this->eraxon_quality_model->get_status_col();

        $data['camp_col'] = $camp_col;
        $data['leads'] = $leads;
        $data['status_col'] = $status_col;
        $data['id'] = $id;
        $data['col_width']=$col_width;



        $this->load->view('eraxon_quality/campaign_sheet', $data);
    }

    public function update_qa_lead()
    {
        $complete_lead = $this->input->post('data');
        $id = $this->input->post('id');
        $response = $this->eraxon_quality_model->update_complete_lead($complete_lead, $id);


        echo json_encode($response);
    }
    //event


}