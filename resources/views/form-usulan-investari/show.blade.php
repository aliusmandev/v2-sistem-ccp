<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Formulir Usulan Investasi - RS Awal Bros</title>
    <style>
        /* ... (CSS retained, unchanged) ... */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
        }

        .page {
            width: 100%;
            padding: 10px;
        }

        .header {
            width: 100%;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #000;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .logo {
            display: table-cell;
            width: 50%;
            font-size: 20pt;
            font-weight: bold;
            color: #000;
            vertical-align: middle;
        }

        .title-section {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: middle;
        }

        .form-title {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .form-subtitle {
            font-size: 9pt;
            color: #333;
        }

        .info-section {
            margin: 15px 0;
            padding: 8px;
            background: #f5f5f5;
            border: 1px solid #333;
        }

        .info-row {
            margin-bottom: 6px;
            font-size: 10pt;
        }

        .info-label {
            display: inline-block;
            width: 35%;
            font-weight: bold;
        }

        .info-value {
            display: inline-block;
            width: 63%;
            border-bottom: 1px solid #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9pt;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th {
            background: #000;
            color: #fff;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
        }

        td {
            padding: 5px 4px;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .note {
            font-size: 8pt;
            font-style: italic;
            margin: 10px 0;
            padding: 6px;
            background: #f0f0f0;
            border-left: 3px solid #000;
        }

        .note strong {
            font-weight: bold;
        }

        .budget-section {
            margin: 12px 0;
            padding: 8px;
            border: 1px solid #333;
            font-size: 9pt;
        }

        .checkbox-group {
            margin: 8px 0;
        }

        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 5px;
            vertical-align: middle;
            text-align: center;
            line-height: 12px;
            font-size: 10pt;
        }

        .signature-section {
            margin-top: 30px;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            border: none;
        }

        .signature-table td {
            width: 33.33%;
            text-align: center;
            padding: 5px;
            border: none;
            vertical-align: top;
        }

        .signature-title {
            font-size: 8pt;
            margin-bottom: 3px;
        }

        .signature-space {
            height: 50px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 9pt;
            border-top: 1px solid #000;
            padding-top: 3px;
            display: inline-block;
            min-width: 150px;
        }

        .footer-note {
            margin-top: 15px;
            font-size: 7pt;
            color: #333;
            border-top: 1px solid #999;
            padding-top: 8px;
        }

        .footer-note p {
            margin-bottom: 3px;
        }

        .intro-text {
            margin: 12px 0;
            font-weight: bold;
            font-size: 10pt;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

@php
    $usulan = $data ?? null;
@endphp

<body>
    <div class="page">
        <div class="header">
            <div class="header-content">
                <div class="logo"></div>
                <div class="title-section">
                    <div class="form-title">FORMULIR USULAN INVESTASI</div>
                    <div class="form-subtitle">Nominal Permintaan
                        {{ number_format($usulan->BiayaAkhir ?? 0, 0, ',', '.') }} s/d
                        {{ number_format($usulan->Total ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Nama Kepala Divisi</span>
                <span class="info-value">: {{ $usulan->NamaKadiv ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Divisi</span>
                <span class="info-value">: {{ $usulan->Divisi ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kategori</span>
                <span class="info-value">: {{ $usulan->Kategori ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span class="info-value">:
                    {{ \Carbon\Carbon::parse($usulan->Tanggal ?? null)->isoFormat('LL') ?? '-' }}</span>
            </div>
        </div>

        <p class="intro-text">
            Dengan ini kami ajukan permohonan untuk pengadaan barang/jasa dengan alasan sebagai berikut:
        </p>
        <div style="margin-bottom:10px;">
            Alasan: <span style="font-weight:normal">{{ $usulan->Alasan ?? '-' }}</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 40%;">Nama barang/jasa (cantumkan juga nama supplier, contact person, nomor
                        telepon supplier yang dihubungi)</th>
                    <th style="width: 8%;">Jumlah</th>
                    <th style="width: 15%;">Harga</th>
                    <th style="width: 10%;">Diskon (%)</th>
                    <th style="width: 10%;">PPN</th>
                    <th style="width: 12%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($usulan->getFuiDetail) && count($usulan->getFuiDetail))
                    @foreach ($usulan->getFuiDetail as $idx => $item)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>
                                Nama barang/jasa: {{ $item->NamaBarang ?? '-' }}<br>
                                {{-- Spesifikasi bisa diubah/ditambah ke DB --}}
                            </td>
                            <td class="text-center">{{ $item->Jumlah ?? '-' }}</td>
                            <td class="text-right">{{ number_format($item->Harga ?? 0, 0, ',', '.') }}</td>
                            <td class="text-center">
                                {{ ($item->Diskon ?? 0) == 0 ? '-' : number_format($item->Diskon, 2) }}
                            </td>
                            <td class="text-center">
                                @if (isset($item->Ppn) && $item->Ppn > 0)
                                    {{ $item->Ppn }}%
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($item->Total ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="7">Tidak ada detail barang/jasa.</td>
                    </tr>
                @endif
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
                    <td class="text-right">{{ number_format($usulan->BiayaAkhir ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $usulan->VendorDipilih ?? '-' }}</td>
                    <td class="text-right">{{ number_format($usulan->HargaDiskonPpn ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($usulan->Total ?? 0, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="budget-section">
            <div class="checkbox-group">
                <span class="checkbox">
                    @if (($usulan->SudahRkap ?? '') === 'Y')
                        ✓
                    @endif
                </span>
                <span>Sudah masuk RKAP dari departemen ybs:
                    <strong>{{ ($usulan->SudahRkap ?? '') === 'Y' ? 'Ya' : 'Tidak' }}</strong></span>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="checkbox">
                    @if (($usulan->SudahRkap ?? '') !== 'Y' && ($usulan->SudahRkap ?? null))
                        ✓
                    @endif
                </span>
                <span>Tidak</span>
            </div>

            <p style="margin: 8px 0;">
                Sisa Budget dari RKAP untuk tahun ini yang masih dapat dipergunakan Rp.
                <u>&nbsp;&nbsp;&nbsp;{{ number_format($usulan->SisaBudget ?? 0, 0, ',', '.') }}&nbsp;&nbsp;&nbsp;</u>
            </p>

            <div style="margin-top: 12px;">
                <strong>Verifikasi keuangan:</strong>
            </div>
            <div class="checkbox-group">
                <span class="checkbox">
                    @if (($usulan->SudahRkap2 ?? '') === 'Y')
                        ✓
                    @endif
                </span>
                <span>Sudah ada RKAP dari departemen ybs: Ya</span>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="checkbox">
                    @if (($usulan->SudahRkap2 ?? '') !== 'Y' && ($usulan->SudahRkap2 ?? null))
                        ✓
                    @endif
                </span>
                <span>Tidak</span>
            </div>

            <p style="margin: 8px 0;">
                Sisa Budget dari RKAP untuk tahun ini yang masih dapat dipergunakan Rp.
                <u>&nbsp;&nbsp;&nbsp;{{ number_format($usulan->SisaBudget2 ?? 0, 0, ',', '.') }}&nbsp;&nbsp;&nbsp;</u>
            </p>
        </div>

        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>
                        <div class="signature-title">Diajukan,<br>Kepala Divisi</div>
                        <div class="signature-space"></div>
                        <div class="signature-name">({{ $usulan->NamaKadiv ?? '-' }})</div>
                    </td>
                    <td>
                        <div class="signature-title">Mengetahui,<br>Direktur RS Awal Bros A. Yani</div>
                        <div class="signature-space"></div>
                        <div class="signature-name">(....................................)</div>
                    </td>
                    <td>
                        <div class="signature-title">Menyetujui,<br>Direktur RS Awal Bros Group</div>
                        <div class="signature-space"></div>
                        <div class="signature-name">(....................................)</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer-note">
            <p>*) Coret yang tidak perlu</p>
            <p>**) Kategori Barang/Jasa/Mesin dan Umum Rp 1.300.000 jatam</p>
            <p style="text-align: right;">RSAB-AY/Langmed-SMM016/LDP.LM/Rev.00</p>
        </div>
    </div>
</body>

</html>
