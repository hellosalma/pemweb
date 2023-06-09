<div class="container-fluid">

    <h3 class="h3 text-gray-800 my-2"><?= $judul ?></h3>

    <div class="row">
        <div class="col-sm-6">

            <?php if ($this->session->flashdata('msg')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    Data berhasil <strong><?= $this->session->flashdata('msg'); ?></strong>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-8 col-sm-12">

                    <div class="card shadow">
                        <div class="card-header">
                            <button type="button" data-toggle="modal" data-target="#tambahRole" class="btn btn-primary btn-modal">Tambah Data</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($role as $num => $r) : ?>
                                        <tr>
                                            <td><?= $num + 1; ?></td>
                                            <td><?= $r['role']; ?></td>
                                            <td>
                                                <button type="button" data-toggle="modal" data-target="#ubahRole" data-id="<?= $r['id']; ?>" data-role="<?= $r['role']; ?>" class=" btn btn-sm btn-warning btn-modal btn-ubah">ubah</button>

                                                <a href="" data-toggle="modal" data-target="#modalHapus" class="btn btn-sm btn-danger btn-hapus" data-id="<?= $r['id']; ?>">hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header badge-primary">
                    <h5 class="modal-title">Hapus <?= $judul; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= base_url('pengaturan/role_hapus'); ?>" id="formHapusRole" method="post">
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahRole" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header badge-primary">
                    <h5 class="modal-title">Tambah Role Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= base_url('pengaturan/role_tambah') ?>" method="post" id="formTambahRole">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="role">Role</label>
                            <input type="text" name="role" id="role" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-tambah">Tambah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ubah -->
    <div class="modal fade" id="ubahRole" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header badge-primary">
                    <h5 class="modal-title">Ubah Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= base_url('pengaturan/role_ubah') ?>" method="post" id="formUbahRole">
                    <div class="modal-body">
                        <input type="hidden" name="id" class="id">
                        <div class="form-group">
                            <label for="role">Role</label>
                            <input type="text" name="role" class="form-control role">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-ubah">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
</div>

<script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/sweetalert/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {

        $(".btn-modal").click(function() {
            $('form:eq(1)')[0].reset();
            $(".is-valid").removeClass("is-valid");
            $(".is-invalid").removeClass("is-invalid");
            $(".invalid-feedback").empty();
        });

        $("#formTambahRole").submit(function(e) {
            e.preventDefault()
            var role = $("#role").val()

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                dataType: 'json',
                data: {
                    role: role
                },
                beforeSend: function() {
                    $("#btn-tambah").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>')
                },
                complete: function() {
                    $("#btn-tambah").html('Tambah Data')
                },
                success: function(res) {
                    if (res.status) {
                        $("#role").addClass("is-valid")

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
                    } else {
                        $.each(res.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid');
                            $('[name="' + key + '"]').next().text(value);
                        })
                    }
                }
            })

            $("#formTambahRole input").on('keyup', function() {
                $(this).removeClass('is-valid is-invalid')
            })
        })

        $(document).on("click", ".btn-ubah", function() {
            $(".modal-body .id").val($(this).data('id'));
            $(".modal-body .role").val($(this).data('role'));
        });

        $("#formUbahRole").submit(function(e) {
            e.preventDefault()

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
                        $(".role").addClass('is-valid')

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
                    } else {
                        $.each(res.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid')
                            $('[name="' + key + '"]').next().text(value)
                        })
                    }
                }
            })

            $("#formUbahRole input").on('keyup', function() {
                $(this).removeClass('is-valid is-invalid')
            })
        })

        $(document).on("click", ".btn-hapus", function() {
            $(".modal-body #id").val($(this).data('id'));
        });

        $("#formHapusRole").submit(function(e) {
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
                }
            });
        });

    })
</script>