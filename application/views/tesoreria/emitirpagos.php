<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Tesoreria</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Tesoreria</a></li>
            <li><strong>Pagos</strong></li>
            <!--li class="active"><strong>Nuevo</strong></li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox" id="ibox_questionnaire">
                <div class="ibox-title bg-primary">
                    <h5>Registro de Pagos</h5> 
                    <div class="ibox-tools">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="ibox-content m-b-sm border-bottom">
                    <div class="sk-spinner sk-spinner-double-bounce">
                        <div class="sk-double-bounce1"></div>
                        <div class="sk-double-bounce2"></div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active show" id="procedimiento_administrativo">
                            <form name="import_form" id="registrar_pago" class="form-horizontal"  >
                                <div class="form-group">
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <label class="control-label">Concepto</label>
                                        <select class="form-control chosen-select" name="id_tupa" id="concepto">
                                            <option>Seleccione concepto</option>
                                            <?php foreach ($tupa as $key => $value) { ?>
                                                <option value="<?= $value->id ?>"><?= $value->nombre ?></option>
                                            <?php } ?>
                                        </select><br>
                                        <label id="monto"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <label>Alumno</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Ingrese codigo, nombre o apellidos para la busqueda." id="alumno">
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Nuevo Alumno" id="nuevo_alumno"><i class="fa fa-plus"></i></button>
                                              </span>
                                            <input type="hidden" id="id_alumno_matricula" value="0">
                                          </div>
                                        <input type="hidden" id="id_alumno" name="id_alumno" value="0">
                                        <input type="hidden" id="fecha_pago" value="0">
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <label>Periodo actual</label><br>
                                        <label id="nombre_periodo"><?= is_numeric($periodo_actual) ? '-' : $periodo_actual->nombre ?></label>
                                        <input type="hidden" id="id_periodo" value="0">
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <label>&nbsp;</label><br>
                                        <button class="btn btn-primary" type="submit" id="generar"><i class=" fa fa-save"></i> Registrar</button>
                                    </div>
                                </div>
                                <div class="form-group" id="procesos" style="display: none;">
                                    <hr>
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <label class="respuestita">Espere...</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <label>Observación</label>
                                        <textarea class="form-control" name="observacion" id="observacion" rows="5" placeholder="Observación...."></textarea>
                                    </div>
                                </div>
                                <div class="" id="respuesta" style="display: none">
                                    <div class="table-responsive">
                                        <hr>
                                        <h4>Pagos</h4>
                                        <table class="table table-striped table-bordered table-hover" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th>Periodo</th>
                                                    <th>Concepto</th>
                                                    <th>Fecha y hora</th>
                                                    <th>Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
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
<script>
    $(document).on('focus',".datepicker", function(){
                    $(this).datepicker({
            format: 'dd-mm-yyyy'
        });
            }); 
    $(function(){
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
        $('[data-toggle="tooltip"]').tooltip()
        $("#concepto").chosen()
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
              "lengthMenu": "Desplegando _MENU_ Registros",
              "search": "Busqueda"
            },
            bFilter: false, 
            bInfo: false,
            bLengthChange: false
          })
        var historial = null
        var put_historial = function(data){
            historial = data
        }
        var get_historial = function(){
            if( historial === null)
                return []
            return historial
        }
        var carga_historial = function (){
            if($('#id_alumno').val() === ''){
                return false
            }
            $.confirm({
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url('alumnos/cargaPagosForAlumno') ?>',
                        type: 'POST',
                        data: {
                            cod_alumno: $('#id_alumno').val(),
                            concepto: $('#concepto').val()
                        },
                        beforeSend: function(){
                            $('#procesos').show()
                        },
                        success: function(response){
                            self.close()
                            if(JSON.parse(response).status == 200){
                                $('#procesos').hide()
                                $('#respuesta').show()
                                var d = JSON.parse(response).data
                                t.fnClearTable()
                                t.fnDraw()
                                put_historial(d)
                                for(var i in d){
                                    t.fnAddData([
                                            d[i].periodo,
                                            d[i].concepto,
                                            d[i].fecha,
                                            d[i].pago
                                    ])
                                }
                            }else{
                                $('#procesos label.respuestita').html('No registra pagos')
                                setTimeout(function(){ 
                                    $('#procesos').hide()
                                    $('#procesos label.respuestita').html('Espere...')
                                }, 3000);
                            }
                        }
                    })
                }
            })
        }
        $('#alumno').autocomplete({
            serviceUrl: '<?= base_url('alumnos/getAlumnoAutocomplete') ?>',
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
        var arma_formulario = function(data){
            var contenido = '<form id="formulario-registro-pago">'
            switch ($('#concepto option:selected').text()){
                case 'Matrícula Regular': {
                            var periodos = data.periodos
                            contenido += '<br><div class="conten"><div class="row"><div class="col-lg-3 col-md-3 col-sm-3"><label>Periodos</label><select name="periodo" class="form-control" id="periodos-registro">'
                            for(var i in periodos){
                                contenido += '<option value="'+periodos[i].id+'">'+periodos[i].nombre+'</option>'
                            }
                            contenido += '</select>'
                            contenido += '</div></div>'
                }
                default: {
                    contenido = 'Alumno <label>'+$('#alumno').val()+'</label>'
                    var periodos = data.periodos
                    contenido += '<br><div class="conten"><div class="row"><div class="col-lg-3 col-md-3 col-sm-3"><label>Periodos</label><select name="periodo" class="form-control" id="periodos-registro">'
                    for(var i in periodos){
                        contenido += '<option value="'+periodos[i].id+'">'+periodos[i].nombre+'</option>'
                    }
                    contenido += '</select>'
                    contenido += '<div class="col-lg-12 col-md-12 col-sm-12"><label>Observación</label><textarea class="form-control" name="observacion" id="observacion"></textarea><div>'
                }
                    break;
            }
            contenido += '</form>'
            return contenido
        }
        $('#registrar_pago').on('submit',function(e){
            e.preventDefault()
            if($('#concepto').val() == 'Seleccione concepto'){
                toastr.error('Seleccione un concepto valido')
                /*$.alert({
                    title: 'Atención',
                    content: 'Seleccione un concepto valido'
                })*/
                return false
            }
            if($('#id_alumno').val() == 0){
                toastr.error('Seleccione un alumno')
                return false
            }
            var data_form = $('#registrar_pago').serialize()
            $.confirm({
                title: 'Registrar',
                columnClass: 'col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3',
                content: function(){
                    var self = this
                    $.ajax({
                        url: '<?= base_url('tesoreria/registrarPago') ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: data_form
                    }).done(function(response){
                        if(response.status == 200){
                            //self.setContent('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12">'+response.message+'</div></div>')
                            toastr.success('Registro Satisfactorio')
                            $.alert({
                                title: 'Registro',
                                content: 'Registro Satisfactorio',
                                buttons: {
                                    imprimir: {
                                        text: 'imprimir',
                                        btnClass: 'btn-warning',
                                        action: function(){
                                            window.open('<?= base_url('tesoreria/comprobantepago/') ?>'+response.data.id_pago,'_blank')
                                            return false
                                        }
                                    },
                                    ok: function(){
                                        $('#concepto').val('Seleccione concepto').trigger("chosen:updated");
                                        $('#alumno').val('')
                                        $('#id_alumno').val(0)
                                        $('#observacion').val('')
                                        $('#monto').html('')
                                    }
                                }
                            })
                        }
                        else{
                            toastr.error(response.message)
                        }
                            self.close()
                    }).fail(function(){
                        self.close()
                        toastr.error('Error consulte con su Administrador')
                    })
                }
            })
            /*$.confirm({
                title: 'Registro de matricula',
                columnClass: 'col-lg-8 col-sm-8 col-md-8 col-lg-offset-2 col-md-offset col-sm-offset-2',
                content: function(){
                    var self = this;
                    //self.setContent('Checking callback flow');
                    return $.ajax({
                        url: '<?= base_url('tesoreria/preparar_pago') ?>',
                        dataType: 'json',
                        data: {
                            id_concepto: $('#concepto').val(),
                            concepto: $('#concepto option:selected').text(),
                            alumno: $('#id_alumno').val()
                        },
                        method: 'POST'
                    }).done(function (response) {
                        console.log(response)
                        if(response.status == 200){
                            var d = response.data
                            self.setContentAppend(arma_formulario(d))
                        }
                        else{
                            self.close()
                            toastr.error('Error', response.message);
                        }
                    })
                },
                buttons: {
                    formSubmit: {
                        text: 'Registrar',
                        btnClass: 'btn-primary',
                        action: function(){
                            $.confirm({
                                title: 'Registro Satisfactorio',
                                content: function(){
                                    var self = this
                                    return $.ajax({
                                        url: '<?= base_url('tesoreria/procesar_pago') ?>',
                                        dataType: 'json',
                                        method: 'POST',
                                        data: {
                                            'periodo': $('#periodos-registro').val(),
                                            'codigo_alumno': $('#codigo_alumno').val(),
                                            'id_especialidad': $('#id_especialidad').val(),
                                            'id_tupa': $('#concepto').val(),
                                            'concepto': $('#concepto option:selected').text(),
                                            'id_ciclo': $('#id_ciclo').val(),
                                            'id_grupo': $('#id_grupo').val(),
                                            'id_turno': $('#id_turno').val(),
                                            'id_especialidad_periodo': $('#id_especialidad_periodo').val(),
                                            'observacion': $('#observacion').val(),
                                            'id_alumno_matricula': $('#id_alumno_matricula').val()
                                        }
                                    }).done(function(response){
                                        if(response.status == 200){
                                            var d = response.data
                                            self.setContentAppend('El alumno <label>'+$('#alumno').val()+'</label> fue matriculado exitosamente')
                                            self.setContentAppend('<input type="hidden" class="id_pago" value="'+d.id_pago+'">')
                                            self.setContentAppend('<input type="hidden" class="cod_alumno" value="'+d.codigo_alumno+'">')
                                            $('#fecha_pago').val(d.fch_pago)
                                            if(typeof d.id_ciclo != 'undefined')
                                                self.setContentAppend('<input type="hidden" class="id_ciclo" value="'+d.id_ciclo+'">')
                                            console.log($('#concepto').val())
                                        }
                                    })
                                },
                                buttons: {
                                    formSubmit: {
                                        text: '<i class="fa fa-print"></i> Imprimir',
                                        btnClass: 'btn-primary',
                                        action: function(){
                                            var id_pago = this.$content.find('input.id_pago').val()
                                            var cod_alumno = this.$content.find('input.cod_alumno').val()
                                            var id_ciclo = this.$content.find('input.id_ciclo').val()
                                            if($('#concepto option:selected').text() == 'Matrícula Regular')
                                                window.open('<?= base_url('tesoreria/fichapago/') ?>'+id_pago,'_blank' )
                                            else
                                                window.open('<?= base_url('tesoreria/comprobantepago/') ?>'+id_pago,'_blank')
                                            //window.open('<?= base_url('alumnos/impresionnuevo/') ?>'+cod_alumno+'/'+id_ciclo,'_blank' )
                                            return false
                                        }
                                    },
                                    ok: function(){
                                        t.fnAddData([
                                            $('#nombre_periodo').html(),
                                            $('#concepto option:selected').text(),
                                            $('#fecha_pago').val(),
                                            $('#monto').html()
                                        ])
                                        $('#concepto').val('Seleccione concepto')
                                        $('#concepto').chosen()
                                        $('#alumno').val('')
                                        $('#id_alumno').val(0)
                                    }
                                }
                            })
                        }
                    },
                    cancelar: function(){}
                }
            })*/
        })
        $('#concepto').on('change',function(){
            $.ajax({
                url: '<?= base_url('tesoreria/getPago') ?>',
                type: 'POST',
                data: {
                    id_pago: $(this).val()
                },
                success: function(response){
                    if(JSON.parse(response).status == 200){
                        var p = JSON.parse(response).data
                        $('#monto').html('Monto: S/'+p.costo)
                    }else{
                        $('#monto').html('')
                    }
                }
            })
        })
        $('#nuevo_alumno').on('click',function(){
            $.confirm({
                title: 'Registro de nuevo alumno',
                columnClass: 'col-lg-12 col-sm-12 col-md-12',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url('alumnos/nuevoAlumnoView') ?>',
                        type: 'POST',
                        data: {
                            nuevo: 1
                        },
                        success: function(response){
                            self.setContentAppend(response)
                        }
                    })
                },
                contentLoaded: function(data, status, xhr){
                    //self.setContentAppend('<h2>Resultado:</h2>');
                },
                onContentReady: function(){
                    var selfForm = this
                    
                    var form_steps = $("#form_register").steps({
                        labels:{
                            current: "Siguiente paso:",
                            pagination: "Paginación",
                            finish: "Finalizar",
                            next: "Siguiente",
                            previous: "Anterior",
                            loading: "Cargando ...",
                            cancel: "Cancelar"
                        },
                        bodyTag: "fieldset",
                        onContentLoaded: function(event, currentIndex){
                            $('.wizard-big.wizard > .content').css('style','min-height: auto !important;')

                        },
                        onStepChanging: function (event, currentIndex, newIndex){
                            // Always allow going backward even if the current step contains invalid fields!
                            if (currentIndex > newIndex)
                            {
                                return true;
                            }


                            var form = $(this);

                            // Clean up if user went backward before
                            if (currentIndex < newIndex)
                            {
                                // To remove error styles
                                $(".body:eq(" + newIndex + ") label.error", form).remove();
                                $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
                            }

                            // Disable validation on fields that are disabled or hidden.
                            form.validate().settings.ignore = ":disabled,:hidden";

                            // Start validation; Prevent going forward if false
                            return form.valid();
                        },
                        onStepChanged: function (event, currentIndex, priorIndex){},
                        onFinishing: function (event, currentIndex){
                            var form = $(this);

                            // Disable validation on fields that are disabled.
                            // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
                            form.validate().settings.ignore = ":hidden";

                            // Start validation; Prevent form submission if false
                            return form.valid();
                        },
                        onFinished: function (event, currentIndex){
                            if($('#id_periodo_2').val() == 0){
                                $.alert({
                                        title: 'Atención',
                                        content: 'No hay periodo abierto para matricular a un nuevo alumno.',
                                        type: 'red',
                                        typeAnimated: true
                                    })
                                    return false
                                }
                                $('#ibox_questionnaire').children('.ibox-content').toggleClass('sk-loading');
                                $.ajax({
                                    url: '<?= base_url("alumnos/newPersonaOnly") ?>',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: $('#form_register').serialize(),
                                    success: function(data){
                                        if(data.status == 200){
                                            toastr.success(data.message)
                                            $('.message').append('<div class="alert alert-success">' + data.message + '</div>');
                                            var al = data.data.alumno
                                            $('#id_alumno').val(al.codigo_alumno) 
                                            $('#alumno').val(al.dni+' - '+al.nombre+' '+al.apell_pat+' '+al.apell_mat)
                                            $('#ibox_questionnaire').children('.ibox-content').removeClass('sk-loading')
                                            selfForm.close()
                                        }else{
                                            $.alert({
                                                title: 'Atención',
                                                content: data.message,
                                                type: 'red',
                                                typeAnimated: true
                                            })
                                            window.location.reload()
                                            $('.message').append('<div class="alert alert-danger">' + data.message + '</div>');
                                        }
                                    }
                                }).fail(function(){
                                    $.alert({
                                        title: 'Atención',
                                        content: 'Error en el registro, Consulte con su Administrador',
                                        type: 'red',
                                        typeAnimated: true
                                    })
                                            //window.location.reload()
                                })
                        }
                    })

                    //this.$content.find('.generar_dni').on('click',function(){
                            $('#generar_dni').on('click',function(){
                                $.confirm({
                                    title: 'Generando',
                                    content: function(){
                                        var self = this
                                        return $.ajax({
                                            url: '<?= base_url('alumnos/generaDni') ?>',
                                            method: 'POST',
                                            dataType: 'json',
                                            data: {
                                                d: 1
                                            }
                                        }).done(function(response){
                                            console.log(response)
                                            if(response.status == 200){
                                                self.close()
                                                $('#dni').val(response.data.dni)
                                            }
                                            else{
                                                self.close()
                                                toastr.error(response.message)
                                            }
                                        }).fail(function(){
                                            self.close()
                                            toastr.success('Error consulte con su Administrador')
                                        })
                                    }
                                })
                            })
                    
        var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                    
                            var tabla
                            $('.datepicker').datepicker({
                                format: 'dd-mm-yyyy',
                                container: container,
                                todayHighlight: true,
                                autoclose: true

                            });
                            $('#especialidad').on('change',function(){
                                $.ajax({
                                    url: '<?= base_url("alumnos/getPeriodoEspecialidad") ?>',
                                    type: 'POST',
                                    data: {
                                        id_especialidad: $('#especialidad').val(),
                                        tipo: 1
                                    },
                                    success: function(data){
                                        var d = JSON.parse(data)
                                        if(JSON.parse(data).status == 200){
                                            var p = d.data
                                            $('#periodo').val(p.nombre)
                                            $('#id_periodo_2').val(p.id_periodo)
                                            $('#id_especialidad_periodo').val(p.id)
                                            $.ajax({
                                                url: '<?= base_url('cursos/getCursosForPeriodo') ?>',
                                                type: 'POST',
                                                data: {
                                                    id_especialidad_periodo: $('#id_especialidad_periodo').val()
                                                },
                                                success: function(response){
                                                    if(JSON.parse(response).status == 200){
                                                        var d = JSON.parse(response).data
                                                        $('.table-responsive').show()
                                                        tabla = $('#dataTable1').dataTable({
                                                          "language": {
                                                            "paginate": {
                                                              "first": "Primera pagina",
                                                              "last": "Ultima pagina",
                                                              "next": "Siguiente",
                                                              "previous": "Anterior"
                                                            },
                                                            "infoEmpty": "Observando 0 a 0 d 0 registros",
                                                            "info": " ",
                                                            "lengthMenu": "Desplegando _MENU_ Registros",
                                                              bFilter: true, 
                                                              bInfo: false
                                                          }
                                                        })
                                                        tabla.fnClearTable()
                                                        tabla.fnDraw()
                                                        for(var i in d){
                                                            tabla.fnAddData([
                                                                    d[i].codigo_curso,
                                                                    d[i].nombre_ciclo,
                                                                    d[i].curso
                                                                ])
                                                        }
                                                        malla_registro = d
                                                    }
                                                }
                                            })
                                        }
                                    }
                                })
                            })
                }
            })
        })
    })
</script>