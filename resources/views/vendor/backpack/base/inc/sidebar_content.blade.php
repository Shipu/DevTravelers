<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
<li><a href="{{ backpack_url('event') }}"><i class="fa fa-user"></i> <span>{{ trans_choice('entity.event', 0) }}</span></a></li>
<li><a href="{{ backpack_url('payment') }}"><i class="fa fa-user"></i> <span>{{ trans_choice('entity.payment', 0) }}</span></a></li>
<li class="treeview">
    <a href="#"><i class="fa fa-group"></i> <span>Administration</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="{{ backpack_url('user') }}"><i class="fa fa-user"></i> <span>Users</span></a></li>
        <li><a href="{{ backpack_url('role') }}"><i class="fa fa-group"></i> <span>Roles</span></a></li>
        <li><a href="{{ backpack_url('permission') }}"><i class="fa fa-key"></i> <span>Permissions</span></a></li>
        <li><a href='{{ backpack_url('attribute') }}'><i class='fa fa-angellist'></i> <span>{{ trans_choice('entity.attribute', 0) }}</span></a></li>
        <li><a href='{{ backpack_url('attribute-set') }}'><i class='fa fa-angle-double-down'></i> <span>{{ trans_choice('entity.attribute-set', 0) }} </span></a></li>
        <li><a href='{{ backpack_url('asset') }}'><i class='fa fa-angle-double-down'></i> <span>{{ trans_choice('entity.asset', 0) }} </span></a></li>
        <li><a href='{{ backpack_url('setting') }}'><i class='fa fa-hdd-o'></i> <span>{{ trans_choice('entity.setting', 0) }}</span></a></li>
        <li><a href='{{ backpack_url('backup') }}'><i class='fa fa-hdd-o'></i> <span>Backups</span></a></li>
    </ul>
</li>
<li><a href='{{ backpack_url('log') }}'><i class='fa fa-terminal'></i> <span>Logs</span></a></li>
{{--<li><a href="{{ backpack_url('menu-item') }}"><i class="fa fa-list"></i> <span>Menu</span></a></li>--}}
{{--{{ dump(\Backpack\MenuCRUD\app\Models\MenuItem::getTree()) }}--}}
