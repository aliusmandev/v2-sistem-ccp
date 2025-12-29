<div class="modal fade" id="modalPenilai" tabindex="-1" aria-labelledby="modalPenilaiLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPenilaiLabel">Permintaan ini Akan dikirim Ke</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPenilai" method="POST" action="{{ route('pp.update', $data->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="IdPengajuan" value="{{ $data->id }}">
                <div class="modal-body">
                    <div class="alert alert-info mb-4" role="alert">
                        <strong>Perhatian:</strong>
                        <ol class="mb-0 ps-4">
                            <li>Pastikan memilih user, jabatan, dan departemendengan benar.</li>
                            <li>Email akan digunakan untuk pengiriman notifikasi ke pihak atau departemen
                                terkait.</li>
                        </ol>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle" style="width:100%;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:90px;">Urutan</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Jabatan</th>
                                    <th>Departemen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $approvalCount = count($approval);
                                    $userLogin = auth()->user();
                                @endphp
                                @forelse($approval as $key => $app)
                                    <tr>
                                        <td>Urutan {{ $key + 1 }}</td>
                                        <td>
                                            @if ($key == 0)
                                                <!-- Urutan 1 ambil dari session login, tidak readonly/disabled -->
                                                <select class="form-control select2 user-penilai-select" name="UserId[]"
                                                    style="width: 100%;" data-row-index="{{ $key }}">
                                                    @foreach ($user as $usr)
                                                        <option value="{{ $usr->id }}|{{ $usr->name }}"
                                                            data-email="{{ $usr->email }}"
                                                            data-jabatanid="{{ $usr->jabatan ?? '' }}"
                                                            data-departemenid="{{ $usr->departemen ?? '' }}"
                                                            {{ $userLogin->id == $usr->id ? 'selected' : '' }}>
                                                            {{ $usr->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select class="form-control select2 user-penilai-select" name="UserId[]"
                                                    style="width: 100%;" data-row-index="{{ $key }}">
                                                    <option value="">Pilih User</option>
                                                    @foreach ($user as $usr)
                                                        <option value="{{ $usr->id }}|{{ $usr->name }}"
                                                            data-email="{{ $usr->email }}"
                                                            data-jabatanid="{{ $usr->jabatan ?? '' }}"
                                                            data-departemenid="{{ $usr->departemen ?? '' }}"
                                                            {{ isset($app->UserId) && $app->UserId == $usr->id ? 'selected' : '' }}>
                                                            {{ $usr->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($key == 0)
                                                <input type="email" class="form-control email-penilai-input"
                                                    name="Email[]" value="{{ $userLogin->email }}"
                                                    data-row-index="{{ $key }}">
                                            @else
                                                <input type="email" class="form-control email-penilai-input"
                                                    name="Email[]"
                                                    value="{{ $app->Email ?? ($app->getUser->email ?? '') }}"
                                                    data-row-index="{{ $key }}">
                                            @endif
                                        </td>
                                        <td>
                                            @if ($key == 0)
                                                <select class="form-control select2 jabatan-penilai-select"
                                                    name="JabatanId[]" style="width: 100%;"
                                                    data-row-index="{{ $key }}">
                                                    @foreach ($jabatan as $jab)
                                                        <option value="{{ $jab->id }}"
                                                            {{ $userLogin->jabatan == $jab->id ? 'selected' : '' }}>
                                                            {{ $jab->Nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select class="form-control select2 jabatan-penilai-select"
                                                    name="JabatanId[]" style="width: 100%;"
                                                    data-row-index="{{ $key }}">
                                                    <option value="">Pilih Jabatan</option>
                                                    @foreach ($jabatan as $jab)
                                                        <option value="{{ $jab->id }}"
                                                            {{ isset($app->JabatanId) && $app->JabatanId == $jab->id ? 'selected' : '' }}>
                                                            {{ $jab->Nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($key == 0)
                                                <select class="form-control select2 departemen-penilai-select"
                                                    name="DepartemenId[]" style="width: 100%;"
                                                    data-row-index="{{ $key }}">
                                                    @foreach ($departemen as $dept)
                                                        <option value="{{ $dept->id }}"
                                                            {{ $userLogin->departemen == $dept->id ? 'selected' : '' }}>
                                                            {{ $dept->Nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select class="form-control select2 departemen-penilai-select"
                                                    name="DepartemenId[]" style="width: 100%;"
                                                    data-row-index="{{ $key }}">
                                                    <option value="">Pilih Departemen</option>
                                                    @foreach ($departemen as $dept)
                                                        <option value="{{ $dept->id }}"
                                                            {{ isset($app->DepartemenId) && $app->DepartemenId == $dept->id ? 'selected' : '' }}>
                                                            {{ $dept->Nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Tidak ada data
                                            penilai</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Tutup
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnKonfirmasiAjukan">
                        <i class="fa fa-paper-plane me-1"></i> Konfirmasi Ajukan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
