<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a nuestra plataforma</title>
</head>
<body>
    <p>Hola {{ $newUser->name }}!,</p>
    <p>Bienvenido a nuestra plataforma. A continuación, te proporcionamos tus credenciales de inicio de sesión:</p>
    <ul>
        <li><strong>Correo electrónico:</strong> {{ $newUser->email }}</li>
        <li><strong>Contraseña actual:</strong> {{ $newUser->password  }}</li>
    </ul>
    <p>Te recomendamos cambiar tu contraseña después de iniciar sesión por primera vez.</p>
    <p>¡Disfruta tu experiencia en nuestra plataforma!</p>

    <p>Regards,<br>Gonzalo Fernández</p>
</body>
</html>
