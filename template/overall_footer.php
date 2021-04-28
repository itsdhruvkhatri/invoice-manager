 <?php if(!empty($_GET['page']) && $_GET['page'] != 'index' && User::logged_in()){ ?>
        </div>
         <?php include 'template/includes/widgets/right_sidebar.php'; ?>
    </div>
</div>
 <?php } ?>
</main>
<footer>
    <?php include 'includes/footer.php'; ?>
</footer>
</body>
</html>
<?php
include 'core/deinit.php';
?>