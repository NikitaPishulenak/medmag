
$(document).ready(function(){
   
   $('a').click(function(){
   if($(this).hasClass('op_box1')){
         $('#underlay1').css('display', 'block');
         $('#lightbox1').css('display', 'block'); }
      if($(this).hasClass('cl_box1')){
         $('#underlay1').css('display', 'none');
         $('#lightbox1').css('display', 'none'); }
		 return flase;
  
   });
   $('#underlay1').click(function(){
      $('#underlay1').css('display', 'none');
      $('#lightbox1').css('display', 'none'); 
	  return flase;
   });
   


	 
});