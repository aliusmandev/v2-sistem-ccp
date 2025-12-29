@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header fw-bold">
            Update Profil User
        </div>
        <div class="card-body">
            <form action="{{ route('users.profile', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="name" class="form-label"><strong>Nama</strong></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            id="name" placeholder="Nama" value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label"><strong>Email</strong></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="email" placeholder="Email" value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label"><strong>Password</strong></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" placeholder="Biarkan kosong jika tidak ingin mengubah password">
                        @error('password')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    </div>



                    <div class="col-md-3">
                        <label for="departemen" class="form-label"><strong>Departemen</strong></label>
                        <select name="departemen" class="select2 form-control @error('departemen') is-invalid @enderror"
                            id="departemen">
                            <option value="">Pilih Departemen</option>
                            @foreach ($departemen as $dpt)
                                <option value="{{ $dpt->id }}"
                                    {{ old('departemen', $user->departemen) == $dpt->id ? 'selected' : '' }}>
                                    {{ $dpt->Nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('departemen')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="jabatan" class="form-label"><strong>Jabatan</strong></label>
                        <select name="jabatan" id="jabatan"
                            class="select2 form-control @error('jabatan') is-invalid @enderror"
                            style="background-color: #e9ecef; color: #495057;">
                            <option>Pilih Jabatan</option>
                            @foreach ($jabatan as $jbt)
                                <option value="{{ $jbt->id }}"
                                    {{ old('jabatan', $user->jabatan) == $jbt->id ? 'selected' : '' }}>
                                    {{ $jbt->Nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jabatan')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-4">
                            <label for="foto" class="form-label"><strong>Foto</strong> <span
                                    class="badge bg-secondary ms-1" style="font-weight:normal;">Opsional</span></label>
                            <div class="custom-upload-area border rounded-4 shadow-sm p-3 d-flex flex-column align-items-center justify-content-center mb-2 position-relative"
                                id="foto-drop-area"
                                style="min-height: 130px; cursor: pointer; background: linear-gradient(135deg, #f7fafc 40%, #f0f4ff 100%); transition: box-shadow .2s;"
                                onclick="document.getElementById('foto').click()" ondrop="handleDrop(event, 'foto')"
                                ondragover="event.preventDefault()" onmouseenter="this.style.boxShadow='0 0 8px #e3ebff'"
                                onmouseleave="this.style.boxShadow='none'">
                                <i class="fa fa-camera fa-2x mb-2 text-primary" aria-hidden="true"></i>
                                <span id="foto-preview-text" class="text-secondary" style="font-size: 1em;">
                                    <span class="fw-bold">Klik</span> atau <span class="fw-bold">drag & drop</span>
                                    file
                                    di sini
                                </span>
                                <img id="foto-preview" src="{{ $user->foto ? asset('uploads/foto/' . $user->foto) : '#' }}"
                                    alt="Preview Foto" class="rounded-circle border mt-2"
                                    style="{{ $user->foto ? 'display:block;' : 'display:none;' }} max-width: 100px; max-height: 100px; object-fit: cover;">
                            </div>
                            @if ($user->foto)
                                <small class="form-text text-muted ms-1">Foto saat ini ditampilkan di atas. Upload file
                                    baru jika ingin mengganti.</small>
                            @else
                                <small class="form-text text-muted ms-1">Gunakan foto wajah (jpg/png, max 2MB).</small>
                            @endif
                            <br>
                            <small class="text-muted ms-1"><em>Opsional: Anda tidak wajib mengunggah foto.</em></small>
                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror"
                                id="foto" style="display: none;" accept="image/*" onchange="previewFile('foto')">
                            @error('foto')
                                <div class="text-danger mt-1">
                                    <i class="fa fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="tandatangan" class="form-label"><strong>Tanda Tangan</strong> <span
                                    class="badge bg-secondary ms-1" style="font-weight:normal;">Opsional</span></label>
                            <div class="custom-upload-area border rounded-4 shadow-sm p-3 d-flex flex-column align-items-center justify-content-center mb-2 position-relative"
                                id="tandatangan-drop-area"
                                style="min-height: 130px; cursor: pointer; background: linear-gradient(135deg,#f0f6fa 40%,#eef5fb 100%); transition: box-shadow .2s;"
                                onclick="document.getElementById('tandatangan').click()"
                                ondrop="handleDrop(event, 'tandatangan')" ondragover="event.preventDefault()"
                                onmouseenter="this.style.boxShadow='0 0 8px #e3ebff'"
                                onmouseleave="this.style.boxShadow='none'">
                                <i class="fa fa-pen-fancy fa-2x mb-2 text-primary" aria-hidden="true"></i>
                                <span id="tandatangan-preview-text" class="text-secondary" style="font-size: 1em;">
                                    <span class="fw-bold">Klik</span> atau <span class="fw-bold">drag & drop</span>
                                    file di sini
                                </span>
                                <img id="tandatangan-preview"
                                    src="{{ $user->tandatangan ? asset('uploads/tandatangan/' . $user->tandatangan) : '#' }}"
                                    alt="Preview Tanda Tangan" class="rounded border mt-2"
                                    style="{{ $user->tandatangan ? 'display:block;' : 'display:none;' }} max-width: 140px; max-height: 60px; object-fit: contain;">
                            </div>
                            @if ($user->tandatangan)
                                <small class="form-text text-muted ms-1">Tanda tangan saat ini ditampilkan di atas.
                                    Upload file baru jika ingin mengganti.</small>
                            @else
                                <small class="form-text text-muted ms-1">Unggah tanda tangan sesuai dokumen resmi
                                    (jpg/png, max 2MB).</small>
                            @endif
                            <br>
                            <small class="text-muted ms-1"><em>Opsional: Anda tidak wajib mengunggah tanda
                                    tangan.</em></small>
                            <input type="file" name="tandatangan"
                                class="form-control @error('tandatangan') is-invalid @enderror" id="tandatangan"
                                style="display: none;" accept="image/*" onchange="previewFile('tandatangan')">
                            @error('tandatangan')
                                <div class="text-danger mt-1">
                                    <i class="fa fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-primary">Update Profil</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            window.previewFile = function(field) {
                var preview = document.getElementById(field + '-preview');
                var file = document.getElementById(field).files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onloadend = function() {
                        preview.src = reader.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            };

            window.handleDrop = function(event, field) {
                event.preventDefault();
                let dt = event.dataTransfer;
                let files = dt.files;
                document.getElementById(field).files = files;
                previewFile(field);
            };
        </script>
    @endpush
@endsection
