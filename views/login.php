<!DOCTYPE>
<html>
	<head>
		<title><?php echo $title ?></title>
	</head>
	<body>
		<h1>Login</h1>

		<form id="login" action="?action=login" method="POST" class="login-form"> <!--Formulari del login-->
			<label>NIU:</label>
			<input type="text" name="niu" required/><!--NIU-->

			<label>Password:</label>
  			<input type="password" name="password" required/><!--PASSWORD-->


  			<input type="submit" value="Login">
		</form>
		<?php 
		if(isset($errors)) {//Mostrar els errors del POST login si hi han
			foreach ($errors as $error) {
			?>
			<p><?php echo $error ?></p>
			<?php
			}
		}
		?>
	</body>
</html>
