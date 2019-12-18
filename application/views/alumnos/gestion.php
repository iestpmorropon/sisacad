<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Alumnos</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Internos</a></li>
            <li class="active"><strong>Alumnos</strong></li>
            <!--li>Internos</li-->
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
                        <a class="nav-link" data-toggle="tab" href="#alumnos"><i class="fa fa-copy"></i> Actas regulares</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="alumnos">
                        <div class="panel-body">
                            <div class="ibox" id="box_import_invoices">
                                <div class="ibox-title bg-primary">
                                    <h5>Lista de alumnos</h5> 
                                    <div class="ibox-tools">
                                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </div>
                                </div>
                                <div class="ibox-content m-b-sm border-bottom">
                                    <div class="sk-spinner sk-spinner-double-bounce">
                                        <div class="sk-double-bounce1"></div>
                                        <div class="sk-double-bounce2"></div>
                                    </div>
                                    <form name="import_form" id="import_form" class="form-horizontal"  >
                                        <div class="form-group">
                                            <div class="col-sm-2">
                                                <label>Periodo </label>
                                                <select class="form-control" name="perido" id="periodo">
                                                    <?php foreach ($periodos as $key => $value) { ?>
                                                         <option value="<?= $value->id ?>"><?= $value->nombre ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="control-label">Turno</label>
                                                <select class="form-control " name="especialidad" id="turno">
                                                    <option>Seleccione turno</option>
                                                    <option value="1">Vespertino</option>
                                                    <!--<option value="2">Nocturno</option>-->
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="control-label">Especialidad</label>
                                                <select class="form-control chosen-select" name="especialidad" id="especialidad">
                                                    <option>Seleccione especialidad</option>
                                                </select>
                                                <span id="result" style="display: none; color: #F00;"></span>
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="control-label">Ciclo</label>
                                                <select class="form-control " name="ciclo" id="ciclo">
                                                    <option>Seleccione ciclo</option>
                                                    <option value="1">Ciclo 1</option>
                                                    <option value="2">Ciclo 2</option>
                                                    <option value="3">Ciclo 3</option>
                                                    <option value="4">Ciclo 4</option>
                                                    <option value="5">Ciclo 5</option>
                                                    <option value="6">Ciclo 6</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <label>&nbsp;</label><br>
                                                <button type="button" class="btn btn-primary" id="buscar" data-toggle="tooltip" data-placement="top" title="Buscar"><i class="fa fa-search"></i> Buscar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wrapper wrapper-content" id="tabla_alumnos" style="display: none;">
	<div class="row">
		<div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content listaItems">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">  
                        	<thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Apellidos y Nombres</th>
                                        <th>DNI</th>
                                        <th>Especialidad</th>
                                        <th>Ciclo</th>
                                        <th>Periodo</th>
                                        <th>Turno</th>
                                        <th></th>
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
<script>
    $(function(){
        $('ul.nav > li > a').on('click',function(){
            if($(this).attr('href') == '#alumnos'){
                $('#tabla_alumnos').show()
            }
            else{
                $('#tabla_alumnos').hide()
            }
        })
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
        $(".chosen-select").chosen()
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
        $('[data-toggle="tooltip"]').tooltip()
        var periodos = []
        var t = $('#dataTable').dataTable({
          "language": {
            "paginate": {
              "first": "Primera pagina",
              "last": "Ultima pagina",
              "next": "Siguiente",
              "previous": "Anterior"
            },
            "emptyTable": "Tabla vacia",
            "infoEmpty": 'Observando 0 a 0 d 0 registros',
            "info": '<div class="btn-group"><button class="btn btn-primary" id="imprimir" disabled><i class="fa fa-print"></i> Imprimir Acta</button><button class="btn btn-warning" id="imprimirboletas" disabled><i class="fa fa-print"></i> Imprimir Boletas</button><button class="btn btn-primary" type="button" id="imprimir-registro" disabled><i class="fa fa-print"></i> Imprimir Registro de Matricula</button></div>',
            "lengthMenu": "Desplegando _MENU_ Registros",
            "search": "Busqueda"
          },
          /*"columns": [
            { "width": "10%" },
            { "width": "40%" },
            { "width": "15%" },
            { "width": "15%" },
            { "width": "15%" },
            { "width": "5%" }
          ],*/
          bFilter: true, 
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},

                {extend: 'print',
                    customize: function (win){
                           $(win.document.body).addClass('white-bg');
                           $(win.document.body).css('font-size', '10px');

                           $(win.document.body).find('table')
                                   .addClass('compact')
                                   .css('font-size', 'inherit');
                   }
                }
            ]
          //bInfo: true
        })
        $('#periodo').on('change',function(){
            $('#especialidad').html('<option>Seleccione especialidad</option>')
            $('#turno').val('Seleccione turno')
            $('#ciclo').val('Seleccione ciclo')
            $(".chosen-select").trigger('chosen:updated')
        })
        $('#turno').on('change',function(){
            $('#especialidad').html('<option>Seleccione especialidad</option>')
            //$('#turno').html('<option>Seleccione turno</option>')
            $('#ciclo').html('<option>Seleccione ciclo</option>')
            $.ajax({
                url: '<?= base_url('alumnos/getEspecialidades') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id_periodo: $('#periodo').val(),
                    id_turno: $('#turno').val()
                }
            }).done(function(response){
                //console.log(response)
                if(response.status == 200){
                    var d = response.data
                    for(var i in d){
                        $('#especialidad').append('<option value="'+d[i].id_especialidad+'">'+d[i].especialidad+'</option>')
                        //console.log(d[i])
                    }
                    $(".chosen-select").trigger('chosen:updated')
                }else{
                    toastr.error('Consulte con su administrador')
                }
            }).fail(function(){
                toastr.error('Existe un error al consultar especialidades')
            })
        })
        $('#especialidad').on('change',function(){
            $('#ciclo').html('<option>Seleccione ciclo</option>')
            $.ajax({
                url: '<?= base_url('alumnos/getCiclosEspecialidadPeriodo') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id_periodo: $('#periodo').val(),
                    id_turno: $('#turno').val(),
                    id_especialidad: $('#especialidad').val()
                }
            }).done(function(response){
                if(response.status == 200){
                    var d = response.data
                    for(var i in d){
                        $('#ciclo').append('<option value="'+d[i].id_ciclo+'">Ciclo '+d[i].id_ciclo+'</option>')
                    }
                }
                else{
                    toastr.error('Consulte con su administrador')
                }
            }).fail(function(){
                toastr.error('Existe un error consulte con su administrador')
            })
        })
        $('#autocompletealumno').autocomplete({
            serviceUrl: '<?= base_url('alumnos/getAlumnoAutocomplete') ?>',
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
                        '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 solicitudes"></div></div></div>')
                $('#resultado').find('div.informacion').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h3 style="text-align: center;">Información</h3></div>')
                $('#resultado').find('div.informacion').append('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Nombres</span></div>')
                $('#resultado').find('div.informacion').append('<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+datos.apell_pat+' '+datos.apell_mat+', '+
                        datos.nombre+'</label></div>')
                $('#resultado').find('div.informacion').append('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>DNI</label></span>')
                $('#resultado').find('div.informacion').append('<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+datos.dni+'</label></div>')
                $('#resultado').find('div.solicitudes').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h3 style="text-align: center;">Solicitudes</h3></div>')
                $('#resultado').find('div.solicitudes').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+
                        '<table class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">'+
                        '<thead><tr><th>Concepto</th><th>Fecha</th><th>Estado</th><th>&nbsp;</th></tr></thead><tbody></tbody></table></div>')
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
                            var r = response.data.infor
                            var t = response.data.solicitudes
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Tipo de alumno</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.tipo_alumno+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Especialidad</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.especialidad+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Estado del alumno</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+getEstadoAlumnoEspecialidad(r.estado_especialidad)+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Grupo</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.grupo+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Fecha Ingreso</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.fch_ingreso+'</label></div></div>')
                            for(var i in t){
                                $('#resultado').find('tbody').append('<tr><td>'+t[i].concepto+'</td><td>'+t[i].fecha+'</td>'+
                                        '<td>'+(parseInt(t[i].estado) == 1 ? 'Pendiente' : 'Atendido')+'</td><td><div class="btn-group">'+
                                        '<a class="btn btn-warning" href="<?= base_url('alumnos/imprimirsolicitud/') ?>'+t[i].id+'" target="_blank"><i class="fa fa-print"></i></a>'+
                                        '</div></td></tr>')
                            }
                            self.close()
                        }).fail(function(){
                            self.setContentAppend('Error en la consulta')
                        })
                    }
                })
//                $.ajax({
//                    url: '<?= base_url('alumnos/getCargaInformacionAlumno') ?>',
//                    type: 'POST',
//                    data: {
//                        cod_alumno: $('#id_alumno').val()
//                    },
//                    success: function(response){
//                        if(JSON.parse(response).status == 200){
//                            var d = JSON.parse(response).data
//                            $('#id_alumno_matricula').val(d.ultima_matricula.id)
//                        }
//                    }
//                })
//                carga_historial()
            },
            onSearchStart: function(q){},
            onSearchComplete: function(q,suggestions){}
        })
        $('#buscar').on('click',function(){
            if($('#especialidad').val() == 'Seleccione especialidad' || $('#turno').val() == 'Seleccione turno' || $('#ciclo').val() == 'Seleccione ciclo' || $('#periodo').val() == 'Seleccione periodo'){
                //$('#result').html('Seleccione los valores a consultar')
                ///setTimeout(function(){ $('#result').html('') },3000)
                toastr.error('Seleccione todos los campos validos para realizar la consulta')
                return false
            }
            $.confirm({
                title: 'Buscando',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?=  base_url('alumnos/traeGrupoAlumnos')  ?>',
                        dataType: 'json',
                        method: 'POST',
                        data: {
                            id_turno: $('#turno').val() == 'Seleccione turno' ? 0 : $('#turno').val(),
                            id_especialidad: $('#especialidad').val() == 'Seleccione especialidad' ? 0 : $('#especialidad').val(),
                            id_periodo: $('#periodo').val() == 'Seleccione periodo' ? 0 : $('#periodo').val(),
                            id_ciclo: $('#ciclo').val() == 'Seleccione ciclo' ? 0 : $('#ciclo').val()
                        }
                    }).done(function(response){
                        self.close()
                        if(response.status == 200){
                            var d = response.data.alumnos
                            var pr = response.data.periodo
                            $('#tabla_alumnos').show()
                            t.fnClearTable()
                            t.fnDraw()
                            for(var i in d){
                                var btn = ''
                                if(pr.estado == 1)
                                    btn = '&nbsp;'
                                else
                                    btn = '<a class="btn btn-warning" href="<?= base_url('alumnos/boletas/') ?>'+d[i].codigo+'/'+$('#especialidad').val()+'/'+$('#ciclo').val()+'/'+$('#periodo').val()+'/'+$('#turno').val()+'" target="_blank" data-toggle="tooltip" data-placement="top" title="Imprimir boleta de notas"><i class="fa fa-print"></i></a>'
                                var bt = '<div class="btn-group"><!---a class="btn btn-success" href="<?= base_url('alumnos/alumno/') ?>'+d[i].codigo+'" target="_blank"><i class="fa fa-eye"></i></a-->'+btn+'</div>'
                                t.fnAddData([
                                    d[i].codigo,
                                    d[i].apell_pat+' '+d[i].apell_mat+' '+d[i].nombre,
                                    d[i].dni,
                                    d[i].especialidad,
                                    d[i].ciclo,
                                    d[i].periodo,
                                    d[i].turno,
                                    bt
                                ])
                                //periodos[d[i].id_periodo] = d[i].periodo
                            }
                            var p = response.data.periodo
                            console.log(p.estado)
                            $('[data-toggle="tooltip"]').tooltip()
                            if(p.estado != 1){
                                $('#imprimir').show()
                                $('#imprimirboletas').show()
                                $('#imprimir').removeAttr('disabled')
                                $('#imprimir').unbind('click')
                                $('#imprimir').on('click',function(){
                                    window.open('<?php echo base_url("alumnos/imprimiractagrupal"); ?>','_blank' )
                                })
                                $('#imprimirboletas').removeAttr('disabled')
                                $('#imprimirboletas').on('click',function(){
                                    window.open('<?php echo base_url("alumnos/imprimirboletasgrupal"); ?>','_blank' )
                                })
                            }else{
                                $('#imprimir').hide()
                                $('#imprimirboletas').hide()
                            }
                            $('#imprimir-registro').removeAttr('disabled')
                            $('#imprimir-registro').on('click',function(){
                                window.open('<?php echo base_url("alumnos/imprimirregistromatriculas"); ?>','_blank' )
                            })
                            t.on('draw.dt',function(){
                                if(p.estado != 1){
                                    $('#imprimir').show()
                                    $('#imprimirboletas').show()
                                    $('#imprimir').removeAttr('disabled')
                                    $('#imprimir').unbind('click')
                                    $('#imprimir').on('click',function(){
                                        window.open('<?php echo base_url("alumnos/imprimiractagrupal"); ?>','_blank' )
                                    })
                                    $('#imprimirboletas').removeAttr('disabled')
                                    $('#imprimirboletas').on('click',function(){
                                        window.open('<?php echo base_url("alumnos/imprimirboletasgrupal"); ?>','_blank' )
                                    })
                                }else{
                                    $('#imprimir').hide()
                                    $('#imprimirboletas').hide()
                                }
                                $('#imprimir-registro').removeAttr('disabled')
                                $('#imprimir-registro').on('click',function(){
                                    window.open('<?php echo base_url("alumnos/imprimirregistromatriculas"); ?>','_blank' )
                                })
                            })
                        }
                        else{
                            toastr.error('Error',response.message)
                        }
                    }).fail(function(){
                        self.close()
                        toastr.error('Error, consulte con su administrador')
                    })
                }
            })
            /*$.ajax({
                url: '<?=  base_url('alumnos/traeGrupoAlumnos')  ?>',
                type: 'POST',
                success: function(response){
                    if(JSON.parse(response).status == 200){
                        var d = JSON.parse(response).data
                        t.fnClearTable()
                        t.fnDraw()
                        for(var i in d){
                            t.fnAddData([
                                d[i].codigo,
                                d[i].nombre+' '+d[i].apell_pat+' '+d[i].apell_mat,
                                d[i].dni,
                                d[i].ciclo,
                                d[i].periodo,
                                '<div class="btn-group"><a class="btn btn-success" href="<?= base_url('alumnos/alumno/') ?>'+d[i].codigo+'" target="_blank"><i class="fa fa-eye"></i></a></div>'
                            ])
                            periodos[d[i].id_periodo] = d[i].periodo
                        }
                        $('#imprimir').removeAttr('disabled')
                        $('#imprimir').on('click',function(){
                            var ops = ''
                            for(var i in periodos)
                                ops += '<option value="'+i+'">'+periodos[i]+'</option>'
                            $.confirm({
                                title: 'Seleccione el periodo que desea imprimir',
                                content: '<label>Periodos disponibles</label><select class="form-control" id="periodo">'+ops+'</select>',
                                buttons: {
                                    imprimir: function(){
                                        window.open('<?php echo base_url("alumnos/imprimiractagrupal/"); ?>'+$('#grupo').val()+'/'+$('#periodo').val(),'_blank' )
                                    },
                                    cancelar: function (){}
                                }
                            })
                        })
                        //$('#imprimir').attr('href','<?= base_url('alumnos/imprimiractagrupal') ?>/'+$('#grupo').val())
                    }
                },
                error: function(){
                    $('#result').html('Error consulte con su administrador')
                    setTimeout(function(){ $('#result').html('') },3000)
                }
            })*/
        })
        /*$('#especialidad').on('change',function(){
            $('#result').html('')
            if($(this).val() == 'Seleccione especialidad'){
                $('#result').html('Seleccione una especialidad valida').show()
                setTimeout(function(){ $('#result').html('') }, 3000);
                return false
            }
            $('#grupo').html('<option>Seleccione grupo</option>')
            $.ajax({
                url: '<?= base_url('alumnos/getGruposEspecialidad') ?>',
                type: 'POST',
                data: {
                    id_especialidad: $('#especialidad').val()
                },
                success: function(response){
                    if(JSON.parse(response).status == 200){
                        var d = JSON.parse(response).data
                        for(var i in d){
                            $('#grupo').append('<option value="'+d[i].id+'">'+d[i].grupo+'</option>')
                        }
                    }
                },
                error: function(){
                    $('#result').html('Error consulte con su administrador')
                }
            })
        })*/
    })
</script>