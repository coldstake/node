<?php
session_start();
require_once ('include/config.php');
require ('include/functions.php');
$wallet = new phpFunctions_Wallet();

if ( $_SESSION['Address'] == '' || empty($_SESSION['Address']) || 
	 $_SESSION['OrderID'] == '' || empty($_SESSION['OrderID']) || 
	 $_SESSION['Price'] == '' || empty($_SESSION['Price']) || 
	 $_SESSION['Days_Online'] == '' || empty($_SESSION['Days_Online']) || 
	 $_SESSION['InvoiceID'] == '' || empty($_SESSION['InvoiceID']) ) {
	die (' The session has expired - please try again.');
}


//Check if node is online before further checks
$check_server = $wallet->ping($scheme, $server_ip, $server_port);

if ( $check_server == '' || empty($check_server) ) {
	die (' The server appears to be unresponsive.');
}

if ( $check_server == '' || empty($check_server) ) {
$message = <<<EOD
	<li><a href=""class="icon fa-circle" style='color:red'>Node offline</a></li>
EOD;
} else {

$get_stakinginfo = $wallet->rpc($scheme,$server_ip,$server_port,$rpc_user,$rpc_pass,'getstakinginfo') ;
	if ( !is_array($get_stakinginfo) ) {
		die (' There was an error with your login parameters. Are your credentials correct?');
	}

if ($get_stakinginfo['staking']>0) {
$message = <<<EOD
<li><a href=""class="icon fa-circle" style='color:green'>Staking online</a></li>
EOD;
} else {
$message = <<<EOD
<li><a href=""class="icon fa-circle" style='color:red'>Staking offline</a></li>
EOD;
}
}

//Check if invoice paid
$OrderPaid = $wallet->GetInvoiceStatus ($_SESSION['InvoiceID'],$_SESSION['OrderID']);
if ( $OrderPaid == 'FAIL' ) {
	die ('Payment not successful - please try again');
}

?>
<!DOCTYPE HTML>
<html>
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
					<h1><a href="index.php">COLDSTAKE.CO.IN</a></h1>
						<nav id="nav">
							<ul>
								<li class="special">
									<a href="#menu" class="menuToggle"><span>Menu</span></a>
									<div id="menu">
										<ul>
											<?php print $message;?>
											<li><a href="index.php">Home</a></li>
											<li><a href="about.html">FAQ</a></li>
											<li><a href="search.php">Search</a></li>
										</ul>
									</div>
								</li>
							</ul>
						</nav>
					</header>

				<!-- Main -->
					<article id="main">
						<header>
							<img src="images/coin_logo-<?php print $ticker; ?>.png" alt="" width="200"/>
						</header>
							<section class="wrapper style5">
								<div class="inner">
								<h3>ORDER #<?php print $_SESSION['OrderID'];?></h3>
								<p>Thank you for your payment - before you get started, open your local wallet and ensure it's fully synced.</p><br>
								<p>Here is your unique cold staking address please enter in your local wallet when prompted: <pre><code><?php print $_SESSION['Address']; ?></code></pre></p>
								</div>
							</section>
					</article>

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