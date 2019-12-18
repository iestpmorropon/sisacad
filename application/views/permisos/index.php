<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Personal</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("permisos");?> ">Gestion</a></li>
            <li class="active"><strong>Permisos</strong></li>
            <!--li ><strong>Nuevo</strong></li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-title">
            <h5>Lista de Usuarios</h5> 
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="ibox-content m-b-sm border-bottom">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
            <div class="content">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Nombres</label>
                        <!--input type="text" class="form-control" name="nombre" placeholder="Ingrese un nombre o codigo"-->
                        <input type="text" class="form-control" placeholder="Ingrese codigo, nombre o apellidos para la busqueda." id="alumno">
                        <input type="hidden" id="id_alumno_matricula" name="id_alumno_matricula" value="0">
                        <input type="hidden" id="id_alumno" value="0">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Roles</label>
                        <select class="form-control chosen-select" id="id_rol" multiple name="roles[]" data-placeholder="Seleccione al menos un rol">
                            <?php if(!is_numeric($roles)) foreach ($roles as $key => $value) if($value->id > 2){ ?>
                                <option value="<?= $value->id ?>"><?= $value->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                        <label>&nbsp;</label><br>
                        <button class="btn btn-primary" type="button" id="buscar"><i class="fa fa-search"></i> Buscar</button>
                        <a href="<?= base_url('permisos/nuevo') ?>" class="btn btn-success" data-toggle="tooltip" data-placemet="top" title="Nuevo usuario"><i class="fa fa-plus"></i> Nuevo</a>
                        <?php if($usuario['id_rol_actual'] == 1){ ?>
                            <a href="<?= base_url('permisos/gestionroles') ?>" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Gestionar Roles"><i class="fa fa-cog"></i> Roles</a>
                        <?php } ?>
                    </div>
                </div>
            </div><br><hr>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Nombres</th>
                            <th>Perfil</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <body>
                        <?php if(!is_numeric($usuarios)) foreach ($usuarios as $key => $value) { ?>
                            <tr>
                                <td><?= $value->usuario ?></td>
                                <td><?= $value->nombre.' '.$value->apell_pat.' '.$value->apell_mat ?></td>
                                <td><?= $value->estado == 1 ? 'Activo' : 'Deshabilitado' ?></td>
                                <td>
                                    <div class="btn-group">
                                        <?php if($value->estado == 1){ ?>
                                            <a href="<?= base_url('permisos/editar/').$value->id ?>" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                            <button type="button" class="btn btn-danger" data-in="<?= $value->id ?>"><i class="fa fa-trash"></i></button>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </body>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
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
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
        $('.chosen-select').chosen()
        $('#buscar').on('click',function(){
            t.fnClearTable()
            t.fnDraw()
            $.confirm({
                title: 'Buscando',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url('permisos/consulta') ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            id_rol: $('#id_rol').val()
                        }
                    }).done(function(response){
                        self.close()
                        var alus = response.data.usuarios
                        for(var i in alus){
                            var estado = ''
                            if(alus[i].estado_usuario == '1')
                                estado = 'Activo'
                            if(alus[i].estado_usuario == '2')
                                estado = 'Inhabilitado'
                            t.fnAddData([
                                alus[i].usuario,
                                alus[i].apell_pat+' '+alus[i].apell_mat+' '+alus[i].nombre,
                                alus[i].perfil,
                                estado,
                                ''
                                ])
                        }
                    }).fail(function(){
                        self.close()
                        toastr.error('Error consulte con su Administrador')
                    })
                }
            })
        })
        $('#alumno').autocomplete({
            serviceUrl: '<?= base_url('alumnos/getAlumnoAutocomplete_') ?>',
            minChars: 3,
            dataType: 'text',
            type: 'POST',
            paramName: 'data',
            params: {
              'data': $('#alumno').val()
            },
            onSelect: function(suggestion){
                var datos = JSON.parse(suggestion.data)
                t.fnClearTable()
                t.fnDraw()
                $('#autocompletecodigo').val(datos.codigo)
                console.log(suggestion)
                $.confirm({
                    title: 'Buscando',
                    content: function(){
                        var self  = this
                        $.ajax({
                            url: '<?= base_url('permisos/consulta') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_usuario: datos.id_usuario
                            }
                        }).done(function(response){
                            self.close()
                            var us = response.data.usuarios
                            for(var i in us){
                                var estado = ''
                                if(us[i].estado_usuario == '1')
                                    estado = 'Activo'
                                if(us[i].estado_usuario == '2')
                                    estado = 'Inhabilitado'
                                t.fnAddData([
                                    us[i].usuario,
                                    us[i].apell_pat+' '+us[i].apell_mat+' '+us[i].nombre,
                                    us[i].perfil,
                                    estado, 
                                    ''
                                    ])
                            }
                        }).fail(function(){
                            self.close()
                            toastr.error('Error consulte con su Administrador')
                        })
                    }
                })
            },  
            onSearchStart: function(q){},
            onSearchComplete: function(q,suggestions){}
        })
    })
</script>