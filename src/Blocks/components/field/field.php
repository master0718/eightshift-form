<?php

/**
 * Template for the Field Component.
 *
 * @package EightshiftForms
 */

use EightshiftForms\Helpers\Components;
use EightshiftForms\Hooks\Filters;

$globalManifest = Components::getManifest(dirname(__DIR__, 2));
$manifest = Components::getManifest(__DIR__);

$fieldUse = Components::checkAttr('fieldUse', $attributes, $manifest);
if (!$fieldUse) {
	return;
}

$componentClass = $manifest['componentClass'] ?? '';
$additionalFieldClass = $attributes['additionalFieldClass'] ?? '';
$selectorClass = $attributes['selectorClass'] ?? $componentClass;
$blockJsClass = $attributes['blockJsClass'] ?? '';
$componentJsClass = $manifest['componentJsClass'] ?? '';

// Update media breakpoints from the filter.
$customMediaBreakpoints = apply_filters(Filters::getBlocksFilterName('breakpoints'), []);
if (
	has_filter(Filters::getBlocksFilterName('breakpoints')) &&
	is_array($customMediaBreakpoints) &&
	isset($customMediaBreakpoints['mobile']) &&
	isset($customMediaBreakpoints['tablet']) &&
	isset($customMediaBreakpoints['desktop']) &&
	isset($customMediaBreakpoints['large'])
) {
	$globalManifest['globalVariables']['breakpoints'] = $customMediaBreakpoints;
}

$unique = Components::getUnique();

$fieldLabel = Components::checkAttr('fieldLabel', $attributes, $manifest);
$fieldHideLabel = Components::checkAttr('fieldHideLabel', $attributes, $manifest);
$fieldId = Components::checkAttr('fieldId', $attributes, $manifest);
$fieldName = Components::checkAttr('fieldName', $attributes, $manifest);
$fieldContent = Components::checkAttr('fieldContent', $attributes, $manifest);
$fieldBeforeContent = Components::checkAttr('fieldBeforeContent', $attributes, $manifest);
$fieldAfterContent = Components::checkAttr('fieldAfterContent', $attributes, $manifest);
$fieldType = Components::checkAttr('fieldType', $attributes, $manifest);
$fieldUseError = Components::checkAttr('fieldUseError', $attributes, $manifest);
$fieldHelp = Components::checkAttr('fieldHelp', $attributes, $manifest);
$fieldDisabled = Components::checkAttr('fieldDisabled', $attributes, $manifest);
$fieldStyle = Components::checkAttr('fieldStyle', $attributes, $manifest);
$fieldUniqueId = Components::checkAttr('fieldUniqueId', $attributes, $manifest);
$fieldAttrs = Components::checkAttr('fieldAttrs', $attributes, $manifest);

$fieldClass = Components::classnames([
	Components::selector($componentClass, $componentClass),
	Components::selector($componentClass, $componentClass, '', $selectorClass),
	Components::selector($additionalFieldClass, $additionalFieldClass),
	Components::selector($fieldDisabled, $componentClass, '', 'disabled'),
	Components::selector($blockJsClass, $blockJsClass),
	Components::selector($componentJsClass, $componentJsClass),
	Components::selector($fieldStyle && $componentClass, $componentClass, '', $fieldStyle),
]);

$fieldTag = 'div';
$labelTag = 'label';

if ($fieldType === 'fieldset') {
	$fieldTag = 'fieldset';
	$labelTag = 'legend';
}

$fieldAttrsOutput = '';
if ($fieldAttrs) {
	foreach ($fieldAttrs as $key => $value) {
		$fieldAttrsOutput .= \wp_kses_post(" {$key}='" . $value . "'");
	}
}

// Additional content filter.
$additionalContent = '';
$filterName = Filters::getBlockFilterName('field', 'additionalContent');
if (has_filter($filterName)) {
	$additionalContent = apply_filters($filterName, $attributes ?? []);
}

?>

<<?php echo esc_attr($fieldTag); ?>
	class="<?php echo esc_attr($fieldClass); ?>"
	data-id="<?php echo esc_attr($unique); ?>"
	<?php echo $fieldAttrsOutput; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
>

	<?php
	if ($fieldUniqueId) {
		echo Components::outputCssVariables($attributes, $manifest, $fieldUniqueId, $globalManifest, 'wp-block'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		echo Components::outputCssVariables($attributes, $manifest, $unique, $globalManifest); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>
	<div class="<?php echo esc_attr("{$componentClass}__inner"); ?>">
		<?php if ($fieldLabel && !$fieldHideLabel) { ?>
			<<?php echo esc_attr($labelTag); ?>
				class="<?php echo esc_attr("{$componentClass}__label"); ?>"
				for="<?php echo esc_attr($fieldId); ?>"
			>
				<span class="<?php echo esc_attr("{$componentClass}__label-inner"); ?>">
					<?php echo esc_html($fieldLabel); ?>
				</span>
			</<?php echo esc_attr($labelTag); ?>>
		<?php } ?>
		<div class="<?php echo esc_attr("{$componentClass}__content"); ?>">
			<?php if ($fieldBeforeContent) { ?>
				<div class="<?php echo esc_attr("{$componentClass}__before-content"); ?>">
					<?php echo $fieldBeforeContent; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php } ?>
			<div class="<?php echo esc_attr("{$componentClass}__content-wrap"); ?>">
				<?php echo $fieldContent; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<?php if ($fieldAfterContent) { ?>
				<div class="<?php echo esc_attr("{$componentClass}__after-content"); ?>">
					<?php echo $fieldAfterContent; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php } ?>
		</div>
		<?php if ($fieldHelp) { ?>
			<div class="<?php echo esc_attr("{$componentClass}__help"); ?>">
				<?php echo $fieldHelp; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php } ?>
		<?php
		if ($fieldUseError) {
			echo Components::render( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'error',
				Components::props('error', $attributes, [
					'errorId' => $fieldId,
					'selectorClass' => $componentClass
				])
			);
		}
		?>
	</div>

	<?php echo $additionalContent; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</<?php echo esc_attr($fieldTag); ?>>
