<?php
  $data=$con->myQuery("SELECT id,file_name,date_modified FROM employees_files WHERE is_deleted=0 AND employee_id=?",array($employee['id']))->fetchAll(PDO::FETCH_ASSOC);
  
  $tab=7;
?>
<?php
  Alert();
?>

  <form class='form-horizontal' action='save_emp_file.php' method="POST" enctype="multipart/form-data">
    <input type='hidden' name='employee_id' value='<?php echo !empty($employee)?$employee['id']:''; ?>'>
      <div class="form-group">
        <label for="certification_id" class="col-md-1 col-md-offset-8 control-label">File *</label>
        <div class="col-md-1">
          <input type='file' name='file' class="filestyle" data-classButton="btn btn-primary" data-input="false" data-classIcon="icon-plus" data-buttonText=" &nbsp;Select File">
        </div>
        <div class="col-md-2 text-center">
          <button type='submit' class='btn btn-success'>Upload </button>
        </div>
      </div>
  </form>

<br/>
<table id='ResultTable' class='table table-bordered table-striped'>
  <thead>
    <tr>
      <th class='text-center'>File</th>
      <th class='text-center'>Date Uploaded</th>
      <th class='text-center'>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($data as $row):
    ?>
      <tr>
        <td class='text-center'><?php echo htmlspecialchars($row['file_name'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['date_modified'])?></td>
        <td class='text-center'>
          <a href='download_file.php?id=<?php echo $row['id']?>&type=e' class='btn btn-default'><span class='fa fa-download'></span></a>
          <a href='delete.php?t=ef&id=<?php echo $row['id']?>&e_id=<?php echo $employee['id']?>&tab=<?php echo $tab;?>' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
        </td>
      </tr>
    <?php
      endforeach;
    ?>
  </tbody>
</table>