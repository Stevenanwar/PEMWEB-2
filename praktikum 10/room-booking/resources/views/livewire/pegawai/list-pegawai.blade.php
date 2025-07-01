<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">List Pegawai</h1>

    @if (session('message'))
    <div class="bg-green-500 text-white p-4 rounded mb-4">
        {{ session('message') }}
    </div>
    @endif

    <div class="flex justify-between mb-4">
        <flux:button :href="route('pegawai.create')" variant="primary">New Pegawai</flux:button>
    </div>
    <table class="min-w-full border-collapse border border-gray-400 mt-4">
        <thead>
            <tr class="text-left bg-gray-100">
                <th class="py-2 px-4 border border-gray-300">No.</th>
                <th class="py-2 px-4 border border-gray-300">NIP</th>
                <th class="py-2 px-4 border border-gray-300">Nama</th>
                <th class="py-2 px-4 border border-gray-300">Unit Kerja</th>
                <th class="py-2 px-4 border border-gray-300">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pegawais as $index => $pegawai)
            <tr>
                <td class="py-2 px-4 border border-gray-300">{{ $index + 1 }}</td>
                <td class="py-2 px-4 border border-gray-300">{{ $pegawai->nip }}</td>
                <td class="py-2 px-4 border border-gray-300">{{ $pegawai->nama }}</td>
                <td class="py-2 px-4 border border-gray-300">{{ $pegawai->nama_unit_kerja }}</td>
                <td class="py-2 px-4 border border-gray-300">
                    <flux:button :href="route('pegawai.edit', $pegawai)">Edit</flux:button>
                    <flux:button variant="danger" wire:click="delete({{ $pegawai->id }})" wire:confirm="Are you sure?">Delete</flux:button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>