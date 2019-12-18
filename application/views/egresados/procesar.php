<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Egresados</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Internos</a></li>
            <li class="active"><strong>Alumnos</strong></li>
            <!--li>Internos</li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-content">
            <div class="btn-group">
                <div class="col-lg-6 col-md-6 col-sm-6 col-lg-offset-3 col-md-offset-3 col-sm-offset-3">
                    <center>
                        <button class="btn btn-default" type="button" data-toggle="tooltip" id="procesar_cursos_regulares" data-placement="left" title="Procesar Cursos Regulares"><i class="fa fa-book-reader fa-2x"></i> Procesar Cursos Regulares</button><br><br><br>
                        <button class="btn btn-default" type="button" data-toggle="tooltip" id="procesar_cursos_actividades" data-placement="left" title="Procesar Cursos Actividades"><i class="fa fa-bars fa-2x"></i> Procesar Cursos Actividades</button><br><br><br>
                        <button class="btn btn-default" type="button" data-toggle="tooltip" id="procesar_practicas" data-placement="left" title="Procesar Practicas profesionales"><i class="fa fa-user-graduate fa-2x"></i> Procesar Practicas</button><br><br><br>
                        <button class="btn btn-default" type="button" data-toggle="tooltip" id="procesar_egresados" data-placement="left" title="Procesar Egresados"><i class="fa fa-graduation-cap fa-2x"></i> Procesar Egresados</button>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('[data-toggle="tooltip"]').tooltip()
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
        $('#procesar_egresados').on('click',function(){
            $.confirm({
                title: 'Atención',
                content: 'Esta seguro de proceder?',
                buttons: {
                    si: {
                        text: 'si',
                        btnClass: 'btn-success',
                        action: function(){
                            $.confirm({
                                title: 'Proceso finalizado',
                                content: function(){
                                    var self = this
                                    return $.ajax({
                                        url: '<?= base_url('egresados/procesar_egresados') ?>',
                                        method: 'POST',
                                        dataType: 'json',
                                        data: {
                                            s: 1
                                        }
                                    }).done(function(response){
                                        self.setContentAppend(response.data.alumnos_nuevos+' alumnos nuevos egresaron satisfactoriamente')
                                        toastr.success(response.message,response.data.alumnos_nuevos+' alumnos nuevos egresaron satisfactoriamente')
                                    }).fail(function(){
                                        self.close()
                                        toastr.error('Ocurrión un error consulte con su administrador')
                                    })
                                },
                                buttons: {
                                    ok: function(){}
                                }
                            })
                        }
                    },
                    no: function(){}
                }
            })
        })
        $('#procesar_practicas').on('click',function(){
            $.confirm({
                title: 'Atención',
                content: 'Esta seguro de proceder?',
                buttons: {
                    si: {
                        text: 'Si',
                        btnClass: 'btn-success',
                        action: function(){
                            $.confirm({
                                title: 'Proceso finalizado',
                                content: function(){
                                    var self = this
                                    return $.ajax({
                                        url: '<?= base_url('egresados/procesar_practicas') ?>',
                                        method: 'POST',
                                        dataType: 'json',
                                        data: {
                                            s: 1
                                        }
                                    }).done(function(response){
                                        //self.close()
                                        self.setContentAppend(response.data.alumnos_nuevos+' alumnos nuevos culminaron sus practicas satisfactoriamente')
                                        toastr.success(response.message,response.data.alumnos_nuevos+' alumnos nuevos culminaron sus practicas satisfactoriamente')
                                    }).fail(function(){
                                        self.close()
                                        toastr.error('Ocurrión un error consulte con su administrador')
                                    })
                                },
                                buttons: {
                                    ok: function(){}
                                }
                            })
                        }
                    },
                    no: function(){}
                }
            })
        })
        $('#procesar_cursos_actividades').on('click',function(){
            $.confirm({
                title: 'Atención',
                content: 'Esta seguro de proceder?',
                buttons: {
                    si: {
                        text: 'Si',
                        btnClass: 'btn-success',
                        action: function(){
                            $.confirm({
                                title: 'Proceso finalizado',
                                content: function(){
                                    var self = this
                                    return $.ajax({
                                        url: '<?= base_url('egresados/procesar_cursos_actividades') ?>',
                                        method: 'POST',
                                        dataType: 'json',
                                        data: {
                                            s: 1
                                        }
                                    }).done(function(response){
                                        //self.close()
                                        self.setContentAppend(response.data.alumnos_nuevos+' alumnos nuevos culminaron sus cursos de actividades satisfactoriamente')
                                        toastr.success(response.message,response.data.alumnos_nuevos+' alumnos nuevos culminaron sus cursos de actividades satisfactoriamente')
                                    }).fail(function(){
                                        self.close()
                                        toastr.error('Ocurrión un error consulte con su administrador')
                                    })
                                },
                                buttons: {
                                    ok: function(){}
                                }
                            })
                        }
                    },
                    no: function(){}
                }
            })
        })
        $('#procesar_cursos_regulares').on('click',function(){
            $.confirm({
                title: 'Atención',
                content: 'Esta seguro de proceder?',
                buttons: {
                    si: {
                        text: 'Si',
                        btnClass: 'btn-success',
                        action: function(){
                            $.confirm({
                                title: 'Proceso finalizado',
                                content: function(){
                                    var self = this
                                    return $.ajax({
                                        url: '<?= base_url('egresados/procesar_cursos_regulares') ?>',
                                        method: 'POST',
                                        dataType: 'json',
                                        data: {
                                            s: 1
                                        }
                                    }).done(function(response){
                                        //self.close()
                                        self.setContentAppend(response.data.alumnos_nuevos+' alumnos nuevos culminaron sus cursos regulares satisfactoriamente')
                                        toastr.success(response.message,response.data.alumnos_nuevos+' alumnos nuevos culminaron sus cursos regulares satisfactoriamente')
                                    }).fail(function(){
                                        self.close()
                                        toastr.error('Ocurrión un error consulte con su administrador')
                                    })
                                },
                                buttons: {
                                    ok: function(){}
                                }
                            })
                        }
                    },
                    no: function(){}
                }
            })
        })
    })
</script>