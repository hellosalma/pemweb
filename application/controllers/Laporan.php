<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logout();

        $this->user = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->model('Sm_model', 'sm');
        $this->load->model('Sk_model', 'sk');
        $this->load->library('dompdf_gen'); // load library dompdf
    }

    public function sm()
    {
        $data = [
            'user' => $this->user,
            'judul' => 'Laporan - Surat Masuk',
        ];

        $this->template->render_page('laporan/sm', $data);
    }

    public function cek_laporan_sm()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->form_validation->set_rules('tgl_mulai', 'Field diatas', 'required');
            $this->form_validation->set_rules('tgl_akhir', 'Field diatas', 'required');
            $this->form_validation->set_rules('difilter', 'Field diatas', 'required');

            $this->form_validation->set_error_delimiters('', '');
            if ($this->form_validation->run() == false) {
                $errors = [
                    'tgl_mulai' => form_error('tgl_mulai'),
                    'tgl_akhir' => form_error('tgl_akhir'),
                    'difilter' => form_error('difilter'),
                ];
                $data = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($data);
            } else {
                $data = [
                    'status' => TRUE,
                    'data' => $this->sm->getSmByDate()
                ];
                echo json_encode($data);
            }
        } else {
            redirect('laporan/sm');
        }
    }

    public function export_laporan_sm()
    {
        if (isset($_POST['pdf'])) {
            $data = [
                'sm' => $this->sm->fetch_data()
            ];

            $this->load->view('laporan/pdf-sm', $data);

            // konfigurasi dompdf
            $paper_size = 'A4';
            $orientation = 'landscape';
            $html = $this->output->get_output();
            $this->dompdf->set_paper($paper_size, $orientation);

            $this->dompdf->load_html($html);
            $this->dompdf->render();
            $this->dompdf->stream('Laporan Surat Masuk.pdf', ['Attachment' => true]); // false atau 0 untuk preview sebelum download

            exit;

            // jika bukan, cek jika yang diklik adalah button dengan name='excel'
        } else if (isset($_POST['excel'])) {
            $data = $this->sm->fetch_data();
            $file_name = 'Laporan Surat Masuk.xlsx';

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NOMOR AGENDA');
            $sheet->setCellValue('B1', 'PENGIRIM');
            $sheet->setCellValue('C1', 'NOMOR SURAT');
            $sheet->setCellValue('D1', 'ISI RINGKAS');
            $sheet->setCellValue('E1', 'TANGGAL SURAT');
            $sheet->setCellValue('F1', 'TANGGAL DITERIMA');
            $sheet->setCellValue('G1', 'DISPOSISI SURAT');
            $sheet->setCellValue('H1', 'KETERANGAN');

            $count = 2;
            foreach ($data as $row) {
                $sheet->setCellValue('A' . $count, $row['no_agenda']);
                $sheet->setCellValue('B' . $count, $row['pengirim']);
                $sheet->setCellValue('C' . $count, $row['no_surat']);
                $sheet->setCellValue('D' . $count, $row['isi']);
                $sheet->setCellValue('E' . $count, $row['tgl_surat']);
                $sheet->setCellValue('F' . $count, $row['tgl_diterima']);
                $sheet->setCellValue('G' . $count, $row['disposisi']);
                $sheet->setCellValue('H' . $count, $row['keterangan']);

                $count++;
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($file_name);

            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=" . basename($file_name));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length:' . filesize($file_name));

            flush();
            readfile($file_name);
            exit;
        }
    }

    public function sk()
    {
        $data = [
            'user' => $this->user,
            'judul' => 'Laporan - Surat Keluar',
        ];

        $this->template->render_page('laporan/sk', $data);
    }

    public function cek_laporan_sk()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->form_validation->set_rules('tgl_mulai', 'Field diatas', 'required');
            $this->form_validation->set_rules('tgl_akhir', 'Field diatas', 'required');
            $this->form_validation->set_rules('difilter', 'Field diatas', 'required');

            $this->form_validation->set_error_delimiters('', '');
            if ($this->form_validation->run() == false) {
                $errors = [
                    'tgl_mulai' => form_error('tgl_mulai'),
                    'tgl_akhir' => form_error('tgl_akhir'),
                    'difilter' => form_error('difilter'),
                ];
                $data = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($data);
            } else {
                $data = [
                    'status' => TRUE,
                    'data' => $this->sk->getSkByDate()
                ];
                echo json_encode($data);
            }
        } else {
            redirect('laporan/sk');
        }
    }

    public function export_laporan_sk()
    {
        if (isset($_POST['pdf'])) {

            $data = [
                'sk' => $this->sk->fetch_data()
            ];

            $this->load->view('laporan/pdf-sk', $data);

            // konfigurasi dompdf
            $paper_size = 'A4';
            $orientation = 'landscape';
            $html = $this->output->get_output();
            $this->dompdf->set_paper($paper_size, $orientation);

            $this->dompdf->load_html($html);
            $this->dompdf->render();
            $this->dompdf->stream('Laporan Surat Keluar.pdf', ['Attachment' => true]);

            exit;
        } else if (isset($_POST['excel'])) {
            $data = $this->sk->fetch_data();
            $file_name = 'Laporan Surat Keluar.xlsx';

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NOMOR AGENDA');
            $sheet->setCellValue('B1', 'PENGIRIM');
            $sheet->setCellValue('C1', 'NOMOR SURAT');
            $sheet->setCellValue('D1', 'ISI RINGKAS');
            $sheet->setCellValue('E1', 'TANGGAL SURAT');
            $sheet->setCellValue('F1', 'TANGGAL DITERIMA');
            $sheet->setCellValue('G1', 'KETERANGAN');

            $count = 2;
            foreach ($data as $row) {
                $sheet->setCellValue('A' . $count, $row['no_agenda']);
                $sheet->setCellValue('B' . $count, $row['pengirim']);
                $sheet->setCellValue('C' . $count, $row['no_surat']);
                $sheet->setCellValue('D' . $count, $row['isi']);
                $sheet->setCellValue('E' . $count, $row['tgl_surat']);
                $sheet->setCellValue('F' . $count, $row['tgl_diterima']);
                $sheet->setCellValue('G' . $count, $row['keterangan']);

                $count++;
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($file_name);

            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=" . basename($file_name));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length:' . filesize($file_name));

            flush();
            readfile($file_name);
            exit;
        }
    }
}
