<?php session_start();
include "include/blankheader.php";
$userid= $_SESSION['id'];

//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
 header("location:../index.php?error=alreadylogout");
}

$search_name="";
include "../connection.php";
// get txt from search bar 
if (isset($_GET['btnsearch'])) {
  $search_name=$_GET['txtsearch'];
  $searchname=$_GET['txtsearch'];
  $query="SELECT * FROM election WHERE title like '%$searchname%' ";
  $qr=mysqli_query($db,$query);
  if ($qr==false) {
      echo "Failed to search data<br>";
      echo "SQL error :".mysqli_error($db);
  }
  //Check the record effected, if no record,
 //display a message
 if(mysqli_num_rows($qr)==0){
    header("Location: admindashboard.php?error=norecordfund&data=$searchname");
 }//end no record
}

else{
    $query="SELECT * FROM election WHERE owner_id='$userid' ";
    $qr=mysqli_query($db,$query);
    if ($qr==false) {
        echo "Failed to get the election information<br>";
        echo "SQL error :".mysqli_error($db);
    }
}
if (isset($_GET['data'])) {
  $search_name=$_GET['data'];
}
?>
   <div class="container">
      <div class="col-xl-12 col-lg-12 col-md-9 col-xs-12">
             <div class="card shadow mb-4">

            <div class="card-header py-3" >
            	<div class="d-sm-flex align-items-center justify-content-between mb-1">
             		<h5 class="m-0  font-weight-bold text-primary">Dashboard</h5>

             		<div class="pull-right">
               <a href="createelection.php" class="btn btn-primary btn-icon-split">
                  <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                  </span>
                  <span class="text">Add Election</span>
               </a>
                </div>
            	</div>
         	</div>
            <div class="card-body">

            <!-- Search form -->
            <form class="form-inline mr-auto" action="admindashboard.php" method="get" name="formsearch">
              <input  name="txtsearch" class="form-control mr-sm-2" type="text" value="<?=$search_name?>" placeholder="Search" aria-label="Search" onfocus="this.value=''" >
              <button name="btnsearch" class="btn btn-primary btn-rounded btn-md my-0" type="submit"><i class="fas fa-search"></i></button>

            </form>
              <?php 
                // error handling for searchbox
                if (isset($_GET['success'])) {
                  if ($_GET['success']=="successfullydeleted") {

                     echo '<br><div class="alert alert-warning" role="alert">Election has been deleted!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> ';
                  }
                }
                echo "<hr>";
                // error handling for searchbox
                if (isset($_GET['error'])) {
                  if ($_GET['error']=="norecordfund") {
                     echo '<br><div class="alert alert-danger" role="alert">No record found for: ' .$search_name.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> ';
                  }
                  if ($_GET['error']=="activeelection") {
                     echo '<br><div class="alert alert-danger" role="alert">Only <strong>1 active election</strong> are allowed! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> ';
                    
                  }
                }
               ?>
               <div class="table-responsive">
          <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th class="th-sm">Status
                  </th>
                  <th class="th-sm">Title
                  <th class="th-sm">End Date
                  </th>
                  <th class="th-sm">Start Date
                  </th>
                  <th class="th-sm">Action
                  </th>
                </tr>
              </thead>

              <tbody>
              	<?php
                while ($record=mysqli_fetch_array($qr)) {
                      $start_time=date("d.m.Y h:i a", strtotime($record['start']));
                      $end_time=date("d.m.Y h:i a", strtotime($record['end']));

                      if ($record['status']=="Paused") {
                        $redirect_pagename="adminoverview";
                      }
                      else
                        $redirect_pagename="electionlaunch";
            ?>
                <tr>
                 <td> <?=$record['status']?></td>
                 <td> <?=$record['title']?></td>

                 <td> <?=$start_time?></td>
                 <td> <?=$end_time?></td>
                 <td>
                  <!-- delete election button -->
                  <a class="btn btn-danger btn-circle " href="deleteelection.php?electionid=<?=$record['election_id']?>"><i class="fas fa-trash"></i></a>
               
                  <!-- view election button -->
                  <a class="btn btn-primary btn-circle " href="<?=$redirect_pagename?>.php?electionid=<?=$record['election_id']?>"><i class="fas fa-eye"></i></a>
                </td>   
               </tr>
<?php 
    }
?>  
  </tbody>
</table>
<!-- close table responsive -->
</div>
</div>
</div>
</div>
</div>
<?php
include "include/footer.template.php";
?>	



