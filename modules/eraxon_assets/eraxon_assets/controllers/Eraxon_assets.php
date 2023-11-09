<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class Eraxon_assets extends AdminController
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('index');
	}
}