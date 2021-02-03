<?php

	session_start();
	
	require_once 'database.php';
	
	if (isset($_POST['amount']))
	{
		$result = $db->prepare('INSERT INTO expenses VALUES (NULL,:user_id,:expenses_category_assigned_to_users_id,:payment_methods_assigned_to_users_id,:amount,:date_of_expense,:expense_comment)');
		$result->bindValue(':user_id',$_SESSION['logged_id'],PDO::PARAM_INT);
		$result->bindValue(':expenses_category_assigned_to_users_id', $_POST['expense_category'] ,PDO::PARAM_INT);
		$result->bindValue(':payment_methods_assigned_to_users_id', $_POST['payment_method'] ,PDO::PARAM_INT);
		$result->bindValue(':amount',$_POST['amount']);
		$result->bindValue(':date_of_expense',$_POST['date_of_expense']);
		$result->bindValue(':expense_comment',$_POST['expense_comment']);
			
		$result->execute();
		
		$added = true;
		
	}
	
	$result = $db->prepare('SELECT id,name FROM payment_methods_assigned_to_users WHERE user_id= :logged_id');
	$result->bindValue(':logged_id',$_SESSION['logged_id'],PDO::PARAM_INT);
	$result->execute();
		
	$payment_methods = $result->fetchAll();
	
	$result = $db->prepare('SELECT id,name FROM expenses_category_assigned_to_users WHERE user_id= :logged_id');
	$result->bindValue(':logged_id',$_SESSION['logged_id'],PDO::PARAM_INT);
	$result->execute();
	
	$expenses_category = $result->fetchAll();
	
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
						<a class="nav-link" href="add_income.php" > <i class="icon-dollar"></i>  Dodaj przychód </a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="add_expense.php" > <i class="icon-basket"></i> Dodaj wydatek</a>
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
		
		<section>
		
		<div class="container-fluid my-4 py-4">
		
				<?php
					if (isset($added) && ($added)) echo '<h2>Dodano wydatek</h2>';
				
				?>
				
				
					<form class="col-sm-10 col-md-8 col-lg-6 py-3 mx-auto square"  method="post" enctype="multipart/form-data">
						
						
					 <div class="row justify-content-around">	
						
						<div class="col-sm-10 col-lg-8">
							<label> Kwota <input type="number" name="amount" step="0.01"></label>
						</div>
						
						<div class="col-sm-10 col-lg-8">
							<label> Data <input type="date" name="date_of_expense" id="today" ></label>
						</div>						
										
						<script>
						document.getElementById('today').value = new Date().toISOString().substring(0, 10);
						</script>
						
					<!--	<div class="col-sm-10 col-lg-8"> Sposób płatności:
							<div><label><input type="radio" value="1" name="platnosc" checked> Gotówka </label></div>
							<div><label><input type="radio" value="2" name="platnosc"> Karta debetowa </label></div>
							<div><label><input type="radio" value="3" name="platnosc"> Karta kredytowa </label></div>
						</div>
					-->



						<div class="col-sm-10 col-lg-8"> Sposób płatności:
						
						<?php
						foreach ($payment_methods as $method)
						{
							echo '<div class="form-check">
								<input class="form-check-input" type="radio" value="'. $method['id'] .'" name="payment_method">
								<label class="form-check-label" for="payment_method">
									'. $method['name'] .'
								</label>					
							</div>';
						}
						?>
						</div>
					
						<div class="col-sm-10 col-lg-8">
							<label>Kategoria:<label/>
							<select class="form-select" name="expense_category" >
								<?php
								foreach ($expenses_category as $method)
								{
									echo '<option value="'. $method['id'] .'">
									'. $method['name'] .'
									</option>';
								
								}
								?>
							</select>
							
						</div>
						
						
						
						
						<div class="col-sm-10 col-lg-8">
							<div><label for="komentarz"> Komentarz(opcjonalnie): </label></div>
								<textarea name="expense_comment" id="komentarz" rows="4" maxlength="100" ></textarea>
						</div>
						
						<div class="col-sm-10 col-lg-8">
					<button class="btn" type="submit">Zapisz</button>
					<button class="btn" type="reset">Anuluj</button>
				
				</div>
				
				</div>
				
					</form>
					
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