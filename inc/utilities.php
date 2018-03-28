<?php

// check detail request
function checkDetailRequest($action, $param)
{
 global $dispatching;
  
 return $dispatching->URLDispatcher($action, $param);
  
}

// current URL
function currentUrl() 
{
$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
$host     = $_SERVER['HTTP_HOST'];
$script   = $_SERVER['SCRIPT_NAME'];
$params   = $_SERVER['QUERY_STRING'];    
return $protocol . '://' . $host . $script . '/' . $params;
}

function currentUri()
{
 $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
 $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
 if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
 $uri = '/' . trim($uri, '/');
 return $uri;
}

// sanitize header email by Kevin Waterson
function safeEmail($string)
{
	return preg_replace ( '((?:\n|\r|\t|%0A|%0D|%08|%09)+)i', '', $string );
}

#
# RFC 822/2822/5322 Email Parser
#
# By Cal Henderson <cal@iamcal.com>
#
# This code is dual licensed:
# CC Attribution-ShareAlike 2.5 - http://creativecommons.org/licenses/by-sa/2.5/
# GPLv3 - http://www.gnu.org/copyleft/gpl.html
#
# $Revision$
#

##################################################################################

function is_valid_email_address($email, $options=array()){

	#
	# you can pass a few different named options as a second argument,
	# but the defaults are usually a good choice.
	#

	$defaults = array(
			'allow_comments'	=> true,
			'public_internet'	=> true, # turn this off for 'strict' mode
	);

	$opts = array();
	foreach ($defaults as $k => $v) $opts[$k] = isset($options[$k]) ? $options[$k] : $v;
	$options = $opts;



	####################################################################################
	#
	# NO-WS-CTL       =       %d1-8 /         ; US-ASCII control characters
	#                         %d11 /          ;  that do not include the
	#                         %d12 /          ;  carriage return, line feed,
	#                         %d14-31 /       ;  and white space characters
	#                         %d127
	# ALPHA          =  %x41-5A / %x61-7A   ; A-Z / a-z
	# DIGIT          =  %x30-39

	$no_ws_ctl	= "[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x7f]";
	$alpha		= "[\\x41-\\x5a\\x61-\\x7a]";
	$digit		= "[\\x30-\\x39]";
	$cr		= "\\x0d";
	$lf		= "\\x0a";
	$crlf		= "(?:$cr$lf)";


	####################################################################################
	#
	# obs-char        =       %d0-9 / %d11 /          ; %d0-127 except CR and
	#                         %d12 / %d14-127         ;  LF
	# obs-text        =       *LF *CR *(obs-char *LF *CR)
	# text            =       %d1-9 /         ; Characters excluding CR and LF
	#                         %d11 /
	#                         %d12 /
	#                         %d14-127 /
	#                         obs-text
	# obs-qp          =       "\" (%d0-127)
	# quoted-pair     =       ("\" text) / obs-qp

	$obs_char	= "[\\x00-\\x09\\x0b\\x0c\\x0e-\\x7f]";
	$obs_text	= "(?:$lf*$cr*(?:$obs_char$lf*$cr*)*)";
	$text		= "(?:[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f]|$obs_text)";

	#
	# there's an issue with the definition of 'text', since 'obs_text' can
	# be blank and that allows qp's with no character after the slash. we're
	# treating that as bad, so this just checks we have at least one
	# (non-CRLF) character
	#

	$text		= "(?:$lf*$cr*$obs_char$lf*$cr*)";
	$obs_qp		= "(?:\\x5c[\\x00-\\x7f])";
	$quoted_pair	= "(?:\\x5c$text|$obs_qp)";


	####################################################################################
	#
	# obs-FWS         =       1*WSP *(CRLF 1*WSP)
	# FWS             =       ([*WSP CRLF] 1*WSP) /   ; Folding white space
	#                         obs-FWS
	# ctext           =       NO-WS-CTL /     ; Non white space controls
	#                         %d33-39 /       ; The rest of the US-ASCII
	#                         %d42-91 /       ;  characters not including "(",
	#                         %d93-126        ;  ")", or "\"
	# ccontent        =       ctext / quoted-pair / comment
	# comment         =       "(" *([FWS] ccontent) [FWS] ")"
	# CFWS            =       *([FWS] comment) (([FWS] comment) / FWS)

	#
	# note: we translate ccontent only partially to avoid an infinite loop
	# instead, we'll recursively strip *nested* comments before processing
	# the input. that will leave 'plain old comments' to be matched during
	# the main parse.
	#

	$wsp		= "[\\x20\\x09]";
	$obs_fws	= "(?:$wsp+(?:$crlf$wsp+)*)";
	$fws		= "(?:(?:(?:$wsp*$crlf)?$wsp+)|$obs_fws)";
	$ctext		= "(?:$no_ws_ctl|[\\x21-\\x27\\x2A-\\x5b\\x5d-\\x7e])";
	$ccontent	= "(?:$ctext|$quoted_pair)";
	$comment	= "(?:\\x28(?:$fws?$ccontent)*$fws?\\x29)";
	$cfws		= "(?:(?:$fws?$comment)*(?:$fws?$comment|$fws))";


	#
	# these are the rules for removing *nested* comments. we'll just detect
	# outer comment and replace it with an empty comment, and recurse until
	# we stop.
	#

	$outer_ccontent_dull	= "(?:$fws?$ctext|$quoted_pair)";
	$outer_ccontent_nest	= "(?:$fws?$comment)";
	$outer_comment		= "(?:\\x28$outer_ccontent_dull*(?:$outer_ccontent_nest$outer_ccontent_dull*)+$fws?\\x29)";


	####################################################################################
	#
	# atext           =       ALPHA / DIGIT / ; Any character except controls,
	#                         "!" / "#" /     ;  SP, and specials.
	#                         "$" / "%" /     ;  Used for atoms
	#                         "&" / "'" /
	#                         "*" / "+" /
	#                         "-" / "/" /
	#                         "=" / "?" /
	#                         "^" / "_" /
	#                         "`" / "{" /
	#                         "|" / "}" /
	#                         "~"
	# atom            =       [CFWS] 1*atext [CFWS]

	$atext		= "(?:$alpha|$digit|[\\x21\\x23-\\x27\\x2a\\x2b\\x2d\\x2f\\x3d\\x3f\\x5e\\x5f\\x60\\x7b-\\x7e])";
	$atom		= "(?:$cfws?(?:$atext)+$cfws?)";


	####################################################################################
	#
	# qtext           =       NO-WS-CTL /     ; Non white space controls
	#                         %d33 /          ; The rest of the US-ASCII
	#                         %d35-91 /       ;  characters not including "\"
	#                         %d93-126        ;  or the quote character
	# qcontent        =       qtext / quoted-pair
	# quoted-string   =       [CFWS]
	#                         DQUOTE *([FWS] qcontent) [FWS] DQUOTE
	#                         [CFWS]
	# word            =       atom / quoted-string

	$qtext		= "(?:$no_ws_ctl|[\\x21\\x23-\\x5b\\x5d-\\x7e])";
	$qcontent	= "(?:$qtext|$quoted_pair)";
	$quoted_string	= "(?:$cfws?\\x22(?:$fws?$qcontent)*$fws?\\x22$cfws?)";

	#
	# changed the '*' to a '+' to require that quoted strings are not empty
	#

	$quoted_string	= "(?:$cfws?\\x22(?:$fws?$qcontent)+$fws?\\x22$cfws?)";
	$word		= "(?:$atom|$quoted_string)";


	####################################################################################
	#
	# obs-local-part  =       word *("." word)
	# obs-domain      =       atom *("." atom)

	$obs_local_part	= "(?:$word(?:\\x2e$word)*)";
	$obs_domain	= "(?:$atom(?:\\x2e$atom)*)";


	####################################################################################
	#
	# dot-atom-text   =       1*atext *("." 1*atext)
	# dot-atom        =       [CFWS] dot-atom-text [CFWS]

	$dot_atom_text	= "(?:$atext+(?:\\x2e$atext+)*)";
	$dot_atom	= "(?:$cfws?$dot_atom_text$cfws?)";


	####################################################################################
	#
	# domain-literal  =       [CFWS] "[" *([FWS] dcontent) [FWS] "]" [CFWS]
	# dcontent        =       dtext / quoted-pair
	# dtext           =       NO-WS-CTL /     ; Non white space controls
	#
	#                         %d33-90 /       ; The rest of the US-ASCII
	#                         %d94-126        ;  characters not including "[",
	#                                         ;  "]", or "\"

	$dtext		= "(?:$no_ws_ctl|[\\x21-\\x5a\\x5e-\\x7e])";
	$dcontent	= "(?:$dtext|$quoted_pair)";
	$domain_literal	= "(?:$cfws?\\x5b(?:$fws?$dcontent)*$fws?\\x5d$cfws?)";


	####################################################################################
	#
	# local-part      =       dot-atom / quoted-string / obs-local-part
	# domain          =       dot-atom / domain-literal / obs-domain
	# addr-spec       =       local-part "@" domain

	$local_part	= "(($dot_atom)|($quoted_string)|($obs_local_part))";
	$domain		= "(($dot_atom)|($domain_literal)|($obs_domain))";
	$addr_spec	= "$local_part\\x40$domain";



	#
	# this was previously 256 based on RFC3696, but dominic's errata was accepted.
	#

	if (strlen($email) > 254) return 0;


	#
	# we need to strip nested comments first - we replace them with a simple comment
	#

	if ($options['allow_comments']){

		$email = email_strip_comments($outer_comment, $email, "(x)");
	}


	#
	# now match what's left
	#

	if (!preg_match("!^$addr_spec$!", $email, $m)){

		return 0;
	}

	$bits = array(
			'local'			=> isset($m[1]) ? $m[1] : '',
			'local-atom'		=> isset($m[2]) ? $m[2] : '',
			'local-quoted'		=> isset($m[3]) ? $m[3] : '',
			'local-obs'		=> isset($m[4]) ? $m[4] : '',
			'domain'		=> isset($m[5]) ? $m[5] : '',
			'domain-atom'		=> isset($m[6]) ? $m[6] : '',
			'domain-literal'	=> isset($m[7]) ? $m[7] : '',
			'domain-obs'		=> isset($m[8]) ? $m[8] : '',
	);


	#
	# we need to now strip comments from $bits[local] and $bits[domain],
	# since we know they're in the right place and we want them out of the
	# way for checking IPs, label sizes, etc
	#

	if ($options['allow_comments']){
		$bits['local']	= email_strip_comments($comment, $bits['local']);
		$bits['domain']	= email_strip_comments($comment, $bits['domain']);
	}


	#
	# length limits on segments
	#

	if (strlen($bits['local']) > 64) return 0;
	if (strlen($bits['domain']) > 255) return 0;


	#
	# restrictions on domain-literals from RFC2821 section 4.1.3
	#
	# RFC4291 changed the meaning of :: in IPv6 addresses - i can mean one or
	# more zero groups (updated from 2 or more).
	#

	if (strlen($bits['domain-literal'])){

		$Snum			= "(\d{1,3})";
		$IPv4_address_literal	= "$Snum\.$Snum\.$Snum\.$Snum";

		$IPv6_hex		= "(?:[0-9a-fA-F]{1,4})";

		$IPv6_full		= "IPv6\:$IPv6_hex(?:\:$IPv6_hex){7}";

		$IPv6_comp_part		= "(?:$IPv6_hex(?:\:$IPv6_hex){0,7})?";
		$IPv6_comp		= "IPv6\:($IPv6_comp_part\:\:$IPv6_comp_part)";

		$IPv6v4_full		= "IPv6\:$IPv6_hex(?:\:$IPv6_hex){5}\:$IPv4_address_literal";

		$IPv6v4_comp_part	= "$IPv6_hex(?:\:$IPv6_hex){0,5}";
		$IPv6v4_comp		= "IPv6\:((?:$IPv6v4_comp_part)?\:\:(?:$IPv6v4_comp_part\:)?)$IPv4_address_literal";


		#
		# IPv4 is simple
		#

		if (preg_match("!^\[$IPv4_address_literal\]$!", $bits['domain'], $m)){

			if (intval($m[1]) > 255) return 0;
			if (intval($m[2]) > 255) return 0;
			if (intval($m[3]) > 255) return 0;
			if (intval($m[4]) > 255) return 0;

		}else{

			#
			# this should be IPv6 - a bunch of tests are needed here :)
			#

			while (1){

				if (preg_match("!^\[$IPv6_full\]$!", $bits['domain'])){
					break;
				}

				if (preg_match("!^\[$IPv6_comp\]$!", $bits['domain'], $m)){
					list($a, $b) = explode('::', $m[1]);
					$folded = (strlen($a) && strlen($b)) ? "$a:$b" : "$a$b";
					$groups = explode(':', $folded);
					if (count($groups) > 7) return 0;
					break;
				}

				if (preg_match("!^\[$IPv6v4_full\]$!", $bits['domain'], $m)){

					if (intval($m[1]) > 255) return 0;
					if (intval($m[2]) > 255) return 0;
					if (intval($m[3]) > 255) return 0;
					if (intval($m[4]) > 255) return 0;
					break;
				}

				if (preg_match("!^\[$IPv6v4_comp\]$!", $bits['domain'], $m)){
					list($a, $b) = explode('::', $m[1]);
					$b = substr($b, 0, -1); # remove the trailing colon before the IPv4 address
					$folded = (strlen($a) && strlen($b)) ? "$a:$b" : "$a$b";
					$groups = explode(':', $folded);
					if (count($groups) > 5) return 0;
					break;
				}

				return 0;
			}
		}
	}else{

		#
		# the domain is either dot-atom or obs-domain - either way, it's
		# made up of simple labels and we split on dots
		#

		$labels = explode('.', $bits['domain']);


		#
		# this is allowed by both dot-atom and obs-domain, but is un-routeable on the
		# public internet, so we'll fail it (e.g. user@localhost)
		#

		if ($options['public_internet']){
			if (count($labels) == 1) return 0;
		}


		#
		# checks on each label
		#

		foreach ($labels as $label){

			if (strlen($label) > 63) return 0;
			if (substr($label, 0, 1) == '-') return 0;
			if (substr($label, -1) == '-') return 0;
		}


		#
		# last label can't be all numeric
		#

		if ($options['public_internet']){
			if (preg_match('!^[0-9]+$!', array_pop($labels))) return 0;
		}
	}


	return 1;
}

##################################################################################

function email_strip_comments($comment, $email, $replace=''){

	while (1){
		$new = preg_replace("!$comment!", $replace, $email);
		if (strlen($new) == strlen($email)){
			return $email;
		}
		$email = $new;
	}
}

##################################################################################

// hash password for volunteer
function shieldPass($password, $id)
{
	
$salt = '!hi#HUde9';

 if (checkMagicQuotes()) {

		$password = stripslashes(strip_tags( htmlspecialchars( $password, ENT_QUOTES ) ) );

		$shield = hash_hmac('sha512', trim($password).$salt.$id, APP_SITEKEY);

		return $shield;

 } else {

		$shield = hash_hmac('sha512', trim($password).$salt.$id, APP_SITEKEY);

		return $shield;
	}
	
}

// generate session key
function generateSessionKey($value)
{
 // create value
 $salt = 'cTtd*7xMCY-MGHfDagnuC6[+yez/DauJUmHTS).t,b,T6_m@TO^WpkFBbm,L<%C';
 $sessionKey = sha1(mt_rand(1000, 9999) . time(). $salt .$value);
 return $sessionKey;
 
}

// create user_activation_key
function createToken($value)
{
	$salt = 'c#haRl891';
	$token = md5( mt_rand( 10000, 99999 ) . time() . $value . $salt);
	return $token;
}

// unique ID
function uniqidReal($lenght = 13) 
{
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
}

// generate Hash
function generateHash($quantityChar)
{
  $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $qtyChar = strlen($characters);
  $qtyChar--;
  
  $hash = null;
  
  for ($i=1; $i<=$quantityChar; $i++) {
      
     $position = rand(0, $qtyChar);
     $hash .= substr($characters, $position, 1);
  }
  
  return $hash;
  
}

// fungsi batas waktu
function timeKeeper()
{
 $time_limit = 30000;
 $_SESSION ['timeOut'] = time() + $time_limit;
}

// fungsi validasi batas waktu
function validateTimeLogIn()
{
	$timeOut = $_SESSION['timeOut'];

	if (time() < $timeOut) {
		timeKeeper();
		return true;
	} else {
       unset( $_SESSION['timeOut'] );
	   return false;
	}
}

// prevent from injection
function preventInject($data)
{

 $data = @trim(stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES))));
 return $data;
 
}

// check magic quotes
function checkMagicQuotes()
{
	if (get_magic_quotes_gpc()) {
		$process = array (
				&$_GET,
				&$_POST,
				&$_COOKIE,
				&$_REQUEST
		);
		while ( list( $key, $val ) = each( $process ) ) {
			foreach ( $val as $k => $v ) {
				unset ( $process [$key] [$k] );
				if (is_array( $v )) {
					$process[$key][stripslashes( $k )] = $v;
					$process[] = &$process [$key] [stripslashes( $k )];
				} else {
					$process[$key][stripslashes( $k )] = stripslashes( $v );
				}
			}
		}
		unset($process);
	}
}

// make slug url friendly
function makeSlug($slug)
{

	// replace non letter or digits by -
	$slug = preg_replace( '~[^\\pL\d]+~u', '-', $slug);

	// trim
	$slug = trim($slug, '-');

	// transliterate
	$slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);

	// lowercase
	$slug = strtolower($slug);

	// remove unwanted characters
	$slug = preg_replace('~[^-\w]+~', '', $slug);

	if (empty($slug)) {
		return 'n-a';
	}

	return $slug;
}

// rename fiile 
function renameFile($filename)
{
 return preg_replace('/\s+/', '_', $filename);
}

// upload logo
function uploadLogo($file_name)
{
 // picture directory
 $upload_path = "../files/picture/";
 $upload_path_thumb = "../files/picture/thumb/";
 $file_uploaded = $upload_path . $file_name;
 $file_type = $_FILES['image']['type'];
 
 // save picture from resources
 move_uploaded_file($_FILES['image']['tmp_name'], $file_uploaded);
 
 // checking file type
 $img_source = null;
 
 if ($file_type == "image/jpeg") {
 	
 	$img_source = imagecreatefromjpeg($file_uploaded);
 	
 } elseif ($file_type == "image/png") {
 	
 	$img_source = imagecreatefrompng($file_uploaded);
 
 } elseif ($file_type == "image/jpg") {
 	
 	$img_source = imagecreatefromjpeg($file_uploaded);
 	
 } elseif ($file_type == "image/gif") {
 	
 	$img_source = imagecreatefromgif($file_uploaded);
 	
 }
 
 $source_width = imagesx($img_source);
 $source_height = imagesy($img_source);
 
 // set picture's size
 $set_width = 135;
 $set_height = ($set_width/$source_width) * $source_height;
 
 // process
 $img_processed = imagecreatetruecolor($set_width, $set_height);
 imagecopyresampled($img_processed, $img_source, 0, 0, 0, 0, $set_width, $set_height, $source_width, $source_height);
 
 // save picture's thumbnail
 if ($_FILES['image']['type'] == "image/jpeg") {
 	
 	imagejpeg($img_processed, $upload_path_thumb . "thumb_" . $file_name);
 	
 } elseif ($_FILES['image']['type'] == "image/png") {
 	
 	imagepng($img_processed, $upload_path_thumb . "thumb_" . $file_name);
 	
 } elseif ($_FILES['image']['type'] == "image/gif") {
 	
 	imagegif($img_processed, $upload_path_thumb . "thumb_" . $file_name);
 	
 } elseif ($_FILES['image']['type'] == "image/jpg") {
 	
 	imagejpeg($img_processed, $upload_path_thumb . "thumb_" . $file_name);
 }
 
 // Delete Picture in computer's memory
 imagedestroy($img_source);
 imagedestroy($img_processed);
 

}

// upload album
function uploadAlbum($file_name)
{
	// picture directory
	$upload_path = '../files/picture/album/';
	$file_destination = $upload_path . $file_name;
	
	// save picture from resources
	move_uploaded_file($_FILES['image']['tmp_name'], $file_destination);
	
	// resize picture
	$resizeImage = new Resize($file_destination);
	$resizeImage ->resizeImage(320, 215, 0);
	$resizeImage ->saveImage($file_destination, 100);
	
}

// upload photo
function uploadPhoto($file_name)
{
	
	// picture directory
	$upload_path = '../files/picture/photo/';
	$upload_path_thumb =  '../files/picture/photo/thumb/';
	$file_uploaded = $upload_path . $file_name;
	$file_type = $_FILES['image']['type'];
	$file_size = $_FILES['image']['size'];
	
	// save picture from resources
	
	if ($file_size > 52000) {
		
	    move_uploaded_file($_FILES['image']['tmp_name'], $file_uploaded);
		
		// resize picture
		$resizeImage = new Resize($file_uploaded);
		$resizeImage ->resizeImage(770, 400, 'crop');
		$resizeImage ->saveImage($file_uploaded, 100);
		

	} else {
	
		move_uploaded_file($_FILES['image']['tmp_name'], $file_uploaded);
				
	}
	
	// checking file type
	$img_source = null;
	
	if ($file_type == "image/jpeg") {
		
		$img_source = imagecreatefromjpeg($file_uploaded);
		
	} elseif ($file_type == "image/png") {
		
		$img_source = imagecreatefrompng($file_uploaded);
		
	} elseif ($file_type == "image/jpg") {
		
		$img_source = imagecreatefromjpeg($file_uploaded);
		
	} elseif ($file_type == "image/gif") {
		
		$img_source = imagecreatefromgif($file_uploaded);
		
	}
	
	$source_width = imagesx($img_source);
	$source_height = imagesy($img_source);
	
	// set picture's size
	$set_width = 320;
	$set_height = ($set_width/$source_width) * $source_height;
	
	// process
	$img_processed = imagecreatetruecolor($set_width, $set_height);
	imagecopyresampled($img_processed, $img_source, 0, 0, 0, 0, $set_width, $set_height, $source_width, $source_height);
	
	// save picture's thumbnail
	if ($_FILES['image']['type'] == "image/jpeg") {
		
	imagejpeg($img_processed, $upload_path_thumb . "thumb_" . $file_name);
		
	} elseif ($_FILES['image']['type'] == "image/png") {
		
	imagepng($img_processed, $upload_path_thumb . "thumb_" . $file_name);
		
	} elseif ($_FILES['image']['type'] == "image/gif") {
		
	imagegif($img_processed, $upload_path_thumb . "thumb_" . $file_name);
		
	} elseif ($_FILES['image']['type'] == "image/jpg") {
		
	imagejpeg($img_processed, $upload_path_thumb . "thumb_" . $file_name);
	
	}
	
	// Delete Picture in computer's memory
	imagedestroy($img_source);
	imagedestroy($img_processed);
	
}

function uploadFile($file_name)
{
	// picture directory
	$upload_path = '../files/document/';
	$file_uploaded = $upload_path . $file_name;
	$file_size = $_FILES['fdoc']['size'];
	
	if ($file_size < 524867) {
	
	 move_uploaded_file($_FILES['fdoc']['tmp_name'], $file_uploaded);
	 
	} else {
		
	  throw new Exception("Your file is too big !. Maximum file size :" . formatSizeUnits(524867));	
	}
	
}

// Konversi format tanggal dd/mm/yyyy -> yyyy/mm/dd
function tgl_ind_to_eng($tgl)
{
	$tgl_eng = substr($tgl, 6, 4) . "-" . substr($tgl, 3, 2) . "-" . substr($tgl, 0, 2);
	return $tgl_eng;
}

// Konversi format tanggal yyyy/mm/dd -> dd/mm/yyyy
function tgl_eng_to_ind($tgl)
{
	$tgl_ind = substr($tgl, 8, 2) . "-" . substr($tgl, 5, 2) . "-" . substr( $tgl, 0, 4);
	return $tgl_ind;
}

// make Date
function makeDate($value, $locale = null)
{
	$day = substr($value, 8, 2 );
	$month = getMonth(substr( $value, 5, 2 ), $locale);
	$year = substr($value, 0, 4 );
	
	if ($locale == 'id') {
	    
	    return $day . ' ' . $month . ' ' . $year;
	    
	} else {
	  
	    return $month . ' ' . $day . ', ' . $year;
	    
	}
	
}

function getMonth($value, $locale = null)
{
    
	switch ($value) {
		
	    case 1 :
		    
		   return (!empty($locale) && $locale == 'id') ? "Januari" : "January";
			
		    //return "January";
		   break;
		   
		case 2 :
		    
		    return (!empty($locale) && $locale == 'id') ? "Pebruari" : "February";
		    //return "February";
			break;
			
		case 3 :
		    
		    return (!empty($locale) && $locale == 'id') ? "Maret" : "March";
			//return "March";
			break;
			
		case 4 :
		    
		    return (!empty($locale) && $locale == 'id') ? "April" : "April";
			//return "April";
			break;
			
		case 5 :
			
		    return (!empty($locale) && $locale == 'id') ? "Mei" : "May";
		    //return "May";
			break;
			
		case 6 :
		    
		    return (!empty($locale) && $locale == 'id') ? "Juni" : "June";
		    //return "June";
			break;
		case 7 :
		    
		    return (!empty($locale) && $locale == 'id') ? "Juli" : "July";
			//return "July";
			break;
			
		case 8 :
			
		    return (!empty($locale) && $locale == 'id') ? "Agustus" : "August";
		    
		    //return "August";
			break;
			
		case 9 :
			
		    return (!empty($locale) && $locale == 'id') ? "September" : "September";
		    //return "September";
			break;
			
		case 10 :
			
		    return (!empty($locale) && $locale == 'id') ? "Oktober" : "October";
		    //return "October";
			break;
			
		case 11 :
			
		    return (!empty($locale) && $locale == 'id') ? "November" : "November";
		    //return "November";
			break;
			
		case 12 :
			
		    return (!empty($locale) && $locale == 'id') ? "Desember" : "December";
		    //return "December";
			break;
			
	}
	
}
 

// format size unit
function formatSizeUnits($bytes)
{
	if ($bytes >= 1073741824)
	{
		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
	}
	elseif ($bytes >= 1048576)
	{
		$bytes = number_format($bytes / 1048576, 2) . ' MB';
	}
	elseif ($bytes >= 1024)
	{
		$bytes = number_format($bytes / 1024, 2) . ' KB';
	}
	elseif ($bytes > 1)
	{
		$bytes = $bytes . ' bytes';
	}
	elseif ($bytes == 1)
	{
		$bytes = $bytes . ' byte';
	}
	else
	{
		$bytes = '0 bytes';
	}
	
	return $bytes;
}

// function redirect page
function directPage($page = '')
{
    
$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false ? 'http' : 'https';
$host     = $_SERVER['HTTP_HOST'];
    
// defining url
$url = $protocol . '://' . $host . dirname($_SERVER['PHP_SELF']);
	
// remove any trailing slashes
$url = rtrim($url, '/\\');
	
// add the page
$url .= '/' . $page;
	
// redirect the user
header("Location: $url");
	
}

// is integer 
function isInteger($input)
{
 if (!ctype_digit(strval($input))) {
 	
 	return false;
 
 } else {
 	
 	return true;
 }
 
}

// imeago - thanks to Bennet Stone devtips.com
function timeAgo($date)
{
    
    if (empty($date))
    {
        return "No date provided";
    }
    
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
    
    $now = time();
    
    $unix_date = strtotime( $date );
    
    
    if ( empty( $unix_date ) )
    {
        return "Bad date";
    }
    
    // is it future date or past date
    
    if ( $now > $unix_date )
    {
        $difference = $now - $unix_date;
        $tense = "ago";
    }
    else
    {
        $difference = $unix_date - $now;
        $tense = "from now";
    }
    
    for ( $j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++ )
    {
        $difference /= $lengths[$j];
    }
    
    $difference = round( $difference );
    
    if ( $difference != 1 )
    {
        $periods[$j].= "s";
    }
    
    return "$difference $periods[$j] {$tense}";
    
}

// Autolink http://www.couchcode.com/php/auto-link-function/
function autolink($text) {
    $pattern = '/(((http[s]?:\/\/(.+(:.+)?@)?)|(www\.))[a-z0-9](([-a-z0-9]+\.)*\.[a-z]{2,})?\/?[a-z0-9.,_\/~#&=:;%+!?-]+)/is';
    $text = preg_replace($pattern, ' <a href="$1">$1</a>', $text);
    // fix URLs without protocols
    $text = preg_replace('/href="www/', 'href="http://www', $text);
    return $text;
}

// value size validation
function valueSizeValidation($form_fields)
{
    
  foreach ($form_fields as $k => $v) {
        
    if(!empty($_POST[$k]) && isset($_POST[$k]{$v + 1})) {
            
        die("{$k} </b> is longer then allowed {$v} byte length");
        
     }
    
   }
}

// remove the HTML tags
function strip_tags_content($text, $tags = '', $invert = false)
{
    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
    $tags = array_unique($tags[1]);
    
    if(is_array($tags) AND count($tags) > 0) {
        if($invert == FALSE) {
            return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
        }
        else {
            return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
        }
    }
    elseif($invert == FALSE) {
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }
    return $text;
    
}

function startSessionOnSite()
{
    session_set_cookie_params(0);
    $lifetime=30000;
    $sn = session_name();
    if (isset($_COOKIE[$sn])) {
        $sessid = $_COOKIE[$sn];
    } else if (isset($_GET[$sn])) {
        $sessid = $_GET[$sn];
    } else {
        return session_start();
        return setcookie($sn,session_id(),time()+$lifetime);
    }
    
    if (!preg_match('/^[a-zA-Z0-9,\-]{22,40}$/', $sessid)) {
        return false;
    }
    return session_start();
    return setcookie($sn,session_id(),time()+$lifetime);
}
