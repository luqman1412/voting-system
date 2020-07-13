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
          <span>Overview</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Setting item -->
      <li class="nav-item <?=$setting_active?>">
        <a class="nav-link " href="adminsetting.php" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>Settings</span>
        </a> 
      </li>

      <!-- Candidate item -->
      <li class="nav-item ">
        <a class="nav-link " href="candidatelist.php" aria-expanded="true" aria-controls="collapseUtilities">
          <!-- Candidate icon -->
          <i class="fa fa-user-circle" aria-hidden="true"></i>
          <span>Candidate</span>
        </a>
      </li>
      <!-- candidate dropdown -->
      <li class="nav-item ">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCandidate" aria-expanded="true" aria-controls="collapseCandidate">
          <i class="fa fa-user-circle" aria-hidden="true"></i>
          <span>Candidate</span>
        </a>
        <div id="collapseCandidate" class="collapse <?=$candidate_show?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item <?=$candidateumum_active?>" href="candidateumum.php">Umum</a>
            <a class="collapse-item <?=$candidatefakulti_active?>" href="candidatefakulti.php">Fakulti</a>
          </div>
        </div>
      </li>

      <!-- section dropdown -->
        <li class="nav-item ">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSection" aria-expanded="true" aria-controls="collapseSection">
          <i class="fa fa-user-circle" aria-hidden="true"></i>
          <span>Section</span>
        </a>
        <div id="collapseSection" class="collapse " aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item " href="sectionsettingumum.php">Umum</a>
            <a class="collapse-item " href="#">Fakulti</a>
          </div>
        </div>
      </li>



      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item <?=$voter_active?>">
        <a class="nav-link" href="voterlist.php"  aria-expanded="true" aria-controls="collapsePages">
          <!-- Voter Icon -->
          <i><svg class="bi bi-people-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
             <path fill-rule="evenodd" d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 100-6 3 3 0 000 6zm-5.784 6A2.238 2.238 0 015 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 005 9c-4 0-5 3-5 4s1 1 1 1h4.216zM4.5 8a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" clip-rule="evenodd"/>
          </svg></i>
          <span>Voter</span>
        </a>
      </li>

     

    </ul>
    <!-- End of Sidebar -->