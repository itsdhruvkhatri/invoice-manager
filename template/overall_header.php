<!DOCTYPE html>
<html>
    <?php
    include 'includes/head.php';
    ?>
    <body>
        <main <?php if((!empty($_GET['page']) && $_GET['page'] == 'index') || empty($_GET['page'])){ echo 'class="frontpng"'; } ?>>
            <?php if(!empty($_GET['page']) && $_GET['page'] != 'index' && User::logged_in()){
                ?>
            <div id="loading"></div>
            <?php
                include 'template/includes/widgets/topnav.php'; ?>
            <div class="container-fluid">
                <div class="row">
                    <?php include 'template/includes/widgets/left_sidebar.php'; ?>
                    <div class="col">
                        <?php disp_notifications(); ?>
                        <br>
                        <?php } ?>