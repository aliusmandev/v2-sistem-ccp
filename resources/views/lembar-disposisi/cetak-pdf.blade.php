<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Lembar Disposisi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            color: #e31e24;
            margin: 0;
        }

        .info-section {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .approval-box {
            height: 80px;
            vertical-align: top;
        }

        .sign-area {
            min-height: 60px;
        }

        .footer-note {
            font-size: 9px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>RS AWAL BROS</h2>
        <h3>LEMBARAN DISPOSISI PENGADAAN BARANG / JASA</h3>
    </div>

    <div class="info-section">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 40%;">NAMA BARANG / JASA YANG AKAN DIBELI</td>
                <td style="border: none;">: {{ $data['namaBarang'] }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">HARGA</td>
                <td style="border: none;">: {{ number_format($data['harga'], 0, ',', '.') }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">RENCANA VENDOR</td>
                <td style="border: none;">: {{ $data['rencanaVendor'] }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">TUJUAN PENGGUNA/RUANGAN</td>
                <td style="border: none;">: {{ $data['tujuanPenempatan'] }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">FORM PERMINTAAN DARI USER</td>
                <td style="border: none;">: {{ $data['formPermintaan'] == 'Y' ? 'ada' : 'Tidak' }}</td>
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
                @if ($data['kadivYanmed'])
                    {{ $data['kadivYanmed']->Justifikasi }}
                @endif
            </td>
            <td class="approval-box">
                <div class="sign-area">
                    @if ($data['kadivYanmed'] && $data['kadivYanmed']->StatusApprove == 'Y')
                        <p>{{ $data['kadivYanmed']->Nama }}</p>
                        <p>{{ date('d/m/Y H:i', strtotime($data['kadivYanmed']->ApprovePada)) }}</p>
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
                @if ($data['kadivJangmed'])
                    {{ $data['kadivJangmed']->Justifikasi }}
                @endif
            </td>
            <td class="approval-box">
                <div class="sign-area">
                    @if ($data['kadivJangmed'] && $data['kadivJangmed']->StatusApprove == 'Y')
                        <p>{{ $data['kadivJangmed']->Nama }}</p>
                        <p>{{ date('d/m/Y H:i', strtotime($data['kadivJangmed']->ApprovePada)) }}</p>
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
            <td class="approval-box"></td>
            <td class="approval-box">
                <div class="sign-area">
                    @if ($data['direktur'] && $data['direktur']->StatusApprove == 'Y')
                        <p>{{ $data['direktur']->Nama }}</p>
                        <p>{{ date('d/m/Y H:i', strtotime($data['direktur']->ApprovePada)) }}</p>
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
            <td class="approval-box"></td>
            <td class="approval-box">
                <div class="sign-area">
                    @if ($data['ghProcurement'] && $data['ghProcurement']->StatusApprove == 'Y')
                        <p>{{ $data['ghProcurement']->Nama }}, {{ $data['ghProcurement']->Jabatan }}</p>
                        <p>{{ date('d/m/Y H:i', strtotime($data['ghProcurement']->ApprovePada)) }}</p>
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
            <td class="approval-box"></td>
            <td class="approval-box">
                <div class="sign-area">
                    @if ($data['direkturRsabGroup'] && $data['direkturRsabGroup']->StatusApprove == 'Y')
                        <p>{{ $data['direkturRsabGroup']->Nama }}</p>
                        <p>{{ date('d/m/Y H:i', strtotime($data['direkturRsabGroup']->ApprovePada)) }}</p>
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
            <td class="approval-box"></td>
            <td class="approval-box">
                <div class="sign-area">
                    @if ($data['ceoRsabGroup'] && $data['ceoRsabGroup']->StatusApprove == 'Y')
                        <p>{{ $data['ceoRsabGroup']->Nama }}</p>
                        <p>{{ date('d/m/Y H:i', strtotime($data['ceoRsabGroup']->ApprovePada)) }}</p>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        *) Coret jika tidak ada halaman lain
    </div>
</body>

</html>
