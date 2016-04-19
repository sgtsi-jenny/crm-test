<?php
	$education_levels=$con->myQuery("SELECT id,name FROM education_level WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
  $employee_educations=$con->myQuery("SELECT ee.id,el.name as education_level,institute,course,date_start,date_end,remarks FROM employees_education ee JOIN education_level el ON ee.educ_level_id=el.id WHERE employee_id=? AND ee.is_deleted=0",array($employee['id']))->fetchAll(PDO::FETCH_ASSOC);
  if(!empty($_GET['ee_id'])){
    $employee_education=$con->myQuery("SELECT ee.id,ee.educ_level_id,institute,course,date_start,date_end,remarks FROM employees_education ee WHERE employee_id=? AND ee.id=? LIMIT 1",array($employee['id'],$_GET['ee_id']))->fetch(PDO::FETCH_ASSOC);
  }
  $tab=2;
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
<div id='collapseForm' class='collapse'>
  <form class='form-horizontal' action='save_education.php' method="POST" >
  	<input type='hidden' name='employee_id' value='<?php echo !empty($employee)?$employee['id']:''; ?>'>
    <input type='hidden' name='id' value='<?php echo !empty($employee_education)?$employee_education['id']:''; ?>'>
      <div class="form-group">
        <label for="educ_level_id" class="col-md-3 control-label">Education Level *</label>
        <div class="col-md-7">
        	<select name='educ_level_id' class='form-control select2' data-placeholder="Select Education Level" <?php echo !(empty($employee_education))?"data-selected='".$employee_education['educ_level_id']."'":NULL ?> style='width:100%' required>
        		<?php
        			echo makeOptions($education_levels);
        		?>
        	</select>
        </div>
      </div>
      <div class="form-group">
          <label for="institute" class="col-md-3 control-label">Institute *</label>
          <div class="col-md-7">
            <input type="text" class="form-control" id="institute" placeholder="Enter Institute" name='institute' value='<?php echo !empty($employee_education)?htmlspecialchars($employee_education['institute']):''; ?>' required>
          </div>
      </div>
      <div class="form-group">
          <label for="course" class="col-md-3 control-label">Course *</label>
          <div class="col-md-7">
            <input type="text" class="form-control" id="course" placeholder="Enter Course" name='course' value='<?php echo !empty($employee_education)?htmlspecialchars($employee_education['course']):''; ?>' required>
          </div>
      </div>
      <div class="form-group">
        <label for="date_start" class="col-md-3 control-label">Date Start *</label>
        <div class="col-md-7">
          <input type="date" class="form-control" id="date_start" name='date_start' value='<?php echo !empty($employee_education)?htmlspecialchars($employee_education['date_start']):''; ?>'  required>
        </div>
      </div>
      <div class="form-group">
        <label for="date_end" class="col-md-3 control-label">Date End </label>
        <div class="col-md-7">
          <input type="date" class="form-control" id="date_end" name='date_end' value='<?php echo !empty($employee_education)?htmlspecialchars($employee_education['date_end']):''; ?>'  >
        </div>
      </div>
      <div class="form-group">
        <label for="remarks" class="col-md-3 control-label">Remarks </label>
        <div class="col-md-7">
        	<textarea class='form-control' name='remarks' id='remarks' ><?php echo !empty($employee_education)?htmlspecialchars($employee_education['remarks']):''; ?></textarea>
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
      <th class='text-center'>Education Level</th>
      <th class='text-center'>Institute</th>
      <th class='text-center'>Course</th>
      <th class='text-center'>Date Start</th>
      <th class='text-center'>Date End</th>
      <th class='text-center'>Remarks</th>
      <th class='text-center'>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($employee_educations as $row):
    ?>
      <tr>
        <td class='text-center'><?php echo htmlspecialchars($row['education_level'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['institute'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['course'])?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['date_start'])?></td>
        <td class='text-center'><?php echo $row['date_end']<>'0000-00-00'?htmlspecialchars($row['date_end']):''?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['remarks'])?></td>
        <td class='text-center'>
          <a href='frm_employee.php?id=<?php echo $employee['id']?>&tab=<?php echo $tab?>&ee_id=<?php echo $row['id']?>' class='btn btn-success btn-sm'><span class='fa fa-pencil'></span></a>
          <a href='delete.php?t=ee&id=<?php echo $row['id']?>&e_id=<?php echo $employee['id']?>&tab=<?php echo $tab;?>' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
        </td>
      </tr>
    <?php
      endforeach;
    ?>
  </tbody>
</table>
<?php 
  if($has_error===TRUE || !empty($employee_education)):
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