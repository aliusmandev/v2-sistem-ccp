@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Permintaan Pembelian</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pp.index') }}">Permintaan Pembelian</a></li>
                    <li class="breadcrumb-item active">Detail Permintaan Pembelian</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card mb-4">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Detail Permintaan Pembelian</h4>
                    <p class="card-text mb-0">
                        Berikut detail permintaan pembelian.
                    </p>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Departemen</strong></label>
                            <input type="text" class="form-control" value="{{ $data->getDepartemen->Nama ?? '-' }}"
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Tanggal</strong></label>
                            <input type="date" class="form-control" value="{{ $data->Tanggal ?? '' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Jenis Permintaan</strong></label>
                            <input type="text" class="form-control" value="{{ $data->getJenisPermintaan->Nama ?? '' }}"
                                readonly>
                        </div>
                    </div>
                    <h5 class="mb-3"><strong>Detail Permintaan Pembelian</strong></h5>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle" id="table-detail-pembelian">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 20%">Nama Barang</th>
                                    <th style="width: 15%">Merk</th>
                                    <th style="width: 13%">Jumlah</th>
                                    <th style="width: 12%">Satuan</th>
                                    <th style="width: 20%">Rencana Penempatan</th>
                                    <th style="width: 20%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $details = $data->getDetail ?? [];
                                    $totalJumlah = 0;
                                @endphp
                                @foreach ($details as $detail)
                                    @php
                                        $totalJumlah += (int) $detail->Jumlah;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control"
                                                value="{{ $detail->getBarang->Nama ?? $detail->NamaBarang }}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control"
                                                value="{{ $detail->getBarang->getMerk->Nama ?? ($detail->getBarang->getMerk->Nama ?? '-') }}"
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" value="{{ $detail->Jumlah }}"
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control"
                                                value="{{ $detail->getBarang->getSatuan->NamaSatuan ?? $detail->Satuan }}"
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control"
                                                value="{{ $detail->RencanaPenempatan }}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="{{ $detail->Keterangan }}"
                                                readonly>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-end">
                                        <strong style="font-size: 1rem;">
                                            Total Jumlah: <span id="total-jumlah-view" style="font-size: 1rem;">
                                                {{ $totalJumlah }}
                                            </span>
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="row mt-4 justify-content-center">
                        <div class="col-12">
                            <h5 class="text-center mb-4"><strong>Persetujuan Permintaan Pembelian</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-borderless" style="max-width:100%; margin: 0 auto;">
                                    <colgroup>
                                        <col style="width: 20%;">
                                        <col style="width: 20%;">
                                        <col style="width: 20%;">
                                        <col style="width: 20%;">
                                        {{-- <col style="width: 20%;"> --}}
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <td class="text-center align-bottom">
                                                Diajukan oleh,
                                            </td>
                                            <td class="text-center align-bottom">
                                                Diketahui oleh,<br>
                                                Kepala Divisi
                                            </td>
                                            <td class="text-center align-bottom">
                                                Diketahui oleh,<br>
                                                Kepala Divisi Penunjang Medis/Umum
                                            </td>
                                            <td class="text-center align-bottom">
                                                Disetujui oleh,<br>
                                                Direktur
                                            </td>
                                            {{-- <td class="text-center align-bottom">
                                                Diterima Oleh,<br>
                                                Logum / SMI
                                            </td> --}}
                                        </tr>
                                        <tr>
                                            <td style="height: 70px;" class="text-center">
                                                @if (!empty($data->DiajukanOleh) && !empty($data->getDiajukanOleh->tandatangan))
                                                    <img src="{{ asset('storage/upload/tandatangan/' . $data->getDiajukanOleh->tandatangan) }}"
                                                        alt="TTD" style="max-width:110px; max-height:60px;">
                                                @endif
                                            </td>
                                            <td style="height: 70px;" class="text-center">
                                                @if (!empty($data->getKepalaDivisi) && !empty($data->getKepalaDivisi->tandatangan))
                                                    <img src="{{ asset('storage/upload/tandatangan/' . $data->getKepalaDivisi->tandatangan) }}"
                                                        alt="TTD" style="max-width:110px; max-height:60px;">
                                                @endif
                                            </td>
                                            <td style="height: 70px;" class="text-center">
                                                @if (!empty($data->getAccPenunjangMedis) && !empty($data->getAccPenunjangMedis->tandatangan))
                                                    <img src="{{ asset('storage/upload/tandatangan/' . $data->getAccPenunjangMedis->tandatangan) }}"
                                                        alt="TTD" style="max-width:110px; max-height:60px;">
                                                @endif
                                            </td>
                                            <td style="height: 70px;" class="text-center">
                                                @if (!empty($data->getAccDirektur) && !empty($data->getAccDirektur->tandatangan))
                                                    <img src="{{ asset('storage/upload/tandatangan/' . $data->getAccDirektur->tandatangan) }}"
                                                        alt="TTD" style="max-width:110px; max-height:60px;">
                                                @endif
                                            </td>
                                            {{-- <td style="height: 70px;" class="text-center">
                                                @if (!empty($data->getSmi) && !empty($data->getSmi->tandatangan))
                                                    <img src="{{ asset('storage/upload/tandatangan/' . $data->getSmi->tandatangan) }}"
                                                        alt="TTD" style="max-width:110px; max-height:60px;">
                                                @endif
                                            </td> --}}
                                        </tr>
                                        <tr>
                                            <td class="text-center" style="padding-bottom:0;">
                                                <hr style="width: 70%; margin:0 auto 3px auto;border-top:2px solid #000;">
                                            </td>
                                            <td class="text-center" style="padding-bottom:0;">
                                                <hr style="width: 70%; margin:0 auto 3px auto;border-top:2px solid #000;">
                                            </td>
                                            <td class="text-center" style="padding-bottom:0;">
                                                <hr style="width: 70%; margin:0 auto 3px auto;border-top:2px solid #000;">
                                            </td>
                                            <td class="text-center" style="padding-bottom:0;">
                                                <hr style="width: 70%; margin:0 auto 3px auto;border-top:2px solid #000;">
                                            </td>
                                            {{-- <td class="text-center" style="padding-bottom:0;">
                                                <hr style="width: 70%; margin:0 auto 3px auto;border-top:2px solid #000;">
                                            </td> --}}
                                        </tr>
                                        <tr>
                                            <td class="text-center align-top">
                                                <small>Nama Lengkap</small><br>
                                                <span style="font-weight:600;">
                                                    {{ $data->getDiajukanOleh->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center align-top">
                                                <small>Nama Lengkap</small><br>
                                                <span style="font-weight:600;">
                                                    {{ $data->getKepalaDivisi->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center align-top">
                                                <small>Nama Lengkap</small><br>
                                                <span style="font-weight:600;">
                                                    {{ $data->getAccPenunjangMedis->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center align-top">
                                                <small>Nama Lengkap</small><br>
                                                <span style="font-weight:600;">
                                                    {{ $data->getAccDirektur->name ?? '-' }}
                                                </span>
                                            </td>
                                            {{-- <td class="text-center align-top">
                                                <small>Nama Lengkap</small><br>
                                                <span style="font-weight:600;">
                                                    {{ $data->getSmi->name ?? '-' }}
                                                </span>
                                            </td> --}}
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="col-12 text-end mt-3">
                            <a href="{{ route('pp.index') }}" class="btn btn-secondary me-2">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>

                            @can('permintaan-approve-kepala-divisi')
                                <!-- Button untuk Kepala Divisi - Diketahui oleh -->
                                <form id="formKepalaDivisi" action="{{ route('pp.acc-kepala-divisi', $data->id) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    <button type="button" class="btn btn-primary me-2 swal-confirm-btn"
                                        data-title="Konfirmasi"
                                        data-text="Apakah Anda yakin ingin mengirimkan ke Kepala Divisi untuk diketahui?"
                                        data-form="formKepalaDivisi">
                                        <i class="fa fa-user"></i> Kepala Divisi
                                    </button>
                                </form>
                            @endcan

                            @can('permintaan-approve-penunjang')
                                <!-- Button untuk Kepala Divisi Penunjang Medis/Umum - Disetujui oleh -->
                                <form id="formDivisiPenunjang"
                                    action="{{ route('pp.acc-kepala-divisi-penunjang-medis', $data->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="button" class="btn btn-primary me-2 swal-confirm-btn"
                                        data-title="Konfirmasi"
                                        data-text="Apakah Anda yakin ingin mengirimkan ke Kepala Divisi Penunjang Medis/Umum untuk disetujui?"
                                        data-form="formDivisiPenunjang">
                                        <i class="fa fa-user-md"></i> Kadiv Penunjang Medis / Umum
                                    </button>
                                </form>
                            @endcan

                            @can('permintaan-approve-direktur')
                                <!-- Button untuk Direktur - Diterima oleh -->
                                <form id="formDirektur" action="{{ route('pp.acc-direktur', $data->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="button" class="btn btn-primary me-2 swal-confirm-btn"
                                        data-title="Konfirmasi"
                                        data-text="Apakah Anda yakin ingin mengirimkan ke Direktur untuk diterima?"
                                        data-form="formDirektur">
                                        <i class="fa fa-user-tie"></i> Direktur
                                    </button>
                                </form>
                            @endcan

                            {{-- @can('permintaan-approve-logistik')
                                <!-- Button untuk Logistik - Logistik / SMI -->
                                <form id="formLogistik" action="{{ route('pp.acc-smi', $data->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="button" class="btn btn-primary me-2 swal-confirm-btn"
                                        data-title="Konfirmasi" data-text="Apakah Anda yakin ingin mengirimkan ke Logistik?"
                                        data-form="formLogistik">
                                        <i class="fa fa-truck"></i> SMI</small>
                                    </button>
                                </form>
                            @endcan --}}



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
        @if (Session::get('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ Session::get('error') }}',
                    iconColor: '#d33',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#d33',
                });
            </script>
        @endif
        <script>
            document.querySelectorAll('.swal-confirm-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let formId = btn.getAttribute('data-form');
                    let form = document.getElementById(formId);
                    let title = btn.getAttribute('data-title') ?? 'Konfirmasi';
                    let text = btn.getAttribute('data-text') ?? 'Apakah Anda yakin?';
                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
