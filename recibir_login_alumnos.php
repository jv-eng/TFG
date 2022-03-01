<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>recibir_login_alumnos</title>
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

		<a href="https://www.upm.es/" target="_blank"><img src="imagenes/Logo_UPM.png"></a>
		<a href="https://www.fi.upm.es/" target="_blank"><img src="imagenes/logo_etsiinf_transparente.png"></a>


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
		$sql = "SELECT password FROM `alumno` WHERE (`mail_alumno` = '" . $_POST["mail"] . "');";
		//echo $sql."<br>";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		while ($row = mysqli_fetch_array($result)) {
			$password_guardada = $row["password"];
		}

		if (strcmp($password_guardada, $_POST["password"]) !== 0) {

		?>
			<p class="generalseparator red"><b>El correo o la contraseña introducidos son incorrectos.</b></p>;

		<?php

		} else {

		?>
			<p class="generalseparator green"><b>Login realizado correctamente.</b></p>

		<?php

			$alumno_logeado = true;
		}
		mysqli_close($con);
		if ($alumno_logeado) {
			$href = "Alumno_menu.html?mail=" . $_POST["mail"];
			$mail = $_POST["mail"];
		}
		?>

	</div>

	<br><br>

	<div class="backandforthbuttons">

		<form action="Login_alumnos.html" method="POST" class="backbutton">
			<input type="submit" name="Volver" value="Volver">
		</form>

	</div>

	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>