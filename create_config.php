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


session_start();
function create_settings() {
?>
<p>This will produce a config.inc.php for you. You will need to cut and paste the results into a file editor and save to your phpMultiAuth directory.</p>
<h2>Settings section</h2>
<form method="post" action="">
  <table width="629" border="1">
    <tr>
      <td width="398">Authentication method </td>
      <td width="215"><select name="method" id="method">
        <option value="SESSION" selected>Session Based</option>
        <option value="HTTPAUTH">HTTP Based</option>
      </select></td>
    </tr>
    <tr>
      <td>HTTP Authentication Realm </td>
      <td><input name="httpauth_realm" type="text" id="httpauth_realm" value="phpMultiAuth"></td>
    </tr>
    <tr>
      <td>Internal Session Prefix</td>
      <td><input name="session_prefix" type="text" id="session_prefix" value="phpmultiauth_session"></td>
    </tr>
    <tr>
      <td>Encrypt session data (mcrypt <?php if (!function_exists(mcrypt_module_open)) echo 'NOT'; ?> found) </td>
      <td><select name="encrypt_session_data" id="encrypt_session_data">
        <option value="TRUE" selected>YES</option>
        <option value="FALSE">NO</option>
      </select></td>
    </tr>
    <tr>
      <td>Key to use for encrypting session data </td>
      <td><input name="encrypt_session_data_key" type="text" id="encrypt_session_data_key" value="378943hbdfnd9b.dsy32ybuFdjdfgbh4389wegfsd3o-336"></td>
    </tr>
    <tr>
      <td>Session logout GET variable (ie pageurl?logout=TRUE) </td>
      <td><input name="session_logout_get_var" type="text" id="session_logout_get_var" value="logout"></td>
    </tr>
    <tr>
      <td>Default page security. If set to insecure, each page that includes this will need $SECURE=TRUE set if it wishes to be secure. </td>
      <td><select name="authdefault" id="authdefault">
        <option value="SECURE" selected>Secure</option>
        <option value="INSECURE">Insecure</option>
      </select></td>
    </tr>
    <tr>
      <td>Escape username/password to ensure no dangerous shell commands can be included. This is worth keeping on. </td>
      <td><select name="escapeshellcmd" id="escapeshellcmd">
        <option value="TRUE" selected>Yes</option>
        <option value="FALSE">No</option>
                  </select></td>
    </tr>
    <tr>
      <td>Add slashes to prevent ' character from breaking the SQL. Worth keeping set on. </td>
      <td><select name="addslashes" id="addslashes">
        <option value="TRUE" selected>Yes</option>
        <option value="FALSE">No</option>
      </select></td>
    </tr>
    <tr>
      <td>Remove the @ sign and anything after - IE if someone logs in as username@domain.com it will only send the username through. </td>
      <td><select name="remove_at_and_anything_after" id="remove_at_and_anything_after">
        <option value="TRUE" selected>Yes</option>
        <option value="FALSE">No</option>
      </select></td>
    </tr>
    <tr>
      <td>Remote trailing dot and anything after. IE if Novell - type usernames such as username.ou.whatever are used only sends username </td>
      <td><select name="remove_trailing_dot_and_anything_after" id="remove_trailing_dot_and_anything_after">
        <option value="TRUE" selected>Yes</option>
        <option value="FALSE">No</option>
      </select></td>
    </tr>
    <tr>
      <td>Register logged in user's password in a session variable. Although this can be encrypted in the session (see above) this is a little dangerous. </td>
      <td><select name="register_password" id="register_password">
        <option value="TRUE">Yes</option>
        <option value="FALSE" selected>No</option>
                  </select></td>
    </tr>
    <tr>
      <td>Path to adodb directory, relative to this directory. Required if you wish to authenticate to database backend. </td>
      <td><input name="adodb_path" type="text" id="adodb_path" value="../adodb/"></td>
    </tr>
  </table>
  <input name="settings" type="hidden" id="settings" value="TRUE">  
<?php
}
function form_footer() {
?>
  <p>
    <input name="ldap" type="submit" id="ldap" value="Add LDAP Server">
    <input name="activedir" type="submit" id="activedir" value="Add Active Directory Server">
    <input name="nis" type="submit" id="nis" value="Add NIS server">
    </p>
  <p>
    <input name="web" type="submit" id="web" value="Add web server">
    <input name="cifs" type="submit" id="cifs" value="Add cifs/samba server">
      <input name="adodb" type="submit" id="cifs" value="Add database server">
  </p>
</form>
<?php
}

function outputsettings() {
	?>
<pre>
// Config.inc.php COPY FROM HERE
// SETTINGS
$settings['method']='<?php echo $_SESSION['method'] ?>';
$settings['httpauth_realm']='<?php echo $_SESSION['httpauth_realm'] ?>';
$settings['session_prefix']='<?php echo $_SESSION['session_prefix'] ?>';
$settings['encrypt_session_data']=<?php echo $_SESSION['encrypt_session_data'] ?>;
$settings['encrypt_session_data_key']='<?php echo $_SESSION['encrypt_session_data_key'] ?>';
$settings['session_logout_get_var']='<?php echo $_SESSION['session_logout_get_var'] ?>';
$settings['authdefault']='<?php echo $_SESSION['authdefault'] ?>';
$settings['escapeshellcmd']=<?php echo $_SESSION['escapeshellcmd'] ?>;
$settings['addslashes']=<?php echo $_SESSION['addslashes'] ?>;
$settings['remove_at_and_anything_after']=<?php echo $_SESSION['remove_at_and_anything_after'] ?>;
$settings['remove_trailing_dot_and_anything_after']=<?php echo $_SESSION['remove_trailing_dot_and_anything_after'] ?>;
$settings['register_password']=<?php echo $_SESSION['register_password'] ?>;
$settings['adodb_path']='<?php echo $_SESSION['adodb_path'] ?>';

// METHODS SECTION
<?php
if ($_SESSION['method_count']>0)
	{
	for ($i=1;$i<=$_SESSION['method_count'];$i++)
		{
		$m='method' . $i . '_';
		switch ($_SESSION[$m . 'type'])
			{
			case 'LDAP':
				?>
$authentication[<?php echo $i-1 ?>]['type']='LDAP';
$authentication[<?php echo $i-1 ?>]['server']='<?php echo $_SESSION[$m . 'server'] ?>';
$authentication[<?php echo $i-1 ?>]['base_dn']='<?php echo $_SESSION[$m . 'base_dn'] ?>';
$authentication[<?php echo $i-1 ?>]['enabled']=<?php echo $_SESSION[$m . 'enabled'] . ";\n" ?>

<?php
				break;
			case 'ACTIVEDIR':
			?>
$authentication[<?php echo $i-1 ?>]['type']='LDAP';
$authentication[<?php echo $i-1 ?>]['server']='<?php echo $_SESSION[$m . 'server'] ?>';
$authentication[<?php echo $i-1 ?>]['base_dn']='<?php echo $_SESSION[$m . 'base_dn'] ?>';
<?php if ($_SESSION[$m . 'attribute']) { ?>
$authentication[<?php echo $i-1 ?>]['attribute']='<?php echo $_SESSION[$m . 'attribute'] ?>';<?php } ?>
<?php if ($_SESSION[$m . 'attribute']) {?>

$authentication[<?php echo $i-1 ?>]['search_account']='<?php echo $_SESSION[$m . 'search_account'] ?>';
<?php } ?>
$authentication[<?php echo $i-1 ?>]['active_directory']=TRUE;
$authentication[<?php echo $i-1 ?>]['populate_session_data_from_ldap']=<?php echo $_SESSION[$m . 'populate_session_data_from_ldap'] ?>;
$authentication[<?php echo $i-1 ?>]['enabled']=<?php echo $_SESSION[$m . 'enabled'] . ";\n" ?>

<?php
				break;
			case 'NIS':
				?>
$authentication[<?php echo $i-1 ?>]['type']='NIS';
$authentication[<?php echo $i-1 ?>]['nis_domain']='<?php echo $_SESSION[$m . 'nis_domain'] ?>';
<?php if ($_SESSION[$m . 'ypmatch_command']) { ?>
$authentication[<?php echo $i-1 ?>]['ypmatch_command']='<?php echo $_SESSION[$m . 'ypmatch_command'] ?>';
<?php } ?>
$authentication[<?php echo $i-1 ?>]['enabled']=<?php echo $_SESSION[$m . 'enabled'] . ";\n" ?>

<?php
				break;
			case 'WEB';
			?>
$authentication[<?php echo $i-1 ?>]['type']='WEB';
$authentication[<?php echo $i-1 ?>]['path']='<?php echo $_SESSION[$m . 'path'] ?>';
$authentication[<?php echo $i-1 ?>]['enabled']=<?php echo $_SESSION[$m . 'enabled'] . ";\n" ?>

<?php
				break;
			case 'CIFS':
			?>
$authentication[<?php echo $i-1 ?>]['type']='CIFS';
$authentication[<?php echo $i-1 ?>]['share']='<?php echo $_SESSION[$m . 'share'] ?>';
$authentication[<?php echo $i-1 ?>]['server_ip']='<?php echo $_SESSION[$m . 'server_ip'] ?>';
$authentication[<?php echo $i-1 ?>]['domain']='<?php echo $_SESSION[$m . 'domain'] ?>';
$authentication[<?php echo $i-1 ?>]['file']='<?php echo $_SESSION[$m . 'file'] ?>';
$authentication[<?php echo $i-1 ?>]['smbclient']='<?php echo $_SESSION[$m . 'smbclient'] ?>';
$authentication[<?php echo $i-1 ?>]['enabled']=<?php echo $_SESSION[$m . 'enabled'] . ";\n" ?>

<?php
				break;
			case 'ADODB';
			?>
$authentication[<?php echo $i-1 ?>]['type']='ADODB';
$authentication[<?php echo $i-1 ?>]['database_server']='<?php echo $_SESSION[$m . 'database_server'] ?>';
$authentication[<?php echo $i-1 ?>]['database']='<?php echo $_SESSION[$m . 'database'] ?>';
$authentication[<?php echo $i-1 ?>]['table']='<?php echo $_SESSION[$m . 'table'] ?>';
$authentication[<?php echo $i-1 ?>]['encryption']='<?php echo $_SESSION[$m . 'table'] ?>';
$authentication[<?php echo $i-1 ?>]['enabled']=<?php echo $_SESSION[$m . 'enabled'] . ";\n" ?>

<?php
				break;
			}
		}
	}
?>
// End copy HERE
</pre>
<form name="form1" method="post" action="">
	<?php
}

function enterldap() {
?>

<form name="form1" method="post" action="">
  <table width="590" border="1">
    <tr>
      <td width="264">LDAP Server / port </td>
      <td width="310"><input name="server" type="text" id="server" value="ldapserver.company.com" size="35">
      <input name="port" type="text" id="port" value="389" size="8"></td>
    </tr>
    <tr>
      <td>Base DN for search </td>
      <td><input name="base_dn" type="text" id="base_dn" value="ou=People,dc=company,dc=com" size="50"></td>
    </tr>
    <tr>
      <td>Enabled</td>
      <td><select name="enabled" id="enabled">
        <option value="TRUE" selected>YES</option>
        <option value="FALSE">NO</option>
      </select></td>
    </tr>
  </table>
  <p>
    <input name="ldap" type="submit" id="ldap" value="Add">
  </p>
</form>
<?php
}
function enteractivedir()
{
?>
<form name="form1" method="post" action="">
  <table width="590" border="1">
    <tr>
      <td width="264">AD LDAP Server / port </td>
      <td width="310"><input name="server" type="text" id="server" value="activedirectory.company.com" size="35">
      <input name="port" type="text" id="port" value="389" size="8"></td>
    </tr>
    <tr>
      <td>Base DN for search </td>
      <td><input name="base_dn" type="text" id="base_dn" value="ou=User Account,dc=activedirectory,dc=company,dc=com" size="50"></td>
    </tr>
    <tr>
      <td>If required, group to search if user is a member of. Leave blank if not required.</td>
      <td><input name="attribute" type="text" id="attribute" value="CN=Staff Group,OU=Groups,DC=activedirectory,DC=company,DC=com" size="50"></td>
    </tr>
    <tr>
      <td>Directory search account for search active directory group memberships, used with above. </td>
      <td><input name="username" type="text" id="username" value="username@activedirectory.company.com" size="25">
          <input name="password" type="text" id="password" value="password" size="25"></td>
    </tr>
    <tr>
      <td>Populate session data from LDAP </td>
      <td><select name="populate_session_data_from_ldap" id="populate_session_data_from_ldap">
        <option value="TRUE" selected>YES</option>
        <option value="FALSE">NO</option>
      </select></td>
    </tr>
    <tr>
      <td>Enabled</td>
      <td><select name="enabled" id="enabled">
        <option value="TRUE" selected>YES</option>
        <option value="FALSE">NO</option>
      </select></td>
    </tr>
  </table>
  <p>
    <input name="activedir" type="submit" id="activedir" value="Add">
  </p>
</form>
<?php
}
function enternis() {
?>
<form name="form1" method="post" action="">
  <table width="590" border="1">
    <tr>
      <td width="264">NIS Domain </td>
      <td width="310"><input name="nis_domain" type="text" id="nis_domain" value="company.com" size="35">      </td>
    </tr>
    <tr>
      <td>If you are NOT using the PHP YP/NIS library, enter the path to ypmatch. It must be executable by the web server user. Otherwise, leave this blank</td>
      <td><input name="ypmatch_command" type="text" id="ypmatch_command" value="/usr/bin/ypmatch" size="30"></td>
    </tr>
    <tr>
      <td>Enabled</td>
      <td><select name="enabled" id="enabled">
        <option value="TRUE" selected>YES</option>
        <option value="FALSE">NO</option>
      </select></td>
    </tr>
  </table>
  <p>
    <input name="nis" type="submit" id="nis" value="Add">
  </p>
</form>
<?php
}
function enterweb() {
?>
<form name="form1" method="post" action="">
  <table width="590" border="1">
    <tr>
      <td>Web Server Type </td>
      <td><select name="w1" id="w1">
          <option value="http://" selected>http://</option>
          <option value="https://">https://</option>
      </select></td>
    </tr>
    <tr>
      <td width="264">Web Server hostname </td>
      <td width="310"><input name="w2" type="text" id="w2" value="www.company.com" size="30"></td>
    </tr>
    <tr>
      <td>Path including text file with contents 'OK' </td>
      <td><input name="w3" type="text" id="w3" value="/securearea/success.txt" size="40"></td>
    </tr>
    <tr>
      <td>Enabled</td>
      <td><select name="enabled" id="enabled">
        <option value="TRUE" selected>Yes</option>
        <option value="FALSE">No</option>
      </select></td>
    </tr>
  </table>
  <p>
    <input name="web" type="submit" id="nis" value="Add">
  </p>
</form>
<?php
}
function entercifs() {
?>
<form name="form1" method="post" action="">
  <table width="590" border="1">
    <tr>
      <td>NT / Samba Server Name </td>
      <td><input name="share1" type="text" id="share1" value="ntserver"></td>
    </tr>
    <tr>
      <td width="264">NT / Samba Server Share </td>
      <td width="310"><input name="share2" type="text" id="share2" value="share"></td>
    </tr>
    <tr>
      <td>NT / Samba Server IP Address </td>
      <td><input name="server_ip" type="text" id="server_ip" value="192.168.10.10"></td>
    </tr>
    <tr>
      <td>NT / Samba Server Domain Name </td>
      <td><input name="domain" type="text" id="domain" value="ntdomain"></td>
    </tr>
    <tr>
      <td>Path to text file with contents 'OK' </td>
      <td><input name="file" type="text" id="file" value="authentication.txt"></td>
    </tr>
    <tr>
      <td>Path to smbclient command. Must be executable by web server </td>
      <td><input name="smbclient" type="text" id="smbclient" value="/usr/local/bin/smbclient" size="30"></td>
    </tr>
    <tr>
      <td>Enabled</td>
      <td><select name="enabled" id="enabled">
        <option value="TRUE" selected>Yes</option>
        <option value="FALSE">No</option>
      </select></td>
    </tr>
  </table>
  <p>
    <input name="cifs" type="submit" id="nis" value="Add">
  </p>
</form>
<?php
}
function enteradodb() {
?>
<form name="form1" method="post" action="">
  <table width="590" border="1">
    <tr>
      <td>ADODB Database Type </td>
      <td><input name="database_server1" type="text" id="database_server1" value="mysql"></td>
    </tr>
    <tr>
      <td width="264">Database Server Hostname </td>
      <td width="310"><input name="database_server2" type="text" id="database_server2" value="localhost"></td>
    </tr>
    <tr>
      <td>Database Username </td>
      <td><input name="database1" type="text" id="database1" value="dbuser"></td>
    </tr>
    <tr>
      <td>Database Password </td>
      <td><input name="database2" type="text" id="database2" value="password"></td>
    </tr>
    <tr>
      <td>Database Name </td>
      <td><input name="database3" type="text" id="database3" value="websitedatabase"></td>
    </tr>
    <tr>
      <td>Users Table Name </td>
      <td><input name="table1" type="text" id="table1" value="users_table"></td>
    </tr>
    <tr>
      <td>Username Field </td>
      <td><input name="table2" type="text" id="table2" value="username_field"></td>
    </tr>
    <tr>
      <td><p>Password Field</p>
      </td>
      <td><input name="table3" type="text" id="table3" value="password_field"></td>
    </tr>
    <tr>
      <td>Password Field Uses MYSQL PASSWORD() encryption?</td>
      <td><select name="encryption" id="encryption">
        <option value="MYSQL" selected>Yes</option>
        <option value="NONE">No</option>
            </select></td>
    </tr>
    <tr>
      <td>Enabled</td>
      <td><select name="enabled" id="enabled">
        <option value="TRUE" selected>Yes</option>
        <option value="FALSE">No</option>
      </select></td>
    </tr>
  </table>
  <p>
    <input name="adodb" type="submit" id="nis" value="Add">
  </p>
</form>
<?php
}
function addldap() {
	global $_POST;
	$_SESSION['method_count']++;
	$m='method' . $_SESSION['method_count'] . '_';
	$_SESSION[$m . 'type']='LDAP';
	$_SESSION[$m . 'server']=$_POST['server'] . ':' . $_POST['port'];
	$_SESSION[$m . 'base_dn']=$_POST['base_dn'];
	$_SESSION[$m . 'enabled']=$_POST['enabled'];
}
function addactivedir() {
	global $_POST;
	$_SESSION['method_count']++;
	$m='method' . $_SESSION['method_count'] . '_';
	$_SESSION[$m . 'type']='ACTIVEDIR';
	$_SESSION[$m . 'server']=$_POST['server'] . ':' . $_POST['port'];
	$_SESSION[$m . 'base_dn']=$_POST['base_dn'];
	if ($_POST['attribute']) $_SESSION[$m . 'attribute']='memberof|' . $_POST['attribute'];
	if ($_POST['attribute']) $_SESSION[$m . 'search_account']=$_POST['username'] . '|' . $_POST['password'];
	$_SESSION[$m . 'populate_session_data_from_ldap']=$_POST['populate_session_data_from_ldap'];
	$_SESSION[$m . 'enabled']=$_POST['enabled'];
}
function addnis() {
	global $_POST;
	$_SESSION['method_count']++;
	$m='method' . $_SESSION['method_count'] . '_';
	$_SESSION[$m . 'type']='NIS';
	$_SESSION[$m . 'nis_domain']=$_POST['nis_domain'];
	if ($_POST['ypmatch_command']) $_SESSION[$m . 'ypmatch_command']=$_POST['ypmatch_command'];
	$_SESSION[$m . 'enabled']=$_POST['enabled'];
}
function addweb() {
	global $_POST;
	$_SESSION['method_count']++;
	$m='method' . $_SESSION['method_count'] . '_';
	$_SESSION[$m . 'type']='WEB';
	$_SESSION[$m . 'path']=$_POST['w1'] . '{username}:{password}@' . $_POST['w2'] . $_POST['w3'];
	$_SESSION[$m . 'enabled']=$_POST['enabled'];
}
function addcifs() {
	global $_POST;
	$_SESSION['method_count']++;
	$m='method' . $_SESSION['method_count'] . '_';
	$_SESSION[$m . 'type']='CIFS';
	$_SESSION[$m . 'share']='//' . $_POST['share1'] . '/' . $_POST['share2'];
	$_SESSION[$m . 'server_ip']=$_POST['server_ip'];
	$_SESSION[$m . 'domain']=$_POST['domain'];
	$_SESSION[$m . 'file']=$_POST['file'];
	$_SESSION[$m . 'smbclient']=$_POST['smbclient'];
	$_SESSION[$m . 'enabled']=$_POST['enabled'];
}
function addadodb() {
	global $_POST;
	$_SESSION['method_count']++;
	$m='method' . $_SESSION['method_count'] . '_';
	$_SESSION[$m . 'type']='ADODB';
	$_SESSION[$m . 'database_server']=$_POST['database_server1'] . '|' . $_POST['database_server2'];
	$_SESSION[$m . 'database'] = $_POST['database1'] . '|' . $_POST['database2'] . '|' . $_POST['database3'];
	$_SESSION[$m . 'table'] = $_POST['table1'] . '|' . $_POST['table2'] . '|' . $_POST['table3'];
	$_SESSION[$m . 'encryption'] = $_POST['encryption'];
	$_SESSION[$m . 'enabled']=$_POST['enabled'];
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Create Config.inc.php</title>
</head>

<body>
<h1>phpMultiAuth</h1>
<p>(C)2004-2005 Steve Goodman</p>
<h2>Create config.inc.php</h2>
<?php 
if (!isset($_POST['settings']) && !isset($_POST['ldap']) && !isset($_POST['activedir'])
	&& !isset($_POST['nis']) && !isset($_POST['web']) && !isset($_POST['cifs']) && !isset($_POST['adodb']))
	{
	create_settings();
	form_footer();
	} else {
	if (isset($_POST['settings']))
		{
		$_SESSION['method']=$_POST['method'];
		$_SESSION['httpauth_realm']=$_POST['httpauth_realm'];
		$_SESSION['session_prefix']=$_POST['session_prefix'];
		$_SESSION['encrypt_session_data']=$_POST['encrypt_session_data'];
		$_SESSION['encrypt_session_data_key']=$_POST['encrypt_session_data_key'];
		$_SESSION['session_logout_get_var']=$_POST['session_logout_get_var'];
		$_SESSION['authdefault']=$_POST['authdefault'];
		$_SESSION['escapeshellcmd']=$_POST['escapeshellcmd'];
		$_SESSION['addslashes']=$_POST['addslashes'];
		$_SESSION['remove_at_and_anything_after']=$_POST['remove_at_and_anything_after'];
		$_SESSION['remove_trailing_dot_and_anything_after']=$_POST['remove_trailing_dot_and_anything_after'];
		$_SESSION['register_password']=$_POST['register_password'];
		$_SESSION['adodb_path']=$_POST['adodb_path'];
		$_SESSION['method_count']=0;
		}
		if (isset($_POST['ldap']))
				{
				if ($_POST['ldap']=='Add')
					{
					addldap();
					outputsettings();
					form_footer();
					
					} else {
					enterldap();
					}
			}
		if (isset($_POST['activedir']))
			{
			if ($_POST['activedir']=='Add')
				{
				addactivedir();
				outputsettings();
				form_footer();
				
				} else {
				enteractivedir();
				}
			}
		if (isset($_POST['nis']))
			{
			if ($_POST['nis']=='Add')
				{
				addnis();
				outputsettings();
				form_footer();
				
				} else {
				enternis();
				}
			}
		if (isset($_POST['web']))
			{
			if ($_POST['web']=='Add')
				{
				addweb();
				outputsettings();
				form_footer();
				
				} else {
				enterweb();
				}
			}
		if (isset($_POST['cifs']))
			{
			if ($_POST['cifs']=='Add')
				{
				addcifs();
				outputsettings();
				form_footer();
				
				} else {
				entercifs();
				}
			}
		if (isset($_POST['adodb']))
			{
			if ($_POST['adodb']=='Add')
				{
				addadodb();
				outputsettings();
				form_footer();
				
				} else {
				enteradodb();
				}
			}
	
	}

?>


<p>&nbsp;</p>
</body>
</html>
