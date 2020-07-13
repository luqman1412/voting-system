<?php

    session_start();
    include "connection.php";
    // get election id from session
    $electionid=$_SESSION['electionid'];
    //If your session isn't valid, it returns you to the login screen for protection
    if(empty($_SESSION['id'])){
    header("location:index.php");
    }


include "header.template.php";
?>
<div class="container-fluid">
  
   <!-- Project Card Example -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Setting Umum</h6>
                </div>
                <div class="card-body">

                    <div class="form-group col-md-7">
                      <form class="user">
                        <div class="form-group col-md-7">
                          <label  for="title" >Election Instrution</label>
                          <input name="txt_electionname" type="text"  class="form-control form-control-user" id="title" placeholder="Please choose " value="">
                        </div>
                        <div class="form-group col-md-7">
                          <label  for="title" >Max vote</label>
                          <input name="txt_electionname" type="text"  class="form-control form-control-user" id="title" placeholder="Max vote" value="">
                        </div>
                        <div  class="col-md-4">
                          <input class="btn btn-primary btn-user btn-block" type="submit" name="btn_submit" value="Save">
                        </div>
                      </form>
                    </div>
                    
                     </div>

                </div>
              </div>
            </div>
</div>
<?php
include "footer.template.php";
?>