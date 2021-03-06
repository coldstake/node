<?php
session_start();
require_once ('include/config.php');
require ('include/functions.php');
$wallet = new phpFunctions_Wallet();

if (isset($_POST['address'])) {
    $address = $_POST['address'];

	$get_balances = $wallet->rpc($scheme,$server_ip,$server_port,$rpc_user,$rpc_pass,'listaddressgroupings') ;
	if ( !is_array($get_balances) ) {
		die (' There was an error with your login parameters. Are your credentials correct?');
	} else {
	foreach($get_balances as $a => $b){
		foreach($b as $c => $d){
			if ($d[0] == $address) {$balance=$d[1];}
		}
	}
}}

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
											<li><a href="search.php">Check My Address</a></li>
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
								<section>
									<h3>Check My Address</h3>
								</div>
								<?php if ( (isset($_POST['address'])) && isset($balance) ){?>
								<div class="table-wrapper">
									<table>
											<thead>
												<tr>
													<th>Address</th>
													<th>Balance</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><?php echo $address;?></td>
													<td><?php echo $balance;?></td>
												</tr>
											</tbody>
											<tfoot>
											</tfoot>
										</table>
								<?php } ?> <br />
								<form method="post" action="">
										<div class="col-24">
											<input type="text" name="address" id="address" value="" placeholder="address" />
										</div>
										<br />
										<div class="col-12">
												<ul class="actions">
													<li><input type="submit" value="Search" class="primary" /></li>
													<li><input type="reset" value="Reset" /></li>
												</ul>
										</div>
								</form>
								</section>	
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