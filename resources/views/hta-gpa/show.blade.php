@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Detail Penilaian</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Hta / Gpa</a></li>
                    <li class="breadcrumb-item active">Detail Penilaian</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12 col-xl-12">

            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Penilaian HTA / GPA
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="mb-3">Informasi Barang</h5>
                        <table class="table align-middle" style="width:100%;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:180px;">Nama Barang</th>
                                    <td>{{ $data->getPengajuanItem[0]->getBarang->Nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Merek</th>
                                    <td>{{ $data->getPengajuanItem[0]->getBarang->getMerk->Nama ?? '-' }}</td>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="alert alert-warning d-flex align-items-center" role="alert" style="min-height: 70px;">
                        <i class="fa fa-exclamation-circle me-2" style="align-self: center; font-size: 1.6rem;"></i>
                        <div class="d-flex align-items-center" style="min-height: 50px;">
                            <ol class="mb-0 ps-2">
                                <li>HTA untuk setiap Vendor sudah diisi dan dapat dilihat pada halaman ini.</li>
                                <li>Semua kolom hanya bisa dibaca (readonly).</li>
                                <li>Jika butuh perubahan, silakan hubungi admin atau kembali ke halaman sebelumnya.</li>
                            </ol>
                        </div>
                    </div>

                    <ul class="nav nav-tabs tab-style-1 d-sm-flex d-block" role="tablist" id="vendorTabs">
                        @foreach ($data->getVendor as $vIdx => $Vendor)
                            <li class="nav-item">
                                <a class="nav-link {{ $vIdx === 0 ? 'active' : '' }}" id="vendor-tab-{{ $vIdx }}"
                                    data-bs-toggle="tab" href="#vendor-pane-{{ $vIdx }}" role="tab"
                                    aria-controls="vendor-pane-{{ $vIdx }}"
                                    aria-selected="{{ $vIdx === 0 ? 'true' : 'false' }}">
                                    {{ $Vendor->getNamaVendor->Nama ?? 'Vendor' }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="vendorTabPanes">
                        @foreach ($data->getVendor as $vIdx => $Vendor)
                            <div class="tab-pane fade {{ $vIdx === 0 ? 'show active' : '' }}"
                                id="vendor-pane-{{ $vIdx }}" role="tabpanel"
                                aria-labelledby="vendor-tab-{{ $vIdx }}">
                                <table class="table align-middle nilai-table" style="width:100%;"
                                    data-vidx="{{ $vIdx }}">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width:10px;">No</th>
                                            <th class="text-center" style="width:17%">Parameter Penilaian</th>
                                            <th class="text-center" style="width:50%">Deskripsi</th>
                                            <th class="text-center" style="width:25%">Penilaian</th>
                                            <th class="text-center">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->getJenisPermintaan->getForm->Parameter as $key => $pm)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    {{ $parameter[$pm - 1]->Nama ?? '-' }}
                                                </td>
                                                <td>
                                                    {!! $Vendor->getHtaGpa->Deskripsi[$key] ?? '-' !!}
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <div class="mb-1 me-1 d-flex align-items-center">

                                                            <input type="number" readonly min="0" max="5"
                                                                value="{{ $Vendor->getHtaGpa->Nilai1[$key] ?? '' }}"
                                                                class="form-control bg-light" style="width:65px;">
                                                        </div>
                                                        <div class="mb-1 me-1 d-flex align-items-center">

                                                            <input type="number" readonly min="0" max="5"
                                                                value="{{ $Vendor->getHtaGpa->Nilai2[$key] ?? '' }}"
                                                                class="form-control bg-light" style="width:65px;">
                                                        </div>
                                                        <div class="mb-1 me-1 d-flex align-items-center">

                                                            <input type="number" readonly min="0" max="5"
                                                                value="{{ $Vendor->getHtaGpa->Nilai3[$key] ?? '' }}"
                                                                class="form-control bg-light" style="width:65px;">
                                                        </div>
                                                        <div class="mb-1 me-1 d-flex align-items-center">

                                                            <input type="number" readonly min="0" max="5"
                                                                value="{{ $Vendor->getHtaGpa->Nilai4[$key] ?? '' }}"
                                                                class="form-control bg-light" style="width:65px;">
                                                        </div>
                                                        <div class="mb-1 d-flex align-items-center">

                                                            <input type="number" readonly min="0" max="5"
                                                                value="{{ $Vendor->getHtaGpa->Nilai5[$key] ?? '' }}"
                                                                class="form-control bg-light" style="width:65px;">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        value="{{ $Vendor->getHtaGpa->SubTotal[$key] ?? '' }}"
                                                        class="form-control bg-light" readonly style="font-weight:bold;">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">Grand Total</th>
                                            <th>
                                                <input type="text" value="{{ $Vendor->getHtaGpa->GrandTotal ?? '' }}"
                                                    class="form-control bg-light" readonly style="font-weight:bold;">
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="row mt-3">
                                    <div class="col-md-6 mb-2">
                                        <div class="mb-2">
                                            <label class="col-form-label fw-bold">Umur Ekonomis</label>
                                            <input type="text" class="form-control bg-light" readonly
                                                value="{{ $Vendor->getHtaGpa->UmurEkonomis ?? '-' }}">
                                        </div>
                                        <div>
                                            <label class="col-form-label fw-bold">Tarif Diusulkan</label>
                                            <input type="text" class="form-control bg-light" readonly
                                                value="{{ $Vendor->getHtaGpa->TarifDiusulkan ?? '-' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="mb-2">
                                            <label class="col-form-label fw-bold">Buyback Period</label>
                                            <input type="text" class="form-control bg-light" readonly
                                                value="{{ $Vendor->getHtaGpa->BuybackPeriod ?? '-' }}">
                                        </div>
                                        <div>
                                            <label class="col-form-label fw-bold">Target Pemakaian Bulanan</label>
                                            <input type="text" class="form-control bg-light" readonly
                                                value="{{ $Vendor->getHtaGpa->TargetPemakaianBulanan ?? '-' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <label class="col-form-label fw-bold">Keterangan</label>
                                        <textarea class="form-control bg-light" rows="3" readonly style="resize: vertical;">{!! $Vendor->getHtaGpa->Keterangan ?? '-' !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mt-4 justify-content-center">
                        <div class="col-12">
                            <h5 class="text-center mb-4"><strong>Persetujuan HTA / GPA</strong></h5>
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
                                            @foreach (($data->getHtaGpa->getPenilai ?? collect())->take(5) as $idx => $penilai)
                                                <td class="text-center align-bottom">
                                                    <strong>Disetujui {{ $penilai->PenilaiKe ?? $idx + 1 }}</strong>
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach (($data->getHtaGpa->getPenilai ?? collect())->take(5) as $penilai)
                                                <td style="height: 70px;" class="text-center">
                                                    @php
                                                        $statusAccY = ($penilai->StatusAcc ?? null) === 'Y';
                                                        // Barcode content bisa berupa apapun (misal ApprovalToken atau PenilaiKe)
                                                        $barcodeValue =
                                                            $penilai->ApprovalToken ?? ($penilai->PenilaiKe ?? '-');
                                                    @endphp
                                                    @if ($statusAccY && $barcodeValue)
                                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x50&data={{ urlencode($barcodeValue) }}"
                                                            alt="Barcode Penilai {{ $penilai->PenilaiKe ?? '' }}"
                                                            style="max-width:120px; max-height:60px;" />
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach (($data->getHtaGpa->getPenilai ?? collect())->take(5) as $penilai)
                                                <td class="text-center" style="padding-bottom:0;">
                                                    <hr
                                                        style="width: 70%; margin:0 auto 3px auto;border-top:2px solid #000;">
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach (($data->getHtaGpa->getPenilai ?? collect())->take(5) as $penilai)
                                                <td class="text-center align-top">
                                                    <span style="font-weight:600;">
                                                        {{ $penilai->Nama ?? '-' }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $penilai->AccPada ?? '-' }}
                                                    </small>
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 text-end mt-3">
                            @foreach (($data->getHtaGpa->getPenilai ?? collect())->take(5) as $idx => $penilai)
                                <a href="#" class="btn btn-success btn-approve-penilai"
                                    data-url="{{ route('htagpa.approve', $penilai->ApprovalToken) }}"
                                    data-penilaike="{{ $penilai->PenilaiKe ?? $idx + 1 }}"
                                    onclick="event.preventDefault();
                                    Swal.fire({
                                        title: 'Konfirmasi Persetujuan',
                                        text: 'Apakah Anda yakin ingin menyetujui HTA/GPA ini sebagai Penilai {{ $penilai->PenilaiKe ?? $idx + 1 }}?',
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonText: 'Ya, Setujui!',
                                        cancelButtonText: 'Batal',
                                        confirmButtonColor: '#28a745',
                                        cancelButtonColor: '#d33'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = '{{ route('htagpa.approve', $penilai->ApprovalToken) }}';
                                        }
                                    });">
                                    <i class="fa fa-check"></i> Setujui HTA/GPA (Penilai
                                    {{ $penilai->PenilaiKe ?? $idx + 1 }})
                                </a>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
@push('js')
    @if (Session::get('error'))
        <script>
            setTimeout(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ Session::get('error') }}',
                    iconColor: '#d33',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#d33',
                });
            }, 500);
        </script>
    @endif
    @if (Session::get('success'))
        <script>
            setTimeout(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ Session::get('success') }}',
                    iconColor: '#28a745',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#28a745',
                });
            }, 500);
        </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function konfirmasiApprove(idx, penilaiKe) {
            Swal.fire({
                title: 'Konfirmasi Persetujuan',
                text: 'Apakah Anda yakin ingin menyetujui HTA/GPA ini sebagai Penilai ' + penilaiKe + '?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approve-form-' + idx).submit();
                }
            });
        }
    </script>
@endpush
