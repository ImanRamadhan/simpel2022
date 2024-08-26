<?php
class NoAuth401 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function view($backto)
    {
        $this->output->set_status_header('401');

        $link = "";
        switch ($backto) {
            case 'ppid':
                $link = "ppid/list_all/";
                break;

            case 'tickets':
                $link = "tickets/list_all/";
                break;

            case 'rujukan':
                $link = "rujukan/list_masuk/";
                break;

            default:
                # code...
                break;
        }
        $data['link'] = $link;
        $this->load->view('err401', $data);
    }
}
