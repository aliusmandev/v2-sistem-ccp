@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Form Input Penilaian</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Hta / Gpa</a></li>
                    <li class="breadcrumb-item active">Isi Penilaian</li>
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
                                </tbody>
                        </table>
                    </div>

                    <div class="alert alert-warning d-flex align-items-center" role="alert" style="min-height: 70px;">
                        <i class="fa fa-exclamation-circle me-2" style="align-self: center; font-size: 1.6rem;"></i>
                        <div class="d-flex align-items-center" style="min-height: 50px;">
                            <ol class="mb-0 ps-2">
                                <li>Isi HTA secara lengkap untuk setiap Vendor terlebih dahulu. Setelah HTA Vendor ini
                                    diisi, baru bisa melanjutkan ke Vendor berikutnya.</li>
                                <li>Semua kolom HTA wajib diisi.</li>
                                <li>Jika {{ auth()->user()->name }} sedang sibuk, Anda dapat menyimpan data sebagai draft
                                    terlebih dahulu dan melanjutkan pengisian di lain waktu.</li>
                            </ol>
                        </div>
                    </div>
                    <form id="formHtaGpa" action="{{ route('htagpa.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <ul class="nav nav-tabs tab-style-1 d-sm-flex d-block" role="tablist" id="vendorTabs">
                            @foreach ($data->getVendor as $vIdx => $Vendor)
                                @php
                                    // Jangan biarkan tab vendor berikutnya aktif jika tab sebelumnya belum diisi (berdasarkan Nilai1)
                                    $disableTab = false;
                                    if ($vIdx >= 1) {
                                        $prevVendor = $data->getVendor[$vIdx - 1] ?? null;
                                        $prevNilai1 = $prevVendor->getHtaGpa->Nilai1[0] ?? null;
                                        $disableTab = is_null($prevNilai1);
                                    }
                                @endphp
                                <li class="nav-item">
                                    <a class="nav-link {{ $vIdx === 0 ? 'active' : '' }} {{ $disableTab ? 'disabled' : '' }}"
                                        id="vendor-tab-{{ $vIdx }}"
                                        @if (!$disableTab) data-bs-toggle="tab" href="#vendor-pane-{{ $vIdx }}" role="tab" aria-controls="vendor-pane-{{ $vIdx }}" aria-selected="{{ $vIdx === 0 ? 'true' : 'false' }}" @else tabindex="-1" aria-disabled="true" @endif
                                        style="{{ $disableTab ? 'pointer-events: none; opacity: 0.5;' : '' }}">
                                        {{ $Vendor->getNamaVendor->Nama ?? 'Vendor' }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="vendorTabPanes">
                            @foreach ($data->getVendor as $vIdx => $Vendor)
                                {{-- @php
                                    dd($Vendor);
                                @endphp --}}
                                <div class="tab-pane fade {{ $vIdx === 0 ? 'show active' : '' }}"
                                    id="vendor-pane-{{ $vIdx }}" role="tabpanel"
                                    aria-labelledby="vendor-tab-{{ $vIdx }}">
                                    <input type="hidden" name="vendor[{{ $vIdx }}][IdVendor]"
                                        value="{{ $Vendor->NamaVendor }}">

                                    <input type="hidden" name="vendor[{{ $vIdx }}][IdPengajuan]"
                                        value="{{ $data->id }}">
                                    <input type="hidden" name="vendor[{{ $vIdx }}][PengajuanItemId]"
                                        value="{{ $data->getPengajuanItem[0]->id ?? '' }}">
                                    <input type="hidden" name="vendor[{{ $vIdx }}][IdBarang]"
                                        value="{{ $data->getPengajuanItem[0]->IdBarang ?? '' }}">
                                    {{-- Additional hidden data if needed --}}

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
                                                        <input type="text" value="{{ $parameter[$pm - 1]->Nama }}"
                                                            class="form-control"
                                                            name="vendor[{{ $vIdx }}][Parameter][]" readonly>
                                                        <input type="hidden" value="{{ $pm }}"
                                                            class="form-control"
                                                            name="vendor[{{ $vIdx }}][IdParameter][]" readonly>
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control" name="vendor[{{ $vIdx }}][Deskripsi][]" rows="4"
                                                            placeholder="Masukkan deskripsi">{!! $Vendor->getHtaGpa->Deskripsi[0] ?? '' !!}</textarea>
                                                    </td>
                                                    <td>
                                                        {{-- @php
                                                            dd($Vendor->getHtaGpa->Nilai1);
                                                        @endphp --}}
                                                        <div class="d-flex gap-1">
                                                            <input type="number" min="0" max="5"
                                                                value="{{ $Vendor->getHtaGpa->Nilai1[$key] ?? '' }}"
                                                                class="form-control nilai-input"
                                                                name="vendor[{{ $vIdx }}][Nilai1][]"
                                                                style="max-width: 100px;"
                                                                oninput="if(this.value>5)this.value=5;if(this.value<0)this.value=0;">
                                                            <input type="number" min="0" max="5"
                                                                value="{{ $Vendor->getHtaGpa->Nilai2[$key] ?? '' }}"
                                                                class="form-control nilai-input"
                                                                name="vendor[{{ $vIdx }}][Nilai2][]"
                                                                style="max-width: 100px;"
                                                                oninput="if(this.value>5)this.value=5;if(this.value<0)this.value=0;">
                                                            <input type="number" min="0" max="5"
                                                                value="{{ $Vendor->getHtaGpa->Nilai3[$key] ?? '' }}"
                                                                class="form-control nilai-input"
                                                                name="vendor[{{ $vIdx }}][Nilai3][]"
                                                                style="max-width: 100px;"
                                                                oninput="if(this.value>5)this.value=5;if(this.value<0)this.value=0;">
                                                            <input type="number" min="0" max="5"
                                                                value="{{ $Vendor->getHtaGpa->Nilai4[$key] ?? '' }}"
                                                                class="form-control nilai-input"
                                                                name="vendor[{{ $vIdx }}][Nilai4][]"
                                                                style="max-width: 100px;"
                                                                oninput="if(this.value>5)this.value=5;if(this.value<0)this.value=0;">
                                                            <input type="number" min="0" max="5"
                                                                value="{{ $Vendor->getHtaGpa->Nilai5[$key] ?? '' }}"
                                                                class="form-control nilai-input"
                                                                name="vendor[{{ $vIdx }}][Nilai5][]"
                                                                style="max-width: 100px;"
                                                                oninput="if(this.value>5)this.value=5;if(this.value<0)this.value=0;">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            value="{{ $Vendor->getHtaGpa->SubTotal[$key] ?? '' }}"
                                                            class="form-control subtotal-input"
                                                            name="vendor[{{ $vIdx }}][SubTotal][]" readonly>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end">Grand Total</th>
                                                <th>
                                                    <input type="text" class="form-control grandtotal-input"
                                                        name="vendor[{{ $vIdx }}][GrandTotal]" readonly
                                                        style="font-weight:bold;">
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label class="col-md-3 col-form-label fw-bold">Umur Ekonomis</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control"
                                                    name="vendor[{{ $vIdx }}][UmurEkonomis]"
                                                    placeholder="Masukkan Umur Ekonomis"
                                                    value="{{ $Vendor->getHtaGpa->UmurEkonomis ?? '' }}">
                                            </div>
                                            <label class="col-md-3 col-form-label fw-bold">Tarif Diusulkan</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control"
                                                    name="vendor[{{ $vIdx }}][TarifDiusulkan]"
                                                    placeholder="Masukkan Tarif Diusulkan"
                                                    value="{{ $Vendor->getHtaGpa->TarifDiusulkan ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-md-3 col-form-label fw-bold">Buyback Period</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control"
                                                    name="vendor[{{ $vIdx }}][BuybackPeriod]"
                                                    placeholder="Masukkan Buyback Period"
                                                    value="{{ $Vendor->getHtaGpa->BuybackPeriod ?? '' }}">
                                            </div>
                                            <label class="col-md-3 col-form-label fw-bold">Target Pemakaian Bulanan</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control"
                                                    name="vendor[{{ $vIdx }}][TargetPemakaianBulanan]"
                                                    placeholder="Masukkan Target Pemakaian Bulanan"
                                                    value="{{ $Vendor->getHtaGpa->TargetPemakaianBulanan ?? '' }}">
                                            </div>
                                        </div>
                                        <label class="col-md-3 col-form-label fw-bold">Keterangan</label>
                                        <div class="col-md-12">
                                            <textarea class="form-control" name="vendor[{{ $vIdx }}][Keterangan]" rows="3"
                                                placeholder="Masukkan Keterangan">{!! $Vendor->getHtaGpa->Keterangan ?? '' !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 d-flex justify-content-end">
                            <button type="submit" name="action" value="draft" class="btn btn-warning me-2">Simpan
                                Sebagai Draft</button>
                            <button type="button" id="btnAjukan" class="btn btn-success me-2">Ajukan</button>
                            <a href="{{ route('pp.show', $data->id) }}" class="btn btn-secondary">Kembali</a>
                        </div>




                    </form>
                </div>
            </div>

            <form id="formAjukanHtaGpa" action="{{ route('htagpa.ajukan') }}" method="POST" style="display:none;">
                @csrf
                <input type="hidden" name="IdPengajuan" value="{{ $data->id }}">
                <input type="hidden" name="PengajuanItemId" value="{{ $data->getPengajuanItem[0]->id ?? '' }}">
                <input type="hidden" name="IdBarang" value="{{ $data->getPengajuanItem[0]->IdBarang ?? '' }}">
                <input type="hidden" name="Status" value="Diajukan">
            </form>

        </div>
    </div>
@endsection
@push('js')
    @if (Session::get('success'))
        <script>
            setTimeout(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ Session::get('success') }}',
                    iconColor: '#4BCC1F',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#4BCC1F',
                });
            }, 500);
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('btnAjukan').addEventListener('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Ajukan Penilaian?',
                    text: "Apakah Anda yakin ingin mengajukan data penilaian ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ajukan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('formAjukanHtaGpa').submit();
                    }
                });
            });
        });
    </script>
    <script>
        function updateSubtotalsAndGrandTotal(vendorTable) {
            let grandTotal = 0;
            vendorTable.find("tbody tr").each(function() {
                let subtotal = 0;
                $(this).find('.nilai-input').each(function() {
                    let nilai = parseFloat($(this).val());
                    if (isNaN(nilai)) nilai = 0;
                    if (nilai > 5) {
                        $(this).val(5);
                        nilai = 5;
                    }
                    if (nilai < 0) {
                        $(this).val(0);
                        nilai = 0;
                    }
                    subtotal += nilai;
                });
                $(this).find('.subtotal-input').val(subtotal);
                grandTotal += subtotal;
            });
            vendorTable.find('.grandtotal-input').val(grandTotal);
        }

        $(document).ready(function() {
            $('.nilai-table').each(function() {
                let vendorTable = $(this);
                updateSubtotalsAndGrandTotal(vendorTable);

                vendorTable.on('input', '.nilai-input', function() {
                    updateSubtotalsAndGrandTotal(vendorTable);
                });
            });
        });
    </script>
@endpush
