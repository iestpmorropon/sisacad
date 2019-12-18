<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Especialidades</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("especialidades");?> ">Especialidad</a></li>
            <li class="active">Gestion</li>
            <!--li ><strong>Nuevo</strong></li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5>Filtro</h5> 
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
	            		<label class="control-label">Periodos</label>
	                    <select class="form-control " name="periodos" id="periodos">
	                    	<option>Seleccione periodo</option>
	                    	<?php foreach ($periodos as $key => $value) { ?>
	                    		<option value="<?= $value->id ?>"><?= $value->nombre ?></option>
	                    	<?php } ?>
	                    </select>
	                </div>
	            	<!--div class="col-sm-3">
	            		<label class="control-label">Busqueda por Nombre</label>
	            		<input type="text" class="form-control" name="Especialidad" required placeholder="Especialidad">
	            	</div>
	            	<div class="col-sm-1">
	            		<label class="control-label">&nbsp;</label><br>
	            		<button type="button" class="btn btn-primary" title="Busqueda" data-toggle="tooltip" data-placement="top" title="Busqueda"><i class="fa fa-search"></i></button>
	            	</div-->
	            	<div class="col-sm-3">
	            		<label class="control-label">&nbsp;</label><br>
	            		<button type="button" class="btn btn-primary" id="nueva-especialidad" data-toggle="tooltip" data-placement="top" title="Nueva Especialidad"><i class="fa fa-plus"></i> Nueva Especialidad</button>
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
                                    <th>Periodo</th>
                                    <th>Turno</th>
                                    <th>Vacantes</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php if($especialidades) foreach ($especialidades as $key => $value) { ?>
                            		<tr>
                            			<td><?= $value->codigo ?></td>
                            			<td><?= $value->nombre ?></td>
                                                <td><?= $value->periodo ?></td>
                                                <td><?= $value->turno ?></td>
                            			<td><?= $value->vacantes ?></td>
                                                <td><?php switch ((int)$value->estado) {
                                                     case 0:
                                                         echo 'En espera';
                                                         break;
                                                     case 1:
                                                         echo 'En curso';
                                                         break;
                                                     case 2:
                                                         echo 'Cerrado';
                                                         break;

                                                     default:
                                                         echo 'En espera';
                                                         break;
                                                 } ?></td>
                            			<td>
                            				<div class="btn-group">
                            					<a title="Malla" href="<?= base_url('especialidades/malla/'.$value->id_periodo.'/'.$value->id_especialidad) ?>" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Malla"><i class="fa fa-th"></i></a>
	                            				<!--button class="btn btn-danger eliminar" type="button" title="Eliminar" data-in="<?= $value->id_especialidad ?>" data-per="<?= $periodos[0]->id ?>" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-trash"></i></button-->
	                            			</div>
                            			</td>
                            		</tr>
                            	<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
		$('#nueva-especialidad').on('click',function(){
			if($('#periodos').val() == 'Seleccione periodo'){
				$.alert({
					title: 'Atención',
					content: 'Seleccione un periodo valido',
					type: 'red',
                                        typeAnimated: true
				})
				return false
			}
			$.confirm({
                title: 'Nuevo Especialidad en el periodo '+$('#periodos option:selected').text(),
                theme: 'light',
                columnClass: 'col-md-8 col-md-offset-2',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url("especialidades/forNewEspecialidadPeriodo") ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            id_periodo: $('#periodos').val()
                        }
                    }).done(function(response){
                        //console.log(response)
                        self.setContentAppend('<div class="content"><form id="form-register-especialidad"><div class="row"><div class="col-lg-6 col-md-6 col-sm-6"><label>Nombre</label><input type="text" class="form-control nombre" name="nombre" id="nombre_especialidad" placeholder="Escriba una especialidad"><input type="hidden" name="id_especialidad" id="id_especialidad_autocomplete" value="0"></div><div class="col-lg-3 col-md-3 col-sm-3"><label>Codigo</label><input type="text" class="form-control" name="codigo" id="codigo_especialidad"><input type="hidden" name="id_periodo" value="'+$('#periodos').val()+'"></div></div><div class="row"><div class="col-lg-3 col-md-3 col-sm-3"><label>Vacantes</label><input type="number" class="form-control" placeholder="400" name="vacantes"></div><div class="col-lg-3 col-md-3 col-sm-3"><label>Turno</label><select class="form-control" name="turno" id="turno"><option value="1">Vespertino</option></select></div><div class="col-lg-3 col-md-3 col-sm-3"><label><input type="radio" name="modular" value="1">&nbsp;Modular</label><br><label><input type="radio" name="modular" value="0">&nbsp;Por asignatura</label></div></div><div class="row"><div class="col-lg-3 col-md-3 col-sm-3"><label>Cantidad de cursos desaprobados para repetir</label><input type="number" class="form-control" name="cursos_minimos_repite" placeholder="2"></div><div class="col-lg-3 col-md-3 col-sm-3"></div></div></form></div>')
                    })
                },
                buttons: {
                    guardar: function(){
                    	$('#codigo_especialidad').removeAttr()
                        //window.location.href = '<?php echo base_url('periodos/editar/'); ?>';
                        $.ajax({
                        	url: '<?= base_url('especialidades/nueva') ?>',
                        	type: 'POST',
                        	data: $('#form-register-especialidad').serialize(),
                        	success: function(response){
                        		console.log(response)
                        		if(JSON.parse(response).status==200){
	                        		var d = JSON.parse(response).data
	                        		if(d.existe != 0){
	                        			$.alert({
	                        				title: 'Atención',
	                        				content: 'Ya existe registro.',
	                        				type: 'green',
	                        				typeAnimated: true
	                        			})
	                        			return false
	                        		}
	                        		window.location.reload()
	                        		$('.table tbody').append('<tr><td>'+d.codigo+'</td><td>'+d.nombre+'</td><td>'+d.vacantes+'</td><td><div class="btn-group"><a class="btn btn-primary title="Malla" href="<?= base_url('especialidades/malla/') ?>'+$('#periodos').val()+'/'+d.id+'" data-toggle="tooltip" data-placement="top"><i class="fa fa-th"></i></a><button type="button" class="btn btn-danger" data-in="'+d.id+'" data-per="'+$('#periodos').val()+'"><i class="fa fa-trash"></i></button></div></td></tr>')
	                        	}else{
	                        		$.alert({
										title: 'Atención',
										content: 'Error en el registro consulte con su administrador',
										type: 'red',
					                    typeAnimated: true
									})
	                        	}
                        	}
                        })
                    },
                    cancelar: function(){}
                },
                contentLoaded: function(data, status, xhr){
                    //self.setContentAppend('<h2>Resultado:</h2>');
                    //this.setContentAppend('<div>Resultado:</div>');
                },
                onContentReady: function(){
                	$('#nombre_especialidad').autocomplete({
			          serviceUrl: '<?= base_url('especialidades/autocompleteespecialidad') ?>',
			          minChars: 3,
			          dataType: 'text',
			          type: 'POST',
			          paramName: 'patron',
			          params: {
			            'patron': $('#nombre_curso').val()
			          },
			          onSelect: function(suggestion){
			            var datos = JSON.parse(suggestion.data)
			            $('#id_especialidad_autocomplete').val(datos.id)
			            $('#codigo_especialidad').val(datos.codigo)
			            console.log(datos)
			          },
			          onSearchStart: function(q){},
			          onSearchComplete: function(q,suggestions){}
			        })
                }
            })
		})

		//carga las especialidades de acuerdo al periodo correspondiente
		$('#periodos').on('change',function(){
			$.ajax({
				url: '<?= base_url("especialidades/getEsepecialidades") ?>',
				type: 'POST',
				data: {
					nombre: $('#periodos option:selected').text()
				},
				success: function(response){
					var r = JSON.parse(response)
						$('table tbody').html('')
					if(r.status != 200){
						/*$.alert({
							title: 'Atención',
							content: r.message,
							type: 'red',
                            typeAnimated: true
						})*/
						toastr.error('No se encuentran especialidades en este periodo')
					}else{
						var d = r.data
						for(var i in d){
                            var estado = ''
                            switch(parseInt(d[i].estado)){
                                case 0:
                                    estado = 'Deshabilitado'
                                    break;
                                case 1: 
                                    estado = 'Habilitado'
                                    break;
                                case 2:
                                    estado = 'Cerrado'
                                    break;
                            }
                            $('table tbody').append('<tr><td>'+d[i].codigo+'</td><td>'+d[i].nombre+'</td><td>'+d[i].periodo+'</td><td>'+d[i].turno+'</td><td>'+d[i].vacantes+'</td><td>'+estado+'</td><td><div class="btn-group"><a title="Malla" href="<?= base_url('especialidades/malla/') ?>'+$('#periodos').val()+'/'+d[i].id_especialidad+'/'+d[i].id_turno+'" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Malla"><i class="fa fa-th"></i></a><!--button class="btn btn-danger eliminar" type="button" data-id="'+d[i].id+'" data-in="'+d[i].id_especialidad+'" data-per="'+$('#periodos').val()+'"><i class="fa fa-trash"></i></button--></div></td></tr>')
						}
						$('table tbody td button.eliminar').unbind('click')
						$('table tbody td button.eliminar').on('click',function(){
							var periodo = $(this).attr('data-per')
							var espec = $(this).attr('data-in')
							var id = $(this).attr('data-id')
							$.confirm({
								title: 'Atención',
								content: 'Esta seguro de quitar la Especialidad del periodo?',
								buttons: {
									si: function(){
										$.confirm({
				                            title: 'Resultado',
				                            content: function(){
				                                var self = this;
				                                //self.setContent('Checking callback flow');
				                                return $.ajax({
				                                    url: '<?= base_url("especialidades/baja") ?>',
				                                    dataType: 'json',
				                                    data: {
				                                        id_especialidad: espec,
				                                        id_periodo: periodo,
				                                        id: id
				                                    },
				                                    method: 'POST'
				                                }).done(function (response) {
				                                	self.setContentAppend(response.message)
				                                	/*$.alert({
														title: 'Atención',
														content: response.message,
														type: 'green',
							                            typeAnimated: true
													})*/
													setTimeout(function() { 
							                            window.location.href = '<?php echo base_url('especialidades'); ?>';
							                        }, 2000); 
				                                }).fail(function(){
					                                  //self.setContentAppend('<div>Fail!</div>');
					                              }).always(function(){
					                                  //self.setContentAppend('<div>Always!</div>');
					                              });
				                            },
				                            contentLoaded: function(data, status, xhr){
				                                //self.setContentAppend('<h2>Resultado:</h2>');
				                            },
				                            onContentReady: function(){
				                                //this.setContentAppend('<div>Resultado:</div>');
				                            }
				                        })
									},
									no: function(){}
								}
							})
						})
					}
				}
			})
		})
	})
</script>