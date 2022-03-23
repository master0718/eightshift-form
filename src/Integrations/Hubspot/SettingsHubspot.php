<?php

/**
 * HubSpot Settings class.
 *
 * @package EightshiftForms\Integrations\Hubspot
 */

declare(strict_types=1);

namespace EightshiftForms\Integrations\Hubspot;

use EightshiftForms\Helpers\Components;
use EightshiftForms\Helpers\Helper;
use EightshiftForms\Hooks\Filters;
use EightshiftForms\Settings\SettingsHelper;
use EightshiftForms\Hooks\Variables;
use EightshiftForms\Integrations\Clearbit\SettingsClearbit;
use EightshiftForms\Integrations\ClientInterface;
use EightshiftForms\Integrations\MapperInterface;
use EightshiftForms\Settings\Settings\SettingsDataInterface;
use EightshiftFormsVendor\EightshiftLibs\Services\ServiceInterface;

/**
 * SettingsHubspot class.
 */
class SettingsHubspot implements SettingsDataInterface, ServiceInterface
{
	/**
	 * Use general helper trait.
	 */
	use SettingsHelper;

	/**
	 * Filter settings sidebar key.
	 */
	public const FILTER_SETTINGS_SIDEBAR_NAME = 'es_forms_settings_sidebar_hubspot';

	/**
	 * Filter settings key.
	 */
	public const FILTER_SETTINGS_NAME = 'es_forms_settings_hubspot';

	/**
	 * Filter global settings key.
	 */
	public const FILTER_SETTINGS_GLOBAL_NAME = 'es_forms_settings_global_hubspot';

	/**
	 * Filter settings is Valid key.
	 */
	public const FILTER_SETTINGS_IS_VALID_NAME = 'es_forms_settings_is_valid_hubspot';

	/**
	 * Settings key.
	 */
	public const SETTINGS_TYPE_KEY = 'hubspot';

	/**
	 * HubSpot Use key.
	 */
	public const SETTINGS_HUBSPOT_USE_KEY = 'hubspot-use';

	/**
	 * API Key.
	 */
	public const SETTINGS_HUBSPOT_API_KEY_KEY = 'hubspot-api-key';

	/**
	 * Item ID Key.
	 */
	public const SETTINGS_HUBSPOT_ITEM_ID_KEY = 'hubspot-item-id';

	/**
	 * Integration fields Key.
	 */
	public const SETTINGS_HUBSPOT_INTEGRATION_FIELDS_KEY = 'hubspot-integration-fields';

	/**
	 * Filemanager folder Key.
	 */
	public const SETTINGS_HUBSPOT_FILEMANAGER_FOLDER_KEY = 'hubspot-filemanager-folder';

	/**
	 * Use Clearbit Key.
	 */
	public const SETTINGS_HUBSPOT_USE_CLEARBIT_KEY = 'hubspot-use-clearbit';

	/**
	 * Use Clearbit email field Key.
	 */
	public const SETTINGS_HUBSPOT_CLEARBIT_EMAIL_FIELD_KEY = 'hubspot-clearbit-email-field';

	/**
	 * Instance variable for Clearbit data.
	 *
	 * @var ClientInterface
	 */
	protected $clearbitClient;

	/**
	 * Instance variable for Hubspot data.
	 *
	 * @var HubspotClientInterface
	 */
	protected $hubspotClient;

	/**
	 * Instance variable for HubSpot form data.
	 *
	 * @var MapperInterface
	 */
	protected $hubspot;

	/**
	 * Create a new instance.
	 *
	 * @param ClientInterface $clearbitClient Inject Clearbit which holds Clearbit connect data.
	 * @param HubspotClientInterface $hubspotClient Inject Hubspot which holds Hubspot connect data.
	 * @param MapperInterface $hubspot Inject HubSpot which holds HubSpot form data.
	 */
	public function __construct(
		ClientInterface $clearbitClient,
		HubspotClientInterface $hubspotClient,
		MapperInterface $hubspot
	) {
		$this->clearbitClient = $clearbitClient;
		$this->hubspotClient = $hubspotClient;
		$this->hubspot = $hubspot;
	}

	/**
	 * Register all the hooks
	 *
	 * @return void
	 */
	public function register(): void
	{
		\add_filter(self::FILTER_SETTINGS_SIDEBAR_NAME, [$this, 'getSettingsSidebar']);
		\add_filter(self::FILTER_SETTINGS_NAME, [$this, 'getSettingsData']);
		\add_filter(self::FILTER_SETTINGS_GLOBAL_NAME, [$this, 'getSettingsGlobalData']);
		\add_filter(self::FILTER_SETTINGS_IS_VALID_NAME, [$this, 'isSettingsValid']);
	}

	/**
	 * Determine if settings are valid.
	 *
	 * @param string $formId Form ID.
	 *
	 * @return boolean
	 */
	public function isSettingsValid(string $formId): bool
	{
		if (!$this->isSettingsGlobalValid()) {
			return false;
		}

		$itemId = $this->getSettingsValue(self::SETTINGS_HUBSPOT_ITEM_ID_KEY, $formId);

		if (empty($itemId)) {
			return false;
		}

		return true;
	}

	/**
	 * Determine if settings global are valid.
	 *
	 * @return boolean
	 */
	public function isSettingsGlobalValid(): bool
	{
		$isUsed = $this->isCheckboxOptionChecked(SettingsHubspot::SETTINGS_HUBSPOT_USE_KEY, SettingsHubspot::SETTINGS_HUBSPOT_USE_KEY);
		$apiKey = !empty(Variables::getApiKeyHubspot()) ? Variables::getApiKeyHubspot() : $this->getOptionValue(SettingsHubspot::SETTINGS_HUBSPOT_API_KEY_KEY);

		if (!$isUsed || empty($apiKey)) {
			return false;
		}

		return true;
	}

	/**
	 * Get Settings sidebar data.
	 *
	 * @return array<string, mixed>
	 */
	public function getSettingsSidebar(): array
	{
		return [
			'label' => __('HubSpot', 'eightshift-forms'),
			'value' => self::SETTINGS_TYPE_KEY,
			'icon' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="m8.5 17 2.5-2m3.25-11v3.5M3.5 3 11 8.625" stroke="#29A3A3" stroke-width="1.5" stroke-linecap="round" fill="none"/><circle cx="14.25" cy="11.75" r="4.25" stroke="#29A3A3" stroke-width="1.5" fill="none"/><circle cx="2.75" cy="2.25" fill="#29A3A3" r="1.75"/><circle cx="14.25" cy="2.75" fill="#29A3A3" r="1.75"/><circle cx="7.75" cy="17.75" fill="#29A3A3" r="1.75"/></svg>',
		];
	}

	/**
	 * Get Form settings data array
	 *
	 * @param string $formId Form Id.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getSettingsData(string $formId): array
	{
		if (!$this->isSettingsGlobalValid()) {
			return [
				[
					'component' => 'highlighted-content',
					'highlightedContentTitle' => __('Some config required', 'eightshift-forms'),
					// translators: %s will be replaced with the global settings url.
					'highlightedContentSubtitle' => sprintf(__('Before using HubSpot you need to configure it in  <a href="%s">global settings</a>.', 'eightshift-forms'), Helper::getSettingsGlobalPageUrl(self::SETTINGS_TYPE_KEY)),
					'highlightedContentIcon' => 'tools',
				]
			];
		}

		$items = $this->hubspotClient->getItems(false);
		$lastUpdatedTime = $items[ClientInterface::TRANSIENT_STORED_TIME]['title'] ?? '';
		unset($items[ClientInterface::TRANSIENT_STORED_TIME]);

		if (!$items) {
			return [
				[
					'component' => 'highlighted-content',
					'highlightedContentTitle' => __('Something went wrong', 'eightshift-forms'),
					'highlightedContentSubtitle' => __('Data from HubSpot couldn\'t be fetched. Check the API key.', 'eightshift-forms'),
					'highlightedContentIcon' => 'error',
				],
			];
		}

		$itemOptions = array_map(
			function ($option) use ($formId) {
				$id = $option['id'] ?? '';

				return [
					'component' => 'select-option',
					'selectOptionLabel' => $option['title'] ?? '',
					'selectOptionValue' => $id,
					'selectOptionIsSelected' => $this->isCheckedSettings($id, self::SETTINGS_HUBSPOT_ITEM_ID_KEY, $formId),
				];
			},
			$items
		);

		array_unshift(
			$itemOptions,
			[
				'component' => 'select-option',
				'selectOptionLabel' => '',
				'selectOptionValue' => '',
			]
		);

		$selectedItem = $this->getSettingsValue(self::SETTINGS_HUBSPOT_ITEM_ID_KEY, $formId);
		$manifestForm = Components::getManifest(dirname(__DIR__, 2) . '/Blocks/components/form');

		$output = [
			[
				'component' => 'intro',
				'introTitle' => __('HubSpot', 'eightshift-forms'),
			],
			[
				'component' => 'select',
				'selectName' => $this->getSettingsName(self::SETTINGS_HUBSPOT_ITEM_ID_KEY),
				'selectId' => $this->getSettingsName(self::SETTINGS_HUBSPOT_ITEM_ID_KEY),
				'selectFieldLabel' => __('Form', 'eightshift-forms'),
				// translators: %1$s will be replaced with js selector, %2$s will be replaced with the cache type, %3$s will be replaced with latest update time.
				'selectFieldHelp' => sprintf(__('If a list isn\'t showing up or is missing some items, try <a href="#" class="%1$s" data-type="%2$s">clearing the cache</a>. Last updated: %3$s.', 'eightshift-forms'), $manifestForm['componentCacheJsClass'], self::SETTINGS_TYPE_KEY, $lastUpdatedTime),
				'selectOptions' => $itemOptions,
				'selectIsRequired' => true,
				'selectValue' => $selectedItem,
				'selectSingleSubmit' => true,
			],
		];

		// If the user has selected the list.
		if ($selectedItem) {
			$beforeContent = '';

			$filterName = Filters::getIntegrationFilterName(self::SETTINGS_TYPE_KEY, 'adminFieldsSettings');
			if (has_filter($filterName)) {
				$beforeContent = \apply_filters($filterName, '') ?? '';
			}

			$formFields = $this->hubspot->getFormFields($formId);

			$useClearbit = \apply_filters(SettingsClearbit::FILTER_SETTINGS_GLOBAL_IS_VALID_NAME, $formId);

			$clearbitOutput = [];

			if ($useClearbit) {
				$isClearbitUsed = $this->isCheckboxSettingsChecked(self::SETTINGS_HUBSPOT_USE_CLEARBIT_KEY, self::SETTINGS_HUBSPOT_USE_CLEARBIT_KEY, $formId);

				$clearbitOutput = [
					[
						'component' => 'divider',
					],
					[
						'component' => 'intro',
						'introTitle' => __('Clearbit', 'eightshift-forms'),
						'introTitleSize' => 'medium',
					],
					[
						'component' => 'checkboxes',
						'checkboxesFieldLabel' => '',
						'checkboxesName' => $this->getSettingsName(self::SETTINGS_HUBSPOT_USE_CLEARBIT_KEY),
						'checkboxesId' => $this->getSettingsName(self::SETTINGS_HUBSPOT_USE_CLEARBIT_KEY),
						'checkboxesIsRequired' => true,
						'checkboxesContent' => [
							[
								'component' => 'checkbox',
								'checkboxLabel' => __('Use Clearbit integration', 'eightshift-forms'),
								'checkboxIsChecked' => $isClearbitUsed,
								'checkboxValue' => self::SETTINGS_HUBSPOT_USE_CLEARBIT_KEY,
								'checkboxSingleSubmit' => true,
							]
						]
					],
				];

				$clearbitOptionsOutput = [];

				if ($isClearbitUsed) {
					// error_log( print_r( ( $this->clearbitClient->getItems() ), true ) );
					// error_log( print_r( ( $this->hubspotClient->getContactProperties() ), true ) );

					$clearbitMapOutput = [];

					$hubspotContactProperties = array_merge(
						[
							[
								'component' => 'select-option',
								'selectOptionLabel' => '',
								'selectOptionValue' => '',
							],
						],
						array_map(
							static function ($option) {
								return [
									'component' => 'select-option',
									'selectOptionLabel' => $option,
									'selectOptionValue' => $option,
									// 'selectOptionIsSelected' => $this->isCheckedSettings($option['inputId'], self::SETTINGS_HUBSPOT_CLEARBIT_EMAIL_FIELD_KEY, $formId),
								];
							},
							$this->hubspotClient->getContactProperties()
						)
					);

					foreach ($this->clearbitClient->getItems() as $item) {
						$clearbitMapOutput[] = [
							'component' => 'select',
							'selectName' => $this->getSettingsName(self::SETTINGS_HUBSPOT_CLEARBIT_EMAIL_FIELD_KEY),
							'selectId' => $this->getSettingsName(self::SETTINGS_HUBSPOT_CLEARBIT_EMAIL_FIELD_KEY),
							'selectFieldLabel' => $item,
							'selectFieldHelp' => __('Map Clearbit fields with HubSpot fields.', 'eightshift-forms'),
							'selectOptions' => $hubspotContactProperties,
							'selectIsRequired' => true,
							// 'selectValue' => $this->getSettingsValue(self::SETTINGS_HUBSPOT_CLEARBIT_EMAIL_FIELD_KEY, $formId),
						];
					}

					// error_log( print_r( ( $clearbitMapOutput ), true ) );
					

					$clearbitOptionsOutput = array_merge(
						[
							[
								'component' => 'select',
								'selectName' => $this->getSettingsName(self::SETTINGS_HUBSPOT_CLEARBIT_EMAIL_FIELD_KEY),
								'selectId' => $this->getSettingsName(self::SETTINGS_HUBSPOT_CLEARBIT_EMAIL_FIELD_KEY),
								'selectFieldLabel' => __('Email field', 'eightshift-forms'),
								'selectFieldHelp' => __('Select what field in HubSpot is email filed.', 'eightshift-forms'),
								'selectOptions' => array_merge(
									[
										[
											'component' => 'select-option',
											'selectOptionLabel' => '',
											'selectOptionValue' => '',
										],
									],
									array_map(
										function ($option) use ($formId) {
											if ($option['component'] === 'input') {
												return [
													'component' => 'select-option',
													'selectOptionLabel' => $option['inputFieldLabel'] ?? '',
													'selectOptionValue' => $option['inputId'] ?? '',
													'selectOptionIsSelected' => $this->isCheckedSettings($option['inputId'], self::SETTINGS_HUBSPOT_CLEARBIT_EMAIL_FIELD_KEY, $formId),
												];
											}
										},
										$formFields
									),
								),
								'selectIsRequired' => true,
								'selectValue' => $this->getSettingsValue(self::SETTINGS_HUBSPOT_CLEARBIT_EMAIL_FIELD_KEY, $formId),
							],
						],
						$clearbitMapOutput
					);
				}

				$clearbitOutput = array_merge($clearbitOutput, $clearbitOptionsOutput);
			}

			$fieldsOutput = [
				[
					'component' => 'divider',
				],
				[
					'component' => 'intro',
					'introTitle' => __('Form fields', 'eightshift-forms'),
					'introTitleSize' => 'medium',
					'introSubtitle' => __('Control which fields show up on the frontend, set up how they look and work.', 'eightshift-forms'),
				],
				[
					'component' => 'group',
					'groupId' => $this->getSettingsName(self::SETTINGS_HUBSPOT_INTEGRATION_FIELDS_KEY),
					'groupBeforeContent' => $beforeContent,
					'groupContent' => $this->getIntegrationFieldsDetails(
						self::SETTINGS_HUBSPOT_INTEGRATION_FIELDS_KEY,
						self::SETTINGS_TYPE_KEY,
						$formFields,
						$formId
					),
				]
			];

			$output = array_merge(
				$output,
				[
					[
						'component' => 'divider',
					],
					[
						'component' => 'intro',
						'introTitle' => __('File manager', 'eightshift-forms'),
						'introTitleSize' => 'medium',
					],
					[
						'component' => 'input',
						'inputName' => $this->getSettingsName(self::SETTINGS_HUBSPOT_FILEMANAGER_FOLDER_KEY),
						'inputId' => $this->getSettingsName(self::SETTINGS_HUBSPOT_FILEMANAGER_FOLDER_KEY),
						'inputPlaceholder' => HubspotClient::HUBSPOT_FILEMANAGER_DEFAULT_FOLDER_KEY,
						'inputFieldLabel' => __('Folder', 'eightshift-forms'),
						'inputFieldHelp' => __('If you use file input field all files will be uploaded to the specified folder.', 'eightshift-forms'),
						'inputType' => 'text',
						'inputValue' => $this->getSettingsValue(self::SETTINGS_HUBSPOT_FILEMANAGER_FOLDER_KEY, $formId),
					],
					
				],
				$clearbitOutput,
				$fieldsOutput
			);
		}

		return $output;
	}

	/**
	 * Get global settings array for building settings page.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getSettingsGlobalData(): array
	{
		$isUsed = $this->isCheckboxOptionChecked(self::SETTINGS_HUBSPOT_USE_KEY, self::SETTINGS_HUBSPOT_USE_KEY);

		$output = [
			[
				'component' => 'intro',
				'introTitle' => __('HubSpot', 'eightshift-forms'),
			],
			[
				'component' => 'intro',
				'introTitle' => __('How to get the API key?', 'eightshift-forms'),
				'introTitleSize' => 'medium',
				// phpcs:ignore WordPress.WP.I18n.NoHtmlWrappedStrings
				'introSubtitle' => __('<ol>
						<li>Log in to your HubSpot account</li>
						<li>Click on the settings cog icon in the top right, next to your account</li>
						<li>In the menu on the left, under <strong>Integrations</strong> click <strong>API Key</strong></li>
						<li>On the page that loads in the <strong>Active API key</strong> panel, click on <strong>Show</strong>, verify the captcha if needed, then click <strong>Copy</strong></li>
						<li>Copy the API key into the field below or use the global constant.</li>
					</ol>', 'eightshift-forms'),
			],
			[
				'component' => 'divider',
			],
			[
				'component' => 'checkboxes',
				'checkboxesFieldLabel' => '',
				'checkboxesName' => $this->getSettingsName(self::SETTINGS_HUBSPOT_USE_KEY),
				'checkboxesId' => $this->getSettingsName(self::SETTINGS_HUBSPOT_USE_KEY),
				'checkboxesIsRequired' => true,
				'checkboxesContent' => [
					[
						'component' => 'checkbox',
						'checkboxLabel' => __('Use HubSpot', 'eightshift-forms'),
						'checkboxIsChecked' => $this->isCheckboxOptionChecked(self::SETTINGS_HUBSPOT_USE_KEY, self::SETTINGS_HUBSPOT_USE_KEY),
						'checkboxValue' => self::SETTINGS_HUBSPOT_USE_KEY,
						'checkboxSingleSubmit' => true,
					]
				]
			],
		];

		if ($isUsed) {
			$apiKey = Variables::getApiKeyHubspot();

			$output = array_merge(
				$output,
				[
					[
						'component' => 'divider',
					],
					[
						'component' => 'input',
						'inputName' => $this->getSettingsName(self::SETTINGS_HUBSPOT_API_KEY_KEY),
						'inputId' => $this->getSettingsName(self::SETTINGS_HUBSPOT_API_KEY_KEY),
						'inputFieldLabel' => __('API key', 'eightshift-forms'),
						'inputFieldHelp' => __('Can also be provided via a global variable.', 'eightshift-forms'),
						'inputType' => 'password',
						'inputIsRequired' => true,
						'inputValue' => !empty($apiKey) ? 'xxxxxxxxxxxxxxxx' : $this->getOptionValue(self::SETTINGS_HUBSPOT_API_KEY_KEY),
						'inputIsDisabled' => !empty($apiKey),
					]
				]
			);
		}

		return $output;
	}
}
