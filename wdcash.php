  <!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    
  <?php include"include/connect.php" ?>
  <?php include"include/head.php" ?>

  <title>View / Add Cash - <?php echo $comp_name ?>  </title>
  
</head>
<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar"
data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
  
<?php $link="wdcash.php"; ;?>


<?php
if(isset($_POST['submitcap'])){
    $msg="Unsuccessful" ;
    
     $srcid=$_POST['src'];
     $rows =mysqli_query($con,"SELECT * FROM acts where id=$srcid ORDER BY name" ) or die(mysqli_error($con));
      while($row=mysqli_fetch_array($rows)){ 
        $srcname = $row['name'];
        $srcbalance = $row['balance'];
        $srctype = $row['type'];
      }

     $destid=$_POST['dest'];
     $rows =mysqli_query($con,"SELECT * FROM acts where id=$destid ORDER BY name" ) or die(mysqli_error($con));
      while($row=mysqli_fetch_array($rows)){ 
        $destname = $row['name'];
        $destbalance = $row['balance'];
        $desttype = $row['type'];
      }

      $amount=$_POST['amount'];
    


     $srcbalance=$srcbalance-$amount;
     $destbalance=$destbalance+$amount;


     $datec=date('Y-m-d');
     $dateup=date('Y-m-d');
     $desp='Cash is Drawn from '.$srcname.' to '.$destname;
     
//transaction Entry
    $data=mysqli_query($con,"INSERT INTO transaction (desp,dract,cract,dr,datec,dateup)VALUES ('$desp','$destid','$srcid','$amount','$datec','$dateup')")or die( mysqli_error($con) );


    $sqls = "UPDATE acts SET `balance` = '$srcbalance' WHERE `id` = $srcid"  ;
    mysqli_query($con, $sqls)or die(mysqli_error($con));

    $sqls = "UPDATE acts SET `balance` = '$destbalance' WHERE `id` = $destid"  ;
    mysqli_query($con, $sqls)or die(mysqli_error($con));


//Ledger Entry
     $rows =mysqli_query($con,"SELECT id FROM transaction ORDER BY id desc limit 1" ) or die(mysqli_error($con));
      while($row=mysqli_fetch_array($rows)){ 
        $jid = $row['id'];

      }

      $desp='Cash drawn from '.$srcname;
      
    $data=mysqli_query($con,"INSERT INTO journal (jid,actid,desp,type,cr,balance,datec,dateup)VALUES ('$jid','$srcid','$desp','$srctype','$amount','$srcbalance','$datec','$dateup')")or die( mysqli_error($con) );

      $desp='Cash to '.$destname;
      
    $data=mysqli_query($con,"INSERT INTO journal (jid,actid,desp,type,dr,balance,datec,dateup)VALUES ('$jid','$destid','$desp','$desttype','$amount','$destbalance','$datec','$dateup')")or die( mysqli_error($con) );

	$msg="Successful" ;
    
}
?>



<?php include"include/header.php" ?>
<?php include"include/sidebar.php" ?>
<div class="app-content content">
  <div class="content-wrapper">


    <div class="col-sm-12">
      <div class="card">
        <div class="card-block">
          <div class="card-body">
           
            <h2>Current Cash Status</h2>
           
           <?php // Overall - For all types of Account from Ledger by account type
           $gtotal=0;
           $allrows =mysqli_query($con,"SELECT id,name FROM acts WHERE purpose='cash'  ORDER BY name" ) or die(mysqli_error($con));
           while($allrow=mysqli_fetch_array($allrows)){
            $actid = $allrow['id'];
            $actname = $allrow['name'];
            
            $tcr=0;
            $tdr=0;
            $total=0;
            $rows =mysqli_query($con,"SELECT cr FROM journal WHERE actid=$actid " ) or die(mysqli_error($con));

            while($row=mysqli_fetch_array($rows)){
              $cr = $row['cr'];
              $tcr=$tcr+$cr;
            } 
            $rows =mysqli_query($con,"SELECT dr FROM journal WHERE actid=$actid " ) or die(mysqli_error($con));
            
            while($row=mysqli_fetch_array($rows)){
              $dr = $row['dr'];
              $tdr=$tdr+$dr;
            } 
            $total=$tdr-$tcr;
            $gtotal=$gtotal+$total;
            ?>

       

           <div class="row align-items-center">
            <div class="col-md-3">
            </div>
            <div class="col-md-4">
                                
              <h3><?php echo $actname ?>:</h3>
            </div>
            <div class="col-md-2">
                                
              <h3>Rs. <?php echo number_format($total,$floating);   ?>/-</h3>
            </div>
            </div>


            <?php } ?>
    
    


          <hr>
          <div class="row align-items-center">
           <div class="col-md-3">
           </div>
           <div class="col-md-4">
                               
             <h3>Total :</h3>
           </div>
           <div class="col-md-2">
                               
             <h3>Rs. <?php echo number_format($gtotal,$floating) ?>/-</h3>
           </div>
           </div>

        </div>
      </div>
    </div>


  	  	<div class="col-sm-12">
  	  	  <div class="card">
  	  	    <div class="card-header" style="padding-bottom: 0px;">
  	  	      <h4 class="card-title">Withdraw Cash / Capital</h4>
  	  	    </div>
  	  	    <div class="card-block">
  	  	      <div class="card-body">
  	  	      	<form action="" method="post">
  	  	      	<div class="row">

  		  	      	<div class="col-sm-4">
  			  	      	<span>Select Drawing Capital Account</span>
                    <select class="form-control select2" name="dest">
                      <?php

                      $rows =mysqli_query($con,"SELECT * FROM acts where type='drawing capital'  ORDER BY name" ) or die(mysqli_error($con));
                                
                        while($row=mysqli_fetch_array($rows)){
                          
                          $id = $row['id'];
                          $name = $row['name']; ?>

                      <option value="<?php echo $id ?>"><?php echo $name ?></option>

                      <?php } ?>

                    </select>
  		  	      	</div>
  		  	      	<div class="col-sm-3">
  			  	      	<span>Cash Account</span>
  			  	      	<select class="form-control select2" name="src">
  			  	      		<?php

  			  	      		$rows =mysqli_query($con,"SELECT * FROM acts where purpose='cash'  ORDER BY name" ) or die(mysqli_error($con));
  			  	      		          
  			  	      			while($row=mysqli_fetch_array($rows)){
  			  	      				
  			  	      				$id = $row['id'];
  			  	      				$name = $row['name']; ?>

  			  	      		<option value="<?php echo $id ?>"><?php echo $name ?></option>

  			  	      		<?php } ?>

  			  	      	</select>

  		  	      	</div>
  		  	      	<div class="col-sm-2">
  			  	      	<span>Amount </span>
  			  	          <input type="number" name="amount" class="form-control" placeholder="0">
  		  	      	</div>
  		  	      	<div class="col-sm-1">
  			  	      	<span>&nbsp;</span>
  			  	          <input type="submit" class="btn btn-primary" name="submitcap" value="Add">
  		  	      	</div>
  		  	      	
  	  	      	</div>
  	  	      </form>
                          <center><h2><?php if(!empty($msg)) { ?>
                            
                            <br>
                            <hr>
                            <br>
                          <?php echo $msg ; } ?></h2></center>
  	  	      </div>
  	  	    </div>
  	  	  </div>
  	  	</div>



    
  </div>
</div>


<?php include"include/footer.php" ?>

</body>
</html>