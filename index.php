<?php
//error_reporting(0);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Images Parser</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<link rel="stylesheet" href="style.css" />
		<script>
			function imgError(img){
				img.onerror = "";
				img.src = "image-not-found.gif";
				return true;
			}
		</script>
		
	</head>
	
	<body>
		<div class="container">
			<form action="" method="POST">
				<input type="text" name="link" placeholder="Enter any URL" required autocomplete="off" value="http://"/>
				<input type="submit" name="extract" value="Extract Images" />
			</form>
			
<?php
function isValidURL($url)
{
    return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

if(isset($_POST['extract']))
{
	$url = $_POST['link'];
	$len = strlen($url);
	
	$pieces = explode("/", $url);
	$result = $pieces[0].'//'.$pieces[2];
		
	if(!isValidURL($url))
	{
		die("* Please enter valid URL including http://<br>");
	}
	
	$html = file_get_contents($url);
	
	$doc = new DOMDocument();
	@$doc->loadHTML($html);
	
	$tags = $doc->getElementsByTagName('img');
	
	foreach($tags as $tag){
		$src = $tag->getAttribute('src');
		
		if(!preg_match("/http:/", $src) && !preg_match("/https:/", $src))
		{
			if($src[0] == '/' && $src[1] == '/')
			{
				$src = 'http:'.$src;
			}
			/*if($url[($len-1)] == '/')
			{
				$src = $url . $src;
			}
			else
			{
				$src = $url . '/' . $src;
			}*/
			else if($src[0] == '/' || $src[0] == '.')
			{
				if($url[($len-1)] == '/')
				{
					$src = $result . $src;
				}
				else
				{
					$src = $result . '/' . $src;
				}
			}
			else
			{
				$src = $url . '/' . $src;
			}
		}
		echo '<img src="'.$src.'" height="200" >';
		echo "\n";
	}
}
?>
		</div>
	</body>
</html>
