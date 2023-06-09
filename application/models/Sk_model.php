<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sk_model extends CI_Model
{
    var $column_order = [null, 'no_agenda', 'pengirim', null, 'tgl_surat', null]; // Field yang bisa orderable
    var $column_search = ['no_agenda', 'pengirim', 'no_surat', 'isi', 'keterangan']; // field yang diizin utk pencarian
    var $order = ['no_agenda' => 'asc']; // default order

    private function _get_datatables_query()
    {
        $this->db->from('surat_keluar'); // surat_keluar adalah table

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('surat_keluar');
        return $this->db->count_all_results();
    }

    public function getSuratKeluar($id = false)
    {
        if ($id == false) {
            $this->db->order_by('no_agenda', 'ASC');
            return $this->db->get('surat_keluar')->result_array();
        } else {
            $this->db->where('id', $id);
            return $this->db->get('surat_keluar')->row_array();
        }
    }

    public function insert_data($file = false)
    {
        $data = [
            'no_agenda' => $this->input->post('no_agenda', true),
            'pengirim' => $this->input->post('pengirim', true),
            'no_surat' => $this->input->post('no_surat', true),
            'isi' => $this->input->post('isi', true),
            'tgl_surat' => $this->input->post('tgl_surat', true),
            'tgl_diterima' => $this->input->post('tgl_diterima', true),
            'keterangan' => $this->input->post('keterangan', true),
            'user_id' => $this->input->post('user_id'),
            'created_at' => date('Y-m-d')
        ];

        if ($file != false) {
          $data['file'] = $file;
        }

        $this->db->insert('surat_keluar', $data);
        $this->session->set_flashdata('msg', 'ditambahkan.');
        $data['status'] = TRUE;
        echo json_encode($data);
    }

    public function update_data($file = false)
    {

        $id = $this->input->post('id');
        $no_agenda = $this->input->post('no_agenda', true);
        $pengirim = $this->input->post('pengirim', true);
        $no_surat = $this->input->post('no_surat', true);
        $isi = $this->input->post('isi', true);
        $tgl_surat = $this->input->post('tgl_surat', true);
        $tgl_diterima = $this->input->post('tgl_diterima', true);
        $keterangan = $this->input->post('keterangan', true);
        $user_id = $this->input->post('user_id');

        if ($file != false) {
            $data['surat'] = $this->db->get_where('surat_keluar', ['id' => $id])->row_array();
            unlink(FCPATH . './uploads/' . $data['surat']['file']);

            $this->db->set('file', $file);
        }

        $this->db->set('no_agenda', $no_agenda);
        $this->db->set('pengirim', $pengirim);
        $this->db->set('no_surat', $no_surat);
        $this->db->set('isi', $isi);
        $this->db->set('tgl_surat', $tgl_surat);
        $this->db->set('tgl_diterima', $tgl_diterima);
        $this->db->set('keterangan', $keterangan);
        $this->db->set('user_id', $user_id);

        $this->db->where('id', $id);
        $this->db->update('surat_keluar');
        $this->session->set_flashdata('msg', 'diperbarui.');
        $data['status'] = TRUE;
        echo json_encode($data);
    }

    public function hapus_multiple($id, $jmlData)
    {
        for ($i = 0; $i < $jmlData; $i++) {

            $files[] = $this->db->get_where('surat_keluar', ['id' => $id[$i]])->row_array();

            if ($files[$i]['file'] != null) {
                unlink(FCPATH . 'uploads/' . $files[$i]['file']);
            }

            $this->db->delete('surat_keluar', ['id' => $id[$i]]);
        }

        return true;
    }

    public function fetch_data()
    {
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $filterby = $this->input->post('filterby');

        return $this->db->query("SELECT * FROM `surat_keluar` WHERE " . $filterby . " BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY no_agenda")->result_array();
    }

    public function getSkByDate()
    {
        $tgl_mulai = $this->input->post('tgl_mulai');
        $tgl_akhir = $this->input->post('tgl_akhir');
        $difilter = $this->input->post('difilter');
        if ($difilter == 'created_at') {
            $this->db->where('created_at >=', $tgl_mulai);
            $this->db->where('created_at <=', $tgl_akhir);
        } else if ($difilter == 'tgl_surat') {
            $this->db->where('tgl_surat >=', $tgl_mulai);
            $this->db->where('tgl_surat <=', $tgl_akhir);
        } else if ($difilter == 'tgl_diterima') {
            $this->db->where('tgl_diterima >=', $tgl_mulai);
            $this->db->where('tgl_diterima <=', $tgl_akhir);
        }

        $this->db->order_by('no_agenda', 'ASC');
        $this->db->select('*');
        return $this->db->get('surat_keluar')->result_array();
    }
}
