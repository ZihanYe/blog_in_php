<div class="header">
	<div class="logo">
		<?php if (isset($_SESSION['user']) and $_SESSION['user']['role'] == "Admin"): ?>
			<a href="<?php echo BASE_URL .'admin/dashboard.php' ?>">
				<h1>IdeaStorm - Admin</h1>
			</a>
		<?php else: ?>
			<a href="<?php echo BASE_URL .'admin/posts.php' ?>">
				<h1>IdeaStorm - Author</h1>
			</a>
		<?php endif ?>
	</div>
	<?php if (isset($_SESSION['user'])): ?>
		<div class="user-info">
			<span><?php echo $_SESSION['user']['username'] ?></span> &nbsp; &nbsp; 
			<a href="<?php echo BASE_URL . '/index.php'; ?>" class="site-btn">Site</a> &nbsp; &nbsp;
			<a href="<?php echo BASE_URL . '/logout.php'; ?>" class="logout-btn">logout</a>
		</div>
	<?php endif ?>
</div>