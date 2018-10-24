
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        @include('inc.sidebar_user_panel')
        <!-- sidebar menu: : style can be found in sidebar.less -->
          @if(request()->decodedPath() != "/") @widget('categories') @endif
          <ul class="sidebar-menu">
              <li class="header">{{ trans('app.menu') }}</li>
          </ul>
          {{ menu('main') }}
      </section>
      <!-- /.sidebar -->
    </aside>
