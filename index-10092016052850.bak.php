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
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/live/js/bootstrap.min.js"></script>
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
					</ol>

					<!-- Wrapper for slides -->
					<div class="carousel-inner" role="listbox">
					  <div class="item active">
						<img src="/live/images/banner-image.jpg">
						<div class="carousel-caption">
							<h2>Welcome to DocNow</h2>
							<p>Finding a doctor near you has never been this easy</p>
						</div>
					  </div>

					  <div class="item">
						<img src="/live/images/banner-image.jpg">
						<div class="carousel-caption">
							<h2>Welcome to DocNow</h2>
							<p>Finding a doctor near you has never been this easy</p>
						</div>
					  </div>
					
					  <div class="item">
						<img src="/live/images/banner-image.jpg">
						<div class="carousel-caption">
							<h2>Welcome to DocNow</h2>
							<p>Finding a doctor near you has never been this easy</p>
						</div>
					  </div>

					  <div class="item">
						<img src="/live/images/banner-image.jpg">
						<div class="carousel-caption">
							<h2>Welcome to DocNow</h2>
							<p>Finding a doctor near you has never been this easy</p>
						</div>
					  </div>
					</div>

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
						<form class="tg-searchform">
							<div class="col-md-9">
								<!--Upper Row-->
								<div class="col-md-6">
									<div class="form-group">
										<span class="select">
											<select id="specialty" class="group">
												<option>Specialty</option>
											</select>
										</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<span class="select">
											<select id="country" class="group">
												<option>Country</option>
											</select>
										</span>
									</div>
								</div>
								
								<!--Lower Row-->
								<div class="col-md-6">
									<div class="form-group">
										<span class="select">
											<select id="Province" class="group">
												<option>Province</option>
											</select>
										</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<span class="select">
											<select id="city" class="group">
												<option>City</option>
											</select>
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="col-md-12">
									<div class="form-group">
										<a id="search_banner" class="tg-btn tg-btn-lg" href="#">Search</a>
									</div>
								</div>
							</div>
							
							
						</form>
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
							<div class="col-lg-6 col-md-5 col-sm-12 col-xs-12 pull-left">
								<div class="tg-contentbox tg-haslayout">
									<div class="tg-heading-border">
										<h2>Why DocNow</h2>
									</div>
									<div class="tg-description">
										<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat wisi enim ad minim veniam quis nostrud.</p>
									</div>
									<ul>
										<li>Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum.</li>
										<li>Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum.</li>
										<li>Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming.</li>
									</ul>
								</div>
							</div>
							<div class="col-lg-6 col-md-7 col-sm-12 col-xs-12 pull-right">
								<div class="col-md-12" style="padding-top: 60px;">
									<img src="/live/images/AppStore.png" class="img-responsive pull-left" style="border: none;" /><img src="/live/images/GooglePlay.png" class="img-responsive pull-right" style="border: none;" />
										
								</div>
								
							</div>
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
				<div class="container">
					<div class="row">
						<div class="col-sm-10 col-sm-offset-1 col-xs-12">
							<div class="tg-theme-heading">
								<h2>Specialties</h2>
								<span class="tg-roundbox"></span>
								<div class="tg-description">
									<p>"Every mountain top is within reach if you just keep climbing." <i>Richard James Molloy</i></p>
								</div>
							</div>
						</div>
						<div class="tg-search-categories">
							<div class="col-sm-4 col-xs-12 tg-expectwidth">
								<div class="tg-search-category">
									<div class="tg-displaytable">
										<div class="tg-displaytablecell">
											<div class="tg-box">
												<h3>DENTAL THRAPY AND ORAL OHYGIENE</h3>
												<i class="icon-stethoscope"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-xs-12 tg-expectwidth">
								<div class="tg-search-category">
									<div class="tg-displaytable">
										<div class="tg-displaytablecell">
											<div class="tg-box">
												<h3>DIETETICS AND NUTRITION</h3>
												<i class="icon-healthcare"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-xs-12 tg-expectwidth">
								<div class="tg-search-category">
									<div class="tg-displaytable">
										<div class="tg-displaytablecell">
											<div class="tg-box">
												<h3>EMERGENCY CARE</h3>
												<i class="icon-capsules"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						
						<div class="tg-search-categories" style="padding-top: 20px;">
							<div class="col-sm-4 col-xs-12 tg-expectwidth">
								<div class="tg-search-category">
									<div class="tg-displaytable">
										<div class="tg-displaytablecell">
											<div class="tg-box">
												<h3>MEDICAL DENTAL</h3>
												<i class="icon-stethoscope"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-xs-12 tg-expectwidth">
								<div class="tg-search-category">
									<div class="tg-displaytable">
										<div class="tg-displaytablecell">
											<div class="tg-box">
												<h3>MEDICAL TECHNOLOGY</h3>
												<i class="icon-healthcare"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-xs-12 tg-expectwidth">
								<div class="tg-search-category">
									<div class="tg-displaytable">
										<div class="tg-displaytablecell">
											<div class="tg-box">
												<h3>OCCUPATIONAL THERAPY, MEDICAL ORTHOTICS, PROSTHETICS & ARTS THERAPY</h3>
												<i class="icon-capsules"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<br><br>
						</div>
						
						<div class="tg-search-categories" style="padding-top: 20px;">
							<div class="col-sm-4 col-xs-12 tg-expectwidth">
								<div class="tg-search-category">
									<div class="tg-displaytable">
										<div class="tg-displaytablecell">
											<div class="tg-box">
												<h3>OPTOMETRY & DISPENSING OPTICIANS</h3>
												<i class="icon-stethoscope"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-xs-12 tg-expectwidth">
								<div class="tg-search-category">
									<div class="tg-displaytable">
										<div class="tg-displaytablecell">
											<div class="tg-box">
												<h3>PHYSIOTHERAPY, PODIATRY AND BIOKINETICS</h3>
												<i class="icon-healthcare"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-xs-12 tg-expectwidth">
								<div class="tg-search-category">
									<div class="tg-displaytable">
										<div class="tg-displaytablecell">
											<div class="tg-box">
												<h3>OTHER</h3>
												<i class="icon-healthcare"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<br><br>
						</div>
						
					</div>
				</div>
			</section>
			<!--************************************
					What to Expect End
			*************************************-->
			<!--************************************
					Are You A Doctor Start
			*************************************-->
			<section class="tg-main-section tg-haslayout parallax-window tg-custom-padding" data-appear-top-offset="600" data-parallax="scroll" data-image-src="/live/images/bgparallax/bgparallax-01.jpg">
				<div class="container">
					<div class="row">
						<div class="tg-areuadoctor tg-haslayout">
							<div class="col-lg-6 col-md-5 col-sm-12 col-xs-12 pull-right">
								<div class="tg-contentbox tg-haslayout">
									<div class="tg-heading-border">
										<h2>are you a doctor?</h2>
										<h3>register your practice now and reach thousands of patients</h3>
									</div>
									<div class="tg-description">
										<p>We know how much it takes to become a qualified doctor so we have taken away all your hassle to look out for your patients once you have qualified. DocDirect will give you an easy reach to all the patients, all you need is to sign up!</p>
									</div>
									<a class="tg-btn" href="#">list you practice now</a>
								</div>
							</div>
							<div class="col-lg-6 col-md-7 col-sm-12 col-xs-12 pull-left">
								<figure class="tg-img">
									<img src="/live/images/img-01.png" alt="image description">
								</figure>
							</div>
						</div>
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