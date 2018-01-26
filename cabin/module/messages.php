<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$messageId = isset($_GET['messageId']) ? abs((int)$_GET['messageId']) : 0;
$accessLevel = $authentication ->accessLevel();

if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster') {
	
   include('../cabin/404.php');

} else {
	
	switch ($action) {
		
		case 'replyMessage':
			
			if ($inbox -> checkMessageId($messageId, $sanitize) == false) {
				
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=messages&error=messageNotFound">';
				
			} else {
				
				replyMessage();
				
			}
			
			break;
			
		case 'deleteMessage':
			
			removeMessage();
			
			break;
			
		default:
			
			listMessages();
			
			break;
			
	}
}

function listMessages()
{
 global $inbox;
 
 $views = array();
 $views['pageTitle'] = "Messages";
 
 $p = new Pagination();
 $limit = 10;
 $position = $p -> getPosition($limit);
 
 $data_messages = $inbox -> showInbox($position, $limit);
 
 $views['messageListing'] = $data_messages['results'];
 $views['totalMessages'] = $data_messages['totalMessages'];
 $views['position'] = $position;
 
 // pagination
 $totalPage = $p -> totalPage($views['totalMessages'], $limit);
 $pageLink = $p -> navPage($_GET['order'], $totalPage);
 $views['pageLink'] = $pageLink;
 
 if (isset($_GET['error'])) {
 	if ($_GET['error'] == 'messageNotFound') $views['errorMessage'] = "Message not found !";
 }
 
 if (isset($_GET['status'])) {
 	if($_GET['status'] == 'messageReplied') $views['statusMessage'] = "Message has been replied";
 	if($_GET['status'] == 'messageDeleted') $views['statusMessage'] = "Message deleted";
 }
 
 require('messages/list-messages.php');
 
}

function replyMessage()
{
 global $inbox, $messageId, $options, $sanitize;
 
 $views = array();
 $views['pageTitle'] = "Reply message";
 $views['formAction'] = "replyMessage";
 
 if (isset($_POST['send']) && $_POST['send'] == 'Reply') {
 	
 	$to = (isset($_POST['email']) ? preg_replace('/[^ \@\.\-\_a-zA-Z0-9]/', '', $_POST['email']) : '');
 	$subject = (isset($_POST['subject']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['subject']) : '');
 	$message = preventInject($_POST['pesan']);
 
 	// get site name
 	$find_options = $options -> findOptions();
 	$views['options'] = $find_options['options'];
 	
 	foreach ($views['options'] as $opsi) {
 	    $siteName = $opsi['site_name'];
 	}
 	
 	$replyMessage = $inbox -> replyMessages($to, $subject, $message, $siteName);
 	
 	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=messages&status=messageReplied">';
 	
 } else {
 	
 	$pesan = $inbox -> readMessage($messageId, $sanitize);
 	$views['message_id'] = $pesan['ID'];
 	$views['sender'] = $pesan['sender'];
 	$views['to'] = $pesan['email'];
 	$views['phone'] = $pesan['phone'];
 	$views['message'] = $pesan['messages'];
 	
 	require('messages/read-message.php');
 	
 }
 
}

function removeMessage()
{
 global $inbox, $messageId, $sanitize;
 
 if (!$message = $inbox -> readMessage($messageId, $sanitize)) {
   echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=messages&error=messageNotFound">';
 }
 
 $delete_message = $inbox -> deleteMessage($messageId, $sanitize);
 
 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=messages&status=messageDeleted">';
 
}