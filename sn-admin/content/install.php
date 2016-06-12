<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Proceso de instalación</title>

        <!-- Bootstrap -->
        <link href="sn-includes/css/bootstrap.css" rel="stylesheet">
        <link href="sn-admin/css/style.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body id="sn-admin">
        <div class="container-fluid"><!-- .container-fluid -->
            <div id="logo-SoftN">
                <br/>
                <img class="center-block" src="sn-admin/img/logo.png" alt="CMS - SoftN"/>
            </div>
            <div class="row clearfix"><!-- .row.clearfix -->    
                <div id="install" class="panel panel-default center-block clearfix">
                    <div class="panel-body">
                        <h2>Proceso de instalación</h2>
                        <hr/>
                        <?php
                        switch ($check) {
                            case 0:
                                ?>
                                <p class="alert alert-danger"><span class="glyphicon glyphicon-info-sign"></span> No se logro establecer la conexión SQL.</p>
                                <a href="install.php" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-arrow-left"></span> Volver</a>
                                <?php
                                break;
                            case 1:
                                if ($step == 3) {
                                    echo '<p class="alert alert-danger"><span class="glyphicon glyphicon-info-sign"></span> No existe el archivo "sn-config.php".</p>';
                                } else {
                                    echo '<p class="alert alert-danger"><span class="glyphicon glyphicon-info-sign"></span> No fue posible crear el archivo "sn-config.php".</p>';
                                }
                                ?>
                                <p>Cree, manualmente, un archivo llamado "<strong>sn-config.php</strong>" con la siguiente información:</p>
                                <textarea class="form-control" rows="15" readonly="readonly">
                                    <?php
                                    foreach ($configFile as $num => $line) {
                                        echo htmlentities($line, ENT_COMPAT, 'UTF-8');
                                    }
                                    ?>
                                </textarea>
                                <p>Despues de crear el fichero, pulse siguiente.</p>
                                <a href="install.php?step=3" class="btn btn-primary">Siguiente</a>
                                <?php
                                break;
                            case 2:
                                ?>
                                <p class="alert alert-success"><span class="glyphicon glyphicon-info-sign"></span> La instalación se completo correctamente.</p>
                                <a href="login.php" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-arrow-left"></span> Volver</a>
                                <?php
                                break;
                            case 3:
                                if (isset($_SESSION['install'])) {
                                    unset($_SESSION['install']);
                                }
                                ?>
                                <p class="alert alert-success"><span class="glyphicon glyphicon-info-sign"></span> La instalación se completo correctamente.</p>
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <p>Usa este usuario y contraseña para acceder al panel de control.</p>
                                        <div class="alert alert-info">
                                            <p>Usuario: admin</p>
                                            <p>Contraseña: admin</p>
                                        </div>
                                        <a href="login.php" class="btn btn-primary btn-block">Iniciar sesión</a>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>Usa esta opción para agregar contenido de ejemplo a la base de datos.</p>
                                        <p><a class="btn btn-warning btn-block" href="?demo=1">Demo</a></p>
                                        <p class="alert alert-warning">Debes iniciar sesión. Ejecutar solo 1 vez.</p>
                                    </div>
                                </div>
                                <?php
                                break;
                            case 4:
                                ?>
                                <p class="alert alert-danger"><span class="glyphicon glyphicon-info-sign"></span> No se encontro el fichero demo.sql</p>
                                <a href="install.php" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-arrow-left"></span> Volver</a>
                                <?php
                                break;
                            case 5:
                                ?>
                                <p class="alert alert-danger"><span class="glyphicon glyphicon-info-sign"></span> Error al ejecutar el fichero install.sql</p>
                                <a href="install.php" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-arrow-left"></span> Volver</a>
                                <?php
                                break;
                            case 6:
                                ?>
                                <p class="alert alert-danger"><span class="glyphicon glyphicon-info-sign"></span> Error al crear el usuario admin.</p>
                                <a href="install.php" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-arrow-left"></span> Volver</a>
                                <?php
                                break;
                            default :
                                ?>
                                <form class="form-horizontal" method="post">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Dirección web:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="install_url" value="<?php echo $_SESSION['install']['install_url']; ?>" placeholder="http://localhost/">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Base de datos:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="install_db" value="<?php echo $_SESSION['install']['install_db']; ?>" placeholder="softn_cms">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Usuario BD:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="install_user" value="<?php echo $_SESSION['install']['install_user']; ?>" placeholder="root">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Contraseña BD:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="install_pass" value="<?php echo $_SESSION['install']['install_pass']; ?>" placeholder="root">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Host:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="install_host" value="<?php echo $_SESSION['install']['install_host']; ?>" placeholder="localhost">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Prefijo:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="install_prefix" value="<?php echo $_SESSION['install']['install_prefix']; ?>" placeholder="sn_">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Charset:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="install_charset" value="<?php echo $_SESSION['install']['install_charset']; ?>" placeholder="utf8">
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="submit" name="step" value="2">Siguente</button>
                                </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div><!-- .row.clearfix -->
        </div><!-- .container-fluid -->
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="sn-includes/js/jquery-1.12.0.js" type="text/javascript"></script>
        <script src="sn-includes/js/bootstrap.min.js"></script>
        <script src="sn-includes/js/script.js" type="text/javascript"></script>
    </body>
</html>