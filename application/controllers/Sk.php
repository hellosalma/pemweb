<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logout();

        $this->user = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->model('Sk_model', 'sk');
    }

    public function index()
    {
        $data = [
            'user' => $this->user,
            'judul' => 'Surat Keluar'
        ];

        $this->template->render_page('surat-keluar/index', $data);
    }

    public function ambilData()
    {
        if ($this->input->is_ajax_request() == true) { // jika ada request ajax yang dikirimkan
            $list = $this->sk->get_datatables();
            $data = [];
            $no = $_POST['start'];
            foreach ($list as $field) {

                $no++;
                $row = [];

                // tombol aksi
                $btnAction = "<div class=\"dropdown\">
                    <button class=\"btn btn-sm btn-info dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                        <i class=\"fa fa-fw fa-list\"></i>
                    </button>
                    <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                        <a href='surat-keluar/$field->id' class='dropdown-item'>Detail</a>
                        <a href='surat-keluar/ubah/$field->id' class='dropdown-item'>Ubah</a>
                    </div>
                </div>";

                $btnFile = "<div class=\"dropdown\">
                    <button class=\"btn btn-sm btn-success dropdown-toggle\" type=\"button\" id=\"dropdownFileButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                        <i class=\"fa fa-fw fa-list\"></i>
                    </button>
                    <div class=\"dropdown-menu\" aria-labelledby=\"dropdownFileButton\">
                    <a href=\"uploads/$field->file\" target=\"_blank\" class='dropdown-item'> Preview</a>
                    <a href=\"uploads/$field->file\" download class='dropdown-item'> Download</a>
                    </div>
                </div>";

                $row[] = "<input type=\"checkbox\" class=\"centangId\" value=\"$field->id\" name=\"id[]\">";
                $row[] = $field->no_agenda;
                $row[] = $field->pengirim;
                $row[] = $field->no_surat;
                $row[] = date('d/m/Y', strtotime($field->tgl_surat));
                ($field->file == null ? $row[] = '-' : $row[] = $btnFile);
                $row[] = $btnAction;
                $data[] = $row;
            }

            $output = [
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->sk->count_all(),
                "recordsFiltered" => $this->sk->count_filtered(),
                "data" => $data,
            ];
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function hapus_multiple()
    {
        if ($this->input->is_ajax_request() == true) {

            $id = $this->input->post('id');
            $jmlData = count($id);

            $hapusData = $this->sk->hapus_multiple($id, $jmlData);

            if ($hapusData == true) {
                $msg = [
                    'status' => TRUE,
                    'msg' => "$jmlData data surat masuk dihapus."
                ];
            } else {
                $msg  = [
                    'status' => FALSE,
                    'msg' => 'Internal Error'
                ];
            }

            echo json_encode($msg);
        } else {
            exit('Gagal terhubung!');
        }
    }

    public function detail($id)
    {
        $data = [
            'user' => $this->user,
            'judul' => 'Detail Surat Keluar',
            'surat' => $this->sk->getSuratKeluar($id),
        ];

        $this->template->render_page('surat-keluar/detail', $data);
    }

    public function tambah()
    {
        $data = [
            'user' => $this->user,
            'judul' => 'Tambah Surat Keluar'
        ];
        $this->template->render_page('surat-keluar/tambah', $data);
    }

    public function tambah_sk()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->form_validation->set_rules('no_agenda', 'No. Agenda', 'required|numeric|is_unique[surat_keluar.no_agenda]');
            $this->form_validation->set_rules('pengirim', 'Pengirim', 'required');
            $this->form_validation->set_rules('no_surat', 'No. Surat', 'required');
            $this->form_validation->set_rules('isi', 'Isi Ringkas', 'required|max_length[300]');
            $this->form_validation->set_rules('tgl_surat', 'Tanggal Surat', 'required');
            $this->form_validation->set_rules('tgl_diterima', 'Tanggal Diterima', 'required');

            $this->form_validation->set_error_delimiters('', '', '');
            if ($this->form_validation->run() == FALSE) {
                $errors  = [
                    'no_agenda' => form_error('no_agenda'),
                    'pengirim' => form_error('pengirim'),
                    'no_surat' => form_error('no_surat'),
                    'isi' => form_error('isi'),
                    'tgl_surat' => form_error('tgl_surat'),
                    'tgl_diterima' => form_error('tgl_diterima'),
                ];

                $data = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($data);
            } else {
                $upload_file = $_FILES['file']['name'];

                if ($upload_file) {
                    $config['upload_path'] = './uploads';
                    $config['allowed_types'] = 'pdf';
                    $config['max_size'] = 5000;
                    $config['encrypt_name'] = TRUE;

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('file')) {
                        $file = $this->upload->data('file_name');

                        // inserting data into db
                        $this->sk->insert_data($file);
                    } else {
                        $errors = [
                            'file' => $this->upload->display_errors('', '')
                        ];

                        $data = [
                            'status' => FALSE,
                            'errors' => $errors
                        ];
                        echo json_encode($data);
                    }
                } else {
                  $this->sm->insert_data();
                }
            }
        } else {
            redirect('surat-keluar/tambah');
        }
    }

    public function hapus()
    {
        $id = $this->input->post('id');
        $data['surat'] = $this->sk->getSuratKeluar($id);

        $this->db->where('id', $id);
        $this->db->delete('surat_keluar');
        $this->session->set_flashdata('msg', 'dihapus.');

        // Hapus file di folder uploads
        unlink(FCPATH . './uploads/' . $data['surat']['file']);
        redirect('surat-keluar');
    }

    public function ubah($id)
    {
        $data = [
            'user' => $this->user,
            'judul' => 'Ubah Surat Keluar',
            'surat' => $this->sk->getSuratKeluar($id)
        ];

        $this->template->render_page('surat-keluar/ubah', $data);
    }

    public function ubah_sk()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id');
            $noAgenda = $this->input->post('no_agenda');
            $sk = $this->sk->getSuratKeluar($id);

            if ($sk['no_agenda'] == $noAgenda) {
                $ruleAgenda = 'required|numeric';
            } else {
                $ruleAgenda = 'required|numeric|is_unique[surat_keluar.no_agenda]';
            }
            $this->form_validation->set_rules('no_agenda', 'No. Agenda', $ruleAgenda);
            $this->form_validation->set_rules('no_surat', 'No. Surat', 'required');
            $this->form_validation->set_rules('pengirim', 'Pengirim', 'required');
            $this->form_validation->set_rules('isi', 'Isi Ringkas', 'required|max_length[300]');
            $this->form_validation->set_rules('tgl_surat', 'Tanggal Surat', 'required');
            $this->form_validation->set_rules('tgl_diterima', 'Tanggal Diterima', 'required');

            $this->form_validation->set_error_delimiters('', '');
            if ($this->form_validation->run() == FALSE) {

                $errors  = [
                    'no_agenda' => form_error('no_agenda'),
                    'pengirim' => form_error('pengirim'),
                    'no_surat' => form_error('no_surat'),
                    'isi' => form_error('isi'),
                    'tgl_surat' => form_error('tgl_surat'),
                    'tgl_diterima' => form_error('tgl_diterima'),
                    'file' => form_error('file'),
                ];

                $data = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($data);
            } else {
                $upload_file = $_FILES['file']['name'];

                if ($upload_file) {
                    $config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'pdf';
                    $config['max_size'] = 5000;
                    $config['encrypt_name'] = TRUE;

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('file')) {
                        $file = $this->upload->data('file_name');
                        $file = str_replace('', '_', $file);

                        // update data w/ new file
                        $this->sk->update_data($file);
                    } else {
                        $errors = [
                            'file' => $this->upload->display_errors('', '')
                        ];

                        $data = [
                            'status' => FALSE,
                            'errors' => $errors
                        ];
                        echo json_encode($data);
                    }
                } else {
                    $this->sk->update_data();
                }
            }
        } else {
            redirect('auth/notfound');
        }
    }
}
