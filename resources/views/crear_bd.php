<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <title>Document</title>

</head>

<body>
    <div class="ppal_container">
        <?php
        require_once 'header.php'
        ?>
        <main>
            <div>
                <h2><?php echo $data ? 'Base de datos creada con éxito' : 'Ha sido imposible crear la base de datos' ?><h2>
            </div>
            <div class="button_container">
                <a href="/login"><button>Ir a login</button></a>
            </div>

        </main>
        <?php require_once 'footer.php' ?>
    </div>
</body>

</html>