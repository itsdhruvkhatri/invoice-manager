<?php User::check_permission(0); 
if($user->type >= 2){
?>
      
    <div class="card">
        <div class="card-body">
            <div class="row">
                
                <div class="col">
                    <div class="card bg-gray">
                        <div class="card-body panel-gray">
                            <div class="row">
                                <div class="col" style="text-align: center;">                                   
                                    <div class="rounded-circle" >
                                        <br>
                                        <br>
                                        <span class="fas fa-wallet fa-2x"></span>
                                    </div>
                                </div> 
                                <div class="col">
                                    <br>
                                    <h6 class="text-secondary"><b><?php echo $language['misc']['total_income']; ?></b></h6>
                                    <h3><?php echo $currency_pfx . ' ' . total_revenue(); ?></h3>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col">
                    <div class="card bg-gray">
                        <div class="card-body panel-gray">
                            <div class="row">
                                <div class="col" style="text-align: center;">
                                    <br>
                                    <br>
                                    <span class="fas fa-wallet fa-2x"></span>
                                </div> 
                                
                                <div class="col">
                                    <br>
                                    <h6 class="text-secondary"><b><?php echo $language['misc']['monthly_income'] ?></b></h6>
                                    <h3><?php echo $currency_pfx . ' ' . monthly_revenue(); ?></h3>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            
            <div class="row">
                
                <div class="col-sm-12">
                    <div class="card bg-gray">
                        <div class="card-body panel-gray">
                            <h6 class="text-secondary text-center"><b><?php echo $language['misc']['invoice_stats']; ?></b></h6>
                            <br>
                            <div class="progress">
                                <div class="progress-bar bg-danger" id="progress" role="progressbar" style="width: <?php echo overdue_perc(); ?>%" aria-valuenow="<?php echo overdue_perc(); ?>" aria-valuemin="0" aria-valuemax="100"><span class="popOver" data-toggle="tooltip" data-placement="top" title="<?php echo $language['status']['over_due'] . ': ' . overdue_perc(); ?>%" class="tooltipz float-right"></span></div>
                                <div class="progress-bar bg-success" id="progress" role="progressbar" style="width: <?php echo paid_perc(); ?>%" aria-valuenow="<?php echo paid_perc(); ?>" aria-valuemin="0" aria-valuemax="100"><span class="popOver" data-toggle="tooltip" data-placement="top" title="<?php echo $language['status']['paid'] . ': ' . paid_perc(); ?>%" class="tooltipz float-right"></span></div>
                                <div class="progress-bar bg-secondary" id="progress" role="progressbar" style="width: <?php echo unpaid_perc(); ?>%" aria-valuenow="<?php echo unpaid_perc(); ?>" aria-valuemin="0" aria-valuemax="100"><span class="popOver" data-toggle="tooltip" data-placement="top" title="<?php echo $language['status']['unpaid'] . ': ' . unpaid_perc(); ?>%" class="tooltipz float-right"></span></div>
                                <div class="progress-bar bg-warning" id="progress" role="progressbar" style="width: <?php echo cancelled_perc(); ?>%" aria-valuenow="<?php echo cancelled_perc(); ?>" aria-valuemin="0" aria-valuemax="100"><span class="popOver" data-toggle="tooltip" data-placement="top" title="<?php echo $language['status']['cancelled'] . ': ' . cancelled_perc(); ?>%" class="tooltipz float-right"></span></div>
                              </div>
                            <br>
                            <br>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-12">
                    <br>
                    <div class="row">
                        
                        <div class="col">
                            <div class="card bg-gray">
                                <div class="card-body panel-gray">
                                    <div class="row">
                                        <div class="col" style="text-align: center;">
                                            <br>
                                            <br>
                                            <span class="fas fa-user fa-2x"></span>
                                        </div> 

                                        <div class="col">
                                            <br>
                                            <h6 class="text-secondary"><b><?php echo $language['misc']['total_clients'] ?></b></h6>
                                            <h3><?php echo total_clients(); ?></h3>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="card bg-gray">
                                <div class="card-body panel-gray">
                                    <div class="row">
                                        <div class="col" style="text-align: center;">
                                            <br>
                                            <br>
                                            <span class="fas fa-file-alt fa-2x"></span>
                                        </div> 

                                        <div class="col">
                                            <br>
                                            <h6 class="text-secondary"><b><?php echo $language['misc']['total_invoices'] ?></b></h6>
                                            <h3><?php echo total_invoices(); ?></h3>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col">
                            <br class="mobile-only">
                            <div class="card bg-gray">
                                <div class="card-body panel-gray">
                                    <div class="row">
                                        <div class="col" style="text-align: center;">
                                            <br>
                                            <br>
                                            <span class="fas fa-book fa-2x"></span>
                                        </div> 

                                        <div class="col">
                                            <br>
                                            <h6 class="text-secondary"><b><?php echo $language['misc']['total_reports'] ?></b></h6>
                                            <h3><?php echo total_reports(); ?></h3>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                                
                    </div>
                </div>
            </div>
            
            
            <br>
<?php } ?>
            <div class="card">
                <div class="card-body">
                    <?php invoice_show(); ?>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-body">
                    <?php payments_show(); ?>
                </div>
            </div>
            

            <div id="chartContainer" style="height: 370px; width: 100%;"></div>

        </div>
    </div>


<script>
    $(function() { 
  $('[data-toggle="tooltip"]').tooltip({
    trigger: 'manual'
  }).tooltip('show');

  });

$(window).scroll(function() {
  // if($( window ).scrollTop() > 10){   scroll down abit and get the action   
  $(".progress-bar").each(function() {
    each_bar_width = $(this).attr('aria-valuenow');
    $(this).width(each_bar_width + '%');
  });

  //  }  
});
    </script>