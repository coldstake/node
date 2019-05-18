<?php 
require_once ('include/config.php');
require ('include/functions.php');
$wallet = new phpFunctions_Wallet();

//Check if node is online before further checks
$check_server = $wallet->ping($scheme, $server_ip, $server_port);

if ( $check_server == '' || empty($check_server) ) {
	die (' The coind server located at '. $scheme.'://'.$server_port.' on Port:['.$server_port.'] appears to be unresponsive.');
}

if ( $check_server == '' || empty($check_server) ) {
$message = <<<EOD
<ul class="icons"><label class="icon fa-circle" style='font-size:16px;color:red'>Node is offline</label></ul>
EOD;
} else {

$get_stakinginfo = $wallet->rpc($scheme,$server_ip,$server_port,$rpc_user,$rpc_pass,'getstakinginfo') ;
	if ( !is_array($get_stakinginfo) ) {
		die (' There was an error with your login parameters. Is your RPC Username and Password correct?');
	}

//if ($get_stakinginfo['staking']>0) { <<<TODO: Replace with staking test
if ($get_stakinginfo['enabled']>0) {
$message = <<<EOD
<ul class="icons"><label class="icon fa-circle" style='font-size:16px;color:green'> Staking is online</label></ul>
EOD;
} else {
$message = <<<EOD
<ul class="icons"><label class="icon fa-circle" style='font-size:16px;color:red'> Staking is offline</label></ul>
EOD;
}
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>COLDSTAKE.CO.IN</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>
	<body class="landing is-preload">

		<!-- Page Wrapper -->
			<div id="page-wrapper">
			<!-- Header -->
					<header id="header" class="alt">
					<?php print $message;?>
						<h1><a href="index.php">COLDSTAKE.CO.IN</a></h1>
						<nav id="nav">
							<ul>
								<li class="special">
									<a href="#menu" class="menuToggle"><span>Menu</span></a>
									<div id="menu">
										<ul>
											<li><a href="index.php">Home</a></li>
											<li><a href="landing.php">Order</a></li>
										</ul>
									</div>
								</li>
							</ul>
						</nav>
					</header>

				<!-- Banner -->
				<section id="banner">
						<div class="inner">
							<h2><img src="images/logo_transparent.png" alt="" width="150"/> <br/>COLDSTAKE.CO.IN</h2>
							<p>The trusted home of <br />
							cold staking<br />
							<a href="landing.php" class="more scrolly"><b>PAYMENT</b></a>
					</section>

				<!-- Footer -->
					<footer id="footer">
						<ul class="icons">
							<li><a href="https://discord.gg/YAZC9Gj" class="fab fa-discord"></a></li>
							<li><a href="mailto:admin@coldstake.co.in" class="icon fa-envelope-o"></a></li>
						</ul>
						<ul class="copyright">
						<li>&copy; COLDSTAKE.CO.IN</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
						</ul>
					</footer>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>