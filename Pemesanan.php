<?php

namespace App\Controllers;

use App\Models\DeliveryModel;
use App\Models\PelangganModel;
use App\Models\PembayaranModel;
use App\Models\PemesananModel;
use App\Models\ServerSideModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Services;

class Pemesanan extends BaseController
{
    public function __construct()
    {
        // load Helper Form
        helper('form');

        $this->requestx = Services::request();
        // load Model
        // $this->pemesanan = new Pemesanan($this->requestx);
        $this->serverside_model = new ServerSideModel();
    }

    public function index()
    {
        $session = session();
        if ($session->get('role') == "ADMIN") {
            $hal = "admin/list_pemesanan";
        } else {
            $hal = "users/list_pemesanan";
        }
        $data = array(
            'id' => $session->get('id'),
            'User_Name' => $session->get('User_Name'),
            'nama_lengkap' => $session->get('nama_lengkap'),
            'role' => $session->get('role'),
            'current_uri' => $this->request->uri->getSegment(1),
        );
        return view($hal, $data);
    }

    public function ajax_list()
    {
        $model = new PemesananModel($this->requestx);
        $session = session();
        if ($this->request->getMethod(true) === 'POST') {
            $column_order = array('IDPemesanan', 'IDPelanggan', 'IDAdmin');
            $column_search = array('IDPemesanan', 'IDPelanggan', 'IDAdmin');
            $order = array('TanggalPemesanan' => 'desc');

            if ($session->get('role') == "ADMIN") {
                $where = "";
            } else {
                $where = array('IDPelanggan' => $session->get('id'));
            }

            $lists = $this->serverside_model->get_datatables('v_pemesanan', $column_order, $column_search, $order, $where);
            $data = [];
            $no = $this->request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->IDPemesanan;
                $row[] = $list->NamaPemesan;
                $row[] = date('d M Y H:i:s', strtotime($list->TanggalPemesanan));
                $row[] = $list->Status;
                $row[] = $list->IDPemesanan;
                $data[] = $row;
            }
            $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $this->serverside_model->count_all('v_pemesanan', $where),
                "recordsFiltered" => $this->serverside_model->count_filtered('v_pemesanan', $column_order, $column_search, $order, $where),
                "data" => $data
            ];
            echo json_encode($output);
        }
    }

    public function create()
    {
        $model = new PemesananModel($this->requestx);
        $pelanggan = new PelangganModel();
        $session = session();
        $xpelanggan = "";
        if ($session->get('role') == "ADMIN") {
            $hal = "admin/form_pemesanan";
            $xpelanggan = $pelanggan->asObject()->findAll();
            $IDPelanggan = $this->request->getVar('IDPelanggan');
        } else {
            $hal = "users/form_pemesanan";
            $IDPelanggan = $session->get('id');
        }
        $data = array(
            'id' => $model->generateCode(),
            'IDPelanggan' => $session->get('id'),
            'User_Name' => $session->get('User_Name'),
            'nama_lengkap' => $session->get('nama_lengkap'),
            'NomorTlp' => $session->get('NomorTlp'),
            'role' => $session->get('role'),
            'current_uri' => $this->request->uri->getSegment(1),
            'pelanggan' => $xpelanggan
        );
        if ($this->request->getMethod(true) === 'POST') {
            $dataPemesanan = [
                'IDPemesanan' => $model->generateCode(),
                'IDPelanggan' => $IDPelanggan,
                'BeratPakaian' => $this->request->getVar('BeratPakaian'),
                'TipePakaian' => $this->request->getVar('TipePakaian'),
                'KondisiPakaian' => $this->request->getVar('KondisiPakaian'),
                'HariPengerjaan' => $this->request->getVar('WaktuPengerjaan'),
                'Status' => 'PENDING'
            ];

            $modelDelivery = new DeliveryModel($this->requestx);
            $dataDelivery = [
                'IDDelivery' => $modelDelivery->generateCode(),
                'HargaDelivery' => $this->request->getVar('HargaDelivery')
            ];

            $modelPembayaran = new PembayaranModel($this->requestx);
            $dataPembayaran = [
                'IDPembayaran' => $modelPembayaran->generateCode(),
                'Jumlah' => $this->request->getVar('Jumlah'),
                'Status_Pembayaran' => "PENDING",
            ];

            $model->saveData($dataPemesanan, $dataDelivery, $dataPembayaran);
            return redirect()->to('/pemesanan/create');
        }

        return view($hal, $data);
    }

    public function read($kode)
    {
        $model = new PemesananModel($this->requestx);
        $session = session();
        if ($session->get('role') == "ADMIN") {
            $hal = "admin/detail_pemesanan";
        } else {
            $hal = "users/detail_pemesanan";
        }
        $this->data_session = array(
            'id' => $session->get('id'),
            'User_Name' => $session->get('User_Name'),
            'nama_lengkap' => $session->get('nama_lengkap'),
            'role' => $session->get('role'),
            'current_uri' => $this->request->uri->getSegment(1),
        );
        $this->data_session['pengajuan'] = $model->searchData(array('IDPemesanan' => $kode));

        if ($this->data_session['pengajuan']->HariPengerjaan == '6') {
            $maxpembayaran = date('d M Y H:i:s', strtotime('+6 hours', strtotime($this->data_session['pengajuan']->TanggalPemesanan)));
        } else if ($this->data_session['pengajuan']->HariPengerjaan == '48') {
            $maxpembayaran = date('d M Y H:i:s', strtotime('+48 hours', strtotime($this->data_session['pengajuan']->TanggalPemesanan)));
        } else if ($this->data_session['pengajuan']->HariPengerjaan == '72') {
            $maxpembayaran = date('d M Y H:i:s', strtotime('+72 hours', strtotime($this->data_session['pengajuan']->TanggalPemesanan)));
        } else {
            $maxpembayaran = 0;
        }

        $this->data_session['pengajuan']->maxPembayaran = $maxpembayaran;

        if (!$this->data_session['pengajuan']) {
            throw PageNotFoundException::forPageNotFound();
        }
        return view($hal, $this->data_session);
    }

    public function update($kode)
    {
        $model = new PemesananModel($this->requestx);
        $data = array(
            "Status" => $this->request->getVar('Status'),
        );
        $model->updateData($kode, $data);
        return redirect()->to('/pemesanan');
    }

    public function delete($kode)
    {
        $model = new PemesananModel($this->requestx);
        $delivery = new DeliveryModel($this->requestx);
        $pembayaran = new PembayaranModel($this->requestx);
        $session = session();
        if ($session->get('role') == "ADMIN") {
            $hal = "admin/list_pemesanan";
        } else {
            $hal = "users/list_pemesanan";
        }
        
        if ($this->request->getMethod() === "post") {
            
            $model->delete($kode);
            $delivery->where("IDPemesanan", $kode)->delete();
            $pembayaran->where("IDPemesanan", $kode)->delete();
        }
        return redirect()->to('/pemesanan');
    }

    public function search()
    {
        $pelanggan = new PelangganModel();
        $xpelanggan = $pelanggan->where("IDPelanggan", $this->request->getVar('IDPelanggan'))->first();
        if (!is_null($xpelanggan)) {
            return json_encode($xpelanggan);
        } else {
            return json_encode('error');
        }
    }
}
