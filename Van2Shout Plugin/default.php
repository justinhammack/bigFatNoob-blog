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

define('VAN2SHOUT_VERSION', '1.2');

// Define the plugin:
$PluginInfo['Van2Shout'] = array(
	'Name' => 'Van2Shout',
	'Description' => 'A simple shoutbox for vanilla2 with support for different groups and private messages',
	'Version' => '1.2',
	'Author' => "Caerostris",
	'AuthorEmail' => 'caerostris@gmail.com',
	'AuthorUrl' => 'http://caerostris.com',
		'SettingsPermission' => array('Plugins.Van2Shout.View', 'Plugins.Van2Shout.Post', 'Plugins.Van2Shout.Delete'),
		'RegisterPermissions' => array('Plugins.Van2Shout.View', 'Plugins.Van2Shout.Post', 'Plugins.Van2Shout.Delete'),
);

class Van2ShoutPlugin extends Gdn_Plugin {
	public function Base_Render_Before(&$Sender) {
		//Add link to shoutbox to navigation if user is allowed to view
		$Session = GDN::Session();
		if($Session->CheckPermission('Plugins.Van2Shout.View')) {
			$Sender->Menu->AddLink('Shoutbox', 'Shoutbox', 'discussions/shoutbox', array('Plugins.Van2Shout.View'), array('class' => 'Shoutbox'));
		}
	}

	public function DiscussionsController_Shoutbox_Create(&$Sender) {
		//Check if user is allowed to view
		$Session = GDN::Session();
		if(!$Session->CheckPermission('Plugins.Van2Shout.View')) {
			return;
		}

		//Display the delete icon?
		if($Session->CheckPermission('Plugins.Van2Shout.Delete')) {
			$Sender->AddDefinition('Van2ShoutDelete', 'true');
		}

		//Display fancy stuff
		$Sender->Title(T('Shoutbox'));
		$Sender->View = dirname(__FILE__).DS.'views'.DS.'discussionscontroller.php';
		$Sender->Render();
	}

	public function PluginController_Van2ShoutData_Create(&$Sender) {
		//Check if user is allowed to view
		$Session = GDN::Session();
			if(!$Session->CheckPermission('Plugins.Van2Shout.View')) {
			return;
		}


		//Displays the posts of the shoutbox

		include_once(dirname(__FILE__).DS.'controllers'.DS.'class.van2shoutdata.php');
		$Van2ShoutData = new Van2ShoutData($Sender);
		echo $Van2ShoutData->ToString();

	}

	public function Setup() {
		//I'd love to use GDN::Structure but this class does not support Auto_Increment
		$dblink = @mysql_connect(C('Database.Host'),C('Database.User'),C('Database.Password'), FALSE, 128);
		if(!$dblink)
			return false;

		@mysql_select_db(C('Database.Name'));
		$query = @mysql_query("CREATE TABLE IF NOT EXISTS GDN_Shoutbox (ID int(30) NOT NULL AUTO_INCREMENT COMMENT \"No questions about this? :P\" PRIMARY KEY, UserName varchar(50) NOT NULL COMMENT \"VarChar(50) is vanilla's max usernamelength\", PM varchar(50) NOT NULL COMMENT \"If the message is a PM, the username it is sent to will go here, so also VarChar(50)\", Content varchar(149) NOT NULL COMMENT \"Maxlength should be 148; 149 just to be sure...\") ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT=\"Stores the shoutbox posts from Van2Shout\" AUTO_INCREMENT=1;"); 
		if(!$query){ file_put_contents('/home/schwalb/sql.log', mysql_error());  return false; }
		mysql_close($query);
	}
}
