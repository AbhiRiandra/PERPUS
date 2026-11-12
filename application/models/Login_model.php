<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function getUser($username)
    {
        // Query builder -> otomatis di-escape (anti SQL injection)
        return $this->db->get_where('user', ['username' => $username])->row_array();
    }
}
