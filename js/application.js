jQuery(function(){
  jQuery(".counter.wrapper").each(function() {
    var xmas = new Date(jQuery(this).find(".launch-date").val());
    var now = new Date();
    var timeDiff = xmas.getTime() - now.getTime();
    if (timeDiff <= 0) {
      jQuery(this).find('.counter').hide();
      jQuery(this).find(".launch-content").show();
    }
    var seconds = Math.floor(timeDiff / 1000);
    var minutes = Math.floor(seconds / 60);
    var hours = Math.floor(minutes / 60);
    var days = Math.floor(hours / 24);
    hours %= 24;
    minutes %= 60;
    seconds %= 60;
    if (days < 10) {
      days = "0"+days;
    }
    if (hours < 10) {
      hours = "0"+hours;
    }
    if (minutes < 10) {
      minutes = "0"+minutes;
    }
    if (seconds < 10) {
      seconds = "0"+seconds;
    }
    if (days < 1) {
      days = "00";
    }
    if (hours < 1) {
      hours = "00";
    }
    if (minutes < 1) {
      minutes = "00";
    }
    if (seconds < 1) {
      seconds = "00";
    }

	  var counter = jQuery(this).find('.counter');
    counter.countdown({
	    image: PLCAjax.plc_image_url+'/digits.png',
	    startTime: days.toString()+':'+hours.toString()+':'+minutes.toString()+':'+seconds.toString(),
	    timerEnd: function(){ 
        jQuery(counter).hide();
        jQuery(counter).parent().find(".launch-content").show();
	    }
	  });
  });
});