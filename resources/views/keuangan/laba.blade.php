@extends('layouts.app')
 

@section('title', 'Laba Bersih')

@section('main-content')
<div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Ringkasan Laba Bersih</h1>

    <div class="bg-white p-6 rounded-xl shadow space-y-4">
        <div class="flex justify-between">
            <span>Pendapatan</span>
            <span class="text-green-600 font-semibold">
                Rp {{ number_format($pendapatan,0,',','.') }}
            </span>
        </div>

        <div class="flex justify-between">
            <span>Pengeluaran</span>
            <span class="text-red-600 font-semibold">
                Rp {{ number_format($pengeluaran,0,',','.') }}
            </span>
        </div>

        <hr>

        <div class="flex justify-between text-lg font-bold">
            <span>Laba Bersih</span>
            <span class="text-blue-600">
                Rp {{ number_format($laba,0,',','.') }}
            </span>
        </div>
    </div>
</div>
@endsection