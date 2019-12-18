<div class="wrapper wrapper-content">
    
    <?php if($user_rol == 'Administrador'){ ?>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-4">
                <div class="widget style1 red-bg">
                    <div class="row">
                        <div class="col-sm-3">
                            <i class="far fa-clock fa-5x"></i>
                        </div>
                        <div class="col-sm-9 text-right">
                            <span>Periodo en curso</span>
                            <h2><?= !is_numeric($data['periodo']) ? $data['periodo']->nombre : '' ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4">
                <div class="widget style1 lazur-bg">
                    <div class="row">
                        <div class="col-sm-3">
                            <i class="fas fa-user-check fa-5x"></i>
                        </div>
                        <div class="col-sm-9 text-right">
                            <span>Nuevos Ingresantes</span>
                            <h2><?= $data['cantidad_cachimbos'] ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4">
                <div class="widget style1 navy-bg">
                    <div class="row">
                        <div class="col-sm-3">
                            <i class="fa fa-user fa-5x"></i>
                        </div>
                        <div class="col-sm-9 text-right">
                            <span>Estudiantes Inscritos</span>
                            <h2><?= $data['cantidad_estudiantes'] ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4">
                <div class="widget style1 yellow-bg">
                    <div class="row">
                        <div class="col-sm-3">
                            <i class="fas fa-door-open fa-5x"></i>
                        </div>
                        <div class="col-sm-9 text-right">
                            <span>Secciones Abiertas</span>
                            <h2><?= $data['cantidad_secciones'] ?></h2>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content" style="height: auto;">
                        <div id="calendario" style="height: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if($user_rol == 'Profesor'){ ?>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-4">
                <div class="widget style1 lazur-bg">
                    <div class="row">
                        <div class="col-sm-3">
                            <i class="fas fa-user-check fa-5x"></i>
                        </div>
                        <div class="col-sm-9 text-right">
                            <span>Nuevos Ingresantes</span>
                            <h2><?= $data['cantidad_cachimbos'] ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4">
                <div class="widget style1 navy-bg">
                    <div class="row">
                        <div class="col-sm-3">
                            <i class="fa fa-user fa-5x"></i>
                        </div>
                        <div class="col-sm-9 text-right">
                            <span>Estudiantes Inscritos</span>
                            <h2><?= $data['cantidad_estudiantes'] ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4">
                <div class="widget style1 yellow-bg">
                    <div class="row">
                        <div class="col-sm-3">
                            <i class="fas fa-door-open fa-5x"></i>
                        </div>
                        <div class="col-sm-9 text-right">
                            <span>Secciones Abiertas</span>
                            <h2><?= $data['cantidad_secciones'] ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4">
                <div class="widget style1 red-bg">
                    <div class="row">
                        <div class="col-sm-3">
                            <i class="fas fa-book fa-5x"></i>
                        </div>
                        <div class="col-sm-9 text-right">
                            <span>Cursos a cargo</span>
                            <h2><?= $data['cantidad_cursos'] ?></h2>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <?php } ?>
</div>    
<script>
    $(function(){
        <?php if($user_rol == 'Administrador'){ ?>
        var calendarEl = document.getElementById('calendario');

        var calendar = null 

        calendar = new FullCalendar.Calendar(calendarEl, {
          locale: 'es',
          plugins: [ 'dayGrid' ]
        });
        calendar.render();
        <?php } ?>
    })
</script>