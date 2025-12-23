<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekomendasi CCP - Cetak Per Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #222;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 30px;
            text-decoration: underline;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #444;
            padding: 6px 8px;
        }

        .main-table th {
            background: #eee;
            font-weight: bold;
            text-align: left;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 60px;
            font-size: 13px;
        }

        .signature-table td {
            border: 1px solid #444;
            padding: 16px 8px 6px 8px;
            text-align: center;
            vertical-align: middle;
            height: 100px;
        }

        .signature-label {
            font-weight: bold;
            margin-bottom: 2px;
            display: block;
        }

        .signature-sub {
            font-size: 13px;
            font-weight: 600;
        }
    </style>
</head>

<body>
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
            <td>Populasi</td>
            @foreach ($rekomendasi->getRekomedasiDetail as $item)
                <td>
                    {{ $item->Populasi ?? '-' }}
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
            <td>Time Line Pekerjaan</td>
            @foreach ($rekomendasi->getRekomedasiDetail as $item)
                <td>
                    {{ $item->TimeLinePekerjaan ?? '-' }}
                </td>
            @endforeach
        </tr>
        <tr>
            <td>Jumlah Pekerja</td>
            @foreach ($rekomendasi->getRekomedasiDetail as $item)
                <td>
                    {{ $item->JumlahPekerja ?? '-' }}
                </td>
            @endforeach
        </tr>
        <tr>
            <td>Luasan</td>
            @foreach ($rekomendasi->getRekomedasiDetail as $item)
                <td>
                    {{ $item->Luasan ?? '-' }}
                </td>
            @endforeach
        </tr>
        <tr>
            <td>Review Vendor</td>
            @foreach ($rekomendasi->getRekomedasiDetail as $item)
                <td>
                    {{ $item->ReviewVendor ?? '-' }}
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
                    <span class="signature-sub" style="font-family: Arial, sans-serif; font-weight: normal;">Procurement
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
</body>

</html>
