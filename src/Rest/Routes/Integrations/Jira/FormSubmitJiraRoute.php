<?php

/**
 * The class register route for public form submiting endpoint - Jira
 *
 * @package EightshiftForms\Rest\Route\Integrations\Jira
 */

declare(strict_types=1);

namespace EightshiftForms\Rest\Routes\Integrations\Jira;

use EightshiftForms\Captcha\CaptchaInterface;
use EightshiftForms\Hooks\Filters;
use EightshiftForms\Integrations\Jira\JiraClientInterface;
use EightshiftForms\Integrations\Jira\SettingsJira;
use EightshiftForms\Labels\LabelsInterface;
use EightshiftForms\Rest\Routes\AbstractBaseRoute;
use EightshiftForms\Rest\Routes\AbstractFormSubmit;
use EightshiftForms\Rest\Routes\Integrations\Mailer\FormSubmitMailerInterface;
use EightshiftForms\Security\SecurityInterface;
use EightshiftForms\Validation\ValidationPatternsInterface;
use EightshiftForms\Validation\Validator;
use EightshiftForms\Validation\ValidatorInterface;

/**
 * Class FormSubmitJiraRoute
 */
class FormSubmitJiraRoute extends AbstractFormSubmit
{
	/**
	 * Route slug.
	 */
	public const ROUTE_SLUG = SettingsJira::SETTINGS_TYPE_KEY;

	/**
	 * Instance variable of ValidatorInterface data.
	 *
	 * @var ValidatorInterface
	 */
	protected $validator;

	/**
	 * Instance variable of ValidationPatternsInterface data.
	 *
	 * @var ValidationPatternsInterface
	 */
	protected $validationPatterns;

	/**
	 * Instance variable of LabelsInterface data.
	 *
	 * @var LabelsInterface
	 */
	protected $labels;

	/**
	 * Instance variable for Jira data.
	 *
	 * @var JiraClientInterface
	 */
	protected $jiraClient;

	/**
	 * Instance variable of FormSubmitMailerInterface data.
	 *
	 * @var FormSubmitMailerInterface
	 */
	public $formSubmitMailer;

	/**
	 * Instance variable of CaptchaInterface data.
	 *
	 * @var CaptchaInterface
	 */
	protected $captcha;

	/**
	 * Instance variable of SecurityInterface data.
	 *
	 * @var SecurityInterface
	 */
	protected $security;

	/**
	 * Create a new instance that injects classes
	 *
	 * @param ValidatorInterface $validator Inject ValidatorInterface which holds validation methods.
	 * @param ValidationPatternsInterface $validationPatterns Inject ValidationPatternsInterface which holds validation methods.
	 * @param LabelsInterface $labels Inject LabelsInterface which holds labels data.
	 * @param JiraClientInterface $jiraClient Inject Jira which holds Jira connect data.
	 * @param FormSubmitMailerInterface $formSubmitMailer Inject FormSubmitMailerInterface which holds mailer methods.
	 * @param CaptchaInterface $captcha Inject CaptchaInterface which holds captcha data.
	 * @param SecurityInterface $security Inject SecurityInterface which holds security data.
	 */
	public function __construct(
		ValidatorInterface $validator,
		ValidationPatternsInterface $validationPatterns,
		LabelsInterface $labels,
		JiraClientInterface $jiraClient,
		FormSubmitMailerInterface $formSubmitMailer,
		CaptchaInterface $captcha,
		SecurityInterface $security
	) {
		$this->validator = $validator;
		$this->validationPatterns = $validationPatterns;
		$this->labels = $labels;
		$this->jiraClient = $jiraClient;
		$this->formSubmitMailer = $formSubmitMailer;
		$this->captcha = $captcha;
		$this->security = $security;
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
	 * Returns validator class.
	 *
	 * @return ValidatorInterface
	 */
	protected function getValidator()
	{
		return $this->validator;
	}

	/**
	 * Returns validator patterns class.
	 *
	 * @return ValidationPatternsInterface
	 */
	protected function getValidatorPatterns()
	{
		return $this->validationPatterns;
	}

	/**
	 * Returns validator labels class.
	 *
	 * @return LabelsInterface
	 */
	protected function getValidatorLabels()
	{
		return $this->labels;
	}

	/**
	 * Returns captcha class.
	 *
	 * @return CaptchaInterface
	 */
	protected function getCaptcha()
	{
		return $this->captcha;
	}

	/**
	 * Returns securicty class.
	 *
	 * @return SecurityInterface
	 */
	protected function getSecurity()
	{
		return $this->security;
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
		$params = $formDataReference['params'];

		// Send application to Hubspot.
		$response = $this->jiraClient->postApplication(
			$params,
			[],
			$formId
		);

		$validation = $response[Validator::VALIDATOR_OUTPUT_KEY] ?? [];

		// There is no need to utput integrations validation issues because Jira doesn't control the form.

		// Skip fallback email if integration is disabled.
		if (!$response['isDisabled'] && $response['status'] === AbstractBaseRoute::STATUS_ERROR) {
			// Send fallback email.
			$this->formSubmitMailer->sendFallbackEmail($response);
		}

		$formDataReference['emailResponseTags'] = $this->getEmailResponseTags($response);

		// Send email if it is configured in the backend.
		if ($response['status'] === AbstractBaseRoute::STATUS_SUCCESS) {
			$this->formSubmitMailer->sendEmails($formDataReference);
		}

		$labelsOutput = $this->labels->getLabel($response['message'], $formId);
		$responseOutput = $response;

		// Output fake success and send fallback email.
		if ($response['isDisabled'] && !$validation) {
			$this->formSubmitMailer->sendFallbackEmail($response);

			$fakeResponse = $this->getIntegrationApiSuccessOutput($response);

			$labelsOutput = $this->labels->getLabel($fakeResponse['message'], $formId);
			$responseOutput = $fakeResponse;
		}

		// Finish.
		return \rest_ensure_response(
			$this->getIntegrationApiOutput(
				$responseOutput,
				$labelsOutput,
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
		$body = $response['body'] ?? [];
		$output = [];

		if (!$body) {
			return $output;
		}

		foreach (Filters::ALL[SettingsJira::SETTINGS_TYPE_KEY]['emailTemplateTags'] as $key => $value) {
			$item = $body[$value] ?? '';

			if ($key === 'jiraIssueUrl') {
				$jiraKey = $body['key'] ?? '';

				if ($jiraKey) {
					$output[$key] = $this->jiraClient->getBaseUrlOutputPrefix() . "browse/{$jiraKey}/";
				}
			} else {
				$output[$key] = $item;
			}
		}

		return $output;
	}
}
