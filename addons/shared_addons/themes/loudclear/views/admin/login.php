<?php $method = isset($_GET["p"]) ? $_GET["p"] : 'login'; ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta name=viewport content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<title><?php echo $this->settings->site_name; ?> - <?php echo lang('login_title');?></title>

	<base href="<?php echo base_url(); ?>"/>
	<meta name="robots" content="noindex, nofollow"/>

	<?php Asset::css('workless/workless.css'); ?>
	<?php Asset::css('workless/application.css'); ?>
	<?php Asset::css('workless/responsive.css'); ?>
	<?php Asset::css('animate/animate.css'); ?>

	<?php Asset::js('jquery/jquery.js'); ?>
	<?php Asset::js('admin/login.js'); ?>

	<?php echo Asset::render() ?>
    
    
        <link rel="shortcut icon" href="/addons/shared_addons/themes/<?= constant('ADMIN_THEME'); ?>/img/logo.png">
</head>

<body id="login-body">

<div id="container" class="login-screen">
	<section id="content">
		<div id="content-body">

			<div class="animated fadeInDown" id="login-logo"></div>
			<?php $this->load->view('admin/partials/notices') ?>
			
                
        
        <?php //$this->load->view('admin/partials/notices'); ?>
        <?php if ($this->session->flashdata('message')): ?>
            <div class="alert error">
                <?php echo $this->session->flashdata('message'); ?>
            </div>
        <?php endif; ?>
        
        <?php 
			switch ($method) { 
				case 'forgot_password' : 
					$this->load->view('admin/auth/forgot_password.php');
					break; 
				case 'reset_pass' : 
					$this->load->view('admin/auth/reset_pass.php');
					break; 
				case 'reset_pass_complete' : 
					$this->load->view('admin/auth/reset_pass_complete.php');
					break; 
				default :
					$this->load->view('admin/auth/login_form.php');
					break;
			}
		?>
            
            
		</div>
	</section>
</div>
<footer id="login-footer">
	<div class="wrapper animated fadeInUp" id="login-credits">
		Copyright &copy; 2009 - <?php echo date('Y'); ?> <a href="http://www.loudclear.ca/" target="_blank">Loud+Clear</a><br>
		<span id="version"><?php echo CMS_VERSION.' '.CMS_EDITION; ?></span>
  </div>
</footer>
</body>
</html>