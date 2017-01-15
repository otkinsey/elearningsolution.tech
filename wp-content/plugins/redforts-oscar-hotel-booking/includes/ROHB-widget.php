<?php

/**
 * Adds Booking Widget widget
 *
 * @author Redforts Software S.L.
 * @copyright Copyright (c) 2015 Redforts Software S.L.
 * @link https://redforts.com
 * @since 0.1.1
 */

class ROHB_Widget extends WP_Widget {

    /**
     * An array of settings for the plugin
     * @var array
     */
    public $settings;

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'rohb_widget', // Base ID
            __('Redforts booking widget', 'rohb'), // Name
            array('description' => __('Displays a booking form connected to your booking engine', 'rohb')) // Args
        );


        // Get current settings
        $this->settings = get_option('rohb');
    }

    /**
     * Returns the value of an option if exists or empty
     */
    public function get_settings_value($field)
    {
        return (isset($this->settings[$field])) ? esc_attr($this->settings[$field]) : '';
    }


    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        $client_code = $this->get_settings_value('client_code');
        // If client code does not exist return
        if (!$client_code) return false;

        // Build widget with $args
        if (!empty($instance['button_txt'])) {
            $button_txt = $instance['button_txt'];
        } else {
            $button_txt = __('Find room', 'rohb');
        }
        // Show promo input
        if (!empty($instance['promo'])) {
            $promo = sprintf('<div class="form-group"><input type="text" name="promo" class="promo_redforts_com" placeholder="%s"></div>', __('Promotional code', 'rohb'));
        } else {
            $promo = '';
        }
        // Filter acco
        if (!empty($instance['acco'])) {
            $accomodation = '<input type="hidden" name="acco" value="' . $instance['acco'] . '">';
        } else {
            $accomodation = '';
        }
        // Filter rate
        if (!empty($instance['rate'])) {
            $rate = '<input type="hidden" name="rate" value="' . $instance['rate'] . '">';
        } else {
            $rate = '';
        }

        // Set form target
        if ($this->get_settings_value('target_blank')) {
            $open_form = sprintf('<form method="POST" action="https://booking.redforts.com/%s" class="widget_redforts_com" target="_blank">', $client_code);
        } else {
            $open_form = sprintf('<form method="POST" action="https://booking.redforts.com/%s" class="widget_redforts_com">', $client_code);
        }

        // widget HTML
        $html = '<div class="rdf-booking-widget">'
              . $open_form
              . $accomodation
              . $rate
              . sprintf('<div class="form-group"><input type="text" placeholder="%s" readonly="readonly" autocomplete="off" class="arrival_redforts_com"></div>', __('Arrival', 'rohb'))
              . sprintf('<div class="form-group"><input type="text" placeholder="%s" readonly="readonly" autocomplete="off" class="departure_redforts_com"></div>', __('Departure', 'rohb'))
              . $promo
              . sprintf('<div class="form-group"><input value="%s" type="submit" class="submit_redforts_com"></div>', $button_txt)
              . '</form></div>';

        // echo widget
        echo $args['before_widget'];
        echo $args['before_title'];
        echo apply_filters('widget_title', $instance['title']);
        echo $args['after_title'];
        echo $html;
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        $title =  !empty($instance['title']) ? $instance['title'] : '';
        $button_txt = !empty($instance['button_txt']) ? $instance['button_txt'] : '';
        $acco = !empty($instance['acco']) ? $instance['acco'] : '';
        $rate = !empty($instance['rate']) ? $instance['rate'] : '';
        $promo = !empty($instance['promo']) ? 'checked="checked"' : '';
        $html = '';

        // Widget title
        $html .= sprintf('<p><label for="%s">%s</label>', $this->get_field_id('title'), __('Title'));
        $html .= sprintf('<input class="widefat" type="text" id="%s" name="%s" value="%s"></p>',
                         $this->get_field_id('title'),
                         $this->get_field_name('title'),
                         esc_attr($title));

        // Button text
        $html .= sprintf('<p><label for="%s">%s</label>', $this->get_field_id('button_txt'), __('Button text', 'rohb'));
        $html .= sprintf('<div class="description">%s</div>',  __('Leave empty to show the term "find room" translated in the appropiate language.', 'rohb'));
        $html .= sprintf('<input class="widefat" type="text" id="%s" name="%s" value="%s"></p>',
                         $this->get_field_id('button_txt'),
                         $this->get_field_name('button_txt'),
                         esc_attr($button_txt));

        // Accomodation
        $html .= sprintf('<p><label for="%s">%s</label>', $this->get_field_id('acco'), __('Accommodation ID(s)', 'rohb'));
        $html .= sprintf('<input class="widefat" type="text" id="%s" name="%s" value="%s"></p>',
                         $this->get_field_id('acco'),
                         $this->get_field_name('acco'),
                         esc_attr($acco));

        // Rates
        $html .= sprintf('<p><label for="%s">%s</label>', $this->get_field_id('rate'), __('Rate ID(s)', 'rohb'));
        $html .= sprintf('<input class="widefat" type="text" id="%s" name="%s" value="%s"></p>',
                         $this->get_field_id('rate'),
                         $this->get_field_name('rate'),
                         esc_attr($rate));

        // Promo code
        $html .= sprintf('<p><input class="checkbox" type="checkbox" id="%s" name="%s" %s>',
                         $this->get_field_id('promo'),
                         $this->get_field_name('promo'),
                         $promo);

        $html .= sprintf('<label for="%s">%s</label></p>', $this->get_field_id('promo'), __('Show promo code input', 'rohb'));

        echo $html;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array(
            'title' => (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '',
            'button_txt' => (!empty($new_instance['button_txt'])) ? strip_tags($new_instance['button_txt']) : '',
            'promo' => (!empty($new_instance['promo'])) ? '1' : '',
            'acco' => (!empty($new_instance['acco'])) ? strip_tags($new_instance['acco']) : '',
            'rate' => (!empty($new_instance['rate'])) ? strip_tags($new_instance['rate']) : '',
        );

        return $instance;
    }

    /**
     * Generates the url to widget.js
     */
    public function widget_script($lang=false)
    {
        $client_code = $this->get_settings_value('client_code');

        if (!$client_code) {
            return false;
        }

        if (!$lang) {
            return sprintf('https://content.redforts.com/%s/widget.js', $client_code);
        } else {
            return sprintf('https://content.redforts.com/%s/%s/widget.js', $lang, $client_code);
        }
    }
}
