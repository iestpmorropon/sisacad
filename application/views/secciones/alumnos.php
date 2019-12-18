<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Secciones</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Secciones</a></li>
            <li class="active"><strong>Alumnos</strong></li>
            <!--li>Internos</li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content listaItems">
                    <input type="hidden" id="id_periodo_curso" value="0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">  
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Apellidos y nombres</th>
                                    <th>Ciclo</th>
                                    <th>Periodo</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!is_numeric($alumnos)) foreach ($alumnos as $key => $value) { ?>
                                    <tr>
                                        <td><?= $value->codigo_alumno ?></td>
                                        <td><?= $value->apell_pat.' '.$value->apell_mat.' '.$value->nombre ?></td>
                                        <td><?= $malla_periodo->id_ciclo ?></td>
                                        <td><?= $especialidad_periodo->periodo ?></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <a href=javascript:history.back(1) class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                            <!--button class="btn btn-success" id="agregar" type="button" data-toggle="tooltip" data-placement="top" title="Agregar un nuevo alumno a la lista"><i class="fa fa-plus"></i> Alumno</button-->
                            <!--a href="#" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Imprimir Lista"><i class="fa fa-print"></i> Imprimir</a-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('[data-toggle="tooltip"]').tooltip()
        tabla = $('.table').dataTable({
                  "language": {
                    "paginate": {
                      "first": "Primera pagina",
                      "last": "Ultima pagina",
                      "next": "Siguiente",
                      "previous": "Anterior"
                    },
                    "infoEmpty": "Observando 0 a 0 d 0 registros",
                    "info": " ",
                    "lengthMenu": "Desplegando _MENU_ Registros",
                      bFilter: true, 
                      bInfo: false
                  },
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
                })
        $('#agregar').on('click',function(){
            $.confirm({
                title: 'Nuevo alumno',
                content: '<div class="content"><div class="row"><div class="col-lg-8 col-md-8 col-sm-8">'+
                        '<label>Nombres y apellidos</label><input type="text" class="form-control" name="nombre" id="autocompletealumno">'+
                        '</div><div class="col-lg-4 col-md-4 col-sm-4">'+
                        '<label>DNI</label><input type="number" step="1" class="form-control" name="dni" id="busquedadni"></div></div>'+
                        '<input type="hidden" id="id_alumno" name="cod_alumno"><input type="hidden" id="apto" name="apto">'+
                        '<div class="row"><div id="resultado_busqueda" class="col-lg-8 col-md-8 col-sm-8 col-lg-offset-2 col-md-offset-2 col-sm-offset-2"></div></div></div>',
                columnClass: 'col-lg-8 col-sm-8 col-md-8 col-lg-offset-2 col-md-offset-2 col-sm-offset-2',
                contentLoaded: function(data, status, xhr){},
                onContentReady: function(){
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
                            $('#id_alumno').val(datos.codigo)
                            $('#busquedadni').val(datos.dni)
                            $.ajax({
                                url: '<?= base_url('alumnos/cargaPagosForAlumno') ?>',
                                type: 'POST',
                                data: {
                                    id_periodo: $('#id_periodo_curso').val(),
                                    cod_alumno: $('#id_alumno').val()
                                },
                                success: function(response){
                                    console.log(response)
                                    if(JSON.parse(response).status == 200){}
                                    else{
                                        $('#resultado_busqueda').html('<div class="alert alert-danger">'+JSON.parse(response).message+'<br>No sera posible registrar al alumno en esta sección</div>')
                                    }
                                }
                            })
                        },
                        onSearchStart: function(q){},
                        onSearchComplete: function(q,suggestions){}
                    })
                },
                buttons: {
                    agregar: function(){},
                    cancelar: function(){}
                }
            })
        })
    })
</script>