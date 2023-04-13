<!-- Blog Sidebar Widgets Column -->
<div class="col-md-4">

	<!-- Login Well -------------------------------------------------- -->
	<div class="well">
		<!-- //checks if the 'userid' key in the $_SESSION superglobal array is empty -->
		<?php if(empty($_SESSION['userid'])):?>
		<h4>Login Page</h4>
		

		<!-- form which perfro -->
		<!-- The form is set to submit the data to the "includes/login.php" 
		file using the POST method. -->
		<form action="includes/login.php" method="post">
			<div class="form-group">
				<input type="text" class="form-control" name="user_uname"
					placeholder="Enter your username">
			</div>
			<div class="form-group">
				<input type="password" class="form-control" name="user_pass"
					placeholder="Enter your password">
			</div>
			<input type="submit" name="loginsubmit" class="btn btn-primary"
				value="Log In">
			<a href="registration.php" class="btn btn-success">Register</a>
		</form>
		
		<!-- <h6>You must be logged in to post or comment.</h6> -->
		<?php else:?>
		<h5>Welcome <?php echo $_SESSION['username'];?>
		<a onclick="return confirm('Are you sure you want to log out?');"
			href="includes/logout.php" class="btn btn-warning pull-right">Log Out</a>
		</h5>
		<?php endif;?>
		
	</div>		

	<!-- Blog Search Well -------------------------------------------- -->
	<div class="well">
		<h4>Blog Search</h4>
		<form action="sposts.php" method="get">
			<div class="input-group">
				<input type="text" class="form-control" name="tag">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit">
						<span class="glyphicon glyphicon-search"></span>
					</button>
				</span>
			</div>
		</form>
	</div>	 <!-- /.well -->	
	
	<!-- Blog Categories Well -->
	<div class="well">
		<h4>Blog Categories</h4>
		<div class="row">
		
		<?php
		// determine categories/column based on total number of categories
		$query = "select count(*) from cms_categories";
		$result = mysqli_query($connection, $query);
		$num_cats = mysqli_fetch_array($result)[0];
		
		$cats_per_col = ceil($num_cats / 2);
		?>
		
			<div class="col-md-6">
				<?php
					// get the first 4 alphabetically sorted category names
					$query = "select * from cms_categories order by cms_categories.cat_title ASC 
								LIMIT 0, $cats_per_col";
					$content = mysqli_query($connection, $query);
				?>	
				<ul class="list-unstyled">
				<?php foreach($content as $iter):?>
					<li><a href="cposts.php?cid=<?php echo $iter['cat_id'];?>">
						<?php echo $iter['cat_title'];?></a>
					</li>
				<?php endforeach;?>
				</ul>
			</div>		<!-- /.col-md-6 -->
			
			<div class="col-md-6">
				<?php
			
					$query = "select * from cms_categories order by cms_categories.cat_title asc limit $cats_per_col";
					$cats = mysqli_query($connection, $query);
				?>	
				<ul class="list-unstyled">
				<?php foreach($cats as $cat):?>
					<li><a href="cposts.php?cid=<?php echo $cat['cat_id'];?>">
						<?php echo $cat['cat_title'];?></a>
					</li>
				<?php endforeach?>
				</ul>
				
			</div>		<!-- /.col-md-6 -->

		</div>		<!-- /.row -->

	</div>		<!-- /.well -->
	<!-- widget means added to the bottom of the page -->
	<?php include 'widget.php';?>

</div>		<!-- /.col-md-4 -->