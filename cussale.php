<!doctype html>
<html>
<head>
  <?php include"include/connect.php" ?>
  <?php include"include/head.php" ?>

  <title>Customer Sale Invoice  - <?php echo $comp_name ?>  </title>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

  <script type="text/javascript" src="dist/jautocalc.js"></script>
  <script type="text/javascript">
    <!--
      $(document).ready(function() {

        function autoCalcSetup() {
          $('form[name=cart]').jAutoCalc('destroy');
          $('form[name=cart] tr[name=line_items]').jAutoCalc({keyEventsFire: true, decimalPlaces: 2, emptyAsZero: true});
          $('form[name=cart]').jAutoCalc({decimalPlaces: 2});
        }
        autoCalcSetup();


        $('button[name=remove]').click(function(e) {
          e.preventDefault();

          var form = $(this).parents('form')
          $(this).parents('tr').remove();
          autoCalcSetup();

        });

        $('button[name=add]').click(function(e) {
          e.preventDefault();

          var $table = $(this).parents('table');
          var $top = $table.find('tr[name=line_items]').first();
          var $new = $top.clone(true);

          $new.jAutoCalc('destroy');
          $new.insertBefore($top);
          $new.find('input[type=text]').val('');
          autoCalcSetup();
          $("select2").select2();

        });

      });
        //-->
      </script>

      <style type="text/css">
      .mycon {
        border: 1px solid #cacfe7;
        color: #3b4781;
        border-radius: 0.25rem;
        padding: 8px 10px;
      }
      .table th, .table td {
        padding: 5px 6px;
        text-align: center;
        vertical-align: middle;
      }
    </style>
  </head>
  <body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar"
  data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">


  <?php if(isset($_POST['send'])){



    $x= count($home = $_POST['qty1']);

    for ($i=0; $i < $x; $i++) {

      $pid = $_POST['item'][$i];
      $qty = $_POST['qty1'][$i];

      $rows =mysqli_query($con,"SELECT stock FROM items where id=$pid ORDER BY name" ) or die(mysqli_error($con));
      while($row=mysqli_fetch_array($rows)){ 
        $stock = $row['stock'];

      }
      if($qty>$stock){

        $msg = 'Quantity is greater than Stock';
        exit($msg);


      }



    }






    $act = $_POST['act'];
    $pay = $_POST['pay'];


    $rows =mysqli_query($con,"SELECT * FROM customers where id=$act ORDER BY name" ) or die(mysqli_error($con));
    while($row=mysqli_fetch_array($rows)){ 
      $actname = $row['name'];
      $actbalance = $row['balance'];
      $acttype = $row['type'];
      $acttypeid = $row['typeid'];

    }

    $chequeno = $_POST['chequeno'];
    $chequeamt = $_POST['chequeamt'];


    $subt = $_POST['sub_total'];
    $amount = preg_replace("/[^0-9^.]/", '', $subt); 
    $discount = $_POST['discount'];


    $discid=200039;
    $rows =mysqli_query($con,"SELECT * FROM acts where id=$discid ORDER BY name" ) or die(mysqli_error($con));
    while($row=mysqli_fetch_array($rows)){ 
      $discname = $row['name'];
      $discbalance = $row['balance'];
      $disctype = $row['type'];
      $disctypeid = $row['typeid'];
    }


    $datec=date('Y-m-d');
    $dateup=date('Y-m-d');



    if($pay=='credit'){

      $srcid=200019;
      $rows =mysqli_query($con,"SELECT * FROM acts where id=$srcid ORDER BY name" ) or die(mysqli_error($con));
      while($row=mysqli_fetch_array($rows)){ 
        $srcname = $row['name'];
        $srcbalance = $row['balance'];
        $srctype = $row['type'];
        $srctypeid = $row['typeid'];

      }

      $destid=$act;
      $rows =mysqli_query($con,"SELECT * FROM customers where id=$destid ORDER BY name" ) or die(mysqli_error($con));
      while($row=mysqli_fetch_array($rows)){ 
        $destname = $row['name'];
        $destbalance = $row['balance'];
        $desttype = $row['type'];
        $desttypeid = $row['typeid'];
      }

      //First Entry

      $srcbalance=$srcbalance+$amount;
      $destbalance=$destbalance+$amount-$discount;
      $aamount=$amount-$discount;
      $discbalance=$discbalance+$discount;




      $desp='Goods are Sold to '.$destname.' on Credit';

                    //Journal Entry
      $data=mysqli_query($con,"INSERT INTO journal (desp,dract,cract,cr,dr,datec,dateup)VALUES ('$desp','$destid','$srcid','$amount','$amount','$datec','$dateup')")or die( mysqli_error($con) );


  $sqls = "UPDATE acts SET `balance` = '$srcbalance' WHERE `id` = $srcid"  ;
  mysqli_query($con, $sqls)or die(mysqli_error($con));

  $sqls = "UPDATE customers SET `balance` = '$destbalance' WHERE `id` = $destid"  ;
  mysqli_query($con, $sqls)or die(mysqli_error($con));

  $sqls = "UPDATE acts SET `balance` = '$discbalance' WHERE `id` = $discid"  ;
  mysqli_query($con, $sqls)or die(mysqli_error($con));




                    //Ledger Entry
      $rows =mysqli_query($con,"SELECT id FROM journal ORDER BY id desc limit 1" ) or die(mysqli_error($con));
      while($row=mysqli_fetch_array($rows)){ 
        $jid = $row['id'];

      }

      $desp='Goods are Sold to '.$destname.' on Credit';


      $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,balance,cr,datec,dateup)VALUES ('$jid','$srcid','$desp','$srctype','$srctypeid','$srcbalance','$aamount','$datec','$dateup')")or die( mysqli_error($con) );


      if(!empty($discount)){

      $desp='Discount Given';

      $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,balance,cr,datec,dateup)VALUES ('$jid','$discid','$desp','$disctype','$disctypeid','$discbalance','$discount','$datec','$dateup')")or die( mysqli_error($con) ); 
      }

      $desp='Sale Account';

      $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,balance,dr,datec,dateup)VALUES ('$jid','$destid','$desp','$desttype','$desttypeid','$destbalance','$amount','$datec','$dateup')")or die( mysqli_error($con) );


    }
    else{


      $destid=$pay;
      $srcid=$act;

      if($destid==200016){ //Cash in Hands

        $srcid=200019;
          $rows =mysqli_query($con,"SELECT * FROM acts where id=$srcid ORDER BY name" ) or die(mysqli_error($con));
          while($row=mysqli_fetch_array($rows)){ 
            $srcname = $row['name'];
            $srcbalance = $row['balance'];
            $srctype = $row['type'];
            $srctypeid = $row['typeid'];
          }


          
          $rows =mysqli_query($con,"SELECT * FROM acts where id=$destid ORDER BY name" ) or die(mysqli_error($con));
          while($row=mysqli_fetch_array($rows)){ 
            $destname = $row['name'];
            $destbalance = $row['balance'];
            $desttype = $row['type'];
            $desttypeid = $row['typeid'];

          }

            //First Entry
    

        $srcbalance=$srcbalance+$amount;
        $destbalance=$destbalance+$amount-$discount;
        $aamount=$amount-$discount;
        $discbalance=$discbalance+$discount;





        $desp='Goods Sold are sold to '.$actname.' - Sales Account Credited';

                          //Journal Entry
         $data=mysqli_query($con,"INSERT INTO journal (desp,dract,cract,dr,datec,dateup)VALUES ('$desp','$destid','$srcid','$amount','$datec','$dateup')")or die( mysqli_error($con) );


         $sqls = "UPDATE acts SET `balance` = '$srcbalance' WHERE `id` = $srcid"  ;
         mysqli_query($con, $sqls)or die(mysqli_error($con));

         $sqls = "UPDATE acts SET `balance` = '$destbalance' WHERE `id` = $destid"  ;
         mysqli_query($con, $sqls)or die(mysqli_error($con));

         $sqls = "UPDATE acts SET `balance` = '$discbalance' WHERE `id` = $discid"  ;
         mysqli_query($con, $sqls)or die(mysqli_error($con));






                          //Ledger Entry
         $rows =mysqli_query($con,"SELECT id FROM journal ORDER BY id desc limit 1" ) or die(mysqli_error($con));
         while($row=mysqli_fetch_array($rows)){ 
          $jid = $row['id'];

        }


        $desp='Goods Sold are sold to '.$actname.' - Sales Account Credited';



        $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,balance,cr,datec,dateup)VALUES ('$jid','$srcid','$desp','$srctype','$srctypeid','$srcbalance','$amount','$datec','$dateup')")or die( mysqli_error($con) );

      if(!empty($discount)){

      $desp='Discount Given';

      $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,ref,balance,dr,datec,dateup)VALUES ('$jid','$discid','$desp','$disctype','$disctypeid',1,'$discbalance','$discount','$datec','$dateup')")or die( mysqli_error($con) );

      $desp='Discount Given to '.$actname;

      $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,ref,balance,dr,datec,dateup)VALUES ('$jid','$act','$desp','$acttype','$acttypeid',1,'$actbalance','$discount','$datec','$dateup')")or die( mysqli_error($con) );
}

        $desp='Payment Received to '.$destname;

        $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,ref,balance,dr,datec,dateup)VALUES ('$jid','$act','$desp','$acttype','$acttypeid',1,'$actbalance','$aamount','$datec','$dateup')")or die( mysqli_error($con) );

      
        $desp='Goods Sold are sold to '.$actname.' - Sales Account Credited';


        $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,ref,balance,cr,datec,dateup)VALUES ('$jid','$act','$desp','$acttype','$acttypeid',1,'$actbalance','$amount','$datec','$dateup')")or die( mysqli_error($con) );


        if(!empty($discount)){

        $desp='Discount Given';

        $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,balance,dr,datec,dateup)VALUES ('$jid','$discid','$desp','$disctype','$disctypeid','$discbalance','$discount','$datec','$dateup')")or die( mysqli_error($con) ); 
        }


        $desp='Payment Received to '.$destname;

        $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,balance,dr,datec,dateup)VALUES ('$jid','$destid','$desp','$desttype','$desttypeid','$destbalance','$aamount','$datec','$dateup')")or die( mysqli_error($con) );

      }
      else if($destid==200032){ //Cheque 

        $srcid=200019;
          $rows =mysqli_query($con,"SELECT * FROM acts where id=$srcid ORDER BY name" ) or die(mysqli_error($con));
          while($row=mysqli_fetch_array($rows)){ 
            $srcname = $row['name'];
            $srcbalance = $row['balance'];
            $srctype = $row['type'];
            $srctypeid = $row['typeid'];
          }

          $rows =mysqli_query($con,"SELECT * FROM acts where id=$destid ORDER BY name" ) or die(mysqli_error($con));
          while($row=mysqli_fetch_array($rows)){ 
            $destname = $row['name'];
            $destbalance = $row['balance'];
            $desttype = $row['type'];
            $desttypeid = $row['typeid'];

          }

            //First Entry
    

         $srcbalance=$srcbalance+$chequeamt;
         $destbalance=$destbalance+$amount;
         $chequebal=$amount-$chequeamt;
         $actbalance=$actbalance+$chequebal;




        $desp='Goods Sold to '.$destname.' From '.$srcname.' No. '.$chequeno;

                          //Journal Entry
         $data=mysqli_query($con,"INSERT INTO journal (desp,dract,cract,cr,datec,dateup,chequeno,chequeamt)VALUES ('$desp','$destid','$srcid','$amount','$datec','$dateup','$chequeno','$chequeamt')")or die( mysqli_error($con) );


         $sqls = "UPDATE acts SET `balance` = '$srcbalance' WHERE `id` = $srcid"  ;
         mysqli_query($con, $sqls)or die(mysqli_error($con));

         $sqls = "UPDATE acts SET `balance` = '$destbalance' WHERE `id` = $destid"  ;
         mysqli_query($con, $sqls)or die(mysqli_error($con));

         $sqls = "UPDATE customers SET `balance` = '$actbalance' WHERE `id` = $act"  ;
         mysqli_query($con, $sqls)or die(mysqli_error($con));






                          //Ledger Entry
         $rows =mysqli_query($con,"SELECT id FROM journal ORDER BY id desc limit 1" ) or die(mysqli_error($con));
         while($row=mysqli_fetch_array($rows)){ 
          $jid = $row['id'];

        }


        $desp='Goods Sold to '.$destname.' From '.$srcname.' No. '.$chequeno;


        $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,balance,cr,datec,dateup)VALUES ('$jid','$srcid','$desp','$srctype','$srctypeid','$srcbalance','$amount','$datec','$dateup')")or die( mysqli_error($con) );

        $desp=$destname.' Sale';

        $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,ref,balance,dr,datec,dateup)VALUES ('$jid','$act','$desp','$acttype','$acttypeid',1,'$actbalance','$amount','$datec','$dateup')")or die( mysqli_error($con) );

        $desp='Goods Sold to '.$destname.' From '.$srcname.' No. '.$chequeno;


        $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,ref,balance,cr,datec,dateup)VALUES ('$jid','$act','$desp','$acttype','$acttypeid',1,'$actbalance','$amount','$datec','$dateup')")or die( mysqli_error($con) );


        $desp=$destname.' Sale';

        $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,balance,dr,datec,dateup)VALUES ('$jid','$destid','$desp','$desttype','$desttypeid','$destbalance','$amount','$datec','$dateup')")or die( mysqli_error($con) );

      }
      else{


        $srcid=200019;
      $rows =mysqli_query($con,"SELECT * FROM acts where id=$srcid ORDER BY name" ) or die(mysqli_error($con));
      while($row=mysqli_fetch_array($rows)){ 
        $srcname = $row['name'];
        $srcbalance = $row['balance'];
        $srctype = $row['type'];
        $srctypeid = $row['typeid'];
      }

      
      $rows =mysqli_query($con,"SELECT * FROM acts where id=$destid ORDER BY name" ) or die(mysqli_error($con));
      while($row=mysqli_fetch_array($rows)){ 
        $destname = $row['name'];
        $destbalance = $row['balance'];
        $desttype = $row['type'];
        $desttypeid = $row['typeid'];

      }

      
                  //First Entry
          

              $srcbalance=$srcbalance+$amount;
              $destbalance=$destbalance+$amount-$discount;
              $aamount=$amount-$discount;
              $discbalance=$discbalance+$discount;





              $desp='Goods Sold are sold to '.$actname.' - Sales Account Credited';

                                //Journal Entry
               $data=mysqli_query($con,"INSERT INTO journal (desp,dract,cract,dr,datec,dateup)VALUES ('$desp','$destid','$srcid','$amount','$datec','$dateup')")or die( mysqli_error($con) );


               $sqls = "UPDATE acts SET `balance` = '$srcbalance' WHERE `id` = $srcid"  ;
               mysqli_query($con, $sqls)or die(mysqli_error($con));

               $sqls = "UPDATE acts SET `balance` = '$destbalance' WHERE `id` = $destid"  ;
               mysqli_query($con, $sqls)or die(mysqli_error($con));

               $sqls = "UPDATE acts SET `balance` = '$discbalance' WHERE `id` = $discid"  ;
               mysqli_query($con, $sqls)or die(mysqli_error($con));






                                //Ledger Entry
               $rows =mysqli_query($con,"SELECT id FROM journal ORDER BY id desc limit 1" ) or die(mysqli_error($con));
               while($row=mysqli_fetch_array($rows)){ 
                $jid = $row['id'];

              }


              $desp='Goods Sold are sold to '.$actname.' - Sales Account Credited';



              $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,balance,cr,datec,dateup)VALUES ('$jid','$srcid','$desp','$srctype','$srctypeid','$srcbalance','$amount','$datec','$dateup')")or die( mysqli_error($con) );

            if(!empty($discount)){

            $desp='Discount Given';

            $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,ref,balance,dr,datec,dateup)VALUES ('$jid','$discid','$desp','$disctype','$disctypeid',1,'$discbalance','$discount','$datec','$dateup')")or die( mysqli_error($con) );

            
            $desp='Discount Given from '.$actname;

            $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,ref,balance,dr,datec,dateup)VALUES ('$jid','$act','$desp','$acttype','$acttypeid',1,'$actbalance','$discount','$datec','$dateup')")or die( mysqli_error($con) );
      }

              $desp='Payment Received to '.$destname;

              $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,ref,balance,dr,datec,dateup)VALUES ('$jid','$act','$desp','$acttype','$acttypeid',1,'$actbalance','$aamount','$datec','$dateup')")or die( mysqli_error($con) );

            
              $desp='Goods Sold are sold to '.$actname.' - Sales Account Credited';


              $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,ref,balance,cr,datec,dateup)VALUES ('$jid','$act','$desp','$acttype','$acttypeid',1,'$actbalance','$amount','$datec','$dateup')")or die( mysqli_error($con) );


              if(!empty($discount)){

              $desp='Discount Given';

              $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,balance,dr,datec,dateup)VALUES ('$jid','$discid','$desp','$disctype','$disctypeid','$discbalance','$discount','$datec','$dateup')")or die( mysqli_error($con) ); 
              }


              $desp='Payment Received to '.$destname;

              $data=mysqli_query($con,"INSERT INTO ledger (jid,actid,desp,type,typeid,balance,dr,datec,dateup)VALUES ('$jid','$destid','$desp','$desttype','$desttypeid','$destbalance','$aamount','$datec','$dateup')")or die( mysqli_error($con) );


    }

  }


  $x= count($home = $_POST['qty1']);

  for ($i=0; $i < $x; $i++) {

    $pid = $_POST['item'][$i];
    $qty = $_POST['qty1'][$i];
    $price = $_POST['price1'][$i];



    $rows =mysqli_query($con,"SELECT name,stock FROM items where id=$pid" ) or die(mysqli_error($con));

    while($row=mysqli_fetch_array($rows)){

      $name = $row['name'];
      $stock = $row['stock'];
    }

    $stock=$stock-$qty;
    $type='out';
    $date=date('Y-m-d');


                      //Journal Entry
    $data=mysqli_query($con,"INSERT INTO itemslog (jid,pid,type,name,price,quantity,subtotal,datec)VALUES ('$jid','$pid','$type','$name','$price','$qty','$amount','$date')")or die( mysqli_error($con) );

    $sqls = "UPDATE items SET `stock` = '$stock' WHERE `id` = $pid"  ;
    mysqli_query($con, $sqls)or die(mysqli_error($con));


  }
  $msg = 'Successfull';





}

?>

<?php $link="cussale.php"; ?>

<?php include"include/header.php" ?>
<?php include"include/sidebar.php" ?>

<div class="app-content content">
 <div class="content-wrapper">

  <form name="cart" method="POST" action="" enctype="multipart/form-data">


    <div class="col-sm-12">
      <div class="card">
        <div class="card-header" style="padding-bottom: 0px;">
          <h4 class="card-title">Make Sales Invoice</h4>
        </div>
        <div class="card-block">
          <div class="card-body">

            <div class="row">

              <div class="col-sm-1">

              </div>
              <div class="col-sm-3">
                <center><span>Date:</span></center>
                <input readonly type="date" class="form-control" name="date" value="<?php echo date('Y-m-d') ?>">
              </div>

              <div class="col-sm-3">
                <center><span>Select Customer Account:</span></center>
                <select class="form-control select2" name="act">

                  <?php

                  $rows =mysqli_query($con,"SELECT * FROM customers  ORDER BY name" ) or die(mysqli_error($con));

                  while($row=mysqli_fetch_array($rows)){

                    $id = $row['id'];
                    $name = $row['name']; ?>

                    <option value="<?php echo $id ?>"><?php echo $name ?></option>

                  <?php } ?>

                </select>

              </div>
              <div class="col-sm-3">
                <center><span>Payment Type:</span></center>
                <select class="form-control select2" id="multiOptions" name="pay">
                  <option value="credit">Credit</option>

                  <?php

                  $rows =mysqli_query($con,"SELECT * FROM acts WHERE purpose ='cash'  ORDER BY name" ) or die(mysqli_error($con));

                  while($row=mysqli_fetch_array($rows)){

                    $id = $row['id'];
                    $name = $row['name']; ?>

                    <option value="<?php echo $id ?>"><?php echo $name ?></option>

                  <?php } ?>

                </select>

              </div>
            </div>
            

            <div class="row" id="chequediv">
              <div class="col-sm-3">
              </div>
              <div class="col-sm-3">
                <center><span>Cheque No :</span></center>
                <input type="text" name="chequeno" class="form-control">

              </div>
              <div class="col-sm-3">
                <center><span>Cheque Amount :</span></center>
                <input type="text" name="chequeamt" class="form-control">

              </div>


            </div>
            <hr>






            <table name="cart" class="table table-striped table-bordered">
              <tr>
                <th style="width:250px;">Product</th>
                <th  style="width:150px;">Qty</th>
                <th  style="width:150px;">Price</th>


                <th colspan="3">Item Total</th>
                <th><button name="add"  class="btn btn-primary"><i class="la la-plus"></i></button></th>
              </tr>



              <tr name="line_items">

                <td>

                 <select class="form-control " name="item[]">
                   <?php

                   $rows =mysqli_query($con,"SELECT * FROM items WHERE pause =0  ORDER BY name" ) or die(mysqli_error($con));

                   while($row=mysqli_fetch_array($rows)){

                     $id = $row['id'];
                     $name = $row['name']; ?>

                     <option value="<?php echo $id ?>"><?php echo $name ?></option>

                   <?php } ?>

                 </select>
               </td>
               <td><input  class="form-control" type="number" name="qty" id="qty" value=""></td>
               <td><input class="form-control" type="number" name="price" id="price" value="">
                <input style="display: none;" type="text" name="item_total" id="item_total" value="" jAutoCalc="{qty} * {price}"></td>

                <td colspan="3">
                  <input class="mycon" style="width:60px;" type="text" name="qty1[]"  id="qty1" value="" readonly> * 
                  <input class="mycon" style="width:60px;"  type="text" name="price1[]" id="price1" value=""  readonly> = 
                  <input  class="mycon" style="width:80px;" type="text" name="item_total1[]" id="item_total1"  readonly value="">
                </td>
                <td><button name="remove" class="btn btn-danger"><i class="la la-close"></i></button></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Total</td>
                <td><div class="input-group"><h4 style="margin-top:12px;">Rs. &nbsp; </h4><input class="form-control"   type="text" name="sub_total" value=""  readonly jAutoCalc="SUM({item_total})"><h4 style="margin-top:12px;">/- </h4> </div></td>

              </tr>





            </table>

              <div class="row">
              <div class="col-md-8">
              </div>
              <div class="col-md-3" id="disdiv">
                <div class="">
                <span>Discount</span>
              <input type="text" name="discount" class="form-control" value="0">
            </div>
            </div>

            </div>
            <hr>
            <center><button name="send"  class="btn btn-primary block-element">Add</button></center>
            

            <center><h2>

              <?php if(!empty($msg)) { ?>

                <br>
                <hr>
                <br>
                <?php echo $msg ; } ?></h2></center>
              </div>
            </div>
          </div>
        </div>

      </form>


            <div class="row">
             <div class="col-lg-2">
             </div>
             <div class="col-lg-8">
               <div class="card">
                <div id="heading1" class="card-header" role="tab">
                  <a data-toggle="collapse" data-parent="#accordionWrapa1" href="#accordion1" aria-expanded="false"
                  aria-controls="accordion1" class="card-title lead">View Quick Inventory (Active Items)</a>
                </div>
                <div id="accordion1" role="tabpanel" aria-labelledby="heading1" class="collapse hide">
                  <div class="card-content">
                   <div class="card-body">
                    <div class="row align-conter-center">

                      <table class="table table-striped table-bordered dataex-select-multi ">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Brand</th>

                            <th>Stock</th>
                            <th>Price</th>


                          </tr>
                        </thead>
                        <tbody>  
                          <?php

                          $rows =mysqli_query($con,"SELECT * FROM items  ORDER BY name" ) or die(mysqli_error($con));

                          while($row=mysqli_fetch_array($rows)){

                            $id = $row['id'];
                            $name = $row['name'];
                            $brand=$row['brand'];

                            $stock=$row['stock']; 
                            $price=$row['price']; 

                            ?>
                            <tr>


                              <td><?php echo $name ?></td>

                              <td><?php

                              $rowsl =mysqli_query($con,"SELECT * FROM itemsb Where id=$brand ORDER BY name" ) or die(mysqli_error($con));

                              while($rowl=mysqli_fetch_array($rowsl)){

                                $brandname = $rowl['name'];?>
                                <?php echo $brandname ?>
                              <?php }
                              ?>

                            </td>



                            <td><?php echo $stock ?></td> 
                            <td><?php echo $price ?></td> 

                     
                      


                          </tr>


                        <?php } ?>

                      </tbody>
                    </table>


                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>



    </div>
  </div>




</body>

<script type="text/javascript">
  
  $(document).ready(function () {

  $('#chequediv').hide();

  $("#multiOptions").change(function () {
      if ($(this).val() == "200032" ) {
         $('#chequediv').show();
         $('#disdiv').hide();
         
      }
      else { 
          $('#chequediv').hide();
          $('#disdiv').show();
           }
  });
  });

</script>

<script type="text/javascript">

  $('#qty').change(function() {
    $('#qty1').val($(this).val());
  });
  $('#price').change(function() {
    $('#price1').val($(this).val());
  });
  $('#item_total').change(function() {
    $('#item_total1').val($(this).val());
  });




</script>






<!-- BEGIN VENDOR JS-->
<script src="dist/lol.js" type="text/javascript"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
<script src="vendors/js/charts/chart.min.js" type="text/javascript"></script>
<script src="vendors/js/charts/raphael-min.js" type="text/javascript"></script>
<script src="vendors/js/charts/morris.min.js" type="text/javascript"></script>
<script src="vendors/js/charts/jvector/jquery-jvectormap-2.0.3.min.js"
type="text/javascript"></script>
<script src="vendors/js/charts/jvector/jquery-jvectormap-world-mill.js"
type="text/javascript"></script>
<script src="data/jvector/visitor-data.js" type="text/javascript"></script>
<script src="vendors/js/tables/datatable/datatables.min.js" type="text/javascript"></script>
<script src="vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>



<!-- END PAGE VENDOR JS-->
<!-- BEGIN MODERN JS-->
<script src="js/core/app-menu.js" type="text/javascript"></script>
<script src="js/core/app.js" type="text/javascript"></script>
<script src="js/scripts/customizer.js" type="text/javascript"></script>
<!-- END MODERN JS-->
<!-- BEGIN PAGE LEVEL JS-->
<script src="js/scripts/pages/dashboard-sales.js" type="text/javascript"></script>

<script src="js/scripts/tables/datatables-extensions/datatables-sources.js"
type="text/javascript"></script>

<script src="js/scripts/forms/select/form-select2.js" type="text/javascript"></script>
<script src="js/scripts/modal/components-modal.js" type="text/javascript"></script>

<script src="js/scripts/tables/datatables-extensions/datatable-button/datatable-html5.js"
type="text/javascript"></script>

<script src="js/scripts/tables/datatables/datatable-api.js" type="text/javascript"></script>


<!-- END PAGE LEVEL JS-->



<script src="js/scripts/extensions/block-ui.js" type="text/javascript"></script>

</html>
