<!DOCTYPE html>
<html lang="es">
<head>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link
		rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.css"
		integrity="sha512-t38vG/f94E72wz6pCsuuhcOPJlHKwPy+PY+n1+tJFzdte3hsIgYE7iEpgg/StngunGszVMrRfvwZinrza0vMTA=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link
		rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"
		integrity="sha512-UJfAaOlIRtdR+0P6C3KUoTDAxVTuy3lnSXLyLKlHYJlcSU8Juge/mjeaxDNMlw9LgeIotgz5FP8eUQPhX1q10A=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>SOLVE|BlueBull</title>
</head>
<body class="blue-grey darken-4">
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script
	src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.js"
	integrity="sha512-m2PhLxj2N91eYrIGU2cmIu2d0SkaE4A14bCjVb9zykvp6WtsdriFCiXQ/8Hdj0kssUB/Nz0dMBcoLsJkSCto0Q=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script
	src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"
	integrity="sha512-NiWqa2rceHnN3Z5j6mSAvbwwg3tiwVNxiAQaaSMSXnRRDh5C2mk/+sKQRw8qjV1vN4nf8iK2a0b048PnHbyx+Q=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
	$(document).ready(function () {
		let now = new Date("now");
		$(".dropdown-trigger").dropdown({
			hover: true,
			constrain_width: false,
			gutter: 25,
			belowOrigin: true,
			alignment: "right"
		});
		$(".datepicker").datepicker({
			defaultDate: new Date((new Date().getFullYear() - 18) + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate()),
			minDate: new Date((now.getFullYear() - 99) + "-01-31"),
			maxDate: new Date("now"),
			yearRange: [new Date().getFullYear() - 90, new Date().getFullYear() - 18],
			i18n: {
				months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
				monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
				weekdays: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
				weekdaysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
				weekdaysAbbrev: ["D", "L", "M", "MI", "J", "V", "S"]
			}
		});
		$(".sidenav").sidenav();
	});
</script>
<?php
	$menuItems = [
		'login' => [
			'signout' => [ 'Cerrar sesión', base_url ( 'signout' ) ],
		],
		'logout' => [
			'signin' => [ 'Iniciar sesión', base_url ( 'signin' ) ],
			/**'forgot' => [ 'Recuperar contraseña', base_url ( 'forgot' ) ],**/
		],
	];
	$session = $session ?? 0;
	$menuSelected = $menuItems[ $session === 0 ? 'login' : 'logout' ];
	$menu = array_reduce ( $menuSelected, function ( $carry, $item ) {
		return $carry . "<li><a href='$item[1]'>$item[0]</a></li>";
	}, '' );
?>
<header>
	<div class="navbar-fixed indigo darken-4">
		<nav>
			<div class="nav-wrapper indigo darken-4">
				<a href="<?= base_url (); ?>" class="brand-logo">Solve | BlueBull</a>
				<a href="<?= base_url (); ?>" data-target="mobile-demo" class="sidenav-trigger"><i
						class="material-icons">menu</i></a>
				<ul class="right hide-on-med-and-down">
					<?= $menu ?>
				</ul>
			</div>
		</nav>
	</div>
	<ul class="sidenav" id="mobile-demo">
		<?= $menu ?>
	</ul>
</header>
<style>
    html {
        height: 100%;
    }
    body {
        display: flex;
        flex-direction: column;
        min-height: 100%;
        margin: 0;
    }
    main {
        flex: 1;
    }
    footer {
        background-color: #01579b; /* Cambia el color según tus necesidades */
        color: white;
        padding: 1em 0;
    }
    .input-field input:focus {
        border-bottom: 1px solid #19298a !important;
        box-shadow: 0 1px 0 0 #000000;
    }
    .input-field input:focus + label {
        color: #19298a !important;
    }
    .input-field input.valid {
        border-bottom: 1px solid #19298A !important;
        box-shadow: 0 1px 0 0 #000000;
    }
    #Loader {
        display: none;
        position: absolute;
        background-color: rgba(255, 255, 255, .1);
        background-image: url('/assets/img/logo.png') !important;
        background-repeat: no-repeat;
        background-position: center;
        background-size: 60%;
        top: 0;
        z-index: 999;
        transition: opacity 5s ease, visibility 5s ease;
    }
</style>
<div  id="Loader"></div>
<main class="blue lighten-5"style="padding: 20px 0 20px 0;">
	<?= $main ?? '' ?>
</main>
<footer class="page-footer indigo darken-4">
	<div class="container">
	</div>
	<div class="footer-copyright">
		<div class="container">
			© 2024 Copyright Solve GCM
		</div>
	</div>
</footer>
</body>
</html>