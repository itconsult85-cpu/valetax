<?php
namespace App\Controllers;
use App\Models\SettingModel;

class Settings extends BaseController
{
    public function index()
    {
        $model = new SettingModel();
        $data['setting'] = $model->find(1); // Ambil data dengan ID 1
        return view('settings_view', $data);
    }

    public function update()
    {
        $model = new SettingModel();
        $model->update(1, [
            'admin_number' => $this->request->getPost('admin_number'),
            'link_mifx'    => $this->request->getPost('link_mifx'),
            'link_valetax' => $this->request->getPost('link_valetax')
        ]);
        return redirect()->to('/bot_settings')->with('pesan', 'Data berhasil diperbarui!');
    }
}