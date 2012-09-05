<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
<head>
  <link rel="stylesheet" id="color-style-css" href="http://the-darkhand.com/wordpress/wp-content/themes/DDH_THEME/styles/ddh-blue.css" type="text/css" media="screen">
  <?php $this->RenderAsset('Head'); ?>
  <?php
    /* Short and sweet */
    require( TEMPLATEPATH . '/header-vanilla.php' );
  ?>


<!-- old vanilla header </head>
<body id="<?php echo $BodyIdentifier; ?>" class="<?php echo $this->CssClass; ?>">
  <div id="header-stripe"></div> -->


  <!-- <div id="wpheader">
      <div id="header-content">
          <div id="wplogo">
            <a href="/" title="Darkhand"><img src="http://the-darkhand.com/wordpress/wp-content/uploads/2012/03/darkhandlogo2.png" title="DDH" alt="DDH"></a>
          </div>
          <div id="wpmenu-wrap">
            <div class="wpmenu-main-menu-container">
              <ul id="wpmenu" class="wpmenu level0">
                <li class="wpmenu-item menu-item-type-post_type menu-item-object-page menu-item-11"><a href="/" class="level0">Home</a></li>
                <li class="wpmenu-item menu-item-type-custom menu-item-object-custom menu-item-19"><a href="/forums/" class="level0">Forums</a></li>
                <li class="wpmenu-item menu-item-type-post_type menu-item-object-page menu-item-18"><a href="/about-us/" class="level0">About Us</a></li>
              </ul>
            </div>
          </div>
      </div>
  </div> -->
   <div id="Frame">
      <div id="Head">
         <div class="Menu">
				<h1><a class="Title" href="<?php echo Url('/'); ?>"><span><?php echo Gdn_Theme::Logo(); ?></span></a></h1>
            <?php
			      $Session = Gdn::Session();
					if ($this->Menu) {
						$this->Menu->AddLink('Dashboard', T('Dashboard'), '/dashboard/settings', array('Garden.Settings.Manage'));
						// $this->Menu->AddLink('Dashboard', T('Users'), '/user/browse', array('Garden.Users.Add', 'Garden.Users.Edit', 'Garden.Users.Delete'));
						$this->Menu->AddLink('Activity', T('Activity'), '/activity');
			         $Authenticator = Gdn::Authenticator();
						if ($Session->IsValid()) {
							$Name = $Session->User->Name;
							$CountNotifications = $Session->User->CountNotifications;
							if (is_numeric($CountNotifications) && $CountNotifications > 0)
								$Name .= ' <span>'.$CountNotifications.'</span>';

							$this->Menu->AddLink('User', $Name, '/profile/{UserID}/{Username}', array('Garden.SignIn.Allow'), array('class' => 'UserNotifications'));
							$this->Menu->AddLink('SignOut', T('Sign Out'), $Authenticator->SignOutUrl(), FALSE, array('class' => 'NonTab SignOut'));
						} else {
							$Attribs = array();
							if (C('Garden.SignIn.Popup') && strpos(Gdn::Request()->Url(), 'entry') === FALSE)
								$Attribs['class'] = 'SignInPopup';

							$this->Menu->AddLink('Entry', T('Sign In'), $Authenticator->SignInUrl($this->SelfUrl), FALSE, array('class' => 'NonTab'), $Attribs);
						}
						echo $this->Menu->ToString();
					}
				?>
            <div class="Search"><?php
					$Form = Gdn::Factory('Form');
					$Form->InputPrefix = '';
					echo
						$Form->Open(array('action' => Url('/search'), 'method' => 'get')),
						$Form->TextBox('Search'),
						$Form->Button('Go', array('Name' => '')),
						$Form->Close();
				?></div>
         </div>
      </div>
      <div id="Body">
         <div id="Content"><?php $this->RenderAsset('Content'); ?></div>
         <div id="Panel"><?php $this->RenderAsset('Panel'); ?></div>
      </div>
      <div id="Foot">
			<?php
				$this->RenderAsset('Foot');
				echo Wrap(Anchor(T('Powered by Vanilla'), C('Garden.VanillaUrl')), 'div');
			?>
			<div id="ThemeCredit">Theme Darkhand by <a href="http://winefolly.com">Wine Folly</a></div>
	  </div>
   </div>
	<?php $this->FireEvent('AfterBody'); ?>
</body>
</html>
