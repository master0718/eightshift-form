<?php

/**
 * File containing Jira specific interface.
 *
 * @package EightshiftForms\Integrations\Jira
 */

namespace EightshiftForms\Integrations\Jira;

/**
 * Interface for a Client
 */
interface JiraClientInterface
{
	/**
	 * Return projects.
	 *
	 * @param bool $hideUpdateTime Determin if update time will be in the output or not.
	 *
	 * @return array<string, mixed>
	 */
	public function getProjects(bool $hideUpdateTime = true): array;

	/**
	 * Return projects issue types.
	 *
	 * @param string $itemId Item ID to search by.
	 *
	 * @return array<string, mixed>
	 */
	public function getIssueType(string $itemId): array;

	/**
	 * API request to post issue.
	 *
	 * @param array<string, mixed> $params Params array.
	 * @param string $formId FormId value.
	 *
	 * @return array<string, mixed>
	 */
	public function postIssue(array $params, string $formId): array;

	/**
	 * Return base url prefix.
	 *
	 * @return string
	 */
	public function getBaseUrlPrefix(): string;
}
