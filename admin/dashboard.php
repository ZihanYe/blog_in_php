<?php  include('../config.php'); ?>	
	<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
	<title>Admin | Dashboard</title>
</head>

<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/post_functions.php'); ?>
<?php $numUser = countUsers(); ?>
<?php $numPublished = countPublishedPosts(); ?>
<?php $numUnpublished = countUnpublishedPosts(); ?>

<body>
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>
	<div class="container dashboard">
		<h1>Statistic</h1>
		<div class="stats">
			<a href="users.php" class="first">
				<span><?php echo $numUser; ?></span> <br>
				<span>Registered users</span>
			</a>
			<a href="posts.php">
				<span><?php echo $numPublished; ?></span> <br>
				<span>Published posts</span>
			</a>
			<a href="posts.php">
				<span><?php echo $numUnpublished; ?></span> <br>
				<span>Pending posts</span>
			</a>
		</div>
		<br><br><br>
		<div class="buttons">
			<a href="users.php">Add Users</a>
			<a href="posts.php">Add Posts</a>
		</div>
	</div>
</body>
</html>