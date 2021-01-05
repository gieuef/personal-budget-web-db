<?php
session_start();

if (isset($_SESSION['logged_id'])) {
		header('Location: menu.php');
		exit();
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
	<link  href="main.css" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
	
</head>


<body>

		<main>
		
	    <section>
		
		<header>
		
		<h1 class="mx-auto text-center mt-5">
			<span style="color: #daa520">Pełny</span>Portfel.com
		</h1>
		</header>
		
	

		<div class="container-fluid square">
		
		<div class="col-sm-8 col-md-6 col-lg-4 mx-auto text-center">
		
		<form method="post" action="menu.php">
				
			<input class="form-control" type="mail" placeholder="e-mail" onfocus="this.placeholder=''" onblur="this.placeholder='email'" name="login">
			
			<input class="form-control" type="password" placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'" name="pass">
						
			<input class="btn" type="submit" value="Zaloguj się">
			
			<?php
			if (isset($_SESSION['bad_attempt'])) {
				echo '<p>Niepoprawny login lub hasło!</p>';
				unset($_SESSION['bad_attempt']);
			}
			?>
			
		</form>
		
		</div>

		
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