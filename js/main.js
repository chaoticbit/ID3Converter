$(document).ready(function() {
    $('.upload-btn').click(function() {
        $('.music-file').click();
    });

    $('.music-file').on('change', function(e) {
        uploadFile();
    });

    function uploadFile(){
        if(!$('.music-file').prop('files')[0]){
            return false;
        }
        var file = $('.music-file').prop('files')[0];
//        var progress = document.getElementById("image-upload-progress");
        var progress = document.getElementById("p1");        
        var loaded = 0;
        var step = 1024*1024;
        var total = file.size;
        var start = 0;

        var baseurl = '';
        var reader = new FileReader();
        var filename = file.name.replace(/[-_%&^$#@"'><!()]/g,'');
        reader.onload = function(e){
            var xhr = new XMLHttpRequest();
            var upload = xhr.upload;
            upload.addEventListener('load',function(){
                loaded += step;
//                progress.value = (loaded/total) * 100;
                $('#p1 > .progressbar').css('width', (loaded/total) * 100 + '%');
                if(loaded <= total){
                    blob = file.slice(loaded,loaded+step);
                    reader.readAsBinaryString(blob);
                }else{
                    loaded = total;
                }
            },false);

        xhr.open("POST",  "http://localhost/ID3Converter/upload.php?filename=" + filename + "&noCache=" + new Date().getTime() + "&totalSize=" + total);
        xhr.overrideMimeType("application/octet-stream");
        xhr.sendAsBinary(e.target.result);
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 && xhr.status == 200){
                var result = JSON.parse(xhr.response);
                console.log(result);
                if(result.status == 'done') {
                    if(result.id3v1) {
                        var tags = result.id3v1;
                        var track = tags.title;
                        var album = tags.album;
                        var artist = tags.artist;
                        $('.no-song-selected').hide();
                        $('.song-selected').show(function(){
                            $('#album-info').val(album);
                            $('#track-info').val(track);
                            $('#artist-info').val(artist);
                        });
                    }
                    else {
                        $('.song-selected').show(function(){
                            $('#album-info').val('');
                            $('#track-info').val('');
                            $('#artist-info').val('');
                        });
                    }
                    $('.uploaded-renamed-file').val(result.filename);
                }
            }
        };
        };
        var blob = file.slice(start,step);
        reader.readAsBinaryString(blob);
    }

    $('.save-btn').click(function(){
        var album = $('#album-info').val();
        var track = $('#track-info').val();
        var artist = $('#artist-info').val();
        var file = $('.uploaded-renamed-file').val();
        console.log(album + ' ' + track + ' ' + artist + ' ' + file);

        $.ajax({
            url: 'http://localhost/ID3Converter/writetags.php?title=' + track + '&album=' + album + '&artist=' + artist + '&filename=' + file,
            type: 'GET',
            cache: false,
            beforeSend: function() {
                $('.spinner-save').addClass('is-active');
            },
            success: function(result) {
                $('.spinner-save').removeClass('is-active');
                result = JSON.parse(result);
                
                if(result.success) {
                    $('#toastMsg').find('.mdl-snackbar__text').html('Successfull Updated');
                    $('#toastMsg').addClass('mdl-snackbar--active').delay(1000).queue(function(next){
                        $(this).removeClass("mdl-snackbar--active");
                        next();
                    });
                }
                else {
                    $('#toastMsg').find('.mdl-snackbar__text').html('Something went wrong');
                    $('#toastMsg').addClass('mdl-snackbar--active').delay(1000).queue(function(next){
                        $(this).removeClass("mdl-snackbar--active");
                        next();
                    });
                }
            }
        })
    });

    $('.song-select-li').click(function(){
        var fileInfo = $(this).find('.select').data('filename');
        console.log(fileInfo);
        $('.uploaded-renamed-file').val(fileInfo.filename);
        $('.no-song-selected').hide();
        $('.song-selected').show(function(){
            if(fileInfo.id3v1) {
                $('#album-info').val(fileInfo.id3v1.album);
                $('#track-info').val(fileInfo.id3v1.title);
                $('#artist-info').val(fileInfo.id3v1.artist);
                $('#album-info').focus();
            }
            else {
                $('#album-info').val('');
                $('#track-info').val('');
                $('#artist-info').val('');
            }
        });
    });
                                        
});

window.onload = function(){    
    
    if(!XMLHttpRequest.prototype.sendAsBinary){
    XMLHttpRequest.prototype.sendAsBinary = function(datastr) {
            function byteValue(x) {
                return x.charCodeAt(0) & 0xff;
            }
            var ords = Array.prototype.map.call(datastr, byteValue);
            var ui8a = new Uint8Array(ords);
            try{
                this.send(ui8a);
            }catch(e){
                this.send(ui8a.buffer);
            }
    };
    }
};
