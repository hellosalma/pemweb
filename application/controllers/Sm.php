<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sm extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logout();

        $this->user = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->model('Sm_model', 'sm');
    }

    public function index()
    {
        $data = [
            'user' => $this->user,
            'judul' => 'Surat Masuk'
        ];

        $this->template->render_page('surat-masuk/index', $data);
    }

    public function ambilData()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->sm->get_datatables();
            $data = [];
            $no = $_POST['start'];
            foreach ($list as $field) {

                $no++;
                $row = [];

                // tombol aksi
                $btnAction = "<div class=\"dropdown\">
                        <button class=\"btn btn-sm btn-info dropdown-toggle\" type=\"button\" class=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                            <i class=\"fa fa-fw fa-list\"></i>
                        </button>
                        <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                            <a href='" . base_url('/') . "disposisi/$field->id' class='dropdown-item'>Disposisi</a>
                            <a href='surat-masuk/$field->id' class='dropdown-item'>Detail</a>
                            <a href='surat-masuk/ubah/$field->id' class='dropdown-item'>Ubah</a>
                        </div>
                    </div>";

                $btnFile = "<div class=\"dropdown\">
                    <button class=\"btn btn-sm btn-success dropdown-toggle\" type=\"button\" class=\"dropdownFileButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
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
                $row[] = $btnFile;
                // ($field->file == null ? $row[] = '-' : $row[] = $btnFile);
                $row[] = $btnAction;
                $data[] = $row;
            }

            $output = [
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->sm->count_all(),
                "recordsFiltered" => $this->sm->count_filtered(),
                "data" => $data,
            ];
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

            $hapusData = $this->sm->hapus_multiple($id, $jmlData);

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
            'judul' => 'Detail Surat Masuk',
            'surat' => $this->sm->getSuratMasuk($id),
        ];

        $this->template->render_page('surat-masuk/detail', $data);
    }

    public function tambah()
    {
        $data = [
            'user' => $this->user,
            'judul' => 'Tambah Surat Masuk'
        ];

        $this->template->render_page('surat-masuk/tambah', $data);
    }

    public function tambah_sm()
    {
        $this->form_validation->set_rules('no_agenda', 'No. Agenda', 'required|numeric|is_unique[surat_masuk.no_agenda]');
        $this->form_validation->set_rules('no_surat', 'No. Surat', 'required');
        $this->form_validation->set_rules('pengirim', 'Pengirim', 'required');
        $this->form_validation->set_rules('isi', 'Isi Ringkas', 'required|max_length[300]');
        $this->form_validation->set_rules('tgl_surat', 'Tanggal Surat', 'required');
        $this->form_validation->set_rules('tgl_diterima', 'Tanggal Diterima', 'required');
        // if (empty($_FILES['file']['name'])) {
        //     $this->form_validation->set_rules('file', 'File', 'required');
        // }

        $this->form_validation->set_error_delimiters('', '', '');
        if ($this->form_validation->run() == FALSE) {
            $errors  = [
                'no_agenda' => form_error('no_agenda'),
                'pengirim' => form_error('pengirim'),
                'no_surat' => form_error('no_surat'),
                'isi' => form_error('isi'),
                'tgl_surat' => form_error('tgl_surat'),
                'tgl_diterima' => form_error('tgl_diterima'),
                // 'file' => form_error('file'),
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
                    $this->sm->insert_data($file);
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
    }

    public function ubah($id)
    {
        $data = [
            'user' => $this->user,
            'judul' => 'Ubah Surat Masuk',
            'surat' => $this->sm->getSuratMasuk($id)
        ];

        $this->template->render_page('surat-masuk/ubah', $data);
    }

    public function ubah_sm()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id');
            $noAgenda = $this->input->post('no_agenda');
            $sm = $this->sm->getSuratMasuk($id);

            if ($sm['no_agenda'] == $noAgenda) {
                $ruleAgenda = 'required|numeric';
            } else {
                $ruleAgenda = 'required|numeric|is_unique[surat_masuk.no_agenda]';
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
                        $this->sm->update_data($file);
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
                    $this->sm->update_data();
                }
            }
        } else {
            redirect('auth/notfound');
        }
    }
}
