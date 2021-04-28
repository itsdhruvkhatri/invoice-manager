<div class="top-nav">
    <div class="container-fluid">
        <div class="row" style="background-color: whitesmoke;">
            <div class="col">                
                <ul>
                    <li>
                        <button type="button" id="sidebarCollapse" class="btn">
                            <i class="fas fa-align-left"></i>
                        </button>
                    </li>    
                    
                    <div class="icons">                   
                        <li class="dropdown">
                            <button type="button" class="btn" id="navbardrop" data-toggle="dropdown">
                                <i class="fa fa-bell fa-lg"></i>
                                <small><span class="badge badge-pill badge-danger"><?php echo $notifications->count_notif(); ?></span></small>
                            </button>
                            <div class="dropdown-menu">
                                <?php $notifications->display();?>                 
                            </div>
                        </li>                       
                        <?php if($user->type > 3){ ?>
                        <li>
                            <a href="settings">
                                <button  type="button"class="btn" >
                                    <span class="fa fa-cog fa-lg"></span>
                                </button>
                            </a>
                        </li>
                        <?php }?>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</div>