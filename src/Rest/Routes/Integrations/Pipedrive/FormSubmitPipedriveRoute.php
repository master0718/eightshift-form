<?php

/**
 * The class register route for public form submiting endpoint - Pipedrive
 *
 * @package EightshiftForms\Rest\Route\Integrations\Pipedrive
 */

declare(strict_types=1);

namespace EightshiftForms\Rest\Routes\Integrations\Pipedrive;

use EightshiftForms\Captcha\CaptchaInterface;
use EightshiftForms\Entries\EntriesInterface;
use EightshiftForms\Hooks\Filters;
use EightshiftForms\Integrations\Pipedrive\PipedriveClientInterface;
use EightshiftForms\Integrations\Pipedrive\SettingsPipedrive;
use EightshiftForms\Labels\LabelsInterface;
use EightshiftForms\Rest\Routes\AbstractBaseRoute;
use EightshiftForms\Rest\Routes\Integrations\Mailer\FormSubmitMailerInterface;
use EightshiftForms\Rest\Routes\AbstractFormSubmit;
use EightshiftForms\Security\SecurityInterface;
use EightshiftForms\Validation\ValidationPatternsInterface;
use EightshiftForms\Validation\ValidatorInterface;

/**
 * Class FormSubmitPipedriveRoute
 */
class FormSubmitPipedriveRoute extends AbstractFormSubmit
{
	/**
	 * Route slug.
	 */
	public const ROUTE_SLUG = SettingsPipedrive::SETTINGS_TYPE_KEY;

	/**
	 * Instance variable for Pipedrive data.
	 *
	 * @var PipedriveClientInterface
	 */
	protected $pipedriveClient;

	/**
	 * Create a new instance that injects classes
	 *
	 * @param ValidatorInterface $validator Inject validation methods.
	 * @param ValidationPatternsInterface $validationPatterns Inject validation patterns methods.
	 * @param LabelsInterface $labels Inject labels methods.
	 * @param CaptchaInterface $captcha Inject captcha methods.
	 * @param SecurityInterface $security Inject security methods.
	 * @param EntriesInterface $entries Inject entries methods.
	 * @param FormSubmitMailerInterface $formSubmitMailer Inject FormSubmitMailerInterface which holds mailer methods.
	 * @param PipedriveClientInterface $pipedriveClient Inject Pipedrive which holds Pipedrive connect data.
	 */
	public function __construct(
		ValidatorInterface $validator,
		ValidationPatternsInterface $validationPatterns,
		LabelsInterface $labels,
		CaptchaInterface $captcha,
		SecurityInterface $security,
		EntriesInterface $entries,
		FormSubmitMailerInterface $formSubmitMailer,
		PipedriveClientInterface $pipedriveClient
	) {
		$this->validator = $validator;
		$this->validationPatterns = $validationPatterns;
		$this->labels = $labels;
		$this->captcha = $captcha;
		$this->security = $security;
		$this->entries = $entries;
		$this->formSubmitMailer = $formSubmitMailer;
		$this->pipedriveClient = $pipedriveClient;
	}

	/**
	 * Get the base url of the route
	 *
	 * @return string The base URL for route you are adding.
	 */
	protected function getRouteName(): string
	{
		return '/' . AbstractBaseRoute::ROUTE_PREFIX_FORM_SUBMIT . '/' . self::ROUTE_SLUG;
	}

	/**
	 * Implement submit action.
	 *
	 * @param array<string, mixed> $formDataReference Form reference got from abstract helper.
	 *
	 * @return mixed
	 */
	protected function submitAction(array $formDataReference)
	{
		$formId = $formDataReference['formId'];

		// Send application to Hubspot.
		$response = $this->pipedriveClient->postApplication(
			$formDataReference['params'],
			$formDataReference['files'],
			$formId
		);

		$formDataReference['emailResponseTags'] = $this->getEmailResponseTags($response);

		// Finish.
		return \rest_ensure_response(
			$this->getIntegrationCommonSubmitAction(
				$response,
				$formDataReference,
				$formId,
			)
		);
	}

	/**
	 * Prepare email response tags from the API response.
	 *
	 * @param array<mixed> $response Response data to extract data from.
	 *
	 * @return array<string, string>
	 */
	private function getEmailResponseTags(array $response): array
	{
		$body = $response['body']['data'] ?? [];
		$output = [];

		if (!$body) {
			return $output;
		}

		foreach (Filters::ALL[SettingsPipedrive::SETTINGS_TYPE_KEY]['emailTemplateTags'] as $key => $value) {
			$output[$key] = $body[$value] ?? '';
		}

		return $output;
	}
}
