<script src="<?= base_url('assets/assets/js/plugins/switchery/switchery.js') ?>"></script>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Personal</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("permisos");?> ">Gestion</a></li>
            <li class="active"><strong>Permisos</strong></li>
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-title">
            <h5>Gestion de Permisos por perfiles</h5> 
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="ibox-content m-b-sm border-bottom">
            <form method="post" id="form-register-user">
                <div class="content">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <label>Perfil</label>
                            <select class="form-control" name="perfil" id="perfil">
                                <option value="0">Seleccione perfil</option>
                                <?php if(!is_numeric($roles)) foreach ($roles as $key => $value) { ?>
                                    <option value="<?= $value->id ?>"><?= $value->nombre ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div><hr>
                    <div class="row" id="permisos">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Accessos a modulos</label>
                            <?php $id_padre = 0;
                            $padres = [];
                                    foreach ($modulos as $key => $value) {
                                        if($value['id_padre'] == 0)
                                            $padres[$value['id']] = $value;
                                    }
                                     foreach ($padres as $key => $value) { 
                             ?>
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <i class="fa <?= $value['logo'] ?>"></i>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4"><?= $value['nombre'] ?></div>
                                </div>
                            <?php foreach ($modulos as $k => $v) if($v['id_padre'] == $value['id']) {  ?>
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1">&nbsp;</div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <i class="fa <?= $v['logo'] ?>"></i>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3"><?= $v['nombre'] ?></div>
                                    <div class="col-lg-5 col-md-5 col-sm-5"><?= $v['descripcion'] ?></div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="i-checks"><label><input type="checkbox" class="i-check" disabled data-id-modulo="<?= $v['id'] ?>" data-in="<?= $v['id_padre'] ?>" id="permiso-<?= $v['id'] ?>" value="0"></label></div>
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
                                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
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
        $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        $('.chosen-select').chosen()
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };

        $('#perfil').on('change',function(){
            if($(this).val() == 0){
                toastr.error('Seleccione un perfil valido')
                $('#form-register-user input.i-check').each(function(index, elem){
                    $(this).prop('disabled',true)
                })
                return false
            }
            $('#form-register-user input.i-check').each(function(index, elem){
                $(this).prop('checked',false)
                $(this).prop('disabled',false)
            })
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
            var id_perfil = $(this).val()
            $.confirm({
                title: 'Consultando',
                content: function(){
                    var self = this
                    $.ajax({
                        url: '<?= base_url('permisos/getPermisosPerfil') ?>',
                        dataType: 'json',
                        method: 'POST',
                        data: {
                            id_perfil: id_perfil
                        }
                    }).done(function(response){
                        console.log(response)
                        var d = response.data.permisos
                        for(var i in d){
                            $('input[data-id-modulo="'+d[i].id_modulo+'"]').prop('checked',true)
                            $('input[data-id-modulo="'+d[i].id_modulo+'"]').val(1)
                        }
                        $('.i-checks').iCheck({
                            checkboxClass: 'icheckbox_square-green',
                            radioClass: 'iradio_square-green',
                        });
                        self.close()
                    }).fail(function(){
                        self.close()
                        toastr.error('Error, consulte con su Administrador')
                    })
                }
            })
        })
        $('.i-checks').on('ifClicked', function (ev) { 
            if(!$(ev.target).is(':checked'))
                $(ev.target).val(1)
            else
                $(ev.target).val(0)
         })
        $('#form-register-user').on('submit',function(e){
            e.preventDefault()
            var mods = []
            $('#form-register-user input.i-check').each(function(index,elem){
                //console.log($(this).val())
                mods.push({id_modulo: $(this).attr('data-id-modulo'), id_padre: $(this).attr('data-in'), valor: $(this).val()})
            })
            $.confirm({
                title: 'Guardando',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url('permisos/guardarPermisos') ?>',
                        dataType: 'json',
                        method: 'POST',
                        data: {
                            id_perfil: $('#perfil').val(),
                            modulos: mods
                        }
                    }).done(function(response){
                        self.close()
                        window.location.reload()
                    }).fail(function(){
                        self.close()
                        toastr.error('Error consulte con su Administrador')
                    })
                }
            })
            console.log(mods)
            /*var form = $(this)
            form.validate().settings.ignore = ":disabled,:hidden";
            form.validate().settings.errorPlacement = function(label,element){
                if($(element).attr('type') == 'radio')
                    label.insertAfter($(element).parent().parent().parent())
                else
                    label.insertAfter($(element).parent().parent())
            }
            form.validate().settings.errorClass= 'error block'
            if(!form.valid())
                return false*/
        })
    })
</script>