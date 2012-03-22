<?php if(!defined('APPLICATION')) exit();
//Copyright (c) 2010-2011 by Caerostris <caerostris@gmail.com>
//	 This file is part of Van2Shout.
//
//	 Van2Shout is free software: you can redistribute it and/or modify
//	 it under the terms of the GNU General Public License as published by
//	 the Free Software Foundation, either version 3 of the License, or
//	 (at your option) any later version.
//
//	 Van2Shout is distributed in the hope that it will be useful,
//	 but WITHOUT ANY WARRANTY; without even the implied warranty of
//	 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	 GNU General Public License for more details.
//
//	 You should have received a copy of the GNU General Public License
//	 along with Van2Shout.  If not, see <http://www.gnu.org/licenses/>.

//Override vanilla's default encoding UTF-8, with UTF-8 e.g. eblah² doesnt work (the ²)
header('Content-Type: text/html; charset=ISO-8859-15');
?>

<h4>Shout Box</h4>
<div id="van2shoutscroll">
	<ul id="shoutboxcontent">
	</ul>
</div>

<?php
	$Session = GDN::Session();
	if($Session->CheckPermission('Plugins.Van2Shout.Post')) {
		echo "<form action='javascript:SubmitMessage();'>\n<input type='text' style='width:90%;' name='shoutboxinput' id='shoutboxinput' onkeydown='checkLength();' />\n";
		echo "<input type='submit' value='POST' id='van2shoutsubmit' name='van2shoutsubmit' />\n</form>\n";
	}
?>

<script type="text/javascript">
	jQuery(document).ready(function($) { UpdateShoutbox(); });
	setInterval('UpdateShoutbox()', 5000);

	function UpdateShoutbox() {
		var obj = document.getElementById("van2shoutscroll");
	        if(obj.scrollHeight - obj.scrollTop == 500) { //the slider currently is at the bottom ==> make it stay there after adding new posts
	                var scrolldown = true;
	        } else {
	                var scrolldown = false;
	        }

		$.get(gdn.url('plugin/Van2ShoutData?postcount=50'), function(data) {
			var string = "";

			var array = data.split("\n");
			for(var key in array) {
				var unparsed = array[key];

				if(unparsed == "") { break; }
				//render PMs
				if(unparsed.indexOf('[!pmcontent!]') != -1) {
					var parsedArray = unparsed.split("[!pmcontent!]");
					var idArray = parsedArray[1].split("[!msgid!]");
					string = string + "<li><strong>" + DeleteMsg(idArray[1]) + "PM from <a href='" + gdn.url('profile/' + parsedArray[0]) + "' target='blank' >" + parsedArray[0] + "</a>: " + idArray[0] + "</strong></li>";
				} else if (unparsed.indexOf('[!pmtocontent!]') != -1) {
					var parsedArray = unparsed.split("[!pmtocontent!]");
					var idArray = parsedArray[1].split("[!msgid!]");
					string = string + "<li><strong>" + DeleteMsg(idArray[1]) + "PM to <a href='" + gdn.url('profile/' + parsedArray[0]) + "' target='blank' >" + parsedArray[0] + "</a>: " + idArray[0] + "</strong></li>";
				} else {
					var parsedArray = unparsed.split("[!content!]");
					var idArray = parsedArray[1].split("[!msgid!]");
					string = string + "<li><strong>" + DeleteMsg(idArray[1]) + "<a href='" + gdn.url('profile/' + parsedArray[0]) + "' target='blank' >" + parsedArray[0] + "</a>: " + idArray[0] + "</strong></li>";
				}
			}

			$("#shoutboxcontent").html(string);

			if(scrolldown == true) {
				//scroll down
				obj.scrollTop = 1000000;
			}
		});
	}

	function checkLength() {
		if($("#shoutboxinput").val().length > 148){
			$("#van2shoutsubmit").attr('disabled', 'disabled');
			$("#shoutboxinput").css("background-color", "red");
		} else {
			$("#van2shoutsubmit").removeAttr('disabled');
			$("#shoutboxinput").css("background-color", "white");
		}
	}


	function SubmitMessage() {
		$.get(gdn.url('plugin/Van2ShoutData?newpost=' + escape($("#shoutboxinput").val())), function(data) {});
		$("#shoutboxinput").val("");
		UpdateShoutbox();
	}

	//return html code of delete button
	function DeleteMsg(id) {
		var str = "";
		if(gdn.definition('Van2ShoutDelete') == "true") {
			str = "<img src='/forums/plugins/Van2Shout/img/del.png' onClick='DeletePost(\"" + id + "\");' /> ";
		} else {
			str = "";
		}
		return str;
	}

	function DeletePost(id) {
		$.get(gdn.url('plugin/Van2ShoutData&del=' + id), function(data) {});
		setTimeout(UpdateShoutbox, 10);
		alert("Message deleted");
	}

</script>

<style type="text/css">
	#van2shoutscroll {
		height:300px;
		overflow:auto;
	}
</style>
