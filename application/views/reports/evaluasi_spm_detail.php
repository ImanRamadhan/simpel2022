<?php $this->load->view("partial/header"); ?>
<section class="content-header">
	<h1>Evaluasi SPM - <?php echo $item_info->spm_tahun; ?> - <?php echo $city; ?></h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('home');?>"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Evaluasi</a></li>
		<li class="active">Evaluasi SPM - <?php echo $item_info->spm_tahun; ?> - <?php echo $city; ?></li>
	</ol>
</section>
<script type="text/javascript" src="js/manage_tables_2.js"></script>
<script type="text/javascript" src="js/manage_tables_3.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	
	
	
    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>

    table_support.init({
        resource: '<?php echo site_url('spms_standards');?>',
        headers: <?php echo $table_headers; ?>,
        pageSize: 100,
        uniqueId: 'sampel_id',
        queryParams: function() {
            return $.extend(arguments[0], {
                spm_id: '<?php echo $spm_id; ?>',
				category: '1',
				city: '<?php echo $city; ?>',
				filters: $("#filters1").val() || [""]
            });
        },
    });
	
	table_support2.init({
        resource: '<?php echo site_url('spms_standards');?>',
        headers: <?php echo $table_headers; ?>,
        pageSize: 100,
        uniqueId: 'sampel_id',
        queryParams: function() {
            return $.extend(arguments[0], {
                spm_id: '<?php echo $spm_id; ?>',
				category: '2',
				city: '<?php echo $city; ?>',
				filters: $("#filters2").val() || [""]
            });
        },
    });
	
	table_support3.init({
        resource: '<?php echo site_url('spms_standards');?>',
        headers: <?php echo $table_headers; ?>,
        pageSize: 100,
        uniqueId: 'sampel_id',
        queryParams: function() {
            return $.extend(arguments[0], {
                spm_id: '<?php echo $spm_id; ?>',
				category: '3',
				city: '<?php echo $city; ?>',
				filters: $("#filters3").val() || [""]
            });
        },
    });
});
</script>
<section class="content">
	
	<div class="container">
		<div class="stepwizard col-md-offset-3">
			<div class="stepwizard-row setup-panel">
			  <div class="stepwizard-step">
				<a href="<?php echo site_url('reports/evaluasi_spm_detail/'.$spm_id.'/'.$city.'/1'); ?>" type="button" class="btn <?php echo ($section == 1)?'btn-primary':'btn-default'; ?> btn-circle" >1</a>
				<p>Sarana dan Prasarana</p>
			  </div>
			  <div class="stepwizard-step">
				<a href="<?php echo site_url('reports/evaluasi_spm_detail/'.$spm_id.'/'.$city.'/2'); ?>" type="button" class="btn <?php echo ($section == 2)?'btn-primary':'btn-default'; ?> btn-circle" >2</a>
				<p>SDM Petugas Layanan</p>
			  </div>
			  <div class="stepwizard-step">
				<a href="<?php echo site_url('reports/evaluasi_spm_detail/'.$spm_id.'/'.$city.'/3'); ?>" type="button" class="btn <?php echo ($section == 3)?'btn-primary':'btn-default'; ?> btn-circle" >3</a>
				<p>Sistem, Prosedur dan Sarana Pendukung</p>
			  </div>
			  <div class="stepwizard-step">
				<a href="<?php echo site_url('reports/evaluasi_spm_detail/'.$spm_id.'/'.$city.'/4'); ?>" type="button" class="btn <?php echo ($section == 4)?'btn-primary':'btn-default'; ?> btn-circle" >4</a>
				<p>Lembar Masukan Observer</p>
			  </div>
			  <div class="stepwizard-step">
				<a href="<?php echo site_url('reports/evaluasi_spm_detail/'.$spm_id.'/'.$city.'/1'); ?>#" type="button" class="btn <?php echo ($complete)?'btn-primary':'btn-default'; ?> btn-circle" ><span class="glyphicon glyphicon-ok"></span></a>
				<p>Selesai</p>
			  </div>
			</div>
		</div>
	</div>
	
	<?php if($section == 1):?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Sarana dan Prasarana</h3>
			<div class="box-tools pull-right">
                
            </div>
		</div>
		<div class="box-body">
			<div id="title_bar" class="btn-toolbar print_hide">
				<?php if(is_admin()): ?>
				
				<?php endif; ?>
			</div>

			<div id="toolbar">
				<div class="pull-left" role="toolbar">
				
				</div>
			</div>

			<div id="table_holder">
				<table id="table"></table>
			</div>
			
		</div>
	</div>
	<?php endif; ?>
	
	<?php if($section == 2):?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">SDM Petugas Layanan</h3>
			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
            </div>
		</div>
		<div class="box-body">
			<div id="title_bar" class="btn-toolbar print_hide">
				<?php if(is_admin()): ?>
				
				<?php endif; ?>
			</div>

			<div id="toolbar2">
				<div class="pull-left" role="toolbar">
				   
				</div>
			</div>

			<div id="table_holder2">
				<table id="table2"></table>
			</div>
			
		</div>
	</div>
	<?php endif; ?>
	
	<?php if($section == 3):?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Sistem, Prosedur dan Sarana Pendukung</h3>
			<div class="box-tools pull-right">
                
            </div>
		</div>
		<div class="box-body">
			<div id="title_bar3" class="btn-toolbar print_hide">
				<?php if(is_admin()): ?>
				
				<?php endif; ?>
			</div>

			<div id="toolbar3">
				<div class="pull-left" role="toolbar">
				   
				</div>
			</div>

			<div id="table_holder3">
				<table id="table3"></table>
			</div>
			
		</div>
	</div>
	<?php endif; ?>
	
	<?php if($section == 4):?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Lembar Masukan Observer</h3>
			<div class="box-tools pull-right">
                
            </div>
		</div>
		<div class="box-body">
			
			<?php 
				$i = 1;
				foreach($interview_questions as $q): 
			?>
			<?php echo form_open($controller_name . '/save_wawancara/' . $spm_id . '/'. $q->w_id, array('id'=>'interview_form_'.$i, 'enctype'=>'multipart/form-data', 'class'=>'')); ?>
			  <div class="form-group">
				
				<p><?php echo $i.'. '.$q->pertanyaan?></p>
				<p>Jawaban:</p>
				<?php
					$jawaban = '';
					$disabled = '';
					if(is_evaluator())
					{
						$jawaban = $q->jawaban;
						$disabled = 'disabled';
					}
					
					if(is_admin())
					{
						$jawaban = $q->jawaban;
						$disabled = 'disabled';
					}
					?>
				<textarea class="form-control" name="jawaban" id="jawaban" rows="5" <?php echo $disabled; ?>><?php echo $jawaban;?></textarea>
			  </div>
			  <?php if(is_user()): ?>
				<div class="form-group">
					<p align="center">&nbsp;</p>
				</div>
				<?php endif; ?>
			</form>
			<hr />
			<?php 
				$i++;
				endforeach; 
			?>
				
		</div>
	</div>
	<?php endif; ?>
	
	<p align="center"><a href="<?php echo site_url('reports/evaluasi_spm?spm_id='.$spm_id);?>" class="btn btn-info">Kembali</a></p>
</section>
<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	
});
</script>
<?php $this->load->view("partial/footer"); ?>
