<?php
/**
 * Template for the Carousel Block.
 *
 * @package Eightshift_Forms\Blocks.
 */

namespace Eightshift_Forms\Blocks;

$block_class    = $attributes['blockClass'] ?? '';
$block_js_class = $attributes['blockJsClass'] ?? '';
$is_free_mode   = $attributes['isFreeMode'] ?? false;
$is_loop        = $attributes['isLoop'] ?? true;

?>

<div
  class="<?php echo esc_attr( "{$block_class} {$block_js_class} swiper-container" ); ?>"
  data-swiper-freeMode="<?php echo esc_attr( $is_free_mode ); ?>"
  data-swiper-loop="<?php echo esc_attr( $is_loop ); ?>"
>
  <div class="<?php echo esc_attr( 'swiper-wrapper' ); ?>">
    <?php echo wp_kses_post( $inner_block_content ); ?>
  </div>
  <div class="<?php echo esc_attr( "{$block_class}__navigation" ); ?>">
    <?php
      $this->render_block_view(
        '/components/carousel-navigation/carousel-navigation.php',
        $attributes
      );
      ?>
  </div>
</div>
