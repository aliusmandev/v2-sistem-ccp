@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Lembar Disposisi</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lembar-disposisi.index') }}">Lembar Disposisi</a></li>
                    <li class="breadcrumb-item active">Tambah Lembar Disposisi</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Formulir Lembar Disposisi</h4>
                </div>
                <div class="card-body">

                    <form>
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>NAMA BARANG / JASA YANG AKAN
                                                DIBELI</strong></label>
                                        <input type="text" class="form-control bg-light"
                                            value="@if (isset($data->getBarang->Nama)) {{ $data->getBarang->Nama }}
                                            @else
                                                {{ $data->NamaBarang ?? '-' }} @endif"
                                            readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"><strong>HARGA</strong></label>
                                        @php
                                            $harga =
                                                $data->Harga ??
                                                ($data->getRekomendasi->getRekomedasiDetail[0]->HargaNego ?? 0);
                                        @endphp
                                        <input type="text" class="form-control bg-light"
                                            value="{{ number_format($harga, 0, ',', '.') }}" readonly>
                                        <small class="form-text text-muted">Harga yang telah dinegosiasi</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"><strong>RENCANA VENDOR</strong></label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $data->getVendor->Nama ?? ($data->RencanaVendor ?? '-') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label class="form-label"><strong>TUJUAN PENGGUNAAN/RUANGAN</strong></label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $data->TujuanPenempatan ?? ($data->getPengajuanPembelian->getPermintaan->getDetail[0]->RencanaPenempatan ?? 'Tidak Diisi Dipermintaan') }}"
                                            readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label d-block"><strong>FORM PERMINTAAN DARI USER</strong></label>
                                        <input type="text" class="form-control bg-light"
                                            value="@if (($data->FormPermintaan ?? null) == 'Y') Ya, Ada
                                                    @elseif(($data->FormPermintaan ?? null) == 'N')
                                                        Tidak ada
                                                    @else
                                                        Tidak diketahui @endif"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('ajukan.show', encrypt($data->id)) }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </form>
                    <div class="row mt-4 justify-content-center">
                        <div class="col-12">
                            <h5 class="text-center mb-4"><strong>Persetujuan Lembar Disposisi</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-borderless" style="max-width:100%; margin: 0 auto;">
                                    <colgroup>
                                        <col style="width: 20%;">
                                        <col style="width: 20%;">
                                        <col style="width: 20%;">
                                        <col style="width: 20%;">
                                        <col style="width: 20%;">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            @foreach ($data->getDetail->take(5) as $idx => $approval)
                                                <td class="text-center align-bottom">
                                                    <strong>Disetujui
                                                        {{ $approval->Jabatan ?? ($approval->Nama ?? $idx + 1) }}</strong>
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach ($data->getDetail->take(5) as $approval)
                                                <td style="height: 70px;" class="text-center">
                                                    @php
                                                        $statusAccY = ($approval->Status ?? null) === 'Y';
                                                        $barcodeValue =
                                                            $approval->ApprovalToken ?? ($approval->Nama ?? '-');
                                                    @endphp
                                                    @if ($statusAccY && $barcodeValue)
                                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x50&data={{ urlencode($barcodeValue) }}"
                                                            alt="Barcode {{ $approval->Nama ?? '' }}"
                                                            style="max-width:120px; max-height:60px;" />
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach ($data->getDetail->take(5) as $approval)
                                                <td class="text-center" style="padding-bottom:0;">
                                                    <hr
                                                        style="width: 70%; margin:0 auto 3px auto;border-top:2px solid #000;">
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach ($data->getDetail->take(5) as $approval)
                                                <td class="text-center align-top">
                                                    <span style="font-weight:600;">
                                                        {{ $approval->Nama ?? '-' }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $approval->updated_at ? \Carbon\Carbon::parse($approval->updated_at)->translatedFormat('d M Y H:i') : '-' }}
                                                    </small>
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 text-end mt-3">
                            @foreach ($data->getDetail->take(5) as $idx => $approval)
                                @if (($approval->Status ?? null) !== 'Y')
                                    <a href="#" class="btn btn-success btn-approve-lembar-disposisi"
                                        data-url="{{ route('lembar-disposisi.approve', $approval->ApprovalToken) }}"
                                        data-nama="{{ $approval->Nama ?? 'Approver ' . ($idx + 1) }}"
                                        onclick="event.preventDefault();
                                        Swal.fire({
                                            title: 'Konfirmasi Persetujuan',
                                            text: 'Apakah Anda yakin ingin menyetujui disposisi ini sebagai {{ $approval->Nama ?? 'Approver ' . ($idx + 1) }}?',
                                            icon: 'question',
                                            showCancelButton: true,
                                            confirmButtonText: 'Ya, Setujui!',
                                            cancelButtonText: 'Batal',
                                            confirmButtonColor: '#28a745',
                                            cancelButtonColor: '#d33'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = '{{ route('lembar-disposisi.approve', $approval->ApprovalToken) }}';
                                            }
                                        });">
                                        <i class="fa fa-check"></i> Setujui
                                        ({{ $approval->Nama ?? 'Approver ' . ($idx + 1) }})
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
