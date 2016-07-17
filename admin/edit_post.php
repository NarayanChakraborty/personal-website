
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
if(!isset($_REQUEST['id']))
{
	header('location:viewpost.php');
}
else
{
	$id=$_REQUEST['id'];
}
?>
<?php

if(isset($_POST['form1']))
{
	try{  
		if(empty($_POST['post_title']))
		{
			throw new Exception("Title can not be empty");
		}
		if(empty($_POST['post_description']))
		{
			throw new Exception("Title can not be empty");
		}
	    if(empty($_POST['cat_id']))
		{
			throw new Exception("Category can not be empty");
		}
		if(empty($_POST['tag_id']))
		{
			throw new Exception("Tag Name can not be empty");
		}
		
		         //Multiple tag... access
		   $tag_id=$_POST['tag_id'];
		   $i=0;
		   
		    if(is_array($tag_id))
			{
			   foreach($tag_id as $key=>$val)
			   {
				   $arr[$i]=$val;
				   $i++;			   
			   }
		   }
		   
		   //implode
		   $tag_ids=implode(",",$arr);
		   
 	    
		  if (
        !isset($_FILES['post_image']['error']) ||
        is_array($_FILES['post_image']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }
		
		
	 //IMAGE MANAGE
        //if no new image is selected  
		if(empty($_FILES['post_image']['name']))
        {
			 //pdo to insert all above informations.. to tbl_post
		   $statement=$db->prepare("update  tbl_post set post_title=?,post_description=?,cat_id=?,tag_id=? where post_id=?");
		   $statement->execute(array($_POST['post_title'],$_POST['post_description'],$_POST['cat_id'],$tag_ids,$id));
		}	
         else
         {
		  if($_FILES['post_image']['size']>1000000)
			      {	   
				throw new Exception("<div class='error'>Sorry,your file is too large</div>"); //image file must be<1MB
												 
			     }
			   
				//access image process one;   
				$up_filename=$_FILES['post_image']['name'];   //file_name
				$file_basename=substr($up_filename,0,strripos($up_filename,'.'));//orginal image name
				$file_ext=substr($up_filename,strripos($up_filename,'.')); //extension
				$f1=$id.$file_ext;  //Rename filename;

				
				//only allow png ,jpg,jpeg,gif
				if(($file_ext!='.png')&&($file_ext!='.jpg')&&($file_ext!='.jpeg')&&($file_ext!='.gif')&&($file_ext!='.PNG')&&($file_ext!='.JPG')&&($file_ext!='.JPEG')&&($file_ext!='.GIF'))
				{
					throw new Exception("only jpg,jpeg,png and gif format are allowed");
				}
				 
				
						$target_folder = 'uploads/'; 
    $upload_image = $target_folder.$f1;
				
				
				//To unlink previous image
				
				
                        $statement1=$db->prepare("select * from tbl_post where post_id=?");
						$statement1->execute(array($id));
						$result1=$statement1->fetchAll(PDO::FETCH_ASSOC);
						foreach($result1 as $row1)
						{
							$real_path= $target_folder.$row1['post_image'];
						    unlink($real_path);
						}
				      //upload image to a folder
		 if(!move_uploaded_file($_FILES['post_image']['tmp_name'],$upload_image)) 
    {
      throw new Exception('image is not uploaded');
    }
				 $statement=$db->prepare("update  tbl_post set post_title=?,post_description=?,post_image=?,cat_id=?,tag_id=? where post_id=?");
		   $statement->execute(array($_POST['post_title'],$_POST['post_description'],$f1,$_POST['cat_id'],$tag_ids,$id));
				
						 
		 }			 


		   $success_message="Post is updated succesfully";
	
	
	
	}
	catch(Exception $e)
	{
		$error_message=$e->getMessage();
	}
}
?>

<?php	
	$statement=$db->prepare("select * from tbl_post where post_id=?");
	$statement->execute(array($id));
	$result=$statement->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $row)
	{
		$post_title=$row['post_title'];
		$post_description=$row['post_description'];
		$post_image=$row['post_image'];
		$cat_id=$row['cat_id'];
		$tag_id=$row['tag_id'];
	}
?>


<?php include_once('header.php'); ?>

<?php include_once('sidebar.php'); ?>


      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
		  <div class="row">
				<div class="col-lg-12">
					<h3 class="page-header"><i class="fa fa fa-bars"></i> Pages</h3>
					<ol class="breadcrumb">
						<li><i class="fa fa-home"></i><a href="index.php">Home</a></li>
						<li><i class="fa fa-square-o"></i>Pages</li>
					</ol>
				</div>
			</div>
              <!-- page start-->



<div style="margin-left:30px;">
<h2>Add new Post</h2>
                   <?php
                      if(isset($error_message)){
                        ?>
                        <div class="alert alert-block alert-danger fade in">
                          <button data-dismiss="alert" class="close close-sm" type="button">
                          <i class="icon-remove">x</i>
                          </button>
                          <strong>Opps!&nbsp; </strong><?php echo $error_message;?>
                       </div>
                        <?php
                      }
                      if (isset($success_message)) {
                       ?>
                        <div class="alert alert-block alert-success fade in">
                          <button data-dismiss="alert" class="close close-sm" type="button">
                          <i class="icon-remove">x</i>
                          </button>
                          <strong>Well done!&nbsp; </strong><?php echo $success_message;?>
                       </div>
                       <?php
                        }
                      ?>
<form action="" method="POST" enctype="multipart/form-data">
<table class="tabl">
    <tr><td><b>Title<b></td></tr>
	<tr><td><input class="long" type="text"name="post_title"value="<?php echo $post_title;?>"></td></tr>
	<tr><td><b>Description</b></td></tr>
	<tr>
		<td>
			<textarea name="post_description" cols="100" rows="10">
			<?php echo $post_description; ?>
			</textarea>
				<script type="text/javascript">
				if ( typeof CKEDITOR == 'undefined' )
				{
					document.write(
						'<strong><span style="color: #ff0000">Error</span>: CKEditor not found</strong>.' +
						'This sample assumes that CKEditor (not included with CKFinder) is installed in' +
						'the "/ckeditor/" path. If you have it installed in a different place, just edit' +
						'this file, changing the wrong paths in the &lt;head&gt; (line 5) and the "BasePath"' +
						'value (line 32).' ) ;
				}
				else
				{
					var editor = CKEDITOR.replace( 'post_description' );
					//editor.setData( '<p>Just click the <b>Image</b> or <b>Link</b> button, and then <b>&quot;Browse Server&quot;</b>.</p>' );
				}

			</script>
		</td>
	</tr>
	<tr><td>Previous FeaturedImage Preview</td></tr>
	<tr><td><img src="uploads/<?php echo $post_image; ?>" alt="" width="300px" height="250px"></td></tr>
	<tr><td>New FeaturedImage</td></tr>
	<tr><td><input type="file" name="post_image"></td></tr>
	<tr><td><b>Select a Category</b></td></tr>
	<tr>
		<td>
			<select name="cat_id">
			     <option value="">Select a Category</option>
               	
                 <?php
				 $statement=$db->prepare("select * from tbl_category order by cat_name asc");
				 $statement->execute();
				 $result=$statement->fetchAll(PDO::FETCH_ASSOC);
				 foreach($result as $row)
				 {
					 if($row['cat_id']==$cat_id)
					 {
						?><option value="<?php echo $row['cat_id'];?>" selected><?php echo $row['cat_name'];?></option><?php	 
					 }
					 else
					 {
						 ?><option value="<?php echo $row['cat_id'];?>"><?php echo $row['cat_name'];?></option><?php	
					 }
				 }
				 
				 ?>
			</select>
		</td>
	</tr>	
	<tr><td><b>Select a Tag</b></td></tr>
	<tr>
	     <td>
			  
                <?php
                $statement=$db->prepare("select * from tbl_tag order by tag_name asc");				
		        $statement->execute();
				$result=$statement->fetchAll(PDO::FETCH_ASSOC);
				foreach($result as $row)
				{
				 $j=0;	
				 $tagarr=explode(",",$tag_id);
				 $tagcount=count(explode(",",$tag_id));
				 $ismatch=0;
				 for(;$j<$tagcount;$j++)
				 {
					 if($tagarr[$j]==$row['tag_id'])
					 {
						 $ismatch=1;
						 break;
					 }
				 }
					if($ismatch==1)
						{
							?><input type="checkbox" name="tag_id[]" value="<?php echo $row['tag_id'];?>" checked><?php echo $row['tag_name']; ?></input><br/><?php
						}
						else
						{
						?><input type="checkbox" name="tag_id[]" value="<?php echo $row['tag_id'];?>"><?php echo $row['tag_name']; ?></input><br/><?php
				        }
				}
				?>
         </td>
	</tr>
	<tr><td><input   style="align:right; width:100px" type="submit" value="post" name="form1"></td></tr>
</table>
</form>	</div>

              <!-- page end-->
          </section>
      </section>
      <!--main content end-->
  </section>
  <?php include_once('footer.php'); ?>