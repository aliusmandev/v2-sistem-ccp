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

{{-- Agar select2 search berjalan di dalam modal, pastikan jQuery & select2 sudah di-load, dan inisialisasi select2 dengan option dropdownParent di set ke modalnya --}}
@push('js')
    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.user-penilai-select').each(function() {
                    if ($(this).hasClass("select2-hidden-accessible")) {
                        $(this).select2('destroy');
                    }
                });
                $('.user-penilai-select').select2({
                    dropdownParent: $('#modalPenilai'),
                    width: '100%',
                    placeholder: "Pilih User",
                    allowClear: true,
                    minimumResultsForSearch: 0
                });
            } else {
                console.warn("Plugin select2 belum dimuat.");
            }

            let isSubmitting = false;

            function disableAllInteractions() {
                isSubmitting = true;
                $(document).on('contextmenu.loading', function(e) {
                    e.preventDefault();
                    return false;
                });
                $(document).on('keydown.loading', function(e) {
                    e.preventDefault();
                    return false;
                });
                $(document).on('mousewheel.loading DOMMouseScroll.loading', function(e) {
                    e.preventDefault();
                    return false;
                });
                $('body').css({
                    'user-select': 'none',
                    '-webkit-user-select': 'none',
                    '-moz-user-select': 'none',
                    '-ms-user-select': 'none'
                });
                if ($('#loading-overlay').length === 0) {
                    $('body').append(
                        '<div id="loading-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:999999;cursor:not-allowed;background:rgba(0,0,0,0.3);"></div>'
                    );
                }
            }

            function enableAllInteractions() {
                isSubmitting = false;
                $(document).off('.loading');
                $('body').css({
                    'user-select': '',
                    '-webkit-user-select': '',
                    '-moz-user-select': '',
                    '-ms-user-select': ''
                });
                $('#loading-overlay').remove();
            }

            // User: set email, jabatanid, departemenid when user changed
            $('.user-penilai-select').on('change', function() {
                var $select = $(this);
                var rowIndex = $select.data('row-index');
                if (rowIndex == 0) return;
                var selected = $select.find('option:selected');
                var email = selected.data('email') || '';
                var jabatanid = selected.data('jabatanid') || '';
                var departemenid = selected.data('departemenid') || '';

                $('input.email-penilai-input[data-row-index="' + rowIndex + '"]').val(email);
                $('select.jabatan-penilai-select[data-row-index="' + rowIndex + '"]').val(jabatanid)
                    .trigger('change');
                $('select.departemen-penilai-select[data-row-index="' + rowIndex + '"]').val(departemenid)
                    .trigger('change');
            });

            // On modal load, set email/jabatanid/departemenid if User selected
            $('.user-penilai-select').each(function() {
                var $select = $(this);
                var rowIndex = $select.data('row-index');
                if (rowIndex == 0) return;
                var selected = $select.find('option:selected');
                if (selected.length && selected.val() !== '') {
                    var email = selected.data('email') || '';
                    var jabatanid = selected.data('jabatanid') || '';
                    var departemenid = selected.data('departemenid') || '';
                    $('input.email-penilai-input[data-row-index="' + rowIndex + '"]').val(email);
                    $('select.jabatan-penilai-select[data-row-index="' + rowIndex + '"]').val(jabatanid);
                    $('select.departemen-penilai-select[data-row-index="' + rowIndex + '"]').val(
                        departemenid);
                }
            });

            // Bro, ini bagian submit: tampilkan sweetalert loading (proses ajax) saat submit
            $('#formPenilai').on('submit', function(e) {
                e.preventDefault();
                if (isSubmitting) return;

                const form = $(this);

                Swal.fire({
                    title: 'Konfirmasi Ajukan?',
                    text: 'Apakah Anda yakin data penilai sudah benar dan ingin mengirim email?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, ajukan!',
                    cancelButtonText: 'Batal',
                    allowOutsideClick: false
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    // Aktifkan disable interactions
                    disableAllInteractions();

                    // Tampilkan SweetAlert loading
                    let seconds = 0;
                    let timerInterval = null;
                    Swal.fire({
                        title: 'Mengirim Email...',
                        html: `
                            <div style="margin:20px 0">
                                <i class="fa fa-envelope fa-3x" style="color:#3085d6"></i>
                            </div>
                            Proses pengiriman email sedang berlangsung.<br>
                            <strong style="color:#d33">JANGAN tutup atau refresh halaman ini!</strong><br>
                            <small>Waktu tunggu: <b id="timer-counter">0</b> detik</small>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            timerInterval = setInterval(() => {
                                seconds++;
                                const counterEl = document.getElementById(
                                    'timer-counter');
                                if (counterEl) {
                                    counterEl.textContent = seconds;
                                }
                            }, 1000);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                        }
                    });

                    // Kirim AJAX request
                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        success: function(res) {
                            clearInterval(timerInterval);
                            enableAllInteractions();

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Email berhasil dikirim ke penilai.',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            clearInterval(timerInterval);
                            enableAllInteractions();

                            let errorMsg = 'Terjadi kesalahan saat mengirim email.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMsg,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                });
            });

            // Re-initialize select2 after modal open
            $('#modalPenilai').on('shown.bs.modal', function() {
                $('.user-penilai-select').each(function() {
                    if (!$(this).hasClass("select2-hidden-accessible")) {
                        $(this).select2({
                            dropdownParent: $('#modalPenilai'),
                            width: '100%',
                            placeholder: "Pilih User",
                            allowClear: true,
                            minimumResultsForSearch: 0
                        });
                    }
                });
            });
        });
    </script>
@endpush
