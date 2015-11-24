<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();
	include 'langCheck.php';

	$id = $_GET['id'];

	$query = $con->prepare("select * from snippets where id = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$query->store_result();
	$number = $query->num_rows;
	$query->close();

	if($number>0 && isset($_GET['id']) && is_numeric($id)){
		$query = $con->prepare("select snippet from snippets where id = ?");
		$query->bind_param("s", $id);
		$query->execute();
		$query->bind_result($code);
		$query->fetch();
		$query->close();

		$code = htmlentities($code);

		$query = $con->prepare("select title, description, date from snippets where id = ?");
		$query->bind_param("s", $id);
		$query->execute();
		$query->bind_result($title, $description, $date);
		$query->fetch();
		$query->close();

		$query = $con->prepare("select tags from tags_snippets where snippets_id = ?");
		$query->bind_param("s", $id);
		$query->execute();
		$query->bind_result($tag);
		while($query->fetch()){
			$temp[] = "#".$tag;
		}
		$query->close();
		$tagsList = implode(", ", $temp);
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $lang['pageTitle']; ?> - <?php echo $title; ?></title>
	<meta charset="utf-8">
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="prettify/prettify.css">
    <link rel="stylesheet" type="text/css" href="css/public.css">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="prettify/prettify.js"></script>
</head>
<body onload="prettyPrint()">

<div class="window">
    <div class="title-area">
        <a href="admin-main.php"><label title="<?php echo $lang['goHome']; ?>" class="appTitle"><?php echo $lang['pageTitle']; ?></label></a>      
    </div>

    <div class="details-window-top">
        <label id="detail-title"><?php echo $title; ?></label>
        <label id="date-label"> - <?php echo $lang['created']; ?> (<?php echo $date; ?>) </label>
    </div>
    <div class="details-window-under">
        <label><?php echo $lang['description']; ?>:</label><br>
        <label id="detail-desc"><?php echo $description; ?></label><br><br>
        <label id="detail-tags"><?php echo $tagsList; ?></label>
    </div>

    <pre class="prettyprint code linenums"><?php echo $code; ?></pre>
</div>

</body>
</html>
<?php }else{ ?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $lang['pageTitle']; ?> - <?php echo $lang['notFound']; ?></title>
	<meta charset="utf-8">
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="css/public.css">
</head>
<body>
<div class="wrap">
	<a href="admin-main.php"><label class="main-label"><?php echo $lang['pageTitle']; ?></label></a>
	<label id="message"><?php echo $lang['snippetNotFound']; ?></label>
</div>

</body>
</html>
<?php } ?>