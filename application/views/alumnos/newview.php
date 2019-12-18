<form  id="form_register" name="form_register" enctype="multipart/form-data" class="wizard-big">
    <h1>Datos personales</h1>
    <h1>Datos estudio</h1>
    <fieldset>
        <div class="row">
            <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                    <label>Nombres*</label>
                    <input style="display: block;" type="text" required class="form-control" id="nombres" name="nombre" placeholder="Juan">
            </div>
            <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                    <label>Apellido Paterno*</label>
                    <input style="display: block;" type="text" required class="form-control" id="apell_pat" name="apell_pat" placeholder="Perez">
            </div>
            <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                    <label>Apellido Materno*</label>
                    <input style="display: block;" type="text" required class="form-control" id="apell_mat" name="apell_mat" placeholder="Albeola">
            </div>
            <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                <label>DNI*</label>
                <div class="input-group">
                    <input style="display: block;" type="text" required class="form-control" id="dni" name="dni" placeholder="87654321">
                    <span class="input-group-btn">
                        <button class="btn btn-primary generar_dni" type="button" data-toggle="tooltip" data-placement="top" title="No trajo DNI generar Aleatorio" id="generar_dni"><i class="fa fa-certificate"></i></button>
                    </span>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <div class="row">
            <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                <label class="control-label">Especialidad</label>
                <select class="form-control" name="especialidad"required id="especialidad_">
                    <option>Seleccione especialidad</option>
                    <?php foreach ($especialidades as $key => $value) { ?>
                        <option value="<?= $value->id ?>"><?= $value->nombre ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-2 col-lg-2 col-md-2 col-xs-12">
                <label>Periodo</label>
                <input style="display: block;" class="form-control" readonly type="text" name="periodo" id="periodo" required value="<?= $periodo->nombre ?>">
                <input type="hidden" name="id_periodo" id="id_periodo" value="<?= $periodo->id ?>">
                <input type="hidden" name="id_especialidad_periodo" id="id_especialidad_periodo">
            </div>
            <div class="col-sm-2 col-lg-2 col-md-2 col-xs-12">
                <label>Turno</label>
                <select name="turno" class="form-control" id="turno">
                    <option value="1">Vespertino</option>
                    <!--<option value="2">Nocturno</option>-->
                </select>
            </div>
            <div class="col-sm-3 col-lg-3 col-md-3 col-xs-12">
                <label>Fecha de Ingreso</label>
                <input style="display: block;" type="text" name="fch_ingreso" class="form-control datepicker" id="fch_ingreso" value="<?= date('d/m/Y') ?>">
            </div>
            <input type="hidden" name="tipoalumno" value="<?= $tipoalumno ?>">
        </div><br>
    </fieldset>
</form>