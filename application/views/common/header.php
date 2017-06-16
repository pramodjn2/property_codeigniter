<!DOCTYPE html>
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- start: HEAD -->
<head>
<?php
$websetting = $this->session->userdata('websetting');

if(empty($title) && !empty($websetting)){
	$title =  $websetting['meta_title'];
}
if(empty($keywords) && !empty($websetting)){
	$keywords =  $websetting['meta_keywords'];
}
if(empty($description) && !empty($websetting)){
	$description =  $websetting['meta_description'];
}
?>

<title><?=$title?></title>
<meta name="description" content="<?=$keywords?>"/>
<meta name="keywords" content="<?=$description?>"/>

<!-- start: META -->
<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="" name="author" />
<!-- end: META -->
<!-- start: MAIN CSS -->
<link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
<link href="<?=base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="<?=base_url();?>assets/plugins/bootstrap/css/bootstrap-formhelpers.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?=base_url();?>assets/plugins/animate.css/animate.min.css">
<link rel="stylesheet" href="<?=base_url();?>assets/css/front-end-main.css">
<link rel="stylesheet" href="<?=base_url();?>assets/css/front-end-main-responsive.css">
<link rel="stylesheet" href="<?=base_url();?>assets/plugins/iCheck/skins/all.css">
<link rel="stylesheet" href="<?=base_url();?>assets/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css">
<link rel="stylesheet" href="<?=base_url();?>assets/plugins/jQuery-Dropdown/css/dd.css">
<link rel="stylesheet" href="<?=base_url();?>assets/plugins/jQuery-Dropdown/css/flags.css">
<link rel="stylesheet" href="<?=base_url();?>assets/plugins/jQuery-Dropdown/css/skin2.css">
<link rel="stylesheet" href="<?=base_url();?>assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css">
<link rel="stylesheet" href="<?=base_url();?>assets/plugins/bootstrap-social-buttons/social-buttons-3.css">
<link rel="stylesheet" href="<?=base_url();?>assets/css/front-end-theme_red.css" type="text/css" id="skin_color">
<link rel="stylesheet" href="<?=base_url();?>assets/fonts/style.css">
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
<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
<!-- end: FONTS API -->
<link rel="shortcut icon" href="<?php echo config_item('base_url');?>favicon.ico" type="image/x-icon" />
<script>var base_url = '<?php echo config_item('base_url');?>';
var site_url = '<?php echo config_item('base_url');?>';
var session_user_id = '<?php echo $this->session->userdata('user_id');?>';</script>
</head>
<!-- end: HEAD -->
<body>
<div class="loader_wrapper" id="loader_wrapper" style="z-index:1050; display:none">
 <div class="loader_text">
  <p>Please Wait Loading...</p>
  <p style="color:#F00; font-size:x-large; font-weight:bold">OTRIGA</p>
  <div class="threepeople_small">
    <img alt="" src="<?=base_url('assets/images/findagent-3people_small.png')?>">
  </div>
  <i class="fa fa-refresh fa-spin"></i>
 </div>
</div>

<!--<div  class="loader_wrapper" id="loader_wrapper" style="z-index: 1050; display:none;">
	<div class="loader_text">
		<p>Please Wait Loading...</p>
		<p>OTRIGA</p>
		<i class="fa fa-refresh fa-spin"></i>
	</div>
</div>-->

<style>
.loader_wrapper {
   /* background:url(http://otriga.com/assets/images/loader-bg.png) no-repeat;*/
    height: 100%;
    /*opacity: 0.8;*/
    position: fixed;
    text-align: center;
    top: 0;
    width: 100%;
    z-index: 1000000000 !important;
	background-color:#000;
	opacity:0.9;
	
}
.loader_wrapper .loader_text{margin-top: 20%;   color: #fff;  font-size: 14px;}
.loader_wrapper .fa-spin {
    color: #fff;
    display: inline-block;
    font-size: 50px; 
}
.threepeople_small {
  margin: 25px auto 0;
  text-align: center;
  width: 170px; margin-bottom:20px;
}
</style>
<?php
$current_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$this->session->set_userdata('current_url', $current_url);
$this->websetting->getSetting('SITE_NAME');
?>