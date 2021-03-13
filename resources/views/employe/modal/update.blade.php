<!-- Add Salary Modal -->
<div id="update-modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah Pegawai</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="update-form" method="POST" action="#" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="row"> 
                <h5 class="col-md-12 modal-title">Data Pribadi</h5>
                <div class="col-sm-6 form-group">
                  <label>Nama Lengkap</label>
                  <input class="form-control" type="text" name="fullname" id="fullname">
                  <input type="hidden" name="id" id="id">
                </div>

                <div class="col-sm-6 form-group">
                    <label>Tempat Lahir</label>
                    <input class="form-control" type="text" name="place_birth" id="place_birth">
                </div>

                <div class="col-sm-6 form-group">
                    <label>Tanggal Lahir</label>
                    <input class="form-control" type="text" name="date_birth" id="input-dob-update">
                </div>

                <div class="col-sm-6 form-group">
                    <label>Jenis Kelamin</label>
                    <select name="gender" id="input-gender-update" class="form-control">
                        <option value="-">Jenis Kelamin</option>
                        <option value="Pria">Pria</option>
                        <option value="Wanita">Wanita</option>
                    </select>
                </div>

                <div class="col-sm-6 form-group">
                    <label>Agama</label>
                    <select name="religion" id="input-religion-update" class="form-control">
                        <option value="-">Agama</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Budha">Budha</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Konghucu">Konghucu</option>
                    </select>
                </div>

                <div class="col-sm-6 form-group">
                    <label>Status Pernikahan</label>
                    <select name="mariage_status" id="input-mariage-update" class="form-control">
                        <option value="-">Status Pernikahan</option>
                        <option value="Menikah">Menikah</option>
                        <option value="Belum Menikah">Belum Menikah</option>
                        <option value="Cerai">Cerai</option>
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Golongan Darah</label>
                    <select name="blood_type" id="input-blood-update" class="form-control">
                        <option value="-">Golongan Darah</option>
                        <option value="A">A</option>
                        <option value="AB">AB</option>
                        <option value="B">B</option>
                        <option value="O">O</option>
                    </select>
                </div>
                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Kontak</h5>
                <div class="col-sm-6 form-group">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" id="email">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Nomor Telpon</label>
                    <input class="form-control" type="text" name="phone_number" id="phone_number">
                </div>

                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Data Alamat</h5>
                <div class=" col-md-6 form-group">
                    <label>Provinsi</label>
                    <select id="input-province-update" name="current_address_province"> 
                        <option> - Pilih Provinsi - </option>
                        @foreach($provinces as $province)
                        <option value="{{$province['name']}}" data-province-code="{{$province['code']}}">{{$province['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Kota</label>
                    <select id="input-city-update" name="current_address_city"> 
                        <option> - Pilih Kota - </option>
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Kecamatan</label>
                    <input class="form-control" type="text" name="current_address_kecamatan" id="current_address_kecamatan">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Kelurahan</label>
                    <input class="form-control" type="text" name="current_address_kelurahan" id="current_address_kelurahan">
                </div>
                <div class="col-sm-3 form-group">
                    <label>RT</label>
                    <input class="form-control" type="text" name="current_address_rt" id="current_address_rt">
                </div>
                <div class="col-sm-3 form-group">
                    <label>RW</label>
                    <input class="form-control" type="text" name="current_address_rw" id="current_address_rw">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Jalan</label>
                    <input class="form-control" type="text" name="current_address_street" id="current_address_street">
                </div>

                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Informasi Bank</h5>
                <div class="col-sm-6 form-group">
                    <label>Nama Bank</label>
                    <input class="form-control" type="text" name="bank_name" id="bank_name">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Nomor Rekening</label>
                    <input class="form-control" type="text" name="bank_account" id="bank_account">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Atas Nama No. Rekening</label>
                    <input class="form-control" type="text" name="owner_bank_number" id="owner_bank_number">
                </div>

                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Nomor Identitas</h5>
                <div class="col-sm-6 form-group">
                    <label>Jenis Identitas</label>
                    <select name="identity_type" class="form-control" id="input-identity-update">
                        <option value="-">Pilih Kartu Pengenal</option>
                        <option value="KTP">KTP</option>
                        <option value="SIM">SIM</option>
                        <option value="Passport">Passport</option>
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Nomor Identitas</label>
                    <input class="form-control" type="text" name="identity_card_number" id="identity_card_number">
                </div>

                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Data Orangtua</h5>
                <div class="col-sm-6 form-group">
                    <label>Nama Ayah</label>
                    <input class="form-control" type="text" name="father_name" id="father_name">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Nama Ibu</label>
                    <input class="form-control" type="text" name="mother_name" id="mother_name">
                </div>

                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Kontak Darurat</h5>
                <div class="col-sm-6 form-group">
                    <label>Nama</label>
                    <input class="form-control" type="text" name="emergency_name" id="emergency_name">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Nomor Telpon</label>
                    <input class="form-control" type="text" name="emergency_number" id="emergency_number">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Hubungan</label>
                    <select name="emergency_relation" id="input-relation-update">
                        <option value="-">Hubungan</option>
                        <option value="Keluarga">Keluarga</option>
                        <option value="Saudara">Saudara</option>
                        <option value="Kerabat">Kerabat</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Data Pekerjaan</h5>
                <div class="col-sm-6 form-group">
                    <label>Status</label>
                    <select name="employe_status" class="form-control" id="input-status-update">
                        <option value="-">Status</option>
                        <option value="Kontrak">Kontrak</option>
                        <option value="Tetap">Tetap</option>
                        <option value="Freelance">Freelance</option>
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Foto</label>
                    <input class="form-control" type="file" name="file">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Tanggal Terima</label>
                    <input class="form-control" type="text" name="joined_at" id="input-joined-update">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Tanggal Keluar</label>
                    <input class="form-control" type="text" name="resign_at" id="input-resign-update">
                </div>

                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Sosial Media</h5>
                <div class="col-sm-6 form-group">
                    <label>Twitter</label>
                    <input class="form-control" type="text" name="twitter" id="twitter">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Facebook</label>
                    <input class="form-control" type="text" name="facebook" id="facebook">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Instagram</label>
                    <input class="form-control" type="text" name="instagram" id="instagram">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Linkedin</label>
                    <input class="form-control" type="text" name="linkedin" id="linkedin">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Youtube</label>
                    <input class="form-control" type="text" name="youtube" id="youtube">
                </div>
            </div>
            <div class="submit-section">
                <div class="col-auto float-right ml-auto pb-2">
                    <button type="button" class="btn btn-close mr-2 btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary float-right loading" 
                    data-loading-text='<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...'>
                      Submit
                    </button>
                  </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- /Add Salary Modal -->