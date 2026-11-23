<?php
// Halaman pemutar.
// Buka: https://domainanda.com/?mov=KODE
// Opsional: &src=videy | slicedrive | videyc | aceimg
?><!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Video: </title>
<style>
* { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
html, body { margin: 0; padding: 0; width: 100%; height: 100%; background: radial-gradient(ellipse at 50% 45%, #171044 0%, #080716 48%, #020207 100%); font-family: Arial, sans-serif; overflow-x: hidden; }
body { display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 10px; }
.video-container { position: relative; width: 100%; max-width: min(450px, calc((100vh - 40px) * 9 / 16), calc(100vw - 20px)); aspect-ratio: 9/16; overflow: hidden; border-radius: 18px; background: linear-gradient(145deg, rgba(14,10,42,.96), rgba(3,8,24,.98)); border: 1px solid rgba(0,245,255,.72); box-shadow: 0 0 12px rgba(0,245,255,.35), 0 0 38px rgba(0,245,255,.18); }
.play-button { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: inline-flex; align-items: center; gap: 11px; min-width: 190px; min-height: 62px; padding: 16px 27px; background: linear-gradient(110deg, #ff00c8 0%, #7928ff 48%, #00eaff 100%); color: #fff; border: 1px solid rgba(255,255,255,.9); border-radius: 999px; font: 800 15px Arial, sans-serif; letter-spacing: 2px; cursor: pointer; z-index: 10; box-shadow: 0 0 8px rgba(0,245,255,.95), 0 0 22px rgba(0,245,255,.65); animation: neonPulse 2s ease-in-out infinite; }
.play-button::before { content: ""; width: 0; height: 0; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 13px solid #fff; }
.play-button:active { transform: translate(-50%, -50%) scale(1.07); }
@keyframes neonPulse { 0%,100% { box-shadow: 0 0 8px rgba(0,245,255,.8), 0 0 22px rgba(0,245,255,.45); } 50% { box-shadow: 0 0 13px rgba(0,245,255,1), 0 0 30px rgba(0,245,255,.75); } }
.loading { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 14px; z-index: 20; background: rgba(0,0,0,0.7); padding: 10px 20px; border-radius: 5px; }
.hidden { display: none !important; }
.video-title { position: absolute; bottom: 12px; left: 12px; right: 12px; color: white; font-size: 13px; background: rgba(0,0,0,0.55); padding: 6px 10px; border-radius: 4px; z-index: 15; max-width: calc(100% - 24px); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; width: fit-content; }
</style>
</head>
<body>
<div class="video-container">
<button type="button" class="play-button" id="playButton">PLAY VIDEO</button>
<div class="loading hidden" id="loading">Loading video...</div>
<div class="video-title" id="videoTitle">Video: </div>
</div>
<script>
function getVideoId(){var p=new URLSearchParams(window.location.search);var m=p.get('mov');if(m)return m.replace('.mp4','');return '';}
function buildUrl(id,cdn){if(cdn==='slicedrive')return 'https://cdn.slicedrive.com/'+id+'.mp4';if(cdn==='videy')return 'https://cdn.videy.co/'+id+'.mp4';if(cdn==='videyc')return 'https://videyc.net/file/'+id+'.mp4';if(cdn==='aceimg')return 'https://cdn2.aceimg.com/'+id+'.mp4';return '';}
var videoId=getVideoId();var urlParams=new URLSearchParams(window.location.search);var manualSrc=urlParams.get('src');var SOURCE_LIST;
if(manualSrc==='videy'){SOURCE_LIST=[buildUrl(videoId,'videy'),buildUrl(videoId,'slicedrive'),buildUrl(videoId,'videyc'),buildUrl(videoId,'aceimg')];}
else if(manualSrc==='slicedrive'){SOURCE_LIST=[buildUrl(videoId,'slicedrive'),buildUrl(videoId,'videy'),buildUrl(videoId,'videyc'),buildUrl(videoId,'aceimg')];}
else if(manualSrc==='videyc'){SOURCE_LIST=[buildUrl(videoId,'videyc'),buildUrl(videoId,'slicedrive'),buildUrl(videoId,'videy'),buildUrl(videoId,'aceimg')];}
else if(manualSrc==='aceimg'){SOURCE_LIST=[buildUrl(videoId,'aceimg'),buildUrl(videoId,'slicedrive'),buildUrl(videoId,'videy'),buildUrl(videoId,'videyc')];}
else{SOURCE_LIST=[buildUrl(videoId,'slicedrive'),buildUrl(videoId,'videy'),buildUrl(videoId,'videyc'),buildUrl(videoId,'aceimg')];}
var videoTitle=document.getElementById('videoTitle');var playButton=document.getElementById('playButton');var loading=document.getElementById('loading');var displayTitle='Video: '+videoId;document.title=displayTitle;
fetch('api.php').then(function(r){return r.json();}).then(function(data){if(data[videoId]){displayTitle=data[videoId];videoTitle.textContent=displayTitle;document.title=displayTitle;}}).catch(function(){});
videoTitle.textContent=displayTitle;
function buildPlayerHTML(){var s='';for(var i=0;i<SOURCE_LIST.length;i++){s+='<source src="'+SOURCE_LIST[i]+'" type="video/mp4">';}return '<!DOCTYPE html><html><head><title>'+displayTitle+'<\/title><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><style>*{box-sizing:border-box;}html,body{margin:0;padding:0;background:#000;}body{display:flex;flex-direction:column;justify-content:center;align-items:center;min-height:100vh;}.ad-top{margin-bottom:8px;text-align:center;}.ad-bottom{margin-top:8px;text-align:center;}video{width:100%;max-width:600px;max-height:80vh;}<\/style><\/head><body><div class="ad-top"><script type="text/javascript">atOptions={"key":"a36fdd372e41b75d0538361ee9fb914f","format":"iframe","height":50,"width":320,"params":{}};<\/scr'+'ipt><script type="text/javascript" src="https://marcotelegraph.com/a36fdd372e41b75d0538361ee9fb914f/invoke.js"><\/scr'+'ipt><\/div><video controls autoplay playsinline>'+s+'Your browser does not support the video tag.<\/video><div class="ad-bottom"><script async="async" data-cfasync="false" src="https://marcotelegraph.com/49c2d0992b2dc82cbd8af6d659e4fd0a/invoke.js"><\/scr'+'ipt><div id="container-49c2d0992b2dc82cbd8af6d659e4fd0a"><\/div><\/div><script type="text/javascript">var _Hasync=_Hasync||[];_Hasync.push(["Histats.start","1,5036646,4,0,0,0,00010000"]);_Hasync.push(["Histats.fasi","1"]);_Hasync.push(["Histats.track_hits",""]);(function(){var hs=document.createElement("script");hs.type="text/javascript";hs.async=true;hs.src="//s10.histats.com/js15_as.js";(document.getElementsByTagName("head")[0]||document.getElementsByTagName("body")[0]).appendChild(hs);})();<\/scr'+'ipt><noscript><a href="/" target="_blank"><img src="//sstatic1.histats.com/0.gif?4741208101" alt="" border="0"><\/a><\/noscript><\/body><\/html>';}
var playClicked=false;
function playVideo(){if(playClicked)return;playClicked=true;playButton.classList.add('hidden');loading.classList.remove('hidden');var html=buildPlayerHTML();var nw=null;try{nw=window.open('','_blank');if(nw){nw.document.open();nw.document.write(html);nw.document.close();}}catch(e){nw=null;}if(!nw||nw.closed){document.open();document.write(html);document.close();}loading.classList.add('hidden');}
playButton.addEventListener('click',playVideo);
playButton.addEventListener('touchend',function(e){e.preventDefault();playVideo();});
</script>
</body>
</html>
