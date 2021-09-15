<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KimiApp</title>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
    <header>
        <h1>KimiApp</h1>
    </header>
    <section class="bar">
        <div>
            <a href="/">Inicio</a>
        </div>
        <div style="display: <?php echo isset($_SESSION['user']) ? 'none': 'block'; ?>">
            <a href="/login">login</a>
        </div>
        <div style="display: <?php echo isset($_SESSION['user']) ? 'block': 'none'; ?>">
            <a href="/perfil">Perfil</a>
        </div>
        <div style="display: <?php echo isset($_SESSION['user']) ? 'block': 'none'; ?>">
            <a href="/salir">Salir</a>
        </div>
    </section>