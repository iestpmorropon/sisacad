<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Gestión</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("especialidades");?> ">Gestión</a></li>
            <li ><strong>Sección</strong></li>
            <!--li class="active"><strong>Nuevo</strong></li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5>Secciones a cargo del profesor <?= $usuario['nombre'].' '.$usuario['apell_pat'].' '.$usuario['apell_mat'] ?></h5>
            <input type="hidden" name="id_profesor" value="<?= $profesor->id ?>" id="id_profesor"> 
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
	                <div class="col-lg-3 col-md-3 col-sm-3">
	            		<label class="control-label">Periodos</label>
	                    <select class="form-control " name="periodos" id="periodos">
	                    	<option>Seleccione periodo</option>
	                    	<?php foreach ($periodos as $key => $value) { ?>
	                    		<option value="<?= $value->id ?>" <?= $id_periodo == 0 ? '' : ( $id_periodo == $value->id ? 'selected' : '') ?> ><?= $value->nombre ?></option>
	                    	<?php } ?>
	                    </select>
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
                                    <th>Cod. de Sección</th>
                                    <th>Especialidad</th>
                                    <th>Curso</th>
                                    <th>Ciclo</th>
                                    <th>Periodo</th>
                                    <th>Turno</th>
                                    <th>Estado</th>
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
        $('[data-toggle="tooltip"]').tooltip()
		var t = $('#dataTable').dataTable({
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
          "columns": [
            { "width": "7%" },
            { "width": "20%" },
            { "width": "20%" },
            { "width": "7%" },
            { "width": "8%" },
            { "width": "8%" },
            { "width": "8%" },
            { "width": "7%" }
          ]
        })
        var actualiza_funcionalidad = function(){
            //funcionalidad para agregar un item
            $('#tablaCapacidades tbody tr td button.agrega_item').unbind('click')
            $('#tablaCapacidades tbody tr td button.agrega_item').on('click',function(){
                var id = $(this).attr('data-in')
                $.confirm({
                    title: 'Agrega Item',
                    columnClass: 'col-md-6 col-md-offset-3',
                    content: '<textarea class="form-control" id="item_new" placeholder="Nuevo a item..."></textarea>',
                    buttons: {
                        guardar: {
                            text: '<i class="fa fa-save"></i> Guardar',
                            action: function(){
                                $.ajax({
                                    url: '<?= base_url('cursos/newItemCapacidad') ?>',
                                    type: 'POST',
                                    data: {
                                        contenido: $('#item_new').val(),
                                        id_capacidad: id
                                    },
                                    success: function(response){
                                        if(JSON.parse(response).status==200){
                                            var d = JSON.parse(response).data
                                            $('#tablaCapacidades tbody').append('<tr>'+
                                                        '<td colspan="2"></td><td>'+d.nombre+'</td><td><div class="btn-group">'+
                                                        '<button type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>'+
                                                        '</div></td></tr>')
                                        }
                                        else{
                                            $.alert(JSON.parse(response).message)
                                        }
                                    }
                                })
                            }
                        },
                        cancelar: {
                            text: '<i class="fa fa-close"></i> Cancelar',
                            action: function(){}
                        }
                    }
                })
            })
            
            //agregando funcion para editar las capacidades
            $('#tablaCapacidades button.editar_capacidad').unbind('click')
            $('#tablaCapacidades button.editar_capacidad').on('click',function(){
                var id = $(this).attr('data-in')
                var tr = $(this).parent().parent().parent()
                $.confirm({
                    title: 'Editar Capacidad',
                    content: function(){
                        var self1 = this
                        return $.ajax({
                            url: '<?= base_url('cursos/getCapacidad') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_capacidad: id
                            },
                            success: function(response){
                                if(response.status == 200){
                                    self1.setContentAppend('<input type="text" class="form-control capacity" name="capacidad" data-id="'+response.data.id+'" id="capacidad_new" value="'+response.data.nombre+'">')
                                }
                                else{
                                    self1.setContentAppend('Error consulte con su administrador')
                                }
                            }
                        })
                    },
                    buttons: {
                        ok: function(){
                            var self1 = this
                            var name = this.$content.find('.capacity').val();
                            var id_capacidad = this.$content.find('.capacity').attr('data-id')
                            $.confirm({
                                title: 'Editando Capacidad',
                                content: function(){
                                    var self2 = this
                                    return $.ajax({
                                        url: '<?= base_url('cursos/editarCapacidad') ?>',
                                        method: 'POST',
                                        dataType: 'json',
                                        data: {
                                            name: name,
                                            id_capacidad: id_capacidad
                                        },
                                        success: function(response){
                                            if(response.status == 200){
                                                self2.close()
                                                self1.close()
                                                $(tr.children()[1]).html(name)
                                            }else{
                                                self2.setContentAppend('Error consulte con su administrador')
                                            }
                                        }
                                    }).fail(function(){
                                        self2.setContentAppend('Error consulte con su administrador')
                                    })
                                }
                            })
                            return false
                        }
                    }
                })
            })
            
            //agregando funcion para editar los items
            $('#tablaCapacidades button.editar_item').unbind('click')
            $('#tablaCapacidades button.editar_item').on('click',function(){
                var id = $(this).attr('data-in')
                var tr = $(this).parent().parent().parent()
                $.confirm({
                    title: 'Editando Item',
                    content: function(){
                        var self1 = this
                        return $.ajax({
                            url: '<?= base_url('cursos/getItemCapacidad') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_item: id
                            },
                            success: function(response){
                                if(response.status == 200){
                                    self1.setContentAppend('<textarea class="form-control item_edit" placeholder="Nuevo a item..." data-id="'+response.data.id+'">'+response.data.contenido+'</textarea>')
                                }
                                else{
                                    self1.setContentAppend('Error consulte con su administrador')
                                }
                            }
                        })
                    },
                    buttons: {
                        ok: function(){
                            var self1 = this
                            var name = this.$content.find('.item_edit').val();
                            var id_item = this.$content.find('.item_edit').attr('data-id')
                            $.confirm({
                                title: 'Editando Capacidad',
                                content: function(){
                                    var self2 = this
                                    return $.ajax({
                                        url: '<?= base_url('cursos/editarItemCapacidad') ?>',
                                        method: 'POST',
                                        dataType: 'json',
                                        data: {
                                            contenido: name,
                                            id_item: id_item
                                        },
                                        success: function(response){
                                            if(response.status == 200){
                                                self2.close()
                                                self1.close()
                                                $(tr.children()[1]).html(name)
                                            }else{
                                                self2.setContentAppend('Error consulte con su administrador')
                                            }
                                        }
                                    }).fail(function(){
                                        self2.setContentAppend('Error consulte con su administrador')
                                    })
                                }
                            })
                            return false
                        }
                    }
                })
            })
        }
        var carga_especialidades = function(){
            t.fnClearTable()
            t.fnDraw()
            $.ajax({
                    url: '<?= base_url('periodos/traeSeccionesForProfesor') ?>',
                    type: 'POST',
                    data: {
                            id_profesor: $('#id_profesor').val(),
                            id_periodo: $('#periodos').val()
                    },
                    success: function(response){
                            if(JSON.parse(response).status==200){
                                    var res = JSON.parse(response).data
                                    for(var i in res){
                                        var sts = 'Abierto'
                                        switch(parseInt(res[i].estado_seccion)){
                                            case 0: sts = 'En curso'; break;
                                            case 1: sts = 'Cerrado Nota Regular'; break;
                                            case 2: sts = 'Cerrado Nota Recuperación'; break;
                                            case 3: sts = 'Cerrado Final'; break;
                                            default: sts = 'Desconocido'; break;
                                        }
                                        var opciones = '<div class="btn-group">'
                                        if(parseInt(res[i].estado_seccion) != 0)
                                            opciones += '<a class="btn btn-primary" href="<?= base_url('cursos/seccion/') ?>'+res[i].seccion+'/'+res[i].id_seccion_curso+'/'+res[i].id_curso+'" data-toggle="tooltip" data-placement="top" title="Lista de alumnos"><i class="fas fa-users"></i></a>'
                                        else
                                            opciones += '<a class="btn btn-primary" href="<?= base_url('cursos/seccionnotas/') ?>'+res[i].seccion+'/'+res[i].id_seccion_curso+'/'+res[i].id_curso+'" data-toggle="tooltip" data-placement="top" title="Ingresar Notas"><i class="fas fa-th"></i></a>'
                                        if(parseInt(res[i].estado_seccion) == 2 || parseInt(res[i].estado_seccion) == 3)
                                            opciones += '<a class="btn btn-warning" target="_blank" href="<?= base_url('cursos/imprimiractacurso/') ?>'+res[i].id_seccion_curso+'" data-toggle="tooltip" data-placement="top" title="Imprimir Acta"><i class="fa fa-print"></i></a>'
                                        opciones += '<button class="btn btn-success capacidades" type="button" data-in="'+res[i].id_seccion_curso+'" data-toggle="tooltip" data-placement="top" title="Gestionar capacidades"><i class="fa fa-cog"></i></button>'
                                        opciones += '</div>'
                                            t.fnAddData([
                                                            res[i].seccion,
                                                            res[i].especialidad,
                                                            res[i].codigo_curso+' - '+res[i].curso,
                                                            res[i].ciclo,
                                                            $('#periodos option:selected').text(),
                                                            res[i].turno,
                                                            sts,
                                                            opciones
                                                    ])

                                            $('[data-toggle="tooltip"]').tooltip()
                                            $('#dataTable tbody tr td button.capacidades').unbind('click')
                                            $('#dataTable tbody tr td button.capacidades').on('click',function(){
                                                var butt = this
                                                var id = $(this).attr('data-in')
                                                $.confirm({
                                                    title: 'Gestion de capacidades',
                                                    columnClass: 'col-md-12',
                                                    content: function(){
                                                        var self = this
                                                        return $.ajax({
                                                            url: '<?= base_url('cursos/getCapacidadesAndItems') ?>',
                                                            method: 'POST',
                                                            dataType: 'json',
                                                            data: {
                                                                id_seccion_curso: id
                                                            },
                                                            success: function(response){
                                                                self.setContentAppend('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12">'+
                                                                        '<h4>Lista de Capacidades<button type="button" id="importar" data-in="'+id+'" class="btn btn-success pull-right"><i class="fa fa-upload"></i> Importar</button>'+
                                                                        '<button type="button" id="agregar_capacidad" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Capacidad</button></h4></div>'+
                                                                        '</div><table class="table table-striped table-bordered table-hover" id="tablaCapacidades">'+
                                                                        '<thead><tr><th>#</th><th>Capacidades</th><th>Items</th><th>Opciones</th></tr></thead>'+
                                                                        '<tbody>')
                                                                if(response.status == 200){
                                                                    var d = response.data
                                                                    for(var i in d){
                                                                        var c = d[i].capacidad
                                                                        self.setContentAppend('<tr data-id="'+c.id+'" data-in="'+(parseInt(i)+1)+'"><td>'+(parseInt(i)+1)+'</td>'+
                                                                                '<td>'+c.nombre+'</td><td></td><td><div class="btn-group">'+
                                                                                '<button type="button" class="btn btn-success editar_capacidad" data-in="'+c.id+'"><i class="fa fa-edit"></i></button>'+
                                                                                '<button type="button" class="btn btn-primary agrega_item" data-in="'+c.id+'"><i class="fa fa-plus"></i></button>'+
                                                                                '</div></td></tr>')
                                                                        var items = d[i].items
                                                                        for(var a in items){
                                                                            self.setContentAppend('<tr data-id="'+items[a].id+'"><td colspan="2"></td><td>'+items[a].contenido+'</td><td><div class="btn-group">'+
                                                                                '<button type="button" class="btn btn-success editar_item" data-in="'+items[a].id+'"><i class="fa fa-edit"></i></button>'+
                                                                                '<button type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>'+
                                                                                '</div></td></tr>')
                                                                        }
                                                                    }
                                                                }
                                                                else{
                                                                    self.setContentAppend('<tr colspan="4"><td>Sin Capacidades</td></tr>')
                                                                }
                                                                self.setContentAppend('</tbody></table>')
                                                            }
                                                        })
                                                    },
                                                    contentLoaded: function(data, status, xhr){},
                                                    onContentReady: function(){
                                                        var self = this
                                                        actualiza_funcionalidad()
                                                        $('#importar').unbind('click')
                                                        $('#importar').on('click',function(){
                                                            var id_seccion_nuevo = $(this).attr('data-in')
                                                            $.confirm({
                                                                title: 'Lista de cursos',
                                                                content: function(){
                                                                    var self1 = this
                                                                    return $.ajax({
                                                                        url: '<?= base_url('periodos/traeSeccionesForProfesor') ?>',
                                                                        method: 'POST',
                                                                        dataType: 'json',
                                                                        data: {
                                                                            id_profesor: $('#id_profesor').val(),
                                                                            id_periodo: $('#periodos').val()
                                                                        }
                                                                    }).done(function(response){
                                                                        console.log(response)
                                                                        var d = response.data
                                                                        var opciones = ''
                                                                        for(var i in d)
                                                                            opciones += '<option value="'+d[i].id_seccion_curso+'">'+d[i].curso+'</option>'
                                                                        self1.setContentAppend('<h4>Seleccione el curso que quiere importar</h4>')
                                                                        self1.setContentAppend('<select class="form-control lista_cursos" id="lista_cursos">'+opciones+'</select>')
                                                                    })
                                                                },
                                                                buttons: {
                                                                    importar: {
                                                                        text: 'Importar',
                                                                        btnClass: 'btn btn-success',
                                                                        action: function(){
                                                                            $.confirm({
                                                                                title: 'Importanto capacidades',
                                                                                content: function(){
                                                                                    var self2 = this
                                                                                    var id = $('#lista_cursos').val()
                                                                                    console.log(id)
                                                                                    return $.ajax({
                                                                                        url: '<?= base_url('cursos/importarCapacidades') ?>',
                                                                                        method: 'POST',
                                                                                        dataType: 'json',
                                                                                        data: {
                                                                                            //var curso = self2.$content.find('.lista_cursos').val();
                                                                                            id_seccion_curso: id,
                                                                                            id_seccion_nuevo: id_seccion_nuevo
                                                                                        }
                                                                                    }).done(function(response){
                                                                                        self2.close()
                                                                                        self.close()
                                                                                        $(butt).trigger('click')
                                                                                        console.log(response)
                                                                                    })
                                                                                }
                                                                            })
                                                                        }
                                                                    },
                                                                    cancelar: function(){}
                                                                }
                                                            })
                                                        })
                                                        $('#agregar_capacidad').unbind('click')
                                                        $('#agregar_capacidad').on('click',function(){
                                                            $.confirm({
                                                                title: 'Nueva Capacidad',
                                                                content: '<input type="text" class="form-control" name="capacidad" id="capacidad_new">',
                                                                buttons: {
                                                                    guardar: {
                                                                        text: '<i class="fa fa-save"></i> Guardar',
                                                                        keys: ['+'],
                                                                        action: function(){
                                                                            $.ajax({
                                                                                url: '<?= base_url('cursos/newCapacidad') ?>',
                                                                                type: 'POST',
                                                                                data: {
                                                                                    capacidad: $('#capacidad_new').val(),
                                                                                    id_seccion_curso: id
                                                                                },
                                                                                success: function(response){
                                                                                    if(JSON.parse(response).status == 200){
                                                                                        var d = JSON.parse(response).data
                                                                                        var cont = 0
                                                                                        console.log($('#tablaCapacidades tbody tr:first').attr('data-in'))
                                                                                        if(!$('#tablaCapacidades tbody tr').eq( 0 ).attr('data-in')){
                                                                                            $('#tablaCapacidades tbody').html('')
                                                                                            cont = 1
                                                                                        }
                                                                                        else{
                                                                                            cont = parseInt($('#tablaCapacidades tbody tr').length)+1
                                                                                        }
                                                                                        $('#tablaCapacidades tbody').append('<tr data-in="'+cont+'"><td>'+cont+'</td>'+
                                                                                                '<td>'+d.nombre+'</td><td></td><td><div class="btn-group">'+
                                                                                                '<button type="button" class="btn btn-primary agrega_item" data-in="'+d.id+'"><i class="fa fa-plus"></i></button>'+
                                                                                                '</div></td></tr>')
                                                                                        actualiza_funcionalidad()
                                                                                    }
                                                                                    else{
                                                                                        $.alert(JSON.parse(response).message)
                                                                                    }
                                                                                }
                                                                            })
                                                                        }
                                                                    },
                                                                    canelar: {
                                                                        text: '<i class="fa fa-window-close"></i> Cancelar',
                                                                        keys: ['esc'],
                                                                        action: function(){}
                                                                    }
                                                                }
                                                            })
                                                        })
                                                    },
                                                    buttons: {
                                                        ok: function(){}
                                                    }
                                                })
                                            })
                                    }
                            }
                    }
            })
        }
		$('#periodos').on('change',function(){
                    if($('#periodos').val() === 'Seleccione periodo'){
                        return false
                    }
			carga_especialidades()
		})
                if($('#periodos').val() != 'Seleccione periodo' ){
                    carga_especialidades()
                }
	})
</script>