<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Secciones</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Secciones</a></li>
            <li class="active"><strong>Lista</strong></li>
            <!--li>Internos</li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox " id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5>Lista de Secciones</h5> 
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
                    <div class="col-sm-2">
                        <label class="control-label">Periodos</label>
                        <select class="form-control " name="periodos" id="periodos">
                            <option>Seleccione periodo</option>
                            <?php foreach ($periodos as $key => $value) { ?>
                                    <option value="<?= $value->id ?>"><?= $value->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-1 col-lg-1 col-md-1 col-xs-12">
                        <label>&nbsp;</label><br>
                        <button class="btn btn-primary" type="button" id="buscar"><i class="fa fa-search"></i> Buscar</button>
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
                                    <th>Curso</th>
                                    <th>Ciclo</th>
                                    <th>Periodo</th>
                                    <th>Turno</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
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
          "aoColumns" : [
            {sWidth: "10%"},
            {sWidth: "50%"},
            {sWidth: "10%"},
            {sWidth: "10%"},
            {sWidth: "10%"},
            {sWidth: "10%"},
            {sWidth: "10%"}
            ]
        })
        $('#buscar').on('click',function(){
            if($('#especialidades').val() == 'Seleccione Especialidad')
                return false
            if($('#periodos').val() == 'Seleccione periodo')
                return false
            t.fnClearTable()
            t.fnDraw()
            $.confirm({
                title: 'Consultando Secciones',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url('secciones/traeSecciones') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_especialidad: $('#especialidades').val(),
                            id_periodo: $('#periodos').val()
                        }
                    }).done(function(response){
                        //self.close()
                        if(response.status == 200){
                            var d = response.data
                            self.close()
                            for(var i in d){
                                var sts = 'Abierto'
                                switch(parseInt(d[i].estado)){
                                    case 0: sts = 'En curso'; break;
                                    case 1: sts = 'Cerrado Nota Regular'; break;
                                    case 2: sts = 'Cerrado Nota Recuperación'; break;
                                    case 3: sts = 'Cerrado Final'; break;
                                    default: sts = 'Cerrado'; break;
                                }
                                t.fnAddData([
                                    d[i].seccion,
                                    d[i].curso,
                                    d[i].ciclo,
                                    $('#periodos option:selected').text(),
                                    d[i].turno,
                                    sts,
                                    '<div class="btn-group"><a class="btn btn-primary" href="<?= base_url('secciones/alumnos/') ?>'+d[i].id+'" data-toggle="tooltip" data-placement="top" title="Alumnos"><i class="fa fa-users"></i></a>'+
                                            '<!--button class="btn btn-success" type="button" data-toggle="tooltip" data-placement="top" title="Detalles"><i class="fa fa-eye"></i></button--></div>'
                                ])
                            }
                            $('[data-toggle="tooltip"]').tooltip()
                        }
                        else{
                            self.setContentAppend('No hay secciones abiertas en este periodo')
                        }
                    })
                }
            })
        })
    })
</script>