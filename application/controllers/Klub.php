<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Klub extends CI_Controller {

	public function index()
	{
		$this->load->model('klub_model');
		$data["klub_list"] = $this->klub_model->getDataKlub();
		$this->load->view('klub',$data);	
	}

		public function datatable()
	{
		$this->load->model('klub_model');
		$data["klub_list"] = $this->klub_model->getDataKlub();
		$this->load->view('klub_datatable',$data);
	}

		public function create()
	{
		$this->load->helper('url','form');	
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required|max_length[100]');
		$this->load->model('klub_model');	
		if($this->form_validation->run()==FALSE){

			$this->load->view('tambah_klub_view');

		}else{
			$config['upload_path'] = './assets/uploads/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']  = 1000000000;
			$config['max_width']  = 10240;
			$config['max_height']  = 7680;
			
			$this->load->library('upload', $config);
			
			if ( ! $this->upload->do_upload('userfile'))
			{
				$error = array('error' => $this->upload->display_errors());
				$this->load->view('tambah_klub_view',$error);
			}
			else
			{
				$this->klub_model->insertKlub();
				redirect('klub/datatable');
			}
		}
	}

		public function update($id)
	{
		//load library
		$this->load->helper('url','form');	
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->load->model('klub_model');
		$data['klub']=$this->klub_model->getKlub($id);
		$filename='logo';

		if($this->form_validation->run()==FALSE){

			$this->load->view('edit_klub_view',$data);

		}else{
			$config['upload_path']          = './assets/uploads/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = '1000000000';
                $config['max_width']            = '10240';
                $config['max_height']           = '7680';

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload())
                {
                        $error = array('error' => $this->upload->display_errors());
						$this->load->view('edit_klub_view');
                }
                else
                {
                		$image_data = $this->upload->data();
                		$configer= array
                		(
                			'image_library' => 'gd2',
                			'source_image' => $image_data['full_path'],
                			'maintain_ratio' => TRUE,
                			'width' => 250,
                			'height' => 250,
                		);
                		$this->load->library('image_lib',$config);
                		$this->image_lib->clear();
						$this->image_lib->initialize($configer);
						$this->image_lib->resize();
						$this->klub_model->updateById($id);
						redirect('klub/datatable');
                }
		}
	}

		public function delete($id)
	{

		$this->load->helper('url','form');
		$this->load->library('form_validation');
		$this->load->model('klub_model');
		$this->klub_model->deleteById($id);
		if($this->form_validation->run()==FALSE){
			redirect('klub/datatable');
		}
		
	}
}