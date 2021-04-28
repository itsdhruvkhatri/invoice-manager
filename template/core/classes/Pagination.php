<?php

class Pagination {
	public 	$per_page,
			$current_page,
			$total_pages,
			$limit,
			$current_page_link,
			$link,
                        $stats_results;

	public function __construct($per_page) {
		global $database;
                global $user;

		/* Initiate the $per_page variable */
		$this->per_page = $per_page;
                
                /* Check if there is any result */
                if(!empty($_GET['page'])){
                    if ($_GET['page'] == "reports"){
                        if ($user->type >= 3){
                            $result = $database->query("SELECT * FROM `reports`");
                            $this->stats_results = $result->num_rows;
                        }
                        if ($user->type < 3){
                            $result = $database->query("SELECT * FROM `reports` WHERE user_id = {$user->user_id}");
                            $this->stats_results = $result->num_rows;
                        }
                    }
                    if ($_GET['page'] == "clients"){
                        $result = $database->query("SELECT * FROM `users`");
                        $this->stats_results = $result->num_rows;
                    }
                    if ($_GET['page'] == "payments"){
                        $result = $database->query("SELECT * FROM `payments`");
                        $this->stats_results = $result->num_rows;
                    }
                    if ($_GET['page'] == "products"){
                        $result = $database->query("SELECT * FROM `products`");
                        $this->stats_results = $result->num_rows;
                    }
                    if ($_GET['page'] == "invoices"){
                        if ($user->type >= 3){
                            $result = $database->query("SELECT * FROM `invoices`");
                            $this->stats_results = $result->num_rows;
                        }
                        if ($user->type < 3){
                            $result = $database->query("SELECT * FROM `invoices` WHERE user_id = {$user->user_id}");
                            $this->stats_results = $result->num_rows;
                        }
                    }
                }

		/* Get the total servers count */
                $total_stats = 0;
                if(!empty($_GET['page'])){
                    if ($_GET['page'] == "reports"){
                                                                  
                        if ($user->type >= 3){                      
                            $stmt = $database->prepare("SELECT COUNT(*) FROM `reports`");
                            if(!empty($stmt)){
                                $stmt->execute();
                                $stmt->bind_result($total_stats);
                                $stmt->fetch();
                                $stmt->close();
                            }
                        }
                        if ($user->type < 3){                      
                            $stmt = $database->prepare("SELECT COUNT(*) FROM `reports` WHERE `user_id` = {$user->user_id}");
                            if(!empty($stmt)){
                                $stmt->execute();
                                $stmt->bind_result($total_stats);
                                $stmt->fetch();
                                $stmt->close();
                            }
                        }
                    }
                    if ($_GET['page'] == "clients"){
                                              
                        $stmt = $database->prepare("SELECT COUNT(*) FROM `users`");
                        if(!empty($stmt)){
                            $stmt->execute();
                            $stmt->bind_result($total_stats);
                            $stmt->fetch();
                            $stmt->close();
                        }
                    }
                    if ($_GET['page'] == "payments"){
                                              
                        $stmt = $database->prepare("SELECT COUNT(*) FROM `payments`");
                        if(!empty($stmt)){
                            $stmt->execute();
                            $stmt->bind_result($total_stats);
                            $stmt->fetch();
                            $stmt->close();
                        }
                    }
                    if ($_GET['page'] == "products"){
                                              
                        $stmt = $database->prepare("SELECT COUNT(*) FROM `products`");
                        if(!empty($stmt)){
                            $stmt->execute();
                            $stmt->bind_result($total_stats);
                            $stmt->fetch();
                            $stmt->close();
                        }
                    }
                    if ($_GET['page'] == "invoices"){
                        if ($user->type <= 3){                      
                            $stmt = $database->prepare("SELECT COUNT(*) FROM `invoices` WHERE `user_id` = {$user->user_id}");
                            if(!empty($stmt)){
                                $stmt->execute();
                                $stmt->bind_result($total_stats);
                                $stmt->fetch();
                                $stmt->close();
                            }
                        }
                        if ($user->type > 3){                      
                            $stmt = $database->prepare("SELECT COUNT(*) FROM `invoices`");
                            if(!empty($stmt)){
                                $stmt->execute();
                                $stmt->bind_result($total_stats);
                                $stmt->fetch();
                                $stmt->close();
                            }
                        }
                    }
                }

		/* Determine the number of total pages */
		$this->total_pages = ceil($total_stats/$this->per_page);

		/* Determine the current page and check for errors */
		$this->current_page = (isset($_GET['current_page'])) ? (int)$_GET['current_page'] : 1;

		/* Check if the current page number is less than 1 or higher than the $total_pages */
		$this->current_page = ($this->current_page < 1 || $this->current_page > $this->total_pages) ? 1 : $this->current_page;

		/* Generate the limit query */
		$this->limit = "LIMIT " . ($this->current_page - 1) * $this->per_page . "," . $this->per_page;
                
                
		
	}

	public function set_current_page_link($current_page_link) {

		/* Initiate the $current_page_link variable */
		$this->current_page_link = $current_page_link;

		/* Generate the $link without any affix */
		$this->link = $this->current_page_link . '/' . $this->current_page;

	}

	public function display($affix = null, $aside = 5) {

		/* Create the next and the previous variables */
		$previous = $this->current_page - 1;
		$next = $this->current_page + 1;

                $pagination = '<nav aria-label="Page navigation example">';
		/* Start generating the links */
		$pagination .= '<ul class="pagination">';

		/* Previous button */
		$pagination .= ($this->current_page != 1) ? '<li class="page-item"><a class="page-link" href="' . $this->current_page_link . '/' . $previous . $affix . '">&laquo;</a></li>' : '<li class="disabled page-item"><a class="page-link" href="' . $this->current_page_link . '/' . $this->current_page . '">&laquo;</a></li>';
		
		/* Previous X buttons */
		for($i = $this->current_page - $aside; $i < $this->current_page; $i++) {
			if($i > 0) {
				$pagination .= '<li class="page-item"><a class="page-link" href="' . $this->current_page_link . '/' . $i . $affix . '">' . $i . '</a></li>';
			}
		}

		/* Current Page */
		$pagination .= '<li class="active page-item"><a class="page-link" href="' . $this->current_page_link . '/' . $this->current_page . $affix . '">' . $this->current_page . '</a></li>';

		/* Next X buttons */
		for($i = $this->current_page + 1; $i <= $this->total_pages; $i++) {
			$pagination .= '<li class="page-item"><a class="page-link" href="' . $this->current_page_link . '/' . $i . $affix . '">' . $i . '</a></li>';
			
			if($i >= $this->current_page + $aside) break;
		}

		/* Next button */
		$pagination .= ($this->current_page != $this->total_pages) ? '<li class="page-item"><a class="page-link" href="' . $this->current_page_link . '/' . $next . $affix . '">&raquo;</a></li>' : '<li class="disabled page-item"><a class="page-link" href="' . $this->current_page_link . '/' . $this->current_page . '">&raquo;</a></li>';

			
		$pagination .= '</ul>';
                
                $pagination .= '</nav>';

		echo $pagination;
	}
        
        public function display_pagination($current_page) {
                $this->affix = '';
                
		/* If there are results, display pagination */
		if($this->stats_results > 0) {

			/* Establish the current page link */
			self::set_current_page_link($current_page);

			echo '
			<div class="panel panel-default">
				<div class="panel-body">';
					
					self::display($this->affix);

				echo '
				</div>
			</div>';
		}
	}

}

?>