<!-- Begin Page Content -->
<div class="container-fluid mb-5">

    <!-- Page Heading -->
    <h1 class="h3 text-gray-800"><?= $judul; ?></h1>

    <div class="row">
        <div class="col-12">

            <?php if ($this->session->flashdata('msg') || $this->session->flashdata('ubahpass')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $this->session->flashdata('msg'); ?> <?= $this->session->flashdata('ubahpass'); ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" alt="profile" class="card-img-top rounded mt-0 mb-3 mx-auto" width="320" height="240">
                    <h4 class="card-title text-center font-weight-bold"><?= $user['username']; ?></h4>
                    <h5 class="card-title text-center mb-0"><?= $user['email']; ?></h5>
                    <p?>
                        </p>
                        <button type="button" data-toggle="modal" data-target="#profileModal" class="btn btn-primary btn-block btn-modal" id="btn-ubah" data-id="<?= $user['id']; ?>">Update Profile</button>
                        <button type="button" data-toggle="modal" data-target="#passwordModal" class="btn btn-success btn-block btn-modal">Change Password</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="profileModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header badge-primary">
                    <h5 class="modal-title" id="profileModalTitle">Form Ubah <?= $judul; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?= form_open_multipart('user/update_profile', ['id' => 'formUserUpdate']); ?>
                <div class="modal-body pb-0">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" class="form-control shadow-sm" readonly>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control shadow-sm" placeholder="masukkan username">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label class="d-none d-md-block d-lg-none">Gambar</label>
                                <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" id="output_image" height="120" width="150" class="shadow-sm p-2 rounded-sm">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <label for="image">Upload gambar</label>
                            <input type="file" name="image" id="image" class="p-1 form-control" onchange="preview_image(event)">
                            <div class="invalid-feedback"></div>
                            <div class="alert-info mt-1 pb-1 rounded text-center">
                                <small>File dengan format .png, .jpg, .svg - maks. 512kb</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-update-user">Update</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="passwordModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header badge-primary">
                    <h5 class="modal-title" id="passwordModalTitle">Form Ubah Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?= form_open_multipart('user/update_password', ['id' => 'formUpdatePassword']); ?>
                <div class="modal-body pb-0">
                    <div class="form-group">
                        <label for="password">Password Lama</label>
                        <input type="password" name="password" id="password" class="form-control shadow-sm" value="<?= set_value('password'); ?>">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="newpass">Password Baru</label>
                        <input type="password" name="newpass" id="newpass" class="form-control" value="<?= set_value('newpass'); ?>">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="newpass2">Konfirmasi Password</label>
                        <input type="password" name="newpass2" id="newpass2" class="form-control shadow-sm">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-update-pass">Update</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>

<script>
    $(document).ready(function() {
        // Upload file (name)
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Preview Gambar
        function preview_image(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('output_image');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        $(".btn-modal").click(function() {
            $('form:eq(1)')[0].reset()
            $("#image").val(null)
            $(".is-valid").removeClass("is-valid")
            $(".is-invalid").removeClass("is-invalid")
        })

        $("#btn-ubah").click(function() {
            var id = $(this).data('id')

            $.ajax({
                url: "<?= base_url('user/get_user_data') ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data)
                    $(".modal-body #email").val(data.email)
                    $(".modal-body #username").val(data.username)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Internal Error');
                }
            });
        })

        $("#formUserUpdate").submit(function(e) {
            e.preventDefault()

            var userData = new FormData(this)
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                dataType: 'json',
                data: userData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('.btn-update-user').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>')
                },
                complete: function() {
                    $('.btn-update-user').html('Update')
                },
                success: function(res) {
                    if (res.status) {
                        location.reload()
                    } else {
                        $.each(res.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid');
                            $('[name="' + key + '"]').next().text(value);
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid');
                                $('[name="' + key + '"]').addClass('is-valid');
                            }
                        })
                    }
                },
                error: function() {

                }
            })

            $("#formUserUpdate input").on("keyup", function() {
                $(this).removeClass("is-valid is-invalid");
            })

        })

        $("#formUpdatePassword").submit(function(e) {
            e.preventDefault()

            var userData = new FormData(this)
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                dataType: 'json',
                data: userData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('.btn-update-pass').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>')
                },
                complete: function() {
                    $('.btn-update-pass').html('Update')
                },
                success: function(res) {
                    if (res.status) {
                        location.reload()
                    } else {
                        $.each(res.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid');
                            $('[name="' + key + '"]').next().text(value);
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid');
                                $('[name="' + key + '"]').addClass('is-valid');
                            }
                        })
                    }
                },
                error: function() {

                }
            })

            $("#formUpdatePassword input").on("keyup", function() {
                $(this).removeClass("is-valid is-invalid");
            })

        })
    })
</script>