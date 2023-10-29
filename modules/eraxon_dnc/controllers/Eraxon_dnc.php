<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class Eraxon_dnc extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->library('curl'); 
    }

    public function index(){

         $client     = new \GuzzleHttp\Client();
        

         $response = $client->request('POST', 'https://portal.scrublists.com/api/auth/login', [
    'auth' => ['Trextech', 'Trextech123']], ['verify' => true]);

         $data['response'] = $response;

        $this->load->view('eraxon_dnc/dnc_manage', $data);
    }

}