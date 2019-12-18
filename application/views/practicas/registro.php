<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Practicas</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("especialidades");?> ">Evaluaciones</a></li>
            <li class="active"><strong>Practicas</strong></li>
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
                        <a class="nav-link" data-toggle="tab" href="#registro"><i class="fa fa-user"></i> Registro</a>
                    </li>
                    <!--li class="active show">
                        <a class="nav-link" data-toggle="tab" href="#consulta"><i class="fa fa-users"></i> Consulta</a>
                    </li-->
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="registro">
                        <div class="panel-body">
                            <div class="ibox" id="box_import_invoices">
                                <div class="ibox-content m-b-sm">
                                    <form name="import_form" id="import_form" class="form-horizontal"  >
                                        <div class="form-group">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <label>Alumno</label>
                                                <input type="text" class="form-control" placeholder="Ingrese DNI, nombre o apellidos para la busqueda." id="alumno">
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
                    <div class="tab-pane " id="consulta">
                        <div class="panel-body">
                            <div class="ibox" id="">
                                <div class="ibox-content m-b-sm">
                                    <form name="" id="form-consulta" class="form-horizontal">
                                        <div class="form-group ">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <label>Rango de fechas</label>
                                                <input type="text" class="input-sm form-control dates" name="date[]" value="<?= 'Desde '.date('d-m-Y').' hasta '.date('d-m-Y') ?>"/>
                                                <input type="hidden" name="fechaStart" id="fechaStart" value="<?= date('d-m-Y') ?>">
                                                <input type="hidden" name="fechaEnd" id="fechaEnd" value="<?= date('d-m-Y') ?>">
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <label>Turno</label>
                                                <select class="form-control" name="turno" id="turno">
                                                    <option>Seleccione un turno</option>
                                                    <option value="1">Vespertino</option>
                                                    <!--<option value="2">Nocturno</option>-->
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <label>Especialidades</label>
                                                <select class="form-control chosen-select" name="especialidades" id="especialidades">
                                                    <option>Seleccione Especialidad</option>
                                                    <?php if($especialidades) foreach ($especialidades as $key => $value) { ?>
                                                        <option value="<?= $value->id ?>"><?= $value->codigo.' - '.$value->nombre ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <label>&nbsp;</label><br>
                                                <button class="btn btn-default" type="submit" id="buscar"><i class="fa fa-search"></i> Buscar</button>
                                            </div>
                                        </div>
                                        <div class="form-group border-top border-bottom">
                                            <center>ó</center>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <label>Alumno</label>
                                                <input type="text" class="form-control" placeholder="Ingrese codigo, nombre o apellidos para la busqueda." id="alumno_">
                                                <input type="hidden" id="id_alumno_matricula" name="id_alumno_matricula" value="0">
                                                <input type="hidden" id="id_alumno" value="0">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label>Codigo</label>
                                                <input type="text" class="form-control" placeholder="18CI100001" name="codigo" id="autocompletecodigo_">
                                            </div>
                                        </div>
                                            <hr>
                                    </form>
                                </div>
                                <div class="ibox-content border-bottom" id="tablaalumnos">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="dataTableListaAlumnos" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Alumno</th>
                                                    <th>Especialidad</th>
                                                    <th>Modulo</th>
                                                    <th>Turno</th>
                                                    <th>Fecha</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('focus',".datepicker", function(){
                    $(this).datepicker({
            format: 'dd-mm-yyyy'
        });
            }); 
    $(function(){
        $(".chosen-select").chosen({width: "100%"});
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
        $(".dates").daterangepicker({
            opens: 'center',
            //drops: 'down',
            //timePicker: true,
            //linkedCalendars: false,
            //startDate: moment().startOf('hour'),
            //endDate: moment().startOf('hour').add(24, 'hour'),
            showDropdowns: true,
            showWeekNumbers: true,
            //timePicker: true,
            //timePickerIncrement: 1,
            //timePicker12Hour: true,
            opens: 'right',
            drops: 'down',
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-default',
            separator: ' to ',
            locale: {
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                daysOfWeek: ['Dm', 'Ln', 'Ma', 'Mr', 'Jv', 'Vr','Sb'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                firstDay: 1,
                format: 'DD/MM hh:mm A'
            }
        });
        var cargaInformacionAlumno = function(){

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
                            $('#resultado').find('div.informacion').html('')
                            $('#resultado').find('div.modulos').html('')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Tip. alumno</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.tipo_alumno+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Especialidad</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.especialidad+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Estado del alumno</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+getEstadoAlumnoEspecialidad(r.estado_especialidad)+'</label></div></div>')
                            //$('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Grupo</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.grupo+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Fecha Ingreso</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.fch_ingreso+'</label></div></div>')
                            //$('#resultado').find('label.nombreEspecialidad').html(r.especialidad)
                            var id_especialidad = r.id_especialidad
                            $('#id_especialidad').val(id_especialidad)
                            $.ajax({
                                url: '<?= base_url('especialidades/getModulesNotas') ?>',
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    id_especialidad: id_especialidad,
                                    cod_alumno: $('#autocompletecodigo').val()
                                },
                                success: function(response){
                                    console.log(response)
                                    $('#resultado').find('div.modulos').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h3 style="text-align: center;">Modulos - Practicas</h3></div>')
                                    if(response.status == 200){
                                        var cadena = '<div class="col-lg-12 col-md-12 col-sm-12"><table class="table table-striped table-bordered table-hover"><thead><tr><th>Número</th><th>Modulos</th><th>Practicas</th></thead>'
                                        var d = response.data
                                        for(var i in d){
                                            var cd = ''
                                            if(typeof d[i].nota == 'number')
                                                cd = '-'
                                            else
                                                cd = d[i].nota.eval
                                            cadena += '<tbody><tr><td>'+d[i].orden+'</td><td>'+d[i].modulo+'</td><td class="modulo-'+d[i].id_modulo+' text-center">'+cd+'</td></tr></tbody>'
                                        }
                                        cadena += '</table></div>'
                                        $('#resultado').find('div.modulos').append(cadena+'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><center><button type="button" class="btn btn-primary" id="agregar_nota" data-toggle="tooltip" data-placement="top" title="Ingresar Notas"><i class="fa fa-edit"></i> Notas</button><!--a class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Imprimir Acta" href="<?= base_url('practicas/imprimiracta/') ?>'+$('#autocompletecodigo').val()+'" target="_blank"><i class="fa fa-print"></i> Imprimir</a--></center></div>')
                                        cargar_funcionalidad()
                                        $('[data-toggle="tooltip"]').tooltip()
                                    }
                                }
                            })
                        }).fail(function(){
                            self.close()
                            toastr.error('Error','Consulte con su administrador')
                        })
                    }
                })
        }
        var table = $('#dataTableListaAlumnos').dataTable({
          "language": {
            "paginate": {
              "first": "Primera pagina",
              "last": "Ultima pagina",
              "next": "Siguiente",
              "previous": "Anterior"
            },
            "emptyTable": "Tabla vacia",
            "infoEmpty": "Observando 0 a 0 d 0 registros",
            //"info": "Observando pagina _PAGE_ de _PAGES_",
            "info": '<a href="<?= base_url('practicas/imprimiractas') ?>" target="_blank" class="btn btn-primary" id="imprimir" id="boton_imprimir"><i class="fa fa-print"></i> Imprimir</a>',
            "lengthMenu": "Desplegando _MENU_ Registros",
            "search": "Busqueda"
          },
          /*"columns": [
            { "width": "10%" },
            { "width": "60%" },
            { "width": "15%" },
            { "width": "15%" }
          ],*/
          bFilter: true, 
          bInfo: true,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
          buttons: [
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'}
            ]
        })
        $('.dates').on('apply.daterangepicker', function(ev, picker) {
            $(this).val('Desde ' + picker.startDate.format('DD-MM-YYYY') + ' hasta ' + picker.endDate.format('DD-MM-YYYY'));
            $('#fechaStart').val(picker.startDate.format('DD-MM-YYYY'))
            $('#fechaEnd').val(picker.endDate.format('DD-MM-YYYY'))
        })
        $('#form-consulta').on('submit',function(e){
            e.preventDefault()
            $.confirm({
                title: 'Consulta',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url('practicas/consulta') ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            fechaStart: $('#fechaStart').val(),
                            fechaEnd: $('#fechaEnd').val(),
                            turno: $('#turno').val() == 'Seleccione un turno' ? 0 : $('#turno').val(),
                            especialidad: $('#especialidades').val() == 'Seleccione Especialidad' ? 0 : $('#especialidades').val()
                        }
                    }).done(function(response){
                        self.close()
                        table.fnClearTable()
                        table.fnDraw()
                        if(response.status == 200){
                            var data = response.data
                            for(var i in data){
                                table.fnAddData([
                                    data[i].nombres,
                                    data[i].especialidad,
                                    data[i].modulo,
                                    data[i].turno,
                                    data[i].fecha_acta
                                    ])
                            }
                        }
                        else{
                            toastr.error('Error',response.message)
                        }
                        console.log(response)
                    }).fail(function(){
                        self.close()
                        toastr.error('Error','Ocurrió un error consulte con su administrador')
                    })
                }
            })
        })
        var cargar_funcionalidad = function(){
            $('#agregar_nota').unbind('click')
            $('#agregar_nota').on('click',function(){
                $.confirm({
                    title: 'Agregando notas de practicas',
                    theme: 'light',
                    columnClass: 'col-md-12',
                    content: function(){
                        var self = this
                        return $.ajax({
                            url: '<?= base_url('especialidades/getModules') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_especialidad: $('#id_especialidad').val(),
                                cod_alumno: $('#autocompletecodigo').val()
                            }
                        }).done(function(response){
                            var cadena = '<div class="content"><div class="row"><form method="post" id="form-notas-no-regulares"><div class="col-lg-12 col-md-12 col-sm-12"><div class="table-responsive"><table id="dataTableMod" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0"><thead><tr><th>Fch. Acta</th><th>Modulos</th><th>Profesor</th><th>Turno</th><th>Tip. Evaluación</th><th>Evaluación</th><th>&nbsp;</th><th>&nbsp;</th></tr></thead><tbody>'
                            var d = response.data
                            for(var i in d){ 
                                var cad = ''
                                console.log(d[i])
                                if(d[i].nota == 0){
                                    cad = 'data-exist="false" data-nota="0"'
                                    band = false
                                    input = '<input required name="eval_'+d[i].id_modulo+'" type="text" class="form-control eval_'+d[i].id_modulo+' " value="'+(band ? (tipoeval == 1 ? valor : '') : '')+'">'
                                    profe = ''
                                    id_prof = 0
                                }
                                else{
                                    cad = 'data-exist="true" data-nota="'+d[i].nota.eval+'" data-idnota="'+d[i].nota.id+'" data-tipoeval="'+d[i].nota.tipo_eval+'"'
                                    if(d[i].nota.tipo_eval == 1)
                                        input = '<input required name="eval_'+d[i].id_modulo+'" disabled type="text" class="form-control eval_'+d[i].id_modulo+' " value="'+(band ? (tipoeval == 1 ? valor : '') : '')+'">'
                                    else
                                        input = '<select disabled required class="form-control eval_'+d[i].id_modulo+'" name="eval_'+d[i].id_modulo+'"><option></option><option '+(d[i].nota.eval == 'A' ? 'selected' : '')+' value="A">A</option><option '+(d[i].nota.eval == 'B' ? 'selected' : '')+' value="B">B</option><option '+(d[i].nota.eval == 'C' ? 'selected' : '')+' value="C">C</option><option '+(d[i].nota.eval == 'D' ? 'selected' : '')+' value="D">D</option></select>'
                                    band = true
                                    tipoeval = d[i].nota.tipo_eval 
                                    fec = d[i].nota.fecha_acta.split('-')
                                    profe = d[i].nota.nombre_prof
                                    id_prof = d[i].nota.id_prof
                                }
                                //las filas ingresadas
                                cadena += '<tr data-in="'+d[i].id_modulo+'"><td><input type="text" class="form-control datepicker datepicker_'+d[i].id_modulo+'" '+(band ? 'disabled' : '')+' value="'+(band ? fec[2]+'-'+fec[1]+'-'+fec[0] : "")+'"></td><td>'+d[i].orden+'.- '+d[i].modulo+'</td><td><input type="text" class="form-control autocompleteprofesor_'+d[i].id_modulo+'" data-in="'+d[i].id_modulo+'" placeholder="Ingrese apellidos" '+(band ? 'disabled' : '')+' value="'+profe+'"><input type="hidden" value="'+id_prof+'" id="id_profesor_'+d[i].id_modulo+'"></td><td><div class="form-group"><select class="form-control turno_'+d[i].id_modulo+'" '+(band ? 'disabled' : '')+'><option></option><option '+(band ? (d[i].nota.id_turno == 1 ? 'selected' : '') : '')+' value="1">Vespertino</option></select></div></td>'+
                                '<td><div class="form-group"><select required class="form-control tipo_eval" data-change="false" name="tipo_eval_'+d[i].id_modulo+'" id="tipo_eval_'+d[i].id_modulo+'" '+(band ? 'disabled' : '')+' data-in="'+d[i].id_modulo+'" '+cad+'><option></option><option '+(band ? (tipoeval == 1 ? 'selected' : '') : '')+' value="1">Numerica</option><option '+(band ? (tipoeval == 2 ? 'selected' : '') : '')+' value="2">Letra</option></select></div></td>'+
                                '<td class="modulo-'+d[i].id_modulo+'"><div class="form-group">'+input+'</div></td><td>'+(band ? '<button type="button" data-toggle="tooltip" data-placement="top" title="Editar la nota" class="btn btn-default editar" data-in="'+d[i].id_modulo+'"><i class="fa fa-lock-open"></i></button></td><td><button class="btn btn-danger eliminar" type="button" data-toggle="tooltip" data-placement="top" data-id="'+d[i].nota.id+'" title="Eliminar"><i class="fa fa-trash"></i></button>' : '</td><td>' ) +'</td></tr>'
                            }
                            cadena += '</tbody></table></div></div></form></div></div>'
                            //console.log(cadena)
                            self.setContentAppend('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12"><h3 class="text-center">Ingreso de Notas</h3></div></div>'+cadena)
                        }).fail(function(){
                            self.setContentAppend('Error consulte con su administrador')
                        })
                    },
                    buttons: {
                        ok: function(){
                            //window.location.href = '<?php echo base_url('periodos/editar/'); ?>';
                            var notas = []
                            $('#dataTableMod tbody tr').each(function(index,element){
                                var obj = {
                                    id: $(this).attr('data-in'),
                                    tipo_eval: $(this).find('.tipo_eval').val(),
                                    eval: $(this).find('.eval_'+$(this).attr('data-in')).val(),
                                    id_eval: $(this).find('#tipo_eval_'+$(this).attr('data-in')).attr('data-exist') ? $(this).find('#tipo_eval_'+$(this).attr('data-in')).attr('data-idnota') : 0,
                                    fecha_acta: $(this).find('.datepicker_'+$(this).attr('data-in')).val(),
                                    editado: $(this).find('.eval_'+$(this).attr('data-in')).is(':disabled') ? 1 : 0,
                                    turno: $(this).find('.turno_'+$(this).attr('data-in')).val(),
                                    profe: $(this).find('#id_profesor_'+$(this).attr('data-in')).val()
                                }
                                notas.push(obj)
                            })
                            $.confirm({
                                title: 'Guardando',
                                content: function(){
                                    var self1 = this
                                    return $.ajax({
                                        url: '<?= base_url('practicas/registrarNota') ?>',
                                        method: 'POST',
                                        dataType: 'json',
                                        data: {
                                            notas: notas,
                                            cod_alumno: $('#autocompletecodigo').val(),
                                            id_especialidad: $('#id_especialidad').val()
                                        }
                                    }).done(function(response){
                                        self1.close()
                                        toastr.success(response.message)
                                        cargaInformacionAlumno()
                                        //$('#alumno').trigger('select')
                                        /*setTimeout(function(){
                                            window.location.reload()
                                        },2000)*/
                                        console.log(response)
                                    }).fail(function(){
                                        self1.close()
                                        toastr.error('Error','Error consulte con su administrador')
                                    })
                                }
                            })
                        },
                        cancelar: function(){}
                    },
                    contentLoaded: function(data, status, xhr){
                        //self.setContentAppend('<h2>Resultado:</h2>');
                        //this.setContentAppend('<div>Resultado:</div>');
                        var t = $('#dataTableMod').dataTable({
                                  "language": {
                                    "paginate": {
                                      "first": "Primera pagina",
                                      "last": "Ultima pagina",
                                      "next": "Siguiente",
                                      "previous": "Anterior"
                                    },
                                    "infoEmpty": "Observando 0 a 0 d 0 registros",
                                    "info": "Observando pagina _PAGE_ de _PAGES_",
                                    "lengthMenu": "Desplegando _MENU_ Registros"
                                  },
                                  scrollY: 400
                                })
                        var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                        $('.datepicker').datepicker({
                                format: 'dd-mm-yyyy',
                                container: container,
                                todayHighlight: true,
                                autoclose: true
                            }).val('')
                        /*$('#dataTableMod tbody tr').each(function(){
                            $(this).find('.datepicker_'+$(this).attr('data-in')).datepicker({
                                format: 'dd-mm-yyyy',
                                container: container,
                                todayHighlight: true,
                                autoclose: true
                            })
                        })*/
                    },
                    onContentReady: function(){
                        var self = this
                        $('#dataTableMod tbody tr button.editar').on('click',function(){
                            $('.datepicker_'+$(this).attr('data-in')).removeAttr('disabled')
                            $('.autocompleteprofesor_'+$(this).attr('data-in')).removeAttr('disabled')
                            $('#tipo_eval_'+$(this).attr('data-in')).removeAttr('disabled')
                            $('.eval_'+$(this).attr('data-in')).removeAttr('disabled')
                            $('.turno_'+$(this).attr('data-in')).removeAttr('disabled')
                        })
                        $('#dataTableMod tbody tr button.eliminar').on('click',function(){
                            var id = $(this).attr('data-id')
                            $.confirm({
                                title: 'Atención',
                                content: 'Esta seguro de eliminar esta práctica?',
                                buttons: {
                                    si: {
                                        text: 'Si',
                                        btnClass: 'btn-success',
                                        keys: ['enter'],
                                        action: function(){
                                            $.confirm({
                                                title: 'Eliminando',
                                                content: function(){
                                                    var selfEliminando = this
                                                    return $.ajax({
                                                        url: '<?= base_url('practicas/eliminarEvaluacionPractica') ?>',
                                                        method: 'POST',
                                                        dataType: 'json',
                                                        data: {
                                                            id: id
                                                        }
                                                    }).done(function(response){
                                                        selfEliminando.close()
                                                        self.close()
                                                        toastr.success(response.message)
                                                        cargaInformacionAlumno()
                                                        //$('#alumno').autocomplete('search')
                                                    }).fail(function(){
                                                        selfEliminando.close()
                                                        toastr.error('Ocurrió un error consulte con su administrador')
                                                    })
                                                }
                                            })
                                        }
                                    },
                                    no: {
                                        text: 'No',
                                        action: function(){}
                                    }
                                }
                            })
                        })
                        $('#dataTableMod tbody tr select.tipo_eval').on('change',function(){
                            if($(this).val() == 1){
                                //$(this).
                                $(this).parent().parent().parent().find('td.modulo-'+$(this).attr('data-in')).html('<div class="form-group"><input type="text" class="form-control eval_'+$(this).attr('data-in')+' "></div>')
                            }
                            else{
                                $(this).parent().parent().parent().find('td.modulo-'+$(this).attr('data-in')).html('<div class="form-group"><select required class="form-control eval_'+$(this).attr('data-in')+'" name="eval_'+$(this).attr('data-in')+'"><option></option><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option></select></div>')
                            }
                        })
                        $('#dataTableMod tbody tr').each(function(){
                            var id_modulo = $(this).attr('data-in')
                            $('.autocompleteprofesor_'+$(this).attr('data-in')).autocomplete({
                                serviceUrl: '<?= base_url('profesores/getProfesoresAutocomplete') ?>',
                                minChars: 3,
                                dataType: 'text',
                                type: 'POST',
                                paramName: 'data',
                                params: {
                                  'data': $(this).val()
                                },
                                onSelect: function(suggestion){
                                    var prof = JSON.parse(suggestion.data)
                                    console.log(prof)
                                    $('#id_profesor_'+$(this).attr('data-in')).val(prof.id)
                                },
                                focus: function(event, ui){
                                    console.log($(this).attr('data-in'))
                                },
                                onSearchStart: function(q){},
                                onSearchComplete: function(q,suggestions){}
                            })
                        })
                    }
                })
            })
        }
        $('[data-toggle="tooltip"]').tooltip()
        var getEstadoAlumnoEspecialidad = function(estado_especialidad){
            var estado = ''
            switch(parseInt(estado_especialidad)){
                case 1: 
                    estado = 'Activo, en curso'
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
                $('#resultado').html('<div class="content"><div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 informacion"></div>'+
                        '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 modulos"></div></div></div>')
                $('#resultado').find('div.informacion').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h3 style="text-align: center;">Información</h3></div>')
                $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Nombres</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+datos.apell_pat+' '+datos.apell_mat+', '+
                        datos.nombre+'</label></div></div>')
                $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>DNI</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+datos.dni+'</label></div></div>')
                cargaInformacionAlumno()
            },
            onSearchStart: function(q){},
            onSearchComplete: function(q,suggestions){}
        })
        $('#alumno_').autocomplete({
            serviceUrl: '<?= base_url('alumnos/getAlumnoAutocomplete') ?>',
            minChars: 3,
            dataType: 'text',
            type: 'POST',
            paramName: 'data',
            params: {
              'data': $('#alumno_').val()
            },
            onSelect: function(suggestion){
                var datos = JSON.parse(suggestion.data)
                console.log(datos)
                $('#autocompletecodigo_').val(datos.codigo)
                $('#autocompletecodigo_').prop('disabled',true)
                $.confirm({
                    title: 'Buscando',
                    content: function(){
                        var self = this
                        return $.ajax({
                        url: '<?= base_url('practicas/consulta') ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            cod_alumno: datos.codigo
                        }
                        }).done(function(response){
                            self.close()
                            table.fnClearTable()
                            table.fnDraw()
                            if(response.status == 200){
                                var data = response.data
                                for(var i in data){
                                    table.fnAddData([
                                        data[i].nombres,
                                        data[i].especialidad,
                                        data[i].modulo,
                                        data[i].turno,
                                        data[i].fecha_acta
                                        ])
                                }
                            }
                            else{
                                toastr.success('El alumno no cuenta con registros de prácticas')
                            }
                            console.log(response)
                        }).fail(function(){
                            self.close()
                            toastr.error('Error','Ocurrió un error consulte con su administrador')
                        })
                    }
                })
                //table.fnAddData([])
            },
            onSearchStart: function(q){},
            onSearchComplete: function(q,suggestions){}
        })
    })
</script>