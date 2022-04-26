<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Admin_Consulta_citas</title>
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
	}
	?>

	<div class="main-container">

		<h2 class="generalseparator green">Calendario de citas</h2>

		<div class="gridinf-container">

			<?php
			$resultados = false;
			$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
			if (!$con) {
				echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
				echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
				echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
				exit;
			}

			$sql = "SELECT * FROM `slot` WHERE `disponible` = '0'  ORDER BY `dia`,`hora`,`minutos`;";
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
			foreach ($con->query($sql) as $row) {
			?>

				<?php
				$resultados = true;
				$query = $con->prepare( "SELECT * FROM `franja_disponibilidad` WHERE `idfranja` = ?;");
				mysqli_stmt_bind_param($query, "i",  $row["id_franja_disponibilidad"]);
				mysqli_stmt_execute($query);
				$result = mysqli_stmt_get_result($query);
				//$result = mysqli_fetch_array($result);
				mysqli_stmt_close($query);

				foreach ($result as $row2) {

					$query = $con->prepare( "SELECT `tbuscar` FROM `profesor` WHERE `id_profesor` = ?");
					mysqli_stmt_bind_param($query, "i",  $row2["id_profesor_fk"]);
					mysqli_stmt_execute($query);
					$result = mysqli_stmt_get_result($query);
					mysqli_stmt_close($query);

					foreach ($result as $row3) {
						$nombre = $row3["tbuscar"];
					}
				}

				$sql = "SELECT * FROM `alumno` WHERE `idalumno` = '" . $row["id_alumno_fk"] . "';";
				$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
				foreach ($con->query($sql) as $row3) {
					$nombre_alumno = $row3["nombre_alumno"];
					$apellidos_alumno = $row3["apellidos_alumno"];
				}
				$idslot = $row["id_slot_posicion"];
				$tipo_citas = $row2['tipo_citas'];
				$asignatura = $row2['asignatura'];
				$dia = $row['dia'];
				$hora = $row['hora'];
				$minutos = $row2['minutos'];
				if ($minutos == 0) $minutos = "00";
				if ($minutos == 5) $minutos = "05";
				$duracion = $row['duracion'];
				$ubicacion = $row2['ubicacion'];
				$notas = $row['comentarios_alumno'];

				?>
				<div class="cita-container">

					<h3 class="generalseparator marineblue"><?php echo $tipo_citas ?> : <?php echo $asignatura ?></h3>
					<p> <span class="black"><b>Día: </b></span><?php echo $dia ?> <span class="black"><b>Hora: </b></span> <?php echo $hora ?>:<?php echo $minutos ?> </p>
					<p> <span class="black"><b>Duracion: </b></span> <?php echo $duracion ?> mins <span class="black"><b>Ubicación: </b></span><?php echo $ubicacion ?></p>
					<p> <span class="black"><b>Profesor: </b></span><?php echo $nombre ?></p>
					<p> <span class="black"><b>Alumno: </b></span><?php echo $nombre_alumno ?></p>
					<p> <span class="black"><b>Notas: </b></span><?php echo $notas ?></p>

				</div>

			<?php

			}
			mysqli_close($con);
			if (!$resultados) {
				?>
				<div></div>
				<p class="generalseparator black"><b>No existen aún citas publicadas en el Sistema.</b></p>
				<div></div>


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