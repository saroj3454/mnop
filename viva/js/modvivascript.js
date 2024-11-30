var recorder;
var medtream;
var alltrack="";
this.$PAGE = this.jQuery("body");
const videoElem=document.getElementById('stream-elem');
var startBtn = document.getElementById('start-stream');
var endBtn = document.getElementById('stop-media');
var that = this;
var count= 1;  
function errorMessage(message, e) {
console.error(message, typeof e == 'undefined' ? '' : e);
//alert(message);
}
$('.modrecordterm label').after('<div id="errorterm"></div>');    
$('.editsubmissionform form').attr('enctype','multipart/form-data');
$("#stream-elem").attr("controls", true);  
this.$PAGE.on('click','[startrecording]',function(){ 
if($('input[name="recordterm"]').is(":checked")){
    $('#modterm').remove(); 
    $('[startrecording]').remove();  
    $('[stoprecording]').css('display','block');  
    var questdata = new FormData();
    questdata.append('vassignmentid',$('#vassignmentid').val());
    questdata.append('action','assignmentquestion');
    questdata.append('sesskey',M.cfg.sesskey);
     $.ajax({
            url: M.cfg.wwwroot+"/local/viva/upload.php",
            method: 'post',
            data: questdata,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            success: function(response){
                var obj=JSON.parse(JSON.stringify(response)); 
                if(obj.status=='1'){
                    $('[allquestion]').html(obj.data);
                }
            }  
        });   
const settings = {
video: true,
audio: true
}
// if(localtrack){
// localtrack.getTracks().forEach(track => track.stop());  
// }
const localtrack =  navigator.mediaDevices.getUserMedia(settings).then((stream) => 
{
    $("#stream-elem").removeAttr("controls");    
    // console.log('gggg',stream);
    videoElem.srcObject = stream;
     medtream = stream.getTracks()[0];
     var mediaStreamTrack = stream.getVideoTracks()[0];
     if (typeof mediaStreamTrack != "undefined") {
            mediaStreamTrack.onended = function () {//for Chrome.
                errorMessage('Your webcam is busy!')
            }
    }else{ errorMessage('Permission denied!'); }
    alltrack = stream;
    recorder = new MediaRecorder(stream,{mimeType: 'video/webm'});
    // console.log(recorder);
    recorder.start();
    const blobContainer = [];
    recorder.ondataavailable = function (e) {
    blobContainer.push(e.data)
    }
    recorder.onerror = function (e) {
    return console.log(e.error || new Error(e.name));
    }
    recorder.onstop = function (e) {
        for (const track of stream.getTracks()) {
            track.stop();
          }
        stream.getTracks().forEach(function(track) {
        track.stop();
        });
        // videoElem.srcObject = null; 
        recorder.stop();
        medtream.stop();
        var form = $('.editsubmissionform form').get(0);
        var reader = new FileReader();
        reader.readAsDataURL(new Blob(blobContainer,{ type: 'video/webm'}));           
        var formdata = new FormData();
        formdata.append('blobFile',new Blob(blobContainer,{ type: 'video/webm'}));
        formdata.append('userid',$('#userid').val());
        formdata.append('vassigneid',$('#vassigneid').val());
        formdata.append('vassignmentid',$('#vassignmentid').val());
        formdata.append('action','assignmentrecording');
        formdata.append('sesskey',M.cfg.sesskey);
        $.ajax({
            url: M.cfg.wwwroot+"/local/viva/upload.php",
            method: 'post',
            data: formdata,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            beforeSend: function(){
             $("#myuploaderloader").css('display','block');   
             $("[stoprecording]").attr("disabled", true);
             $("[stoprecording]").prop('disabled', true);
             $('[stoprecording]').removeClass('btn-primary');
             $('[stoprecording]').addClass('btn-secondary');
             $("[stoprecording]").html('Video Uploading...');

           },
            success: function(vdata){
                var obj=JSON.parse(JSON.stringify(vdata)); 
                if(obj.status=='1'){
                     $("#myuploaderloader").css('display','none'); 
                    form.submit();
                   // console.log(vdata);
                   // console.log(obj.data.viva_action);
                }
                 

            }
        });
    }
},function (e) {
                    var message;
                    switch (e.name) {
                        case 'NotFoundError':
                        case 'DevicesNotFoundError':
                            message = 'Please setup your webcam first.';
                            break;
                        case 'SourceUnavailableError':
                            message = 'Your webcam is busy';
                            break;
                        case 'PermissionDeniedError':
                        case 'SecurityError':
                            message = 'Permission denied!';
                            break;
                        default: errorMessage('Reeeejected!', e);
                            return;
                    }
                    errorMessage(message);
                }).catch(function(err) {
console.log("Unable to capture WebCam.", err);
});

     }else{
    $('.modrecordterm #errorterm').html('<div style="color:red;" class="error" id="modterm">Please checked I agree to be recorded</div>');
        // $('.vivafelement').hide();
        // $(".vivafelement input[name='question_1']").removeAttr("required");
        // $('[name="assignsubmission_viva_enabled"]').removeAttr('checked')
     }
    
}); 
this.$PAGE.on('click','[stoprecording]',function(){ 
      videoElem.pause();
      recorder.stop();
    // medtream.stop();
      
  
    
});   
this.$PAGE.on('click','input[name="recordterm"]',function(){ 
    if($(this).is(":checked")){
        $('[startrecording]').addClass('btn-primary');
        $('[startrecording]').removeClass('btn-secondary');
        $('#modterm').remove();
    }else{
        $('[startrecording]').removeClass('btn-primary');
        $('[startrecording]').addClass('btn-secondary');
    }
});  
           
    
               