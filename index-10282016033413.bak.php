<? include_once ("pagebuilder/template-freeform.php"); ?>
<!doctype html>
<!--[if lt IE 7]>		<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>			<html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>			<html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->	<html class="no-js" lang=""> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>DocNow</title>
	<meta name="description" content="">
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
	
	<!--Slider-->
	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7//live/css/bootstrap.min.css">-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="/live/js/custom.js"></script>

	
	<!--End Slider-->
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
		<header id="header" class="tg-haslayout">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<strong class="logo">
							<a href=""><img src="/live/images/logo.png" alt="image description"></a>
						</strong>
						<nav id="tg-nav" class="tg-nav">
							<? ShowContainer (1); ?>
						</nav>
					</div>
				</div>
			</div>
		</header>
		<div class="flash-messages">
			<? ShowContainer (9); ?>
		</div>
		<!--************************************
				Header End
		*************************************-->
		<!--************************************
				Home Slider Start
		*************************************-->
		<div id="tg-homebanner" class="tg-homebanner tg-haslayout">
			<div class="tg-haslayout">
				<div id="myCarousel" class="carousel slide" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators">
					  <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
					  <li data-target="#myCarousel" data-slide-to="1"></li>
					  <li data-target="#myCarousel" data-slide-to="2"></li>
					  <li data-target="#myCarousel" data-slide-to="3"></li>
					  <li data-target="#myCarousel" data-slide-to="3"></li>
					</ol>

					<!-- Wrapper for slides -->
					<? ShowContainer (4); ?>

					<!-- Left and right controls -->
					<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
					  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					  <span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
					  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					  <span class="sr-only">Next</span>
					</a>
				</div>
			</div>
			<div class="tg-searcharea tg-haslayout">
				<div class="container">
					<div class="row">
						<? ShowContainer (7); ?>
					</div>
				</div>
			</div>
			<div class="show-search"><i class="fa fa-search"></i></div>
		</div>
		<!--************************************
				Home Slider End
		*************************************-->
		<!--************************************
				Main Start
		*************************************-->
		<main id="main" class="tg-haslayout">
			<!--************************************
					Why DocNow
			*************************************-->
			<section class="tg-padding-top tg-haslayout">
				<div class="container">
					<div class="row">
						<div class="tg-healthcareonthego tg-haslayout">
							<? ShowContainer (5); ?>
						</div>
					</div>
				</div>
			</section>
			<!--************************************
					Why DocNow End
			*************************************-->
			
			<!--************************************
					Specialties
			*************************************-->
			<section class="tg-main-section tg-haslayout">
				<? ShowContainer (10); ?>
			</section>
			<!--************************************
					What to Expect End
			*************************************-->
			<!--************************************
					Are You A Doctor Start
			*************************************-->
			<section class="tg-main-section tg-haslayout tg-custom-padding" data-appear-top-offset="600" data-parallax="scroll" >
				<div class="container">
					<div class="row">
						<? ShowContainer (6); ?>
					</div>
				</div>
			</section>
			<!--************************************
					Specialties End
			*************************************-->
			
			
		</main>
		<!--************************************
				Main End
		*************************************-->
		<!--************************************
				Footer Start
		*************************************-->
		<footer id="footer" class="tg-haslayout">
			<? ShowContainer (2); ?>
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
					<? ShowContainer (8); ?>
				</div>
				<div role="tabpanel" class="tab-pane tg-haslayout" id="tg-signup-formarea">
					<? ShowContainer (3); ?>
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
<!--<script src="http://maps.google.com/maps/api/js?sensor=false"></script>-->	
    <!--<script src="/live/js/map/jquery.gomap.js"></script>
	<script src="/live/js/map/markerclusterer.min.js"></script>-->
	<script src="/live/js/countdown.js"></script>
	<script src="/live/js/jquery.nicescroll.js"></script>
	<script src="/live/js/parallax.js"></script>
	<script src="/live/js/owl.carousel.js"></script>
	<script src="/live/js/prettyPhoto.js"></script>
	<script src="/live/js/appear.js"></script>
	<script src="/live/js/countTo.js"></script>
<!--<script src="/live/js/map/map.js"></script>-->	
    <script src="/live/js/main.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			$("#patient").hide();
			$("#doctor").hide();
			$("#doc-link").click(function(){
				$("#patient").hide();
				$("#doctor").show();
			});
			$("#pat-link").click(function(){
				$("#patient").show();
				$("#doctor").hide();
			});
		});
	</script>
</body>
</html>