<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH AKTIFITAS REWARD SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-upload" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/action-aktivitas-reward-siswa/add') }}">
                        @csrf
                        <h2 class="card-inside-title">
                            Tanggal
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="date" class="datepicker form-control" name="tanggal" required="" id="tanggal"
                                    aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis Aktivitas Reward Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control" id="">
                                    <option selected disabled>Pilih Jenis</option>
                                    <option value="1">Harian</option>
                                    <option value="2">Mingguan</option>
                                    <option value="3">Bulanan</option>
                                    <option value="4">Insidentil</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Penilai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control" id="">
                                    <option selected disabled>Pilih Penilai</option>
                                    <option value="1">Guru</option>
                                    <option value="2">Pengurus Kelas</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Karakter yang ditanamkan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="checkbox" id="disiplin" name="disiplin" value="disiplin">
                                <label for="disiplin">Disiplin</label>
                                <br>
                                <input type="checkbox" id="religius" name="religius" value="religius">
                                <label for="religius">Religius</label>
                                <br>
                                <input type="checkbox" id="tangguh" name="tangguh" value="tangguh">
                                <label for="tangguh">Tangguh dan Tanggung Jawab</label>
                                <br>
                                <input type="checkbox" id="peduli" name="peduli" value="peduli">
                                <label for="peduli">Peduli</label>
                                <br>
                                <input type="checkbox" id="komunikasi" name="komunikasi" value="komunikasi">
                                <label for="komunikasi">Komunikasi</label>
                                <br>
                                <input type="checkbox" id="kolaborasi" name="kolaborasi" value="kolaborasi">
                                <label for="kolaborasi">Kolaborasi</label>
                                <br>
                                <input type="checkbox" id="kritis" name="kritis" value="kritis">
                                <label for="kritis">Kritis dan Pemecahan Masalah</label>
                                <br>
                                <input type="checkbox" id="kreatif" name="kreatif" value="kreatif">
                                <label for="kreatif">Kreatif dan Inovatif</label>
                                <br>
                                <input type="checkbox" id="jujur" name="jujur" value="jujur">
                                <label for="jujur">Kejujuran</label>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control" id="">
                                    <option selected disabled>Pilih Status</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Non Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>

                        {{-- <h2 class="card-inside-title">
                            Siswa yang bersangkutan ( Opsional )
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control selectpicker show-tick" data-show-subtext="true"
                                    data-live-search="true" name="id_siswa" data-size="5">
                                    <option value="" selected>-- Pilih Siswa --</option>
                                    @foreach ($all_siswa as $item)
                                        <option value="{{ $item->id_siswa }}">
                                            {{ $item->pengguna->nm_pengguna . ' ( ' . $item->kelas->nm_kelas . ' ) ' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Status
                        </h2>
                        <div class="demo-radio-button">
                            <input name="status" type="radio" value="1" id="target_1" />
                            <label for="target_1">Selesai</label>
                            <input name="status" type="radio" value="0" id="target_2" />
                            <label for="target_2">Belum Selesai</label>
                        </div>

                        <h2 class="card-inside-title">
                            File Pendukung ( pdf , pptx , docx , doc , xlsx , png , jpg , jpeg | max 5 mb )
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>
                                    <input type="file" class="form-control" name="file" />
                                </label>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Uraian Kegiatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea rows="4" cols="50" class="form-control" name="keterangan" required="" aria-required="true"
                                    aria-invalid="true"></textarea>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    $(document).ready(function () {
        $('#tanggal').on('change', function () {
            let tanggal = $(this).val();
            console.log(tanggal);
        });
    });
</script>


{{-- <script>
    $('#form-upload').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function(input) {
            $(input).parents('.form-group').addClass('error');
        },
        unhighlight: function(input) {
            $(input).parents('.form-group').removeClass('error');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                enctype: 'multipart/form-data',
                data: new FormData($('#form-upload')[0]),
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == 200) {
                        vex.dialog.alert(response.message);
                    } else if (response.status == 201) {
                        vex.dialog.alert(response.message);
                        window.location.href = response.link;
                    } else if (response.status == 202) {
                        vex.dialog.alert(response.message);
                        loadURI(response.path);
                    } else if (response.status == 203) {
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
                    } else if (response.status == 204) {
                        loadURI(response.path);
                    } else if (response.status == 300) {
                        vex.dialog.alert(response.message);
                    }
                },
                complete: function() {
                    $('input').removeAttr('readonly', 'readonly');
                    $('button').removeAttr('disabled');
                }
            });
        }
    });
</script> --}}
