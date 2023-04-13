<?php include 'includes/db.php';?>
<?php include 'includes/header.php';?>
<?php include 'includes/navigation.php';?>
<?php include 'includes/functions.php';?>

<?php
	// GET variable 'l' = 'x' means login authentication failed in login.php
	if(isset($_GET['l'])) {
		//real escape which is used for sql injection.
		$l = mysqli_real_escape_string($connection, $_GET['l']);
		if($l == 'x') {
			echo "<script>alert('Incorrect details , try');</script>";	
		}
	}
?>

<?php 
	// toal no of posts.
	$query = "select * from cms_posts";






	$results = mysqli_query($connection, $query);
	$total_posts = mysqli_num_rows($results);
	$total_pages = ceil($total_posts / 10);
	
	// if $total_pages is 0, set it to 1 so pagination will not look for page 0
	if($total_pages < 1) {
		$total_pages = 1;
	}

	// check $_GET to get page number for pagination, otherwise start with page 1 
	if(isset($_GET['p'])) {
		$page = mysqli_real_escape_string($connection, $_GET['p']);

		// the 1st number in LIMIT is a multiple of POSTSPERPAGE starting at 0
		$first_limit = ($page - 1) * 10;
	} else {
		// $first_limit is needed for LIMIT clause, $page is needed for setting
		// active class of pagination buttons
		$first_limit = 0;
		$page = 1;
	}
	
	// create LIMIT clause
	$limit_clause = "limit $first_limit, " . 10;
	
	// find all posts
	$query = "SELECT cms_posts.*, cms_users.user_image FROM cms_posts
				INNER JOIN cms_users ON cms_posts.post_author = cms_users.user_uname
				WHERE post_status = 'Published'
				ORDER BY post_date DESC " . $limit_clause;

	$posts = mysqli_query($connection, $query);
	
	if(!$posts) {	
		//$div_class = 'danger';
		die('Database error: ' . mysqli_error($connection));
	} else {
		$post_count = mysqli_num_rows($posts);		
		if($post_count == 0) {
			//$div_class = 'danger';
			//$div_msg = "Nothing to show here";
		} else {
			$div_class = 'success';
			$div_msg = "Showing all published posts.";
		}
	}	
	
?>
<!-- special alert div
<?php if(!empty($div_msg)):?>
<div class="alert alert-  <?//php echo $div_class;?>"> 
	<?php //echo $div_msg;?>
</div>
<?php endif;?>			 -->

<!-- Blog Post Begins Here -->

<?php foreach($posts as $post):?>
<h2>
<a href="post.php?pid=<?php echo $post['post_id'];?>"><?php echo $post['post_title'];?></a>
</h2>
<p class="lead">by 
	<a href="aposts.php?u=<?php echo $post['post_author'];?>">
		<?php echo $post['post_author'];?>
		<img width="100" src="images/<?php echo $post['user_image'];?>" "> <!-- height="64px -->
	</a>
</p>
<p><span class="glyphicon glyphicon-time"></span> 
<?php date_default_timezone_set("Asia/Kolkata"); ?>
	Posted on <?php echo date('M. j, Y, g:i a', strtotime($post['post_date']));?></p>
<hr>
<a href="post.php?pid=<?php echo $post['post_id'];?>">
<?php empty($post['post_image'])?$post['post_image']='post_default.png':
		$post['post_image'];?>
	<img class="img-responsive" src="images/<?php echo $post['post_image'];?>" alt="image">
</a>
<hr>
<p><?php echo shortenText($post['post_content']);?></p>
<a class="btn btn-primary" href="post.php?pid=<?php echo $post['post_id'];?>">
	Read More <span class="glyphicon glyphicon-chevron-right"></span>
</a>
<hr>
<?php endforeach;?>

<!-- pagination links ---------------------------------------- -->
<div class="pagination-div">
	<ul class="pagination pagination-sm"  >
		<li>
				<a href="index.php?p=1" aria-label="Previous">
  				<span aria-hidden="true">&laquo;</span>
				</a>
			</li>
	  <?php for($i = 1; $i <= $total_pages; $i++):?>
	  <?php if($i == $page):?>
	  <li class="active">
		  	<a href="index.php?p=<?php echo $i;?>"><?php echo $i;?></a>
	  	</li>
	  	<?php else:?>
	  <li>
		  	<a href="index.php?p=<?php echo $i;?>"><?php echo $i;?></a>
	  	</li>
	  	<?php endif;?>
	  <?php endfor;?>
	  <li>
			<a href="index.php?p=<?php echo $total_pages;?>" aria-label="Next">
  				<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>
	</div>

</div>		<!-- /.col-md-8 -->

<?php  include 'includes/sidebar.php'; ?> 



<!-- This is footer file , which includes downside of the project -->
<?php  include 'includes/footer.php'; ?>
