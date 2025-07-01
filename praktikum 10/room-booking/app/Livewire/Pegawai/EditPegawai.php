<?php

namespace App\Livewire\Pegawai;

use Livewire\Component;
use App\Models\Pegawai;
use Livewire\Attributes\Validate;
use App\Models\UnitKerja;

class EditPegawai extends Component
{
    #[Validate('required|string|max:10')]
    public $nip;

    #[Validate('required|string|max:50')]
    public string $nama;

    #[Validate('required|string|max:50')]
    public $unit_kerja;

    public Pegawai $pegawai;

    public $unit_kerjas;

    public function mount(Pegawai $pegawai){
        $this ->pegawai = $pegawai;
        $this ->nip = $pegawai->nip; 
        $this ->nama = $pegawai->nama;
        $this ->unit_kerja = $pegawai->unit_kerja_id;
        $this ->unit_kerjas = UnitKerja::all();
    }

    public function save()
    {
        $this->validate();

        $this->pegawai->update([
            'nip' => $this->nip,
            'nama' => $this->nama,
            'unit_kerja_id' => $this->unit_kerja, 
        ]);

        session()->flash('message', 'Pegawai berhasil diubah');

        $this->redirectRoute('pegawai.index');
    }

    public function render()
    {
        return view('livewire.pegawai.edit-pegawai');
    }
}
