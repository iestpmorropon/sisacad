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
                    <div class="col-sm-6 col-lg 6 col-md-6">
                        <label>Alumno</label>
                        <input type="text" class="form-control" placeholder="Ingrese DNI, nombre o apellidos para la busqueda." id="alumno">
                        <input type="hidden" id="id_alumno_matricula" name="id_alumno_matricula" value="0">
                        <input type="hidden" id="id_alumno" value="0">
                    </div>
                    <?php if($tipoalumno == 'ingresante' || $tipoalumno == 'traslado' || $tipoalumno == 'regular' || $tipoalumno = 'registroIngresante'){ ?>
                	<div class="col-sm-1" id="nuevo">
                        <label>&nbsp;</label><br>
                		<a href="<?= base_url('alumnos/'.$tipoalumno) ?>" class="btn btn-primary" id="siguiente">Siguiente <i class="fa fa-arrow-right"></i></a>
                	</div>
                    <?php } ?>
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
                                    <th>Nombres</th>
                                    <th>Especialidad</th>
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
            },
            onSearchStart: function(q){},
            onSearchComplete: function(q,suggestions){}
        })
	})
</script>