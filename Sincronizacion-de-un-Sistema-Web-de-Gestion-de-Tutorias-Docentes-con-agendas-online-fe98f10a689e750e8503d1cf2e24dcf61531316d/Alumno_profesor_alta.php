<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Alumno_Profesor_Alta</title>
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

		<h3 class="generalseparator marineblue">¿Es usted alumno o profesor?</h3>


		<form action="profesor_alta.php" method="POST" class="generalseparator">
			<input type="submit" name="Profesor" value="Profesor" class="functionality-button">
		</form>
		<form action="alumno_alta.php" method="POST" class="generalseparator">
			<input type="submit" name="Alumno" value="Alumno" class="functionality-button">
		</form>

	</div>

	<div class="backandforthbuttons">

		<form action="Login.php" method="POST" class="backbutton">
			<input type="submit" name="Volver" value="Volver">
		</form>

	</div>
	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>