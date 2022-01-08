<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Upload Product</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/product/view')?>">Products</a></li>
					<li class="breadcrumb-item active" aria-current="page">Upload Product</li>
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

	if($this->session->flashdata('msg_success')){ ?>
  <div class="alert alert-success alert-dismissible"> 
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<?=($this->session->flashdata('msg_success'))?> 
  </div>
  <?php }
  if($this->session->flashdata('error_msg')){ ?>
  <div class="alert alert-danger alert-dismissible"> 
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<?=($this->session->flashdata('error_msg'));?> 
  </div> 
  <?php
  }
   if(validation_errors()){ ?>
	<div class="alert alert-danger alert-dismissible" role="alert">
	  <?php echo validation_errors(); ?>
	</div>
  <?php }
  
	$attributes = array('class' => 'upload_product', 'id' => 'upload_product'); 
	echo form_open_multipart('admin/product/csvView',$attributes);?>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Add CSV</label>
		<div class="col-md-9 col-sm-12 row pr-0">
			<div class="col-sm-12 col-md-12 pr-0">
				<input type="file" name="csv" multiple accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  id="csv" onchange="return ValidateCSVFileUpload()"  required >
				<span class="csv error"></span>
			</div>
		</div>
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
 <script type="text/javascript">
  
	function ValidateCSVFileUpload() {
        var fuData = document.getElementById('csv');
        var FileUploadPath = fuData.value;

        if (FileUploadPath == '') {
            alert("Please upload an CSV file");

        } else {
            var Extension = FileUploadPath.substring(
                    FileUploadPath.lastIndexOf('.') + 1).toLowerCase();


			if (Extension!= "csv") {
                alert("File only allows types of CSV.");
				$('#csv').val('');
            } 
			
        }
    }
    </script>