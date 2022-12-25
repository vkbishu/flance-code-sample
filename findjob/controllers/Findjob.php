<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Findjob extends MX_Controller {

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
        $this->load->model('findjob_model');
		$this->load->model('jobdetails/jobdetails_model');
		$this->load->library('pagination');
		$idiom = $this->session->userdata('lang');
		$this->lang->load('findjob', $idiom);
        parent::__construct();

        $this->load->model('project/project_model');
        $this->project_model->check_expired();

    }

    public function index() {
		/* redirect(base_url('findjob/browse')); */
		$data['srch_url'] = uri_string();
		$data['srch_param'] = $data['srch_string'] = $this->input->get();
		
		$breadcrumb=array(
                    array(
                            'title'=>__('findjob_category','Category'),'path'=>''
                    )
                );

		$data['breadcrumb']=$this->autoload_model->breadcrumb($breadcrumb,'All Category');
				
		$head['current_page']='findjob';
		$head['ad_page']='findjob';
		$load_extra=array();
		$data['load_css_js']=$this->autoload_model->load_css_js($load_extra);
		$this->layout->set_assest($head);
		$this->autoload_model->getsitemetasetting("meta","pagename","Findjob");
		$lay['client_testimonial']="inc/footerclient_logo";
		$data['category_list'] = getAllCategoryData();
		/* get_print($data['category_list']); */
		$this->layout->view('category',$lay,$data,'normal');
	}
	
	public function browse($cat='',$cat_id='',$sub_cat='',$sub_cat_id=''){
		$user=$this->session->userdata('user');
		$user_id = $user[0]->user_id;
		
		$data['srch_url'] = uri_string();
		$data['srch_param'] = $data['srch_string'] = $this->input->get();
		/* get_print($data['srch_param']); */
		if(!empty($data['srch_param']['country'])){
			$data['srch_param']['ccode'] = $this->auto_model->getFeild('Code', 'country', 'Name', $data['srch_param']['country']);
		}
		
		$data['srch_param']['category'] = $cat;
		$data['srch_param']['category_id'] = $cat_id;
		$data['srch_param']['sub_catgory'] = $sub_cat;
		$data['srch_param']['sub_catgory_id'] = $sub_cat_id;
		
		$data['srch_string'] = !empty($data['srch_string']) ? $data['srch_string'] : array();
		
		$budget = explode(',',$data['srch_param']['budget']);
		$data['srch_param']['min'] = $budget[0];
		$data['srch_param']['max'] = $budget[1];

		$data['selected_skills'] = [];
	
		$data['parent_category'] = $this->auto_model->getCategory(0);
		$data['child_category'] = array();
		if(!empty($cat_id)){
			$data['child_category'] =  $this->auto_model->getCategory($cat_id);
		}
		$data['countries'] =  $this->auto_model->getCountry();
		$data['exp_levels'] =  get_table('experience_level', ['status' => 'Y'], 'array');

		$breadcrumb=array(
                    array(
                            'title'=>__('find_project','Find Project'),'path'=>''
                    )
                );

		$data['breadcrumb']=$this->autoload_model->breadcrumb($breadcrumb, __('find_project', 'Find Project'));
		$head['current_page']='findjob';
		$head['ad_page']='findjob';
		$load_extra=array();
		$data['load_css_js']=$this->autoload_model->load_css_js($load_extra);
		$this->layout->set_assest($head);
		$this->autoload_model->getsitemetasetting("meta","pagename","Findjob");
		$lay['client_testimonial']="inc/footerclient_logo";

		$this->layout->view('list',$lay,$data,'normal');
    }
    
    public function filter_ajax(){
        $data['srch_param'] = $data['srch_string'] = $this->input->get();
        $data['offset'] = 10;
		$data['limit'] = !empty($data['srch_param']['per_page']) ? $data['srch_param']['per_page'] : 0;
		$data['srch_string'] = !empty($data['srch_string']) ? $data['srch_string'] : array();
		
		$budget = explode(',',$data['srch_param']['budget']);
		$data['srch_param']['min'] = $budget[0];
		$data['srch_param']['max'] = $budget[1];
		
		unset($data['srch_string']['per_page']);
		unset($data['srch_string']['total']);
        $data['projects'] = $this->findjob_model->list_project($data['srch_param'] , $data['limit'] , $data['offset']);
        $data['total_projects'] = $this->findjob_model->list_project($data['srch_param'] , $data['limit'] , $data['offset'] , FALSE);
        /*Pagination Start*/

		$config['base_url'] = base_url('findjob/filter_ajax');
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['total_rows'] = $data['total_projects'];
		$config['per_page'] = $data['offset'];
		
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = __('pagination_first','First');
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='page-item active'><a href='javascript:void(0)' class='page-link'>";
		$config['cur_tag_close'] = '</a></li>';
		$config['last_link'] = __('pagination_last','Last');;
		$config['last_tag_open'] = "<li class='page-item last'>";
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '<i class="zmdi zmdi-chevron-right"></i>';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="zmdi zmdi-chevron-left"></i>';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>'; 
		$config['attributes'] = array('class' => 'page-link');
		$this->pagination->initialize($config);
		$data['links'] = $this->pagination->create_links();
		/*Pagination End*/
        $this->load->view('projectlist-ajax',$data);
    }
	
	public function get_skills(){
		$res = array();
		$skills = get_results(array('select' => '*', 'from' => 'skills', 'status' => 'Y'));
		if(!empty($skills)){
			foreach($skills as $k => $v){
				$res[] = array(
					'text' => $v['skill_name'],
					'value' => $v['id']
				);
			}
		}
		echo json_encode($res);
	}

    
    
}
