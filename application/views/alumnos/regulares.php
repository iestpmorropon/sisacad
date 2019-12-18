<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Alumnos</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Internos</a></li>
            <li>Alumno</li>
            <li class="active"><strong>Nuevo</strong></li>
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5>Lista de alumnos</h5> 
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
                    <div class="col-sm-6 col-lg 6 col-md-6">
                        <label>Alumno</label>
                        <input type="text" class="form-control" placeholder="Ingrese DNI, nombre o apellidos para la busqueda." id="alumno">
                        <input type="hidden" id="id_alumno_matricula" name="id_alumno_matricula" value="0">
                        <input type="hidden" id="id_alumno" value="0">
                    </div>
                    <div class="col-lg-2 col-sm-2 col-md-2">
                        <label>&nbsp;</label><br>
                        <button type="button" class="btn btn-primary" id="matricular" data-toggle="tooltip" data-placement="top" title="Matricular">Siguiente <i class="fa fa-arrow-right"></i></button>
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
                                    <th>Especialidad</th>
                                    <th>Ciclo</th>
                                    <th>Periodo</th>
                                    <th>Turno</th>
                                    <th>Estado</th>
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
        var cargaMatriculas = function(){
            $('#dataTable tbody').html('')
            $.confirm({
                    title: 'Consultando',
                    content: function(){
                        var self = this
                        $.ajax({
                            url: '<?= base_url('alumnos/cargaMatriculas') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                cod_alumno: $('#id_alumno').val()
                            }
                        }).done(function(response){
                            console.log(response)
                            self.close()
                            if(response.status == 200){
                                var d = response.data
                                for(var i in d){
                                    var estado = ''
                                    switch(d[i].estado_semestre){
                                        case '0': 
                                            estado = 'Desaprobado'
                                            break;
                                        case '1': 
                                            estado = 'Aprobado'
                                            break;
                                        case '2':
                                            estado = 'Matriculado'
                                            break;
                                        default:
                                            estado = ''
                                            break;
                                    }
                                    $('#dataTable tbody').append('<tr><td>'+d[i].cod_alumno+'</td><td>'+d[i].especialidad+'</td><td>Ciclo '+d[i].id_ciclo+'</td><td>'+d[i].periodo+'</td><td>'+d[i].turno+'</td><td>'+estado+'</td></tr>')
                                }
                            }
                            else{
                                toastr.error(response.message)
                            }
                        }).fail(function(){
                            self.close()
                            toastr.error('Error consulte con su Administrador')
                        })
                    }
                })
        }
        $('#matricular').on('click',function(){
            $.confirm({
                title: 'Matricula',
                columnClass: 'col-md-6 col-md-offset-3',
                content: function(){
                    var self = this
                    $.ajax({
                        url: '<?= base_url('alumnos/preparaMatricula') ?>',
                        dataType: 'json',
                        method: 'POST',
                        data: {
                            cod_alumno: $('#id_alumno').val()
                        }
                    }).done(function(response){
                        if(response.status == 200){
                            var al = response.data.alumno
                            var esp = response.data.especialidad
                            var mat = response.data.matricula_aprobada
                            var ciclo = parseInt(mat.id_ciclo)+1
                            var ciclos = ''
                            var turnos = ''
                            for (var i = ciclo; i <= 6; i++) {
                                ciclos += '<option value="'+i+'">Ciclo '+i+'</option>'
                            }
                            turnos = '<option value="1" '+(mat.id_turno == '1' ? 'selected' : '')+'>VESPERTINO</option>'
                            self.setContentAppend('<div class="row"><input type="hidden" class="cod_alumno" value="'+al.codigo+'"><input type="hidden" class="id_especialidad_periodo" value="'+response.data.alumno_especialidad.id_especialidad_periodo+'"><input type="hidden" class="id_especialidad" value="'+response.data.alumno_especialidad.id_especialidad+'"><input type="hidden" class="id_alumno_especialidad"value="'+response.data.alumno_especialidad.id+'"><input type="hidden" class="id_periodo" value="'+response.data.periodo.id+'"><div class="col-lg-6 col-md-6 col-sm-6">Alumno: </div><div class="col-lg-6 col-md-6 col-sm-6"><label>'+al.apell_pat+' '+al.apell_mat+' '+al.nombre+'</label><br></div></div><div class="row"><div class="col-lg-6 col-md-6 col-sm-6">Especialidad: </div><div class="col-lg-6 col-md-6 col-sm-6"><label>'+esp.nombre+'</label><br></div></div><div class="row"><div class="col-lg-6 col-md-6 col-sm-6">Ciclo:</div><div class="col-lg-6 col-md-6 col-sm-6"><select class="form-control ciclo" name="ciclo">'+ciclos+'</select></div></div><br><div class="row"><div class="col-lg-6 col-md-6 col-sm-6">Turno: </div><div class="col-lg-6 col-md-6 col-sm-6"><select class="form-control turno" name="turno">'+turnos+'</select></div></div>')
                            /*if(response.data.estado == '0'){
                                $.alert('Plan de estudios diferente al actual. Matricularlo como traslado interno.')
                            }*/
                        }
                        else{
                            self.close()
                            toastr.error(response.message)
                        }
                    }).fail(function(){
                        self.close()
                        toastr.error('Error consulte con su Administrador')
                    })
                },
                buttons: {
                    matricular: {
                        text: 'Matricular',
                        btnClass: 'btn-success',
                        action: function(){
                            var self = this
                            var id_ciclo = this.$content.find('.ciclo').val()
                            var id_turno = this.$content.find('.turno').val()
                            var cod_alumno = this.$content.find('.cod_alumno').val()
                            var id_especialidad_periodo = this.$content.find('.id_especialidad_periodo').val()
                            var id_periodo = this.$content.find('.id_periodo').val()
                            var id_especialidad = this.$content.find('.id_especialidad').val()
                            var id_alumno_especialidad = this.$content.find('.id_alumno_especialidad').val()
                            $.confirm({
                                title: 'Consultando',
                                content: function(){
                                    var self1 = this
                                    $.ajax({
                                        url: '<?= base_url('alumnos/matricularRegular') ?>',
                                        dataType: 'json',
                                        method: 'POST',
                                        data: {
                                            id_ciclo: id_ciclo,
                                            id_turno: id_turno,
                                            cod_alumno: cod_alumno,
                                            id_especialidad_periodo: id_especialidad_periodo,
                                            id_periodo: id_periodo,
                                            id_especialidad: id_especialidad,
                                            id_alumno_especialidad: id_alumno_especialidad
                                        }
                                    }).done(function(response){
                                        if(response.status == 200){
                                            self1.close()
                                            console.log(response)
                                            self.close()
                                            cargaMatriculas()
                                            toastr.success(response.message)
                                            $.alert({
                                                title: 'Matricula',
                                                content: response.message,
                                                buttons: {
                                                    imprimir: {
                                                        text: 'imprimir',
                                                        btnClass: 'btn-warning',
                                                        action: function(){
                                                            window.open('<?php echo base_url("alumnos/impresionnuevo/"); ?>'+cod_alumno+'/'+id_ciclo,'_blank' )
                                                            return false
                                                        }
                                                    },
                                                    ok: function(){}
                                                }
                                            })
                                        }
                                        else{
                                            self1.close()
                                            toastr.error(response.message)
                                            $.alert(response.message)
                                        }
                                    }).fail(function(){
                                        self1.close()
                                        toastr.error('Error consulte con su Administrador')
                                    })
                                }
                            })
                            return false
                        }
                    },
                    cerrar: function(){}
                }
            })
        })

        $('#alumno').autocomplete({
            serviceUrl: '<?= base_url('alumnos/getAlumnoAutocomplete_') ?>',
            minChars: 3,
            dataType: 'json',
            type: 'POST',
            paramName: 'data',
            params: {
              'data': $('#alumno').val()
            },
            onSelect: function(suggestion){
                console.log(suggestion)
                var d = JSON.parse(suggestion.data)
                $('#id_alumno').val(d.codigo)
                $('#siguiente').attr('href',$('#siguiente').attr('href')+'/'+d.codigo)
                cargaMatriculas()
            },
            onSearchStart: function(q){},
            onSearchComplete: function(q,suggestions){}
        })
    })
</script>