<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Tesoreria</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Tesoreria</a></li>
            <li><strong>Tupa</strong></li>
            <!--li class="active"><strong>Nuevo</strong></li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 


<div class="wrapper wrapper-content animated fadeInRight" style="padding: 10px 10px 10px !important;">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox" id="ibox_questionnaire">
                <div class="ibox-title bg-primary">
                    <h5>Conceptos de pago</h5>
                </div>
                <div class="ibox-content">
                    <div class="sk-spinner sk-spinner-double-bounce">
                        <div class="sk-double-bounce1"></div>
                        <div class="sk-double-bounce2"></div>
                    </div>
                    <form name="import_form" id="form_new_concepto" class="form-horizontal"  >
                        <div class="form-group">
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <label>Categoria de tupa</label>
                                <select class="form-control" id="categoria" name="categoria" required>
                                    <?php foreach ($categoria as $key => $value) { ?>
                                        <option value="<?= $value->id ?>"><?= $value->nombre ?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" id="id_tupa" name="id_tupa" value="0">
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <label>Nombre del concepto</label>
                                <input type="text" class="form-control" name="nombre_concepto" id="nombre_concepto" placeholder="Ingrese concepto" required>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <label>Costo</label>
                                <input type="number" class="form-control" step="0.01" name="costo_concepto" id="costo_concepto" placeholder="100.00" required>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <label>&nbsp;</label><br>
                                <button class="btn btn-primary" type="button" id="guardar"><i class="fas fa-save"></i> Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wrapper wrapper-content" style="padding: 10px 10px 10px !important;">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content listaItems">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">  
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Concepto</th>
                                    <th>Categoria</th>
                                    <th>Costo</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tupa as $key => $value) { ?>
                                <tr>
                                    <td><?= $value->id ?></td>
                                    <td><?= $value->concepto ?></td>
                                    <td><?= $value->categoria ?></td>
                                    <td><?= $value->costo ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-success editar" type="button" data-in="<?= $value->id ?>"><i class="fa fa-edit"></i></button>
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
<script>
    $(function(){
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
            "lengthMenu": "Desplegando _MENU_ Registros"
          },
          "order": [[ 1, "asc" ]],
          "columns": [
            { "width": "5%" },
            { "width": "45%" },
            { "width": "30%" },
            { "width": "15%" },
            { "width": "10%" }/*,
            { "width": "8%" },
            { "width": "8%" }*/
          ]
        })
        var row = null
        t.on('draw.dt',function(){
            $('#dataTable tbody tr td button.editar').unbind()
            $('#dataTable tbody tr td button.editar').on('click',function(){
                var tr = this
                var id = $(this).attr('data-in')
                $.confirm({
                    title: 'Consultando',
                    content: function(){
                        var self = this
                        $.ajax({
                            url: '<?= base_url('tesoreria/getPago') ?>',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_pago: id
                            }
                        }).done(function(response){
                            if(response.status === 200){
                                $('html, body').animate({
                                    scrollTop: $('#ibox_questionnaire').offset().top
                                },500)
                                var d = response.data
                                $('#categoria').val(d.id_categoria_tupa)
                                $('#nombre_concepto').val(d.nombre)
                                $('#costo_concepto').val(d.costo)
                                $('#id_tupa').val(d.id)
                                row = tr
                            }
                            self.close()
                        }).fail(function(){
                            self.setContentAppend('Error consulte con su Administrador')
                            //self.close()
                        })
                    }
                })
            })
        })
        t.fnDraw()
        var registrar = function(){
            $.ajax({
                url: '<?= base_url('tesoreria/newConcepto') ?>',
                type: 'POST',
                data: $('#form_new_concepto').serialize(),
                success: function(response){
                    if(JSON.parse(response).status == 200){
                        var d = JSON.parse(response).data
                        if($('#id_tupa').val() == 0)
                            t.fnAddData([
                                parseInt(t.api().rows().count())+1,
                                d.nombre,
                                $('#categoria option:selected').text(),
                                parseFloat(d.costo).toFixed(2),
                                '<div class="btn-group"><button class="btn btn-success editar" type="button" data-in="'+d.id+'"><i class="fa fa-edit"></i></button></div>'
                            ])
                        else{
                            //$(row).parent().parent().parent().remove()
                            console.log(t.api().row(row))
                            t.api().row($(row).parent().parent().parent()).remove()
                            t.fnAddData([
                                d.id,
                                d.nombre,
                                $('#categoria option:selected').text(),
                                parseFloat(d.costo).toFixed(2),
                                '<div class="btn-group"><button class="btn btn-success editar" type="button" data-in="'+d.id+'"><i class="fa fa-edit"></i></button></div>'
                            ])
                            t.fnDraw()
                        }
                        $('#nombre_concepto').val('')
                        $('#costo_concepto').val('')
                        $('#nombre_concepto').focus()
                    }
                }
            })
        }
        $('#form_new_concepto').on('keyup',function(e){
            if(e.which == 13){
                $('#form_new_concepto').validate().settings.ignore = ":disabled,:hidden"
                if(!$('#form_new_concepto').valid())
                    return false
                $.confirm({
                    title: 'Atención',
                    content: 'Esta seguro de los datos ingresados?',
                    buttons: {
                        si: {
                            text: 'Guardar',
                            btnClass: 'btn-blue',
                            keys: ['enter'],
                            action: function(){
                                registrar()
                            },
                        },
                        no: function(){}
                    }
                })
            }
        })
        $('#guardar').on('click',function(){
            $('#form_new_concepto').validate().settings.ignore = ":disabled,:hidden"
            if(!$('#form_new_concepto').valid())
                return false
            $.confirm({
                title: 'Atención',
                content: 'Esta seguro de los datos ingresados?',
                buttons: {
                    si: function(){
                        registrar()
                    },
                    no: function(){}
                }
            })
        })
    })
</script>