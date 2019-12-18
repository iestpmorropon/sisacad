<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Perfil</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("home");?> ">Home</a></li>
            <li class="active"><strong>Perfil</strong></li>
            <!--li ><strong>Nuevo</strong></li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div> 
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="row m-b-lg m-t-lg">
        <div class="col-lg-12 col-md-12 col-sm-12">
        	<div class="profile-info">
        		<div>
        			<div>
        				<h2 class="no-margins"><?= $usuario['nombre'].' '.$usuario['apell_pat'].' '.$usuario['apell_mat'] ?></h2>
        				<h4>DNI: <?= $usuario['dni'] ?></h4>
        				<small>Telefono: <?= $usuario['telefono'] == '' ? '-' : $usuario['telefono'] ?></small><br>
                        <small>Celular 1: <?= $usuario['celular_1'] == '' ? '-' : $usuario['celular_1'] ?></small><br>
                        <small>Celular 2: <?= $usuario['celular_2'] == '' ? '-' : $usuario['celular_2'] ?></small><br>
        				<small>Direccion: <?= $usuario['direccion'] == '' ? '-' : $usuario['direccion'] ?></small><br>
        				<button type="button" id="editar-persona" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Editar datos"><i class="fa fa-edit"></i> Editar</button>
        			</div>
        		</div>
        	</div>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-6 col-md-6 col-sm-6">
    		<?php if($usuario['id_rol_actual'] == 2){ ?>
    			<div class="ibox">
    				<div class="ibox-title">
    					<h3><center>Información de Alumno</center></h3>
    				</div>
    				<div class="ibox-content">
    					<h3><?= $usuario['nombre'].' '.$usuario['apell_pat'].' '.$usuario['apell_mat'] ?></h3>
    					<small>Especialidad: <?= $infor_alumno->especialidad ?></small><br>
    					<small>Ciclo <?= $infor_alumno->id_ciclo ?></small><br>
    					<small>Ultimo periodo: <?= $infor_alumno->periodo ?></small><br>
    					<small>Turno: <?= $infor_alumno->turno ?></small>
    				</div>
    			</div>
    		<?php } ?>
    	</div>
    	<div class="col-lg-6 col-md-6 col-sm-6">
    		<div class="ibox">
    			<div class="ibox-title">
    				<h3><center>Información de Usuario</center></h3>
    			</div>
    			<div class="ibox-content">
    				<small>Usuario: <label><?= $usuario['usuario'] ?></label></small><br>
    				<small>Contraseña: <label>********</label></small><br>
    				<button class="btn btn-default" id="editar-contra" type="button" data-toggle="tooltip" data-placement="top" title="Editar Contraseña"><i class="fa fa-edit"></i> Editar</button>
    			</div>
    		</div>
    	</div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('focus',".datepicker", function(){
                    $(this).datepicker({
            format: 'dd-mm-yyyy'
        });
            }); 
	$(function(){
		$('[data-toggle="tooltip"]').tooltip()
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
		$('#editar-persona').on('click',function(){
			$.confirm({
				title: 'Edición',
                columnClass: 'col-md-8 col-lg-8 col-sm-8 col-md-offset-2 col-lg-offset-2 col-sm-offset-2',
				content: function(){
                    var self = this
                    $.ajax({
                        url: '<?= base_url('home/preparaperfil') ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            usuario: '<?= $usuario['usuario'] ?>'
                        }
                    }).done(function(response){
                        if(response.status == 200){
                            var u = response.data.usuario
                            console.log(u)
                            var g = response.data.generos
                            var ec = response.data.estado_civil
                            var selec_generos = '<select class="form-control genero">'
                            for(var i in g)
                                selec_generos += '<option value="'+g[i].id+'" '+(g[i].id == u.id_genero ? 'selected' : '')+'>'+g[i].nombre+'</option>'
                            selec_generos += '</select>'
                            var selec_estado = '<select class="form-control estado_civil">'
                            for(var i in ec)
                                selec_estado += '<option value="'+ec[i].id+'" '+(ec[i].id == u.id_estado_civil ? 'selected' : '')+'>'+ec[i].nombre+'</option>'
                            selec_estado += '</select>'
                            self.setContentAppend('<form id="form-update-persona"><div class="content"><div class="row"><div class="col-lg-4 col-md-4 col-sm-4"><label>Nombres</label><input type="text" class="form-control" disabled value="'+u.nombre+'" readonly><input type="hidden" class="id_persona" value="'+u.id_persona+'"></div><div class="col-lg-4 col-md-4 col-sm-4"><label>Apell. Paterno</label><input type="text" class="form-control" disabled value="'+u.apell_pat+'" readonly></div><div class="col-lg-4 col-md-4 col-sm-4"><label>Apell. Materno</label><input type="text" class="form-control" disabled value="'+u.apell_mat+'" readonly></div><div class="col-lg-4 col-md-4 col-sm-4"><label>DNI</label><input type="text" class="form-control" disabled readonly value="'+u.dni+'"></div><div class="col-lg-4 col-md-4 col-sm-4"><label>Telefono</label><input type="number" class="form-control telefono" value="'+(u.telefono == null ? '' : u.telefono)+'"></div><div class="col-lg-4 col-md-4 col-sm-4"><label>Celular 1</label><input type="number" class="form-control celular_1" value="'+(u.celular_1 == null ? '' : u.celular_1)+'"></div><div class="col-lg-4 col-md-4 col-sm-4"><label>Celular 2</label><input type="number" class="form-control celular_2" value="'+(u.celular_2 == null ? '' : u.celular_2)+'"></div><div class="col-lg-8 col-md-8 col-sm-8"><label>Dirección</label><input type="text" class="form-control direccion" value="'+(u.direccion == null ? '' : u.direccion)+'"></div><div class="col-lg-4 col-md-4 col-sm-4"><label>Email</label><input type="email" class="form-control email" value="'+(u.email == null ? '' : u.email)+'"></div><div class="col-lg-4 col-md-4 col-sm-4"><label>Fec. Nacimiento</label><input type="text" class="form-control datepicker fch_nac" value="'+(u.fch_nac == null ? '<?= date('d-m-Y') ?>' : u.fch_nac)+'"></div><div class="col-lg-4 col-md-4 col-sm-4"><label>Genero</label>'+selec_generos+'</div><div class="col-lg-4 col-md-4 col-sm-4"><label>Estado Civil</label>'+selec_estado+'</div></div></div></form>')
                        }
                        else{
                            self.close()
                            toastr.error(response.message)
                        }
                    }).fail(function(){
                        self.close()
                        toastr.error('Error, consulte con su administrador')
                    })
                },
                buttons: {
                    guardar: {
                        text: 'guardar',
                        btnClass: 'btn-success',
                        action: function(){
                            var self = this
                            var data_persona = {
                                id_persona : self.$content.find('.id_persona').val(),
                                telefono : self.$content.find('.telefono').val(),
                                celular_1 : self.$content.find('.celular_1').val(),
                                celular_2 : self.$content.find('.celular_2').val(),
                                direccion : self.$content.find('.direccion').val(),
                                email : self.$content.find('.email').val(),
                                fch_nac : self.$content.find('.fch_nac').val(),
                                genero : self.$content.find('.genero').val(),
                                estado_civil : self.$content.find('.estado_civil').val()
                            }
                            $.confirm({
                                title: 'Atención',
                                content: 'Esta seguro de los datos ingresados?',
                                buttons: {
                                    si: function(){
                                        $.confirm({
                                            title: 'Actualizando',
                                            content: function(){
                                                var self1 = this
                                                $.ajax({
                                                    url: '<?= base_url('home/actualizarPersona') ?>',
                                                    method: 'POST',
                                                    dataType: 'json',
                                                    data: data_persona
                                                }).done(function(response){
                                                    self.close()
                                                    self1.close()
                                                    toastr.success(response.message)
                                                    window.location.reload()
                                                }).fail(function(){
                                                    self1.close()
                                                    toastr.error('Error consulte con su administrador')
                                                })
                                            }
                                        })
                                    },
                                    no: function(){}
                                }
                            })
                        }
                    },
                    cancelar: function(){}
                },
                contentLoaded: function(data, status, xhr){
                    $('.datepicker').datepicker({
                            format: 'dd-mm-yyyy',
                            container: container,
                            todayHighlight: true,
                            autoclose: true
                        })
                }
			})
		})
        $('#editar-contra').on('click',function(){
            $.confirm({
                title: 'Actualizar Contraseña',
                content: '<label>Ingrese contraseña</label><input type="password" class="form-control password" name="contra" placeholder="***********" required><label>Repita la contraseña</label><input type="password" class="form-control repeat_password" name="repeat_password" placeholder="***********" required>',
                onContentReady: function(){},
                buttons: {
                    actualizar: {
                        text: 'Actualizar',
                        btnClass: 'btn-success',
                        action: function(){
                            var self = this
                            var password = self.$content.find('.password').val()
                            var repeat_password = self.$content.find('.repeat_password').val()
                            if(password != repeat_password){
                                $.alert('Introduzca los datos correctos, contraseñas diferentes')
                                return false
                            }
                            $.confirm({
                                title: 'Atención',
                                content: 'Esta seguro de los datos ingresados?',
                                buttons:{
                                    si: function(){
                                        $.confirm({
                                            title: 'Actualizando',
                                            content: function(){
                                                var self2 = this
                                                return $.ajax({
                                                    url: '<?= base_url('home/actualizarPassword') ?>',
                                                    method: 'POST',
                                                    dataType: 'json',
                                                    data: {
                                                        password: password,
                                                        repeat_password: repeat_password
                                                    }
                                                }).done(function(response){
                                                    if(response.status == 200){
                                                        self.close()
                                                        self2.close()
                                                        toastr.success(response.message)
                                                        window.location.reload()
                                                    }
                                                    else{
                                                        self2.close()
                                                        toastr.error(response.message)
                                                    }
                                                }).fail(function(){
                                                    self2.close()
                                                    toastr.error('Error consulte con su administrador')
                                                })
                                            }
                                        })
                                    },
                                    no: function(){}
                                }
                            })
                            return false
                        }
                    },
                    cancelar: function(){}
                }
            })
        })
	})
</script>