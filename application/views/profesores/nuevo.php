<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Gestion Interna</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Gestion</a></li>
            <li>Profesores</li>
            <li class="active"><strong>Nuevo</strong></li>
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox " id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5>Registro de Profesores</h5> 
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="ibox-content m-b-sm border-bottom" >
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
            <form name="form-register-profesor" id="form-register-profesor" class="form-horizontal" method="POST" >
                <div class="form-group">
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Nombre*</label>
                        <input type="text" class="form-control" required name="nombre" required placeholder="Juan" id="nombre" value="<?= !is_numeric($profesor) ? strtoupper( $profesor->nombre ) : '' ?>">
                        <input type="hidden" name="id_profesor" id="id_profesor" value="<?= !is_numeric($profesor) ? $profesor->id_profesor : 0 ?>">
                        <input type="hidden" name="id_persona" id="id_persona" value="<?= !is_numeric($profesor) ? $profesor->id_persona : 0 ?>">
                        <input type="hidden" name="id_usuario" id="id_usuario" value="<?= !is_numeric($profesor) ? $profesor->id_usuario : 0 ?>">
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Apell. Paternos*</label>
                        <input type="text" class="form-control" required name="apell_pat" required placeholder="Perez" id="apell_pat" value="<?= !is_numeric($profesor) ? strtoupper( $profesor->apell_pat ) : '' ?>">
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Apell. Maternos*</label>
                        <input type="text" class="form-control" required name="apell_mat" required placeholder="Albela" id="apell_mat" value="<?= !is_numeric($profesor) ? strtoupper( $profesor->apell_mat ) : '' ?>">
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>DNI*</label>
                        <input type="text" class="form-control" required name="dni" required placeholder="87654321"  value="<?= !is_numeric($profesor) ? $profesor->dni : '' ?>" id="dni">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
                        <label>Dirección</label>
                        <input type="text" name="direccion" id="direccion" placeholder="Dirección" class="form-control" value="<?= !is_numeric($profesor) ? $profesor->direccion : '' ?>">
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Fec. Nacimiento</label>
                        <input type="text" class="form-control datepicker"  name="fch_nac" value="<?= !is_numeric($profesor) ? date('d-m-Y',strtotime($profesor->fch_nac)) : date('d-m-Y') ?>">
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Genero</label>
                        <select name="genero" class="form-control" id="genero">
                            <option>Seleccione genero</option>
                            <?php foreach ($genero as $key => $value) { ?>
                                <option value="<?= $value->id ?>" <?= !is_numeric($profesor) && $profesor->id_genero == $value->id ? 'selected' : '' ?>><?= $value->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Estado Civil</label>
                        <select name="estado_civil" class="form-control" id="estado_civil">
                            <option>Seleccione Estado Civil</option>
                            <?php foreach ($estado_civil as $key => $value) { ?>
                                <option value="<?= $value->id ?>" <?= !is_numeric($profesor) && $profesor->id_estado_civil == $value->id ? 'selected' : '' ?>><?= $value->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Email</label>
                        <input style="display: block;" type="email" name="email" placeholder="Email" id="email" class="form-control" value="<?= !is_numeric($profesor) ? $profesor->email : '' ?>">
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Telefono</label>
                        <input style="display: block;" type="number" class="form-control" name="telefono" id="telefono" placeholder="073123456" value="<?= !is_numeric($profesor) ? $profesor->telefono : '' ?>">
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Celular 1</label>
                        <input style="display: block;" type="number" class="form-control" name="celular_1" id="celular_1" placeholder="073123456" value="<?= !is_numeric($profesor) ? $profesor->celular_1 : '' ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Celular 2</label>
                        <input style="display: block;" type="number" class="form-control" name="celular_2" id="celular_2" placeholder="073123456"  value="<?= !is_numeric($profesor) ? $profesor->celular_2 : '' ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Codigo*</label>
                        <input type="text" class="form-control" name="codigo" id="codigo" readonly placeholder="PRF-MAIN"  value="<?= !is_numeric($profesor) ? $profesor->codigo : '' ?>">
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Usuario*</label>
                        <input type="text" name="usuario" class="form-control" required placeholder="Usuario" id="usuario"  value="<?= !is_numeric($profesor) ? $profesor->usuario : '' ?>">
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <div class="col-sm-3">
                        <div class="btn-group">
                            <a href=javascript:history.back(1) class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h6>* Campos Obligatorios</h6>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">&nbsp;</div>
<script type="text/javascript">
    $(document).on('focus',".datepicker", function(){
                    $(this).datepicker({
            format: 'dd-mm-yyyy'
        });
            }); 
    $(function(){
        $('#dni').on('blur',function(){
            if($(this).val() != '' && $(this).val().length == 8){
                $('#codigo').val('P'+$('#dni').val())
                $('#usuario').val($('#dni').val())
            }
        })
        $('#form-register-profesor').on('submit',function(e){
            e.preventDefault()
            /*if($('#genero').val() == 'Seleccione genero'){
                $.alert({
                    title: 'Atención',
                    content: 'Seleccione un genero valido',
                    type: 'red',
                    typeAnimated: true
                })
                return false
            }
            if($('#estado_civil').val() == 'Seleccione Estado Civil'){
                $.alert({
                    title: 'Atención',
                    content: 'Seleccione un estado civil valido',
                    type: 'red',
                    typeAnimated: true
                })
                return false
            }
            var form = $(this);

            // Disable validation on fields that are disabled or hidden.
            form.validate().settings.ignore = ":disabled,:hidden";

            // Start validation; Prevent going forward if false
            if(!form.valid())
                return false;*/
            $('#codigo').prop('disabled',false)
            $('#codigo').removeAttr('readonly')
            var form_data = $(this).serialize()
            $.confirm({
                title: 'Nuevo Profesor',
                theme: 'light',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url("profesores/nuevo") ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: form_data
                    }).done(function(response){
                        self.setContentAppend(response.message+' codigo de Profesor '+response.data.codigo_prof)
                        //if(response.status == 200){}
                    })
                },
                buttons: {
                    ok: function(){
                        window.location.href = '<?php echo base_url('profesores'); ?>';
                    }
                },
                contentLoaded: function(data, status, xhr){
                    //self.setContentAppend('<h2>Resultado:</h2>');
                },
                onContentReady: function(){
                    //this.setContentAppend('<div>Resultado:</div>');
                }
            })
        })
        var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true
          
        });
        $('#apell_mat').on('keyup',function(){
            //$.alert($(this).val())
            var nom = $('#nombre').val() != '' ? $('#nombre').val().split(' ') : ['']
            var apell_pat = $('#apell_pat').val() != '' ? $('#apell_pat').val() : ' '
            var apell_mat = $('#apell_mat').val() != '' ? $('#apell_mat').val() : ' '
            $('#usuario').val(apell_pat.charAt(0) + nom[0] + apell_mat.charAt(0))
        })
    })
</script>