<?php 
session_start(); 
require ('/var/secure/keys.php');
require_once ('include/config.php');
require ('include/functions.php');
$wallet = new phpFunctions_Wallet();

// Set  price

$now = new DateTime();
$end_date = new DateTime(date($service_end_date));
$difference = $now->diff($end_date);
$days_remaining = $difference->d;
$service_price = ceil(($price / $online_days) * $days_remaining);

print_r ($now);
echo '<br>';
print_r ($end_date);
echo '<br>';
print_r ($difference);
echo '<br>';
echo '<br>' . $price;
echo '<br>' . $online_days;
echo '<br>' . $days_remaining;

$_SESSION['Price']=$service_price;

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
		<script src='https://www.google.com/recaptcha/api.js?render=<?php echo $captcha_site_key; ?>'></script>
        <script>
            grecaptcha.ready(function () {
                grecaptcha.execute('<?php echo $captcha_site_key; ?>', { action: 'payment' }).then(function (token) {
                    var recaptchaResponse = document.getElementById('recaptchaResponse');
					console.log(recaptchaResponse)
                    recaptchaResponse.value = token;
                });
            });
        </script>
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
											<li><a href="about.html">About</a></li>
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
							cold staking<br /></p>
						</div>

					<form method="post" action="landing.php">
            					<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
								<input type="submit" class="button icon fa-shopping-cart" value="$$_SESSION['Price'] - Pay Now" />
					</form>

					</section>
				<!-- One -->
				<section id="one" class="wrapper style1 special">
						<div class="inner">
							<header class="major">
								<h2>Full Node as a Service</h2>
								<p>Effortless cold staking with no technical knowledge required</p>
							</header>
							<ul class="icons major">
								<li><span class="icon fa-diamond major style1"><span class="label">Lorem</span></span></li>
								<li><span class="icon fa-heart-o major style2"><span class="label">Ipsum</span></span></li>
								<li><span class="icon fa-code major style3"><span class="label">Dolor</span></span></li>
							</ul>
						</div>
					</section>

				<!-- Two -->
					<section id="two" class="wrapper alt style2">
						<section class="spotlight">
							<div class="image"><img src="images/pic01.jpg" alt="" /></div><div class="content">
								<h2>Cold Staking</h2>
								<p>
								Cold Staking lets users securely delegate staking powers to “staking nodes” which contain no coins. The purpose of these “staking nodes” is to provide a dedicated resource connected to a blockchain network and stake on behalf of another wallet without being able to spend its coins. In other words, it allows users to stake offline coins.
								</p>
							</div>
						</section>
						<section class="spotlight">
							<div class="image"><img src="images/pic02.jpg" alt="" /></div><div class="content">
								<h2>Benefits of staking</h2>
								<p>Staking enables coin holders to earn compounding rewards in return for freezing their staked coins so they cannot be otherwise used while they are being staked. Stack those coins!</p>
							</div>
						</section>
						<section class="spotlight">
							<div class="image"><img src="images/pic03.jpg" alt="" /></div><div class="content">
								<h2>Withdraw at anytime<br />
								No Penalties</h2>
								<p>Staked Coins aren't kept on the full node, so you and only you can withdraw your coins back to your wallet at anytime. No fee's or penalties.</p>
							</div>
						</section>
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