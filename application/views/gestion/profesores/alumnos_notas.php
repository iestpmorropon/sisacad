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
                            <?php if(count($capacidades) > 0) { foreach ($capacidades as $key => $value) { $val = $value['capacidad']; ?>
                            <th colspan="<?= count($value['items']) ?>">CAPACIDAD <?= ($key+1) ?><h6 style="padding: 0px;"><?= $val->nombre ?></h6></th>
                            <?php } } else{ ?>
                            <th>Sin Capacidades </th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th colspan="2">&nbsp;</th>
                            <?php if(!is_numeric($capacidades)) foreach ($capacidades as $key => $value) 
                                    if(!is_numeric($value['items'])) foreach ($value['items'] as $k => $val) { ?>
                                <th><?= 'I'.($k+1) ?></th>
                            <?php } ?>
                            <th>PROMEDIO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nota = false; if(!is_numeric($alumnos)) foreach ($alumnos as $key => $value) { ?>
                            <tr id="<?= $value->id_alumno_matricula ?>" data-in="<?= $value->id_alumno_matricula ?>">
                                <input type="hidden" class="cod_alumno" value="<?= $value->codigo_alumno ?>">
                                <td><?= ($key+1) ?></td>
                                <td><?= $value->apell_pat.' '.$value->apell_mat.' '.$value->nombre ?></td>
                                <?php foreach($capacidades as $ky => $val) if(!is_numeric($val['items']))
                                        foreach ($val['items'] as $k => $v) { ?>
                                <td>
                                    <input min="0" max="20" pattern="^[0-9]+" type="number" class="form-control nota-practica" data-in="<?= $v->id ?>" data-cod="<?= $val['capacidad']->id ?>" value="<?= isset($value->notas[$v->id]) ? $value->notas[$v->id] : '' ?>" <?= isset($value->notas[$v->id]) ? 'disabled' : '' ?> >
                                </td>
                                        <?php if(isset($value->notas[$v->id])) $nota = true; } ?>
                                <td>
                                    <input type="number" class="form-control nota-promedio"  disabled>
                                    <div class="row" id="conf-<?= $value->id_alumno_matricula ?>">
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div><br>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="btn-group" id="group-buttons">
                    <a class="btn btn-danger" id="volver" href="<?= base_url('cursos/gestionseccion/').$seccion_curso->id_periodo ?>"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                    <!--a href=javascript:history.back(1) class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Volver</a-->
                    <button type="button" class="btn btn-success" id="calcular" data-toggle="tooltip" data-placemet="top" title="Calcular Promedio"><i class="fas fa-calculator"></i> Calcular</button>
                    <button type="button" class="btn btn-primary" id="guardar" data-toggle="tooltip" data-placement="top" title="Guardar Notas" disabled><i class="fa fa-save"></i> Guardar</button>
                    <button type="button" class="btn btn-primary" style="display: <?= $nota ? 'block;' : 'none;' ?>" id="editar" data-toggle="tooltip" data-placement="top" title="Editar Notas"><i class="fa fa-edit"></i> Editar</button>
                    <button type="button" class="btn btn-danger" <?= $nota ? 'false;' : 'true;' ?> id="cerrado" data-toggle="tolltip" data-placement="top" title="Cerrado nota regular"><i class="fas fa-window-close"></i> Cerrar</button>
                    <!--a href="<?= base_url('cursos/impresionseccionnotas/').$seccion->id_seccion_curso ?>" style="display: <?= $seccion->estado_seccion == 3 ? 'block;' : 'none;' ?>" class="btn btn-warning" target="_blank"><i class="fa fa-print"></i> Imprimir</a-->
                </div>
                <label id="resultado"></label>
            </div>
            <div>&nbsp;</div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('[data-toggle="tooltip"]').tooltip()
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
        $('.nota-practica').keydown(function(){
            if($(this).val().length > 2)
                return false
        }).keyup(function(){
            if(parseInt($(this).val()) > 20){
                $(this).val(20)
            }
            if($(this).val().length >= 2){
                var nex = $('.nota-practica').index(this)+1
                var nexl = $('.nota-practica').eq(nex)
                nexl.focus()
            }
        })
        $('#editar').on('click',function(){
            $('#dataTable tbody tr input.nota-practica').each(function(index,element){
                $(this).prop('disabled',false)
            })
        })
        $('#cerrado').on('click',function(){
            $.alert({
                title: 'Atención',
                content: 'Esta seguro de cerrar el proceso de notas regulares??',
                buttons: {
                    si: function(){
                        $.confirm({
                            title: 'Actualizando',
                            content: function(){
                                var self = this
                                $.ajax({
                                    url: '<?= base_url('cursos/updateSeccion') ?>',
                                    method: 'POST',
                                    dataType: 'json',
                                    data: {
                                        estado: 1,
                                        id_seccion_curso: $('#id_seccion_curso').val()
                                    }
                                }).done(function(response){
                                    self.close()
                                    $('#label').html('Curso Cerrado por el profesor')
                                    $('#calcular').hide()
                                    $('#guardar').hide()
                                    $('#editar').hide()
                                    $('#cerrar').hide()
                                }).fail(function(response){
                                    self.setContentAppend('Error consulte con su administrador')
                                })
                            }
                        })
                    },
                    no: function(){}
                }
            })
        })
        $('#guardar').on('click',function(){
            $('#editar').prop('disabled',true)
            var len = $('#dataTable tbody tr').length
            $('#dataTable tbody tr').each(function(index, element){
                var id_alumno_matricula = $(this).attr('data-in')
                var cod_alumno = $(this).find('input.cod_alumno').val()
                $('#volver').attr('disabled',true)
                $('#calcular').attr('disabled',true)
                $('#guardar').attr('disabled',true)
                $('#cerrado').attr('disabled',true)
                $('#resultado').html('Espere...')
                $(this).find('input.nota-practica').each(function(){
                    if($(this).val() != ''){
                        var id_capacidad = $(this).attr('data-cod')
                        var id_item = $(this).attr('data-in')
                        var valor_nota = $(this).val()
                        var input = $(this)
                        $(input).prop( "disabled", true )
                        $.ajax({
                            url: '<?= base_url('cursos/ingresaNotaPractica') ?>',
                            type: 'POST',
                            data: {
                                id_alumno_matricula: id_alumno_matricula,
                                id_curso: <?= $id_curso ?>,
                                cod_alumno: cod_alumno,
                                id_capacidad: id_capacidad,
                                id_item: id_item,
                                valor_nota: valor_nota
                            },
                            success: function(response){
                                if(JSON.parse(response).status == 200){
                                    var id = JSON.parse(response).data
                                    $(input).attr('data-id',id)
                                    if(index == (len-1)){
                                        $('#editar').prop('disabled',false)
                                        $('#editar').show()
                                        $('#cerrado').removeAttr('disabled')
                                        $('#volver').removeAttr('disabled')
                                        $('#calcular').removeAttr('disabled')
                                        $('#guardar').removeAttr('disabled')
                                        $('#resultado').html('')
                                        toastr.success('Guardado correctamente')
                                    }
                                }
                            }
                        })
                    }
                })
                $(this).find('input.nota-promedio').each(function(){
                    var nota = $(this).val()
                    if($(this).val() != ''){
                        $.ajax({
                            url: '<?= base_url('cursos/registraNotaAlumno') ?>',
                            type: 'POST',
                            data: {
                                nota: nota,
                                tipo_nota: 2,
                                tipo_eval: 1,
                                id_curso: <?= $id_curso ?>,
                                id_alumno_matricula: id_alumno_matricula,
                                codigo_alumno: cod_alumno
                            },
                            success: function(response){}
                        })
                    }
                })
            })
        })
        var calcular = function(){
            $('#guardar').prop('disabled',false)
            $('#dataTable tbody tr').each(function(index,element){
                var nota_capac = []
                var nota_items = []
                var id_capacidad = ''
                $(this).find('input.nota-practica').each(function(){
                    id_capacidad = $(this).attr('data-cod')
                    if(id_capacidad in nota_capac)
                        nota_items = nota_capac[id_capacidad]
                    if($(this).val() == ''){
                        nota_items.push(0)
                        $(this).val(0)
                    }
                    else
                        nota_items.push($(this).val())
                    nota_capac[id_capacidad] = nota_items
                    nota_items = []
                })
                var pro = 0
                var c = 0
                for(var i in nota_capac){
                    var it = 0
                    var ar = nota_capac[i]
                    for(var a in ar){
                        it += parseFloat(ar[a])
                    }
                    pro += parseFloat(it)/parseFloat(ar.length)
                    c++
                    console.log('CAPACIDAD '+i+' promedio: '+pro)
                }
                console.log(parseFloat(pro)/parseFloat(c))
                $(this).find('input.nota-promedio').val(Math.round(parseFloat(pro)/parseFloat(c)))
                nota_capac = []
                nota_items = []
            })
        }
        
        $('#calcular').on('click',function(){
            calcular()
        })
        <?php if($nota){ ?>
            calcular()
            $('#guardar').prop('disabled',true)
        <?php } ?>
    })
</script>