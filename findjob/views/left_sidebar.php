<script src="<?php echo JSN;?>simplebar.min.js"></script>

<?php
function check_query($key='' , $arr=array()){
	if(is_array($key)){
		foreach($key as$v){
			if(array_key_exists($v , $arr)){
				unset($arr[$v]);
			}
		}
	}else{
		if(array_key_exists($key , $arr)){
			unset($arr[$key]);
		}
	}
	return count($arr) > 0 ? http_build_query($arr).'&' : '';
}
$lang = $this->session->userdata('lang');
?>

<aside class="col-lg-3 col-12">
	<a href="javascript:void(0)" class="float-right d-lg-none" id="filter" title="Filter"><i class="icon-feather-filter f20"></i></a>
    <div class="left_sidebar">
		<form id="srchForm2">
		<h4 class="title-sm"><?php echo __('findjob_sidebar_category','Category'); ?></h4> 
        <div class="scroll-bar mb-4" data-simplebar>
            <ul class="list-group">
                <?php foreach($parent_category as $key =>$val){
                    $categoryName = $val['cat_name'];
                ?>
                <li class="list-group-item">
                    <div class="checkbox">
                        <input class="magic-checkbox project-category" type="checkbox" name="category_id[]" id="category_<?php echo $val['cat_id'];?>" value="<?php echo $val['cat_id'];?>" <?php echo (!empty($srch_param['category_id']) && $srch_param['category_id'] == $val['cat_id']) ? 'checked' : ''; ?>>
                        <label for="category_<?php echo $val['cat_id'];?>"> <?php echo $categoryName;?></label>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>

        <div id="sub-category-wrapper">
            <!-- sub category -->
            <?php if(!empty($srch_param['category_id'])){
                $options = [
                    'data' => $this->auto_model->getCategory($srch_param['category_id']),
                    'type' => 'category',
                    'field_name' => 'sub_category_id',
                    'value' => [$srch_param['sub_catgory_id']],
                    'extra'  => ['class' => 'project-sub-category'],
                    'option_title' => 'Sub Category',
                ];
               $this->load->view('option/filter-checkbox', $options);
            }?>
        </div>
		
		<div class="sidebar-widget">
			<h4 class="title-sm"><?php echo __('findjob_sidebar_project_type','Project Type'); ?></h4>
			<?php $url = !empty($srch_string) ? '?'.check_query('ptype' , $srch_string)  : '?'; ?>
			<select class="selectpicker" title="All" name="ptype" onchange="submitForm()">
				<option value=""><?php echo __('findjob_sidebar_all','All'); ?></option>
				<option value="H" <?php echo ($srch_param['ptype'] == 'H') ? 'selected' : '';?>><?php echo __('findjob_sidebar_hourly','Hourly'); ?></option>
				<option value="F" <?php echo ($srch_param['ptype'] == 'F') ? 'selected' : '';?>><?php echo __('findjob_sidebar_fixed','Fixed'); ?></option>
			</select>
        </div>
		
        <div class="sidebar-widget">
			<h4 class="title-sm"><?php echo __('findjob_experience_level','Experience level'); ?></h4>
			<?php $url = !empty($srch_string) ? '?'.check_query('exp_level' , $srch_string)  : '?'; ?>
			<select class="selectpicker" title="All" name="exp_level" onchange="submitForm()">
				<option value=""><?php echo __('findjob_sidebar_all','All'); ?></option>
				<?php if(count($exp_levels) > 0){foreach($exp_levels as $k => $v){ ?>
				<option value="<?php echo $v['id']; ?>" <?php echo ($srch_param['exp_level'] == $v['id']) ? 'selected' : '';?>><?php echo ($lang == 'polish') ? $v['name_p'] : $v['name']; ?></option>
				<?php } } ?>
			</select>
        </div>


		<div class="sidebar-widget">
			<h4 class="title-sm"><?php echo __('findjob_sidebar_featured_project','Featured Project'); ?></h4>
			<?php $url = !empty($srch_string) ? '?'.check_query('featured' , $srch_string) : '?'; ?>
			<select class="selectpicker" title="All" name="featured" onchange="submitForm()">
				<option value=""><?php echo __('findjob_sidebar_all','All'); ?></option>
				<option value="Y" <?php echo (!empty($srch_param['featured']) AND $srch_param['featured'] == 'Y') ? 'selected' : '';?>><?php echo __('findjob_sidebar_featured','Featured'); ?></option>
				<option value="N" <?php echo (!empty($srch_param['featured']) AND $srch_param['featured'] == 'N') ? 'selected' : '';?>><?php echo __('findjob_sidebar_non_featured','Non-Featured'); ?></option>
			</select>
        </div>

        <div class="sidebar-widget">
			<h4 class="title-sm"><?php echo __('findjob_sidebar_project_environment','Project Environment'); ?></h4>
			<?php $url = !empty($srch_string) ? '?'.check_query('env' , $srch_string) : '?'; ?>
			<select class="selectpicker" title="All" name="env" onchange="submitForm()">
				<option value=""><?php echo __('findjob_sidebar_all','All'); ?></option>
				<option value="ON" <?php echo (!empty($srch_param['env']) AND $srch_param['env'] == 'ON') ? 'selected' : '';?>><?php echo __('findjob_sidebar_online','Online'); ?></option>
				<option value="OFF" <?php echo (!empty($srch_param['env']) AND $srch_param['env'] == 'OFF') ? 'selected' : '';?>><?php echo __('findjob_sidebar_offline','Offline'); ?></option>
			</select>
        </div>

		<div class="sidebar-widget">
            <h4 class="title-sm"><?php echo __('findjob_sidebar_budget','Budget'); ?></h4>
            <div class="margin-top-55"></div>
            <!-- Range Slider -->
            <input class="range-slider" type="text" name="budget" value="<?php echo $srch_param['max']?>" data-slider-currency="<?php echo CURRENCY;?>" data-slider-min="10" data-slider-max="2500" data-slider-step="25" data-slider-value="[<?php echo !empty($srch_param['max']) ? $srch_param['min'].','.$srch_param['max'] : '10,2500'?>]"/>
			<button type='button' onclick="filterForm()" class='btn btn-site btn-block margin-top-20'><?php echo __('findjob_sidebar_submit','Submit'); ?></button>
        </div>        

		<div class="sidebar-widget">
			<h4 class="title-sm"><?php echo __('findjob_sidebar_posted_within','Posted within'); ?></h4>
			<?php
				$url = !empty($srch_string) ? '?'. check_query('posted' , $srch_string) : '?';
			?>
			<select class="selectpicker" title="All" name="posted" onchange="submitForm()">
				<option value=""><?php echo __('findjob_sidebar_all','All'); ?></option>
				<option value="1" <?php echo (!empty($srch_param['posted']) AND $srch_param['posted'] == '1') ? 'selected' : '';?>><?php echo __('findjob_sidebar_posted_within_24_hours','Posted within 24 hours'); ?></option>
				<option value="3" <?php echo (!empty($srch_param['posted']) AND $srch_param['posted'] == '3') ? 'selected' : '';?>><?php echo __('findjob_sidebar_posted_within_3_days','Posted within 3 days'); ?></option>
				<option value="7" <?php echo (!empty($srch_param['posted']) AND $srch_param['posted'] == '7') ? 'selected' : '';?>><?php echo __('findjob_sidebar_posted_within_7_days','Posted within 7 days'); ?></option>
			</select>
		</div>

        <div class="sidebar-widget">
			<h4 class="title-sm"><?php echo __('findjob_sidebar_country','Country'); ?></h4>
			<?php $url = !empty($srch_string) ? '?'.check_query(array('ccode' , 'country') , $srch_string) : '?'; ?>
			<select class="selectpicker" title="All" name="ccode" data-size="10" data-live-search="true">
				<option value=""><?php echo __('findjob_sidebar_all','All'); ?></option>
				<?php print_select_option($countries, 'code', 'name');?>
			</select>
        </div>
        
		</form>
    </div>

</aside>

<script>

$('.project-category').change(function(){
    var selected_val =  $('.project-category').serializeArray();
    var selected_val_sub =  $('.project-sub-category').serializeArray();
    var cat_id =  [];
    var sub_cat_id=[];

    selected_val.map(function(res){
        cat_id.push(res.value);
    });

    selected_val_sub.map(function(res){
        sub_cat_id.push(res.value);
    });
    console.log(sub_cat_id);

    var $container = $('#sub-category-wrapper');
    getSubCategory({
        category_id: cat_id,
        format: 'filter-checkbox',
        field_name: 'sub_category_id',
        value: sub_cat_id,
        extra: {
            class: 'project-sub-category'
        }
    })
    .then(function(res){
        if(res.status == 1){
            $container.html(res.data);
            filterForm();
        }
        
    });
});

$(document).on('change', '.project-sub-category', function(){
    filterForm();
});

function submitForm(){
    filterForm();
}

$(document).ready(function(){
  $('#filter').click(function(){
    $('.left_sidebar').slideToggle();
  });
});

$(document).on('click', '#ajax-pagination a', function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    var $container = $('#'+$('#ajax-pagination').data('containerId'));
    $.get(url, function(res){
        $container.html(res);
    });
});
</script>