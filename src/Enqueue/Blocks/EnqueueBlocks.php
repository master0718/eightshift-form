<?php

/**
 * Enqueue class used to define all script and style enqueues for Gutenberg blocks.
 *
 * @package EightshiftForms\Enqueue\Blocks
 */

declare(strict_types=1);

namespace EightshiftForms\Enqueue\Blocks;

use EightshiftForms\Config\Config;
use EightshiftForms\Settings\Settings\SettingsGeneral;
use EightshiftForms\Settings\SettingsHelper;
use EightshiftFormsVendor\EightshiftLibs\Manifest\ManifestInterface;
use EightshiftFormsVendor\EightshiftLibs\Enqueue\Blocks\AbstractEnqueueBlocks;

/**
 * Enqueue_Blocks class.
 */
class EnqueueBlocks extends AbstractEnqueueBlocks
{

	/**
	 * Filter additional blocks key.
	 */
	public const FILTER_ADDITIONAL_BLOCKS_NAME = 'es_forms_additional_blocks';

	/**
	 * Use general helper trait.
	 */
	use SettingsHelper;

	/**
	 * Create a new admin instance.
	 *
	 * @param ManifestInterface $manifest Inject manifest which holds data about assets from manifest.json.
	 */
	public function __construct(ManifestInterface $manifest)
	{
		$this->manifest = $manifest;
	}

	/**
	 * Register all the hooks
	 */
	public function register(): void
	{
		// Editor only script.
		\add_action('enqueue_block_editor_assets', [$this, 'enqueueBlockEditorScriptLocal']);

		// Editor only style.
		\add_action('enqueue_block_editor_assets', [$this, 'enqueueBlockEditorStyleLocal'], 50);

		// Editor and frontend style.
		\add_action('enqueue_block_assets', [$this, 'enqueueBlockStyleLocal'], 50);

		// Frontend only script.
		\add_action('wp_enqueue_scripts', [$this, 'enqueueBlockScriptLocal']);
	}

	/**
	 * Method that returns editor only script with check.
	 *
	 * @return mixed
	 */
	public function enqueueBlockEditorScriptLocal()
	{
		if ($this->getOptionValue(SettingsGeneral::SETTINGS_GENERAL_DISABLE_DEFAULT_SCRIPTS_KEY)) {
			return null;
		}

		return $this->enqueueBlockEditorScript();
	}

	/**
	 * Method that returns editor only style with check.
	 *
	 * @return mixed
	 */
	public function enqueueBlockEditorStyleLocal()
	{
		if ($this->getOptionValue(SettingsGeneral::SETTINGS_GENERAL_DISABLE_DEFAULT_STYLES_KEY)) {
			return null;
		}

		return $this->enqueueBlockEditorStyle();
	}

	/**
	 * Method that returns editor and frontend style with check.
	 *
	 * @return mixed
	 */
	public function enqueueBlockStyleLocal()
	{
		if ($this->getOptionValue(SettingsGeneral::SETTINGS_GENERAL_DISABLE_DEFAULT_STYLES_KEY)) {
			return null;
		}

		return $this->enqueueBlockStyle();
	}

	/**
	 * Method that returns frontend only script with check.
	 *
	 * @return mixed
	 */
	public function enqueueBlockScriptLocal()
	{
		if ($this->getOptionValue(SettingsGeneral::SETTINGS_GENERAL_DISABLE_DEFAULT_SCRIPTS_KEY)) {
			return null;
		}

		return $this->enqueueBlockScript();
	}

	/**
	 * Method that returns assets name used to prefix asset handlers.
	 *
	 * @return string
	 */
	public function getAssetsPrefix(): string
	{
		return Config::getProjectName();
	}

	/**
	 * Method that returns assets version for versioning asset handlers.
	 *
	 * @return string
	 */
	public function getAssetsVersion(): string
	{
		return Config::getProjectVersion();
	}

	/**
	 * Get script localizations
	 *
	 * @return array
	 */
	protected function getLocalizations(): array
	{
		if (!is_admin()) {
			return [];
		}

		return [
			'esFormsBlocksLocalization' => [
				'additionalBlocks' => apply_filters(self::FILTER_ADDITIONAL_BLOCKS_NAME, []),
			]
		];
	}
}
