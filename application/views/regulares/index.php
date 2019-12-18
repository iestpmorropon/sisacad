<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Regulares</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Regulares</a></li>
            <li class="active"><strong>Actas</strong></li>
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
                        <a class="nav-link" data-toggle="tab" href="#alumnos"><i class="fa fa-copy"></i> Actas Regulares</a>
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
                                            <div class="col-sm-2">
                                                <label>Periodo </label>
                                                <select class="form-control" name="perido" id="periodo">
                                                    <?php foreach ($periodos as $key => $value) { ?>
                                                         <option value="<?= $value->id ?>"><?= $value->nombre ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="control-label">Turno</label>
                                                <select class="form-control " name="especialidad" id="turno">
                                                    <option>Seleccione turno</option>
                                                    <option value="1">Vespertino</option>
                                                    <!--<option value="2">Nocturno</option>-->
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="control-label">Especialidad</label>
                                                <select class="form-control chosen-select" name="especialidad" id="especialidad">
                                                    <option>Seleccione especialidad</option>
                                                </select>
                                                <span id="result" style="display: none; color: #F00;"></span>
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="control-label">Ciclo</label>
                                                <select class="form-control " name="ciclo" id="ciclo">
                                                    <option>Seleccione ciclo</option>
                                                    <option value="1">Ciclo 1</option>
                                                    <option value="2">Ciclo 2</option>
                                                    <option value="3">Ciclo 3</option>
                                                    <option value="4">Ciclo 4</option>
                                                    <option value="5">Ciclo 5</option>
                                                    <option value="6">Ciclo 6</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <label>&nbsp;</label><br>
                                                <button type="button" class="btn btn-primary" id="buscar" data-toggle="tooltip" data-placement="top" title="Buscar"><i class="fa fa-search"></i> Buscar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
                                        <th>Apellidos y Nombres</th>
                                        <th>DNI</th>
                                        <th>Cod. Curso</th>
                                        <th>Curso</th>
                                        <th>Nota</th>
                                        <th>Estado</th>
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
<script type="text/javascript">
    $(function(){

        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
        $(".chosen-select").chosen()
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
        $('[data-toggle="tooltip"]').tooltip()
        var periodos = []
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
            "info": '',
            "lengthMenu": "Desplegando _MENU_ Registros",
            "search": "Busqueda"
          },
          /*"columns": [
            { "width": "10%" },
            { "width": "40%" },
            { "width": "15%" },
            { "width": "15%" },
            { "width": "15%" },
            { "width": "5%" }
          ],*/
          bFilter: true, 
          //bInfo: true
        })
        $('#periodo').on('change',function(){
            $('#especialidad').html('<option>Seleccione especialidad</option>')
            $('#turno').val('Seleccione turno')
            $('#ciclo').val('Seleccione ciclo')
            $(".chosen-select").trigger('chosen:updated')
        })
        $('#turno').on('change',function(){
            $('#especialidad').html('<option>Seleccione especialidad</option>')
            //$('#turno').html('<option>Seleccione turno</option>')
            $('#ciclo').html('<option>Seleccione ciclo</option>')
            $.ajax({
                url: '<?= base_url('alumnos/getEspecialidades') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id_periodo: $('#periodo').val(),
                    id_turno: $('#turno').val()
                }
            }).done(function(response){
                //console.log(response)
                if(response.status == 200){
                    var d = response.data
                    for(var i in d){
                        $('#especialidad').append('<option value="'+d[i].id_especialidad+'">'+d[i].especialidad+'</option>')
                        //console.log(d[i])
                    }
                    $(".chosen-select").trigger('chosen:updated')
                }else{
                    toastr.error('Consulte con su administrador')
                }
            }).fail(function(){
                toastr.error('Existe un error al consultar especialidades')
            })
        })
        $('#especialidad').on('change',function(){
            $('#ciclo').html('<option>Seleccione ciclo</option>')
            $.ajax({
                url: '<?= base_url('alumnos/getCiclosEspecialidadPeriodo') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id_periodo: $('#periodo').val(),
                    id_turno: $('#turno').val(),
                    id_especialidad: $('#especialidad').val()
                }
            }).done(function(response){
                if(response.status == 200){
                    var d = response.data
                    for(var i in d){
                        $('#ciclo').append('<option value="'+d[i].id_ciclo+'">Ciclo '+d[i].id_ciclo+'</option>')
                    }
                }
                else{
                    toastr.error('Consulte con su administrador')
                }
            }).fail(function(){
                toastr.error('Existe un error consulte con su administrador')
            })
        })
        var actualizarFuncionalidades = function(){
            $('#dataTable tbody tr td button.editar_nota').on('click',function(){
                var id = $(this).attr('data-id')
                $.confirm({
                    title: 'Editar Evaluacion',
                    columnClass: 'col-md-8 col-lg-8 col-sm-8 col-lg-offset-2 col-md-offset-2 col-lg-offset-2 col-sm-offset-2',
                    content: function(){
                        var self = this
                        return $.ajax({
                            url: 'regulares/getInfoAlumnoCurso',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id: id
                            }
                        }).done(function(response){
                            var ciclos_romanos = ['','I','II','III','IV','V','VI']
                            if(response.status == 200){
                                var d = response.data
                                var string = ''
                                if(d.tipo_eval == 1)
                                    string = '<input type="number" class="form-control nota_nueva" name="nota_nueva" placeholder="00">'
                                else
                                    string = '<select class="form-control nota_nueva" name="nota_nueva"><option></option><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option></select>'
                                self.setContentAppend('<div class="row"><input type="hidden" class="id_alumno_matricula_curso" value="'+id+'"><h2 class="text-center"><i class="fa fa-user"></i>'+d.apell_pat+' '+d.apell_mat+' '+d.nombre+'<h2></div><div class="row"><div class="col-lg-3 col-md-3 col-sm-3">Especialidad</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+d.especialidad+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Periodo</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+d.periodo+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Turno</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+d.turno+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Ciclo</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+ciclos_romanos[d.id_ciclo]+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Código Curso</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+d.codigo+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Nombre Curso</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+d.curso+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Nota Actual</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+d.valor_nota+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Nota Nueva</div><div class="col-lg-3 col-md-3 col-sm-3">'+string+'<input type="hidden" class="eval_minima" value="'+d.eval_minima+'"></div></div>')
                            }
                            else{
                                self.setContentAppend(response.message)
                            }
                        }).fail(function(){
                            self.close()
                            toastr.error('Error consulte con su administrador')
                        })
                    },
                    buttons: {
                        editar: {
                            text: 'Editar',
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            action: function(){
                                var nota_nueva = this.$content.find('.nota_nueva').val()
                                var eval_minima = this.$content.find('.eval_minima').val()
                                if(nota_nueva == ''){
                                    toastr.error('Ingrese un dato valido')
                                    return false
                                }
                                var id = this.$content.find('.id_alumno_matricula_curso').val()
                                $.confirm({
                                    title: 'editando',
                                    content: function(){
                                        var self1 = this
                                        return $.ajax({
                                            url: 'regulares/actualizarNota',
                                            method: 'POST',
                                            dataType: 'json',
                                            data: {
                                                id: id,
                                                nota_nueva: nota_nueva,
                                                eval_minima: eval_minima
                                            }
                                        }).done(function(response){
                                            self1.close()
                                            toastr.success('Actualizacion completa')
                                        }).fail(function(){
                                            self1.close()
                                            toastr.error('Error consulte con su administrador')
                                        })
                                    }
                                })
                            }
                        },
                        cancelar: function(){}
                    }
                })
            })
            t.on('draw.dt',function(){
                $('#dataTable tbody tr td button.editar_nota').unbind('click')
                $('#dataTable tbody tr td button.editar_nota').on('click',function(){
                    var id = $(this).attr('data-id')
                    $.confirm({
                        title: 'Editar Evaluacion',
                        columnClass: 'col-md-8 col-lg-8 col-sm-8 col-lg-offset-2 col-md-offset-2 col-lg-offset-2 col-sm-offset-2',
                        content: function(){
                            var self = this
                            return $.ajax({
                                url: 'regulares/getInfoAlumnoCurso',
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    id: id
                                }
                            }).done(function(response){
                                var ciclos_romanos = ['','I','II','III','IV','V','VI']
                                if(response.status == 200){
                                    var d = response.data
                                    var string = ''
                                    if(d.tipo_eval == 1)
                                        string = '<input type="number" class="form-control nota_nueva" name="nota_nueva" placeholder="00">'
                                    else
                                        string = '<select class="form-control nota_nueva" name="nota_nueva"><option></option><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option></select>'
                                    self.setContentAppend('<div class="row"><input type="hidden" class="id_alumno_matricula_curso" value="'+id+'"><h2 class="text-center"><i class="fa fa-user"></i>'+d.apell_pat+' '+d.apell_mat+' '+d.nombre+'<h2></div><div class="row"><div class="col-lg-3 col-md-3 col-sm-3">Especialidad</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+d.especialidad+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Periodo</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+d.periodo+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Turno</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+d.turno+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Ciclo</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+ciclos_romanos[d.id_ciclo]+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Código Curso</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+d.codigo+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Nombre Curso</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+d.curso+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Nota Actual</div><div class="col-lg-9 col-md-9 col-sm-9"><label>'+d.valor_nota+'</label></div><div class="col-lg-3 col-md-3 col-sm-3">Nota Nueva</div><div class="col-lg-3 col-md-3 col-sm-3">'+string+'<input type="hidden" class="eval_minima" value="'+d.eval_minima+'"></div></div>')
                                }
                                else{
                                    self.setContentAppend(response.message)
                                }
                            }).fail(function(){
                                self.close()
                                toastr.error('Error consulte con su administrador')
                            })
                        },
                        buttons: {
                            editar: {
                                text: 'Editar',
                                btnClass: 'btn-primary',
                                keys: ['enter'],
                                action: function(){
                                    var nota_nueva = this.$content.find('.nota_nueva').val()
                                    var eval_minima = this.$content.find('.eval_minima').val()
                                    if(nota_nueva == ''){
                                        toastr.error('Ingrese un dato valido')
                                        return false
                                    }
                                    var id = this.$content.find('.id_alumno_matricula_curso').val()
                                    $.confirm({
                                        title: 'editando',
                                        content: function(){
                                            var self1 = this
                                            return $.ajax({
                                                url: 'regulares/actualizarNota',
                                                method: 'POST',
                                                dataType: 'json',
                                                data: {
                                                    id: id,
                                                    nota_nueva: nota_nueva,
                                                    eval_minima: eval_minima
                                                }
                                            }).done(function(response){
                                                self1.close()
                                                toastr.success('Actualizacion completa')
                                            }).fail(function(){
                                                self1.close()
                                                toastr.error('Error consulte con su administrador')
                                            })
                                        }
                                    })
                                }
                            },
                            cancelar: function(){}
                        }
                    })
                })
            })
        }
        $('#buscar').on('click',function(){
            if($('#especialidad').val() == 'Seleccione especialidad' || $('#turno').val() == 'Seleccione turno' || $('#ciclo').val() == 'Seleccione ciclo' || $('#periodo').val() == 'Seleccione periodo'){
                //$('#result').html('Seleccione los valores a consultar')
                ///setTimeout(function(){ $('#result').html('') },3000)
                toastr.error('Seleccione todos los campos validos para realizar la consulta')
                return false
            }
            $.confirm({
                title: 'Buscando',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?=  base_url('regulares/getNotaAlumnoCurso')  ?>',
                        dataType: 'json',
                        method: 'POST',
                        data: {
                            id_turno: $('#turno').val() == 'Seleccione turno' ? 0 : $('#turno').val(),
                            id_especialidad: $('#especialidad').val() == 'Seleccione especialidad' ? 0 : $('#especialidad').val(),
                            id_periodo: $('#periodo').val() == 'Seleccione periodo' ? 0 : $('#periodo').val(),
                            id_ciclo: $('#ciclo').val() == 'Seleccione ciclo' ? 0 : $('#ciclo').val()
                        }
                    }).done(function(response){
                        self.close()
                        if(response.status == 200){
                            var d = response.data
                            $('#tabla_alumnos').show()
                            t.fnClearTable()
                            t.fnDraw()
                            for(var i in d){
                                var status = ''
                                if(d[i].estado == 1)
                                    status = 'Aprobado'
                                else
                                    status = 'Desaprobado'
                                t.fnAddData([
                                    d[i].apell_pat+' '+d[i].apell_mat+' '+d[i].nombre,
                                    d[i].dni,
                                    d[i].codigo,
                                    d[i].curso,
                                    d[i].valor_nota,
                                    status,
                                    '<div class="btn-group"><button class="btn btn-primary editar_nota" data-id="'+d[i].id+'" type="button" data-toggle="tooltip" data-placement="top" title="Editar Nota"><i class="fa fa-edit"></i></button></div>'
                                ])
                                //periodos[d[i].id_periodo] = d[i].periodo
                            }
                            actualizarFuncionalidades()
                        }
                        else{
                            toastr.error('Error',response.message)
                        }
                    })
                }
            })
        })
    })
</script>