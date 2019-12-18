<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Lista de Secciones</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Periodos</a></li>
            <li class="active"><strong>Secciones</strong></li>
            <!--li>Internos</li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div>
<div class="row wrapper border-bottom white-bg page-heading" style="padding-top: 10px;">
    <div class="col-lg-12">
        <div class="card text-center">
              <div class="card-header">
                    <ul class="nav nav-pills card-header-pills">
                      <li class="nav-item">
                        <a class="nav-link active" href="#" data-in="panel-lista">Secciones</a>
                      </li>
                      <?php if($periodo->estado != 2){ ?>
                          <li class="nav-item">
                            <a class="nav-link" href="#" data-in="panel-registo">Registro Secciones</a>
                          </li>
                      <?php } ?>
                    </ul>
              </div>
        </div>
    </div>
</div>
<div id="panel-lista">
    <div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
        <div class="ibox " id="box_import_invoices">
            <div class="ibox-title bg-primary">
                <h5>Lista de secciones aperturadas para el periodo <?= $periodo->nombre ?></h5> 
                <div class="ibox-tools">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="ibox-content m-b-sm">
                <div class="sk-spinner sk-spinner-double-bounce">
                    <div class="sk-double-bounce1"></div>
                    <div class="sk-double-bounce2"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTableListaSecciones" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sección</th>
                                <th>Curso</th>
                                <th>Especialidad</th>
                                <th>Periodo</th>
                                <th>Turno</th>
                                <th>Profesor</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!is_numeric($lista)) foreach ($lista as $key => $value) { ?>
                                <tr>
                                    <td><?= ($key+1) ?></td>
                                    <td><?= $value->nombre ?></td>
                                    <td><?= $value->curso ?></td>
                                    <td><?= $value->especialidad ?></td>
                                    <td><?= 'Ciclo '.$value->id_ciclo ?></td>
                                    <td><?= $value->turno ?></td>
                                    <td><?= $value->profesor ?></td>
                                    <td><?php switch ($value->estado_sec_curso) {
                                        case 0:
                                            echo 'En curso';
                                            break;

                                        case 1:
                                            echo 'Cerrado Nota Regular';
                                            break;

                                        case 2:
                                            echo 'Cerrado Nota Recuperación';
                                            break;

                                        case 3:
                                            echo 'Cerrado Final';
                                            break;
                                        
                                        default:
                                            echo 'Cerrado';
                                            break;
                                    }  ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <!--button type="button" class="btn btn-primary editar" data-in="<?= $value->id_seccion_curso ?>" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-edit"></i></button-->
                                            <button type="button" class="btn btn-primary asignar" data-in="<?= $value->id_seccion_curso ?>" data-toggle="tooltip" data-placement="top" title="Asignar Profesor"><i class="fas fa-chalkboard-teacher"></i></button>
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
<div id="panel-registo" style="display: none;">
    <div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
        <div class="ibox " id="box_import_invoices">
            <div class="ibox-title bg-primary">
                <h5>Registro de Secciones academicos para el periodo <?= $periodo->nombre ?></h5> 
                <div class="ibox-tools">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="ibox-content m-b-sm border-bottom">
                <div class="sk-spinner sk-spinner-double-bounce">
                    <div class="sk-double-bounce1"></div>
                    <div class="sk-double-bounce2"></div>
                </div>
                <form name="form-register-seccion" id="form-register-seccion" class="form-horizontal"  >
                    <div class="form-group">
                        <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        	<label>Especialidad</label>
                        	<select class="form-control" required name="especialidad" id="especialidad">
                                <option>Seleccione Especialidad</option>
                                <?php if($especialidades) foreach ($especialidades as $key => $value) { ?>
                                	<option value="<?= $value->id_especialidad ?>"><?= $value->nombre ?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="id_periodo" id="id_periodo" value="<?= $periodo->id ?>">
                            <input type="hidden" name="id_especialidad_periodo" id="id_especialidad_periodo" value="0">
                        </div>
                        <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                            <label>Turno</label>
                            <select class="form-control" required name="turno" id="turno">
                                <option>Seleccione Turno</option>
                                <option value="1">Vespertino</option>
                                <!--<option value="2">Nocturno</option>-->
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content">
    	<div class="row">
    		<div class="col-lg-6 col-md-6">
                <div class="ibox">
                    <div class="ibox-content listaItems">
                        <div class="table-responsive">
                            <table class="table table-stripped" id="dataTable" width="100%" cellspacing="0">  
                            	<thead>
                                    <tr>
                                    	<th>Ciclo</th>
                                    	<th>Curso</th>
                                        <th>Promocion</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="ibox">
                    <div class="ibox-content listaItems">
                        <div class="table-responsive">
                            <table class="table table-stripped" id="dataTableSeccion" width="100%" cellspacing="0">  
                                <thead>
                                    <tr>
                                        <th>Seccion</th>
                                        <th>Curso</th>
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
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <center>
                    <div class="btn-group">
                        <a href=javascript:history.back(1) class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Volver</a>
                        <button type="button" id="generar" disabled class="btn btn-primary"><i class="fa fa-check"></i> Generar</button>
                    </div>
                </center>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(function(){
        $('[data-toggle="tooltip"]').tooltip()
        var t = $('#dataTable').dataTable({
          "language": {
            "paginate": {
              "first": "Primera pagina",
              "last": "Ultima pagina",
              "next": "Siguiente",
              "previous": "Anterior"
            },
            "emptyTable": "Tabla vacia",
            "infoEmpty": "Observando 0 a 0 d 0 registros",
            "info": "Observando pagina _PAGE_ de _PAGES_",
            "lengthMenu": "Desplegando _MENU_ Registros",
            "search": "Busqueda"
          },
          "columns": [
            { "width": "10%" },
            { "width": "60%" },
            { "width": "15%" },
            { "width": "15%" }
          ],
          bFilter: true, 
          bInfo: true
        })
        var tseccion = $('#dataTableSeccion').dataTable({
          "language": {
            "paginate": {
              "first": "Primera pagina",
              "last": "Ultima pagina",
              "next": "Siguiente",
              "previous": "Anterior"
            },
            "emptyTable": "Tabla vacia",
            "infoEmpty": "Observando 0 a 0 d 0 registros",
            "info": "Observando pagina _PAGE_ de _PAGES_",
            "lengthMenu": "Desplegando _MENU_ Registros",
            "search": "Busqueda"
          },
          bFilter: true, 
          bInfo: true,
          "drawCallback": function( settings ) {
            $('#guardar_generado').on('click',function(){
                $.confirm({
                    title: 'Atención',
                    content: 'Esta seguro de los datos generados?',
                    buttons: {
                        si: function(){
                            window.location.reload();
                        },
                        no: function(){}
                    }
                })
            })
            $('#eliminar_generado').on('click',function(){
                console.log('deshabilita las secciones aperturadas')
            })
          }
        })
        var tseccioncurso = $('#dataTableListaSecciones').dataTable({
          "language": {
            "paginate": {
              "first": "Primera pagina",
              "last": "Ultima pagina",
              "next": "Siguiente",
              "previous": "Anterior"
            },
            "emptyTable": "Tabla vacia",
            "infoEmpty": "Observando 0 a 0 d 0 registros",
            "info": "Observando pagina _PAGE_ de _PAGES_",
            "lengthMenu": "Desplegando _MENU_ Registros",
            "search": "Busqueda"
          },
          bFilter: true, 
          bInfo: false
        })
        var cargarListaSecciones = function(){
            $.ajax({
                url: '<?= base_url('periodos/cargaSecciones') ?>',
                type: 'POST',
                data: {
                    id_periodo: $('#id_periodo').val()
                },
                success: function(response){
                    if(JSON.parse(response).status != 200){
                        $.alert({
                            title: 'Atención',
                            content: JSON.parse(response).message,
                            type: 'red',
                            typeAnimated: true
                        })
                        return false
                    }else{
                        var d = JSON.parse(response).data
                        tseccioncurso.fnClearTable()
                        tseccioncurso.fnDraw()
                        for(var i in d){
                            tseccioncurso.fnAddData([
                                parseInt(i)+1,
                                d[i].nombre,
                                d[i].curso,
                                d[i].especialidad,
                                d[i].id_profesor != 1 ? d[i].profesor : '-',
                                parseInt(d[i].estado) == 1 ? 'Abierto' : 'Cerrado',
                                '<div class="btn-group"><button type="button" class="btn btn-primary editar" data-in="'+d[i].id_seccion_curso+'" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-primary asignar" data-in="'+d[i].id_seccion_curso+'" data-toggle="tooltip" data-placement="top" title="Asignar Profesor"><i class="fas fa-chalkboard-teacher"></i></button></div>'
                                ])
                        }
                        $('#dataTableListaSecciones tbody tr td button.asignar').unbind('click')
                        $('#dataTableListaSecciones tbody tr td button.asignar').on('click',function(){
                            var ele = $(this).parent().parent().parent()
                            var id_seccion_curso = $(this).attr('data-in')
                            $.confirm({
                                title: 'Asignación del profesor',
                                theme: 'light',
                                columnClass: 'col-md-6 col-md-offset-3',
                                content: '<div class="content"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12"><label>Profesor</label><input type="text" class="form-control prof" name="prof" id="prof" placeholder="Juan Perez"><input type="hidden" id="id_profesor" name="id_profesor" value="0"><input type="hidden" id="profesor"></div></div></div>',
                                buttons: {
                                    asignar: function(){
                                        //window.location.href = '<?php echo base_url('periodos/editar/'.$periodo->id); ?>';
                                        $.ajax({
                                            url: '<?= base_url('profesores/asignacionProfesor') ?>',
                                            type: 'POST',
                                            data: {
                                                id_seccion_curso: id_seccion_curso,
                                                id_profesor: $('#id_profesor').val()
                                            },
                                            success: function(response){
                                                var d = JSON.parse(response).data
                                                $(ele.children()[6]).html(d.nombre+' '+d.apell_pat+' '+d.apell_mat)
                                            }
                                        })
                                    },
                                    but: {
                                        text: 'Sin Profesor',
                                        btnClass: 'btn btn-danger',
                                        action: function(){
                                            $.ajax({
                                                url: '<?= base_url('profesores/asignacionProfesor') ?>',
                                                type: 'POST',
                                                data: {
                                                    id_seccion_curso: id_seccion_curso,
                                                    id_profesor: 1
                                                },
                                                success: function(response){
                                                    $(ele.children()[6]).html('-')
                                                }
                                            })
                                        }
                                    }
                                },
                                contentLoaded: function(data, status, xhr){
                                    //self.setContentAppend('<h2>Resultado:</h2>');
                                    //this.setContentAppend('<div>Resultado:</div>');
                                },
                                onContentReady: function(){
                                    $('#prof').autocomplete({
                                        serviceUrl: '<?= base_url('profesores/getProfesoresAutocomplete') ?>',
                                        minChars: 3,
                                        dataType: 'text',
                                        type: 'POST',
                                        paramName: 'data',
                                        params: {
                                            'data': $('#prof').val()
                                        },
                                        onSelect: function(suggestion){
                                            var d = JSON.parse(suggestion.data)
                                            $('#id_profesor').val(d.id)
                                            $('#profesor').val(d.nombre+' '+d.apellidos)
                                        }
                                    })
                                }
                            })
                        })
                    }
                }
            })
        }
        $('div.card ul li.nav-item a').on('click',function(){
            if(!$(this).hasClass('active')){
                $('#'+$('div.card ul li.nav-item a.active').attr('data-in')).hide()
                $('div.card ul li.nav-item a.active').removeClass('active')
                $(this).addClass('active')
                $('#'+$(this).attr('data-in')).animate({ opacity: "show" }, { duration: "slow" })
            }
        })
        $('#dataTableListaSecciones tbody tr td button.asignar').on('click',function(){
            var ele = $(this).parent().parent().parent()
            var id_seccion_curso = $(this).attr('data-in')
            $.confirm({
                title: 'Asignación del profesor',
                theme: 'light',
                columnClass: 'col-md-6 col-md-offset-3',
                content: '<div class="content"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12"><label>Profesor</label><input type="text" class="form-control prof" name="prof" id="prof" placeholder="Juan Perez"><input type="hidden" id="id_profesor" name="id_profesor" value="0"><input type="hidden" id="profesor"></div></div></div>',
                buttons: {
                    asignar: function(){
                        //window.location.href = '<?php echo base_url('periodos/editar/'.$periodo->id); ?>';
                        $.ajax({
                            url: '<?= base_url('profesores/asignacionProfesor') ?>',
                            type: 'POST',
                            data: {
                                id_seccion_curso: id_seccion_curso,
                                id_profesor: $('#id_profesor').val()
                            },
                            success: function(response){
                                var d = JSON.parse(response).data
                                $(ele.children()[6]).html(d.nombre+' '+d.apell_pat+' '+d.apell_mat)
                            }
                        })
                    },
                    but: {
                        text: 'Sin Profesor',
                        btnClass: 'btn btn-danger',
                        action: function(){
                            $.ajax({
                                url: '<?= base_url('profesores/asignacionProfesor') ?>',
                                type: 'POST',
                                data: {
                                    id_seccion_curso: id_seccion_curso,
                                    id_profesor: 1
                                },
                                success: function(response){
                                    $(ele.children()[6]).html('-')
                                }
                            })
                        }
                    }
                },
                contentLoaded: function(data, status, xhr){
                    //self.setContentAppend('<h2>Resultado:</h2>');
                    //this.setContentAppend('<div>Resultado:</div>');
                },
                onContentReady: function(){
                    $('#prof').autocomplete({
                        serviceUrl: '<?= base_url('profesores/getProfesoresAutocomplete') ?>',
                        minChars: 3,
                        dataType: 'text',
                        type: 'POST',
                        paramName: 'data',
                        params: {
                            'data': $('#prof').val()
                        },
                        onSelect: function(suggestion){
                            var d = JSON.parse(suggestion.data)
                            $('#id_profesor').val(d.id)
                            $('#profesor').val(d.nombre+' '+d.apellidos)
                        }
                    })
                }
            })
        })
        $('#especialidad').on('change',function(){
            $('#turno').val('Seleccione Turno')
            tseccion.fnClearTable()
            tseccion.fnDraw()
            t.fnClearTable()
            t.fnDraw()
        })
        $('#turno').on('change',function(){
            if($(this).val() == 'Seleccione Turno')
                return false
            if($('#especialidad').val == 'Seleccione Especialidad'){
                $.alert('Seleccione una especialidad')
                return false
            }
            t.fnClearTable()
            t.fnDraw()
            tseccion.fnClearTable()
            tseccion.fnDraw()
            $.confirm({
                title: 'Cargando cursos',
                theme: 'light',
                columnClass: 'col-md-8 col-md-offset-2',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url("periodos/cursosForSections") ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            id_especialidad: $('#especialidad').val(),
                            id_periodo: $('#id_periodo').val(),
                            id_turno: $('#turno').val()
                        }
                    }).done(function(response){
                        if(response.status==200){
                            var cursos = response.data.cursos
                            if(cursos.length == 0){
                                self.close();
                                $.alert('Secciones ya creadas o no se encuentran cursos en el plan de estudios')
                                return false
                            }
                            $('#id_especialidad_periodo').val(response.data.id_especialidad_periodo)
                            t.fnClearTable()
                            t.fnDraw()
                            for(var i in cursos)
                                t.fnAddData([
                                    cursos[i].nombre_ciclo,
                                    cursos[i].codigo_curso+' - '+cursos[i].curso,
                                    cursos[i].promocion,
                                    ''
                                    ])
                            self.close()
                            $('#generar').removeAttr('disabled')
                        }else{
                            self.setContentAppend('Secciones ya creada o no se encuentran cursos en el plan de estudios')
                        }
                    })
                },
            })
        })
        $('#generar').on('click',function(){
            $.confirm({
                title: 'Atención',
                content: 'Esta seguro de generar secciones?',
                buttons: {
                    si: function(){
                        if($('#turno').val() == 'Seleccione Turno'){
                            $.alert({
                                title: 'Atención',
                                content: 'Seleccione un Turno valido',
                                type: 'red',
                                typeAnimated: true
                            })
                            return false
                        }
                        var lista_cursos = t.api().rows()
                        tseccion.fnClearTable()
                        tseccion.fnDraw()
                        for (var i = 0; i < lista_cursos[0].length; i++) {
                            var row = lista_cursos.rows(i).data()
                            var codigo = row[0][1].split(' - ')[0]
                            var promocion = row[0][2]
                            $.ajax({
                                url: '<?= base_url('periodos/nuevaSeccion') ?>',
                                type: 'POST',
                                data: {
                                    especialidad: $('#especialidad').val(),
                                    curso: codigo,
                                    id_periodo: $('#id_periodo').val(),
                                    turno: $('#turno').val(),
                                    promocion: promocion
                                },
                                success: function(response){
                                    var resp = JSON.parse(response).data
                                    var curso = resp.curso
                                    var sec = resp.seccion
                                    tseccion.fnAddData([
                                        sec.codigo_seccion,
                                        curso.codigo+' - '+curso.nombre,
                                        ''
                                        ])
                                    $('#generar').attr('disabled',true)
                                }
                            })
                        }
                        $.ajax({
                            url: '<?= base_url('periodos/activaPeriodo') ?>',
                            type: 'POST',
                            data: {
                                id_periodo: $('#id_periodo').val(),
                                especialidad: $('#especialidad').val(),
                                turno: $('#turno').val()
                            }
                        })
                    },
                    no: function(){}
                }
            })
        })
	})
</script>