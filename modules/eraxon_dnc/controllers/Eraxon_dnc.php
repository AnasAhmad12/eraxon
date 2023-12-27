<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class Eraxon_dnc extends AdminController
{

    // public $username = 'Trextech';
    // public $pass = 'Trextech123';
    

    public function __construct()
    {
        parent::__construct();
        $this->load->model('eraxon_dnc/eraxon_dnc_model');
    }

    public function manage_dnc()
    {
        $this->load->view('eraxon_dnc/manage');
    }

    public function all_dnc_requests()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('eraxon_dnc', 'tables/requests_table'));
        }
        $data['requests'] = $this->eraxon_dnc_model->get_dnc_request();
        $this->load->view('eraxon_dnc/requests',$data);
    }
    public function get_dnc_verified()
    {   
        $phonenumber = '';
        $message = '';
        $response = '';
        $data = [];
        $username = 'trextech';
        $pass = 'Trextech';


            if ($this->input->post()) 
            {
                    $phonenumber = $this->input->post('verify_number');
                    $client  = new GuzzleHttp\Client(['cookies' => true]);
                    $jar     = new \GuzzleHttp\Cookie\CookieJar;
                    $auth = 'Basic '. base64_encode ($username . ':' . $pass);
                    $options = [
                    'multipart' => [
                      [
                        'name' => 'upload',
                        'contents' => $phonenumber
                      ]
                    ]];

                    $headers = ['Authorization' => $auth];
          
                    $request = new Request('POST', 'https://portal.scrublists.com/api/upload?filtersetId=1', $headers);
                  
                    $res = $client->sendAsync($request, $options)->wait();

                    $json_query = $res->getBody()->getContents(true);
                    $result = json_decode($json_query,true);

                    $ans = '';
                    $color = '';
                    $check = false;
                    if($result['results']['Good']['count'] > 0)
                    {
                        $ans = 'Good';
                        $color = '#2da000';

                    }else if($result['results']['Bad']['count'] > 0)
                    {
                        $ans = 'Bad';
                        $color = '#ff0000';

                    }else if($result['results']['invalid']['count'] > 0)
                    {
                        $ans = 'Invalid';
                        $color = '#1486ff';
                    }else
                    {
                        $ans = 'error';
                        $color = '#ff0000';
                    }
                    
                    $data['number'] = $phonenumber;
                    $data['result'] = $ans;
                    $data['color']  = $color;

                    $verified = $this->eraxon_dnc_model->verifyfromdb($phonenumber);

                    if($verified)
                    {
                        $result2 =  $this->eraxon_dnc_model->get_data_by_number($phonenumber);

                            if($result2->result != $ans)
                            {
                                $request = array(
                                'id_staff' => get_staff_user_id(),
                                'phonenumber' => $phonenumber,
                                'result' => $ans,
                                'json_query' => $json_query
                            );

                             $update_id = $this->eraxon_dnc_model->update_dnc_request($result2->id,$request);
                            }

                    }else
                    {
                        $request = array(
                                'id_staff' => get_staff_user_id(),
                                'phonenumber' => $phonenumber,
                                'result' => $ans,
                                'json_query' => $json_query);

                        $insert_id = $this->eraxon_dnc_model->add_dnc_request($request);

                    }


                echo json_encode($data);    

            }        




    }
    public function get_dnc()
    {

        $phonenumber = '';
        $message = '';
        $response = '';
        $data = [];
        $username = 'trextech';
        $pass = 'Trextech';

        if ($this->input->post()) 
        {
             
                $phonenumber = $this->input->post('verify_number');

                $verified = $this->eraxon_dnc_model->verifyfromdb($phonenumber);

                if($verified)
                {

                   $result =  $this->eraxon_dnc_model->get_data_by_number($phonenumber);

                   $ans = '';
                   $color = '';

                   if($result->result == 'Good')
                   {
                        $ans = 'Good';
                        $color = '#2da000';

                   }else if($result->result == 'Bad')
                   {
                        $ans = 'Bad';
                        $color = '#ff0000';

                   }else if($result->result == 'Invalid')
                   {
                        $ans = 'Invalid';
                        $color = '#1486ff';
                   }else{

                        $ans = 'error';
                        $color = '#ff0000';
                   }

                    $data['number'] = $phonenumber;
                    $data['result'] = $ans;
                    $data['color']  = $color;

                    echo json_encode($data);

                }else
                {


                    $client  = new GuzzleHttp\Client(['cookies' => true]);
                    $jar     = new \GuzzleHttp\Cookie\CookieJar;
                    $auth = 'Basic '. base64_encode ($username . ':' . $pass);
                    $options = [
                    'multipart' => [
                      [
                        'name' => 'upload',
                        'contents' => $phonenumber
                      ]
                    ]];

                    $headers = ['Authorization' => $auth];
          
                    $request = new Request('POST', 'https://portal.scrublists.com/api/upload?filtersetId=1', $headers);
                  
                    $res = $client->sendAsync($request, $options)->wait();

                    $json_query = $res->getBody()->getContents(true);
                    $result = json_decode($json_query,true);
                    //var_dump($result);
                    //echo "<br><br><br><br>-----------------------------------<br><br><br><br>";
                    //$result = (array)$json_query;
                    /*echo $result ;*/
                    //var_dump($result);
                    $ans = '';
                    $color = '';
                    if($result['results']['Good']['count'] > 0)
                    {
                        $ans = 'Good';
                        $color = '#2da000';

                    }else if($result['results']['Bad']['count'] > 0)
                    {
                        $ans = 'Bad';
                        $color = '#ff0000';

                    }else if($result['results']['invalid']['count'] > 0)
                    {
                        $ans = 'Invalid';
                        $color = '#1486ff';
                    }else
                    {
                        $ans = 'error';
                        $color = '#ff0000';
                    }
                    
                    $data['number'] = $phonenumber;
                    $data['result'] = $ans;
                    $data['color']  = $color;
        
                    $request = array(
                            'id_staff' => get_staff_user_id(),
                            'phonenumber' => $phonenumber,
                            'result' => $ans,
                            'json_query' => $json_query
                        );

                    $insert_id = $this->eraxon_dnc_model->add_dnc_request($request);
                  
                    echo json_encode($data);
                 }
             
        }

        
       /* $response = $client->request('POST', 'https://portal.scrublists.com/api/auth/login', 
        ['auth' => ['Trextech', 'Trextech123']],['cookies' => $jar],
        ['headers' =>['Accept' => 'application/json',
                    'Content-type' => 'application/json']]);

        $data['response'] = $response;*/
      /*$response2 = $client->request('GET', 'https://portal.scrublists.com/api/auth/profile',
        ['auth' => ['Trextech', 'Trextech123']],['debug' => true],                              
        ['headers' =>['Accept' => 'application/json',
                    'Content-type' => 'application/json']]);
      
        $data['response2'] = $response2;*/
      /*try {
        $response3 = $client->request('POST', 'https://portal.scrublists.com/api/upload?filtersetId=1',
        ['auth' => ['Trextech', 'Trextech123']],['cookies' => $jar],
        ['headers' =>[
          'Authorization' => $auth,
          'Accept' => 'application/json',
          'Content-type' => 'application/json']],$options1
       );
      }catch (RequestException $e)
      {
        if ($e->hasResponse()){
            if ($e->getResponse()->getStatusCode() == '400') 
            {
                    $message = "Got response 400";
            }
        }
      }*/
      //$this->load->view('eraxon_dnc/dnc_manage', $data);
    }


    public function update_status()
    {
        if ($this->input->post()) 
        {
            $id = $this->input->post('id');
            $result = $this->input->post('result');

            $data = array(
                            'result' =>$result,
                            );  
            $this->eraxon_dnc_model->update_status($id,$data);                         
        }
    }

}