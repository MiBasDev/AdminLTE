<?php

namespace Com\Daw2\Controllers;

class CalculosNotasController extends \Com\Daw2\Core\BaseController {

    function mostrarFormulario(): void {
        $data = [];
        $data['titulo'] = 'Cálculos de notas';
        $data['seccion'] = 'calculos-notas';

        $this->view->showViews(array('templates/header.view.php', 'calculos-notas.view.php', 'templates/footer.view.php'), $data);
    }

    function procesarFormulario(): void {
        $data = [];
        $data['titulo'] = 'Cálculos de notas';
        $data['seccion'] = 'calculos-notas';

        $data['errores'] = $this->checkForm($_POST);

        $data['isOk'] = count($data['errores']) === 0;
        //$data['textarea'] = filter_input_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $data['input'] = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        if (count($data['errores']) === 0) {
            $textarea = json_decode($_POST['textarea'], true);

            // Array con los datos de la cabecera.
            $cabecera = ['Módulo', 'Media', 'Aprobados', 'Suspensos', 'Máximo', 'Mínimo'];

            // Lo introducimos en los datos de la tabla.
            $data['datosTabla'] = [$cabecera];

            foreach ($textarea as $nombreMateria => $alumnos) {
                // Variables para los cálculos de los datos.
                $media = 0;
                $mediaAlumno = 0;
                $contadorNotasAlumnos = 0;
                $aprobados = 0;
                $suspensos = 0;
                $max = PHP_INT_MIN;
                $min = PHP_INT_MAX;
                $alumnoMax = "";
                $alumnoMin = "";

                // Procesamos los datos.
                foreach ($alumnos as $key => $notasAlumno) {
                    foreach ($notasAlumno as $nota) {
                        $media += $nota;
                        $mediaAlumno += $nota;
                        if ($nota >= $max) {
                            $max = $nota;
                            $alumnoMax = "$key: " . round($nota);
                        }
                        if ($nota <= $min) {
                            $min = $nota;
                            $alumnoMin = "$key: " . round($nota);
                        }
                        $contadorNotasAlumnos++;
                    }
                    $mediaAlumno /= count($notasAlumno);
                    if ($mediaAlumno >= 5) {
                        $aprobados++;
                    } else {
                        $suspensos++;
                    }
                    $mediaAlumno = 0;
                }

                // Creamos un array asociativo para los valores de las variables.
                $row[$nombreMateria] = [];
                $row[$nombreMateria]['nombreMateria'] = ucfirst($nombreMateria);
                $row[$nombreMateria]['media'] = round($media / $contadorNotasAlumnos, 2);
                $row[$nombreMateria]['aprobados'] = $aprobados;
                $row[$nombreMateria]['suspensos'] = $suspensos;
                $row[$nombreMateria]['max'] = $alumnoMax;
                $row[$nombreMateria]['min'] = $alumnoMin;

                // Lo metemos en los datos de la tabla.
                array_push($data['datosTabla'], $row[$nombreMateria]);
            }

            $data['alumnos'] = [];
            foreach ($textarea as $nombreMateria => $alumnos) {
                foreach ($alumnos as $nombreAlumno => $notasAlumno) {
                    $media = 0;
                    foreach ($notasAlumno as $nota) {
                        $media += $nota;
                    }
                    $media /= count($notasAlumno);
                    if (!isset($data['alumnos'][$nombreAlumno])) {
                        $data['alumnos'][$nombreAlumno] = [
                            'aprobado' => 0,
                            'suspenso' => 0
                        ];
                    }
                    $media >= 5 ? $data['alumnos'][$nombreAlumno]['aprobado']++ : $data['alumnos'][$nombreAlumno]['suspenso']++;
                }
            }
        }

        $this->view->showViews(array('templates/header.view.php', 'calculos-notas.view.php', 'templates/footer.view.php'), $data);
    }

    private function checkForm(array $post): array {
        $datos = json_decode($post['textarea'], true);
        $errores = [];
        $erroresTexto = [];

        if (empty($post['textarea'])) {
            $errores['notas'] = 'Este campo es obligatorio';
        }

        if (is_null($datos)) {
            $erroresTexto[] = 'No se ha enviado un Json válido';
        } else {
            foreach ($datos as $nombreMateria => $datosMateria) {
                if (!is_string($nombreMateria)) {
                    $erroresTexto[] = "'$nombreMateria' no es un nombre de asigantura válido.";
                }
                if (!is_array($datosMateria)) {
                    $erroresTexto[] = "'$nombreMateria' no tiene asignado un array de datos.";
                } else {
                    foreach ($datosMateria as $alumno => $notas) {
                        if (!is_string($alumno)) {
                            $erroresTexto[] = "Asignatura: '$nombreMateria', el alumno '$alumno' no tiene un nombre válido.";
                        }
                        foreach ($notas as $examen => $nota) {
                            if (!is_numeric($nota)) {
                                $erroresTexto[] = "Asignatura: '$nombreMateria', el alumno '$alumno' en el '" . ($examen + 1) . "º examen' tiene como nota '$nota' que no es válida.";
                            } else {
                                if ($nota < 0 || $nota > 10) {
                                    $erroresTexto[] = "Asignatura: '$nombreMateria', el alumno '$alumno' en el '" . ($examen + 1) . "º examen' tiene como nota '$nota' que no es válida.";
                                }
                            }
                        }
                    }
                }
            }
        }

        if (count($erroresTexto) > 0) {
            $errores['texto'] = $erroresTexto;
        }

        return $errores;
    }
}
