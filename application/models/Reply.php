<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Reply class
 * for Admin
 */

class Reply extends CI_Model
{
	
	public function get_info_direktorat($dir_id){
		$query = "SELECT concat(name, ' ( ', kota, ' )') as name FROM desk_direktorat WHERE id = '$dir_id'";
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_info_desk_reply($dir_id, $replyto){
		$query = "select a.name, a.message , a.dt
		from desk_replies a, desk_users b 
		where a.staffid = b.id AND b.direktoratid = '$dir_id' and replyto = '$replyto'";
		$results = $this->db->query($query);
		return $results->result_array();
	}
	
}
?>
