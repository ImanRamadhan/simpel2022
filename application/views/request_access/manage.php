<?php $this->load->view("partial/header"); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Layanan</a></li>
                    <li class="breadcrumb-item active">Request Access Login</li>
                </ol>
            </div>
            <h4 class="page-title">Request Access Login</h4>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">

        <div class="card ">
            <div class="card-header bg-primary text-white">
                Request Access Login
            </div>
            <div class="card-body">
                <?php if (is_administrator()): ?>
                    <a href="<?php echo site_url("request_access/create"); ?>" class="btn btn-info btn-sm btn-round"><i class="fa fa-plus"></i> Tambah Request </a>
                <?php endif; ?>
                <div id="table_holder">
                    <table id="table" class="table table-striped w-100"></table>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() {

        <?php $this->load->view('partial/bootstrap_tables_locale'); ?>

        table_support.init({
            resource: '<?php echo site_url($controller_name); ?>',
            headers: <?php echo $table_headers; ?>,
            pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
            uniqueId: 'id',
            cookie: true,
            checbox : false,
            cookieIdTable: 'requestLoginTable',
            sortName: 'name',
            sortOrder: 'asc',
            queryParams: function() {
                return $.extend(arguments[0], {});
            },
        });
        $(document).on('click','.data-btn-approve',function(event){
            event.preventDefault();
            console.log("test");
            var  id = $(this).data('id')
            if(confirm('yakin ingin menerima permintaan akses login data tersebut?')){
                updateRequest(id,1);
            }
        })
        $(document).on('click','.data-btn-reject',function(event){
            event.preventDefault();
            console.log("test");
            var  id = $(this).data('id')
            if(confirm('yakin ingin menonaktifkan/menolak permintaan akses login data tersebut?')){
                updateRequest(id,2);
            }
        })
        function updateRequest(id,type){
            $.ajax({
                url : "<?php echo site_url('request_access/save')?>",
                data : $.extend(csrf_form_base(), {
                    id : id,
                    type : type
                }),
                dataType : 'json',
                method : "POST",
                success : function(response){
                    $.notify( response.message, { type: response.success ? 'success' : 'danger' });
                    table_support.refresh()
                }
            })
        }
    });
 
</script>

<?php $this->load->view("partial/footer"); ?>