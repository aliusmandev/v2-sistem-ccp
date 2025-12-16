          <div class="modal fade" id="modalPenilai" tabindex="-1" aria-labelledby="modalPenilaiLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="modalPenilaiLabel">Isi Data Penilai</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form id="formPenilai" method="POST" action="{{ route('htagpa.simpan-penilai') }}">
                          @csrf
                          <input type="hidden" name="IdPengajuan" value="{{ $data->id }}">
                          <input type="hidden" name="PengajuanItemId"
                              value="{{ $data->getPengajuanItem[0]->id ?? '' }}">
                          <input type="hidden" name="IdBarang"
                              value="{{ $data->getPengajuanItem[0]->IdBarang ?? '' }}">
                          <div class="modal-body">
                              <div class="alert alert-info mb-4" role="alert">
                                  <strong>Perhatian:</strong>
                                  <ol class="mb-0 ps-4">
                                      <li>Mohon isi nama penilai beserta gelarnya dengan benar.</li>
                                      <li>Mohon masukkan alamat email penilai dengan benar karena HTA akan diajukan
                                          melalui email tersebut.</li>
                                  </ol>
                              </div>
                              <div class="table-responsive">
                                  <table class="table align-middle" style="width:100%;">
                                      <thead class="table-light">
                                          <tr>
                                              <th style="width:90px;">Penilai</th>
                                              <th>Input Tipe</th>
                                              <th>Nama</th>
                                              <th>Email</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          @for ($i = 1; $i <= 5; $i++)
                                              @php
                                                  $defaultType = 'master';
                                                  $namaText = '';
                                                  $emailValue = isset($user[$i - 1]) ? $user[$i - 1]->email : '';
                                              @endphp
                                              <tr>
                                                  <td>Penilai {{ $i }}</td>
                                                  <td>
                                                      <select name="TipeInputPenilai[]"
                                                          class="form-select tipe-input-penilai"
                                                          data-penilai-index="{{ $i }}">
                                                          <option value="master"
                                                              @if ($defaultType == 'master') selected @endif>Dari
                                                              Data
                                                              Master</option>
                                                          <option value="manual"
                                                              @if ($defaultType == 'manual') selected @endif>Input
                                                              Manual
                                                          </option>
                                                      </select>
                                                  </td>
                                                  <td>
                                                      <div class="form-master-penilai"
                                                          data-penilai-index="{{ $i }}"
                                                          @if ($defaultType !== 'master') style="display:none;" @endif>
                                                          <select name="NamaPenilai[]"
                                                              class="form-select select2 penilai-select"
                                                              data-penilai-index="{{ $i }}">
                                                              <option value="" data-email="">Pilih Nama Penilai
                                                                  {{ $i }}</option>
                                                              @foreach ($user as $u)
                                                                  <option value="{{ $u->id }}"
                                                                      data-email="{{ $u->email }}">
                                                                      {{ $u->name }}
                                                                  </option>
                                                              @endforeach
                                                          </select>
                                                      </div>
                                                      <div class="form-manual-penilai"
                                                          data-penilai-index="{{ $i }}"
                                                          @if ($defaultType !== 'manual') style="display:none;" @endif>
                                                          <input type="text" name="NamaPenilaiManual[]"
                                                              class="form-control" value="{{ $namaText }}"
                                                              placeholder="Nama Penilai {{ $i }}">
                                                      </div>
                                                  </td>
                                                  <td>
                                                      <input type="email" name="EmailPenilai[]"
                                                          class="form-control email-penilai-input"
                                                          data-penilai-index="{{ $i }}"
                                                          value="{{ $emailValue }}"
                                                          placeholder="Email Penilai {{ $i }}">
                                                  </td>
                                              </tr>
                                          @endfor
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
          @push('js')
              <script>
                  $(document).ready(function() {
                      // Ganti tipe input antara master & manual
                      $('.tipe-input-penilai').on('change', function() {
                          var index = $(this).data('penilai-index');
                          var tipe = $(this).val();
                          let $row = $(this).closest('tr');
                          if (tipe === 'master') {
                              $row.find('.form-master-penilai[data-penilai-index="' + index + '"]').show();
                              $row.find('.form-manual-penilai[data-penilai-index="' + index + '"]').hide();
                              var email = $row.find('.penilai-select').find('option:selected').data('email') || '';
                              $row.find('input.email-penilai-input').val(email);
                          } else {
                              $row.find('.form-master-penilai[data-penilai-index="' + index + '"]').hide();
                              $row.find('.form-manual-penilai[data-penilai-index="' + index + '"]').show();
                              $row.find('input.email-penilai-input').val('');
                          }
                      });

                      // Sync email saat pilih dari master
                      $('.penilai-select').on('change', function() {
                          var index = $(this).attr('data-penilai-index');
                          var email = $(this).find('option:selected').data('email') || '';
                          $('input.email-penilai-input[data-penilai-index="' + index + '"]').val(email);
                      });

                      // Default: Jika master, set email otomatis sesuai pilihan nama penilai
                      $('.tipe-input-penilai').each(function() {
                          var $select = $(this);
                          if ($select.val() === 'master') {
                              var index = $select.data('penilai-index');
                              var $row = $select.closest('tr');
                              var email = $row.find('.penilai-select').find('option:selected').data('email') || '';
                              $row.find('input.email-penilai-input').val(email);
                          }
                      });

                      // SweetAlert konfirmasi submit
                      $('#formPenilai').on('submit', function(e) {
                          e.preventDefault();
                          Swal.fire({
                              title: 'Konfirmasi Ajukan?',
                              text: 'Apakah Anda yakin data penilai sudah benar dan ingin mengirim HTA ke email penilai?',
                              icon: 'question',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Ya, ajukan!',
                              cancelButtonText: 'Batal'
                          }).then((result) => {
                              if (result.isConfirmed) {
                                  this.submit();
                              }
                          });
                      });
                  });
              </script>
          @endpush
