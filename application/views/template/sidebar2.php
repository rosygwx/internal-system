<nav class="navbar navbar-default" id="sidebar">
    <ul class="nav navbar-nav sidenav">	
	    <!-- <li <?php if($current=='dashboard'){?> class="active" <?php } ?> ><a href="#">Dashoboard</a></li> <?}?> -->

      <li <?php if($current=='revenue'){?> class="active" <?php } ?> ><a href="<?=BASE_URL()?>contract/revenue">Revenue</a>

      <!-- <li <?php if($current=='company'){?> class="active" <?php } ?> ><a href="<?=BASE_URL()?>company/lists">Company</a></li> <?}?> -->

      
          
      </li> <?}?>
      <li <?php if($current=='contract'){?> class="active" <?php } ?> ><a href="<?=BASE_URL()?>contract/lists">Contract</a>
      		
      </li> <?}?>
      

      <li <?php if($current=='task'){?> class="active" <?php } ?> ><a href="<?=BASE_URL()?>task/lists">Task</a></li> <?}?>
     

      <li <?php if($current=='schedule'){?> class="active" <?php } ?> >
        <a data-toggle="collapse" aria-expanded="false" href="<?=BASE_URL()?>schedule/lists">Schedule</a>
        <ul class="collapse list-unstyled" id="homeSubmenu">
                    <li><a href="#">Page</a></li>
                    <li><a href="#">Page</a></li>
                    <li><a href="#">Page</a></li>
                </ul>
      </li> 


     <!--  <li <?php if($current=='employee'){?> class="active" <?php } ?> ><a href="<?=BASE_URL()?>employee/lists">Employee</a></li> <?}?> -->
    
    <!-- <li <?php if($current=='test'){?> class="active" <?php } ?> ><a data-toggle="collapse" aria-expanded="false"  href="<?=BASE_URL()?>schedule/lists">Test</a>
			<ul class="collapse list-unstyled">
                <li><a href="<?=BASE_URL()?>contract/lists">Lists</a></li>
                <li><a href="<?=BASE_URL()?>contract/add">Add</a></li>
            </ul>
      </li> <?}?> -->
    </ul>
</nav>

<!--
<div class="wrapper">

    <nav id="sidebar">
        <!-- Sidebar Header
        <div class="sidebar-header">
            <h3>Collapsible Sidebar</div>
        </div>

        <!-- Sidebar Links 
        <ul class="list-unstyled components">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">About</a></li>

            <li><!-- Link with dropdown items 
                <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">Pages</a>
                <ul class="collapse list-unstyled" id="homeSubmenu">
                    <li><a href="#">Page</a></li>
                    <li><a href="#">Page</a></li>
                    <li><a href="#">Page</a></li>
                </ul>

            <li><a href="#">Portfolio</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </nav>

</div>
-->