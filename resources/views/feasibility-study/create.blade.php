@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Input Data Feasibility Study</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fs.index') }}">Feasibility Study</a></li>
                    <li class="breadcrumb-item active">Input Data</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Formulir Input Data Feasibility Study</h4>
                </div>
                <form action="{{ route('fs.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="idPengajuan" value="{{ $idPengajuan }}">
                    <input type="hidden" name="idPengajuanItem" value="{{ $idPengajuanItem }}">

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="NamaBarang" class="form-label fw-bold">Nama Barang</label>
                                <select class="form-select select2 @error('NamaBarang') is-invalid @enderror"
                                    name="NamaBarang" id="NamaBarang">
                                    <option value="">-- Pilih Nama Barang --</option>
                                    @if (isset($barang))
                                        <option value="{{ $barang->id }}"
                                            {{ old('NamaBarang', $barang->Nama ?? '') == $barang->Nama ? 'selected' : '' }}>
                                            {{ $barang->Nama }}
                                        </option>
                                    @endif
                                </select>
                                @error('NamaBarang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="NilaiInvestasi" class="form-label fw-bold">Nilai Investasi</label>
                                <input type="text"
                                    class="form-control rupiah @error('NilaiInvestasi') is-invalid @enderror"
                                    name="NilaiInvestasi" id="NilaiInvestasi"
                                    value="{{ old('NilaiInvestasi', isset($data->getFui->Total) ? number_format($data->getFui->Total, 0, ',', '.') : '0') }}"
                                    placeholder="Masukkan nilai investasi" oninput="this.value = formatRupiah(this.value)">

                                @error('NilaiInvestasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="Spesifikasi" class="form-label fw-bold">Spesifikasi</label>
                                <textarea class="form-control @error('Spesifikasi') is-invalid @enderror" name="Spesifikasi" id="Spesifikasi"
                                    rows="10" placeholder="Masukkan spesifikasi">{{ old('Spesifikasi', $data->getRekomendasi->getRekomedasiDetail[0]) }}</textarea>
                                @error('Spesifikasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">Biaya Tetap</h6>
                                    <div class="mb-3">
                                        <label for="BungaTetap" class="form-label fw-bold">Bunga Tetap</label>
                                        <input type="text"
                                            class="form-control rupiah @error('BungaTetap') is-invalid @enderror"
                                            name="BungaTetap" id="BungaTetap" value="{{ old('BungaTetap') }}"
                                            placeholder="Masukkan bunga tetap" oninput="formatRupiahAndCalculateTotal()">
                                        @error('BungaTetap')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="Penyusutan" class="form-label fw-bold">Penyusutan</label>
                                        <input type="text"
                                            class="form-control rupiah @error('Penyusutan') is-invalid @enderror"
                                            name="Penyusutan" id="Penyusutan" value="{{ old('Penyusutan') }}"
                                            placeholder="Masukkan penyusutan" oninput="formatRupiahAndCalculateTotal()">
                                        @error('Penyusutan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="Maintenance" class="form-label fw-bold">Maintenance</label>
                                        <input type="text"
                                            class="form-control rupiah @error('Maintenance') is-invalid @enderror"
                                            name="Maintenance" id="Maintenance" value="{{ old('Maintenance') }}"
                                            placeholder="Masukkan biaya maintenance"
                                            oninput="formatRupiahAndCalculateTotal()">
                                        @error('Maintenance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="Pegawai" class="form-label fw-bold">Pegawai</label>
                                        <input type="text"
                                            class="form-control rupiah @error('Pegawai') is-invalid @enderror"
                                            name="Pegawai" id="Pegawai" value="{{ old('Pegawai') }}"
                                            placeholder="Masukkan biaya pegawai" oninput="formatRupiahAndCalculateTotal()">
                                        @error('Pegawai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="SewaGedung" class="form-label fw-bold">Sewa Gedung</label>
                                        <input type="text"
                                            class="form-control rupiah @error('SewaGedung') is-invalid @enderror"
                                            name="SewaGedung" id="SewaGedung" value="{{ old('SewaGedung') }}"
                                            placeholder="Masukkan biaya sewa gedung"
                                            oninput="formatRupiahAndCalculateTotal()">
                                        @error('SewaGedung')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="TotalBiayaTetap" class="form-label fw-bold">Total Biaya Tetap</label>
                                        <input type="text"
                                            class="form-control rupiah @error('TotalBiayaTetap') is-invalid @enderror"
                                            name="TotalBiayaTetap" id="TotalBiayaTetap"
                                            value="{{ old('TotalBiayaTetap') }}" placeholder="Masukkan total biaya tetap"
                                            readonly>
                                        @error('TotalBiayaTetap')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">Biaya Variable</h6>
                                    <div class="mb-3">
                                        <label for="Konsumable" class="form-label fw-bold">Konsumable</label>
                                        <input type="text"
                                            class="form-control rupiah @error('Konsumable') is-invalid @enderror"
                                            name="Konsumable" id="Konsumable" value="{{ old('Konsumable') }}"
                                            placeholder="Masukkan biaya konsumable"
                                            oninput="formatRupiahInput(this); calculateTotalVariable();">
                                        @error('Konsumable')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="Dokter" class="form-label fw-bold">Dokter</label>
                                        <input type="text"
                                            class="form-control rupiah @error('Dokter') is-invalid @enderror"
                                            name="Dokter" id="Dokter" value="{{ old('Dokter') }}"
                                            placeholder="Masukkan biaya dokter"
                                            oninput="formatRupiahInput(this); calculateTotalVariable();">
                                        @error('Dokter')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="TotalBiayaVariable" class="form-label fw-bold">Total Biaya
                                            Variable</label>
                                        <input type="text"
                                            class="form-control rupiah @error('TotalBiayaVariable') is-invalid @enderror"
                                            name="TotalBiayaVariable" id="TotalBiayaVariable"
                                            value="{{ old('TotalBiayaVariable') }}"
                                            placeholder="Masukkan total biaya variable" readonly>
                                        @error('TotalBiayaVariable')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="Tarif" class="form-label fw-bold">Tarif</label>
                                    <input type="text"
                                        class="form-control rupiah @error('Tarif') is-invalid @enderror" name="Tarif"
                                        id="Tarif" value="{{ old('Tarif') }}" placeholder="Masukkan tarif"
                                        oninput="formatRupiahInput(this);">
                                    @error('Tarif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card mt-4">
                                <div class="card-header bg-light">
                                    <h5 class="fw-bold mb-0">Data Rugi Laba (7 Tahun)</h5>
                                </div>
                                <div class="card-body py-3">
                                    <div class="table-responsive">
                                        <table class="table align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Keterangan</th>
                                                    @for ($i = 1; $i <= 7; $i++)
                                                        <th>Tahun {{ $i }}</th>
                                                    @endfor
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>Tahun Ke</th>
                                                    @for ($i = 1; $i <= 7; $i++)
                                                        <td>
                                                            {{ $i }}
                                                            <input type="hidden"
                                                                name="rugi_laba[TahunKe][{{ $i }}]"
                                                                value="{{ $i }}">
                                                        </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td>Jumlah Pasien</td>
                                                    @for ($i = 1; $i <= 7; $i++)
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm"
                                                                name="rugi_laba[JumlahPasien][{{ $i }}]"
                                                                value="{{ old("rugi_laba.JumlahPasien.$i") }}"
                                                                placeholder="Masukan jumlah pasien">
                                                        </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td>Tarif Umum</td>
                                                    @for ($i = 1; $i <= 7; $i++)
                                                        <td>
                                                            <input type="text"
                                                                class="form-control form-control-sm rupiah-input"
                                                                name="rugi_laba[TarifUmum][{{ $i }}]"
                                                                value="{{ old("rugi_laba.TarifUmum.$i") ? 'Rp ' . number_format((int) preg_replace('/\D/', '', old("rugi_laba.TarifUmum.$i")), 0, ',', '.') : '' }}"
                                                                placeholder="Masukan tarif umum"
                                                                oninput="formatRupiahInput(this)">
                                                        </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td>Tarif BPJS</td>
                                                    @for ($i = 1; $i <= 7; $i++)
                                                        <td>
                                                            <input type="text"
                                                                class="form-control form-control-sm rupiah-input"
                                                                name="rugi_laba[TarifBpjs][{{ $i }}]"
                                                                value="{{ old("rugi_laba.TarifBpjs.$i") ? 'Rp ' . number_format((int) preg_replace('/\D/', '', old("rugi_laba.TarifBpjs.$i")), 0, ',', '.') : '' }}"
                                                                placeholder="Masukan tarif BPJS"
                                                                oninput="formatRupiahInput(this)">
                                                        </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td>Revenue</td>
                                                    @for ($i = 1; $i <= 7; $i++)
                                                        <td>
                                                            <input type="text"
                                                                class="form-control form-control-sm rupiah-input"
                                                                name="rugi_laba[Revenue][{{ $i }}]"
                                                                value="{{ old("rugi_laba.Revenue.$i") ? 'Rp ' . number_format((int) preg_replace('/\D/', '', old("rugi_laba.Revenue.$i")), 0, ',', '.') : '' }}"
                                                                placeholder="Masukan revenue"
                                                                oninput="formatRupiahInput(this)">
                                                        </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td>Biaya Tetap</td>
                                                    @for ($i = 1; $i <= 7; $i++)
                                                        <td>
                                                            <input type="text"
                                                                class="form-control form-control-sm rupiah-input"
                                                                name="rugi_laba[BiayaTetap][{{ $i }}]"
                                                                value="{{ old("rugi_laba.BiayaTetap.$i") ? 'Rp ' . number_format((int) preg_replace('/\D/', '', old("rugi_laba.BiayaTetap.$i")), 0, ',', '.') : '' }}"
                                                                placeholder="Masukan biaya tetap"
                                                                oninput="formatRupiahInput(this)">
                                                        </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td>Biaya Variable</td>
                                                    @for ($i = 1; $i <= 7; $i++)
                                                        <td>
                                                            <input type="text"
                                                                class="form-control form-control-sm rupiah-input"
                                                                name="rugi_laba[BiayaVariable][{{ $i }}]"
                                                                value="{{ old("rugi_laba.BiayaVariable.$i") ? 'Rp ' . number_format((int) preg_replace('/\D/', '', old("rugi_laba.BiayaVariable.$i")), 0, ',', '.') : '' }}"
                                                                placeholder="Masukan biaya variable"
                                                                oninput="formatRupiahInput(this)">
                                                        </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td>Net Profit</td>
                                                    @for ($i = 1; $i <= 7; $i++)
                                                        <td>
                                                            <input type="text"
                                                                class="form-control form-control-sm rupiah-input"
                                                                name="rugi_laba[NetProfit][{{ $i }}]"
                                                                value="{{ old("rugi_laba.NetProfit.$i") ? 'Rp ' . number_format((int) preg_replace('/\D/', '', old("rugi_laba.NetProfit.$i")), 0, ',', '.') : '' }}"
                                                                placeholder="Masukan net profit"
                                                                oninput="formatRupiahInput(this)">
                                                        </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td>EBITDA</td>
                                                    @for ($i = 1; $i <= 7; $i++)
                                                        <td>
                                                            <input type="text"
                                                                class="form-control form-control-sm rupiah-input"
                                                                name="rugi_laba[Ebitda][{{ $i }}]"
                                                                value="{{ old("rugi_laba.Ebitda.$i") ? 'Rp ' . number_format((int) preg_replace('/\D/', '', old("rugi_laba.Ebitda.$i")), 0, ',', '.') : '' }}"
                                                                placeholder="Masukan EBITDA"
                                                                oninput="formatRupiahInput(this)">
                                                        </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td>Akumulasi EBITDA</td>
                                                    @for ($i = 1; $i <= 7; $i++)
                                                        <td>
                                                            <input type="text"
                                                                class="form-control form-control-sm rupiah-input"
                                                                name="rugi_laba[AkumEbitda][{{ $i }}]"
                                                                value="{{ old("rugi_laba.AkumEbitda.$i") ? 'Rp ' . number_format((int) preg_replace('/\D/', '', old("rugi_laba.AkumEbitda.$i")), 0, ',', '.') : '' }}"
                                                                placeholder="Masukan akumulasi EBITDA"
                                                                oninput="formatRupiahInput(this)">
                                                        </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td>ROI Tahun Ke-</td>
                                                    @for ($i = 1; $i <= 7; $i++)
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm"
                                                                name="rugi_laba[RoiTahunKe][{{ $i }}]"
                                                                value="{{ old("rugi_laba.RoiTahunKe.$i") }}"
                                                                placeholder="Masukan ROI tahun ke-{{ $i }}">
                                                        </td>
                                                    @endfor
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- <p class="text-muted mt-2" style="font-size: 0.95em;">*Isikan data utama saja secara
                                        manual. Hitungan revenue, net profit, dsb bisa manual/terpisah <br> Versi sederhana:
                                        hanya Jumlah Pasien, Tarif dan Biaya (Tanpa hitung otomatis).</p> --}}
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('ajukan.show', encrypt($idPengajuan)) }}"
                                    class="btn btn-secondary me-2">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                </form>

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
        document.addEventListener('DOMContentLoaded', function() {
            var input = document.getElementById('NilaiInvestasi');
            if (input && input.value) {
                input.value = formatRupiah(input.value);
            }
        });

        function formatRupiah(angka) {
            angka = angka.replace(/[^,\d]/g, '').toString();
            var split = angka.split(',');
            var sisa = split[0].length % 3;
            var rupiah = split[0].substr(0, sisa);
            var ribuan = split[0].substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            rupiah = rupiah ? rupiah : '0';
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }
    </script>
    <script>
        function formatRupiah(angka, prefix = 'Rp ') {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                rupiah += (sisa ? '.' : '') + ribuan.join('.');
            }
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix + rupiah;
        }

        function parseRupiahToInt(rupiahStr) {
            // Remove anything not digit
            return parseInt(rupiahStr.replace(/[^0-9]/g, '')) || 0;
        }

        function formatRupiahAndCalculateTotal() {
            const fields = [
                'BungaTetap',
                'Penyusutan',
                'Maintenance',
                'Pegawai',
                'SewaGedung'
            ];

            let total = 0;
            fields.forEach(id => {
                let input = document.getElementById(id);
                if (input) {
                    let val = input.value;
                    // Format
                    input.value = val ? formatRupiah(val.replace(/^Rp\s*/, '')) : '';
                    // Sum for total
                    total += parseRupiahToInt(input.value);
                }
            });

            let totalField = document.getElementById('TotalBiayaTetap');
            if (totalField) {
                totalField.value = total > 0 ? formatRupiah(total.toString()) : '';
            }
        }

        // Initial format for fields if there is old value on page load
        document.addEventListener('DOMContentLoaded', function() {
            formatRupiahAndCalculateTotal();
        });

        // Format for single input on the fly (for variable biaya & Tarif)
        function formatRupiahInput(input) {
            // Remove "Rp" prefix & formatting, keep only digits and comma
            let val = input.value.replace(/^Rp\s*\.?/i, '');
            input.value = val ? formatRupiah(val) : '';
        }
    </script>
    <script>
        function calculateTotalVariable() {
            let konsumableEl = document.getElementById('Konsumable');
            let dokterEl = document.getElementById('Dokter');

            let konsumable = konsumableEl ? parseRupiahToInt(konsumableEl.value) : 0;
            let dokter = dokterEl ? parseRupiahToInt(dokterEl.value) : 0;
            let total = konsumable + dokter;

            let totalField = document.getElementById('TotalBiayaVariable');
            if (totalField) {
                totalField.value = total > 0 ? formatRupiah(total.toString()) : '';
            }
        }

        // Inisialisasi agar saat halaman reload nilai tetap diformat dan total terhitung
        document.addEventListener('DOMContentLoaded', function() {
            // Format Variable Biaya fields
            let konsumableEl = document.getElementById('Konsumable');
            let dokterEl = document.getElementById('Dokter');
            let tarifEl = document.getElementById('Tarif');
            if (konsumableEl && konsumableEl.value) konsumableEl.value = formatRupiah(konsumableEl.value.replace(
                /^Rp\s*\.?/i, ''));
            if (dokterEl && dokterEl.value) dokterEl.value = formatRupiah(dokterEl.value.replace(/^Rp\s*\.?/i, ''));
            if (tarifEl && tarifEl.value) tarifEl.value = formatRupiah(tarifEl.value.replace(/^Rp\s*\.?/i, ''));
            calculateTotalVariable();
        });
    </script>
    <script>
        // Tidak perlu tambahTahun dan kurangiTahun, hapus fungsi-fungsi tersebut

        // Opsi tambahan perhitungan otomatis Revenue, NetProfit, EBITDA, dll (contoh sederhana)
        document.addEventListener('DOMContentLoaded', function() {
            hitungRugiLabaSemuaTahun();
            const inputs = document.querySelectorAll('#tabel-rugi-laba input:not([readonly])');
            inputs.forEach(function(input) {
                input.addEventListener('input', hitungRugiLabaSemuaTahun);
            });
        });

        function parseRupiah(str) {
            if (!str) return 0;
            return parseInt(String(str).replace(/[^0-9]/g, '')) || 0;
        }

        // Format rupiah saat ketik untuk semua input .rupiah-input di tabel rugi laba
        function formatRupiahInput(input) {
            // Remove "Rp","." on the left and keep only numbers and comma
            let value = input.value.replace(/[^,\d]/g, '');
            if (value) {
                // Add thousand separator and Rp prefix
                let split = value.split(',');
                let sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
                if (ribuan) {
                    rupiah += (sisa ? '.' : '') + ribuan.join('.');
                }
                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                input.value = rupiah ? 'Rp ' + rupiah : '';
            } else {
                input.value = '';
            }
        }

        function hitungRugiLabaSemuaTahun() {
            let tahun_count = 7; // Selalu 7 tahun
            for (let i = 1; i <= tahun_count; i++) {
                // Ambil elemen sesuai field dan tahun
                let jumlahPasien = document.querySelector(`input[name="rugi_laba[JumlahPasien][${i}]"]`);
                let tarifUmum = document.querySelector(`input[name="rugi_laba[TarifUmum][${i}]"]`);
                let tarifBpjs = document.querySelector(`input[name="rugi_laba[TarifBpjs][${i}]"]`);
                let revenue = document.querySelector(`input[name="rugi_laba[Revenue][${i}]"]`);
                let biayaTetap = document.querySelector(`input[name="rugi_laba[BiayaTetap][${i}]"]`);
                let biayaVariable = document.querySelector(`input[name="rugi_laba[BiayaVariable][${i}]"]`);
                let netProfit = document.querySelector(`input[name="rugi_laba[NetProfit][${i}]"]`);
                let ebitda = document.querySelector(`input[name="rugi_laba[Ebitda][${i}]"]`);
                let akumEbitda = document.querySelector(`input[name="rugi_laba[AkumEbitda][${i}]"]`);
                let roiTahunKe = document.querySelector(`input[name="rugi_laba[RoiTahunKe][${i}]"]`);

                // Contoh logika:
                // Revenue = (Jumlah Pasien x Tarif Umum)
                let valJumlahPasien = jumlahPasien ? parseRupiah(jumlahPasien.value) : 0;
                let valTarifUmum = tarifUmum ? parseRupiah(tarifUmum.value) : 0;
                let valTarifBpjs = tarifBpjs ? parseRupiah(tarifBpjs.value) : 0;
                let valRevenue = valJumlahPasien * valTarifUmum;
                if (revenue) {
                    revenue.value = valRevenue ? 'Rp ' + valRevenue.toLocaleString('id-ID') : '';
                }

                // NetProfit = Revenue - (Biaya Tetap + Biaya Variable)
                let valBiayaTetap = biayaTetap ? parseRupiah(biayaTetap.value) : 0;
                let valBiayaVariable = biayaVariable ? parseRupiah(biayaVariable.value) : 0;
                let valNetProfit = valRevenue - (valBiayaTetap + valBiayaVariable);
                if (netProfit) {
                    netProfit.value = valNetProfit ? 'Rp ' + valNetProfit.toLocaleString('id-ID') : '';
                }

                // EBITDA = NetProfit (bisa sesuaikan rumus jika ada komponen tambahan)
                if (ebitda) {
                    ebitda.value = valNetProfit ? 'Rp ' + valNetProfit.toLocaleString('id-ID') : '';
                }

                // Akumulasi EBITDA (AkumEbitda) = AkumEbitda sebelumnya + EBITDA tahun ini
                if (akumEbitda) {
                    let akumPrev = 0;
                    if (i > 1) {
                        let prev = document.querySelector(`input[name="rugi_laba[AkumEbitda][${i-1}]"]`);
                        akumPrev = prev ? parseRupiah(prev.value) : 0;
                    }
                    let valAkum = akumPrev + (ebitda ? parseRupiah(ebitda.value) : 0);
                    akumEbitda.value = valAkum ? 'Rp ' + valAkum.toLocaleString('id-ID') : '';
                }

                // ROI Tahun Ke = (Revenue/Investasi)
                let nilaiInvestasiEl = document.getElementById('NilaiInvestasi');
                let valInvestasi = nilaiInvestasiEl ? parseRupiah(nilaiInvestasiEl.value) : 1;
                if (roiTahunKe) {
                    let valRoi = valInvestasi ? Math.round((valRevenue / valInvestasi) * 100) : 0;
                    roiTahunKe.value = valRoi ? (valRoi + '%') : '';
                }
            }
        }
    </script>
@endpush
