<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Especialidades</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("especialidades");?> ">Especialidad</a></li>
            <li class="active">Cursos</li>
            <li ><strong>Nuevo</strong></li>
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
	        <form name="import_form" id="import_form" class="form-horizontal">
	            <div class="form-group">
	                <div class="col-sm-4">
	                	<label>Especialidades</label>
	                	<select class="form-control" name="especialidades" id="especialidades">
	                		<option>Seleccione Especialidad</option>
	                		<?php if($especialidades) foreach ($especialidades as $key => $value) { ?>
	                			<option value="<?= $value->id ?>"><?= $value->codigo.' - '.$value->nombre ?></option>
	                		<?php } ?>
	                	</select>
	                	<input type="hidden" name="id_especialidad_periodo" id="id_especialidad_periodo">
	                	<input type="hidden" name="cant_cursos" id="cant_cursos" value="0001">
	                </div>
	                <div class="col-sm-2">
	                	<label>&nbsp;</label><br>
	                	<button class="btn btn-primary" type="button" id="nuevocurso"><i class="fa fa-plus"></i> Nuevo Curso</button>
	                </div>
	                <div class="col-sm-2">
	                	<label id="message"></label>
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
                        <table class="table table-striped table-bordered table-hover diplay" id="dataTable" width="100%" cellspacing="0">  
                        	<thead>
                                <tr>
                                	<th>Codigo</th>
                                	<th>Curso</th>
                                	<th>Modulo</th>
                                	<th>Modulo-Padre</th>
                                	<th>Creditos</th>
                                	<th>Horas</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
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
		//var tab = $('#dataTable').DataTable()
		$('#nuevocurso').on('click',function(){
			var espec = $('#especialidades').val()
			if(espec == 'Seleccione Especialidad'){
				$.alert('Especialidad correcta')
				return false
			}
			$.confirm({
				title: 'Nuevo Curso',
				theme: 'light',
				columnClass: 'col-md-8 col-md-offset-2',
				content: function(){
					var self = this
					return $.ajax({
						url: '<?= base_url("especialidades/forNuewCurso") ?>',
						method: 'POST',
						dataType: 'json',
						data: {
                                                    id_especialidad: espec
                                                }
					}).done(function(response){
						if(response.status == 200){
							var cils = ''
							var mods = '<option value="0">Seleccione</option>'
							for(var i in response.data.ciclos)
								cils += '<option value="'+response.data.ciclos[i].id+'">'+response.data.ciclos[i].nombre+'</option>'
							for(var i in response.data.modulos)
								mods += '<option value="'+response.data.modulos[i].id+'">'+response.data.modulos[i].nombre+'</option>'
							$('#id_especialidad_periodo').val(response.data.espe_periodo.id)
							var cod_espe = $('#especialidades option:selected').text().toUpperCase().split(' - ')
							self.setContentAppend('<div class="content">'+
                                                                '<form id="form-register-curso">'+
                                                                '<div class="row">'+
                                                                '<div class="col-lg-6 col-md-6 col-sm-6">'+
                                                                '<label>Nombre</label><input type="text" class="form-control nombre" name="nombre" id="nombre_curso" required placeholder="NUEVO CURSO">'+
                                                                '<input type="hidden" id="id_curso" name="id_curso" value="0"></div>'+
                                                                '<div class="col-lg-4 col-md-4 col-sm-4">'+
                                                                '<label>Codigo</label><input type="hidden" id="cod_espe" value="'+cod_espe[0]+'">'+
                                                                '<input type="text" class="form-control codigo" name="codigo" readonly id="ini_codigo" placeholder="MOMP0001"></div></div>'+
                                                                '<div class="row">'+
                                                                '<div class="col-lg-3 col-md-3 col-sm-3">'+
                                                                '<label>Tipo de Curso</label><select class="form-control" id="tipo_curso" name="tipo_curso">'+
                                                                '<option>Seleccione</option><option value="1">TRANSVERSAL</option><option value="2">ASIGNATURA</option><option value="3">PROFESIONAL</option><option value="4">ACTIVIDADES - ASIGNATURAS</option></select></div>'+
                                                                '<div class="col-lg-3 col-md-3 col-sm-3"><label>Modulos</label>'+
                                                                '<select class="form-control" id="modulo" disabled="true" name="modulo">'+mods+'</select></div>'+
                                                                '<div class="col-lg-6 col-md-6 col-sm-6">'+
                                                                '<label>Especialidad: '+$('#especialidades option:selected').text()+'</label><input type="hidden" name="especialidad" value="'+$('#especialidades').val()+'"></div></div>'+
                                                                '<div class="row">'+
                                                                '<div class="col-lg-3 col-md-3 col-sm-3">'+
                                                                '<label>Num. Creditos</label><input type="number" required id="creditos" step="0.01" class="form-control creditos" name="creditos" placeholder="1.50"></div>'+
                                                                '<div class="col-lg-2 col-md-2 col-sm-2">'+
                                                                '<label>Horas</label><input type="number" required class="form-control horas" id="horas" name="horas" placeholder="2"></div>'+
                                                                '<div class="col-lg-3 col-md-3 col-sm-3"><label>Tipo Evaluación</label><br>'+
                                                                '<label><input type="radio" value="1" id="numerico" name="tipo_eval"> Numerico</label><br>'+
                                                                '<label><input type="radio" value="2" id="alfanumerico" name="tipo_eval"> Alfanumerico</label></div>'+
                                                                '<div class="col-lg-3 col-md-3 col-sm-3"><label>Nota Minima para aprobar</label><input type="text" class="form-control" name="notaminimavalor" required id="notaminimavalor" placeholder="10">'+
                                                                '<select style="display: none;" class="form-control" name="notaminimaletra" id="notaminimaletra"><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option></select></div></div>'+
                                                                '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12"><label>Descripción</label><textarea class="form-control" row="10" name="descripcion" id="descripcion"></textarea></div></div>'+
                                                                '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12"><label>Temas</label><textarea class="form-control" row="10" name="temas" id="temas"></textarea></div></div></form></div>')
						}else{
							self.setContentAppend('<label class="danger">'+response.message+'</label>')
						}
					})
				},
				buttons: {
					formSubmit: {
						text: 'registrar',
						action: function(){
							var form = $('#form-register-curso')
							if(!form.valid()){
								return false
							}
							if($('#form-register-curso input.nombre').val() == '' || $('#tipo_curso').val() == 'Seleccione'){
								$.alert({
									title: 'Atención',
									content: 'Ingrese Datos correctos',
									type: 'red',
									typeAnimated: true
								})
								return false;
							}
							$('#creditos').removeAttr('disabled')
							$.ajax({
								url: '<?= base_url("especialidades/registroCurso") ?>',
								type: 'POST',
								data: $('#form-register-curso').serialize(),
								success: function(response){
									if(JSON.parse(response).status == 200){
										$.alert({
											title: 'Atención',
											content: JSON.parse(response).message,
											type: 'green',
				                            typeAnimated: true
										})
										$('#especialidades').trigger('change')
									}else{
										$.alert({
					                        title: 'Atención',
					                        content: JSON.parse(response).message,
					                        type: 'red',
					                        typeAnimated: true
					                    })
									}
								}
							}).fail(function(){
			                    $.alert({
			                        title: 'Atención',
			                        content: 'Error en el registro, Consulte con su Administrador',
			                        type: 'red',
			                        typeAnimated: true
			                    })
			                })
						}
					},
					cancelar: {}
				},
                contentLoaded: function(data, status, xhr){
                    //self.setContentAppend('<h2>Resultado:</h2>');
                },
                onContentReady: function(){
                    //this.setContentAppend('<div>Resultado:</div>');
                    var self = this
                    $('#tipo_curso').on('change',function(){
    					if($('#tipo_curso').val() == '2' || $('#tipo_curso').val() == '4'){
    						$('#modulo').html('<option value="0">Seleccione</option>')
    						self.$content.find('.creditos').val('1')
    						self.$content.find('.creditos').prop('disabled',true)
    						/*$('#creditos').val('1')
    						$('#creditos').prop('disabled',true)*/
    					}
    					else{
    						$('#modulo').html('')
    						self.$content.find('.creditos').val()
    						self.$content.find('.creditos').prop('disabled',false)
    						/*$('#creditos').val('')
    						$('#creditos').removeAttr('disabled')*/
    					}
                    	var id_tipo = $('#tipo_curso').val()
                    	$.confirm({
                    		title: 'modulos',
                    		content: function(){
                    			var selfbusq = this
                    			return $.ajax({
                    				url: '<?= base_url('especialidades/cargaTipoModulos') ?>',
                    				method: 'POST',
                    				dataType: 'json',
                    				data: {
                    					id_tipo: id_tipo,
                    					id_especialidad: $('#especialidades').val()
                    				}
                    			}).done(function(response){
                    				if(response.status == 200){
                    					var mds = response.data.modulos
                    					for(var i in mds){
	                    					$('#modulo').append('<option value="'+mds[i].id+'">'+mds[i].orden+'.- '+mds[i].nombre+'</option>')
	                    				}
                    					$('#modulo').removeAttr('disabled')
                    				}
                    				else{
                    					$('#modulo').html('<option value="0">Seleccione</option>')
                    					$('#modulo').attr('disabled',true)
                    				}
                    					selfbusq.close()
                    			}).fail(function(){
                    				selfbusq.close()
                    			})
                    		}
                    	})
	                    /*if($('#tipo_curso').val() == '3' || $('#tipo_curso').val() == '2')
	                    	$('#modulo').removeAttr('disabled')
	                    else
	                    	$('#modulo').attr('disabled',true)*/
	                })
	                $('#numerico').on('click',function(){
	                	$('#notaminimavalor').show()
	                	$('#notaminimavalor').attr('required')
	                	$('#notaminimaletra').hide()
	                	$('#notaminimaletra').removeAttr('required')
	                })
	                $('#alfanumerico').on('click',function(){
	                	$('#notaminimavalor').hide()
	                	$('#notaminimavalor').removeAttr('required')
	                	$('#notaminimaletra').show()
	                	$('#notaminimaletra').attr('required')
	                })
	                $('#nombre_curso').on('blur',function(){
	                	var nom = $(this).val().toUpperCase().split(' ')
	                	if($('#ini_codigo').val() == '')
	                		$('#ini_codigo').val(nom[0].substring(0,1)+(1 in nom ? nom[1].substring(0,1) : nom[0].substring(1,2))+$('#cod_espe').val()+$('#cant_cursos').val())
	                })
	                /*$('#nombre_curso').autocomplete({
			          serviceUrl: '<?= base_url('especialidades/getCursoAutocomplete') ?>',
			          minChars: 3,
			          dataType: 'text',
			          type: 'POST',
			          paramName: 'data',
			          params: {
			            'data': $('#nombre_curso').val()
			          },
			          onSelect: function(suggestion){
			            var datos = JSON.parse(suggestion.data)
			            $('#id_curso').val(datos.id)
			            $('#ini_codigo').val(datos.codigo)
			            $('#creditos').val(datos.creditos)
			            $('#horas').val(datos.horas)
			            $('#descripcion').html(datos.descripcion)
			            $('#temas').html(datos.temas)
			            console.log(datos)
			            $.ajax({
			            	url: '<?= base_url('especialidades/traeDetalles') ?>',
			            	type: 'POST',
			            	data: {
			            		id_curso: datos.id
			            	},
			            	success: function(response){
			            		console.log(response)
			            		var d = JSON.parse(response).data
			            		var nota = d.nota_minima
			            		console.log(nota)
			            		if(nota.tipo_eval == 1){
			            			$('#numerico').attr('checked',true)
			            			$('#notaminimavalor').val(nota.eval_minima)
			            			$('#notaminimavalor').show()
	                				$('#notaminimaletra').hide()
			            		}else{
			            			$('#alfanumerico').attr('checked',true)
			            			$('#notaminimaletra').val(nota.eval_minima)
			            			$('#notaminimavalor').hide()
	                				$('#notaminimaletra').show()
			            		}
			            	}
			            })
			            console.log(datos)
			            //$('#idPersona').val(datos.id)
			          },
			          onSearchStart: function(q){},
			          onSearchComplete: function(q,suggestions){}
			        })*/
                }
			})
		})
		$('#especialidades').on('change',function(){
			$.ajax({
				url: '<?= base_url("especialidades/traeCursos") ?>',
				type: 'POST',
				data: {
					id_especialidad: $('#especialidades').val(),
					id_periodo: $('#periodos').val()
				},
				success: function(response){
					if(JSON.parse(response).status != 200){
						$('#message').html(JSON.parse(response).message)
						setTimeout(function() {
                            $('#message').html('')
                        }, 2000);
                        $('table tbody').html('')
					}
					else{
						var d = JSON.parse(response).data
						$('table tbody').html('')
						var cont = 0
						var id_ciclo_band = d[0].id_ciclo
						var ciclos  = []
						for(var i in d){
							$('table tbody').append('<tr><td>'+d[i].codigo+'</td><td>'+d[i].nombre+'</td><td>'+d[i].modulo+'</td><td>'+d[i].modulo_padre+'</td><td>'+
                                                        d[i].creditos+'</td><td>'+d[i].horas+'</td><td><div class="btn-group">'+
                                                        '<button type="button" class="btn btn-primary ver" title="Detalles" data-in="'+d[i].codigo+'"><i class="fa fa-eye"></i></button>'+
                                                        '<button type="button" class="btn btn-primary editar" title="Editar" data-in="'+d[i].codigo+'"><i class="fa fa-edit"></i></button></div></td></tr>')
							cont++;
						}
						cont++;
						var cor = '0'.repeat(4-(cont+'').length)+cont
						$('#cant_cursos').val(cor)
						$('table tbody tr td button.ver').unbind('click')
						$('table tbody tr td button.ver').on('click',function(){
							var c = $(this).attr('data-in')
							$.confirm({
								title: 'Consulta Curso',
								theme: 'light',
								columnClass: 'col-md-8 col-md-offset-2',
								content: function(){
									var self = this
									return $.ajax({
										url: '<?= base_url("especialidades/getInforCurso_") ?>',
										method: 'POST',
										dataType: 'json',
										data: {
                                            codigo: c
                                        }
									}).done(function(response){
                                        console.log(response)
                                        if(response.status == 200){
                                            var d = response.data
                                            self.setContentAppend('<div class="row"><div class="col-lg-12 col-md-12">'+
                                                '<span>Codigo - Nombre</span><h3 class="p-xxs bg-muted"><i class="fa fa-book"></i>&nbsp;'+d.curso.codigo+' - '+d.curso.nombre+'</h3></div>'+
                                                '<div class="col-lg-3 col-md-3"><span>Creditos</span><h3 class="p-xxs bg-muted"><i class="fa fa-coins"></i>&nbsp;'+d.curso.creditos+'</h3></div></div>')
                                            self.setContentAppend('<div class="row"><div class="col-lg-12 col-md-12">'+
                                                    '<span>Especialidad</span><h3 class="p-xxs bg-muted"><i class="fa fa-flag"></i>&nbsp;'+d.infor.especialidad+'</h3></div></div>')
                                            self.setContentAppend('<div class="row"><div class="col-lg-12 col-md-12">'+
                                                    '<span>Modulo</span><h3 class="p-xxs bg-muted"><i class="fa fa-flag"></i>&nbsp;'+d.infor.modulo+'</h3></div></div>')
                                            self.setContentAppend('<div class="row"><div class="col-lg-12 col-md-12">'+
                                                    '<span>Descripción</span><h3 class="p-xxs bg-muted"><i class="fa fa-flag"></i>&nbsp;'+d.curso.descripcion+'</h3></div></div>')
                                            self.setContentAppend('<div class="row"><div class="col-lg-12 col-md-12">'+
                                                    '<span>Temas</span><h3 class="p-xxs bg-muted"><i class="fa fa-flag"></i>&nbsp;'+d.curso.temas+'</h3></div></div>')
                                        }
									})
								}
							})
						})
					}
				}
			})
		})
	})
</script>