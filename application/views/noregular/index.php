<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">No Regulares</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("evaluaciones");?> ">Evaluaciones</a></li>
            <li class="active"><strong>No regulares</strong></li>
            <!--li ><strong>Nuevo</strong></li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li class="active show">
                        <a class="nav-link" data-toggle="tab" href="#repitencias"><i class="fa fa-user"></i> No regulares</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="repitencias">
                        <div class="panel-body">
                            <div class="ibox">
                            	<div class="ibox-title bg-primary">
                                    <h5>Notas no Regulares</h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </div>
                                </div>
                                <div class="ibox-content m-b-sm">
                                    <form name="import_form" id="import_form" class="form-horizontal"  >
                                        <div class="form-group">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <label>Alumno</label>
                                                <input type="text" class="form-control" placeholder="Ingrese codigo, nombre o apellidos para la busqueda." id="alumno">
                                                <input type="hidden" id="id_alumno_matricula" name="id_alumno_matricula" value="0">
                                                <input type="hidden" id="id_alumno" value="0">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label>Codigo</label>
                                                <input type="text" class="form-control" placeholder="18CI100001" name="codigo" id="autocompletecodigo">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3" id="chargeEspecialidad"style="display: none;">
                                                <label>Especialidad</label>
                                                <label class="nombreEspecialidad"></label>
                                                <input type="hidden" name="id_especialidad" id="id_especialidad" value="0">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="ibox-content border-bottom" id="resultado"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--div class="row"><div class="col-lg-4 col-sm-4 col-md-4"><label>Especialidad</label></div><div class="col-md-8 col-lg-8 col-sm-8"></div></div-->
<script type="text/javascript">
    $(document).on('focus',".datepicker", function(){
                    $(this).datepicker({
            format: 'dd-mm-yyyy'
        });
            }); 
	$(function(){
        $('ul.nav > li > a').on('click',function(){
            if($(this).attr('href') == '#alumnos'){
                $('#tabla_alumnos').show()
            }
            else{
                $('#tabla_alumnos').hide()
            }
        })
        $('[data-toggle="tooltip"]').tooltip()
        var getEstadoAlumnoEspecialidad = function(estado_especialidad){
            var estado = ''
            switch(parseInt(estado_especialidad)){
                case 1: 
                    estado = 'Activo, en cruso'
                    break;
                case 2: 
                    estado = 'Repitente Inactivo'
                    break;
                case 3: 
                    estado = 'Repitente Activo'
                    break;
                case 4: 
                    estado = 'Egresado'
                    break;
                default:
                    estado = 'Error'
                    break;
            }
            return estado
        }
        $(".chosen-select").chosen({width: "100%"});
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
        var cargaFuncionalidades = function(){
            $('#curso_no_encontrado').on('click',function(){
                $.confirm({
                    title: 'Ingresar curso',
                    columnClass: 'col-md-8 col-lg-8 col-sm-8 col-md-offset-2 col-lg-offset-2 col-sm-offset-2',
                    content: function(){
                        var self = this
                        return $.ajax({
                            url: '<?= base_url('noregular/cargaCursosNoEncontrados') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                charge: 1,
                                cod_alumno: $('#autocompletecodigo').val()
                            }
                        }).done(function(response){
                            if(response.status == 200){
                                self.setContentAppend('<div class="table-responsive"><table class="table table-striped table-bordered table-hover" id="listaCursosNoEncontrados" width="100%" cellspacing="0"><thead><tr><th>Cod. Curso</th><th>Nombre</th><th>Creditos</th><th>Ciclo</th><th>Nota</th><th>&nbsp;</th><th>&nbsp;</th></tr></thead><tbody>')
                                var d = response.data
                                for(var i in d){
                                    self.setContentAppend('<tr><td>'+d[i].codigo+'</td><td>'+d[i].curso+'</td><td>'+d[i].creditos+'</td><td>'+d[i].id_ciclo+'</td><td>'+(typeof d[i].valor_nota === 'undefined' ? '-' : d[i].valor_nota)+'</td><td><div class="btn-group"><button class="btn btn-primary agregar" type="button" data-codigo="'+d[i].codigo+'" data-nombre="'+d[i].curso+'" data-ciclo="'+d[i].id_ciclo+'" data-curso="'+d[i].id_curso+'" data-toggle="tooltip" data-placement="top" title="Ingresar nota"><i class="fa fa-edit"></i></button></div></td><td><button class="btn btn-default consultar" type="button" data-toggle="tooltip" data-placement="top" data-ciclo="'+d[i].id_ciclo+'" data-curso="'+d[i].id_curso+'" title="Consultar evaluaciones"><i class="fa fa-question-circle"></i></button></td></tr>')
                                }
                                self.setContentAppend('</tbody></table></div>')
                            }
                            else{
                                toastr.error(response.message)
                                self.close()
                            }
                        }).fail(function(){
                            self.close()
                            toastr.error('Error Consulte con su administrador')
                        })
                    },
                    contentLoaded: function(data, status, xhr){
                        $('[data-toggle="tooltip"]').tooltip()
                    },
                    onContentReady: function(){
                        var self = this
                        var table = $('#listaCursosNoEncontrados').dataTable({
                                          "language": {
                                            "paginate": {
                                              "first": "Primera pagina",
                                              "last": "Ultima pagina",
                                              "next": "Siguiente",
                                              "previous": "Anterior"
                                            },
                                            "emptyTable": "Tabla vacia",
                                            "infoEmpty": "Observando 0 a 0 d 0 registros",
                                            "info": "Observando pagina _PAGE_ de _PAGES_",
                                            //"info": '<a href="<?= base_url('practicas/imprimiractas') ?>" target="_blank" class="btn btn-primary" id="imprimir" id="boton_imprimir"><i class="fa fa-print"></i> Imprimir</a>',
                                            "lengthMenu": "Desplegando _MENU_ Registros",
                                            "search": "Busqueda"
                                          }
                                        })
                        $('#listaCursosNoEncontrados tbody tr td button.consultar').unbind('click')
                        $('#listaCursosNoEncontrados tbody tr td button.consultar').on('click',function(){
                            var but = $(this)
                            //var id_alumno_matricula_curso = $(this).attr('data-id-ref')
                            var cod_alumno = $('#autocompletecodigo').val()
                            var id_ciclo = $(this).attr('data-ciclo')
                            var id_curso = $(this).attr('data-curso')
                            $.confirm({
                                title: 'Informacion de notas',
                                columnClass: 'col-md-10 col-lg-10 col-sm-10',
                                content: function(){
                                    var self3 = this
                                    return $.ajax({
                                        url: '<?= base_url('noregular/listarNotasAux') ?>',
                                        method: 'POST',
                                        dataType: 'json',
                                        data: {
                                            cod_alumno: cod_alumno,
                                            id_curso: id_curso
                                        }
                                    }).done(function(response){
                                        if(response.status == 200){
                                            var d = response.data
                                            var cadena = '<div class="content"><div class="row"><form><div class="col-lg-12 col-md-12 col-sm-12"><div class="table-responsive"><table class="table table-striped table-bordered table-hover" id="tablaNotas_" width="100%" cellspacing="0"><thead><tr><th>Cod. Curso</th><th>Curso</th><th>Tipo de Nota</th><th>Evaluacion</th><th>Fecha de Acta</th><th>Fecha de registro</th><th>&nbsp</th><th>&nbsp</th></tr></thead><tbody>'
                                            for(var i in d){
                                                cadena += '<tr><td>'+d[i].codigo+'</td><td>'+d[i].curso+'</td><td>'+d[i].tipo_nota+'</td><td>'+d[i].valor_nota+'</td><td>'+d[i].fecha_acta+'</td><td>'+d[i].fecha_registro+'</td><td><div class="btn-group"><button class="btn btn-default editar" type="button" data-id="'+d[i].id_nota_no_regular_aux+'" data-id-periodo="'+d[i].id_periodo+'" data-id-ciclo="'+id_ciclo+'" data-id-turno="'+d[i].id_turno+'" data-id-tipo-nota="'+d[i].id_tipo_nota_no_regular+'" data-toggle="tooltip" data-placement="top" title="Editar Evaluacion"><i class="fa fa-edit"></i></button></div></td><td><button class="btn btn-danger eliminar" type="button" data-id="'+d[i].id_nota_no_regular_aux+'" data-id-periodo="'+d[i].id_periodo+'" data-id-ciclo="'+id_ciclo+'" data-id-turno="'+d[i].id_turno+'" data-id-tipo-nota="'+d[i].id_tipo_nota_no_regular+'" data-toggle="tooltip" data-placement="top" title="Eliminar registro"><i class="fa fa-trash"></i></td></tr>'
                                            }
                                            cadena += '</tbody></table></div></div></form></div></div>'
                                            self3.setContentAppend(cadena)
                                            var t = $('#tablaNotas_').dataTable({
                                                      "language": {
                                                        "paginate": {
                                                          "first": "Primera pagina",
                                                          "last": "Ultima pagina",
                                                          "next": "Siguiente",
                                                          "previous": "Anterior"
                                                        },
                                                        "emptyTable": "Tabla vacia",
                                                        "infoEmpty": "Observando 0 a 0 d 0 registros",
                                                        "info": "Observando pagina _PAGE_ de _PAGES_",
                                                        //"info": '<a href="<?= base_url('practicas/imprimiractas') ?>" target="_blank" class="btn btn-primary" id="imprimir" id="boton_imprimir"><i class="fa fa-print"></i> Imprimir</a>',
                                                        "lengthMenu": "Desplegando _MENU_ Registros",
                                                        "search": "Busqueda"
                                                      }
                                                    })
                                        }
                                        else{
                                            self3.close()
                                            toastr.error(response.message)
                                        }
                                    }).fail(function(){
                                        self3.close()
                                        toastr.error('Ocurio un error en el registo consulte con su administrador')
                                    })
                                },
                                onContentReady: function(){
                                    var self3 = this
                                    $('#tablaNotas_ tbody tr td button.eliminar').on('click',function(){
                                        var id_nota_no_regular_aux = $(this).attr('data-id')
                                        $.confirm({
                                            title: 'Atención',
                                            content: 'Esta seguro de eliminar el registro de evaluacion?',
                                            buttons: {
                                                si: {
                                                    text: 'si',
                                                    btnClass: 'btn-success',
                                                    keys: ['enter'],
                                                    action: function(){
                                                        $.confirm({
                                                            title: 'Eliminando',
                                                            content: function(){
                                                                var selfEliminar = this
                                                                return $.ajax({
                                                                    url: '<?= base_url('noregular/eliminarRegistroNoRegularAux') ?>',
                                                                    method: 'POST',
                                                                    dataType: 'json',
                                                                    data: {
                                                                        s: 1,
                                                                        id_nota_no_regular_aux: id_nota_no_regular_aux
                                                                    }
                                                                }).done(function(response){
                                                                    if(response.status == 200){
                                                                        selfEliminar.close()
                                                                        self3.close()
                                                                        self.close()
                                                                        $('#curso_no_encontrado').trigger('click')
                                                                        toastr.success(response.message)
                                                                    }
                                                                    else{
                                                                        selfEliminar.close()
                                                                        toastr.error(response.message)
                                                                    }
                                                                }).fail(function(){
                                                                    selfEliminar.close()
                                                                    toastr.error('Ocurrio un error consulte con su administrador')
                                                                })
                                                            }
                                                        })
                                                    }
                                                },
                                                no: {
                                                    text: 'no',
                                                    action: function(){}
                                                }
                                            }
                                        })
                                    })
                                    $('#tablaNotas_ tbody tr td button.editar').on('click',function(){
                                        var id_nota_no_regular_aux = $(this).attr('data-id')
                                        var id_ciclo = $(this).attr('data-id-ciclo')
                                        var id_turno = $(this).attr('data-id-turno')
                                        var id_periodo = $(this).attr('data-id-periodo')
                                        var id_tipo_nota_no_regular = $(this).attr('data-id-tipo-nota')
                                        $.confirm({
                                            title: 'Ingrese evaluación',
                                            content: function(){
                                                var self4 = this
                                                return $.ajax({
                                                    url: '<?= base_url('noregular/cargaForEdicionAux') ?>',
                                                    dataType: 'json',
                                                    method: 'POST',
                                                    data: {
                                                        s: 1,
                                                        id_nota_no_regular_aux: id_nota_no_regular_aux
                                                    }
                                                }).done(function(response){
                                                    if(response.status == 200){
                                                        var periodos = response.data.periodos
                                                        var per = ''
                                                        for(var i in periodos)
                                                            per += '<option value="'+periodos[i].id_periodo+'" '+(response.data.nota_no_reg.id_periodo == periodos[i].id_periodo ? 'selected' : '')+'>'+periodos[i].nombre+'</option>'
                                                        var d = response.data.tipos
                                                        var cad = ''
                                                        var f = response.data.nota_no_reg.fecha_acta.split('-')
                                                        for(var i in d)
                                                            cad += '<option value="'+d[i].id+'" '+(response.data.nota_no_reg.id_tipo_nota_no_regular == d[i].id ? 'selected' : '')+'>'+d[i].nombre+'</option>'
                                                        var tipo_eva = ''
                                                        if(response.data.curso.tipo_eval == 1)
                                                            tipo_eva = '<input type="text" class="form-control valor_nota" name="valor_nota" value="'+response.data.nota_no_reg.valor_nota+'" placeholder="01">'
                                                        else{
                                                            tipo_eva = '<select class="form-control valor_nota" name="valor_nota"><option value="A" '+(response.data.nota_no_reg.valor_nota == 'A' ? 'selected' : '')+'>A</option><option value="B" '+(response.data.nota_no_reg.valor_nota == 'B' ? 'selected' : '')+'>B</option><option value="C" '+(response.data.nota_no_reg.valor_nota == 'C' ? 'selected' : '')+'>C</option><option value="D" '+(response.data.nota_no_reg.valor_nota == 'D' ? 'selected' : '')+'>D</option></select>'
                                                        }
                                                        var turnos = response.data.turnos
                                                        var tur = ''
                                                        for( var i in turnos )
                                                            tur += '<option value="'+turnos[i].id_turno+'" '+(response.data.nota_no_reg.id_turno == turnos[i].id_turno ? 'selected':'')+'>'+turnos[i].nombre+'</option>'
                                                        self4.setContentAppend('<form id="actualizacion_nota"><input type="hidden" class="id-curso" value="'+response.data.curso.id_curso+'"><input type="hidden" name="id_nota_no_regular_aux" class="id_nota_no_regular_aux" value="'+id_nota_no_regular_aux+'"><input type="hidden" class="id_tipo_nota_no_regular" name="id_tipo_nota_no_regular" value="'+id_tipo_nota_no_regular+'"><input type="hidden" class="id_especialidad" name="id_especialidad" value="'+$('.id_especialidad_').val()+'"><input type="hidden" class="id_periodo" name="id_periodo" value="'+id_periodo+'"><input type="hidden" name="id_ciclo" value="'+id_ciclo+'"><input type="hidden" name="id_turno" value="'+id_turno+'"><label>Evaluacion</label>'+tipo_eva+'<label>Tipo de nota</label><select class="form-control tipo_nota" name="tipo_nota">'+cad+'</select><label>Fecha Acta</label><input type="text" class="form-control datepicker fecha_acta" name="fecha" value="'+f[2]+'-'+f[1]+'-'+f[0]+'"><label>Periodo</label><select class="form-control select-periodo periodo" name="periodo" id="select-periodo" required><option></option>'+per+'</select><label>Turno</label><select class="form-control selec-turno" name="turno" required><option></option>'+tur+'</select><label>Profesor</label><input type="text" class="form-control autocompleteprofesor2" id="autocompleteprofesor2" placeholder="Profesor" value="'+(response.data.profesor == 0 ? '' : response.data.profesor.apell_pat+' '+response.data.profesor.apell_mat+' '+response.data.profesor.nombre)+'"><input type="hidden" id="id_profesor2" name="id_profesor" value="'+(response.data.profesor == 0 ? 0 : response.data.profesor.id_profesor)+'"></form>')
                                                    }
                                                    else{
                                                        self4.close()
                                                        toastr.error(response.message)
                                                        $(but).trigger('click')
                                                    }
                                                }).fail(function(){
                                                    self4.close()
                                                    toastr.error('Ocurrio un error en el registo consulte con su administrador')
                                                })
                                            },
                                            buttons: {
                                                editar: {
                                                    text: 'Editar',
                                                    btnClass: 'btn btn-success',
                                                    keys: ['enter'],
                                                    action: function(){
                                                        var form = $('#actualizacion_nota')
                                                        if(!form.valid())
                                                            return false
                                                        var data_form = $('#actualizacion_nota').serialize()
                                                        $.ajax({
                                                            url: '<?= base_url('noregular/actualizarNotaNoRegularAux') ?>',
                                                            method: 'POST',
                                                            dataType: 'json',
                                                            data: data_form
                                                        }).done(function(response){
                                                            self3.close()
                                                            toastr.success(response.message)
                                                        }).fail(function(){
                                                            toastr.error('Ocurrio un error en el registro consulte con su administrador')
                                                        })
                                                    }
                                                },
                                                cancelar: function(){}
                                            },
                                            contentLoaded: function(data, status, xhr){
                                                var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                                                $('.datepicker').datepicker({
                                                        format: 'dd-mm-yyyy',
                                                        container: container,
                                                        todayHighlight: true,
                                                        autoclose: true
                                                    })
                                            },
                                            onContentReady: function(){
                                                $('#autocompleteprofesor2').autocomplete({
                                                    serviceUrl: '<?= base_url('profesores/getProfesoresAutocomplete') ?>',
                                                    minChars: 3,
                                                    dataType: 'text',
                                                    type: 'POST',
                                                    paramName: 'data',
                                                    params: {
                                                      'data': $('#autocompleteprofesor2').val()
                                                    },
                                                    onSelect: function(suggestion){
                                                        var prof = JSON.parse(suggestion.data)
                                                        console.log(prof)
                                                        $('#id_profesor2').val(prof.id)
                                                    },
                                                    focus: function(event, ui){
                                                        console.log($(this).attr('data-in'))
                                                    },
                                                    onSearchStart: function(q){},
                                                    onSearchComplete: function(q,suggestions){}
                                                })
                                                var form_register = this
                                                $('#select-periodo').on('change',function(){
                                                    var id_periodo = form_register.$content.find('.select-periodo').val();
                                                    var select_turno = form_register.$content.find('.selec-turno')
                                                    var id_curso = form_register.$content.find('.id-curso').val()
                                                    $.ajax({
                                                        url: 'regulares/chargeTurnosForPeriodo',
                                                        method: 'POST',
                                                        dataType: 'json',
                                                        data: {
                                                            id_periodo: id_periodo,
                                                            id_curso: id_curso
                                                        }
                                                    }).done(function(response){
                                                        if(response.status == 200){
                                                            var select = ''
                                                            var d = response.data
                                                            for(var i in d)
                                                                select += '<option value="'+d[i].id_turno+'">'+d[i].nombre+'</option>'
                                                            $(select_turno).html(select)
                                                        }
                                                        else{
                                                            toastr.error(response.message)
                                                        }
                                                    }).fail(function(){})
                                                })
                                            }
                                        })
                                    })
                                }
                            })
                        })
                        $('#listaCursosNoEncontrados tbody tr td button.agregar').unbind('click')
                        $('#listaCursosNoEncontrados tbody tr td button.agregar').on('click',function(){
                            var data_button = $(this)
                            $.confirm({
                                title: 'Agregar Nota',
                                columnClass: 'col-md-8 col-lg-8 col-sm-8 col-md-offset-2 col-lg-offset-2 col-sm-offset-2',
                                content: function(){
                                    var selfAgregado = this
                                    return $.ajax({
                                        url: '<?= base_url('regulares/chargeRegistroNota') ?>',
                                        dataType: 'JSON',
                                        method: 'POST',
                                        data: {
                                            cod_alumno: $('#autocompletecodigo').val(),
                                            id_curso: $(data_button).attr('data-curso')
                                        }
                                    }).done(function(response){
                                        if(response.status == 200){
                                            var tipos_nota = ''
                                            var per = ''
                                            var periodos = response.data.periodos
                                            for(var i in periodos)
                                                per += '<option value="'+periodos[i].id_periodo+'">'+periodos[i].nombre+'</option>'
                                            var tipos = response.data.tipos_nota
                                            for(var i in tipos)
                                                tipos_nota += '<option value="'+tipos[i].id+'">'+tipos[i].nombre+'</option>'
                                            var tipo_eval = response.data.tipo_eval
                                            if(tipo_eval.tipo_eval == 2)
                                                tipoe = '<select class="form-control valor_nota" name="valor_nota" required><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option></select><input type="hidden" name="eval_minima" value="'+tipo_eval.eval_minima+'">'
                                            else
                                                tipoe = '<input type="text" class="form-control valor_nota" name="valor_nota" required placeholder="00"><input type="hidden" name="eval_minima" value="'+tipo_eval.eval_minima+'">'
                                            var string = '<form id="registro_nota_aux"><input type="hidden" name="cod_alumno" value="'+$('#autocompletecodigo').val()+'"><input type="hidden" name="id_curso" class="id_curso" value="'+$(data_button).attr('data-curso')+'"><input type="hidden" name="id_especialidad" class="id_especialidad_" value="'+$('.id_especialidad_').val()+'"><div class="content"><div class="row"><div class="col-lg-4 col-sm-4 col-md-4"><label>Apellidos y Nombres</div><div class="col-md-8 col-lg-8 col-sm-8">'+$('label.credenciales').html()+'</div></div><div class="row"><div class="col-lg-4 col-sm-4 col-md-4"><label>Codigo de Curso</label></div><div class="col-md-8 col-lg-8 col-sm-8">'+$(data_button).attr('data-codigo')+'</div></div><div class="row"><div class="col-lg-4 col-sm-4 col-md-4"><label>Nombre Curso</label></div><div class="col-md-8 col-lg-8 col-sm-8">'+$(data_button).attr('data-nombre')+'</div></div><div class="row"><div class="col-lg-4 col-sm-4 col-md-4"><label>Especialidad</label></div><div class="col-md-8 col-lg-8 col-sm-8">'+$('label.especialidad_').html()+'</div></div><div class="row"><div class="col-lg-4 col-sm-4 col-md-4"><label>Ciclo</label></div><div class="col-md-8 col-lg-8 col-sm-8">'+$(data_button).attr('data-ciclo')+'<input type="hidden" class="id_ciclo_" name="id_ciclo" value="'+$(data_button).attr('data-ciclo')+'"></div></div><div class="row"><div class="col-lg-4 col-sm-4 col-md-4"><label>Periodo</label></div><div class="col-md-8 col-lg-8 col-sm-8"><select class="form-control periodo_lib" id="periodo_lib" name="id_periodo" required><option></option>'+per+'</select></div></div><div class="row"><div class="col-lg-4 col-sm-4 col-md-4"><label>Turno</label></div><div class="col-md-8 col-lg-8 col-sm-8"><select name="id_turno" class="form-control turno_lib" required></select></div></div><div class="row"><div class="col-lg-4 col-sm-4 col-md-4"><label>Tipo de Nota</label></div><div class="col-md-8 col-lg-8 col-sm-8"><select class="form-control tipo_nota_lib" name="id_tipo_nota_no_regular">'+tipos_nota+'</select></div></div><div class="row"><div class="col-lg-4 col-sm-4 col-md-4"><label>Valor Nota</label></div><div class="col-md-8 col-lg-8 col-sm-8">'+tipoe+'</div></div><div class="row"><div class="col-lg-4 col-sm-4 col-md-4"><label>Fecha Acta</label></div><div class="col-md-8 col-lg-8 col-sm-8"><input name="fecha" type="text" required class="form-control datepicker" value="<?= date('d-m-Y') ?>"></div></div><div class="row"><div class="col-lg-4 col-sm-4 col-md-4"><label>Profesor</label></div><div class="col-md-8 col-lg-8 col-sm-8"><input type="text" class="form-control autocompleteprofesor_" placeholder="Juan Perez"><input type="hidden" name="id_profesor" id="id_profesor_" value="0"></div></div><div class="row"><div class="col-lg-4 col-sm-4 col-md-4"><label>Registrar en acta</label></div><div class="col-md-8 col-lg-8 col-sm-8"><input type="checkbox" name="crear_acta" class="crear_acta" checked value="1"></div></div></div></form>'
                                            selfAgregado.setContentAppend(string)
                                        }
                                        else{
                                            selfAgregado.close()
                                            toastr.error(response.message)
                                        }
                                    }).fail(function(){
                                        selfAgregado.close()
                                        toastr.error('Ocurio un error en el registo consulte con su administrador')
                                    })
                                },
                                buttons: {
                                    registrar: {
                                        text: 'registrar',
                                        btnClass: 'btn btn-success',
                                        keys: ['enter'],
                                        action: function(){
                                            var form = $('#registro_nota_aux')
                                            // Disable validation on fields that are disabled or hidden.
                                            form.validate().settings.ignore = ":disabled,:hidden";
                                            form.validate().settings.errorPlacement = function(label,element){
                                                if($(element).attr('type') == 'radio')
                                                    label.insertAfter($(element).parent().parent().parent())
                                                else
                                                    label.insertAfter($(element).parent().parent())
                                            }
                                            form.validate().settings.errorClass= 'error block'

                                            // Start validation; Prevent going forward if false
                                            if(!form.valid())
                                                return false
                                            var data_ser = $('#registro_nota_aux').serialize()
                                            $.confirm({
                                                title: 'Registrando',
                                                content: function(){
                                                    var selfRegistro = this
                                                    return $.ajax({
                                                        url: '<?= base_url('regulares/registroNotaAux') ?>',
                                                        method: 'POST',
                                                        dataType: 'json',
                                                        data: data_ser
                                                    }).done(function(response){
                                                        selfRegistro.close()
                                                        toastr.success(response.message)
                                                        self.close()
                                                        $('#curso_no_encontrado').trigger('click')
                                                    }).fail(function(){
                                                        selfRegistro.close()
                                                        toastr.error('Ocurio un error en el registo consulte con su administrador')
                                                    })
                                                }
                                            })
                                        }
                                    },
                                    cancelar: function(){}
                                },
                                contentLoaded: function(data, status, xhr){
                                    $('[data-toggle="tooltip"]').tooltip()
                                },
                                onContentReady: function(){
                                    $('.autocompleteprofesor_').autocomplete({
                                        serviceUrl: '<?= base_url('profesores/getProfesoresAutocomplete') ?>',
                                        minChars: 3,
                                        dataType: 'text',
                                        type: 'POST',
                                        paramName: 'data',
                                        params: {
                                          'data': $('#autocompleteprofesor_').val()
                                        },
                                        onSelect: function(suggestion){
                                            var prof = JSON.parse(suggestion.data)
                                            console.log(prof)
                                            $('#id_profesor_').val(prof.id)
                                        },
                                        focus: function(event, ui){
                                            console.log($(this).attr('data-in'))
                                        },
                                        onSearchStart: function(q){},
                                        onSearchComplete: function(q,suggestions){}
                                    })
                                    var form_register = this
                                    $('#periodo_lib').on('change',function(){
                                        var id_periodo = form_register.$content.find('.periodo_lib').val();
                                        var select_turno = form_register.$content.find('.turno_lib')
                                        var id_curso = form_register.$content.find('.id_curso').val()
                                        $.ajax({
                                            url: 'regulares/chargeTurnosForPeriodo',
                                            method: 'POST',
                                            dataType: 'json',
                                            data: {
                                                id_periodo: id_periodo,
                                                id_curso: id_curso
                                            }
                                        }).done(function(response){
                                            if(response.status == 200){
                                                var select = ''
                                                var d = response.data
                                                for(var i in d)
                                                    select += '<option value="'+d[i].id_turno+'">'+d[i].nombre+'</option>'
                                                $(select_turno).html(select)
                                            }
                                            else{
                                                toastr.error(response.message)
                                            }
                                        }).fail(function(){})
                                    })
                                }
                            })
                        })
                    }
                })
            })
        }
        $('#alumno').autocomplete({
            serviceUrl: '<?= base_url('alumnos/getAlumnoAutocomplete_') ?>',
            minChars: 3,
            dataType: 'text',
            type: 'POST',
            paramName: 'data',
            params: {
              'data': $('#alumno').val()
            },
            onSelect: function(suggestion){
                var datos = JSON.parse(suggestion.data)
                $('#autocompletecodigo').val(datos.codigo)
                $('#autocompletecodigo').prop('disabled',true)
                $('#resultado').html('<div class="content"><div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 informacion"></div>'+
                        '<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 notas"></div></div></div>')
                $('#resultado').find('div.informacion').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h3 style="text-align: center;">Información</h3></div>')
                $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Nombres</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label class="credenciales">'+datos.apell_pat+' '+datos.apell_mat+', '+datos.nombre+'</label></div></div>')
                $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>DNI</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+datos.dni+'</label></div></div>')
                $.confirm({
                    title: 'Información',
                    content: function(){
                        var self = this
                        return $.ajax({
                            url: '<?= base_url('alumnos/getCargaInformacionAlumno') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                cod_alumno: $('#autocompletecodigo').val()
                            }
                        }).done(function(response){
                            self.close()
                            var r = response.data.infor
                            var t = response.data.solicitudes
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Tip. alumno</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.tipo_alumno+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Especialidad</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><input type="hidden" class="id_especialidad_" value="'+r.id_especialidad+'"><label class="especialidad_">'+r.especialidad+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Estado del alumno</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+getEstadoAlumnoEspecialidad(r.estado_especialidad)+'</label></div></div>')
                            //$('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Grupo</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.grupo+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Fecha Ingreso</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.fch_ingreso+'</label></div></div>')
                            //$('#resultado').find('label.nombreEspecialidad').html(r.especialidad)
                            var id_especialidad = r.id_especialidad
                            $('#id_especialidad').val(id_especialidad)
                            $.ajax({
                                url: '<?= base_url('especialidades/getNotasAlumno') ?>',
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    id_especialidad: id_especialidad,
                                    cod_alumno: $('#autocompletecodigo').val()
                                },
                                success: function(response){
                                    console.log(response)
                                    $('#resultado').find('div.notas').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h3 style="text-align: center;">Notas regulares</h3></div>')
                                    if(response.status == 200){
                                        var cadena = '<div class="col-lg-12 col-md-12 col-sm-12"><div class="table-responsive"><table class="table table-striped table-bordered table-hover" id="tablaNotas"><thead><tr><th>Cod.Curso</th><th>Curso</th><th>Eval. Regular</th><th>Eval. No Regular</th><th>Periodo</th><th>Turno</th><th>Ciclo</th><th>&nbsp</th><th>&nbsp;</th></tr></thead><tbody>'
                                        var d = response.data
                                        for(var i in d){
                                            cadena += '<tr><td>'+d[i].cod_curso+'</td><td>'+d[i].curso+'</td><td style="color: '+(d[i].tipo_eval == 1 ? (parseInt(d[i].valor_nota) >= parseInt(d[i].eval_minima) ? '#000' : '#F00') : d[i].valor_nota >= d[i].eval_minima ? '#000' : '#F00')+';">'+d[i].valor_nota+'</td><td>'+(d[i].nota_no_regular ? d[i].nota_no_regular.valor_nota : '-' )+'</td><td>'+d[i].periodo+'</td><td>'+d[i].turno+'</td><td>'+d[i].ciclo+'</td><td><div class="btn-group"><button class="btn btn-default registrar_nota" type="button" data-in="'+d[i].id_alumno_matricula_curso+'" data-id-periodo="'+d[i].id_periodo+'" data-id-turno="'+d[i].id_turno+'" data-eval-minima="'+d[i].eval_minima+'" data-id-ciclo="'+d[i].id_ciclo+'" data-toggle="tooltip" data-placement="top" title="Ingresar Nota"><i class="fa fa-edit"></i></button></td><td><button class="btn btn-default consultar" type="button" data-toggle="tooltip" data-placement="top" title="Consultar no regulares" data-id-periodo="'+d[i].id_periodo+'" data-id-turno="'+d[i].id_turno+'" data-id-ciclo="'+d[i].id_ciclo+'" data-id-ref="'+d[i].id_alumno_matricula_curso+'"><i class="fa fa-question-circle"></i></button></div></td></tr>'
                                        }
                                        cadena += '</tbody></table></div></div>'
                                        $('#resultado').find('div.notas').append(cadena+'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><center><div class="btn-group"><button type="button" class="btn btn-danger" id="curso_no_encontrado" data-toggle="tooltip" data-placement="top" title="Cursos no encontrados"><i class="fa fa-times"></i> Cursos no encontrados</button></div></center></div>')
                                        $('[data-toggle="tooltip"]').tooltip()
                                        var t = $('#tablaNotas').dataTable({
                                          "language": {
                                            "paginate": {
                                              "first": "Primera pagina",
                                              "last": "Ultima pagina",
                                              "next": "Siguiente",
                                              "previous": "Anterior"
                                            },
                                            "emptyTable": "Tabla vacia",
                                            "infoEmpty": "Observando 0 a 0 d 0 registros",
                                            "info": "Observando pagina _PAGE_ de _PAGES_",
                                            //"info": '<a href="<?= base_url('practicas/imprimiractas') ?>" target="_blank" class="btn btn-primary" id="imprimir" id="boton_imprimir"><i class="fa fa-print"></i> Imprimir</a>',
                                            "lengthMenu": "Desplegando _MENU_ Registros",
                                            "search": "Busqueda"
                                          }
                                        })
                                        cargaFuncionalidades()
            //consultar y editar una nota
            $('#tablaNotas tbody tr button.consultar').unbind('click')
            $('#tablaNotas tbody tr button.consultar').on('click',function(){
                var but = $(this)
                var id_alumno_matricula_curso = $(this).attr('data-id-ref')
                var id_periodo = $(this).attr('data-id-periodo')
                var id_ciclo = $(this).attr('data-id-ciclo')
                var id_turno = $(this).attr('data-id-turno')
                $.confirm({
                    title: 'Informacion de notas',
                    columnClass: 'col-md-10 col-lg-10 col-sm-10',
                    content: function(){
                        var self3 = this
                        return $.ajax({
                            url: '<?= base_url('noregular/listarNotas') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_alumno_matricula_curso: id_alumno_matricula_curso
                            }
                        }).done(function(response){
                            if(response.status == 200){
                                var d = response.data
                                var cadena = '<div class="content"><div class="row"><form><div class="col-lg-12 col-md-12 col-sm-12"><div class="table-responsive"><table class="table table-striped table-bordered table-hover" id="tablaNotas_" width="100%" cellspacing="0"><thead><tr><th>Cod. Curso</th><th>Curso</th><th>Tipo de Nota</th><th>Evaluacion</th><th>Fecha de Acta</th><th>Fecha de registro</th><th>&nbsp</th><th>&nbsp</th></tr></thead><tbody>'
                                for(var i in d){
                                    cadena += '<tr><td>'+d[i].codigo+'</td><td>'+d[i].curso+'</td><td>'+d[i].tipo_nota+'</td><td>'+d[i].valor_nota+'</td><td>'+d[i].fecha_acta+'</td><td>'+d[i].fecha_registro+'</td><td><div class="btn-group"><button class="btn btn-default editar" type="button" data-id="'+d[i].id_nota_no_regular+'" data-id-periodo="'+id_periodo+'" data-id-ciclo="'+id_ciclo+'" data-id-turno="'+id_turno+'" data-id-tipo-nota="'+d[i].id_tipo_nota_no_regular+'" data-toggle="tooltip" data-placement="top" title="Editar Evaluacion"><i class="fa fa-edit"></i></button></div></td><td><button class="btn btn-danger eliminar" type="button" data-id="'+d[i].id_nota_no_regular+'" data-toggle="tooltip" data-placement="top" title="Eliminar registro"><i class="fa fa-trash"></i></td></tr>'
                                }
                                cadena += '</tbody></table></div></div></form></div></div>'
                                self3.setContentAppend(cadena)
                                var t = $('#tablaNotas_').dataTable({
                                          "language": {
                                            "paginate": {
                                              "first": "Primera pagina",
                                              "last": "Ultima pagina",
                                              "next": "Siguiente",
                                              "previous": "Anterior"
                                            },
                                            "emptyTable": "Tabla vacia",
                                            "infoEmpty": "Observando 0 a 0 d 0 registros",
                                            "info": "Observando pagina _PAGE_ de _PAGES_",
                                            //"info": '<a href="<?= base_url('practicas/imprimiractas') ?>" target="_blank" class="btn btn-primary" id="imprimir" id="boton_imprimir"><i class="fa fa-print"></i> Imprimir</a>',
                                            "lengthMenu": "Desplegando _MENU_ Registros",
                                            "search": "Busqueda"
                                          }
                                        })
                            }
                            else{
                                self3.close()
                                toastr.error(response.message)
                            }
                        }).fail(function(){
                            self3.close()
                            toastr.error('Ocurio un error en el registo consulte con su administrador')
                        })
                    },
                    onContentReady: function(){
                        var self3 = this
                        $('#tablaNotas_ tbody tr td button.eliminar').on('click',function(){
                            var id_nota_no_regular = $(this).attr('data-id')
                            $.confirm({
                                title: 'Atención',
                                content: 'Esta seguro de eliminar el registro de evaluacion?',
                                buttons: {
                                    si: {
                                        text: 'si',
                                        btnClass: 'btn-success',
                                        keys: ['enter'],
                                        action: function(){
                                            $.confirm({
                                                title: 'Eliminando',
                                                content: function(){
                                                    var selfEliminar = this
                                                    return $.ajax({
                                                        url: '<?= base_url('noregular/eliminarRegistroNoRegular') ?>',
                                                        method: 'POST',
                                                        dataType: 'json',
                                                        data: {
                                                            s: 1,
                                                            id_nota_no_regular: id_nota_no_regular
                                                        }
                                                    }).done(function(response){
                                                        if(response.status == 200){
                                                            selfEliminar.close()
                                                            self3.close()
                                                            toastr.success(response.message)
                                                        }
                                                        else{
                                                            selfEliminar.close()
                                                            toastr.error(response.message)
                                                        }
                                                    }).fail(function(){
                                                        selfEliminar.close()
                                                        toastr.error('Ocurrio un error consulte con su administrador')
                                                    })
                                                }
                                            })
                                        }
                                    },
                                    no: {
                                        text: 'no',
                                        action: function(){}
                                    }
                                }
                            })
                        })
                        $('#tablaNotas_ tbody tr td button.editar').on('click',function(){
                            var id_nota_no_regular = $(this).attr('data-id')
                            var id_ciclo = $(this).attr('data-id-ciclo')
                            var id_turno = $(this).attr('data-id-turno')
                            var id_periodo = $(this).attr('data-id-periodo')
                            var id_tipo_nota_no_regular = $(this).attr('data-id-tipo-nota')
                            $.confirm({
                                title: 'Ingrese evaluación',
                                content: function(){
                                    var self4 = this
                                    return $.ajax({
                                        url: '<?= base_url('noregular/cargaForEdicion') ?>',
                                        dataType: 'json',
                                        method: 'POST',
                                        data: {
                                            s: 1,
                                            id_nota_no_regular: id_nota_no_regular
                                        }
                                    }).done(function(response){
                                        if(response.status == 200){
                                            var d = response.data.tipos
                                            var cad = ''
                                            var f = response.data.nota_no_reg.fecha_acta.split('-')
                                            for(var i in d)
                                                cad += '<option value="'+d[i].id+'" '+(response.data.nota_no_reg.id_tipo_nota_no_regular == d[i].id ? 'selected' : '')+'>'+d[i].nombre+'</option>'
                                            var tipo_eva = ''
                                            if(response.data.curso.tipo_eval == 1)
                                                tipo_eva = '<input type="text" class="form-control valor_nota" name="valor_nota" value="'+response.data.nota_no_reg.valor_nota+'" placeholder="01">'
                                            else{
                                                tipo_eva = '<select class="form-control valor_nota" name="valor_nota"><option value="A" '+(response.data.nota_no_reg.valor_nota == 'A' ? 'selected' : '')+'>A</option><option value="B" '+(response.data.nota_no_reg.valor_nota == 'B' ? 'selected' : '')+'>B</option><option value="C" '+(response.data.nota_no_reg.valor_nota == 'C' ? 'selected' : '')+'>C</option><option value="D" '+(response.data.nota_no_reg.valor_nota == 'D' ? 'selected' : '')+'>D</option></select>'
                                            }
                                            self4.setContentAppend('<form id="actualizacion_nota"><input type="hidden" name="id_nota_no_regular" class="id_nota_no_regular" value="'+id_nota_no_regular+'"><input type="hidden" class="id_tipo_nota_no_regular" name="id_tipo_nota_no_regular" value="'+id_tipo_nota_no_regular+'"><input type="hidden" class="id_especialidad" name="id_especialidad" value="'+$('.id_especialidad_').val()+'"><input type="hidden" class="id_periodo" name="id_periodo" value="'+id_periodo+'"><input type="hidden" name="id_ciclo" value="'+id_ciclo+'"><input type="hidden" name="id_turno" value="'+id_turno+'"><label>Evaluacion</label>'+tipo_eva+'<label>Tipo de nota</label><select class="form-control tipo_nota" name="tipo_nota">'+cad+'</select><label>Fecha Acta</label><input type="text" class="form-control datepicker fecha_acta" name="fecha" value="'+f[2]+'-'+f[1]+'-'+f[0]+'"><label>Profesor</label><input type="text" class="form-control autocompleteprofesor2" id="autocompleteprofesor2" placeholder="Profesor" value="'+(response.data.profesor == 0 ? '' : response.data.profesor.apell_pat+' '+response.data.profesor.apell_mat+' '+response.data.profesor.nombre)+'"><input type="hidden" id="id_profesor2" name="id_profesor" value="'+(response.data.profesor == 0 ? 0 : response.data.profesor.id_profesor)+'"></form>')
                                        }
                                        else{
                                            self4.close()
                                            toastr.error(response.message)
                                            $(but).trigger('click')
                                        }
                                    }).fail(function(){
                                        self4.close()
                                        toastr.error('Ocurrio un error en el registo consulte con su administrador')
                                    })
                                },
                                buttons: {
                                    editar: {
                                        text: 'Editar',
                                        btnClass: 'btn btn-success',
                                        keys: ['enter'],
                                        action: function(){
                                            var form = $('#actualizacion_nota')
                                            if(!form.valid())
                                                return false
                                            var data_form = $('#actualizacion_nota').serialize()
                                            $.ajax({
                                                url: '<?= base_url('noregular/actualizarNotaNoRegular') ?>',
                                                method: 'POST',
                                                dataType: 'json',
                                                data: data_form
                                            }).done(function(response){
                                                self3.close()
                                                toastr.success(response.message)
                                            }).fail(function(){
                                                toastr.error('Ocurrio un error en el registro consulte con su administrador')
                                            })
                                        }
                                    },
                                    cancelar: function(){}
                                },
                                contentLoaded: function(data, status, xhr){
                                    var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                                    $('.datepicker').datepicker({
                                            format: 'dd-mm-yyyy',
                                            container: container,
                                            todayHighlight: true,
                                            autoclose: true
                                        })
                                },
                                onContentReady: function(){
                                    $('#autocompleteprofesor2').autocomplete({
                                        serviceUrl: '<?= base_url('profesores/getProfesoresAutocomplete') ?>',
                                        minChars: 3,
                                        dataType: 'text',
                                        type: 'POST',
                                        paramName: 'data',
                                        params: {
                                          'data': $('#autocompleteprofesor2').val()
                                        },
                                        onSelect: function(suggestion){
                                            var prof = JSON.parse(suggestion.data)
                                            console.log(prof)
                                            $('#id_profesor2').val(prof.id)
                                        },
                                        focus: function(event, ui){
                                            console.log($(this).attr('data-in'))
                                        },
                                        onSearchStart: function(q){},
                                        onSearchComplete: function(q,suggestions){}
                                    })
                                }
                            })
                        })
                    }
                })
            })
            //ACCION PARA REGISTRAR UNA NOTA
            $('#tablaNotas tbody tr button.registrar_nota').unbind('click')
            $('#tablaNotas tbody tr button.registrar_nota').on('click',function(){
                var id_periodo = $(this).attr('data-id-periodo')
                var id_turno = $(this).attr('data-id-turno')
                var id_ciclo = $(this).attr('data-id-ciclo')
                var id_alumno_matricula_curso = $(this).attr('data-in')
                var eval_minima = $(this).attr('data-eval-minima')
                var input = this
                $.confirm({
                    title: 'Ingresando Nota',
                    columnClass: 'col-md-8 col-lg-8 col-sm-8 col-md-offset-2 col-lg-offset-2 col-sm-offset-2',
                    content: function(){
                        var self1 = this
                        self1.setContentAppend('<input type="hidden" class="id_periodo" value="'+id_periodo+'"><input type="hidden" class="id_turno" value="'+id_turno+'"><input type="hidden" class="id_ciclo" value="'+id_ciclo+'"><input type="hidden" class="eval_minima" value="'+eval_minima+'"><input type="hidden" class="id_alumno_matricula_curso" value="'+id_alumno_matricula_curso+'">')
                        return $.ajax({
                            url: '<?= base_url('noregular/getTipoNotaNoRegular') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                estado: 1,
                                id_alumno_matricula_curso: id_alumno_matricula_curso
                            }
                        }).done(function(response){
                            if(response.status == 200){
                                var d = response.data.evaluaciones
                                var c = response.data.curso
                                var cad = ''
                                for(var i in d){
                                    cad += '<option value="'+d[i].id+'">'+d[i].nombre+'</option>'
                                }
                                var not = ''
                                if(c.tipo_eval == 1){
                                    not = '<input type="text" class="form-control evaluacion" placeholder="Ingrese evaluacion" name="evaluacion">'
                                }
                                else{
                                    not = '<select class="form-control evaluacion" name="evaluacion"><option>Seleccione una nota</option><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option></select>'
                                }
                                self1.setContentAppend('<div class="content"><div class="row"><div class="col-lg-6 col-md-6 col-sm-6"><label>Tipo de nota</label><select name="tipo_nota" class="form-control tipo_nota">'+cad+'</select></div><div class="col-lg-6 col-md-6 col-sm-6"><label>Evaluación</label>'+not+'</div><hr><div class="col-lg-6 col-md-6 col-sm-6"><label>Profesor</label><input type="text" class="form-control" id="autocompleteprofesor" placeholder="Ingrese apellidos y nombres"><input type="hidden" name="id_profesor" id="id_profesor" value="0"></div><div class="col-lg-6 col-md-6 col-sm-6"><label>Fecha Acta</label><input type="text" class="form-control datepicker" value="<?= date('d-m-Y') ?>"></div></div></div>')
                            }else{
                                self1.setContentAppend('<label>Error consulte con su administrador</label>')
                            }
                        }).fail(function(){
                            self1.setContentAppend('<label>Error consulte con su administrador</label>')
                        })
                    },
                    buttons: {
                        registrar: {
                            text: '<i class="fa fa-save"></i> Registrar',
                            action: function(){
                                var evaluacion = this.$content.find('.evaluacion').val();
                                if(evaluacion == ''){
                                    toastr.error('Ingrese una evaluacion correcta')
                                    return false
                                }
                                var tipo_nota = this.$content.find('.tipo_nota').val();
                                var autocompleteprofesor = this.$content.find('#autocompleteprofesor').val();
                                var id_profesor = this.$content.find('#id_profesor').val();
                                var id_periodo = this.$content.find('.id_periodo').val();
                                var id_turno = this.$content.find('.id_turno').val();
                                var id_ciclo = this.$content.find('.id_ciclo').val();
                                var fecha = this.$content.find('.datepicker').val()
                                var valor_nota = this.$content.find('.evaluacion').val()
                                var eval_minima = this.$content.find('.eval_minima').val()
                                var id_alumno_matricula_curso = this.$content.find('.id_alumno_matricula_curso').val()
                                if(autocompleteprofesor == '' || id_profesor == 0){
                                    toastr.error('Ingrese un profesor correcto')
                                    return false
                                }
                                $.confirm({
                                    title: 'Registrando',
                                    content: function(){
                                        var self2 = this
                                        return $.ajax({
                                            url: '<?= base_url('noregular/registrarNotaNoRegular') ?>',
                                            method: 'POST',
                                            dataType: 'json',
                                            data: {
                                                id_especialidad: $('#id_especialidad').val(),
                                                id_periodo: id_periodo,
                                                id_turno: id_turno,
                                                id_ciclo: id_ciclo,
                                                fecha: fecha,
                                                id_profesor: id_profesor,
                                                valor_nota:  valor_nota,
                                                eval_minima: eval_minima,
                                                id_alumno_matricula_curso: id_alumno_matricula_curso,
                                                id_tipo_nota_no_regular: tipo_nota
                                            }
                                        }).done(function(response){
                                            if(response.status == 200){
                                                $('button[data-in="'+id_alumno_matricula_curso+'"]').parent().parent().siblings('td').eq(3).html(valor_nota)
                                                //$(input).parent().parent().parent().siblings('td').eq(3).html(valor_nota)
                                                self2.setContentAppend('Registro Satisfactorio<input type="hidden" class="id_acta" value="'+response.data.id_acta+'">')
                                                self2.close()
                                                toastr.success('Registro Satisfactorio')
                                            }
                                            else{
                                                toastr.error('Ocurio un error en el registo consulte con su administrador')
                                                self2.close()
                                            }
                                        }).fail(function(){
                                            self2.close()
                                            toastr.error('Ocurio un error en el registo consulte con su administrador')
                                        })
                                    },
                                    buttons: {
                                        /*imprimir: {
                                            text: '<i class="fa fa-print"></i> Imprimir',
                                            btnClass: 'btn-primary',
                                            action: function(){
                                                window.open('<?= base_url('noregular/imprimeActaNoRegular') ?>','_blank')
                                                return false
                                            }
                                        },*/
                                        aceptar: function(){}
                                    }
                                })
                            }
                        },
                        cancelar: function(){}
                    },
                    contentLoaded: function(data, status, xhr){
                        var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                        $('.datepicker').datepicker({
                                format: 'dd-mm-yyyy',
                                container: container,
                                todayHighlight: true,
                                autoclose: true
                            })
                    },
                    onContentReady: function(){
                        $('#autocompleteprofesor').autocomplete({
                            serviceUrl: '<?= base_url('profesores/getProfesoresAutocomplete') ?>',
                            minChars: 3,
                            dataType: 'text',
                            type: 'POST',
                            paramName: 'data',
                            params: {
                              'data': $('#autocompleteprofesor').val()
                            },
                            onSelect: function(suggestion){
                                var prof = JSON.parse(suggestion.data)
                                console.log(prof)
                                $('#id_profesor').val(prof.id)
                            },
                            focus: function(event, ui){
                                console.log($(this).attr('data-in'))
                            },
                            onSearchStart: function(q){},
                            onSearchComplete: function(q,suggestions){}
                        })
                    }
                })
            })
        t.on('draw.dt',function(){
            $('#tablaNotas tbody tr button.consultar').unbind('click')
            $('#tablaNotas tbody tr button.consultar').on('click',function(){
                var but = $(this)
                var id_alumno_matricula_curso = $(this).attr('data-id-ref')
                var id_periodo = $(this).attr('data-id-periodo')
                var id_ciclo = $(this).attr('data-id-ciclo')
                var id_turno = $(this).attr('data-id-turno')
                $.confirm({
                    title: 'Informacion de notas',
                    columnClass: 'col-md-10 col-lg-10 col-sm-10',
                    content: function(){
                        var self3 = this
                        return $.ajax({
                            url: '<?= base_url('noregular/listarNotas') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_alumno_matricula_curso: id_alumno_matricula_curso
                            }
                        }).done(function(response){
                            if(response.status == 200){
                                var d = response.data
                                var cadena = '<div class="content"><div class="row"><form><div class="col-lg-12 col-md-12 col-sm-12"><div class="table-responsive"><table class="table table-striped table-bordered table-hover" id="tablaNotas_" width="100%" cellspacing="0"><thead><tr><th>Cod. Curso</th><th>Curso</th><th>Tipo de Nota</th><th>Evaluacion</th><th>Fecha de Acta</th><th>Fecha de registro</th><th>&nbsp</th></tr></thead><tbody>'
                                for(var i in d){
                                    cadena += '<tr><td>'+d[i].codigo+'</td><td>'+d[i].curso+'</td><td>'+d[i].tipo_nota+'</td><td>'+d[i].valor_nota+'</td><td>'+d[i].fecha_acta+'</td><td>'+d[i].fecha_registro+'</td><td><div class="btn-group"><button class="btn btn-default editar" type="button" data-id="'+d[i].id_nota_no_regular+'" data-id-periodo="'+id_periodo+'" data-id-ciclo="'+id_ciclo+'" data-id-turno="'+id_turno+'" data-id-tipo-nota="'+d[i].id_tipo_nota_no_regular+'" data-toggle="tooltip" data-placement="top" title="Editar Evaluacion"><i class="fa fa-edit"></i></button></div></td></tr>'
                                }
                                cadena += '</tbody></table></div></div></form></div></div>'
                                self3.setContentAppend(cadena)
                                var t = $('#tablaNotas_').dataTable({
                                          "language": {
                                            "paginate": {
                                              "first": "Primera pagina",
                                              "last": "Ultima pagina",
                                              "next": "Siguiente",
                                              "previous": "Anterior"
                                            },
                                            "emptyTable": "Tabla vacia",
                                            "infoEmpty": "Observando 0 a 0 d 0 registros",
                                            "info": "Observando pagina _PAGE_ de _PAGES_",
                                            //"info": '<a href="<?= base_url('practicas/imprimiractas') ?>" target="_blank" class="btn btn-primary" id="imprimir" id="boton_imprimir"><i class="fa fa-print"></i> Imprimir</a>',
                                            "lengthMenu": "Desplegando _MENU_ Registros",
                                            "search": "Busqueda"
                                          }
                                        })
                            }
                            else{
                                self3.close()
                                toastr.error(response.message)
                            }
                        }).fail(function(){
                            self3.close()
                            toastr.error('Ocurio un error en el registo consulte con su administrador')
                        })
                    },
                    onContentReady: function(){
                        var self3 = this
                        $('#tablaNotas_ tbody tr td button.editar').on('click',function(){
                            var id_nota_no_regular = $(this).attr('data-id')
                            var id_ciclo = $(this).attr('data-id-ciclo')
                            var id_turno = $(this).attr('data-id-turno')
                            var id_periodo = $(this).attr('data-id-periodo')
                            var id_tipo_nota_no_regular = $(this).attr('data-id-tipo-nota')
                            $.confirm({
                                title: 'Ingrese evaluación',
                                content: function(){
                                    var self4 = this
                                    return $.ajax({
                                        url: '<?= base_url('noregular/cargaForEdicion') ?>',
                                        dataType: 'json',
                                        method: 'POST',
                                        data: {
                                            s: 1,
                                            id_nota_no_regular: id_nota_no_regular
                                        }
                                    }).done(function(response){
                                        if(response.status == 200){
                                            var d = response.data.tipos
                                            var cad = ''
                                            var f = response.data.nota_no_reg.fecha_acta.split('-')
                                            for(var i in d)
                                                cad += '<option value="'+d[i].id+'" '+(response.data.nota_no_reg.id_tipo_nota_no_regular == d[i].id ? 'selected' : '')+'>'+d[i].nombre+'</option>'
                                            var tipo_eva = ''
                                            if(response.data.curso.tipo_eval == 1)
                                                tipo_eva = '<input type="text" class="form-control valor_nota" name="valor_nota" value="'+response.data.nota_no_reg.valor_nota+'" placeholder="01">'
                                            else{
                                                tipo_eva = '<select class="form-control valor_nota" name="valor_nota"><option value="A" '+(response.data.nota_no_reg.valor_nota == 'A' ? 'selected' : '')+'>A</option><option value="B" '+(response.data.nota_no_reg.valor_nota == 'B' ? 'selected' : '')+'>B</option><option value="C" '+(response.data.nota_no_reg.valor_nota == 'C' ? 'selected' : '')+'>C</option><option value="D" '+(response.data.nota_no_reg.valor_nota == 'D' ? 'selected' : '')+'>D</option></select>'
                                            }
                                            self4.setContentAppend('<form id="actualizacion_nota"><input type="hidden" name="id_nota_no_regular" class="id_nota_no_regular" value="'+id_nota_no_regular+'"><input type="hidden" class="id_tipo_nota_no_regular" name="id_tipo_nota_no_regular" value="'+id_tipo_nota_no_regular+'"><input type="hidden" class="id_especialidad" name="id_especialidad" value="'+$('.id_especialidad_').val()+'"><input type="hidden" class="id_periodo" name="id_periodo" value="'+id_periodo+'"><input type="hidden" name="id_ciclo" value="'+id_ciclo+'"><input type="hidden" name="id_turno" value="'+id_turno+'"><label>Evaluacion</label>'+tipo_eva+'<label>Tipo de nota</label><select class="form-control tipo_nota" name="tipo_nota">'+cad+'</select><label>Fecha Acta</label><input type="text" class="form-control datepicker fecha_acta" name="fecha" value="'+f[2]+'-'+f[1]+'-'+f[0]+'"><label>Profesor</label><input type="text" class="form-control autocompleteprofesor2" id="autocompleteprofesor2" placeholder="Profesor" value="'+(response.data.profesor == 0 ? '' : response.data.profesor.apell_pat+' '+response.data.profesor.apell_mat+' '+response.data.profesor.nombre)+'"><input type="hidden" id="id_profesor2" name="id_profesor" value="'+(response.data.profesor == 0 ? 0 : response.data.profesor.id_profesor)+'"></form>')
                                        }
                                        else{
                                            self4.close()
                                            toastr.error(response.message)
                                            $(but).trigger('click')
                                        }
                                    }).fail(function(){
                                        self4.close()
                                        toastr.error('Ocurrio un error en el registo consulte con su administrador')
                                    })
                                },
                                buttons: {
                                    editar: {
                                        text: 'Editar',
                                        btnClass: 'btn btn-success',
                                        keys: ['enter'],
                                        action: function(){
                                            var form = $('#actualizacion_nota')
                                            if(!form.valid())
                                                return false
                                            var data_form = $('#actualizacion_nota').serialize()
                                            $.ajax({
                                                url: '<?= base_url('noregular/actualizarNotaNoRegular') ?>',
                                                method: 'POST',
                                                dataType: 'json',
                                                data: data_form
                                            }).done(function(response){
                                                self3.close()
                                                toastr.success(response.message)
                                            }).fail(function(){
                                                toastr.error('Ocurrio un error en el registro consulte con su administrador')
                                            })
                                        }
                                    },
                                    cancelar: function(){}
                                },
                                contentLoaded: function(data, status, xhr){
                                    var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                                    $('.datepicker').datepicker({
                                            format: 'dd-mm-yyyy',
                                            container: container,
                                            todayHighlight: true,
                                            autoclose: true
                                        })
                                },
                                onContentReady: function(){
                                    $('#autocompleteprofesor2').autocomplete({
                                        serviceUrl: '<?= base_url('profesores/getProfesoresAutocomplete') ?>',
                                        minChars: 3,
                                        dataType: 'text',
                                        type: 'POST',
                                        paramName: 'data',
                                        params: {
                                          'data': $('#autocompleteprofesor2').val()
                                        },
                                        onSelect: function(suggestion){
                                            var prof = JSON.parse(suggestion.data)
                                            console.log(prof)
                                            $('#id_profesor2').val(prof.id)
                                        },
                                        focus: function(event, ui){
                                            console.log($(this).attr('data-in'))
                                        },
                                        onSearchStart: function(q){},
                                        onSearchComplete: function(q,suggestions){}
                                    })
                                }
                            })
                        })
                    }
                })
            })
            $('#tablaNotas tbody tr button.registrar_nota').unbind('click')
            $('#tablaNotas tbody tr button.registrar_nota').on('click',function(){
                var id_periodo = $(this).attr('data-id-periodo')
                var id_turno = $(this).attr('data-id-turno')
                var id_ciclo = $(this).attr('data-id-ciclo')
                var id_alumno_matricula_curso = $(this).attr('data-in')
                var eval_minima = $(this).attr('data-eval-minima')
                $.confirm({
                    title: 'Ingresando Nota',
                    columnClass: 'col-md-8',
                    content: function(){
                        var self1 = this
                        self1.setContentAppend('<input type="hidden" class="id_periodo" value="'+id_periodo+'"><input type="hidden" class="id_turno" value="'+id_turno+'"><input type="hidden" class="id_ciclo" value="'+id_ciclo+'"><input type="hidden" class="eval_minima" value="'+eval_minima+'"><input type="hidden" class="id_alumno_matricula_curso" value="'+id_alumno_matricula_curso+'">')
                        return $.ajax({
                            url: '<?= base_url('noregular/getTipoNotaNoRegular') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                estado: 1,
                                id_alumno_matricula_curso: id_alumno_matricula_curso
                            }
                        }).done(function(response){
                            if(response.status == 200){
                                var d = response.data.evaluaciones
                                var c = response.data.curso
                                var cad = ''
                                for(var i in d){
                                    cad += '<option value="'+d[i].id+'">'+d[i].nombre+'</option>'
                                }
                                var not = ''
                                if(c.tipo_eval == 1){
                                    not = '<input type="text" class="form-control evaluacion" placeholder="Ingrese evaluacion" name="evaluacion">'
                                }
                                else{
                                    not = '<select class="form-control evaluacion" name="evaluacion"><option>Seleccione una nota</option><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option></select>'
                                }
                                self1.setContentAppend('<div class="content"><div class="row"><div class="col-lg-6 col-md-6 col-sm-6"><label>Tipo de nota</label><select name="tipo_nota" class="form-control tipo_nota">'+cad+'</select></div><div class="col-lg-6 col-md-6 col-sm-6"><label>Evaluación</label>'+not+'</div><hr><div class="col-lg-6 col-md-6 col-sm-6"><label>Profesor</label><input type="text" class="form-control" id="autocompleteprofesor" placeholder="Ingrese apellidos y nombres"><input type="hidden" name="id_profesor" id="id_profesor" value="0"></div><div class="col-lg-6 col-md-6 col-sm-6"><label>Fecha Acta</label><input type="text" class="form-control datepicker" value="<?= date('d-m-Y') ?>"></div></div></div>')
                            }else{
                                self1.setContentAppend('<label>Error consulte con su administrador</label>')
                            }
                        }).fail(function(){
                            self1.setContentAppend('<label>Error consulte con su administrador</label>')
                        })
                    },
                    buttons: {
                        registrar: {
                            text: '<i class="fa fa-save"></i> Registrar',
                            action: function(){
                                var evaluacion = this.$content.find('.evaluacion').val();
                                if(evaluacion == ''){
                                    toastr.error('Ingrese una evaluacion correcta')
                                    return false
                                }
                                var tipo_nota = this.$content.find('.tipo_nota').val();
                                var autocompleteprofesor = this.$content.find('#autocompleteprofesor').val();
                                var id_profesor = this.$content.find('#id_profesor').val();
                                var id_periodo = this.$content.find('.id_periodo').val();
                                var id_turno = this.$content.find('.id_turno').val();
                                var id_ciclo = this.$content.find('.id_ciclo').val();
                                var fecha = this.$content.find('.datepicker').val()
                                var valor_nota = this.$content.find('.evaluacion').val()
                                var eval_minima = this.$content.find('.eval_minima').val()
                                var id_alumno_matricula_curso = this.$content.find('.id_alumno_matricula_curso').val()
                                if(autocompleteprofesor == '' || id_profesor == 0){
                                    toastr.error('Ingrese un profesor correcto')
                                    return false
                                }
                                $.confirm({
                                    title: 'Registrando',
                                    content: function(){
                                        var self2 = this
                                        return $.ajax({
                                            url: '<?= base_url('noregular/registrarNotaNoRegular') ?>',
                                            method: 'POST',
                                            dataType: 'json',
                                            data: {
                                                id_especialidad: $('#id_especialidad').val(),
                                                id_periodo: id_periodo,
                                                id_turno: id_turno,
                                                id_ciclo: id_ciclo,
                                                fecha: fecha,
                                                id_profesor: id_profesor,
                                                valor_nota:  valor_nota,
                                                eval_minima: eval_minima,
                                                id_alumno_matricula_curso: id_alumno_matricula_curso,
                                                id_tipo_nota_no_regular: tipo_nota
                                            }
                                        }).done(function(response){
                                            if(response.status == 200){
                                                self2.setContentAppend('Registro Satisfactorio<input type="hidden" class="id_acta" value="'+response.data.id_acta+'">')
                                                self2.close()
                                                toastr.success('Registro Satisfactorio')
                                            }
                                            else{
                                                toastr.error('Ocurio un error en el registo consulte con su administrador')
                                                self2.close()
                                            }
                                        }).fail(function(){
                                            self2.close()
                                            toastr.error('Ocurio un error en el registo consulte con su administrador')
                                        })
                                    }
                                })
                            }
                        },
                        cancelar: function(){}
                    },
                    contentLoaded: function(data, status, xhr){
                        var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                        $('.datepicker').datepicker({
                                format: 'dd-mm-yyyy',
                                container: container,
                                todayHighlight: true,
                                autoclose: true
                            })
                    },
                    onContentReady: function(){
                        $('#autocompleteprofesor').autocomplete({
                            serviceUrl: '<?= base_url('profesores/getProfesoresAutocomplete') ?>',
                            minChars: 3,
                            dataType: 'text',
                            type: 'POST',
                            paramName: 'data',
                            params: {
                              'data': $('#autocompleteprofesor').val()
                            },
                            onSelect: function(suggestion){
                                var prof = JSON.parse(suggestion.data)
                                console.log(prof)
                                $('#id_profesor').val(prof.id)
                            },
                            focus: function(event, ui){
                                console.log($(this).attr('data-in'))
                            },
                            onSearchStart: function(q){},
                            onSearchComplete: function(q,suggestions){}
                        })
                    }
                })
            })
        })
                                    }
                                }
                            })
                        }).fail(function(){
                            self.close()
                            toastr.error('Error','Consulte con su administrador')
                        })
                    }
                })
            },
            onSearchStart: function(q){},
            onSearchComplete: function(q,suggestions){}
        })
	})
</script>