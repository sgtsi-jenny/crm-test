<?php
  $data=$con->myQuery("SELECT id,first_name,middle_name,last_name,contact_no,address,remarks FROM employees_emergency_contacts eec WHERE is_deleted=0 AND employee_id=?",array($employee['id']))->fetchAll(PDO::FETCH_ASSOC);
  if(!empty($_GET['eec_id'])){
    $record=$con->myQuery("SELECT id,first_name,middle_name,last_name,contact_no,address,remarks FROM employees_emergency_contacts eec WHERE employee_id=? AND id=? LIMIT 1",array($employee['id'],$_GET['eec_id']))->fetch(PDO::FETCH_ASSOC);
  }
  $tab=9;
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
  <form class='form-horizontal' action='save_emergency_contacts.php' method="POST">
    <input type='hidden' name='employee_id' value='<?php echo !empty($employee)?$employee['id']:''; ?>'>
    <input type='hidden' name='id' value='<?php echo !empty($record)?$record['id']:''; ?>'>
      <div class="form-group">
        <label for="first_name" class="col-md-3 control-label">First Name *</label>
        <div class="col-md-7">
          <input type="text" class="form-control" id="first_name" placeholder="First Name" name='first_name' value='<?php echo !empty($record)?htmlspecialchars($record['first_name']):''; ?>'  required>
        </div>
      </div>
      <div class="form-group">
        <label for="middle_name" class="col-md-3 control-label">Middle Name </label>
        <div class="col-md-7">
          <input type="text" class="form-control" id="middle_name" name='middle_name' placeholder="Middle Name" value='<?php echo !empty($record)?htmlspecialchars($record['middle_name']):''; ?>'  >
        </div>
      </div>
      <div class="form-group">
        <label for="last_name" class="col-md-3 control-label">Last Name *</label>
        <div class="col-md-7">
          <input type="text" class="form-control" id="last_name" placeholder="Last Name" name='last_name' value='<?php echo !empty($record)?htmlspecialchars($record['last_name']):''; ?>'  required>
        </div>
      </div>
      <div class="form-group">
        <label for="contact_no" class="col-md-3 control-label">Contact Number *</label>
        <div class="col-md-7">
          <textarea class='form-control' name='contact_no' id='contact_no' placeholder="Contact Number" required><?php echo !empty($record)?htmlspecialchars($record['contact_no']):''; ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label for="address" class="col-md-3 control-label">Address </label>
        <div class="col-md-7">
          <textarea class='form-control' name='address' id='address' placeholder="Address"><?php echo !empty($record)?htmlspecialchars($record['address']):''; ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label for="remarks" class="col-md-3 control-label">Remarks </label>
        <div class="col-md-7">
          <textarea class='form-control' name='remarks' id='remarks' placeholder="Remarks"><?php echo !empty($record)?htmlspecialchars($record['remarks']):''; ?></textarea>
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
      <th class='text-center'>Contact Person</th>
      <th class='text-center'>Contact No</th>
      <th class='text-center'>Address</th>
      <th class='text-center'>Remarks</th>
      <th class='text-center'>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($data as $row):
    ?>
      <tr>
        <td class='text-center'><?php echo htmlspecialchars($row['last_name'].", ".$row['first_name']." ".$row['middle_name'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['contact_no'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['address'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['remarks'])?></td>
        <td class='text-center'>
          <a href='frm_employee.php?id=<?php echo $employee['id']?>&tab=<?php echo $tab?>&eec_id=<?php echo $row['id']?>' class='btn btn-success btn-sm'><span class='fa fa-pencil'></span></a>
          <a href='delete.php?t=eec&id=<?php echo $row['id']?>&e_id=<?php echo $employee['id']?>&tab=<?php echo $tab;?>' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
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