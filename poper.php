<?php
/**
 * Poper
 *
 * @package       POPER
 * @author        Latracal
 * @license       gplv2
 * @version       1.0.9
 *
 * @wordpress-plugin
 * Plugin Name:   Poper
 * Plugin URI:    https://www.poper.ai/
 * Description:   AI Driven Popup Builder that can convert visitors into customers, increase subscriber count, and skyrocket sales. Create engaging widgets & videos.
 * Version:       1.0.9
 * Author:        Latracal
 * Author URI:    https://www.latracal.com
 * Text Domain:   poper
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with Poper. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Include your custom code here.
// Define constants.
define( 'POPER_VERSION', '1.0.9' );
define( 'POPER_DEBUG', false );
define( 'POPER_URL', plugin_dir_url( __FILE__ ) );
define( 'POPER_PATH', plugin_dir_path( __FILE__ ) );

if ( ! function_exists( 'poper_get_path' ) ) {
	/**
	 * Get path helper function.
	 *
	 * @param string $arg Relative Path.
	 */
	function poper_get_path( $arg = '' ) {
		return POPER_PATH . $arg;
	}
}

require_once poper_get_path( 'icons.php' );

function poper_menu() {
    add_menu_page(
        'Poper Settings',
        'Poper',
        'manage_options',
        'poper-settings',
        'poper_settings_page',
        poper_get_svg(),
        32
    );
}

add_action('admin_menu', 'poper_menu');

function poper_add_admin_css() {
    wp_enqueue_style("poper-admin", POPER_URL . "style.css", array(), POPER_VERSION, "all");
    wp_enqueue_script("poper-admin", POPER_URL . "script.js", array('jquery'), POPER_VERSION, true);
}

add_action( 'admin_enqueue_scripts', 'poper_add_admin_css' );


// Step 4: Save account ID as an option
function poper_save_account_id() {
    if (isset($_POST['submit'])) {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        check_admin_referer('poper-settings');

        $account_id = isset($_POST['account_id']) ? sanitize_email(wp_unslash($_POST['account_id'])) : '';
        
        $old_account_id = get_option('poper_account_id');

        if($old_account_id !== $account_id){
            update_option('poper_account_id', $account_id);
            delete_option('poper_account_id_verified');

            if(!empty($account_id)){
                // Also update poper_account_id_md5 for security
                update_option('poper_account_id_md5', md5($account_id));
                update_option( 'poper-cache-notice', 1 );
            } else {
                // If account id is empty, remove the md5 option
                delete_option('poper_account_id_md5');
            }
        }

        // Redirect back to page
        wp_safe_redirect( esc_url( admin_url( 'admin.php?page=poper-settings' ) ) );
        
    }
}

add_action('admin_post_poper_save_account_id', 'poper_save_account_id');

// Step 5: Enqueue script if option is present
function poper_enqueue_script() {
    $account_id = get_option('poper_account_id_md5');

    if (!empty($account_id)) {
        ?>
<!-- Poper Code Start - poper.ai -->
<script 
  id="poper-js-script" 
  data-account-id="<?php echo esc_attr($account_id); ?>" 
  src="https://app.poper.ai/share/poper.js" 
  defer
></script>
<script>
    window.Poper = window.Poper || [];
    window.Poper.push({
        accountID: "<?php echo esc_attr($account_id); ?>",
    });
</script>
<!-- Poper Code End -->
<?php
    }
}
add_action('wp_head', 'poper_enqueue_script');



// Step 6: Display settings page
function poper_settings_page() {
    ?>
    <style>

#wpcontent{
    min-height: calc(100vh - 100px);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
#wpcontent .wrap{
    max-width: 600px;
    min-height: calc(100vh - 100px);
    display: flex;
    flex-direction: column;
    justify-content: center;

}
        .wrap .heading{
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .wrap .heading img{
            width: 30px;
            height: auto;
        }

        .email_inst{
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .poper-container{
            margin-top: 10px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .label{
            margin-bottom: 10px;
            font-weight: 600;
            display: block;
            font-size: 16px;
        }
        .submit-btn{
            display: inline-block;
            background-color: #f14a16;
            color: #fff;
            font-weight: 600;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition-duration: 0.3s;
            text-decoration: none;
        }
        .submit-btn:hover{
            background-color: #c3380d;
            color: #fff;
        }
        .submit-btn.white-btn{
            background-color: white;
            color: black;
        }
        .submit-btn.white-btn:hover{
            background-color: #f4f4f4;
            color: #000;
        }
        .input-field{
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .inner-heading{
            margin-top: 0px;
        }
        .inner-para{
            margin-top: 0px;
        }
        .submit-btn-container{
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .poper-container ul{
            list-style-position: outside;
            padding-left: 20px;
        }
        .poper-container ul li{
            list-style-type: disc;
        }
    </style>
    <div class="wrap">
        <h1 class="heading">
            <img src="<?php echo esc_url(POPER_URL . 'assets/images/poper.svg'); ?>" alt="Poper">
            <?php echo esc_html(get_admin_page_title()); ?>
        </h1>
        <form method="post" class="poper-container" action="admin-post.php">
            <?php wp_nonce_field('poper-settings'); ?>
            <label for="account_id" class="label">Enter your account email:</label>
            <ul>
                <li>This will be the email that you currently use in Poper, or the one you intend to use.</li>
            </ul>
            <input type="email" id="account_id" name="account_id" class="input-field" value="<?php echo esc_attr(get_option('poper_account_id')); ?>"><br>
            <input type="submit" class="submit-btn" name="submit" value="Save Account Email">
            <input type="hidden" name="action" value="poper_save_account_id">
            <?php
            if (get_option('poper_account_id') && get_option('poper_account_id_verified') !== "1") {
                    ?>
                        <h2 class="inner-heading" style="margin-top: 20px;">Login and verify your domain</h2>
                        <ul>
                            <li>Create a new account in Poper using the button below.</li>
                            <li>If you already have an account, login to your Poper account and verify your domain from the domains page.</li>
                        </ul>
                        <div class="submit-btn-container">
                            <a href="https://www.poper.ai?utm_source=wordpress&utm_medium=plugininstall" target="_blank" class="submit-btn white-btn" style="margin-bottom: 10px;">Login</a>
                            <a href="#" class="submit-btn verification-btn" style="margin-bottom: 10px;">Verify</a>
                        </div>
                        <div style="text-align: center; margin-top: 10px;">
                            <a href="mailto:hello@poper.ai" style="margin-bottom: 10px;">Need help?</a>
                        </div>
                    <?php
                }
                ?>
                        <?php
        if (get_option('poper_account_id') && get_option('poper_account_id_verified') === "1") {
            ?>
                <h2 class="inner-heading" style="margin-top: 20px;">✅ Your account is connected!</h2>
                <p class="inner-para">Now you can start creating pop-ups on your website using Poper.
                </p>
                <a href="https://app.poper.ai" target="_blank" class="submit-btn">Manage Popups</a>
                <div style="text-align: center; margin-top: 10px;">
                            <a href="mailto:hello@poper.ai" style="margin-bottom: 10px;">Need help?</a>
                        </div>
            <?php
        }
        ?>
        </form>
    <?php
}

// Poper Account ID Verification
function poper_mark_domain_verified() {
	update_option( 'poper_account_id_verified', "1" );
}

add_action( 'wp_ajax_poper_mark_domain_verified', 'poper_mark_domain_verified' );



require_once poper_get_path( 'notices/review.php' );
require_once poper_get_path( 'notices/cache.php' );

// Set transient for 1 day after installing the plugin
add_action( 'activated_plugin', 'poper_set_transient_on_activation' );

function poper_set_transient_on_activation( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        set_transient( 'poper_dismiss_notice_temporary', 1, 14 * 24 * 60 * 60 );
    }
}

function add_settings_link($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=poper-settings') . '">Settings</a>';
    array_push($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'add_settings_link');
