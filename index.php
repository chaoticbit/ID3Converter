<?php
require_once('getid3/getid3.php');
$getID3 = new getID3;
// $fileInfo = $getID3->analyze('uploads/1787091466519005.mp3');
// $fileInfo = json_encode($fileInfo);
// echo '<script>console.log(' . $fileInfo . ')</script>';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>ID3 Converter Mp3</title>
        <link rel="stylesheet" href="css/main.css" media="screen" charset="utf-8">
        <link rel="stylesheet" href="css/grids.css" media="screen" charset="utf-8">
        <link rel="stylesheet" href="css/grids-responsive.css" media="screen" charset="utf-8">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" charset="utf-8">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">-->
        <link rel="stylesheet" href="css/material.min.css" charset="utf-8">
    </head>
    <body>
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row" style="padding-left: 20px;">
                    <!-- Title -->
                    <span class="mdl-layout-title">ID3 Converter</span>
                    <!-- Add spacer, to align navigation to the right -->
                    <div class="mdl-layout-spacer"></div>
                    <!-- Navigation. We hide it in small screens. -->
                    <nav class="mdl-navigation mdl-layout--large-screen-only">                        
                        <a class="mdl-navigation__link fg-white" href="">Logout</a>
                    </nav>
                </div>
            </header>            
            <main class="mdl-layout__content">
                <div class="page-content" style="padding-left: 20px;">
                    <div class="pure-g">
                        <div class="pure-u-1-3">
                            <h6 class="fg-gray">Upload new Files or select from existing ones</h6>
                        </div>
                        <div class="pure-u-2-3" style="padding-top: 15px;">
                            <button class="upload-btn mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored flt-left">
                                Upload new songs
                            </button>
                            <input type="file" name="musicFile" class="music-file" style="display:none;" />
                            <!--                            <div class="mdl-spinner mdl-js-spinner is-active"></div>--> 
                            <div class="progress-container flt-left">
                                <div id="p1" class="mdl-progress mdl-js-progress flt-left" style="padding: 5px;overflow: hidden;"></div>                            
                            </div>
                        </div>
                    </div>
                    <div class="pure-g">
                        <div class="pure-u-1-3">
                            <div class="overflow-song-list">
                                <ul class="demo-list-two mdl-list file-select-container" style="padding-right: 20px;padding-top: 1px;">
                                    <?php
                                    $dir = 'uploads';
                                    if (is_dir($dir)) {
                                        if ($dh = opendir($dir)) {
                                            while (($file = readdir($dh)) !== false) {
                                                if ($file != '.' && $file != '..' && $file != '.DS_Store') {
                                                    $fileInfo = $getID3->analyze('uploads/' . $file);
                                                    $arr = array(
                                                        'id3v1' => $fileInfo['id3v1'],
                                                        'filename' => $fileInfo['filename']
                                                    );
                                                    $obj = json_encode($arr);
                                                    //echo "<a href='javascript:;' data-filename='" . $obj . "'>" . $fileInfo['filename'] . "</a><br>";
                                                    echo '<li class="mdl-list__item mdl-list__item--two-line song-select-li">';
                                                    echo '<span class="mdl-list__item-primary-content">';
                                                    echo '<i class="material-icons mdl-list__item-avatar">music_note</i>';
                                                    echo "<span class='select' data-filename='" . $obj . "'>" . $fileInfo['filename'] . "</span>";
                                                    echo '<span class="mdl-list__item-sub-title" style="padding-left: 55px;"><small>by</small> ' . $fileInfo['id3v1']['artist'] . '</span>';
                                                    echo '</span>';
                                                    echo '</li>';
                                                }
                                            }
                                            closedir($dh);
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div class="pure-u-2-3 song-selected" style="display: none;">                            
                            <h6>Song Information</h6>
                            <div class="pure-g">
                                <div class="pure-u-1">
                                    <p class="margin0">Album</p>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 50%;padding-top: 5px;">
                                        <input class="mdl-textfield__input" type="text" id="album-info">
                                        <label class="mdl-textfield__label" for="album-info"></label>
                                    </div>
                                </div>
                                <div class="pure-u-1">
                                    <p class="margin0">Artist</p>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 50%;padding-top: 5px;">
                                        <input class="mdl-textfield__input" type="text" id="artist-info">
                                        <label class="mdl-textfield__label" for="artist-info"></label>
                                    </div>
                                </div>
                                <div class="pure-u-1">
                                    <p class="margin0">Title</p>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 50%;padding-top: 5px;">
                                        <input class="mdl-textfield__input" type="text" id="track-info">
                                        <label class="mdl-textfield__label" for="track-info"></label>
                                    </div>
                                </div>
                                <div class="pure-u-1">
                                    <button class="save-btn mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored flt-left">
                                        Save
                                    </button>
                                    <div class="mdl-spinner mdl-js-spinner spinner-save" style="margin: 5px 10px;"></div>
                                </div>
                                <input type="hidden" class="uploaded-renamed-file" />
                            </div>                            
                        </div>
                        <div class="pure-u-2-3 no-song-selected" style="padding-right: 20px;">
                            <div class="flt-left" style="background: rgba(235,235,235,0.3);display: block;width: 100%;height: 100%;">
                                <p class="txt-center" style="margin: 250px 0 0 0;"><i class="material-icons" style="font-size: 30px;color: #666;">info</i></p>
                                <p class="txt-center light" style="font-size: 20px;">No song selected</p>                            
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="toastMsg" class="mdl-js-snackbar mdl-snackbar">
            <div class="mdl-snackbar__text">Song Information Saved</div>            
            <div class="mdl-snackbar__action"></div>
        </div>
        <!--        <div class="container">
                    <div class="pure-g">
                        <div class="pure-u-1 headbar fg-white">
                            <h4 class="fg-white" style="padding: 5px 20px;">ID3 Converter</h4>
                        </div>
                    </div>
                    <div style="height: 50px;"></div>
                    <div class="pure-g">
                        <div class="pure-u-1-3">
                            <div class="pure-g">
                                <div class="pure-u-1">
                                    <h5 style="padding: 5px 20px;">Upload Files</h5><br>
                                    <div class="file-upload-container" style="padding: 5px 20px;">
                                        <input type="file" name="musicFile" class="music-file" style="display:none;" />
                                        <button class="bg-cyan fg-white upload-btn">Select file to upload</button><br>
                                        <progress id="image-upload-progress" class="image-upload-progress" value="0" max="100"></progress>
                                    </div>
                                </div>
                                <div class="pure-u-1">
                                    <h5 style="padding: 5px 20px 0;">Select from existing</h5><br>
                                    <div class="file-select-container" style="padding: 0px 20px 0;">
        <?php
        $dir = 'uploads';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != '.' && $file != '..' && $file != '.DS_Store') {
                        $fileInfo = $getID3->analyze('uploads/' . $file);
                        $arr = array(
                            'id3v1' => $fileInfo['id3v1'],
                            'filename' => $fileInfo['filename']
                        );
                        $obj = json_encode($arr);
                        echo "<a href='javascript:;' data-filename='" . $obj . "'>" . $fileInfo['filename'] . "</a><br>";
                        // echo $fileInfo;
                    }
                }
                closedir($dh);
            }
        }
        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pure-u-2-3">
                            <div class="song-metadata">
                                <h5 style="padding-top: 5px;">Song metadata</h5><br>
                                <div class="pure-g">
                                    <div class="pure-u-2-3">
                                        <div class="pure-u-1">
                                            <span style="color: #555;">Album</span><br>
                                            <input type="text" class="song-input-text" id="album-info" />
                                        </div>
                                        <div class="pure-u-1" style="padding-top: 10px;">
                                            <span style="color: #555;">Artist</span><br>
                                            <input type="text" class="song-input-text" id="artist-info" />
                                        </div>
                                        <div class="pure-u-1" style="padding-top: 10px;">
                                            <span style="color: #555;">Track</span><br>
                                            <input type="text" class="song-input-text" id="track-info" />
                                        </div>
                                        <div class="pure-u-1" style="padding-top: 10px;">
                                            <button class="bg-cyan fg-white save-btn">Save</button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" class="uploaded-renamed-file" />
                            </div>
                        </div>
                    </div>
                </div>-->
        <script src="js/jquery-2.1.3.min.js"></script>
        <script src="js/material.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
