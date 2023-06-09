<div class="container-fluid">

    <section>
        <div class="row">
            <div class="col-12">
                <h3 class="text-gray-900"><?= $judul; ?></h3>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <?= form_open_multipart('sm/ubah_sm', ['id' => 'formUbahSm']); ?>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <input type="hidden" name="id" value="<?= $surat['id']; ?>">
                                <div class="form-group">
                                    <label for="no_agenda">No. Agenda</label>
                                    <input type="text" name="no_agenda" id="no_agenda" class="form-control" value="<?= set_value('no_agenda') ? set_value('no_agenda') : $surat['no_agenda']; ?>">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group">
                                    <label for="pengirim">Pengirim</label>
                                    <input type="text" name="pengirim" id="pengirim" class="form-control" value="<?= set_value('pengirim') ? set_value('pengirim') : $surat['pengirim']; ?>">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group">
                                    <label for="no_surat">No. Surat</label>
                                    <input type="text" name="no_surat" id="no_surat" class="form-control" value="<?= set_value('no_surat') ? set_value('no_surat') : $surat['no_surat']; ?>">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group">
                                    <label for="isi">Isi Ringkas</label>
                                    <textarea name="isi" id="isi" cols="30" rows="5" class="form-control"><?= set_value('isi') ? set_value('isi') : $surat['isi']; ?></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="tgl_surat">Tanggal Surat</label>
                                    <input type="date" name="tgl_surat" id="tgl_surat" class="form-control" value="<?= set_value('tgl_surat') ? set_value('tgl_surat') : $surat['tgl_surat']; ?>">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group">
                                    <label for="tgl_diterima">Tanggal Diterima</label>
                                    <input type="date" name="tgl_diterima" id="tgl_diterima" class="form-control" value="<?= set_value('tgl_diterima') ? set_value('tgl_diterima') : $surat['tgl_diterima']; ?>">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label> <sup>*opsional</sup>
                                    <input type="text" name="keterangan" id="keterangan" class="form-control" value="<?= set_value('keterangan') ? set_value('keterangan') : $surat['keterangan']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="file">Upload File</label>
                                    <input type="file" name="file" id="file" class="form-control p-1">
                                    <div class="invalid-feedback"></div>
                                    <small class="text-sm text-info">Format file yang diizinkan .PDF dan ukuran maks 5 MB!</small>
                                </div>
                                <input type="hidden" name="user_id" value="<?= $user['role_id']; ?>">
                                <button type="submit" id="btn-ubah" class="btn btn-primary btn-block">Simpan Data</button>
                            </div>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>

<script>
    $(document).ready(function() {

        $("#formUbahSm").submit(function(e) {
            e.preventDefault()

            var data = new FormData(this)

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                dataType: "json",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#btn-ubah").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>')
                },
                complete: function() {
                    $("#btn-ubah").html('Simpan Data')
                },
                success: function(res) {
                    if (res.status == false) {
                        $.each(res.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid');
                            $('[name="' + key + '"]').next().text(value);
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid');
                                $('[name="' + key + '"]').addClass('is-valid');
                            }
                        })
                    } else {
                        window.location.href = "<?= base_url('surat-masuk') ?>";
                    }
                }
            })

            $("#formUbahSm input").on('keyup', function() {
                $(this).removeClass('is-valid is-invalid')
            })
        })

    })
</script>