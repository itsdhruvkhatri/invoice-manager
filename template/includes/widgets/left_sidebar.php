<div class="wrapper">
    <nav id="sidebar">
        <div class="sidebar-header">
            <a class="navbar-brand" href="dashboard">
            <img src="template/images/<?php echo $settings->logo; ?>" alt="Logo" width="50"/>
            &nbsp; <?php echo $settings->page_title; ?>
            </a>
        </div>

        <ul class="list-unstyled components">
            
             <li class=" dropdown">
            <a href='#' id="navbardrop" data-toggle="dropdown"><img src="
                <?php if($user->pic_url == null){ 
                    echo "template/images/profile/user.png";
                }else{
                    echo "template/images/profile/" . $user->pic_url;
                }
                ?>" class="rounded-circle" style="height: 50px; width: 50px;" alt="Profile Pic"/>&nbsp;&nbsp;<?php echo $user->name . " " . $user->surname . " " . "<span class=\"fas fa-sort-down\"></span>"; ?> 
             </a>
            <div class="dropdown-menu">       
                <a class="dropdown-item badge" href="clients/edit">
                    <i class="fa fa-cog"></i> <?php echo $language['menu']['settings']; ?>
                </a>
                <br>
                <a class="dropdown-item badge" href="logout">
                    <i class="fas fa-power-off"></i> <?php echo $language['menu']['sign_out']; ?>
                </a>
            </div>      
         </li> 
            <li class="active">
                <a href="dashboard"><span class="fa fa-tachometer-alt"></span>&nbsp;<?php echo $language['menu']['dashboard']; ?></a>
            </li>
            <li>
                <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class="fa fa-copy"></span>&nbsp;<?php echo $language['menu']['invoices']; ?></a>
                <ul class="collapse list-unstyled" id="homeSubmenu">
                    <li>
                        <a href="invoices"><span class="fa fa-file-alt"></span>&nbsp;<?php echo $language['menu']['view_invoices']; ?></a>
                    </li>
                    <li>
                        <?php if($user->type > 1){ ?><a href="invoices/add"><span class="fa fa-file-signature"></span>&nbsp;<?php echo $language['menu']['create_invoices']; ?></a><?php } ?>
                    </li>
                </ul>
            </li>
            <?php if($user->type > 2){ ?>
            <li>
                <a href="#clientSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class="fa fa-users"></span>&nbsp;<?php echo $language['menu']['user']; ?></a>
                <ul class="collapse list-unstyled" id="clientSubmenu">
                    <li>
                        <a href="clients/view"><span class="fa fa-user"></span>&nbsp;<?php echo $language['menu']['view_users']; ?></a>              
                    </li>
                    <li>
                        <a href="clients/add"><span class="fa fa-user-plus"></span>&nbsp;<?php echo $language['menu']['create_users']; ?></a>
                    </li>
                </ul>
            </li>
            <?php }?>
            <?php if($user->type > 0 && $user->type != 2){ ?>
            <li>
                <a href="#productSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class="fa fa-shopping-cart"></span>&nbsp;<?php echo $language['menu']['product']; ?></a>
                <ul class="collapse list-unstyled" id="productSubmenu">
                    <li>
                        <a href="products"><?php echo $language['menu']['view_products']; ?></a>               
                    </li>
                    <li>
                        <a href="products/add"><?php echo $language['menu']['create_products']; ?></a>
                    </li>
                </ul>
            </li>
            <?php } ?>
            <li>
                <a href="reports">
                    <span class="fa fa-book"></span>&nbsp;<?php echo $language['menu']['report']; ?>
                </a>
            </li>
            <?php if($user->type >= 2){ ?>
            <li>
                <a href="payments">
                    <span class="fa fa-coins"></span>&nbsp;<?php echo $language['menu']['payment']; ?>
                </a>
            </li>
            <?php }?>
        </ul>
    </nav>

</div>
                