<?php

namespace App\Controllers;
use App\Models\CatatanModel;
use CodeIgniter\Controller;

class Catatan extends Controller
{
    public function index()
    {
        $model = new CatatanModel();
        $data['catatan'] = $model->findAll();
        return view('catatan', $data);
    }

    public function simpan()
    {
        $model = new CatatanModel();
        $model->save([
            'keterangan' => $this->request->getPost('keterangan'),
            'jumlah' => $this->request->getPost('jumlah'),
            'jenis' => $this->request->getPost('jenis'),
            'tanggal' => $this->request->getPost('tanggal') 
        ]);

        return redirect()->to('/catatan');
    }
}
