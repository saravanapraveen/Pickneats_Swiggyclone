<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">
        <ul class="navbar-nav theme-brand flex-row  text-center">
            <li class="nav-item theme-text">
                <a href="index.html" class="nav-link"> Pickneats </a>
            </li>
            <li class="nav-item toggle-sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather sidebarCollapse feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
            </li>
        </ul>
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu <?php echo $dashboardActivation?>">
                <?php
                    if($dashboardToggle == ''){
                        $dashboardToggle = 'false';
                    }
                ?>
                <a href="dashboard.php" aria-expanded="<?php echo $dashboardToggle ?>" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span> Dashboard</span>
                    </div>
                </a>
            </li>
            <li class="menu menu-heading">
                <div class="heading">
                    <span>Master Control</span>
                </div>
            </li>
            <?php 
            if($control_id==0)
            {
                ?>
                    <li class="menu <?php echo $cityActivation ?>">
                        <?php
                            if($cityToggle == ''){
                                $cityToggle = 'false';
                            }
                        ?>
                        <a href="city.php" aria-expanded="<?php echo $cityToggle ?>" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                <span> City</span>
                            </div>
                        </a>
                    </li>
                <?php
            }
            ?>
            <li class="menu <?php echo $serviceActivation ?>">
                <?php
                    if($serviceToggle == ''){
                        $serviceToggle = 'false';
                    }
                ?>
                <a href="service.php" aria-expanded="<?php echo $serviceToggle ?>" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span> Service</span>
                    </div>
                </a>
            </li>
            <?php
                if($control_id == 0)
                {
                    ?>
                        <li class="menu <?php echo $adminActivation ?>">
                            <?php
                                if($adminToggle == ''){
                                    $adminToggle = 'false';
                                }
                            ?>
                            <a href="admin.php" aria-expanded="<?php echo $adminToggle ?>" class="dropdown-toggle">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                    <span> Admin</span>
                                </div>
                            </a>
                        </li>

                        <li class="menu <?php echo $ownerActivation ?>">
                            <?php
                                if($ownerToggle == ''){
                                    $ownerToggle = 'false';
                                }
                            ?>
                            <a href="owner.php" aria-expanded="<?php echo $ownerToggle ?>" class="dropdown-toggle">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                    <span> Owner</span>
                                </div>
                            </a>
                        </li>

                    <?php
                }
            ?>
            
            
            <li class="menu <?php echo $offerActivation ?>">
                <?php
                    if($offerToggle == ''){
                        $offerToggle = 'false';
                    }
                ?>
                <a href="offers.php" aria-expanded="<?php echo $offerToggle ?>" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span> Offers</span>
                    </div>
                </a>
            </li>
            <?php
                if($control_id == 0)
                {
                    ?>
                        <li class="menu <?php echo $controlActivation ?>">
                            <?php
                                if($controlToggle == ''){
                                    $controlToggle = 'false';
                                }
                            ?>
                            <a href="controls.php" aria-expanded="<?php echo $controlToggle ?>" class="dropdown-toggle">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                    <span> Controls</span>
                                </div>
                            </a>
                        </li>
                    <?php
                }
            ?>
            
            <li class="menu menu-heading">
                <div class="heading">
                    <span>Menus</span>
                </div>
            </li>
            <li class="menu <?php echo $shopActivation ?>">
                <?php
                    if($shopToggle == ''){
                        $shopToggle = 'false';
                    }
                ?>
                <a href="shop.php" aria-expanded="<?php echo $shopToggle ?>" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span> Shop</span>
                    </div>
                </a>
            </li>
            <li class="menu <?php echo $timeActivation ?>">
                <?php
                    if($timingToggle == ''){
                        $timingToggle = 'false';
                    }
                ?>
                <a href="timing.php" aria-expanded="<?php echo $timingToggle ?>" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span> Timing</span>
                    </div>
                </a>
            </li>
            <li class="menu <?php echo $unitActivation ?>">
                <?php
                    if($unitToggle == ''){
                        $unitToggle = 'false';
                    }
                ?>
                <a href="unit.php" aria-expanded="<?php echo $unitToggle ?>" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span> Unit</span>
                    </div>
                </a>
            </li>
        </ul>
    </nav>
</div>