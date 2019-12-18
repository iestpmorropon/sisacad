<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Alumnos</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("dashboard");?> ">Internos</a></li>
            <li>Alumnos</li>
            <li class="active"><strong>Traslado</strong></li>
            <!--li>Internos</li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div>
<div class="row wrapper border-bottom white-bg page-heading" style="padding-top: 10px;">
    <div class="col-lg-12">
        <div class="card text-center">
          <div class="tabs-container">
            <ul class="nav nav-tabs">
              <li class="active">
                <a data-toggle="tab" class="nav-link " href="#panel-interno" data-in="panel-interno">Traslado Interno</a>
              </li>
              <li>
                <a data-toggle="tab" class="nav-link" href="#panel-externo" data-in="panel-externo">Traslado Externo</a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" role="tabpanel" id="panel-interno">
                <div class="panel-body">
                  <div class="row">
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
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
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
              <div class="tab-pane" role="tabpanel" id="panel-externo">
                <div class="panel-body">
                  <div class="panel-body">
                  <div class="row">
                    <div class="form-group">
                      <div class="col-sm-6 col-lg 6 col-md-6">
                        <label>Alumno</label>
                        <input type="text" class="form-control" placeholder="Ingrese DNI, nombre o apellidos para la busqueda." id="alumno_">
                        <input type="hidden" id="id_alumno_matricula_" name="id_alumno_matricula_" value="0">
                        <input type="hidden" id="id_alumno_" value="0">
                      </div>
                      <div class="col-lg-2 col-sm-2 col-md-2">
                        <label>&nbsp;</label><br>
                        <a href="<?= base_url('alumnos/trasladoExterno') ?>" class="btn btn-primary" id="siguiente">Siguiente <i class="fa fa-arrow-right"></i></a>
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
      $('#alumno_').autocomplete({
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
            },
            onSearchStart: function(q){},
            onSearchComplete: function(q,suggestions){}
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
    $('#matricular').on('click',function(){
      $.confirm({
        title: 'Matricula',
        columnClass: 'col-md-6 col-md-offset-3',
        content: function(){
          var self = this
          return $.ajax({
            url: '<?= base_url('alumnos/preparaTraslado') ?>',
            method: 'POST',
            dataType: 'json',
            data: {
              cod_alumno: $('#id_alumno').val()
            }
          }).done(function(response){
            if(response.status == 200){
              var p = response.data.periodo
              var e = response.data.especialidades
              var options = '<option>Seleccione especialidad</option>'
              for(var i in e){
                options += '<option value="'+e[i].id_especialidad+'">'+e[i].nombre+'</option>'
              }
              self.setContentAppend('<form id="form-register-traslado"><div class="row"><div class="col-lg-6 col-md-6 col-sm-6">Periodo: </div><input type="hidden" class="id_periodo_activo" value="'+p.id+'"><div class="col-lg-6 col-md-6 col-sm-6"><label>'+p.nombre+'</label></div></div><div class="row"><div class="col-lg-6 col-md-6 col-sm-6">Especialidad: </div><div class="col-lg-6 col-md-6 col-sm-6"><select class="form-control id_especialidad" name="especialidad" required id="especialidad_charge">'+options+'</select></div></div><br><div class="row"><div class="col-lg-6 col-md-6 col-sm-6">Turno: </div><div class="col-lg-6 col-md-6 col-sm-6"><select class="form-control id_turno" required name="turno" id="turno_charge"><option>Seleccione Turno</option></select></div></div><br><div class="row"><div class="col-lg-6 col-md-6 col-sm-6">Ciclo: </div><div class="col-lg-6 col-md-6 col-sm-6"><select class="form-control id_ciclo" required name="ciclo" id="ciclo_charge"><option>Seleccione Ciclo</option></select></div></div><br><div class="row"><div class="col-lg-6 col-md-6 col-sm-6">Plan de estudios: </div><div class="col-lg-6 col-md-6 col-sm-6"><select class="form-control plan" name="plan" required id="plan_charge"></select></div></div><br><div class="row"><div class="col-lg-6 col-md-6 col-sm-6">Expediente Ingreso</div><div class="col-lg-6 col-md-6 col-sm-6"><input type="text" class="form-control expediente_ingreso" name="expediente" placeholder="NNNN/AAAA"></div></div></form>')
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
        contentLoaded: function(data, status, xhr){
            //self.setContentAppend('<h2>Resultado:</h2>');
            //this.setContentAppend('<div>Resultado:</div>');
        },

        onContentReady: function(){
          var id_periodo = this.$content.find('.id_periodo_activo').val()
          var selfthis = this

          this.$content.find('.id_ciclo').on('change',function(){
            
            var id_ciclo = $(this).val()
            var id_turno = selfthis.$content.find('.id_turno').val()
            var id_especialidad = selfthis.$content.find('.id_especialidad').val()
            $.confirm({
              title: 'Consultando',
              content: function(){
                var self2 = this
                return $.ajax({
                  url: '<?= base_url('alumnos/cargaInformacionTraslado4') ?>',
                  method: 'POST',
                  dataType: 'json',
                  data: {
                    id_especialidad: id_especialidad,
                    id_periodo: id_periodo,
                    id_turno: id_turno,
                    id_ciclo: id_ciclo
                  }
                }).done(function(response){

                  if(response.status == 200){
                    self2.close()
                    var pers = response.data.periodos_anteriores
                    console.log(response.data)
                    $('#plan_charge').html('')
                    for(var i in pers){
                      $('#plan_charge').append('<option value="'+pers[i].id+'">'+pers[i].nombre+'</option>')
                    }
                  }
                  else{
                    self2.close()
                    toastr.error(response.message)
                  }
                }).fail(function(){
                  self2.close()
                  toastr.error('Error consulte con su Administrador')
                })
              }
            })
          })

          this.$content.find('.id_turno').on('change',function(){
            var id_turno = $(this).val()
            var id_especialidad = selfthis.$content.find('.id_especialidad').val()
            $.confirm({
              title: 'Consultando',
              content: function(){
                var self2 = this
                return $.ajax({
                  url: '<?= base_url('alumnos/cargaInformacionTraslado3') ?>',
                  method: 'POST',
                  dataType: 'json',
                  data: {
                    id_especialidad: id_especialidad,
                    id_turno: id_turno,
                    id_periodo: id_periodo
                  }
                }).done(function(response){
                  if(response.status == 200){
                    self2.close()
                    var cls = response.data.ciclos
                    $('#ciclo_charge').html('')
                    for(var i in cls){
                      $('#ciclo_charge').append('<option value="'+cls[i].id_ciclo+'">'+cls[i].ciclo+'</option>')
                    }
                    var pers = response.data.periodos_anteriores
                    $('#plan_charge').html('')
                    for(var i in pers){
                      $('#plan_charge').append('<option value="'+pers[i].id+'">'+pers[i].nombre+'</option>')
                    }
                  }
                  else{
                    self2.close()
                    toastr.error(response.message)
                  }
                }).fail(function(){
                  self2.close()
                  toastr.error('Error consulte con su Administrador')
                })
              }
            })
          })
          this.$content.find('.id_especialidad').on('change',function(){
            var id_especialidad = $(this).val()
            $.confirm({
              title: 'Consultando',
              content: function(){
                var self1 = this
                return $.ajax({
                  url: '<?= base_url('alumnos/cargaInformacionTraslado2') ?>',
                  method: 'POST',
                  dataType: 'json',
                  data: {
                    id_especialidad: id_especialidad,
                    id_periodo: id_periodo
                  }
                }).done(function(response){
                  if(response.status == 200){
                    self1.close()
                    var trs = response.data.turnos
                    $('#turno_charge').html('')
                    for(var i in trs){
                      $('#turno_charge').append('<option value="'+trs[i].id_turno+'">'+trs[i].turno+'</option>')
                    }
                    var cls = response.data.ciclos
                    $('#ciclo_charge').html('')
                    for(var i in cls){
                      $('#ciclo_charge').append('<option value="'+cls[i].id_ciclo+'">'+cls[i].ciclo+'</option>')
                    }
                    var pers = response.data.periodos_anteriores
                    $('#plan_charge').html('')
                    for(var i in pers){
                      $('#plan_charge').append('<option value="'+pers[i].id+'">'+pers[i].nombre+'</option>')
                    }
                  }
                  else{
                    self1.close()
                    toastr.error(response.message)
                  }
                }).fail(function(){
                  self1.close()
                  toastr.error('Error consulte con su Administrador')
                })
              }
            })
          })
        },
        buttons: {
          matricular: {
            text: 'matricular',
            btnClass: 'btn-success',
            action: function(){
              var self = this
              var id_especialidad = this.$content.find('.id_especialidad').val()
              var id_periodo_activo = this.$content.find('.id_periodo_activo').val()
              var id_turno = this.$content.find('.id_turno').val()
              var id_ciclo = this.$content.find('.id_ciclo').val()
              var id_periodo_prom = this.$content.find('.plan').val()
              var expediente = this.$content.find('.expediente_ingreso').val()
              $.confirm({
                title: 'Matricula',
                content: function(){
                  var self2 = this
                  return $.ajax({
                    url: '<?= base_url('alumnos/matriculaTraslado') ?>',
                    dataType: 'json',
                    method: 'POST',
                    data: {
                      id_especialidad: id_especialidad,
                      id_periodo_activo: id_periodo_activo,
                      id_periodo_promocion: id_periodo_prom,
                      id_turno: id_turno,
                      id_ciclo: id_ciclo,
                      cod_alumno: $('#id_alumno').val(),
                      expediente_ingreso: expediente
                    }
                  }).done(function(response){
                    if(response.status == 200){
                      self2.close()
                      self.close()
                      toastr.success(response.message)
                      $.alert({
                        title: 'Matricula',
                        content: response.message,
                        buttons: {
                          imprimir: {
                            text: 'imprimir',
                            btnClass: 'btn-warning',
                            action: function(){
                              window.open('<?php echo base_url("alumnos/impresionnuevo/"); ?>'+$('#id_alumno').val()+'/'+id_ciclo,'_blank' )
                              return false
                            }
                          },
                          ok: function(){}
                        }
                      })
                    }
                    else{
                      self2.close()
                      toastr.error(response.message)
                    }
                  }).fail(function(){
                    self2.close()
                    toastr.error('Error consulte con su Administrador')
                  })
                }
              })
            }
          },
          cancelar: function(){}
        }
      })
    })
  })
</script>
<!--
  <div class="row"><div class="col-lg-6 col-md-6 col-sm-6"></div><div class="col-lg-6 col-md-6 col-sm-6"></div></div>
  -->