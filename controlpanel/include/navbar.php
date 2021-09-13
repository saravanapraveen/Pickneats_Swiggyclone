<div class="header-container fixed-top">
    <header class="header navbar navbar-expand-sm">
        <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>
        <ul class="navbar-item flex-row">
            <li class="nav-item align-self-center page-heading">
                <div class="page-header">
                    <div class="page-title">
                        <h3><?php echo $pageName ?></h3>
                    </div>
                </div>
            </li>
        </ul>
        <ul class="navbar-item flex-row search-ul">
            <li class="nav-item align-self-center search-animated">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search toggle-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <form class="form-inline search-full form-inline search" role="search">
                    <div class="search-bar">
                        <input type="text" class="form-control search-form-control  ml-lg-auto" id="input-search" placeholder="Search...">
                    </div>
                    <div class="search-box">
                        <?php
                            $sql = "SELECT * FROM login WHERE control='2' ORDER BY login_name ASC";
                            $result = $conn->query($sql);
                            while($shopLogin = $result->fetch_assoc()){
                                $shop_id = $shopLogin['login_id'];

                                $sql1 = "SELECT * FROM shop WHERE login_id='$shop_id'";
                                $result1 = $conn->query($sql1);
                                $shopLogin1 = $result1->fetch_assoc();
                        ?>
                                <a href="view-shop.php?login_id=<?php echo $shop_id ?>" class="items">
                                    <div class="search-flex">
                                        <img class="search-img" src="<?php echo $shopLogin1['shop_image'] ?>" alt="">
                                        <p class="search-text"><?php echo $shopLogin['login_name'] ?></p>
                                    </div>
                                </a>
                        <?php
                            }
                        ?>
                    </div>
                </form>
            </li>
        </ul>
        <ul class="navbar-item flex-row navbar-dropdown">
            <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <!-- <img src="assets/img/90x90.jpg" alt="avatar"> -->
                    <div class="avatar avatar-sm">
                        <span class="avatar-title rounded-circle">AG</span>
                    </div>
                </a>
                <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                    <div class="user-profile-section">
                        <div class="media mx-auto">
                            <!-- <img src="assets/img/90x90.jpg" class="img-fluid mr-2" alt="avatar"> -->
                            <div class="avatar avatar-sm">
                                <span class="avatar-title rounded-circle">AG</span>
                            </div>
                            <div class="media-body">
                                <h5>Xavier</h5>
                                <p>Project Leader</p>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-item">
                        <a href="change-password.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-unlock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg>
                            <span> Change Password</span>
                        </a>
                    </div>
                    <div class="dropdown-item">
                        <a href="javascript:void(0);" onclick="logOut()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Log Out</span>
                        </a>
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>