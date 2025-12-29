@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Permintaan Pembelian</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Permintaan Pembelian</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            <a class="btn btn-primary" href="{{ route('pp.create') }}">
                Tambah Permintaan Baru
            </a>


        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title">Daftar Permintaan Pembelian</h4>
                    <p class="card-text">
                        Tabel ini berisi semua data permintaan pembelian yang diajukan oleh departemen.
                    </p>
                </div>
                <div class="card-body">

                    <!-- Filter section -->
                    <div class="row mb-3">
                        {{-- <div class="col-md-4">
                            <label for="filter-perusahaan" class="form-label">Filter Perusahaan / RS</label>
                            <select class="form-select select2" id="filter-perusahaan">
                                <option value="">-- Semua Perusahaan --</option>
                                @foreach ($perusahaan as $item)
                                    <option value="{{ $item->Kode }}">{{ $item->Nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filter-jenis" class="form-label">Filter Jenis Permintaan</label>
                            <select class="form-select select2" id="filter-jenis">
                                <option value="">-- Semua Jenis --</option>
                                @foreach ($jenisPermintaan as $item)
                                    <option value="{{ $item->id }}">{{ $item->Nama }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        {{-- <div class="col-md-4">
                            <label for="filter-departemen" class="form-label">Filter Departemen</label>
                            <select class="form-select select2" id="filter-departemen">
                                <option value="">-- Semua Departemen --</option>
                                @foreach ($departemen as $item)
                                    <option value="{{ $item->id }}">{{ $item->Nama }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>

                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="permintaanTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nomor Permintaan</th>
                                    <th>Jenis Permintaan</th>
                                    <th>Departemen</th>
                                    <th>Tanggal</th>
                                    <th>Diajukan Oleh</th>
                                    <th>Diajukan Perusahaan</th>
                                    <th>Diajukan Pada</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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

    <script>
        $(document).ready(function() {
            // Hapus data
            $('body').on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Hapus Data?',
                    text: "Apakah Anda yakin ingin menghapus permintaan pembelian ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('pp.destroy', ':id') }}'.replace(':id', id),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status === 200) {
                                    Swal.fire('Dihapus!', response.message, 'success');
                                    $('#permintaanTable').DataTable().ajax.reload();
                                } else {
                                    Swal.fire('Gagal!', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON?.message ??
                                    'Terjadi kesalahan saat menghapus.', 'error');
                            }
                        });
                    }
                });
            });

            // Load DataTable
            function loadDataTable() {
                $('#permintaanTable').DataTable({
                    responsive: true,
                    serverSide: true,
                    processing: true,
                    bDestroy: true,
                    ajax: {
                        url: "{{ route('pp.index') }}",
                        data: function(d) {
                            d.perusahaan = $('#filter-perusahaan').val();
                            d.jenis = $('#filter-jenis').val();
                            d.departemen = $('#filter-departemen').val();
                        },
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Memuat...</span>',
                        paginate: {
                            next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                            previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'NomorPermintaan',
                            name: 'NomorPermintaan'
                        },
                        {
                            data: 'Jenis',
                            name: 'Jenis'
                        },
                        {
                            data: 'Departemen',
                            name: 'Departemen'
                        },
                        {
                            data: 'Tanggal',
                            name: 'Tanggal'
                        },
                        {
                            data: 'DiajukanOleh',
                            name: 'DiajukanOleh'
                        },
                        {
                            data: 'KodePerusahaan',
                            name: 'KodePerusahaan'
                        },
                        {
                            data: 'DiajukanPada',
                            name: 'DiajukanPada'
                        },
                        {
                            data: 'Status',
                            name: 'Status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            }

            // Trigger reload when filter changes
            $('#filter-perusahaan, #filter-jenis, #filter-departemen').on('change', function() {
                $('#permintaanTable').DataTable().ajax.reload();
            });

            loadDataTable();
        });
    </script>
@endpush
