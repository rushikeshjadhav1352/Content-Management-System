<?php include 'includes/db.php';?>
<?php include 'includes/header.php';?>
<?php include 'includes/navigation.php';
//include 'extra_functions.php';  
?>
<?php include 'includes/functions.php';?>



<?php
//sposts 
//find total number of posts to determine number of pages for pagination
		
function total_no_of_posts(){

    if(isset($_GET['tag'])) {
		$tag = mysqli_real_escape_string($connection, $_GET['tag']);

$query = "SELECT * FROM cms_posts where post_tags like '%$tag%' or post_title like '%$tag%' ";
		$result = mysqli_query($connection, $query);
		$total_posts = mysqli_num_rows($result);
		$total_pages = ceil($total_posts / POSTSPERPAGE);
}
}
        ?>