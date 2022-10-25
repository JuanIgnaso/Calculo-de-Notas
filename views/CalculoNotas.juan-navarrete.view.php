<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $data['titulo']; ?></h1>

</div>
<?php
if(isset($data['errores']) && empty($data['errores'])){
?>
<div class="alert alert-success">
    Formato Correcto
</div>
<?php
}
?>
<!-- Content Row -->

<div class="row">

    <div class="col-12">
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo $data['div_titulo']; ?></h6>                                    
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <!--<form action="./?sec=formulario" method="post">                   -->
                <form  method="post" action="./?sec=CalculoNotas.juan_navarrete">
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1">Inserte su codigo JSON Aquí</label>
                       <textarea class="form-control" id="json_notas" name="json_notas" rows="5"><?php echo isset($data['input']['json_notas']) ? $data['input']['json_notas'] : ''; ?></textarea>
                        <p class="text-danger small"><?php echo isset($data['errores']['json_notas']) ? $data['errores']['json_notas'] : '' ?></p>

                    </div>
                    <div class="mb-3">
                        <input type="submit" value="enviar" name="enviar" class="btn btn-primary"/>
                    </div>
                </form>
            </div>
        </div>
    </div>                        
</div>

