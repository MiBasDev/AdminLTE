
<div class="row">    
    <?php
    if (isset($isOk) && $isOk) {
        ?>
        <div class="col-12">
            <div class="alert alert-success">
                ¡Registro guardado correctamente!
            </div>
        </div>
        <div class="col-12">
            <div class="alert alert-dark">
                <?php echo isset($result) ? $result : ''; ?>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="col-12">
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Formulario contar letras</h6>                                    
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <!--<form action="./?sec=formulario" method="post">                   -->
                <form method="post" action="/contar-letras">  
                    <div class="mb-3">
                        <label for="username">Letras:</label>
                        <input class="form-control" id="letters" type="text" name="letters" placeholder="Introduce una palabra" value="<?php echo isset($input['letters']) ? $input['letters'] : ''; ?>">                        
                        <p class="text-danger"><?php echo isset($errores['letters']) ? $errores['letters'] : ''; ?></p>
                    </div>
                    <div class="mb-3">
                        <input type="submit" value="Enviar" name="enviar" class="btn btn-primary"/>
                    </div>
                </form>
            </div>
        </div>
    </div>                        
</div>


