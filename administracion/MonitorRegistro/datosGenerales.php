<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <form>
                    <div class="card-body">
                        <div id="datosUsuario">
                            <div class="mb-3">
                                <label for="nombresUsuario" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="nombresUsuario" name="nombresUsuario" placeholder="Nombres">
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <div>
                                        <label for="apellidoPaterno" class="form-label">Apellido Paterno</label>
                                        <input type="text" class="form-control" id="apellidoPaterno" name="apellidoPaterno" placeholder="Apellido Paterno">
                                    </div>
                                </div>
                                <div class="col">
                                    <div>
                                        <label for="apellidoMaterno" class="form-label">Apellido Materno</label>
                                        <input type="text" class="form-control" id="apellidoMaterno" name="apellidoMaterno" placeholder="Apellido Materno">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="celular" class="form-label" id="labelCelular">Celular</label>
                                <input type="text" class="form-control" id="celular" name="celular" placeholder="Celular" maxlength="10">
                            </div>

                            <div class="mb-3">
                                <label for="correo" class="form-label" id="labelCorreo">Correo</label>
                                <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo">
                            </div>

                            <div class="mb-3">
                                <label for="dependencia" class="form-label" id="dependencia">Dependencia</label>
                                <select class="form-control" id="dependencia" name="dependencia">
                                    <option value="NA">No aplica</option>
                                    <?php $selectDependencias = $con->query("SELECT * FROM tb_web_cv_dependencias;");
                                        if($selectDependencias)
                                        {
                                            while($row = $selectDependencias->fetch_assoc())
                                            {
                                                echo '<option value="' . $row['ID_Dependencia'] . '">' . $row['Dependencia'] . '</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <hr class="rounded-bottom">
                            <div class="mb-3">
                                <label for="area" class="form-label">Área</label>
                                <input type="text" class="form-control" id="area" name="area" placeholder="Área">
                            </div>
                            <div class="mb-3">
                                <label for="cargo" class="form-label">Cargo</label>
                                <input type="text" class="form-control" id="cargo" name="cargo" placeholder="Cargo">
                            </div>
                            <div class="mb-3">
                                <label for="noEmpleado" class="form-label">No. de empleado</label>
                                <input type="text" class="form-control" id="noEmpleado" name="noEmpleado" placeholder="No. de empleado">
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" id="guardar1" class="btn btn-success me-2" onclick="GuardarInfo_Registro()">Guardar</button>
                            <button type="button" class="btn btn-danger" onclick="cerrar_Pagina1()">Cerrar</button>
                        </div>
                    </div>
                </form>
            </div>
            <br>
        </div>
    </div>
</div>
<style>
    hr.rounded-top {
        border-top: 5px solid #d90000;
        border-radius: 5px;
    }

    hr.rounded-bottom {
        border-bottom: 5px solid #d90000;
        border-radius: 5px;
    }
</style>