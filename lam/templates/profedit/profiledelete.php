<?php
/*
$Id$

  This code is part of LDAP Account Manager (http://www.sourceforge.net/projects/lam)
  Copyright (C) 2003  Roland Gruber

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

  Manages deletion of profiles.

*/

include_once("../../lib/profiles.inc");
include_once("../../lib/ldap.inc");
include_once("../../lib/config.inc");

// start session
session_save_path("../../sess");
@session_start();

setlanguage();

// check if user is logged in, if not go to login
if (!$_SESSION['ldap'] || !$_SESSION['ldap']->server()) {
	metaRefresh("../login.php");
	exit;
}

// print standard header
echo $_SESSION['header'];
echo ("<html>\n");
echo ("<head><title>" . _("Delete User Profile") . "</title>\n");
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../style/layout.css\">\n";
echo "<meta http-equiv=\"pragma\" content=\"no-cache\">\n";
echo "<meta http-equiv=\"cache-control\" content=\"no-cache\">\n";
echo ("</head>\n<body>\n<p><br></p>\n");

// check if admin has submited delete operation
if ($_POST['submit']) {
	// delete user profile
	if ($_POST['type'] == "user") {
		if (!delUserProfile($_POST['del'])) {
			StatusMessage("ERROR", "", _("Unable to delete profile!") . " " . $_POST['del']);
		}
		else StatusMessage("INFO", "", _("Deleted profile:") . " " . $_POST['del']);
	}
	// delete group profile
	elseif ($_POST['type'] == "group") {
		if (!delGroupProfile($_POST['del'])) {
			StatusMessage("ERROR", "", _("Unable to delete profile!") . " " . $_POST['del']);
		}
		else StatusMessage("INFO", "", _("Deleted profile:") . " " . $_POST['del']);
	}
	// delete host profile
	elseif ($_POST['type'] == "host") {
		if (!delHostProfile($_POST['del'])) {
			StatusMessage("ERROR", "", _("Unable to delete profile!") . " " . $_POST['del']);
		}
		else StatusMessage("INFO", "", _("Deleted profile:") . " " . $_POST['del']);
	}
	// wrong profile type
	else {
		StatusMessage("ERROR", "", _("Wrong or missing type!") . " " . $_POST['type']);
	}
	echo ("<br><a href=\"profilemain.php\">" . _("Back to Profile Editor") . "</a>");
	echo ("</body></html>\n");
	exit;
}

// check if admin has aborted delete operation
if ($_POST['abort']) {
	StatusMessage("INFO", "", _("Delete operation canceled."));
	echo ("<br><a href=\"profilemain.php\">" . _("Back to Profile Editor") . "</a>");
	echo ("</body></html>\n");
	exit;
}

// check if right type was given
$type = $_GET['type'];
if (($type == "user") || ($type == "host") || ($type == "group")) {
	// user profile
	if ($type == "user") {
		echo ("<p align=\"center\"><big>" . _("Do you really want to delete this profile?") . " <b>");
			echo ($_GET['del'] . "</b></big><br></p>\n");
		echo ("<form action=\"profiledelete.php\" method=\"post\">\n");
		echo ("<p align=\"center\">\n");
		echo ("<input type=\"submit\" name=\"submit\" value=\"" . _("Submit") . "\">\n");
		echo ("<input type=\"submit\" name=\"abort\" value=\"" . _("Abort") . "\">\n");
		echo ("<input type=\"hidden\" name=\"type\" value=\"user\">");
		echo ("<input type=\"hidden\" name=\"del\" value=\"" . $_GET['del'] . "\">");
		echo ("</p></form></body></html>\n");
	}
	// group profile
	elseif ($type == "group") {
		echo ("<p align=\"center\"><big>" . _("Do you really want to delete this profile?") . " <b>");
			echo ($_GET['del'] . "</b></big><br></p>\n");
		echo ("<form action=\"profiledelete.php\" method=\"post\">\n");
		echo ("<p align=\"center\">\n");
		echo ("<input type=\"submit\" name=\"submit\" value=\"" . _("Submit") . "\">\n");
		echo ("<input type=\"submit\" name=\"abort\" value=\"" . _("Abort") . "\">\n");
		echo ("<input type=\"hidden\" name=\"type\" value=\"group\">");
		echo ("<input type=\"hidden\" name=\"del\" value=\"" . $_GET['del'] . "\">");
		echo ("</p></form></body></html>\n");
	}
	// host profile
	elseif ($type == "host") {
		echo ("<p align=\"center\"><big>" . _("Do you really want to delete this profile?") . " <b>");
			echo ($_GET['del'] . "</b></big><br></p>\n");
		echo ("<form action=\"profiledelete.php\" method=\"post\">\n");
		echo ("<p align=\"center\">\n");
		echo ("<input type=\"submit\" name=\"submit\" value=\"" . _("Submit") . "\">\n");
		echo ("<input type=\"submit\" name=\"abort\" value=\"" . _("Abort") . "\">\n");
		echo ("<input type=\"hidden\" name=\"type\" value=\"host\">");
		echo ("<input type=\"hidden\" name=\"del\" value=\"" . $_GET['del'] . "\">");
		echo ("</p></form></body></html>\n");
	}
}
else{
	// no valid profile type
	StatusMessage("ERROR", "", _("Wrong or missing type!") . " " . $type);
	echo ("<a href=\"profilemain.php\">" . _("Back to Profile Editor") . "</a>");
}
