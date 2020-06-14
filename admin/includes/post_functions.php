<?php 
$user_id = 1;
// Post variables
$post_id = 0;
$isEditingPost = false;
$published = 0;
$title = "";
$post_slug = "";
$body = "";
$featured_image = "";
$post_topic = "";

/* - - - - - - - - - - 
-  Post functions
- - - - - - - - - - -*/

// if user clicks the create post button
if (isset($_POST['create_post'])) {
	$user_id = $_SESSION['user']['id'];
	createPost($_POST); 
}
// if user clicks the Edit post button
if (isset($_GET['edit-post'])) {
	$user_id = $_SESSION['user']['id'];
	$isEditingPost = true;
	$post_id = $_GET['edit-post'];
	editPost($post_id);
}
// if user clicks the update post button
if (isset($_POST['update_post'])) {
	$user_id = $_SESSION['user']['id'];
	updatePost($_POST);
}
// if user clicks the Delete post button
if (isset($_GET['delete-post'])) {
	$user_id = $_SESSION['user']['id'];
	$post_id = $_GET['delete-post'];
	deletePost($post_id);
}

// if user clicks the publish post button
if (isset($_GET['publish']) || isset($_GET['unpublish'])) {
	$message = "";
	if (isset($_GET['publish'])) {
		$message = "Post published successfully";
		$post_id = $_GET['publish'];
	} else if (isset($_GET['unpublish'])) {
		$message = "Post successfully unpublished";
		$post_id = $_GET['unpublish'];
	}
	togglePublishPost($post_id, $message);
}

function togglePublishPost($post_id, $message)
{
	global $conn;
	$sql = "UPDATE posts SET published=!published WHERE id=$post_id";
	
	if (mysqli_query($conn, $sql)) {
		$_SESSION['message'] = $message;
		header("location: posts.php");
		exit(0);
	}
}

function createPost($request_values)
	{
		global $conn, $errors, $user_id, $title, $featured_image, $topic_id, $body, $published;
		$title = esc($request_values['title']);
		$body = htmlentities(esc($request_values['body']));
		if (isset($request_values['topic_id'])) {
			$topic_id = esc($request_values['topic_id']);
		}
		if (isset($request_values['publish'])) {
			$published = esc($request_values['publish']);
		}
		// create slug: if title is "The Storm Is Over", return "the-storm-is-over" as slug
		$post_slug = makeSlug($title);
		// validate form
		if (empty($title)) { array_push($errors, "Post title is required"); }
		if (empty($body)) { array_push($errors, "Post body is required"); }
		if (empty($topic_id)) { array_push($errors, "Post topic is required"); }
		// Get image name
	  	$featured_image = $_FILES['featured_image']['name'];
	  	if (empty($featured_image)) { array_push($errors, "Featured image is required"); }
	  	else{
	  		// image file directory
		  	$target = "../static/images/" . basename($featured_image);
		  	if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
		  		// echo '<script>alert("Unable to upload the image")</script>';
		  		array_push($errors, "Failed to upload image. Please check file settings for your server");
		  	}
	  	}
	  	
		// Ensure that no post is saved twice. 
		$post_check_query = "SELECT * FROM posts WHERE slug='$post_slug' LIMIT 1";
		$result = mysqli_query($conn, $post_check_query);

		if (mysqli_num_rows($result) > 0) { // if post exists
			array_push($errors, "A post already exists with that title.");
		}
		// create post if there are no errors in the form
		if (count($errors) == 0) {
			$query = "INSERT INTO posts (user_id, title, slug, image, body, published, created_at, updated_at) VALUES($user_id, '$title', '$post_slug', '$featured_image', '$body', $published, now(), now())";
			if(mysqli_query($conn, $query)){ // if post created successfully
				$inserted_post_id = mysqli_insert_id($conn);
				// create relationship between post and topic
				$sql = "INSERT INTO post_topic (post_id, topic_id) VALUES ($inserted_post_id, $topic_id)";
				if (! mysqli_query($conn, $sql)) {
					$_SESSION['message'] = "Post created successfully but topic not added";
				} else {
					$_SESSION['message'] = "Post created successfully";
				}
				header('location: posts.php');
				exit(0);
			}
		}
	}

/* * * * * * * * * * * * * * * * * * * * *
* - Takes post id as parameter
* - Fetches the post from database
* - sets post fields on form for editing
* * * * * * * * * * * * * * * * * * * * * */
function editPost($role_id)
{
	global $conn, $errors, $title, $post_slug, $body, $published, $isEditingPost, $post_id, $featured_image, $post_topic;
	$sql = "SELECT * FROM posts WHERE id=$role_id LIMIT 1";
	$result = mysqli_query($conn, $sql);
	$post = mysqli_fetch_assoc($result);
	// set form values on the form to be updated
	$title = $post['title'];
	$body = $post['body'];
	$published = $post['published'];
	$featured_image = $post['image'];

	$sql = "SELECT T.name AS name FROM topic T JOIN post_topic P WHERE P.post_id=$role_id AND T.id = P.topic_id";
	$result = mysqli_query($conn, $sql);
	$res = mysqli_fetch_assoc($result);
	if ($res !== null){
		$post_topic = $res['name'];
	}
}

function updatePost($request_values)
{
	global $conn, $errors, $post_id, $title, $featured_image, $topic_id, $body, $published;

	$title = esc($request_values['title']);
	$body = esc($request_values['body']);
	$post_id = esc($request_values['post_id']);
	if (isset($request_values['topic_id'])) {
		$topic_id = esc($request_values['topic_id']);
	}
	// create slug: if title is "The Storm Is Over", return "the-storm-is-over" as slug
	$post_slug = makeSlug($title);

	if (empty($title)) { array_push($errors, "Post title is required"); }
	if (empty($body)) { array_push($errors, "Post body is required"); }
	$featured_image = $_FILES['featured_image']['name'];
	if (!empty($featured_image)) {
		// Get image name
	  	// image file directory
	  	$target = "../static/images/" . basename($featured_image);
	  	if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
	  		array_push($errors, "Failed to upload image. Please check file settings for your server");
	  	}
	} else {
		$featured_image = $request_values['old_featured_image'];
	}

	// register topic if there are no errors in the form
	if (count($errors) == 0) {
		$query = "UPDATE posts SET title='$title', slug='$post_slug', views=0, image='$featured_image', body='$body', published=$published, updated_at=now() WHERE id=$post_id";
		// attach topic to post on post_topic table
		if(mysqli_query($conn, $query)){
			if (isset($request_values['topic_id'])) {
				$topic_id = esc($request_values['topic_id']);
				// create relationship between post and topic
				$sql = "UPDATE post_topic SET topic_id=$topic_id WHERE post_id=$post_id";
				mysqli_query($conn, $sql);
				// header('location: posts.php');
				// exit(0);
			}
			$_SESSION['message'] = "Post updated successfully";
			header('location: posts.php');
			exit(0);
		} else {
			$_SESSION['message'] = "Something went wrong when updating the post";
			header('location: posts.php');
			exit(0);
		}
	}
}

// delete blog post
function deletePost($post_id)
{
	global $conn;
	$sql = "DELETE FROM posts WHERE id=$post_id";
	if (mysqli_query($conn, $sql)) {
		$_SESSION['message'] = "Post successfully deleted";
		header("location: posts.php");
		exit(0);
	}
}


// get all posts from DB
function getAllPosts()
{
	global $conn;
	
	// Admin can view all posts
	// Author can only view their posts
	if ($_SESSION['user']['role'] == "Admin") {
		$sql = "SELECT * FROM posts";
	} elseif ($_SESSION['user']['role'] == "Author") {
		$user_id = $_SESSION['user']['id'];
		$sql = "SELECT * FROM posts WHERE user_id=$user_id";
	}
	$result = mysqli_query($conn, $sql);
	$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

	$final_posts = array();
	foreach ($posts as $post) {
		$post['author'] = getPostAuthorById($post['user_id']);
		array_push($final_posts, $post);
	}
	return $final_posts;
}
// get the author/username of a post
function getPostAuthorById($user_id)
{
	global $conn;
	$sql = "SELECT username FROM users WHERE id=$user_id";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		// return username
		return mysqli_fetch_assoc($result)['username'];
	} else {
		return null;
	}
}

// count published posts
function countPublishedPosts()
{
	global $conn;
	$sql = "SELECT COUNT(*) AS num FROM posts WHERE published = 1";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		// return
		return mysqli_fetch_assoc($result)['num'];
	} else {
		return 0;
	}
}

// count unpublished posts
function countUnpublishedPosts()
{
	global $conn;
	$sql = "SELECT COUNT(*) AS num FROM posts WHERE published = 0";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		// return
		return mysqli_fetch_assoc($result)['num'];
	} else {
		return 0;
	}
}
?>