<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

     if(!AllowUser(array(1))){
         redirect("index.php");
     }

  $data=$con->myQuery("SELECT opp_name,org_name,sales_stage,tprice,users,cname,id FROM vw_opp where utype=?",array($_SESSION[WEBAPP]['user']['id']))->fetchAll(PDO::FETCH_ASSOC);
    makeHead("Contacts");

?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Contacts
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">

            <div class='col-md-12'>
              <?php 
                Alert();
              ?>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-12">
                        <!-- <div class='col-ms-12 text-right'>
                          <a href='frm_customers.php' class='btn btn-success'> Create New <span class='fa fa-plus'></span> </a>
                        </div> -->
                        <br/>
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Photo</th>
                                                <th class='text-center'>Name</th>
                                                <th class='text-center'>Creator</th>
                                                <th class='text-center'>Department</th>
                                                <th class='text-center'>Position/Rank</th>
                                                <th class='text-center'>Company</th>
                                                <th class='text-center'>Birth date</th>
                                                <th class='text-center'>Email</th>
                                                <th class='text-center'>Home Phone</th>
                                                <th class='text-center'>Mobile Phone</th>
                                                <th class='text-center'>Office Phone</th>
                                                <th class='text-center'>Description</th>
                              <th class='text-center' style='min-width:100px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php
                                            $uid=$_SESSION[WEBAPP]['user']['id'];
                                            //if ($_SESSION[WEBAPP]['user']['user_type']==1):
                                            
                                               $contacts=$con->myQuery("SELECT profile_pic, CONCAT(lname, ', ', fname) As contact_name, CONCAT(users.last_name, ', ', users.first_name) As assigned_name, contacts.assigned_to, departments.name,contacts.pos_rank, department_id, organizations.org_name, contacts.org_id, dob, contacts.email, home_phone, mobile_phone, office_phone, contacts.description, contacts.id FROM contacts left join users on contacts.assigned_to=users.id inner join departments on departments.id=contacts.department_id inner join organizations on organizations.id=contacts.org_id where contacts.is_deleted=0")->fetchAll(PDO::FETCH_ASSOC); 
                                               foreach ($contacts as $contact):
                                            
                                        ?>

                                                <tr>
                                                        
                                                        <?php
                                                            foreach ($contact as $key => $value):
                                                            if($key=='profile_pic'):
                                                        ?>
                                                            <td><a href='uploads/<?php echo $account['profile_pic'] ?>'>
                                                                <img src='uploads/<?php echo $account['profile_pic'];?>' class='img-responsive' width='40px' height='40px'></a>
                                                            </td>
                                                            <?php
                                                            elseif($key=='id'):
                                                            
                                                        ?>                   
                                                                                                                                         
                                                            <td>
                                                                <a class='btn btn-sm btn-brand' href='frm_contacts.php?id=<?php echo $value;?>'><span class='fa fa-pencil'></span></a>
                                                                <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $value?>&t=co' onclick='return confirm("This contact will be deleted.")'><span class='fa fa-trash'></span></a>
                                                            </td>
                                                        <?php
                                                            elseif($key=='assigned_to'):
                                                            elseif($key=='department_id'):
                                                            elseif($key=='org_id'):
                                                        ?>                   
                                                                                 
                                                           
                                                        <?php
                                                            else:
                                                        ?>

                                                            <td>
                                                                <?php
                                                                    echo htmlspecialchars($value);
                                                                ?>
                                                            </td>

                                                        <?php
                                                            endif;
                                                            endforeach;
                                                        ?>
                                                    </tr>
                                            <?php
                                                endforeach;
                                            ?>


                          </tbody>
                        </table>
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
            "scrollX": true,
            dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"excel",
                        text:"<span class='fa fa-download'></span> Download as Excel File "
                    }
                    ],

        });
      });
</script>

<?php
  Modal();
    makeFoot();
?>