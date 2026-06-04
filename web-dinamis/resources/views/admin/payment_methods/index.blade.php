@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Metode Pembayaran</h2>
        <a href="{{ route('admin.payment-methods.create') }}" class="bg-[#CFB53B] text-white px-4 py-2 rounded shadow hover:bg-yellow-600 transition">
            + Tambah Metode
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-700 text-sm">
                    <th class="p-4 border-b">Nama</th>
                    <th class="p-4 border-b">Tipe</th>
                    <th class="p-4 border-b">No. Rekening / Nama</th>
                    <th class="p-4 border-b">Status</th>
                    <th class="p-4 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paymentMethods as $method)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">{{ $method->name }}</td>
                        <td class="p-4">
                            @if($method->type == 'bank_transfer') Transfer Bank
                            @elseif($method->type == 'qris') QRIS
                            @elseif($method->type == 'ewallet') E-Wallet
                            @else COD @endif
                        </td>
                        <td class="p-4">
                            @if($method->account_number)
                                {{ $method->account_number }} a.n {{ $method->account_name }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="p-4">
                            @if($method->is_active)
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Aktif</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Nonaktif</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <a href="{{ route('admin.payment-methods.edit', $method->id) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                            <form action="{{ route('admin.payment-methods.destroy', $method->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus metode ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data metode pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
