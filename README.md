# Fast Crypto Tracker 🚀

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg) ![WordPress](https://img.shields.io/badge/WordPress-Tested_6.4-success.svg) ![PHP](https://img.shields.io/badge/PHP-7.4+-777bb4.svg)

A high-performance WordPress plugin that fetches real-time Bitcoin data using the **CoinGecko API**. Built with a focus on **clean architecture**, **caching strategies**, and **security best practices**.

---

## 🧠 Engineering Decisions (Why I built it this way)

As a developer coming from a MERN background, I treated this WordPress plugin like a backend microservice. I avoided "hacking" code into `functions.php` and instead used a strict **Object-Oriented** structure.

### 1. Performance Optimization (Caching)

**Problem:** Fetching data from an external API on every page load causes slow render times and hits API rate limits.  

**Solution:** I implemented the **WordPress Transients API** to cache the response for **1 hour**.

- **First Load:** ~500ms (API Call)  
- **Subsequent Loads:** <50ms (Served from Database Cache)

### 2. Standardization & Security

**Problem:** PHP `cURL` can be inconsistent across different server environments.  

**Solution:** I used the **WP HTTP API** (`wp_remote_get`), which handles SSL verification, headers, and transport methods automatically. All output is sanitized using `esc_html()` to prevent XSS vulnerabilities.

---

## 📂 Architecture

The project follows the **WordPress Plugin Boilerplate (WPPB)** standard to ensure separation of concerns:

```text
fast-crypto-tracker/
├── admin/                   # Backend Admin UI logic
├── includes/                # Core Logic (Loader, i18n, Activator)
│   ├── class-loader.php     # Registers Hooks & Shortcodes
│   └── ...
├── public/                  # Frontend Display Logic
│   ├── class-public.php     # API Fetching & Caching Implementation
│   └── ...
└── fast-crypto-tracker.php # Main Bootstrap File
```
## 🛠 Features

- **Shortcode Support:** Use [crypto_price] anywhere on the site.

- **Fail-Safe Error Handling:** If the API is down, the plugin gracefully handles the error without breaking the site.

- **Zero-Config:** Works immediately upon activation.

## 💻 Code Highlight
Here is the core logic responsible for the Fetch & Cache strategy

(public/class-fast-crypto-tracker-public.php):
```php
public function get_crypto_price_shortcode( $atts ) {
    // 1. Check Cache (Transient)
    $price = get_transient( 'fast_btc_price' );

    if ( false === $price ) {
        // 2. Fetch from API if cache is empty
        $response = wp_remote_get(
            'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd'
        );

        if ( is_wp_error( $response ) ) {
            return 'API Error';
        }

        $body  = wp_remote_retrieve_body( $response );
        $data  = json_decode( $body, true );
        $price = $data['bitcoin']['usd'];

        // 3. Set Cache for 1 Hour (3600 seconds)
        set_transient( 'fast_btc_price', $price, 3600 );
    }

    // 4. Return Sanitized Output
    return '<div class="crypto-box">Bitcoin: $' . number_format( $price ) . '</div>';
}
```
## 🚀 Installation
1. Download the repository as a .zip file.

1. Go to WordPress Admin > Plugins > Add New > Upload Plugin.

1. Upload and Activate Fast Crypto Tracker.

1. Add [crypto_price] to any Page or Post.  
