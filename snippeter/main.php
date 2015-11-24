<?php

    session_start();

    include 'functions.php';
    include 'database/connect.php';
    include 'langCheck.php';
   
    protect();
    user_counter();

    $query = $con->prepare("select user_id from users where username = ?");
    $query->bind_param("s", $_SESSION['user']);
    $query->execute();
    $query->bind_result($user_id);
    $query->fetch();
    $query->close();

    $availableTags = array();

    $query = $con->prepare("select distinct tags from tags_snippets where user_id = ?");
    $query->bind_param("s", $user_id);
    $query->execute();
    $query->bind_result($tags);
    while($query->fetch()){
        $availableTags[] = "'" . $tags . "'";
    }
    $query->close();

    $gNames = array();
    $gIds = array();
    $query = $con->prepare("select id, name from groups where user_id = ?");
    $query->bind_param("s", $user_id);
    $query->execute();
    $query->bind_result($i, $n);
    while($query->fetch()){
        array_push($gIds, $i);
        array_push($gNames, $n);
    }
    $query->close();

?>
<!DOCTYPE html>
<html style="background:white !important;">
<head>
	<title><?php echo $lang['pageTitle']; ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Source+Code+Pro' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Inconsolata' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="prettify/prettify.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href="css/jquery.tagit.css" rel="stylesheet" type="text/css">
    <link href="css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script> 
    <script src="prettify/prettify.js"></script>
    <script src="jquery.clipboard.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/tag-it.js" type="text/javascript"></script>
    <script src="js/script.js" type="text/javascript"></script>
	<script>
        $(function(){
            $('#myTags').tagit({
                availableTags: [<?php echo implode(", ", $availableTags); ?>],
                placeholderText : "<?php echo $lang['snippetTags']; ?>"
            });
        });
    </script>

</head>
<body onload="prettyPrint()" style="background:white !important;">
<label hidden id="langHolder"><?php if(isset($_SESSION['lang'])) echo $_SESSION['lang'];  else echo "en";?></label>
<label id="sitePath-holder" hidden><?php echo $pageRoot; ?></label>
    <div class="blur full full1" style="display:none;"></div>
    <div class="add-snippet-window full" style="display:none;">
        <input type="text" placeholder="<?php echo $lang['name'] ?>" name="name" id="name">
        <label data-id="" id="groupSelect"><?php echo $lang['selectGroup']; ?></label>
        <ul class="groupDropDown">
            <?php
                for($i=0;$i<sizeof($gNames);$i++){ ?>
                <li id="<?php echo $gIds[$i]; ?>"><?php echo $gNames[$i]; ?></li>
            <?php } ?>
        </ul><br>
        <textarea placeholder="<?php echo $lang['description'] ?>" name="description" id="description"></textarea><br>
        <textarea placeholder="<?php echo $lang['snippet'] ?>" name="snippet" id="snippetArea"></textarea>
        <input type="text" name="tags" id="myTags">
        
        
        <label id="save-snippet"><?php echo $lang['save']; ?></label>
        <label id="snippet-cancel"><?php echo $lang['cancel']; ?></label>
        <label id="snippet-error"></label>
        <label hidden data-type="save" class="check-label"></label>
        <label hidden class="id-holder"></label>
    </div>

    <form class="sublime-snippet-window" method="post" action="sublime-snippet.php" style="display:none;">
        <input type="text" name="tabTrigger" id="sublime-snippet-input" placeholder="<?php echo $lang['tabTrigger']; ?>">
        <input type="submit" id="submit-sublime-snippet" value="<?php echo $lang['download']; ?>">
        <label id="sublime-snippet-cancel"><?php echo $lang['cancel']; ?></label>
        <label id="sublime-instructions"><span style="font-weight: bold;"><?php echo $lang['instructions']; ?>: </span><?php echo $lang['smallInstructions']; ?></label>
        <textarea id="sublime-code" type="text" name="code" hidden></textarea>
        <input id="sublime-title" type="text" name="title" hidden>
    </form>

    <div class="share-window" style="display:none;">
        <label><?php echo $lang['wantShareSnippet'] ?> <span id="share-option" style="background-color: #27AE60"><?php echo $lang['yes']; ?></span></label>
        <label id="share-close">X</label>
        <input type="text" id="share-link">
    </div>

    <div class="snippet-option-wrap">
        <label id="share-label" class="snippet-icons" title="<?php echo $lang['snippetPrivate'] ?>"><?php echo $lang['private']; ?></label>
        <label id="sublime-label" class="snippet-icons" title="<?php echo $lang['exportAsSublime'] ?>">Sublime Text</label>
        <label id="code-label" class="snippet-icons" title="<?php echo $lang['rawCode']; ?>">&lt;/<?php echo $lang['code']; ?>&gt;</label>
        <label id="copy-label" class="snippet-icons" title="<?php echo $lang['copyClipboard']; ?>"><?php echo $lang['copy']; ?></label>
    </div>

    <form id="upload-form" method="post" action="import.php" enctype="multipart/form-data" style="display:none;">
        <label id="upload-message"><?php echo $lang['selectJson']; ?></label>
        <input type="file" name="file">
        <label id="upload-cancel"><?php echo $lang['cancel']; ?></label>
        <input type="submit" value="<?php echo $lang['import']; ?>" id="upload-import">
    </form>

    <div class="add-group">
        <form id="addGroupForm" action="add-group.php" method="post">
            <input type="text" name="name" placeholder="<?php echo $lang['groupName']; ?>">
            <input id="addGroupSubmit" type="submit" value="<?php echo $lang['save']; ?>">
            <label id="addGroupCancel">X</label>
        </form>

    </div>
    <label id="addGroupError"></label>

    <div class="settings-form" style="display:none;">
        <div class="settings-sidebar">
            <ul>
                <li class="setting-active"><?php echo $lang['mainSettings']; ?></li>
                <li><?php echo $lang['email']; ?></li>
                <li><?php echo $lang['password']; ?></li>
            </ul>
        </div>

        <?php
            $query = $con->prepare("select line_nums, font, size from users where user_id = ?");
            $query->bind_param("s", $user_id);
            $query->execute();
            $query->bind_result($value, $font, $size);
            $query->fetch();
            $query->close();
        ?>

        <div class="main-settings">
            <label class="line-numbers"><?php echo $lang['lineNumbers']; ?>: 
            <?php if($value == 1){ ?>
            <span style="background-color: <?php echo '#27ae60' ?>" id="line-num-span" data-value="1"><?php echo $lang['enabled']; ?></span>
            <?php }else{ ?>
            <span style="background-color: <?php echo '#e74c3c' ?>" id="line-num-span" data-value="0"><?php echo $lang['disabled']; ?></span>
            <?php } ?>
            </label>
            <div class="font-chooser"><label class="current-font" data-font="<?php echo $font; ?>"><?php echo $font; ?></label>
                <ul style="display: none">
                    <li style="font-family: 'Droid Sans'">Droid Sans</li>
                    <li style="font-family: 'Inconsolata'">Inconsolata</li>
                    <li style="font-family: 'Source Code Pro'">Source Code Pro</li>
                </ul>
            </div>
            <div class="font-size" data-size="<?php echo $size; ?>">
            <?php if($size === '70'){ ?>
                <label class="active-size"><?php echo $lang['small']; ?></label>
                <label><?php echo $lang['medium']; ?></label>
                <label><?php echo $lang['large']; ?></label>
            <?php }else if($size === '80'){ ?>
                <label><?php echo $lang['small']; ?></label>
                <label class="active-size"><?php echo $lang['medium']; ?></label>
                <label><?php echo $lang['large']; ?></label>
            <?php }else if($size === '90'){?>
                <label><?php echo $lang['small']; ?></label>
                <label><?php echo $lang['medium']; ?></label>
                <label class="active-size"><?php echo $lang['large']; ?></label>
            <?php } ?>
            </div>
        </div>

        <div class="mail-settings" style="display: none;">
            <input type="text" name="new-email" id="s-new-email" placeholder="<?php echo $lang['newEmail']; ?>"><br>
            <input type="text" name="rep-email" id="s-rep-email" placeholder="<?php echo $lang['repeatEmail']; ?>">
            <label id="s-notification"></label>
        </div>

        <div class="password-settings" style="display: none;">
            <input type="password" name="old-pass" id="s-old-pass" placeholder="<?php echo $lang['oldPassword']; ?>"><br>
            <input type="password" name="new-pass" id="s-new-pass" placeholder="<?php echo $lang['newPassword']; ?>"><br>
            <input type="password" name="rep-pass" id="s-rep-pass" placeholder="<?php echo $lang['repeatPassword']; ?>">
            <label id="s-notification-pass"></label>
        </div>
        <div class="main-setting-buttons">
            <label class="setting-ok" data-current="main"><?php echo $lang['apply']; ?></label>
            <label class="setting-close"><?php echo $lang['close']; ?></label>
        </div>
    </div>

    <div class="window">
        <div class="title-area">
            <label class="appTitle"><?php echo $lang['pageTitle']; ?></label>

            <input type="text" placeholder="<?php echo $lang['search']; ?>" class="search-bar">
            <label id="export-label" class="option-labels"><?php echo $lang['exportAll']; ?>
            
                <div class="export-options">
                    <ul>
                        <li id="export-all-sublime" title="<?php echo $lang['exportAllSublime']; ?>"><a href="export-sublime.php">Sublime text</a></li>
                        <li id="export-all-json" title="<?php echo $lang['exportAllJson']; ?>"><a href="export-json.php">JSON</a></li>
                        <li id="export-all-plain" title="<?php echo $lang['exportPlain']; ?>"><a href="export-plain.php"><?php echo $lang['plainText']; ?></a></li>
                    </ul>
                </div>
            </label>
            <label id="import-label" class="option-labels"><?php echo $lang['import']; ?></label>
            <label id="settings-label" class="option-labels"><?php echo $lang['settings']; ?></label>
            <a class="signout" href="logout.php"><?php echo $lang['signOut']; ?></a>
            <label class="username"><?php echo $_SESSION['user']; ?></label>
        </div>
        
        
        <div class="upperOptions">
            <label id="groupsTrigger" onclick="showGroups();" class="upperOptionsActive"><i class="fa fa-folder-open-o"></i> <?php echo $lang['groups']; ?></label><label id="tagsTrigger" onclick="showTags();"># <?php echo $lang["tags"]; ?></label>
        </div>
        <div class="tag-list" style="display:none;">
        <?php 
            $tagsArray = array();
            $query = $con->prepare("select distinct tags from tags_snippets where user_id = '$user_id' order by tags");
            
            $query->execute();
            $query->bind_result($tag);

            while($query->fetch()) {
                $tagsArray[] = $tag;
            } 
            $query->close(); 

            $countArray = array();
            $countSnippet = $con->prepare("select count(*) from tags_snippets where tags = ? and user_id = ?");
            foreach($tagsArray as $tag1){
                $countSnippet->bind_param("ss", $tag1, $user_id);
                $countSnippet->execute();
                $countSnippet->bind_result($count);
                $countSnippet->fetch();
                $countArray[] = $count;
            }
            
            $countSnippet->close(); 
            for($i = 0; $i < sizeof($tagsArray); $i++){ ?>
                <div class="tag1" onclick="findSnippets('<?php echo $tagsArray[$i]; ?>', 0);">
                    <label># <?php echo $tagsArray[$i]; ?></label><label class="count-tag"><?php echo $countArray[$i]; ?></label>
                </div>
        <?php } ?>
            
        </div>
        <div class="snippets" style="display: none;">
                
        </div>

        <div class="groups">
        <?php
            $groupsArray = array();
            $groupIds = array();
            $query = $con->prepare("select id, name from groups where user_id = ?");
            $query->bind_param("s", $user_id);
            $query->execute();
            $query->bind_result($ids, $groupNames);
            while($query->fetch()){
                array_push($groupIds, $ids);
                array_push($groupsArray, $groupNames);
            }
            $query->close();

            $countGroups = array();
            $query = $con->prepare("select * from snippets where group_id in (select id from groups where name = ?)");
            for($i = 0; $i < sizeof($groupsArray); $i++){  
                $query->bind_param("s", $groupsArray[$i]);
                $query->execute();
                $query->store_result();
                $n = $query->num_rows;  
                array_push($countGroups, $n);
            }
            $query->close();

            for($i = 0; $i < sizeof($groupsArray); $i++){
        ?>
            <div class="group" onclick="findSnippetsFromGroups('<?php echo $groupIds[$i]; ?>', 0);">
                <label class="groupName"><i class="fa fa-folder-open-o"></i> <?php echo $groupsArray[$i]; ?></label><label><i data-id="<?php echo $groupIds[$i]; ?>" class="fa fa-trash-o trash-over group-delete"></i></label><label class="count-tag-group"><?php echo $countGroups[$i]; ?></label>
            </div>
        <?php } ?>
        </div>

        <div class="bottom-options">
        <div title="<?php echo $lang['addNewSnippet']; ?>" class="bottom-add">+</div>
            <div title="<?php echo $lang['addGroup']; ?>" class="bottom-addGroup"><i class="fa fa-folder-open-o"></i></div>
           
        </div>

        <pre class="prettyprint code <?php if($value === 1) echo 'linenums';?>" style="font-family: <?php echo $font; ?>; font-size: <?php echo $size; ?>%;"></pre>
        <pre hidden class="raw-code"></pre>

        <div class="details-window-top">
            <label id="detail-title"></label>
            <label id="date-label"> - <?php echo $lang['created']; ?> (2013-03-04) </label>
            <img id="details-button" class="arrow" src="img/arrow.png" title="<?php echo $lang['showMoreDetails']; ?>">
        </div>
        <div class="details-window-under">
            <label><?php echo $lang['description']; ?>:</label><br>
            <label id="detail-desc"></label><br>
            <label id="detail-tags"></label>
        </div>

    </div>

</body>
</html>
