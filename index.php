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


*/

// 'index' page for directory. This can be changed, or removed. It is not part of the web application.

if (!file_exists('./config.inc.php')) {
	header("Location: readme.html");
	exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>phpMultiAuth</title>
</head>

<body>
<h1>phpMultiAuth</h1>
<p>phpMultiAuth v1.05</p>
<p>Copyright (C) 2004-2005 Steve Goodman</p>
<p>This library is free software; you can redistribute it and/or<br>
  modify it under the terms of the GNU Lesser General Public<br>
  License as published by the Free Software Foundation; either<br>
  version 2.1 of the License, or (at your option) any later version.</p>
<p>This library is distributed in the hope that it will be useful,<br>
  but WITHOUT ANY WARRANTY; without even the implied warranty of<br>
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU<br>
  Lesser General Public License for more details.</p>
<p>You should have received a copy of the GNU Lesser General Public<br>
  License along with this library; if not, write to the Free Software<br>
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA<br>
</p>
<p>&nbsp; </p>
<p>This is the include directory for phpMultiAuth. This directory is of no interest to web site visitors. If you wish to learn more about phpMultiAuth, please visit: <a href="http://phpmultiauth.sourceforge.net">http://phpmultiauth.sourceforge.net</a> </p>
</body>
</html>
