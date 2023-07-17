<style>
    input {
        position: relative;
        width: 150px;
        height: 20px;
        color: white;
    }

    input:before {
        position: absolute;
        top: 3px;
        left: 3px;
        content: attr(data-date);
        display: inline-block;
        color: black;
    }

    input::-webkit-datetime-edit,
    input::-webkit-inner-spin-button,
    input::-webkit-clear-button {
        display: none;
    }

    input::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }
</style>
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>
                        Frequently Asked Questions
                    </h2>
                </div>
                <div class="body">
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                          <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                              <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Apabila muncul notifikasi gagal mengirimkan akun verifikasi, apa yang harus saya lakukan?
                              </button>
                            </h2>
                          </div>
                      
                          <div id="collapseOne" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body" style="padding: 3rem">
                                <p>Pastikan terlebih dahulu data yang didaftarkan :</p>
                                <ol>
                                    <li>Masukkan username dan password lalu klik login</li>
                                    <li>Apabila gagal untuk log in dan muncul “Sign in failed”</li>
                                    <li>Klik “lupa password” untuk mengubah password</li>
                                </ol>
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                              <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Kenapa belum mendapat email verifikasi?
                              </button>
                            </h2>
                          </div>
                          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body" style="padding: 3rem">
                                <p>
                                    Hal ini bisa disebabkan karena handphone atau device belum sync ke email. Bisa juga 
                                    terjadi karena kesalahan <i>input</i> alamat <i>email (typo)</i>, alamat <i>email</i> yang di cek dengan yang 
                                    didaftarkan berbeda, sehingga <i>email</i> tidak diterima.
                                </p>
                                <p>Apa yang bisa dilakukan :</p>
                                <ol>
                                    <li>Pastikan terlebih dahulu <i>email</i> yang didaftarkan tidak salah dalam penulisan</li>
                                    <li>Pastikan kembali sudah mengecek di seluruh <i>folder email</i> terdaftar, <i>inbox, promotion, 
                                        spam, junk,</i> dan <i>all mail</i></li>
                                    <li>Pastikan <i>Mark as Not Spam</i> bagi email yang masuk ke folder spam</li>
                                    <li>Pastikan maksimum pengiriman <i>email</i> verifikasi adalah 1 x 24 jam setelah mendaftar 
                                        di <i>website recruitment</i></li>
                                </ol>

                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" id="headingThree">
                            <h2 class="mb-0">
                              <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Bagaimana kalau sudah melebihi 1 x 24 jam belum mendapat email verifikasi ?
                              </button>
                            </h2>
                          </div>
                          <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                            <div class="card-body" style="padding: 3rem">
                                <ol>
                                    <li>
                                        Pelamar dapat melakukan pendaftaran ulang (re-registrasi) dengan menggunakan ID 
                                        KTP dan alamat <i>email</i> yang sama. Jangan sampai beda, karena sistem akan kembali 
                                        mengirimkan <i>email</i> verifikasi ke <i>email</i> yang didaftarkan.
                                    </li>
                                    <li>
                                        Setelah melakukan pendaftaran ulang (re-registrasi), cek secara berkala seluruh 
                                        folder <i>email, inbox, promotion, spam, junk,</i> dan <i>all mail</i>.
                                    </li>
                                </ol>
                                <p>
                                    Jika masih mengalami kendala yang sama setelah 1x24 jam, silakan hubungi tim diakad
                                </p>
                            </div>
                          </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingFour">
                              <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Bagaimana jika saya lupa password?
                                </button>
                              </h2>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                              <div class="card-body" style="padding: 3rem">
                                  <ol>
                                      <li>
                                        klik tombol lupa <i>password</i> di halaman login. Kemudian reset <i>password</i> akan dikirimkan 
                                        ke <i>email</i> yang didaftarkan
                                      </li>
                                  </ol>
                              </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree">
                              <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseThree">
                                    Mengapa saya selalu gagal melakukan lupa password?
                                </button>
                              </h2>
                            </div>
                            <div id="collapseFive" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                              <div class="card-body" style="padding: 3rem">
                                  <P>
                                    Kejadian seperti ini bisa terjadi karena proses penggantian <i>password</i> yang terputus, bisa 
                                    disebabkan karena jaringan yang tidak stabil atau session untuk akses <i>website</i> telah 
                                    berakhir. Hal-hal yang harus dilakukan:
                                  </P>
                                  <ol>
                                    <li>Pastikan telah melakukan “Lupa <i>Password</i>” dan memasukan <i>email</i> yang terdaftar di sistem.</li>
                                    <li>Cek email “<i>Reset Password</i>” di seluruh folder <i>email, inbox, spam, junk,</i> dan <i>all mail.</i></li>
                                    <li>Klik tombol “<i>Reset Password</i>” yang ada di email.</li>
                                    <li>Masukkan <i>password</i> baru sesuai dengan ketentuan (minimum 8 karakter, gabungan angka, huruf kecil, serta huruf kapital) dan ulangi <i>password</i>.</li>
                                    <li>Klik <i>“Reset”</i> sampai muncul notifikasi “<i>Password telah diubah</i>”.</li>
                                    <li>Lakukan <i>login</i> menggunakan <i>email</i> dan <i>password</i> yang telah diubah.</li>
                                  </ol>
                              </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree">
                              <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseThree">
                                    Email dan password salah. Bagaimana caranya agar dapat login kembali?
                                </button>
                              </h2>
                            </div>
                            <div id="collapseSix" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                              <div class="card-body" style="padding: 3rem">
                                  <P>
                                    Hal ini dapat disebabkan oleh 2 kondisi, yaitu <i>email</i> belum terdaftar atau salah 
                                    penulisan <i>email</i> dan password. Jika <i>email</i> belum terdaftar, silahkan
                                    melakukan <i>register</i>  terlebih dahulu. Jika akun lupa <i>password</i>, silahkan klik 
                                    tombol <b>Lupa Password</b> di halaman login. Kemudian <i>reset password</i> akan dikirimkan 
                                    ke <i>email</i> Anda
                                  </P>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
