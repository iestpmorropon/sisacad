<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $parameters['APP_NAME'] ?> | Login</title>

    <link href="<?= base_url();?>assets/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url();?>assets/assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="<?= base_url();?>assets/assets/css/animate.css" rel="stylesheet">
    <link href="<?= base_url();?>assets/assets/css/style.css" rel="stylesheet">
    
    <!-- Mainly scripts -->
    <script src="<?= base_url();?>assets/assets/js/jquery-2.1.1.js"></script>
    <script src="<?= base_url();?>assets/assets/js/bootstrap.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="<?= base_url();?>assets/assets/js/inspinia.js"></script>
    <script src="<?= base_url();?>assets/assets/js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="<?= base_url();?>assets/assets/js/plugins/jquery-ui/jquery-ui.min.js"></script>
    

</head>

<body class="white-bg">

    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div >
            <div class="logo-name">
                <img src="<?= base_url();?>assets/assets/img/logo_psm.png" />
            </div>
            
            <h3>Bienvenido a <?= $parameters['APP_NAME'] ?></h3>   
            <p>Ingrese su usuario y contraseña</p>
            
            <?php if ($this->session->flashdata('login_check')) {?>
                <div class="alert alert-danger">
                    <strong> Error </strong>
                    <?= $this->session->flashdata('login_check'); ?>
                </div>
            <?php } ?>
            
            <?= form_open('','class="m-t" id="loginform"'); ?>
                <div class="form-group">
                    <input id="usuario" name="username" type="text" class="form-control" placeholder="Usuario" required="">
                </div>
                <div class="form-group">
                    <input id="password" name="password" type="password" autocomplete="off" class="form-control" placeholder="Contraseña" required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Ingresar</button>
                <!--
                <p class="text-muted text-center"><small><?php //echo lang('login_no_account');?></small></p>
                <a class="btn btn-instagram block" href="register.html"><?php //echo lang('login_create_account');?></a>
                -->
            <?= form_close(); ?>
            
        </div>
    </div>

<script type="text/javascript">
    $(function(){
        console.log('Desarrollado por Servicio JSilva')
    })
</script>
</body>

</html>