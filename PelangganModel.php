<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table = 'pelanggan';
    protected $allowedFields = ['IDPelanggan', 'User_Name', 'NamaPelanggan', 'NomorTlp', 'Email'];

    public function generateCode()
    {
        $kode = $this->checkCode();
        $kodetampil  = "P" . sprintf("%04s", $kode);
        return $kodetampil;
    }

    public function checkCode()
    {
        $query = $this->db->query("SELECT MAX(IDPelanggan) as kode FROM pelanggan");
        if (!is_null($query->getNumRows())) {
            $data = $query->getRow();
            $nourut = substr($data->kode, 1, 4);
            $kode = $nourut + 1;
        } else {
            $kode = 1;
        }
        return $kode;
    }
}
