<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Alumnos</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Egresados</a></li>
            <li class="active"><strong>Extras</strong></li>
            <!--li>Internos</li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5>Lista de egresados</h5> 
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <label>Plan de estudios</label>
                    <select class="form-control" name="periodo" id="periodo">
                        <?php if(!is_numeric($periodos)) foreach ($periodos as $key => $value) { ?>
                            <option value="<?= $value->id_periodo ?>"><?= $value->periodo ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <label>&nbsp;</label><br>
                    <button type="button" class="btn btn-default" id="buscar_rango"><i class="fa fa-search"></i> Buscar</button>
                </div>
            </div><br>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Cod.Alumno</th>
                                    <th>Apellidos y Nombres</th>
                                    <th>Especialidad</th>
                                    <th>Plan de Estudios</th>
                                    <th>Turno</th>
                                    <th>Cursos</th>
                                    <th>Cur. Forzados</th>
                                    <th>Actividades</th>
                                    <th>Act. Forzados</th>
                                    <th>Practicas</th>
                                    <th>Prac. Forzadas</th>
                                    <th>Egresado</th>
                                    <th>&nbsp;</th>
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
        $(".dates").daterangepicker({
            opens: 'center',
            //drops: 'down',
            //timePicker: true,
            //linkedCalendars: false,
            //startDate: moment().startOf('hour'),
            //endDate: moment().startOf('hour').add(24, 'hour'),
            showDropdowns: true,
            showWeekNumbers: true,
            //timePicker: true,
            //timePickerIncrement: 1,
            //timePicker12Hour: true,
            opens: 'right',
            drops: 'down',
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-default',
            separator: ' to ',
            locale: {
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                daysOfWeek: ['Dm', 'Ln', 'Ma', 'Mr', 'Jv', 'Vr','Sb'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                firstDay: 1,
                format: 'DD/MM hh:mm A'
            }
        });
        $('.dates').on('apply.daterangepicker', function(ev, picker) {
            $(this).val('Desde ' + picker.startDate.format('DD-MM-YYYY') + ' hasta ' + picker.endDate.format('DD-MM-YYYY'));
            $('#fechaStart').val(picker.startDate.format('DD-MM-YYYY'))
            $('#fechaEnd').val(picker.endDate.format('DD-MM-YYYY'))
        })
        var cargar_funcionalidades = function(){
            $('#dataTable tbody tr td button.editar').unbind('click')
            $('#dataTable tbody tr td button.editar').on('click',function(){
                var id = $(this).attr('data-id')
                $.confirm({
                    title: 'Modificar',
                    columnClass: 'col-md-6 col-md-offset-3',
                    content: function(){
                        var self = this
                        return $.ajax({
                            url: '<?= base_url('egresados/consultaPreparaCambio') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_alumno_especialidad: id
                            }
                        }).done(function(response){
                            if(response.status == 202){
                                self.close()
                                toastr.error(response.message)
                            }else{
                                var d = response.data
                                var string = '<hr><div class="col-lg-12 col-md-12 col-sm-12">Culmino Cursos: '+(parseInt(d.cursos) == 1 ? 'Si' : 'No')+(parseInt(d.cursos) == 1 ? '' : '</div>'+
                                    '<hr><div class="col-lg-12 col-md-12 col-sm-12"><div class="row">'+
                                        '<div class="col-lg-6 col-md-6 col-sm-6">Forzar Cursos: </div>'+
                                        '<div class="col-lg-6 col-md-6 col-sm-6"><select class="form-control" name="forzar_cursos"><option '+(parseInt(d.cursos_forzado) == 1 ? 'selected' : '')+' value="1">Si</option><option '+(parseInt(d.cursos_forzado) == 0 ? 'selected' : '')+' value="0">No</option></select></div>'+
                                        '</div><hr>')+'</div>'+
                                '<div class="col-lg-12 col-md-12 col-sm-12">'+(parseInt(d.lleva_cursos_actividades) == 1 ? (parseInt(d.actividades) == 1 ? '' : '</div><hr><div class="col-lg-12 col-md-12 col-sm-12"><div class="row"><div class="col-lg-6 col-md-6 col-sm-6">Forzar Actividades: </div><div class="col-lg-6 col-md-6 col-sm-6"><select class="form-control" name="actividades"><option '+(parseInt(d.actividades_forzado) == 1 ? 'selected' : '')+'  value="1">Si</option><option '+(parseInt(d.actividades_forzado) == 0 ? 'selected' : '')+' value="0">No</option></select></div></div><hr>') : '')+'</div>'+
                                '<div class="col-lg-12 col-md-12 col-sm-12">Culmino Practicas: '+(parseInt(d.practicas) == 1 ? 'Si' : 'No')+(parseInt(d.practicas) == 1 ? '' : '</div><hr>'+
                                    '<div class="col-lg-12 col-md-12 col-sm-12"><div class="row">'+
                                        '<div class="col-lg-6 col-md-6 col-sm-6">Forzar Practicas: </div>'+
                                        '<div class="col-lg-6 col-md-6 col-sm-6"><select class="form-control" name="practicas"><option '+(parseInt(d.practicas_forzado) == 1 ? 'selected' : '')+' value="1">Si</option><option '+(parseInt(d.practicas_forzado) == 0 ? 'selected' : '')+' value="0">No</option></select></div>'+
                                        '</div><hr>')+'</div>'
                                //if(parseInt(d.cursos) == 1)
                                self.setContentAppend('<form id="actualiza_estado"><div class="row"><input type="hidden" name="id_alumno_especialidad" value="'+d.id_alumno_especialidad+'"><div class="col-lg-12 col-md-12 col-sm-12">Alumno: <label>'+d.apell_pat+' '+d.apell_mat+' '+d.nombre+'</label></div><div class="col-lg-12 col-md-12 col-sm-12">Codigo: <label>'+d.cod_alumno+'</label></div><div class="col-lg-12 col-md-12 col-sm-12">Especialidad: <label>'+d.especialidad+'</label></div><div class="col-lg-12 col-md-12 col-sm-12">Periodo: <label>'+d.periodo+'</label></div><div class="col-lg-12 col-md-12 col-sm-12">Turno: <label>'+d.turno+'</label></div>'+string+'</div></form>')
                            }
                        }).fail(function(){
                            self.close()
                            toastr.error('Ocurrió un error consulte con su administrador')
                        })
                    },
                    buttons: {
                        guardar: {
                            text: 'guardar',
                            btnClass: 'btn-success',
                            action: function(){
                                var data = $('#actualiza_estado').serialize()
                                $.confirm({
                                    title: 'Guardando',
                                    content: function(){
                                        var self1 = this
                                        return $.ajax({
                                            url: '<?= base_url('egresados/actualizarAlumnoEspecialidad') ?>',
                                            method: 'POST',
                                            dataType: 'json',
                                            data: data
                                        }).done(function(response){
                                            self1.close()
                                            self.close()
                                            toastr.success(response.message)
                                            $('#buscar_rango').trigger('click')
                                        }).fail(function(){
                                            self1.close()
                                            toastr.error('Ocurrió un error consulte con su administrador')
                                        })
                                    }
                                })
                            }
                        },
                        cancelar: function(){}
                    }
                })
            })
        }
        $('#buscar_rango').on('click',function(){
            t.fnClearTable()
            t.fnDraw()
            $.confirm({
                title: 'Consultando',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url('egresados/carga_egresados') ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            periodo: $('#periodo').val(),
                            status: 1
                        }
                    }).done(function(response){
                        if(response.status == 200){
                            var d = response.data
                            for(var i in d){
                                t.fnAddData([
                                    d[i].cod_alumno,
                                    d[i].apell_pat+' '+d[i].apell_mat+' '+d[i].nombre,
                                    d[i].especialidad,
                                    d[i].periodo,
                                    d[i].turno,
                                    parseInt(d[i].cursos) == 1 ? 'Si': 'No',
                                    parseInt(d[i].cursos_forzado) == 1 ? 'Si' : 'No',
                                    parseInt(d[i].lleva_cursos_actividades) == 1 ? (parseInt(d[i].actividades) == 1 ? 'Si': 'No') : 'No lleva',
                                    parseInt(d[i].lleva_cursos_actividades) == 1 ? (parseInt(d[i].actividades_forzado) == 1 ? 'Si' : 'No') : 'No lleva',
                                    parseInt(d[i].practicas) == 1 ? 'Si': 'No',
                                    parseInt(d[i].practicas_forzado) == 1 ? 'Si' : 'No',
                                    parseInt(d[i].estado_egreso) == 1 ? 'Si' : 'No',
                                    '<div class="btn-group"><button class="btn btn-default editar" type="button" data-id="'+d[i].id_alumno_especialidad+'"><i class="fa fa-edit"></i></button></div>'
                                    ])
                            }
                            cargar_funcionalidades()
                            t.on('draw.dt',function(){
                                cargar_funcionalidades()
                            })
                        }
                        else{
                            toastr.error(response.message)
                        }
                        self.close()
                    }).fail(function(){
                        self.close()
                        toastr.error('Ocurrió un error consulte con su administrador')
                    })
                }
            })
        })
    })
</script>