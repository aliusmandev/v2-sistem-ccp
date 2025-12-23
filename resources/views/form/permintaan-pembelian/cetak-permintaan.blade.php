<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Pembelian - RS Awal Bros</title>
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

        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin: 20px;
            background: white;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .logo {
            color: #DC143C;
            font-size: 24pt;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .title-section {
            text-align: center;
        }

        .title-section h2 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
        }

        .title-section p {
            margin: 2px 0;
            font-size: 10pt;
            font-style: italic;
        }

        .form-info {
            margin: 20px 0;
        }

        .form-info table {
            border: none;
            font-size: 11pt;
        }

        .form-info td {
            padding: 4px 0;
            border: none;
        }

        .form-info td:first-child {
            width: 120px;
            font-weight: normal;
        }

        .form-info input {
            border: none;
            border-bottom: 1px solid #000;
            width: 300px;
            padding: 2px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 10pt;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
        }

        .main-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9pt;
        }

        .main-table td:nth-child(2) {
            text-align: left;
        }

        .notes {
            margin: 10px 0;
            font-size: 9pt;
        }

        .signature-section {
            margin-top: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            font-size: 10pt;
        }

        .signature-box {
            text-align: center;
        }

        .signature-box p {
            margin: 5px 0;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 180px;
            margin: 50px auto 5px auto;
        }

        .signature-title {
            font-weight: normal;
            font-size: 9pt;
        }

        .footer-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 100px;
            font-size: 10pt;
        }

        .footer-box {
            text-align: center;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #DC143C;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14pt;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .print-button:hover {
            background-color: #B71C1C;
        }
    </style>
</head>

<body>

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
                    <input type="text" value="{{ $data->getDepartemen->Nama ?? '' }}">
                </td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:
                    <input type="text"
                        value="{{ !empty($data->Tanggal) ? \Carbon\Carbon::parse($data->Tanggal)->format('d-m-Y') : '' }}">
                </td>
            </tr>
            <tr>
                <td>No.</td>
                <td>:
                    <input type="text" value="{{ $data->NomorPermintaan ?? '' }}">
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
                $detailCount = isset($data->getDetail) ? $data->getDetail->count() : 0;
            @endphp

            @foreach ($data->getDetail as $i => $detail)
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
                        @if (isset($data->getDiajukanOleh->name))
                            {{ $data->getDiajukanOleh->name }}
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

    {{-- <div class="notes">
        <p>*Diisi oleh Logistik/SMI</p>
        <p>**Bila user adalah dokter, maka paraf dilakukan oleh KSM</p>
    </div> --}}

    <div class="signature-section">
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
    </div>

</body>

</html>
