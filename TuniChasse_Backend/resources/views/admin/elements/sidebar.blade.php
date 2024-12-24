

  <!--Start sidebar-wrapper-->
   <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
     <div class="brand-logo">
      <a href="index.html">
       <img src="{{asset('images/logo-icon.png')}}" class="logo-icon" alt="logo icon">
       <h5 class="logo-text">Dashboard Admin</h5>
     </a>
   </div>
   <ul class="sidebar-menu do-nicescrol">
      <li class="sidebar-header">MAIN NAVIGATION</li>
      <li>
        <a href="{{ route('admin.index') }}">
          <i class="zmdi zmdi-view-dashboard" ></i> <span>Dashboard</span>
        </a>
      </li>

      <li>
        <a href="#users" data-toggle="collapse"  class="dropdown-toggle">
          <i class="zmdi zmdi-invert-colors"></i> <span class="titre-dropdown">Gestion utilisateurs</span>
        </a>
         <ul class="list-unstyled collapse " id="users" style="padding-left :40px;">
                           <li><a href="{{ route('admin.listusers') }}"> <span>Liste Users</span></a></li>
                           <li><a href="{{ route('admin.addusers') }}"> <span>Add Users</span></a></li>

        </ul>
      </li>
      <li>
        <a href="#prois" data-toggle="collapse"  class="dropdown-toggle" >
          <i class="zmdi zmdi-grid"></i> <span class="titre-dropdown">Gestion de Proi</span>
        </a>
        <ul class="list-unstyled collapse " id="prois" style="padding-left :40px;">
                           <li><a href="{{ route('admin.listproies') }}"> <span>Liste Proi</span></a></li>
                           <li><a href="{{ route('admin.addproies') }}"> <span>Add Proi</span></a></li>

        </ul>

      </li>
<!--
      <li>
        <a href="#notifs" data-toggle="collapse"  class="dropdown-toggle">
          <i class="zmdi zmdi-format-list-bulleted"></i> <span class="titre-dropdown">Gestion Notifications</span>
        </a>
        <ul class="list-unstyled collapse " id="notifs" style="padding-left :40px;">
                           <li><a href="{{ route('admin.listnotif') }}"> <span>Liste Notification</span></a></li>
                           <li><a href="{{ route('admin.addnotif') }}"> <span>Add Notification</span></a></li>

        </ul>
      </li>-->
      <li>
        <a href="{{ route('admin.listalerts') }}">
          <i class="zmdi zmdi-face"></i> <span>Alerts</span>
        </a>
      </li>
     <!-- <li>
  <a href="#lois" data-toggle="collapse" class="dropdown-toggle">
    <i class="zmdi zmdi-grid"></i>
    <span class="titre-dropdown" >Gestion de Lois</span>
  </a>
  <ul class="list-unstyled collapse" id="lois" style="padding-left: 40px;">
    <li><a href="{{ route('admin.listlois') }}"> <span>Liste Lois</span></a></li>
    <li><a href="{{ route('admin.addloi') }}"> <span>Add Loi</span></a></li>
  </ul>
</li>
-->

      <li>
        <a href="{{ route('admin.calendar') }}">
          <i class="zmdi zmdi-calendar-check"></i> <span>Calendar</span>

        </a>
      </li>

      <li>
        <a href="{{ route('admin.suivimap') }}">
          <i class="zmdi zmdi-face"></i> <span>Suivi Chasseur</span>
        </a>
      </li>

      <li>
        <a href="{{ route('admin.maps') }}">
          <i class="zmdi zmdi-lock"></i> <span>Geo-fence</span>
        </a>
      </li>





    </ul>


   <!--End sidebar-wrapper-->
