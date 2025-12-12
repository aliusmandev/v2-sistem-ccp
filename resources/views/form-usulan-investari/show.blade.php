<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Usulan Investasi - RS Awal Bros</title>
    <style>
        /* ... style unchanged ... */
        @page {
            size: A4;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            background: white;
            padding: 15mm;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .logo {
            background: #d32f2f;
            color: white;
            padding: 8px 15px;
            font-weight: bold;
            font-size: 14pt;
            border: 2px solid #000;
        }

        .header-title {
            text-align: right;
            flex: 1;
            margin-left: 20px;
        }

        .header-title h1 {
            font-size: 12pt;
            margin-bottom: 3px;
        }

        .header-title p {
            font-size: 9pt;
        }

        .info-box {
            background: #f5f5f5;
            padding: 8px;
            margin: 10px 0;
            font-size: 8pt;
            border: 1px solid #999;
        }

        .section-title {
            background: #e0e0e0;
            padding: 5px 8px;
            font-weight: bold;
            margin: 10px 0 5px 0;
            border: 1px solid #999;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f5f5f5;
            font-weight: bold;
            font-size: 8pt;
        }

        td {
            font-size: 8pt;
        }

        .form-row {
            display: flex;
            margin-bottom: 8px;
        }

        .form-label {
            width: 150px;
            font-weight: normal;
        }

        .form-value {
            flex: 1;
            border-bottom: 1px solid #000;
            min-height: 20px;
        }

        .checkbox {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            margin: 0 5px;
            vertical-align: middle;
        }

        .note {
            font-size: 7pt;
            font-style: italic;
            color: #333;
            margin: 5px 0;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .footer-note {
            background: #ffeb3b;
            padding: 8px;
            margin-top: 20px;
            font-size: 7pt;
            border: 1px solid #000;
            text-align: right;
        }

        .small-text {
            font-size: 7pt;
        }

        .budget-section {
            margin: 10px 0;
        }

        .budget-row {
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #999;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">RS AWAL BROS</div>
        <div class="header-title">
            <h1>FORMULIR USULAN INVESTASI</h1>
            <p>Nominal Permintaan 3 Juta s/d 50 Juta</p>
        </div>
    </div>

    <div class="info-box">
        <strong>Data Usulan Investasi</strong>
    </div>

    <div class="section-title">Data Usulan Investasi</div>
    <table>
        <tr>
            <td style="width:40%;">
                <strong>Id (FUI)</strong>: {{ $data->id ?? null }}<br>
                <strong>Id Pengajuan</strong>: {{ $data->IdPengajuan ?? null }}<br>
                <strong>Pengajuan Item Id</strong>: {{ $data->PengajuanItemId ?? null }}<br>
                <strong>Id Vendor</strong>: {{ $data->IdVendor ?? null }}<br>
                <strong>Id Barang</strong>: {{ $data->IdBarang ?? null }}<br>
                <strong>Tanggal</strong>: {{ $data->Tanggal ?? null }}<br>
                <strong>Nama Kepala Divisi</strong>: {{ $data->NamaKadiv ?? null }}<br>
                <strong>Divisi</strong>: {{ $data->Divisi ?? null }}<br>
                <strong>Kategori</strong>: {{ $data->Kategori ?? null }}<br>
            </td>
            <td style="width:40%;">
                <strong>Tanggal 2</strong>: {{ $data->Tanggal2 ?? null }}<br>
                <strong>Nama Kepala Divisi 2</strong>: {{ $data->NamaKadiv2 ?? null }}<br>
                <strong>Divisi 2</strong>: {{ $data->Divisi2 ?? null }}<br>
                <strong>Kategori 2</strong>: {{ $data->Kategori2 ?? null }}<br>
                <strong>Alasan</strong>: <span style="white-space: pre-wrap">{{ $data->Alasan ?? null }}</span><br>
                <strong>Biaya Akhir</strong>:
                {{-- {{ $data->BiayaAkhir !== null ? 'Rp ' . number_format($data->BiayaAkhir, 0, ',', '.') : null }}<br> --}}
                <strong>Vendor Dipilih</strong>: {{ $data->VendorDipilih ?? null }}<br>
                <strong>Harga = Diskon = PPN</strong>: {{ $data->HargaDiskonPpn ?? null }}<br>
                <strong>Total</strong>:
                {{ $data->Total !== null ? 'Rp ' . number_format($data->Total, 0, ',', '.') : null }}<br>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Sudah RKAP</strong>:
                @if (isset($data->SudahRkap))
                    {{ $data->SudahRkap === 1 ? 'Ya' : ($data->SudahRkap === 0 ? 'Tidak' : null) }}
                @else
                    {{ null }}
                @endif
                <br>
                <strong>Sisa Budget RKAP</strong>:
                {{ $data->SisaBudget !== null ? 'Rp ' . number_format($data->SisaBudget, 0, ',', '.') : null }}<br>
                <strong>Sudah RKAP 2</strong>:
                @if (isset($data->SudahRkap2))
                    {{ $data->SudahRkap2 === 1 ? 'Ya' : ($data->SudahRkap2 === 0 ? 'Tidak' : null) }}
                @else
                    {{ null }}
                @endif
                <br>
                <strong>Sisa Budget RKAP 2</strong>:
                {{ $data->SisaBudget2 !== null ? 'Rp ' . number_format($data->SisaBudget2, 0, ',', '.') : null }}<br>
                <strong>Diajukan Oleh</strong>: {{ $data->DiajukanOleh ?? null }}<br>
                <strong>Diajukan Pada</strong>: {{ $data->DiajukanPada ?? null }}<br>
            </td>
        </tr>
    </table>

    <div class="section-title">Detail Barang/Jasa yang Diusulkan</div>
    <table>
        <thead>
            <tr>
                <th style="width:4%;">No</th>
                <th>Nama Barang / Jasa</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Diskon</th>
                <th>PPN</th>
                <th>Total</th>
                <th>User Input</th>
                <th>User Update</th>
            </tr>
        </thead>
        <tbody>
            @php $no=1; @endphp
            @if (isset($data->getFuiDetail) && count($data->getFuiDetail))
                @foreach ($data->getFuiDetail as $detail)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $detail->NamaBarang ?? null }}</td>
                        <td>{{ $detail->Jumlah ?? null }}</td>
                        <td>
                            {{ isset($detail->Harga) ? 'Rp ' . number_format($detail->Harga, 0, ',', '.') : null }}
                        </td>
                        <td>
                            {{ isset($detail->Diskon) ? $detail->Diskon . '%' : null }}
                        </td>
                        <td>
                            {{ isset($detail->Ppn) ? $detail->Ppn . '%' : null }}
                        </td>
                        <td>
                            {{ isset($detail->Total) ? 'Rp ' . number_format($detail->Total, 0, ',', '.') : null }}
                        </td>
                        <td>{{ $detail->UserCreate ?? null }}</td>
                        <td>{{ $detail->UserUpdate ?? null }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="text-center">Tidak ada detail usulan.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer-note">
        *) Cetak Form bila perlu<br>
        **) Kategori Barang / Jasa : Media dan Umum Rp. 3.000.000 (belum validasi)
    </div>


</body>

</html>
