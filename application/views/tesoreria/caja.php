<script src="<?= base_url('assets/assets/js/plugins/switchery/switchery.js') ?>"></script>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Tesoreria</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Tesoreria</a></li>
            <li><strong>Caja</strong></li>
            <!--li class="active"><strong>Nuevo</strong></li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox" id="ibox_questionnaire">
                <div class="ibox-title">
                    <h5>Ingresos</h5>
                </div>
                <div class="ibox-content">
                    <form id="formulario-busqueda">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Desde - Hasta</label>
                                    <input type="text" class="form-control" name="rango" id="rango_fechas" value="<?= date('Y-m-d') . ' hasta ' .date('Y-m-d') ?>">
                    <input type="hidden" name="fechaStart" id="fechaStart" value="<?= date('d-m-Y') ?>">
                    <input type="hidden" name="fechaEnd" id="fechaEnd" value="<?= date('d-m-Y') ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Alumno</label>
                                    <input type="text" class="form-control" placeholder="Ingrese DNI, nombre o apellidos para la busqueda." id="alumno">
                                    <input type="hidden" id="id_alumno" name="id_alumno" value="0">
                                    <input type="hidden" id="fecha_pago" value="0">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button class="btn btn-primary" type="submit" id="buscar"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <table class="table table-striped table-bordered table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Alumno</th>
                                        <th>Cod. Alumno</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!is_numeric($pagos)) foreach ($pagos as $value) {  ?>
                                    <tr>
                                        <td><?= $value->concepto ?></td>
                                        <td><?= $value->alumno ?></td>
                                        <td><?= $value->codigo ?></td>
                                        <td><?= $value->monto ?></td>
                                        <td><?= $value->fch_pago ?></td>
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
<script>
    $(function(){
        var elem_2 = document.querySelector('.js-switch');
        var switchery_2 = new Switchery(elem_2, { color: '#ED5565' });
        /*$('span#diario').on('click',function(){
            if(elem_2.checked){
                $('#rango_fechas').prop('disabled',true)
            }else{
                $('#rango_fechas').prop('disabled',false)
            }
        })*/
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
        $('#rango_fechas').daterangepicker({
            opens: 'center',
            showDropdowns: true,
            showWeekNumbers: true,
            opens: 'right',
            drops: 'down',
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-default',
            separator: ' hasta ',
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
                $('#id_alumno').val(datos.codigo)
                $.ajax({
                    url: '<?= base_url('alumnos/cargaDataForPagos') ?>',
                    type: 'POST',
                    data: {
                        cod_alumno: $('#id_alumno').val()
                    },
                    success: function(response){
                        if(JSON.parse(response).status == 200){
                            var d = JSON.parse(response).data
                            $('#id_alumno_matricula').val(d.ultima_matricula.id)
                        }
                    }
                })
                //carga_historial()
            },
            onSearchStart: function(q){},
            onSearchComplete: function(q,suggestions){}
        })
        $('.dates').on('apply.daterangepicker', function(ev, picker) {
            $(this).val('Desde ' + picker.startDate.format('DD-MM-YYYY') + ' hasta ' + picker.endDate.format('DD-MM-YYYY'));
            $('#fechaStart').val(picker.startDate.format('DD-MM-YYYY'))
            $('#fechaEnd').val(picker.endDate.format('DD-MM-YYYY'))
        })
        $('.chosen-select').chosen({width: "100%"});
        var table = $('.dataTable').dataTable({
            "language": {
              "paginate": {
                "first": "Primera pagina",
                "last": "Ultima pagina",
                "next": "Siguiente",
                "previous": "Anterior"
              },
              "infoEmpty": "Observando 0 a 0 d 0 registros",
              "info": "Observando pagina _PAGE_ de _PAGES_",
              "lengthMenu": "Desplegando _MENU_ Registros",
              "search": "Busqueda"
            },
            pageLength: 25,
            //responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { extend: 'copy'},
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
        })
        $('#formulario-busqueda').on('submit',function(e){
            e.preventDefault()
            //var data = $(this).serialize()
            table.fnClearTable()
            table.fnDraw()
            $.confirm({
                title: 'Consultando',
                content: function(){
                    var self = this
                    $.ajax({
                        url: '<?= base_url('tesoreria/consultaFiltro') ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            desde: $('#fechaStart').val(),
                            hasta: $('#fechaEnd').val(),
                            alumno: $('#id_alumno').val()
                        }
                    }).done(function(response){
                        if(response.status == 200){
                            self.close()
                            console.log(response.data)
                            var ps = response.data.pagos
                            for(var i in ps){
                                table.fnAddData([
                                    ps[i].concepto,
                                    ps[i].alumno,
                                    ps[i].cod_alumno,
                                    ps[i].monto,
                                    ps[i].fch_pago
                                    ])
                            }
                        }
                        else{
                            self.close()
                            toastr.error(response.message)
                        }
                    }).fail(function(){
                        self.close()
                    })
                }
            })
        })
    })
</script>