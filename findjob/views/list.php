<link rel="stylesheet" href="<?php echo ASSETS;?>plugins/taginput/tokenize2.min.css" type="text/css" />
<script src="<?php echo ASSETS;?>plugins/taginput/tokenize2.min.js" type="text/javascript"></script>
     
<section class="sec">
<div class="container">
<?php echo $breadcrumb;?>
<div class="row">

<aside class="col-lg-9 col-12">
<div class="topcontrol_box" style="display:none">
<div class="topcbott"></div>
</div>
<h4 class="title-sm">Here are some projects for you </h4>

	
<div class="searchbox">
  <form id="srchForm">
	<input type="hidden" name="append_skill" value="<?php echo $srch_param['append_skill'] == 1 ? $srch_param['append_skill'] : 0;?>"/>
	<div class="form-group">
		<select class="form-control inputtag" name="skills[]" multiple>
		<?php if(count($selected_skills) > 0){foreach($selected_skills as $k => $v){ ?>
			<option value="<?php echo $v['id']; ?>" selected="selected"><?php echo $v['skill_name']; ?></option>
		<?php } } ?>
		</select>
	</div>
	
    
    <div class="input-group">      
      <input type="text" class="form-control" name="term" placeholder="Search by keyword.." autocomplete="off" value="<?php echo !empty($srch_param['term']) ? $srch_param['term'] : ''; ?>">
      <div class="input-group-append">
      	<button type="submit" class="btn btn-site"><i class="icon-feather-search"></i> <?php echo __('findjob_search','Search'); ?></button>
  	  </div>
    </div>
	  
	  
  </form>
  <p class="text-right" style="display:none;"><a style="cursor: pointer;"><?php echo __('findjob_advance_search','Advanced Search'); ?></a></p>
</div>
					
<!-- Job Listing -->	

<div class="listings-container margin-top-35" id="project-list">

</div>
</aside>
<?php $this->load->view('left_sidebar'); ?>
</div>

</div>
</section>
<div class="clearfix"></div>

<script type="text/javascript">
$('.inputtag').tokenize2({
	placeholder: "Type Skill",
	dataSource: function(search, object){
		$.ajax({
			url : '<?php echo base_url('contest/get_skills')?>',
			data: {search: search},
			dataType: 'json',
			success: function(data){
				var $items = [];
				$.each(data, function(k, v){
					$items.push(v);
				});
				object.trigger('tokenize:dropdown:fill', [$items]);
			}
		});
	}
});

$('.inputtag').on('tokenize:tokens:add', function(o){
	filterForm();
});

$('.inputtag').on('tokenize:tokens:remove', function(o){
	filterForm();
});

function filterForm(){
    var frm = $('#srchForm').serialize();
	var frm2 = $('#srchForm2').serialize();
    if(frm2 != ''){
        frm += '&'+frm2;
    }
    var $container = $('#project-list');
    showLoader($container, '', 200);
    setTimeout(function(){
        $.get('<?php echo base_url('findjob/filter_ajax')?>', frm, function(res){
            $container.html(res);
        });
    }, 500);
 
}

$(function(){
    filterForm();
});

</script>







