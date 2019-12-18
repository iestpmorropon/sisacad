<!DOCTYPE html>
<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $parameters['APP_NAME']?></title>
    
    <link href="<?php echo base_url();?>assets/assets/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    
    <link href="<?php echo base_url();?>assets/assets/css/plugins/datetimepicker/bootstrap-datetimepicker.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    
    <link href="<?php echo base_url();?>assets/assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">

    
    <link href="<?php echo base_url();?>assets/assets/css/plugins/dualListBox/bootstrap-duallistbox.min.css" rel="stylesheet">
    
    
    <link href="<?php echo base_url();?>assets/assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
    
    <link href="<?php echo base_url();?>assets/assets/css/plugins/clockpicker/clockpicker.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/assets/css/plugins/select2/select2.min.css" rel="stylesheet">
    
    <link href="<?php echo base_url();?>assets/assets/css/plugins/footable/footable.bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
    
    
    <link href="<?php echo base_url();?>assets/assets/css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
    
    <!-- Toastr style -->
    <link href="<?php echo base_url();?>assets/assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    
    <!-- Footable -->
    <link href="<?php echo base_url();?>assets/assets/css/plugins/footable/footable.bootstrap.min.css" rel="stylesheet">

    <!-- iCheck style -->
    <link href="<?php echo base_url();?>assets/assets/css/plugins/iCheck/custom.css" rel="stylesheet">
    
    <!-- Gritter -->
    <link href="<?php echo base_url();?>assets/assets/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    
    <!-- Sweet Alert -->
    <link href="<?php echo base_url();?>assets/assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">


    <!-- Data picker -->
    
    <link href="<?php echo base_url();?>assets/assets/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">

  
    <link href="<?php echo base_url();?>assets/assets/css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/assets/css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">
 
    <link href="<?php echo base_url();?>assets/assets/css/plugins/steps/jquery.steps.css" rel="stylesheet">
    
    <link href="<?php echo base_url();?>assets/assets/css/animate.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/assets/css/jquery-confirm.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/assets/css/styles-autocomplete.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/assets/css/styles.css" rel="stylesheet">
    <link href='<?= base_url() ?>assets/node_modules/@fullcalendar/core/main.css' rel='stylesheet' />
    <link href='<?= base_url() ?>assets/node_modules/@fullcalendar/daygrid/main.css' rel='stylesheet' />
    <link href='<?= base_url() ?>assets/assets/css/plugins/switchery/switchery.css' rel='stylesheet' />

        
</head>

<body>
    <div id="wrapper">
        <nav class="sidebar navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> 
                            <span>
                                <!-- <img alt="image" class="img-circle" src="<?= $user_info_logged_in->avatar ?> "/> -->
                            <?php if ($usuario['url_imagen']){ ?>
                                <img alt="image" class="img-circle"  src="<?php echo site_url("images/image/".($usuario['url_imagen']?$usuario['url_imagen']:'user.jpg')."");?>" width="128px" height="128px"  />
                            <?php }else{ ?>
                                <img alt="image" class="img-circle" src="<?= site_url('assets/assets/img/user.jpg') ?>" >
                            <?php  } ?>
                            </span>
                            
                            <span class="clear"> 
                                <span class="block m-t-xs"> <strong class="font-bold"><i class="fas fa-user"></i>&nbsp;<?= $usuario['nombre'] ?></strong></span> 
                            </span>
                            
                        </div>
                        <div class="logo-element">
                            <?= $parameters['APP_NAME'] ?>
                        </div>
                    </li>
                    
                    <li class="<?php echo activate_menu("dashboard");?>" >
                        <a href="<?php echo base_url("/");?>"> <i class="fa fa-home"></i> <span class="nav-label">Dashboard</span> </a>
                    </li>

                    <?php if(!is_null($usuario['modulos'])) foreach ($usuario['modulos'] as $key => $value) { ?>
                        <li class="<?= isset($value['hijos']) && $module == $value['nombre'] ? 'active': '' ?>">
                            <a class="nav-link <?= isset($value['hijos']) ? 'dropdown-toggle collapsed' : '' ?>" <?= isset($value['hijos']) ? 'data-toggle="collapse" aria-expanded="false"' : '' ?> href="#<?= isset($value['hijos']) ? $value['id'] : '' ?>"><i class="fas <?= $value['logo'] ?>"></i> <span class="nav-label"><?= $value['nombre'] ?></span></a>
                            <?php if(isset($value['hijos'])) { ?>
                                <ul class="list-unstyled collapse" id="<?= $value['id'] ?>">
                                    <?php foreach($value['hijos'] as $k => $v) { ?>
                                        <li class="nav-item">
                                            <a href="<?= base_url($v['url']) ?>" class="nav-link"><i class="fas <?= $v['logo'] ?>"></i> <span class="nav-label"><?= $v['nombre'] ?></span></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </li>
                    <?php } ?>
                    
                    <?php if(0) foreach($enable_modules as $module) { ?>   
                            <li class="<?php echo activate_menu($module->route); ?>">      
                                <a href="<?php echo base_url("/".$module->route);?>"> <i class="fa <?= $module->icon; ?>"></i> <span class="nav-label"><?php echo lang($module->name_lang_key); ?></span></a>       
                            </li>
                    <?php } ?>               
                </ul>

            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg <?php if(0){ ?> dashbard-1 <?php }?>">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">Bienvenido</span>
                        </li>
                        <li>
                            <a href="<?= base_url().'profile' ?>"><span class="m-r-sm text-muted welcome-message"><i class="fas fa-user"></i>&nbsp;<?= $usuario['nombre'].' '.$usuario['apell_pat'].' '.$usuario['apell_mat'] ?></span></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><span class="nav-label">Rol: <?= $usuario['rol_actual'] ?></span><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php foreach ($usuario['roles'] as $key => $value) { ?>
                                    <li><a href="<?= base_url('home/cambiorol/'.$value->id_rol) ?>"><?= $value->rol ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php if(0) if($user_rol <> 'customer'){ ?>
                            <li class="dropdown"> 
                                <a class="dropdown-toggle count-info"  data-toggle="dropdown" href="#"  >
                                    <i class="fa fa-bell"></i> 
                                    <span class="label label-warning"><?= $num_notifications?></span>
                                </a>
                                <?php if($num_notifications > 0){?>
                                <ul class="dropdown-menu dropdown-messages">
                                    <?php foreach($notifications as $notification){ ?>
                                    <li>
                                        <div class="dropdown-messages-box">
                                            <div>
                                                <?= lang('common_company')?> <strong><?= $notification->company_name?></strong> <?= lang('common_invoice_register')?> <strong><?= $notification->number?></strong>
                                                <?= lang('common_date_register') ?> <?php echo nice_date($notification->date_invoice,'d/m/Y');?> <?= lang('common_in').' '.lang('currency_'.$notification->currency_lang)?> 
                                            </div>
                                        </div>
                                    </li>
                                    <li class="divider"></li>
                                    <?php } ?>                                
                                </ul>
                                <?php } ?>
                            </li>
                        <?php } ?>  
                        
                        <li>
                            <a href="<?php echo site_url('login/logout') ?>">
                                <i class="fas fa-sign-out-alt"></i> Salir
                            </a>
                        </li>
                    </ul>

                </nav>
            </div>

    
    
    
    <!-- Mainly scripts -->
    <script src="<?php echo base_url();?>assets/assets/js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo base_url();?>assets/assets/js/jquery-ui.js"></script>
    <script src="<?php echo base_url();?>assets/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?php echo base_url();?>assets/assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>


     <!-- Moment -->
    
    <script src="<?php echo base_url();?>assets/assets/js/plugins/moment/moment-with-locales.js"></script>
    


    <!-- Dual Listbox -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/dualListBox/jquery.bootstrap-duallistbox.js"></script>
    
    <!-- Chosen -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/chosen/chosen.jquery.js"></script>

    <!-- Typehead -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>
    
    
    
    <!-- Data picker -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    
   
    <!-- Datarange picker -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/daterangepicker/daterangepicker.js"></script>
    
   
    
    <!-- MENU -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    

    <!-- Clock picker -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/clockpicker/clockpicker.js"></script>

    <!-- Select2 -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/select2/select2.full.min.js"></script>

    <!-- FooTable -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/footable/footable.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    
    <!-- DataTable -->
    <!-- JSZip 2.5.0, pdfmake 0.1.18, DataTables 1.10.13, Buttons 1.2.3, HTML5 export 1.2.3, Print view 1.2.3 -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/dataTables/datatables.min.js"></script> 
    

    <!-- other script for Datable-->
    
    <script src="<?php echo base_url();?>assets/assets/js/plugins/dataTables/buttons.colVis.min.js"></script>
    <script src="<?php echo base_url();?>assets/assets/js/plugins/dataTables/dataTables.rowsGroup.js"></script>
    

    <!-- Flot -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/flot/jquery.flot.js"></script>
    <script src="<?php echo base_url();?>assets/assets/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="<?php echo base_url();?>assets/assets/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="<?php echo base_url();?>assets/assets/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="<?php echo base_url();?>assets/assets/js/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/peity/jquery.peity.min.js"></script>

    <!-- GITTER -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- ChartJS-->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Toastr -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/toastr/toastr.min.js"></script>
    
    <!-- iCheck -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/iCheck/icheck.min.js"></script>

    <!-- Jasny -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>
    
    <!-- jqGrid -->
   
    <script src="<?php echo base_url();?>assets/assets/js/plugins/jqGrid/i18n/grid.locale-es.js"></script>
    <script src="<?php echo base_url();?>assets/assets/js/plugins/jqGrid/jquery.jqGrid.min.js"></script>
    
    <!-- Steps -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/steps/jquery.steps.min.js"></script>
    
    <!-- Sweet Alert -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Jquery Validate -->
    <script src="<?php echo base_url();?>assets/assets/js/plugins/validate/jquery.validate.min.js"></script>
    
    <!-- Custom and plugin javascript -->
    <script src="<?php echo base_url();?>assets/assets/js/inspinia.js"></script>
    <!-- <script src="<?php echo base_url();?>assets/assets/js/plugins/pace/pace.min.js"></script> -->
    <script src="<?php echo base_url();?>assets/assets/js/jquery-confirm.js"></script>
    
    <script src="<?php echo base_url();?>assets/assets/js/jquery.autocomplete.js"></script>
    <script src='<?= base_url() ?>assets/node_modules/@fullcalendar/core/main.js'></script>
    <script src='<?= base_url() ?>assets/node_modules/@fullcalendar/daygrid/main.js'></script>
    <script type="text/javascript">
        
        jQuery.extend(jQuery.validator.messages, {
            required: "Este campo es requerido.",
            remote: "Please fix this field.",
            email: "Ingrese un email correcto.",
            url: "Ingrese una URL valida.",
            date: "Ingrese una fecha correcta.",
            dateISO: "Please enter a valid date (ISO).",
            number: "Please enter a valid number.",
            digits: "Please enter only digits.",
            creditcard: "Please enter a valid credit card number.",
            equalTo: "Please enter the same value again.",
            accept: "Please enter a value with a valid extension.",
            maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
            minlength: jQuery.validator.format("Please enter at least {0} characters."),
            rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
            range: jQuery.validator.format("Please enter a value between {0} and {1}."),
            max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
            min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
        });
          $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
            weekHeader: 'Sm',
            dateFormat: 'dd-mm-yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
          };
          $.datepicker.setDefaults($.datepicker.regional['es']);
          $(function(){
            $('[data-toggle="tooltip"]').tooltip()
          $.fn.dataTable.ext.classes.sPageButton = 'btn btn-primary';
          })
    </script>

<?= $content ?>

        </div>
    </div>
    
</body>
</html>