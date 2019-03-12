<style type="text/css">
    body,html{
    
  }

  nav.sidebar, .main{
    -webkit-transition: margin 200ms ease-out;
      -moz-transition: margin 200ms ease-out;
      -o-transition: margin 200ms ease-out;
      transition: margin 200ms ease-out;
  }

  .main{
    padding: 10px 10px 0 10px;
  }

 @media (min-width: 765px) {

    .main{
      position: absolute;
      width: calc(100% - 40px); 
      margin-left: 40px;
      float: right;
    }

    nav.sidebar:hover + .main{
      margin-left: 200px;
    }

    nav.sidebar.navbar.sidebar>.container .navbar-brand, .navbar>.container-fluid .navbar-brand {
      margin-left: 0px;
    }

    nav.sidebar .navbar-brand, nav.sidebar .navbar-header{
      text-align: center;
      width: 100%;
      margin-left: 0px;
    }
    
    nav.sidebar a{
      padding-right: 13px;
    }

    nav.sidebar .navbar-nav > li:first-child{
      border-top: 1px #e5e5e5 solid;
    }

    nav.sidebar .navbar-nav > li{
      border-bottom: 1px #e5e5e5 solid;
    }

    nav.sidebar .navbar-nav .open .dropdown-menu {
      position: static;
      float: none;
      width: auto;
      margin-top: 0;
      background-color: transparent;
      border: 0;
      -webkit-box-shadow: none;
      box-shadow: none;
    }

    nav.sidebar .navbar-collapse, nav.sidebar .container-fluid{
      padding: 0 0px 0 0px;
    }

    .navbar-inverse .navbar-nav .open .dropdown-menu>li>a {
      color: #777;
    }

    nav.sidebar{
      width: 200px;
      height: 100%;
      margin-left: -160px;
      float: left;
      margin-bottom: 0px;
    }

    nav.sidebar li {
      width: 100%;
    }

    nav.sidebar:hover{
      margin-left: 0px;
    }

    .forAnimate{
      opacity: 0;
    }
  }
   
  @media (min-width: 1330px) {

    .main{
      width: calc(100% - 200px);
      margin-left: 200px;
    }

    nav.sidebar{
      margin-left: 0px;
      float: left;
    }

    nav.sidebar .forAnimate{
      opacity: 1;
    }
  }

  nav.sidebar .navbar-nav .open .dropdown-menu>li>a:hover, nav.sidebar .navbar-nav .open .dropdown-menu>li>a:focus {
    color: #CCC;
    background-color: transparent;
  }

  nav:hover .forAnimate{
    opacity: 1;
  }
  section{
    padding-left: 15px;
  }
  
 
</style>

<nav class="navbar navbar-default sidebar" role="navigation">
    <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>      
    </div>
    <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <!-- <li class="dropdown <?php if($current == 'revenue'){ echo 'active'; }?>">

          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Revenue <i class="fas fa-chart-line fa-lg pull-right hidden-xs" ></i></a>
          <ul class="dropdown-menu forAnimate" role="menu">
            <li><a href="<?=base_url().'contract/revenue?company_type=1'?>"><i class="fas fa-angle-right"></i> TxDot</a></li>
            <li><a href="<?=base_url().'contract/revenue?company_type=2'?>"><i class="fas fa-angle-right"></i> Commercial</a></li>
          </ul>
        </li>  -->

        <li class="dropdown <?php if($current == 'contract'){ echo 'active'; }?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Contract <i class="fas fa-folder-open fa-lg pull-right hidden-xs"></i></a>
          <ul class="dropdown-menu forAnimate" role="menu">
            <li><a href="<?=base_url().'contract/lists'?>"><i class="fas fa-angle-right"></i></i> List (TxDot)</a></li>
            <li><a href="<?=base_url().'contract/add'?>"><i class="fas fa-angle-right"></i>  Add (TxDot )</a></li>
            <li><a href="<?=base_url().'contract/lists?company_type=2'?>"><i class="fas fa-angle-right"></i> List (Commercial)</a></li>
            <li><a href="<?=base_url().'contract/addCom'?>"><i class="fas fa-angle-right"></i> Add (Commercial)</a></li>
          </ul>
        </li> 

       <!--  <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Task <i class="fas fa-clipboard-list fa-lg pull-right hidden-xs" ></i></a>
          <ul class="dropdown-menu forAnimate" role="menu">
            <li><a href="<?=base_url().'task/lists?company_type=1'?>"><i class="fas fa-angle-right"></i> TxDot</a></li>
            <li><a href="<?=base_url().'task/lists?company_type=2'?>"><i class="fas fa-angle-right"></i> Commercial</a></li>
            
          </ul>
        </li>  -->

        <li class="dropdown <?php if($current == 'schedule'){ echo 'active'; }?>" >
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Schedule <i class="fas fa-tasks fa-lg pull-right hidden-xs" ></i></a>
          <ul class="dropdown-menu forAnimate" role="menu">
            <li><a href="<?=base_url().'schedule/calendar?'?>"><i class="fas fa-angle-right"></i> Calendar(TxDOT)</a></li>
            <li><a href="<?=base_url().'schedule/lists?company_type=1'?>"><i class="fas fa-angle-right"></i> List (TxDot)</a></li>
            <li><a href="<?=base_url().'schedule/listsCom'?>"><i class="fas fa-angle-right"></i> List (Commercial)</a></li>
            
            <li><a href="<?=base_url().'schedule/add'?>"><i class="fas fa-angle-right"></i>  Add (Commercial)</a></li>
          </ul>
        </li> 

         <li class="dropdown <?php if($current == 'report'){ echo 'active'; }?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Report <i class="fas fa-chart-pie fa-lg pull-right hidden-xs" ></i></a>
          <ul class="dropdown-menu forAnimate" role="menu">
            <li><a href="<?=base_url().'contract/revenue'?>"><i class="fas fa-angle-right"></i> Revenue (TxDot)</a></li>
            <li><a href="<?=base_url().'contract/monthlyBill'?>"><i class="fas fa-angle-right"></i> Monthly Bill (TxDot)</a></li>
            <li><a href="<?=base_url().'contract/dailyReport'?>"><i class="fas fa-angle-right"></i> Daily Report (TxDot)</a></li>
            <li><a href="<?=base_url().'contract/revenue/?company_type=2'?>"><i class="fas fa-angle-right"></i> Revenue (Commercial)</a></li>
            <li><a href="<?=base_url().'schedule/monthlyBillCom'?>"><i class="fas fa-angle-right"></i> Monthly Bill (Commercial)</a></li>
            <li><a href="<?=base_url().'truck/reportExpense'?>"><i class="fas fa-angle-right"></i> Truck Expenses<br>&nbsp&nbsp(ManagerPlus)</a></li>
          </ul>
        </li> 

        
        <!-- <li <?php if($current == 'employee'){ echo 'class="active"'; }?> ><a href="<?=base_url().'employee/lists'?>"> Employee<i class="fas fa-users fa-lg pull-right hidden-xs" ></i></a></li> -->   
      </ul>
    </div>
  </div>
</nav>




<!-- Original Copy

<nav class="navbar navbar-default sidebar" role="navigation">
    <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>      
    </div>
    <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-home"></span></a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Usuarios <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-user"></span></a>
          <ul class="dropdown-menu forAnimate" role="menu">
            <li><a href="{{URL::to('createusuario')}}">Crear</a></li>
            <li><a href="#">Modificar</a></li>
            <li><a href="#">Reportar</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li class="divider"></li>
            <li><a href="#">Informes</a></li>
          </ul>
        </li>          
        <li ><a href="#">Libros<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-th-list"></span></a></li>        
        <li ><a href="#">Tags<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-tags"></span></a></li>
      </ul>
    </div>
  </div>
</nav> -->