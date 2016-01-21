<!DOCTYPE html>
<html>
<head>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="js/jquery.knob.js"></script>

<?php 
// get folder
$TrackDir = $_GET["t"] ;
$FileExt = "*";

$i = 0;
$CONTROLS = "controls";
//$MEDIAGROUP = "mediagroup=\"group\""
if (is_dir($TrackDir)) {
  //echo "is_dir true";
  foreach(glob($TrackDir."/*.".$FileExt) as $file) {
      $STR_MusicVar .= "music.push('$file');\n";
      $STR_AOVar .= "AudioObj.push(document.getElementById(\"AO$i\")); \n  ";

      //if ($i>0) { $CONTROLS = ""; }
      $STR_AO_inDiv .= "<audio id=\"AO$i\" preload=\"metadata\" $MEDIAGROUP $CONTROLS ></audio> \n  ";

      $title = basename($file, ".mp3");
      $STR_List .= "<input type=\"checkbox\" name=\"$i\" onclick=\"checkboxClickMute(this);\"> $title <br>\n     ";
      $i++;
  }
}
?>

<script>
//var a;
var AudioObj = [];
var lblPbr;
var lblVol
var f;
var m;
var p = 100; //playbackRate
var v = 100; //Volume
var s;
var t;
var PlayPause;

window.onload = function () {
  //a = document.getElementById("myAudio");
  <? echo "$STR_AOVar"?>

  lblPbr = document.getElementById("currentPbr");
  lblVol = document.getElementById("currentVol");  
  f = document.getElementById("myForm");
  m = document.createTextNode("Sorry, your browser does not support HTML5 audio.")
  //p = document.getElementById("pbr");
  //v = document.getElementById("vol");
  PlayPause = document.getElementById("PlayPause");

  for (i=0; i<music.length; i++) {
    loadTrack(music[i],false,AudioObj[i]);
  }

  f.reset();
  lblPbr.innerHTML = p;
  lblVol.innerHTML = v;
  ChangeVol(v/100);
};

function loadTrack(track, start, a) {
    t = a.currentTime;
    a.pause();
    
    while (a.firstChild) {
      a.removeChild(a.firstChild)
    }
    
    s = document.createElement("source");
    s.type = "audio/ogg";
    s.src = track;//.concat(".ogg");
    a.appendChild(s);
    
    s = document.createElement("source");
    s.type = "audio/mp4";
    s.src = track;//.concat(".m4a");
    a.appendChild(s);

    s = document.createElement("source");
    s.type = "audio/mpeg";
    s.src = track;//.concat(".mp3");
    a.appendChild(s);
    
    a.appendChild(m);
    
    a.mediaGroup = "mediaGroup";

    a.load();
    a.playbackRate = p;
    //alert (t);
    a.volume = v.value;
    if (start && t) {
      a.play();     
    }
};

function checkboxClickMute(myCheckbox) {
  selection = myCheckbox.name;
  AudioObj[selection].muted = myCheckbox.checked;
  if (AudioObj[i].muted == false) { AudioObj[i].volume = v; }
};

function TogglePlayPause() {
  for (i=0; i<AudioObj.length; i++) {
    if (AudioObj[i].paused) {AudioObj[i].play();
      } else { AudioObj[i].pause(); }
  }
};

function ChangeVol(v) {
  for (i=0; i<AudioObj.length; i++) {
    if (AudioObj[i].muted == false) { AudioObj[i].volume = v; }
  }
};

function ChangePbr(p) {
  for (i=0; i<AudioObj.length; i++) {
    AudioObj[i].playbackRate = p;
  }
  alert(AudioObj[0].playbackRate);
};

var music = [];
<? echo "$STR_MusicVar"?>

</script>
 
<style>
.holder {
   width:320px;
}
 
#pbr {
   width:100%;
}

#vol {
   width:100%;
}
</style>
 
</head>
<body>

<br>
 
<div class="holder">
  <? echo $STR_AO_inDiv ?>

  <br>

  <button type="button" onclick="TogglePlayPause();">Play/Pause</button>

  <form id="myForm">
    Playback speed: <span id="currentPbr"></span>% <br><!-- <input id="pbr" type="range" value="1" min="0.5" max="1" step="0.01" > -->
    <input type="text" class="PbrDial" data-width="150" value="100" data-min="50" data-max="150" data-angleOffset=-125 data-angleArc=250 > <br><br>

    Volume: <span id="currentVol"></span>% <br><!-- <input id="vol" type="range" value="1" min="0" max="1" step="0.01" > -->
    <input type="text" class="VolDial" data-width="150" value="100" data-angleOffset=-125 data-angleArc=250 > <br><br>


    <? echo $STR_List?>
    <input type="checkbox" onclick="checkboxClick(this);"> Metronome 
  </form>
</div>

<script>
    $(".VolDial").knob({
         'change' : function (v) { 
            v = Math.round(v)
            //console.log(v); 
            lblVol.innerHTML = v;
            ChangeVol(v/100);
         }
    });
    $(".PbrDial").knob({
         'change' : function (p) { 
            p = Math.round(p)
            //console.log(p); 
            lblPbr.innerHTML = p;
            ChangePbr(p/100);
         }
    });
</script>

</body>
</html>

