<?php $this->load->view("partial/header"); ?>
<script src="<?php echo base_url() ?>assets/js/jquery.fileDownload.js"></script>
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Lapsing</a></li>
                    <li class="breadcrumb-item active">Lapsing Yanblik</li>
                </ol>
            </div>
            <h4 class="page-title"></h4>
        </div>
    </div>
</div>

<script type="text/javascript">
    var xhr = null;
    window.onbeforeunload = onUnload;

    function onUnload() {
        if (xhr) xhr.abort();
        return "some message";
    };

    $(document).ready(function() {
        $('#buttonExport').on('click', function() {
            $.fileDownload('<?php echo site_url('excels/download_lapsing_komoditas') ?>', {

                data: {
                    formType: '<?php echo $lapsing_type; ?>',
                    tgl1: moment($("#tgl1").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    tgl2: moment($("#tgl2").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    kota: $("#kota").val(),
                    formType: $("#formType").val(),
                    kategori: $("#kategori").val(),
                    gender: $("#gender").val(),
                    <?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
                },
                httpMethod: 'POST',
                success: function(Result) {},
                error: function(request, status, error) {
                    //alert('error');
                    // alert(request.responseText);
                }
            });
        });

        $('#process_btn').on('click', function(e) {
            $.remember({
                name: 'lapsing.tgl1',
                value: $('#tgl1').val()
            })
            $.remember({
                name: 'lapsing.tgl2',
                value: $('#tgl2').val()
            })

            $.ajax({
                url: '<?php echo site_url('Lapsing/lapsing_data') ?>',
                data: {
                    formType: '<?php echo $lapsing_type; ?>',
                    tgl1: moment($("#tgl1").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    tgl2: moment($("#tgl2").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    kota: $("#kota").val(),
                    direktorat: $("#direktorat").val(),
                    type: "1",
                    kategori: $("#kategori").val(),
                    gender: $("#gender").val(),
                    <?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
                },
                type: 'POST',
                success: function(Result) {
                    var DataChart = JSON.parse(Result);
                    $("#tblTaskListJenisPengaduan").empty();
                    createTable(DataChart, "tblTaskListJenisPengaduan");
                },
                error: function(request, status, error) {
                    //alert('error');
                    //alert(request.responseText);
                }
            });

            $.ajax({
                url: '<?php echo site_url('Lapsing/lapsing_data') ?>',
                data: {
                    formType: '<?php echo $lapsing_type; ?>',
                    tgl1: moment($("#tgl1").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    tgl2: moment($("#tgl2").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    kota: $("#kota").val(),
                    direktorat: $("#direktorat").val(),
                    type: "2",
                    kategori: $("#kategori").val(),

                    gender: $("#gender").val(),
                    <?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
                },
                type: 'POST',
                success: function(Result) {
                    var DataChart = JSON.parse(Result);
                    $("#tblTaskListMekanismeMenjawab").empty();
                    createTable(DataChart, "tblTaskListMekanismeMenjawab");
                },
                error: function(request, status, error) {
                    //alert('error');
                    //alert(request.responseText);
                }
            });

            $.ajax({
                url: '<?php echo site_url('Lapsing/lapsing_data') ?>',
                data: {
                    formType: '<?php echo $lapsing_type; ?>',
                    tgl1: moment($("#tgl1").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    tgl2: moment($("#tgl2").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    kota: $("#kota").val(),
                    direktorat: $("#direktorat").val(),
                    type: "3",
                    kategori: $("#kategori").val(),

                    gender: $("#gender").val(),
                    <?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
                },
                type: 'POST',
                success: function(Result) {
                    var DataChart = JSON.parse(Result);
                    $("#tblTaskListJenisProfesiPengadu").empty();
                    createTable(DataChart, "tblTaskListJenisProfesiPengadu");
                },
                error: function(request, status, error) {
                    //alert('error');
                    //alert(request.responseText);
                }
            });

            $.ajax({
                url: '<?php echo site_url('Lapsing/lapsing_data') ?>',
                data: {
                    formType: '<?php echo $lapsing_type; ?>',
                    tgl1: moment($("#tgl1").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    tgl2: moment($("#tgl2").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    kota: $("#kota").val(),
                    direktorat: $("#direktorat").val(),
                    type: "4",
                    kategori: $("#kategori").val(),

                    gender: $("#gender").val(),
                    <?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
                },
                type: 'POST',
                success: function(Result) {
                    var DataChart = JSON.parse(Result);
                    $("#tblTaskListKelInfoProduk").empty();
                    createTable(DataChart, "tblTaskListKelInfoProduk");
                },
                error: function(request, status, error) {
                    //alert('error');
                    // alert(request.responseText);
                }
            });

            $.ajax({
                url: '<?php echo site_url('Lapsing/lapsing_data') ?>',
                data: {
                    formType: '<?php echo $lapsing_type; ?>',
                    tgl1: moment($("#tgl1").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    tgl2: moment($("#tgl2").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    kota: $("#kota").val(),
                    direktorat: $("#direktorat").val(),
                    type: "7",
                    kategori: $("#kategori").val(),

                    gender: $("#gender").val(),
                    <?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
                },
                type: 'POST',
                success: function(Result) {
                    var DataChart = JSON.parse(Result);
                    $("#tblTaskListKelLegalitas").empty();
                    createTable(DataChart, "tblTaskListKelLegalitas");
                },
                error: function(request, status, error) {
                    //alert('error');
                    // alert(request.responseText);
                }
            });

            /*$.ajax({
            url: '<?php echo site_url('Lapsing/lapsing_data') ?>',
            data: {
                formType : '<?php echo $lapsing_type; ?>',
                tgl1: moment($("#tgl1").val(),"DD/MM/YYYY").format("YYYY-MM-DD"),
                tgl2: moment($("#tgl2").val(),"DD/MM/YYYY").format("YYYY-MM-DD"),
                kota: $("#kota").val(),
                type: "8",
                kategori: $("#kategori").val(),
                
                gender: $("#gender").val(),
				<?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
            },
            type: 'POST',
            success: function (Result) {
                var DataChart = JSON.parse(Result);
                $("#tblTaskListKelPenandaan").empty();
                createTable(DataChart, "tblTaskListKelPenandaan");
            },
            error: function (request, status, error) {
                //alert('error');
                //alert(request.responseText);
            }
        });*/

            $.ajax({
                url: '<?php echo site_url('Lapsing/lapsing_data') ?>',
                data: {
                    formType: '<?php echo $lapsing_type; ?>',
                    tgl1: moment($("#tgl1").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    tgl2: moment($("#tgl2").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
                    kota: $("#kota").val(),
                    direktorat: $("#direktorat").val(),
                    type: "10",
                    kategori: $("#kategori").val(),

                    gender: $("#gender").val(),
                    <?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
                },
                type: 'POST',
                success: function(Result) {
                    var DataChart = JSON.parse(Result);
                    $("#tblTaskListKelInfoUmum").empty();
                    createTable(DataChart, "tblTaskListKelInfoUmum");
                },
                error: function(request, status, error) {
                    //alert('error');
                    //alert(request.responseText);
                }
            });
        });
        if ($.remember({
                name: 'lapsing.tgl1'
            }) != null) {
            $('#tgl1').val($.remember({
                name: 'lapsing.tgl1'
            }));
        } else {
            $("#tgl1").val("<?php echo date('01/m/Y'); ?>");
        }

        if ($.remember({
                name: 'lapsing.tgl2'
            }) != null) {
            $('#tgl2').val($.remember({
                name: 'lapsing.tgl2'
            }));
        } else {
            $("#tgl2").val("<?php echo date('d/m/Y'); ?>");
        }

    });

    function createTable(dataChart, tableName) {
        var table = document.getElementById(tableName);
        console.log(table);
        //Create Header
        //Header
        var row = table.insertRow(-1);

        var cell_start_header = 0;
        for ($header_loop = 0; $header_loop < dataChart[0]['header'].length; $header_loop++) {
            var cell_sub_header_month = row.insertCell(cell_start_header);
            cell_sub_header_month.style.backgroundColor = dataChart[0]['header'][$header_loop]['background'];
            cell_sub_header_month.style.color = "black";
            cell_sub_header_month.style.textAlign = "center";
            cell_sub_header_month.innerHTML = dataChart[0]['header'][$header_loop]['name'].bold();

            cell_start_header++;
        }

        //data
        datas = dataChart[1]['data'];
        for ($i = 0; $i < datas.length; $i++) {
            var row = table.insertRow(-1);

            for (x = 0; x < datas[$i].length; x++) {
                var cell_nama = row.insertCell(x);
                cell_nama.style.backgroundColor = "#F0EC73";
                cell_nama.style.color = "black";

                if (x == 0) {
                    cell_nama.style.textAlign = "left";
                } else {
                    cell_nama.style.textAlign = "center";
                }

                cell_nama.innerHTML = datas[$i][x];
            }
        }

        //Jumlah
        datas = dataChart[2]['jumlah'];
        for ($i = 0; $i < datas.length; $i++) {
            var row = table.insertRow(-1);

            for (x = 0; x < datas[$i].length; x++) {
                var cell_nama = row.insertCell(x);
                cell_nama.style.backgroundColor = "#F0EC73";
                cell_nama.style.color = "black";

                if (x == 0) {
                    cell_nama.style.textAlign = "left";
                } else {
                    cell_nama.style.textAlign = "center";
                }

                cell_nama.innerHTML = datas[$i][x];
            }
        }
    }
</script>


<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header bg-primary text-white">
                <?php echo $title; ?>
            </div>
            <div class="card-body">
                <div id="title_bar" class="btn-toolbar float-right">
                </div>
                <?php
                if ($lapsing_type == "LAPSING") {
                    $this->load->view("lapsing/component-laporan-header");
                } else if ($lapsing_type == "GENDER") {
                    $this->load->view("lapsing/component-laporan-gender-header");
                } else {
                    $this->load->view("lapsing/component-laporan-komoditas-header");
                }
                ?>
                <br />
                <div id="table_holder">
                    <div class="card-header bg-success text-white">
                        Kelompok Jenis Pengaduan
                    </div>
                    <table id="tblTaskListJenisPengaduan" class="table table-bordered table-striped table-hovered">
                        <thead>
                        </thead>
                    </table>

                    <div class="card-header bg-success text-white">
                        Kelompok Mekanisme Menjawab
                    </div>
                    <table id="tblTaskListMekanismeMenjawab" class="table table-bordered table-striped table-hovered">
                        <thead>
                        </thead>
                    </table>

                    <div class="card-header bg-success text-white">
                        Jenis Profesi Pengadu/Konsumen
                    </div>
                    <table id="tblTaskListJenisProfesiPengadu" class="table table-bordered table-striped table-hovered">
                        <thead>
                        </thead>
                    </table>

                    <div class="card-header bg-success text-white">
                        Kelompok Informasi Produk
                    </div>
                    <table id="tblTaskListKelInfoProduk" class="table table-bordered table-striped table-hovered">
                        <thead>
                        </thead>
                    </table>

                    <div class="card-header bg-success text-white">
                        Kelompok Legalitas
                    </div>
                    <table id="tblTaskListKelLegalitas" class="table table-bordered table-striped table-hovered">
                        <thead>
                        </thead>
                    </table>
                    <!--
                    <div class="card-header bg-success text-white">
                        Kelompok Penandaan
                    </div>
                    <table id="tblTaskListKelPenandaan" class="table table-bordered table-striped table-hovered">
                        <thead>
                        </thead>
                    </table>
					-->

                    <div class="card-header bg-success text-white">
                        Kelompok Info Umum
                    </div>
                    <table id="tblTaskListKelInfoUmum" class="table table-bordered table-striped table-hovered">
                        <thead>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#kota').on('change', function() {
            $.remember({
                name: 'lapsing.kota',
                value: $('#kota').val()
            })

            if ($(this).val() == '') {
                $('#direktorat').empty();
                return;
            }

            $.getJSON("<?php echo site_url('lapsingv2/get_direktorat/'); ?>" + $(this).val(), function(data) {
                if (data) {
                    $('#direktorat').empty();
                    $('#direktorat').append($('<option></option>').attr('value', '').text('ALL'));
                    $.each(data, function(key, entry) {
                        $('#direktorat').append($('<option></option>').attr('value', entry.id).text(entry.name));
                    })
                }
            });
        });

        if ($.remember({
                name: 'lapsing.kota'
            }) != null)
            $('#kota').val($.remember({
                name: 'lapsing.kota'
            }));

        $.extend($.fn.datepicker.defaults, {
            format: 'dd/mm/yyyy',
            language: 'id',
            daysOfWeekHighlighted: [0, 6],
            todayHighlight: true,
            weekStart: 1
        });
        $("#tgl1").datepicker({
            zIndexOffset: '1001',
            orientation: 'bottom'
        });
        $("#tgl2").datepicker({
            zIndexOffset: '1001',
            orientation: 'bottom'
        });


    });
</script>
<?php $this->load->view("partial/footer"); ?>