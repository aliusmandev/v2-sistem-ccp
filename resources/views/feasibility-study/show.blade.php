@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Detail Data Feasibility Study</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fs.index') }}">Feasibility Study</a></li>
                    <li class="breadcrumb-item active">Detail Data</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Detail Data Feasibility Study</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Barang</label>
                            <div class="form-control bg-light">
                                {{ $data->getBarang->Nama ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nilai Investasi</label>
                            <div class="form-control bg-light">
                                {{ isset($data->NilaiInvestasi) ? 'Rp ' . number_format($data->NilaiInvestasi, 0, ',', '.') : '-' }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Spesifikasi</label>
                            <div class="form-control bg-light">
                                {{ $data->Spesifikasi ?? '-' }}
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Biaya Tetap</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Bunga Tetap</label>
                                    <div class="form-control bg-light">
                                        {{ isset($data->BungaTetap) ? 'Rp ' . number_format($data->BungaTetap, 0, ',', '.') : '-' }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Penyusutan</label>
                                    <div class="form-control bg-light">
                                        {{ isset($data->Penyusutan) ? 'Rp ' . number_format($data->Penyusutan, 0, ',', '.') : '-' }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Maintenance</label>
                                    <div class="form-control bg-light">
                                        {{ isset($data->Maintenance) ? 'Rp ' . number_format($data->Maintenance, 0, ',', '.') : '-' }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pegawai</label>
                                    <div class="form-control bg-light">
                                        {{ isset($data->Pegawai) ? 'Rp ' . number_format($data->Pegawai, 0, ',', '.') : '-' }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Sewa Gedung</label>
                                    <div class="form-control bg-light">
                                        {{ isset($data->SewaGedung) ? 'Rp ' . number_format($data->SewaGedung, 0, ',', '.') : '-' }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Total Biaya Tetap</label>
                                    <div class="form-control bg-light">
                                        {{ isset($data->TotalBiayaTetap) ? 'Rp ' . number_format($data->TotalBiayaTetap, 0, ',', '.') : '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Biaya Variable</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Konsumable</label>
                                    <div class="form-control bg-light">
                                        {{ isset($data->Konsumable) ? 'Rp ' . number_format($data->Konsumable, 0, ',', '.') : '-' }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Dokter</label>
                                    <div class="form-control bg-light">
                                        {{ isset($data->Dokter) ? 'Rp ' . number_format($data->Dokter, 0, ',', '.') : '-' }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Total Biaya Variable</label>
                                    <div class="form-control bg-light">
                                        {{ isset($data->TotalBiayaVariable) ? 'Rp ' . number_format($data->TotalBiayaVariable, 0, ',', '.') : '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label fw-bold">Tarif</label>
                                <div class="form-control bg-light">
                                    {{ isset($data->Tarif) ? 'Rp ' . number_format($data->Tarif, 0, ',', '.') : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <h5 class="fw-bold mb-0">Data Rugi Laba (7 Tahun)</h5>
                            </div>
                            <div class="card-body py-3">
                                <div class="table-responsive">
                                    <table class="table align-middle" id="tabel-rugi-laba">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Keterangan</th>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <th>Tahun {{ $i }}</th>
                                                @endfor
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Get the details as a collection, indexed by TahunKe (as integer for easy lookup)
                                                $details = $data->getFsDetail->keyBy(function ($item) {
                                                    return (int) $item->TahunKe;
                                                });
                                            @endphp
                                            <tr>
                                                <th>Tahun Ke</th>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <td>{{ $i }}</td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td>Jumlah Pasien</td>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <td>
                                                        {{ !empty($details[$i]) ? $details[$i]->JumlahPasien ?? '-' : '-' }}
                                                    </td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td>Tarif Umum</td>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <td>
                                                        {{ !empty($details[$i]) && isset($details[$i]->TarifUmum) ? 'Rp ' . number_format($details[$i]->TarifUmum, 0, ',', '.') : '-' }}
                                                    </td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td>Tarif BPJS</td>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <td>
                                                        {{ !empty($details[$i]) && isset($details[$i]->TarifBpjs) ? 'Rp ' . number_format($details[$i]->TarifBpjs, 0, ',', '.') : '-' }}
                                                    </td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td>Revenue</td>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <td>
                                                        {{ !empty($details[$i]) && isset($details[$i]->Revenue) ? 'Rp ' . number_format($details[$i]->Revenue, 0, ',', '.') : '-' }}
                                                    </td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td>Biaya Tetap</td>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <td>
                                                        {{ !empty($details[$i]) && isset($details[$i]->BiayaTetap) ? 'Rp ' . number_format($details[$i]->BiayaTetap, 0, ',', '.') : '-' }}
                                                    </td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td>Biaya Variable</td>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <td>
                                                        {{ !empty($details[$i]) && isset($details[$i]->BiayaVariable) ? 'Rp ' . number_format($details[$i]->BiayaVariable, 0, ',', '.') : '-' }}
                                                    </td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td>Net Profit</td>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <td>
                                                        {{ !empty($details[$i]) && isset($details[$i]->NetProfit) ? 'Rp ' . number_format($details[$i]->NetProfit, 0, ',', '.') : '-' }}
                                                    </td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td>EBITDA</td>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <td>
                                                        {{ !empty($details[$i]) && isset($details[$i]->Ebitda) ? 'Rp ' . number_format($details[$i]->Ebitda, 0, ',', '.') : '-' }}
                                                    </td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td>Akumulasi EBITDA</td>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <td>
                                                        {{ !empty($details[$i]) && isset($details[$i]->AkumEbitda) ? 'Rp ' . number_format($details[$i]->AkumEbitda, 0, ',', '.') : '-' }}
                                                    </td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td>ROI Tahun Ke-</td>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <td>
                                                        {{ !empty($details[$i]) && isset($details[$i]->RoiTahunKe) ? $details[$i]->RoiTahunKe : '-' }}
                                                    </td>
                                                @endfor
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <p class="text-muted mt-2" style="font-size: 0.95em;">*Data hasil input feasibility study.
                                </p>
                            </div>

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
                                                    <td class="text-center" style="height:100px;">
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
                                <a href="{{ route('ajukan.show', encrypt($data->id)) }}" class="btn btn-secondary me-2">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>

                                @foreach ($approval as $item)
                                    @if (auth()->id() == ($item->UserId ?? null))
                                        <a href="#" class="btn btn-primary me-2 swal-confirm-btn-approve"
                                            data-url="{{ route('fs.approve', $item->ApprovalToken) }}"
                                            data-title="Konfirmasi"
                                            data-text="Apakah Anda yakin ingin menyetujui sebagai {{ $item->getJabatan->Nama }}?">
                                            <i class="fa {{ $item->icon ?? 'fa-user' }}"></i>
                                            Setujui
                                        </a>
                                    @endif
                                @endforeach


                            </div>
                        </div>

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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.swal-confirm-btn-approve').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var url = this.getAttribute('data-url');
                    var title = this.getAttribute('data-title') || 'Konfirmasi';
                    var text = this.getAttribute('data-text') ||
                        'Yakin ingin melakukan tindakan ini?';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Setujui'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });
        });
    </script>
@endpush
