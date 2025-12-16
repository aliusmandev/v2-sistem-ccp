@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Detail Usulan Investasi</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Usulan Investasi</a></li>
                    <li class="breadcrumb-item active">Detail Usulan Investasi</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Formulir Usulan Investasi</h4>
                </div>
                <div class="card-body">


                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h5 class="fw-bold mb-1">Departemen Peminta</h5>
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Tanggal</label>
                                    <input type="date" class="form-control" value="{{ $usulan->Tanggal ?? '-' }}"
                                        readonly>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Departemen</label>
                                    <input type="text" class="form-control"
                                        value="{{ $usulan->getDepartemen->Nama ?? '-' }}" readonly>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Nama Kepala Divisi</label>
                                    <input type="text" class="form-control" value="{{ $usulan->getKadiv->name ?? '-' }}"
                                        readonly>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Kategori</label>
                                    <input type="text" class="form-control" value="{{ $usulan->Kategori ?? '-' }}"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h5 class="fw-bold mb-1">Departemen Pembelian</h5>
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Tanggal</label>
                                    <input type="date" class="form-control" value="{{ $usulan->Tanggal2 ?? '-' }}"
                                        readonly>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Departemen</label>
                                    <input type="text" class="form-control"
                                        value="{{ $usulan->getDepartemen->Nama ?? '-' }}" readonly>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Nama Kepala Divisi</label>
                                    <input type="text" class="form-control" value="{{ $usulan->getKadiv2->name ?? '-' }}"
                                        readonly>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Kategori</label>
                                    <input type="text" class="form-control" value="{{ $usulan->Kategori2 ?? '-' }}"
                                        readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="Keterangan" class="form-label fw-bold">Dengan ini kami ajukan permohonan untuk
                            pengadaan barang / jasa dengan alasan sebagai berikut :</label>
                        <textarea class="form-control" id="Keterangan" rows="3" readonly>{{ $usulan->Alasan ?? '-' }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">List Item Usulan Investasi</label>
                        <div class="table-responsive">
                            <table class="table align-middle" width="100%">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:5%">No</th>
                                        <th>Nama Barang</th>
                                        <th>Nama Vendor</th>
                                        <th width="10%">Jumlah</th>
                                        <th>Harga</th>
                                        <th>Diskon</th>
                                        <th>PPN</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $grandTotal = 0; @endphp
                                    @forelse ($usulan->getFuiDetail as $key => $item)
                                        @php
                                            $jumlah = $item->Jumlah ?? 0;
                                            $harga = $item->Harga ?? 0;
                                            $diskon = $item->Diskon ?? 0;
                                            $ppn = $item->Ppn ?? 0;
                                            $subtotal = $jumlah * $harga - $diskon;
                                            $totalPpn = $subtotal * ($ppn / 100);
                                            $total = $subtotal + $totalPpn;
                                            $grandTotal += $total;
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $item->getNamaBarang->Nama ?? '-' }} -
                                                {{ $item->getNamaBarang->getMerk->Nama ?? '-' }}

                                            </td>
                                            <td>{{ $item->getVendor->Nama ?? '-' }}</td>
                                            <td>{{ $jumlah }} - {{ $item->getNamaBarang->getSatuan->NamaSatuan }}
                                            </td>
                                            <td>{{ 'Rp ' . number_format($harga, 0, ',', '.') }}</td>
                                            <td>{{ 'Rp ' . number_format($diskon, 0, ',', '.') }}</td>
                                            <td>
                                                {{ $ppn }} %
                                                <br>
                                                <small
                                                    class="text-muted">{{ 'Rp ' . number_format($totalPpn, 0, ',', '.') }}</small>
                                            </td>
                                            <td>{{ 'Rp ' . number_format($total, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Belum ada item.</td>
                                        </tr>
                                    @endforelse
                                    <tr>
                                        <td colspan="7" class="text-end fw-bold">Grand Total</td>
                                        <td>
                                            <strong>{{ 'Rp ' . number_format($grandTotal, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Rincian Biaya</label>
                        <div class="table-responsive">
                            <table class="table align-middle" width="100%">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:5%">No</th>
                                        <th>Biaya / Harga Akhir</th>
                                        <th>Suplier yang dipilih</th>
                                        <th>Harga + Diskon + PPN</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            {{ isset($usulan->BiayaAkhir) ? 'Rp ' . number_format($usulan->BiayaAkhir, 0, ',', '.') : '-' }}
                                        </td>
                                        <td>
                                            {{ $usulan->getVendor->Nama ?? '-' }}
                                        </td>
                                        <td>
                                            {{ isset($usulan->HargaDiskonPpn) ? 'Rp ' . number_format($usulan->HargaDiskonPpn, 0, ',', '.') : '-' }}
                                        </td>
                                        <td>
                                            {{ isset($usulan->Total) ? 'Rp ' . number_format($usulan->Total, 0, ',', '.') : '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <h5 class="fw-bold mb-2">Verifikasi RKAP <span class="fw-normal">(Departemen)</span>
                                    </h5>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Sudah masuk RKAP dari departemen ybs:</label>
                                        <div>
                                            @if ($usulan->SudahRkap === 'Y')
                                                <span class="badge bg-success">Ya</span>
                                            @elseif($usulan->SudahRkap === 'N')
                                                <span class="badge bg-danger">Tidak</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Sisa Budget dari RKAP untuk tahun ini yang masih
                                            dapat dipergunakan:</label>
                                        <input type="text" class="form-control"
                                            value="{{ isset($usulan->SisaBudget) ? 'Rp ' . number_format($usulan->SisaBudget, 0, ',', '.') : '-' }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <h5 class="fw-bold mb-2">Verifikasi RKAP <span class="fw-normal">(Keuangan)</span>
                                    </h5>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Sudah masuk RKAP dari departemen ybs:</label>
                                        <div>
                                            @if ($usulan->SudahRkap2 === 'Y')
                                                <span class="badge bg-success">Ya</span>
                                            @elseif($usulan->SudahRkap2 === 'N')
                                                <span class="badge bg-danger">Tidak</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Sisa Budget dari RKAP untuk tahun ini yang masih
                                            dapat dipergunakan:</label>
                                        <input type="text" class="form-control"
                                            value="{{ isset($usulan->SisaBudget2) ? 'Rp ' . number_format($usulan->SisaBudget2, 0, ',', '.') : '-' }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 justify-content-center">
                        <div class="col-12">
                            <h5 class="text-center mb-4"><strong>Persetujuan Permintaan Pembelian</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-borderless" style="max-width:60%; margin: 0 auto;">
                                    <colgroup>
                                        <col style="width: 50%;">
                                        <col style="width: 50%;">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <td class="text-center align-bottom">Diajukan oleh,<br>Kepala Divisi Jangmed
                                            </td>
                                            <td class="text-center align-bottom">Disetujui oleh,<br>Direktur</td>
                                        </tr>
                                        <tr>
                                            <td style="height: 70px;" class="text-center">
                                                @php
                                                    $ttdKadiv =
                                                        !empty($usulan->getAccKadiv) &&
                                                        !empty($usulan->getAccKadiv->tandatangan)
                                                            ? asset(
                                                                'storage/upload/tandatangan/' .
                                                                    $usulan->getAccKadiv->tandatangan,
                                                            )
                                                            : asset('assets/img/ccp/default_approve.png');
                                                @endphp
                                                @if (!empty($usulan->getAccKadiv->name))
                                                    <img src="{{ $ttdKadiv }}" alt="TTD Kadiv"
                                                        style="height: 100px;">
                                                @endif
                                            </td>
                                            <td style="height: 70px;" class="text-center">
                                                @php
                                                    $ttdDirektur =
                                                        !empty($usulan->getAccDirektur) &&
                                                        !empty($usulan->getAccDirektur->tandatangan)
                                                            ? asset(
                                                                'storage/upload/tandatangan/' .
                                                                    $usulan->getAccDirektur->tandatangan,
                                                            )
                                                            : asset('assets/img/ccp/default_approve.png');
                                                @endphp
                                                @if (!empty($usulan->getAccDirektur->name))
                                                    <img src="{{ $ttdDirektur }}" alt="TTD Direktur"
                                                        style="height: 100px;">
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center" style="padding-bottom:0;">
                                                <hr style="width: 70%; margin:0 auto 3px auto;border-top:2px solid #000;">
                                            </td>
                                            <td class="text-center" style="padding-bottom:0;">
                                                <hr style="width: 70%; margin:0 auto 3px auto;border-top:2px solid #000;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center align-top">
                                                <span style="font-weight:600;">
                                                    {{ $usulan->getAccKadiv ? $usulan->getAccKadiv->name : '-' }}
                                                </span>
                                                <br>
                                                <small class="text-muted" style="font-size:85%;">
                                                    @if (!empty($usulan->KadivJangMedPada))
                                                        {{ \Carbon\Carbon::parse($usulan->KadivAt)->translatedFormat('d M Y H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </small>
                                            </td>
                                            <td class="text-center align-top">
                                                <span style="font-weight:600;">
                                                    {{ $usulan->getAccDirektur ? $usulan->getAccDirektur->name : '-' }}
                                                </span>
                                                <br>
                                                <small class="text-muted" style="font-size:85%;">
                                                    @if (!empty($usulan->DirekturPada))
                                                        {{ \Carbon\Carbon::parse($usulan->DirekturPada)->translatedFormat('d M Y H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </small>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="col-12 text-end mt-4 d-flex justify-content-end align-items-center">
                        @can('fui-approve-direktur')
                            <form id="acc-direktur-form" action="{{ route('rekomendasi.setujui-direktur', $usulan->id) }}"
                                method="POST" style="display:inline-block;">
                                @csrf
                                <button type="button" class="btn btn-success me-2" id="acc-direktur-btn">
                                    <i class="fa fa-check"></i> ACC Direktur
                                </button>
                            </form>
                        @endcan
                        @can('fui-approve-jangmed')
                            <form id="acc-jangmed-form" action="{{ route('rekomendasi.setujui-kadiv', $usulan->id) }}"
                                method="POST" style="display:inline-block;">
                                @csrf
                                <button type="button" class="btn btn-primary me-2" id="acc-jangmed-btn">
                                    <i class="fa fa-check"></i> ACC Jangmed
                                </button>
                            </form>
                        @endcan
                        <a href="{{ route('usulan-investasi.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    @if (Session::get('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ Session::get('success') }}',
                iconColor: '#4BCC1F',
                confirmButtonText: 'Oke',
                confirmButtonColor: '#4BCC1F',
            });
        </script>
    @endif

    <script>
        document.getElementById('acc-direktur-btn').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi ACC Direktur',
                text: "Apakah Anda yakin ingin meng-ACC sebagai Direktur?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ACC Direktur!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('acc-direktur-form').submit();
                }
            });
        });

        document.getElementById('acc-jangmed-btn').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi ACC Jangmed',
                text: "Apakah Anda yakin ingin meng-ACC sebagai Jangmed?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ACC Jangmed!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('acc-jangmed-form').submit();
                }
            });
        });
    </script>
@endpush
