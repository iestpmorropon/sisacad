<script src="<?= base_url('assets/assets/js/plugins/switchery/switchery.js') ?>"></script>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Personal</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("permisos");?> ">Gestion</a></li>
            <li class="active">Permisos</li>
            <li ><strong>Usuario</strong></li>
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-title">
            <h5><?= isset($usuario) ? 'Editar' : 'Nuevo' ?> Usuario</h5> 
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="ibox-content m-b-sm border-bottom">
            <form method="post" id="form-register-user">
                <div class="content">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Nombre*</label>
                            <input type="text" class="form-control required" name="nombre" placeholder="Juan" required value="<?= isset($usuario_edit) && !is_numeric($usuario_edit) ? $usuario_edit->nombre : '' ?>">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Apellido Paterno*</label>
                            <input type="text" class="form-control required" name="apell_pat" placeholder="Perez" required value="<?= isset($usuario_edit) && !is_numeric($usuario_edit) ? $usuario_edit->apell_pat : '' ?>">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Apellido Materno*</label>
                            <input type="text" class="form-control required" name="apell_mat" placeholder="Albeloa" required value="<?= isset($usuario_edit) && !is_numeric($usuario_edit) ? $usuario_edit->apell_pat : '' ?>">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>DNI*</label>
                            <input type="text" class="form-control required" name="dni" placeholder="12345678" required value="<?= isset($usuario_edit) && !is_numeric($usuario_edit) ? $usuario_edit->apell_mat : '' ?>">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Email</label>
                            <input type="text" class="form-control" name="email" placeholder="email@email.com" value="<?= isset($usuario_edit) && !is_numeric($usuario_edit) ? $usuario_edit->email : '' ?>">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Fech. Nacimiento</label>
                            <input type="text" class="form-control datepicker" name="fecha_nac" value="<?= date('d-m-Y') ?>" value="<?= isset($usuario_edit) && !is_numeric($usuario_edit) ? $usuario_edit->fch_nac : '' ?>">
                        </div>
                        <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                            <label>Genero*</label>
                            <select name="genero" class="form-control" required id="genero" required>
                                <option>Seleccione genero</option>
                                <?php if(!is_numeric($genero)) foreach ($genero as $key => $value) { ?>
                                    <option value="<?= $value->id ?>" <?= isset($usuario_edit) && !is_numeric($usuario_edit) && $usuario_edit->id_genero == $value->id ? 'selected' : '' ?>><?= $value->nombre ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                            <label>Estado Civil*</label>
                            <select name="estado_civil" class="form-control" required id="estado_civil">
                                <option>Seleccione Estado Civil</option>
                                <?php if(!is_numeric($estado_civil)) foreach ($estado_civil as $key => $value) { ?>
                                    <option value="<?= $value->id ?>" <?= isset($usuario_edit) && !is_numeric($usuario_edit) && $usuario_edit->id_estado_civil == $value->id ? 'selected' : '' ?>><?= $value->nombre ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Usuario*</label>
                            <input type="text" class="form-control" name="usuario" required value="<?= isset($usuario_edit) && !is_numeric($usuario_edit) ? $usuario_edit->usuario : '' ?>">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Password*</label>
                            <input type="password" class="form-control" name="password" required>
                            <h6>Por defecto sera 123456</h6>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Rol</label>
                            <select class="form-control" name="rol" required>
                                <option>Seleccione un rol</option>
                                <?php foreach ($roles as $key => $value) { ?>
                                    <option value="<?= $value->id ?>"><?= $value->nombre ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div><hr>
                    <div class="row" id="permisos">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Accessos a modulos</label>
                            <?php $id_padre = 0; foreach ($modulos as $key => $value) { 
                                if($value['id_padre'] == 0){
                             ?>
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <i class="fa <?= $value['logo'] ?>"></i>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4"><?= $value['nombre'] ?></div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <input type="checkbox" class="js-switch" data-id="<?= $value['id'] ?>" data-in="<?= $value['id_padre'] ?>" id="permiso-<?= $value['id'] ?>" value="0">
                                        </div>
                                    </div>
                                </div>
                            <?php }else{ ?>
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1">&nbsp;</div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <i class="fa <?= $value['logo'] ?>"></i>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4"><?= $value['nombre'] ?></div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <input type="checkbox" class="js-switch" data-id="<?= $value['id'] ?>" data-in="<?= $value['id_padre'] ?>" id="permiso-<?= $value['id'] ?>" value="0">
                                        </div>
                                    </div>
                                </div>
                            <?php } } ?>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <div class="btn-group">
                                <a href=javascript:history.back(1) class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                                <button class="btn btn-primary" type="button"><i class="fa fa-save"></i> Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true
        });
        $('.chosen-select').chosen()
        var elements = []
        <?php foreach ($modulos as $key => $value) 
                if($value['id_padre'] == 0) { ?>
            var elem_<?= $value['id'] ?> = document.querySelector('#permiso-<?= $value['id'] ?>')
            var switchchery_<?= $value['id'] ?> = new Switchery(elem_<?= $value['id'] ?>,{ color: '#ED5565' })
        <?php } else { ?>
            var elem_<?= $value['id'] ?> = document.querySelector('#permiso-<?= $value['id'] ?>')
            var switchchery_<?= $value['id'] ?> = new Switchery(elem_<?= $value['id'] ?>,{ color: '#1ab394' })
            //var elem_<?= $value['id'] ?> = new Switchery(document.querySelector('#permiso-<?= $value['id'] ?>'),{ color: '#1ab394' })
        <?php } ?>
        //elements['elem_<?= $value['id'] ?>']  = elem_<?= $value['id'] ?>

        console.log(elements)
        $('input:checkbox').on('change',function(){
            console.log($(this).attr('id'))
            /*if(document.querySelector('input#'+$(this).attr('id')).checked)
                console.log($(this).attr('data-in'))
            if(elements['elem_'+$(this).attr('data-id')].checked){
                console.log($(this).attr('data-in'))
            }*/
        })

        /*/var elem_2 = document.querySelector('.js-switch');
        var switchery_2 = new Switchery(elem_2, { color: '#ED5565' });*/
        $('#form-register-user').on('submit',function(e){
            e.preventDefault()
            var form = $(this)
            form.validate().settings.ignore = ":disabled,:hidden";
            form.validate().settings.errorPlacement = function(label,element){
                if($(element).attr('type') == 'radio')
                    label.insertAfter($(element).parent().parent().parent())
                else
                    label.insertAfter($(element).parent().parent())
            }
            form.validate().settings.errorClass= 'error block'
            if(!form.valid())
                return false
        })
    })
</script>