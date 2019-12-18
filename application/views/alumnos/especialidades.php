<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2 class="tituloPaginaActual">Alumnos</h2>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("especialidades");?> ">Alumnos</a></li>
            <li class="active"><strong>Evaluaciones</strong></li>
            <!--li ><strong>Nuevo</strong></li-->
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div>
<div class="wrapper wrapper-content animated fadeInRight ecommerce" style="padding-bottom: 0px !important;">
    <div class="ibox" id="box_import_invoices">
        <div class="ibox-title bg-primary">
            <h5>Especialidades curzadas</h5> 
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="ibox-content m-b-sm border-bottom">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <table class="table table-striped table-bordered table-hover tablas-ciclo" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Periodo Ingreso</th>
                            <th>Ult. Ciclo</th>
                            <th>Turno</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!is_numeric($especialidades)) foreach ($especialidades as $key => $val) { ?>
                            <tr>
                                <td><?= ($key+1) ?></td>
                                <td><?= $val->especialidad ?></td>
                                <td><?= $val->periodo ?></td>
                                <td><?= $val->ciclo ?></td>
                                <td><?= $val->turno ?></td>
                                <td><?php switch ($val->estado) {
                                	case 1:
                                		echo 'En curso';
                                		break;

                                	case 2:
                                		echo 'Repitente Inactivo';
                                		break;

                                	case 3:
                                		echo 'Repitente Activo';
                                		break;

                                	case 4:
                                		echo 'Egresado';
                                		break;
                                	
                                	default:
                                		# code...
                                		break;
                                } ?></td>
                                <td>
                                	<div class="btn-group">
                                		<a href="<?= base_url('cursos/historial/').$val->id_especialidad_periodo ?>" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Revisar"><i class="fa fa-eye"></i></a>
                                	</div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="row">&nbsp;</div>
        </div>
    </div>
</div>