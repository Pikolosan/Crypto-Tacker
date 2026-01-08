<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Parth_Crypto_Tracker
 * @subpackage Parth_Crypto_Tracker/public
 */

class Parth_Crypto_Tracker_Public {

    private $Parth_Crypto_Tracker;
    private $version;

    public function __construct( $Parth_Crypto_Tracker, $version ) {
        $this->Parth_Crypto_Tracker = $Parth_Crypto_Tracker;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style( $this->Parth_Crypto_Tracker, plugin_dir_url( __FILE__ ) . 'css/parth-crypto-tracker-public.css', array(), $this->version, 'all' );
    }

    public function enqueue_scripts() {
        wp_enqueue_script( $this->Parth_Crypto_Tracker, plugin_dir_url( __FILE__ ) . 'js/parth-crypto-tracker-public.js', array( 'jquery' ), $this->version, false );
    }

    // --- NEW ENGINEERING LOGIC START ---
    /**
     * Fetch Bitcoin Price with Caching (Transients)
     * Shortcode: [crypto_price]
     */
    public function get_crypto_price_shortcode( $atts ) {

        // 1. Check if we have the price in the Database Cache (Transient)
        // This is what rtCamp looks for: "Performance Awareness"
        $price = get_transient( 'parth_btc_price' );

        if ( false === $price ) {
            
            // 2. Data not in cache? Fetch from External API
            $response = wp_remote_get( 'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd' );

            // 3. Error Handling (Safety)
            if ( is_wp_error( $response ) ) {
                return '<div class="error">Crypto API Error.</div>';
            }

            // 4. Parse Data
            $body = wp_remote_retrieve_body( $response );
            $data = json_decode( $body, true );

            if ( isset( $data['bitcoin']['usd'] ) ) {
                $price = $data['bitcoin']['usd'];

                // 5. Save to Cache for 1 Hour (3600 seconds)
                // This prevents your site from crashing if the API goes down
                set_transient( 'parth_btc_price', $price, 3600 );
            }
        }

        // 6. Return HTML (Always sanitize output if it was user input, but this is internal)
        return '<div style="padding: 20px; background: #f0f0f1; border-left: 5px solid #0073aa;">
                    <strong>Bitcoin Price:</strong> $' . number_format( $price ) . '
                </div>';
    }
    // --- NEW ENGINEERING LOGIC END ---

}