<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Product View</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Product View</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/product/view')?>" class="btn btn-primary btn-sm" > View All</a>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
	<?php
	$attributes = array('class' => 'csv_add_product', 'id' => 'csv_add_product'); 
	echo form_open_multipart('admin/product/uploadProduct',$attributes);
		// echo '<pre>';
		 // print_r($csv);
		 // print_r($categories);
		 // echo '</pre>';
		 //die;
		$fieldName = array('item_name','description','item_price','main_cat_ids','discount');
		 $heading = array('Product Name','Product Description','Price','Category Name','Discount');
		?>
		<div class="nav-tabs-custom testimonial-group">
			<?php 
			foreach($csv as $k=>$v)
			{
				if($k==0){continue;}
				echo '<h3>Product '.$k.'</h3>
					<div class="row">';
					for($i=0;$i<count($v);$i++)
					{
						if($i!=3)
						{
							echo '<div class="single_prod col-md-2">
								<div class="form-group ">
							<label class="control-label">'.$heading[$i].'</label>
							<input type="text"  name="'.$fieldName[$i].'[]" class="form-control" value="'.$v[$i].'"  placeholder="'.$heading[$i].'"></div></div>';	
						}else if($i==3)
						{
							echo '
							<div class="single_prod col-md-2">
								<div class="form-group ">
									<label class="control-label">'.$heading[$i].'</label>
									<select name="'.$fieldName[$i].'[]" class="form-control">';
									foreach($categories as $kk=>$vv)
									{
										$sel = (trim(strtolower($vv))==trim(strtolower($v[$i])))?'selected':'';
										echo '<option value="'.$kk.'" '.$sel.'>'.$vv.'</option>';
									}
									echo '</select>
								</div>
							</div>';	
						}
					
					}
				echo '</div>';
			}?>
		</div>
			
	<div class="row">
		<div class=" col-sm-12">
			<div class="input-group mb-0">
				<button type="submit" class="btn btn-primary btn-sm btn-block disable">SUBMIT</button>
			</div>
		</div>
	</div>
	</form>
	
</div>
 