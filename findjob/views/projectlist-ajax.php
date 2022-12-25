
<?php
if(count($projects)>0){ foreach($projects as $key=>$val){ ?>
<div class="job-listing">    	

<!-- Job Listing Details -->
<div class="job-listing-details">
	<!-- Details -->
<?php
/**
 * 
 *  For Email
 */
$pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
$replacement = "[*****]";
 $val['description'] = htmlentities( $val['description']);
$val['description']=preg_replace($pattern, $replacement, $val['description']);


/**
 *  For URL
 */
$pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
$replacement = "[*****]";
$val['description']=preg_replace($pattern, $replacement, $val['description']);


/**
 * For bad words
 */
$healthy = explode(",",BAD_WORDS);
$yummy   = array("[*****]");
$val['description'] = str_replace($healthy, $yummy, $val['description']);


/**
 *  For Mobile
 */

$pattern = "/(?:1-?)?(?:\(\d{3}\)|\d{3})[-\s.]?\d{3}[-\s.]?\d{4}/x";
$replacement = "[*****]";
$val['description'] = preg_replace($pattern, $replacement, $val['description']);

?>	
    
    <div class="job-listing-description">        
        <h3 class="clearfix job-listing-title"><a href="<?php echo VPATH;?>jobdetails/details/<?php echo $val['project_id'];?>/<?php echo $this->auto_model->getcleanurl(htmlentities($val['title']));?>/"><?php echo ucwords(htmlentities($val['title']));?> </a>
        <?php if($val['featured']=='Y') {?>
        <span class="badge badge-success float-right">Featured</span>
        <?php } ?>
        </h3>
        <p class="job-listing-text"><?php echo substr($val['description'],0,250);?> ... <a href="<?php echo VPATH;?>jobdetails/details/<?php echo $val['project_id'];?>/<?php echo $this->auto_model->getcleanurl(htmlentities($val['title']));?>/"><?php echo __('findjob_more','more'); ?></a></p>
        <?php
	$q = array(
		'select' => 's.skill_name,s.arabic_skill_name,s.spanish_skill_name,s.swedish_skill_name , s.id',
		'from' => 'project_skill ps',
		'join' => array(array('skills s' , 'ps.skill_id = s.id' , 'INNER')),
		'offset' => 200,
		'where' => array('ps.project_id' => $val['project_id'])
	);
	$skills = get_results($q);
	?>
    <div class="task-tags">    
		<?php
		foreach($skills as $k => $v){
			$skill_name=$v['skill_name'];
		?>
		<span><?php echo $skill_name;?></span>  
		<?php } ?>
    </div>    
    </div>
	
</div>
<!-- Job Listing Footer -->
    <div class="job-listing-footer"> 
		<?php
           $totalbid=$this->jobdetails_model->gettotalbid($val['project_id']);
        ?>   
        <ul>
            <li><i class="icon-material-outline-location-on"></i> <span class="label"><?php echo __('findjob_posted_by','Posted by'); ?></span> <?php echo $val['username']; ?></a> <img src="<?php echo get_country_flag($val['user_country']); ?>" class="flag"/></li>
            <li><i class="icon-material-outline-business-center"></i> <?php if($val['project_type']=='F'){echo __('findjob_fixed','Fixed'); } else{echo __('findjob_hourly','Hourly');}?></li>
            <li><i class="icon-material-outline-account-balance-wallet"></i> <span class="label"><?php echo __('findjob_price','Price'); ?></span> <?php echo CURRENCY;?><?php echo $val['buget_min'];?>-<?php echo CURRENCY;?><?php echo $val['buget_max'];?></li>
            <li><i class="icon-material-outline-access-time"></i>  <span class="label"><?php echo __('findjob_posted','Posted'); ?></span> <?php echo __(strtolower(date('M',strtotime($val['post_date']))),date('M',strtotime($val['post_date']))).' '.date('d',strtotime($val['post_date'])).','.date('Y',strtotime($val['post_date']));?></li>      
                                         
    		<li><span class="label">Bids</span> <?php echo $totalbid;?> </li>
        </ul>
    </div>    	
    </div>
<?php } } else {
	echo "<div class='alert alert-danger'>".__('findjob_no_jobs_found','No jobs found')."</div>";	
} ?>
   


<nav aria-label="Page navigation" id="ajax-pagination" data-container-id="project-list">  
<?php  
if(isset($links)){                     
 echo $links;   
}
 ?> 
</nav>