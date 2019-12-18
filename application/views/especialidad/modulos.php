<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Especialidades</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("especialidades");?> ">Especialidad</a></li>
            <li class="active">Modulos</li>
            <li ><strong>Nuevo</strong></li>
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div>
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox " id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5>Registro de Modulos</h5> 
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="ibox-content m-b-sm border-bottom" style="display: none;" id="busqueda">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
            <form name="form-register" id="form-busqueda" class="form-horizontal"  >
                <div class="form-group">
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                    	<label>Especialidad</label>
                        <select class="form-control" required name="especialidad" id="especialidad_busqueda">
                            <option>Seleccione Especialidad</option>
                            <?php if($especialidades) foreach ($especialidades as $key => $value) { ?>
                            	<option value="<?= $value->id ?>"><?= $value->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>&nbsp;</label><br>
                        <button type="button" class="btn btn-primary" id="nuevo"><i class="fa fa-plus"></i> Nuevo</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="ibox-content m-b-sm border-bottom" id="registro">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
            <form name="form-register" id="form-register" class="form-horizontal"  >
                <div class="form-group">
                        <div class="col-sm-4 col-lg-4 col-md-4 col-xs-12">
                        	<label>Nombre</label>
                        	<input type="text" class="form-control" required name="nombre" placeholder="Nombre de Modulo" id="modulo">
                        	<input type="hidden" name="id_modulo" id="id_modulo" value="0">
                        	<input type="hidden" name="id_especialidad_modulo" id="id_especialidad_modulo" value="0">
                        </div>
                        <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                            <label>Especialidad</label><br>
                            <select class="form-control chosen-select" required name="especialidad" id="especialidad">
                                <option>Seleccione Especialidad</option>
                                <?php if($especialidades) foreach ($especialidades as $key => $value) { ?>
                                    <option value="<?= $value->id ?>"><?= $value->nombre ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <label>Tipo de Modulo</label><br>
                            <div class="i-checks"><label><input type="radio" value="1" name="tipo" id="tipo_1">&nbsp;Profesional</label></div>
                            <div class="i-checks"><label><input type="radio" value="2" name="tipo" id="tipo_2">&nbsp;Transversal</label></div>
                        </div>
                        <div class="col-lg-2 col-sm-2 col-md-2">
                            <label>Orden del modulo</label><br>
                            <select class="form-control" name="orden" id="orden">
                                <?php for ($i=0; $i < 5; $i++) { ?>
                                    <option value="<?= ($i+1) ?>"><?= ($i+1) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <button type="button" class="btn btn-primary" id="limpiar"><i class="fa fa-eraser"></i> Limpiar</button>
                    	<button type="submit" class="btn btn-primary" id="guardar"><i class="fa fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-primary" id="buscar"><i class="fa fa-search"></i> Buscar</button>
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
                                    <th>Nombre</th>
                                    <th>Especialidad</th>
                                    <th>Tipo de Modulo</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php /*if($modulos) foreach ($modulos as $key => $value) { ?>
                            		<tr>
                            			<td><?= $value->modulo ?></td>
                            			<td><?= $value->especialidad ?></td>
                            			<td><?= $value->cant_cursos ?></td>
                            			<td>
                            				<div class="btn-group">
                            					<button class="btn btn-primary editar" type="button" data-in="<?= $value->id ?>"><i class="fa fa-edit"></i></button>
                            				</div>
                            			</td>
                            		</tr>
                            	<?php }*/ ?>
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
        $("#especialidad").chosen({ width: '100%' })
        //$('#periodo').chosen({ width: '100%' })
        $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        $('.i-checks').on('ifClicked', function (ev) { 
            $('#orden').html('')
            if($(ev.target).attr('id') == 'tipo_1'){
                for (var i = 0; i < 5; i++) {
                    $('#orden').append('<option value="'+(parseInt(i)+1)+'">'+(parseInt(i)+1)+'</option>')
                }
            }
            else{
                $('#orden').append('<option value="0">0</option>')
            }
        })
        $('#nuevo').on('click',function(){
            $('#busqueda').hide()
            $('#registro').show()
        })
        $('#buscar').on('click',function(){
            $('#registro').hide()
            $('#busqueda').show()
        })
        $('#limpiar').on('click',function(){
            $('#modulo').val('')
            $('#especialidad').val('Seleccione Especialidad')
            $('#cant_curso').val('')
        })
		$('#form-register').on('submit',function(e){
			e.preventDefault()
			$.ajax({
				url: '<?= base_url("especialidades/newModulo") ?>',
				type: 'POST',
				data: $('#form-register').serialize(),
				success: function(response){
					var r = JSON.parse(response)
					console.log(r)
					$.alert({
						title: 'Atención',
						content: r.message,
						theme: 'light',
						type: 'green',
                                                typeAnimated: true,
						buttons: {
							ok: function(){
								window.location.href = '<?php echo base_url('especialidades/modulos'); ?>';
							}
						}
					})
				},
				error: function(request, status, error){
					$.alert({
						title: 'Atención',
						content: 'Ocurrio un error en el registro',
						type: 'red',
                        typeAnimated: true
					})
				}
			})
		})
		/*$('table tbody tr td button.editar').on('click',function(){
			var id = $(this).attr('data-in')
			$.ajax({
				url: '<?= base_url('especialidades/getEspecialidadModulos') ?>',
				type: 'POST',
				data: {
					'id_especialidad_modulo': id
				},
				success: function(response){
					if(JSON.parse(response).status == 200){
						var d = JSON.parse(response).data
						console.log(d)
						$('#id_modulo').val(d.id_modulo)
						//$('#id_especialidad_modulo').val(d.id)
						$('#modulo').val(d.modulo)
						$('#especialidad').val(d.id_especialidad)//.trigger('chosen:updated')
                        $(".chosen-select").trigger("chosen:updated")
                        //$('#especialidad')
						//$('#cant_curso').val(d.cant_cursos)
					}
				},
				error: function(request, status, error){
					$.alert({
						title: 'Atención',
						content: 'Ocurrio un error en la consulta',
						type: 'red',
                                                typeAnimated: true
					})
				}
			})
		})
                $('#especialidad').on('change',function(){
                    var id = $(this).val()
                    $('#orden').html('')
                    $.ajax({
                        url: '<?= base_url('especialidades/getOrder') ?>',
                        type: 'POST',
                        data: {
                            id_especialidad: id
                        },
                        success: function(response){
                            if(JSON.parse(response).status == 200){
                                var n = JSON.parse(response).data.cantidad
                                var or = JSON.parse(response).data.orders
                                console.log(or)
                                if(n>=1)
                                    for(var i = 1; i <= parseInt(n)+1 ; i++){
                                        $('#orden').append('<option value="'+i+'" '+(typeof or[i] === 'undefined' ? '' : 'disabled')+'>'+i+'</option>')
                                    }
                                else
                                    $('#orden').append('<option value="1">1</option>')
                            }
                        },
                        error: function(request,status,error){
                            $.alert({
                                title: 'Atención',
                                content: 'Ocurrio un error en la consulta',
                                type: 'red',
                                typeAnimated: true
                            })
                            $('#orden').html('<option value="1">1</option>')
                        }
                    })
                })*/
                $('#especialidad_busqueda').on('change',function(){
                    var id = $(this).val()
                    $.ajax({
                        url: '<?= base_url('especialidades/getOnlyModules') ?>',
                        type: 'POST',
                        data: {
                            id_especialidad: id
                        },
                        success: function(response){
                                $('#dataTable tbody').html('')
                            if(JSON.parse(response).status == 200){
                                var d = JSON.parse(response).data
                                for(var i in d){
                                    $('#dataTable tbody').append('<tr><td>'+d[i].modulo+'</td><td>'+d[i].especialidad+'</td><td>'+(d[i].orden == '0' ? 'Transversal' : 'Profesional')+'</td><td><div class="btn-group"><!--button class="btn btn-primary editar" type="button" data-in="'+d[i].id+'"><i class="fa fa-edit"></i></button--></div></td></tr>')
                                    $('table tbody tr td button.editar').unbind('click')
                                    $('table tbody tr td button.editar').on('click',function(){
                                        $('#registro').show()
                                        $('#busqueda').hide()
                                        var id = $(this).attr('data-in')
                                        $.ajax({
                                                url: '<?= base_url('especialidades/getEspecialidadModulos') ?>',
                                                type: 'POST',
                                                data: {
                                                        'id_especialidad_modulo': id
                                                },
                                                success: function(response){
                                                        if(JSON.parse(response).status == 200){
                                                                var d = JSON.parse(response).data
                                                                //console.log(d)
                                                                $('#id_modulo').val(d.id_modulo)
                                                                $('#id_especialidad_modulo').val(d.id)
                                                                $('#modulo').val(d.modulo)
                                                                $('#especialidad').val(d.id_especialidad)
                                                                $('#especialidad').trigger('click')
                                                                $('#cant_curso').val(d.cant_cursos)
                                                                $('#tipo_1').removeAttr('checked')
                                                                $('#tipo_2').removeAttr('checked')
                                                                if(d.tipo == 1)
                                                                    $('#tipo_1').attr('checked',true)
                                                                if(d.tipo == 2)
                                                                    $('#tipo_2').attr('checked',true)
                                                                $('.i-checks').iCheck({
                                                                    checkboxClass: 'icheckbox_square-green',
                                                                    radioClass: 'iradio_square-green',
                                                                });
                                                        }
                                                },
                                                error: function(request, status, error){
                                                        $.alert({
                                                                title: 'Atención',
                                                                content: 'Ocurrio un error en la consulta',
                                                                type: 'red',
                                                                typeAnimated: true
                                                        })
                                                }
                                        })
                                })
                                }
                            }else{
                                $.alert({
                                    title: 'Atención',
                                    content: JSON.parse(response).message,
                                    type: 'res',
                                    typeAnimated: true
                                })
                            }
                        }
                    })
                })
	})
</script>