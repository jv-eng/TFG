<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>recibir_login_Profesor</title>
	<link rel="stylesheet" href="./style.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,maximun-sacale=1,munimun-sacale=1">

</head>

<script>
	function todayDate() {
		var d = new Date();
		var n = d.getFullYear() + "  ";
		return document.getElementById("date").innerHTML = n;
	}
</script>

<body onload="todayDate()">

<header>

<div align="left"><a href="https://www.upm.es/" target="_blank"><img src="imagenes/logo_copia.png"></a></div>

<div align="right"><a href="https://www.fi.upm.es/" target="_blank"><img src="imagenes/logo_etsiinf_transparente.png"></a></div>

</header>


	<div class="titulo">
		<h1>Sistema de reserva de tutorías</h1>
	</div>

	<div class="main-container">
		<?php

		$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
		if (!$con) {
			echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
			echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
			echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}

		$query = $con->prepare("SELECT * FROM `profesor` WHERE (`mail` = ?);");
		mysqli_stmt_bind_param($query, "s", $_POST["mail"]);
		mysqli_stmt_execute($query);
		$result = mysqli_stmt_get_result($query);
		mysqli_stmt_close($query);

		foreach ($result as $row) {
			$password_guardada = $row["password"];
			$id_profesor = $row["id_profesor"];
		}

		if (strcmp($password_guardada, $_POST["password"]) !== 0) {

		?>
			<p class="generalseparator red"><b>El correo o la contraseña introducidos son incorrectos.</b></p>;

		<?php

		} else {

		?>
			<p class="generalseparator green"><b>Login realizado correctamente.</b></p>

		<?php
		}
		mysqli_close($con);
		?>

	</div>

	<div class="backandforthbuttons">

		<form action="Login_profesores.html" method="POST" class="backbutton">
			<input type="submit" name="Volver" value="Volver">
		</form>

	</div>

	<form action="Profesor_menu.php" method="POST">
		<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
		<input type="hidden" name="id" value=<?php echo $id ?>>
		<input type="submit" name="Continuar" value="Continuar" class="functionality-button">
	</form>

	<footer>
		&copy; <em id="date"></em> UPM
	</footer>
</body>

</html>