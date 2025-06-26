<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro - RUTVANS</title>
    <!-- Fuentes y Estilos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e8ebf0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            width: 80%;
            max-width: 1000px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        .form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-section h2 {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #3b3b3b;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
        }

        .register-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #ff6600;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            font-family: 'Poppins', sans-serif;
        }

        .register-btn:hover {
            background-color: #cc5500;
        }

        .login-link {
            margin-top: 15px;
            font-size: 14px;
            text-align: center;
        }

        .login-link a {
            color: #ff6600;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #ff3300;
        }

        .logo-section {
            flex: 1;
            background-color: #ff6600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-section img {
            width: 70%;
            max-width: 300px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .logo-section {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Formulario -->
        <div class="form-section">
            <h2>Register</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="input-group">
                    <input type="text" name="name" placeholder="Nombre" required />
                </div>
                <div class="input-group">
                    <input type="email" name="email" placeholder="Correo" required />
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Contraseña" required />
                </div>
                <div class="input-group">
                    <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required />
                </div>
                <button type="submit" class="register-btn">Registrarse</button>
            </form>
            <p class="login-link">¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
        </div>

        <!-- Logo -->
        <div class="logo-section">
            <img src="{{ asset('asset/Logo_RutVans_Login1.png') }}" alt="Logo Punto de Venta" />
        </div>
    </div>
</body>

</html>
