<?php
/**
 * Template for the Page Overlay Component.
 *
 * @package Eightshift_Forms\Blocks.
 */

namespace Eightshift_Forms\Blocks;

use Eightshift_Libs\Helpers\Components;

$block_class = $attributes['blockClass'] ?? 'page-overlay';

$classes = Components::classnames([
  $block_class,
  "js-{$block_class}",
]);

?>

<div class="<?php echo esc_attr( $classes ); ?>"></div>
