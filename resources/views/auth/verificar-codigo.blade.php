<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar código</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="width: 400px;">
            <h3 class="text-center mb-3">Verificación</h3>
            <p class="text-center text-muted">Ingresa el código de 6 dígitos que enviamos a tu correo.</p>
            @if (session('mensaje'))
                <div class="alert alert-success">
                    {{ session('mensaje') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
            <form action="{{ url()->full() }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="codigo" class="form-label">Código de verificación</label>
                    <input type="text" name="codigo" id="codigo" class="form-control text-center" placeholder="000000" maxlength="6" inputmode="numeric" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Verificar código</button>
            </form>
        </div>
    </div>
</body>
</html>