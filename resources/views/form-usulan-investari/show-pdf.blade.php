<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Formulir Usulan Investasi - RS Awal Bros</title>
    <style>
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
            margin-left: 1cm;
            margin-right: 1cm;
        }

        .page {
            width: 100%;
            padding: 10px 0;
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

<body>
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
                    <b>Diisi lengkap oleh pemohon, setelah ditandatangani Atasan dan Direktur, kemudian diserahkan ke
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
                            {{ $item->getNamaBarang->Nama ?? '-' }} / {{ $item->getNamaBarang->getMerk->Nama ?? '-' }}
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


        {{-- <h5 class="text-center mb-4"><strong>Persetujuan</strong></h5> --}}
        <!-- Untuk cetak PDF tanda tangan approval -->
        <table style="width:100%; margin: 0 auto; border:none;">
            <colgroup>
                @if (!empty($approval))
                    @foreach ($approval as $item)
                        <col style="width: {{ 100 / count($approval) }}%;">
                    @endforeach
                @endif
            </colgroup>
            <tbody>
                <tr>
                    @foreach ($approval as $item)
                        <td style="text-align:center; font-weight:600; border:none;">
                            {{ $item->getJabatan->Nama ?? '-' }}<br>
                            {{ $item->getDepartemen->Nama ?? '' }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($approval as $item)
                        <td class="text-center align-bottom" style="height: 20px; border:none;">
                            {{-- Tempat kosong untuk tanda tangan basah di cetak PDF --}}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($approval as $item)
                        <td style="height: 70px; text-align:center; border:none;">
                            @if (!empty($item->Ttd))
                                <img src="{{ public_path('storage/upload/tandatangan/' . $item->Ttd) }}" alt="TTD"
                                    style="max-width:110px; max-height:60px;">
                            @else
                                <!-- Jika tidak ada tanda tangan digital, biarkan kosong untuk tanda tangan manual -->
                            @endif
                        </td>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($approval as $item)
                        <td class="text-center" style="padding-bottom:0; border:none;">
                            <hr style="width: 70%; margin:0 auto 3px auto;border-top:2px solid #000;">
                        </td>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($approval as $item)
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
</body>

</html>
