
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alas al Progreso</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
<header class="header">
    <nav class="nav">
        <div class="containerlogo">
            <img class="logo" src="http://localhost/Cooperativa-main/img/logocooperativa.png" alt="">
            <div class="containertitles">       
                <h1 class="titlecoop">Cooperativa</h1>
                <p>Alas al progreso</p>
            </div>
        </div>
    </nav>
</header>
<section class="container-content">
<aside class="verticalcontainer">
    <ul class="verticalmenu">
        <li class="menuitem">
            <a href="main.php">Inicio</a>
        </li>
        <li class="menuitem">
            <a href="">Empleados</a>
            <ul class="submenu">
                <li><a href="./forms/empleados.php">Registrar empleado</a></li>
                <li><a href="./views/viewEmpleados.php">Ver Empleados</a></li>
            </ul>
        </li>
        <li class="menuitem">
            <a href="clientes.php">Clientes</a>
            <ul class="submenu">
                <li><a href="./forms/clientes.php">Crear Cliente</a></li>
                <li><a href="./views/ViewClientes.php">Ver Clientes</a></li>
    
            </ul>
        </li>
        <li class="menuitem">
            <a href="">Nomina</a>
            <ul class="submenu">
                <li><a href="./forms/nomina.php">Agregar a la nomina</a></li>
                <li><a href="./views/viewNomina.php">Ver nomina</a></li>
            </ul>
        </li>
        <li class="menuitem">
            <a href="./main.php">Cerrar sesion</a>
        </li>
    </ul>
</aside>
<section class="content">
<a href="./forms/cobro.php" class="card">
        <div class="card-content">
            <div class="card-title">Cobrar</div>
            <p>Desde aqui puedes realizar cobros a los clientes con prestamos pendientes.</p>

        </div>
    </a>
    <a href="./views/viewPrestamo.php" class="card">
        <div class="card-content">
            <div class="card-title">Ver Prestamos</div>
            <p>Desde aqui puedes visualizar los prestamos pendientes sin por saldar y saldos.</p>
        </div>
    </a>
    <a href="./forms/prestamos.php" class="card">
        <div class="card-content">
            <div class="card-title">Registrar Prestamo</div>
            <p>Desde aqui puedes registrar los prestamos que se ofrecen a los clientes.</p>
        </div>
    </a>
</section>
</section>
<script>
    // JavaScript para mostrar/ocultar submenús
    document.querySelectorAll('.menuitem').forEach(item => {
        item.addEventListener('click', function (e) {
            // Evita que el enlace principal se active al hacer clic
            if (e.target.tagName === 'A' && e.target.nextElementSibling) {
                e.preventDefault();
            }
            // Muestra u oculta el submenú
            const submenu = this.querySelector('.submenu');
            if (submenu) {
                submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            }
        });
    });
</script>
</body>
</html>