<?php

/**
 * Redforts Oscar Hotel Booking settings class
 * Administrative contents
 *
 * @author Redforts Software S.L.
 * @copyright Copyright (c) 2015 Redforts Software S.L.
 * @link https://redforts.com
 * @since 0.1.1
 */

class ROHB_Admin {

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
        // Load current settings
        $this->settings = get_option('rohb');

        // Add Redforts admin menu
        add_action('admin_menu', array($this, 'register_admin_menu'));

        // Register options
        add_action('admin_init', array($this, 'register_settings'));

        // Enqueue scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Adds custom css
        add_action('admin_head', array($this, 'enqueue_custom_css'));

        //print_r($GLOBALS);
    }

    /**
     * Adds the settings page to the wp admin menu
     */
    public function register_admin_menu()
    {
        $p = add_menu_page(
            __('Redforts Oscar Hotel Booking options', 'rohb'),
            'Redforts',
            'manage_options',
            'reforts-oscar-hotel-booking-settings',
            array($this, 'admin_page'),
            ROHB_URL . '/assets/images/redforts-icon.png',
            '90'
        );

        add_action('load-' . $p, array($this, 'add_help_tabs'));
    }

    /**
     * Display the options page for the plugin
     */
    public function admin_page()
    {
        echo '<div class="wrap">';
        echo '<img src="' . ROHB_URL . 'assets/images/redforts-logo.png">';
        printf('<h1>%s</h1>', __('Redforts Oscar Hotel Booking', 'rohb'));

        if (isset($_GET['settings-updated'])) {
            if ($e = get_settings_errors()) {
                foreach ($e as $i) {
                    if ($i['type'] == 'error') {
                        echo '<div id="rohb-feedback" class="updated settings-error error is-dismissible">';
                    } else {
                        echo '<div id="rohb-feedback" class="updated settings-error notice is-dismissible">';
                    }

                    printf('<p><strong>%s</strong></p>', $i['message']);
                    echo '</div>';
                }
            }
        }

        echo '<form method="post" action="options.php">';
        settings_fields('redforts-oscar-hotel-booking-settings');
        do_settings_sections('redforts-oscar-hotel-booking-settings');
        submit_button();
        echo '</form>';
        echo '</div>';
    }

    /**
     * Register plugin settings
     */
    public function register_settings()
    {
        // Register settings array name
        register_setting(
            'redforts-oscar-hotel-booking-settings',
            'rohb',
            array($this, 'sanitize_input')
        );

        // Settings section: Main section
        add_settings_section(
            'main-settings',
            __('Main settings', 'rohb'),
            array($this, 'main_settings_callback'),
            'redforts-oscar-hotel-booking-settings'
        );

        // client_code
        add_settings_field(
            'client_code',
            __('Client code', 'rohb'),
            array($this, 'client_code_callbak'),
            'redforts-oscar-hotel-booking-settings',
            'main-settings'
        );

        // button_color
        add_settings_field(
            'button_color',
            __('Button color', 'rohb'),
            array($this, 'button_color_callbak'),
            'redforts-oscar-hotel-booking-settings',
            'main-settings'
        );

        // button_hover_color
        add_settings_field(
            'button_hover_color',
            __('Focused button color', 'rohb'),
            array($this, 'button_hover_color_callback'),
            'redforts-oscar-hotel-booking-settings',
            'main-settings'
        );

        // button_text_color
        add_settings_field(
            'button_text_color',
            __('Button text color', 'rohb'),
            array($this, 'button_text_color_callback'),
            'redforts-oscar-hotel-booking-settings',
            'main-settings'
        );

        // button_hover_text_color
        add_settings_field(
            'button_hover_text_color',
            __('Focused button text color', 'rohb'),
            array($this, 'button_hover_text_color_callback'),
            'redforts-oscar-hotel-booking-settings',
            'main-settings'
        );

        // open booking engine in new window/tab
        add_settings_field(
            'target_blank',
            __('Open in new tab/window', 'rohb'),
            array($this, 'target_blank_callbak'),
            'redforts-oscar-hotel-booking-settings',
            'main-settings'
        );

        // custom css
        add_settings_field(
            'custom_css',
            __('Custom css', 'rohb'),
            array($this, 'custom_css_callback'),
            'redforts-oscar-hotel-booking-settings',
            'main-settings'
        );
    }

    /**
     * Sanitizes user input for options
     */
    public function sanitize_input($input)
    {
        $valid_input = array();

        // Sanitize client_code
        $client_code = trim($input['client_code']);

        if (preg_match('/^[0-9a-zA-Z]{8,}+$/', $client_code)) {
            $valid_input['client_code'] = strip_tags(stripslashes($client_code));
        } elseif (empty($client_code)) {
            $valid_input['client_code'] = '';
        } else {
            add_settings_error('redforts-oscar-hotel-booking-settings', 'rohb-client-code-error', __('Not a correct client code', 'rohb'), 'error');
            $valid_input['client_code'] = $this->settings['client_code'];
        }

        // target_blank option
        if (isset($input['target_blank'])) {
            $valid_input['target_blank'] = 1;
        } else {
            $valid_input['target_blank'] = 0;
        }

        // Sanitize colors
        $color_inputs = array('button_color', 'button_text_color', 'button_hover_color', 'button_hover_text_color');

        foreach ($color_inputs as $f) {
            $val = trim($input[$f]);
            $val = strip_tags(stripslashes($val));

            if (false === $this->validate_color($val)) {
                add_settings_error('redforts-oscar-hotel-bookoing-settings', 'rohb-error', __('Insert a valid color', 'rohb'), 'error');
                $valid_input[$f] = $this->settings[$f];
            } else {
                $valid_input[$f] = $val;
            }
        }

        // custom css
        $custom_css = trim($input['custom_css']);
        $valid_input['custom_css'] = $custom_css;

        return apply_filters('sanitize_options', $valid_input, $input);
    }

    /**
     * Check if value is a valid HEX color.
     */
    public function validate_color($value)
    {
        if (preg_match('/^#[a-f0-9]{6}$/i', $value) || empty($value)) {
            return true;
        }

        return false;
    }

    /**
     * Options page initial section
     */
    public function main_settings_callback()
    {
        // Initial section display
        printf('<p>%s</p>', __('Make sure to visit the Help tab to learn how to use the plugin', 'rohb'));
    }

    /**
     * Displays client_code field in settings page
     */
    public function client_code_callbak()
    {
        $value = $this->get_settings_value('client_code');

        printf('<input type="text" class="text" id="rohb[client_code]" name="rohb[client_code]" value="%s">', $value);
        printf('<p class="description">%s</p>', __('Your client code in Oscar', 'rohb'));
    }

    /**
     * Displays checkbox option Open in new tab
     */
    public function target_blank_callbak()
    {
        $value = $this->get_settings_value('target_blank');
        if ($value) {
            printf('<input type="checkbox" class="checkbox" id="rohb[target_blank]" name="rohb[target_blank]" checked="checked">');
        } else {
            printf('<input type="checkbox" class="checkbox" id="rohb[target_blank]" name="rohb[target_blank]">');
        }
        printf('<p class="description">%s</p>', __('Check this if you want the booking engine to open in a new tab or window', 'rohb'));
    }

    /**
     * Displays button_color field in settings page
     */
    public function button_color_callbak()
    {
        $value = $this->get_settings_value('button_color');

        printf('<input type="text" id="rohb[button_color]" name="rohb[button_color]" class="color-picker" value="%s">', $value);
        printf('<p class="description">%s</p>', __('Choose the color of the button', 'rohb'));
    }

    /**
     * Displays button_hover_color field in settings page
     */
    public function button_hover_color_callback()
    {
        $value = $this->get_settings_value('button_hover_color');

        printf('<input type="text" id="rohb[button_hover_color]" name="rohb[button_hover_color]" class="color-picker" value="%s">', $value);
        printf('<p class="description">%s</p>', __('Choose the color for the button when gets focused or pressed', 'rohb'));
    }

    /**
     * Displays button_text_color field in settings page
     */
    public function button_text_color_callback()
    {
        $value = $this->get_settings_value('button_text_color');

        printf('<input type="text" id="rohb[button_text_color]" name="rohb[button_text_color]" class="color-picker" value="%s">', $value);
        printf('<p class="description">%s</p>', __('Choose the color for the button text', 'rohb'));
    }

    /**
     * Displays button_hover_text_color field in settings page
     */
    public function button_hover_text_color_callback()
    {
        $value = $this->get_settings_value('button_hover_text_color');

        printf('<input type="text" id="rohb[button_hover_text_color]" name="rohb[button_hover_text_color]" class="color-picker" value="%s">', $value);
        printf('<p class="description">%s</p>', __('Choose the color for the button text', 'rohb'));
    }

    /**
     * Displays button_hover_text_color field in settings page
     */
    public function custom_css_callback()
    {
        $value = $this->get_settings_value('custom_css');
        if ($value) {
            printf('<input type="checkbox" class="checkbox" id="rohb[custom_css]" name="rohb[custom_css]" checked="checked">');
        } else {
            printf('<input type="checkbox" class="checkbox" id="rohb[custom_css]" name="rohb[custom_css]">');
        }
        printf('<p class="description">%s</p>', __('Enable custom css for widgets (advanced settings)', 'rohb'));
    }

    /**
     * Returns the value of an option if exists or empty
     */
    public function get_settings_value($field)
    {
        return (isset($this->settings[$field])) ? esc_attr($this->settings[$field]) : '';
    }

    /**
     * Enqueues admin scripts
     */
    public function enqueue_admin_scripts()
    {
        // css rules for Color Picker
        wp_enqueue_style('wp-color-picker');
        // the widget css
        wp_enqueue_style('oscar-widget-style', ROHB_URL . 'assets/css/widget.css');
        //wp_enqueue_script('wp-color-picker');
        wp_enqueue_script(
            'rohb-custom-js',
            plugins_url('assets/js/jquery.custom.js', ROHB_BASE),
            array('jquery', 'wp-color-picker'),
            '',
            true
        );
    }

    /**
     * Help tabs
     */
    public function add_help_tabs()
    {
        $screen = get_current_screen();

        $screen->add_help_tab(array(
            'id' => 'main_help',
            'title' => __('Overview', 'rohb'),
            'content' => '',
            'callback' => array($this, 'main_help_callback')
        ));

        $screen->add_help_tab(array(
            'id' => 'shortcode_help',
            'title' => __('Shortcodes', 'rohb'),
            'content' => '',
            'callback' => array($this, 'shortcode_help_callback')
        ));

        $screen->add_help_tab(array(
            'id' => 'widget_help',
            'title' => __('Widgets', 'rohb'),
            'content' => '',
            'callback' => array($this, 'widget_help_callback')
        ));


        $screen->set_help_sidebar('<p><strong>'.
                                  __('More info', 'rohb') . '</strong></p>' .
                                  '<p><a href="https://oscar.redforts.com/setup,be,integration,wordpress" target="_blank">' .
                                  __('Oscar & Wordpress', 'rohb') . '</a></p>');
    }

    /**
     * Enqueues the custom css if needed
     */
    public function enqueue_custom_css()
    {
        echo $this->generate_custom_css();
    }

    /**
     * Injects the css for the chosen options
     */
    public function generate_custom_css()
    {
        $client_code = $this->get_settings_value('client_code');
        $custom_css = $this->get_settings_value('custom_css');

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

        if ($custom_css) {
            $css[] = $custom_css;
        }

        $css[] = 'pre { font-weight: 600; text-align: center; }';

        $css[] = '</style>';

        return implode("\n", $css);
    }

    /**
     * Show help
     */
    public function help_callback($section)
    {
        $lang = $this->current_locale();
        $help_file = sprintf('includes/static/%s-help-%s.php', $section, $lang);
        $default_file = sprintf('includes/static/%s-help-en.php', $section);

        if (file_exists(ROHB_PATH . $help_file)) {
            include ROHB_PATH . $help_file;
        } else {
            include ROHB_PATH . $default_file;
        }
    }

    public function main_help_callback()
    {
        $this->help_callback('main');
        return true;
    }

    public function shortcode_help_callback()
    {
        $this->help_callback('shortcode');
        return true;
    }

    public function widget_help_callback()
    {
        $this->help_callback('widget');
        return true;
    }

    /**
     * Return current locale
     */
    public function current_locale()
    {
        return substr(get_locale(), 0, 2);
    }
}