// Set timeout variables
// var TIM_TIMEOUT_WARNING = 0.1; // Display warning in x minutes
// var TIM_TIMEOUT_NOW     = 0.1; // Warning has been shown, give the user x minutes to interact

var TIM_TIMEOUT_WARNING_ID;
var TIM_TIMEOUT_NOW_ID;

var isTimerWarningModalOpened = false;

// Set the timer when there is a draft booking
function timSetBookingTimers() {
	// Booking exists but without items
	if (parseInt(document.getElementById('timBTI').value) == 0){
		return;
	}

	// console.log('timSetBookingTimers');
    document.addEventListener('mousemove', timResetTimer, false);
    document.addEventListener('mousedown', timResetTimer, false);
    document.addEventListener('keypress', timResetTimer, false);
    document.addEventListener('touchmove', timResetTimer, false);
    document.addEventListener('onscroll', timResetTimer, false);

    timStartBookingTimer(); // First load or continue booking
}

function timStartBookingTimer() {
	var TIM_TIMEOUT_WARNING = document.getElementById('timTO').value; // Display warning in x minutes
	// var TIM_TIMEOUT_WARNING = 0.4;

	isTimerWarningModalOpened = false;

    TIM_TIMEOUT_WARNING_ID = setTimeout('timExecuteTimeOutWarning()', TIM_TIMEOUT_WARNING * 60 * 1000);
}

function timResetTimer() {
	if (isTimerWarningModalOpened){
		return;
	}

	// Only if modal is not opened
    timClearTimers();
    timStartBookingTimer();
}

function timClearTimers(){
	clearTimeout(TIM_TIMEOUT_WARNING_ID);
    clearTimeout(TIM_TIMEOUT_NOW_ID);
}

// Show timer warning modal and clear first timer - create second timer
function timExecuteTimeOutWarning() {
	// console.log('timExecuteTimeOutWarning');
	var TIM_TIMEOUT_NOW = 1; // Warning has been shown, give the user x minutes to interact
	// var TIM_TIMEOUT_NOW = 0.2;

    var bookingId = document.getElementById('timBID').value; // booking cart id

	clearTimeout(TIM_TIMEOUT_WARNING_ID);
    TIM_TIMEOUT_NOW_ID = setTimeout("timExecuteTimeOutNow('"+ bookingId +"')", TIM_TIMEOUT_NOW * 60 * 1000);
    
    document.createElement('div').id = 'booking-inactivity';

    /*var href = document.getElementById('timUrl').value + 
    	'/public/includes/class-tim-travel-manager-ajax.php?option=booking-inactivity&bookingId='+ bookingId;

    var fancyboxParams = {
    	autoSize:            true, 
    	preventCloseOutside: true, 
    	hideCloseBtn:        true
    }; 

	openModal(href, fancyboxParams);*/

	var params = {
		action:  'open_modal', 
        f_nonce: timData.f_nonce, // Front-end nonce
        option:  'booking-inactivity', 
        id:      bookingId
	};

	timPostAjax(timData.ajaxurl, params, function(response){
		var fancyboxParams = {
    		autoSize:            true, 
    		preventCloseOutside: true, 
    		hideCloseBtn:        true
    	};

      	openModal(response, fancyboxParams);
    });
	
	isTimerWarningModalOpened = true;
}

// Close timer warning modal and clear both timers
function timExecuteTimeOutNow(bookingId){	
	// console.log('timExecuteTimeOutNow');
	timCancelBooking(bookingId, true);
}

function timContinueWithBooking(bookingId){
	// console.log('timContinueWithBooking');
	jQuery.fancybox.close('booking-inactivity');

	timClearTimers();
	timSetBookingTimers(bookingId);
}

// Delete booking from API and delete cartSession
function timCancelBooking(bookingId, deletedBySystem){
	// console.log('timCancelBooking');	

	var params = {
		action:    'delete_booking_api', 
        f_nonce:   timData.f_nonce,
        bookingId: bookingId, 
        lang:      document.getElementById('timCL').value
	};

	if (deletedBySystem){
		params.deletedBySystem = true;
	}

	jQuery.post(timData.ajaxurl, params, function(response) {
		if (response == 'false'){ return false; }

		timClearTimers();

		jQuery.fancybox.close('booking-inactivity');

    	window.location = document.getElementById('timHomeUrl').value;
    });
	
	// Reload, close modal or go to page
	// location.reload();
    // window.location = logoutUrl
}



// document.getElementById('booking-expired-msg').display = 'inline-block'; // not working, just close the modal after


// reset main timer i,e idle time to 0 on mouse move, keypress or reload
/*window.onload = reset_main_timer;
document.onmousemove = reset_main_timer;
document.onkeypress = reset_main_timer;

var draftBookingTimer;
var count = 0;
// var draftBookingInterval = 5*60000; // 5 minutes
var draftBookingInterval = 5*60000; // 5 minutes

var MINUTES_UNITL_AUTO_DELETE_BOOKING = 5; // in mins
var CHECK_INTERVAL = 1000;                 // in ms

function startTimTimer(){
	draftBookingTimer = setInterval(function() {
		count = count + 1;
    	console.log(count);

    	if (count = 5000){ // 5 sec for testing
    		timAjaxContent('booking-inactivity', '-');
    	}
    }, CHECK_INTERVAL); 
}

function resetTimTimer(){
	clearInterval(draftBookingTimer);
}


function timContinueBooking(){
	console.log('timCancelBooking');
	clearInterval(draftBookingTimer);
}

function timCancelBooking(){
	console.log('timCancelBooking');
	clearInterval(draftBookingTimer);
}*/


/*function showBookingInactivityModal(){
	timAjaxContent('booking-inactivity', '-');
}*/

/*function dialogSetInterval(){
	setInterval(function() {
		console.log();
	}, intervalTime);
}*/