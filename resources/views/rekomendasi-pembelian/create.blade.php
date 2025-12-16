@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Form Rekomendasi Pembelian</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Rekomendasi Pembelian</a></li>
                    <li class="breadcrumb-item active">Form Rekomendasi</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12 col-xl-12">

            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Rekomendasi Pembelian untuk Pengajuan: <strong>{{ $data->KodePengajuan }}</strong>
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
                                <li>Isi rekomendasi pembelian untuk setiap Vendor dengan data yang lengkap. Setelah
                                    rekomendasi untuk Vendor ini diisi, Anda dapat melanjutkan ke Vendor berikutnya.</li>
                                <li>Semua kolom rekomendasi wajib diisi.</li>
                                <li>Jika {{ auth()->user()->name }} sedang sibuk, Anda dapat menyimpan data sebagai draft
                                    dan melanjutkan pengisian di lain waktu.</li>
                            </ol>
                        </div>
                    </div>
                    <form id="formRekomendasi" action="{{ route('rekomendasi.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <ul class="nav nav-tabs tab-style-1 d-sm-flex d-block" role="tablist" id="vendorTabs">
                            @foreach ($data->getVendor as $vIdx => $Vendor)
                                @php
                                    // The tab is enabled if it is the first, or if all previous have nilai fields (HargaAwal & HargaNego) terisi.
                                    $enabled = $vIdx === 0;
                                    if ($vIdx > 0) {
                                        $enabled = true;
                                        for ($i = 0; $i < $vIdx; $i++) {
                                            $prevRek = $data->getVendor[$i]->getRekomendasi ?? null;
                                            if (
                                                empty(optional($prevRek)->HargaAwal) ||
                                                empty(optional($prevRek)->HargaNego)
                                            ) {
                                                $enabled = false;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                <li class="nav-item">
                                    <a class="nav-link {{ $vIdx === 0 ? 'active' : '' }} {{ $enabled ? '' : 'disabled' }}"
                                        id="vendor-tab-{{ $vIdx }}" data-bs-toggle="tab"
                                        href="{{ $enabled ? '#vendor-pane-' . $vIdx : 'javascript:void(0)' }}"
                                        role="tab" aria-controls="vendor-pane-{{ $vIdx }}"
                                        aria-selected="{{ $vIdx === 0 ? 'true' : 'false' }}"
                                        @if (!$enabled) tabindex="-1" aria-disabled="true" @endif>
                                        {{ $Vendor->getNamaVendor->Nama ?? 'Vendor' }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="vendorTabPanes">
                            @foreach ($data->getVendor as $vIdx => $Vendor)
                                @php
                                    $paramHarga = 11 - 1;
                                    $paramSpek = 2 - 1;
                                    $paramGaransi = 13 - 1;
                                    $paramBmhp = 12 - 1;
                                @endphp
                                <div class="tab-pane fade {{ $vIdx === 0 ? 'show active' : '' }}"
                                    id="vendor-pane-{{ $vIdx }}" role="tabpanel"
                                    aria-labelledby="vendor-tab-{{ $vIdx }}">

                                    {{-- {{ dd($Vendor->getHtaGpa->Deskripsi) }} --}}
                                    <input type="hidden" name="rekomendasi[{{ $vIdx }}][IdPengajuan]"
                                        value="{{ $data->id }}">
                                    <input type="hidden" name="rekomendasi[{{ $vIdx }}][PengajuanItemId]"
                                        value="{{ $data->getPengajuanItem[0]->id ?? '' }}">
                                    <input type="hidden" name="rekomendasi[{{ $vIdx }}][IdRekomendasi]"
                                        value="">
                                    <input type="hidden" name="rekomendasi[{{ $vIdx }}][IdVendor]"
                                        value="{{ $Vendor->NamaVendor }}">
                                    <input type="hidden" name="rekomendasi[{{ $vIdx }}][NamaPermintaan]"
                                        value="{{ $data->getPengajuanItem[0]->getBarang->id ?? '' }}">

                                    <table class="table align-middle nilai-table" style="width:100%;"
                                        data-vidx="{{ $vIdx }}">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center" style="width:5%;">No</th>
                                                <th class="text-center" style="width:25%;">Parameter</th>
                                                <th class="text-center" style="width:70%;">Deskripsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td class="fw-bold">Harga Awal</td>
                                                <td>
                                                    <input type="text"
                                                        name="rekomendasi[{{ $vIdx }}][HargaAwal]"
                                                        class="form-control currency-input-global"
                                                        placeholder="Masukkan Harga Awal"
                                                        value="{{ isset($Vendor->getRekomendasi->HargaAwal) ? $Vendor->getRekomendasi->HargaAwal : (isset($Vendor->getHtaGpa->Deskripsi[$paramHarga]) ? $Vendor->getHtaGpa->Deskripsi[$paramHarga] : (old("rekomendasi.$vIdx.HargaAwal") ? preg_replace('/[^0-9]/', '', old("rekomendasi.$vIdx.HargaAwal")) : '')) }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td class="fw-bold">Harga Nego</td>
                                                <td>
                                                    <input type="text"
                                                        name="rekomendasi[{{ $vIdx }}][HargaNego]"
                                                        class="form-control currency-input-global"
                                                        placeholder="Masukkan Harga Nego"
                                                        value="{{ isset($Vendor->getRekomendasi->HargaNego) ? $Vendor->getRekomendasi->HargaNego : old("rekomendasi.$vIdx.HargaNego") }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td class="fw-bold">Spesifikasi</td>
                                                <td>
                                                    <textarea class="form-control" name="rekomendasi[{{ $vIdx }}][Spesifikasi]" rows="10"
                                                        placeholder="Masukkan Spesifikasi">{{ isset($Vendor->getRekomendasi->Spesifikasi) ? $Vendor->getRekomendasi->Spesifikasi : (isset($Vendor->getHtaGpa->Deskripsi[$paramSpek]) ? $Vendor->getHtaGpa->Deskripsi[$paramSpek] : old("rekomendasi.$vIdx.Spesifikasi")) }}</textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">4</td>
                                                <td class="fw-bold">Negara Produksi</td>
                                                <td>
                                                    <select name="rekomendasi[{{ $vIdx }}][NegaraProduksi]"
                                                        class="form-control select2" data-placeholder="Pilih Negara">
                                                        <option value="">Pilih Negara</option>
                                                        @foreach ($negara as $n)
                                                            <option value="{{ $n->Kode }}"
                                                                {{ isset($Vendor->getRekomendasi->NegaraProduksi) && $Vendor->getRekomendasi->NegaraProduksi == $n->Kode ? 'selected' : (old("rekomendasi.$vIdx.NegaraProduksi") == $n->Kode ? 'selected' : '') }}>
                                                                {{ $n->Nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">5</td>
                                                <td class="fw-bold">Garansi</td>
                                                <td>
                                                    <input type="text"
                                                        name="rekomendasi[{{ $vIdx }}][Garansi]"
                                                        class="form-control" placeholder="Garansi"
                                                        value="{{ isset($Vendor->getRekomendasi->Garansi) ? $Vendor->getRekomendasi->Garansi : (isset($Vendor->getHtaGpa->Deskripsi[$paramGaransi]) ? $Vendor->getHtaGpa->Deskripsi[$paramGaransi] : old("rekomendasi.$vIdx.Garansi")) }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">6</td>
                                                <td class="fw-bold">Teknisi</td>
                                                <td>
                                                    <input type="text"
                                                        name="rekomendasi[{{ $vIdx }}][Teknisi]"
                                                        class="form-control" placeholder="Teknisi"
                                                        value="{{ isset($Vendor->getRekomendasi->Teknisi) ? $Vendor->getRekomendasi->Teknisi : old("rekomendasi.$vIdx.Teknisi") }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">7</td>
                                                <td class="fw-bold">Bmhp</td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                        name="rekomendasi[{{ $vIdx }}][Bmhp]"
                                                        placeholder="Bahan Medis Habis Pakai (Bmhp)"
                                                        value="{{ isset($Vendor->getRekomendasi->Bmhp) ? $Vendor->getRekomendasi->Bmhp : (isset($Vendor->getHtaGpa->Deskripsi[$paramBmhp]) ? $Vendor->getHtaGpa->Deskripsi[$paramBmhp] : old("rekomendasi.$vIdx.Bmhp")) }}">
                                                </td>
                                            </tr>
                                            <!-- POPULASI Setelah Bmhp -->
                                            <tr>
                                                <td class="text-center">8</td>
                                                <td class="fw-bold">Populasi</td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                        name="rekomendasi[{{ $vIdx }}][Populasi]"
                                                        placeholder="Populasi"
                                                        value="{{ isset($Vendor->getRekomendasi->Populasi) ? $Vendor->getRekomendasi->Populasi : old("rekomendasi.$vIdx.Populasi") }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">9</td>
                                                <td class="fw-bold">Spare Part</td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                        name="rekomendasi[{{ $vIdx }}][SparePart]"
                                                        placeholder="Spare Part"
                                                        value="{{ isset($Vendor->getRekomendasi->SparePart) ? $Vendor->getRekomendasi->SparePart : old("rekomendasi.$vIdx.SparePart") }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">10</td>
                                                <td class="fw-bold">Backup Unit</td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                        name="rekomendasi[{{ $vIdx }}][BackupUnit]"
                                                        placeholder="Backup Unit"
                                                        value="{{ isset($Vendor->getRekomendasi->BackupUnit) ? $Vendor->getRekomendasi->BackupUnit : old("rekomendasi.$vIdx.BackupUnit") }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">11</td>
                                                <td class="fw-bold">TOP (Term of Payment)</td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                        name="rekomendasi[{{ $vIdx }}][Top]"
                                                        placeholder="Contoh: 30 hari"
                                                        value="{{ isset($Vendor->getRekomendasi->Top) ? $Vendor->getRekomendasi->Top : old("rekomendasi.$vIdx.Top") }}">
                                                </td>
                                            </tr>
                                            {{-- <tr>
                                                <td class="text-center">12</td>
                                                <td class="fw-bold">Rekomendasi</td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                        name="rekomendasi[{{ $vIdx }}][Rekomendasi]"
                                                        placeholder="Isi Rekomendasi"
                                                        value="{{ old("rekomendasi.$vIdx.Rekomendasi") }}">
                                                </td>
                                            </tr> --}}
                                            <tr>
                                                <td class="text-center">12</td>
                                                <td class="fw-bold">Keterangan</td>
                                                <td>
                                                    <textarea class="form-control" name="rekomendasi[{{ $vIdx }}][Keterangan]" rows="3"
                                                        placeholder="Masukkan Keterangan">{{ isset($Vendor->getRekomendasi->Keterangan) ? $Vendor->getRekomendasi->Keterangan : old("rekomendasi.$vIdx.Keterangan") }}</textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 d-flex justify-content-end">
                            <button type="submit" name="action" value="draft" class="btn btn-warning me-2">Simpan
                                Rekomendasi</button>

                            <a href="{{ route('rekomendasi.show', encrypt($data->id)) }}"
                                class="btn btn-secondary">Kembali</a>
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
    <script>
        // Fungsi format Rupiah
        function formatRupiahInput(input) {
            let angka = input.value.replace(/[^,\d]/g, '').toString();
            let split = angka.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            input.value = rupiah;
        }

        $(document).ready(function() {
            $('.currency-input-global, .harga-nego-input').each(function() {
                formatRupiahInput(this);
            });

            // Event format otomatis saat mengetik atau blur (untuk perubahan manual)
            $(document).on('keyup blur', '.currency-input-global, .harga-nego-input', function() {
                formatRupiahInput(this);
            });
        });
    </script>
@endpush
