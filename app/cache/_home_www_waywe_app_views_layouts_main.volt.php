
   <!-- navbar -->
    <header class="navbar navbar-inverse" role="banner">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" id="menu-toggler">
                <span class="sr-only">Навигация</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html"><?php echo $this->tag->image(array('img/logo-waywe-index.png')); ?></a>
        </div>
        <ul class="nav navbar-nav pull-right hidden-xs">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle hidden-xs hidden-sm" data-toggle="dropdown">
                     <i class="icon-cog"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="personal-info.html">Личная информация</a></li>
                    <li><a href="signin.html">Выход</a></li>
                </ul>
            </li>
        </ul>
    </header>
    <!-- end navbar -->
    
    <!-- sidebar -->
    <div id="sidebar-nav">
        <ul id="dashboard-menu">
            <li>
                <a href="index.html">
                    <i class="icon-edit"></i>
                    <span>Кабинет</span>
                </a>
            </li>            
            <li>
                <a class="dropdown-toggle" href="#">
                    <i class="icon-group"></i>
                    <span>Клиенты</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <li><a href="user-list.html">Список контактов</a></li>
                    <li><a href="new-user.html">Новый клиент</a></li>
                </ul>
            </li>
            <li class="hidden-desktop">                
                <a class="dropdown-toggle" href="#">
                    <i class="icon-cog"></i>
                    <span>Настройки</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <li><a href="personal-info.html">Личная информация</a></li>
                    <li><a href="signin.html">Выход</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- end sidebar -->

    <?php echo $this->getContent(); ?>