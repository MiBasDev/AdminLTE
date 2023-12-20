
<div class="row">    
    <?php
    if (isset($isOk) && $isOk) {
        ?>
        <div class="col-12">
            <div class="alert alert-success">
                ¡Datos procesados correctamente!
            </div>
        </div>
        <?php
    }
    ?>
    <div class="col-12">
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Formulario cálculos de notas</h6>                                    
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <form method="post" action="/calculos-notas">  
                    <div class="mb-3">
                        <label for="textarea">Alumnos y notas:</label>
                        <textarea class="form-control" id="textarea" name="textarea" rows="3"><?php echo isset($input['textarea']) ? $input['textarea'] : ''; ?></textarea>
                        <p class="text-danger"><?php echo isset($errores['texto']) ? implode("<br>", $errores['texto']) : ''; ?></p>
                    </div>
                    <div class="mb-3">
                        <input type="submit" value="Enviar" name="enviar" class="btn btn-primary"/>
                    </div>
                    <?php
                    // Sacamos por pantalla los valores de la tabla si están 
                    // bien introducidos.
                    if (isset($isOk) && $isOk) {
                        $firstRow = true;
                        $tabla = '<table class="table table-bordered table-striped">';
                        foreach ($datosTabla as $numberRow => $row) {
                            $tabla .= "<tr>";
                            foreach ($row as $column) {
                                $firstRow === true ? $tabla .= "<th>$column</th>" : $tabla .= "<td>$column</td>";
                            }
                            if ($firstRow === true) {
                                $firstRow = false;
                            }
                            $tabla .= "</tr>";
                        }
                        $tabla .= '</table>';
                        echo $tabla;

                        $todoAprobado = [];
                        $dejanUnaOMas = [];
                        $promocionan = [];
                        $noPromocionan = [];
                        foreach ($alumnos as $nombre => $notas) {
                            if ($notas['suspenso'] == 0) {
                                array_push($todoAprobado, $nombre);
                            } else {
                                array_push($dejanUnaOMas, $nombre);
                            }
                            if ($notas['suspenso'] <= 1) {
                                array_push($promocionan, $nombre);
                            } else {
                                array_push($noPromocionan, $nombre);
                            }
                        }
                        ?>
                        <div class="row">
                            <div class="col-3">
                                <div class="alert alert-success">
                                    <h4><span style="text-decoration:underline;">TODO APROBADO</span>:</h4>
                                    <ul>
                                        <?php
                                        foreach ($todoAprobado as $nombre) {
                                            echo "<li>$nombre</li>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="alert alert-warning">
                                    <h4><span style="text-decoration:underline;">ALGUNA SUSPENSA</span>:</h4>
                                    <ul>
                                        <?php
                                        foreach ($dejanUnaOMas as $nombre) {
                                            echo "<li>$nombre</li>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="alert alert-info">
                                    <h4><span style="text-decoration:underline;">PROMOCIONAN</span>:</h4>
                                    <ul>
                                        <?php
                                        foreach ($promocionan as $nombre) {
                                            echo "<li>$nombre</li>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="alert alert-danger">
                                    <h4><span style="text-decoration:underline;">NO PROMOCIONAN</span>:</h4>
                                    <ul>
                                        <?php
                                        foreach ($noPromocionan as $nombre) {
                                            echo "<li>$nombre</li>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>                        
</div>


