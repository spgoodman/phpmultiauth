<?php
/*

phpMultiAuth v1.05

Copyright (C) 2004-2005  Steve Goodman

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Steve Goodman Contact Email: steve@goodman.net


*/


// The directory the includes including this one are in. Do not change
$settings['includedir'] = dirname(__FILE__);

// Load settings
if (file_exists($settings['includedir'] . '/config.inc.php')) {
	require($settings['includedir'] . '/config.inc.php');
	} else {
	die('Config file has not been generated. Use create_config.inc.php or read config.inc.php.example for ideas');
	}
// Load Functions
require($settings['includedir'] . '/functions.inc.php');

// Interactive Components

// Login dialogues

if (!isset($SECURE))
		{
		if ($settings['authdefault']=="SECURE")
			{
			$SECURE=TRUE;
			} else {
			$SECURE=FALSE;
			}
		}
if ($SECURE==FALSE)
	{
	// Check if user is already logged in, but has come back to an unsecure page. This can be used for extra content for secure users on a single page, and for logout to ensure page
	session_start($settings['session_prefix']);
	if (get_session("authenticated")=="TRUE") $SECURE=TRUE;
	}
if ($SECURE==TRUE)
	{
	if ($settings['method']=="HTTPAUTH")
		{
		// HTTP Authentication. If set will require standard HTTP authentication here
		if ($_SERVER["PHP_AUTH_USER"] && $_SERVER["PHP_AUTH_PW"] && ereg("^Basic ", $_SERVER["HTTP_AUTHORIZATION"]) )
			{
   			list($_SERVER["PHP_AUTH_USER"], $_SERVER["PHP_AUTH_PW"]) = explode(":", base64_decode(substr($_SERVER["HTTP_AUTHORIZATION"], 6)) );
			}
   		$authenticated = FALSE;
		if ($_SERVER["PHP_AUTH_USER"] || $_SERVER["PHP_AUTH_PW"])
			{
			$authenticated = login($_SERVER["PHP_AUTH_USER"],$_SERVER["PHP_AUTH_PW"]);
   			}
		if(!$authenticated) 
			{
			header('WWW-Authenticate: Basic realm="' . $settings['realm'] . '"');
   			if (ereg("Microsoft", $_SERVER["SERVER_SOFTWARE"]))
				{
   				header("Status: 401 Unauthorized");
   				} else {
   				header("HTTP/1.0 401 Unauthorized");				
   				}
			echo file_get_contents($settings['includedir'] . '/templates/httpauth.html');
			// Crucially, EXIT here!!! This prevents the content from being viewed.
			exit;
    		}
		} elseif ($settings['method']=="SESSION") {
		// SESSION Authentication. if set will use sessions here and login html page
		// Init authentication variable if it's not already
		session_start($settings['session_prefix']);
		
		if (get_session("authenticated")==FALSE) set_session("authenticated","FALSE");
		if (get_session("authenticated")=="FALSE")
			{
			// Not logged in. Either generate login page or process login request here.
			$error=NULL;
			if (isset($_POST[$settings['session_prefix'] . '_loginflag']))
				{
				// The login flag has been set, therefore the user is attempting to login
				
				if (!isset($_POST['username'])) $error.='<li>Username field missing from form</li>';
				if (!isset($_POST['password'])) $error.='<li>Password field missing from form</li>';
				if ($_POST['username']=='') $error.='<li>Missing Username</li>';
				if ($_POST['password']=='') $error.='<li>Missing Password</li>';
				if (!$error)
					{
					if (login($_POST['username'],$_POST['password'])==TRUE)
						{
						set_session("authenticated","TRUE");
						set_session("username",$_POST['username']);
						if ($settings['register_password']==TRUE) set_session("password",$_POST['password']);
						set_session("authentication_type",$information['type']);
						set_session("authentication_server",$information['server']);
						set_session("surname",$information['surname']);
						set_session("department",$information['department']);
						set_session("description",$information['description']);
						set_session("displayname",$information['displayname']);
						set_session("givenname",$information['givenname']);
						set_session("mail",$information['mail']);
						set_session("office",$information['office']);
						set_session("title",$information['title']);
						set_session("telephonenumber",$information['telephonenumber']);
						session_write_close();
						header('Location: ' . $PHP_SELF);
						exit;
						} else {
						$error="<li>The Username or Password specified does not appear to be correct or you have not been given access to this resource</li>";
						}
					}
				}
			$login=file_get_contents($settings['includedir'] . '/templates/session_login.html');
			$login=str_replace('_loginflag',$settings['session_prefix'] . '_loginflag',$login);
			if ($error) 
				{
				$login=str_replace('{ERROR}','The following Error(s) have been found:<br>' . $error,$login);
				} else {
				$login=str_replace('{ERROR}','',$login);
				}
			if (isset($_POST['username'])) 
				{
				$login=str_replace('username_marker',$_POST['username'],$login);
				} else {
				$login=str_replace('username_marker','',$login);
				}
			echo $login;
			// Must EXIT below else the protected content shows
			exit;
			} elseif (get_session("authenticated")=="TRUE") {
			// The user has already been authenticated. Only thing to check for here is a logout request
			if (isset($_GET[$settings['session_logout_get_var']]))
				{
				if ($_GET[$settings['session_logout_get_var']]=="TRUE")
					{
					// Logout request
					$_SESSION = array();
					if (isset($_COOKIE[session_name($settings['session_prefix'])])) 
						{
	   					setcookie(session_name($settings['session_prefix']), '', time()-42000, '/');
						}
					session_destroy();
					header('Location: ' . $PHP_SELF);
					exit;
					}
			
				}
			}
		}
	}



?>