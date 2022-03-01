<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>recibir_Profesor_alta</title>
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
		$nombre = "";
		$apellido = "";
		$despacho = "";
		if ($_POST["password1"] != $_POST["password2"]) {
			echo 'La contraseña no coincide con la confirmación de la misma.';
			$Continuar = "profesor_alta.php";
		} else {
			$mail = "" . $_POST["mail"] . "" . $_POST["tipo_mail"] . "";
			if (is_valid_email($mail)) {
				$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias')
					or die('Error en conexion al servidor');
				$password_encript = md5($_POST["password1"]);
				$tbuscar = $_POST["nombre"] . " " . $_POST["apellido"];
				$sql = "SELECT * FROM `profesor`;";
				$result = mysqli_query($con, $sql) or die('Error en la operación de alta en el sistema. El correo introducido ya está registrado.');
				$row_cnt = $result->num_rows;
				if ($row_cnt == 0) {
					$sql = "INSERT INTO profesor VALUES(null,'" . $password_encript . "','" . $_POST["nombre"] . "','" . $_POST["apellido"] . "','" . $tbuscar . "','" . $mail . "','" . $_POST["Despacho"] . "','1','1',null)";
					$result = mysqli_query($con, $sql) or die('Error en la operación de alta en el sistema. El correo introducido ya está registrado.');
				} else {
					$sql = "INSERT INTO profesor VALUES(null,'" . $password_encript . "','" . $_POST["nombre"] . "','" . $_POST["apellido"] . "','" . $tbuscar . "','" . $mail . "','" . $_POST["Despacho"] . "','0','0',null)";
					$result = mysqli_query($con, $sql) or die('Error en la operación de alta en el sistema. El correo introducido ya está registrado.');
				}
				$nombre = $_POST["nombre"];
				$apellido = $_POST["apellido"];
				$despacho = $_POST["Despacho"];
				mysqli_close($con);

		?>

				<h3 class="generalseparator green">Alta realizada con éxito. Espere a que el Administrador valide su cuenta.</h3>

				<p class="generalseparator marineblue"><b><span class="black">El nombre introducido es:</span> <?php echo $nombre ?> <?php echo $apellido ?> </b></p>
				<p class="generalseparator marineblue"><b><span class="black">El correo introducido es:</span> <?php echo $mail ?> </b></p>
				<p class="generalseparator marineblue"><b><span class="black">El despacho introducido es:</span> <?php echo $despacho ?> </b></p>

			<?php
				$Continuar = "Login.php";
			} else {

			?>
				<h3 class="generalseparator red">El correo introducido no está correctamente formado.</h3>

		<?php

				$Continuar = "profesor_alta.php";
			}
		}
		/**
		 * Valida un email usando filter_var. 
		 *  Devuelve true si es correcto o false en caso contrario
		 *
		 * @param    string  $str la dirección a validar
		 * @return   boolean
		 */
		function is_valid_email($str)
		{
			return (false !== filter_var($str, FILTER_VALIDATE_EMAIL));
		}
		?>

		<form action=<?php echo $Continuar ?> method="POST" class="generalseparator">
			<input type="submit" name="Continuar" value="Continuar" class="functionality-button">
		</form>

	</div>

	<div class="backandforthbuttons">

		<form action="profesor_alta.php" method="POST" class="backbutton">
			<input type="submit" name="Volver" value="Volver">
		</form>

	</div>


	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>