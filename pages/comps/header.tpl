<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <button id='leftMenuOpen' class='pull-left'><i class='glyphicon glyphicon-menu-hamburger'></i></button>
    <a href='#' class='pull-left navbar-brand'>{$APPS_COMPANY}</a>

    <ul class="nav pull-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle strgtMidle"><i class='glyphicon glyphicon-user'></i><span class="postionName"> #SESS_USER_NAME# </span> <b class="caret"></b></a>
            <ul class="dropdown-menu profile-menu">
                <li><a href="{_link('modules/myProfile')}">My Profile</a></li>
                <li class="divider"></li>
                <li><a href="{_link('logout.php')}" target='top'>Logout</a></li>
            </ul>
        </li>
    </ul>

    <!--<button id='rightMenuOpen' class='pull-right'><i class='glyphicon glyphicon-bullhorn'></i></button>-->

    <!--<ul class="nav pull-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle ADD"><i class="glyphicon glyphicon-plus"></i><b class="caret"></b></a>
            <ul class="dropdown-menu">
                {assign var="MENUARR" value=getAddMenu()}
                {foreach from=$MENUARR item=menu}
                  <li class='{$menu.class}'><a href="{_link($menu.link)}">{$menu.title}</a></li>
                {/foreach}
            </ul>
        </li>
    </ul>-->

    <!--<div class="search-form hidden-xs">
        <div class="search-input-area">
            <input id='searchQuery' class="search-query" type="text" placeholder="Search ...">
            <i class="fa fa-search"></i>
        </div>
    </div>-->

    <!--<div class="tool-buttons hidden-xs">
      <div class="btn-group">
        {assign var="MENUARR" value=getToolsMenu()}
        {foreach from=$MENUARR item=menu}
          <a href="{_link($menu.link)}" class="btn btn-primary {$menu.class}" title="{$menu.tips}" target="{$menu.target}"><i class='{$menu.iconpath}'></i><span class='hidden'>{$menu.title}</span></a>
        {/foreach}
      </div>
    </div>-->

</nav>
