<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\CodigoVerificacion;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validar los campos
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ]);

        // Buscar usuario por correo
        $usuario = Usuario::where('correo', $request->correo)->first();

        // Comprobar que exista y que la contraseña coincida
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->withErrors([
                'correo' => 'Correo o contraseña incorrectos.'
            ])->onlyInput('correo');
        }

        if ($usuario->rol->nombre == 'Administrador') {
            // Generar un código aleatorio de 6 dígitos
            $codigo = random_int(100000, 999999);

            // Eliminar códigos anteriores de este usuario
            CodigoVerificacion::where('usuario_id', $usuario->id)->delete();

            // Guardar el código cifrado y su fecha de expiración
            CodigoVerificacion::create([
                'usuario_id' => $usuario->id,
                'codigo' => Hash::make($codigo),
                'expira_en' => now()->addMinutes(5),
                'utilizado' => false
            ]);

            // Guardar temporalmente el ID del administrador
            session([
                'usuario_pendiente_verificacion' => $usuario->id
            ]);

            // Enviar el código por correo
            Mail::raw(
                "Tu código de verificación es: $codigo. Tiene una vigencia de 5 minutos.",
                function ($message) use ($usuario) {
                    $message->to($usuario->correo)
                            ->subject('Código de verificación');
                }
            );

            // Generar una URL firmada que expira en 5 minutos
            $urlVerificacion = URL::temporarySignedRoute(
                'verificar.codigo',
                now()->addMinutes(5),
                ['usuario' => $usuario->id]
            );

            // Redirigir a la URL temporal
            return redirect()->to($urlVerificacion)
                ->with('mensaje', 'Te enviamos un código de verificación a tu correo.');
        }

        // Iniciar sesión del empleado
        Auth::login($usuario);

        $request->session()->regenerate();

        return redirect()->route('principal');
    }

    public function mostrarVerificacion(Usuario $usuario)
    {
        // Comprobar que el usuario de la URL sea el pendiente
        if (session('usuario_pendiente_verificacion') != $usuario->id) {
            return redirect()->route('login');
        }

        return view('auth.verificar-codigo');
    }

    public function verificarCodigo(Request $request, Usuario $usuario)
    {
        // Validar que se hayan ingresado 6 dígitos
        $request->validate([
            'codigo' => 'required|digits:6'
        ]);

        // Comprobar que el usuario de la URL sea el pendiente
        if (session('usuario_pendiente_verificacion') != $usuario->id) {
            return redirect()->route('login');
        }

        // Obtener el ID del administrador
        $usuarioId = $usuario->id;

        // Buscar el código pendiente del administrador
        $codigoVerificacion = CodigoVerificacion::where('usuario_id', $usuarioId)
            ->where('utilizado', false)
            ->latest()
            ->first();

        // Comprobar que exista
        if (!$codigoVerificacion) {
            return back()->withErrors([
                'codigo' => 'No existe un código de verificación válido.'
            ]);
        }

        // Comprobar si expiró
        if (now()->greaterThan($codigoVerificacion->expira_en)) {

            $codigoVerificacion->delete();

            session()->forget('usuario_pendiente_verificacion');

            return redirect()->route('login')->withErrors([
                'correo' => 'El código ha expirado. Inicia sesión nuevamente.'
            ]);
        }

        // Comprobar que el código coincida
        if (!Hash::check($request->codigo, $codigoVerificacion->codigo)) {
            return back()->withErrors([
                'codigo' => 'El código ingresado es incorrecto.'
            ]);
        }

        // Marcar el código como utilizado
        $codigoVerificacion->update([
            'utilizado' => true
        ]);

        // Iniciar sesión del administrador
        $usuario = Usuario::findOrFail($usuarioId);

        Auth::login($usuario);

        // Regenerar la sesión por seguridad
        $request->session()->regenerate();

        // Eliminar el ID pendiente de la sesión
        session()->forget('usuario_pendiente_verificacion');

        return redirect()->route('principal');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}