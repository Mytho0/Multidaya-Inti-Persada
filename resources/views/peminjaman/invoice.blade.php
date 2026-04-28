<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $peminjaman->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
            background: #fff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            font-size: 22px;
            margin-bottom: 5px;
        }
        .header h3 {
            font-size: 14px;
            color: #666;
            font-weight: normal;
        }
        .header p {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        /* Title */
        .title {
            text-align: center;
            margin: 15px 0;
        }
        .title h2 {
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        /* Info Table */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 30%;
            font-weight: bold;
        }
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 11px;
        }
        .items-table td:last-child,
        .items-table th:last-child {
            text-align: right;
        }
        .items-table td:nth-child(3),
        .items-table th:nth-child(3),
        .items-table td:nth-child(4),
        .items-table th:nth-child(4) {
            text-align: right;
        }
        .items-table td:nth-child(2),
        .items-table th:nth-child(2) {
            text-align: center;
        }
        /* Total */
        .total {
            text-align: right;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .total table {
            width: 280px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .total td {
            padding: 5px;
        }
        .total .grand-total {
            font-size: 14px;
            font-weight: bold;
        }
        /* Terbilang */
        .terbilang {
            margin: 15px 0;
            padding: 10px;
            background: #f9f9f9;
            border-left: 3px solid #333;
            font-style: italic;
            font-size: 11px;
        }
        /* Tanda Tangan */
        .ttd {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .ttd div {
            text-align: center;
            width: 200px;
        }
        .ttd .line {
            margin-top: 40px;
            padding-top: 5px;
            border-top: 1px solid #333;
            width: 180px;
        }
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-aktif { background: #dcfce7; color: #166534; }
        .status-selesai { background: #f3f4f6; color: #374151; }
        .status-terlambat { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Perusahaan -->
        <div class="header">
            <h1>CV. MULTIDAYA INTI PERSADA</h1>
            <h3>MIP VISUAL SOLUTIONS</h3>
            <p>Jin Rayong, Berdikari No. 17, Kedon Jeruk, Jakarta Barat 11540</p>
            <p>Telp: 08123456789 | Email: info@multidaya.com</p>
        </div>

        <!-- Title KWITANSI -->
        <div class="title">
            <h2>KWITANSI</h2>
        </div>

        <!-- Info Peminjaman -->
        <table class="info-table">
            <tr><td>No. Invoice</td><td>: {{ $peminjaman->invoice_number }}</td></tr>
            <tr><td>Tanggal</td><td>: {{ now()->format('d/m/Y') }}</td></tr>
            <tr><td>Nama Penyewa</td><td>: {{ $peminjaman->nama_penyewa }}</td></tr>
            <tr><td>No. Telepon</td><td>: {{ $peminjaman->no_telepon }}</td></tr>
            <tr><td>Nama Acara</td><td>: {{ $peminjaman->nama_acara ?? '-' }}</td></tr>
            <tr><td>Lokasi Acara</td><td>: {{ $peminjaman->lokasi_acara ?? '-' }}</td></tr>
            <tr><td>Periode Sewa</td><td>: {{ \Carbon\Carbon::parse($peminjaman->tanggal_sewa)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y') }}</td></tr>
            <tr><td>Waktu Sewa</td><td>: {{ $peminjaman->waktu_sewa }} - {{ $peminjaman->waktu_kembali }}</td></tr>
            <tr><td>Status</td><td>: <span class="status-badge status-{{ $peminjaman->status_pengembalian }}">{{ strtoupper($peminjaman->status_pengembalian) }}</span></td></tr>
        </table>

        <!-- Detail Barang -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th width="50">Jml</th>
                    <th width="100">Harga</th>
                    <th width="100">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman->details as $detail)
                <tr>
                    <td>{{ $detail->nama_barang }}</td>
                    <td style="text-align: center">{{ $detail->jumlah }}</td>
                    <td style="text-align: right">Rp {{ number_format($detail->harga_sewa, 0, ',', '.') }}</td>
                    <td style="text-align: right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total -->
        <div class="total">
            <table>
                <tr>
                    <td width="150">Subtotal</td>
                    <td width="10">:</td>
                    <td width="120">Rp {{ number_format($peminjaman->total_harga, 0, ',', '.') }}</td>
                </tr>
                @if($peminjaman->diskon > 0)
                <tr>
                    <td>Diskon</td>
                    <td>:</td>
                    <td>Rp {{ number_format($peminjaman->diskon, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($peminjaman->denda > 0)
                <tr>
                    <td>Denda</td>
                    <td>:</td>
                    <td class="text-red">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr style="font-weight: bold; font-size: 14px;">
                    <td>TOTAL</td>
                    <td>:</td>
                    <td>Rp {{ number_format($peminjaman->grand_total + ($peminjaman->denda ?? 0), 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Terbilang -->
        <div class="terbilang">
            <strong>Terbilang:</strong> {{ terbilang($peminjaman->grand_total + ($peminjaman->denda ?? 0)) }} Rupiah
        </div>

        <!-- Tanda Tangan -->
        <div class="ttd">
            <div>
                <p>Hormat Kami,</p>
                <div class="line"></div>
                <p>(.........................)</p>
            </div>
            <div>
                <p>Penerima,</p>
                <div class="line"></div>
                <p>{{ $peminjaman->nama_penyewa }}</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda menggunakan jasa kami</p>
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>

@php
function terbilang($angka) {
    $angka = (float)$angka;
    $bilangan = array('', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas');
    
    if ($angka < 12) {
        return $bilangan[$angka];
    } elseif ($angka < 20) {
        return $bilangan[$angka - 10] . ' Belas';
    } elseif ($angka < 100) {
        return $bilangan[floor($angka / 10)] . ' Puluh ' . $bilangan[$angka % 10];
    } elseif ($angka < 200) {
        return 'Seratus ' . terbilang($angka - 100);
    } elseif ($angka < 1000) {
        return $bilangan[floor($angka / 100)] . ' Ratus ' . terbilang($angka % 100);
    } elseif ($angka < 2000) {
        return 'Seribu ' . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        return terbilang(floor($angka / 1000)) . ' Ribu ' . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        return terbilang(floor($angka / 1000000)) . ' Juta ' . terbilang($angka % 1000000);
    }
    return 'Angka terlalu besar';
}
@endphp