<?php
  $data=$con->myQuery("SELECT id,company,position,salary,date_start,date_end,remarks,department FROM employees_employment_history eeh WHERE is_deleted=0 AND employee_id=?",array($employee['id']))->fetchAll(PDO::FETCH_ASSOC);
  if(!empty($_GET['eeh_id'])){
    $record=$con->myQuery("SELECT id,company,position,salary,date_start,date_end,remarks,department FROM employees_employment_history eeh WHERE employee_id=? AND id=? LIMIT 1",array($employee['id'],$_GET['eeh_id']))->fetch(PDO::FETCH_ASSOC);
  }
  $tab=4;
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
  <form class='form-horizontal' action='save_employment_history.php' method="POST" onsubmit='return validate(this)'>
    <input type='hidden' name='employee_id' value='<?php echo !empty($employee)?$employee['id']:''; ?>'>
    <input type='hidden' name='id' value='<?php echo !empty($record)?$record['id']:''; ?>'>
      <div class="form-group">
        <label for="date_start" class="col-md-3 control-label">Date Start *</label>
        <div class="col-md-7">
          <input type="date" class="form-control" id="date_start" name='date_start' value='<?php echo !empty($record)?htmlspecialchars($record['date_start']):''; ?>'  required>
        </div>
      </div>
      <div class="form-group">
        <label for="date_end" class="col-md-3 control-label">Date End </label>
        <div class="col-md-7">
          <input type="date" class="form-control" id="date_end" name='date_end' value='<?php echo !empty($record)?htmlspecialchars($record['date_end']):''; ?>'  >
        </div>
      </div>
      <div class="form-group">
        <label for="company" class="col-md-3 control-label">Company *</label>
        <div class="col-md-7">
          <input type="text" class="form-control" id="company" placeholder="Company" name='company' value='<?php echo !empty($record)?htmlspecialchars($record['company']):''; ?>'  required>
        </div>
      </div>
      <div class="form-group">
        <label for="department" class="col-md-3 control-label">Department *</label>
        <div class="col-md-7">
          <input type="text" class="form-control" id="department" placeholder="Department" name='department' value='<?php echo !empty($record)?htmlspecialchars($record['department']):''; ?>'  required>
        </div>
      </div>
      <div class="form-group">
      <label for="position" class="col-md-3 control-label">Position *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="position" placeholder="Position" name='position' value='<?php echo !empty($record)?htmlspecialchars($record['position']):''; ?>'  required>
      </div>
      </div>
      <div class="form-group">
        <label for="salary" class="col-md-3 control-label">Salary *</label>
        <div class="col-md-7">
          <input type="number" min='1' class="form-control" id="salary" placeholder="Salary" name='salary' value='<?php echo !empty($record)?htmlspecialchars($record['salary']):''; ?>'  required>
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
      <th class='text-center'>Date Start</th>
      <th class='text-center'>Date End</th>
      <th class='text-center'>Company</th>
      <th class='text-center'>Department</th>
      <th class='text-center'>Position</th>
      <th class='text-center'>Salary</th>
      <th class='text-center'>Remarks</th>
      <th class='text-center'>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($data as $row):
    ?>
      <tr>
        <td class='text-center'><?php echo htmlspecialchars($row['date_start'])?></td>
        <td class='text-center'><?php echo $row['date_end']=='0000-00-00'?'':htmlspecialchars($row['date_end'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['company'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['department'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['position'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['salary'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['remarks'])?></td>
        <td class='text-center'>
          <a href='frm_employee.php?id=<?php echo $employee['id']?>&tab=<?php echo $tab?>&eeh_id=<?php echo $row['id']?>' class='btn btn-success btn-sm'><span class='fa fa-pencil'></span></a>
          <a href='delete.php?t=eeh&id=<?php echo $row['id']?>&e_id=<?php echo $employee['id']?>&tab=<?php echo $tab;?>' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
        </td>
      </tr>
    <?php
      endforeach;
    ?>
  </tbody>
</table>
<script type="text/javascript">
  function validate(frm) {
    if($("#date_end").val()!==""){
      if($("#date_start").val() > $("#date_end").val()){
        alert("Start date cannot be greater than end date.");
        return false;
      }
      else if($("#date_start").val() == $("#date_end").val()){
        alert("End date should be greater than start date.")
        return false;
      }
    }
    

    return true;
  }
</script>
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