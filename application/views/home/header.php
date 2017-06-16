<!DOCTYPE html>
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- start: HEAD --><head>
<title>Otriga: Home</title>
<!-- start: META -->
<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="" name="description" />
<meta content="" name="author" />
<!-- end: META -->
<!-- start: MAIN CSS -->
<link href="<?php echo config_item('base_url');?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="<?php echo config_item('base_url');?>assets/plugins/bootstrap/css/bootstrap-formhelpers.min.css" rel="stylesheet">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo config_item('base_url');?>assets/plugins/animate.css/animate.min.css">
<link rel="stylesheet" href="<?php echo config_item('base_url');?>assets/css/front-end-main.css">
<link rel="stylesheet" href="<?php echo config_item('base_url');?>assets/css/front-end-main-responsive.css">
<link rel="stylesheet" href="<?php echo config_item('base_url');?>assets/css/front-end-theme_red.css" type="text/css" id="skin_color">
<link rel="stylesheet" href="<?php echo config_item('base_url');?>assets/fonts/style.css">
<!-- end: MAIN CSS -->


<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
<?php
	if(isset($stylesheet) && !empty($stylesheet)){
		$i=0;
		foreach($stylesheet as $value){$i++;								
			$tab = ($i!=1)?"\t\t ":"";
			echo '<link rel="stylesheet" href="'.config_item('base_url').$value.'">'."\n";
		}
	}
	if(isset($style) && !empty($style)){
		foreach($style as $value){
			echo $value;
		}
	}
?>
<!-- end: EXTRA CSS REQUIRED FOR THIS PAGE ONLY -->

<!-- start: HTML5SHIV FOR IE8 -->
<!--[if lt IE 9]>
<script src="<?php echo config_item('base_url');?>assets/plugins/html5shiv.js"></script>
<![endif]-->
<!-- end: HTML5SHIV FOR IE8 -->

<!-- start: FONTS API -->
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
<!-- end: FONTS API -->
<link rel="shortcut icon" href="<?php echo config_item('base_url');?>favicon.ico" type="image/x-icon" />

<script>var base_url = '<?php echo config_item('base_url');?>';</script>
<script>var site_url = '<?php echo config_item('base_url');?>';</script>
</head>
<!-- end: HEAD -->
<body>