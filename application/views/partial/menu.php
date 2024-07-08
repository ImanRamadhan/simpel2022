<ul class="navigation-menu">

	<li class="">
		<a href="<?php echo site_url('home'); ?>">
			<i class="mdi mdi-view-dashboard"></i>
			Dashboard
		</a>

	</li>

	<li class="has-submenu">
		<a href="#"><i class="mdi mdi-buffer"></i>Layanan</a>
		<ul class="submenu">
			<li><a href="<?php echo site_url('tickets/list_all') ?>">Layanan</a></li>
			<!--<li><a href="<?php echo site_url('tickets/list_yanblik') ?>">Layanan Publik</a></li>-->
			<li><a href="<?php echo site_url('tickets/list_my') ?>">Layanan Saya</a></li>

			<?php if (is_verificator()) : ?>
				<li><a href="<?php echo site_url('tickets/list_verifikasi') ?>">Layanan Verifikasi Saya</a></li>
			<?php endif; ?>

			<?php if (is_allowed('can_create_tickets')) : ?>
				<li><a href="<?php echo site_url('drafts') ?>">Drafts Saya</a></li>
				<li class="dropdown-divider"></li>
				<li><a href="<?php echo site_url('drafts/create') ?>">Tambah Layanan</a></li>
				<li><a href="<?php echo site_url('drafts/uploadxls') ?>">Tambah Data via Upload</a></li>
			<?php endif; ?>
		</ul>
	</li>

	<li class="has-submenu">
		<a href="#"><i class="mdi mdi-bullseye"></i>Rujukan</a><span class="badge badge-danger badge-pill noti-icon-badge" id="rujukan_keluar_replied">0</span>
		<ul class="submenu ">
			<li class="">

				<a href="<?php echo site_url('rujukan/list_masuk'); ?>">Rujukan Masuk &nbsp;<i class="badge badge-danger badge-pill text-white" id="rujukan_masuk_not_closed"></i></a>

			</li>

			<li class=""><a href="<?php echo site_url('rujukan/list_keluar') ?>">Rujukan Keluar </a></li>
			<li class=""><a href="<?php echo site_url('rujukan/list_keluar_saya') ?>">Rujukan Keluar Saya</a></li>
			<li class=""><a href="<?php echo site_url('rujukan/list_status_closed') ?>">Rujukan Status Closed</a></li>

			<li class="">
				<a href="<?php echo site_url('rujukan/list_status_tl') ?>">Rujukan Status TL Pengaduan <i class="badge badge-danger badge-pill text-white"></i>
			</li></a>
			<li class=""><a href="<?php echo site_url('rujukan/list_status_fb') ?>">Rujukan Status Feedback</a></li>

		</ul>
	</li>

	<li class="has-submenu">
		<a href="#"><i class="mdi mdi-buffer"></i>Lapsing</a>
		<ul class="submenu megamenu">
			<li>
				<ul>

					<li><a href="<?php echo site_url('lapsingv2/lapsing') ?>">Lapsing</a></li>
					<li><a href="<?php echo site_url('lapsingv2/lapsing_komoditas') ?>">Lapsing Komoditas</a></li>
					<li><a href="<?php echo site_url('lapsing/lapsing_sp4n') ?>">Lapsing SP4N</a></li>
					<li><a href="<?php echo site_url('lapsingv2/lapsing_rujukan_masuk') ?>">Lapsing Rujukan Masuk</a></li>
					<li><a href="<?php echo site_url('lapsingv2/lapsing_rujukan_keluar') ?>">Lapsing Rujukan Keluar</a></li>
				</ul>
			</li>
			<li>
				<ul>
					<li><a href="<?php echo site_url('lapsing/lapsing_ppid') ?>">Lapsing PPID</a></li>
					<!-- <li><a href="<?php echo site_url('lapsingv2/lapsing_ppid') ?>">Lapsing PPID (new)</a></li> -->
					<li><a href="<?php echo site_url('lapsing/lapsing_yanblik') ?>">Lapsing Yanblik</a></li>
					<li><a href="<?php echo site_url('lapsing/lapsing_gender') ?>">Lapsing Gender</a></li>

				</ul>
			</li>

		</ul>
	</li>

	<li class="has-submenu">
		<a href="#"><i class="mdi mdi-buffer"></i>Database</a>
		<ul class="submenu">
			<li><a href="<?php echo site_url('databases'); ?>">Database</a></li>
			<li><a href="<?php echo site_url('databasesv2'); ?>">Database (New)</a></li>
			<li><a href="<?php echo site_url('databases/rujukan'); ?>">Database Rujukan</a></li>
			<li><a href="<?php echo site_url('databasesv2/rujukan'); ?>">Database Rujukan (New)</a></li>
			<li><a href="<?php echo site_url('databases/resume'); ?>">Resume Harian</a></li>
			<li><a href="<?php echo site_url('databasesv2/yanblik'); ?>">Database Yanblik (New)</a></li>
			<li><a href="<?php echo site_url('monitorings/monbalai'); ?>">Monitoring Balai</a></li>
		</ul>
	</li>



	<li class="has-submenu">
		<a href="#"><i class="mdi mdi-buffer"></i>PPID</a>
		<ul class="submenu">
			<li><a href="<?php echo site_url('ppid/list_all') ?>">Layanan Permintaan Informasi Publik</a></li>
			<li><a href="<?php echo site_url('ppid/list_keberatan') ?>">Layanan Pengajuan Keberatan</a></li>
			<!--<li><a href="<?php echo site_url('ppid/list_sengketa') ?>">Sengketa Informasi Publik</a></li>-->

			<!--<li><a href="<?php echo site_url('drafts/create_sengketa') ?>">Tambah Data Sengketa</a></li>-->
			<?php if (is_allowed('can_create_tickets')) : ?>
				<li class="dropdown-divider"></li>
				<li><a href="<?php echo site_url('drafts/create_ppid') ?>">Tambah Permintaan Informasi Publik</a></li>
				<li><a href="<?php echo site_url('drafts/create_keberatan') ?>">Tambah Pengajuan Keberatan</a></li>
			<?php endif; ?>
		</ul>
	</li>




	<!--<li class="">
		<a href="<?php echo site_url('graphs/layanan'); ?>">
			<i class="mdi mdi-chart-bar"></i>
			Grafik
		</a>
		
	</li>-->
	<li class="has-submenu">
		<a href="#"><i class="mdi mdi-buffer"></i>Grafik</a>
		<ul class="submenu">

			<li><a href="<?php echo site_url('graphs/layanan') ?>">Grafik Layanan</a></li>
			<li><a href="<?php echo site_url('graphs_ppid/ppid') ?>">Grafik PPID</a></li>

		</ul>
	</li>

	<?php if (is_administrator()) : ?>
		<li class="has-submenu">
			<a href="#"><i class="mdi mdi-table"></i>Data Master</a>
			<ul class="submenu">
				<li>
					<a href="<?php echo site_url('users'); ?>">Pengaturan User</a>
				</li>
				<li>
					<a href="<?php echo site_url('balais'); ?>">Pengaturan Balai/Loka</a>
				</li>
				<li>
					<a href="<?php echo site_url('depts'); ?>">Pengaturan Unit Kerja</a>
				</li>
				<li>
					<a href="<?php echo site_url('calendars'); ?>">Pengaturan Hari Libur</a>
				</li>
				<li>
					<a href="<?php echo site_url('jobs'); ?>">Pengaturan Profesi</a>
				</li>
				<li>
					<a href="<?php echo site_url('klasifikasis'); ?>">Pengaturan Klasifikasi</a>
				</li>
				<li>
					<a href="<?php echo site_url('subklasifikasis'); ?>">Pengaturan Subklasifikasi</a>
				</li>
				<li>
					<a href="<?php echo site_url('slas'); ?>">Pengaturan SLA</a>
				</li>
			</ul>
		</li>
	<?php endif; ?>

	<li class="has-submenu">
		<a href="#"><i class="mdi mdi-help-box"></i>Bantuan</a>
		<ul class="submenu">
			<li>
				<a href="https://simpellpk.pom.go.id/simpel2022/public/docs/Manual-Simpellpk-Pusat%202022_rev%202309.pdf" target="_blank">User Manual</a>
			</li>
		</ul>
	</li>
</ul>