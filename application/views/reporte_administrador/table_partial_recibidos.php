<table class="table table-hover"  id="miTabla">
                    <tr>
             <th>Nro traslado</th>
                        <th>Sucursal enviado</th>
                        <th>Fecha</th>
                        <th>Comentario</th>
                  
                        <th class="text-center">Acciones</th>
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): 


        ?>
        <td><?php echo $record->id_traslado ?></td>
                        <td><?php echo $record->sucursal_traslado ?></td>
                        <td><?php echo $record->fecha_actual ?></td>
                        <td><?php echo $record->comentario ?></td>
             <td class="text-center">



           
 <a class="btn btn-sm btn-info" href="<?php echo base_url().'trasladar/exportToPDF/'.$record->id_traslado; ?>" title="ticket" target="_blank"><i class="fa fa-file-text-o"></i></a>

                        </td>
       
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
