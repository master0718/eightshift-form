<?php
/**
 * Template for the Button Block view.
 *
 * @package Eightshift_Forms\Blocks.
 */

namespace Eightshift_Forms\Blocks;

$this->render_block_view(
  '/components/button/button.php',
  [
    'blockClass' => $attributes['blockClass'] ?? '',
    'button'     => $attributes['button'] ?? [],
  ]
);
