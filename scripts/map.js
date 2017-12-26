var myLatlng = [];
var styledMap =[];
var mapOptions=[];
var map=[];
var image=[];
var marker=[];
var zoom=[];
function initialize() {
myLatlng[0] = new google.maps.LatLng(53.861636,27.485116);
var styles=[ {stylers:[{hue:"#c0c0c0"},{saturation:-100},{gamma:0.4}]},{featureType:"road",elementType:"geometry",stylers:[{lightness:20},{visibility:"on"},{saturation:-50},]},{featureType:"road",elementType:"labels",stylers:[{lightness:20},]},{featureType:"road",elementType:"labels.text.stroke",stylers:[{visibility:"on"}]},{featureType:"poi.park",elementType:"geometry",stylers:[{lightness:30},]},{"elementType": "labels.icon","stylers": [{ "visibility": "off" }]} ];
for (var i = 0; i < 1; i++){
styledMap[i] = new google.maps.StyledMapType(styles, {name: "Styled map"}); 
mapOptions[i]={navigationControl: false,streetViewControl: false,mapTypeControl: false,scaleControl: false,scrollwheel: false,zoom: 16,center: myLatlng[i],mapTypeId: google.maps.MapTypeId.ROADMAP,};
map[i] = new google.maps.Map(document.getElementById('map-canvas_'+i),mapOptions[i]);
map[i].mapTypes.set('map-style', styledMap[i]);
map[i].setMapTypeId('map-style');
image[i] = new google.maps.MarkerImage('https://'+location.hostname+'/images/map/marker.png',new google.maps.Size(48,62),new google.maps.Point(0,0),new google.maps.Point(24,62));   
marker[i] = new google.maps.Marker({position: myLatlng[i],map: map[i],clickable: false,title: 'Charmer',icon: image[i]}); 
}
         
$(function(){

$(".map_zoom_p").click(function(){
n=$(this).attr('class');
n=n.replace('map_zoom_p_','');
n=n.replace(' map_zoom_p','');
zoom[n] = map[n].getZoom();map[n].setZoom(zoom[n]+1);
});

$(".map_zoom_m").click(function(){
n=$(this).attr('class');
n=n.replace('map_zoom_m_','');
n=n.replace(' map_zoom_m','');
zoom[n] = map[n].getZoom();map[n].setZoom(zoom[n]-1);
});  
});         
          
}         
          
google.maps.event.addDomListener(window, 'load', initialize);