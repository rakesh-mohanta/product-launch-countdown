jQuery(function() {
	twenty9_days = "";
	for(i=1;i<30;i++) {
		twenty9_days += "<option value='"+i+"'>"+i+"</option>";
	}
	thirty_days = twenty9_days + "<option value='30'>30</option>";
	thirty1_days = thirty_days + "<option value='31'>31</option>";
	jQuery(document).delegate(".drop-datepicker .year, .drop-datepicker .month", "click", function() {
		// Record day.
		var day = jQuery(this).parent().find(".day").val();

		thirtyday_months = new Array("April", "June", "September", "November");

		if (jQuery.inArray(jQuery(this).parent().find(".month").val(), thirtyday_months) >= 0) {
			console.log("good");
			jQuery(this).parent().find(".day").html(thirty_days);
		} else {
			jQuery(this).parent().find(".day").html(thirty1_days);
		}

		if ((jQuery(this).parent().find(".month").val() == "February") && ((jQuery(this).parent().find(".year").val() % 4) == 0)) {
			jQuery(this).parent().find(".day").html(thirty_days);
		} else if (jQuery(this).parent().find(".month").val() == "February") {
			jQuery(this).parent().find(".day").html(twenty9_days);
		}

		// After day select is repopulated, see if you can set the day again.
		jQuery(this).parent().find(".day").val(day);
	});

});