<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Alumnos</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Internos</a></li>
            <li>Alumnos</li>
            <li class="active"><strong>Gestion</strong></li>
            <!--li>Internos</li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5>Busqueda de alumno</h5> 
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
                    <div class="col-sm-6 col-lg 6 col-md-6">
                        <label>Alumno</label>
                        <input type="text" class="form-control" placeholder="Ingrese DNI, nombre o apellidos para la busqueda." id="alumno">
                        <input type="hidden" id="id_alumno_matricula" name="id_alumno_matricula" value="0">
                        <input type="hidden" id="id_alumno" value="0">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content listaItems">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">  
                        	<thead>
                                <tr>
                                	<th>Codigo</th>
                                    <th>Especialidad</th>
                                    <th>Ciclo</th>
                                    <th>Periodo</th>
                                    <th>Turno</th>
                                    <th>Opciones</th>
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
<script type="text/javascript">
	$(function(){
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
        var direccion = '<?= base_url('alumnos/').$tipoalumno ?>'
		var config = {
                '.chosen-select'           : {},
                '.chosen-select-deselect'  : {allow_single_deselect:true},
                '.chosen-select-no-single' : {disable_search_threshold:10},
                '.chosen-select-no-results': {no_results_text:'Oops, no se encontró!'},
                '.chosen-select-width'     : {width:"95%"}
        }

        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }
        var t = $('.table').dataTable({
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
          /*"aoColumns" : [
            {sWidth: "145px"},
            {sWidth: "390px"},
            {sWidth: "90px"},
            {sWidth: "180px"},
            {sWidth: "180px"},
            ]*/
        })
        $('#alumno').on('change',function(){
            if($(this).val() == '')
                $('#siguiente').attr('href',direccion)
        })
        var cargaFuncionalidades = function(){
            $('#dataTable tbody tr td button.eliminar').on('click',function(){
                var br = $(this)
                var id = $(this).attr('data-id')
                var id_especialidad = $(this).attr('data-id-especialidad')
                var id_periodo = $(this).attr('data-id-periodo')
                var id_ciclo = $(this).attr('data-id-ciclo')
                var id_turno = $(this).attr('data-id-turno')
                $.confirm({
                    title: 'Atención',
                    content: 'Es seguro de eliminar este registro',
                    buttons: {
                        si: function(){
                            $.confirm({
                                title: 'Eliminando',
                                content: function(){
                                    var self = this
                                    return $.ajax({
                                        url: '<?= base_url('alumnos/eliminarMatricula') ?>',
                                        method: 'POST',
                                        dataType: 'json',
                                        data: {
                                            id_alumno_matricula: id,
                                            id_especialidad: id_especialidad,
                                            id_periodo: id_periodo,
                                            id_ciclo: id_ciclo,
                                            id_turno: id_turno
                                        }
                                    }).done(function(response){
                                        if(response.status == 200){
                                            self.close()
                                            toastr.success(response.message)
                                            $(br).parent().parent().parent().remove()
                                        }
                                        else{
                                            self.close()
                                            toastr.error(response.message)
                                        }
                                    }).fail(function(){
                                        self.close()
                                        toastr.error('Error consulte con su Administrador')
                                    })
                                }
                            })
                        },
                        no: function(){}
                    }
                })
            })
        }
        var cargaMatriculas = function(){
            $('#dataTable tbody').html('')
            $.confirm({
                    title: 'Consultando',
                    content: function(){
                        var self = this
                        $.ajax({
                            url: '<?= base_url('alumnos/cargaMatriculas_') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                cod_alumno: $('#id_alumno').val()
                            }
                        }).done(function(response){
                            console.log(response)
                            self.close()
                            if(response.status == 200){
                                var d = response.data.matriculas
                                var pr = response.data.periodo_activo
                                for(var i in d){
                                    var estado = ''
                                    $('#dataTable tbody').append('<tr><td>'+d[i].cod_alumno+'</td><td>'+d[i].especialidad+'</td><td>Ciclo '+d[i].id_ciclo+'</td><td>'+d[i].periodo+'</td><td>'+d[i].turno+'</td><td>'+(d[i].periodo == pr.nombre ? '<div class="btn-group"><button class="btn btn-danger eliminar" data-id="'+d[i].id+'" data-id-especialidad="'+d[i].id_especialidad+'" data-id-ciclo="'+d[i].id_ciclo+'" data-id-periodo="'+d[i].id_periodo+'" data-id-turno="'+d[i].id_turno+'" type="button" data-targget="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-trash"></i></button></div>' : '')+'</td></tr>')
                                }
                                cargaFuncionalidades()
                            }
                            else{
                                toastr.error(response.message)
                            }
                        }).fail(function(){
                            self.close()
                            toastr.error('Error consulte con su Administrador')
                        })
                    }
                })
        }
        $('#alumno').autocomplete({
            serviceUrl: '<?= base_url('alumnos/getAlumnoAutocomplete_') ?>',
            minChars: 3,
            dataType: 'json',
            type: 'POST',
            paramName: 'data',
            params: {
              'data': $('#alumno').val()
            },
            onSelect: function(suggestion){
                console.log(suggestion)
                var d = JSON.parse(suggestion.data)
                $('#id_alumno').val(d.codigo)
                $('#siguiente').attr('href',$('#siguiente').attr('href')+'/'+d.codigo)
                cargaMatriculas()
            },
            onSearchStart: function(q){},
            onSearchComplete: function(q,suggestions){}
        })
	})
</script>