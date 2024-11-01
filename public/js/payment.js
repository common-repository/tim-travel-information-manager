function timSelectPaymentGateway(option, lang){ // TODO: do it Ajax
	var params = {
		action:  'load_payment_form', 
		f_nonce:  timData.f_nonce, 
		option:   option, 
        lang:     lang
	};

	timShowSpinner('tim_travel_form_spinner');

	var loadDataResult = document.getElementById('timPaymentBox');

	loadDataResult.innerHTML = '';
	// jQuery.post(timData.ajaxurl, params, function(response) {
	timPostAjax(timData.ajaxurl, params, function(response){
		loadDataResult.innerHTML = response;

		if (option === 'bac_ecommerce'){
			document.getElementById('ccnumber').focus();
		}
		else if (option === 'paypal'){
			loadPaypalPayment();
		}

		document.querySelector('.tim-policies').style.display = 'inline-block';

		timHideSpinner('tim_travel_form_spinner');
	});
}

function timProcessBacEcommercePayment(){
	setButton();

	var error = 0;

	error = timValidateClientDetails();

	error = validateCreditCardNumber('ccnumber', error);
	error = validateInput('cvv', error);
	error = validateCreditCardExpirationDate('expDate', error);
	error = validateInput('ccName', error);
	
	if (error){ //  || !document.getElementById('timValidEmail').value / add timValidEmail = 1 when $_SESSION['guestData']
		setButton(1);
		return false;
	}

	var paymentGateway = document.getElementById('timPaymentGateway').value +'_'+ document.getElementById('timCurrencyCode').value.toLowerCase();

	var params = {
		action:         'process_ecommerce_payment', 
		f_nonce:         timData.f_nonce, 
		id:              document.getElementById('timBookingId').value, 
		name:            document.getElementById('timGuestName').value, 
		last_name:       document.getElementById('timGuestLastName').value, 
		email:           document.getElementById('timGuestEmail').value, 
		country_id:      document.getElementById('timGuestCountry').value.split('-')[0], 
		phone_number:    document.getElementById('timGuestPhoneNumber').value, 
		phone_code:      document.getElementById('timGuestPhoneCode').value, 
		tax_id_code:     document.getElementById('timGuestTaxIdCode').value, 
		tax_id_number:   document.getElementById('timGuestTaxIdNumber').value, 
		notes:           document.getElementById('timNotes').value, 
		lang:            document.getElementById('timCL').value, 
		payment_gateway: paymentGateway, 
		pg:              'bac' 

		// ccType: document.getElementById('ccType').value, 
		// ccName: document.getElementById('ccName').value
	};

	var clientId = document.getElementById('timClientId').value;
	if (clientId){ // Logged user
		params.client_id = clientId;
	}

	timShowSpinner('tim_spinner');

	timPostAjax(timData.ajaxurl, params, function(response){
		var orderUrl = document.getElementById('timHomeUrl').value +'order/?act=paid&pg=bac'; // Company must register the correct url in the gateway, remember es/
		
		document.getElementById('redirect').value = orderUrl;

		var form = document.getElementById('tim_checkout_form');
		form.action = document.getElementById('bac_endpoint').value;
		form.submit();

		return;	
	});
}

function timProcessBcrEcommercePayment(){
	setButton();

	var error = 0;

	error = timValidateClientDetails();
	
	if (error){
		setButton(1);
		return false;
	}

	var paymentGateway = document.getElementById('timPaymentGateway').value +'_'+ document.getElementById('timCurrencyCode').value.toLowerCase();

	var params = {
		action:         'process_ecommerce_payment', 
		f_nonce:         timData.f_nonce, 
		id:              document.getElementById('timBookingId').value, 
		name:            document.getElementById('timGuestName').value, 
		last_name:       document.getElementById('timGuestLastName').value, 
		email:           document.getElementById('timGuestEmail').value, 
		country_id:      document.getElementById('timGuestCountry').value.split('-')[0], 
		phone_number:    document.getElementById('timGuestPhoneNumber').value, 
		phone_code:      document.getElementById('timGuestPhoneCode').value, 
		tax_id_code:     document.getElementById('timGuestTaxIdCode').value, 
		tax_id_number:   document.getElementById('timGuestTaxIdNumber').value, 
		notes:           document.getElementById('timNotes').value, 
		lang:            document.getElementById('timCL').value, 
		payment_gateway: paymentGateway, 
		pg:              'bcr'
	};

	var clientId = document.getElementById('timClientId').value;
	if (clientId){ // Logged user
		params.client_id = clientId;
	}

	timShowSpinner('tim_spinner');
	// timHideSpinner('tim_travel_form_spinner');

	timPostAjax(timData.ajaxurl, params, function(response){
		// var orderUrl = document.getElementById('timOrderUrl').value +'?act=paid&pg=bcr';
		var orderUrl = document.getElementById('timHomeUrl').value +'order/?act=paid&pg=bcr'; // Company must register the correct url in the gateway, remember es/
		
		document.getElementById('redirect').value = orderUrl;

		var form = document.getElementById('tim_checkout_form');
		form.action = document.getElementById('bcr_endpoint').value;
		form.submit();

		return;	
	});
}

function timProcessPayLaterPayment(){
	setButton(); // disable

	var error = 0;

	error = timValidateClientDetails();

	if (error){
		setButton(1);
		return false;
	}

	var lang = document.getElementById('timCL').value;

	var params = {
		action:         'process_pay_later_payment', 
		f_nonce:         timData.f_nonce, 
		id:              document.getElementById('timBookingId').value, 
		name:            document.getElementById('timGuestName').value, 
		last_name:       document.getElementById('timGuestLastName').value, 
		email:           document.getElementById('timGuestEmail').value, 
		country_id:      document.getElementById('timGuestCountry').value.split('-')[0], 
		phone_number:    document.getElementById('timGuestPhoneNumber').value, 
		phone_code:      document.getElementById('timGuestPhoneCode').value, 
		tax_id_code:     document.getElementById('timGuestTaxIdCode').value, 
		tax_id_number:   document.getElementById('timGuestTaxIdNumber').value, 
		notes:           document.getElementById('timNotes').value, 
		lang:            lang, 
		payment_gateway: document.getElementById('timPaymentGateway').value
	};

	var clientId = document.getElementById('timClientId').value;
	if (clientId){ // Logged user
		params.client_id = clientId;
	}

	var resultContentError = document.getElementById('timCustomerBookingFormErrorMsg');
	resultContentError.innerHTML = '';

	timShowSpinner('tim_spinner');

	// console.log(params); return;
	
	timPostAjax(timData.ajaxurl, params, function(response){
		if (response == 'false'){ return false; }

		timHideSpinner('tim_spinner');

		var response = JSON.parse(response);
		// console.log(response);

		if (!response.errors){
			var orderUrl    = document.getElementById('timHomeUrl').value +'order/';
			window.location = orderUrl +'?act=done&oid='+ response.id +'&onm='+ response.booking_number +'&lng='+ lang;
		}
		else{
			setButton(1); // enable

			resultContentError.innerHTML = '<div class="tim_alert tim_alert_danger">'+ response.errors +'</div><br />';
		}
	});
}

function loadPaypalPayment(){
	var timGetPaypalLocate = function (lang){
		var locale;
		switch (lang){
			case 'es': locale = 'es_ES'; break;
			case 'de': locale = 'de_DE'; break;
			default: locale = 'en_US';
		}

		return locale;
	};

	var lang = document.getElementById('timCL').value;

	var locale = timGetPaypalLocate(lang);

	var apiUrl        = document.getElementById('timApiUrl').value;
	var bookingId     = document.getElementById('timBookingId').value;
	var subdomain     = document.getElementById('timSubdomain').value;
	var companyApiKey = document.getElementById('timCompanyApiKey').value;
	var domainApiKey  = document.getElementById('timDomainApiKey').value;

	var CREATE_PAYMENT_URL  = apiUrl +'/create_paypal_payment';
	var EXECUTE_PAYMENT_URL = apiUrl +'/execute_paypal_payment';

	var paymentGateway = document.getElementById('timPaymentGateway').value;

	var formErrorMsg = document.getElementById('timCustomerBookingFormErrorMsg');
	formErrorMsg.innerHTML = '';

	function isNameValid() {
		return document.getElementById('timGuestName').value;
	}

	function isLastNameValid() {
		return document.getElementById('timGuestLastName').value;
	}

	function isEmailValid() {
		var valid = true;

		var email = document.getElementById('timGuestEmail').value;
		if (!email){
			valid = false;
		}
		else{
			var filter = /^[A-Za-z][A-Za-z0-9_.]*@[A-Za-z0-9_-]+\.[A-Za-z0-9_.]+[A-za-z]$/;
			if (!(filter.test(email))){
				valid = false;
			}
		}

		return valid;
	}

	function isPhoneValid() {
		var valid = true;

		var phone = document.getElementById('timGuestPhoneNumber').value;
		if (!phone){
			valid = false;
		}
		else{
			var filter = /^[+]*[(]{0,1}[0-9]{1,3}[)]{0,1}[-\s\./0-9]*$/g
			if (!(filter.test(phone))){
				valid = false;
			}
		}

		return valid;
	}

	function isCountryValid() {
		return document.getElementById('timGuestCountry').value;
	}

	function isTaxIdCodeValid() {
		return document.getElementById('timGuestTaxIdCode').value;
	}

	function isTaxIdNumberValid() {
		return document.getElementById('timGuestTaxIdNumber').value;
	}

	function onFormChange(handler) {
		jQuery('#timGuestName').on('change', handler);
		jQuery('#timGuestLastName').on('change', handler);
		jQuery('#timGuestEmail').on('change', handler);
		jQuery('#timGuestPhoneNumber').on('change', handler);
		jQuery('#timGuestCountry').on('change', handler);
		jQuery('#timGuestTaxIdCode').on('change', handler);
		jQuery('#timGuestTaxIdNumber').on('change', handler);
	}

	function toggleButton(actions) {
		var isFormValid = true;

		if ( !isNameValid() ){
			isFormValid = false;
		}
		else if ( !isLastNameValid() ) {
			isFormValid = false;
		}
		else if ( !isEmailValid() ) {
			isFormValid = false;
		}
		else if ( !isPhoneValid() ) {
			isFormValid = false;
		}
		else if ( !isCountryValid() ) {
			isFormValid = false;
		}
		else if ( !isTaxIdCodeValid() ) {
			isFormValid = false;
		}
		else if ( !isTaxIdNumberValid() ) {
			isFormValid = false;
		}
		/*else if(document.getElementById('timValidEmail').value == 0){
			isFormValid = false;
		}*/

		return isFormValid ? actions.enable() : actions.disable();
	}

	var clientId = document.getElementById('timClientId').value;

	paypal.Buttons({
	    style: {
	    	size:  'medium',
	    	color: 'blue',
	    	shape: 'pill',
	    	label: 'checkout'
	    }, 

	    validate: function(actions) {
	    	var isClientLogged = document.getElementById('timIsClientLogged').value;
	    	if (isClientLogged){
	    		return actions.enable();
	    	}

	    	toggleButton(actions);

	    	onFormChange(function() {
	    		toggleButton(actions);
	    	});
	    },

	    onClick: function() {
	    	setButton();

	    	var error = timValidateClientDetails(0);

			if (error){
				setButton(1);
				return false;
			}

			return true;
	    },

        createOrder: function() {
        	var createOrderParams = {
				sub:             subdomain,
				cak:             companyApiKey, 
				dak:             domainApiKey,
				id:              bookingId,
				payment_gateway: paymentGateway, 
				email:           document.getElementById('timGuestEmail').value,
				lang:            lang
			};

			if (clientId){ // Logged user
				createOrderParams.client_id = clientId;
			}

            return fetch(CREATE_PAYMENT_URL, {
                method: 'post', 
                headers: {
        			'content-type': 'application/json'
      			},
				body: JSON.stringify(createOrderParams)
            }).then(function(res) {
                return res.json();
            }).then(function(response) {
                return response.id;
            });
        }, 

        onApprove: function(data) {
			var onApproveParams = {
				orderID:         data.orderID,
	            sub:             subdomain, 
	            cak:             companyApiKey, 
	        	dak:             domainApiKey, 
	        	id:              bookingId,
	        	payment_gateway: paymentGateway,
	        	name:            document.getElementById('timGuestName').value,
	        	last_name:       document.getElementById('timGuestLastName').value, 
	        	email:           document.getElementById('timGuestEmail').value, 
	        	country_id:      document.getElementById('timGuestCountry').value.split('-')[0], 
	        	phone_number:    document.getElementById('timGuestPhoneNumber').value,
	        	phone_code:      document.getElementById('timGuestPhoneCode').value,
	        	tax_id_code:     document.getElementById('timGuestTaxIdCode').value, 
	        	tax_id_number:   document.getElementById('timGuestTaxIdNumber').value, 
	        	notes:           document.getElementById('timNotes').value, 
	        	lang:            lang
			};

			if (clientId){ // Logged user
				onApproveParams.client_id = clientId;
			}

			// TODO: it takes some time to procced the payment. Here we can spinner or disable closing
			timShowSpinner('tim_spinner');

			return fetch(EXECUTE_PAYMENT_URL, {
				method: 'post',
				headers: {
					'content-type': 'application/json'
				},
				body: JSON.stringify(onApproveParams)
			}).then(function(res) {
                return res.json();
            }).then(function(response) {
            	// console.log(response);
            	// timHideSpinner('tim_spinner');
            	// return;

                if (response.errors) {
            		timHideSpinner('tim_spinner');
                	
                	formErrorMsg.innerHTML = '<div class="tim_alert tim_alert_danger">'+ response.errors +'</div><br />';

					if (response.emailInUse){
						document.getElementById('timGuestEmail').classList.add('tim_error_input');
					}

					return;
                }

            	// php sessions not working when change from https to http
				var data = {
					action: 'paypal_order_completed', 
			        f_nonce: timData.f_nonce, // Front-end nonce
			        bookingItemData: response
			    };

				document.getElementById('tim_checkout_form').reset(); // check

				timPostAjax(timData.ajaxurl, data, function(){
					timHideSpinner('tim_spinner');

					var orderUrl    = document.getElementById('timHomeUrl').value +'order/';
					window.location = orderUrl +'?act=done&oid='+ response.id +'&onm='+ response.booking_number +'&lng='+ lang; // booking_language response.booking.language.lang
				});
            });
		}
    }).render('#paypal-button');
}


// Client / Billing details
function timValidateClientDetails(error){
	var isClientLogged = document.getElementById('timIsClientLogged').value;

	if (!isClientLogged){
		error = validateInput('timGuestName', error);
		error = validateInput('timGuestLastName', error);
		error = validateInput('timGuestCountry', error);
		error = validatePhone('timGuestPhoneNumber', error);
		error = validateEmail('timGuestEmail', error);
		error = validateInput('timGuestTaxIdCode', error);
		error = validateTaxIdNumber('timGuestTaxIdNumber', error);
	}

	return error;
}

// visa, mastercad, american express, discover, dinersclub, jcb
function validateCreditCardNumber(id, error){
	var visa       = /^(?:4[0-9]{12}(?:[0-9]{3})?)$/;
	var mastercad  = /^(?:5[1-5][0-9]{14})$/;
	var american   = /^(?:3[47][0-9]{13})$/;
	var discover   = /^(?:6(?:011|5[0-9][0-9])[0-9]{12})$/;
	var dinersclub = /^(?:3(?:0[0-5]|[68][0-9])[0-9]{11})$/;
	var jcb        = /^(?:(?:2131|1800|35\d{3})\d{11})$/;

	var input = document.getElementById(id);

	// input.className = '';
	input.classList.remove('tim_error_input');

	if (document.getElementById(id +'Err')){
		document.getElementById(id +'Err').remove();
	}

	var invalidInput = 0;

	if (input.value == ''){ // Keep ==
		invalidInput = 1;
	}
	else{
		var ccType = ''; 
		if (input.value.match(visa)){
			ccType = 'Visa';
		}
		else if(input.value.match(mastercad)){
			ccType = 'Mastercad';
		}
		else if(input.value.match(american)){
			ccType = 'American Express';
		}
		else if(input.value.match(discover)){
			ccType = 'Discover';
		}
		else if(input.value.match(dinersclub)){
			ccType = 'Dinersclub';
		}
		else if(input.value.match(jcb)){
			ccType = 'Jcb';
		}
		else{
			invalidInput = 1;
		}
	}

	if (invalidInput){
		// input.className = 'tim_error_input';
		input.classList.add('tim_error_input');

		// var inputError = document.getElementById(id +'Error').value;
		// input.insertAdjacentHTML('afterend', '<div id="'+ id +'Err" class="tim_error_input_msg">'+ inputError +'</div>');

		return 1;
	}

	document.getElementById('ccType').value = ccType;

	return error;



	/*if (input.value == '' || (
		!input.value.match(visa) && 
		!input.value.match(mastercad) && 
		!input.value.match(american) &&
		!input.value.match(discover) && 
		!input.value.match(dinersclub) && 
		!input.value.match(jcb)) ){ //  || input.value == 0 Keep ==
			error = 1;

		input.className = 'tim_error_input';

		var inputError = document.getElementById(id +'Error').value;
		input.insertAdjacentHTML('afterend', '<div id="'+ id +'Err" class="tim_error_input_msg">'+ inputError +'</div>');

		return 1;
	}

	return error;*/
}

function validateCreditCardExpirationDate(id, error){
	var expMonth = document.getElementById('expMonth');
	var expYear  = document.getElementById('expYear');

	expMonth.classList.remove('tim_error_input');
	expYear.classList.remove('tim_error_input');

	var date = new Date();
	var day  = date.getDate();

	var expirationDate = expMonth.value +'/'+ day +'/'+ expYear.value;
	var currentDate = setCurrentDate();

	if (document.getElementById(id +'Err')){
		document.getElementById(id +'Err').remove();
	}

	if (Date.parse(currentDate) > Date.parse(expirationDate)){
		expMonth.classList.add('tim_error_input');
		expYear.classList.add('tim_error_input');

		// var inputError = document.getElementById(id +'Error').value;
		// expMonth.insertAdjacentHTML('afterend', '<div id="'+ id +'Err" class="tim_error_input_msg">'+ inputError +'</div>');

		return 1;
	}

	var ccexp = expMonth.value + expYear.value.substr(-2);
	document.getElementById('ccexp').value = ccexp;

	return error;
}

// mm/dd/yyy
function setCurrentDate(){
	var date  = new Date();
	var day   = date.getDate();      if (day < 10){ day = '0'+ day; }
	var month = date.getMonth() + 1; if (month < 10){ month = '0'+ month; }
	var year  = date.getFullYear();

	date = month +'/'+ day +'/'+ year;

	return date;
}

function timApplyDiscountCoupon() {
	var button = document.getElementById('timApplyCodeBtn');
	button.disable = true;
	
	var error = 0;

	error = validateInput('timCouponCode', error);

	if (error){
		button.disable = false;
		return false;
	}

	var params = {
		action:      'apply_discount_coupon_to_order_api', 
        f_nonce:     timData.f_nonce, // Front-end nonce
        booking_id:  document.getElementById('timBookingId').value, 
        coupon_code: document.getElementById('timCouponCode').value, 
        lang:        document.getElementById('timCL').value
	};

	var couponCodeInvalid = document.getElementById('timCouponCodeInvalid');
	couponCodeInvalid.innerHTML = '';

	timShowSpinner('tim_spinner');
    
	timPostAjax(timData.ajaxurl, params, function(response){
      	timHideSpinner('tim_spinner');

		if (response === 'error'){
			button.disable = false;

			// couponCodeInvalid.innerHTML = '<div class="tim_error_input_msg">'+ response.errors +'</div>';

			var errorCouponMsg = document.getElementById('timLabelErrorCoupon').value;
			couponCodeInvalid.innerHTML = '<div class="tim_error_input_msg">'+ errorCouponMsg +'</div>';			

			return;
		}

		document.getElementById('timCheckoutTotals').innerHTML = response;
    });
}

function timDeleteDiscountCoupon() {
	var params = {
		action:     'delete_discount_coupon_to_order_api', 
        f_nonce:    timData.f_nonce, // Front-end nonce
        booking_id: document.getElementById('timBookingId').value, 
        lang:       document.getElementById('timCL').value
	};

	// timShowSpinner('tim_spinner');

	timPostAjax(timData.ajaxurl, params, function(response){
      	// timHideSpinner('tim_spinner');

		if (response !== 'error'){
      		document.getElementById('timCheckoutTotals').innerHTML = response;
		}
    });
}





// for params in redirect url - use this if problems in production
/*function timProcessBacEcommercePayment(){
	setButton();

	var error = 0;

	error = timValidateClientDetails();
	
	error = validateCreditCardNumber('ccnumber', error);
	error = validateInput('cvv', error);
	error = validateCreditCardExpirationDate('expDate', error);
	error = validateInput('ccName', error);
	
	if (error){ //  || !document.getElementById('timValidEmail').value / add timValidEmail = 1 when $_SESSION['guestData']
		setButton(1);
		return false;
	}

	var orderUrl = document.getElementById('timHomeUrl').value +'order/?act=paid&pg=bac';
	// document.getElementById('timOrderUrl').value +'?act=paid&pg=bac'+

	// Direct form
	var redirect = 
		orderUrl +
		'&id='+ document.getElementById('timBookingId').value +
		'&name='+ document.getElementById('timGuestName').value +
		'&last_name='+ document.getElementById('timGuestLastName').value +
		'&email='+ document.getElementById('timGuestEmail').value +
		'&country_id='+ document.getElementById('timGuestCountry').value.split('-')[0] +
		'&phone_number='+ document.getElementById('timGuestPhoneNumber').value +
		'&phone_code='+ document.getElementById('timGuestPhoneCode').value +
		'&tax_id_code='+ document.getElementById('timGuestTaxIdCode').value +
		'&tax_id_number='+ document.getElementById('timGuestTaxIdNumber').value +
		'&notes='+ document.getElementById('timNotes').value +
		'&lng='+ document.getElementById('timCL').value + 
		
		'&ccType='+ document.getElementById('ccType').value + 
		'&ccName='+ document.getElementById('ccName').value;

	var clientId = document.getElementById('timClientId').value; 

	if (clientId){ // Logged user
		redirect += '&client_id='+ clientId;
	}

	timShowSpinner('tim_spinner');

	document.getElementById('redirect').value = redirect;

	var form = document.getElementById('tim_checkout_form');
	form.action = document.getElementById('bac_endpoint').value;
	form.submit();

	return;	
}*/