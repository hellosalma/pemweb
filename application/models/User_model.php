<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function get_user_data($id)
    {
        return $this->db->select('email, username')->get('user', ['id' => $id])->row_array();
    }
    public function ubahProfile($image = false)
    {

        if ($image != false) {
            $this->db->set('image', $image);
        }
        $this->db->set('username', $this->input->post('username', TRUE));
        $this->db->where('email', $this->user['email']);
        $this->db->update('user');

        $this->session->set_flashdata('msg', '<div class="alert alert-success p-0 m-0">Profile berhasil di perbarui.</div>');
        echo json_encode(['status' => TRUE]);
    }

    public function ubahPassword()
    {
        // jika tidak sama dengan password baru
        $pass_hash = password_hash($this->input->post('newpass'), PASSWORD_DEFAULT);

        $this->db->set('password', $pass_hash);
        $this->db->where('email', $this->user['email']);
        $this->db->update('user');

        echo json_encode(['status' => TRUE]);
        $this->session->set_flashdata('ubahpass', '<div class="alert alert-success p-0 m-0">Password berhasil diubah.</div>');
    }
}
