<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Gestion Interna</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Gestion</a></li>
            <li class="active"><strong>Profesores</strong></li>
            <!--li>Internos</li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<!--div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox " id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5>Registro de Profesores</h5> 
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="ibox-content m-b-sm border-bottom" >
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
            <form name="form-register-periodo" id="form-register-periodo" class="form-horizontal"  >
                <div class="form-group">
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Especialidades</label>
                        <select class="form-control" name="especialidades" id="especialidades">
                            <option>Seleccione Especialidad</option>
                            <?php if($especialidades) foreach ($especialidades as $key => $value) { ?>
                                <option value="<?= $value->id ?>"><?= $value->codigo.' - '.$value->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                        <label>Aepllidos y Nombres</label>
                        <input type="text" name="apell_nom" class="form-control" placeholder="Perez, Juan">
                    </div>
                    <div class="col-sm-1 col-lg-1 col-md-1 col-xs-12">
                        <label>&nbsp;</label><br>
                        <button class="btn btn-primary" type="button" id="buscar"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                    <div class="col-sm-1 col-lg-1 col-md-1 col-xs-12">
                        <label>&nbsp;</label><br>
                        <a href="<?= base_url('profesores/nuevo') ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div-->
<div class="wrapper wrapper-content">
    <div class="ibox-title bg-primary">
        <h5>Lista de Profesores</h5> 
        <div class="ibox-tools">
        <a href="<?= base_url('profesores/nuevo') ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo profesor</a>
            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="ibox-content m-b-sm border-bottom" >
        <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
        </div>
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
                                        <th>Usuario</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!is_numeric($profesores)) foreach ($profesores as $key => $value) { ?>
                                        <tr>
                                            <td><?= $value->codigo ?></td>
                                            <td><?= $value->nombre.' '.$value->apell_pat.' '.$value->apell_mat ?></td>
                                            <td><?= $value->usuario ?></td>
                                            <td><?php 
                                            switch ($value->estado) {
                                                 case 0:
                                                     echo 'Inhabilitado';
                                                     break;
                                                 case 1:
                                                     echo 'Habilitado';
                                                     break;
                                                 
                                                 default:
                                                     # code...
                                                     break;
                                             } ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= base_url('profesores/editar/'.$value->id_profesor) ?>" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                                    <?php if(isset($value->jefe)){ ?>
                                                        <button class="btn btn-danger quita" data-id-profesor="<?= $value->id_profesor ?>" data-id-jefe="<?= $value->jefe->id ?>" data-id-usuario="<?= $value->id_usuario ?>" type="button" data-toggle="tooltip" data-placement="right" title="Quitar de jefe de area"><i class="fa fa-window-close"></i></button>
                                                    <?php }else{ ?>
                                                        <button class="btn btn-success asignar" data-id-profesor="<?= $value->id_profesor ?>" data-id-usuario="<?= $value->id_usuario ?>" type="button" data-toggle="tooltip" data-placement="top" title="Asignar jefe de area"><i class="fa fa-chalkboard-teacher"></i></button>
                                                    <?php } ?>
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
</div>
<script type="text/javascript">
    $(function(){
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
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

        $('table tbody tr td button.quita').on('click',function(){
            var id_profesor = $(this).attr('data-id-profesor')
            var id_jefe = $(this).attr('data-id-jefe')
            var id_usuario = $(this).attr('data-id-usuario')
            $.confirm({
                title: 'Atención',
                content: 'Esta seguro de quitar al profesor como jefe de area?',
                buttons: {
                    si: function(){
                        $.confirm({
                            title: 'Quitando',
                            content: function(){
                                var self = this
                                return $.ajax({
                                    url: '<?= base_url('profesores/setQuitarAsignacion') ?>',
                                    method: 'POST',
                                    dataType: 'json',
                                    data: {
                                        id_profesor: id_profesor,
                                        id_jefe: id_jefe,
                                        id_usuario: id_usuario
                                    }
                                }).done(function(response){
                                    toastr.success(response.message)
                                        self.close()
                                        setTimeout(function(){
                                            window.location.reload()
                                        },1000)
                                }).fail(function(){
                                    self.close()
                                    toastr.error('Error consulte con su administrador')
                                })
                            }
                        })
                    },
                    no: function(){}
                }
            })
        })

        $('table tbody tr td button.asignar').on('click',function(){
            var id_profesor = $(this).attr('data-id-profesor')
            var id_usuario = $(this).attr('data-id-usuario')
            $.confirm({
                title: 'Asignar profesor',
                columnClass: 'col-md-6 col-md-offset-3',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url('profesores/preparaAsignacion') ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            id_profesor: id_profesor,
                            id_usuario: id_usuario
                        }
                    }).done(function(response){
                        //self.close()
                        var esp = response.data.especialidades
                        self.setContentAppend('<div class="content"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12"><label>Especialidad</label><select class="form-control especialidad" name="especialidad">')
                        var cad = ''
                        for(var i in esp){
                            cad += '<option value="'+esp[i].id+'">'+esp[i].nombre+'</option>'
                        }
                        self.setContentAppend(cad+'</select"></div></div></div>')
                    }).fail(function(){
                        self.close()
                        toastr.error('Error consulte con su administrador')
                    })
                },
                buttons: {
                    asignar: {
                        text: 'Asignar',
                        btnClass: 'btn-primary',
                        action: function(){
                            var id_especialidad = this.$content.find('.especialidad').val()
                            $.confirm({
                                title: 'Consignando',
                                content: function(){
                                    var self1 = this
                                    return $.ajax({
                                        url: '<?= base_url('profesores/setAsignar') ?>',
                                        method: 'POST',
                                        dataType: 'json',
                                        data: {
                                            id_profesor: id_profesor,
                                            id_especialidad: id_especialidad,
                                            id_usuario: id_usuario
                                        }
                                    }).done(function(response){
                                        toastr.success(response.message)
                                        self1.close()
                                        setTimeout(function(){
                                            window.location.reload()
                                        },1000)
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
</script>