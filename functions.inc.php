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

// Functions

function login($username,$password)
	{
	// Routine that checks login against list of authentication servers, returning TRUE if login is successful
	global $authentication, $settings, $restrict_to, $information;
	if ($settings['escapeshellcmd']==TRUE)
		{
		$username=escapeshellcmd($username);
		$password=escapeshellcmd($password);
		}
	if ($settings['addslashes']==TRUE)
		{
		$username=addslashes($username);
		$password=addslashes($password);
		}
	if ($settings['remove_at_and_anything_after']==TRUE)
		{
		if (strstr($username,'@')) $username=substr($username,0,strpos($username,'@'));
		}
	if ($settings['remove_trailing_dot_and_anything_after']==TRUE)
		{
		if (strstr($username,'.')) $username=substr($username,0,strpos($username,'.'));
		}
	
	// Check restricted usernames
	$restricted=FALSE;
	if ($restrict_to)
		$restricted=TRUE;
		{
		$restrict_to_array=explode('|',$restrict_to);
		foreach ($restrict_to_array as $i) {
			if ($i==$username) $restricted=FALSE;
			
		}
		
		}
	$result=FALSE;
	if ($restricted==FALSE) {
    	for ($i = 0; $i <= count($authentication);$i++)
			{
			$server=NULL;
			$port=NULL;
			if ($authentication[$i]['enabled']==TRUE)
				{
				list($server,$port) = explode(':',$authentication[$i]['server']);
				switch ($authentication[$i]['type'])
					{
					case "LDAP":
						if (!function_exists('ldap_connect')) die('LDAP module required for this type of authentication');
						$result=ldap($username,$password,$server,$port,$authentication[$i]['base_dn'],$authentication[$i]['attribute'],$authentication[$i]['search_account'],$authentication[$i]['active_directory']);
						break;
					case "NIS":
						$result=nis($username,$password,$authentication[$i]['nis_domain'],$authentication[$i]['ypmatch_command']);
						break;
					case "WEB":
						$result=web($username,$password,$authentication[$i]['path']);
						break;
					case "CIFS":
						$result=cifs($username,$password,$authentication[$i]['smbclient'],$authentication[$i]['share'],$authentication[$i]['file'],$authentication[$i]['server_ip'],$authentication[$i]['domain']);
						break;
					case "ADODB":
						$adodb_type=NULL;
						$adodb_hostname=NULL;
						$adodb_username=NULL;
						$adodb_password=NULL;
						$adodb_database=NULL;
						$adodb_table=NULL;
						$adodb_username_field=NULL;
						$adodb_password_field=NULL;
						$adodb_encryption_type=NULL;
						list($adodb_type,$adodb_hostname)=explode('|',$authentication[$i]['database_server']);
						list($adodb_username,$adodb_password,$adodb_database)=explode('|',$authentication[$i]['database']);
						list($adodb_table,$adodb_username_field,$adodb_password_field)=explode('|',$authentication[$i]['table']);
						$adodb_encryption_type=$authentication[$i]['encryption'];
						$result=adodb_auth($username,$password,$adodb_type,$adodb_hostname,$adodb_username,$adodb_password,$adodb_database,$adodb_table,$adodb_username_field,$adodb_password_field,$adodb_encryption_type);
						break;
					}
				}
			if ($result!=FALSE) break;
			}
		}
	return $result;
	}

function ldap($username,$password,$server,$port,$base_dn,$attribute,$search_account,$active_directory) 
	{
	global $information;
	$information['server']=$server;
	$information['type']='LDAP';
	// Route called by login function to authenticate against LDAP server. Returns FALSE if cannot login, true otherwise
	if ($port=="") $port=NULL;
	$ds=ldap_connect($server,$port) or die("Cannot connect to $server");
	if ($active_directory==TRUE) 
		{
		ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3) or die("Could not set Active Directory option");
		$identifier='CN';
		} else {
		$identifier='UID';
		}
	if ($search_account)
		{
		list($search_username,$search_password) = explode('|',$search_account);
		$r=ldap_bind($ds,$search_username,$search_password) or die("Could not bind to $server as $search_username");
		} else {
		$r=ldap_bind($ds) or die("Could not bind to $server anonymously");
		}
	$sr = ldap_search( $ds, $base_dn, $identifier . '=' . $username);
	if ($sr)	
		{
		
        $result = ldap_get_entries($ds,$sr);
		
        if ($result[0]) 
			{
            if (ldap_bind( $ds, $result[0]['dn'], $password) ) 
				{
				$information['surname']=$result[0]['sn'][0];
				$information['department']=$result[0]['department'][0];
				if ($information['department']=="") $information['department']=$result[0]['ou'][0];
				$information['description']=$result[0]['description'][0];
				$information['displayname']=$result[0]['displayname'][0];
				if ($information['displayname']=="") $information['displayname']=$result[0]['cn'][0];
				$information['givenname']=$result[0]['givenname'][0];
				$information['mail']=$result[0]['mail'][0];
				$information['office']=$result[0]['physicaldeliveryofficename'][0];
				if ($information['office']=="") $information['office']=$result[0]['roomnumber'][0];
				$information['title']=$result[0]['title'][0];
				$information['telephonenumber']=$result[0]['telephonenumber'][0];
				if (strstr($information['telephonenumber'],'359-3611 X')) $information['telephonenumber']=$result[0]['telephonenumber'][1];
				if ($attribute)
					{
					list($attribute_name,$attribute_value) = explode('|',$attribute);
					$attribute_name=strtolower($attribute_name);
					$matching=FALSE;
					foreach ($result[0][$attribute_name] as $attribute_match)
						{
						if (strtolower($attribute_value)==strtolower($attribute_match))
							{
							$matching=TRUE;
							break;
							}
						}
					if ($matching==TRUE)
						{
						return TRUE;
						} else {
						return FALSE;
						}
					} else {
					return TRUE;
					}
				} else {
				return FALSE;
				}
            } else {
			return FALSE;
			}
        } else {
			return FALSE;
		}
	ldap_unbind($ds);
}

function encrypt($key,$data)
	{
	// Encrypt Data
	if (!function_exists('mcrypt_module_open')) die ('MCRYPT library not installed or configured correctly');
	$td = mcrypt_module_open('des', '', 'ecb', '');
   	$key = substr($key, 0, mcrypt_enc_get_key_size($td));
   	$iv_size = mcrypt_enc_get_iv_size($td);
   	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	if (mcrypt_generic_init($td, $key, $iv) != -1) 
		{
    	$data = mcrypt_generic($td, base64_encode($data));
    	mcrypt_generic_deinit($td);
    	mcrypt_module_close($td);
		} else {
		$data=FALSE;
		}
	return $data;
	}

function decrypt($key,$data)
	{
	// Decrypt Data
	if (!function_exists('mcrypt_module_open')) die ('MCRYPT library not installed or configured correctly');
	$td = mcrypt_module_open('des', '', 'ecb', '');
   	$key = substr($key, 0, mcrypt_enc_get_key_size($td));
   	$iv_size = mcrypt_enc_get_iv_size($td);
   	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	if (mcrypt_generic_init($td, $key, $iv) != -1) 
		{
    	$data = base64_decode(mdecrypt_generic($td, $data));
    	mcrypt_generic_deinit($td);
    	mcrypt_module_close($td);
		} else {
		$data=FALSE;
		}
	return $data;
	} 
	   
function set_session($name,$data)
	{
	// Sets session data based on encryption settings, and authentication session name settings
	global $settings;
	if ($settings['encrypt_session_data']==TRUE)
		{
		if (!function_exists('mcrypt_module_open')) die ('MCRYPT library not installed or configured correctly');
		if (!$settings['encrypt_session_data_key']) $settings['encrypt_session_data_key']='tH15_15_4-d3f4ult.k3y';
		//$data=mcrypt_ecb(MCRYPT_TripleDES, $settings['encrypt_session_data_key'], base64_encode($data), MCRYPT_ENCRYPT);
		$data=encrypt($settings['encrypt_session_data_key'],$data);
		}
	if (!$settings['session_prefix']) $settings['session_prefix']="9734562Authentication";
	$_SESSION[$settings['session_prefix'] . '_' . $name] = $data;
	}

function get_session($name)
	{
	// Gets session data based on encryption settings, and authentication session name settings
	global $settings;
	$data = $_SESSION[$settings['session_prefix'] . '_' . $name];
	
	if ($data)
		{
		if ($settings['encrypt_session_data']==TRUE)
			{
			if (!function_exists('mcrypt_module_open')) die ('MCRYPT library not installed or configured correctly');
			if (!$settings['encrypt_session_data_key']) $settings['encrypt_session_data_key']='tH15_15_4-d3f4ult.k3y';
			//$data=base64_decode(mcrypt_ecb(MCRYPT_TripleDES, $settings['encrypt_session_data_key'], $data, MCRYPT_DECRYPT));
			$data=decrypt($settings['encrypt_session_data_key'],$data);
			}
		} else {
		$data=FALSE;
		}
	return $data;
	}

function adodb_auth($username,$password,$adodb_type,$adodb_hostname,$adodb_username,$adodb_password,$adodb_database,$adodb_table,$adodb_username_field,$adodb_password_field,$adodb_encryption_type)
	{
	global $settings;
	// Authenticate Against ADODB DATABASE
	if (!function_exists('ADOLoadCode')) require($settings['adodb_path'] . 'adodb.inc.php');
	$QUB_Caching = false;
	ADOLoadCode($adodb_type);
	$database=&ADONewConnection($adodb_type);
	$database->PConnect($adodb_hostname,$adodb_username,$adodb_password,$adodb_database) or die($database->ErrorMsg());
	$query="SELECT * FROM `$adodb_table` WHERE `$adodb_username_field` = '$username' AND `confirmed_email` = 1 AND `$adodb_password_field` = ";
	if ($adodb_encryption_type=="NONE")
		{
		$query.="'$password' LIMIT 1";
		} elseif ($adodb_encryption_type=="MYSQL") {
		$query.="PASSWORD('$password') LIMIT 1";
		}
	$result=$database->Execute($query) or die($database->ErrorMsg());
	if ($result->RecordCount()>0) 
		{
		return TRUE;
		} else {
		return FALSE;
		}
	$result->Close();
	}

function web($username,$password,$path)
	{
	// Authentication against remote web file. Looking for file with the words 'OK' in that we successfully retrieve, based on a successful username password
	$path=str_replace('{password}',$password,str_replace('{username}',$username,$path));
	
	if (strstr(file_get_contents($path),'OK'))
		{
		return TRUE;
		} else {
		return FALSE;
		}
	}

function cifs($username,$password,$smbclient,$share,$file,$server_ip,$domain)
	{
	// Authentication against CIFS / Samba server. Looking for file with the words 'OK' in that we successfully retrieve, based on a successful username password
	if (!file_exists($smbclient)) die("Could not find smbclient command specified");
	if (!is_executable($smbclient)) die("The smbclient command was found but the web server will not be able to execute it");
	if (stristr(exec($smbclient . ' "' . $share . '" -I ' . $server_ip . ' -d 0 -E -U ' . $username . '%' . $password . ' -W "' . $domain . '" -c "get ' . $file . ' - "'),'OK')==TRUE)
		{
		return TRUE;
		} else {
		return FALSE;
		}
	}

function nis($username,$password,$nis_domain,$ypmatch_command=FALSE)
	{
	if (!$ypmatch_command) 
		{
		$ypmatch=yp_match($nis_domain, "passwd.byname", $username);
		if (yp_errno() != 5 && yp_errno != 0) die ('YP library error: ' . yp_errno() . ': ' . yp_err_string(yp_errno()));
		} else {
		if (!file_exists($ypmatch_command)) die("Could not find ypmatch command specified");
		if (!is_executable($ypmatch_command)) die("The ypmatch command was found but the web server will not be able to execute it");
		$ypmatch=exec("$ypmatch_command -d $nis_domain $username passwd");
		}
	list($ignore,$cpassword,$ignore) = explode(':',$ypmatch);
	if ($cpassword == crypt($password,$cpassword))
		{
		return TRUE;
		} else {
		return FALSE;
		}
	
	
	}
?>