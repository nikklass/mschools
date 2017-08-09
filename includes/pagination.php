<?php
if ($totPages < 11) { 
	for ($o=1; $o<=ceil($count/$lperpage); $o++) { 
				if ($page == $o) $linkshtml .= "<li class='active'><a href='#' title='Current page'>$o</a></li>";  
		 		else $linkshtml .= "<li><a href='$nsitepath?page=".($o)."$addurl'>$o</a></li>";
		} 
	} 
	else {  
	
		// First 11 pages, if current page is less than 5 pages in 
		if (($page - 5) < 1) 
		{ 
			for ($o=1; $o<=11; $o++) { 
				if ($page == $o) $linkshtml .= "<li class='active'><a href='#' title='Current page'>$o</a></li>"; 
				else $linkshtml .= "<li><a href='$nsitepath?page=".($o)."$addurl'>$o</a></li>";
			} 
		} else if (($page + 5) > $totPages)	{ 
		// Output last 11 pages 
			for ($o= ($totPages - 11); $o<=$totPages; $o++) { 
				if ($page == $o) $linkshtml .= "<li class='active'><a href='#' title='Current page'>$o</a></li>";  
		 		else $linkshtml .= "<li><a href='$nsitepath?page=".($o)."$addurl'>$o</a></li>";
			} 
		} else { 
		// Output pages 5 below, and 5 above 
			for ($o= ($page - 5); $o<=($page + 5); $o++) { 
				if ($page == $o) $linkshtml .= "<li class='active'><a href='#' title='Current page'>$o</a></li>"; 
		 		else $linkshtml .= "<li><a href='$nsitepath?page=".($o)."$addurl'>$o</a></li>";
			} 
		}
	}
	
	//set next and prev links

	$maxPage = ceil($count/$lperpage);
	
	if ($newcnt > 0) { //are there records to show????
	
		if ($page > 1)
		{
			$prevpage = $page - 1;
			$prev = "<li><a href='$nsitepath?page=$prevpage".$addurl."' title='Previous'>&lsaquo;</a></li>";
			$first = "<li><a href='$nsitepath?page=1' title='First'>&laquo;</a></li>";
		}
		else
		{
			// we're on page one, don't enable 'previous' link
			$prev = "<li class='disabled'><a href='#' title='Previous'>&lsaquo;</a></li>";
			$first = "<li class='disabled'><a href='#' title='First'>&laquo;</a></li>";

		}
					
						
		if ($page < $maxPage)
		{
			$nxtpage = $page + 1;
			$next = "<li><a href='$nsitepath?page=$nxtpage".$addurl."' title='Next'>&rsaquo;</a></li>";
			$last = "<li><a href='$nsitepath?page=$maxPage".$addurl."' title='Last'>&raquo;</a></li>";			
		}
		else
		{
			// we're on the last page, don't enable 'next' link	
			$next = "<li class='disabled'><a href='#' title='Next'>&rsaquo;</a></li>";
			$last = "<li class='disabled'><a href='#' title='Last'>&raquo;</a></li>";
		}
	
	} //end if newcnt
	
	
?>
