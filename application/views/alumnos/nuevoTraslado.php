<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Alumnos</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Internos</a></li>
            <li>Alumno</li>
            <li class="active"><strong>Traslado Externo</strong></li>
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox" id="ibox_questionnaire">
                <div class="ibox-title">
                    <h5>Registro de nuevo Alumno</h5>
                </div>
                <div class="ibox-content">
                    <div class="sk-spinner sk-spinner-double-bounce">
                        <div class="sk-double-bounce1"></div>
                        <div class="sk-double-bounce2"></div>
                    </div>
                    <form  id="form_register" name="form_register" enctype="multipart/form-data" class="wizard-big">
                    	<h1>Datos personales</h1>
                    	<h1>Datos educativos</h1>
                    	<fieldset>
                    		<div class="row">
                    			<div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
	                    			<label>Nombres*</label>
	                    			<input style="display: block;" type="text" required class="form-control" id="nombres" name="nombre" placeholder="Juan" value="<?= !is_numeric($alumno) ? $alumno->nombre : '' ?>" <?= !is_numeric($alumno) ? 'disabled' : '' ?>>
                                    <input type="hidden" name="id_persona" value="<?= !is_numeric($alumno) ? $alumno->id_persona : 0 ?>">
                                    <input type="hidden" name="cod_alumno" value="<?= !is_numeric($alumno) ? $alumno->codigo : '' ?>">
                    			</div>
                    			<div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                    				<label>Apellido Paterno*</label>
                    				<input style="display: block;" type="text" required class="form-control" id="apell_pat" name="apell_pat" placeholder="Perez" value="<?= !is_numeric($alumno) ? $alumno->apell_pat : '' ?>" <?= !is_numeric($alumno) ? 'disabled' : '' ?>>
                    			</div>
                    			<div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                    				<label>Apellido Materno*</label>
                    				<input style="display: block;" type="text" required class="form-control" id="apell_mat" name="apell_mat" placeholder="Albeola" value="<?= !is_numeric($alumno) ? $alumno->apell_mat : '' ?>" <?= !is_numeric($alumno) ? 'disabled' : '' ?>>
                    			</div>
                    			<div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                    				<label>DNI*</label>
                    				<input style="display: block;" type="number" required class="form-control" id="dni" name="dni" placeholder="87654321" value="<?= !is_numeric($alumno) ? $alumno->dni : '' ?>" <?= !is_numeric($alumno) ? 'disabled' : '' ?>>
                    			</div>
                    		</div>
                            <?php if(is_numeric($alumno)){/* ?>
                            <div class="row">
                                <div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
                                    <label>Dirección</label>
                                    <input style="display: block;" type="text" name="direccion" id="direccion" placeholder="Dirección" class="form-control" value="<?= !is_numeric($alumno) ? $alumno->direccion : '' ?>">
                                </div>
                                <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                                    <label>Fecha de Nacimiento</label>
                                    <input style="display: block;" type="text" name="fch_nac" class="form-control datepicker" id="fch_nac" value="<?= date('d-m-Y') ?>" value="<?= !is_numeric($alumno) ? $alumno->fch_nac : '' ?>">
                                </div>
                                <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                                    <label>Genero*</label>
                                    <select name="genero" class="form-control" required id="genero">
                                        <option>Seleccione genero</option>
                                        <?php foreach ($genero as $key => $value) { ?>
                                            <option value="<?= $value->id ?>" <?= !is_numeric($alumno) && $alumno->id_genero ? 'selected' : '' ?>><?= $value->nombre ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                                    <label>Estado Civil*</label>
                                    <select name="estado_civil" class="form-control" required id="estado_civil">
                                        <option>Seleccione Estado Civil</option>
                                        <?php foreach ($estado_civil as $key => $value) { ?>
                                            <option value="<?= $value->id ?>" <?= !is_numeric($alumno) && $alumno->id_estado_civil ? 'selected' : '' ?>><?= $value->nombre ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                                    <label>Email</label>
                                    <input style="display: block;" type="email" name="email" placeholder="Email" id="email" class="form-control" value="<?= !is_numeric($alumno) ? $alumno->email : '' ?>">
                                </div>
                                <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                                    <label>Telefono</label>
                                    <input style="display: block;" type="number" class="form-control" name="telefono" id="telefono" placeholder="073123456" value="<?= !is_numeric($alumno) ? $alumno->telefono : '' ?>">
                                </div>
                                <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                                    <label>Celular 1</label>
                                    <input style="display: block;" type="number" class="form-control" name="celular_1" id="celular_1" placeholder="073123456" value="<?= !is_numeric($alumno) ? $alumno->celular_1 : '' ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                                    <label>Celular 2</label>
                                    <input style="display: block;" type="number" class="form-control" name="celular_2" id="celular_2" placeholder="073123456" value="<?= !is_numeric($alumno) ? $alumno->celular_2 : '' ?>">
                                </div>
                            </div>
                        <?php */} ?>
                    	</fieldset>
                    	<fieldset>
                    		<div class="row">
                    			<div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
	                    			<label class="control-label">Especialidad *</label>
			                        <select class="form-control" name="especialidad" id="especialidad" required id="especialidad">
			                        	<option>Seleccione especialidad</option>
			                        	<?php foreach ($especialidades as $key => $value) { ?>
			                        		<option value="<?= $value->id ?>"><?= $value->especialidad ?></option>
			                        	<?php } ?>
			                        </select>
                    			</div>
                                <div class="col-sm-2 col-lg-2 col-md-2 col-xs-12">
                                    <label>Periodo *</label>
                                    <input style="display: block;" class="form-control" readonly type="text" name="periodo" id="periodo" required>
                                    <input type="hidden" name="id_periodo" id="id_periodo" value="0">
                                    <input type="hidden" name="id_especialidad_periodo" id="id_especialidad_periodo">
                                </div>
                                <div class="col-sm-2 col-lg-2 col-md-2 col-xs-12">
                                    <label>Turno *</label>
                                    <select name="turno" class="form-control" id="turno">
                                    </select>
                                </div>
                                <div class="col-sm-2 col-lg-2 col-md-2 col-xs-12">
                                    <label>Ciclo *</label>
                                    <select name="ciclo" class="form-control" id="ciclo">
                                    </select>
                                </div>
                                <div class="col-sm-2 col-lg-2 col-md-2 col-xs-12">
                                    <label>Plan de Estudios *</label>
                                    <select name="plan" class="form-control" id="plan">
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <label>N° Expediente</label>
                                    <input type="text" class="form-control" name="nro_expediente" placeholder="NNNN/AAAA" style="display: block;">
                                </div>
                                <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                                    <label>Fecha de Ingreso *</label>
                                    <input style="display: block;" type="text" name="fch_ingreso" class="form-control datepicker" id="fch_ingreso" value="<?= date('d/m/Y') ?>">
                                </div>
                                <input type="hidden" name="tipoalumno" value="<?= $tipoalumno ?>">
                    		</div><br>
                            <!--div class="row">
                                <div class="table-responsive" style="display: none;">
                                    <table class="table table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Codigo</th>
                                                <th>Ciclo</th>
                                                <th>Curso</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div-->  
                    	</fieldset>
                    </form>
                    <a href=javascript:history.back(1) class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                </div>
            </div>
       	</div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('focus',".datepicker", function(){
                    $(this).datepicker({
            format: 'dd-mm-yyyy'
        });
            }); 
	$(function(){
        var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        var tabla = null
        var malla_registro = null
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true
          
        });
        /*var cargaTabla = function(){
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
                      }
                    })
        }*/

		$("#form_register").steps({
            labels:{
                current: "Siguiente paso:",
                pagination: "Paginación",
                finish: "Finalizar",
                next: "Siguiente",
                previous: "Anterior",
                loading: "Cargando ...",
                cancel: "Cancelar"
            },
            bodyTag: "fieldset",
            //enableFinishButton: false,
            //showFinishButtonAlways: false,
            transitionEffect: "slideLeft",
            onContentLoaded: function(event, currentIndex){
                $('.wizard-big.wizard > .content').css('style','min-height: auto !important;')
            },
            onStepChanging: function (event, currentIndex, newIndex){
            	// Always allow going backward even if the current step contains invalid fields!
                if (currentIndex > newIndex)
                {
                    return true;
                }


                var form = $(this);

                // Clean up if user went backward before
                if (currentIndex < newIndex)
                {
                    // To remove error styles
                    $(".body:eq(" + newIndex + ") label.error", form).remove();
                    $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
                }

                // Disable validation on fields that are disabled or hidden.
                form.validate().settings.ignore = ":disabled,:hidden";

                // Start validation; Prevent going forward if false
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex){},
            onFinishing: function (event, currentIndex){
                var form = $(this);

                // Disable validation on fields that are disabled.
                // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
                form.validate().settings.ignore = ":hidden";

                // Start validation; Prevent form submission if false
                return form.valid();
            },
            onFinished: function (event, currentIndex){
                if($('#id_periodo').val() == 0){
                    $.alert({
                        title: 'Atención',
                        content: 'No hay periodo abierto para matricular a un nuevo alumno.',
                        type: 'red',
                        typeAnimated: true
                    })
                    return false
                }
                $('#ibox_questionnaire').children('.ibox-content').toggleClass('sk-loading');
                $.ajax({
                    url: '<?= base_url("alumnos/matriculaTrasladoExterno") ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: $('#form_register').serialize(),
                    success: function(data){
                        if(data.status == 200){
                            $.alert({
                                title: 'Atención',
                                content: data.message,
                                type: 'green',
                                typeAnimated: true,
                                buttons: {
                                    imprimir: function(){
                                        //$('#ibox_questionnaire').children('.ibox-content').toggleClass('sk-loading');
                                        //window.location.href = '<?php echo base_url("alumnos/impresionnuevo/"); ?>'+data.data.codigo_alumno
                                        window.open('<?php echo base_url("alumnos/impresionnuevo/"); ?>'+data.data.codigo_alumno,'_blank' )
                                        return false
                                        //window.open('<?php echo base_url("alumnos/impresioncursos/"); ?>'+data.data.codigo_alumno,'_blank' )
                                        //window.location.href = '<?php echo base_url('alumnos/'.$tipoalumno); ?>';
                                    },
                                    ok: function(){
                                        window.location.href = '<?php echo base_url('alumnos/'.$tipoalumno.'/'); ?>'+$('#especialidad').val();
                                    }
                                }
                            })
                            $('.message').append('<div class="alert alert-success">' + data.message + '</div>'); 
                        }else{
                            $.alert({
                                title: 'Atención',
                                content: data.message,
                                type: 'red',
                                typeAnimated: true
                            })
                            $('#ibox_questionnaire').children('.ibox-content').removeClass('sk-loading');
                            //window.location.reload()
                            $('.message').append('<div class="alert alert-danger">' + data.message + '</div>');
                        }
                        /*setTimeout(function() { 
                                $('.message').empty();
                                window.location.href = '<?php echo base_url('alumnos/'.$tipoalumno); ?>';
                        }, 2000); 
                        console.log(data)*/
                    }
                }).fail(function(){
                    $.alert({
                        title: 'Atención',
                        content: 'Error en el registro, Consulte con su Administrador',
                        type: 'red',
                        typeAnimated: true
                    })
                            //window.location.reload()
                })
            }
        }).validate({
            errorPlacement: function (error, element)
            {
                element.before(error);
            }
        })
        
        $('#especialidad').on('change',function(){
            if($(this).val() == 'Seleccione especialidad'){
                $('.table tbody').html('')
                $('.table-responsive').hide()
                $('#periodo').html('')
                $('#turno').html('')
                $('#ciclo').html('')
                 $('#plan').html('')
                return false
            } 
            $.ajax({
                url: '<?= base_url("alumnos/getPeriodoEspecialidad") ?>',
                type: 'POST',
                data: {
                    id_especialidad: $('#especialidad').val(),
                    tipo: 1
                },
                success: function(data){
                    var d = JSON.parse(data)
                    if(JSON.parse(data).status == 200){
                        var p = d.data.esp_p
                        $('#periodo').val(p.nombre)
                        $('#id_periodo').val(p.id_periodo_actual)
                        $('#turno').html('')

                        
                        var turs = d.data.turno
                        for(var i in turs){
                            $('#turno').append('<option value="'+turs[i].id_turno+'">'+turs[i].turno+'</option>');
                        }
                        
                        $('#turno').val(p.id_turno)
                        $('#ciclo').html('')
                        var cls = d.data.ciclos
                        for(var i in cls){
                            $('#ciclo').append('<option value="'+cls[i].id_ciclo+'">'+cls[i].ciclo+'</option>')
                        }

                        $('#plan').html('')
                        var planes = d.data.periodos_anteriores 
                        for(var i in planes){
                            $('#plan').append('<option value="'+planes[i].id+'">'+planes[i].nombre+'</option>')
                        }
                        //$('#turno').attr('disabled',true)
                        $('#id_especialidad_periodo').val(p.id)

                        $.ajax({
                            url: '<?= base_url('cursos/getCursosForPeriodo') ?>',
                            type: 'POST',
                            data: {
                                id_especialidad_periodo: $('#id_especialidad_periodo').val(),
                                id_turno: $('#turno').val()
                            },
                            success: function(response){
                                if(JSON.parse(response).status == 200){
                                    var d = JSON.parse(response).data.cursos
                                    $('.table-responsive').show()
                                    //cargaTabla()
                                    tabla.fnClearTable()
                                    tabla.fnDraw()
                                    for(var i in d){
                                        tabla.fnAddData([
                                                parseInt(i)+1,
                                                d[i].codigo_curso,
                                                d[i].nombre_ciclo,
                                                d[i].curso,
                                                ''
                                            ])
                                    }
                                    malla_registro = d
                                }
                            }
                        })
                    } 
                }
            })
        })

        $('#turno').on('change',function(){
            $.confirm({
              title: 'Consultando',
              content: function(){
                var self2 = this
                return $.ajax({
                  url: '<?= base_url('alumnos/cargaInformacionTraslado3') ?>',
                  method: 'POST',
                  dataType: 'json',
                  data: {
                    id_especialidad: $('#especialidad').val(),
                    id_turno: $('#turno').val(),
                    id_periodo: $('#id_periodo').val()
                  }
                }).done(function(response){
                  if(response.status == 200){
                    self2.close()
                    var cls = response.data.ciclos
                    $('#ciclo').html('')
                    for(var i in cls){
                      $('#ciclo').append('<option value="'+cls[i].id_ciclo+'">'+cls[i].ciclo+'</option>')
                    }

                    var pln = response.data.periodos_anteriores
                    $('#plan').html('')
                    for(var i in pln){
                      $('#plan').append('<option value="'+pln[i].id+'">'+pln[i].nombre+'</option>')
                    }

                  }
                  else{
                    self2.close()
                    toastr.error(response.message)
                  }
                }).fail(function(){
                  self2.close()
                  toastr.error('Error consulte con su Administrador')
                })
              }
            }) 
        })

         $('#ciclo').on('change',function(){
            $.confirm({
              title: 'Consultando',
              content: function(){
                var self2 = this
                return $.ajax({
                  url: '<?= base_url('alumnos/cargaInformacionTraslado4') ?>',
                  method: 'POST',
                  dataType: 'json',
                  data: {
                    id_especialidad: $('#especialidad').val(),
                    id_turno: $('#turno').val(),
                    id_periodo: $('#id_periodo').val(),
                    id_ciclo: $('#ciclo').val()
                  }
                }).done(function(response){
                  if(response.status == 200){
                    self2.close()
                    var pln = response.data.periodos_anteriores
                    $('#plan').html('')
                    for(var i in pln){
                      $('#plan').append('<option value="'+pln[i].id+'">'+pln[i].nombre+'</option>')
                    }

                  }
                  else{
                    self2.close()
                    toastr.error(response.message)
                  }
                }).fail(function(){
                  self2.close()
                  toastr.error('Error consulte con su Administrador')
                })
              }
            }) 
         })

	})
</script>