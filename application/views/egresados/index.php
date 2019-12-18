<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Alumnos</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Egresados</a></li>
            <li class="active"><strong>Consulta</strong></li>
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
                <div class="col-lg-4 col-md-4 col-sm-4 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                    <label>Rango de fechas</label>
                    <input type="text" class="input-sm form-control dates" name="date[]" value="<?= 'Desde '.date('d-m-Y').' hasta '.date('d-m-Y') ?>"/>
                    <input type="hidden" name="fechaStart" id="fechaStart" value="<?= date('d-m-Y') ?>">
                    <input type="hidden" name="fechaEnd" id="fechaEnd" value="<?= date('d-m-Y') ?>">
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <label>&nbsp;</label><br>
                    <button type="button" class="btn btn-default" id="buscar_rango"><i class="fa fa-search"></i> Buscar</button>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <label>&nbsp;</label><br>
                    <a class="btn btn-warning" disabled id="imprimir" target="_blank" href="<?= base_url('egresados/listaIngreso') ?>"><i class="fa fa-print"></i> Imprimir</a>
                </div>
            </div><hr>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Cod.Alumno</th>
                                    <th>Apellidos y Nombres</th>
                                    <th>Especialidad</th>
                                    <th>Periodo</th>
                                    <th>Turno</th>
                                    <th>Fecha Egreso</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!is_numeric($egresados)) foreach ($egresados as $key => $value) { ?>
                                    <tr>
                                        <td><?= $value->cod_alumno ?></td>
                                        <td><?= $value->apell_pat.' '.$value->apell_mat.' ',$value->nombre ?></td>
                                        <td><?= $value->especialidad ?></td>
                                        <td><?= $value->periodo ?></td>
                                        <td><?= $value->turno ?></td>
                                        <td><?= $value->fch_egreso ?></td>
                                        <td>&nbsp;</td>
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
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},

                {extend: 'print',
                    customize: function (win){
                           $(win.document.body).addClass('white-bg');
                           $(win.document.body).css('font-size', '10px');

                           $(win.document.body).find('table')
                                   .addClass('compact')
                                   .css('font-size', 'inherit');
                   }
                }
            ]
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
        $('#buscar_rango').on('click',function(){
            t.fnClearTable()
            t.fnDraw()
            $('#imprimir').attr('disabled',true)
            $.confirm({
                title: 'Consultando',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url('egresados/carga_egresados') ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            desde: $('#fechaStart').val(),
                            hasta: $('#fechaEnd').val()
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
                                    d[i].fch_egreso,
                                    ''
                                    ])
                            }
                            $('#imprimir').removeAttr('disabled')
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