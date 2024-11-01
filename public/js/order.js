function setTaxIdNumberMask(type){
	var disabled;

	var timGuestTaxIdNumber = jQuery('#timGuestTaxIdNumber');

	timGuestTaxIdNumber.val('');

	if (type){
		disabled = false;

		var mask;

		switch (type){
			case '01': // Individual
				mask = '9 9999 9999'; // 9
			break;
			case '02': // Company
				mask = '9 999 999 999'; // 10
			break;
			case '03': // // Extranjero - DIMEX
				mask = '999999999999'; // 11-12
			break;
			
			// case '04': // NITE
			// 	mask = '9999999999'; // 10
			// break;
			// case '06': // Passport
			// 	mask = '999999999';
			// break;
		}

		timGuestTaxIdNumber.inputmask({
			mask:               mask,
			autoUnmask:         true,
	    	removeMaskOnSubmit: true
		});	
	}
	else{
		disabled = true;
		// timGuestTaxIdNumber.val('');
	}

	timGuestTaxIdNumber.prop('disabled', disabled);
}

function timAddItemToOrder(bookingItemData) {
	var currencyId = document.getElementById('timUserCurrency').value;

	var departureTime = document.getElementById('departureTime');
	if (departureTime){
		bookingItemData.departure_time = timConvertTimeToDB(departureTime.value);
	}

	if (document.getElementById('timProviderId')){
		bookingItemData.provider_id = document.getElementById('timProviderId').value;
	}

	var params = {
		action:          'add_item_to_order_api', 
        f_nonce:         timData.f_nonce, // Front-end nonce
        lang:            document.getElementById('timCL').value, 
        currency_id:     currencyId, 
        bookingItemData: bookingItemData
	};

	// console.log(params); return;

	if (document.getElementById('timAvailabilityResult')){
		var availabilityResult = document.getElementById('timAvailabilityResult');
		availabilityResult.style.opacity = 0.5;
	}
    
    timShowSpinner('tim_spinner');

	jQuery.post(timData.ajaxurl, params, function(response) {
	// timPostAjax(timData.ajaxurl, params, function(response){ // Cannot send multi object => action: abc, bookingItemData: {}
		if (response == 'false'){ return false; }

		timHideSpinner('tim_spinner');

		if (document.getElementById('timAvailabilityResult')){
			availabilityResult.style.opacity = 1;
		}

    	window.location = document.getElementById('timHomeUrl').value +'cart/';
    });
}

function timOpenEditItemFromOrder(bookingItemId, type, priceListId) {
	// var href = document.getElementById('timUrl').value +
	// 	'/public/includes/class-tim-travel-manager-ajax.php?option=cart&id='+ 
	// 	bookingItemId +'&type='+ type +'&pLId='+ priceListId;

	// openModal(href);
	// return;

	var params = {
		action:  'open_modal', 
        f_nonce: timData.f_nonce, // Front-end nonce
        option:  'cart', 
        id:      bookingItemId, 
        type:    type,
        pLId:    priceListId, 
        lang:    document.getElementById('timCL').value
	};

	timPostAjax(timData.ajaxurl, params, function(response){
      	openModal(response);
    });
}

function timUpdateItemFromOrder(bookingItemData){
	var params = {
		action:          'update_item_from_order_api', 
        f_nonce:         timData.f_nonce, // Front-end nonce
        bookingItemData: bookingItemData, 
        lang:            document.getElementById('timCL').value
	};

	// console.log(bookingItemData);
	// return;

	timShowSpinner('tim_spinner');
    
	jQuery.post(timData.ajaxurl, params, function(response) {
	// timPostAjax(timData.ajaxurl, params, function(response){ // Cannot send multi object => action: abc, bookingItemData: {}
      	if (response == 'false'){ return false; }

      	document.getElementById('timCartDetail').innerHTML = response;

      	timHideSpinner('tim_spinner');

      	jQuery.fancybox.close();
    });
}

// Add pickup and/or dropoff to order
function timApplyPlacesToItemOrder(bookingItemId){
	var bookingItemData = {
		booking_item_id: bookingItemId
	};
	
	var pickUpPlaceId = document.getElementById('timPickupPlaceId_'+ bookingItemId).value;
	if (pickUpPlaceId){
		bookingItemData.pickup_place_id = pickUpPlaceId;
	}

	var dropOffPlaceId = document.getElementById('timDropoffPlaceId_'+ bookingItemId).value;
	if (dropOffPlaceId){
		bookingItemData.dropoff_place_id = dropOffPlaceId;
	}

	var params = {
		action:         'apply_places_to_order_api', 
        f_nonce:         timData.f_nonce, // Front-end nonce
        bookingItemData: bookingItemData, 
        lang:            document.getElementById('timCL').value
	};

	// console.log(bookingItemData);
	// return;

	timShowSpinner('tim_spinner');
    
	jQuery.post(timData.ajaxurl, params, function(response) {
	// timPostAjax(timData.ajaxurl, params, function(response){ // Cannot send multi object => action: abc, bookingItemData: {}
      	if (response == 'false'){ return false; }

      	document.getElementById('timCartDetail').innerHTML = response;

      	timHideSpinner('tim_spinner');
    });
}

function timRemoveItemFromOrder(bookingItemId){
	timShowSpinner('tim_spinner');
	
	var params = {
		action:        'remove_item_from_order_api', 
        f_nonce:       timData.f_nonce, // Front-end nonce
        bookingItemId: bookingItemId, 
        lang:          document.getElementById('timCL').value
	};
    
	timPostAjax(timData.ajaxurl, params, function(response){
      	if (response == 'false'){ return false; }

      	timHideSpinner('tim_spinner');

      	// Importatn as session is only executed via ajax, and removing an item does not reload the page
      	var totalBookingItems = document.getElementById('timTotalBookingItems').value - 1;

      	document.getElementById('timCartDetail').innerHTML = response;
      	document.getElementById('timBTI').innerHTML = totalBookingItems;

      	// Cart enable
      	if (document.getElementById('timOTI')){
      		document.getElementById('timOTI').innerHTML = totalBookingItems;
      	}
    });
}

function timSelectPickupPlace(option, bookingItemId){
	var pickupValid  = true;
	var dropoffValid = true;

	if (option == 'both' || option == 'pickup'){
		var pickUpPlaceId = document.getElementById('timPickupPlaceId_'+ bookingItemId).value;
		if (!pickUpPlaceId || pickUpPlaceId == null){
			pickupValid = false;
		}
	}

	if (option == 'both' || option == 'dropoff'){
		var dropOffPlaceId = document.getElementById('timDropoffPlaceId_'+ bookingItemId).value;
		if (!dropOffPlaceId || dropOffPlaceId == null){
			dropoffValid = false;
		}
	}
	
	if (pickupValid && dropoffValid){
		document.getElementById('addPlaces_'+ bookingItemId).disabled = false;
	}
}

function timUnavailableProductDates(date) {
	// unavailableDates = typeof(unavailableDates) != 'undefined' ? unavailableDates : [];

	var unavailableDates = document.getElementById('unavailableDates') 
								? JSON.parse(document.getElementById('unavailableDates').value) : [];

	var year  = date.getFullYear();
	var month = (date.getMonth()+1);
	var day   = date.getDate(); 
	
	if (month < 10) month = '0' + month;
    if (day < 10) day = '0' + day;

    var dmy = year + '-' + month + '-' +day;

	if (jQuery.inArray(dmy, unavailableDates) != -1) {
		return [false, 'disabled', '']; // unAvailable
	} else {
		return [true, '','']; // Available
	}
}

function timProductDatePicker(bookingType, numberOfMonths, lang, bookingItemStartDate, bookingItemEndDate){
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional[''+ lang +'']);

	var from = jQuery('#timFrom');

	if (bookingType === 'hotel'){
		var to = jQuery('#timTo');
	}

	var dateFormatDB = 'yy-mm-dd'; // default datepicker format is mm/dd/yyyy

	from.datepicker({
		firstDay:       1,
		// beforeShowDay:  timUnavailableProductDates, // on first load only
		minDate:        0, 
		numberOfMonths: numberOfMonths, 
		altField:       '#timFromDB',
		altFormat:      dateFormatDB, 
		// dateFormat:    'DD d M, yy',
		onSelect: function() {
			displayAvailabilityLabel();

			if (!bookingItemStartDate && bookingType !== 'hotel'){ // New and except hotels
				switch (bookingType){
					case 'tour':
						timCheckTourRates();
					break;

					case 'transportation':
						timCheckTransportationRates();
					break;

					case 'package':
						// timCheckPackageRates();
					break;
				}
			}
			else{ // New or edit
				if (to){
					// Get from date value
					var fromDate = from.datepicker('getDate');

					// Assign 1 day to to date
					var minToDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() + 1);
					to.datepicker('option', 'minDate', minToDate);

					// timCheckHotelAvailability();

			        setTimeout(function(){ // hack
			        	document.getElementById('timTo').focus();
			        }, 10);

			        document.getElementById('timAvailabilityResult').innerHTML = '';
			        document.getElementById('timAvailabilityResultMsg').style.display = 'block';
		        }
			}
		}
	}).datepicker('widget').wrap('<div class="tim-datepicker"/>');

	if (bookingItemStartDate){
		var date = bookingItemStartDate.split('-');
		var year  = date[0];
		var month = date[1]-1;
		var day   = date[2];

		from.datepicker('setDate', new Date(year,month,day));
	}

	if (bookingType === 'hotel'){
		var fromDate  = from.datepicker('getDate');
		var minToDate = (fromDate) ? new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() + 1) : '+1D';

		to.datepicker({
			minDate:        minToDate,
			numberOfMonths: 1, 
			altField:       '#timToDB',
			altFormat:      dateFormatDB, 
			onSelect: function() {
				document.getElementById('timAvailabilityResult').innerHTML = '';
		        document.getElementById('timAvailabilityResultMsg').style.display = 'block';
			}
			// dateFormat:     'DD d M, yy'
		}); //*.attr('readonly', 'readonly')
	}

	if (bookingItemEndDate){
		var date = bookingItemEndDate.split('-');
		var year  = date[0];
		var month = date[1]-1;
		var day   = date[2];

		to.datepicker('setDate', new Date(year,month,day));
	}

	if (bookingType !== 'package'){ // remove when package rates are implemented
		displayAvailabilityLabel();
	}

	function displayAvailabilityLabel(){
		if (document.getElementById('timAvailabilityFor')){
			var label = timLabels;

			var labelAvailabilityFor = label.availabilityFor;
			var availabilityFor = labelAvailabilityFor +': <b>'+ from.val() +'</b>';

			document.getElementById('timAvailabilityFor').innerHTML = availabilityFor;
		}
	}
}

function timVerifyOrderForm(){
	setButton();
	var error = 0;

	var orderID = document.getElementById('timOrderId').value;
	var email   = document.getElementById('timCustomerEmail').value;
	
	error = validateEmail('timCustomerEmail', error);
	error = validateInput('timOrderId', error);

	if (error){
		setButton(1);
		return false;
	}

	var lang = document.getElementById('timCL').value;

	var params = {
		action:       'verify_order_api', 
        f_nonce:       timData.f_nonce, 
        bookingNumber: orderID,
        clientEmail:   email, 
        lang:          document.getElementById('timCL').value
	};

	var formErrorMsg = document.getElementById('timCustomerFormErrorMsg');

	timShowSpinner('tim_spinner');

	// jQuery.post(timData.ajaxurl, params, function(response) {
	timPostAjax(timData.ajaxurl, params, function(response){
		if (response == 'false'){ return false; }

		timHideSpinner('tim_spinner');

      	var response = JSON.parse(response);
		if (!response.error){
			var orderUrl    = document.getElementById('timHomeUrl').value +'order/';
			window.location = orderUrl +'?act=view&oid='+ response.id +'&onm='+ response.booking_number +'&lng='+ lang; // response.language.lang booking_language
		}
		else{
			setButton(1);
			formErrorMsg.innerHTML = '<br /><div class="tim_alert tim_alert_danger">'+ response.error +'</div>';
		}
    });
}


// TOURS
// ------------------------------------

function timCheckTourRates(bookingItemId){
	var currencyId     = document.getElementById('timUserCurrency').value;
	var currencyCode   = document.getElementById('timUserCurrencyCode').value;
	var currencySymbol = document.getElementById('timUserCurrencySymbol').value;

    //var now            = new Date();
  	//var currentUTCDate = new Date( now.getTime() + (now.getTimezoneOffset() * 60000)); // Thu Apr 26 2018 20:34:42 GMT-0600 (CST)

  	var currentUTCDate = new Date().toISOString(); // 2018-04-26T22:02:12.059Z

  	var adults   = document.getElementById('timAdults')   ? document.getElementById('timAdults').value   : 0;
  	var children = document.getElementById('timChildren') ? document.getElementById('timChildren').value : 0;
  	var infants  = document.getElementById('timInfants')  ? document.getElementById('timInfants').value  : 0;
  	var seniors  = document.getElementById('timSeniors')  ? document.getElementById('timSeniors').value  : 0;

	var params = {
		action:         'check_tour_rates_api', 
        f_nonce:        timData.f_nonce, 
		currentUTCDate: currentUTCDate, 
		lang:           document.getElementById('timCL').value, 
		currency_id:    currencyId, 
		bookingItemData: {
			booking_type:    'tour', 
			start_date:      document.getElementById('timFromDB').value, // yyyy-mm-dd 
			adults:          parseInt(adults), 
			children:        parseInt(children), 
			infants:         parseInt(infants), 
			seniors:         parseInt(seniors), 
			tour_id:         document.getElementById('timTourId').value, 
			tour_content_id: document.getElementById('timTourContentId').value
		}
	};

  	if (!bookingItemId){ // Add
  		if (document.getElementById('timSPLAccepted')){
  			params.spl_accepted = document.getElementById('timSPLAccepted').value;
  		}

  		if (document.getElementById('timTourDefaultPickupPlaceId')){
			params.bookingItemData.pickup_place_id = document.getElementById('timTourDefaultPickupPlaceId').value;
		}

		if (document.getElementById('timTourDefaultDropoffPlaceId')){
			params.bookingItemData.dropoff_place_id = document.getElementById('timTourDefaultDropoffPlaceId').value;
		}

		timCheckTourRatesAdd(params, currencySymbol);
  	} else { // Edit
  		params.booking_id    = document.getElementById('timBookingId').value;          // in modal
  		params.price_list_id = document.getElementById('timBookingPriceListId').value; // in modal

  		timCheckTourRatesEdit(bookingItemId, params, currencySymbol);
	}
}

function timCheckTourRatesAdd(params, currencySymbol){
	var availabilityResult = document.getElementById('timTourOptions');
	availabilityResult.style.opacity = 0.5;

	timShowSpinner('tim-inline-spinner');

	// CONSIDER STORING THE FULL MONTH AND store 

	jQuery.post(timData.ajaxurl, params, function(response) {
	// timPostAjax(timData.ajaxurl, params, function(response){ // Cannot send multi object => action: abc, bookingItemData: {}
		var label = timLabels;

		//if (response == 'false'){ return false; }
		var content = '';
		if (response == 'false'){
			content = '<div style="text-align:center;"><b>Invalid access.</b><br />Contact the website owner.</div>';
		} else {
		  	var data = JSON.parse(response);

		  	var tour_options             = data.tour_options;
		  	var productSaleTaxes         = data.saleTaxes;
		  	var documentDateExchangeRate = data.documentDateExchangeRate;
		  	var taxesIncluded            = data.taxesIncluded;

		  	// console.log(data);
		  	// return;

		  	params.bookingItemData.exchange_rate_value = documentDateExchangeRate.exchangeRateValue; // value for booking
		  	// params.bookingItemData.spl_accepted        = params.spl_accepted || false;   // for booking

		  	if (params.spl_accepted){
		  		params.bookingItemData.spl_accepted = true;
		  	}

		  	params.bookingItemData.priceListIdByProvider = data.priceListIdByProvider;

		  	if (tour_options){
		  		var adults   = params.bookingItemData.adults;
			  	var children = params.bookingItemData.children;
			  	var infants  = params.bookingItemData.infants;
			  	var seniors  = params.bookingItemData.seniors;

			  	var totalPax = adults + children + infants + seniors;

			  	var atLeastOneScheduleIsAvailable = false;
			  	var atLeastOneScheduleHasRates    = false;

			  	var unavailableDates = JSON.parse(document.getElementById('unavailableDates').value);
			  	var currentStartDate = document.getElementById('timFromDB');

			  	var productCostType = 'per_person';

			  	for ( var i=0; i < tour_options.length; i++ ) {
			  		var tourOption = tour_options[i];

			  		var schedules = '';
			  		if (tourOption.schedules.length > 0){
			      		for ( var j=0; j < tourOption.schedules.length; j++ ) {
			          		var schedule = tourOption.schedules[j];

			          		var availableContent, disabledOption = '';
			          		
			          		params.bookingItemData.subtotal_price = 0;

			          		var productPrice = schedule.date_rates;

							params.bookingItemData.tour_option_id          = tourOption.id;
		          			params.bookingItemData.tour_option_schedule_id = schedule.id;
		          			params.bookingItemData.departure_time          = schedule.departure;
		          			params.bookingItemData.quantity                = totalPax;

							params.bookingItemData.adult_price  = adults   > 0 ? applyExchangeRateConversion(productPrice.adult_price, documentDateExchangeRate) : 0;
							params.bookingItemData.child_price  = children > 0 ? applyExchangeRateConversion(productPrice.child_price, documentDateExchangeRate) : 0;
							params.bookingItemData.infant_price = infants  > 0 ? applyExchangeRateConversion(productPrice.infant_price, documentDateExchangeRate) : 0;
							params.bookingItemData.senior_price = seniors  > 0 ? applyExchangeRateConversion(productPrice.senior_price, documentDateExchangeRate) : 0;
							
							var item_price                       = setDayPrice(productPrice, productCostType, adults, children, infants, seniors, documentDateExchangeRate);
							var base_price                       = setBasePrice(item_price, taxesIncluded, productSaleTaxes);
							var commission_price                 = base_price * (productPrice.client_commission_percentage / 100);
							var net_price                        = base_price - commission_price;
							var pickup_price                     = 0;
							var dropoff_price                    = 0;
							var discount_price                   = 0;
							var subtotal_price                   = net_price + pickup_price + dropoff_price - discount_price;
							var tax_price_percentage             = getProductTaxesTotalPercentage(productSaleTaxes);
							var tax_exoneration_price_percentage = getProductTaxesTotalExonerationPercentage(productSaleTaxes);
							var net_tax_price_percentage         = tax_price_percentage - tax_exoneration_price_percentage;
							
							params.bookingItemData.sale_taxes = setProductTaxesAmounts(subtotal_price, productSaleTaxes);

							var tax_price             = getProductTaxesTotalAmount(params.bookingItemData.sale_taxes);
							var tax_exoneration_price = getProductTaxesTotalExonerationAmount(params.bookingItemData.sale_taxes);
							var net_tax_price         = tax_price - tax_exoneration_price;

							var total_price = subtotal_price + net_tax_price;

							params.bookingItemData.item_price                       = timRoundNum(item_price);
							params.bookingItemData.base_price                       = timRoundNum(base_price);
							params.bookingItemData.commission_price_percentage      = productPrice.client_commission_percentage;
							params.bookingItemData.commission_price                 = timRoundNum(commission_price);
							params.bookingItemData.net_price                        = timRoundNum(net_price);
							params.bookingItemData.pickup_price                     = pickup_price;
							params.bookingItemData.dropoff_price                    = dropoff_price;
							params.bookingItemData.discount_price                   = timRoundNum(discount_price);
							params.bookingItemData.subtotal_price                   = timRoundNum(subtotal_price);
							params.bookingItemData.tax_price_percentage             = tax_price_percentage;
							params.bookingItemData.tax_price                        = timRoundNum(tax_price);
							params.bookingItemData.tax_exoneration_price_percentage = tax_exoneration_price_percentage;
							params.bookingItemData.tax_exoneration_price            = timRoundNum(tax_exoneration_price);
							params.bookingItemData.net_tax_price_percentage         = net_tax_price_percentage;
							params.bookingItemData.net_tax_price                    = timRoundNum(net_tax_price);
							params.bookingItemData.total_price                      = timRoundNum(total_price);

							var price = !taxesIncluded ? params.bookingItemData.subtotal_price : params.bookingItemData.total_price;

							// console.log('sale_taxes: ', params.bookingItemData.sale_taxes);
							// console.log('item_price: ', item_price);
							// console.log('base_price: ', base_price);
							// console.log('net_price: ', net_price);
							// console.log('subtotal_price: ', subtotal_price);
							// console.log('tax_price: ', tax_price);
							// console.log('total_price: ', total_price);

							// console.log(schedule);
						
			          		if (schedule.is_available && schedule.isCutOffValid){ //  && price > 0
			          			if (totalPax >= schedule.min_pax_required){
			          				availableContent = "<a href='javascript:void(0)' class='tim-option-btn' onclick='timAddItemToOrder("+ JSON.stringify(params.bookingItemData) +")'>"+ label.book +"</a>";
			          			} else{
			          				availableContent = '<br /><span class="tim_error_input_msg"><b>'+ label.errorMinPaxRequired +': '+ schedule.min_pax_required +'</b></span>';
			          			}

			          			atLeastOneScheduleIsAvailable = true;
			          		} else {
				                availableContent = '<br /><span class="tim_label tim_label_md tim_label_danger">'+ label.errorNotAvailable +'</span>';
				                disabledOption   = ' tim_text_disabled';
				            }

				            if (productPrice.day){
				            	atLeastOneScheduleHasRates = true;
				            }

				            var priceContent = price > 0 ? currencySymbol + timCurrencyFormat(price) : '-';

				            var duration_time_unit = label.hours; // schedule.duration_time_unit

				            schedules += 
				          		'<div class="tim-option-content">'+
				                    '<div class="tim-departure">'+ 
				                    	'<span class="tim-option-content-title">'+ label.departure +'</span>'+ 
				                    	timFormatHour(schedule.departure) +
				                    '</div>'+
				                    '<div class="tim-duration">'+ 
				                    	'<span class="tim-option-content-title">'+ label.duration +'</span>'+ 
				                    	schedule.duration +' <small>'+ duration_time_unit +'</small>'+
				                    '</div>'+
				                    '<div class="tim-price">'+ 
				                    	'<span class="tim-option-content-title">'+ label.price +'</span>'+ 
				                    	priceContent +
				                    '</div>'+
				                    '<div class="tim-book">'+ 
				                    	availableContent +
				                    '</div>'+
				                '</div>'; //. tim-option-content"
				                // params.bookingItemData.subtotal_price
			          	}
			      	}
			      	else{
			      		schedules += 
			      			'<div class="tim-option-content"><b>* '+ label.errorSelectEarlierDate +'</b></div>';
			      	}

			  		content +=
				  		'<div class="tim-options-wrapper">'+
				  			'<div class="tim-option-title">'+ tourOption.name +'</div>'+
				  			'<div class="tim-option-body">'+
				            	schedules +
							'</div>'+
				  		'</div>';
			  	}

		      	// In case at least on schedule is available and at least one has rates
			  	// Unexpected behaviour
			  	if (!atLeastOneScheduleIsAvailable && atLeastOneScheduleHasRates){
		      		unavailableDates.push(currentStartDate.value);

		      		// console.log(unavailableDates);

		      		var newStartDate = increaseDate(currentStartDate.value, 1);

				    currentStartDate.value = newStartDate;

		      		// var currentUTCDate = new Date().toISOString();
		      		// console.log(currentUTCDate);

		      		// var unavailableDates = ['2020-06-18', '2020-06-19'];

		      		document.getElementById('unavailableDates').value = JSON.stringify(unavailableDates);

					// timProductDatePicker('tour', 1, params.lang, newStartDate);
					jQuery('#timFrom').datepicker('option', 'beforeShowDay', timUnavailableProductDates).datepicker('refresh');

					// timCheckTourRates();
    				// return;
		      	}
		  	} else {
		  		content = '<div style="text-align:center;"><b>No rates found.</b></div>';
		  	}
		}

		timHideSpinner('tim-inline-spinner');

	  	document.getElementById('timTourOptions').innerHTML = content;

	  	availabilityResult.style.opacity = 1;
	});
}

function timCheckTourRatesEdit(bookingItemId, params, currencySymbol){
	var pickUpPlaceIdInput = document.getElementById('timPickupPlaceId');
	var pickUpPlaceId      = pickUpPlaceIdInput.value;
	
	pickUpPlaceIdInput.classList.remove('tim_error_input');
	if (!pickUpPlaceId || pickUpPlaceId == null){
		pickUpPlaceIdInput.classList.add('tim_error_input');
		pickUpPlaceIdInput.focus();
		return false;
	}

	var dropOffPlaceIdInput = document.getElementById('timDropOffPlaceId');
	var dropOffPlaceId      = dropOffPlaceIdInput.value;

	dropOffPlaceIdInput.classList.remove('tim_error_input');
	if (!dropOffPlaceId || dropOffPlaceId == null){
		dropOffPlaceIdInput.classList.add('tim_error_input');
		dropOffPlaceIdInput.focus();
		return false;
	}

	var form = document.forms['tim_update_cart'];

	var tourOptions   = form.elements['schedule'].value.split('-');
	var tourOptionId  = tourOptions[0];
	var scheduleId    = tourOptions[1];
	var departureTime = tourOptions[2];

	params.bookingItemData.booking_item_id         = bookingItemId;
	params.bookingItemData.tour_option_id          = tourOptionId;
	params.bookingItemData.tour_option_schedule_id = scheduleId;
	params.bookingItemData.departure_time          = departureTime;
	params.bookingItemData.pickup_place_id         = pickUpPlaceId;
	params.bookingItemData.dropoff_place_id        = dropOffPlaceId;

	var availabilityResult = document.getElementById('timAvailabilityResult');
	availabilityResult.innerHTML = '';

	timShowSpinner('tim_spinner');

	jQuery.post(timData.ajaxurl, params, function(response) {
	// timPostAjax(timData.ajaxurl, params, function(response){ // Cannot send multi object => action: abc, bookingItemData: {}
		if (response == 'false'){ return false; }

		var label = timLabels;

		var data = JSON.parse(response);
	  	
	  	// console.log(data);
	  	// return;

		if (!data.error) {
			var schedule                 = data.tour_options;
			var productSaleTaxes         = data.saleTaxes;
			var documentDateExchangeRate = data.documentDateExchangeRate;
			var taxesIncluded            = data.taxesIncluded;

			var booking = data.booking;

		  	var productPrice = schedule.date_rates;

		  	var adults   = params.bookingItemData.adults;
		  	var children = params.bookingItemData.children;
		  	var infants  = params.bookingItemData.infants;
		  	var seniors  = params.bookingItemData.seniors;

		  	var productCostType = 'per_person';

		  	var totalPax = adults + children + infants + seniors;

		  	params.bookingItemData.adult_price  = adults   > 0 ? applyExchangeRateConversion(productPrice.adult_price, documentDateExchangeRate) : 0;
			params.bookingItemData.child_price  = children > 0 ? applyExchangeRateConversion(productPrice.child_price, documentDateExchangeRate) : 0;
			params.bookingItemData.infant_price = infants  > 0 ? applyExchangeRateConversion(productPrice.infant_price, documentDateExchangeRate) : 0;
			params.bookingItemData.senior_price = seniors  > 0 ? applyExchangeRateConversion(productPrice.senior_price, documentDateExchangeRate) : 0;
			
			var item_price                       = setDayPrice(productPrice, productCostType, adults, children, infants, seniors, documentDateExchangeRate);
			var base_price                       = setBasePrice(item_price, taxesIncluded, productSaleTaxes);
			var commission_price                 = base_price * (productPrice.client_commission_percentage / 100);
			var net_price                        = base_price - commission_price;
			var pickup_price                     = applyExchangeRateConversion(schedule.pickup_price, documentDateExchangeRate);
			var dropoff_price                    = applyExchangeRateConversion(schedule.dropoff_price, documentDateExchangeRate);
			var discount_price                   = 0;
			var subtotal_price                   = net_price + pickup_price + dropoff_price - discount_price;
			var tax_price_percentage             = getProductTaxesTotalPercentage(productSaleTaxes);
			var tax_exoneration_price_percentage = getProductTaxesTotalExonerationPercentage(productSaleTaxes);
			var net_tax_price_percentage         = tax_price_percentage - tax_exoneration_price_percentage;

			if (booking.discount_coupon_id){
                if (booking.discount_coupon.discount_type == 'percentage'){
                    discount_price = subtotal_price * (booking.discount_coupon.value / 100);
                    subtotal_price = subtotal_price - discount_price;
                } else {
                    discount_price = applyExchangeRateConversion(booking.discount_coupon.value, documentDateExchangeRate);
                    subtotal_price = (item_price + pickup_price + dropoff_price - discount_price) / ((net_tax_price_percentage / 100) + 1); // tax_price_percentage
                }
            }
			
			params.bookingItemData.sale_taxes = setProductTaxesAmounts(subtotal_price, productSaleTaxes);

			var tax_price             = getProductTaxesTotalAmount(params.bookingItemData.sale_taxes);
			var tax_exoneration_price = getProductTaxesTotalExonerationAmount(params.bookingItemData.sale_taxes);
			var net_tax_price         = tax_price - tax_exoneration_price;

			var total_price = subtotal_price + net_tax_price;

			params.bookingItemData.item_price                       = timRoundNum(item_price);
			params.bookingItemData.base_price                       = timRoundNum(base_price);
			params.bookingItemData.commission_price_percentage      = productPrice.client_commission_percentage;
			params.bookingItemData.commission_price                 = timRoundNum(commission_price);
			params.bookingItemData.net_price                        = timRoundNum(net_price);
			params.bookingItemData.pickup_price                     = pickup_price;
			params.bookingItemData.dropoff_price                    = dropoff_price;
			params.bookingItemData.discount_price                   = timRoundNum(discount_price);
			params.bookingItemData.subtotal_price                   = timRoundNum(subtotal_price);
			params.bookingItemData.tax_price_percentage             = tax_price_percentage;
			params.bookingItemData.tax_price                        = timRoundNum(tax_price);
			params.bookingItemData.tax_exoneration_price_percentage = tax_exoneration_price_percentage;
			params.bookingItemData.tax_exoneration_price            = timRoundNum(tax_exoneration_price);
			params.bookingItemData.net_tax_price_percentage         = net_tax_price_percentage;
			params.bookingItemData.net_tax_price                    = timRoundNum(net_tax_price);
			params.bookingItemData.total_price                      = timRoundNum(total_price);

			// console.log(pickup_price);

			var price = !taxesIncluded ? params.bookingItemData.subtotal_price : params.bookingItemData.total_price;

			// console.log(params.bookingItemData);
			// return;

			var extras = params.bookingItemData.pickup_price + params.bookingItemData.dropoff_price;

			var content =
			'<div class="tim_align_right" style="font-size:14px;">';

			if (extras){
				if ( !taxesIncluded ){
		            content = '<br />'+ content +
		            	'<hr />'+ label.subtotal +': <b>'+ currencySymbol + params.bookingItemData.net_price +'</b><br />'+
		            	'<small>(+) '+ label.transportation +':</small> '+ currencySymbol + extras +'<br />';
				} 
				else {
					content = '<br />'+ content +
		            	'(+) '+ label.transportation +'<br />';
				}
			}

			//var option = 'update'; // "+ JSON.stringify(option) +"
			content = content +
	           	"<br /><hr /><b>"+ label.totalPrice +": "+ currencySymbol + timCurrencyFormat(price) +"</b><br /><br />"+
            	"<button type='button' class='tim-btn tim-btn-lg'"+
            	" onclick='timUpdateItemFromOrder("+ JSON.stringify(params.bookingItemData) +")'>"+ label.updateOrder +"</button>"+
            "</div>";

			availabilityResult.innerHTML = content;
		}
		else{
			var contentError = '<br /><div class="tim_alert tim_alert_danger"><b>'+ data.error +'</b></div>'
			availabilityResult.innerHTML = contentError;
		}

		timHideSpinner('tim_spinner');
	});
}


// TRANSPORTATION
// ------------------------------------

function timCheckTransportationRates(bookingItemId){
	var currencyId     = document.getElementById('timUserCurrency').value;
	var currencyCode   = document.getElementById('timUserCurrencyCode').value;
	var currencySymbol = document.getElementById('timUserCurrencySymbol').value;

  	var currentUTCDate = new Date().toISOString(); // 2018-04-26T22:02:12.059Z

  	var adults   = document.getElementById('timAdults') ? document.getElementById('timAdults').value : 0;
  	var children = document.getElementById('timChildren') ? document.getElementById('timChildren').value : 0;
  	var infants  = document.getElementById('timInfants') ? document.getElementById('timInfants').value : 0;
  	var seniors  = document.getElementById('timSeniors') ? document.getElementById('timSeniors').value : 0;

	var params = {
		action:         'check_transportation_rates_api', 
        f_nonce:        timData.f_nonce, // Front-end nonce
		currentUTCDate: currentUTCDate, 
		lang:           document.getElementById('timCL').value, 
		currency_id:    currencyId, 
		scheduleType:   document.getElementById('timTransportationScheduleType').value, 
		bookingItemData: {
			booking_type:              'transportation', 
			start_date:                document.getElementById('timFromDB').value, // yyyy-mm-dd 
			adults:                    parseInt(adults), 
			children:                  parseInt(children), 
			infants:                   parseInt(infants), 
			seniors:                   parseInt(seniors), 
			transportation_id:         document.getElementById('timTransportationId').value, 
			transportation_content_id: document.getElementById('timTransportationContentId').value
		}
	};

	if (!bookingItemId){ // Add
		if (document.getElementById('timSPLAccepted')){
  			params.spl_accepted = document.getElementById('timSPLAccepted').value;
  		}

		timCheckTransportationRatesAdd(params, currencySymbol);
  	}
  	else{ // Edit
  		params.booking_id    = document.getElementById('timBookingId').value;          // in modal
  		params.price_list_id = document.getElementById('timBookingPriceListId').value; // in modal

  		timCheckTransportationRatesEdit(bookingItemId, params, currencySymbol);
	}
}

function timCheckTransportationRatesAdd(params, currencySymbol){
	var availabilityResult = document.getElementById('timAvailabilityResult');
	availabilityResult.style.opacity = 0.5;

	timShowSpinner('tim-inline-spinner');

	//console.log(params);

	jQuery.post(timData.ajaxurl, params, function(response) {
	// timPostAjax(timData.ajaxurl, params, function(response){ // Cannot send multi object => action: abc, bookingItemData: {}
		var label = timLabels;

		var content = '';
		if (response == 'false'){
			content = '<div style="text-align:center;"><b>Invalid access.</b><br />Contact the website owner.</div>';
		}
		else{
		  	var data = JSON.parse(response);

		  	var transportation_schedules = data.transportation_schedules;
		  	var productSaleTaxes         = data.saleTaxes;
		  	var documentDateExchangeRate = data.documentDateExchangeRate;
		  	var taxesIncluded            = data.taxesIncluded;

		  	// console.log(data);
		  	// return;

		  	params.bookingItemData.exchange_rate_value = documentDateExchangeRate.exchangeRateValue; // value for booking
		  	// params.bookingItemData.spl_accepted        = params.spl_accepted || false;   // for booking

		  	if (params.spl_accepted){
		  		params.bookingItemData.spl_accepted = true;
		  	}

		  	params.bookingItemData.priceListIdByProvider = data.priceListIdByProvider;

	  		var showSchedules  = true;
	  		var labelDeparture = label.departure;

	  		if (params.scheduleType === 'open'){
	  			showSchedules  = false;
	  			labelDeparture = label.selectTime;
	  		}

	  		if (transportation_schedules){
	      		var adults   = params.bookingItemData.adults;
			  	var children = params.bookingItemData.children;
			  	var infants  = params.bookingItemData.infants;
			  	var seniors  = params.bookingItemData.seniors;

			  	var totalPax = adults + children + infants + seniors;

			  	var atLeastOneScheduleIsAvailable = false;
			  	var atLeastOneScheduleHasRates    = false;

			  	var unavailableDates = JSON.parse(document.getElementById('unavailableDates').value);
			  	var currentStartDate = document.getElementById('timFromDB');

			  	var productCostType;

			  	var schedules = '';

			  	if (transportation_schedules.length > 0){
		      		for ( var i=0; i < transportation_schedules.length; i++ ) {
		          		var schedule = transportation_schedules[i];

		          		var availableContent, disabledOption = '';

		          		params.bookingItemData.subtotal_price = 0;

		          		var productPrice = schedule.date_rates;

	          			params.bookingItemData.transportation_schedule_id = schedule.id;
	          			params.bookingItemData.departure_time             = schedule.departure;

	          			params.bookingItemData.quantity = 1;
	          			productCostType = 'per_service';

	          			if (params.scheduleType === 'fixed'){ // Shuttle
							params.bookingItemData.quantity = totalPax;
							productCostType = 'per_person';

							params.bookingItemData.adult_price  = adults   > 0 ? applyExchangeRateConversion(productPrice.adult_price, documentDateExchangeRate) : 0;
							params.bookingItemData.child_price  = children > 0 ? applyExchangeRateConversion(productPrice.child_price, documentDateExchangeRate) : 0;
							params.bookingItemData.infant_price = infants  > 0 ? applyExchangeRateConversion(productPrice.infant_price, documentDateExchangeRate) : 0;
							params.bookingItemData.senior_price = seniors  > 0 ? applyExchangeRateConversion(productPrice.senior_price, documentDateExchangeRate) : 0;
						}

						var item_price                       = setDayPrice(productPrice, productCostType, adults, children, infants, seniors, documentDateExchangeRate);
                        var base_price                       = setBasePrice(item_price, taxesIncluded, productSaleTaxes);
                        var commission_price                 = base_price * (productPrice.client_commission_percentage / 100);
                        var net_price                        = base_price - commission_price;
                        var pickup_price                     = 0;
                        var dropoff_price                    = 0;
                        var discount_price                   = 0;
                        var subtotal_price                   = net_price + pickup_price + dropoff_price - discount_price;
                        var tax_price_percentage             = getProductTaxesTotalPercentage(productSaleTaxes);
                        var tax_exoneration_price_percentage = getProductTaxesTotalExonerationPercentage(productSaleTaxes);
						var net_tax_price_percentage         = tax_price_percentage - tax_exoneration_price_percentage;
                        
                        params.bookingItemData.sale_taxes = setProductTaxesAmounts(subtotal_price, productSaleTaxes);

						var tax_price             = getProductTaxesTotalAmount(params.bookingItemData.sale_taxes);
						var tax_exoneration_price = getProductTaxesTotalExonerationAmount(params.bookingItemData.sale_taxes);
						var net_tax_price         = tax_price - tax_exoneration_price;

                        var total_price = subtotal_price + net_tax_price;

                        params.bookingItemData.item_price                       = timRoundNum(item_price);
                        params.bookingItemData.base_price                       = timRoundNum(base_price);
                        params.bookingItemData.commission_price_percentage      = productPrice.client_commission_percentage;
                        params.bookingItemData.commission_price                 = timRoundNum(commission_price);
                        params.bookingItemData.net_price                        = timRoundNum(net_price);
                        params.bookingItemData.pickup_price                     = pickup_price;
                        params.bookingItemData.dropoff_price                    = dropoff_price;
                        params.bookingItemData.discount_price                   = timRoundNum(discount_price);
                        params.bookingItemData.subtotal_price                   = timRoundNum(subtotal_price);
                        params.bookingItemData.tax_price_percentage             = tax_price_percentage;
                        params.bookingItemData.tax_price                        = timRoundNum(tax_price);
                        params.bookingItemData.tax_exoneration_price_percentage = tax_exoneration_price_percentage;
						params.bookingItemData.tax_exoneration_price            = timRoundNum(tax_exoneration_price);
						params.bookingItemData.net_tax_price_percentage         = net_tax_price_percentage;
						params.bookingItemData.net_tax_price                    = timRoundNum(net_tax_price);
                        params.bookingItemData.total_price                      = timRoundNum(total_price);

                        var price = !taxesIncluded ? params.bookingItemData.subtotal_price : params.bookingItemData.total_price;

		          		if (schedule.is_available && schedule.isCutOffValid){
		          			if (totalPax >= schedule.min_pax_required){
		          				availableContent = "<a href='javascript:void(0)' class='tim-option-btn' onclick='timAddItemToOrder("+ JSON.stringify(params.bookingItemData) +")'>"+ label.book +"</a>";
		          			}
		          			else{
		          				availableContent = '<span class="tim_error_input_msg"><b>'+ label.errorMinPaxRequired +': '+ schedule.min_pax_required +'</b></span>';
		          			}

		          			atLeastOneScheduleIsAvailable = true;
		          		}
		          		else{
			                availableContent = '<br /><span class="tim_label tim_label_md tim_label_danger">'+ label.errorNotAvailable +'</span>';
			                disabledOption   = ' tim_text_disabled';
			            }

			            if (productPrice.day){
			            	atLeastOneScheduleHasRates = true;
			            }

			            schedules += 
			            '<div class="tim-option-content">'+
			            	'<div class="tim-departure">'+
			            		'<span class="tim-option-content-title">'+ labelDeparture +'</span>';
					            if (showSchedules){
					            	schedules += timFormatHour(schedule.departure);
					            }
					            else{
					            	if (!disabledOption){
					            		schedules += '<input type="text" id="departureTime" name="departureTime" class="timepicker" />';
					            	}
					            	else{
					            		schedules += '-';
					            	}
					            }
			            schedules += '</div>'; //. tim-departure

			            var duration_time_unit = label.hours; // schedule.duration_time_unit

			            var priceContent = price > 0 ? currencySymbol + timCurrencyFormat(price) : '-';

		                schedules += 
						    '<div class="tim-duration">'+ 
						    	'<span class="tim-option-content-title">'+ label.duration +'</span>'+ 
						    	schedule.duration +' <small>'+ duration_time_unit +'</small>'+
						    '</div>'+
						    '<div class="tim-price">'+ 
						    	'<span class="tim-option-content-title">'+ label.price +'</span>'+ 
						    	priceContent +
						    '</div>'+
						    '<div class="tim-book">'+ 
						    	availableContent +
						    '</div>';
						
						schedules += '</div>'; //. tim-option-content"
		          	}
		        }
		      	else{
		      		schedules += 
		      			'<div class="tim-option-content"><b>* '+ label.errorSelectEarlierDate +'</b></div>';
		      	}

		      	content +=
			  		'<div class="tim-options-wrapper">'+
			  			'<div class="tim-option-body">'+
			            	schedules +
						'</div>'+
			  		'</div>';

		      	// In case at least on schedule is available and at least one has rates
		      	// Unexpected behaviour
			  	if (!atLeastOneScheduleIsAvailable && atLeastOneScheduleHasRates){
		      		unavailableDates.push(currentStartDate.value);

		      		// console.log(unavailableDates);

		      		var newStartDate = increaseDate(currentStartDate.value, 1);

				    currentStartDate.value = newStartDate;

		      		document.getElementById('unavailableDates').value = JSON.stringify(unavailableDates);

		      		// timProductDatePicker('transportation', 1, params.lang, newStartDate);
    				jQuery('#timFrom').datepicker('option', 'beforeShowDay', timUnavailableProductDates).datepicker('refresh');

    				// timCheckTransportationRates();
    				// return;
		      	}
	      	}
	      	else{
	      		content = '<div style="text-align:center;"><b>No rates found.</b></div>';
	      	}
		}

		timHideSpinner('tim-inline-spinner');

	  	document.getElementById('timTransportationSchedules').innerHTML = content;

	  	// Open departures
	  	if (!showSchedules){
	  		setTimePicker('timepicker');
	  	}

	  	availabilityResult.style.opacity = 1;
	});
}

function timCheckTransportationRatesEdit(bookingItemId, params, currencySymbol){
	var pickUpPlaceIdInput = document.getElementById('timPickupPlaceId');
	var pickUpPlaceId      = pickUpPlaceIdInput.value;
	
	pickUpPlaceIdInput.classList.remove('tim_error_input');
	if (!pickUpPlaceId || pickUpPlaceId == null){
		pickUpPlaceIdInput.classList.add('tim_error_input');
		pickUpPlaceIdInput.focus();
		return false;
	}

	var dropOffPlaceIdInput = document.getElementById('timDropOffPlaceId');
	var dropOffPlaceId      = dropOffPlaceIdInput.value;

	dropOffPlaceIdInput.classList.remove('tim_error_input');
	if (!dropOffPlaceId || dropOffPlaceId == null){
		dropOffPlaceIdInput.classList.add('tim_error_input');
		dropOffPlaceIdInput.focus();
		return false;
	}

	var form = document.forms['tim_update_cart'];

	var scheduleId, departureTime;

	var departureTime = document.getElementById('departureTime').value;
	if (departureTime){
		scheduleId    = document.getElementById('timScheduleId').value;
		departureTime = timConvertTimeToDB(departureTime);
	}
	else{
		var transportationSchedules = form.elements['schedule'].value.split('-');
		scheduleId    = transportationSchedules[0];
		departureTime = transportationSchedules[1];
	}

	params.bookingItemData.booking_item_id         = bookingItemId;
	params.bookingItemData.transportation_schedule_id = scheduleId;
	params.bookingItemData.departure_time          = departureTime;
	params.bookingItemData.pickup_place_id         = pickUpPlaceId;
	params.bookingItemData.dropoff_place_id        = dropOffPlaceId;

	var availabilityResult = document.getElementById('timAvailabilityResult');
	availabilityResult.innerHTML = '';

	timShowSpinner('tim_spinner');

	jQuery.post(timData.ajaxurl, params, function(response) {
	// timPostAjax(timData.ajaxurl, params, function(response){ // Cannot send multi object => action: abc, bookingItemData: {}
		if (response == 'false'){ return false; }

		var label = timLabels;

		var data = JSON.parse(response);

		// console.log(data); return;

		if (!data.error){
		  	var schedule                 = data.transportation_schedules;
		  	var productSaleTaxes         = data.saleTaxes;
			var documentDateExchangeRate = data.documentDateExchangeRate;
			var taxesIncluded            = data.taxesIncluded;

			var booking = data.booking;

			var productPrice = schedule.date_rates;

		  	var adults   = params.bookingItemData.adults;
		  	var children = params.bookingItemData.children;
		  	var infants  = params.bookingItemData.infants;
		  	var seniors  = params.bookingItemData.seniors;

		  	var totalPax = adults + children + infants + seniors;

		  	params.bookingItemData.quantity = 1;
		  	var productCostType = 'per_service';

  			if (params.scheduleType === 'fixed'){ // Shuttle
				params.bookingItemData.quantity = totalPax;
				productCostType = 'per_person';

				params.bookingItemData.adult_price  = adults   > 0 ? applyExchangeRateConversion(productPrice.adult_price, documentDateExchangeRate) : 0;
				params.bookingItemData.child_price  = children > 0 ? applyExchangeRateConversion(productPrice.child_price, documentDateExchangeRate) : 0;
				params.bookingItemData.infant_price = infants  > 0 ? applyExchangeRateConversion(productPrice.infant_price, documentDateExchangeRate) : 0;
				params.bookingItemData.senior_price = seniors  > 0 ? applyExchangeRateConversion(productPrice.senior_price, documentDateExchangeRate) : 0;
			}

			var item_price                       = setDayPrice(productPrice, productCostType, adults, children, infants, seniors, documentDateExchangeRate);
            var base_price                       = setBasePrice(item_price, taxesIncluded, productSaleTaxes);
            var commission_price                 = base_price * (productPrice.client_commission_percentage / 100);
            var net_price                        = base_price - commission_price;
            var pickup_price                     = applyExchangeRateConversion(schedule.pickup_price, documentDateExchangeRate);
            var dropoff_price                    = applyExchangeRateConversion(schedule.dropoff_price, documentDateExchangeRate);
            var discount_price                   = 0;
            var subtotal_price                   = net_price + pickup_price + dropoff_price - discount_price;
            var tax_price_percentage             = getProductTaxesTotalPercentage(productSaleTaxes);
            var tax_exoneration_price_percentage = getProductTaxesTotalExonerationPercentage(productSaleTaxes);
			var net_tax_price_percentage         = tax_price_percentage - tax_exoneration_price_percentage;

            if (booking.discount_coupon_id){
                if (booking.discount_coupon.discount_type == 'percentage'){
                    discount_price = subtotal_price * (booking.discount_coupon.value / 100);
                    subtotal_price = subtotal_price - discount_price;
                } else {
                    discount_price = applyExchangeRateConversion(booking.discount_coupon.value, documentDateExchangeRate);
                    subtotal_price = (item_price + pickup_price + dropoff_price - discount_price) / ((net_tax_price_percentage / 100) + 1);
                }
            }
            
            params.bookingItemData.sale_taxes = setProductTaxesAmounts(subtotal_price, productSaleTaxes);

            var tax_price             = getProductTaxesTotalAmount(params.bookingItemData.sale_taxes);
			var tax_exoneration_price = getProductTaxesTotalExonerationAmount(params.bookingItemData.sale_taxes);
			var net_tax_price         = tax_price - tax_exoneration_price;

            var total_price = subtotal_price + net_tax_price;

            params.bookingItemData.item_price                       = timRoundNum(item_price);
            params.bookingItemData.base_price                       = timRoundNum(base_price);
            params.bookingItemData.commission_price_percentage      = productPrice.client_commission_percentage;
            params.bookingItemData.commission_price                 = timRoundNum(commission_price);
            params.bookingItemData.net_price                        = timRoundNum(net_price);
            params.bookingItemData.pickup_price                     = pickup_price;
            params.bookingItemData.dropoff_price                    = dropoff_price;
            params.bookingItemData.discount_price                   = timRoundNum(discount_price);
            params.bookingItemData.subtotal_price                   = timRoundNum(subtotal_price);
            params.bookingItemData.tax_price_percentage             = tax_price_percentage;
            params.bookingItemData.tax_price                        = timRoundNum(tax_price);
            params.bookingItemData.tax_exoneration_price_percentage = tax_exoneration_price_percentage;
			params.bookingItemData.tax_exoneration_price            = timRoundNum(tax_exoneration_price);
			params.bookingItemData.net_tax_price_percentage         = net_tax_price_percentage;
			params.bookingItemData.net_tax_price                    = timRoundNum(net_tax_price);
            params.bookingItemData.total_price                      = timRoundNum(total_price);

            var price = !taxesIncluded ? params.bookingItemData.subtotal_price : params.bookingItemData.total_price;

            var extras = params.bookingItemData.pickup_price + params.bookingItemData.dropoff_price;

			var content =
			'<div class="tim_align_right" style="font-size:14px;">';

			if (extras){
				if ( !taxesIncluded ){
		            content = '<br />'+ content +
		            	'<hr />'+ label.subtotal +': <b>'+ currencySymbol + params.bookingItemData.net_price +'</b><br />'+
		            	'<small>(+) '+ label.transportation +':</small> '+ currencySymbol + extras +'<br />';
				} 
				else {
					content = '<br />'+ content +
		            	'(+) '+ label.transportation +'<br />';
				}
			}

			var content = content +
	           	"<br /><hr /><b>"+ label.totalPrice +": "+ currencySymbol + timCurrencyFormat(price) +"</b><br /><br />"+
            	"<button type='button' class='tim-btn tim-btn-lg'"+ 
            	" onclick='timUpdateItemFromOrder("+ JSON.stringify(params.bookingItemData) +")'>"+ label.updateOrder +"</button>"+
            "</div>";

			availabilityResult.innerHTML = content;
		}
		else{
			var contentError = '<br /><div class="tim_alert tim_alert_danger"><b>'+ response.error +'</b></div>'
			availabilityResult.innerHTML = contentError;
		}

		timHideSpinner('tim_spinner');
	});
}


/*function timSelectTransportationType(){
	console.log('timSelectTransportationType');
	
	// var transportationType = document.getElementById('transportationType');
}*/

function timSearchTransportation(){
	setButton();
	var error = 0;

	error = validateInput('timDepartureLocation', error);
	error = validateInput('timArrivalLocation', error);
	error = validateInput('timFrom', error);
	error = validateInput('timDepartureTime', error);

	if (error){
		setButton(1);
		return false;
	}

	var currencyId     = document.getElementById('timUserCurrency').value;
	var currencySymbol = document.getElementById('timUserCurrencySymbol').value;

  	var currentUTCDate = new Date().toISOString(); // 0000-00-00:00:00.059Z

  	var startDate = document.getElementById('timFromDB').value; // yyyy-mm-dd 

  	var adults   = parseInt(document.getElementById('timAdults').value);
  	var children = parseInt(document.getElementById('timChildren').value);
  	var infants  = parseInt(document.getElementById('timInfants').value);
  	var seniors  = 0;

	var params = {
		action:             'search_transportations_rates_api', 
        f_nonce:             timData.f_nonce, 
        currentUTCDate:      currentUTCDate, 
        lang:                document.getElementById('timCL').value, 
		currency_id:         currencyId, 
        departureLocationId: document.getElementById('timDepartureLocation').value, 
        arrivalLocationId:   document.getElementById('timArrivalLocation').value, 
        start_date:          startDate,
        departure_time:      document.getElementById('timDepartureTime').value, 
        adults:              adults, 
        children:            children, 
        infants:             infants
	};

	timShowSpinner('tim_spinner');

	timPostAjax(timData.ajaxurl, params, function(response){
		timHideSpinner('tim_spinner');

		setButton(1);

       	var label = timLabels;

		var content = '';
		if (response == 'false'){
			content = '<div style="text-align:center;"><b>Invalid access.</b><br />Contact the website owner.</div>';
		}
		else{
		  	var data = JSON.parse(response);

		  	var transportations    = data.transportations;
		  	var documentDateExchangeRate = data.documentDateExchangeRate;
		  	var taxesIncluded      = data.taxesIncluded;

		  	// console.log(data);
		  	// return;

		  	var bookingItemData = {
				booking_type:          'transportation', 
				start_date:            startDate,
				adults:                adults, 
				children:              children, 
				infants:               infants, 
				seniors:               seniors, 
				exchange_rate_value:   documentDateExchangeRate.exchangeRateValue // value
			};

			var transportationContent = '';

	  		if (transportations.length > 0){
			  	var totalPax = adults + children + infants + seniors;

			  	var productCostType;

			  	transportations.forEach(function(transportation){
		      		bookingItemData.transportation_id         = transportation.transportation_id;
		      		bookingItemData.transportation_content_id = transportation.transportation_content_id;
		      		bookingItemData.provider_id               = transportation.provider_id;
		      		bookingItemData.priceListIdByProvider     = transportation.priceListIdByProvider;

		      		var productSaleTaxes = transportation.saleTaxes;

			  		var scheduleType = transportation.schedule_type;
			  		
			  		var scheduleTypeLabel;

			  		var availableContent;
			  		var disabledOption = '';
			  		var pickupTime;

		      		var schedules = '';
		      		var productPrice;

		          	transportation.transportation_schedules.forEach(function(schedule){
		          		disabledOption = '';

		          		bookingItemData.subtotal_price = 0;

		          		if (schedule.is_available && schedule.isCutOffValid){
		          			productPrice = schedule.date_rates;

		          			bookingItemData.transportation_schedule_id = schedule.id;
		          			bookingItemData.departure_time             = schedule.departure;

		          			bookingItemData.quantity = 1;
		          			productCostType = 'per_service';

		          			if (scheduleType === 'fixed'){ // Shuttle
								bookingItemData.quantity = totalPax;
								productCostType = 'per_person';

								bookingItemData.adult_price  = adults   > 0 ? applyExchangeRateConversion(productPrice.adult_price, documentDateExchangeRate) : 0;
								bookingItemData.child_price  = children > 0 ? applyExchangeRateConversion(productPrice.child_price, documentDateExchangeRate) : 0;
								bookingItemData.infant_price = infants  > 0 ? applyExchangeRateConversion(productPrice.infant_price, documentDateExchangeRate) : 0;
								bookingItemData.senior_price = seniors  > 0 ? applyExchangeRateConversion(productPrice.senior_price, documentDateExchangeRate) : 0;
							}

							var item_price                       = setDayPrice(productPrice, productCostType, adults, children, infants, seniors, documentDateExchangeRate);
                            var base_price                       = setBasePrice(item_price, taxesIncluded, productSaleTaxes);
                            var commission_price                 = base_price * (productPrice.client_commission_percentage / 100);
                            var net_price                        = base_price - commission_price;
                            var pickup_price                     = 0;
                            var dropoff_price                    = 0;
                            var discount_price                   = 0;
                            var subtotal_price                   = net_price + pickup_price + dropoff_price - discount_price;
                            var tax_price_percentage             = getProductTaxesTotalPercentage(productSaleTaxes);
                            var tax_exoneration_price_percentage = getProductTaxesTotalExonerationPercentage(productSaleTaxes);
							var net_tax_price_percentage         = tax_price_percentage - tax_exoneration_price_percentage;
                            
                            bookingItemData.sale_taxes = setProductTaxesAmounts(subtotal_price, productSaleTaxes);

                            var tax_price             = getProductTaxesTotalAmount(bookingItemData.sale_taxes);
							var tax_exoneration_price = getProductTaxesTotalExonerationAmount(bookingItemData.sale_taxes);
							var net_tax_price         = tax_price - tax_exoneration_price;

                            var total_price = subtotal_price + net_tax_price;

                            bookingItemData.item_price                       = timRoundNum(item_price);
                            bookingItemData.base_price                       = timRoundNum(base_price);
                            bookingItemData.commission_price_percentage      = productPrice.client_commission_percentage;
                            bookingItemData.commission_price                 = timRoundNum(commission_price);
                            bookingItemData.net_price                        = timRoundNum(net_price);
                            bookingItemData.pickup_price                     = pickup_price;
                            bookingItemData.dropoff_price                    = dropoff_price;
                            bookingItemData.discount_price                   = timRoundNum(discount_price);
                            bookingItemData.subtotal_price                   = timRoundNum(subtotal_price);
                            bookingItemData.tax_price_percentage             = tax_price_percentage;
                            bookingItemData.tax_price                        = timRoundNum(tax_price);
                            bookingItemData.tax_exoneration_price_percentage = tax_exoneration_price_percentage;
							bookingItemData.tax_exoneration_price            = timRoundNum(tax_exoneration_price);
							bookingItemData.net_tax_price_percentage         = net_tax_price_percentage;
							bookingItemData.net_tax_price                    = timRoundNum(net_tax_price);
                            bookingItemData.total_price                      = timRoundNum(total_price);

                            var price = !taxesIncluded ? bookingItemData.subtotal_price : bookingItemData.total_price;

	                        // const returnedTarget = Object.assign(bookingItemData, source);

		          			if (totalPax >= schedule.min_pax_required){ //  && price > 0
		          				availableContent = "<a href='javascript:void(0)' class='tim-btn' onclick='timAddItemToOrder("+ JSON.stringify(bookingItemData) +")'>"+ label.book +"</a>";
		          			} else {
		          				availableContent = '<span class="tim_error_input_msg"><b>'+ label.errorMinPaxRequired +': '+ schedule.min_pax_required +'</b></span>';
		          			}
		          		} else {
			                availableContent = '<br /><span class="tim_label tim_label_md tim_label_danger">'+ label.errorNotAvailable +'</span>';
			                disabledOption   = ' tim_text_disabled';
			            }

			            scheduleTypeLabel = (scheduleType === 'fixed') ? label.fixed : label.open;
			            pickupTime = (scheduleType === 'fixed') ? timFormatHour(schedule.departure) : document.getElementById('timDepartureTime').value;

			            var duration_time_unit = label.hours; // schedule.duration_time_unit

			            var priceContent = price > 0 ? currencySymbol + timCurrencyFormat(price) : '-';

		                schedules += 
		                	'<tr>'+
		                		'<td class="tim_align_center">'+ 
							    	scheduleTypeLabel +
							    '</td>'+
							    '<td class="tim_align_center">'+ 
							    	schedule.duration +' <small>'+ duration_time_unit +'</small>'+
							    '</td>'+
							    '<td class="tim_align_center">'+ 
							    	pickupTime +
							    '</td>'+
							    '<td class="tim_align_center">'+ 
							    	'<b>'+ priceContent +'</b>'+
							    '</td>'+ 
							    '<td class="tim_align_center">'+ 
							    	availableContent +
							    '</td>'
							'</tr>';
		          	});

					transportationContent += 
						'<h3 class="tim_align_center">'+ 
							transportation.transportation_content_name +
						'</h3>'+
						'<table class="tim_table tim_table_no_border">'+ 
							'<thead>'+
				                '<tr>'+
				                    '<th class="tim_align_center">'+ label.scheduleType +'</th>'+ 
				                    '<th class="tim_align_center">'+ label.duration +'</th>'+ 
				                    '<th class="tim_align_center">'+ label.pickupTime +'</th>'+
				                    '<th class="tim_align_center" style="width: 100px;">'+ label.price +'</th>'+
				                    '<th class="tim_align_center" style="width: 210px;"></th>'+
				                '</tr>'+
				            '</thead>'+
	            			'<tbody>'+
	            				schedules +
	            			'</tbody>'+ 
            			'</table>'+ 
						'<br />';
          		});
	      	}
	      	else{
	      		transportationContent = '<div style="text-align:center;"><b>No rates found.</b></div>';
	      	}

	  		content +=
	  		// '<div class="tim-options-wrapperx">'+
	  			// '<div class="tim-option-body">'+
	            	transportationContent //+
				// '</div>'+
	  		// '</div>';
		}

		document.getElementById('timTransportations').innerHTML = content;
    }); 
}


// HOTELS
// ------------------------------------

var timRoomsSelected;
function timCheckHotelAvailability(){
	var error = 0;
	error = validateInput('timTo', error); // , 'timLabelErrorCheckIn'
	error = validateInput('timFrom', error); // , 'timLabelErrorCheckOut'

	var today     = getTodayDate();
	var startDate = document.getElementById('timFromDB').value;
	var endDate   = document.getElementById('timToDB').value;

	if (startDate && (startDate < today || startDate >= endDate)){
		alert('Invalid date');
		return;
	}

	if (error){
		return false;
	}

	var bookingItemId = document.getElementById('timBookingItemId') ? document.getElementById('timBookingItemId').value : '';

	var currencyId     = document.getElementById('timUserCurrency').value;
	var currencyCode   = document.getElementById('timUserCurrencyCode').value;
	var currencySymbol = document.getElementById('timUserCurrencySymbol').value;

  	var currentUTCDate = new Date().toISOString(); // 2018-04-26T22:02:12.059Z

	var params = {
		action:         'check_hotel_availability_api', 
        f_nonce:        timData.f_nonce, // Front-end nonce
		currentUTCDate: currentUTCDate, 
		lang:           document.getElementById('timCL').value, 
		currency_id:    currencyId,  
		bookingItemData: {
			booking_type:     'hotel', 
			start_date:       startDate, // yyyy-mm-dd 
			end_date:         endDate,   // yyyy-mm-dd
			hotel_id:         document.getElementById('timHotelId').value, 
			hotel_content_id: document.getElementById('timHotelContentId').value
		}
	};

	if (!bookingItemId){ // new
		if (document.getElementById('timSPLAccepted')){
			params.spl_accepted = document.getElementById('timSPLAccepted').value;
		}
	}
	else{ // edit
		var bookedRooms = timBookedRooms;

		params.booking_id    = document.getElementById('timBookingId').value;          // in modal
		params.price_list_id = document.getElementById('timBookingPriceListId').value; // in modal

		params.bookingItemData.booking_item_id = bookingItemId;
	}

	var label = timLabels;

  	var availabilityResult = document.getElementById('timAvailabilityResult');
	availabilityResult.style.opacity = 0.5;

	document.getElementById('timAvailabilityResultMsg').style.display = 'none';

	timShowSpinner('tim_travel_form_spinner'); // it uses block instead of inline-block
	// jQuery(spinner).css('display', 'inline-block');

	jQuery.post(timData.ajaxurl, params, function(response) {
	// timPostAjax(timData.ajaxurl, params, function(response){ // Cannot send multi object => action: abc, bookingItemData: {}
		//if (response == 'false'){ return false; }
		var content = '';
		if (response == 'false'){
			content = '<div style="text-align:center;"><b>Invalid access.</b><br />Contact the website owner.</div>';
		}
		else{
		  	var data  = JSON.parse(response);
		  	var rooms = data.hotel_rooms;

		  	// console.log(data);

			var dataTaxes = {
				saleTaxes:                data.saleTaxes, 
				documentDateExchangeRate: data.documentDateExchangeRate, 
				taxesIncluded:            data.taxesIncluded, 
				priceListIdByProvider:    data.priceListIdByProvider,
				booking:                  data.booking, 
				// spl_accepted:             params.spl_accepted || false, // for booking
			};

			if (params.spl_accepted){
		  		dataTaxes.spl_accepted = true;
		  	}

		  	timRoomsSelected = [];

			var rowsContent = '', 
			    roomTypeContent = '', 
				priceContent = '', 
				roomsContent ='', 
				paxContent = '', 
				adultsContent = '', 
				childrenContent = '';

			var room, roomId, roomMinAvailability, bedConfiguration;
			var x, maxUnitsPerRoom;
			
			var roomTypeSelected;
			var totalUnitsPerRoom, unitsSelected;
			var totalAdultsPerUnit, adultsSelected;
			var totalChildrenPerUnit, childrenSelected;

			for ( var i = 0; i < rooms.length; i++ ) {
				room   = rooms[i];
				roomId = room.id;

				roomMinAvailability = room.min_availability;

				roomTypeSelected     = '';
				totalUnitsPerRoom    = 0;
				totalAdultsPerUnit   = 2; // (bookingItem) ? 0 : 2;
				totalChildrenPerUnit = 0;

				if (bookingItemId && room.date_rates.length > 0){ // Edit
					for ( var r = 0; r < bookedRooms.length; r++ ) {
						if (bookedRooms[r].id === roomId){
							roomTypeSelected = bookedRooms[r];

							var unitsPerRoom = roomTypeSelected.unitsPerRoom;

							totalUnitsPerRoom    = roomTypeSelected.unitsPerRoom;
							totalAdultsPerUnit   = roomTypeSelected.units[0].adults;   // First unit
							totalChildrenPerUnit = roomTypeSelected.units[0].children; // First unit
							break;
						}
					}

					roomMinAvailability = roomMinAvailability + unitsPerRoom; // Blockings = unitsPerRoom
				}

				// Room type
				roomTypeContent = '';
				if (room.logo.url !== '' && room.logo.url !== null){
                    roomTypeContent += 
                    // '<a href="javascript:void(0)" onclick="timAjaxContent(\'hotel-room\', \''+ roomId +'\')">'+
						'<img src="'+ room.logo.url +'" alt="'+ room.name +'" class="tim-table-product-image" />'; //+
					// '</a>';
                }

                bedConfiguration = room.bed_configuration ? ' ('+ room.bed_configuration +')' : '';

                roomTypeContent += 
                '<div class="tim_table_td_title">'+ room.name +'</div>'+
                '<i class="fa fa-bed"></i> <b>'+ room.occupancy_type.name +'</b>'+ bedConfiguration +'<br />';
                if (room.wifi){
                    roomTypeContent += ' <span class="tim_label tim_label_success tim_label_rd">'+ label.wifi +'</span>';
                }
                if (room.not_smoking){
                    roomTypeContent += ' <span class="tim_label tim_label_success tim_label_rd">'+ label.notSmoking +'</span>';
                }
                // roomTypeContent += '<div style="margin-top:7px;"><a href="javascript:void(0)" onclick="timAjaxContent(\'hotel-room\', \''+ roomId +'\')"><i class="fa fa-plus-circle"></i> '+ label.details +'</a></div>';

				// Price
				priceContent = '';
                if (roomMinAvailability > 0){
					priceContent = currencySymbol + timCurrencyFormat(room.price_from);
				}

				if (roomMinAvailability < 5){ // Show msg only when availability is less than n
	                priceContent += '<div style="font-size: 11px; color: #f00;">';
	                priceContent += (roomMinAvailability > 0) ? label.only +' <b>'+ roomMinAvailability +'</b> '+ label.left
	                                                          : label.notAvailable;
	                priceContent += '</div>';
				}

				// Rooms
				roomsContent = '<div class="tim_pax_selector">';
				roomsContent += '<label>'+ label.rooms +'</label>';
				if ((roomMinAvailability+totalUnitsPerRoom) > 0){
					
					maxUnitsPerRoom = (roomMinAvailability > 9) ? 9 : roomMinAvailability;
					// maxUnitsPerRoom += totalUnitsPerRoom;

					// console.log(totalUnitsPerRoom);
					// console.log(maxUnitsPerRoom);

					roomsContent += "<select id='timUnitsPerRoom_"+ roomId +"'"; 
					roomsContent += " onchange='timAddHotelRoomUnit("+ JSON.stringify(room) +", "+ JSON.stringify(roomTypeSelected) +", true, "+ JSON.stringify(dataTaxes) +")' class='tim_select_highlighted'>";
					for ( x = 0; x <= maxUnitsPerRoom; x++ ) {
						unitsSelected = (x === totalUnitsPerRoom) ? ' selected' : '';

						roomsContent += '<option value="'+ x +'"'+ unitsSelected +'>'+ x +'</option>';
					}
					roomsContent += '</select>';
				}
				else{
					roomsContent += '-';
				}
				roomsContent += '</div>';

				// Adults
				adultsContent = '<div class="tim_pax_selector">';
				adultsContent +='<label>'+ label.adults +'</label>';
				if ((roomMinAvailability+totalUnitsPerRoom) > 0){
					adultsContent += "<select id='timAdults_"+ roomId +"[0]' name='timAdults_"+ roomId +"[0]' disabled";
					adultsContent += " onchange='timCheckHotelRoomUnitPax("+ JSON.stringify(room) +", 0, "+ JSON.stringify(dataTaxes) +")'>";
					for ( x = 1; x <= room.max_occupancy; x++ ) {
						adultsSelected = (x === totalAdultsPerUnit) ? ' selected' : '';

						adultsContent += '<option value="'+ x +'"'+ adultsSelected +'>'+ x +'</option>';
					}
					adultsContent += '</select>';
				}
				else{
					adultsContent += '-';
				}
				adultsContent += '</div>';
				
				// Children
				childrenContent = '<div class="tim_pax_selector">';
				childrenContent += '<label>'+ label.children +'</label>';
				if ((roomMinAvailability+totalUnitsPerRoom) > 0 && room.max_occupancy > 2 && room.children_allowed){
					childrenContent += "<select id='timChildren_"+ roomId +"[0]' name='timChildren_"+ roomId +"[0]' disabled";
					childrenContent += " onchange='calculateHotelRoomsTotalPrice("+ JSON.stringify(dataTaxes) +")'>";
					// for ( x = 0; x <= room.max_occupancy-2; x++ ) {
					for ( x = 0; x <= room.max_children_occupancy; x++ ) {
						childrenSelected = (x === totalChildrenPerUnit) ? ' selected' : '';

						childrenContent += '<option value="'+ x +'"'+ childrenSelected +'>'+ x +'</option>';
					}
					childrenContent += '</select>';
				}
				else{
					childrenContent += '-';
				}
				childrenContent += '</div>';

				var childrenMaxOccupancyPerRoomLabel = '-';
				if (room.children_allowed){
					childrenMaxOccupancyPerRoomLabel = '<div title="test">'+
                        '<i class="fa fa-child"></i> '+ room.max_children_occupancy +'</div>';
				}

				rowsContent += 
				'<tr class="tim_table_tr_align_top">'+
					// Room type
                    '<td>'+
                    	roomTypeContent +
                    '</td>'+
                    // Price & availability
                    '<td data-th="'+ label.priceFrom +'">'+
                        priceContent +
                    '</td>'+
                    // Max
                    '<td data-th="'+ label.max +'">'+
                        '<i class="fa fa-user"></i> '+ room.max_occupancy + childrenMaxOccupancyPerRoomLabel + 
                    '</td>'+
                    // Rooms
                    '<td data-th="'+ label.rooms +'">'+
                    	'<div class="tim_room_selector_wrapper">'+ 
	                    	'<div class="tim_rooms_selector">'+ 
								roomsContent + 
							'</div>'+ 
							'<div class="tim_paxes_selector">'+ 
								'<div>'+ 
									adultsContent + childrenContent + 
								'</div>'+ 
								'<div id="timAdditionalPaxContainer_'+ roomId +'"></div>'+ 
							'</div>'+
						'</div>'+
                    '</td>'+
                '</tr>';
			}

			content = 
			'<div class="tim_clr">'+
			    '<div class="tim_col_9">'+
			        '<table class="tim_table tim_table_no_border tim_table_align_top">'+
			            '<thead>'+
			                '<tr>'+
			                    '<th>'+ label.roomType +'</th>'+
			                    '<th style="width: 100px;" class="tim_align_center">'+ label.priceFrom +'</th>'+
			                    '<th style="width: 60px;" class="tim_align_center">'+ label.max +'</th>'+
			                    '<th style="width: 210px;" class="tim_align_center">'+ label.rooms +'</th>'+
			                '</tr>'+
			            '</thead>'+
            			'<tbody>'+ 
            				rowsContent +  
						'</tbody>'+
			        '</table>'+
			    '</div>'+
			    '<div class="tim_col_3">'+
			        '<div class="tim-totals-box-wrapper">'+
			            '<div class="tim-totals-box-title">'+
			            	label.accommodations +
			            '</div>'+
			            '<div class="tim-totals-box">'+
			                '<div id="timTotalsBoxResult">'+
			                	label.enterOneRoom +
			                '</div>'+
			            '</div>'+
			        '</div>'+
			    '</div>'+
			'</div>';
		}

		timHideSpinner('tim_travel_form_spinner');

	  	availabilityResult.style.opacity = 1;

	  	availabilityResult.innerHTML = content;

	  	if (bookingItemId && room.date_rates.length > 0){ // Edit - incorrect use of room.date_ranges - put it inside the loop
		  	for ( var i = 0; i < rooms.length; i++ ) {
			  	room   = rooms[i];
				roomId = room.id;
			  	
			  	for ( var r = 0; r < bookedRooms.length; r++ ) {
					if (bookedRooms[r].id === roomId){
						roomTypeSelected = bookedRooms[r];

						document.getElementById('timUnitsPerRoom_'+ roomId).value = roomTypeSelected.unitsPerRoom;

						timAddHotelRoomUnit(room, roomTypeSelected, false, dataTaxes);
						break;
					}
				}
			}

			calculateHotelRoomsTotalPrice(dataTaxes);
		}
	});
}

function timAddHotelRoomUnit(room, roomTypeSelected, calculateTotalPrice, dataTaxes){
	var roomId = room.id;

	var unitsPerRoom = parseInt(document.getElementById('timUnitsPerRoom_'+ roomId).value);

	var paxContent = '', 
	    adultsContent = '', 
	    childrenContent = '';

	var x;

	var label = timLabels;

	// Disable if first unit is 0
	var paxSelectorDisabled = (unitsPerRoom > 0) ? false : true;

	document.querySelector('[name="timAdults_'+ roomId +'[0]"]').disabled = paxSelectorDisabled;
	if (document.querySelector('[name="timChildren_'+ roomId +'[0]"]')){
		document.querySelector('[name="timChildren_'+ roomId +'[0]"]').disabled = paxSelectorDisabled;
	}

	if (unitsPerRoom === 0){
		timRemoveItemFromArray(timRoomsSelected, 'id', roomId);
	}
	else{
		var itemFound = timFindItemInArray(timRoomsSelected, 'id', roomId);

		if (itemFound){
			itemFound.unitsPerRoom = unitsPerRoom;
		}
		else{
			room.unitsPerRoom = unitsPerRoom;
			timRoomsSelected.push(room);
		}
	}

	if (unitsPerRoom > 1){
		var totalAdultsPerUnit, adultsSelected;
		var totalChildrenPerUnit, childrenSelected;

		for (var j = 1; j < unitsPerRoom; j++){
			totalAdultsPerUnit   = (roomTypeSelected && roomTypeSelected.units[j]) ? roomTypeSelected.units[j].adults : 2;
			totalChildrenPerUnit = (roomTypeSelected && roomTypeSelected.units[j]) ? roomTypeSelected.units[j].children : 0;

			// Adults
			adultsContent = '<div class="tim_pax_selector">';
			adultsContent += "<select id='timAdults_"+ roomId +"["+ j +"]' name='timAdults_"+ roomId +"["+ j +"]'";
			adultsContent += " onchange='timCheckHotelRoomUnitPax("+ JSON.stringify(room) +", "+ j +", "+ JSON.stringify(dataTaxes) +")'>";
			for ( x = 1; x <= room.max_occupancy; x++ ) {
				adultsSelected = (x === totalAdultsPerUnit) ? ' selected' : '';

				adultsContent += '<option value="'+ x +'"'+ adultsSelected +'>'+ x +'</option>';
			}
			adultsContent += '</select>';
			adultsContent += '</div>';

			// Children
			childrenContent = '<div class="tim_pax_selector">';
			if (room.max_occupancy > 2 && room.children_allowed){
				childrenContent += "<select id='timChildren_"+ roomId +"["+ j +"]' name='timChildren_"+ roomId +"["+ j +"]'";
				childrenContent += " onchange='calculateHotelRoomsTotalPrice("+ JSON.stringify(dataTaxes) +")'>";
				// for ( x = 0; x <= room.max_occupancy-2; x++ ) {
				for ( x = 0; x <= room.max_children_occupancy; x++ ) {
					childrenSelected = (x === totalChildrenPerUnit) ? ' selected' : '';

					childrenContent += '<option value="'+ x +'"'+ childrenSelected +'>'+ x +'</option>';
				}
				childrenContent += '</select>';
			}
			else{
				childrenContent += '<label>-</label>';
			}
			childrenContent += '</div>';

			paxContent += '<div>'+ adultsContent + childrenContent +'</div>'; //  style="float: left; width: 100%;"
		}
	}

	document.getElementById('timAdditionalPaxContainer_'+ roomId).innerHTML = paxContent;

	if (calculateTotalPrice){
		calculateHotelRoomsTotalPrice(dataTaxes);
	}
}

function timCheckHotelRoomUnitPax(room, j, dataTaxes){
	var roomId = room.id;

	var adults = document.querySelector('[name="timAdults_'+ roomId +'['+ j +']"]').value;

	if (room.max_occupancy > 2 && room.children_allowed){
		var children = document.querySelector('[name="timChildren_'+ roomId +'['+ j +']"]');
		
		// var maxChildrenAllowed = parseInt(room.max_occupancy) - parseInt(adults);

		var availableOccupancy = parseInt(room.max_occupancy) - parseInt(adults);
        var maxChildrenAllowed = availableOccupancy > room.max_children_occupancy ? room.max_children_occupancy : availableOccupancy;

		// Add children if max > adults
		if (maxChildrenAllowed > 0){
			for (var c = children.length; c <= maxChildrenAllowed; c++){
            	var option = new Option(c, c);
            	children.add(option, undefined);

				// var option   = document.createElement('option');
				// option.value = c;
				// option.text  = c;
				// children.appendChild(option);
			}
		
			// Remove children
			if (children.length - 1 >= maxChildrenAllowed){
				for (var c = children.length - 1; c > maxChildrenAllowed; c--){
					children.remove(c);
					// children.removeChild(children.options[c]);
				}

				if (children.value > maxChildrenAllowed){
                    children.value = 0; 
                }
			}
		}
		else{
			children.innerHTML = '<option value="0" selected>0</option>';
		}
	}

	calculateHotelRoomsTotalPrice(dataTaxes);
}

// validate if at least one room is selected and enable book button form
// otherwise disable button
function calculateHotelRoomsTotalPrice(dataTaxes){
	var bookingItemId = document.getElementById('timBookingItemId') ? document.getElementById('timBookingItemId').value : '';

	var startDate = document.getElementById('timFromDB').value;
	var endDate   = document.getElementById('timToDB').value;

	var currencyId     = document.getElementById('timUserCurrency').value;
	var currencyCode   = document.getElementById('timUserCurrencyCode').value;
	var currencySymbol = document.getElementById('timUserCurrencySymbol').value;

	var start = timParseDate(startDate);
	var end   = timParseDate(endDate);

	var totalNights = timGetDiffInDays(start, end);

	var content;

	var adultsPerUnit, 
	    childrenPerUnit, 
	    totalPricePerUnit = 0;

	var totalAdultsPerRoom, 
	    totalChildrenPerRoom
	    totalPricePerRoom = 0;

	var totalRoomsSelected    = 0,
	    totalAdultsSelected   = 0, 
	    totalChildrenSelected = 0, 
	    costPerRoomOccupancy  = 0, 
	    pricePerRoomOccupancy = 0;
	    
	var itemPrice = 0;

		// discountPricePercentage = 0, // If we enter coupon code
	    // extras             = 0

	var clientCommissionPercentage = 0;

	var disableBtn = false;

	var unitNumber;
	var unitsSelected = [];

	var room, roomId;
	var roomTypesSelected = '';

	var j;

	for (var i = 0; i < timRoomsSelected.length; i++){
		room   = timRoomsSelected[i];
		roomId = room.id;

		// At least one unit per room is selected
		if (room.unitsPerRoom > 0){
			totalRoomsSelected += room.unitsPerRoom;

			for (j = 0; j < room.unitsPerRoom; j++){
				unitNumber = (j+1);

				adultsPerUnit   = parseInt(document.querySelector('[name="timAdults_'+ roomId +'['+ j +']"]').value) || 0;
				childrenPerUnit = document.querySelector('[name="timChildren_'+ roomId +'['+ j +']"]') ? 
									parseInt(document.querySelector('[name="timChildren_'+ roomId +'['+ j +']"]').value) : 0;

				totalAdultsSelected   += adultsPerUnit;
				totalChildrenSelected += childrenPerUnit;

				room.date_rates.forEach(function (dateRate) {
					pricePerRoomOccupancy = getRatePerRoomOccupancy(adultsPerUnit, childrenPerUnit, dateRate, 'price');

					unitsSelected.push({
						day:           dateRate.day, 
						unit_number:   unitNumber, 
						adults:        adultsPerUnit, 
						children:      childrenPerUnit, 
						item_price:    pricePerRoomOccupancy, 
						hotel_id:      document.getElementById('timHotelId').value, 
						hotel_room_id: roomId
					});

					itemPrice += pricePerRoomOccupancy;

					clientCommissionPercentage = dateRate.client_commission_percentage; // TODO: it is taken the last, what about the rest per date ?
				});
			}

			roomTypesSelected += 
				room.name +' (x'+ room.unitsPerRoom +')<br />';
		}
	}

	// var itemTotals = getItemTotals(taxIncludedInPrice, itemPrice, discountPricePercentage, extras, taxPricePercentage);

	var label = timLabels;

	if (totalRoomsSelected > 0){
		var bookingItemData = {
			booking_type:        'hotel', 
			start_date:          startDate, // yyyy-mm-dd 
			end_date:            endDate,   // yyyy-mm-dd
			quantity:            totalRoomsSelected, 
			adults:              totalAdultsSelected, 
			children:            totalChildrenSelected, 
			infants:             0, 
			seniors:             0, 
			total_rooms:         totalRoomsSelected, 
			hotel_id:            document.getElementById('timHotelId').value, 
			hotel_content_id:    document.getElementById('timHotelContentId').value, 
			booking_item_hotel_dates: unitsSelected, 

			// spl_accepted: dataTaxes.spl_accepted // for booking
		};

		if (dataTaxes.spl_accepted){
			bookingItemData.spl_accepted = true
		}

		var productSaleTaxes         = dataTaxes.saleTaxes;
		var documentDateExchangeRate = dataTaxes.documentDateExchangeRate;
		var taxesIncluded            = dataTaxes.taxesIncluded;

		var booking = dataTaxes.booking;

		var item_price                       = applyExchangeRateConversion(itemPrice, documentDateExchangeRate);
        var base_price                       = setBasePrice(item_price, taxesIncluded, productSaleTaxes);
        var commission_price                 = base_price * (clientCommissionPercentage / 100);
        var net_price                        = base_price - commission_price;
        var pickup_price                     = 0;
        var dropoff_price                    = 0;
        var discount_price                   = 0;
        var subtotal_price                   = net_price + pickup_price + dropoff_price - discount_price;
        var tax_price_percentage             = getProductTaxesTotalPercentage(productSaleTaxes);
        var tax_exoneration_price_percentage = getProductTaxesTotalExonerationPercentage(productSaleTaxes);
		var net_tax_price_percentage         = tax_price_percentage - tax_exoneration_price_percentage;

        if (booking && booking.discount_coupon_id){
            if (booking.discount_coupon.discount_type == 'percentage'){
                discount_price = subtotal_price * (booking.discount_coupon.value / 100);
                subtotal_price = subtotal_price - discount_price;
            } else {
                discount_price = applyExchangeRateConversion(booking.discount_coupon.value, documentDateExchangeRate);
                subtotal_price = (item_price + pickup_price + dropoff_price - discount_price) / ((net_tax_price_percentage / 100) + 1);
            }
        }
        
        bookingItemData.sale_taxes = setProductTaxesAmounts(subtotal_price, productSaleTaxes);

        var tax_price             = getProductTaxesTotalAmount(bookingItemData.sale_taxes);
		var tax_exoneration_price = getProductTaxesTotalExonerationAmount(bookingItemData.sale_taxes);
		var net_tax_price         = tax_price - tax_exoneration_price;

        var total_price = subtotal_price + net_tax_price;

        bookingItemData.item_price                       = timRoundNum(item_price);
        bookingItemData.base_price                       = timRoundNum(base_price);
        bookingItemData.commission_price_percentage      = clientCommissionPercentage;
        bookingItemData.commission_price                 = timRoundNum(commission_price);
        bookingItemData.net_price                        = timRoundNum(net_price);
        bookingItemData.pickup_price                     = pickup_price;
        bookingItemData.dropoff_price                    = dropoff_price;
        bookingItemData.discount_price                   = timRoundNum(discount_price);
        bookingItemData.subtotal_price                   = timRoundNum(subtotal_price);
        bookingItemData.tax_price_percentage             = tax_price_percentage;
        bookingItemData.tax_price                        = timRoundNum(tax_price);
        bookingItemData.tax_exoneration_price_percentage = tax_exoneration_price_percentage;
		bookingItemData.tax_exoneration_price            = timRoundNum(tax_exoneration_price);
		bookingItemData.net_tax_price_percentage         = net_tax_price_percentage;
		bookingItemData.net_tax_price                    = timRoundNum(net_tax_price);
        bookingItemData.total_price                      = timRoundNum(total_price);

        var price = !taxesIncluded ? bookingItemData.subtotal_price : bookingItemData.total_price;

        bookingItemData.exchange_rate_value = documentDateExchangeRate.exchangeRateValue; // value for booking

        // params.bookingItemData.spl_accepted = params.spl_accepted || false;   // for booking

		bookingItemData.priceListIdByProvider = dataTaxes.priceListIdByProvider;

		var btnContent = '';
		if (!bookingItemId){ // Add
			btnContent = 
			"<button type='button' class='tim-btn' id='timAddItemBtn' onclick='timAddItemToOrder("+ JSON.stringify(bookingItemData) +")' disabled>"+
	        	label.bookNow +
	        '</button>';
		}
		else{ // Update
			bookingItemData.booking_item_id = bookingItemId;

			btnContent = 
			"<button type='button' class='tim-btn' id='timAddItemBtn' onclick='timUpdateItemFromOrder("+ JSON.stringify(bookingItemData) +")' disabled>"+
	        	label.updateOrder +
	        '</button>';
		}

		var label = timLabels;

		var taxesContent = '';
		if (bookingItemData.tax_price_percentage > 0){
			taxesContent += '<div style="tim-totals-price-note">+'+ label.taxes.toLowerCase() +'</div>';
		}

		var contentChildren = '';
		if (totalChildrenSelected){
			contentChildren = ' / <b>'+ label.children +':</b> '+ totalChildrenSelected;
		}

		content =
		'<ul>'+
	        '<li>'+ roomTypesSelected +'</li>'+
	        '<li>&nbsp;</li>'+
	        '<li>'+
	            '<b>'+ label.adults +':</b> '+ totalAdultsSelected + contentChildren + 
	        '</li>'+
	        '<li>'+
	            '<b>'+ label.roomNights +':</b> '+ totalNights + 
	        '</li>'+
	        '<li class="tim-totals-price">'+
	            '<div class="tim-totals-price-label">'+ label.totalPrice +'</div>'+
	            '<div class="tim-totals-price-amount">'+ currencyCode +' <span>'+ currencySymbol + timCurrencyFormat(price) +'</span></div>'+
	            taxesContent +
	        '</li>'+
	    '</ul>'+ 
	    btnContent;
	}
	else{
		content = label.enterOneRoom;

		disableBtn = true;
	}

	/*var totalsBoxWrapper = jQuery('.tim-totals-box-wrapper');
	totalsBoxWrapper.addClass('tim-transition-in');
	setTimeout(function() {
    	totalsBoxWrapper.removeClass('tim-transition-in');
    	totalsBoxWrapper.addClass('tim-transition-out');
    }, 500);*/

    // var totalsBoxWrapper = document.getElementsByClassName('tim-totals-box-wrapper')[0];
    var totalsBoxWrapper = document.querySelector('.tim-totals-box-wrapper');
	totalsBoxWrapper.classList.add('tim-transition-in');
	setTimeout(function() {
    	totalsBoxWrapper.classList.remove('tim-transition-in');
    	totalsBoxWrapper.classList.add('tim-transition-out');
    }, 500);

 	document.getElementById('timTotalsBoxResult').innerHTML = content;

 	if (document.getElementById('timAddItemBtn')){
 		document.getElementById('timAddItemBtn').disabled = disableBtn;
 	}
}


// PACKAGES
// ------------------------------------

function timCheckPackageRates(){ }

function timCheckPackageForm(){
	setButton();
	var error = 0;

	var packageDate = document.getElementById('timFromDB').value;
	var adults      = document.getElementById('timAdults').value;
	var children    = document.getElementById('timChildren').value;
	var infants     = document.getElementById('timInfants').value;
	var name        = document.getElementById('timCustomerName').value;
	var email       = document.getElementById('timCustomerEmail').value;

	var today = getTodayDate();

	if (packageDate && packageDate <= today){
		alert('Invalid date');
		return;
	}

	error = validateEmail('timCustomerEmail', error); // , 'timLabelErrorEmail'
	error = validateInput('timCustomerName', error); // , 'timLabelErrorName'
	error = validateInput('timAdults', error); // , 'timLabelErrorAdults'
	error = validateInput('timFrom', error); // , 'timLabelErrorCheckIn'

	if (error){
		setButton(1);
		return false;
	}

	var params = {
		action:       'check_package_request_api', 
        f_nonce:      timData.f_nonce, 
       	package_id:   document.getElementById('timPackageId').value, 
       	package_date: packageDate, 
       	adults:       parseInt(adults), 
        children:     parseInt(children), 
        infants:      parseInt(infants), 
        name:         name, 
        email:        email, 
        lang:         document.getElementById('timCL').value 
	};

	// console.log(data); return;

	timShowSpinner('tim_travel_form_spinner');

	// jQuery.post(timData.ajaxurl, params, function(response) {
	timPostAjax(timData.ajaxurl, params, function(response){
      	if (response == 'false'){ return false; }

      	// console.log(response);
      	// return;

      	var formMsg = document.getElementById('timRequestPackageFormMsg');

		var labelRequestPackageSent = document.getElementById('timLabelRequestPackageSent').value;
		formMsg.innerHTML = '<br /><div class="tim_alert tim_alert_lg tim_alert_success">'+ labelRequestPackageSent +' <a href="javascript:void(0);" onclick="timCloseAlert(\'timRequestPackageFormMsg\')" class="tim_alert_close">x</a></div>';

		document.getElementById('timFrom').value = '';
		document.getElementById('timAdults').value = '';
		document.getElementById('timChildren').value = 0;
		document.getElementById('timInfants').value = 0;
		document.getElementById('timCustomerName').value = '';
		document.getElementById('timCustomerEmail').value = '';

		timHideSpinner('tim_spinner');
    });
}


// Set price for all paxes
function setDayPrice(item, productCostType, adults, children, infants, seniors, documentDateExchangeRate) {
    var amount;

    if (productCostType === 'per_person'){
        amount = adults   * item.adult_price + 
                 children * item.child_price + 
                 infants  * item.infant_price + 
                 seniors  * item.senior_price;
    }
    else{
        amount = item.service_price;
    }

    return applyExchangeRateConversion(amount, documentDateExchangeRate);
}

function setBasePrice(item_price, taxesIncluded, taxes){
	if (!taxesIncluded || !taxes){
        return item_price;
    }

    var totalTaxesValue = taxes.reduce(function(total, tax) {
    	return total + tax.value;
    }, 0);

    var totalTaxesExonerationsPercentage = taxes.reduce(function(total, tax) {
    	return (tax.exoneration ? tax.exoneration.percentage : 0);
    }, 0);

    var totalNetTaxesPercentage = totalTaxesValue - totalTaxesExonerationsPercentage;
    
    return item_price / ((totalNetTaxesPercentage / 100) + 1); // totalTaxesValue

    // return timRoundNum( item_price / (totalTaxesValue/100+1) );
    // return item_price / (totalTaxesValue/100+1);
}

// For including the amount to tax
function setProductTaxesAmounts(subtotal, taxes){
	if (taxes.length === 0) return taxes;

    return taxes.map(function (tax) {
        tax.amount = (tax.computation_type === 'percentage') ? subtotal * (tax.value / 100) : tax.value;

        // To include the amount to tax exoneration
        if (tax.computation_type === 'percentage' && tax.exoneration) {
            tax.exoneration.amount = subtotal * (tax.exoneration.percentage / 100);
        }

        return tax;
    });

    return 0;
}

function getProductTaxesTotalPercentage(taxes){
	if (taxes.length === 0) return 0;

    return taxes.reduce(function(total, tax) {
    	return total + tax.value;
    }, 0);
}

function getProductTaxesTotalAmount(taxes){
	if (taxes.length === 0) return 0;

	return timRoundNum(taxes.reduce(function(total, tax) {
    	return total + tax.amount;
    }, 0));

    // return timRoundNum(taxes.reduce(function(total, tax) {
    // 	return total + tax.amount;
    // }, 0));
}

function getProductTaxesTotalExonerationPercentage(taxes){
    if (taxes.length === 0) return 0;

    return taxes.reduce(function(total, tax) {
    	return total + (tax.exoneration ? tax.exoneration.percentage : 0);
    }, 0);
}

function getProductTaxesTotalExonerationAmount(taxes){
    if (taxes.length === 0) return 0;

    return timRoundNum(taxes.reduce(function(total, tax) {
    	return total + (tax.exoneration ? tax.exoneration.amount : 0);
    }, 0));
}

// For all transactions
function applyExchangeRateConversion(amount, documentDateExchangeRate){
    if (amount === 0){
        return amount;
    }

    var value;

    switch (documentDateExchangeRate.conversion){
        case 'multiply':
            value = amount * documentDateExchangeRate.value;
        break;
        case 'divide':
            value = amount / documentDateExchangeRate.value;
        break;
        default:
            value = amount * documentDateExchangeRate.value[0] / documentDateExchangeRate.value[1];
        break;
    }

    return timRoundNum(value);
}

function getRatePerRoomOccupancy(adults, children, dateRate, option){
	var rate = 0;

	switch (adults){
        case 1:
          	rate = dateRate['single_'+ option];
        break;
        case 2:
         	rate = dateRate['double_'+ option];
        break;
        case 3:
        	rate = dateRate['triple_'+ option];
       	break;
        case 4:
         	rate = dateRate['quadruple_'+ option];
        break;
        default:
        	rate = (dateRate['quadruple_'+ option] + ((adults - 4) * dateRate['additional_person_'+ option]));
        break;
    }

    rate = rate + (children * dateRate['child_'+ option]);

    return rate;
}




// function setDayTotalPrice(day){
//     return timRoundNum(day.subtotal_price + day.tax_price);
// }





// var actualChildrenLength = children.length; // children('option') if using Jquery
					// children.innerHTML += '<option value="'+ c +'">'+ c +'</option>'; // Not working
					// document.querySelector('[name="timChildren_'+ roomId +'['+ j +']"] option[value="'+ c +'"]').remove();

	// tax_price_percentage:      13%
	// discount_price_percentage: 10%

	// TAX APPLIED TO PRICE
	// item_price:          $100
	// discount_price:      $10-   ( item_price * (discount_price_percentage / 100) )
	// Subtotal:            $90    ( item_price - discount_price )
	// Extras:              $20+   ( pickup_price + dropoff_price ) *with tax
	// TotalPrice:          $110   ( Subtotal + Extras )
	// tax_price:           $12.65 ( TotalPrice - TotalPrice / ((tax_price_percentage / 100)+1) )
	// TotalWithOutTax:     $97.34 ( TotalPrice - tax_price )


	// TAX NOT APPLIED TO PRICE - first get base price with tax
	// item_price:          $88.50
	// tax_price:           $11.51 ( itemPriceWithTax - item_price )
	// itemPriceWithTax:    $100   ( item_price * ((tax_price_percentage / 100)+1) )
	// discount_price:      $10-   ( itemPriceWithTax * (discount_price_percentage / 100) )
	// Subtotal:            $90    ( itemPriceWithTax - discount_price )
	// Extras:              $20+   ( pickup_price + dropoff_price ) *with tax
	// TotalPrice:          $110   ( Subtotal + Extras )
	// tax_price:           $12.65 ( TotalPrice - TotalPrice / ((tax_price_percentage / 100)+1) )
	// TotalWithOutTax:     $97.34 ( TotalPrice - tax_price )