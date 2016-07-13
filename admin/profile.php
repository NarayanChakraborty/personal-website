
<?php
ob_start();
session_start();
if($_SESSION['name']!='snchousebd')
{
header('location: login.php');
}
include('../config.php');
?>


<?php

if(isset($_POST['form1']))
{
	try{  
		
		   
		   
		   //pdo to insert all above informations.. to tbl_post
		   $statement=$db->prepare("update  tbl_welcome set description=? where id=?");
		   $statement->execute(array($_POST['post_description'],1));
		   
		   $success_message="Post is inserted succesfully";
	}
	
	catch(Exception $e)
	{
		$error_message=$e->getMessage();
	}
}
?>

<?php include_once('header.php'); ?>

<?php include_once('sidebar.php'); ?>


      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
		  <div class="row">
				<div class="col-lg-12">
					
					<ol class="breadcrumb">
						<li><i class="fa fa-home"></i><a href="index.html">Home</a></li>
						<li><i class="fa fa-bars"></i>Pages</li>
						<li><i class="fa fa-square-o"></i>Pages</li>
					</ol>
				</div>
			</div>
              <!-- page start-->



<div style="margin-left:30px;">
                                  <!-- edit-profile -->
                                  <div id="edit-profile" class="tab-pane">
                                    <section class="panel">                                          
                                          <div class="panel-body bio-graph-info">
                                              <h1> Profile Info</h1>
                                              <form class="form-horizontal" role="form">                                                  
                                                  <div class="form-group">
                                                      <label class="col-lg-2 control-label">First Name</label>
                                                      <div class="col-lg-4">
                                                          <input type="text" class="form-control" id="f-name" placeholder=" ">
                                                      </div>
                                                  </div>
                                                  <div class="form-group">
                                                      <label class="col-lg-2 control-label">Last Name</label>
                                                      <div class="col-lg-4">
                                                          <input type="text" class="form-control" id="l-name" placeholder=" ">
                                                      </div>
                                                  </div>
                                                  <div class="form-group">
                                                      <label class="col-lg-2 control-label">Present Status</label>
                                                      <div class="col-lg-8">
                                                          <textarea name="" id="" class="form-control" cols="30" rows="5"></textarea>
                                                      </div>
                                                  </div>
                                           <div class="form-group">
                                                      <div class="col-lg-offset-2 col-lg-10">
                                                          <button type="submit" class="btn btn-primary">Update</button>
                                                        
                                                      </div>
                                                  </div>
                                              </form>
                                          </div>
                                      </section>
                                  </div>
								  
								  
								        <section class="panel">                                          
                                          <div class="panel-body bio-graph-info">
                                              <h1>Add New Achievement</h1>
                                              <form class="form-horizontal" role="form">                                                  
                                                  <div class="form-group">
                                                      <label class="col-lg-2 control-label">Achievement Title</label>
                                                      <div class="col-lg-6">
                                                          <input type="text" name="title"  class="form-control" id="f-name" placeholder=" ">
                                                      </div>
                                                  </div>
                                              
                                                  <div class="form-group">
                                                      <label class="col-lg-2 control-label">Description</label>
                                                      <div class="col-lg-8">
                                                          <textarea name="" id="" name="details" class="form-control" cols="30" rows="5"></textarea>
                                                      </div>
                                                  </div>
                                           <div class="form-group">
                                                      <div class="col-lg-offset-2 col-lg-10">
                                                          <button type="submit" name="form2" class="btn btn-primary">Save</button>
                                                        
                                                      </div>
                                                  </div>
                                              </form>
                                          </div>
                                      </section>
<h2>View All Achievements</h2>  
<table class="tabl2" width="100%">
<tr>
    <th width="5%">Serial</th>
	<th width="75%">Title</th>
	<th width="20%">Action</th>
</tr>

<!-------SQL with PDO to fetch all category----->
		<?php	
					 if(isset($error_message1)){
                        ?>
                        <div class="alert alert-block alert-danger fade in">
                          <button data-dismiss="alert" class="close close-sm" type="button">
                          <i class="icon-remove"> X</i>
                          </button>
                          <strong>Opps!&nbsp; </strong><?php echo $error_message1;?>
                       </div>
                        <?php
                      }
                      if (isset($success_message1)) {
                       ?>
                       <div class="alert alert-success fade in">
                          <button data-dismiss="alert" class="close close-sm" type="button">
                            <i class="icon-remove"> X</i>
                          </button>
                          <strong>Well done!&nbsp; </strong><?php echo $success_message1;?>
                       </div>
                       <?php
                        }
                      ?>
					 
					 <?php
                      if(isset($error_message2)){
                        ?>
                        <div class="alert alert-block alert-danger fade in">
                          <button data-dismiss="alert" class="close close-sm" type="button">
                          <i class="icon-remove"> X</i>
                          </button>
                          <strong>Opps!&nbsp; </strong><?php echo $error_message2;?>
                       </div>
                        <?php
                      }
                      if (isset($success_message2)) {
                       ?>
                       <div class="alert alert-success fade in">
                          <button data-dismiss="alert" class="close close-sm" type="button">
                            <i class="icon-remove">X</i>
                          </button>
                          <strong>Well done!&nbsp; </strong><?php echo $success_message2;?>
                       </div>
                       <?php
                        }
                      ?>					 
						
		
		
		
		
<?php
$i=0;
$statement=$db->prepare("select * from tbl_tag order by tag_name asc");
$statement->execute();
$result=$statement->fetchAll(PDO::FETCH_ASSOC); 
if($result==null)
{
	 echo "No Entry"; 
	
}
foreach($result as $row)
{

 $i++;
 ?>
<tr>
    <td><?php echo $i; ?></td>
    <td>

	<?php echo $row['tag_name'];?></td>
    <td><a class="btn btn-info " data-toggle="modal" href="#myModal<?php echo $row['tag_id'];?>">
                                Edit
                              </a>
							 
								<!---- For Edit------->
							  
	                  <!-- Modal -->
							  <div class="modal fade" id="myModal<?php echo $row['tag_id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
								  <div class="modal-content">
									<div class="modal-header">
									  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									  <h4 class="modal-title">Edit This Tag Name</h4>
									</div>
									<div class="modal-body">
									  <h4>Tag Name :</h4>
									  <form method="post" action="" enctype="multipart/form-data">
										<input type="text"value="<?php echo $row['tag_name'];?>"class="form-control" name="edit_tag_name"><br>
										<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
										<input type="hidden" name="hidden_id_for_edit_tag" value="<?php echo $row['tag_id'];?>">
										<input type="submit" value="Update" class="btn btn-success" name="form_edit_tag">
									  </form>
									</div>         
								  </div>
								</div>
							  </div>
							  <!-- modal -->
							  <!---- For Edit------->
							  
	&nbsp;|&nbsp;
	
	  <a class="btn btn-danger " data-toggle="modal"
							  data-target="#MyModal<?php echo $row['tag_id'];?>"  >
                                  Delete!
                              </a>
	</td>
	
	
	 <!-------------FOR Delete-------------->
							  
							  <!-- Modal -->
								<div id="MyModal<?php echo $row['tag_id'];?>" class="modal fade " role="dialog">
								  <div class="modal-dialog">

									<!-- Modal content-->
									<div class="modal-content">
									  <div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">DELETE Confirmation</h4>
									  </div>
									  <div class="modal-body">
										<h4>Are You Confirm To Delete This Element?</h4>
									  </div>
									  <div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<a class="btn btn-danger btn-ok" href="delete_tag.php?ID=<?php echo $row['tag_id'];?>" >Confirm</a>
									  </div>
									</div>

								  </div>
								</div>
															  
															  
							  <!-------------FOR Delete-------------->
	
	
	
</tr>  

<?php	
}
?>
</table>	
<?php include("footer.php"); ?>   