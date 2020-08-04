<?php 
$pagename=basename($_SERVER['PHP_SELF']);
$current_page="active";

if ($pagename=="adminoverview.php") {
    $overview_active=$current_page;
}
elseif ($pagename=="adminsetting.php") {
    $setting_active=$current_page;
}
elseif ($pagename=="candidateumum.php") {
    $candidate_show="show";
    $candidateumum_active=$current_page;
    }
elseif ($pagename=="candidatefakulti.php") {
      $candidate_show="show";
    $candidatefakulti_active=$current_page;
    }
elseif ($pagename=="voterlist.php") {
    $voter_active=$current_page;
    }
elseif ($pagename=="launchmenu.php") {
    $launch_active=$current_page;
    
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
      <li class="nav-item <?=$overview_active?>">
        <a class="nav-link" href="adminoverview.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Setting item -->
      <li class="nav-item <?=$setting_active?>">
        <a class="nav-link " href="adminsetting.php" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-cog"></i>
          <span>Settings</span>
        </a> 
      </li>

      <!-- candidate dropdown -->
      <li class="nav-item ">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCandidate" aria-expanded="true" aria-controls="collapseCandidate">
          <i class="fas fa-user"></i>
          <span>Candidate</span>
        </a>
        <div id="collapseCandidate" class="collapse <?=$candidate_show?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item <?=$candidateumum_active?>" href="candidateumum.php">Umum</a>
            <a class="collapse-item <?=$candidatefakulti_active?>" href="candidatefakulti.php">Fakulti</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Voter Menu -->
      <li class="nav-item <?=$voter_active?>">
        <a class="nav-link" href="voterlist.php"  aria-expanded="true" aria-controls="collapsePages">
          <!-- Voter Icon -->
          <i class="fas fa-user-friends" aria-hidden="true"></i>
          <span>Voter</span>
        </a>
      </li>

       <!-- Nav Item - Launch Menu -->
      <li class="nav-item <?=$launch_active?>">
        <a class="nav-link" href="launchmenu.php"  aria-expanded="true" aria-controls="collapsePages">
          <!-- Launch Icon -->
          <i class="fas fa-rocket"></i>
          <span>Launch</span>
        </a>
      </li>

    </ul>
    <!-- End of Sidebar -->