<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">

            <h3 class="h3 text-gray-800 mb-3">Daftar <?= $judul ?></h3>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-primary btn-modal" data-toggle="modal" data-target="#modalTambah">Tambah Data</button>
                        </div>
                        <div class="col my-auto">
                            <a href="<?= base_url('surat-masuk'); ?>" class="float-right btn btn-outline-dark "><i class="fa fa-reply"> Kembali</i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tbl-disposisi">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Detail</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($disposisi) : ?>
                                    <?php foreach ($disposisi as $num => $d) : ?>
                                        <tr>
                                            <td><?= $num + 1 ?></td>
                                            <td>
                                                <table class="table-borderless">
                                                    <tr>
                                                        <td>Jabatan</td>
                                                        <td>:</td>
                                                        <td><?= $d['jabatan']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sifat Surat</td>
                                                        <td>:</td>
                                                        <td><?= $d['sifat']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Batas Waktu</td>
                                                        <td>:</td>
                                                        <td>
                                                            <?php if ($d['batas_waktu'] == 0000 - 00 - 00) : ?> - <?php else : ?> <?= date('d/m/Y', strtotime($d['batas_waktu'])); ?> <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Perihal</td>
                                                        <td>:</td>
                                                        <td class="text-justify"><?= $d['isi']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Catatan</td>
                                                        <td>:</td>
                                                        <td><?= $d['catatan']; ?></td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>
                                                <button type="button" data-toggle="modal" data-target="#modalUbah" class="btn btn-success btn-ubah btn-modal" data-id="<?= $d['id']; ?>"><i class="fa fa-edit"></i> Edit</button>
                                                <button type="button" data-toggle="modal" data-target="#modalHapus" class="btn btn-danger btn-hapus" data-id="<?= $d['id']; ?>"><i class="fa fa-trash-alt"></i> Hapus</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                            </tbody>
                        <?php else : ?>
                            <tr>
                                <td colspan="7">
                                    <h5>Data tidak ditemukan!</h5>
                                </td>
                            </tr>

                        <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

</div>

<!-- Modal Boxes -->
<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header badge-primary">
                <h5 class="modal-title">Hapus <?= $judul; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('disposisi/hapus/'); ?>" id="formHapusDisposisi" method="post">
                <div class="modal-body">
                    Apakah anda yakin ingin menghapus data ini?
                    <input type="hidden" name="id" id="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-hapus">Yakin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header badge-primary">
                <h5 class="modal-title">Tambah <?= $judul; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <form action="<?= base_url('disposisi/tambah_data'); ?>" id="formTambahDisposisi" method="post">
                            <input type="hidden" name="sm_id" value="<?= $sm_id; ?>">
                            <div class="form-group">
                                <label for="jabatan_id">Tujuan</label>
                                <select name="jabatan_id" id="jabatan_id" class="form-control shadow-sm">
                                    <option value="">-- PILIH --</option>
                                    <?php foreach ($jabatan as $j) : ?>
                                        <option value="<?= $j['id'] ?>" <?= set_select('jabatan_id', $j['id']); ?>><?= $j['jabatan'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="isi">Isi Ringkas</label>
                                <textarea name="isi" id="isi" cols="30" rows="6" class="form-control"><?= set_value('isi') ?></textarea>
                            </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="batas_waktu">Batas Waktu</label>
                            <input type="date" name="batas_waktu" id="batas_waktu" class="form-control shadow-sm" value="<?= set_value('batas_waktu') ?>"></input>
                        </div>
                        <div class="form-group">
                            <label for="sifat_id">Sifat</label>
                            <select name="sifat_id" id="sifat_id" class="form-control shadow-sm">
                                <option value="">-- PILIH --</option>
                                <?php foreach ($sifat as $s) : ?>
                                    <option value="<?= $s['id'] ?>" <?= set_select('sifat_id',  $s['id']); ?>><?= $s['sifat'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea name="catatan" id="catatan" cols="30" rows="2" class="form-control shadow-sm"><?= set_value('catatan') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btn-tambah">Tambah Data</button>
            </div>
            </form>
            <!-- ./ form -->
        </div>
    </div>
</div>

<div class="modal fade" id="modalUbah" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header badge-primary">
                <h5 class="modal-title">Ubah <?= $judul; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <form action="<?= base_url('disposisi/ubah_data'); ?>" id="formUbahDisposisi" method="post">
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="sm_id" value="<?= $sm_id; ?>">
                            <div class="form-group">
                                <label for="ubah_jabatan_id">Tujuan</label>
                                <select name="jabatan_id" id="ubah_jabatan_id" class="form-control shadow-sm">
                                    <option value="">-- PILIH --</option>
                                    <?php foreach ($jabatan as $j) : ?>
                                        <option value="<?= $j['id'] ?>" <?= set_select('jabatan_id', $j['id']); ?>><?= $j['jabatan'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="isi">Isi Ringkas</label>
                                <textarea name="isi" id="isi" cols="30" rows="6" class="form-control"><?= set_value('isi') ?></textarea>
                            </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="batas_waktu">Batas Waktu</label>
                            <input type="date" name="batas_waktu" id="batas_waktu" class="form-control shadow-sm" value="<?= set_value('batas_waktu') ?>"></input>
                        </div>
                        <div class="form-group">
                            <label for="ubah_sifat_id">Sifat</label>
                            <select name="sifat_id" id="ubah_sifat_id" class="form-control shadow-sm">
                                <option value="">-- PILIH --</option>
                                <?php foreach ($sifat as $s) : ?>
                                    <option value="<?= $s['id'] ?>" <?= set_select('sifat_id',  $s['id']); ?>><?= $s['sifat'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea name="catatan" id="catatan" cols="30" rows="2" class="form-control shadow-sm"><?= set_value('catatan') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btn-ubah">Simpan Data</button>
            </div>
            </form>
            <!-- ./ form -->
        </div>
    </div>
</div>

<!-- JavaScript-->
<script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/sweetalert/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {
        $(".btn-modal").click(function() {
            $('form:eq(1)')[0].reset()
            $(".is-valid").removeClass("is-valid")
            $(".is-invalid").removeClass("is-invalid")
        });

        $(".btn-ubah").click(function() {
            var id = $(this).data('id')
            $(".modal-body #id").val(id)

            $.ajax({
                url: "<?= base_url('disposisi/get_disposisi_row') ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    id: id
                },
                success: function(data) {
                    var jabatan_id = data.jabatan_id
                    var sifat_id = data.sifat_id

                    $("#ubah_jabatan_id > option").each(function() {
                        if (jabatan_id == this.value) {
                            $(this).attr("selected", "selected")
                        }
                    })
                    $("#ubah_sifat_id > option").each(function() {
                        if (sifat_id == this.value) {
                            $(this).attr("selected", "selected")
                        }
                    })

                    $(".modal-body #isi").val(data.isi)
                    $(".modal-body #batas_waktu").val(data.batas_waktu)
                    $(".modal-body #catatan").val(data.catatan)

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Internal Error');
                }
            });
        })

        $("#formUbahDisposisi").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                dataType: 'json',
                data: $(this).serialize(),
                beforeSend: function() {
                    $("#btn-ubah").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>')
                },
                complete: function() {
                    $("#btn-ubah").html('Simpan Data')
                },
                success: function(res) {
                    if (res.status) {
                        $("#modalUbah").modal("toggle")

                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: res.msg,
                            showConfirmButton: false,
                            timer: 1000
                        })
                        setTimeout(function() {
                            location.reload()
                        }, 1000);
                    } else {
                        $.each(res.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid')
                            $('[name="' + key + '"]').next().text(value)
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid')
                                $('[name="' + key + '"]').addClass('is-valid')
                            }
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Internal Error');
                }
            })

            $("select").change(function() {
                $(this).removeClass('is-valid is-invalid')
            })
        })

        $("#formTambahDisposisi").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                dataType: 'json',
                data: $(this).serialize(),
                beforeSend: function() {
                    $("#btn-tambah").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>')
                },
                complete: function() {
                    $("#btn-tambah").html('Tambah Data')
                },
                success: function(res) {
                    if (res.status) {
                        $("#modalTambah").modal("toggle")

                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: res.msg,
                            showConfirmButton: false,
                            timer: 1000
                        })
                        setTimeout(function() {
                            location.reload()
                        }, 1000);
                    } else {
                        $.each(res.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid')
                            $('[name="' + key + '"]').next().text(value)
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid')
                                $('[name="' + key + '"]').addClass('is-valid')
                            }
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Internal Error');
                }
            })

            $("select").change(function() {
                $(this).removeClass('is-valid is-invalid')
            })
        })

        $(document).on("click", ".btn-hapus", function() {
            $(".modal-body #id").val($(this).data('id'))
        })

        $("#formHapusDisposisi").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                dataType: 'json',
                data: $(this).serialize(),
                beforeSend: function() {
                    $("#btn-hapus").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>')
                },
                complete: function() {
                    $("#btn-hapus").html('Yakin')
                },
                success: function(res) {
                    if (res.status) {
                        $("#modalHapus").modal("toggle")
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: res.msg,
                            showConfirmButton: false,
                            timer: 1000
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Internal Error');
                }
            });
        });
    })
</script>