<?php
/**
 * Template for the Wrapping Advance block.
 *
 * @package Eightshift_Forms\Blocks.
 */

namespace Eightshift_Forms\Blocks;

use Eightshift_Libs\Helpers\Components;

// Used to add or remove wrapper.
$has_wrapper = $attributes['hasWrapper'] ?? true;

if ( $has_wrapper ) {

  $id = $attributes['id'] ?? '';

  $wrapper_main_class = 'wrapper';

  $wrapper_class = Components::classnames([
    $wrapper_main_class,
    isset( $attributes['styleBackgroundColor'] ) && ! empty( $attributes['styleBackgroundColor'] ) ? "{$wrapper_main_class}__bg-color--{$attributes['styleBackgroundColor']}" : '',
    $attributes['styleSpacingTop'] ? Components::responsive_selectors($attributes['styleSpacingTop'], 'spacing-top', $wrapper_main_class) : '',
    $attributes['styleSpacingBottom'] ? Components::responsive_selectors($attributes['styleSpacingBottom'], 'spacing-bottom', $wrapper_main_class) : '',
    $attributes['styleHideBlock'] ? Components::responsive_selectors($attributes['styleHideBlock'], 'hide-block', $wrapper_main_class, true) : '',
  ]);

  $wrapper_container_class = Components::classnames([
    "{$wrapper_main_class}__container",
    $attributes['styleContainerWidth'] ? Components::responsive_selectors($attributes['styleContainerWidth'], 'container-width', $wrapper_main_class) : '',
    $attributes['styleContainerSpacing'] ? Components::responsive_selectors($attributes['styleContainerSpacing'], 'container-spacing', $wrapper_main_class) : '',
  ]);

  $wrapper_inner_class = Components::classnames([
    "{$wrapper_main_class}__inner",
    $attributes['styleContentWidth'] ? Components::responsive_selectors($attributes['styleContentWidth'], 'inner-content-width', $wrapper_main_class) : '',
    $attributes['styleContentOffset'] ? Components::responsive_selectors($attributes['styleContentOffset'], 'inner-offset', $wrapper_main_class) : '',
  ]);

  ?>
  <div class="<?php echo esc_attr( $wrapper_class ); ?>" <?php ! empty( $id ) ? printf('id="%s"', esc_attr( $id ) ): '' ?>>
    <div class="<?php echo esc_attr( $wrapper_container_class ); ?>">
      <div class="<?php echo esc_attr( $wrapper_inner_class ); ?>">
        <?php
          $this->render_wrapper_view(
            $template_path,
            $attributes,
            $inner_block_content
          );
        ?>
      </div>
    </div>
  </div>
  <?php
} else {
  $this->render_wrapper_view(
    $template_path,
    $attributes,
    $inner_block_content
  );
}
