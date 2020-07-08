<?php
/**
 * Template for the Video Component.
 *
 * @package Eightshift_Forms\Blocks.
 */

namespace Eightshift_Forms\Blocks;

$media = $attributes['media'] ?? [];

if ( empty( $media['url'] ) ) {
  return;
}

$block_class = $attributes['blockClass'] ?? '';

?>

<video
  src="<?php echo esc_url( $media['url'] ); ?>"
  class="<?php echo esc_attr( "video {$block_class}__video" ); ?>"
  autoplay
  loop
  muted
  playsinline
  preload="metadata">
</video>
