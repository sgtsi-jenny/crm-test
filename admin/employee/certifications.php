<?php
  $data=$con->myQuery("SELECT ec.id,ec.institute,ec.date_given,ec.remarks,c.name as certification FROM employees_certifications ec JOIN certifications c ON ec.certification_id=c.id WHERE ec.is_deleted=0 AND ec.employee_id=?",array($employee['id']))->fetchAll(PDO::FETCH_ASSOC);
  if(!empty($_GET['ec_id'])){
    $record=$con->myQuery("SELECT id,institute,date_given,remarks,certification_id FROM employees_certifications WHERE employee_id=? AND id=? LIMIT 1",array($employee['id'],$_GET['ec_id']))->fetch(PDO::FETCH_ASSOC);
  }

  $certifications=$con->myQuery("SELECT id,name FROM certifications WHERE is_deleted=0 AND is_deleted=0",array($employee['id']))->fetchAll(PDO::FETCH_ASSOC);
  $tab=6;
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
  <form class='form-horizontal' action='save_emp_certifications.php' method="POST" >
    <input type='hidden' name='employee_id' value='<?php echo !empty($employee)?$employee['id']:''; ?>'>
    <input type='hidden' name='id' value='<?php echo !empty($record)?$record['id']:''; ?>'>
      <div class="form-group">
        <label for="certification_id" class="col-md-3 control-label">Certification *</label>
        <div class="col-md-7">
          <select name='certification_id' class='form-control select2' data-placeholder="Select Certification" <?php echo !(empty($record))?"data-selected='".$record['certification_id']."'":NULL ?> style='width:100%' required>
            <?php
              echo makeOptions($certifications);
            ?>
          </select>
        </div>
        
      </div>
      <div class="form-group">
          <label for="institute" class="col-md-3 control-label">Institute *</label>
          <div class="col-md-7">
            <input type="text" class="form-control" id="institute" placeholder="Enter Institute" name='institute' value='<?php echo !empty($record)?htmlspecialchars($record['institute']):''; ?>' required>
          </div>
      </div>
      <div class="form-group">
          <label for="date_given" class="col-md-3 control-label">Date Given *</label>
          <div class="col-md-7">
            <input type="date" class="form-control" id="date_given"  name='date_given' value='<?php echo !empty($record)?htmlspecialchars($record['date_given']):''; ?>' required>
          </div>
      </div>
      <div class="form-group">
        <label for="remarks" class="col-md-3 control-label">Remarks </label>
        <div class="col-md-7">
          <textarea class='form-control' name='remarks' id='remarks' ><?php echo !empty($record)?htmlspecialchars($record['remarks']):''; ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-10 col-md-offset-2 text-center">
          <a href='frm_employee.php?id=<?php echo $employee['id']?>&tab=<?php echo $tab?>' class='btn btn-default'>Cancel</a>
          <button type='submit' class='btn btn-success'>Save </button>
        </div>
      </div>
  </form>
</div>
<br/>
<table id='ResultTable' class='table table-bordered table-striped'>
  <thead>
    <tr>
      <th class='text-center'>Certification</th>
      <th class='text-center'>Institute</th>
      <th class='text-center'>Date Given</th>
      <th class='text-center'>Remarks</th>
      <th class='text-center'>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($data as $row):
    ?>
      <tr>
        <td class='text-center'><?php echo htmlspecialchars($row['certification'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['institute'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['date_given'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['remarks'])?></td>
        <td class='text-center'>
          <a href='frm_employee.php?id=<?php echo $employee['id']?>&tab=<?php echo $tab?>&ec_id=<?php echo $row['id']?>' class='btn btn-success btn-sm'><span class='fa fa-pencil'></span></a>
          <a href='delete.php?t=ec&id=<?php echo $row['id']?>&e_id=<?php echo $employee['id']?>&tab=<?php echo $tab;?>' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
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