<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

	$hb_report = HB_Report_Price::instance();
?>
<h3 class="chart_title"><?php _e( 'Report Chart Amount Total', 'wp-hotel-booking' ) ?></h3>
<canvas id="hotel_canvas_report_price"></canvas>
<script>

	(function($){
		window.onload = function(){
			var ctx = document.getElementById( 'hotel_canvas_report_price' ).getContext( '2d' );

			window.myLine = new Chart( ctx ).Line( <?php echo json_encode( $hb_report->series() ) ?>, {
				responsive: true
			});
		}

		// $.datepicker.setDefaults({ dateFormat: hotel_booking_l18n.date_time_format });
		// $.datepicker.setDefaults({ dateFormat: 'mm/dd/yy' });
        $('#tp-hotel-report-checkin').datepicker({
            dateFormat      : hotel_booking_l18n.date_time_format,
            monthNames      : hotel_booking_l18n.monthNames,
            monthNamesShort : hotel_booking_l18n.monthNamesShort,
            dayNames        : hotel_booking_l18n.dayNames,
            dayNamesShort   : hotel_booking_l18n.dayNamesShort,
            dayNamesMin     : hotel_booking_l18n.dayNamesMin,
            onSelect: function(){
            	var _self = $(this),
                	date = $(this).datepicker('getDate'),
                	timestamp = new Date( date ) / 1000 - ( new Date().getTimezoneOffset() * 60 );

                $("#tp-hotel-report-checkout").datepicker( 'option', 'minDate', date);
                _self.parent().find( 'input[name="report_in_timestamp"]' ).val( timestamp );
            }
        });
        $('#tp-hotel-report-checkout').datepicker({
            dateFormat      : hotel_booking_l18n.date_time_format,
            monthNames      : hotel_booking_l18n.monthNames,
            monthNamesShort : hotel_booking_l18n.monthNamesShort,
            dayNames        : hotel_booking_l18n.dayNames,
            dayNamesShort   : hotel_booking_l18n.dayNamesShort,
            dayNamesMin     : hotel_booking_l18n.dayNamesMin,
            onSelect: function(){
            	var _self = $(this),
                	date = $(this).datepicker('getDate'),
                	timestamp = new Date( date ) / 1000 - ( new Date().getTimezoneOffset() * 60 );
                $("#tp-hotel-report-checkin").datepicker( 'option', 'maxDate', date)
                _self.parent().find( 'input[name="report_out_timestamp"]' ).val( timestamp );
            }
        });
	})(jQuery);

</script>
