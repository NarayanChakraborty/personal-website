<?php include('header.php'); ?>
		
		<?php  include_once('config.php'); ?>
 <!-- Page Content -->
    <div class="container" style="margin-top:10px;">

        <div class="row">

		
		

            <!-- Blog Post Content Column -->
            <div class="col-lg-8">
            <?Php
			
			 /* ===================== Pagination Code Starts ================== */
			$adjacents = 7;
										
					
			
			
			$statement = $db->prepare("SELECT * FROM tbl_post ORDER BY post_id DESC");
			$statement->execute();
			$total_pages = $statement->rowCount();
							
			
			$targetpage = $_SERVER['PHP_SELF'];   //your file name  (the name of this file)
			$limit = 3;                                 //how many items to show per page
			$page = @$_GET['page'];
			if($page) 
				$start = ($page - 1) * $limit;          //first item to display on this page
			else
				$start = 0;
			
						
			$statement = $db->prepare("SELECT * FROM tbl_post ORDER BY post_id DESC LIMIT $start, $limit");
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			
			
			if ($page == 0) $page = 1;                  //if no page var is given, default to 1.
			$prev = $page - 1;                          //previous page is page - 1
			$next = $page + 1;                          //next page is page + 1
			$lastpage = ceil($total_pages/$limit);      //lastpage is = total pages / items per page, rounded up.
			$lpm1 = $lastpage - 1;   
			$pagination = "";
			if($lastpage > 1)
			{   
				$pagination .= "<div class=\"pagination\">";
				if ($page > 1) 
					$pagination.= "<a href=\"$targetpage?page=$prev\">&#171; back</a>";
				else
					$pagination.= "<span class=\"disabled\">&#171;back</span>";    
				if ($lastpage < 7 + ($adjacents * 2))   //not enough pages to bother breaking it up
				{   
					for ($counter = 1; $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
					}
				}
				elseif($lastpage > 5 + ($adjacents * 2))    //enough pages to hide some
				{
					if($page < 1 + ($adjacents * 2))        
					{
						for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"current\">$counter</span>";
							else
								$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
						}
						$pagination.= "...";
						$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
						$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";       
					}
					elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
					{
						$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
						$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
						$pagination.= "...";
						for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"current\">$counter</span>";
							else
								$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
						}
						$pagination.= "...";
						$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
						$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";       
					}
					else
					{
						$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
						$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
						$pagination.= "...";
						for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"current\">$counter</span>";
							else
								$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
						}
					}
				}
				if ($page < $counter - 1) 
					$pagination.= "<a href=\"$targetpage?page=$next\">next &#187;</a>";
				else
					$pagination.= "<span class=\"disabled\">next &#187;</span>";
				$pagination.= "</div>\n";       
			}
			/* ===================== Pagination Code Ends ================== */	
			 
			 
			 /* 
			 $statement=$db->prepare("select * from tbl_post order by post_id desc");
			 $statement->execute(array());
			 $result=$statement->fetchAll(PDO::FETCH_ASSOC);*/
			 
			 foreach($result as $row)
				{
			 ?>
			
			
			
                <!-- Blog Post -->
                <div class="post">
                <!-- Title -->
                <h3><?php echo $row['post_title'];?></h3>
                <!-- Date/Time -->
                <p style="margin-top:5px;"><span class="glyphicon glyphicon-time" ></span> Posted on 
				<?php
				$post_date=$row['post_date'];
				$post_day=substr($post_date,8,2);
				$post_month=substr($post_date,5,2);
				$post_year=substr($post_date,0,4);
				
				if($post_month=='01') {$post_month='Jan';}
				if($post_month=='02') {$post_month='Feb';}
			    if($post_month=='03') {$post_month='Mar';}
				if($post_month=='04') {$post_month='Apr';}
				if($post_month=='05') {$post_month='May';}
				if($post_month=='06') {$post_month='Jun';}
				if($post_month=='07') {$post_month='JUL';}
				if($post_month=='08') {$post_month='Aug';}
				if($post_month=='09') {$post_month='Sep';}
				if($post_month=='10') {$post_month='Oct';}
				if($post_month=='11') {$post_month='Nov';}
				if($post_month=='12') {$post_month='Dec';}
				
				echo $post_day." ".$post_month.", ".$post_year; 	
			?><span class="categories">&nbsp; Tags : <em>
				<?php
								//when retrive data (using explode)
								$arr=explode(',',$row['tag_id']);
								$arr_count=count(explode(',',$row['tag_id']));
								$k=0;
								$arr1=array();
								for($j=0;$j<$arr_count;$j++)
								{
									 $statement1=$db->prepare("select * from tbl_tag where tag_id=?");
									 $statement1->execute(array($arr[$j]));
									 $result1=$statement1->fetchAll(PDO::FETCH_ASSOC);
									 foreach($result1 as $row1)
									 {
										$arr1[$k]=$row1['tag_name'];  
									 }
								   $k++;
                                }
								$tag_names=implode(",",$arr1);
								echo $tag_names;
                              ?>	
</em>							  
				
				</span>
				</p>

                <hr>
                
				
                <!-- Preview Image -->
                <img  src="admin/uploads/<?php echo $row['post_image'];?>" class="img-responsive"  alt="" width="200px" height="160px" style="float:left;padding-right:12px">
                 
				  <div class="a">
           				<p >
						<?php
						$pices=explode(" ",$row['post_description']);
						$first_page=implode(" ",array_splice($pices,0,90));
						$first_page=$first_page."<b> ..........</b>";
						?>
						<?php
						     echo $first_page;
						?>
						</p>
                 </div>
                <hr>
				<p class="comments"><a href="blog2.php?id=<?php echo $row['post_id']; ?>">Comments 
				<?php
				   $statement1=$db->prepare("select * from tbl_comment where post_id=? and action=1");
                   $statement1->execute(array($row['post_id']));
                   $totalnum=$statement1->rowCount();
                   echo "(".$totalnum.")";				   
				?></a>
				

				<span>|</span>   <a href="blog2.php?id=<?php echo $row['post_id']; ?>">Continue Reading</a></p><br><br>
				
				</div>
				<!--- Blog Post--->           
			<?php
				}
				?>

			
			<div class="pagination">			  
<?php echo $pagination;?>	
</div>
			
			
			
			
            </div>


			
			
            <!-- Blog Sidebar Widgets Column -->
			
               <?php include('blog_sidebar.php'); ?>

        </div>
        <!-- /.row -->
</div>
		
<?php include('footer.php'); ?>			
			