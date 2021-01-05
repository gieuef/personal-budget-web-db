<?php
session_start();

require_once 'database.php';

if (!isset($_SESSION['logged_id'])) {
	
	if (isset($_POST['login'])) {
		
		$login = filter_input(INPUT_POST, 'login');
		$password = filter_input(INPUT_POST, 'pass');
		
		$userQuery = $db->prepare('SELECT id, username, password, email FROM users WHERE email = :login');
		$userQuery->bindValue(':login', $login, PDO::PARAM_STR);
		$userQuery->execute();
		
		$user = $userQuery->fetch();
		
		
		if ($user && $password==$user['password']){
		//password_verify($password, $user['password']) 
			$_SESSION['logged_id'] = $user['id'];
			unset($_SESSION['bad_attempt']);
		} else {
			$_SESSION['bad_attempt'] = true;
			header('Location: logging.php');
			exit();
		}
		
	} else {
		
		header('Location: logging.php');
		exit();
	
	}
}

?>



<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	
	<title>Budżet osobisty</title>
	
	<meta name="description" content="Aplikacja do prowadzenia budżetu osobistego" />
	<meta name="keywords" content="budżet osobisty, wydatki, planowanie budżetu, oszczędzanie" />
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link  href="css/fontello.css" rel="stylesheet" type="text/css" />
	<link  href="main.css" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
	
</head>


<body>
 
		
		<header>
		
		<h1 class="mx-auto text-center mt-5">
			<span style="color: #daa520">Pełny</span>Portfel.com
		</h1>
		</header>
		
		<main>
		
	
		<section>

		<div class="container-fluid menu">
		
		<!--<div class="col-sm-8 col-md-6 col-lg-4 mx-auto text-center"> -->
		
		<nav class="navbar navbar-light bg-menu navbar-expand-md">
		
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu"> 
				<span class="navbar-toggler-icon"> </span> 
			</button>
			
			<div class="collapse navbar-collapse" id="mainmenu">
			
				<ul class="navbar-nav mx-auto">
				
					<li class="nav-item">
						<a class="nav-link" href="#" > <i class="icon-dollar"></i>  Dodaj przychód </a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="#" > <i class="icon-basket"></i> Dodaj wydatek</a>
					</li>
					
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button"> <i class="icon-chart-pie"></i> Przeglądaj bilans</a>
					
						<div class="dropdown-menu">
						
							<a class="dropdown-item" href="#"> Bieżący miesiąc</a>
							<a class="dropdown-item" href="#"> Poprzedni miesiąc</a>
							<a class="dropdown-item" href="#"> Bieżący rok </a>
							<a class="dropdown-item" href="#"> Niestandardowy</a>
							
						
						</div>
						
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="#" > <i class="icon-cogs"></i> Ustawienia </a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="logout.php" ><i class="icon-logout"></i> Wyloguj się </a>
					</li>
				
				
				</ul>
			
			</div>
		
		</nav>
		
		

		
		</div>
		
		</section>

		</main>

		

		<footer class="footer mt-5">
	Pełnyportfel.com &copy; 2020
		
		</footer>

		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	
		<script src="js/bootstrap.min.js"></script>

</body>

</html>