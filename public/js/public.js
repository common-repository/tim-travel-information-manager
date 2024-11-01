(function( $ ) {
	'use_strict';

	// Document ready
	$(function () {

		var fancyBox = $('a.tim_fancybox');
	
		if (fancyBox.length){
			// Initialize the Lightbox for any links with the 'fancybox' class
    		$('a.tim_fancybox').fancybox();
    	}

		$('.tim_list_search_filter_form select[name="sort"]').change(function(){
	        if($(this).val() !== 'all'){
	            $('.tim_list_search_filter_form').submit();
	        }
	    });

		// Set currency value
		$('.tim-currency-value').click(function() {
			var currency_code = $(this).attr('id');

			var data = {
				'action':       'set_currency_value', 
                'f_nonce':       timData.f_nonce, // Front-end nonce
				'currency_code': currency_code
			}

			$.post(timData.ajaxurl, data, function(response) {
				location.reload();
			});
		});

		// Load map on bootstrap tab
		$('#tim-tab-map').click(function(e) {
            setTimeout(timInitializeMap, 1000);
        });


        $('.tim-tabs .tim-tabs-links a').on('click', function(e) {
			var currentAttrValue = $(this).attr('href');
			 
			$('.tim-tabs ' + currentAttrValue).show().siblings().hide();
			 
			$(this).parent('li').addClass('active').siblings().removeClass('active');
			 
			e.preventDefault();
		});


        // Load swiper on tabs
		$('.swipper.tim-tabs-links a').click(function(){
    		timLoadSwiper();
		});
	});

})( jQuery );

// Load google map
function timInitializeMap(option, directions) {
	if (option == undefined){
		option = '';
	}

	var googleMap = document.getElementById('tim_googleMap'+ option);
	
	if ((googleMap !== null) && (typeof google != 'undefined')){
		var timMapType = document.getElementById('timMapType'+ option);
		if (timMapType !== null){
			if (timMapType.value === 'waypoints'){
				timGoogleRouteWayPointsMap(googleMap, directions);
			}
			else if (timMapType.value === 'multiple'){
				timLoadMultiLocationMap(googleMap, directions);
			}
		}
		else{
			var geoLat  = document.getElementById('tim_geoLat'+ option).value;
			var geoLng  = document.getElementById('tim_geoLng'+ option).value;
			var geoZoom = document.getElementById('tim_geoZoom'+ option).value;

			var position = new google.maps.LatLng(geoLat, geoLng);

			var mapOptions = {
				center: position,
				zoom: parseInt(geoZoom),
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				scrollwheel: false
			};

			var map = new google.maps.Map(googleMap, mapOptions);

			var marker = new google.maps.Marker({
			 	position: position,
			});

			marker.setMap(map);

			google.maps.event.trigger(map, 'resize');
		}
	}
}

function timGoogleRouteWayPointsMap(googleMap, directions){
	var directionsDisplay = new google.maps.DirectionsRenderer;
    var directionsService = new google.maps.DirectionsService;
    
	var mapOptions = {
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

    var map = new google.maps.Map(googleMap, mapOptions);

    directionsDisplay.setMap(map);
    calculateAndDisplayRoute(directionsService, directionsDisplay, directions);
}

function calculateAndDisplayRoute(directionsService, directionsDisplay, directions) {
	var origin      = { lat: directions.departure.lat, lng: directions.departure.lng };
	var destination = { lat: directions.arrival.lat,   lng: directions.arrival.lng };

	var waypoints = [];
	if (directions.waypoints && directions.waypoints.length > 0){
		for (var i = 0; i < directions.waypoints.length; i++) {
			var waypoint = directions.waypoints[i];
			var stopOnRoute = ( waypoint.stop_on_route ) ? true : false;
			waypoints.push({
		 		location: new google.maps.LatLng(waypoint.location.lat, waypoint.location.lng), 
		 		stopover: stopOnRoute
		 	});
		}
	}

	var selectedMode = 'DRIVING';
	directionsService.route({
		origin:      origin,
		destination: destination,
		waypoints:   waypoints,
		optimizeWaypoints: true,

		// Note that Javascript allows us to access the constant
		// using square brackets and a string value as its
		// "property."
		travelMode: google.maps.TravelMode[selectedMode]
	}, function(response, status) {
		if (status === 'OK') {
			directionsDisplay.setDirections(response);
		} else {
			window.alert('Directions request failed due to ' + status);
		}
	});
}

function timLoadMultiLocationMap(googleMap, directions){
	// If the browser supports the Geolocation API
	if (typeof navigator.geolocation == 'undefined') {
		alert('Your browser doesnt support the Geolocation API');
		return;
	}

	// Group locations
	coords = timGroupLocations(directions);

	var mapOptions = {
		zoom: 8,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	var map = new google.maps.Map(googleMap, mapOptions);

	var path = [];
	// Create the polyline's points
	for (i = 0; i < coords.length; i++) {
		path.push(
			new google.maps.LatLng(
				coords[i].lat,
				coords[i].lng
			)
		);
	}
	
	// Create the array that will be used to fit the view to the points range and
	// place the markers to the polyline's points
	var latLngBounds = new google.maps.LatLngBounds();
	
	for(var i = 0; i < path.length; i++) {		
		latLngBounds.extend(path[i]);
		
		var marker = new google.maps.Marker({
			map: map,
			position: path[i]//,
			//label: day,
			//title: 'Day '+ coords[i].day_number +' - '+ coords[i].name
		});

		if (coords[i].locations.length > 0){
			var day = '';
			for (var a = 0; a < coords[i].locations.length; a++) {
				day += '<li title="Day '+ coords[i].locations[a].day_number +' - '+ coords[i].locations[a].name +'">'+ coords[i].locations[a].day_number +'</li>';
			}

			var infowindow = new google.maps.InfoWindow({
		     	content: '<div class="tim_map_labels"><ul>'+ day +'</ul></div>'
		    });

		    infowindow.open(map, marker);
		}
	}

	// Creates the polyline object
	var polyline = new google.maps.Polyline({
		map: map,
		path: path,
		strokeColor: '#0000FF',
		strokeOpacity: 0.7,
		strokeWeight: 2
	});

	// Fit the bounds of the generated points
	map.fitBounds(latLngBounds);
}

function timGroupLocations(array) {
	var newArray  = [];
	var dataArray = [];
	
	var item, lat, day;

	var totalItems = array.length;

	// Store unique values
	for (var i = 0; i < totalItems; i++) {
		item = array[i];

		//if (typeof(newArray[item]) == 'undefined'){
		if (!newArray[item.lat]) {
			newArray[item.lat] = {
				lat: item.lat,
				lng: item.lng,
				locations: []
			};
		}
	}

	//var totalKeys = Object.keys(newArray).length;

	var a = 1;
	for(var key in newArray){
		lat = newArray[key].lat;

		for (var i = 0; i < totalItems; i++) {
			item = array[i];

			day = {
				day_number: item.day_number, 
				name:       item.name
			}

			//if ( (lat === item.lat) && ((i <= totalItems) && (key < totalKeys)) ){
			if (lat === item.lat){
				newArray[key].locations.push(day);	
			}	
		}

		a++;
    }

    for(var key in newArray){
       dataArray.push(newArray[key]);
    }

    var lastItem = newArray[array[totalItems-1].lat] = {
		lat: array[totalItems-1].lat,
		lng: array[totalItems-1].lng,
		locations: []
	};

    // Push last locations data
    dataArray.push(lastItem);

    return dataArray;
}

function timAjaxContent(option, id) {
	// var params = id ? '&id=' + id : '';
	// var href = document.getElementById('timUrl').value + '/public/includes/class-tim-travel-manager-ajax.php?option='+ option + params;
	// openModal(href);

	var params = {
		action:  'open_modal', 
        f_nonce: timData.f_nonce, // Front-end nonce
        option:  option, 
        lang:    document.getElementById('timCL').value
	};

	if (id){
		params.id = id;
	}

	timPostAjax(timData.ajaxurl, params, function(response){
      	// jQuery.fancybox(response);
      	openModal(response);
    });
}

function openModal(content, fancyboxParams) { // href
	var params = {
		// href:      href, 
		content:   content, 
		type:      'ajax',
		autoSize:  false,
	    maxWidth:  1100, // 900
		maxHeight: 800, 
		// fitToView: false, 
	    width:     '95%', 
	    afterShow: function() {
            timLoadModalSlider();
        }
	};

	if (fancyboxParams){
		if (fancyboxParams.preventCloseOutside){
			params.helpers = { 
	   			overlay : { closeClick: false }
	  		};
		}

		if (fancyboxParams.hideCloseBtn){
			params.closeBtn = false;
		}

		if (fancyboxParams.autoSize){
			params.autoSize = true;
		}
	}

	jQuery.fancybox.open(params);
}

function timLoadModalSlider() {
	if (jQuery('div.tim_detail_slider_modal').length) {
		setTimeout(function() { // Delay x sec
			var swiperTab = new Swiper('.tim_detail_slider_modal', {
			    navigation: {
		        	nextEl: '.swiper-button-next',
		        	prevEl: '.swiper-button-prev',
		    	}, 
		   		pagination: {
		        	el: '.swiper-pagination',
		        	clickable: true, 
		      	}

			    // pagination: '.swiper-pagination',
			    // nextButton: '.swiper-button-next',
			    // prevButton: '.swiper-button-prev',
			    // slidesPerView: 1,
			    // paginationClickable: true,
			    // spaceBetween: 30,
			    // loop: true
			});

			//swiperTab.update();
		}, 500);
	}
}

function timLoadSwiper(){
	var swiper = new Swiper('.tim-detail-slider', {
     	navigation: {
        	nextEl: '.swiper-button-next',
        	prevEl: '.swiper-button-prev'
    	}, 
   		pagination: {
        	el: '.swiper-pagination',
        	clickable: true,
      	}
    });

    var swiperRooms = new Swiper('.tim-rooms-slider', {
     	navigation: {
        	nextEl: '.swiper-button-next',
        	prevEl: '.swiper-button-prev'
    	}
    });

    var swiperCarrousel = new Swiper('.tim-carrousel-slider', {
        pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        grabCursor: false,
        spaceBetween: 20,
        slidesPerView: 3,
        breakpoints: {
            992: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            768: {
                slidesPerView: 1,
                spaceBetween: 20
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 10
            }
        }
    });
}

function timTabContent(evt, tabOption, subTabs) {
	var i, x, tablinks;
	x = document.getElementsByClassName('tim_tab_content');
	for (i = 0; i < x.length; i++) {
		x[i].style.display = 'none';
	}

	if (!subTabs){ // links inside tabs
		tablinks = document.getElementsByClassName('tablink');
		for (i = 0; i < x.length; i++) {
			if (tablinks[i] !== undefined){
				tablinks[i].className = tablinks[i].className.replace(' active', '');
			}
		}

		evt.currentTarget.className += ' active';
	}
	
	document.getElementById(tabOption).style.display = 'block';

	if ( tabOption == 'timTabMap' ){
		timInitializeMap('Tab');
	}
}

function timToggleContent(id){
    var content = document.getElementById(id);

	if (content.style.display === 'none') {
		content.style.display = 'block';
	} else {
		content.style.display = 'none';
	}
}

function timScrollTo(id){
    document.getElementById(id).scrollIntoView({ behavior: 'smooth' });

    return false;
}

// Show an error page here, TODO: when an error occurs
function timShowErrorLabelApi(errorLabel){
	// var input = jQuery('#timGuestEmail');
	// input.removeClass('tim_error_input');
	// input.addClass('tim_error_input');
	// input.after('<div id="timGuestEmailErrorMsg" class="tim_error_input_msg">'+ errorLabel +'</div>');

	var input = document.getElementById('timGuestEmail');
	input.classList.remove('tim_error_input');
	input.classList.add('tim_error_input');
	input.insertAdjacentHTML('afterend', '<div id="timGuestEmailErrorMsg" class="tim_error_input_msg">'+ errorLabel +'</div>');
	
	input.focus();
}

function timLoadData(action, lang){
	var params = {
		action:  action, 
        f_nonce: timData.f_nonce, 
        lang:    lang || document.getElementById('timCL').value
	};

	var loadDataResult = document.getElementById('timLoadDataResult');

	// console.log(document.getElementById('timCL').value);

	// jQuery.post(timData.ajaxurl, params, function(response) {
	timPostAjax(timData.ajaxurl, params, function(response){
      	loadDataResult.innerHTML = response;
    });
}

function timCheckLoginForm(){
	setButton();
	var error = 0;

	var email    = document.getElementById('timCustomerEmail').value;
	var password = document.getElementById('timCustomerPassword').value;
	
	error = validateInput('timCustomerPassword', error);
	error = validateEmail('timCustomerEmail', error);

	if (error){
		setButton(1);
		return false;
	}

	var params = {
		action:   'create_client_login_api', 
        f_nonce:  timData.f_nonce, 
        email:    email, 
        password: password, 
        lang:     document.getElementById('timCL').value
	};

	var formErrorMsg = document.getElementById('timCustomerFormErrorMsg');

	timShowSpinner('tim_spinner');

	// jQuery.post(timData.ajaxurl, data, function(response) {
	timPostAjax(timData.ajaxurl, params, function(response){
		if (response == 'false'){ return false; }

      	var response = JSON.parse(response);
		if (!response.errors){
			location.reload();
		}
		else{
			setButton(1);
			formErrorMsg.innerHTML = '<div class="tim_alert tim_alert_danger">'+ response.errors +'</div>';
		}

		timHideSpinner('tim_spinner');
    });
}

function timLogout(){
	var params = {
		action:  'client_logout', 
        f_nonce: timData.f_nonce // Front-end nonce
	};
    
	// jQuery.post(timData.ajaxurl, data, function(response) {
	timPostAjax(timData.ajaxurl, params, function(response){
      	location.reload();
    });
}

function timCheckSignupForm(){
	setButton();
	var error = 0;

	var name        = document.getElementById('timCustomerName').value;
	var lastName    = document.getElementById('timCustomerLastName').value;
	var email       = document.getElementById('timCustomerEmail').value;
	var phoneNumber = document.getElementById('timCustomerPhoneNumber').value;
	var phoneCode   = document.getElementById('timCustomerPhoneCode').value;
	var country     = document.getElementById('timCustomerCountry').value;

	var taxIdCode   = document.getElementById('timGuestTaxIdCode').value;
	var taxIdNumber = document.getElementById('timGuestTaxIdNumber').value;

	var password = document.getElementById('timCustomerPassword').value;
	
	error = validateInput('timCustomerPassword', error); // , validatePassword 'timLabelErrorPassword'
	
	error = validateInput('timGuestTaxIdCode', error);
	error = validateInput('timGuestTaxIdNumber', error);

	error = validateInput('timCustomerCountry', error);
	error = validatePhone('timCustomerPhoneNumber', error); //  'timLabelErrorPhone'
	error = validateEmail('timCustomerEmail', error); // , 'timLabelErrorEmail'
	error = validateInput('timCustomerLastName', error);
	error = validateInput('timCustomerName', error);

	if (error){
		setButton(1);
		return false;
	}

	var countryId = country.split('-')[0];

	var params = {
		action:        'create_client_signup_api', 
        f_nonce:       timData.f_nonce, 
        name:          name, 
        last_name:     lastName,
        tax_id_code:   taxIdCode, 
        tax_id_number: taxIdNumber,  
        email:         email, 
        phone_number:  phoneNumber, 
        phone_code:    phoneCode, 
        country_id:    countryId, 
        password:      password, 
        lang:          document.getElementById('timCL').value
	};

	// console.log(params); return;

	var formErrorMsg = document.getElementById('timCustomerFormErrorMsg');

	timShowSpinner('tim_spinner');

	// jQuery.post(timData.ajaxurl, params, function(response) {
	timPostAjax(timData.ajaxurl, params, function(response){
      	if (response == 'false'){ return false; }

      	var response = JSON.parse(response);
		if (!response.errors){
			location.reload();
		}
		else{
			setButton(1);

			var serverErrors = [];
			for(var field in response.errors){
				var error = response.errors[field];
                serverErrors = error.join(', ');
        	}

			formErrorMsg.innerHTML ='<div class="tim_alert tim_alert_danger">'+ serverErrors +'</div>';
		}

		timHideSpinner('tim_spinner');
    });
}

function timSelectCountry(value, option){
	var phoneNumberInput = document.getElementById('tim'+ [option] +'PhoneNumber');
	var phoneCodeInput   = document.getElementById('tim'+ [option] +'PhoneCode');

	phoneNumberInput.value = '';
	phoneCodeInput.value   = '';

	// if (!value){
		phoneNumberInput.disabled = (!value) ? true : false;

		// phoneNumberInput.focus(); // error after tab-typing. it focus on first character

		// phoneNumberInput.value = '';
		// phoneCodeInput.value   = '';
		// return;
	// }

	// phoneNumberInput.disabled = false;

	var phoneCode = value.split('-')[1];
	phoneCodeInput.value = phoneCode;

	jQuery('#tim'+ [option] +'PhoneNumber').inputmask({
		mask:               '('+ phoneCodeInput.value +') 9{0,20}',
		autoUnmask:         true,
    	removeMaskOnSubmit: true, 
    	greedy: false
	});	
}

function timCheckPasswordRecoveryForm(){
	setButton();
	var error = 0;

	var email = document.getElementById('timCustomerEmail').value;
	
	error = validateEmail('timCustomerEmail', error); // , 'timLabelErrorEmail'

	if (error){
		setButton(1);
		return false;
	}

	var params = {
		action:  'create_client_password_api', 
        f_nonce: timData.f_nonce, 
        email:   email, 
        lang:    document.getElementById('timCL').value
	};

	var formErrorMsg = document.getElementById('timCustomerFormErrorMsg');

	timShowSpinner('tim_spinner');

	// jQuery.post(timData.ajaxurl, params, function(response) {
	timPostAjax(timData.ajaxurl, params, function(response){
      	if (response == 'false'){ return false; }

      	var response = JSON.parse(response);
      	
		if (!response.errors){
			timLoadData('load_edit_password_form');

			document.getElementById('timClientEmail').value = response.email;
		}
		else{
			setButton(1);
			formErrorMsg.innerHTML = '<div class="tim_alert tim_alert_danger">'+ response.errors +'</div><br />';
		}

		timHideSpinner('tim_spinner');
    });
}

function timCheckUpdatePassword(){
	setButton();
	var error = 0;

	var code     = document.getElementById('timCustomerCode').value;
	var password = document.getElementById('timCustomerPassword').value;
	
	error = validateInput('timCustomerPassword', error);
	error = validateInput('timCustomerCode', error);

	if (error){
		setButton(1);
		return false;
	}

	var email = document.getElementById('timClientEmail').value;

	var data = {
		action:  'update_client_password_api', 
        f_nonce:  timData.f_nonce, 
        email:    email, 
        code:     code, 
        password: password, 
        lang:     document.getElementById('timCL').value
	};

	var formErrorMsg = document.getElementById('timCustomerFormErrorMsg');

	timShowSpinner('tim_spinner');

	jQuery.post(timData.ajaxurl, data, function(response) {
      	if (response == 'false'){ return false; }

      	var response = JSON.parse(response);
      	
		if (!response.errors){
			timLoadData('load_login_form');

			var labelPasswordUpdated = document.getElementById('timLabelPasswordUpdated').value;

			var formSuccessMsg = document.getElementById('timCustomerFormSuccessMsg');
			formSuccessMsg.innerHTML = '<div class="tim_alert tim_alert_success">'+ labelPasswordUpdated +'.</div><br />';
		}
		else{
			setButton(1);
			formErrorMsg.innerHTML = '<div class="tim_alert tim_alert_danger">'+ response.errors +'</div>';
		}

		timHideSpinner('tim_spinner');
    });
}

function timCheckUpdateClientProfile(){
	setButton();
	var error = 0;

	var id          = document.getElementById('timCustomerId').value;
	var name        = document.getElementById('timCustomerName').value;
	var lastName    = document.getElementById('timCustomerLastName').value;
	var email       = document.getElementById('timCustomerEmail').value;
	var phoneNumber = document.getElementById('timCustomerPhoneNumber').value;
	var phoneCode   = document.getElementById('timCustomerPhoneCode').value;
	var country     = document.getElementById('timCustomerCountry').value;
	
	error = validateInput('timCustomerCountry', error);
	error = validatePhone('timCustomerPhoneNumber', error);
	error = validateEmail('timCustomerEmail', error);
	error = validateInput('timCustomerLastName', error);
	error = validateInput('timCustomerName', error);

	if (error){
		setButton(1);
		return false;
	}

	var countryId = country.split('-')[0];

	var params = {
		action:        'update_client_profile_api', 
        f_nonce:       timData.f_nonce, 
        id:            id, 
        name:          name, 
        last_name:     lastName, 
        email:         email, 
        phone_number:  phoneNumber, 
        phone_code:    phoneCode, 
        country_id:    countryId, 
        lang:          document.getElementById('timCL').value
	};

	// console.log(params); return;

	var formErrorMsg = document.getElementById('timClientProfileFormErrorMsg');

	timShowSpinner('tim_spinner');

	// jQuery.post(timData.ajaxurl, params, function(response) {
	timPostAjax(timData.ajaxurl, params, function(response){
      	if (response == 'false'){ return false; }

      	var response = JSON.parse(response);
		if (!response.errors){
			var labelProfileUpdated = document.getElementById('timLabelProfileUpdated').value;

			var formSuccessMsg = document.getElementById('timCustomerFormSuccessMsg');
			formSuccessMsg.innerHTML = '<div class="tim_alert tim_alert_lg tim_alert_success">'+ labelProfileUpdated +' <a href="javascript:void(0);" onclick="timCloseAlert(\'timCustomerFormSuccessMsg\')" class="tim_alert_close">x</a></div>';

			timLoadData('load_client_profile');
		}
		else{
			setButton(1);

			var serverErrors = [];
			for(var field in response.errors){
				var error = response.errors[field];
                serverErrors = error.join(', ');
        	}

			formErrorMsg.innerHTML = '<div class="tim_alert tim_alert_danger">'+ serverErrors +'</div>';
		}

		timHideSpinner('tim_spinner');
    });
}

function timCloseAlert(id){
	document.getElementById(id).innerHTML = '';
}

function timCheckUpdateClientPassword(){
	setButton();
	var error = 0;

	var id                   = document.getElementById('timCustomerId').value;
	var password             = document.getElementById('timCustomerPassword').value;
	var passwordConfirmation = document.getElementById('timCustomerPasswordConfirmation').value;
	
	error = validateInput('timCustomerPasswordConfirmation', error);
	error = validateInput('timCustomerPassword', error);
	error = validatePasswordConfirmation('timCustomerPassword', 'timCustomerPasswordConfirmation', error);

	if (error){
		setButton(1);
		return false;
	}

	var params = {
		action:   'update_client_profile_password_api', 
        f_nonce:  timData.f_nonce, 
        id:       id, 
        password: password, 
        lang:     document.getElementById('timCL').value
	};

	// console.log(params); return;

	var formErrorMsg = document.getElementById('timClientPasswordFormErrorMsg');

	timShowSpinner('tim_spinner');

	// jQuery.post(timData.ajaxurl, params, function(response) {
	timPostAjax(timData.ajaxurl, params, function(response){
      	if (response == 'false'){ return false; }

      	var response = JSON.parse(response);
		if (!response.errors){
			var labelPasswordUpdated = document.getElementById('timLabelPasswordUpdated').value;

			var formSuccessMsg = document.getElementById('timCustomerFormSuccessMsg');
			formSuccessMsg.innerHTML = '<div class="tim_alert tim_alert_lg tim_alert_success">'+ labelPasswordUpdated +' <a href="javascript:void(0);" onclick="timCloseAlert(\'timCustomerFormSuccessMsg\')" class="tim_alert_close">x</a></div>';

			document.getElementById('timCustomerPassword').value = '';
			document.getElementById('timCustomerPasswordConfirmation').value = '';

			timLoadData('load_client_profile');
		}
		else{
			setButton(1);

			var serverErrors = [];
			for(var field in response.errors){
				var error = response.errors[field];
                serverErrors = error.join(', ');
        	}

			formErrorMsg.innerHTML = '<div class="tim_alert tim_alert_danger">'+ serverErrors +'</div>';
		}

		timHideSpinner('tim_spinner');
    });
}

// Accepted/Declined
function timProcessSecondaryPriceList(option){
	var params = {
		action:  'accept_secondary_price_list', 
		option:  option, 
        f_nonce: timData.f_nonce
	};

	timPostAjax(timData.ajaxurl, params, function(){

		// var data = JSON.parse(response);
		// console.log(data);

      	location.reload();
    });

 	// save cookie 30 days
	// '/' cookie is available in all website
	// timCreateCookie('secondary_price_list_processed', option, 30, '/');
	// location.reload();
}

// function timCreateCookie(name, value, days2expire, path) {
//     var date = new Date();
//     date.setTime(date.getTime() + (days2expire * 24 * 60 * 60 * 1000));
//     var expires = date.toUTCString();
//     document.cookie = name + '=' + value + ';' +'expires=' + expires + ';' +'path=' + path + ';';
// }

function validateInput(id, error){
	var input = document.getElementById(id);
	
	input.classList.remove('tim_error_input');

	if (document.getElementById(id +'Err')){
		document.getElementById(id +'Err').remove();
	}

	if (input.value == '' || input.value == 0){
		input.classList.add('tim_error_input');

		if (document.getElementById(id +'Error')){
			var inputError = document.getElementById(id +'Error').value;
			input.insertAdjacentHTML('afterend', '<div id="'+ id +'Err" class="tim_error_input_msg">'+ inputError +'</div>');
		}

		error = 1;
	}

	return error;
}

function validateEmail(id, error){
	var input = document.getElementById(id);

	input.classList.remove('tim_error_input');

	if (document.getElementById(id +'Err')){
		document.getElementById(id +'Err').remove();
	}

	if (input.value == '' ){ //  || input.value == 0
		input.classList.add('tim_error_input');
		
		if (document.getElementById(id +'Error')){
			var inputError = document.getElementById(id +'Error').value;
			input.insertAdjacentHTML('afterend', '<div id="'+ id +'Err" class="tim_error_input_msg">'+ inputError +'</div>');
		}

		error = 1;
	}
	else{
		var filter = /^[A-Za-z][A-Za-z0-9_.]*@[A-Za-z0-9_-]+\.[A-Za-z0-9_.]+[A-za-z]$/;
		if (!(filter.test(input.value))){
			input.classList.add('tim_error_input');

			if (document.getElementById(id +'Invalid')){
				var inputError = document.getElementById(id +'Invalid').value;
				input.insertAdjacentHTML('afterend', '<div id="'+ id +'Err" class="tim_error_input_msg">'+ inputError +'</div>');
			}

			error = 1;
		}
	}

	return error;
}

function validatePhone(id, error){
	var input = document.getElementById(id);

	input.classList.remove('tim_error_input');

	if (document.getElementById(id +'Err')){
		document.getElementById(id +'Err').remove();
	}

	if (input.value == '' || input.value == 0){
		input.classList.add('tim_error_input');

		if (document.getElementById(id +'Error')){
			var inputError = document.getElementById(id +'Error').value;
			input.insertAdjacentHTML('afterend', '<div id="'+ id +'Err" class="tim_error_input_msg">'+ inputError +'</div>');
		}

		error = 1;
	}
	else{
		var filter = /^[+]*[(]{0,1}[0-9]{1,3}[)]{0,1}[-\s\./0-9]*$/g;
		if (!(filter.test(input.value))){
			input.classList.add('tim_error_input');

			if (document.getElementById(id +'Invalid')){
				var inputError = document.getElementById(id +'Invalid').value;
				input.insertAdjacentHTML('afterend', '<div id="'+ id +'Err" class="tim_error_input_msg">'+ inputError +'</div>');
			}

			error = 1;
		}
	}

	return error;
}

function validateTaxIdNumber(id, error){
	var input = document.getElementById(id);

	input.classList.remove('tim_error_input');

	if (document.getElementById(id +'Err')){
		document.getElementById(id +'Err').remove();
	}

	if (input.value == '' || input.value == 0){
		input.classList.add('tim_error_input');

		if (document.getElementById(id +'Error')){
			var inputError = document.getElementById(id +'Error').value;
			input.insertAdjacentHTML('afterend', '<div id="'+ id +'Err" class="tim_error_input_msg">'+ inputError +'</div>');
		}

		error = 1;
	}
	else{
		var taxIdCode = document.getElementById('timGuestTaxIdCode').value;

		var taxIdNumberLength;

		switch (taxIdCode){
			case '01': // Individual
				taxIdNumberLength = 9;
			break;
			case '02': // Company
				taxIdNumberLength = 10;
			break;
			case '03': // DIMEX
				taxIdNumberLength = 10;
			break;
			case '04': // NITE
				taxIdNumberLength = 10;
			break;

			case '06': // Passport
				taxIdNumberLength = 9;
			break;
		}

		if (input.value.length < taxIdNumberLength){
			input.classList.add('tim_error_input');

			if (document.getElementById(id +'Invalid')){
				var inputError = document.getElementById(id +'Invalid').value;
				input.insertAdjacentHTML('afterend', '<div id="'+ id +'Err" class="tim_error_input_msg">'+ inputError +'</div>');
			}

			error = 1;
		}
	}

	return error;
}

function validatePasswordConfirmation(optionPassword, optionPasswordConfirmation, error){
	var inputPassword             = document.getElementById(optionPassword);
	var inputPasswordConfirmation = getElementById(optionPasswordConfirmation);

	var formErrorMsg = document.getElementById('timClientPasswordFormErrorMsg');
	formErrorMsg.innerHTML = '';
	if (inputPassword.value != '' && inputPasswordConfirmation.value != '' && inputPassword.value != inputPasswordConfirmation.value){
		inputPassword.className = 'tim_error_input';
		inputPasswordConfirmation.className = 'tim_error_input';

		if (document.getElementById(id +'Error')){
			var inputError = getElementById(id +'Error').value;
			formErrorMsg.innerHTML ='<br /><div class="tim_alert tim_alert_danger">'+ inputError +'</div>';
		}
		
		// inputPassword.addClass('tim_error_input');
		// inputPasswordConfirmation.addClass('tim_error_input');

		error = 1;
	}

	return error;
}

// 1 = activate / 0 = disable
function setButton(option){
	// if (typeof jQuery('.timSendButton') !== 'undefined') {
	if (typeof document.getElementsByClassName('timSendButton')[0] !== 'undefined') {
		// var button   = jQuery('.timSendButton');
		var button = document.getElementsByClassName('timSendButton')[0];
		var disabled = ( option ) ? false : true;

		// button.attr('disabled', disabled);
		button.disabled = disabled;
	}
}


function timShowSpinner(option){
	document.getElementsByClassName(option)[0].style.display = 'inline';
}

function timHideSpinner(option){
	document.getElementsByClassName(option)[0].style.display = 'none';
}

function timRemoveItemFromArray(array, key, value) {
    var index = timFindArrayIndex(array, key, value);
    array.splice(index, 1);
    return array;
}

function timFindItemInArray(array, key, value){
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return array[i];
        }
    }
    return null;
}

// Find index of an array
function timFindArrayIndex(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return i;
        }
    }
    return null;
}


// Date & time functions
// ----------------------------------

function setTimePicker(id, defaultTime){
	var options = {
		//now: '12:35',                          // hh:mm 24 hour format only, defaults to current time
        //twentyFour: true,                   // Display 24 hour format, defaults to false
        //title: 'Timepicker',                   // The Wickedpicker's title,
        timeSeparator: ':',                // The string to put in between hours and minutes (and seconds)
        //secondsInterval: 1,                  // Change interval for seconds, defaults to 1,
        //minutesInterval: 1,                  // Change interval for minutes, defaults to 1
        //beforeShow: null,                    // A function to be called before the Wickedpicker is shown
        //afterShow: null,                     // A function to be called after the Wickedpicker is closed/hidden
        //show: null,                          // A function to be called when the Wickedpicker is shown
        //clearable: false                     // Make the picker's input clearable (has clickable "x")
	};

	if (defaultTime){
		options.now = defaultTime;
	}

	jQuery('.'+ id).wickedpicker(options);
}

// Conver time 1:25 PM to 15:25 to store in DB
function timConvertTimeToDB(departureTime){
	var time = departureTime.split(' ');
		
	var hourRange = time[0].split(':');
	var hour      = hourRange[0];
	var minutes   = hourRange[1];
	var meridiam  = time[1];
	
	if (hour < 10) {
	    hour = '0' + hour;
	}

	if ( meridiam === 'PM' ) {
	    switch (hour){
			case '01': hour = '13'; break;
			case '02': hour = '14'; break;
			case '03': hour = '15'; break;
			case '04': hour = '16'; break;
			case '05': hour = '17'; break;
			case '06': hour = '18'; break;
			case '07': hour = '19'; break;
			case '07': hour = '20'; break;
			case '09': hour = '21'; break;
			case '10': hour = '22'; break;
			case '11': hour = '23'; break;
			case '12': hour = '24'; break;
		}
	}

	return hour +':'+ minutes;
}

// Convert yyyy-mm-dd to correct date, substracting 1 month
function timParseDate(date){
	var mdy = date.split('-');

	return new Date(mdy[0], mdy[1]-1, mdy[2]);
}

function timGetDiffInDays(start, end) {
    // Take the difference between the dates and divide by milliseconds per day.
    // Round to nearest whole number to deal with DST.
	var milliSecondsPerDay = 24 * 60 * 60 * 1000;

	return Math.round( (end - start) / milliSecondsPerDay );
}

// yyyy-mm-dd
function increaseDate(originalDate, days){
	var parts = originalDate.split('-');
	
	var date = new Date(
		parseInt(parts[0], 10),     // year
		parseInt(parts[1], 10) - 1, // month (starts with 0)
		parseInt(parts[2], 10)      // date
	);
	
	date.setDate(date.getDate() + 1);

	parts[0] = '' + date.getFullYear();
	parts[1] = '' + (date.getMonth() + 1);
	
	if (parts[1].length < 2) {
		parts[1] = '0' + parts[1];
	}
	
	parts[2] = '' + date.getDate();
	
	if (parts[2].length < 2) {
		parts[2] = '0' + parts[2];
	}

	return parts.join('-');
}

function getTodayDate(){
	var today = new Date();
	var dd    = String(today.getDate()).padStart(2, '0');
	var mm    = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
	var yyyy  = today.getFullYear();

	today = yyyy +'-'+ mm +'-'+ dd;

	return today;
}

function timFormatHour( hour ){
	var hourRange = hour.split(':');
	var hours     = hourRange[0];
	var minutes   = hourRange[1];
	var meridian  = '';

	if ( hours < '12' ){
		meridian = 'AM';
	}
	else if ( hours == '12' ){
		meridian = 'MD';
	}
	else{
		meridian = 'PM';
	}

	hour = (hour == '00:00') ? '12:00' : hour;

	value = hour +' '+ meridian;

	return value;
}


// Round to at most 2 decimal places (only if necessary)
function timRoundNum(number, decimals) {
	number   = isNaN(number) ? 0 : number;
    decimals = decimals || 2;

    return Number(Math.round(number+'e'+decimals)+'e-'+decimals);


    // num = Math.round(num * 100) / 100;
    // return parseFloat(num);
}

// Only numbers "0123456789"
function timOnlyNumbers(e) {
	if (e.keyCode) code = e.keyCode;
	else if (e.which) code = e.which;
	if (code == 8 || code == 9 || code == 13) return true;
	var character = String.fromCharCode (code);
	return '0' <= character && character <= '9';
}

// fix to n decimales only if necessary
function timCurrencyFormat(amount, decimals) {
	decimals = decimals || 2;

    return amount.toFixed(decimals).replace(/\.?0*$/g,'');
}

function timPostAjax(url, data, success) {
    var params = typeof data == 'string' ? data : Object.keys(data).map(
        function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
    ).join('&');

    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('POST', url);
    xhr.onreadystatechange = function() {
        if (xhr.readyState>3 && xhr.status==200) {
        	success(xhr.responseText);
        }
    };
    
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(params);

    return xhr;
}