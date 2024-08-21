 </div><!-- container -->

            <footer class="footer text-center text-sm-left">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                                © 2020 <span class="text-muted d-none d-sm-inline-block float-right"></span>
                        </div>
                    </div>
                </div>                    
            </footer>
        </div>
        <!-- end page-wrapper -->

		
        <script src="<?php echo base_url()?>assets/js/app.js?v=1.0"></script>
		<script type="text/javascript">
			$(document).ready(function()
			{
				<?php if($this->session->city == 'PUSAT'): ?>
				// $.getJSON('<?php echo site_url('dashboard/rujukan_keluar_replied');?>', function (data) {
				// 	//console.log(data);
				// 	$('#rujukan_keluar_replied').html(data);
				// });
				
				$.getJSON('<?php echo site_url('dashboard/rujukan_masuk_not_closed');?>', function (data) {
					//console.log(data);
					$('#rujukan_masuk_not_closed').html(data);
				});

				$.getJSON('<?php echo site_url('dashboard/rujukan_masuk_not_closed_menu');?>', function (data) {
					//console.log(data);
					$('#rujukan_masuk_not_closed_menu').html(data);
				});
				
				<?php elseif($this->session->city == 'UNIT TEKNIS'): ?>
				
				$.getJSON('<?php echo site_url('dashboard_unit/rujukan_masuk_not_answered');?>', function (data) {
					//console.log(data);
					//data = "";
					$('#rujukan_masuk_not_answered').html(data);
					//$('#rujukan_masuk_not_closed').html(data);
				});
				
				
				<?php else: ?>
				$.getJSON('<?php echo site_url('dashboard_balai/rujukan_masuk_not_answered');?>', function (data) {
					//console.log(data);
					//data = "";
					$('#rujukan_masuk_not_answered').html(data);
				});
				// $.getJSON('<?php echo site_url('dashboard_balai/rujukan_masuk_not_closed');?>', function (data) {
				// 	//console.log(data);
				// 	//data = "";
				// 	$('#rujukan_masuk_not_closed').html(data);
				// });
				<?php endif; ?>

				$.getJSON('<?php echo site_url('dashboard/ppid_need_action');?>', function (data) {
					$('#ppid_need_action').html(data);
				});
				
			});
		</script>
    </body>
</html>