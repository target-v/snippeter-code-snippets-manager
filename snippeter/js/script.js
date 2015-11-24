window.lang = "";
window.tagTemp = "";
window.positionCheck = "groups";

$(document).ready(function(){

	$(".flagsWrap img").click(function(){
		$.post("changeLang.php", {"lang" : $(this).attr("id")}, function(){
			location.reload();
		});
	});

	$(".export-options ul li").css("width", ($("#export-label").width() + 20));

	$.post("lang/"+$("#langHolder").text()+".php", {"lang" : "true"}, function(data){
		lang = data;
	}, "json");
});

$(document).ready(function(){

	$(".login-errors").hide();

	$(".group-delete").click(function(e){
		e.stopPropagation();
		var t = $(this);
		var id = t.data("id");

		$.post("delete-group.php", {"id" : id}, function(data){
			if(data == "ok"){
				window.location.reload();
			}
		});
	});

	$("#groupSelect").click(function(){
		$(this).css("border-radius",  "5px 5px 0 0");
		$(".groupDropDown").fadeIn(200);
	});

	$(".groupDropDown li").click(function(){
		var t = $(this);
		$("#groupSelect").css("border-radius", "5px");
		$("#groupSelect").attr("data-id", t.attr("id"));
		$("#groupSelect").text(t.text());
		$(".groupDropDown").fadeOut(200);
	});

	$("#submit-button-login").click(function(){

		$(".login-form").submit(function(){
			return false;	
		});

		$.post("login-data.php", {
			'username' : $("#input-username").val(),
			'password' : $("#input-password").val(),
			'remember-me' : $("#input-remember").val()
		}, function(data){
			if(data == 'ok'){
				window.location.href = "main.php";
			}else{
				$(".login-errors").fadeIn(300);
				$(".login-errors").html(data);
			}
			
		});
	});

	$(".bottom-addGroup").click(function(){
		$(".add-group").fadeIn(200);
	});

	$("#addGroupCancel").click(function(){
		$("#addGroupError").fadeOut(200);
		$("#addGroupError").html("");
		$(".add-group").fadeOut(200);
		$(".blur").fadeOut(200);
	});

	$(".error").hide();

	$("#submit-button").click(function(){
		$(".register-form").submit(function(){
			return false;	
		});

		$.post("register-data.php", {
			'username' : $("#username-register").val(),
			'email' : $("#email-register").val(),
			'password' : $("#password-register").val(),
			'rpassword' : $("#rpassword-register").val()
		}, function(data){
			$(".error").fadeIn(300);
			$(".error").html("");
			for(var i in data){
				$(".error").append(data[i] + "<br>");
			}		
		}, "json");
	});

	$(".reset-errors").hide();

	$("#reset-submit").click(function(){
		$(".reset-form").submit(function(){
			return false;	
		});

		$.post("reset-password.php", {'email' : $("#reset-email").val(), 'flag' : 'user'}, function(data){
			$(".reset-errors").fadeIn(300);
			$(".reset-errors").html("<label>"+data+"</label>");
		});
	});

	$("#addGroupSubmit").click(function(){
		$("#addGroupForm").ajaxForm({url : "add-group.php", type: "post", success: addGroupCallBack});

		function addGroupCallBack(data){
			if(data != "ok"){
				$("#addGroupError").html(data);
				$("#addGroupError").fadeIn(200);
			}else{
				window.location.reload();
			}
		}
	});

	var check = 0;
	window.tempSnippet = 0;
	$("#upload-import").click(function(){
		$('#upload-form').ajaxForm({url: 'import.php', type: 'post', beforeSubmit: showMessage,success: receiveData});
		function showMessage(){
			$("#upload-message").text(lang.importingCodeSnippets);
		}

        function receiveData(data){
            $("#upload-message").text(data);
            if(data == 'ok'){
            	setTimeout(function(){
	            	window.location.reload();
	            }, 2000);  
            }
        }
	});

	$(".settings-form").hide();

	$(".font-chooser").click(function(){
		$(".font-chooser ul").fadeIn(200);
		$(".font-chooser").css("background-color", "#2980b9");
	});

	$(".font-chooser ul li").click(function(e){
		e.stopPropagation();
		$(".setting-ok").text(lang.apply);
		$("#s-notification").text("");
		$("#s-notification-pass").text("");
		$(".font-chooser label").text($(this).text());
		$(".font-chooser label").css("font-family", $(this).text());
		$(".current-font").data("font", $(this).text());
		$(".font-chooser").css("background-color", "#3498db");
		$(".font-chooser ul").fadeOut(200);
	});

	$(".line-numbers span").click(function(){
		$(".setting-ok").text(lang.apply);
		$("#s-notification").text("");
		$("#s-notification-pass").text("");
		if($(".line-numbers span").css("background-color") == "rgb(39, 174, 96)"){
			$(".line-numbers span").text(lang.disabled);
			$(".line-numbers span").css("background-color", "#e74c3c");
			$("#line-num-span").data("value", 0);
		}else{
			$(".line-numbers span").text(lang.enabled);
			$(".line-numbers span").css("background-color", "#27ae60");
			$("#line-num-span").data("value", 1);
		}
	});

	$(".setting-ok").click(function(){
		if($(this).data("current") == 'main'){
			$.post("settings.php", {
				'line-nums' : $("#line-num-span").data('value'),
				'font' : $(".current-font").data('font'),
				'size' : $(".font-size").data('size'),
				'set' : '1'
			}, function(data){
				if(data == 'ok'){
					check = 1;
					$(".setting-ok").text(lang.applied);
				}else{
					// fail
				}
			});
		}else if($(this).data("current") == 'mail'){
			$.post("settings.php", {
				'new-mail' : $("#s-new-email").val(),
				'rep-mail' : $("#s-rep-email").val(),
				'set' : '2'
			}, function(data){
				if(data == 'ok'){
					$(".setting-ok").text(lang.applied);
					$("#s-notification").css("color", "#27ae60");
					$("#s-notification").text(lang.checkEmailDetails);
				}else{
					$("#s-notification").css("color", "#e74c3c");
					$("#s-notification").text(data);
				}
			});
		}else if($(this).data("current") == 'password'){
			$.post("settings.php", {
				'old-pass' : $("#s-old-pass").val(),
				'new-pass' : $("#s-new-pass").val(),
				'rep-pass' : $("#s-rep-pass").val(),
				'set' : '3'
			}, function(data){
				if(data == 'ok'){
					$(".setting-ok").text(lang.applied);
					$("#s-notification-pass").css("color", "#27ae60");
					$("#s-notification-pass").text(lang.passwordChanged);
				}else{
					$("#s-notification-pass").css("color", "#e74c3c");
					$("#s-notification-pass").text(data);
				}
			});
		}
		
	});

	$(".font-size label").click(function(){
		$(".setting-ok").text(lang.apply);
		$("#s-notification").text("");
		$("#s-notification-pass").text("");
		$(".font-size label").removeClass("active-size");
		$(this).addClass("active-size");
		if($(this).text() == lang.small){
			$(".font-size").data("size", '70');
		}else if($(this).text() == lang.medium){
			$(".font-size").data("size", '80');
		}else{
			$(".font-size").data("size", '90');
		}
	});

	$("#submit-sublime-snippet").click(function(){
		$(".sublime-snippet-window").fadeOut(300);
		$(".blur").fadeOut(300);
	});

	$("#upload-cancel").click(function(){
		$("#upload-form").fadeOut();
	});

	$("#upload-form").hide();

	$(".export-options ul").hide();

	$("#import-label").click(function(){
		$("#upload-form").fadeIn(300);
	});

	$("#export-label").mouseenter(function(){
		$("#export-label").css("background-color", "#438eca");
		$(".export-options ul").fadeIn(200);
	});

	$("#export-label").mouseleave(function(){
		$("#export-label").css("background-color", "transparent");
		$(".export-options ul").fadeOut(200);
	});

	$(".sublime-snippet-window").hide();

	$("#sublime-label").click(function(){
		$("#sublime-code").val($(".raw-code").html());
		$("#sublime-title").val($("#detail-title").text());
		$("#sublime-snippet-input").val("");
		$(".sublime-snippet-window").fadeIn(300);
		$(".blur").fadeIn(300);
	});

	$("#sublime-snippet-cancel").click(function(){
		$(".sublime-snippet-window").fadeOut(300);
		$(".blur").fadeOut(300);
	});

	$(".details-window-top").hide();

	$("#details-button").hide();
	$(".code").css("z-index", "-1");
	$(".snippet-icons").css("z-index", "0");

	$(".search-bar").keyup(function(){
		$.post("search.php", {text : $(".search-bar").val()}, function(data){
			if(data.trim() != "" || $(".search-bar").val() != ""){
				if(positionCheck == "tags"){
					$(".tag-list").hide("slide", { direction: "right" }, 300);
	        		$(".snippets").show("slide", { direction: "left" }, 300);
	        	}else if(positionCheck == "groups"){
	        		$(".groups").hide("slide", { direction: "right" }, 300);
	        		$(".snippets").show("slide", { direction: "left" }, 300);
	        	}

        		$(".snippets").html(data);
			}if($(".search-bar").val() == ""){
				if(positionCheck == "tags"){
					$(".snippets").hide("slide", { direction: "left" }, 300);
	    			$(".tag-list").show("slide", { direction: "right" }, 300);
	    		}else if(positionCheck == "groups"){
	    			$(".snippets").hide("slide", { direction: "left" }, 300);
	    			$(".groups").show("slide", { direction: "right" }, 300);
	    		}
			}
			else if(data.trim() == ""){
    			$(".snippets").html("<label class='no-results'>"+lang.noResults+"</label>");
			}
		});
	});

	$(".full").hide();
	$(".full1").hide();
	$(".snippet-group-option").hide();
	$(".bottom-add").click(function(event){
		$(".groupDropDown").hide();
		$("#groupSelect").css("border-radius", "5px");
        $("#save-snippet").html(lang.save);
		$(".check-label").data("type", "save");
		$("#name").val("");
		$("#description").val("");
		$("#snippetArea").val("");
		$("#myTags").tagit("removeAll");
		$(".full").fadeIn(300);
	});

	$("#snippet-cancel").click(function(){
		$(".full").fadeOut(300);
	});

	$("#snippet-error").hide();

	$("#save-snippet").click(function(){
		if($(".check-label").data('type') == 'save'){
			$.post("input-snippet.php", {'name' : $("#name").val(), 'description' : $("#description").val(),
			'snippet' : $("#snippetArea").val(), 'tags' : JSON.stringify($("#myTags").tagit("assignedTags")), 'flag' : false,
			"groups" : $("#groupSelect").attr("data-id")}, function(data){
					if(data == 'ok'){
						location.reload();
					}else{
						$("#snippet-error").html("");
						$("#snippet-error").fadeIn(300);
						$("#snippet-error").html(data);
					}
					
				});
		}else if($(".check-label").data('type') == 'update'){
			$.post("input-snippet.php", {'name' : $("#name").val(), 'description' : $("#description").val(),
			'snippet' : $("#snippetArea").val(), 'tags' : JSON.stringify($("#myTags").tagit("assignedTags")), 'flag' : true,
			'id' : $(".id-holder").val(), "groups" : $("#groupSelect").attr("data-id")}, function(data){
					if(data == 'ok'){
						window.location.reload();
					}else{
						$("#snippet-error").html("");
						$("#snippet-error").fadeIn(300);
						$("#snippet-error").html(data);
					}
				});
		}
	});

	$(document).on("click", ".snippet", function(){
		$(".snippet").removeClass("active");
		$(this).addClass("active");
	});


	

	$("#login-button").click(function(){
		$(".index-wrap").hide();
		$(".login-wrap").fadeIn(300);
	});

	$("#forgot-pass-link").click(function(){
		$(".index-wrap").hide();
		$(".login-wrap").hide();
		$(".reset-wrap").fadeIn(300);
	});

	$("#details-button").click(function(event){
		event.preventDefault();
		if ($(this).hasClass("isDown") ) {
			$(".details-window-under").animate({"top": "-60px", "opacity" : 0}, 500);			
			$(this).removeClass("isDown");
			$("#details-button").removeClass("box_rotate box_transition");
			$("#details-button").prop("title", lang.showMoreDetails);
		} else {
			$(".details-window-under").animate({"top" : "90px", "opacity" : 1}, 500);	
			$(this).addClass("isDown");
			$("#details-button").addClass("box_rotate box_transition");
			$("#details-button").prop("title", lang.hideDetails);
		}
		return false;

	});

	$("#code-label").click(function(){
		var win=window.open('about:blank');
	    with(win.document)
	    {
	    	open();
	    	write($(".raw-code").html().replace(/\n/g,"<br>"));
	    	close();
	    }
	});

	$('#copy-label').clipboard({
	path: 'jquery.clipboard.swf', copy: function() {
			$("#copy-label").text(lang.copied);
			$("#copy-label").css("right", "60px");
			return $(".raw-code").text();
		}
	});
		
	$(document).on("keydown", function(e) { 
	    if (e.keyCode === 114 || (e.ctrlKey && e.keyCode === 70)) {
			$(".search-bar").focus();
			e.preventDefault();
		}

		if(e.keyCode === 13){
			$(".snippets div").first().trigger("click");
		}
	});

	$(".settings-sidebar ul li").click(function(){
		$(".setting-ok").text(lang.apply);
		$("#s-notification").text("");
		$("#s-notification-pass").text("");
		$(".settings-sidebar ul li").removeClass("setting-active");
		$(this).addClass("setting-active");

		if($(this).text() == lang.mainSettings){
			$(".main-settings").show();
			$(".mail-settings").hide();
			$(".password-settings").hide();
			$(".setting-ok").data("current", "main");
		}else if($(this).text() == lang.email){
			$(".main-settings").hide();
			$(".mail-settings").show();
			$(".password-settings").hide();
			$(".setting-ok").data("current", "mail");
		}else if($(this).text() == lang.password){
			$(".main-settings").hide();
			$(".mail-settings").hide();
			$(".password-settings").show();
			$(".setting-ok").data("current", "password");
		}
	});

	$("#settings-label").click(function(){
		$(".settings-form").fadeIn(300);
		$(".blur").fadeIn(300);
	});

	$(".setting-close").click(function(){
		if(check == 1) location.reload();
		else{
			$(".settings-form").fadeOut(300);
			$(".blur").fadeOut(300);
		}
	});

	$(".share-window").hide();

	$("#share-label").click(function(){
		$(".share-window").fadeIn(300);
	});

	$("#share-close").click(function(){
		$(".share-window").fadeOut(300);
	});

	$("#share-option").click(function(){
		if($(this).text() == lang.yes){
			$(this).text(lang.no);
			$(this).css("background-color", "#E74C3C");
			$("#share-link").prop('disabled', false);
			$("#share-link").addClass("active-share");
			$("#share-link").removeClass("inactive-share");
			$("#share-label").text(lang.public);
			$("#share-label").prop('title', lang.snippetPublic);
			$.post("share.php", {'value' : 1, 'id' : tempSnippet});
		}else{
			$(this).text(lang.yes);
			$(this).css("background-color", "#27AE60");
			$("#share-link").prop('disabled', true);
			$("#share-link").removeClass("active-share");
			$("#share-link").addClass("inactive-share");
			$("#share-label").text(lang.private);
			$("#share-label").prop('title',lang.snippetPrivate);
			$.post("share.php", {'value' : 0, 'id' : tempSnippet});
		}
	});
	
});

function showTags(){
	$("#groupsTrigger").removeClass("upperOptionsActive");
	$("#tagsTrigger").addClass("upperOptionsActive");
	positionCheck = "tags";
	$(".tag-list").fadeIn(200);
	$(".snippets").fadeOut();
	$(".groups").fadeOut();
}

function showGroups(){
	$("#groupsTrigger").addClass("upperOptionsActive");
	$("#tagsTrigger").removeClass("upperOptionsActive");
	positionCheck = "groups";
	$(".tag-list").fadeOut();
	$(".snippets").fadeOut();
	$(".groups").fadeIn(200);
}

function findSnippetsFromGroups(id, u){
	$("#copy-label").text(lang.copy);
	$.post("find-snippetsGroup.php", {'groupId' : id}, function(data){

		$(".groups").hide("slide", { direction: "right" }, 300);
        $(".snippets").show("slide", { direction: "left" }, 300);

		$(".snippets").html("");
		$(".snippets").append("<div onclick='goBackGroup();' class='back'>< "+lang.back+"</div>");

		for(var i in data.title){
			$(".snippets").append("<div onclick='if (event.target === this) getSnippet("+data.snippetId[i]+");' data-snippetId="+data.snippetId[i]+" class='snippet'><p onclick='if (event.target === this) getSnippet("+data.snippetId[i]+");'>@ "+data.title[i]+"</p><label onclick='removeSnippet("+data.snippetId[i]+");'><i class='fa fa-trash-o'></i></label><label onclick='editSnippet("+data.snippetId[i]+");'><i class='fa fa-pencil-square-o'></i></label></div>");
		}
		
		if(u == true){
			$(".snippets div:nth-child(2)").addClass("active");
		}
		
	}, "json");
}

function removeSnippet(id){
	$.post("remove-snippet.php", {id : id}, function(message){
		if(message == 'ok'){
			location.reload();
		}
	});
}

function findSnippets(tag, u){
	$("#copy-label").text(lang.copy);
	$.post("find-snippets.php", {'tag' : tag}, function(data){

		$(".tag-list").hide("slide", { direction: "right" }, 300);
        $(".snippets").show("slide", { direction: "left" }, 300);

		$(".snippets").html("");
		$(".snippets").append("<div onclick='goBack();' class='back'>< "+lang.back+"</div>");

		for(var i in data.title){
			$(".snippets").append("<div onclick='if (event.target === this) getSnippet("+data.snippetId[i]+");' data-snippetId="+data.snippetId[i]+" class='snippet'><p onclick='if (event.target === this) getSnippet("+data.snippetId[i]+");'>@ "+data.title[i]+"</p><label onclick='removeSnippet("+data.snippetId[i]+");'><i class='fa fa-trash-o'></i></label><label onclick='editSnippet("+data.snippetId[i]+");'><i class='fa fa-pencil-square-o'></i></label></div>");
		}
		
		if(u == true){
			$(".snippets div:nth-child(2)").addClass("active");
		}
		
	}, "json");

	tagTemp = tag;

	
		
}

function getSnippet(id){
	$("#copy-label").text(lang.copy);
	$("#copy-label").css("right", "65px");
	getDetails(id);
	$("#details-button").show();
	$(".details-window-top").show();
	$(".snippet-option-wrap").css("z-index", "1");
	$.post("get-snippet.php", {'id' : id, 'flag' : true}, function(data){
		$(".code").html(data);
		$(".raw-code").html(data);

		$('.prettyprinted').removeClass('prettyprinted');
		
		prettyPrint();
		var linenums = $(".linenums > li").length / 100;
		var temp = 10*parseInt((linenums-2)) + 30;
		if(linenums > 2)
		$(".prettyprint ol.linenums > li").css("left", temp+"px");

		$(".code").css("z-index", "0");
		$(".snippet-icons").css("z-index", "1");
	});
}

function getDetails(id){
	$("#copy-label").text(lang.copy);
	$("#copy-label").css("right", "65px");
	$.post("get-details.php", {'id' : id}, function(data){
		$("#detail-title").html(data.title);
		$("#detail-desc").html(data.description);
		$("#detail-tags").html(data.tags);
		$("#date-label").html("- "+lang.created+" ("+data.date+") ");
		tempSnippet = data.idSnippet;
		$("#share-link").val($("#sitePath-holder").text()+"/public.php?id="+data.idSnippet);
		if(data.public == 0){
			$("#share-option").text(lang.yes);
			$("#share-option").css("background-color", "#27AE60");
			$("#share-label").text(lang.private);
			$("#share-label").prop('title', lang.snippetPrivate);
			$("#share-link").prop('disabled', true);
			$("#share-link").removeClass("active-share");
			$("#share-link").addClass("inactive-share");
		}else if(data.public == 1){
			$("#share-option").text(lang.no);
			$("#share-option").css("background-color", "#E74C3C");
			$("#share-label").text(lang.public);
			$("#share-label").prop('title', lang.snippetPublic);
			$("#share-link").prop('disabled', false);
			$("#share-link").addClass("active-share");
			$("#share-link").removeClass("inactive-share");
		}
	}, "json");
}

function goBack(){
	$(".snippets").hide("slide", { direction: "left" }, 300);
    $(".tag-list").show("slide", { direction: "right" }, 300);
}

function goBackGroup(){
	$(".snippets").hide("slide", { direction: "left" }, 300);
    $(".groups").show("slide", { direction: "right" }, 300);
}

function editSnippet(id){
	$("#snippet-error").hide();
	$("#snippet-error").html("");

	$("#copy-label").text(lang.copy);
	$("#copy-label").css("right", "65px");
	$("#name").val("");
	$("#description").val("");
	$("#snippetArea").val("");
	$("#myTags").tagit("removeAll");

	$.post("get-snippet.php", {'id' : id, 'flag' : false}, function(data){
		$("#name").val(data.title);
		$("#description").val(data.description);
		$("#snippetArea").val(data.snippet);
	}, "json");

	$.post("get-group.php", {"id" : id}, function(data){
		if(!data.id){
			$("#groupSelect").attr("data-id", data.id);
			$("#groupSelect").text(data.name);
		}else{
			$("#groupSelect").attr("data-id", null);
			$("#groupSelect").text(lang["groupSelect"]);
		}
			
	}, "json");

	$.post("get-tags.php", {id : id}, function(tags){

		for(var i in tags){
			$("#myTags").tagit("createTag", tags[i].toString());
		}

		$(".id-holder").val(id);
		$("#save-snippet").html(lang.update);
		$(".check-label").data("type", "update");

		$(".full").fadeIn(300);
	}, "json");
}