<? include_once ("pagebuilder/template-freeform.php"); ?>
<!doctype html>
<!--[if lt IE 7]>		<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>			<html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>			<html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->	<html class="no-js" lang=""> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Search Results - ||TITLE||</title>
	<meta name="ROBOTS" content="INDEX, FOLLOW"> 
	<meta name="keywords" content="||KEYWORDS||" /> 
	<meta name="description" content="||DESCRIPTION||" /> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="XYZ123">

	<link rel="apple-touch-icon" href="/live/apple-touch-icon.png">
	<link rel="stylesheet" href="/live/css/bootstrap.min.css">
	<link rel="stylesheet" href="/live/css/normalize.css">
	<link rel="stylesheet" href="/live/css/font-awesome.min.css">
	<link rel="stylesheet" href="/live/css/icomoon.css">
	<link rel="stylesheet" href="/live/css/owl.theme.css">
	<link rel="stylesheet" href="/live/css/owl.carousel.css">
	<link rel="stylesheet" href="/live/css/prettyPhoto.css">
	<link rel="stylesheet" href="/live/css/main.css">
	<link rel="stylesheet" href="/live/css/transitions.css">
	<link rel="stylesheet" href="/live/css/color.css">
	<link rel="stylesheet" href="/live/css/responsive.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="/live/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
	<script src="/live/js/custom.js"></script>
</head>
<body>
	<!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<!--************************************
			Wrapper Start
	*************************************-->
	<div id="wrapper" class="tg-haslayout">
		<!--************************************
				Header Start
		*************************************-->
		<header id="header" class="tg-haslayout tg-inner-header">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<div class="tg-navigationarea tg-haslayout">
							
								<? ShowContainer (1); ?>
							
						</div>
					</div>
				</div>
			</div>
		</header>
		<!--************************************
				Header End
		*************************************-->
		<!--************************************
				Home Slider Start
		*************************************-->
		<div id="tg-innerbanner" class="tg-innerbanner tg-bglight tg-haslayout">
			<div class="tg-pagebar tg-haslayout">
				<div class="container">
					<div class="row">
						<div class="col-xs-12">
							<h1>Search Results</h1>
							<ol class="tg-breadcrumb">
								<li><a href="/">Home</a></li>
								<li class="active">Search Results</li>
								<!-- <li class="dropdown">
									<i class="fa fa-bars" aria-hidden="true" data-toggle="dropdown"></i>
									<ul class="dropdown-menu">
										<li><a href="doctor-gridview2.html" style="color:#062e4c">Doctors</a></li>
										<li><a href="reviews.html" style="color:#062e4c">Past Appointments</a></li>
										<li><a href="settings.html" style="color:#062e4c">Setting</a></li>
										<li><a href="#" style="color:#062e4c">Sign Out</a></li>
									</ul>
								</li> -->
							</ol>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<div id="tg-innerbanner" class="tg-innerbanner tg-bglight tg-haslayout">
			<div class="flash-messages">
				<? ShowContainer (11); ?>
			</div>
			<div class="tg-searcharea tg-haslayout">
				<div class="container">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
							<? ShowContainer (7); ?>
						</div>
						
					</div>
				</div>
			</div>
			
		</div>
		<!--************************************
				Home Slider End
		*************************************-->
		<!--************************************
				Main Start
		*************************************-->
		<main id="main" class="tg-main-section tg-haslayout">
			<div class="container">
				<div class="row">
					<!-- <div class="col-md-12 col-sm-12 col-xs-12 pull-right"> -->
						<? ShowContainer (2); ?>
					<!-- </div> -->
				</div>
			</div>
		</main>
		<!--************************************
				Main End
		*************************************-->
		<!--************************************
				Footer Start
		*************************************-->
		<footer id="footer" class="tg-haslayout">
			<? ShowContainer (3); ?>
		</footer>
		<!--************************************
				Footer End
		*************************************-->
	</div>
	<!--************************************
			Wrapper End
	*************************************-->
	<!--************************************
		Sign In Sign Up Light Box Start
	*************************************-->
		<div class="modal fade tg-user-modal" tabindex="-1" role="dialog">
		<div class="tg-modal-content">
			<ul class="tg-modaltabs-nav" role="tablist">
				<li role="presentation" class="active"><a href="#tg-signin-formarea" aria-controls="tg-signin-formarea" role="tab" data-toggle="tab">sign in</a></li>
				<li role="presentation"><a href="#tg-signup-formarea" aria-controls="tg-signup-formarea" role="tab" data-toggle="tab">sign up</a></li>
			</ul>
			<div class="tab-content tg-haslayout">
				<div role="tabpanel" class="tab-pane tg-haslayout active" id="tg-signin-formarea">
					<? ShowContainer (5); ?>
				</div>
				<div role="tabpanel" class="tab-pane tg-haslayout" id="tg-signup-formarea">
					<? ShowContainer (6); ?>
				</div>
			</div>
		</div>
	</div>
	<!--************************************
		Sign In Sign Up Light Box End
	*************************************-->
<!--<script src="/live/js/vendor/jquery-library.js"></script>-->	
    <script src="/live/js/vendor/bootstrap.min.js"></script>
	<script src="/live/js/jquery-ui.js"></script>
<!-- 	<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<script src="/live/js/map/jquery.gomap.js"></script> -->
	<script src="/live/js/map/markerclusterer.min.js"></script>
	<script src="/live/js/countdown.js"></script>
	<script src="/live/js/jquery.nicescroll.js"></script>
	<script src="/live/js/parallax.js"></script>
	<script src="/live/js/owl.carousel.js"></script>
	<script src="/live/js/prettyPhoto.js"></script>
	<script src="/live/js/appear.js"></script>
	<script src="/live/js/countTo.js"></script>
<!--<script src="/live/js/map/map.js"></script>-->	
    <script src="/live/js/main.js"></script>
</body>
</html>