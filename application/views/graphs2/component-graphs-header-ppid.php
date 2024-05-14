<div class="row form-horizontal">
	<div class="col-sm-12 col-lg-8 border-0">
		<div class="form-group form-group-sm row">
			<?php echo form_label('Pilih Grafik', 'label_kota', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-7'>
				<?php
				echo form_dropdown('grafik', $graphs, '', 'class="form-control form-control-sm" id="grafik"');?>				
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Pilih Tanggal', 'label_tgl', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-3'>
				<div class="form-group">
					<div class="input-group">
					<input type="text" id="tgl1" name="tgl1" class="form-control" placeholder="" autocomplete="off">
						<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					</div>
					</div>
				</div>
			</div>
			<div class='col-sm-1'>
				<?php echo form_label('s.d', 'label_kota', array('class'=>'required col-form-label')); ?>
			</div>
			<div class='col-sm-3'>
				<div class="form-group">
					<div class="input-group">
					<input type="text" id="tgl2" name="tgl2" class="form-control" placeholder="" autocomplete="off">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					</div>
					</div>
				</div>
			</div>	
		</div>
		<div id='filterkota'>
			<div class="form-group form-group-sm row" id="divKota">
				<?php echo form_label('Pilih Kota', 'label_kota', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<?php
					echo form_dropdown('kota', $cities, '', 'class="form-control form-control-sm" id="kota"');?>
				</div>
			</div>
		</div>
		
	
		<div class="form-group form-group-sm row">
			<div class='col-sm-12 text-center'>
				<button class="btn btn-primary btn-sm" id="process_btn"> <i class="fa fa-search"></i> Lihat Grafik</button>
			</div>
		</div>
		<script type="text/javascript">
		function bindingKota() {
			
            if(localStorage.getItem("grafik.grafik") == 7){
				var x = document.getElementById("divKota");
				x.style.visibility = "hidden";
			} else {
				var x = document.getElementById("divKota");
				x.style.visibility = "visible";
			}
        }
        //window.onload = bindingKota;

		function bindingKriteria_(){
			var grafikVal = document.getElementById("grafik").value;
			if(grafikVal == 7){
				var x = document.getElementById("divKota");
				x.style.visibility = "hidden";
			} else {
				var x = document.getElementById("divKota");
				x.style.visibility = "visible";
			}
		}
		function bindingKriteria(){

			var filterwaktulayanan = document.getElementById("filterwaktulayanan");
			filterwaktulayanan.style.display = "none";

			var filterkota = document.getElementById("filterkota");
			filterkota.style.display = "block";

			var grafikVal = document.getElementById("grafik").value;

			if((grafikVal == 7) || (grafikVal == 9)){
				filterkota.style.display = "none";
			}

			if(grafikVal == 11){
				filterwaktulayanan.style.display = "block";
			}

			if(grafikVal == 12){
				filterwaktulayanan.style.display = "block";

				var divdatasource = document.getElementById("divdatasource");
				divdatasource.style.display = "none";
			}
		}
		$(document).ready(function()
		{
			/*$('#klasifikasi_id').on('change', function(){
				var klasifikasi = $('#klasifikasi_id').val();
				
				$('#subklasifikasi_id').empty();
				$.each(subklasifikasi[klasifikasi], function (key, entry) {
					//console.log(entry);
					$('#subklasifikasi_id').append($('<option></option>').attr('value', entry.id).text(entry.value));
				});
			});*/
			

		});
		window.onload = function () {
			$('#grafik').selectedIndex = "1";
			$('#grafik').change();
			//bindingKota();
		}
		</script>
	</div>
</div>