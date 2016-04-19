<?php
    require_once 'support/config.php';
    if(!isLoggedIn()){
        toLogin();
        die();
    }
    makeHead("Calendar");
?>
<div id='wrapper'>
<?php
     require_once 'template/navbar.php';
?>
</div>

<div id="page-wrapper">
            <div class='row'>
                </br>
                <div class='col-md-3'>
                    <!--
                    <?php
                        require_once 'template/account_information.php';
                    ?>
                    -->
                    <div class='row'>
                        <div class="list-group " >
                          <span href="" class="list-group-item active" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;My Calendar</span>
                          <a href="events.php" class="list-group-item" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Calendar List</a>
                        </div>
                    </div>                    
                </div>
                
                <div class='col-md-9'>                    
                    <div class='row'>
                        <!-- <div class='col-sm-12'>
                               
                        <a href='frm_to_do.php' class='btn btn-success pull-right'> <span class='fa fa-plus'></span> Add To Do</a>
                        <a href='frm_event.php' class='btn btn-success pull-right'> <span class='fa fa-plus'></span> Add Event</a>
                               
                        </div>
                        <br/> <br/><br/>  -->

                        <div class='col-md-12'>
                                <?php
                                include 'calendar.php';   
                                ?>
                                
                                    <div id='calendar'></div>

                                
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
</div>


<!-- Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="eventModal">Quick Create Event</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" onsubmit="return validatePost(this)" action='save_event.php' method='POST'>
            <input type='hidden' name='account_id' value='<?php echo htmlspecialchars($_GET['id']) ?>'>

          <div class="form-group">
            <label for="" class="col-md-4 control-label">*Subject</label>
            <div class="col-sm-6">
                <input type='text' name='subject' class='form-control'>
            </div>
            
          </div>
          <div class="form-group">
            <label for="" class="col-sm-4 control-label">*Start Date and Time</label>
            <div class="col-sm-6">
                    <?php
                        $expected_close_date="";
                        if(!empty($opportunity)){
                        $expected_close_date=$opportunity['expected_close_date'];
                        if($expected_close_date=="0000-00-00"){
                        $expected_close_date="";
                        }
                        }
                    ?>
                <input type='date' class='form-control' name='start_date' value='<?php echo $start_date; ?>'>
                </div>
                <label for="" class="col-sm-4 control-label"></label>
                <div class="col-sm-3">
                <input type="time" class="form-control" value="<?php $date = date("H:i", strtotime($row['time_d'])); echo "$date"; ?>" id="until_t" name="start_time" />
            </div>
          </div>

          <div class="form-group">
            <label for="" class="col-sm-4 control-label">*End Date and Time</label>
            <div class="col-sm-6">
                    <?php
                        $expected_close_date="";
                        if(!empty($opportunity)){
                        $expected_close_date=$opportunity['expected_close_date'];
                        if($expected_close_date=="0000-00-00"){
                        $expected_close_date="";
                        }
                        }
                    ?>
                <input type='date' class='form-control' name='end_date' value='<?php echo $purchase_date; ?>'>
                </div>
                <label for="" class="col-sm-4 control-label"></label>
                <div class="col-sm-3">
                <input type="time" class="form-control" value="<?php $date = date("H:i", strtotime($row['time_d'])); echo "$date"; ?>" id="end_time" name="end_time" />
            </div>
          </div>

          <div class='form-group'>
            <label for="" class="col-md-4 control-label">*Status</label>
            <div class="col-sm-6">
                <select name='event_stat' class='no-search'>
                <option value='1'>Planned</option>
                <option value='2'>Held</option>
                <option value='3'>Not Held</option>
                </select>
            </div>  
          </div>
          <div class='form-group'>
            <label for="" class="col-md-4 control-label">*Activity Type</label>
            <div class="col-sm-6">
                <select name='activity_type' class='no-search'>
                <option value='1'>Call</option>
                <option value='2'>Meeting</option>
                <option value='3'>Mobile Call</option>
                </select>
            </div>  
          </div>
          <div class='form-group'>
            <label for="" class="col-md-4 control-label">*User</label>
            <div class="col-sm-6">
                <select id="disabledSelect" class='form-control' name='assigned_to' data-placeholder="Select a user" <?php echo!(empty($opportunity))?"data-selected='".$opportunity['assigned_to']."'":"data-selected='".$_SESSION[WEBAPP]['user']['id']."'" ?>>
                   <?php
                        echo makeOptions($user);
                    ?>
                </select>
            </div>  
          </div>
          <div class="form-group">
            <div class="col-sm-offset-4 col-sm-6">
              <button type="submit" class="btn btn-success">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    function validatePost(post_form){
        console.log();
        var str_error="";
        $.each($(post_form).serializeArray(),function(index,field){
            console.log(field);
            if(field.value==""){
            
                switch(field.name){
                    case "subject":
                        str_error+="Please Enter Subject. \n";
                        break;
                    case "start_date":
                        str_error+="Please provide starting date. \n";
                        break;
                    case "start_time":
                        str_error+="Please provide starting time. \n";
                        break;
                    case "end_date":
                        str_error+="Please provide ending date. \n";
                        break;
                    case "end_time":
                        str_error+="Please provide ending time. \n";
                        break;
                    case "event_stat":
                        str_error+="Please select event status. \n";
                        break;
                    case "activity_type":
                        str_error+="Please select activity type. \n";
                        break;
                    case "assigned_to":
                        str_error+="Please select user. \n";
                        break;
                    
                }
                
            }

        });
        if(str_error!=""){
            alert("You have the following errors: \n" + str_error );
            return false;
        }
        else{
            return true
        }
    }

    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY": true,
                "scrollX": true
        });
    });
    
</script>
<?php
    makeFoot();
?>