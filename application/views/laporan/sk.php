<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <h3 class="h3 text-gray-800"><?= $judul; ?></h3>
            <div class="card shadow-sm my-3">
                <div class="card-body border-left-info rounded-sm">
                    <i class="fa fa-fw fa-info-circle fa-lg"></i> <strong> Silahkan pilih tanggal surat untuk menemukan surat keluar yang diinginkan.</strong>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="<?= base_url('laporan/cek_laporan_sk') ?>" id="laporan-sk" method="post">
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                        <label for="tgl_mulai">Dari Tanggal:</label>
                        <input type="date" name="tgl_mulai" class="form-control shadow-sm mb-1" id="tgl_mulai" value="<?= set_value('tgl_mulai'); ?>">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                        <label for="tgl_akhir">Sampai Tanggal: </label>
                        <input type="date" name="tgl_akhir" class="form-control shadow-sm mb-1" id="tgl_akhir" value="<?= set_value('tgl_akhir'); ?>">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                        <label for="difilter">Filter berdasarkan:</label>
                        <select name="difilter" class="form-control shadow-sm mb-1" id="difilter">
                            <option value="">-- Pilih --</option>
                            <option value="created_at" <?= set_select('difilter', 'created_at'); ?>>Tanggal Ditambah</option>
                            <option value="tgl_surat" <?= set_select('difilter', 'tgl_surat'); ?>>Tanggal Surat</option>
                            <option value="tgl_diterima" <?= set_select('difilter', 'tgl_diterima'); ?>>Tanggal Diterima</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-xl-1 col-lg-1 col-md-12 col-sm-12">
                        <button type="submit" name="btncek" class="btn btn-primary shadow-sm btn-block btn-cek float-left" style="margin-top: 2rem;">Cek</button>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                        <div id="view-export">
                            <button type="button" data-toggle="modal" data-target="#modalExport" class="btn btn-info shadow-sm btn-block" style="margin-top: 2rem;"><i class="fa fa-download"></i> Export</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Export -->
    <div class="modal fade" id="modalExport" tabindex="-1" role="dialog" aria-labelledby="modalTitleExport" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-gray-900">Pilih Format Laporan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <form action="<?= base_url('laporan/export_laporan_sk') ?>" method="post">
                        <input type="hidden" name="startdate" id="startdate">
                        <input type="hidden" name="enddate" id="enddate">
                        <input type="hidden" name="filterby" id="filterby">

                        <button type="submit" name="excel" class="btn btn-outline-success shadow-sm">Excel</button>
                        <button type="submit" name="pdf" class="btn btn-outline-danger shadow-sm">PDF</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="view-data">
        <div class="row mt-3">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tbl-laporan">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Detail Surat</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /. Container-fluid -->
</div>

<!-- JavaScript-->
<script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>

<script>
    $("#view-export").hide()
    $("#view-data").hide()

    $(document).ready(function() {

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd
        }
        if (mm < 10) {
            mm = '0' + mm
        }

        today = yyyy + '-' + mm + '-' + dd;
        document.getElementById("tgl_mulai").setAttribute("max", today);

        $("#laporan-sk").submit(function(e) {
            e.preventDefault()

            var startdate = $("#tgl_mulai").val()
            var enddate = $("#tgl_akhir").val()
            var filterby = $("#difilter").val()
            $("#startdate").val(startdate)
            $("#enddate").val(enddate)
            $("#filterby").val(filterby)

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                dataType: "json",
                data: $(this).serialize(),
                beforeSend: function() {
                    $(".btn-cek").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
                },
                complete: function() {
                    $(".btn-cek").html('Cek')
                },
                success: function(res) {
                    if (res.status) {
                        var content = '';
                        if (res.data.length > 0) {
                            $.each(res.data, function(key, val) {
                                content += '<tr><td>' + ++key + '</td>';
                                content += '<td><table class="table-borderless"><tr><td><b>No. Agenda</b></td><td>:</td><td>' + val.no_agenda + '</td>';
                                content += '</tr><tr><td><b>Pengirim</b></td><td>:</td><td>' + val.pengirim + '</td></tr>';
                                content += '<tr><td><b>No. Surat</b></td><td>:</td><td>' + val.no_surat + '</td></tr>';
                                content += '<tr><td><b>Isi Ringkas</b></td><td>:</td><td class="text-justify">' + val.isi + '</td></tr>';
                                content += '<tr><td><b>Keterangan</b></td><td>:</td><td class="text-justify">' + val.keterangan + '</td></tr>';
                                content += '<tr><td><b>File</b></td><td>:</td><td class="text-justify"><a href="<?= base_url("uploads/") ?>' + val.file + '" download>Download</a></td></tr>';
                                content += '</table></td></tr>';
                            })
                        } else {
                            content += '<tr><td colspan="5"><h3 class="text-center">Data belum ada.</h3></td></tr>';
                        }

                        $("table tbody").html(content)

                        $("#view-export").show()
                        $("#view-data").show()
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
                }
            })

            $("#laporan-sk input").on('change', function() {
                $(this).removeClass('is-valid is-invalid')
            });
            $("#laporan-sk select").on('change', function() {
                $(this).removeClass('is-valid is-invalid')
            })
        })
    })
</script>