<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Alumnos</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("especialidades");?> ">Alumnos</a></li>
            <li>Evaluaciones</li>
            <li class="active"><strong>Historial</strong></li>
            <!--li ><strong>Nuevo</strong></li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div>
<div class="tabs-container">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active">
            <a class="nav-link " data-toggle="tab" href="#regulares">Regulares</a>
        </li>
        <li>
            <a href="#auxiliares" class="nav-link" data-toggle="tab">No regulares axuliares</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="regulares">
            <?php if(!is_numeric($periodos)) foreach ($periodos as $key => $value) { ?>
                <div class="panel-body">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="ibox-title bg-primary">
                            <h3><?= $value->ciclo.' - Periodo: '.$value->periodo ?></h3>
                            <?php $promedio = 0; $ponderado = 0; $puntaje = 0;
                            $conteo = 0;
                            $suma = 0;
                            $creditos = 0;
                            $aprobados = 0;
                            foreach ($value->cursos as $ky => $v) if(is_numeric($v->valor_nota)) {
                                $suma += $v->valor_nota;
                                $puntaje += $v->creditos*$v->valor_nota;
                                $conteo += 1;
                                $creditos += $v->creditos;
                                if((int)$v->valor_nota >= (int)$v->eval_minima)
                                    $aprobados += 1;
                            }
                            $promedio = $suma/$conteo;
                            $ponderado = $puntaje/$creditos;
                             ?>
                        </div>
                        <div>
                            <div class="col-lg-6 col-md-6 col-sm-6">Cantidad de Cursos: <label><?= number_format(count($value->cursos),0,'.','') ?></label></div>
                            <div class="col-lg-6 col-md-6 col-sm-6">Cursos Aprobados: <label><?= number_format($aprobados,0,'.','') ?></label></div>
                            <div class="col-lg-6 col-md-6 col-sm-6">Promedio: <label><?= number_format($promedio,2,'.','') ?></label></div>
                            <div class="col-lg-6 col-md-6 col-sm-6">Puntaje: <label><?= number_format($puntaje,2,'.','') ?></label></div>
                            <div class="col-lg-6 col-md-6 col-sm-6">Promedio Ponderado: <label><?= number_format($ponderado,2,'.','') ?></label></div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <table class="table table-striped table-bordered table-hover tablas-ciclo" >
                            <thead>
                                <tr>
                                    <th colspan="6">Regulares</th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>Curso</th>
                                    <th>Modulo</th>
                                    <th>Creditos</th>
                                    <th>Nota</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!is_numeric($value->cursos)) foreach ($value->cursos as $k => $val) { ?>
                                    <tr>
                                        <td><?= $val->codigo ?></td>
                                        <td><?= $val->curso ?></td>
                                        <td><?= $val->modulo ?></td>
                                        <td><?= $val->creditos ?></td>
                                        <td><?= $val->valor_nota ?></td>
                                        <td></td>
                                    </tr>
                                <?php } ?>
                                <?php if(!is_numeric($value->no_regulares)){ ?>
                                    <tr>
                                        <th colspan="6">No Regulares</th>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <?php if(!is_numeric($value->no_regulares)){ ?> 
                                <tbody>
                                    <?php foreach ($value->no_regulares as $k => $val) { ?>
                                        <tr>
                                            <td><?= $val->codigo ?></td>
                                            <td><?= $val->curso ?></td>
                                            <td><?= $val->modulo ?></td>
                                            <td><?= $val->creditos ?></td>
                                            <td><?= $val->valor_nota ?></td>
                                            <td><?= $val->tipo_nota ?></td>
                                        </tr>
                                    <?php } ?>   
                                </tbody>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="tab-pane" id="auxiliares">
            <div class="panel-body">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <table class="table table-striped table-bordered table-hover tablas-ciclo" >
                        <thead>
                            <tr>
                                <th>Periodo</th>
                                <th>Cod. Curso</th>
                                <th>Curso</th>
                                <th>Ciclo</th>
                                <th>Creditos</th>
                                <th>Nota</th>
                                <th>Tipo nota</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!is_numeric($auxiliares)) foreach ($auxiliares as $key => $value) { ?>
                                <tr>
                                    <td><?= $value->periodo ?></td>
                                    <td><?= $value->codigo ?></td>
                                    <td><?= $value->curso ?></td>
                                    <td><?= $value->ciclo ?></td>
                                    <td><?= $value->creditos ?></td>
                                    <td><?= $value->valor_nota ?></td>
                                    <td><?= $value->tipo_nota ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>