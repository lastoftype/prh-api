<?php include 'prh-api.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Penguin Random House API Demo</title>
	<link rel="stylesheet" href="dist/css/bootstrap.css">
</head>
<body>

	<div class="container">
		<div class="col-md-12">
			<div class="jumbotron">
				<h1>PRH API Class</h1>
				<p>Get an <code>API_KEY</code> to begin using.</p>
				<p>You can request a key at <a href="http://developer.penguinrandomhouse.com">developer.penguinrandomhouse.com</a></p>
			</div><!-- /.jumbotron -->
		</div>
		<div class="col-md-12">
			<pre>
			<?php 
		
					// Get info for 'Gone Girl' Hardcover ISBN
					$info = PRHAPI::get_title('9780307588364'); 
					print_r($info); 

			?>
			</pre>
		</div><!-- /.col-md-12 -->
	</div><!-- /.container -->
	
</body>
</html>