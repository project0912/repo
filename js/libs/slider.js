b = imagesLoaded('#slider', function (a, b, c) {
    debugger;
});

b.on('fail', function () {
    debugger
});

//
////$(window).load(function() {
//    var sliderUL = $('div.slider').css('overflow', 'hidden').children('ul'),
//            imgs = sliderUL.find('img'),
//            imgWidth = imgs[0].width,
//            imgsLen = imgs.length,
//            current = 1,
//            totalImgsWidth = imgWidth * imgsLen;
//
//    $('#slider-nav').show().find('button').on('click', function() {
//        var direction = $(this).data('dir'),
//            loc = imgWidth;
//
//        //Check direction of click
//        ( direction === "next" ) ? ++current : --current;
//        
//        //if first image
//        if ( current === 0 ) {
//            current = imgsLen;
//            loc = totalImgsWidth - imgWidth;
//            direction = "next";
//        } else if ( current - 1 === imgsLen) {
//            current = 1;
//            loc = 0; //reset the location
//        }
//        
//        transition(sliderUL, loc, direction);
//    });
//    
//    function transition(container, loc, direction) {
//        var unit; //+/-
//        
//        if( direction && loc !== 0){
//            unit = ( direction === 'next') ? '-=' : '+='
//        }
//
//        container.animate({
//            'margin-left': unit? (unit + loc) : loc
//        }, 1000);
//    }
//    
//});