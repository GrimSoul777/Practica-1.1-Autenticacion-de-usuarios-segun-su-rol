<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <div class="container mt-5">

        <div class="card shadow p-4">

            <h1>Bienvenido al sistema</h1>

            <p>Has iniciado sesión correctamente.</p>

            <h5>Usuario: {{ Auth::user()->nombre }}</h5>

            <h5>Rol: {{ Auth::user()->rol->nombre }}</h5>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="btn btn-danger mt-3">
                    Cerrar sesión
                </button>
            </form>

        </div>

    </div>

</body>
</html>