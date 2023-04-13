<?php include 'includes/db.php';?>
<?php include 'includes/header.php';?>
<?php include 'includes/navigation.php';?>
<?php include 'includes/functions.php';?>

<?php
	// ---------- when 'Submit' button for comment is pressed ------------
	if(isset($_POST['commentsubmit'])) {
		$c_author = $_SESSION['username'];
		$c_content = mysqli_real_escape_string($connection, $_POST['comment_content']);
		
		// validate input
		if(empty($c_content)) {
			$div_class = 'danger';
			$div_msg = 'You must enter comment to submit.';
		} else {

			
			$c_post_id = $_GET['pid'];
			




			$query = "insert into cms_comments 
					(comment_post_id, comment_author, comment_content, comment_date)
					VALUES ($c_post_id, '$c_author', '$c_content', now())";
			
			$result = mysqli_query($connection, $query);
			
		// function confirmQuery(mixed $result, mixed $operation): array<string>

			$div_info = confirmQuery($result, 'insert');
			$div_class 		= $div_info['div_class'];
			$div_msg 			= $div_info['div_msg'];
		}
	}
?>

<?php
	// ----------- get the post_id passed from index.php -----------------
	if(isset($_GET['pid'])) {
		$pid = mysqli_real_escape_string($connection, $_GET['pid']);
		
		$query = "update cms_posts set post_views_count = post_views_count + 1
					where post_id = $pid";
		mysqli_query($connection, $query);
		
		$query = "select cms_posts.*, cms_users.user_image, 
					count(cms_comments.comment_id) AS post_comment_count 
					from cms_posts 
					INNER JOIN cms_users ON cms_posts.post_author = cms_users.user_uname 
					LEFT OUTER JOIN cms_comments on cms_posts.post_id = cms_comments.comment_post_id 
					where cms_posts.post_id = $pid 
					GROUP BY cms_posts.post_id";
					
		$result = mysqli_query($connection, $query);
		global $post;
		if(!$result) {
			$div_class = 'danger';
			$div_msg = 'Database error: ' . mysqli_error($connection);
			die("FAILED".mysqli_error($connection));
		} else {
			$post = mysqli_fetch_array($result);	
		}
	}
?>
	<!--------------------- special alert div ------------------------ -->
	<?php if(!empty($div_msg)):?>
	<div class="alert alert-<?php echo $div_class;?>">
		<?php echo $div_msg;?>
	</div>
	<?php endif;?>			
	<!-- -------------------------the post --------------------------- -->
	<h2>
	<!-- <?php echo "Hello rushikesh"; ?> -->
	<a href="#"><?php echo $post['post_title'];?></a>
	</h2>
	<p class="lead">by 
		<a href="aposts.php?u=<?php echo $post['post_author'];?>">
			<?php echo $post['post_author'];?>
			<img src="images/<?php echo $post['user_image'];?>" width="64px" height="64px">
		</a>
	</p>
	<!-- <?php echo "Hello rushikesh"; ?> -->
	<p><span class="glyphicon glyphicon-time"></span>
	<?php date_default_timezone_set('Asia/Kolkata'); ?>
		Posted on <?php echo date('M. j, Y, g:i a', strtotime($post['post_date']));?></p>
	<hr>
	<?php empty($post['post_image'])?$post['post_image']='post_default.png':
		$post['post_image'];?>
	<img class="img-responsive" src="images/<?php echo $post['post_image'];?>" alt="">
	<hr>
	<p><?php echo $post['post_content'];?></p>
	<p><span class="glyphicon glyphicon-eye-open"></span>&nbsp;

	<!-- //&nbsp is space  -->
		<?php echo $post['post_views_count'];?>&nbsp;views&nbsp;&nbsp;
		<span class="glyphicon glyphicon-comment"></span>&nbsp;
		<?php echo $post['post_comment_count'];?>&nbsp;comments
	</p>
	<hr>
	
	<!----------------------- comments form -------------------------- -->
	<?php if(isset($_SESSION['userid'])):?>
	<div class="well">
		<h4>Leave a Comment:</h4>

		<form action="post.php?pid=<?php echo $post['post_id'];?>" method="post" role="form">
			<div class="form-group">
				<label for="comment_author">Your Username</label>
				<div class="well well-sm">
					<?php echo $_SESSION['username'];?>
				</div>		
			</div>
			<div class="form-group">
				<label for="comment_content">Your Comment</label>
				<textarea class="form-control" name="comment_content" rows="4"></textarea>
			</div>
			<button type="submit" class="btn btn-primary" name="commentsubmit">Submit</button>
		</form>
	</div>
	<?php endif;?>
	<hr>
	<!--------------------- posted comments -------------------------- -->
<?php
	$pid = $post['post_id'];

	$q = "SELECT cms_comments.*, cms_users.user_uname, cms_users.user_image
				FROM cms_comments
				LEFT OUTER JOIN cms_users ON 
				comment_author = user_uname
				WHERE comment_post_id = $pid 
				ORDER BY comment_date DESC";
				
	$comments = mysqli_query($connection, $q);
?>
	
<?php	if($comments):?>				
	<?php foreach($comments as $comment):?>
	<div class="media">
	<?php if(!empty($comment['user_uname'])):?>
		<a class="pull-left" href="aposts.php?u=<?php echo $comment['user_uname'];?>">			
	<?php else:?>
		<a class="pull-left" href="#">
	<?php endif;?>
		<?php if(!empty($comment['user_image'])):?>
			<img class="media-object" src="images/<?php echo $comment['user_image'];?>"
				width="64px" height="64px">
		<?php else:?>
			<img class="media-object" src="images/default.png"
				width="64px" height="64px">
		<?php endif;?>
		</a>
		<div class="media-body">
			<h4 class="media-heading"><?php echo $comment['comment_author'];?>
				<small><?php echo date('M. j, Y, g:i a', strtotime($comment['comment_date']));?>
				&nbsp;&nbsp;
				
					<?php if($comment['comment_status']=='like'):?>
					( <i class="glyphicon glyphicon-thumbs-up"></i>
					<?php echo $post['post_author'].' likes this!'?> )
					<?php elseif($comment['comment_status']=='dislike'):?>
					( <i class="glyphicon glyphicon-thumbs-down"></i>
					<?php echo $post['post_author'].' dislikes this!'?> )
					<?php endif;?>
				</small>
			</h4>
			<?php echo $comment['comment_content'];?>
		</div>
	</div>
	<?php endforeach;?>
<?php endif;?>
	
</div>		<!-- /.col-md-8 -->

<?php  include 'includes/sidebar.php'; ?>     
<?php  include 'includes/footer.php'; ?>
