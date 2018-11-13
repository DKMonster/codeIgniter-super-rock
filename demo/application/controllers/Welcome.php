<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index() {
		$this->load->view('welcome_message');
  }

  function view($page = 'index') {
	  if (!file_exists('application/views/pages/'.$page.'.php')) {
	    show_404();
    }
  
	  $pageInfo['title'] = ucfirst($page); // 第一個字母大寫
  
	  $this->load->view('pages/'.$page, $pageInfo);
  }
}
