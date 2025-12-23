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
                            <!-- Tambah baris untuk nama jabatan di atas tabel approval -->
                            <div class="mb-2 text-center">
                                @if (!empty($approval))
                                    <div class="row justify-content-center">
                                        @foreach ($approval as $item)
                                            <div class="col text-center" style="font-weight:600;">
                                                {{ $item->getJabatan->Nama ?? '-' }}
                                                {{ $item->getDepartemen->Nama ?? '-' }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless" style="max-width:100%; margin: 0 auto;">
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
                                                <td class="text-center align-bottom">
                                                    {{-- Nama jabatan sudah ditampilkan di atas, bisa dikosongi atau diisi strip --}}
                                                    -
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach ($approval as $item)
                                                <td class="text-center" style="height:70px;">
                                                    @if ($item->Status == 'Approved')
                                                        {!! QrCode::size(80)->generate(route('approval.validasi', $item->ApprovalToken)) !!}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach ($approval as $item)
                                                <td class="text-center" style="padding-bottom:0;">
                                                    <hr
                                                        style="width: 70%; margin:0 auto 3px auto;border-top:2px solid #000;">
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach ($approval as $item)
                                                <td class="text-center align-top">
                                                    <small>Nama Lengkap</small><br>
                                                    <span style="font-weight:600;">
                                                        {{ $item->Nama ?? '-' }}
                                                    </span>
                                                    <br>
                                                    <small>{{ $item->Status ?? '-' }}</small>
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-12 text-end mt-3">
                            <a href="javascript:history.back()" class="btn btn-secondary me-2">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>

                            @foreach ($approval as $item)
                                <form id="formApprove{{ $item->id }}"
                                    action="{{ route('pp.approve', $item->ApprovalToken) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <input type="hidden" name="UserId" value="{{ $item->UserId }}">
                                    <input type="hidden" name="DokumenId" value="{{ $item->DokumenId }}">
                                    <input type="hidden" name="JenisForm" value="{{ $item->JenisFormId }}">
                                    @if (auth()->id() == ($item->UserId ?? null))
                                        <button type="button" class="btn btn-primary me-2 swal-confirm-btn"
                                            data-title="Konfirmasi"
                                            data-text="Apakah Anda yakin ingin menyetujui sebagai {{ $item->getJabatan->Nama ?? $item->JenisUser }}?"
                                            data-form="formApprove{{ $item->id }}">
                                            <i class="fa {{ $item->icon ?? 'fa-user' }}"></i>
                                            {{ $item->JabatanNama ?? $item->JenisUser }}
                                        </button>
                                    @endif
                                </form>
                            @endforeach
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
