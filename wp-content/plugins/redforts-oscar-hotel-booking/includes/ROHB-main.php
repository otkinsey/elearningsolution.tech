<?php

/**
 * Redforts Oscar Hotel Booking main class
 *
 * @author Redforts Software S.L.
 * @copyright Copyright (c) 2015 Redforts Software S.L.
 * @link https://redforts.com
 * @since 0.1.0
 */

class ROHB_Main {

    /**
     * The single instance of the class
     * @var ROHB_Main instance
     */
    public static $instance;

    /**
     * An array of settings for the plugin
     * @var array
     */
    public $settings;

    /**
     * Creates & returns an instance of the class
     * @return ROHB_Main instance
     */
    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Constructor
     * Adds hooks to initialize the class
     */
    public function __construct()
    {
        // Load languages
        add_action('plugins_loaded', array($this, 'load_languages'));

        // Adds shortcode
        add_action('init', array($this, 'register_shortcode'));

        // Adds widget
        add_action('widgets_init', array($this, 'register_widget'));
        // Add Custom widget area
        add_action('widgets_init', array($this, 'register_widget_area'));
        // Show custom widget area
        add_filter('the_content', array($this, 'show_widget_area'));

        // Enqueues js & css scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_widget_scripts'));

        // Adds custom css
        add_action('wp_head', array($this, 'enqueue_custom_css'));

        // Get current settings
        $this->settings = get_option('rohb');

        // Supported langauges
        $this->supported_languages = array(
            'ca' => 'Català',
            'es' => 'Castellano',
            'de' => 'Deutsch',
            'en' => 'English',
            'fr' => 'Français',
            'it' => 'Italiano',
            'nl' => 'Nederlands',
            'pt' => 'Português',
            'ru' => 'Русский',
        );

        // Load only if is in administrative section
        if (is_admin()) {
            ROHB_Admin::get_instance();
        }
    }

    /**
     * Loads language files
     */
    public function load_languages()
    {
        load_plugin_textdomain(
            'rohb',
            false,
            plugin_basename(dirname(ROHB_BASE) . '/languages')
        );
    }

    /**
     * Returns the value of an option if exists or empty
     */
    public function get_settings_value($field)
    {
        return (isset($this->settings[$field])) ? esc_attr($this->settings[$field]) : '';
    }

    /**
     * Generates the contents for the booking widget shortcode
     */
    public function booking_widget_shortcode($atts)
    {
        $client_code = $this->get_settings_value('client_code');

        if (!$client_code) return false;

        $a = shortcode_atts(array(
            'button_txt' => '',
            'promo_field' => '',
            'acco' => '',
            'rate' => '',
        ), $atts);

        wp_enqueue_script('oscar-widget-script', $this->widget_script());

        // Set default text for the button
        if (empty($a['button_txt'])) {
            $button_txt = __('Find room', 'rohb');
        } else {
            $button_txt = $a['button_txt'];
        }

        if (!empty($a['promo_field'])) {
            $promo = sprintf('<div class="form-group"><input type="text" name="promo" class="promo_redforts_com" placeholder="%s"></div>', __('Promotional code', 'rohb'));
        } else {
            $promo = '';
        }

        if (!empty($a['acco'])) {
            $accomodation = '<input type="hidden" name="acco" value="' . $a['acco'] . '">';
        } else {
            $accomodation = '';
        }

        if (!empty($a['rate'])) {
            $rate = '<input type="hidden" name="rate" value="' . $a['rate'] . '">';
        } else {
            $rate = '';
        }

        if ($this->get_settings_value('target_blank')) {
            $open_form = sprintf('<form method="POST" action="https://booking.redforts.com/%s" class="widget_redforts_com" target="_blank">', $client_code);
        } else {
            $open_form = sprintf('<form method="POST" action="https://booking.redforts.com/%s" class="widget_redforts_com">', $client_code);
        }

        $html = '<div class="rdf-booking-widget">'
              . $open_form
              . $accomodation
              . $rate
              . sprintf('<div class="form-group"><input type="text" placeholder="%s" readonly="readonly" autocomplete="off" class="arrival_redforts_com"></div>', __('Arrival', 'rohb'))
              . sprintf('<div class="form-group"><input type="text" placeholder="%s" readonly="readonly" autocomplete="off" class="departure_redforts_com"></div>', __('Departure', 'rohb'))
              . $promo
              . sprintf('<div class="form-group"><input value="%s" type="submit" class="submit_redforts_com"></div>', $button_txt)
              . '</form></div>';

        return $html;
    }

    /**
     * Register widget shortcode
     */
    public function register_shortcode()
    {
        add_shortcode('rdf-booking-widget', array($this, 'booking_widget_shortcode'));
    }

    /**
     * Register widget
     */
    public function register_widget()
    {
        register_widget('ROHB_Widget');
    }

    /**
     * Register custom widget area
     */
    public function register_widget_area()
    {
        register_sidebar(array(
            'id'            => 'rdf_page_content_top',
            'name'          => __('Content top', 'rohb'),
            'description'   => __('Area before single page contents', 'rohb'),
            'before_widget' => '<div class="widget-area-content-top">',
            'after_widget'  => '</div>',
            'before_title'  => '',
            'after_title'   => '',
        ));
    }

    /**
     * Shows the custom widget area before all single pages
     */
    public function show_widget_area($content)
    {
        if (is_singular(array('page')) &&
            is_active_sidebar('rdf_page_content_top') &&
            is_main_query()) {
            dynamic_sidebar('rdf_page_content_top');
        }
        return $content;
    }

    /**
     * Generates the url to widget.js
     */
    public function widget_script()
    {
        $client_code = $this->get_settings_value('client_code');

        if (!$client_code) {
            return false;
        }

        if (array_key_exists($this->current_locale(), $this->supported_languages)) {
            return sprintf('https://content.redforts.com/%s/%s/widget.js', $this->current_locale(), $client_code);
        } else {
            return sprintf('https://content.redforts.com/%s/widget.js', $client_code);
        }
    }

    /**
     * Enqueues widget scripts
     */
    public function enqueue_widget_scripts()
    {
        wp_enqueue_script('oscar-widget-script', $this->widget_script());
        wp_enqueue_style('oscar-widget-style', ROHB_URL . 'assets/css/widget.css');
        if ($this->get_settings_value('custom_css')) {
            wp_enqueue_style('oscar-widget-custom-style', ROHB_URL . 'custom.css');
        }
    }

    /**
     * Injects the css for the choosen options
     */
    public function generate_custom_css()
    {
        $client_code = $this->get_settings_value('client_code');

        if (!$client_code) {
            return false;
        }

        $css[] = '<style type="text/css">';

        if ($this->get_settings_value('button_color') || $this->get_settings_value('button_text_color')) {
            $css[] = '.rdf-booking-widget input[type="submit"] {';

            if ($this->get_settings_value('button_color')) {
                $css[] = 'background-color: ' . $this->settings['button_color'] . ';';
            }

            if ($this->get_settings_value('button_text_color')) {
                $css[] = 'color: ' . $this->settings['button_text_color'] . ';';
            }

            $css[] = '}';
        }

        if ($this->get_settings_value('button_hover_color') || $this->get_settings_value('button_hover_text_color')) {
            $css[] = '.rdf-booking-widget input[type="submit"]:hover {';

            if ($this->get_settings_value('button_hover_color')) {
                $css[] = sprintf('background-color: %s;', $this->get_settings_value('button_hover_color'));
            }

            if ($this->settings['button_hover_text_color']) {
                $css[] = sprintf('color: %s;', $this->get_settings_value('button_hover_text_color'));
            }

            $css[] = '}';
        }

        $css[] = '</style>';

        return implode("\n", $css);
    }

    /**
     * Enqueues the custom css if needed
     */
    public function enqueue_custom_css()
    {
        echo $this->generate_custom_css();
    }

    /**
     * Return current locale
     */
    public function current_locale()
    {
        return substr(get_locale(), 0, 2);
    }
}
