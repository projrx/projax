
<!-- Sidebar  -->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">



      <li class="<?php if($link=="index.php") echo "active"; ?> nav-item"><a href="home.php"><i class="la la-dashboard"></i><span class="menu-title" data-i18n="nav.dash.main">Dashboard</span></a>

      </li>

      <li class="<?php if($link=="daybook.php") echo "active"; ?> nav-item"><a href="daybook.php"><i class="la la-calendar-check-o"></i><span class="menu-title" data-i18n="nav.dash.main">Daybook</span></a>

      </li>


      <li class=" navigation-header">
        <span>Invoices</span>
      </li>



      <li class=" nav-item"><a href="#"><i class="la la-money"></i><span class="menu-title">Cash</span></a>

        <ul class="menu-content">

         

          <li class="<?php if($link=="addcash.php") echo "active"; ?>"><a class="menu-item" href="addcash.php" >View / Add Cash</a> </li>

          <li class="<?php if($link=="addloan.php") echo "active"; ?>"><a class="menu-item" href="addloan.php" >Add / Pay Loan</a> </li>

          <li class="<?php if($link=="wdcash.php") echo "active"; ?>"><a class="menu-item" href="wdcash.php" >Withdraw Cash</a> </li>


        </ul>
      </li>

      <li class=" nav-item"><a href="#"><i class="la la-codepen"></i><span class="menu-title">Sales</span></a>

        <ul class="menu-content">

         

          <li class="<?php if($link=="cussale.php") echo "active"; ?>"><a class="menu-item" href="cussale.php" >Customer Sale</a> </li>

          <li class="<?php if($link=="cntsale.php") echo "active"; ?>"><a class="menu-item" href="cntsale.php" >Counter Sale</a> </li>

          <li class="<?php if($link=="recsale.php") echo "active"; ?>"><a class="menu-item" href="recsale.php" >Accounts Receivealbes</a> </li>

          <li class="<?php if($link=="rtnsale.php") echo "active"; ?>"><a class="menu-item" href="rtnsale.php" >Return Sales</a> </li>

        </ul>
      </li>


      <li class=" nav-item"><a href="#"><i class="la la-industry"></i><span class="menu-title">Purchase</span></a>
        <ul class="menu-content">


          <li class="<?php if($link=="addpur.php") echo "active"; ?>"><a class="menu-item" href="addpur.php" >Add Purchase</a> </li>

          <li class="<?php if($link=="paypur.php") echo "active"; ?>"><a class="menu-item" href="paypur.php" >Accounts Payables </a> </li>

          <li class="<?php if($link=="rtnpur.php") echo "active"; ?>"><a class="menu-item" href="rtnpur.php" >Return Purchase </a> </li>

       
        </ul>
      </li>

      <li class=" nav-item"><a href="#"><i class="la la-credit-card"></i><span class="menu-title">Payments</span></a>
        <ul class="menu-content">

       

          <li class="<?php if($link=="addexp.php") echo "active"; ?>"><a class="menu-item" href="addexp.php" >Add Expenses </a> </li>

          <li class="<?php if($link=="addast.php") echo "active"; ?>"><a class="menu-item" href="addast.php" >Add Fixed Assets</a> </li>

        </ul>
      </li>

      <li class=" nav-item"><a href="#"><i class="la la-clipboard"></i><span class="menu-title">Invoices / Bills</span></a>
        <ul class="menu-content">

       

          <li class="<?php if($link=="viewpinv.php") echo "active"; ?>"><a class="menu-item" href="viewpinv.php" >View Purchase Invoice </a> </li>

          <li class="<?php if($link=="viewinv.php") echo "active"; ?>"><a class="menu-item" href="viewinv.php" >View Our Invoices / Bills</a> </li>

        </ul>
      </li>


      





      <li class=" navigation-header">
        <span>Accounts</span>
      </li>




      <li class=" nav-item"><a href="#"><i class="la la-users"></i><span class="menu-title">Accounts</span></a>
        <ul class="menu-content">



          <li class="<?php if($link=="addacts.php") echo "active"; ?>"><a class="menu-item" href="addacts.php" > Add New Account</a> </li>

          <li class="<?php if($link=="editacts.php") echo "active"; ?>"><a class="menu-item" href="editacts.php" > View / Edit Account</a> </li>


        </ul>
      </li>

      <li class=" nav-item"><a href="#"><i class="la la-truck"></i><span class="menu-title">Vendors</span></a>
        <ul class="menu-content">


          <li class="<?php if($link=="addvend.php") echo "active"; ?>"><a class="menu-item" href="addvend.php" > Add Vendor Account</a> </li>

          <li class="<?php if($link=="editvend.php") echo "active"; ?>"><a class="menu-item" href="editvend.php" > View or Edit Vendor</a> </li>


        </ul>
      </li>


      <li class=" nav-item"><a href="#"><i class="la la-cart-plus"></i><span class="menu-title">Customers</span></a>
        <ul class="menu-content">

          <li class="<?php if($link=="addcust.php") echo "active"; ?>"><a class="menu-item" href="addcust.php" > Add Customers Account</a> </li>

          <li class="<?php if($link=="editcust.php") echo "active"; ?>"><a class="menu-item" href="editcust.php" > View or Edit Customers</a> </li>


        </ul>
      </li>


      <li class=" navigation-header">
        <span>Reports</span>
      </li>





      <li class=" nav-item"><a href="#"><i class="la la-tasks"></i><span class="menu-title">Ledger</span></a>
        <ul class="menu-content">

          <li class="<?php if($link=="genled.php") echo "active"; ?>"><a class="menu-item" href="genled.php" > General Ledger</a> </li>

 
          <li class="<?php if($link=="paypur.php") echo "active"; ?>"><a class="menu-item" href="paypur.php" >Accounts Payables </a> </li>

          <li class="<?php if($link=="recsale.php") echo "active"; ?>"><a class="menu-item" href="recsale.php" >Accounts Receivealbes</a> </li>


        </ul>
      </li>

      <li class="<?php if($link=="balsheet.php") echo "active"; ?> nav-item"><a href="balsheet.php"><i class="la la-columns"></i><span class="menu-title">Balance Sheet</span></a>

      </li>
      <li class="<?php if($link=="trialbal.php") echo "active"; ?> nav-item"><a href="trialbal.php"><i class="la la-database"></i><span class="menu-title">Trial Balance</span></a>

      </li>

      <li class=" nav-item"><a href="#"><i class="la la-server"></i><span class="menu-title">Other Reports</span></a>
        <ul class="menu-content">

          <li class="<?php if($link=="pls.php") echo "active"; ?>"><a class="menu-item" href="pls.php" >Profit Loss Statement</a> </li>

      
          <li class="<?php if($link=="pls.php") echo "active"; ?>"><a class="menu-item" href="pls.php" >Income Statement </a> </li>

          <li class="<?php if($link=="expr.php") echo "active"; ?>"><a class="menu-item" href="expr.php" >Expense Report</a> </li>


        </ul>
      </li>




      <li class=" navigation-header">
        <span>Inventory</span>
      </li>



      <li class=" nav-item"><a href="#"><i class="la la-cubes"></i><span class="menu-title">Inventory</span></a>
        <ul class="menu-content">

       

          <li class="<?php if($link=="viewitems.php") echo "active"; ?>"><a class="menu-item" href="viewitems.php" >View Stock </a> </li>

          <li class="<?php if($link=="additems.php") echo "active"; ?>"><a class="menu-item" href="additems.php" >Add Products</a> </li>

        </ul>
      </li>



      <li class=" navigation-header">
        <span>&nbsp;</span>
      </li>


      <li class=" navigation-header">
        <span>&nbsp;</span>
      </li>



   </ul> 




 </div>
</div>    