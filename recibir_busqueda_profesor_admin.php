<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>recibir_busqueda_profesor_admin</title>
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
			$sql = "SELECT id_sesion FROM `session` WHERE (`mail_profesor` = '" . $_COOKIE["mail"] . "');";
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
			$row = mysqli_fetch_array($result);

			if ($result && $row != [] && $row["id_sesion"] == md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR'])) {
				// $recordatorio = "<p class= " . "recordatorio" . ">Usted está logeado como: " . $_COOKIE["mail"];
				setcookie("mail", $_POST["mail"], time() + 3600);	//Renovar cookie
				$time = time();
				$time_click = $time + 3600;
				setcookie("id_sesion", $row["id_sesion"], $time_click);	//Renovar cookie
				$back_page = "Admin_Buscador.php";
				$Admin_Consulta_Franjas = "Admin_Consulta_Franjas.php";
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
		// $back_page = "Login.php";
	}
	?>

	<div class="main-container">

		<?php
		$resultados = false;
		$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
		if (!$con) {
			echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
			echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
			echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
		$sql = "SELECT * FROM `profesor` WHERE (`tbuscar` LIKE '%" . $_POST["nombre"] . "%');";
		// echo $sql."<br>";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		foreach ($con->query($sql) as $row) {
			if (!$row["Administrador"]) {
				$resultados = true;
		?>
				<?php
				// Inicializamos variables y guardamos valores de los datos franja
				$buscar = "";
				$correo = "";
				$despacho = "";
				$buscar = $row["tbuscar"];
				$correo = $row["mail"];
				$despacho = $row["Despacho"];

				// print "<b><big>" . $row["tbuscar"] . "</big></b>\t";
				// echo "<br>";
				// print "Correo: " . $row["mail"] . "; \t";
				// print "Despacho: " . $row["Despacho"] . ".\t";

				?>

				<div class="generalseparator">

					<h3 class="generalseparator marineblue"> <?php echo $buscar ?> </h3>
					<p> <span class="black"><b>Correo: </b></span><?php echo $correo ?></p>
					<p> <span class="black"><b>Despacho: </b></span><?php echo $despacho ?></p>

				</div>

				<form action=<?php echo "Admin_Consulta_Franjas.php" ?> method="POST" class="generalseparator">
					<input type="hidden" name="id_profesor" value=<?php echo $row["id_profesor"] ?>>
					<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
					<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
					<input type="hidden" name="nombre" value=<?php echo $_POST["nombre"] ?>>
					<input type="submit" name="Consultar franjas" value="Ver Franjas" class="functionality-button">
				</form>
			<?php
			}
		}
		mysqli_close($con);
		if (!$resultados) {

			?>
			<p class="generalseparator marineblue"><b>No se ha encontrado ninguna coincidencia.</b></p>;

		<?php

		}

		?>

	</div>

	<div class="backandforthbuttons">

		<form action=<?php echo $back_page ?> method="POST" class="backbutton">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="hidden" name="nombre" value=<?php echo $_POST["nombre"] ?>>
			<input type="submit" name="Volver" value="Volver">
		</form>

		<form action="Login.php" method="POST" class="logoutbutton">
			<input type="submit" name="Logout" value="< Logout">
			<input type="hidden" name="mail" value=<?php echo $_COOKIE["mail"] ?>>
		</form>

	</div>

	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>