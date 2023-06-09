<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logout();
        is_admin();

        $this->user = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->model('Pengaturan_model', 'pengaturan');
    }

    public function role()
    {
        $data = [
            'user' => $this->user,
            'judul' => 'Daftar Role',
            'role' => $this->pengaturan->getRole()
        ];

        $this->template->render_page('settings/role', $data);
    }

    public function role_tambah()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->form_validation->set_rules('role', 'Role', 'required|is_unique[user_role.role]');

            $this->form_validation->set_error_delimiters('', '');
            if ($this->form_validation->run() == false) {
                $errors = [
                    'role' => form_error('role')
                ];
                $data = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($data);
            } else {
                $this->pengaturan->insertRole();
            }
        } else {
            redirect('pengaturan/role');
        }
    }

    public function role_ubah()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id');
            $role = $this->input->post('role', true);
            $data = $this->pengaturan->getRole($id);

            if ($role == $data['role']) {
                $rules = 'required';
            } else {
                $rules = 'required|is_unique[user_role.role]';
            }

            $this->form_validation->set_rules('role', 'Role', $rules);

            $this->form_validation->set_error_delimiters('', '');
            if ($this->form_validation->run() == false) {
                $errors = [
                    'role' => form_error('role')
                ];
                $data = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($data);
            } else {
                $this->pengaturan->updateRole($id, $role);
            }
        } else {
            redirect('pengaturan/role');
        }
    }

    public function role_hapus()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $this->db->delete('user_role');

            $data = [
                'status' => TRUE,
                'msg' => 'Data berhasil dihapus'
            ];
            echo json_encode($data);
        } else {
            redirect('pengaturan/role');
        }
    }

    public function pengguna()
    {
        $data = [
            'user' => $this->user,
            'judul' => 'Daftar Pengguna',
            'pengguna' => $this->pengaturan->getPengguna(),
            'role' => $this->pengaturan->getRole()
        ];

        $this->template->render_page('settings/pengguna', $data);
    }

    public function pengguna_tambah()
    {
        $this->form_validation->set_rules('name', 'Nama', 'required|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[user.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('pass1', 'Password', 'required|trim|min_length[8]');
        $this->form_validation->set_rules('pass2', 'Konfirmasi Password', 'required|trim|matches[pass1]');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == false) {
            $errors = [
                'name' => form_error('name'),
                'username' => form_error('username'),
                'email' => form_error('email'),
                'pass1' => form_error('pass1'),
                'pass2' => form_error('pass2')
            ];

            $data = [
                'status' => FALSE,
                'errors' => $errors
            ];
            echo json_encode($data);
        } else {
            $this->pengaturan->insertPengguna();
        }
    }

    public function pengguna_hapus()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $this->db->delete('user');

            $data = [
                'status' => TRUE,
                'msg' => 'Data berhasil dihapus.'
            ];
            echo json_encode($data);
        } else {
            redirect('pengaturan/pengguna');
        }
    }

    public function jabatan()
    {
        $data = [
            'user' => $this->user,
            'judul' => 'Daftar Klasifikasi',
            'jabatan' => $this->pengaturan->getJabatan()
        ];

        $this->template->render_page('settings/jabatan', $data);
    }

    public function jabatan_tambah()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->form_validation->set_rules('jabatan', 'Jabatan', 'required|is_unique[jabatan.jabatan]');

            $this->form_validation->set_error_delimiters('', '');
            if ($this->form_validation->run() == false) {
                $errors = [
                    'jabatan' => form_error('jabatan')
                ];
                $data = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($data);
            } else {
                $this->pengaturan->insertJabatan();
            }
        } else {
            redirect('pengaturan/klasifikasi');
        }
    }

    public function jabatan_ubah()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id');
            $jabatan = $this->input->post('jabatan', true);
            $data = $this->pengaturan->getJabatan($id);

            if ($jabatan == $data['jabatan']) {
                $rules = 'required';
            } else {
                $rules = 'required|is_unique[jabatan.jabatan]';
            }

            $this->form_validation->set_rules('jabatan', 'Jabatan', $rules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == false) {
                $errors = [
                    'jabatan' => form_error('jabatan')
                ];
                $data = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($data);
            } else {
                $this->pengaturan->updateJabatan($id, $jabatan);
            }
        } else {
            redirect('pengaturan/klasifikasi');
        }
    }

    public function jabatan_hapus()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $this->db->delete('jabatan');

            $data = [
                'status' => TRUE,
                'msg' => 'Data berhasil dihapus'
            ];
            echo json_encode($data);
        } else {
            redirect('pengaturan/klasifikasi');
        }
    }

    public function sifat()
    {
        $data = [
            'user' => $this->user,
            'judul' => 'Daftar Sifat Surat',
            'sifat' => $this->pengaturan->getSifat()
        ];

        $this->template->render_page('settings/sifat', $data);
    }

    public function sifat_tambah()
    {
        $this->form_validation->set_rules('sifat', 'Sifat', 'required|is_unique[sifat.sifat]', [
            'required' => 'Sifat surat wajib diisi.',
            'is_unique' => 'Sifat surat sudah ada.',
        ]);
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == false) {
            $errors = [
                'sifat' => form_error('sifat'),
            ];

            $data = [
                'status' => FALSE,
                'errors' => $errors
            ];
            echo json_encode($data);
        } else {
            $this->pengaturan->insertSifat();
        }
    }

    public function sifat_ubah()
    {
        $id = $this->input->post('id');
        $sifat = $this->input->post('sifat', true);
        $data = $this->pengaturan->getSifat($id);

        if ($sifat == $data['sifat']) {
            $rules = 'required';
        } else {
            $rules = 'required|is_unique[sifat.sifat]';
        }

        $this->form_validation->set_rules('sifat', 'Sifat', $rules, [
            'required' => 'Sifat surat wajib diisi.',
            'is_unique' => 'Sifat surat sudah ada.',
        ]);
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == false) {
            $errors = [
                'sifat' => form_error('sifat'),
            ];

            $data = [
                'status' => FALSE,
                'errors' => $errors
            ];
            echo json_encode($data);
        } else {
            $this->pengaturan->updateSifat($id, $sifat);
        }
    }

    public function sifat_hapus()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $this->db->delete('sifat');

            $data = [
                'status' => TRUE,
                'msg' => 'Data berhasil dihapus.'
            ];
            echo json_encode($data);
        } else {
            redirect('pengaturan/sifat');
        }
    }
}
