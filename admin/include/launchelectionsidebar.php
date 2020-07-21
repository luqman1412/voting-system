<?php 
 $pagename=basename($_SERVER['PHP_SELF']);
$current_page="active";
if ($pagename=="electionlaunch.php") {
    $dashboard_active=$current_page;
}
elseif ($pagename=="resultumum.php") {
      $result_show="show";
       $result_umum=$current_page;
}
elseif ($pagename=="resultfakulti.php") {
      $result_show="show";
      $result_fakulti=$current_page;
}
    

 ?>
  <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admindashboard.php">

        <div class="sidebar-brand-text mx-3">KUIS E-voting Systems</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item <?=$dashboard_active?>">
        <a class="nav-link" href="electionlaunch.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- candidate item -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="resultpage.php">
          <i class="fas fa-fw fa-cog"></i>
          <span>Result</span>
        </a>
<!--         <div id="collapseTwo" class="collapse <?=$result_show?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item <?=$result_umum?>" href="resultumum.php">Umum</a>
            <a class="collapse-item <?=$result_fakulti?>" href="resultfakulti.php">Fakulti</a>
          </div>
        </div> -->
      </li>

        

    </ul>
    <!-- End of Sidebar -->