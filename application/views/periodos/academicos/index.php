<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Periodos Academicos</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Periodos</a></li>
            <li class="active"><strong>Academicos</strong></li>
            <!--li>Internos</li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox border-bottom" id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5>Registro de periodos academicos</h5> 
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="ibox-content m-b-sm border-bottom" style="display: none;">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
            <form name="form-register-periodo" id="form-register-periodo" class="form-horizontal"  >
                <div class="form-group">
                    <div class="col-sm-2 col-lg-2 col-md-2 col-xs-12">
                    	<label>Año</label>
                    	<input type="number" class="form-control" required name="anio" placeholder="<?= date('Y') ?>" id="anio">
                    	<input type="hidden" id="id_periodo" name="id_periodo" value="0">
                    </div>
                    <div class="col-sm-2 col-lg-2 col-md-2 col-xs-12">
                    	<label>Semestre</label>
                        <select class="form-control" required name="ciclo" id="ciclo">
                            <option>Seleccione Semestre</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                        </select>
                    </div>
                    <div class="col-sm-2 col-lg-2 col-md-2 col-xs-12">
                        <label>Tipo Semestre</label><br>
                        <label><input type="radio" name="tipo" value="1" id="impar">&nbsp;Impar</label>
                        <label><input type="radio" name="tipo" value="0" id="par">&nbsp;Par</label>
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                    	<label>Fecha Inicio</label>
                    	<input type="text" class="form-control datepicker" required name="fch_inicio" value="<?= date('d-m-Y') ?>" id="fch_inicio">
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                    	<label>Fecha Fin</label>
                    	<input type="text" class="form-control datepicker" required name="fch_fin" value="<?= date('d-m-Y') ?>" id="fch_fin">
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                    	<label>Fecha Inicio de matrícula</label>
                    	<input type="text" class="form-control datepicker" required name="matricula_inicio" value="<?= date('d-m-Y') ?>" id="matricula_inicio">
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                    	<label>Fecha Fin de matrícula</label>
                    	<input type="text" class="form-control datepicker" required name="matricula_fin" value="<?= date('d-m-Y') ?>" id="matricula_fin">
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                    	<label>Fecha Inicio de Clases</label>
                    	<input type="text" class="form-control datepicker" required name="clases_inicio" value="<?= date('d-m-Y') ?>" id="clases_inicio">
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                    	<label>Fecha Fin de Clases</label>
                    	<input type="text" class="form-control datepicker" required name="clases_fin" value="<?= date('d-m-Y') ?>" id="clases_fin">
                        <input type="hidden" id="fecha_temporal" name="" value="<?= date('d-m-Y') ?>">
                    </div>
                </div>
               	<br>
                <div class="form-group">
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <button type="button" class="btn btn-primary" id="nuevo"><i class="fa fa-eraser"></i> Limpiar</button>
                    	<button type="submit" class="btn btn-primary" id="guardar"><i class="fa fa-save"></i> Guardar</button>
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
                                    <th>#</th>
                                	<th>Periodo</th>
                                	<th>Fecha Inicio</th>
                                	<th>Inicio de Matricula</th>
                                    <th>Inicio de Clases</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php if(!is_numeric($periodos)) foreach ($periodos as $key => $value) { ?>
                            		<tr>
                                        <td><?= ($key+1) ?></td>
                            			<td><?= $value->nombre ?></td>
                            			<td><?= $value->fch_inicio ?></td>
                            			<td><?= $value->matricula_inicio ?></td>
                            			<td><?= $value->clases_inicio ?></td>
                                        <td><?php switch ($value->estado) {
                                            case 0:
                                                echo 'Espera';
                                                break;
                                            case 1:
                                                echo 'En curso';
                                                break;
                                            case 2:
                                                echo 'Cerrado';
                                                break;
                                            
                                            default:
                                                # code...
                                                break;
                                        } ?></td>
                            			<td>
                            				<div class="btn-group">
                                                <?php if($value->estado == 1){ ?>
                                					<button type="button" class="btn btn-primary editar" data-in="<?= $value->id ?>" data-toggle="tooltip" data-placement="top" title="Editar Periodo"><i class="fa fa-edit"></i></button>
                                                <?php } ?>
                                                <?php if($value->estado != 2){ ?>
                                                <a href="<?= base_url('periodos/editar/'.$value->id) ?>" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Editar Secciones" ><i class="fas fa-arrow-alt-circle-right"></i></a>
                                                <?php } ?>
                                                <?php if($value->estado == 1){ ?>
                                                    <button type="button" class="btn btn-danger cerrar" data-in="<?= $value->id ?>" data-toggle="tooltip" data-placement="top" title="Cerrar el periodo"><i class="fas fa-window-close"></i></button>
                                                <?php } ?>
                            				</div>
                            			</td>
                            		</tr>
                            	<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
          
                <div class="ibox-title bg-primary">
                    <h5>Lista de periodos</h5> <br>
                    <h6>Solo debe de existir un periodo activo</h6>
                    <div class="ibox-tools">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
        $('[data-toggle="tooltip"]').tooltip()
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
        var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            container: container,
            todayHighlight: true,
            orientation: "bottom auto",
            autoclose: true
        });
        $('#ciclo').on('change',function(){
            if($('#ciclo option:selected').val() == '01'){
                $('#impar').attr('checked',true)
                $('#par').attr('checked',false)
            }
            if($('#ciclo option:selected').val() == '02'){
                $('#par').attr('checked',true)
                $('#impar').attr('checked',false)
            }
        })
        $('#form-register-periodo').on('submit',function(e){
        	e.preventDefault()
        	$.ajax({
        		url: '<?= base_url('periodos/nuevo') ?>',
        		type: 'POST',
        		data: $('#form-register-periodo').serialize(),
        		success: function(data){
        			console.log(data)
                    var d = JSON.parse(data)
                    if(d.status!= 200){
                        $.alert({
                            title: 'Atención',
                            content: d.message,
                            type: 'red',
                            typeAnimated: true
                        })
                        $('.message').show();
                        $('.message').append('<div class="alert alert-danger">' + d.mesagge + '</div>');
                    }else{
                        $.alert({
                            title: 'Atención',
                            content: d.message,
                            type: 'green',
                            typeAnimated: true
                        })
                        $('.message').show()
                        $('.message').append('<div class="alert alert-success">' + d.message + '</div>');
                    }
                    setTimeout(function() { 
                        $('.message').empty();
                        window.location.href = '<?php echo base_url('periodos/academicos'); ?>';
                    }, 2000);
        		}
        	}).fail(function(){
                    $.alert({
                        title: 'Atención',
                        content: 'Error en el registro, Consulte con su Administrador',
                        type: 'red',
                        typeAnimated: true
                    })
                })
        })
        $('#nuevo').on('click',function(){
            $('#id_periodo').val(0)
            $('#anio').val('')
            $('#ciclo').val('Seleccione Semestre')
            $('#fch_inicio').val($('#fecha_temporal').val())
            $('#fch_fin').val($('#fecha_temporal').val())
            $('#matricula_inicio').val($('#fecha_temporal').val())
            $('#matricula_fin').val($('#fecha_temporal').val())
            $('#clases_inicio').val($('#fecha_temporal').val())
            $('#clases_fin').val($('#fecha_temporal').val())
        })
        $('tbody tr td button.editar').on('click',function(){
	       	var id_periodo = $(this).attr('data-in')
        	$.ajax({
        		url: '<?= base_url('periodos/getPeriodo') ?>',
        		type: 'POST',
        		data: {
        			id_periodo: id_periodo
        		},
        		success: function(data){
        			var d = JSON.parse(data)
        			if(d.status!= 200){
        				$('.message').show();
        				$('.message').append('<div class="alert alert-danger">' + d.mesagge + '</div>');
        			}else{
        				var p = d.data
        				$('#id_periodo').val(p.id)
        				var anio = p.nombre.split('-')
        				$('#anio').val(anio[0])
        				$('#ciclo').val(anio[1])
        				$('#fch_inicio').val(p.fch_inicio)
        				$('#fch_fin').val(p.fch_fin)
        				$('#matricula_inicio').val(p.matricula_inicio)
        				$('#matricula_fin').val(p.matricula_fin)
        				$('#clases_inicio').val(p.clases_inicio)
        				$('#clases_fin').val(p.clases_fin)
        				$('.ibox').removeClass('border-bottom')
        				$('.ibox-content').show()
                        $('#box_import_invoices').scroll()
                        $('#ciclo').trigger('change')
        			}
        		}
        	})
        })
        $('tbody tr td button.cerrar').on('click',function(){
            var id_periodo = $(this).attr('data-in')
            var but = $(this)
            $.alert({
                title: 'Atención',
                content: '¿Esta seguro de cerrar el periodo academico?',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    si: function(){
                        $.confirm({
                            title: 'Respuesta',
                            theme: 'light',
                            columnClass: 'col-md-8 col-md-offset-2',
                            content: function(){
                                var self = this
                                return $.ajax({
                                    url: '<?= base_url("periodos/cerrarPeriodoAcademico") ?>',
                                    method: 'POST',
                                    dataType: 'json',
                                    data: {
                                        id_periodo: id_periodo
                                    }
                                }).done(function(response){
                                    if(response.status==200){
                                        var d = response.data
                                        self.setContentAppend('Se cerraron '+d.especialidades+' Especialidades en este periodo y '+d.secciones+' secciones.')
                                        $(but).remove()
                                    }
                                }).fail( function() {
                                    self.setContentAppend('Error consulte con su Administrador')
                                })
                            },
                            buttons: {
                                ok: function(){
                                    window.location.reload()
                                }
                            }
                        })
                    },
                    no: function(){}
                }
            })
        })
	})
</script>