<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Findjob_model extends BaseModel {

    public function __construct() {
        return parent::__construct();
    }

	public function list_project($srch_param=array() , $limit=0 , $offset=40 , $for_list=TRUE){
        
        $today = date('Y-m-d');

		if($srch_param){
			foreach($srch_param as $k => $v){
				if(!is_array($v)){
					$srch_param[$k] = htmlentities($v);
				}
				
			}
		}

		$project = $this->db->dbprefix('projects');
		$user = $this->db->dbprefix('user');
		$this->db->select("$project.*,$user.fname,$user.lname,$user.country,$user.city,$user.username")->from('projects');
		$this->db->join("user", "$project.user_id = $user.user_id" , "INNER");
		$this->db->join("project_skill", "project_skill.project_id=projects.project_id" , "LEFT");
		$this->db->join("skills", "skills.id=project_skill.skill_id" , "LEFT");
		$this->db->join("projects_category p_c", "p_c.project_id=projects.project_id" , "LEFT");
		
		$this->db->where("$project.visibility_mode", "Public");
		if(!empty($srch_param['skills'])){
			$this->db->where_in("project_skill.skill_id", $srch_param['skills']);
		}
		
		if(!empty($srch_param['category_id'])){
            if(is_array($srch_param['category_id'])){
                $this->db->where_in("p_c.category_id" , $srch_param['category_id']);
            }else{
                $this->db->where("p_c.category_id" , $srch_param['category_id']);
            }
			
		}
		
		if(!empty($srch_param['sub_category_id'])){
            if(is_array($srch_param['sub_category_id'])){
                $this->db->where_in("p_c.sub_category_id" , $srch_param['sub_category_id']);
            }else{
                $this->db->where("p_c.sub_category_id" , $srch_param['sub_category_id']);
            }
			
		}
		
		if(!empty($srch_param['exp_level']) && $srch_param['exp_level'] != 'All'){
			$this->db->where("$project.exp_level" , $srch_param['exp_level']);
		}
		
		if(!empty($srch_param['ccode'])){
            if(is_array($srch_param['ccode'])){
                $this->db->where_in("$user.country" , $srch_param['ccode']);
            }else{
                $this->db->where("$user.country" , $srch_param['ccode']);
            }
			
		}
		
		if(!empty($srch_param['env']) AND $srch_param['env'] != 'All'){
			$this->db->where("$project.environment" , $srch_param['env']);
		}
		
		if(!empty($srch_param['ptype']) AND $srch_param['ptype'] != 'All'){
			$this->db->where("$project.project_type" , $srch_param['ptype']);
		}
		
		if(!empty($srch_param['featured']) AND $srch_param['featured'] != 'All'){
			$this->db->where("$project.featured" , $srch_param['featured']);
		}
		if($srch_param['budget']){
			$budget = explode(',',$srch_param['budget']);
			$srch_param['min'] = $budget[0];
			$srch_param['max'] = $budget[1];
		}
		
		if(!empty($srch_param['min'])){
			$this->db->where("$project.buget_min >=",$srch_param['min']);
		}
		
		if(!empty($srch_param['max'])){
			$this->db->where("$project.buget_max <=" , $srch_param['max']);
		}
		
		if(!empty($srch_param['q']) || !empty($srch_param['term'])){
			$term = !empty($srch_param['q']) ? $srch_param['q'] : $srch_param['term'];
			$term = addslashes($term);
			$this->db->where("($project.title LIKE '%{$term}%' OR $project.description LIKE '%{$term}%')");
		}
		
		if(!empty($srch_param['posted']) AND $srch_param['posted'] != 'All'){
			$newdate=date('Y-m-d',strtotime("-".$srch_param['posted']." day",strtotime(date('Y-m-d'))));
			$this->db->where('post_date >=',$newdate);
		}
		
        $this->db->where(array("$project.status"=>'O',"$project.project_status"=>'Y'));
        $this->db->where("$project.expiry_date >= ", $today); // expiry date check
		$this->db->group_by("$project.project_id");
		if($for_list){
            $result = $this->db->limit($offset , $limit)->order_by("$project.featured" , 'ASC')->order_by("$project.id" , "DESC")->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
	}

}