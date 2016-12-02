<? include_once ("pagebuilder/template-freeform.php"); ?>
<!doctype html>
<!--[if lt IE 7]>		<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>			<html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>			<html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->	<html class="no-js" lang=""> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>||TITLE||</title>
	<meta name="ROBOTS" content="INDEX, FOLLOW"> 
	<meta name="keywords" content="||KEYWORDS||" /> 
	<meta name="description" content="||DESCRIPTION||" /> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
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
	<script src="/live/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
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
							<strong class="logo">
								<a href="/"><img src="/live/images/logo.png" alt="image description"></a>
							</strong>
							<nav id="tg-nav" class="tg-nav">
								<? ShowContainer (1); ?>
							</nav>
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
						<? ShowContainer (4); ?>
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
					<div class="col-md-12 col-sm-12 col-xs-12 pull-right">
						<? ShowContainer (2); ?>
					</div>
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
					<form class="tg-form-modal tg-form-signin">
						<fieldset>
							<div class="form-group">
								<input type="email" class="form-control" placeholder="Email Address">
							</div>
							<div class="form-group">
								<input type="password" class="form-control" placeholder="Password">
							</div>
							<div class="form-group tg-checkbox">
								<label>
									<input type="checkbox" class="form-control">
									Remember Me
								</label>
								<a class="tg-forgot-password" href="#">
									<i>Forgot Password</i>
									<i class="fa fa-question-circle"></i>
								</a>
							</div>
							<button class="tg-btn tg-btn-lg">LOGIN now</button>
						</fieldset>
					</form>
				</div>
				<div role="tabpanel" class="tab-pane tg-haslayout" id="tg-signup-formarea">
					<form class="tg-form-modal tg-form-signup">
						<fieldset>
							<div class="form-group">
								<span class="select">
									<select>
										<option>Doctor one</option>
										<option>Doctor two</option>
										<option>Doctor three</option>
									</select>
								</span>
							</div>
							<div class="form-group">
								<span class="select">
									<select>
										<option>Specialty*</option>
										<option>Specialty two</option>
										<option>Specialty three</option>
									</select>
								</span>
							</div>
							<div class="form-group">
								<input type="text" class="form-control" placeholder="First Name">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" placeholder="last Name">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Phone Number">
							</div>
							<div class="form-group">
								<input type="email" class="form-control" placeholder="Email">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" placeholder="ZIP Code">
							</div>
							<div class="form-group tg-checkbox">
								<label>
									<input type="checkbox" class="form-control">
									I agree with the terms and conditions
								</label>
							</div>
							<button class="tg-btn tg-btn-lg">Create an Account</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!--************************************
		Sign In Sign Up Light Box End
	*************************************-->
	<script src="/live/js/vendor/jquery-library.js"></script>
	<script src="/live/js/vendor/bootstrap.min.js"></script>
	<script src="/live/js/jquery-ui.js"></script>
	<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<script src="/live/js/map/jquery.gomap.js"></script>
	<script src="/live/js/map/markerclusterer.min.js"></script>
	<script src="/live/js/countdown.js"></script>
	<script src="/live/js/jquery.nicescroll.js"></script>
	<script src="/live/js/parallax.js"></script>
	<script src="/live/js/owl.carousel.js"></script>
	<script src="/live/js/prettyPhoto.js"></script>
	<script src="/live/js/appear.js"></script>
	<script src="/live/js/countTo.js"></script>
	<script src="/live/js/map/map.js"></script>
	<script src="/live/js/main.js"></script>
</body>
</html>