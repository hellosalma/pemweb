<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Disposisi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logout();

        $this->user = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->model('Disposisi_model', 'dispos');
        $this->load->model('Pengaturan_model', 'pengaturan');
    }

    public function index($sm_id)
    {
        $query = $this->dispos->checkSm($sm_id);
        if ($query->row_array()) {
            $data = [
                'user' => $this->user,
                'judul' => 'Disposisi Surat',
                'disposisi' => $this->dispos->getDisposisi($sm_id),
                'sm_id' => $sm_id,
                'jabatan' => $this->pengaturan->getJabatan(),
                'sifat' => $this->pengaturan->getSifat(),
            ];
            $this->template->render_page('disposisi/index', $data);
        } else {
            redirect('auth/notfound');
        }
    }

    public function tambah_data()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->form_validation->set_rules('jabatan_id', 'Jabatan', 'required');
            $this->form_validation->set_rules('sifat_id', 'Sifat Surat', 'required');

            $this->form_validation->set_error_delimiters('', '');
            if ($this->form_validation->run() == FALSE) {
                $errors  = [
                    'jabatan_id' => form_error('jabatan_id'),
                    'sifat_id' => form_error('sifat_id'),
                ];
                $data = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($data);
            } else {
                $this->dispos->insert();
            }
        } else {
            redirect('auth/notfound');
        }
    }

    public function get_disposisi_row()
    {
        $id = $this->input->post('id');
        $data = $this->dispos->getRowDispos($id);
        echo json_encode($data);
    }

    public function ubah_data()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->form_validation->set_rules('jabatan_id', 'Jabatan', 'required');
            $this->form_validation->set_rules('sifat_id', 'Sifat Surat', 'required');

            $this->form_validation->set_error_delimiters('', '');
            if ($this->form_validation->run() == FALSE) {
                $errors  = [
                    'jabatan_id' => form_error('jabatan_id'),
                    'sifat_id' => form_error('sifat_id'),
                ];
                $data = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($data);
            } else {
                $this->dispos->update();
            }
        } else {
            redirect('auth/notfound');
        }
    }

    public function hapus()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id');
            $this->db->delete('disposisi', ['id' => $id]);

            $data = [
                'status' => TRUE,
                'msg' => 'Data berhasil dihapus.'
            ];
            echo json_encode($data);
        } else {
            redirect('surat-masuk');
        }
    }
}
