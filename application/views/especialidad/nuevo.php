<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Especialidades</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("especialidades");?> ">Especialidad</a></li>
            <li >Gestion</li>
            <li class="active"><strong>Nuevo</strong></li>
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5>Registro de Especialidad Periodo</h5> 
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="ibox-content m-b-sm border-bottom">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
	        <form name="registro_form" id="registro_form" class="form-horizontal"  >
	            <div class="form-group">
	            	<div class="col-sm-5">
	            		<label class="control-label">Nombre</label>
	            		<input type="text" class="form-control" name="especialidad" required id="nombre" placeholder="Especialidad">
	            		<input type="hidden" name="id_especialidad" value="0" id="id_especialidad">
	            	</div>
	            	<div class="col-sm-2">
	            		<label class="control-label">Codigo</label>
	            		<input type="text" class="form-control" name="codigo" required id="codigo" placeholder="Codigo">
	            	</div>
	            	<div class="col-sm-2">
	            		<label class="control-label">Vacantes</label>
	            		<input type="text" class="form-control" name="vacantes" required placeholder="400">
	            	</div>
	                <div class="col-sm-2">
	            		<label class="control-label">Periodo</label>
	                    <select class="form-control " name="id_periodo" id="id_periodo">
	                    	<option>Seleccione periodo</option>
	                    	<?php foreach ($periodos as $key => $value) { ?>
	                    		<option value="<?= $value->id ?>"><?= $value->nombre ?></option>
	                    	<?php } ?>
	                    </select>
	                </div>
	            </div>
	            <div class="form-group">
	            	<div class="col-sm-3">
	            		<div class="btn-group">
		            		<a href=javascript:history.back(1) class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Volver</a>
		            		<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
		            	</div>
	            	</div>
	            </div>
	        </form>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(function(){
		/*$('#nombre').on('blur',function(){
			var nom = $('#nombre').val().split(' ')
			if(nom.length == 2){
				$('#codigo').val(nom[0].charAt(0)+nom[1].charAt(0))
			}
			if(nom.length == 1)
				$('#codigo').val(nom[0].charAt(0)+nom[0].charAt(1))
		})*/
		$('#nombre').autocomplete({
	      serviceUrl: '<?= base_url("especialidades/autocompleteespecialidad") ?>',
	      minChars: 2,
	      dataType: 'text',
	      type: 'POST',
	      paramName: 'patron',
	      params: {
	        'patron': $(this).val()
	      },
	      onSelect: function(suggestion){
	        var datos = JSON.parse(suggestion.data)
	        $('#id_especialidad').val(datos.id)
	        $('#codigo').val(datos.codigo)
	        //$('#id_direccion').val(datos.direccion)
	      },
	      onSearchStart: function(q){},
	      onSearchComplete: function(q,suggestions){}
	    })
	    /*$('#buscar').on('click',function(){
	    	$.confirm({
	    		title: 'Especialidades',
	    		theme: 'light',
	    		columnClass: 'col-md-8 col-md-offset-2',
	    		content: function(){
	                var self = this;
	                //self.setContent('Checking callback flow');
	                return $.ajax({
	                	url: '<?= base_url("especialidades/traeEspecialidades") ?>',
                        dataType: 'json',
                        data: {
                            id_especialidad: 1
                        },
                        method: 'POST'
                    }).done(function (response) {
                    	if(response.status != 200)
                    		$.alert({
							title: 'Atención',
								content: r.message,
								type: 'red',
	                            typeAnimated: true
							})
                    	else{
	                    	self.setContentAppend('<div class="content"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12"><table class="table especialidades"><thead><tr><th>Codigo</th><th>Nombre</th><th>Seleccionar</th></head><tbody>')
	                    	var d = response.data
	                    	for(var i in d){
	                    		self.setContentAppend('<tr><td>'+d[i].codigo+'</td><td>'+d[i].nombre+'</td><td><button class="btn btn-primary" type="button" data-in="'+d[i].id+'"><i class="fa fa-circle"></i></button></td></tr>')
	                    	}
	                    	self.setContentAppend('</tbody></table></div></div></div>')
	                    }
                    })
	            },
	            buttons: {
	            	cancelar: function(){}
	            },
	            contentLoaded: function(data, status, xhr){
	                //self.setContentAppend('<h2>Resultado:</h2>');
	            },
	            onContentReady: function(){
	                //this.setContentAppend('<div>Resultado:</div>');
	                var self = this
	                $('table.especialidades tbody tr td button').on('click',function(){
	                	//var reg = $(this).parent().parent().siblings('td:first-child').html()
	                	console.log($(this).parent().parent().children('td').eq(1))
	                	$('#nombre').val($(this).parent().parent().children('td').eq(1).html())
	                	$('#codigo').val($(this).parent().parent().children('td').eq(0).html())
	                	$('#id_especialidad').val($(this).attr('data-in'))
	                	self.close()
	                })
	            }
	    	})
	    })*/
		$('#registro_form').on('submit',function(e){
			e.preventDefault()
			var da = $('#registro_form').serialize()
			$.ajax({
				url: '<?= base_url("especialidades/nueva") ?>',
				type: 'POST',
				data: da,
				success: function(data){
					var d = JSON.parse(data)
					if(d.status==200){
                        $.alert({
                            title: 'Atención',
                            content: d.message,
                            type: 'green',
                            typeAnimated: true,
                            buttons: {
                            	si: function(){
                            		window.location.href = '<?php echo base_url('especialidades'); ?>';
                            	}
                            }
                        })
					}
					else{
						$.alert({
                            title: 'Atención',
                            content: d.message,
                            type: 'red',
                            typeAnimated: true
                        })
					}
				}
			}).fail(function(){
                    $.alert({
                        title: 'Atención',
                        content: 'Error en el registro, Consulte con su Administrador',
                        type: 'red',
                        typeAnimated: true
                    })
                })
		})
	})
</script>