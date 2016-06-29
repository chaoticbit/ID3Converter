<?php
$TextEncoding = 'UTF-8';
$file = $_GET['filename'];
$title = $_GET['title'];
$artist = $_GET['artist'];
$album = $_GET['album'];

require_once('getid3/getid3.php');
$getID3 = new getID3;
$getID3->setOption(array('encoding'=>$TextEncoding));

require_once('getid3/write.php');

$tagwriter = new getid3_writetags;
$tagwriter->filename = 'uploads/' . $file;
$tagwriter->tagformats = array('id3v1');
$tagwriter->overwrite_tags = true;
$tagwriter->remove_other_tags = false;
$tagwriter->tag_encoding = $TextEncoding;

$TagData = array(
	'title' => array($title),
	'artist' => array($artist),
	'album' => array($album),
);
$tagwriter->tag_data = $TagData;
if ($tagwriter->WriteTags()) {
	// if (!empty($tagwriter->warnings)) {
	// 	echo 'There were some warnings:<br>'.implode('<br><br>', $tagwriter->warnings);
	// }
    echo json_encode(array('success'=> true));
} else {
	echo json_encode(array('success'=> false, 'error'=>$tagwriter->errors));
}

?>
