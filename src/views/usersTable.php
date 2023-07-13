<?php
Layout::header('usuarios');
?>

    <div class="d-flex justify-content-between">
        <h4>Usuarios</h4>
    </div>

    <div class="datatable">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>id</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </tfoot>
            <tbody>

            <?php  foreach ($data as $key => $value) { ?>
            
                <tr>
                    <td><?php echo $value['id']; ?></td>
                    <td><?php echo $value['name']; ?></td>
                    <td><?php echo $value['last_name']; ?></td>
                    <td><?php echo $value['username']; ?></td>
                    <td><?php echo $value['email']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($value['timestamp_create'])); ?></td>
                    <td></td>
                </tr>
                
            <?php }  ?>

            </tbody>
        </table>
    </div>


<?php
Layout::footer();