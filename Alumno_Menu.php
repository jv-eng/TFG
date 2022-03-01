<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Menú alumno</title>
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

	<?php
	$bienvenido = "";
	$notificaciones2 = 0;
	if (isset($_COOKIE["mail"]) && isset($_COOKIE["id_sesion"])) {
		if ($_COOKIE["mail"] != "" && $_COOKIE["id_sesion"] != "") {
			$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
			if (!$con) {
				echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
				echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
				echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
				exit;
			}
			$sql = "SELECT id_sesion FROM `session` WHERE (`mail_alumno` = '" . $_COOKIE["mail"] . "');";
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
			$row = mysqli_fetch_array($result);

			if ($result && $row != [] && $row["id_sesion"] == md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR'])) {

				$sql2 = "SELECT nombre_alumno FROM `alumno` WHERE (`mail_alumno` = '" . $_COOKIE["mail"] . "');";
				$result2 = mysqli_query($con, $sql2) or die('Error en la consulta a la BDD aaaaa');
				$row2 = mysqli_fetch_array($result2);
				$bienvenido = $row2["nombre_alumno"];

				$sql3 = "SELECT COUNT(id_notificaciones_alumno) AS 'count' FROM `notificaciones_alumno` WHERE (`mail_alumno` = '" . $_COOKIE["mail"] . "');";
				$result3 = mysqli_query($con, $sql3) or die('Error en la consulta a la BDD aaaaa');
				$row3 = mysqli_fetch_array($result3);
				$notificaciones2 = $row3["count"];

				setcookie("mail", $_POST["mail"], time() + 3600);	//Renovar cookie
				$time = time();
				$time_click = $time + 3600;
				setcookie("id_sesion", $row["id_sesion"], $time_click);	//Renovar cookie
				if ($_COOKIE["mail"] == "admin@fi.upm.es") {
					$admin = true;
					$back_page = "Menu_admin.php";
				} else {
					$admin = false;
					$back_page = "Login.php";
				}
				$Alumno_Buscador = "Alumno_Buscador.php";
				$Alumno_consulta_citas = "Alumno_consulta_citas.php";
				$Alumno_buzon = "Alumno_buzon.php";
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
				// $Alumno_Buscador = "Login.php";
				// $Alumno_consulta_citas = "Login.php";
				// $Alumno_buzon = "Login.php";
				// $back_page = "Login.php";
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
		// $Alumno_Buscador = "Login.php";
		// $Alumno_consulta_citas = "Login.php";
		// $Alumno_buzon = "Login.php";
		// $back_page = "Login.php";
	}
	?>

	<div class="grid-container">

		<div class="buscaprofesor-container">

			<p class="generalseparator marineblue"> <b>¿En búsqueda de un profesor?</b></p>

			<form action=<?php echo $Alumno_Buscador ?> method="POST" class="generalseparator">
				<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
				<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
				<input type="submit" name="Buscador" value="Buscar Profesor" class="functionality-button">
			</form>

		</div>

		<div class="alumno-profesor-container">

			<h3 class="generalseparator marineblue">Bienvenido a la plataforma, <?php echo $bienvenido ?> </h3>

			<p class="generalseparator"><b> Usted tiene <?php echo $notificaciones2 ?>
					<?php if ($notificaciones2 > 1 || $notificaciones2 == 0) {
						echo "notificaciones";
					} else {
						echo "notificacion";
					}  ?>
				</b></p>


			<form action=<?php echo $Alumno_buzon ?> method="POST" class="generalseparator">
				<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
				<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
				<input type="submit" name="Buzon de notificaciones" value="Buzon de notificaciones" class="functionality-button">
			</form>

		</div>

		<div class="cita-container">

			<p class="generalseparator marineblue"> <b>Consulte su calendario de citas</b></p>


			<form action=<?php echo $Alumno_consulta_citas ?> method="POST" class="generalseparator">
				<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
				<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
				<input type="submit" name="Consultar citas" value="Consultar citas" class="functionality-button">
			</form>

		</div>


	</div>

	<div class="backandforthbuttons">

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