<?php

namespace App\Livewire\Pegawai;

use Livewire\Component;
use App\Models\Pegawai;

class ListPegawai extends Component
{
    public function render()
    {
        return view('livewire.pegawai.list-pegawai', [
            'pegawais' => Pegawai::select('pegawai.*', 'unit_kerja.nama as nama_unit_kerja')
                ->join('unit_kerja', 'pegawai.unit_kerja_id', '=', 'unit_kerja.id')
                ->get(),
        ]);
    }
    public function delete($id)
    {
        $pegawai = Pegawai::find($id);
        if ($pegawai) {
            $pegawai->delete();
            session()->flash('message', 'pegawai berhasil dihapus.');
        }
    }
}
