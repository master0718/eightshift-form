<?php
/**
 * Plugin Name: Eightshift forms
 * Plugin URI:
 * Description: Eightshift form builder plugin.
 * Author: Team Eightshift
 * Author URI: https://eightshift.com/
 * Version: 1.0.0
 * Text Domain: eightshift-forms
 *
 * @package Eightshift_Forms
 *
 * @since 1.0.0
 */

declare( strict_types=1 );

namespace Eightshift_Forms;

/**
 * If this file is called directly, abort.
 *
 * @since 1.0.0
 */
if ( ! \defined( 'WPINC' ) ) {
  die;
}

/**
 * Include the autoloader so we can dynamically include the rest of the classes.
 *
 * @since 1.0.0
 */
require __DIR__ . '/vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 *
 * @since 1.0.0
 */
register_activation_hook(
  __FILE__,
  function() {
    ( new Core\Activate() )->activate();
  }
);

/**
 * The code that runs during plugin deactivation.
 *
 * @since 1.0.0
 */
register_deactivation_hook(
  __FILE__,
  function() {
    ( new Core\Deactivate() )->deactivate();
  }
);

/**
 * Begins execution of the theme.
 *
 * Since everything within the theme is registered via hooks,
 * then kicking off the theme from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
( new Core\Main() )->register();
