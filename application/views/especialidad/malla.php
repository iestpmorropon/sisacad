<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Especialidades</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("especialidades");?> ">Especialidad</a></li>
            <li>Gestion</li>
            <li class="active" ><strong>Malla</strong></li>
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-title ">
            <h5>Malla de <?= $especialidad->nombre ?> - <?= $periodo->nombre ?> - <?= $turno->nombre ?></h5> 
            <input type="hidden" name="id_especialidad" id="id_especialidad" value="<?= $especialidad->id; ?>">
            <input type="hidden" name="id_periodo" id="id_periodo" value="<?= $periodo->id ?>">
            <input type="hidden" name="id_especialidad_periodo" id="id_especialidad_periodo" value="<?= $especialidad_perio->id ?>">
            <input type="hidden" name="name_especialidad" id="name_especialidad" value="<?= $especialidad->nombre ?>">
            <input type="hidden" name="id_turno" id="id_turno" value="<?= $turno->id ?>">
            <div class="ibox-tools btn-group">
                <?php if(!is_array($malla)){ ?>
                    <button type="button" class="btn btn-primary pull-right" id="importar"><i class="fas fa-download"></i> Importar Malla</button>
                <?php } ?>
                    <button type="button" class="btn btn-primary pull-right" id="nuevo-ciclo"><i class="fa fa-plus"></i>Nuevo Ciclo</button>
            </div>
        </div>
    </div>
</div>
<?php if(!is_numeric($ciclos)) foreach ($ciclos as $key => $value) { ?>
<div class="wrapper wrapper-content animated fadeInRight ecommerce" id="<?= $value->id ?>-ciclo" style="padding-bottom: 0px !important; <?= (is_array ($malla) && isset ($malla[$value->id])) ? 'display: block;' : 'display: none;' ?>">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5><?= $value->nombre ?></h5> 
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="ibox-content m-b-sm border-bottom">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <table class="table tablas-ciclo table-striped table-bordered table-hover" id="ciclo-<?= $value->id ?>">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Modulo</th>
                            <th>Horas</th>
                            <th>Creditos</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $cursos = isset($malla[$value->id]) ? $malla[$value->id] : []; foreach ($cursos as $key => $val) { ?>
                            <tr id="curso-<?= $val->id_curso ?>" data-in="<?= $val->id_curso ?>">
                                <td><?= ($key+1) ?></td>
                                <td><?= $val->codigo_curso ?></td>
                                <td><?= $val->curso ?></td>
                                <td><?= $val->modulo ?></td>
                                <td><?= $val->horas ?></td>
                                <td><?= $val->creditos ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-danger quitar" data-id-malla="<?= $val->id_malla ?>" data-id-curso="<?= $val->id_curso ?>" data-id-ciclo="<?= $val->id_ciclo ?>" data-id-especialidad="<?= $id_especialidad ?>" data-id-esp-per="<?= $val->id_especialidad_periodo ?>" data-toggle="tooltip" data-placement="top" title="Quitar curso de malla"><i class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="col-lg">
                    <button type="button" data-ciclo="<?= $value->id ?>" class="btn btn-primary pull-right nuevo-curso" data-toggle="tooltip" data-placement="top" title="Gestionar Cursos"><i class="fa fa-plus"></i> Gestionar Curso</button>
                </div>
            </div>
            <div class="row">&nbsp;</div>
        </div>
    </div>
</div>
<?php }  ?>
<div class="row">&nbsp;</div>
<script type="text/javascript">
    $(function(){
        $('[data-toggle="tooltip"]').tooltip()
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
        $('#nuevo-ciclo').on('click',function(){
            var ciclos = "<?php if(!is_numeric($ciclos)) foreach ($ciclos as $key => $value){
                echo "<option value='".$value->id."' ".(is_array ($malla) && isset ($malla[$value->id]) ? 'disabled ' : '') .'>'.$value->nombre.'</option>';
            } ?>"
            $.confirm({
                title: 'Registro nuevo ciclo',
                content: '<label>Seleccione un ciclo a abrir</label><select class="form-control" id="ciclo-plus" name="ciclo">'+ciclos+'</select>',
                buttons: {
                    abrir: function(){
                        var id_ciclo = $('#ciclo-plus').val()
                        $('#'+id_ciclo+'-ciclo').show()
                        $('html, body').animate({
                            scrollTop: $("#"+id_ciclo+'-ciclo').offset().top
                          }, 1000)
                    },
                    cancelar: function(){}
                }
            })
        })
        actualizar_funcionalidades = function(){
            $('button.quitar').unbind('click')
            $('button.quitar').on('click',function(){
                var id_ciclo = $(this).attr('data-id-ciclo')
                var id_curso = $(this).attr('data-id-curso')
                var id_especialidad = $(this).attr('data-id-especialidad')
                var id_malla = $(this).attr('data-id-malla')
                var row_inf = $(this)
                $.confirm({
                    title: 'Atención',
                    content: 'Esta seguro de retirar este curso?',
                    buttons: {
                        si: function(){
                            $.confirm({
                                title: 'Quitando curso',
                                content: function(){
                                    var self = this
                                    return $.ajax({
                                        url: '<?= base_url('especialidades/retiraCursoMalla') ?>',
                                        method: 'POST',
                                        dataType: 'json',
                                        data: {
                                            id_ciclo: id_ciclo,
                                            id_curso: id_curso,
                                            id_malla: id_malla,
                                            id_especialidad: id_especialidad,
                                            id_especialidad_periodo: $('#id_especialidad_periodo').val(),
                                            id_periodo: $('#id_periodo').val()
                                        }
                                    }).done(function(response){
                                        $(row_inf).parent().parent().parent().remove()
                                        self.close()
                                        toastr.success(response.message)
                                    }).fail(function(){
                                        self.close()
                                        toastr.error('Ocurrió un error consulte con su administrador')
                                    })
                                }
                            })
                        },
                        no: function(){}
                    }
                })
            })
        }
        actualizar_funcionalidades()
        $('#importar').on('click',function(){
            $.confirm({
                title: 'Especialidad '+$('#name_especialidad').val() ,
                columnClass: 'col-lg-8 col-sm-8 col-md-8 col-lg-offset-2 col-md-offset col-sm-offset-2',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url('especialidades/traePeriodosEspecialidad') ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            id_especialidad: <?= $id_especialidad ?>,
                            id_especialidad_periodo: $('#id_especialidad_periodo').val()
                        }
                    }).done(function(response){
                        var p = response.data.periodos
                        self.setContentAppend('<div class="content"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12">'+
                                '<label>Seleccione el periodo que desea importar la malla</label></div>'+
                                '<div class="col-lg-3 col-md-3 col-sm-3">')
                        self.setContentAppend('<select class="form-control periodo" name="periodo" id="periodo_import">')
                        for(var i in p){
                            self.setContentAppend('<option value="'+p[i].id_periodo+'-'+p[i].id_turno+'">'+p[i].periodo+'-'+p[i].turno+'</option>')
                        }
                        self.setContentAppend('</select></div></div></div>')
                    }).fail(function(jqXHR, textStatus, errorThrown){
                        self.close()
                        console.log(jqXHR)
                        console.log(textStatus)
                        console.log(errorThrown)
                        toastr.error('Error', 'Error, consulte con su administrador');
                    })
                },
                buttons: {
                    importar: {
                        text: 'Importar',
                        btnClass: 'btn btn-success',
                        action: function(){
                            var pr = this.$content.find('.periodo').val().split('-')
                            var id_periodo = pr[0]
                            var id_turno = pr[1]
                            $.confirm({
                                title: 'Importando',
                                content: function(){
                                    var self1 = this
                                    return $.ajax({
                                        url: '<?= base_url('especialidades/importarMalla') ?>',
                                        method: 'POST',
                                        dataType: 'json',
                                        data: {
                                            id_especialidad : <?= $id_especialidad ?>,
                                            id_periodo: <?= $periodo->id ?>,
                                            id_last_periodo: id_periodo,
                                            id_last_turno: id_turno,
                                            id_turno: $('#id_turno').val()
                                        }
                                    }).done(function(response){
                                        if(response.status == 200){
                                            self1.close()
                                            window.location.reload()
                                        }
                                        else{
                                            self1.setContentAppend(response.message)
                                        }
                                    }).fail(function(jqXHR, textStatus, errorThrown){
                                        self.close()
                                        console.log(jqXHR)
                                        console.log(textStatus)
                                        console.log(errorThrown)
                                        toastr.error('Error', 'Error, consulte con su administrador');
                                    })
                                }
                            })
                        }
                    },
                    cancelar: function(){}
                }
            })
        })
        $('button.nuevo-curso').on('click',function(){
            //$.alert($(this).attr('data-ciclo'))
            var id_ciclo = $(this).attr('data-ciclo')
            $.confirm({
                title: 'Nuevo Curso',
                theme: 'light',
                columnClass: 'col-md-8 col-md-offset-2',
                content: function(){
                    var self = this
                    return $.ajax({
                        url: '<?= base_url("especialidades/traeCursos") ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            id_especialidad: $('#id_especialidad').val(),
                            all: 1
                        }
                    }).done(function(response){
                        //console.log(response)
                        self.setContentAppend('<table class="table table-stripped" id="dataTable" width="100%" cellspacing="0"><thead><tr><th>Codigo</th><th>Nombre</th><th>Horas</th><th>Creditos</th><th>Opciones</th></tr></thead><body>')
                        if(response.status == 200){
                            var res = response.data
                            for(var i in res){
                                var existe = false
                                $('.tablas-ciclo tbody tr').each(function(index,item){
                                    if($(this).attr('data-in') == res[i].id)
                                        existe = true
                                })
                                self.setContentAppend('<tr><td>'+res[i].codigo+'</td><td>'+res[i].nombre+'</td><td>'+res[i].horas+'</td><td>'+res[i].creditos+'</td><td><button type="type" class="btn btn-'+(existe ? 'danger' : 'primary')+' seleccionar" data-horas="'+res[i].horas+'" data-in="'+res[i].id+'" data-codigo="'+res[i].codigo+'" data-nombre="'+res[i].nombre+'" data-creditos="'+res[i].creditos+'" data-toggle="tooltip" data-placement="top" title="Seleccionar"><i class="fa fa-'+(existe ? 'trash':'circle')+'"></i></button></td></tr>')
                            }
                        }
                        self.setContentAppend('</tbody></table>')
                    })
                },
                buttons: {
                    ok: function(){
                        window.location.reload()
                        //window.location.href = '<?php echo base_url('periodos/editar/'.$periodo->id); ?>';
                    }
                },
                contentLoaded: function(data, status, xhr){
                    //self.setContentAppend('<h2>Resultado:</h2>');
                    //this.setContentAppend('<div>Resultado:</div>');
                },
                onContentReady: function(){
                    $('[data-toggle="tooltip"]').tooltip()
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
                      bFilter: true, 
                      bInfo: false
                    })
                    //this.setContentAppend('<div>Resultado:</div>');
                    $('button.seleccionar').unbind('click')
                    $('button.seleccionar').on('click',function(){
                        var self = this
                        var id_curso = $(this).attr('data-in')
                        if($(self).hasClass('btn-primary')){
                            var count = $('#ciclo-'+id_ciclo+' tbody tr:last').find('td:eq(0)').text()
                            console.log(count)
                            $('#ciclo-'+id_ciclo+' tbody').append('<tr data-in="'+$(this).attr('data-in')+'" ><td>'+(parseInt(count == '' ? 0 : count)+1)+'</td><td>'+$(self).attr('data-codigo')+'</td><td>'+$(self).attr('data-nombre')+'</td><td>-</td><td>'+$(self).attr('data-horas')+'</td><td>'+$(self).attr('data-creditos')+'</td><td><div class="btn-group"><button type="button" class="btn btn-danger quitar" data-id-malla="" data-id-curso="'+id_curso+'" data-id-ciclo="'+id_ciclo+'" data-id-especialidad="'+$('#id_especialidad').val()+'" data-id-esp-per="'+$('#id_especialidad_periodo').val()+'" data-toggle="tooltip" data-placement="top" title="Quitar curso de malla"><i class="fa fa-trash"></i></button></div></td></tr>')
                            actualizar_funcionalidades()
                            $(self).removeClass('btn-primary').addClass('btn-danger')
                            $(self).html('<i class="fa fa-trash"></i>')
                            $.ajax({
                                url: '<?= base_url('especialidades/newMalla') ?>',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    id_curso: id_curso,
                                    id_ciclo: id_ciclo,
                                    id_especialidad: $('#id_especialidad').val(),
                                    id_periodo: $('#id_periodo').val(),
                                    id_especialidad_periodo: $('#id_especialidad_periodo').val(),
                                    id_turno: $('#id_turno').val(),
                                    orden: parseInt($('#ciclo-'+id_ciclo+' tbody tr').length)
                                },
                                success: function(response){
                                    console.log(response)
                                    $('#ciclo-'+id_ciclo).find('button[data-id-curso="'+id_curso+'"]').attr('data-id-malla',response.data.id_malla)
                                }
                            })
                        }else{
                            $.confirm({
                                title: 'Atención',
                                content: 'Esta seguro de eliminar este curso?',
                                buttons: {
                                    si: function(){
                                        $(self).removeClass('btn-danger').addClass('btn-primary')
                                        $(self).html('<i class="fa fa-circle"></i>')
                                        var id_malla = $('#ciclo-'+id_ciclo).find('button[data-id-curso="'+id_curso+'"]').attr('data-id-malla')
                                        $.ajax({
                                            url: '<?= base_url('especialidades/retiraCursoMalla') ?>',
                                            type: 'POST',
                                            data: {
                                                id_curso: id_curso,
                                                id_ciclo: id_ciclo,
                                                id_malla: id_malla,
                                                id_especialidad_periodo: $('#id_especialidad_periodo').val(),
                                                id_especialidad: $('#id_especialidad').val(),
                                                id_periodo: $('#id_periodo').val()
                                            },
                                            success: function(response){
                                                console.log(response)
                                            }
                                        })
                                        //$('#'+$(self).attr('data-in')).remove()
                                        $('.tablas-ciclo tbody tr').each(function(index,item){
                                            if($(this).attr('data-in') == $(self).attr('data-in'))
                                                $(this).remove()
                                        })
                                    },
                                    no: function(){}
                                }
                            })
                        }
                    })
                    t.on('draw.dt',function(){
                        //this.setContentAppend('<div>Resultado:</div>');
                        $('button.seleccionar').unbind('click')
                        $('button.seleccionar').on('click',function(){
                            var self = this
                            var id_curso = $(this).attr('data-in')
                            if($(self).hasClass('btn-primary')){
                                var count = $('#ciclo-'+id_ciclo+' tbody tr').length
                                $('#ciclo-'+id_ciclo+' tbody').append('<tr data-in="'+$(this).attr('data-in')+'" ><td>'+(parseInt(count)+1)+'</td><td>'+$(self).attr('data-codigo')+'</td><td>'+$(self).attr('data-nombre')+'</td><td>-</td><td>'+$(self).attr('data-horas')+'</td><td>'+$(self).attr('data-creditos')+'</td><td><div class="btn-group"><button type="button" class="btn btn-danger quitar" data-id-malla="" data-id-curso="'+id_curso+'" data-id-ciclo="'+id_ciclo+'" data-id-especialidad="'+$('#id_especialidad').val()+'" data-id-esp-per="'+$('#id_especialidad_periodo').val()+'" data-toggle="tooltip" data-placement="top" title="Quitar curso de malla"><i class="fa fa-trash"></i></button></div></td></tr>')
                                actualizar_funcionalidades()
                                $(self).removeClass('btn-primary').addClass('btn-danger')
                                $(self).html('<i class="fa fa-trash"></i>')
                                $.ajax({
                                    url: '<?= base_url('especialidades/newMalla') ?>',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        id_curso: id_curso,
                                        id_ciclo: id_ciclo,
                                        id_especialidad: $('#id_especialidad').val(),
                                        id_periodo: $('#id_periodo').val(),
                                        id_especialidad_periodo: $('#id_especialidad_periodo').val(),
                                        id_turno: $('#id_turno').val(),
                                        orden: parseInt($('#ciclo-'+id_ciclo+' tbody tr').length)
                                    },
                                    success: function(response){
                                        console.log(response)
                                        $('#ciclo-'+id_ciclo).find('button[data-id-curso="'+id_curso+'"]').attr('data-id-malla',response.data.id_malla)
                                    }
                                })
                            }else{
                                $.confirm({
                                    title: 'Atención',
                                    content: 'Esta seguro de eliminar este curso?',
                                    buttons: {
                                        si: function(){
                                            $(self).removeClass('btn-danger').addClass('btn-primary')
                                            $(self).html('<i class="fa fa-circle"></i>')
                                            var id_malla = $('#ciclo-'+id_ciclo).find('button[data-id-curso="'+id_curso+'"]').attr('data-id-malla')
                                            $.ajax({
                                                url: '<?= base_url('especialidades/retiraCursoMalla') ?>',
                                                type: 'POST',
                                                data: {
                                                    id_curso: id_curso,
                                                    id_ciclo: id_ciclo,
                                                    id_malla: id_malla,
                                                    id_especialidad_periodo: $('#id_especialidad_periodo').val(),
                                                    id_especialidad: $('#id_especialidad').val(),
                                                    id_periodo: $('#id_periodo').val()
                                                },
                                                success: function(response){
                                                    console.log(response)
                                                }
                                            })
                                            //$('#'+$(self).attr('data-in')).remove()
                                            $('.tablas-ciclo tbody tr').each(function(index,item){
                                                if($(this).attr('data-in') == $(self).attr('data-in'))
                                                    $(this).remove()
                                            })
                                        },
                                        no: function(){}
                                    }
                                })
                            }
                        })
                    })
                }
            })
        })
    })
</script>