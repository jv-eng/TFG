<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Admin_Cuentas</title>
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
		<form action=<?php echo "Menu_admin.php" ?> method="POST">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="submit" name="Volver" value="Sistema de reserva de tutorías" class="inicio-button">
		</form>
	</div>

	<?php
	// $recordatorio = "";
	if (isset($_COOKIE["mail"]) && isset($_COOKIE["id_sesion"])) {
		if ($_COOKIE["mail"] != "" && $_COOKIE["id_sesion"] != "") {
			$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
			if (!$con) {
				echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
				echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
				echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
				exit;
			}
			$query = $con->prepare("SELECT id_sesion FROM `session` WHERE (`mail_profesor` = ?);");
  			mysqli_stmt_bind_param($query, "s", $_COOKIE["mail"]);
			mysqli_stmt_execute($query);
			$result = mysqli_stmt_get_result($query);
			$row = mysqli_fetch_array($result);
			mysqli_stmt_close($query);

			if ($result && $row != [] && $row["id_sesion"] == md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR'])) {
				// $recordatorio = "<p class= " . "recordatorio" . ">Usted está logeado como: " . $_COOKIE["mail"];
				setcookie("mail", $_POST["mail"], time() + 3600);	//Renovar cookie
				$time = time();
				$time_click = $time + 3600;
				setcookie("id_sesion", $row["id_sesion"], $time_click);	//Renovar cookie
				$Menu_admin = "Menu_admin.php";
			} else {
	?>
				<div class="main-container">
					<h3 class="generalseparator red">Sesión expirada. Por favor, vuelva a logearse.</h3>

					<form action="Login.php" method="POST" class="generalseparator">
						<input type="submit" name="inicio" value="Volver al menú de inicio" class="functionality-button">
					</form>

				</div>

		<?php
				// echo "<b><big>Sesión expirada. Por favor, vuelva a logearse.</big></b>";
				// $Menu_admin = "Login.php";
			}
		}
	} else {
		?>
		<div class="main-container">
			<h3 class="generalseparator red">Sesión expirada. Por favor, vuelva a logearse.</h3>

			<form action="Login.php" method="POST" class="generalseparator">
				<input type="submit" name="inicio" value="Volver al menú de inicio" class="functionality-button">
			</form>

		</div>

	<?php
		// echo "<b><big>Sesión expirada. Por favor, vuelva a logearse.</big></b>";
		// $Menu_admin = "Login.php";
	}
	?>

	<div class="main-container">

		<div class="upper-container">

			<div class="gridinf-container">
				<?php
				$resultados1 = false;
				$resultados2 = false;
				$resultados3 = false;
				$sql = "SELECT * FROM `profesor`;";
				$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');

				// Inicializamos variables y guardamos valores de los usuarios
				$buscar = "";
				$correo = "";
				$despacho = "";
				?>


			</div>

			<h3 class="generalseparator green">Profesores:</h3>

			<div class="gridinf-container">

				<?php

				$sql = "SELECT * FROM `profesor`;";
				$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');

				// Inicializamos variables y guardamos valores de los usuarios
				$buscar = "";
				$correo = "";
				$despacho = "";
				foreach ($con->query($sql) as $row) {
					$resultados2 = true;
					$buscar = $row["tbuscar"];
					$correo = $row["mail"];
					$despacho = $row["Despacho"];

				?>

					<div class="alumno-profesor-container">

						<div class="generalseparator">

							<h3 class="generalseparator marineblue"> <?php echo $buscar ?> </h3>
							<p> <span class="black"><b>Correo: </b></span><?php echo $correo ?></p>
							<p> <span class="black"><b>Despacho: </b></span><?php echo $despacho ?></p>

						</div>

						<form action=<?php echo "Borra_Cuentas.php" ?> method="POST">
							<input type="hidden" name="id_profesor" value=<?php echo $row["id_profesor"] ?>>
							<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
							<input type="hidden" name="mail_profesor" value=<?php echo $row["mail"] ?>>
							<input type="submit" name="BorrarProfesor" value="Borrar" class="functionality-button">
						</form>

					</div>

				<?php

				}
				if (!$resultados2) {

				?>

					<div></div>

					<h3 class="generalseparator red">No se ha encontrado ninguna coincidencia.</h3>

					<div></div>


				<?php

				}

				?>

			</div>

		</div>

		<div class="lower-container">

			<h3 class="generalseparator green">Alumnos:</h3>

			<div class="gridinf-container">

				<?php

				$sql = "SELECT * FROM `alumno` WHERE `mail_alumno` != 'admin@fi.upm.es'";
				$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
				// Inicializamos variables y guardamos valores de los usuarios
				$nombre_alumno = "";
				$apellidos_alumno = "";
				$correo_alumno = "";

				foreach ($con->query($sql) as $row) {
					$resultados3 = true;
					$nombre_alumno = $row["nombre_alumno"];
					$apellidos_alumno = $row["apellidos_alumno"];
					$correo_alumno = $row["mail_alumno"];

					// print "<b><big>" . $row["nombre_alumno"] . " " . $row["apellidos_alumno"] . "</big></b>\t";
					// echo "<br>";
					// print "Correo: " . $row["mail_alumno"] . " \t";
				?>

					<div class="alumno-profesor-container">

						<div class="generalseparator">

							<h3 class="generalseparator marineblue"> <?php echo $nombre_alumno ?> <?php echo $apellidos_alumno ?> </h3>
							<p> <span class="black"><b>Correo: </b></span><?php echo $correo_alumno ?></p>

						</div>

						<form action=<?php echo "Borra_Cuentas.php" ?> method="POST">
							<input type="hidden" name="idalumno" value=<?php echo $row["idalumno"] ?>>
							<input type="hidden" name="mail_alumno" value=<?php echo $row["mail_alumno"] ?>>
							<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
							<input type="submit" name="BorrarAlumno" value="Borrar" class="functionality-button">
						</form>
					</div>

				<?php

				}
				mysqli_close($con);
				?>

			</div>

			<?php

			if (!$resultados3) {

			?>
				<h3 class="generalseparator red">No se ha encontrado ninguna coincidencia.</h3>
			<?php
			}
			?>

		</div>

	</div>

	<div class="backandforthbuttons">

		<form action=<?php echo $Menu_admin ?> method="POST" class="backbutton">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="submit" name="Volver" value="Volver">
		</form>

		<form action="Login.php" method="POST" class="logoutbutton">
			<input type="submit" name="Logout" value="< Logout">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
		</form>

	</div>

	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>