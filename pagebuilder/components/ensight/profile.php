<?
//--- Automatic profiler for PageBuilder
//--- This is not a true component, and should always be included directly into a page
//--- Expects:
//---    Session_ID or Profile_ID (local)
//---    Config (the requested page's configuration array)
//---    Filename (the requested page's filename with no path info)
//--- Returns:
//---    Session_ID and UserStatus, as well as all
//---    supplied variables
//--- Notes:
//---    Now supporting the new Profiling 2.0 module with improved recognition

if (defined ("EnableProfiling2_0")) {

	//--- Look up my session ID (special case for preview pages)
	$Session_ID = (($_EM == 1) || ($_PM == 1) ? $_REQUEST['Session_ID'] : $_COOKIE['Session_ID']);

	if (($Session_ID) && (!$Profile_ID)) {
		$Profile_ID = LocateSession ($Session_ID);
	}

	//--- Get the user's status
	if ($Profile_ID) {
		$UserStatus = RetrieveUserStatus ($Profile_ID);
	} else {
		$UserStatus = PROFILE_GUEST;
	}

	//--- Am I allowed?
	if ($Config) {
		if (($UserStatus < $Config['AccessLevel_NUM']) && (ROOT_URL."/".$Filename != RLM)) {
			Redirect (UpdateQueryString (RLM, array ("Error_NUM" => "2", "Dest_URL" => $Filename), array ()));
		}
	}

	//--- Special instruction for personal homepages
	if ((PersonalizeFrontPage == 1) && (ROOT_URL."/".$Filename == HOME) && ($UserStatus > PROFILE_GUEST) && ($_EM != 1) && ($_PM != 1)) {
		Redirect (UpdateQueryString (PHP, array (), array ()));
	}

	//--- Ensight profiler cookie identifier (epci)
	setcookie ("epci", '1', 0, (defined ("COOKIE_PATH") ? COOKIE_PATH : WWW_ROOT), (defined ("CookieDomain") ? CookieDomain : ''));

} else {

	//--- Look up my session ID
	if (($Session_ID) && (!$Profile_ID)) {
		$Profile_ID = LocateSession ($Session_ID);
	}

	//--- Return with a profile
	if (!$Profile_ID) {
		if ($_SERVER['REDIRECT_TARGET']) {
			Redirect (ROOT_URL."/click.php?".(SessionCookies ? "u=".$_SERVER['REQUEST_URI']."&cookie=k" : "u=".$_SERVER['REQUEST_URI']).(RetrieveLastURL () ? "&o=".urlencode (RetrieveLastURL ()) : ""), (SessionCookies ? setcookie ("CookieMonster", md5 (uniqid (rand())), 0, (defined ("COOKIE_PATH") ? COOKIE_PATH : WWW_ROOT), (defined ("CookieDomain") ? CookieDomain : '')) : ''));
		} else {
			Redirect (UpdateQueryString (ROOT_URL."/click.php", (SessionCookies ? array ("u" => $Filename, "cookie" => "k") : array ("u" => $Filename)), array ("Session_ID")).(RetrieveLastURL () ? "&o=".urlencode (RetrieveLastURL ()) : ""), (SessionCookies ? setcookie ("CookieMonster", md5 (uniqid (rand())), 0, (defined ("COOKIE_PATH") ? COOKIE_PATH : WWW_ROOT), (defined ("CookieDomain") ? CookieDomain : '')) : ''));
		}
	}

	//--- Get the user's status
	$UserStatus = RetrieveUserStatus ($Profile_ID);

	//--- Am I allowed?
	if ($Config) {
		if (($UserStatus < $Config['AccessLevel_NUM']) && (ROOT_URL."/".$Filename != RLM)) {
			Redirect (UpdateQueryString (RLM, array ("Error_NUM" => "2", "Dest_URL" => $Filename), array ()));
		}
	}

	//--- Special instruction for personal homepages
	if ((PersonalizeFrontPage == 1) && (ROOT_URL."/".$Filename == HOME) && ($UserStatus > PROFILE_GUEST) && ($_EM != 1) && ($_PM != 1)) {
		Redirect (UpdateQueryString (PHP, array (), array ()));
	}

}
?>