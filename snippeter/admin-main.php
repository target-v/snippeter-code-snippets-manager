<?php 
	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	protectAdmin();

	$query = $con->prepare("select id from admin where username = ?");
    $query->bind_param("s", $_SESSION['admin']);
    $query->execute();
    $query->bind_result($user_id);
    $query->fetch();
    $query->close();
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $lang['pageTitle']; ?> - <?php echo $lang['adminPanel']; ?></title>
	<meta charset="utf-8">
    <link href='http://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/admin-main.css">
    <link href="css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
    <link href="css/jquery.tagit.css" rel="stylesheet" type="text/css">
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/admin-script.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/tag-it.js" type="text/javascript"></script>
    <script>
        $(function(){
            $('#myTags').tagit();
        });
    </script>
</head>
<body style="background: #ecf0f1 !important;">
<label hidden id="langHolder"><?php if(isset($_SESSION['lang'])) echo $_SESSION['lang']; else echo "en";?></label>
<label hidden id="siteHolder"><?php echo $pageRoot; ?></label>
<div class="admin-sidebar">
	<label id="admin-title"><?php echo $lang['adminPanel']; ?></label>
	<ul class="admin-menu">
		<li class="active" id="dashboard-button"><?php echo $lang['dashboard']; ?></li>
		<li id="snippets-button"><?php echo $lang['snippets']; ?></li>
		<li id="users-button"><?php echo $lang['usersList']; ?></li>
		<li id="banned-users-button"><?php echo $lang['bannedUsers']; ?></li>
		<li id="settings-button"><?php echo $lang['settings']; ?></li>
		<li id="admin-logout"><?php echo $lang['logout']; ?></li>
	</ul>
</div>

<div id="admin-blur"></div>

<div class="edit-snippet-window">
    <input type="text" placeholder="<?php echo $lang['name'] ?>" name="name" id="name"><br>
    <textarea placeholder="<?php echo $lang['description'] ?>" name="description" id="description"></textarea><br>
    <textarea placeholder="<?php echo $lang['snippet'] ?>" name="snippet" id="snippetArea"></textarea>
    <input type="text" name="tags" id="myTags">
    <label id="save-snippet"><?php echo $lang['save']; ?></label>
    <label id="snippet-cancel"><?php echo $lang['cancel']; ?></label><label class="tags-label"><?php echo $lang['snippetTags']; ?></label>
    <label id="snippet-error"></label>
    <label hidden data-type="save" class="check-label"></label>
    <label hidden class="id-holder"></label>
</div>

<div class="add-user-window">
	<input type="text" name="username" placeholder="<?php echo $lang['username']; ?>" id="manual-username">
	<input type="password" name="password" placeholder="<?php echo $lang['password']; ?>" id="manual-password"><br>
	<input type="text" name="email" placeholder="<?php echo $lang['email']; ?>" id="manual-email">
	<div class="manual-buttons-wrap">
		<label id="manual-error">e</label>
		<label id="manual-save"><?php echo $lang['save']; ?></label>
		<label id="manual-close"><?php echo $lang['close']; ?></label>
	</div>
</div>

<div class="edit-user-window">
	<input type="text" name="username" placeholder="<?php echo $lang['username']; ?>" id="edit-username">
	<input type="password" name="password" placeholder="<?php echo $lang['password']; ?>" id="edit-password"><br>
	<input type="text" name="email" placeholder="<?php echo $lang['email']; ?>" id="edit-email">
	<label id="edit-activate"></label>
	<label hidden id="edit-id-holder"></label>
	<label hidden id="edit-activate-holder"></label>
	<div class="edit-buttons-wrap">
		<label id="edit-error">e</label>
		<label id="edit-save"><?php echo $lang['save']; ?></label>
		<label id="edit-close"><?php echo $lang['close']; ?></label>
	</div>
</div>

<?php 
	$query = $con->prepare("select count(*) as count from users");
	$query->bind_result($regUsers);
	$query->execute();
	$query->fetch();
	$query->close();

	$query = $con->prepare("select count(*) as count from users where active = 0");
	$query->bind_result($inactiveUsers);
	$query->execute();
	$query->fetch();
	$query->close();

	$query = $con->prepare("select count(*) as count from users where banned = 1");
	$query->bind_result($bannedUsers);
	$query->execute();
	$query->fetch();
	$query->close();

	$query = $con->prepare("select count(*) as count from snippets");
	$query->bind_result($numSnippets);
	$query->execute();
	$query->fetch();
	$query->close();

	$query = $con->prepare("select count(*) as count from snippets where public = 1");
	$query->bind_result($publicSnippets);
	$query->execute();
	$query->fetch();
	$query->close();

	$query = $con->prepare("select count(*) from user_online");
	$query->bind_result($userCount);
	$query->execute();
	$query->fetch();
	$query->close();
?>

<div class="dashboard page">
	<label class="page-title"><?php echo $lang['dashboard']; ?></label>
	<div class="dash-info">
		<div id="online-users"><?php echo $lang['onlineUsers']; ?>: <?php echo $userCount; ?></div>
		<div id="registered-users"><?php echo $lang['registeredUsers']; ?>: <?php echo $regUsers; ?></div>
		<div id="inactive-users"><?php echo $lang['inactiveUsers']; ?>: <?php echo $inactiveUsers; ?></div>
		<div id="banned-users"><?php echo $lang['bannedUsers']; ?>: <?php echo $bannedUsers; ?></div>
		<div id="snippets-num"><?php echo $lang['numberOfSnippets']; ?>: <?php echo $numSnippets; ?></div>
		<div id="snippets-public"><?php echo $lang['publicSnippets']; ?>: <?php echo $publicSnippets; ?></div>
	</div>
</div>

<div class="snippets page" style="display:none;">
	<label class="page-title"><?php echo $lang['snippets']; ?></label><br>
	<input type="text" id="searchSnippet" placeholder="<?php echo $lang['search']; ?>">
	
	<div class="snippet-options">
		<label id="edit-snippet" title="<?php echo $lang['editSnippet']; ?>"><?php echo $lang['edit']; ?></label>
		<label id="delete-snippet" title="<?php echo $lang['deleteSelectedSnippet']; ?>"><?php echo $lang['delete']; ?></label>
		<label class="visible" id="select-all-snippets" title="<?php echo $lang['selectAllSnippets']; ?>"><?php echo $lang['selectAll']; ?></label>
	</div>
	<div class="snippets-table-wrap">
		<table>
			<tr id="snippet-table-header">
				<td><?php echo $lang['owner']; ?></td>
				<td><?php echo $lang['title']; ?></td>
				<td><?php echo $lang['description']; ?></td>
				<td><?php echo $lang['public']; ?></td>
				<td><?php echo $lang['select']; ?></td>
			</tr>
			<?php
				$query = $con->prepare("select id, user_id, title, description, public from snippets limit ?");
				$query->bind_param("s", $numSnip);
				$query->execute();
				$query->store_result();
				$query->bind_result($snippet_id, $user_id, $title, $description, $public);
				$q = $con->prepare("select username from users where user_id = ?");

				while($query->fetch()){ 
					$q->bind_param("s", $user_id);
					$q->execute();
					$q->bind_result($username);
					$q->fetch();
				?>
			<tr class="snippet-row">

				<td class="owner-holder" onclick="previewSnippet('<?php echo $snippet_id; ?>');" data-username="<?php echo $username; ?>"><?php echo $username; ?></td>
				<td class="title-holder" onclick="previewSnippet('<?php echo $snippet_id; ?>');"><?php echo $title; ?></td>
				<td><?php echo $description; ?></td>
				<td class='publicStatus' data-publicStatus="1"><?php if($public == 0) echo 'No'; else echo 'Yes';?></td>
				<td><input type="checkbox" class="snippet-checker"><label hidden data-snippet-id = "<?php echo $snippet_id; ?>" class="snippet-id-holder"><?php echo $snippet_id; ?></label></td>
			</tr>
			<?php } $q->close(); ?>
		</table>

		<?php 
			$query = $con->prepare("select count(*) from snippets");
			$query->execute();
			$query->bind_result($snippetsNum);
			$query->fetch();
			$query->close();

			$pages = ceil($snippetsNum / $numSnip);
			if($pages > 1){ ?>
			<div class="snippet-pagination">
			<?php for($i = 1; $i <= $pages; $i++){ ?>
			<label <?php if($i == 1) echo "class='activePage'";?>><?php echo $i; ?></label>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</div>

<div class="settings page" style="display: none;">
	<label class="page-title"><?php echo $lang['settings']; ?></label>
	<div class="settings-panel">
	<?php 
		$query = $con->prepare("select email from admin where username = 'admin'");
		$query->bind_result($adminEmail);
		$query->execute();
		$query->fetch();
		$query->close();
	?>
		<div id="setting-wrap">
			<p id="admin-email"><?php echo $lang['currentAdminEmail']; ?>: <?php echo $adminEmail; ?></p>
		
			<label id="setting-pass-button"><?php echo $lang['changePassword']; ?></label>
			<label id="setting-mail-button"><?php echo $lang['changeEmail']; ?></label>

			<div class="setting-pass-form">
				<input type="password" name="oldpass" id="setting-oldpass" placeholder="<?php echo $lang['oldPassword']; ?>"><br>
				<input type="password" name="newpass" id="setting-newpass" placeholder="<?php echo $lang['newPassword']; ?>"><br>
				<input type="password" name="reppas" id="setting-reppass" placeholder="<?php echo $lang['repeatPassword']; ?>">
				<label id="setting-submit"><?php echo $lang['change']; ?></label>
				<label id="setting-close"><?php echo $lang['close']; ?></label>
			</div>
			<label id="setting-error"></label>

			<div class="setting-mail-form">
				<input type="text" name="newmail" id="setting-newmail" placeholder="<?php echo $lang['newEmail']; ?>"><br>
				<input type="text" name="repmail" id="setting-repmail" placeholder="<?php echo $lang['repeatEmail']; ?>"><br>
				<label id="setting-submit-mail"><?php echo $lang['change']; ?></label>
				<label id="setting-close-mail"><?php echo $lang['close']; ?></label>
			</div>
			<label id="setting-error-mail"></label>
		</div>
	</div>
</div>

<div class="users page" style="display: none;">
	<label class="page-title"><?php echo $lang['users']; ?></label><br>
	<input type="text" id="searchBox" placeholder="<?php echo $lang['search']; ?>">
	
	<div class="user-options">
		<label class="visible" id="add-user" title="<?php echo $lang['manualAddNewUser']; ?>"><?php echo $lang['add']; ?></label>
		<label id="edit-user" title="<?php echo $lang['editUser']; ?>"><?php echo $lang['edit']; ?></label>
		<label id="delete-user" title="<?php echo $lang['deleteSelectedUser']; ?>"><?php echo $lang['delete']; ?></label>
		<label id="ban" title="<?php echo $lang['banUsers'] ?>"><?php echo $lang['ban']; ?></label>
		<label class="visible" id="select-all" title="<?php echo $lang['selectAllUsers']; ?>"><?php echo $lang['selectAll']; ?></label>
	</div>
	<div class="table-wrap">
		<table>
			<tr id="table-header">
				<td><?php echo $lang['id']; ?></td>
				<td><?php echo $lang['username']; ?></td>
				<td><?php echo $lang['email']; ?></td>
				<td><?php echo $lang['joined']; ?></td>
				<td><?php echo $lang['active']; ?></td>
				<td><?php echo $lang['select']; ?></td>
			</tr>

			<?php 
				$query = $con->prepare("select user_id, username, email, joined, active from users where banned = 0 limit ?");
				$query->bind_param("s", $numUsers);
				$query->execute();
				$query->bind_result($user_id, $username, $email, $joined, $active);
				while($query->fetch()){ ?>
				<tr class="user-row">
					<td data-id="<?php echo $user_id; ?>" class="userId"><?php echo $user_id; ?></td>
					<td class="username-holder" data-username="<?php echo $username; ?>"><?php echo $username; ?></td>
					<td class="email-holder"><?php echo $email; ?></td>
					<td><?php echo $joined; ?></td>
					<td class='activeStatus' data-activeStatus="<?php echo $active; ?>"><?php if($active == 1) echo $lang['yes']; else echo $lang['no']; ?></td>
					<td><input type="checkbox" class="checker"></td>
				</tr>
			<?php } ?>

		</table>
		<?php 
			$query = $con->prepare("select count(*) from users where banned = 0");
			$query->execute();
			$query->bind_result($usersNum);
			$query->fetch();
			$query->close();

			$pages = ceil($usersNum / $numUsers);
			if($pages > 1){ ?>
			<div class="pagination">
			<?php for($i = 1; $i <= $pages; $i++){ ?>
			<label <?php if($i == 1) echo "class='activePage'";?>><?php echo $i; ?></label>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</div>

<div class="banned-users page" style="display: none;">
	<label class="page-title"><?php echo $lang['bannedUsers']; ?></label><br>
	<input type="text" id="banned-searchBox" placeholder="<?php echo $lang['search']; ?>">

	<div class="banned-user-options">
		<label id="unban" title="<?php echo $lang['removeBan']; ?>"><?php echo $lang['unban']; ?></label>
		<label class="visible" id="banned-select-all" title="Select all users"><?php echo $lang['selectAll']; ?></label>
	</div>
	<div class="banned-table-wrap">
		<table>
			<tr id="banned-table-header">
				<td><?php echo $lang['id']; ?></td>
				<td><?php echo $lang['username']; ?></td>
				<td><?php echo $lang['email']; ?></td>
				<td><?php echo $lang['joined']; ?></td>
				<td><?php echo $lang['active']; ?></td>
				<td><?php echo $lang['select']; ?></td>
			</tr>

			<?php 
				$query = $con->prepare("select user_id, username, email, joined, active from users where banned = 1 limit ?");
				$query->bind_param("s", $numUsers);
				$query->execute();
				$query->bind_result($user_id, $username, $email, $joined, $active);
				while($query->fetch()) { 
			?>
				<tr class="banned-user-row">
					<td data-id="<?php echo $user_id; ?>" class="userId"><?php echo $user_id; ?></td>
					<td data-busername="<?php echo $username; ?>"><?php echo $username; ?></td>
					<td><?php echo $email; ?></td>
					<td><?php echo $joined; ?></td>
					<td><?php if($active == 1) echo $lang['yes']; else echo $lang['no']; ?></td>
					<td><input type="checkbox" class="banned-checker"></td>
				</tr>
			<?php } ?>

		</table>
		<?php 
			$query = $con->prepare("select count(*) from users where banned = 1");
			$query->execute();
			$query->bind_result($usersNum);
			$query->fetch();
			$query->close();

			$pages = ceil($usersNum / $numUsers);
			if($pages > 1){ ?>
			<div class="banned-pagination">
			<?php for($i = 1; $i <= $pages; $i++){ ?>
			<label <?php if($i === 1) echo "class='activePage'";?>><?php echo $i; ?></label>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</div>

</body>
</html>