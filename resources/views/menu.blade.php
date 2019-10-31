<?php
    $user = \Illuminate\Support\Facades\Session::get('user');
?>
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- search form -->
        <form action="{{ url('param/change/year') }}" method="post" class="sidebar-form">
            {{ csrf_field() }}
            <div class="input-group">
                <?php $year = date('Y'); ?>
                <select name="year" class="form-control">
                    @while($year >= '2018')                    
                    <option {{ (Session::get('year')==$year) ? 'selected':'' }}>{{ $year }}</option>
                    <?php $year-- ;?>
                    @endwhile
                </select>
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-check"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="{{ ($menu=='home') ? 'active':'' }}">
                <a href="{{ url('/') }}/">
                    <i class="fa fa-home"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ ($menu=='participants') ? 'active':'' }}">
                <a href="{{ url('/participants') }}/">
                    <i class="fa fa-users"></i> <span>Participants</span>
                </a>
            </li>
            <li class="{{ ($menu=='division') ? 'active':'' }}">
                <a href="{{ url('/division') }}/">
                    <i class="fa fa-building"></i> <span>Division</span>
                </a>
            </li>
            
            <li class="{{ ($menu=='trainings') ? 'active':'' }}">
                <a href="{{ url('/trainings') }}/">
                    <i class="fa fa-clipboard"></i> <span>Trainings</span>
                </a>
            </li>
            <li class="{{ ($menu=='deliverables') ? 'active':'' }}">
                <a href="{{ url('/deliverables') }}/">
                    <i class="fa fa-random"></i> <span>Deliverables</span>
                </a>
            </li>
            {{--  <li class="treeview @if($menu=='documents' || $menu=='accept' || $menu=='pending') menu-open @endif ">
                <a href="#">
                    <i class="fa fa-file-pdf-o"></i> <span>Documents</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" @if($menu=='documents' || $menu=='accept' || $menu=='pending') style="display:block;" @endif >
                    <li class="{{ ($menu=='documents') ? 'active':'' }}"><a href="{{ url('/documents') }}"><i class="fa fa-folder-open-o"></i> My Documents</a></li>
                    <li class="{{ ($menu=='accept') ? 'active':'' }}"><a href="#acceptDocument" data-toggle="modal"><i class="fa fa-calendar-plus-o"></i> Accept Document(s)</a></li>
                    <li class="{{ ($menu=='pending') ? 'active':'' }}"><a href="{{ url('/documents/pending') }}"><i class="fa fa-clock-o"></i> Pending Documents</a></li>
                </ul>
            </li>  --}}
            <li class="header">REPORTS</li>
            <li class="{{ ($menu=='monitoring') ? 'active':'' }}">
                <a href="{{ url('/monitoring') }}/">
                    <i class="fa fa-area-chart"></i> <span>Monitoring</span>
                </a>
            </li>
            <li class="{{ ($menu=='notraining') ? 'active':'' }}">
                <a href="{{ url('/participants/notraining') }}/">
                    <i class="fa fa-user-times"></i> <span>No Traning</span>
                </a>
            </li>
            <li class="header">ACCOUNT SETTINGS</li>
            <li>
                <a href="{{ url('/logout') }}">
                    <i class="fa fa-sign-out"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>