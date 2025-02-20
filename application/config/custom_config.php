<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['upload_setting'] = array(
	//'upload_path' => './uploads/files/',
	'upload_path' => 'C:/Apache24/htdocs/simpellpk/attachments/',
	'allowed_types' => 'gif|jpg|png|pdf|doc|docx|zip|rar|txt|csv|jpeg',
	'max_size' => '20480',
	'max_size_mb' => '20MB',
	'encrypt_name' => TRUE,
	//'max_width' => '640',
	//'max_height' => '480'
);

$config['upload_draft_setting'] = array(
	//'upload_path' => './uploads/drafts/',
	'upload_path' => 'C:/Apache24/htdocs/simpellpk/attachments_drafts/',
	'allowed_types' => 'gif|jpg|png|pdf|doc|docx|zip|rar|txt|csv|jpeg',
	'max_size' => '20480',
	'max_size_mb' => '20MB',
	'encrypt_name' => TRUE,
	//'max_width' => '640',
	//'max_height' => '480'
);

$config['ppid_setting'] = array(
	'kop_path' => 'C:/Apache24/htdocs/simpel2022/public/assets/images/kop/',
	'template_path' => 'C:/Apache24/htdocs/simpel2022/application/doc_templates/ppid/',
);
