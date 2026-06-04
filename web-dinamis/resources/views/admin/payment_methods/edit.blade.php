@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Metode Pembayaran</h2>
        <a href="{{ route('admin.payment-methods.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded shadow p-6 max-w-2xl">
        <form action="{{ route('admin.payment-methods.update', $paymentMethod->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Metode</label>
                <input type="text" name="name" value="{{ old('name', $paymentMethod->name) }}" class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Tipe</label>
                <select name="type" class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300">
                    <option value="bank_transfer" {{ $paymentMethod->type == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="qris" {{ $paymentMethod->type == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="ewallet" {{ $paymentMethod->type == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                    <option value="cod" {{ $paymentMethod->type == 'cod' ? 'selected' : '' }}>COD</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nomor Rekening / No HP</label>
                <input type="text" name="account_number" value="{{ old('account_number', $paymentMethod->account_number) }}" class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Atas Nama (Account Name)</label>
                <input type="text" name="account_name" value="{{ old('account_name', $paymentMethod->account_name) }}" class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Upload Barcode QRIS (Opsional)</label>
                @if($paymentMethod->qr_code)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $paymentMethod->qr_code) }}" alt="QR Code" class="h-32 object-contain border p-2">
                    </div>
                @endif
                <input type="file" name="qr_code" class="w-full border rounded p-2" accept="image/*">
                <small class="text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar.</small>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ $paymentMethod->is_active ? 'checked' : '' }} class="mr-2">
                    <span class="text-gray-700 font-bold">Aktifkan Metode Ini</span>
                </label>
            </div>

            <button type="submit" class="bg-[#CFB53B] text-white px-6 py-2 rounded shadow hover:bg-yellow-600 transition">
                Update
            </button>
        </form>
    </div>
</div>
@endsection
