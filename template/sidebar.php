<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="index.php"?"active":"";?>">
              <a href="index.php">
              
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="my_cal.php"?"active":"";?>">
              <a href="my_cal.php">
                <i class="fa fa-calendar-o"></i> <span>Calendar</span>
              </a>
            </li>

            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="customers.php"?"active":"";?>">
              <a href="customers.php">
                <i class="fa fa-users"></i> <span>Customers</span>
              </a>
            </li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="opportunities.php"?"active":"";?>">
              <a href="opportunities.php">
                <i class="fa fa-lightbulb-o"></i> <span>Oppurtunities</span>
              </a>
            </li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="contacts.php"?"active":"";?>">
              <a href="contacts.php">
                <i class="fa fa-phone"></i> <span>Contacts</span>
              </a>
            </li>



            <li class='header'>REQUEST APPROVAL MENU</li>
            <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="overtime_approval.php"?"active":"";?>'>
            <a href='overtime_approval.php'><i class="fa fa-file-text"></i>
            <span>Customers </span> <?php echo empty($overtime_count)?'':"<small class='label pull-right bg-primary'>{$overtime_count}</small>";?></a>  
            </li>
            
            <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="leave_approval.php"?"active":"";?>'><a href='leave_approval.php'><i class="fa fa-file-text"></i>
            <span>Opportunities</span> <?php echo empty($leave_count)?'':"<small class='label pull-right bg-primary'>{$leave_count}</small>";?></a>
            </li>
            
            <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="ob_approval.php"?"active":"";?>'><a href='ob_approval.php'><i class="fa fa-file-text"></i>
            <span>Contacts</span> <?php echo empty($ob_count)?'':"<small class='label pull-right bg-primary'>{$ob_count}</small>";?></a>
            </li>

            
            <?php
              if(AllowUser(array(1))):
            ?>
            <li class='header'>ADMINISTRATOR MENU</li>
            <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array("audit_log.php","employees.php","terminated_employees.php","users.php","education_level.php","skills.php","trainings.php","certifications.php","job_title.php","leave_type.php","departments.php","monitor_attendance.php","company_files.php","employee_files.php","tax_status.php","pay_grade.php","employment_status.php","approval_matrix.php","settings.php")))?"active":"";?>'>
              <a href=''><i class="fa fa-cubes"></i><span>Administrator</span><i class="fa fa-angle-left pull-right"></i></a>
              <ul class='treeview-menu'>
                
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="audit_log.php"?"active":"";?>">
                  <a href="audit_log.php">
                    <i class="fa fa-list"></i> <span>Audit Log</span>
                  </a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="users.php"?"active":"";?>">
                  <a href="users.php">
                    <i class="fa fa-users"></i> <span>Users</span>
                  </a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="leave_type.php"?"active":"";?>">
                  <a href="leave_type.php">
                    <i class="fa fa-building"></i> <span>Sales Stages</span>
                  </a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="departments.php"?"active":"";?>">
                  <a href="departments.php">
                    <i class="fa fa-building"></i> <span>Opportunity Types</span>
                  </a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="monitor_attendance.php"?"active":"";?>">
                  <a href="monitor_attendance.php">
                    <i class="fa fa-clock-o"></i> <span>Customer Types</span>
                  </a>
                </li>

                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="departments.php"?"active":"";?>">
                  <a href="departments.php">
                    <i class="fa fa-building"></i> <span>Departments</span>
                  </a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="monitor_attendance.php"?"active":"";?>">
                  <a href="monitor_attendance.php">
                    <i class="fa fa-clock-o"></i> <span>Ratings</span>
                  </a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="monitor_attendance.php"?"active":"";?>">
                  <a href="monitor_attendance.php">
                    <i class="fa fa-clock-o"></i> <span>Locations</span>
                  </a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="time_management.php"?"active":"";?>">
                  <a href="opportunnities.php">
                    <i class="fa fa-file"></i> <span>Reports</span>
                  </a>
                </li>
                
                  </ul>
                </li>
              </ul>
            </li>
            <?php
              endif;
            ?>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>