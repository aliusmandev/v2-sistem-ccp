<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekomendasi CCP - Cetak Per Barang</title>
    <!-- ==== Rekomendasi Section CSS ==== -->
    <style>
        .rekomendasi body,
        .rekomendasi {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #222;
        }

        .rekomendasi .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 30px;
            text-decoration: underline;
        }

        .rekomendasi .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .rekomendasi .main-table th,
        .rekomendasi .main-table td {
            border: 1px solid #444;
            padding: 6px 8px;
        }

        .rekomendasi .main-table th {
            background: #eee;
            font-weight: bold;
            text-align: left;
        }

        .rekomendasi .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 60px;
            font-size: 13px;
        }

        .rekomendasi .signature-table td {
            border: 1px solid #444;
            padding: 16px 8px 6px 8px;
            text-align: center;
            vertical-align: middle;
            height: 100px;
        }

        .rekomendasi .signature-label {
            font-weight: bold;
            margin-bottom: 2px;
            display: block;
        }

        .rekomendasi .signature-sub {
            font-size: 13px;
            font-weight: 600;
        }
    </style>
    <!-- ==== Disposisi Section CSS ==== -->
    <style>
        .dispo {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .dispo .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .dispo .header h2 {
            color: #e31e24;
            margin: 0;
        }

        .dispo .header h3 {
            margin: 2px 0 0 0;
            font-size: 16px;
            font-weight: 500;
        }

        .dispo .info-section {
            margin-bottom: 15px;
        }

        .dispo table {
            width: 100%;
            border-collapse: collapse;
        }

        .dispo table,
        .dispo th,
        .dispo td {
            border: 1px solid #000;
        }

        .dispo th,
        .dispo td {
            padding: 8px;
            text-align: left;
        }

        .dispo .approval-box {
            height: 80px;
            vertical-align: top;
        }

        .dispo .sign-area {
            min-height: 60px;
        }

        .dispo .footer-note {
            font-size: 9px;
            margin-top: 5px;
        }
    </style>
    <!-- ==== FUI Section CSS ==== -->
    <style>
        .FUI {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin: 15px 18px;
            background: #fff;
        }

        .FUI .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .FUI .logo {
            color: #DC143C;
            font-size: 24pt;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .FUI .title-section {
            text-align: center;
        }

        .FUI .form-title {
            font-size: 14pt;
            font-weight: bold;
        }

        .FUI .form-subtitle {
            margin: 2px 0;
            font-size: 11pt;
            font-style: italic;
        }

        .FUI table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }

        .FUI th,
        .FUI td {
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: left;
        }

        .FUI th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9pt;
        }

        .FUI .note {
            margin: 10px 0;
            font-size: 9pt;
        }

        .FUI .checkbox-group {
            margin-top: 4px;
            font-size: 10pt;
        }

        .FUI .intro-text {
            margin: 18px 0 6px 0;
        }

        .FUI .budget-section {
            margin-top: 8px;
            font-size: 10pt;
        }

        .FUI .footer-note {
            font-size: 9px;
            margin-top: 5px;
        }
    </style>
    <!-- ==== Permintaan Section CSS ==== -->
    <style>
        .permintaan {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            background: #fff;
            margin: 15px 18px;
        }

        .permintaan .header {
            text-align: center;
            border-bottom: 1.5px solid #000;
            margin-bottom: 20px;
        }

        .permintaan .title-section h2 {
            margin: 0 0 2px 0;
            font-size: 16pt;
            font-weight: bold;
        }

        .permintaan .title-section p {
            margin: 2px 0;
            font-size: 10pt;
            font-style: italic;
        }

        .permintaan .form-info {
            margin: 20px 0;
        }

        .permintaan .form-info table {
            border: none;
            font-size: 11pt;
        }

        .permintaan .form-info td {
            padding: 4px 0;
            border: none;
        }

        .permintaan .form-info td:first-child {
            width: 110px;
            font-weight: normal;
        }

        .permintaan .form-info input {
            border: none;
            border-bottom: 1px solid #000;
            width: 260px;
            padding: 2px;
            background: transparent;
        }

        .permintaan .main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 10pt;
        }

        .permintaan .main-table th,
        .permintaan .main-table td {
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
        }

        .permintaan .main-table th {
            background-color: #f9f9f9;
            font-weight: bold;
            font-size: 9pt;
        }

        .permintaan .main-table td:nth-child(2) {
            text-align: left;
        }

        .permintaan .signature-section {
            margin-top: 20px;
        }

        .permintaan .notes {
            margin: 10px 0;
            font-size: 9pt;
        }
    </style>
    <!-- ==== Shared/Global CSS (optional print or fallback) ==== -->
    <style>
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Rekomendasi Section -->
    <div class="rekomendasi">
        <div class="title">
            REKOMENDASI PEMBELIAN BARANG
        </div>

        <table class="main-table" style="margin-bottom: 45px;">
            <tr>
                <td style="width: 100px;">RS yang Meminta</td>
                <td colspan="{{ max(1, $rekomendasi->getRekomedasiDetail->count()) }}">
                    <strong>
                        {{ $rekomendasi->getRekomedasiDetail[0]->getPerusahaan->Nama ?? '-' }}
                    </strong>
                </td>
            </tr>
            <tr>
                <td>Nama Vendor</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td>
                        {{ $item->getNamaVendor->Nama ?? '-' }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Nama Barang</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td>
                        {{ $item->getBarang->Nama ?? '-' }} / {{ $item->getBarang->getMerk->Nama ?? '-' }} /
                        {{ $item->getBarang->Tipe ?? '-' }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Harga Awal</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td style="text-align: right;">
                        @if ($item->HargaAwal)
                            Rp
                            {{ is_numeric($item->HargaAwal) ? number_format($item->HargaAwal, 0, ',', '.') : $item->HargaAwal }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Harga Nego</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td style="text-align: right;">
                        @if ($item->HargaNego)
                            Rp
                            {{ is_numeric($item->HargaNego) ? number_format($item->HargaNego, 0, ',', '.') : $item->HargaNego }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Spesifikasi</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td>
                        {{ $item->Spesifikasi ?? '-' }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Negara Produksi</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td>
                        {{ $item->getNegara->Nama ?? '-' }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Garansi</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td>
                        {{ $item->Garansi ?? '-' }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Teknisi</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td>
                        {{ $item->Teknisi ?? '-' }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Bmhp</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td>
                        {{ $item->Bmhp ?? '-' }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>SparePart</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td>
                        {{ $item->SparePart ?? '-' }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>BackupUnit</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td>
                        {{ $item->BackupUnit ?? '-' }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Top</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td>
                        {{ $item->Top ?? '-' }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Rekomendasi</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td>
                        {{ $item->Rekomendasi ?? '-' }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Keterangan</td>
                @foreach ($rekomendasi->getRekomedasiDetail as $item)
                    <td>
                        {{ $item->Keterangan ?? '-' }}
                    </td>
                @endforeach
            </tr>
        </table>

        <div style="margin-top: 40px;">
            <div style="text-align: right; margin-bottom: 10px;">
                <span>
                    Pekanbaru,
                    {{ isset($rekomendasi->getRekomedasiDetail[0]->created_at)
                        ? \Carbon\Carbon::parse($rekomendasi->getRekomedasiDetail[0]->created_at)->format('d-m-Y')
                        : date('d-m-Y') }}
                </span>
            </div>

            <table class="signature-table" style="font-family: Arial, sans-serif; font-weight: normal;">
                <tr>
                    <td>
                        <span class="signature-label" style="font-family: Arial, sans-serif; font-weight: normal;">Yang
                            Menegosiasi</span>
                        <div style="height:40px;"></div>
                        <span style="display: block;">
                            {{ $rekomendasi->getUserNego->name ?? '-' }}
                        </span>
                        <hr style="width: 70%; margin: 12px auto 0 auto; border: 0; border-top: 1.3px solid #aaa;">
                    </td>
                    <td>
                        <span class="signature-label"
                            style="font-family: Arial, sans-serif; font-weight: normal;">Disetujui</span>
                        <span class="signature-sub"
                            style="font-family: Arial, sans-serif; font-weight: normal;">Procurement
                            Group</span>
                        <div style="height:28px;"></div>
                        <span style="display: block;">
                            {{ $rekomendasi->getDisetujuiOleh->name ?? '-' }}
                        </span>
                        <hr style="width: 70%; margin: 12px auto 0 auto; border: 0; border-top: 1.3px solid #aaa;">
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div style="page-break-before: always;"></div>
    <!-- Disposisi Section -->
    <div class="dispo">
        <div class="header">
            <h2>RS AWAL BROS</h2>
            <h3>LEMBARAN DISPOSISI PENGADAAN BARANG / JASA</h3>
        </div>

        <div class="info-section">
            <table style="border: none;">
                <tr style="border: none;">
                    <td style="border: none; width: 40%;">NAMA BARANG / JASA YANG AKAN DIBELI</td>
                    <td style="border: none;">: {{ $data['namaBarang'] ?? '-' }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;">HARGA</td>
                    <td style="border: none;">:
                        {{ isset($data['harga']) ? number_format($data['harga'], 0, ',', '.') : '-' }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;">RENCANA VENDOR</td>
                    <td style="border: none;">: {{ $data['rencanaVendor'] ?? '-' }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;">TUJUAN PENGGUNA/RUANGAN</td>
                    <td style="border: none;">: {{ $data['tujuanPenempatan'] ?? '-' }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;">FORM PERMINTAAN DARI USER</td>
                    <td style="border: none;">
                        :
                        @if (isset($data['formPermintaan']))
                            {{ $data['formPermintaan'] == 'Y' ? 'ada' : 'Tidak' }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <table>
            <tr>
                <th style="width: 70%;">JUSTIFIKASI PEMBELIAN BARANG/JASA</th>
                <th style="width: 30%;">KADIV YANMED RS</th>
            </tr>
            <tr>
                <td class="approval-box">
                    @if (!empty($data['approval'][0]) && isset($data['approval'][0]->Catatan))
                        {{ $data['approval'][0]->Catatan }}
                    @endif
                </td>
                <td class="approval-box">
                    <div class="sign-area">
                        @if (!empty($data['approval'][0]) && isset($data['approval'][0]->Nama))
                            <p>{{ $data['approval'][0]->Nama }}</p>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <th style="width: 70%;">JUSTIFIKASI PEMILIHAN BARANG/JASA</th>
                <th style="width: 30%;">KADIV JANGMED RS</th>
            </tr>
            <tr>
                <td class="approval-box">
                    @if (!empty($data['approval'][1]) && isset($data['approval'][1]->Catatan))
                        {{ $data['approval'][1]->Catatan }}
                    @endif
                </td>
                <td class="approval-box">
                    <div class="sign-area">
                        @if (!empty($data['approval'][1]) && isset($data['approval'][1]->Nama))
                            <p>{{ $data['approval'][1]->Nama }}</p>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <th style="width: 70%;">PERSETUJUAN</th>
                <th style="width: 30%;">DIREKTUR RS</th>
            </tr>
            <tr>
                <td class="approval-box">
                    @if (!empty($data['approval'][2]) && isset($data['approval'][2]->Catatan))
                        {{ $data['approval'][2]->Catatan }}
                    @endif
                </td>
                <td class="approval-box">
                    <div class="sign-area">
                        @if (!empty($data['approval'][2]) && isset($data['approval'][2]->Nama))
                            <p>{{ $data['approval'][2]->Nama }}</p>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <th style="width: 70%;">PERSETUJUAN</th>
                <th style="width: 30%;">GH PROCUREMENT</th>
            </tr>
            <tr>
                <td class="approval-box">
                    @if (!empty($data['approval'][3]) && isset($data['approval'][3]->Catatan))
                        {{ $data['approval'][3]->Catatan }}
                    @endif
                </td>
                <td class="approval-box">
                    <div class="sign-area">
                        @if (!empty($data['approval'][3]) && isset($data['approval'][3]->Nama))
                            <p>{{ $data['approval'][3]->Nama }}</p>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <th style="width: 70%;">PERSETUJUAN</th>
                <th style="width: 30%;">DIREKTUR RSAB GROUP</th>
            </tr>
            <tr>
                <td class="approval-box">
                    @if (!empty($data['approval'][4]) && isset($data['approval'][4]->Catatan))
                        {{ $data['approval'][4]->Catatan }}
                    @endif
                </td>
                <td class="approval-box">
                    <div class="sign-area">
                        @if (!empty($data['approval'][4]) && isset($data['approval'][4]->Nama))
                            <p>{{ $data['approval'][4]->Nama }}</p>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <th style="width: 70%;">PERSETUJUAN</th>
                <th style="width: 30%;">CEO RSAB GROUP</th>
            </tr>
            <tr>
                <td class="approval-box">
                    @if (!empty($data['approval'][5]) && isset($data['approval'][5]->Catatan))
                        {{ $data['approval'][5]->Catatan }}
                    @endif
                </td>
                <td class="approval-box">
                    <div class="sign-area">
                        @if (!empty($data['approval'][5]) && isset($data['approval'][5]->Nama))
                            <p>{{ $data['approval'][5]->Nama }}</p>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer-note">
            *) Coret jika tidak ada halaman lain
        </div>
    </div>
    <div style="page-break-before: always;"></div>
    <!-- FUI Section -->
    <div class="FUI">
        <div class="page">
            <div class="header">
                <div class="header-content">
                    <div class="logo">RS AWAL BROS</div>
                    <div class="title-section">
                        <div class="form-title">FORMULIR USULAN INVESTASI</div>
                        <div class="form-subtitle">{{ $usulan->getNamaForm->Nama }}</div>
                    </div>
                </div>
            </div>

            <table
                style="width: 100%; border-collapse: collapse; font-size:10pt; margin-bottom: 15px; border:1px solid #000;">
                <tr>
                    <td colspan="6" style="border:1px solid #000;padding:4px; background:#f3f3f3;">
                        <b>Diisi lengkap oleh pemohon, setelah ditandatangani Atasan dan Direktur, kemudian diserahkan
                            ke
                            Bagian Pembelian Barang / Jasa Medik dan Umum :</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border:1px solid #000; padding:4px; background:#f9f9f9;">
                        <b>Diisi oleh Pemohon (Departemen terkait)</b>
                    </td>
                    <td colspan="3" style="border:1px solid #000; padding:4px; background:#f9f9f9;">
                        <b>Diisi oleh Bagian Pembelian</b>
                    </td>
                </tr>
                <tr>
                    <td style="width:13%; border:1px solid #000; padding:4px;">Tanggal</td>
                    <td style="width:2%; border:1px solid #000; padding:4px;">:</td>
                    <td style="width:18%; border:1px solid #000; padding:4px;">
                        {{ \Carbon\Carbon::parse($usulan->Tanggal)->translatedFormat('d F Y') }}
                    </td>
                    <td style="width:13%; border:1px solid #000; padding:4px;">Tanggal</td>
                    <td style="width:2%; border:1px solid #000; padding:4px;">:</td>
                    <td style="width:18%; border:1px solid #000; padding:4px;">
                        {{ \Carbon\Carbon::parse($usulan->Tanggal2)->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td style="border:1px solid #000; padding:4px;">Nama Kepala Divisi</td>
                    <td style="border:1px solid #000; padding:4px;">:</td>
                    <td style="border:1px solid #000; padding:4px;">{{ $usulan->getKadiv->name }}</td>
                    <td style="border:1px solid #000; padding:4px;">Nama Kepala Divisi</td>
                    <td style="border:1px solid #000; padding:4px;">:</td>
                    <td style="border:1px solid #000; padding:4px;">{{ $usulan->getKadiv2->name }}</td>
                </tr>
                <tr>
                    <td style="border:1px solid #000; padding:4px;">Divisi</td>
                    <td style="border:1px solid #000; padding:4px;">:</td>
                    <td style="border:1px solid #000; padding:4px;">{{ $usulan->getDepartemen->Nama }}</td>
                    <td style="border:1px solid #000; padding:4px;">Divisi</td>
                    <td style="border:1px solid #000; padding:4px;">:</td>
                    <td style="border:1px solid #000; padding:4px;">{{ $usulan->getDepartemen2->Nama }}</td>
                </tr>
                <tr>
                    <td style="border:1px solid #000; padding:4px;">Kategori</td>
                    <td style="border:1px solid #000; padding:4px;">:</td>
                    <td style="border:1px solid #000; padding:4px;">{{ $usulan->Kategori }}</td>
                    <td style="border:1px solid #000; padding:4px;">Kategori</td>
                    <td style="border:1px solid #000; padding:4px;">:</td>
                    <td style="border:1px solid #000; padding:4px;">{{ $usulan->Kategori }}</td>
                </tr>
                <tr>
                    <td colspan="6" style="border:1px solid #000;padding:4px; font-size:9pt;">
                        <i>* Pilih salah satu</i>
                    </td>
                </tr>
            </table>

            <p class="intro-text">
                Dengan ini kami ajukan permohonan untuk pengadaan barang/jasa dengan alasan sebagai berikut:
            </p>
            <p>{{ $usulan->Alasan }}</p>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 40%;">Nama Barang / Merek</th>
                        <th style="width: 8%;">Jumlah</th>
                        <th style="width: 15%;">Harga</th>
                        <th style="width: 10%;">Diskon</th>
                        <th style="width: 10%;">PPN</th>
                        <th style="width: 12%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usulan->getFuiDetail as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                {{ $item->getNamaBarang->Nama ?? '-' }} /
                                {{ $item->getNamaBarang->getMerk->Nama ?? '-' }}
                            </td>
                            <td class="text-center">{{ $item->Jumlah ?? '-' }}</td>
                            <td class="text-right">
                                Rp {{ number_format($item->Harga ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                {{ $item->Diskon !== null ? 'Rp ' . number_format($item->Diskon, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-center">
                                {{ $item->Ppn !== null ? $item->Ppn : '-' }}
                            </td>
                            <td class="text-right">
                                Rp {{ number_format($item->Total ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="note">
                <strong>Catatan:</strong><br>
                1. Lampiran quotation diatas Rp 100.000.000<br>
                2. Untuk nominal di atas Rp 100.000.000 lampirkan MTA/KFA<br>
                3. Penawaran harga yang telah disetujui, dan pembanding antara vendor. Jika tidak ada vendor pembanding,
                mohon jelaskan alasannya
            </div>

            <table style="margin-top: 15px;">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Biaya / Harga Akhir</th>
                        <th style="width: 30%;">Supplier yang dipilih</th>
                        <th style="width: 20%;">Harga + Diskon + PPN</th>
                        <th style="width: 20%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td class="text-right">
                            Rp {{ number_format($VendorAcc->Harga ?? 0, 0, ',', '.') }}
                        </td>
                        <td>{{ $VendorAcc->getVendorDipilih->Nama }}</td>
                        <td class="text-right">
                            Rp {{ number_format($VendorAcc->Harga ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="text-right">
                            Rp {{ number_format($VendorAcc->Total ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="budget-section">
                <div class="checkbox-group">
                    <span>Sudah masuk RKAP dari departemen ybs:</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    @if ($usulan->SudahRkap == 'Y')
                        <span>Ya, Sudah</span>
                    @elseif ($usulan->SudahRkap == 'N')
                        <span>Tidak, Belum</span>
                    @else
                        <span>-</span>
                    @endif
                </div>

                <p style="margin: 8px 0;">
                    Sisa Budget dari RKAP untuk tahun ini yang masih dapat dipergunakan Rp.
                    <u>{{ number_format($usulan->SisaBudget ?? 0, 0, ',', '.') }}</u> [diisi oleh departemen terkait]
                </p>

                <div style="margin-top: 12px;">
                    <strong>Verifikasi keuangan:</strong>
                </div>
                <div class="checkbox-group">
                    <span>Sudah masuk RKAP dari departemen ybs:</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    @if ($usulan->SudahRkap2 == 'Y')
                        <span>Ya, Sudah</span>
                    @elseif ($usulan->SudahRkap2 == 'N')
                        <span>Tidak, Belum</span>
                    @else
                        <span>-</span>
                    @endif
                </div>

                <p style="margin: 8px 0;">
                    Sisa Budget dari RKAP untuk tahun ini yang masih dapat dipergunakan Rp.
                    <u>{{ number_format($usulan->SisaBudget2 ?? 0, 0, ',', '.') }}</u>
            </div>

            <table style="width:100%; margin: 0 auto; border:none;">
                <colgroup>
                    @if (!empty($approval3))
                        @foreach ($approval3 as $item)
                            <col style="width: {{ 100 / count($approval3) }}%;">
                        @endforeach
                    @endif
                </colgroup>
                <tbody>
                    <tr>
                        @foreach ($approval3 as $item)
                            <td style="text-align:center; font-weight:600; border:none;">
                                {{ $item->getJabatan->Nama ?? '-' }}<br>
                                {{ $item->getDepartemen->Nama ?? '' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($approval3 as $item)
                            <td class="text-center align-bottom" style="height: 20px; border:none;">
                                {{-- Tempat kosong untuk tanda tangan basah di cetak PDF --}}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($approval3 as $item)
                            <td style="height: 70px; text-align:center; border:none;">
                                @if (!empty($item->Ttd))
                                    <img src="{{ public_path('storage/upload/tandatangan/' . $item->Ttd) }}"
                                        alt="TTD" style="max-width:110px; max-height:60px;">
                                @else
                                    <!-- Jika tidak ada tanda tangan digital, biarkan kosong untuk tanda tangan manual -->
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($approval3 as $item)
                            <td class="text-center" style="padding-bottom:0; border:none;">
                                <hr style="width: 70%; margin:0 auto 3px auto;border-top:2px solid #000;">
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($approval3 as $item)
                            <td class="text-center align-top" style="border:none;">
                                <span style="font-weight:600; display: block; text-align: center;">
                                    {{ $item->Nama ?? '-' }}
                                </span>
                                <div style="display: block; text-align: center;">
                                    <small style="display: inline-block;">{{ $item->Status ?? '-' }}</small>

                                </div>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>

            <div class="footer-note">
                <p>*) Coret yang tidak perlu</p>
                <p>**) Kategori Barang/Jasa/Mesin dan Umum Rp 1.300.000 jatam</p>
                <p style="text-align: right;">RSAB-AY/Langmed-SMM016/LDP.LM/Rev.00</p>
            </div>
        </div>
    </div>
    <div style="page-break-before: always;"></div>
    <!-- Permintaan Section -->
    <div class="permintaan">
        <div class="header">
            <div class="title-section">
                <h2>PERMINTAAN PEMBELIAN</h2>
                <p>PURCHASE REQUESTION</p>
            </div>
        </div>

        <div class="form-info">
            <table>
                <tr>
                    <td>Unit</td>
                    <td>:
                        <input type="text" value="{{ $permintaan->getDepartemen->Nama ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:
                        <input type="text"
                            value="{{ !empty($permintaan->Tanggal) ? \Carbon\Carbon::parse($permintaan->Tanggal)->format('d-m-Y') : '' }}">
                    </td>
                </tr>
                <tr>
                    <td>No.</td>
                    <td>:
                        <input type="text" value="{{ $permintaan->NomorPermintaan ?? '' }}">
                    </td>
                </tr>
            </table>
        </div>

        <table class="main-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Barang</th>
                    <th style="width: 10%;">Jumlah</th>
                    <th style="width: 10%;">Satuan</th>
                    <th style="width: 20%;">Nama dan Paraf User**</th>
                    <th style="width: 15%;">Rencana Pemanfaatan</th>
                    <th style="width: 15%;">Keterangan Pembelian</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $maxRows = 10;
                    $detailCount = isset($permintaan->getDetail) ? $permintaan->getDetail->count() : 0;
                @endphp

                @foreach ($permintaan->getDetail as $i => $detail)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            {{ $detail->getBarang->Nama ?? '' }}
                            @if (!empty($detail->getBarang->getMerk->Nama))
                                <br><span style="font-size:8pt; font-style:italic; color:#444;">Merk:
                                    {{ $detail->getBarang->getMerk->Nama }}</span>
                            @endif
                        </td>
                        <td>
                            {{ is_numeric($detail->Jumlah) ? number_format($detail->Jumlah, 0, ',', '.') : $detail->Jumlah }}
                        </td>
                        <td>
                            {{ $detail->getBarang->getSatuan->NamaSatuan }}
                        </td>
                        <td>
                            @if (isset($permintaan->getDiajukanOleh->name))
                                {{ $permintaan->getDiajukanOleh->name }}
                            @endif
                        </td>
                        <td>
                            {{ $detail->RencanaPenempatan ?? '' }}
                        </td>
                        <td>
                            {{ $detail->Keterangan ?? '' }}
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        <div class="signature-section">
            <table style="width:100%; margin: 0 auto; border:none;">
                <colgroup>
                    @if (!empty($approval3))
                        @foreach ($approval3 as $item)
                            <col style="width: {{ 100 / count($approval3) }}%;">
                        @endforeach
                    @endif
                </colgroup>
                <tbody>
                    <tr>
                        @foreach ($approval3 as $item)
                            <td style="text-align:center; font-weight:600; border:none;">
                                {{ $item->getJabatan->Nama ?? '-' }}<br>
                                {{ $item->getDepartemen->Nama ?? '' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($approval3 as $item)
                            <td class="text-center align-bottom" style="height: 20px; border:none;">
                                {{-- Tempat kosong untuk tanda tangan basah di cetak PDF --}}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($approval3 as $item)
                            <td style="height: 70px; text-align:center; border:none;">
                                @if (!empty($item->Ttd))
                                    <img src="{{ public_path('storage/upload/tanpermintaanngan/' . $item->Ttd) }}"
                                        alt="TTD" style="max-width:110px; max-height:60px;">
                                @else
                                    <!-- Jika tidak ada tanda tangan digital, biarkan kosong untuk tanda tangan manual -->
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($approval3 as $item)
                            <td class="text-center" style="padding-bottom:0; border:none;">
                                <hr style="width: 70%; margin:0 auto 3px auto;border-top:2px solid #000;">
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($approval3 as $item)
                            <td class="text-center align-top" style="border:none;">
                                <span style="font-weight:600; display: block; text-align: center;">
                                    {{ $item->Nama ?? '-' }}
                                </span>
                                <div style="display: block; text-align: center;">
                                    <small style="display: inline-block;">{{ $item->Status ?? '-' }}</small>

                                </div>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
