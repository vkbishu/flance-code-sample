<?php $lang = $this->session->userdata('lang');?>
<section class="sec">
	<div class="container">	
    	<?php echo $breadcrumb;?>  	
		<!-- category Listing -->	
        <div class="categorySec">
            <h2 style='margin-bottom: 30px'>Browse Job by Category</h2>
            <?php /* <ul class="catList">
                <?php
                if(count($category_list)>0){
                    foreach($category_list as $key=>$val){
                        $cat_url = $this->auto_model->getcleanurl($val['cat_name']).'/'.$val['cat_id'];
                ?>
                <li><a href="<?php echo base_url().'findjob/browse/'.$cat_url;?>"><?php echo $val['cat_name'];?></a></li>
                <?php }//for
                }//if
                ?>
            </ul> */ ?>
            <?php
                if(count($category_list)>0){
                    foreach($category_list as $key=>$val){
                        $cat_url = $this->auto_model->getcleanurl($val['cat_name']).'/'.$val['cat_id'];
                        switch($lang){
                            case 'spanish':
                                //$categoryName = $val['spanish_cat_name'];
                                $categoryName = !empty($val['spanish_cat_name'])? $val['spanish_cat_name'] : $val['cat_name'];
                                break;
                            case 'arabic':
                            //$categoryName = $val['spanish_cat_name'];
                            $categoryName = !empty($val['arabic_cat_name'])? $val['arabic_cat_name'] : $val['cat_name'];
                            break;
                            case 'portuguese':
                            //$categoryName = $val['spanish_cat_name'];
                            $categoryName = !empty($val['swedish_cat_name'])? $val['swedish_cat_name'] : $val['cat_name'];
                            break;
                            default :
                                $categoryName = $val['cat_name'];
                                break;
                        }
            ?>
                
                <div class="card">
                <div class="card-header">
                    <h4><?php echo $categoryName;?></h4>
                </div>
                <div class="card-body">
                    <ul class="list list-3 cat-flex">
                    <?php
                    $q2 = array('select' => '*' , 'from' => 'categories' , 'where' => array('status' => 'Y' ,'parent_id' => $val['cat_id']) , 'offset' => 'all');
                    $sub_cat =  get_results($q2);
                        if($sub_cat){
                            foreach($sub_cat as $ksub=>$vsub){
                                $sub_url = '?append_skill=0&skills%5B%5D='.$vsub['id'];
                            switch($lang){
                            case 'spanish':
                                //$categoryName = $val['spanish_cat_name'];
                                $categoryNames = !empty($vsub['spanish_cat_name'])? $vsub['spanish_cat_name'] : $vsub['cat_name'];
                                break;
                            case 'arabic':
                            //$categoryName = $val['spanish_cat_name'];
                            $categoryNames = !empty($vsub['arabic_cat_name'])? $vsub['arabic_cat_name'] : $vsub['cat_name'];
                            break;
                            case 'portuguese':
                            //$categoryName = $val['spanish_cat_name'];
                            $categoryNames = !empty($vsub['swedish_cat_name'])? $vsub['swedish_cat_name'] : $vsub['cat_name'];
                            break;
                            default :
                                $categoryNames = $vsub['cat_name'];
                                break;
                        
                        }
                                
                        ?>
                            <li><a href='<?php echo base_url('findjob/browse').'/'.$this->auto_model->getcleanurl($val['cat_name']).'/'.$val['cat_id'].'/'.$this->auto_model->getcleanurl($vsub['cat_name']).'/'.$vsub['cat_id'];?>'><?php echo $categoryNames;?></a></li>
                        <?php
                            }
                        }
                    ?>
                    </ul>
                    </div>
                </div>
            <?php
                    }//for
                }//if
            ?>                
        </div>	
	</div>
</section>