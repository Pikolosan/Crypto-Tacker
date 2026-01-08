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

    /**
     * Fetch Bitcoin Price with Caching (Transients)
     * Shortcode: [crypto_price]
     */
    public function get_crypto_price_shortcode( $atts ) {        
        // 1. Capture User Selection (from URL if form is submitted, otherwise defaults)
        // We check $_GET because the form uses method="GET"
        $selected_coin = isset( $_GET['pct_coin'] ) ? sanitize_text_field( $_GET['pct_coin'] ) : 'bitcoin';
        $selected_curr = isset( $_GET['pct_curr'] ) ? sanitize_text_field( $_GET['pct_curr'] ) : 'usd';

        // 2. Define Dropdown Options
        $coins = array(
            'bitcoin'  => 'Bitcoin',
            'ethereum' => 'Ethereum',
            'dogecoin' => 'Dogecoin',
            'ripple'   => 'XRP',
            'solana'   => 'Solana',
            'cardano'  => 'Cardano'
        );

        $currencies = array( 
            'usd' => 'USD ($)', 
            'eur' => 'EUR (€)', 
            'inr' => 'INR (₹)', 
            'gbp' => 'GBP (£)' 
        );

        // 3. Fetch Price Logic (Same caching engine as before)
        $cache_key = 'parth_crypto_' . $selected_coin . '_' . $selected_curr;
        $price     = get_transient( $cache_key );
        $display_price = '';

        if ( false === $price ) {
            $api_url = "https://api.coingecko.com/api/v3/simple/price?ids={$selected_coin}&vs_currencies={$selected_curr}";
            $response = wp_remote_get( $api_url );

            if ( is_wp_error( $response ) ) {
                $display_price = 'API Error';
            } else {
                $body = wp_remote_retrieve_body( $response );
                $data = json_decode( $body, true );

                if ( isset( $data[ $selected_coin ][ $selected_curr ] ) ) {
                    $price = $data[ $selected_coin ][ $selected_curr ];
                    set_transient( $cache_key, $price, 3600 ); // Cache for 1 hour
                    $display_price = number_format( $price, 2 );
                } else {
                    $display_price = 'N/A';
                }
            }
        } else {
            $display_price = number_format( $price, 2 );
        }

        // 4. Render the HTML Form (The UI)
        $output = '<div class="pct-wrapper" style="max-width: 350px; background: #fff; padding: 20px; border: 1px solid #e5e5e5; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); font-family: sans-serif;">';
        
        // Header
        $output .= '<h3 style="margin-top:0; color:#333;">Crypto Check</h3>';
        
        // Start Form
        $output .= '<form method="GET" action="">';
        
        // Coin Dropdown
        $output .= '<div style="margin-bottom: 15px;">';
        $output .= '<label style="font-weight:bold; font-size:12px; color:#555;">SELECT COIN</label><br>';
        $output .= '<select name="pct_coin" style="width:100%; padding:8px; margin-top:5px; border:1px solid #ccc; border-radius:4px;">';
        foreach ( $coins as $id => $name ) {
            $selected = ( $id === $selected_coin ) ? 'selected' : '';
            $output .= "<option value='{$id}' {$selected}>{$name}</option>";
        }
        $output .= '</select></div>';

        // Currency Dropdown
        $output .= '<div style="margin-bottom: 15px;">';
        $output .= '<label style="font-weight:bold; font-size:12px; color:#555;">CURRENCY</label><br>';
        $output .= '<select name="pct_curr" style="width:100%; padding:8px; margin-top:5px; border:1px solid #ccc; border-radius:4px;">';
        foreach ( $currencies as $id => $name ) {
            $selected = ( $id === $selected_curr ) ? 'selected' : '';
            $output .= "<option value='{$id}' {$selected}>{$name}</option>";
        }
        $output .= '</select></div>';

        // Submit Button
        $output .= '<button type="submit" style="width:100%; background:#2271b1; color:white; padding:10px; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">Update Price</button>';
        $output .= '</form>';

        // Result Display
        $output .= '<div style="margin-top: 20px; text-align: center; padding: 15px; background: #f0f6fc; border-radius: 6px; border: 1px solid #cce5ff;">';
        $output .= '<span style="display:block; font-size:12px; color:#555; text-transform:uppercase;">Current Price (' . strtoupper($selected_curr) . ')</span>';
        $output .= '<span style="display:block; font-size:24px; font-weight:bold; color:#2271b1;">' . $display_price . '</span>';
        $output .= '</div>';

        $output .= '</div>'; // End Wrapper

        return $output;
    }

}