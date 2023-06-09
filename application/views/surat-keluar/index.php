<div class="container-fluid">

    <!-- Flash data -->
    <div class="flashdata" data-flashdata="<?= $this->session->flashdata('msg'); ?>"></div>

    <div class="row mt-4">
        <div class="col-12">

            <h3 class="judul h3 text-gray-800">Daftar <?= $judul ?></h3>

            <div class="card shadow mb-4">

                <?= form_open('sk/hapus_multiple', ['class' => 'formHapusSk']); ?>

                <div class="card-header py-3">
                    <a href="<?= base_url('surat-keluar/tambah'); ?>" class="btn btn-primary shadow-sm"><i class="fas fa-fw fa-plus"></i> Tambah Data</a>

                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash-alt"></i> Hapus Data</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped display nowrap" id="dataSk">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="centangSemua">
                                    </th>
                                    <th>No. Agenda</th>
                                    <th>Tujuan</th>
                                    <th>No. Surat</th>
                                    <th>Tanggal Surat</th>
                                    <th>File</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>

        </div>
    </div>

</div>

</div>

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>

<!-- Datatables -->
<script src="<?= base_url('assets/') ?>vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.2.7/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>

<!-- Sweet Alert 2 -->
<script src="<?= base_url('assets/') ?>vendor/sweetalert/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {
        tblSuratKeluar();

        function tblSuratKeluar() {
            $('#dataSk').DataTable({
                "responsive": true,
                "destroy": true,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?= base_url('sk/ambildata') ?>",
                    "type": "POST"
                },
                "scrollY": '270px',
                "columnDefs": [{
                    "targets": [0, 3, 4, 5],
                    "orderable": false,
                }],
            });
        }

        // checkbox selection - hapus
        $(".centangSemua").on("click", function(e) {
            if ($(this).is(":checked")) {
                $(".centangId").prop("checked", true)
            } else {
                $(".centangId").prop("checked", false)
            }
        });

        $(".formHapusSk").submit(function(e) {
            e.preventDefault();

            let jmlSk = $(".centangId:checked").length; // jumlah data yang terpilih (selected)
            if (jmlSk === 0) { // jika tidak ada data yang dipilih, maka
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Tidak ada data yang terpilih!',
                })
            } else {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: `anda akan menghapus ${jmlSk} data`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yakin!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: $(this).attr('method'),
                            url: $(this).attr('action'),
                            data: $(this).serialize(),
                            dataType: 'json',
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire(
                                        'Berhasil!',
                                        response.msg,
                                        'success'
                                    )
                                    tblSuratKeluar();
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
                            }

                        });
                    }
                })
            }
        });

        // Sweet Alert 2 x Flash Data
        var swal = $(".flashdata").data("flashdata");
        if (swal) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: `Data berhasil ${swal}`,
                showConfirmButton: false,
                timer: 1200
            })
        }
    })
</script>