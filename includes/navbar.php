<div class="navbar">
	<div class="logo_div">
		<a href="index.php"><h1>IdeaStorm</h1></a>
	</div>
	<ul>
	  <li><a class="active" href="index.php">Home</a></li>
	  <li><a href="#news">News</a></li>
	  <li><a href="contact.php">Contact</a></li>
	  <li><a href="#about">About</a></li>
	  <?php if (!isset($_SESSION['user']['username'])) { ?>
	  	<li><a href="register.php">Join us</a></li>
	  <?php } ?>
	</ul>
</div>