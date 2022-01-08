<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php 
    $controller = $this->router->fetch_class();
    $method = $this->router->fetch_method();
    $seoTag = seoTag();
	
	if(isset($seoTag) && is_array($seoTag) && count($seoTag)>0 && array_key_exists($method,$seoTag)){
		$data = $seoTag[$method]; 
		?>
		<meta name="description" content="<?=$data['description']; ?>">
		<meta name="keywords" content="<?=$data['keywords']; ?>">
		<title><?=$data['title']; ?></title>
		<?php
	}else{
		?>
		<meta name="description" content="Eastern Eye">
		<meta name="keywords" content="Eastern Eye">
		<title><?=$page_title; ?></title>
		<?php
	}
	
    ?>
    <link type="text/css" href="<?=base_url()?>assets/frontend/css/bootstrap-datepicker.css" rel="stylesheet">
    <link type="text/css" href="<?=base_url()?>assets/frontend/css/jquery.timepicker.css" rel="stylesheet">
    <link type="text/css" href="<?=base_url()?>assets/frontend/css/style.css" rel="stylesheet">
    <link type="text/css" href="<?=base_url()?>assets/frontend/css/bootstrap.css" rel="stylesheet">
    <link type="text/css" href="<?=base_url()?>assets/frontend/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link
        href="https://fonts.googleapis.com/css?family=Bungee|Clicker+Script|Crafty+Girls|Indie+Flower|Open+Sans:300,400,600|Zilla+Slab:300,400,500,600,700"
        rel="stylesheet">


    <script type="text/javascript">
    var site_url = "<?=base_url()?>";
    var currency = "<?=CURRENCY?>";
    var country = "<?=COUNTRY?>";
    var mobile_length = "<?=MOBILE_LENGTH?>";
    </script>
</head>

<body>
    <div class="msg_error_success" style="display:none">
        <div class="alert alert-success alert-dismissible suss">
            <button type="button" class="close " data-dismiss="alert" aria-hidden="true">×</button>
            <span class="fixed_success"></span>
        </div>

        <div class="alert alert-danger alert-dismissible err">
            <button type="button" class="close " data-dismiss="alert" aria-hidden="true">×</button>
            <span class="fixed_error"></span>
        </div>
    </div>

    <div class="loading-image" style="display:none">
        <img src="<?=base_url()?>assets/frontend/img/loading.gif" />
    </div>