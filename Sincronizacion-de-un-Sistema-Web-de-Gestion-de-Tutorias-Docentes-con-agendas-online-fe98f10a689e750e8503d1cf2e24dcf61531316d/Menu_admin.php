<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Menú administrador</title>
	<link rel="stylesheet" href="./style.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,maximun-sacale=1,munimun-sacale=1">
</head>



<body onload="todayDate()">

	<header>

		<a href="https://www.upm.es/" target="_blank"><img src="imagenes/Logo_UPM.png"></a>
		<a href="https://www.fi.upm.es/" target="_blank"><img src="imagenes/logo_etsiinf_transparente.png"></a>


	</header>
	<div class="titulo">
		<h1> 			
			Sistema de reserva de tutorías
		</h1>
	</div>

	<?php
		if (isset($_COOKIE["mail"]) && isset($_COOKIE["id_sesion"])) {
			if ($_COOKIE["mail"] != "" && $_COOKIE["id_sesion"] != "") {
				$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
				if (!$con) {
					echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
					echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
					echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
					exit;
				}
				$sql = "SELECT id_sesion FROM `session` WHERE (`mail_profesor` = '" . $_COOKIE["mail"] . "');";
				$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
				$row = mysqli_fetch_array($result);

				if ($result && $row!=[] && $row["id_sesion"] == md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR'])) {
					$sql2 = "SELECT nombre FROM `profesor` WHERE (`mail` = '" . $_COOKIE["mail"] . "');";
					$result2 = mysqli_query($con, $sql2) or die('Error en la consulta a la BDD aaaaa');
					$row2 = mysqli_fetch_array($result2);
					$bienvenido =  $row2["nombre"];

					setcookie("mail", $_POST["mail"], time() + 3600);	//Renovar cookie
					$time = time();
					$time_click = $time + 3600;
					setcookie("id_sesion", $row["id_sesion"], $time_click);	//Renovar cookie
					$Profesor_menu = "Profesor_menu.php";
					$Alumno_Menu = "Alumno_Menu.php";
					$Admin_Consulta_Franjas = "Admin_Consulta_Franjas.php";
					$Admin_Consulta_Citas = "Admin_Consulta_Citas.php";
					$Admin_Buscador = "Admin_Buscador.php";
					$Admin_Cuentas = "Admin_Cuentas.php";
				} else {
				?>
					<div class="main-container">
						<h3 class="generalseparator red">Sesión expirada. Por favor, vuelva a logearse.</h3>

						<form action="Login.php" method="POST" class="generalseparator">
							<input type="submit" name="inicio" value="Volver al menú de inicio" class="functionality-button">
						</form>

					</div>

				<?php
					exit();
					// echo "<b><big>Sesión expirada. Por favor, vuelva a logearse.</big></b>";
					// $Profesor_menu = "Login.php";
					// $Alumno_Menu = "Login.php";
					// $Admin_Consulta_Franjas = "Login.php";
					// $Admin_Consulta_Citas = "Login.php";
					// $Admin_Buscador = "Login.php";
					// $Admin_Cuentas = "Login.php";
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
			exit();
			// echo "<b><big>Sesión expirada. Por favor, vuelva a logearse.</big></b>";
			// $Profesor_menu = "Login.php";
			// $Alumno_Menu = "Login.php";
			// $Admin_Consulta_Franjas = "Login.php";
			// $Admin_Consulta_Citas = "Login.php";
			// $Admin_Buscador = "Login.php";
			// $Admin_Cuentas = "Login.php";
		}
		?>

	<div class="grid-container">

		<div class="franja-container">

			<h3 class="generalseparator marineblue">Calendario de Franjas </h3>

			<form action=<?php echo $Admin_Consulta_Franjas ?> method="POST" class="generalseparator">
				<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
				<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
				<input type="submit" name="Consultar franjas" value="Consultar franjas" class="functionality-button">
			</form>
		</div>

		<div class="main-container">

			<h3 class="generalseparator marineblue">Bienvenido a la plataforma, <span class="red"><?php echo $bienvenido ?> </span></h3>

			<hr class="generalseparator">

			<h3 class="generalseparator marineblue">¿En búsqueda de un profesor?</h3>


			<form action=<?php echo $Admin_Buscador ?> method="POST" class="generalseparator">
				<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
				<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
				<input type="submit" name="Buscar profesor" value="Buscar profesor" class="functionality-button">
			</form>

			<hr class="generalseparator">

			<h3 class="generalseparator marineblue">Acceso al gestor de cuentas</h3>


			<form action=<?php echo $Admin_Cuentas ?> method="POST" class="generalseparator">
				<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
				<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
				<input type="submit" name="Administrar cuentas" value="Gestor de Cuentas" class="functionality-button">
			</form>

		</div>

		<div class="cita-container">

			<h3 class="generalseparator marineblue">Calendario de Citas </h3>


			<form action=<?php echo $Admin_Consulta_Citas ?> method="POST" class="generalseparator">
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