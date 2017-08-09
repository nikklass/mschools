			<?php if ($count >= 1) { //show record count if at least one record exists ?>
			 <?php
				 
				$pagestart = $offset + 1;
				$pageend = $offset + $newcnt;
				/*echo "<div id='pager'><div class='pagination_text round_3px'>";
				echo "Total: $count &nbsp;&nbsp;&nbsp; ";
				echo "Showing: ( $pagestart";
				echo " -  $pageend ) &nbsp;&nbsp;&nbsp;"; 
				echo "</div></div>"; */
			?>
			<?php } ?>
			
			
			<?php if ($count > $lperpage) { //show pagination only if recs are more than one page ?>
					
					
					<?php 
                        
                        echo "<ul class='pagination margin-none'>";
                        
                        echo $first;
                        echo $prev;
                        echo $linkshtml;
                        if (($totPages > $maxPages)&&(($totPages-$page)>=6)) { //show only if pages more than maxpages(11)  & less than last 7 pages
                            echo "<li class='disabled'><a href=''>...</a></li>";
                            echo "<li><a href='$nsitepath?page=".($totPages)."$addurl' title='Last'>$totPages</a></li>";
                        }
                        echo $next;
                        echo $last;

                        echo "</ul>";
                        
                    ?>
                    
			
			<?php } ?>