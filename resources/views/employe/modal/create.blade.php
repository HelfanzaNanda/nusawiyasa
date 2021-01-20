<!-- Add Salary Modal -->
<div id="add-modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Pegawai</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="add-form" method="POST" action="#">
            {!! csrf_field() !!}
            <div class="row"> 
                <h5 class="col-md-12 modal-title">Data Pribadi</h5>
                <div class="col-sm-6 form-group">
                  <label>Nama Lengkap</label>
                  <input class="form-control" type="text" name="fullname">
                </div>

                <div class="col-sm-6 form-group">
                    <label>Tempat Lahir</label>
                    <input class="form-control" type="text" name="place_birth">
                </div>

                <div class="col-sm-6 form-group">
                    <label>Tanggal Lahir</label>
                    <input class="form-control" type="text" name="date_birth" id="input-dob">
                </div>

                <div class="col-sm-6 form-group">
                    <label>Jenis Kelamin</label>
                    <select name="gender" id="input-gender" class="form-control">
                        <option value="-">Jenis Kelamin</option>
                        <option value="Pria">Pria</option>
                        <option value="Wanita">Wanita</option>
                    </select>
                </div>

                <div class="col-sm-6 form-group">
                    <label>Agama</label>
                    <select name="religion" id="input-religion" class="form-control">
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
                    <select name="mariage_status" id="input-mariage" class="form-control">
                        <option value="-">Status Pernikahan</option>
                        <option value="Menikah">Menikah</option>
                        <option value="Belum Menikah">Belum Menikah</option>
                        <option value="Cerai">Cerai</option>
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Golongan Darah</label>
                    <select name="blood_type" id="input-blood" class="form-control">
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
                    <input class="form-control" type="email" name="email">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Nomor Telpon</label>
                    <input class="form-control" type="text" name="phone_number">
                </div>

                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Data Alamat</h5>
                <div class=" col-md-6 form-group">
                    <label>Provinsi</label>
                    <select id="input-province" name="current_address_province"> 
                        <option> - Pilih Provinsi - </option>
                        @foreach($provinces as $province)
                        <option value="{{$province['name']}}" data-province-code="{{$province['code']}}">{{$province['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Kota</label>
                    <select id="input-city" name="current_address_city"> 
                        <option> - Pilih Kota - </option>
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Kecamatan</label>
                    <input class="form-control" type="text" name="current_address_kecamatan">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Kelurahan</label>
                    <input class="form-control" type="text" name="current_address_kelurahan">
                </div>
                <div class="col-sm-3 form-group">
                    <label>RT</label>
                    <input class="form-control" type="text" name="current_address_rt">
                </div>
                <div class="col-sm-3 form-group">
                    <label>RW</label>
                    <input class="form-control" type="text" name="current_address_rw">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Jalan</label>
                    <input class="form-control" type="text" name="current_address_street">
                </div>

                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Informasi Bank</h5>
                <div class="col-sm-6 form-group">
                    <label>Nama Bank</label>
                    <input class="form-control" type="text" name="bank_name">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Nomor Rekening</label>
                    <input class="form-control" type="text" name="bank_account">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Atas Nama No. Rekening</label>
                    <input class="form-control" type="text" name="owner_bank_number">
                </div>

                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Nomor Identitas</h5>
                <div class="col-sm-6 form-group">
                    <label>Jenis Identitas</label>
                    <select name="identity_type" class="form-control" id="input-identity">
                        <option value="-">Pilih Kartu Pengenal</option>
                        <option value="KTP">KTP</option>
                        <option value="SIM">SIM</option>
                        <option value="Passport">Passport</option>
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Nomor Identitas</label>
                    <input class="form-control" type="text" name="identity_card_number">
                </div>

                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Data Orangtua</h5>
                <div class="col-sm-6 form-group">
                    <label>Nama Ayah</label>
                    <input class="form-control" type="text" name="father_name">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Nama Ibu</label>
                    <input class="form-control" type="text" name="mother_name">
                </div>

                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Kontak Darurat</h5>
                <div class="col-sm-6 form-group">
                    <label>Nama</label>
                    <input class="form-control" type="text" name="emergency_name">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Nomor Telpon</label>
                    <input class="form-control" type="text" name="emergency_number">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Hubungan</label>
                    <select name="emergency_relation" id="input-relation">
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
                    <select name="employe_status" class="form-control" id="input-status">
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
                    <input class="form-control" type="text" name="joined_at" id="input-joined">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Tanggal Keluar</label>
                    <input class="form-control" type="text" name="resign_at" id="input-resign">
                </div>

                <div class="col-md-12"><hr></div>
                <h5 class="col-md-12 modal-title">Sosial Media</h5>
                <div class="col-sm-6 form-group">
                    <label>Twitter</label>
                    <input class="form-control" type="text" name="twitter">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Facebook</label>
                    <input class="form-control" type="text" name="facebook">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Instagram</label>
                    <input class="form-control" type="text" name="instagram">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Linkedin</label>
                    <input class="form-control" type="text" name="linkedin">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Youtube</label>
                    <input class="form-control" type="text" name="youtube">
                </div>
            </div>
            <div class="submit-section">
              <button class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- /Add Salary Modal -->