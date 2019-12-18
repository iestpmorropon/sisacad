<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Gestión</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("especialidades");?> ">Gestión</a></li>
            <li >Sección</li>
            <li class="active"><strong>Alumnos</strong></li>
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5>Lista de alumnos para la seccion <?= $seccion->seccion ?> para el curso <?= $seccion->curso ?></h5>
            <input type="hidden" id="id_seccion" value="<?= $seccion->id_seccion ?>">
            <input type="hidden" id="id_seccion_curso" value="<?= $seccion->id_seccion_curso ?>">
            <div class="ibox-tools">
                <!--a class="collapse-link"><i class="fa fa-chevron-up"></i></a-->
            </div>
        </div>
        <div class="ibox-content m-b-sm border-bottom">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div><br>
            <h4 id="label"><?php 
            switch ($seccion->estado_seccion_curso) {
                case 0:
                        echo 'Curso Abierto en curso';
                    break;
                case 1: 
                        echo 'Curso Cerrado por el profesor';
                    break;
                case 2:
                        echo 'Seccion Totalmente Cerrada';
                    break;

                default:
                    break;
            }
            ?></h4><hr>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <table class="table table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">  
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Apellidos y Nombres</th>
                            <th>DNI</th>
                            <th>Nota Regular</th>
                            <th>Recuperación</th>
                            <th>Nota Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $recuperacion = 0; foreach ($alumnos as $key => $value) { $recuperacion = isset($value->id_nota_recuperacion) ? 1 : 0; ?>
                            <tr id="<?= $value->id_alumno_matricula ?>" >
                                <td><?= ($key+1) ?></td>
                                <td><?= $value->apell_pat.' '.$value->apell_mat.' '.$value->nombre ?></td>
                                <td><?= $value->dni ?></td>
                                <td>
                                    <div class="row" id="conf-<?= $value->id_alumno_matricula ?>">
                                        <div class="col-lg-11 col-md-11 col-sm-11">
                                            <?php
                                            $valor_nota = $value->valor_nota;
                                             if($tipo_eval->tipo_eval == 1){ 
                                                $color = $value->valor_nota == '-' ? '' : ((int)$eval->eval_minima > (int)$value->valor_nota ? '#ed5565' : '#1ab394');
                                                $color_letra = $value->valor_nota == '-' ? '' : ((int)$eval->eval_minima > (int)$value->valor_nota ? '#fff' : '#000');
                                                ?>
                                            <input type="number" class="form-control nota" style="background: <?= $color ?> !important; color: <?= $color_letra ?> !important;" name="nota_<?= $value->id_alumno_matricula ?>" data-nota="<?= $value->id_nota ?>" data-in="<?= $value->id_alumno_matricula ?>" value="<?= $value->valor_nota == '-' ? '' : $value->valor_nota ?>" <?= $seccion_curso->estado == 0 ? '' : 'disabled' ?>>
                                            <?php }else{ ?>
                                            <select class="form-control nota" name="nota_<?= $value->id_alumno_matricula ?>" data-in="<?= $value->id_alumno_matricula ?>" <?= $value->valor_nota == '-' ? '' : 'disabled' ?>>
                                                <option value="A" <?= $value->valor_nota == '-' ? '' : 'selected' ?>>A</option>
                                                <option value="B" <?= $value->valor_nota == '-' ? '' : 'selected' ?>>B</option>
                                                <option value="C" <?= $value->valor_nota == '-' ? '' : 'selected' ?>>C</option>
                                                <option value="D" <?= $value->valor_nota == '-' ? '' : 'selected' ?>>D</option>
                                            </select>
                                            <?php } ?>
                                            <input type="hidden" name="id_alumno_matricula<?= $value->id_alumno_matricula ?>" value="<?= $value->id_alumno_matricula ?>">
                                            <input type="hidden" name="id_nota_<?= $value->id_alumno_matricula ?>" id="id_nota_<?= $value->id_alumno_matricula ?>" value="<?= $value->id_nota ?>">
                                            <input type="hidden" name="codigo_alumno_<?= $value->codigo_alumno ?>" class="codigo_alumno" value="<?= $value->codigo_alumno ?>">
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1">
                                            <label class="respuesta"><i></i></label>
                                        </div>
                                    </div>
                                    <!--div class="btn-group">
                                        <button class="btn btn-primary notas" data-toggle="tooltip" data-in="<?= '' ?>" data-placement="top" title="Ver Notas"><i class="fas fa-edit"></i></button>
                                    </div-->
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-lg-11 col-md-11 col-sm-11">
                                            <?php if(isset($value->id_nota_recuperacion)) if($tipo_eval->tipo_eval == 1){ 
                                                $color = $value->valor_nota_recuperacion == '-' ? '' : ((int)$eval->eval_minima > (int)$value->valor_nota_recuperacion ? '#ed5565' : '#1ab394');
                                                $color_letra = $value->valor_nota_recuperacion == '-' ? '' : ((int)$eval->eval_minima > (int)$value->valor_nota_recuperacion ? '#fff' : '#000');
                                            }
                                            if(isset($value->id_nota_recuperacion))
                                                $valor_nota = $value->valor_nota_recuperacion;
                                                ?>
                                            <input type="number" name="recuperacion_" style="background: <?= $color ?> !important; color: <?= $color_letra ?> !important;" <?= $color == '#1ab394' ? 'disabled':'' ?> data-padre="<?= isset($value->id_nota_recuperacion) ? $value->id_nota : 0 ?>" data-in="<?= isset($value->id_nota_recuperacion) ? $value->id_nota_recuperacion : 0 ?>"  value="<?= isset($value->id_nota_recuperacion) ? $value->valor_nota_recuperacion : '-' ?>" class="form-control recuperacion" <?= isset($value->id_nota_recuperacion) && $value->id_nota_recuperacion != 0 ? 'disabled' : '' ?> <?= $seccion_curso->estado == 1 ? '' : 'disabled' ?>>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-lg-11 col-md-11 col-sm-11">
                                            <input type="number" name="final_" data-in="" disabled=""  data-nota="<?= isset($value->id_nota_recuperacion) && $value->id_nota_recuperacion != 0 ? $value->id_nota_recuperacion : $value->id_nota ?>" class="form-control final" value="" <?= $seccion_curso->estado == 2 ? '' : 'disabled' ?>>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div><br>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="btn-group" id="group-buttons">
                    <!--a href=javascript:history.back(1) class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Volver</a-->
                    <a class="btn btn-danger" href="<?= base_url('cursos/gestionseccion/').$seccion_curso->id_periodo ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                    <button style="display: <?= isset($alumnos[0]) && $alumnos[0]->valor_nota != '-' ? 'none' : 'block' ?>;" type="button" class="btn btn-primary" id="guardar" data-toggle="tooltip" data-placement="top" title="Guardar Notas"><i class="fa fa-save"></i> Guardar</button>
                    <button style="display: <?= $seccion->estado_seccion_curso == 1 ? 'block' : 'none' ?>" type="button" class="btn btn-primary" id="recuperacion" data-toggle="tooltip" data-placement="top" title="Guardar Recuperacion"><i class="fas fa-save"></i> Guardar Recuperación</button>
                    <button style="display: <?= $seccion->estado_seccion_curso == 1 ? 'block' : 'none' ?>" disabled type="button" class="btn btn-primary" id="editar_recuperacion" data-toggle="tooltip" data-placement="top" title="Editar Notas"><i class="fa fa-edit"></i> Editar Recuperación</button>
                    <button style="display: <?= $seccion->estado_seccion_curso == 1 ? 'block' : 'none' ?>" type="button" class="btn btn-danger" id="cerrar_seccion" data-toggle="tooltip" data-placement="top" title="Cerrar Totalmente la seccion"><i class="fas fa-door-closed"></i> Cerrar Seccion</button>
                    <a style="display: <?= $seccion->estado_seccion_curso == 2 || $seccion->estado_seccion_curso == 3 ? 'block' : 'none' ?>;" href="<?= base_url('cursos/imprimiractacurso/').$seccion->id_seccion_curso ?>" class="btn btn-warning" disabled id="imprimir_acta" data-toggle="tooltip" data-placement="top" title="Imprimir Acta" target="_blank"><i class="fa fa-print"></i> Imprimir Acta</a>
                    <a href="<?= base_url('cursos/imprimirlista/').$id_seccion_curso.'/'.$id_curso ?>" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Imprimir Lista de alumnos" target="_blank"><i class="fa fa-print"></i> Lista</a>
                </div>
                <label id="resultado"></label>
            </div>
            <div class="pull-right">
            <span>Leyenda </span><label class="label label-primary">Aprobado</label>&nbsp;<label class="label label-danger">Desaprobado</label><br>
            </div><br>
            <div>&nbsp;</div>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(function(){
            var nota_minima = '<?= $eval->eval_minima ?>'
        $('[data-toggle="tooltip"]').tooltip()
        $('#editar_recuperacion').on('click',function(){
            $('#dataTable tbody tr td input.recuperacion').each(function(index,element){
                if($(this).val() != '0' && $(this).val() != '')
                    $(this).removeAttr('disabled')
            })
            $('#recuperacion').removeAttr('disabled')
        })
        $('.table tbody tr input.recuperacion').on('keyup',function(){
            var estado = false
            $('table tbody tr input.recuperacion').each(function(index,element){
                if($(this).val() != '')
                    estado = true
            })
            if(estado)
                $('#recuperacion').removeAttr('disabled')
            else
                $('#recuperacion').attr('disabled')
        })
        $('#cerrar_seccion').on('click',function(){
            $.confirm({
                title: 'Atención',
                content: 'Esta seguro de cerrar la sección?',
                buttons: {
                    si: function(){
                        $.confirm({
                            title: 'Resultado',
                            content: function(){
                                var self = this
                                var notas = []
                                $('.table tbody tr input.final').each(function(index,element){
                                    var id = $(this).attr('data-nota')
                                    //$(this).removeAttr('disabled')
                                    notas.push({id: id, nota: $(this).val()})
                                    //notas.id = $(this).val()
                                })
                                return $.ajax({
                                    url: '<?= base_url("profesores/cerrarSeccionCursoForProfesor") ?>',
                                    method: 'POST',
                                    dataType: 'json',
                                    data: {
                                        id_seccion_curso: $('#id_seccion_curso').val(),
                                        id_seccion: $('#id_seccion').val(),
                                        notas: notas
                                    }
                                }).done(function(response){
                                    if(response.status == 200){
                                        self.setContentAppend('Se cerro la sección con satisfacción ahora puede imprimir el acta')
                                        $('#imprimir_acta').removeAttr('disabled')
                                        $('#imprimir_acta').show()
                                        //self.close()
                                        $('#guardar').hide()
                                        $('#editar').hide()
                                        $('#cerrar_regular').hide()
                                        $('#recuperacion').hide()
                                        $('#cerrar_seccion').hide()
                                        $('#label').html('Seccion Totalmente Cerrada')
                                        $('#editar_recuperacion').hide()
                                    }
                                })
                            },
                            buttons: {
                                imprimir: {
                                    text: 'Imprimir',
                                    btnClass: 'btn btn-warning',
                                    action: function(){
                                        window.open('<?= base_url('cursos/imprimiractacurso/').$seccion->id_seccion_curso ?>','_blank')
                                    }
                                },
                                ok: function(){}
                            }
                        })
                    },
                    no: function(){}
                }
            })
        })
        $('#cerrar_regular').on('click',function(){
            $.confirm({
                title: 'Atención',
                content: 'Esta deacuerdo con los datos ingresados?',
                buttons: {
                    si: function(){
                        $.confirm({
                            content: function(){
                                var self = this
                                return $.ajax({
                                    url: '<?= base_url("profesores/cerrarCursoForProfesor") ?>',
                                    method: 'POST',
                                    dataType: 'json',
                                    data: {
                                        id_seccion_curso: $('#id_seccion_curso').val()
                                    }
                                }).done(function(response){
                                    if(response.status == 200){
                                        self.close()
                                        $('#guardar').hide()
                                        $('#editar').hide()
                                        $('#cerrar_regular').hide()
                                        $('#recuperacion').show()
                                        $('#cerrar_seccion').show()
                                    }
                                })
                            }
                        })
                    },
                    no: function(){}
                }
            })
        })
//        $('#editar').on('click',function(){
//            $('.table tbody tr td .nota').each(function(){
//                $(this).removeAttr('disabled')
//            })
//            $('#guardar').removeAttr('disabled')
//            $('#guardar').show()
//            $('#editar').hide()
//        })

        <?php if($recuperacion){ ?>
            $('.table tbody tr').each(function(){
                var rec = $(this).find('input.recuperacion')
                if($(rec).val() == ''){
                    $(this).find('input.final').val($(this).find('input.nota').val())
                    if(parseInt($(this).find('input.nota').val()) >= parseInt(nota_minima)){
                        $(this).find('input.final').attr('style','background: #1ab394; color: #000;')
                    }else{
                        $(this).find('input.final').attr('style','background: #ed5565 ; color: #fff;')
                    }
                }
                else{
                    $(this).find('input.final').val($(rec).val())
                    if(parseInt($(rec).val()) >= parseInt(nota_minima)){
                        $(this).find('input.final').attr('style','background: #1ab394; color: #000;')
                    }else{
                        $(this).find('input.final').attr('style','background: #ed5565 ; color: #fff;')
                    }
                }
            })
            $('#recuperacion').prop('disabled',true)
            $('#editar_recuperacion').prop('disabled',false)
            $('#imprimir_acta').removeAttr('disabled')
        <?php } ?>
        $('#recuperacion').on('click',function(){
            $.alert({
                title: 'Atención',
                content: 'Esta seguro de los datos ingresados?',
                buttons: {
                    si: function(){
                        var len = $('.table tbody tr').length
                        $('.table tbody tr').each(function(index,element){
                            var tr = this
                            var id_alumno_matricula = $(this).find('.nota').attr('data-in')
                            var codigo_alumno = $(this).find('input.codigo_alumno').val()
                            var rec = $(this).find('input.recuperacion')
                            var not = $(this).find('input.nota')
                            var id_nota_recuperacion = $(this).find('input.recuperacion').attr('data-in')
                            if($(rec).val() != ''){
                                $.ajax({
                                    url: '<?= base_url('cursos/registarNotaRecuperacion') ?>',
                                    type: 'POST',
                                    data: {
                                        id_nota: $(not).attr('data-nota'),
                                        valor_nota: $(rec).val(),
                                        tipo_nota: 3,
                                        tipo_eval: <?= $tipo_eval->tipo_eval ?>,
                                        id_curso: <?= $id_curso ?>,
                                        id_alumno_matricula: id_alumno_matricula,
                                        codigo_alumno: codigo_alumno,
                                        id_nota_recuperacion: id_nota_recuperacion
                                    },
                                    success: function(response){
                                        if(JSON.parse(response).status === 200){
                                            $(tr).find('input.final').val($(rec).val())
                                            $(tr).find('input.final').attr('data-nota',JSON.parse(response).data.id)
                                        }
                                        $(tr).find('input.final').attr('disabled',true)
                                    }
                                })
                            }
                            else{
                                $(rec).val(0)
                            }
                            $(rec).prop('disabled',true)
                            $(this).find('input.final').val($(this).find('input.nota').val())
                            if(index == (len-1)){
                                $('#editar_recuperacion').prop('disabled',false)
                                $('#editar_recuperacion').show()
                                $('#imprimir_acta').removeAttr('disabled')
                            }
                        })
//                        $('.table tbody tr').each(function(){
//                            var nota = $(this).find('.nota').val()
//                            var id_alumno_matricula = $(this).find('.nota').attr('data-in')
//                            var id_nota = $(this).find('.nota').attr('data-nota')
//                            var lab = $(this).find('label.respuesta')
//                            var campo = $(this).find('.nota')
//                            var codigo_alumno = $(this).find('input.codigo_alumno').val()
//                            if(nota != '' && nota.length <= 2){}
//                        })
                    },
                    no: function(){}
                }
            })
        })
        $('#guardar').on('click',function(){
            $.alert({
                title: 'Atención',
                content: 'Esta seguro de los datos ingresados?',
                buttons: {
                    si: function(){
                        $('.table tbody tr').each(function(){
                            var nota = $(this).find('.nota').val()
                            var id_alumno_matricula = $(this).find('.nota').attr('data-in')
                            var lab = $(this).find('label.respuesta')
                            var campo = $(this).find('.nota')
                            var codigo_alumno = $(this).find('input.codigo_alumno').val()
                            if(nota !== '' && nota.length <= 2){
                                $.ajax({
                                    url: '<?= base_url('cursos/registraNotaAlumno') ?>',
                                    type: 'POST',
                                    data: {
                                        nota: nota,
                                        tipo_nota: 2,
                                        tipo_eval: <?= $tipo_eval->tipo_eval ?>,
                                        id_curso: <?= $id_curso ?>,
                                        id_alumno_matricula: id_alumno_matricula,
                                        codigo_alumno: codigo_alumno
                                    },
                                    beforeSend: function(){
                                        $('#guardar').attr('disabled',true)
                                        //$(lab).html('<i class="fa fa-')
                                    },
                                    done: function(){
                                         setTimeout(function(){$(lab).html('')},300)
                                    },
                                    success: function(response){
                                        if(JSON.parse(response).status == 200){
                                            var d = JSON.parse(response).data
                                            $(lab).html('<i class="fa fa-check"></i>')
                                            $(campo).attr('disabled',true)
                                        }
                                        $('#guardar').removeAttr('disabled')
                                    }
                                })
                            }
                        })
                        $('#guardar').hide()
                        $('#editar').show()
                        $('#cerrar_regular').show()
                        $('#imprimir').show()
                    },
                    no: function(){
                    }
                }
            })
        })
    })
</script>