function swipe(container) {
    var element = container.find(":first");
    var swipe = {};
    var slides = element.find("li");
    var width = container.width();
    var height = container.height();
    element.css({
        width  : (slides.length * width) + 'px',
        height : height + 'px'
    });
    $.each(slides, function(index) {
        var slide = slides.eq(index);
        slide.css({
            width: width + 'px',
            height: height + 'px'
        });
    });

    swipe.scrollTo = function(x,speed) {
    	element.css({
    		'transition-timing-function': 'linear',
        	'transition-duration': '5000ms',
        	'transform': 'translate3d(-' + (width * 2) + 'px,0px,0px)'
    	});
    	return this;
    };
    return swipe;
}