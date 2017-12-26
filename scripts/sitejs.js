$(document).ready(function() {
var animDuration = 700;
			$('#fullpage').fullpage({
			    anchors: ['firstPage', 'secondPage', '3rdPage', '4thPage','5thPage'],
				menu: '#menu',
				scrollOverflow: true,
				css3:true,
				afterLoad: function(anchorLink, index){
                    loadedSection = $(this);
					loadedSection.find('.intro').animate({
	                left: '0%'
	                }, animDuration);
					
					$('a').live("click", function(){
	                    var href = $(this).attr("href");
		                var arrayHref=href.split('/');
		                if (arrayHref.length<=3) return true;
		                var menuLinks=$(this).closest("#menu");
		                if(!(menuLinks.html()==undefined)) return true;
	                    $('.intro').animate({
	                    left: '-110%'
	                    }, animDuration);               
                        setTimeout(function () {
	                    window.location = href;
	                    }, animDuration);
                    return false;
                    });
                }
			});

});

