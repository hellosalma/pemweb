<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logout();

        $this->user = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->model('User_model', 'model');
    }

    public function index()
    {
        $data = [
            'user' => $this->user,
            'judul' => 'My Profile'
        ];

        $this->template->render_page('user/index', $data);
    }

    public function get_user_data()
    {
        $data = $this->model->get_user_data($this->input->post('id'));
        echo json_encode($data);
    }

    public function update_profile()
    {
        $username = $this->input->post('username', TRUE);

        if ($username == $this->user['username']) {
            $rules = 'required|trim';
        } else {
            $rules = 'required|trim|is_unique[user.username]';
        }

        $this->form_validation->set_rules('username', 'Username', $rules);

        $this->form_validation->set_error_delimiters('', '', '');
        if ($this->form_validation->run() == false) {
            $errors = [
                'username' => form_error('username'),
            ];

            $data = [
                'status' => FALSE,
                'errors' => $errors
            ];
            echo json_encode($data);
        } else {
            if ($_FILES['image']['name']) {
                $config['allowed_types'] = 'jpg|png|svg';
                $config['max_size'] = '512';
                $config['upload_path'] = './assets/img/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $this->user['image'];
                    if ($old_image != 'default.svg') {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }

                    $new_image = $this->upload->data('file_name');
                    $this->model->ubahprofile($new_image);
                } else {
                    $errors = [
                        'image' => $this->upload->display_errors('', '')
                    ];
                    $data = [
                        'status' => FALSE,
                        'errors' => $errors
                    ];
                    echo json_encode($data);
                }
            } else {
                $this->model->ubahprofile();
            }
        }
    }

    public function update_password()
    {
        $this->form_validation->set_rules('password', 'Password Lama', 'required');
        $this->form_validation->set_rules('newpass', 'Password Baru', 'required|min_length[8]');
        $this->form_validation->set_rules('newpass2', 'Konfirmasi Password', 'required|matches[newpass]', [
            'matches' => 'Konfirmasi password tidak sesuai'
        ]);

        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() == false) {
            $errors = [
                'password' => form_error('password'),
                'newpass' => form_error('newpass'),
                'newpass2' => form_error('newpass2'),
            ];

            $data = [
                'status' => FALSE,
                'errors' => $errors
            ];
            echo json_encode($data);
        } else {
            $userPass = $this->user['password'];
            $pass = $this->input->post('password');
            $newpass = $this->input->post('newpass');

            // jika password yang di verify tidak sama dengan password user dari db
            if (!password_verify($pass, $userPass)) {
                $errors = [
                    'password' => 'Password lama salah.'
                ];
                $data = [
                    'status' => FALSE,
                    'errors' => $errors
                ];

                echo json_encode($data);
            } else {
                // jika input password lama sama dengan input password baru
                if ($newpass == $pass) {
                    $errors = [
                        'newpass' => 'Password baru tidak boleh sama dengan password lama.'
                    ];
                    $data = [
                        'status' => FALSE,
                        'errors' => $errors
                    ];

                    echo json_encode($data);
                } else {
                    // UPDATE NEW PASSWORD
                    $this->model->ubahpassword();
                }
            }
        }
    }
}
