<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrar usuario</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-6">

                <div class="card shadow">

                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Registrar usuario</h4>
                    </div>

                    <div class="card-body">

                        <form action="#" method="POST">

                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Nombre</label>

                                <input type="text"
                                       class="form-control"
                                       name="nombre"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Correo electrónico</label>

                                <input type="email"
                                       class="form-control"
                                       name="correo"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>

                                <input type="password"
                                       class="form-control"
                                       name="password"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rol</label>

                                <select class="form-select" name="rol_id" required>
                                    <option value="">Selecciona un rol</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Registrar
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>
</html>