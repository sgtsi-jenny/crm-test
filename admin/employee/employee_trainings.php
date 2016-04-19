<?php
  $data=$con->myQuery("SELECT et.id,t.name,t.location,t.topic,t.training_date,t.bond_months FROM employees_trainings et JOIN trainings t ON et.training_id=t.id WHERE et.is_deleted=0 AND et.employee_id=?",array($employee['id']))->fetchAll(PDO::FETCH_ASSOC);
  if(!empty($_GET['eeh_id'])){
    $record=$con->myQuery("SELECT id,training_id FROM employees_trainings et WHERE employee_id=? AND id=? LIMIT 1",array($employee['id'],$_GET['eeh_id']))->fetch(PDO::FETCH_ASSOC);
  }
  $trainings=$con->myQuery("SELECT id,CONCAT(name,' - ',training_date)as name FROM trainings WHERE id NOT IN (SELECT training_id FROM employees_trainings WHERE employee_id=? AND is_deleted=0) AND is_deleted=0",array($employee['id']))->fetchAll(PDO::FETCH_ASSOC);

  $tab=5;
?>
<?php
  $has_error=FALSE;
  if(!empty($_SESSION[WEBAPP]['Alert']) && $_SESSION[WEBAPP]['Alert']['Type']=="danger"){
    $has_error=TRUE;
  }
  Alert();
?>
<div class='text-right'>
<button class='btn btn-success' data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">Toggle Form </button>
</div>
<br/>
<div id='collapseForm' class='collapse'>
  <form class='form-horizontal' action='save_emp_trainings.php' method="POST" >
    <input type='hidden' name='employee_id' value='<?php echo !empty($employee)?$employee['id']:''; ?>'>
    <input type='hidden' name='id' value='<?php echo !empty($record)?$record['id']:''; ?>'>
      <div class="form-group">
        <label for="training_id" class="col-md-2 control-label">Training *</label>
        <div class="col-md-8">
          <select name='training_id' class='form-control select2' data-placeholder="Select Skill" <?php echo !(empty($record))?"data-selected='".$record['training_id']."'":NULL ?> style='width:100%' required>
            <?php
              echo makeOptions($trainings);
            ?>
          </select>
        </div>
        <div class="col-md-2 text-center">
          <a href='frm_employee.php?id=<?php echo $employee['id']?>&tab=<?php echo $tab?>' class='btn btn-default'>Cancel</a>
          <button type='submit' class='btn btn-success'>Add </button>
        </div>
      </div>
      
  </form>
</div>
<br/>
<table id='ResultTable' class='table table-bordered table-striped'>
  <thead>
    <tr>
      <th class='text-center'>Name</th>
      <th class='text-center'>Location</th>
      <th class='text-center'>Topic</th>
      <th class='text-center'>Training Date</th>
      <th class='text-center'>Bond Months</th>
      <th class='text-center'>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($data as $row):
    ?>
      <tr>
        <td class='text-center'><?php echo htmlspecialchars($row['name'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['location'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['topic'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['training_date'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['bond_months'])?></td>
        <td class='text-center'>
          <a href='delete.php?t=et&id=<?php echo $row['id']?>&e_id=<?php echo $employee['id']?>&tab=<?php echo $tab;?>' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
        </td>
      </tr>
    <?php
      endforeach;
    ?>
  </tbody>
</table>
<?php 
  if($has_error===TRUE || !empty($record)):
?>
<script type="text/javascript">
  $(function(){
    $('#collapseForm').collapse({
      toggle: true
    })    
  });
</script>

<?php
  endif;
?>