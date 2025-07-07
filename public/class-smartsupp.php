<?php
/**
 * Smartsupp Live Chat
 *
 * @package   Smartsupp
 * @author    Smartsupp <vladimir@smartsupp.com>
 * Version:   3.2
 * @copyright 2014 smartsupp.com
 * @license   GPL-2.0+
 * @link      http://www.smartsupp.com
 */

use Smartsupp\ChatGenerator;


require_once __DIR__ . '/../vendor/autoload.php';

class Smartsupp {


	/**
	 * Plugin version
	 *
	 * @since   0.1.0
	 * @var     string
	 */
	const VERSION = '3.6';

	/**
	 * Plugin slug
	 *
	 * @since    0.1.0
	 * @var      string
	 */
	protected $plugin_slug = 'smartsupp';

	/**
	 * Plugin text domain
	 *
	 * @var      string
	 */
	protected $plugin_text_domain = 'smartsupp-live-chat';

	/**
	 * Instance of this class.
	 *
	 * @since    0.1.0
	 * @var      object
	 */
	protected static $instance = null;


	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.1.0
	 */
	private function __construct() {
		/**
		 * Load plugin text domain for translations.
		 *
		 * @since 0.1.0
		 */
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		/**
		 * Add chat script to WordPress footer.
		 *
		 * @since 0.1.0
		 */
		add_action( 'wp_footer', array( $this, 'add_chat_script' ) );
	}


	/**
	 * Return the plugin slug.
	 *
	 * @since    0.1.0
	 *
	 * @return    string Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}


	/**
	 * Return an instance of this class.
	 *
	 * @since     0.1.0
	 *
	 * @return    $this    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since     0.1.0
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_text_domain;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, basename( plugin_dir_path( dirname(__FILE__) ) ) . '/languages/' );
	}


	public static function is_woocommerce_active() {
		return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
	}


	/**
	 * Insert chat script to WP footer
	 *
	 * @since     0.1.0
	 */
	public function add_chat_script() {
		global $wpdb;

		$smartsupp = get_option( 'smartsupp' );

		if ( $smartsupp['active'] != '1' ) {
			return;
		}

		$chat_id = esc_attr( $smartsupp['chat-id'] );

		if ( $chat_id == '' || ! is_string( $chat_id ) ) {
			return;
		}

		// set key
		$code = new ChatGenerator( $chat_id );

		// set cookie domain
		$code->setCookieDomain( parse_url( get_site_url(), PHP_URL_HOST ) );

		$dashboardName = '';
		$userEmail     = '';

		if ( is_user_logged_in() ) {
			$user          = wp_get_current_user();
			$dashboardName = $user->display_name . ' (' . $user->ID . ')';

			$code->setVariable( 'userName', __( 'Username', 'smartsupp-live-chat' ), $user->user_login );
			$code->setVariable( 'email', __( 'Email', 'smartsupp-live-chat' ), $userEmail = $user->user_email );
			$code->setVariable( 'role', __( 'Role', 'smartsupp-live-chat' ), implode( ' ,', $user->roles ) );
			$code->setVariable( 'name', __( 'Name', 'smartsupp-live-chat' ), $user->first_name . ' ' . $user->last_name );

			if ( self::is_woocommerce_active() ) {
				$this->addBillingLocation( $code, $user );
				$this->addSpent( $code, $user );
				$this->addOrder( $code, $user );
			}
		}

		$code->setName( $dashboardName );
		$code->setEmail( $userEmail );
		$code->setPlatform( 'WP ' . get_bloginfo( 'version' ) );

		$code->render( true );

		if ( ! empty( $smartsupp['optional-code'] ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<script>' . wp_unslash( $smartsupp['optional-code'] ) . '</script>';
		}
	}


	private function addBillingLocation( ChatGenerator $code, $user ) {
		$countryCode = get_user_meta( $user->ID, 'billing_country', true );
		$city        = get_user_meta( $user->ID, 'billing_city', true );
		if ( $city || $countryCode ) {
			$location = $city . ', ' . $countryCode;
		} else {
			$location = '-';
		}

		$code->setVariable( 'billingLocation', __( 'Billing Location', 'smartsupp-live-chat' ), $location );
	}


	private function addSpent( ChatGenerator $code, $user ) {
		global $wpdb;
		if ( ! get_user_meta( $user->ID, '_money_spent', true ) ) {
			$spent = $wpdb->get_var(
				$wpdb->prepare(
					"
                SELECT COALESCE( SUM( CAST(meta2.meta_value AS DECIMAL(14,2)) ), 0 )
                FROM   {$wpdb->posts}         AS posts
                INNER  JOIN {$wpdb->postmeta} AS meta
                  ON   posts.ID = meta.post_id
                INNER  JOIN {$wpdb->postmeta} AS meta2
                  ON   posts.ID = meta2.post_id
                WHERE  meta.meta_key     = %s
                  AND  meta.meta_value   = %d
                  AND  posts.post_type    = %s
                  AND  posts.post_status  = %s
                  AND  meta2.meta_key     = %s
                ",
					array(
						'_customer_user',
						$user->ID,
						'shop_order',
						'wc-completed',
						'_order_total',
					)
				)
			);

			update_user_meta( $user->ID, '_money_spent', $spent );
		}

		$spent = get_user_meta( $user->ID, '_money_spent', true );

		if ( ! $spent ) {
			$spent = 0;
		}

		$formattedSpent = sprintf( get_woocommerce_price_format(), html_entity_decode( get_woocommerce_currency_symbol(), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, ), $spent );

		$code->setVariable( 'spent', __( 'Spent', 'smartsupp-live-chat' ), $formattedSpent );
	}


	private function addOrder( ChatGenerator $code, $user ) {
		global $wpdb;
		if ( ! get_user_meta( $user->ID, '_order_count', true ) ) {
			$count = $wpdb->get_var(
				$wpdb->prepare(
					"
                SELECT COUNT( * )
                FROM {$wpdb->posts}         AS posts
                LEFT JOIN {$wpdb->postmeta} AS meta
                  ON posts.ID = meta.post_id
                WHERE meta.meta_key    = %s
                  AND posts.post_type  = %s
                  AND posts.post_status = %s
                  AND meta.meta_value  = %d
                ",
					array(
						'_customer_user',
						'shop_order',
						'wc-completed',
						$user->ID,
					)
				)
			);

			update_user_meta( $user->ID, '_order_count', $count );
		}

		$count = absint( get_user_meta( $user->ID, '_order_count', true ) );
		$code->setVariable( 'order', __( 'Orders', 'smartsupp-live-chat' ), $count );
	}
}
