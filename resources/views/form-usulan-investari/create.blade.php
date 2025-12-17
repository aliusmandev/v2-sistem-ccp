@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Form Usulan Investasi</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Usulan Investasi</a></li>
                    <li class="breadcrumb-item active">Buat Usulan Investasi</li>
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
                <form action="{{ route('usulan-investasi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $data->id }}" name="IdPengajuan">
                    <input type="hidden" value="{{ $PengajuanItemId }}" name="PengajuanItemId">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <h5 class="fw-bold mb-1">Departemen Peminta</h5>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold">Tanggal</label>
                                        <input type="date" class="form-control" name="Tanggal"
                                            value="{{ old('Tanggal') }}">
                                        @error('Tanggal')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold">Departemen</label>
                                        <select class="form-select select2" name="Divisi">
                                            <option value="">-- Pilih Departemen --</option>
                                            @foreach ($departemen as $d)
                                                <option value="{{ $d->id }}"
                                                    {{ old('Divisi') == $d->id || (!old('Divisi') && isset($data->DepartemenId) && $data->DepartemenId == $d->id) ? 'selected' : '' }}>
                                                    {{ $d->Nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('Divisi')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold">Nama Kepala Divisi</label>
                                        <select class="form-select select2" name="NamaKadiv">
                                            <option value="">-- Pilih Kepala Divisi --</option>
                                            @foreach ($user as $u)
                                                <option value="{{ $u->id }}"
                                                    {{ old('NamaKadiv') == $u->id ? 'selected' : '' }}>{{ $u->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('NamaKadiv')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold">Kategori</label>
                                        <select name="Kategori" class="form-select">
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="Pembelian Baru"
                                                {{ old('Kategori2', $data->Tujuan ?? '') == 'Pembelian Baru' ? 'selected' : '' }}>
                                                Pembelian Baru
                                            </option>
                                            <option value="Penggantian"
                                                {{ old('Kategori2', $data->Tujuan ?? '') == 'Penggantian' ? 'selected' : '' }}>
                                                Penggantian
                                            </option>
                                            <option value="Perbaikan"
                                                {{ old('Kategori2', $data->Tujuan ?? '') == 'Perbaikan' ? 'selected' : '' }}>
                                                Perbaikan
                                            </option>
                                        </select>
                                        @error('Kategori')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <h5 class="fw-bold mb-1">Departemen Pembelian</h5>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold">Tanggal</label>
                                        <input type="date" class="form-control" name="Tanggal2"
                                            value="{{ old('Tanggal2') }}">
                                        @error('Tanggal2')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold">Departemen</label>
                                        <select class="form-select select2" name="Divisi2">
                                            <option value="">-- Pilih Departemen --</option>
                                            @foreach ($departemen as $d)
                                                <option value="{{ $d->id }}"
                                                    {{ old('Divisi') == $d->id || (!old('Divisi') && isset($data->DepartemenId) && $data->DepartemenId == $d->id) ? 'selected' : '' }}>
                                                    {{ $d->Nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('Divisi2')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold">Nama Kepala Divisi</label>
                                        <select class="form-select select2" name="NamaKadiv2">
                                            <option value="">-- Pilih Kepala Divisi --</option>
                                            @foreach ($user as $u)
                                                <option value="{{ $u->id }}"
                                                    {{ old('NamaKadiv2') == $u->id ? 'selected' : '' }}>
                                                    {{ $u->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('NamaKadiv2')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold">Kategori</label>
                                        <select name="Kategori2" class="form-select">
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="Pembelian Baru"
                                                {{ old('Kategori2', $data->Tujuan ?? '') == 'Pembelian Baru' ? 'selected' : '' }}>
                                                Pembelian Baru
                                            </option>
                                            <option value="Penggantian"
                                                {{ old('Kategori2', $data->Tujuan ?? '') == 'Penggantian' ? 'selected' : '' }}>
                                                Penggantian
                                            </option>
                                            <option value="Perbaikan"
                                                {{ old('Kategori2', $data->Tujuan ?? '') == 'Perbaikan' ? 'selected' : '' }}>
                                                Perbaikan
                                            </option>
                                        </select>
                                        @error('Kategori2')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="Keterangan" class="form-label fw-bold">Dengan ini kami ajukan permohonan untuk
                                pengadaan barang / jasa dengan alasan sebagai berikut :</label>
                            <textarea class="form-control" name="Alasan" id="Keterangan" rows="3"
                                placeholder="Masukkan keterangan tambahan di sini...">{{ old('Alasan') }}</textarea>
                            @error('Alasan')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">List Item dari Vendor yang di-ACC</label>
                            <div class="table-responsive">
                                <table class="table align-middle" width="100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:5%">No</th>
                                            <th>Nama Barang</th>
                                            <th>Vendor</th>
                                            <th width="10%">Jumlah</th>
                                            <th>Harga</th>
                                            <th>Diskon</th>
                                            <th>PPN</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $grandTotal = 0;

                                            // Helper untuk format rupiah
                                            function rupiah($angka)
                                            {
                                                return 'Rp ' . number_format($angka, 0, ',', '.');
                                            }
                                            // Helper JS: format input ke rupiah
                                        @endphp
                                        @forelse ($data->getVendor as $key => $rekom)
                                            @php
                                                $jumlah = old(
                                                    'items.' . $key . '.Jumlah',
                                                    $rekom->getVendorDetail[0]->Jumlah ?? 0,
                                                );
                                                $harga = old(
                                                    'items.' . $key . '.Harga',
                                                    $rekom->getVendorDetail[0]->HargaSatuan ?? 0,
                                                );
                                                $diskon = old(
                                                    'items.' . $key . '.Diskon',
                                                    $rekom->getVendorDetail[0]->TotalDiskon ?? 0,
                                                );
                                                $ppn = old('items.' . $key . '.Ppn', $rekom->Ppn ?? 0);

                                                // Konversi kembali string bertitik/berkoma ke numerik untuk kalkulasi
                                                $harga_num = preg_replace('/[^\d]/', '', $harga);
                                                $harga_num = $harga_num !== '' ? (int) $harga_num : 0;

                                                $diskon_num = preg_replace('/[^\d]/', '', $diskon);
                                                $diskon_num = $diskon_num !== '' ? (int) $diskon_num : 0;

                                                $jumlah_num = is_numeric($jumlah) ? $jumlah : 0;
                                                $ppn_num = is_numeric($ppn) ? $ppn : 0;

                                                $subtotal = $jumlah_num * $harga_num - $diskon_num;
                                                $totalPpn = $subtotal * ($ppn_num / 100);
                                                $total = $subtotal + $totalPpn;
                                                $grandTotal += $total;
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <select class="form-select select2"
                                                        name="items[{{ $key }}][NamaBarang]"
                                                        style="width: 100%;" data-placeholder="Pilih barang">
                                                        @foreach ($barang as $b)
                                                            <option value="{{ $b->id }}"
                                                                {{ old('items.' . $key . '.NamaBarang', $rekom->getVendorDetail[0]->NamaBarang ?? '') == $b->Nama ? 'selected' : '' }}>
                                                                {{ $b->Nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    @error('items.' . $key . '.NamaBarang')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <select class="form-select select2"
                                                        name="items[{{ $key }}][Vendor]" style="width: 100%;"
                                                        data-placeholder="Pilih Vendor">

                                                        <option value="{{ $rekom->getNamaVendor->id }}"
                                                            {{ old('items.' . $key . '.Vendor', $rekom->getNamaVendor[0]->id ?? '') == $rekom->getNamaVendor->id ? 'selected' : '' }}>
                                                            {{ $rekom->getNamaVendor->Nama }}
                                                        </option>
                                                    </select>
                                                    @error('items.' . $key . '.Vendor')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" name="items[{{ $key }}][Jumlah]"
                                                        class="form-control" placeholder="Masukkan jumlah..."
                                                        value="{{ $jumlah }}">
                                                    @error('items.' . $key . '.Jumlah')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" name="items[{{ $key }}][Harga]"
                                                            class="form-control rupiah-input"
                                                            placeholder="Masukkan harga..."
                                                            value="{{ old('items.' . $key . '.Harga', isset($harga) ? number_format((int) preg_replace('/[^\d]/', '', $harga), 0, ',', '.') : 0) }}">
                                                    </div>
                                                    @error('items.' . $key . '.Harga')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" name="items[{{ $key }}][Diskon]"
                                                            class="form-control rupiah-input"
                                                            placeholder="Masukkan diskon..."
                                                            value="{{ old('items.' . $key . '.Diskon', isset($diskon) ? number_format((int) preg_replace('/[^\d]/', '', $diskon), 0, ',', '.') : 0) }}">
                                                    </div>
                                                    @error('items.' . $key . '.Diskon')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01"
                                                            name="items[{{ $key }}][Ppn]" class="form-control"
                                                            placeholder="Masukkan PPN..." value="{{ $ppn }}">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <small class="text-muted">{{ rupiah($totalPpn) }}</small>
                                                    @error('items.' . $key . '.Ppn')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" name="items[{{ $key }}][Total]"
                                                            class="form-control" placeholder="Total otomatis"
                                                            value="{{ number_format($total, 0, ',', '.') }}" readonly>
                                                    </div>
                                                    @error('items.' . $key . '.Total')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Belum ada item.</td>
                                            </tr>
                                        @endforelse
                                        <tr>
                                            <td colspan="6" class="text-end fw-bold">Grand Total</td>
                                            <td>
                                                <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            function formatRupiah(angka) {
                                                angka = angka.replace(/[^,\d]/g, '');
                                                var split = angka.split(',');
                                                var sisa = split[0].length % 3,
                                                    rupiah = split[0].substr(0, sisa),
                                                    ribuan = split[0].substr(sisa).match(/\d{3}/g);

                                                if (ribuan) {
                                                    rupiah += (sisa ? '.' : '') + ribuan.join('.');
                                                }
                                                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                                                return rupiah;
                                            }
                                            document.querySelectorAll('.rupiah-input').forEach(function(input) {
                                                input.addEventListener('input', function(e) {
                                                    var caret = input.selectionStart;
                                                    var value = input.value;
                                                    var cleanValue = value.replace(/[^,\d]/g, '');
                                                    var formatted = formatRupiah(cleanValue);
                                                    input.value = formatted;
                                                });
                                            });
                                        });
                                    </script>
                                </table>
                                @if ($errors->has('items'))
                                    <div class="text-danger mt-1">{{ $errors->first('items') }}</div>
                                @endif
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
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" name="BiayaAkhir" class="form-control rupiah"
                                                        value="{{ old('BiayaAkhir', isset($data2->getVendor[0]->getVendorDetail[0]->HargaSatuan) ? number_format($data2->getVendor[0]->getVendorDetail[0]->HargaSatuan, 0, ',', '.') : '') }}">
                                                </div>
                                                @error('BiayaAkhir')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="hidden" name="VendorDipilih"
                                                    value="{{ old('VendorDipilih', $data2->getVendor[0]->getNamaVendor->id ?? '') }}">
                                                <span>{{ $data2->getVendor[0]->getNamaVendor->Nama ?? '' }}</span>
                                                @error('VendorDipilih')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" name="HargaDiskonPpn"
                                                        class="form-control rupiah"
                                                        value="{{ old('HargaDiskonPpn', isset($data2->getVendor[0]->getVendorDetail[0]->HargaSatuan) ? number_format($data2->getVendor[0]->getVendorDetail[0]->HargaSatuan, 0, ',', '.') : '') }}">
                                                </div>
                                                @error('HargaDiskonPpn')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" name="Total" class="form-control rupiah"
                                                        value="{{ old('Total', isset($data2->getVendor[0]->getVendorDetail[0]->TotalHarga) ? number_format($data2->getVendor[0]->getVendorDetail[0]->TotalHarga, 0, ',', '.') : '') }}">
                                                </div>
                                                @error('Total')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
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
                                        <h5 class="fw-bold mb-2">Verifikasi RKAP <span
                                                class="fw-normal">(Departemen)</span>
                                        </h5>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Sudah masuk RKAP dari departemen ybs:</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="SudahRkap"
                                                        id="rkapYaDepartemen" value="Y"
                                                        {{ old('SudahRkap') == 'Y' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="rkapYaDepartemen">Ya</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="SudahRkap"
                                                        id="rkapTidakDepartemen" value="N"
                                                        {{ old('SudahRkap') == 'N' ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="rkapTidakDepartemen">Tidak</label>
                                                </div>
                                            </div>
                                            @error('SudahRkap')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold" for="sisaBudgetRKAPDepartemen">Sisa Budget
                                                dari
                                                RKAP untuk tahun ini yang masih dapat dipergunakan:</label>
                                            <input type="text" class="form-control rupiah"
                                                id="sisaBudgetRKAPDepartemen" name="SisaBudget"
                                                placeholder="Masukkan sisa budget RKAP" value="{{ old('SisaBudget') }}">
                                            @error('SisaBudget')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
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
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="SudahRkap2"
                                                        id="rkapYaKeuangan" value="Y"
                                                        {{ old('SudahRkap2') == 'Y' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="rkapYaKeuangan">Ya</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="SudahRkap2"
                                                        id="rkapTidakKeuangan" value="N"
                                                        {{ old('SudahRkap2') == 'N' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="rkapTidakKeuangan">Tidak</label>
                                                </div>
                                            </div>
                                            @error('SudahRkap2')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold" for="sisaBudgetRKAPKeuangan">Sisa Budget
                                                dari
                                                RKAP untuk tahun ini yang masih dapat dipergunakan:</label>
                                            <input type="text" class="form-control rupiah" id="sisaBudgetRKAPKeuangan"
                                                name="SisaBudget2" placeholder="Masukkan sisa budget RKAP"
                                                value="{{ old('SisaBudget2') }}">
                                            @error('SisaBudget2')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end mt-4">
                            <a href="{{ route('pp.show', encrypt($data->id)) }}" class="btn btn-secondary me-2">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                        </div>
                    </div>
                </form>

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
            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    var separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix === undefined ? rupiah : (rupiah ? prefix + ' ' + rupiah : '');
            }

            document.querySelectorAll('.rupiah').forEach(function(input) {
                input.addEventListener('input', function(e) {
                    let caret = this.selectionStart;
                    let value = this.value;
                    let oldLength = value.length;
                    let formatted = formatRupiah(value, 'Rp');
                    this.value = formatted;
                    let newLength = formatted.length;
                    this.setSelectionRange(caret + (newLength - oldLength), caret + (newLength -
                        oldLength));
                });
            });
        });
    </script>
@endpush
