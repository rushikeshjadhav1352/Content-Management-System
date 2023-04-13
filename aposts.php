<?php include 'includes/database.php';?>
<?php include 'includes/header.php';?>
<?php include 'includes/navigation.php';?>
<?php include 'includes/functions.php';?>

<?php
	if(isset($_GET['u'])) {
		$uname = mysqli_real_escape_string($connection, $_GET['u']);
		
		// find total number of posts to determine number of pages for pagination
		$query = "select * from cms_posts where post_author = '$uname'";
		$result = mysqli_query($connection, $query);
		$total_posts = mysqli_num_rows($result);
		$total_pages = ceil($total_posts / POSTSPERPAGE);
		
		// if $total_pages is 0, set it to 1 so pagination will not look for page 0
		if($total_pages < 1) {
			$total_pages = 1;
		}

		// check $_GET to get page number for pagination, otherwise start with page 1 
		if(isset($_GET['p'])) {
			$page = mysqli_real_escape_string($connection, $_GET['p']);

			// the 1st number in LIMIT is a multiple of POSTSPERPAGE starting at 0
			$first_limit = ($page - 1) * POSTSPERPAGE;
		} else {
			// $first_limit is needed for LIMIT clause, $page is needed for setting
			// active class of pagination buttons
			$first_limit = 0;
			$page = 1;
		}
		
		// create LIMIT clause
		$limit_clause = "LIMIT $first_limit, " . POSTSPERPAGE;
		
		// find posts for a specific author
		$q = "SELECT cms_posts.*, cms_users.user_image FROM cms_posts
					INNER JOIN cms_users ON cms_posts.post_author = cms_users.user_uname
					WHERE post_author = '$uname' 
					AND post_status = 'Published'
					ORDER BY post_date DESC " . $limit_clause;
					
		$posts = mysqli_query($connection, $q);
		
		if(!$posts) {	
			$div_class = 'danger';
			$div_msg = 'Database error: ' . mysqli_error($connection);
		} else {
			$post_count = mysqli_num_rows($posts);		
			if($post_count == 0) {
				$page_count = 0;
				$div_class = 'danger';
				$div_msg = "Sorry, no posts found for user <strong>'$uname'</strong>.";
			} else {
				$page_count = ceil($post_count / 8);
				$div_class = 'success';
				$div_msg = " Showing published posts for user <strong>'$uname'</strong>.";
				$div_msg .= " <a href='index.php'>Show All</a>";
			}
		}
	}
	
?>
	<!-- special alert div -->
	<?php if(!empty($div_msg)):?>
	<div class="alert alert-<?php echo $div_class;?>">
		<?php echo $div_msg;?>
	</div>
	<?php endif;?>			
	
	<!-- Blog Post Begins Here -->
	
	<?php foreach($posts as $post):?>
	<h2>
	<a href="post.php?pid=<?php echo $post['post_id'];?>"><?php echo $post['post_title'];?></a>
	</h2>
	<p class="lead">by 	<?php echo $post['post_author'];?>
		<img src="images/<?php echo $post['user_image'];?>" width="64px" height="64px">
	</p>
	<p><span class="glyphicon glyphicon-time"></span>
	<?php date_default_timezone_set('Asia/Kolkata'); ?>
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
  				<a href="aposts.php?p=1&u=<?php echo $uname;?>" aria-label="Previous">
    				<span aria-hidden="true">&laquo;</span>
  				</a>
				</li>
		  <?php for($i = 1; $i <= $total_pages; $i++):?>
		  <?php if($i == $page):?>
		  <li class="active">
			  	<a href="aposts.php?p=<?php echo $i;?>&u=<?php echo $uname;?>"><?php echo $i;?></a>
		  	</li>
		  	<?php else:?>
		  <li>
			  	<a href="aposts.php?p=<?php echo $i;?>&u=<?php echo $uname;?>"><?php echo $i;?></a>
		  	</li>
		  	<?php endif;?>
		  <?php endfor;?>
		  <li>
				<a href="aposts.php?p=<?php echo $total_pages;?>&u=<?php echo $uname;?>" 
					aria-label="Next">
    				<span aria-hidden="true">&raquo;</span>
  				</a>
				</li>
			</ul>
		</div>
	
</div>		<!-- /.col-md-8 -->

<?php  include 'includes/sidebar.php'; ?>     
<?php  include 'includes/footer.php'; ?>
