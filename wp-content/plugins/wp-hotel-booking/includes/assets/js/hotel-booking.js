;
(function ($) {
	var $doc = $(document);

	if ( Date.prototype.compareWith == undefined ) {
		Date.prototype.compareWith = function (d) {
			if (typeof d == 'string')
			{
				d = new Date(d);
			}

			var thisTime = parseInt(this.getTime() / 1000),
				compareTime = parseInt(d.getTime() / 1000);
			if (thisTime > compareTime) {
				return 1;
			} else if (thisTime < compareTime) {
				return -1;
			}
			return 0;
		}
	}

	function isEmail( email ) {
		return new RegExp('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+@[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$').test(email);
	}

	function isDate( date ) {
		date = new Date( date );
		return ! isNaN( date.getTime() );
	}

	function parseJSON(data) {
		if ( ! $.isPlainObject( data ) ) {
			var m = data.match(/<!-- HB_AJAX_START -->(.*)<!-- HB_AJAX_END -->/);
			try {
				if (m) {
					data = $.parseJSON(m[1]);
				} else {
					data = $.parseJSON(data);
				}
			} catch (e) {
				console.log(e);
				data = {};
			}
		}
		return data;
	}

	function fetchCustomerInfo() {
		var $button = $(this),
			$email = $('input[name="existing-customer-email"]');
		if ( ! isEmail( $email.val() ) ) {
			alert( hotel_booking_l18n.invalid_email );
			$email.focus();
			return;
		}
		$button.attr('disabled', true);
		$email.attr('disabled', true);
		var customer_table = $('.hb-col-padding hb-col-border');
		$.ajax({
			url       : hotel_settings.ajax,
			dataType  : 'html',
			type      : 'post',
			data      : {
				action: 'hotel_booking_fetch_customer_info',
				email : $email.val()
			},
			beforeSend: function () {
				customer_table.hb_overlay_ajax_start();
			},
			success   : function (response) {
				customer_table.hb_overlay_ajax_stop();
				response = parseJSON( response );
				if ( response && response.ID ) {
					var $container = $('#hb-order-new-customer');
					for ( var key in response.data ) {
						var inputName = key.replace(/^_hb_/, '');
						var $field = $container.find('input[name="' + inputName + '"], select[name="' + inputName + '"], textarea[name="' + inputName + '"]');
						$field.val(response.data[key]);
					}
					$container.find('input[name="existing-customer-id"]').val(response.ID);
					$('.hb-order-existing-customer').fadeOut(function () {
						//$(this).remove();
					});
				} else {
					alert('Customer email not found!');
				}
				$button.removeAttr('disabled');
				$email.removeAttr('disabled');

			},
			error     : function () {
				customer_table.hb_overlay_ajax_stop();
				alert( hotel_booking_l18n.ajax_error );
				$button.removeAttr( 'disabled' );
				$email.removeAttr( 'disabled' );
			}
		});
	}

	function validateOrder($form) {

		var $title = $('select[name="title"]', $form);
		if (-1 == $title.val()) {
			alert(hotel_booking_l18n.empty_customer_title);
			$title.focus();
			return false;
		}

		var $firstName = $('input[name="first_name"]', $form);
		if ( ! $firstName.val() ) {
			alert(hotel_booking_l18n.empty_customer_first_name);
			$firstName.focus();
			return false;
		}

		var $lastName = $('input[name="last_name"]', $form);
		if ( ! $lastName.val() ) {
			alert( hotel_booking_l18n.empty_customer_last_name );
			$lastName.focus();
			return false;
		}

		var $address = $('input[name="address"]', $form);
		if ( ! $address.val()) {
			alert( hotel_booking_l18n.empty_customer_address );
			$address.focus();
			return false;
		}

		var $city = $('input[name="city"]', $form);
		if (! $city.val() ) {
			alert(hotel_booking_l18n.empty_customer_city);
			$city.focus();
			return false;
		}

		var $state = $('input[name="state"]', $form);
		if ( ! $state.val() ) {
			alert( hotel_booking_l18n.empty_customer_state );
			$state.focus();
			return false;
		}

		var $postalCode = $('input[name="postal_code"]', $form);
		if ( ! $postalCode.val() ) {
			alert( hotel_booking_l18n.empty_customer_postal_code );
			$postalCode.focus();
			return false;
		}

		var $country = $('select[name="country"]', $form);
		if ( ! $country.val() ) {
			alert( hotel_booking_l18n.empty_customer_country );
			$country.focus();
			return false;
		}

		var $phone = $('input[name="phone"]', $form);
		if ( ! $phone.val() ) {
			alert( hotel_booking_l18n.empty_customer_phone );
			$phone.focus();
			return false;
		}

		var $email = $('input[name="email"]', $form);
		if ( ! isEmail( $email.val() ) ) {
			alert( hotel_booking_l18n.customer_email_invalid );
			$email.focus();
			return false;
		}

		var $payment_method = $('input[name="hb-payment-method"]:checked');
		if ( $payment_method.length == 0 ) {
			alert( hotel_booking_l18n.no_payment_method_selected );
			return false;
		}

		var $tos = $('input[name="tos"]');
		if ( $tos.length && ! $tos.is(':checked') ) {
			alert(hotel_booking_l18n.confirm_tos);
			return false;
		}
		if ( $('input[name="existing-customer-id"]', $form).val() ) {
			if ($email.val() != $('input[name="existing-customer-email"]', $form).val()) {
				if ( ! confirm( hotel_booking_l18n.customer_email_not_match ) ) {
					return false;
				}
			}
		}
		return true;
	}

	function stripeSubmit(form) {
		var pl_key = 'pk_test_HHukcwWCsD7qDFWKKpKdJeOT';
		if ( typeof TPBooking_Payment_Stripe !== 'undefined' ) {
			pl_key = TPBooking_Payment_Stripe.stripe_publish;
		}

		var handler = StripeCheckout.configure({
			key   : pl_key,
			image : 'https://stripe.com/img/documentation/checkout/marketplace.png',
			locale: 'auto',
			token : function (token) {
				// Use the token to create the charge with a server-side script.
				// You can access the token ID with `token.id`
				stripe_payment_process(form, token);
			}
		});

		var first_name = form.find('input[name="first_name"]').val().trim();
		var last_name = form.find('input[name="last_name"]').val().trim();
		var email = form.find('input[name="email"]').val().trim();
		var currency = form.find('input[name="currency"]').val().trim();
		var price = 0;
		if (form.find('input[name="pay_all"]').is(':checked')) {
			price = form.find('input[name="total_price"]').val();
		}
		else {
			price = form.find('input[name="total_advance"]').val();
		}

		// Open Checkout with further options
		handler.open({
			name       	: first_name + ' ' + last_name,
			description	: email,
			currency	: currency,
			amount     	: price * 100
		});
	}

	function stripe_payment_process(form, token) {
		var data = {};
		var payment_data = form.serializeArray();
		var button = form.find('button[type="submit"]');

		$.each(payment_data, function (index, obj) {
			data[obj.name] = obj.value;
		});

		$.extend(token, data);

		$.ajax({
			url       : hotel_settings.ajax,
			data      : token,
			type      : 'POST',
			dataType  : 'html',
			beforeSend: function () {
				button.addClass('hb_loading');
			}
		}).done(function (res) {
			button.removeClass('hb_loading');
			res = parseJSON(res);

			if ( typeof res.result !== 'undefined' && res.result == 'success' ) {
				if (typeof res.redirect !== 'undefined')
					window.location.href = res.redirect;
			}
			else if (typeof res.message !== 'undefined') {
				alert(res.message);
			}
		}).fail(function () {
			button.removeClass('hb_loading');
		});
	}

	function orderSubmit(form) {
		var action = window.location.href.replace(/\?.*/, '');
		form.attr('action', action);
		var button = form.find('button[type="submit"]');
		if (form.triggerHandler('hotel_booking_place_order') !== false) {
			$.ajax({
				type      : 'POST',
				url       : hotel_settings.ajax,
				data      : form.serialize(),
				dataType  : 'text',
				beforeSend: function () {
					button.addClass('hb_loading');
				},
				success   : function (code) {
					button.removeClass('hb_loading');
					try {
						var response = parseJSON(code);
						if (response.result == 'success') {
							if (response.redirect != undefined) {
								window.location.href = response.redirect;
							}
						}
						else if (typeof response.message !== 'undefined') {
							alert(response.message);
						}
					} catch (e) {
						alert(e)
					}
				},
				error     : function () {
					button.removeClass('hb_loading');
					alert('eror')
				}

			});
		}
		return false;
	}

	function applyCoupon() {
		var $coupon = $('input[name="hb-coupon-code"]');
		var table = $coupon.parents('table');
		if (!$coupon.val()) {
			alert(hotel_booking_l18n.enter_coupon_code)
			$coupon.focus();
			return false;
		}
		$.ajax({
			type      : 'POST',
			url       : hotel_settings.ajax,
			data      : {
				action: 'hotel_booking_apply_coupon',
				code  : $coupon.val()
			},
			dataType  : 'text',
			beforeSend: function () {
				table.hb_overlay_ajax_start();
			},
			success   : function (code) {
				table.hb_overlay_ajax_stop();
				try {
					var response = parseJSON(code);
					if (response.result == 'success') {
						window.location.href = window.location.href;
					} else {
						alert(response.message);
					}
				} catch (e) {
					alert(e)
				}
			},
			error     : function () {
				table.hb_overlay_ajax_stop();
				alert('error')
			}
		});
	}

	/**
	 * HB_Booking_Cart object class
	 * @type {Object}
	 */
	HB_Booking_Cart = {

		init: function () {
			this.add_to_cart();
			this.remove_cart();
		},

		hb_add_to_cart_callback: function ( data, callback ) {
			var mini_cart = $('.hotel_booking_mini_cart');
			var length = mini_cart.length;
			var template = wp.template('hb-minicart-item');
			template = template(data);

			if ( length === 0 )
				return;

			for ( var i = 0; i < length; i++ ) {
				var cart = $(mini_cart[i]),
					cart_item = $(mini_cart[i]).find('.hb_mini_cart_item'),
					insert = false,
					empty = cart.find('.hb_mini_cart_empty'),
					footer_ele = cart.find('.hb_mini_cart_footer'),
					items_length = cart_item.length;

				if ( items_length === 0 ) {
					var footer = wp.template('hb-minicart-footer');
					var ele = footer_ele;
					if ( empty.length === 1 ) {
						empty.after(footer({}));
						empty.before(template);
					} else {
						footer_ele.before(template);
					}
					insert = true;
					break;
				} else {
					for ( var y = 0; y < items_length; y++ ) {
						var item = $(cart_item[y]),
							cart_id = item.attr( 'data-cart-id' );

						if ( data.cart_id === cart_id ) {
							item.replaceWith( template );
							insert = true;
							break;
						}
					}

					if ( insert === false ) {
						footer_ele.before( template );
					}
				}
			}

			$('.hb_mini_cart_empty').remove();
			var timeout = setTimeout(function () {
				$('.hb_mini_cart_item').removeClass('active');
				clearTimeout(timeout);
			}, 3500);

			if ( typeof callback !== 'undefined' ) {
				callback();
			}
		},

		hb_remove_cart_item_callback: function ( cart_id, res ) {
			var minicart = $('.hotel_booking_mini_cart');
			for (var i = 0; i < minicart.length; i++) {
				var cart = $(minicart[i]);
				var items = cart.find('.hb_mini_cart_item');

				for (var y = 0; y < items.length; y++) {
					var _item = $(items[y]),
						cart_item_id = _item.attr( 'data-cart-id' );
					if ( cart_id === cart_item_id ) {
						_item.remove();
						break;
					}
				}

				// append message empty cart
				items = cart.find('.hb_mini_cart_item');
				if (items.length === 0) {
					var empty = wp.template('hb-minicart-empty');
					cart.find('.hb_mini_cart_footer').remove();
					cart.append( empty({}) );
					break;
				}
			}

			var cart_table = $('#hotel-booking-payment, #hotel-booking-cart');

			for ( var i = 0; i < cart_table.length; i++ ) {
				var _table = $(cart_table[i]);
				var tr = _table.find('table').find('.hb_checkout_item, .hb_addition_services_title');
				for (var y = 0; y < tr.length; y++) {
					var _tr = $( tr[y] );
						cart_item_id = _tr.attr( 'data-cart-id' ),
						parent_item_id = _tr.attr( 'data-parent-id' );
					if ( cart_id === cart_item_id || cart_id === parent_item_id ) {
						_tr.remove();
						continue;
					}
				}

				if ( typeof res.sub_total !== 'undefined' )
					_table.find('span.hb_sub_total_value').html( res.sub_total );

				if ( typeof res.grand_total !== 'undefined' )
					_table.find('span.hb_grand_total_value').html( res.grand_total );

				if ( typeof res.advance_payment !== 'undefined' )
					_table.find('span.hb_advance_payment_value').html( res.advance_payment );

			}
		},

		add_to_cart: function () {
			var searchResult = $('form.hb-search-room-results');

			searchResult.each(function () {
				$(this).submit(function (event) {
					event.preventDefault();
					var _form = $(this);
					var button = _form.find('.hb_add_to_cart');
					var number_room_select = $(this).find('.number_room_select').val();
					if (typeof number_room_select === 'undefined' || number_room_select === '') {
						alert(hotel_booking_l18n.waring.room_select);
						return false;
					}
					var data = $(this).serializeArray();
					var room_title = $(this).find('.hb-room-name');
					$.ajax({
						url       : hotel_settings.ajax,
						type      : 'POST',
						data      : data,
						dataType  : 'html',
						beforeSend: function () {
							// _form.hb_overlay_ajax_start();
							button.addClass('hb_loading');
						},
						success   : function (code) {
							_form.hb_overlay_ajax_stop();
							code = parseJSON(code);
							if (typeof code.message !== 'undefined') {
								room_title.find('.hb_success_message').remove();
								room_title.append(code.message);
								var timeOut = setTimeout(function () {
									room_title.find('.hb_success_message').remove();
								}, 3000);
							}

							if (typeof code.status !== 'undefined' && code.status === 'success') {
								// add message successfully
								if( typeof code.redirect !== 'undefined' )
								{
									window.location.href = code.redirect;
								}
							}
							else {
								alert(code.message);
							}

							if ( typeof code.id !== 'undefined' ) {
								HB_Booking_Cart.hb_add_to_cart_callback(code);
							}
							button.removeClass('hb_loading');
						},
						error     : function () {
							// searchResult.hb_overlay_ajax_stop();
							button.removeClass('hb_loading');
							alert(hotel_booking_l18n.waring.try_again);
						}
					});
					return false;
				});
			});
		},

		remove_cart: function () {
			// var updateOrderButton
			$(document).on('click', '.hb_remove_cart_item', function (e) {
				e.preventDefault();

				var tr = $(this).parents('tr'),
					cart_item = $(this).attr('data-cart-id');
				$.ajax({
					url       : hotel_settings.ajax,
					type      : 'POST',
					data      : {
						cart_id  : cart_item,
						nonce : hotel_settings.nonce,
						action: 'hotel_booking_ajax_remove_item_cart'
					},
					dataType  : 'html',
					beforeSend: function () {
						tr.hb_overlay_ajax_start();
					}
				}).done(function (res) {
					res = parseJSON(res);
					if (typeof res.status === 'undefined' || res.status !== 'success')
						alert(hotel_booking_l18n.waring.try_again);

					if (typeof res.sub_total !== 'undefined')
						$('span.hb_sub_total_value').html(res.sub_total);

					if (typeof res.grand_total !== 'undefined')
						$('span.hb_grand_total_value').html(res.grand_total);

					if (typeof res.advance_payment !== 'undefined')
						$('span.hb_advance_payment_value').html(res.advance_payment);
					tr.hb_overlay_ajax_stop();
					tr.remove();
					HB_Booking_Cart.hb_remove_cart_item_callback( cart_item, res );
				});
			});

			//remove minicart item
			$('.hotel_booking_mini_cart').on('click', '.hb_mini_cart_remove', function (event) {
				event.preventDefault();
				var minicart = $('.hotel_booking_mini_cart');
				var item = $(this).parents('.hb_mini_cart_item');
				var cart_id = item.attr( 'data-cart-id' );

				$.ajax({
					url       : hotel_settings.ajax,
					type      : 'POST',
					data      : {
						cart_id  	: cart_id,
						nonce 		: hotel_settings.nonce,
						action		: 'hotel_booking_ajax_remove_item_cart'
					},
					dataType  : 'html',
					beforeSend: function () {
						item.addClass('before_remove');
						item.hb_overlay_ajax_start();
					}
				}).done(function (res) {
					res = parseJSON(res);
					if ( typeof res.status === 'undefined' || res.status !== 'success' ) {
						alert(hotel_booking_l18n.waring.try_again);
						return;
					}

					HB_Booking_Cart.hb_remove_cart_item_callback( cart_id, res );
					item.hb_overlay_ajax_stop();
				});
			});
		},

	};

	$(document).ready(function () {
		HB_Booking_Cart.init();
		$.datepicker.setDefaults({ dateFormat: hotel_booking_l18n.date_time_format });
		// $.datepicker.setDefaults({dateFormat: 'mm/dd/yy'});
		var today = new Date();
		var tomorrow = new Date();
		tomorrow.setDate( today.getDate() + 1 );

		$('input[id^="check_in_date"]').datepicker({
			dateFormat 		: hotel_booking_l18n.date_time_format,
			monthNames 	  	: hotel_booking_l18n.monthNames,
			monthNamesShort	: hotel_booking_l18n.monthNamesShort,
			dayNames 		: hotel_booking_l18n.dayNames,
			dayNamesShort 	: hotel_booking_l18n.dayNamesShort,
			dayNamesMin		: hotel_booking_l18n.dayNamesMin,
			minDate       	: tomorrow,
			maxDate       	: '+365D',
			numberOfMonths	: 1,
			onSelect      : function (selected) {
				var unique = $(this).attr('id');
				unique = unique.replace('check_in_date_', '');
				var date = $(this).datepicker('getDate');
				if (date) {
					date.setDate(date.getDate() + 1);
				}
				$( '#check_out_date_' + unique).datepicker( 'option', 'minDate', date)
			}
		});

		$('input[id^="check_out_date"]').datepicker({
			dateFormat 		: hotel_booking_l18n.date_time_format,
			monthNames 	  	: hotel_booking_l18n.monthNames,
			monthNamesShort	: hotel_booking_l18n.monthNamesShort,
			dayNames 		: hotel_booking_l18n.dayNames,
			dayNamesShort 	: hotel_booking_l18n.dayNamesShort,
			dayNamesMin		: hotel_booking_l18n.dayNamesMin,
			minDate       	: tomorrow,
			maxDate       	: '+365D',
			numberOfMonths	: 1,
			onSelect      : function (selected) {
				var unique = $(this).attr('id');
				unique = unique.replace('check_out_date_', '');
				$( '#check_in_date_' + unique).datepicker( 'option', 'maxDate', selected);
			}
		});

		$('#datepickerImage').click( function () {
			$('#txtFromDate').datepicker('show');
		});

		$('#datepickerImage1').click( function () {
			$('#txtToDate').datepicker('show');
		});

		$('form[class^="hb-search-form"]').submit( function ( e ) {
			e.preventDefault();
			var unique = $(this).attr('class');
			var button = $(this).find('buton[type="submit"]');
			unique = unique.replace('hb-search-form-', '');

			var $check_in = $( '#check_in_date_' + unique );
			if ( ! isDate( $check_in.datepicker( 'getDate' ) ) ) {
				alert( hotel_booking_l18n.empty_check_in_date );
				$check_in.focus();
				return false;
			}

			var $check_out = $( '#check_out_date_' + unique );
			if ( ! isDate( $check_out.datepicker( 'getDate' ) ) ) {
				alert( hotel_booking_l18n.empty_check_out_date );
				$check_out.focus();
				return false;
			}

			var check_in = new Date( $check_in.datepicker( 'getDate' ) ),
				check_out = new Date( $check_out.datepicker( 'getDate' ) ),
				current = new Date();
			if ( check_in.compareWith( current ) == -1 ) {
				alert( hotel_booking_l18n.check_in_date_must_be_greater );
				$check_in.focus();
				return false;
			}

			if ( check_in.compareWith( check_out ) >= 0 ) {
				alert( hotel_booking_l18n.check_out_date_must_be_greater );
				$check_out.focus();
				return false;
			}

			var action = $(this).attr('action') || window.location.href;
			var data = $(this).serializeArray();
			for ( var i = 0; i < data.length; i++ ) {
				var input = data[i];
				if ( input.name === 'check_in_date' || input.name === 'check_out_date' ) {
					var time = $(this).find( 'input[name="'+input.name+'"]' ).datepicker('getDate');
					time = new Date( time );
					data.push({
						name: 'hb_' + input.name,
						value: time.getTime() / 1000 - ( time.getTimezoneOffset() * 60 )
					})
				}
			}
			$.ajax({
				url       : hotel_settings.ajax,
				type      : 'post',
				dataType  : 'html',
				data      : data,
				beforeSend: function () {
					button.addClass('hb_loading');
				},
				success   : function ( response ) {
					response = parseJSON(response);
					if( typeof response.success === 'undefined' || ! response.success ) {
						return;
					}

					// redirect if url is ! undefined
					if( typeof response.url !== 'undefined' )
					{
						window.location.href = response.url;
					}
					else if ( response.sig ) {
						window.location.href = action.replace(/\?.*/, '') + '?hotel-booking-params=' + response.sig;
					}
					button.removeClass('hb_loading');
				}
			});
			return false;
		});

		$('form#hb-payment-form').submit( function ( e ) {
			e.preventDefault();
			var _self = $(this);
			var _method = _self.find('input[name="hb-payment-method"]:checked').val();

			var action = window.location.href.replace(/\?.*/, '');
			try {

				if (_self.triggerHandler('hb_order_submit') === false) {
					return false;
				}

				_self.attr('action', action);

				if (!validateOrder(_self)) {
					return false;
				}

				if (_method === 'stripe') {
					stripeSubmit(_self);
				}
				else {
					orderSubmit(_self);
				}

			} catch (e) {
				alert(e)
			}
		});

		$('#fetch-customer-info').click(fetchCustomerInfo);

		$doc.on('click', '.hb-view-booking-room-details, .hb_search_room_item_detail_price_close', function (e) {
			e.preventDefault();
			var _self = $(this);
			var _details = _self.parents('.hb-room-content').find('.hb-booking-room-details');

			_details.toggleClass('active');

			// $(this).closest('.hb-room-content').find('.hb-booking-room-details').fadeToggle();
		}).on('click', 'input[name="hb-payment-method"]', function () {
			if (this.checked) {
				$('.hb-payment-method-form:not(.' + this.value + ')').slideUp();
				$('.hb-payment-method-form.' + this.value + '').slideDown();
			}
		}).on('click', '#hb-apply-coupon', function () {
			applyCoupon();
		}).on('click', '#hb-remove-coupon', function (evt) {
			evt.preventDefault();
			var table = $(this).parents('table');
			$.ajax({
				url       : hotel_settings.ajax,
				type      : 'post',
				dataType  : 'html',
				data      : {
					action: 'hotel_booking_remove_coupon'
				},
				beforeSend: function () {
					table.hb_overlay_ajax_start();
				},
				success   : function (response) {
					table.hb_overlay_ajax_stop();
					response = parseJSON(response)
					if (response.result == 'success') {
						window.location.href = window.location.href
					}
				}
			});
		});

		// single room detail tabs
		var hb_single_details = $('.hb_single_room_details');
		var hb_single_details_tab = hb_single_details.find('.hb_single_room_tabs')
		var hb_single_details_content = hb_single_details.find('.hb_single_room_tabs_content');
		var hb_single_tab_details = $('.hb_single_room_tab_details');
		var hb_current_uri = window.location.href;

		var commentID = hb_current_uri.match(/\#comment-[0-9]+/gi);

		if (commentID && typeof commentID[0] !== 'undefined') {
			hb_single_details_tab.find('a').removeClass('active');
			hb_single_details_tab.find('a[href="#hb_room_reviews"]').addClass('active');
		}
		else {
			hb_single_details_tab.find('a:first').addClass('active');
			$('.hb_single_room_tabs_content .hb_single_room_tab_details:not(:first)').hide();
		}

		hb_single_tab_details.hide();
		var tabActive = hb_single_details_tab.find('a.active').attr('href');
		hb_single_details_content.find(tabActive).show();

		hb_single_details_tab.find('a').on('click', function (event) {
			event.preventDefault();
			hb_single_details_tab.find('a').removeClass('active');
			$(this).addClass('active');
			var tab_id = $(this).attr('href');
			hb_single_tab_details.hide();
			hb_single_details_content.find(tab_id).show();
			return false;
		});

		$('.hb-rating-input').rating();

		$('#commentform').submit(function () {
			var rate = $( '#rating' ),
				val = rate.val();
			if( rate.length === 1 && typeof val !== 'undefined' && val === '' )
			{
				window.alert(hotel_booking_l18n.review_rating_required);
				return false;
			}
			$(this).submit();
		});
	});

	// rating single room
	$.fn.rating = function () {
		var ratings = this,
			legnth = this.length;

		for( var i = 0; i < legnth; i++ )
		{
			var rating = $( ratings[i] ),
				html = [];

			html.push( '<span class="rating-input" data-rating="1"></span>' );
			html.push( '<span class="rating-input" data-rating="2"></span>' );
			html.push( '<span class="rating-input" data-rating="3"></span>' );
			html.push( '<span class="rating-input" data-rating="4"></span>' );
			html.push( '<span class="rating-input" data-rating="5"></span>' );
			html.push( '<input name="rating" id="rating" type="hidden" value="" />' );
			rating.html( html.join('') );

			rating.mousemove( function( e ){
				e.preventDefault();
				var parentOffset = ratings.offset(),
					relX = e.pageX - parentOffset.left,
					star = $(this).find( '.rating-input' ),
					star_width = star.width(),
					rate = Math.ceil( relX / star_width );

				for( var y = 0; y < star.length; y++ )
				{
					var st = $( star[y] ),
						_data_star = parseInt( st.attr( 'data-rating' ) );
					if( _data_star <= rate )
					{
						st.addClass( 'high-light' );
					}
				}
			}).mouseout( function( e ){
				var parentOffset = ratings.offset(),
					relX = e.pageX - parentOffset.left,
					star = $(this).find( '.rating-input' ),
					star_width = star.width(),
					rate = $(this).find( '.rating-input.selected' );

				if( rate.length === 0 )
				{
					star.removeClass( 'high-light' );
				}
				else
				{
					for( var y = 0; y < star.length; y++ )
					{
						var st = $( star[y] ),
							_data_star = parseInt( st.attr( 'data-rating' ) );

						if( _data_star <= parseInt( rate.attr( 'data-rating' ) ) )
						{
							st.addClass( 'high-light' );
						}
						else
						{
							st.removeClass( 'high-light' );
						}
					}
				}
			}).mousedown( function( e ){
				var parentOffset = ratings.offset(),
					relX = e.pageX - parentOffset.left,
					star = $(this).find( '.rating-input' ),
					star_width = star.width(),
					rate = Math.ceil( relX / star_width );
				star.removeClass( 'selected' ).removeClass( 'high-light' );
				for( var y = 0; y < star.length; y++ )
				{
					var st = $( star[y] ),
						_data_star = parseInt( st.attr( 'data-rating' ) );
					if( _data_star === rate )
					{
						st.addClass( 'selected' ).addClass( 'high-light' );
						break;
					}
					else
					{
						st.addClass( 'high-light' );
					}
				}
				rating.find( 'input[name="rating"]' ).val( rate );
			} );

		}

	}

	// overlay before ajax
	$.fn.hb_overlay_ajax_start = function () {
		var _self = this;
		_self.css({
			'position': 'relative',
			'overflow': 'hidden'
		});
		var overlay = '<div class="hb_overlay_ajax">';
		overlay += '</div>';

		_self.append(overlay);
	}

	$.fn.hb_overlay_ajax_stop = function () {
		var _self = this;
		var overlay = _self.find('.hb_overlay_ajax');

		overlay.addClass('hide');
		var timeOut = setTimeout(function () {
			overlay.remove();
			clearTimeout(timeOut);
		}, 400);
	}

})((jQuery));