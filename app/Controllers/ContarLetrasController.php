<?php

namespace Com\Daw2\Controllers;

class ContarLetrasController extends \Com\Daw2\Core\BaseController {

    function mostrarFormulario(): void {
        $data = [];
        $data['titulo'] = 'Contar letras';
        $data['seccion'] = 'contar-letras';

        $this->view->showViews(array('templates/header.view.php', 'contar-letras.view.php', 'templates/footer.view.php'), $data);
    }

    function procesarFormulario(): void {
        $data = [];
        $data['titulo'] = 'Contar letras';
        $data['seccion'] = 'contar-letras';

        $data['errores'] = $this->checkForm($_POST);

        $data['isOk'] = count($data['errores']) === 0;
        //$data['input'] = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $data['input'] = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        if (count($data['errores']) === 0) {
            $data['result'] = 'La longitud del string "' . $data['input']['letters'] . '" es de ' . strlen($data['input']['letters']) . ' dígitos.';
        }

        $this->view->showViews(array('templates/header.view.php', 'contar-letras.view.php', 'templates/footer.view.php'), $data);
    }

    private function checkForm(array $post): array {
        $errores = [];
        if (empty($post['letters'])) {
            $errores['letters'] = 'Este campo es obligatorio';
        } else if (!preg_match('/^[a-zA-Z0-9]/', $post['letters'])) {
            $errores['letters'] = 'Debes escribir letras.';
        }

        return $errores;
    }
}
