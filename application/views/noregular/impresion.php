<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">No Regular</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("especialidades");?> ">Evaluaciones</a></li>
            <li class="active"><strong>Impresion</strong></li>
            <!--li ><strong>Nuevo</strong></li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <!--li>
                        <a class="nav-link" data-toggle="tab" href="#registro"><i class="fa fa-user"></i> Registro</a>
                    </li-->
                    <li class="active show">
                        <a class="nav-link" data-toggle="tab" href="#consulta"><i class="fa fa-users"></i> Consulta</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="consulta">
                        <div class="panel-body">
                            <div class="ibox" id="">
                                <div class="ibox-content m-b-sm">
                                    <form name="" id="form-consulta" class="form-horizontal">
                                        <div class="form-group ">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <label>Rango de fechas</label>
                                                <input type="text" class="input-sm form-control dates" name="date[]" value="<?= 'Desde '.date('d-m-Y').' hasta '.date('d-m-Y') ?>"/>
                                                <input type="hidden" name="fechaStart" id="fechaStart" value="<?= date('d-m-Y') ?>">
                                                <input type="hidden" name="fechaEnd" id="fechaEnd" value="<?= date('d-m-Y') ?>">
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <label>&nbsp;</label><br>
                                                <button class="btn btn-default" type="submit" id="buscar"><i class="fa fa-search"></i> Buscar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="ibox-content border-bottom" id="tablaalumnos">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="dataTableListaAlumnos" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Alumno</th>
                                                    <th>Tipo Eval. No Regular</th>
                                                    <th>Periodo</th>
                                                    <th>Turno</th>
                                                    <th>Especialidad</th>
                                                    <th>Ciclo</th>
                                                    <th>Fecha</th>
                                                    <th>Curso</th>
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
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $(".chosen-select").chosen({width: "100%"});
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
        $(".dates").daterangepicker({
            opens: 'center',
            showDropdowns: true,
            showWeekNumbers: true,
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
        var table = $('#dataTableListaAlumnos').dataTable({
          "language": {
            "paginate": {
              "first": "Primera pagina",
              "last": "Ultima pagina",
              "next": "Siguiente",
              "previous": "Anterior"
            },
            "emptyTable": "Tabla vacia",
            "infoEmpty": "Observando 0 a 0 d 0 registros",
            //"info": "Observando pagina _PAGE_ de _PAGES_",
            "info": '<a href="<?= base_url('noregular/imprimiractas') ?>" target="_blank" class="btn btn-primary" id="imprimir" id="boton_imprimir"><i class="fa fa-print"></i> Imprimir</a>',
            "lengthMenu": "Desplegando _MENU_ Registros",
            "search": "Busqueda"
          },
          bFilter: true, 
          bInfo: true,
            responsive: true
        })
        $('.dates').on('apply.daterangepicker', function(ev, picker) {
            $(this).val('Desde ' + picker.startDate.format('DD-MM-YYYY') + ' hasta ' + picker.endDate.format('DD-MM-YYYY'));
            $('#fechaStart').val(picker.startDate.format('DD-MM-YYYY'))
            $('#fechaEnd').val(picker.endDate.format('DD-MM-YYYY'))
        })
        $('#form-consulta').on('submit',function(e){
            e.preventDefault()
            $.confirm({
                title: 'Consulta',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url('noregular/consulta') ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            fechaStart: $('#fechaStart').val(),
                            fechaEnd: $('#fechaEnd').val(),
                            turno: $('#turno').val() == 'Seleccione un turno' ? 0 : $('#turno').val(),
                            especialidad: $('#especialidades').val() == 'Seleccione Especialidad' ? 0 : $('#especialidades').val()
                        }
                    }).done(function(response){
                        self.close()
                        table.fnClearTable()
                        table.fnDraw()
                        if(response.status == 200){
                            var data = response.data
                            for(var i in data){
                                table.fnAddData([
                                    data[i].apell_pat+' '+data[i].apell_mat+' '+data[i].nombre ,
                                    data[i].tipo_no_regular,
                                    data[i].periodo,
                                    data[i].turno,
                                    data[i].especialidad,
                                    data[i].ciclo,
                                    data[i].fecha,
                                    data[i].curso,
                                    '<!--div class="btn-grou"><a class="btn btn-warning" href="<?= base_url('noregular/imprimeActaNoRegular/') ?>'+data[i].id+'" title="Imprimir Acta" data-toggle="tooltip" data-placement="top" target="_blank"><i class="fa fa-print"></i></a></div-->'
                                    ])
                            }
                            $('[data-toggle="tooltip"]').tooltip()
                        }
                        else{
                            toastr.error('Error',response.message)
                        }
                        console.log(response)
                    }).fail(function(){
                        self.close()
                        toastr.error('Error','Ocurrió un error consulte con su administrador')
                    })
                }
            })
        })
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>