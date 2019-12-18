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
                        <a class="nav-link" data-toggle="tab" href="#alumnos"><i class="fa fa-copy"></i> Filstro de alumnos</a>
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
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <label>Alumno</label>
                                                <input type="text" class="form-control" placeholder="Ingrese DNI, nombre o apellidos para la busqueda." id="alumno">
                                                <input type="hidden" id="id_alumno_matricula" name="id_alumno_matricula" value="0">
                                                <input type="hidden" id="id_alumno" value="0">
                                                <input type="hidden" id="autocompletecodigo" value="">
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
                                        <th>Ciclo</th>
                                        <th>Periodo</th>
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
        $('#import_form').on('submit',function(e){
            e.preventDefault()
        })
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
        $(".chosen-select").chosen()
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
        // función que genera un número aleatorio entre los límites superior e inferior pasados por parámetro
        function genera_aleatorio(i_numero_inferior, i_numero_superior) {
            var     i_aleatorio  =   Math.floor((Math.random() * (i_numero_superior - i_numero_inferior + 1)) + i_numero_inferior);
            return  i_aleatorio;
        }
        var generaPassword = function(){
            var lista_de_caracteres =   '123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
            var pass = ''
            for (var i = 0; i < 6; i++) 
                pass += lista_de_caracteres.charAt(genera_aleatorio(0,lista_de_caracteres.length))
            return pass
        }
        var cargaFuncionalidadHistorial = function(){
            $('#tablehistorial tbody tr td button.consulta').on('click',function(){
                var cod_alumno = $(this).attr('data-cod')
                var id_alumno_especialidad = $(this).attr('data-id-ea')
                $.confirm({
                    title: 'Opciones',
                    content: '<div class="btn-group"><div class="col-lg-12 col-md-12 col-sm-12"><a class="btn btn-warning" target="_blank" href="<?= base_url('alumnos/imprimecertificado/') ?>'+cod_alumno+'/'+id_alumno_especialidad+'"><i class="fa fa-print"><i> Imprimir Certificado</a></div></div><hr><div class="btn-group"></div>',
                    buttons: {
                        cancelar: function(){}
                    }
                })
            })
        }
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
            "info": '<div class="btn-group"><button class="btn btn-primary" id="imprimir" disabled><i class="fa fa-print"></i> Imprimir Actas</button><button class="btn btn-warning" id="imprimirboletas" disabled><i class="fa fa-print"></i> Imprimir Boletas</button></div>',
            "lengthMenu": "Desplegando _MENU_ Registros",
            "search": "Busqueda"
          },
          bFilter: true, 
          //bInfo: true
        })
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
                        '<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 historial"></div></div></div>')
                $('#resultado').find('div.informacion').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h3 style="text-align: center;">Información</h3></div>')
                $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Nombres</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+datos.apell_pat+' '+datos.apell_mat+', '+
                        datos.nombre+'</label></div></div>')
                $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>DNI</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+datos.dni+'</label></div></div>')
                $('#resultado').find('div.historial').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h3 style="text-align: center;">Historial</h3></div>')
                $('#resultado').find('div.historial').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+
                        '<div class="table-responsive"><table class="table table-striped table-bordered table-hover" id="tablehistorial" width="100%" cellspacing="0">'+
                        '<thead><tr><th>Cod. Alumno</th><th>Apell. Nombres</th><th>Especialidad</th><th>Plan de Estudios</th><th>Turno</th><th>Culminó cursos</th><th>Culminó prácticas</th><th>Egresó</th><th>&nbsp;</th></tr></thead><tbody></tbody></table></div></div>')
                $.confirm({
                    title: 'Información',
                    content: function(){
                        var self = this
                        return $.ajax({
                            url: '<?= base_url('alumnos/getCargaInformacionAlumnoHistorial') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                cod_alumno: $('#autocompletecodigo').val()
                            }
                        }).done(function(response){
                            self.close()
                            var r = response.data.infor
                            var h = response.data.historial
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Tip. alumno</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.tipo_alumno+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Especialidad</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.especialidad+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Estado del alumno</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+getEstadoAlumnoEspecialidad(r.estado_especialidad)+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Fecha Ingreso</span></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label>'+r.fch_ingreso+'</label></div></div>')
                            $('#resultado').find('div.informacion').append('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><center><button class="btn btn-warning" id="resetear" data-id="'+r.id_usuario+'" type="button"><i class="fa fa-undo"></i> Resetear contraseña</button></center></div></div>')
                            for(var i in h){
                                $('#resultado').find('tbody').append('<tr><td>'+h[i].cod_alumno+'</td><td>'+h[i].apell_pat+' '+h[i].apell_mat+' '+h[i].nombre+'</td><td>'+h[i].especialidad+'</td><td>'+h[i].periodo+'</td><td>'+h[i].turno+'</td>'+
                                        '<td>'+(parseInt(h[i].cursos) == 1 ? 'Si' : 'No')+'</td><td>'+(parseInt(h[i].practicas) == 1 ? 'Si' : 'No')+'</td><td>'+(parseInt(h[i].estado_egreso) == 1 ? 'Si' : 'No')+'</td><td><div class="btn-group"><button class="btn btn-default consulta" type="button" data-cod="'+h[i].cod_alumno+'" data-id-ea="'+h[i].id_alumno_especialidad+'" data-toggle="tooltip" data-placement="top" title="Consultar"><i class="fa fa-question-circle"></i></button></div></td><!--td><div class="btn-group"><a class="btn btn-warning" target="_blank" href="<?= base_url('alumnos/imprimecertificado/') ?>'+h[i].cod_alumno+'" data-toggle="tooltip" data-placement="top" title="Emitir Certificado"><i class="fa fa-print"></i></a></div></td--></tr>')
                            }
                            var table = $('#tablehistorial').dataTable({
                              "language": {
                                "paginate": {
                                  "first": "Primera pagina",
                                  "last": "Ultima pagina",
                                  "next": "Siguiente",
                                  "previous": "Anterior"
                                },
                                "emptyTable": "Tabla vacia",
                                "infoEmpty": 'Observando 0 a 0 d 0 registros',
                                "info": ' ',
                                "lengthMenu": "Desplegando _MENU_ Registros",
                                "search": "Busqueda"
                              },
                              "columns": [
                                { "width": "10%" },
                                { "width": "20%" },
                                { "width": "25%" },
                                { "width": "5%" },
                                { "width": "5%" },
                                { "width": "5%" },
                                { "width": "5%" },
                                { "width": "5%" },
                                { "width": "5%" }
                              ],
                              bFilter: true, 
                              //bInfo: true
                            })
                            $('#resetear').unbind('click')
                            $('#resetear').on('click',function(){
                                var id_usuario = $(this).attr('data-id')
                                $.confirm({
                                    title: 'Actualizar Contraseña',
                                    content: '<label>Ingrese contraseña</label><input type="text" disabled class="form-control password" name="contra" placeholder="***********" value="'+generaPassword()+'" required>',
                                    onContentReady: function(){},
                                    buttons: {
                                        generar: {
                                            text: 'generar',
                                            btnClass: 'btn-primary',
                                            action: function(){
                                                this.$content.find('.password').val(generaPassword())
                                                return false
                                            }
                                        },
                                        actualizar: {
                                            text: 'Actualizar',
                                            btnClass: 'btn-success',
                                            action: function(){
                                                var self = this
                                                var password = self.$content.find('.password').val()
                                                $.confirm({
                                                    title: 'Atención',
                                                    content: 'Esta seguro de los datos ingresados?',
                                                    buttons:{
                                                        si: function(){
                                                            $.confirm({
                                                                title: 'Actualizando',
                                                                content: function(){
                                                                    var self2 = this
                                                                    return $.ajax({
                                                                        url: '<?= base_url('home/actualizarPassword') ?>',
                                                                        method: 'POST',
                                                                        dataType: 'json',
                                                                        data: {
                                                                            password: password,
                                                                            type: 1,
                                                                            id_usuario: id_usuario
                                                                        }
                                                                    }).done(function(response){
                                                                        if(response.status == 200){
                                                                            self.close()
                                                                            self2.close()
                                                                            toastr.success(response.message)
                                                                            //window.location.reload()
                                                                        }
                                                                        else{
                                                                            self2.close()
                                                                            toastr.error(response.message)
                                                                        }
                                                                    }).fail(function(){
                                                                        self2.close()
                                                                        toastr.error('Error consulte con su administrador')
                                                                    })
                                                                }
                                                            })
                                                        },
                                                        no: function(){}
                                                    }
                                                })
                                                return false
                                            }
                                        },
                                        cancelar: function(){}
                                    }
                                })
                            })
                            cargaFuncionalidadHistorial()
                        })
                    }
                })
            }
        })
    })
</script>