<?php
  $data=$con->myQuery("SELECT eal.id,eal.total_leave,eal.balance_per_year,DATE_FORMAT(eal.date_added,'%Y') as date_added,l.name FROM `employees_available_leaves` eal JOIN leaves l ON eal.leave_id=l.id WHERE eal.is_deleted=0 AND eal.is_cancelled=0 AND employee_id=?",array($employee['id']))->fetchAll(PDO::FETCH_ASSOC);
  
  if (!empty($_GET['ur'])) 
  {
    $ur=$_GET['ur'];
  }

  if(!empty($_GET['eal_id']))
  {
    $record=$con->myQuery("SELECT eal.id,eal.total_leave,eal.balance_per_year,l.name,eal.leave_id,DATE_FORMAT(eal.date_added,'%Y') as date_added FROM `employees_available_leaves` eal JOIN leaves l ON eal.leave_id=l.id  WHERE employee_id=? AND eal.id=? AND eal.is_deleted=0 LIMIT 1",array($employee['id'],$_GET['eal_id']))->fetch(PDO::FETCH_ASSOC);
    $leaves=$con->myQuery("SELECT id,name FROM leaves WHERE is_deleted=0 ",array($employee['id']))->fetchAll(PDO::FETCH_ASSOC);
  }
  else
  {
    $leaves=$con->myQuery("SELECT id,name FROM leaves WHERE id NOT IN (SELECT leave_id FROM employees_available_leaves WHERE employee_id=? AND is_deleted=0) AND is_deleted=0 ",array($employee['id']))->fetchAll(PDO::FETCH_ASSOC);
  }


  $tab=8;
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
  <form class='form-horizontal' action='save_leave.php' method="POST" >
    <input type='hidden' name='employee_id' value='<?php echo !empty($employee)?$employee['id']:''; ?>'>
    <input type='hidden' name='balance' value='<?php echo !empty($record)?$record['balance_per_year']:''; ?>'>
    <input type='hidden' name='total' value='<?php echo !empty($record)?$record['total_leave']:''; ?>'>
    <input type='hidden' name='ur' value='<?php echo !empty($ur)?$ur:''; ?>'>
    <input type='hidden' name='date_added' value='<?php echo !empty($record)?$record['date_added']:''; ?>'>
    <input type='hidden' name='l_id' value='<?php echo !empty($record)?$record['leave_id']:''; ?>'>
    <input type='hidden' name='id' value='<?php echo !empty($record)?$record['id']:''; ?>'>
      <div class="form-group">
        <label for="leave_id" class="col-md-3 control-label">Leave Type *</label>
        <div class="col-md-7">
          <?php 
            if (!empty($record)) {
          ?>
          <select name='leave_id' class='form-control select2' data-placeholder="Select Leave type" <?php echo !(empty($record))?"data-selected='".$record['leave_id']."'":NULL ?> style='width:100%' required="required" disabled='disabled '>
          <?php echo makeOptions($leaves); }else{ ?>
          <select name='leave_id' class='form-control select2' data-placeholder="Select Leave type" <?php echo !(empty($record))?"data-selected='".$record['leave_id']."'":NULL ?> style='width:100%' required="required">
          <?php
              echo makeOptions($leaves);
            }
          ?>
          </select>
          
        </div>
      </div>
      <div class="form-group">
          <label for="balance_per_year" class="col-md-3 control-label">Leave Balance *</label>
          <div class="col-md-7">
            <input type="number" step='1' min='0' class="form-control" id="balance_per_year" placeholder="Enter Leave Balance" name='balance_per_year' value='<?php echo !empty($record)?htmlspecialchars($record['total_leave']):''; ?>' required="required">
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
      <th class='text-center'>Leave</th>
      <th class='text-center'>Total Leave</th>
      <th class='text-center'>Available Leave</th>
      <th class='text-center'>Year</th>
      <th class='text-center'>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($data as $row):
    ?>
      <tr>
        <td class='text-center'><?php echo htmlspecialchars($row['name'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['total_leave'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['balance_per_year'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['date_added'])?></td>
        
        <td class='text-center'>
          <a title='Reset <?php echo htmlspecialchars($row['name'])?>' href='frm_employee.php?id=<?php echo $employee['id']?>&tab=<?php echo $tab?>&eal_id=<?php echo $row['id']?>&ur=1' class='btn btn-success btn-sm'><span class='fa fa-undo'></span></a>
          <a title='Change Leave Balance' href='frm_employee.php?id=<?php echo $employee['id']?>&tab=<?php echo $tab?>&eal_id=<?php echo $row['id']?>&ur=2' class='btn btn-info btn-sm'><span class='fa fa-pencil'></span></a>
          <a title='Remove Leave' href='delete.php?t=eal&id=<?php echo $row['id']?>&e_id=<?php echo $employee['id']?>&tab=<?php echo $tab;?>' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
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