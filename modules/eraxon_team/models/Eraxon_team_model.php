<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * hr payroll model
 */
class Eraxon_team_model extends App_Model {

	public function __construct() {
		parent::__construct();
	}

	public function add_team($data)
	{
		$this->db->insert(db_prefix() . 'eraxon_team', $data);
		$insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Team Added [Team Name: ' . $data['teamname'] . ']');
        }

        return $insert_id;
	}

	public function get_teams($id='')
	{

		if($id == '')
		{
			
		}

	}
}