<?php

	session_start();
	
	if (isset($_POST['email']))
	{
		$all_OK = true;
		
		$nick = $_POST['nick'];
		
		if ((strlen($nick)<3) || (strlen($nick)>20))
		{
			$all_OK = false;
			$_SESSION['e_nick'] = "Nick musi posiadać od 3 do 20 znaków";
		}
		
		if (ctype_alnum($nick) == false)
		{
			$all_OK = false;
			$_SESSION['e_nick'] = "Nick może składać się tylko z liter i cyfr (bez polskich znaków)";
		}
		
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		
		if (empty($email))
		{
			$all_OK = false;
			$_SESSION['e_email'] = "Podaj poprawny adres e-mail";
		}
		
		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];
		
		if ((strlen($pass1)<5) || (strlen($pass1)>20))
		{
			$all_OK = false;
			$_SESSION['e_pass'] = "Hasło musi posiadać od 5 do 20 znaków";
		}
		
		if ($pass1 != $pass2)
		{
			$all_OK = false;
			$_SESSION['e_pass'] = "Podane hasła nie są identyczne";
		}
		
		$pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
		
		$_SESSION['fr_nick'] = $nick;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_pass1'] = $pass1;
		$_SESSION['fr_pass2'] = $pass2;
		
		require_once 'database.php';
		
		
		$userQuery = $db->prepare('SELECT id, username, password, email FROM users WHERE username = :nick');
		$userQuery->bindValue(':nick', $nick, PDO::PARAM_STR);
		$userQuery->execute();
		
		$result = $userQuery->rowCount();
		
		if ($result)
		{
			$all_OK = false;
			$_SESSION['e_nick'] = "Istnieje już taki nick. Wybierz inny.";
		}
		
		
		$userQuery = $db->prepare('SELECT id, username, password, email FROM users WHERE email = :email');
		$userQuery->bindValue(':email', $email, PDO::PARAM_STR);
		$userQuery->execute();
		
		$result = $userQuery->rowCount();
		
		if ($result)
		{
			$all_OK = false;
			$_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu e-mail";
		}
		
		
		if ($all_OK )
		{
			$query = $db->prepare('INSERT INTO users VALUES (NULL, :username, :pass, :email)');
			$query->bindValue(':username', $nick, PDO::PARAM_STR);
			$query->bindValue(':pass', $pass_hash, PDO::PARAM_STR);
			$query->bindValue(':email', $email, PDO::PARAM_STR);
			$query->execute();
			
			$_SESSION['success'] = true;
			
			$last_id = $db->lastInsertId();
			
			$query = $db->prepare('INSERT INTO payment_methods_assigned_to_users (`user_id`,`name`) SELECT :user_id,name FROM payment_methods_default');
			$query->bindValue(':user_id', $last_id, PDO::PARAM_INT);
			$query->execute();
			
			$query = $db->prepare('INSERT INTO expenses_category_assigned_to_users (`user_id`,`name`) SELECT :user_id,name FROM expenses_category_default');
			$query->bindValue(':user_id', $last_id, PDO::PARAM_INT);
			$query->execute();
			
			$query = $db->prepare('INSERT INTO incomes_category_assigned_to_users (`user_id`,`name`) SELECT :user_id,name FROM incomes_category_default');
			$query->bindValue(':user_id', $last_id, PDO::PARAM_INT);
			$query->execute();
			
			
			header('Location: witamy.php');
			
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
		
		<form method="post">
			
			<input  class="form-control" type="text" placeholder="<?php
				if (isset($_SESSION['fr_nick']))
				{
					echo $_SESSION['fr_nick'];
					unset($_SESSION['fr_nick']);
				}
				else echo 'Imię';
			
			?>"	onfocus="this.placeholder=''" onblur="this.placeholder='Imię'" name="nick"> 
			
			<?php if (isset($_SESSION['e_nick']))
				{
					echo '<span style="color:red">'.$_SESSION['e_nick'].'</span>';
					unset($_SESSION['e_nick']);
				}
			?>
			
			<input class="form-control" type="mail" placeholder="<?php
				if (isset($_SESSION['fr_email']))
				{
					echo $_SESSION['fr_email'];
					unset($_SESSION['fr_email']);
				}
				else echo 'e-mail';
			
			?>"	onfocus="this.placeholder=''" onblur="this.placeholder='email'" name="email"> 
			
			<?php if (isset($_SESSION['e_email']))
				{
					echo '<span style="color:red">'.$_SESSION['e_email'].'</span>';
					unset($_SESSION['e_email']);
				}
			?>
			
			<input class="form-control" type="password" value="<?php
				if (isset($_SESSION['fr_pass1']))
				{
					echo $_SESSION['fr_pass1'];
					unset($_SESSION['fr_pass1']);
				}
			
			?>"	placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'" name="pass1"> 
			
			<?php if (isset($_SESSION['e_pass']))
				{
					echo '<span style="color:red">'.$_SESSION['e_pass'].'</span>';
					unset($_SESSION['e_pass']);
				}
			?>
			
			<input class="form-control" type="password" value="<?php
				if (isset($_SESSION['fr_pass2']))
				{
					echo $_SESSION['fr_pass2'];
					unset($_SESSION['fr_pass2']);
				}
				
			
			?>"	placeholder="Powtórz hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Powtórz hasło'" name="pass2"> 
			
			<input class="btn" type="submit" value="Rejestracja">
			
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