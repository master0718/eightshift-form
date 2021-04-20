<?php

/**
 * Mailchimp integration class.
 *
 * @package EightshiftForms\Integrations
 */

declare(strict_types=1);

namespace EightshiftForms\Integrations\Mailchimp;

use EightshiftForms\Cache\Cache;
use EightshiftForms\Hooks\Filters;
use EightshiftForms\Exception\MissingFilterInfoException;
use EightshiftForms\Integrations\ClientInterface;
use MailchimpMarketing\ApiClient;

/**
 * Mailchimp integration class.
 */
class Mailchimp
{

  /**
   * Lists transient name
   *
   * @var string
   */
	public const CACHE_LISTS = 'eightshift-forms-mailchimp-lists';

  /**
   * Lists transient expiration time.
   *
   * @var int
   */
	public const CACHE_LIST_TIMEOUT = 60 * 15; // 15 min

  /**
   * Mailchimp Marketing Api client.
   *
   * @var ApiClient
   */
	private $client;

  /**
   * Our own implementation of Mailchimp Marketing Client.
   *
   * @var ClientInterface
   */
	private $mailchimpMarketingClient;

  /**
   * Cache object used for caching Mailchimp responses.
   *
   * @var Cache
   */
	private $cache;

  /**
   * Constructs object
   *
   * @param ClientInterface $mailchimpMarketingClient Mailchimp marketing client.
   * @param Cache           $transientCache            Transient cache object.
   */
	public function __construct(ClientInterface $mailchimpMarketingClient, Cache $transientCache)
	{
		$this->mailchimpMarketingClient = $mailchimpMarketingClient;
		$this->cache = $transientCache;
	}

  /**
   * Adds or updates a member in Mailchimp.
   *
   * @param  string $list_id      Audience list ID.
   * @param  string $email        Contact's email.
   * @param  array  $merge_fields List of merge fields to add to request.
   * @param  array  $params       (Optional) list of params from request.
   * @param  string $status       (Optional) Member's status (if new).
   * @return mixed
   *
   * @throws \Exception When response is invalid.
   */
	public function add_or_update_member(string $list_id, string $email, array $merge_fields, array $params = [], string $status = 'pending')
	{
		$this->setupClientConfigAndVerify();

		$params['email_address'] = $email;
		$params['status_if_new'] = $status;
		$params['merge_fields']  = $merge_fields;

		$response = $this->client->lists->setListMember($list_id, $this->calculate_subscriber_hash($email), $params);

		if (! is_object($response) || ! isset($response->id, $response->email_address)) {
			throw new \Exception('setListMember response invalid');
		}

		return $response;
	}

  /**
   * Adds a member in Mailchimp.
   *
   * @param  string $list_id      Audience list ID.
   * @param  string $email        Contact's email.
   * @param  array  $merge_fields List of merge fields to add to request.
   * @param  array  $params       (Optional) list of params from request.
   * @param  string $status       (Optional) Member's status (if new).
   * @return mixed
   *
   * @throws \Exception When response is invalid.
   */
	public function add_member(string $list_id, string $email, array $merge_fields, array $params = [], string $status = 'pending')
	{
		$this->setupClientConfigAndVerify();

		$params['email_address'] = $email;
		$params['status']        = $status;
		$params['merge_fields']  = $merge_fields;

		$response = $this->client->lists->addListMember($list_id, $params);

		if (! is_object($response) || ! isset($response->id, $response->email_address)) {
			throw new \Exception('setListMember response invalid');
		}

		return $response;
	}

  /**
   * Add a tag to a member.
   *
   * @param  string $list_id   Audience list ID.
   * @param  string $email     Contact's email.
   * @param  array  $tag_names Just a 1d array of tag names. Other processing is done inside.
   * @return bool
   *
   * @throws \Exception When response is invalid.
   */
	public function add_member_tags(string $list_id, string $email, array $tag_names): bool
	{
		$this->setupClientConfigAndVerify();

	  // This call requires a very specific format for tags.
		$tags_request = [
		'tags' => array_map(function ($tag_name) {
			return [
			'name' => $tag_name,
			'status' => 'active',
			];
		}, $tag_names),
		];

		$update_response = $this->client->lists->updateListMemberTags($list_id, md5($email), $tags_request);

	  // This call is weird in that an empty (204) response means success. If something went very wrong it
	  // will throw an exception. If something is slightly off (such as not having the correct format for
	  // tags array), it will also return an empty response.
		return ! empty($update_response);
	}

  /**
   * List a member
   *
   * @param  string $list_id Audience list ID.
   * @param  string $email   Contact's email.
   * @return mixed
   */
	public function get_list_member(string $list_id, string $email)
	{
		$this->setupClientConfigAndVerify();
		return $this->client->lists->getListMember($list_id, $this->calculate_subscriber_hash($email));
	}

  /**
   * Get information about all lists in the account.
   *
   * @param bool $is_fresh Set to true if you want to fetch the lists regardless if we already have them cached.
   * @return mixed
   *
   * @throws \Exception When response is invalid.
   */
	public function get_all_lists(bool $is_fresh = false)
	{

		$cached_response = $this->cache->get(self::CACHE_LISTS);

		if ($is_fresh || empty($cached_response)) {
			$this->setupClientConfigAndVerify();
			$response = $this->client->lists->getAllLists();

			if (! isset($response, $response->lists) && ! is_array($response->lists)) {
				throw new \Exception('Lists response invalid');
			}

			foreach ($response->lists as $list_obj) {
				if (! is_object($list_obj) || ! isset($list_obj->id, $list_obj->name)) {
					throw new \Exception('Lists response invalid');
				}
			}

			$this->cache->save(self::CACHE_LISTS, (string) wp_json_encode($response), self::CACHE_LIST_TIMEOUT);
		} else {
			$response = json_decode($cached_response);
		}

		return $response;
	}

  /**
   * Get information about all tags & segments in the account.
   *
   * @param  string $list_id Audience list ID.
   * @return mixed
   *
   * @throws \Exception When response is invalid.
   */
	public function get_all_segments(string $list_id)
	{
		$this->setupClientConfigAndVerify();
		$response = $this->client->lists->listSegments($list_id);

		if (! isset($response, $response->segments) && ! is_array($response->segments)) {
			throw new \Exception('Segments response invalid');
		}

		foreach ($response->segments as $segment_obj) {
			if (! is_object($segment_obj) || ! isset($segment_obj->id, $segment_obj->name, $segment_obj->type)) {
				throw new \Exception('Specific segment response invalid');
			}
		}
		return $response;
	}

  /**
   * Calculates the subscriber hash from email.
   *
   * @param  string $email Contact's email.
   * @return string
   */
	private function calculate_subscriber_hash(string $email): string
	{
		return md5($email);
	}

  /**
   * Make sure we have the data we need defined as filters.
   *
   * @throws MissingFilterInfoException When not all required keys are set.
   *
   * @return void
   */
	private function setupClientConfigAndVerify(): void
	{
		if (! has_filter(Filters::MAILCHIMP)) {
			throw MissingFilterInfoException::view_exception(Filters::MAILCHIMP, esc_html__('entire_filter', 'eightshift-forms'));
		}

		if (empty(\apply_filters(Filters::MAILCHIMP, 'api_key'))) {
			throw MissingFilterInfoException::view_exception(Filters::MAILCHIMP, 'api_key');
		}

		if (empty(\apply_filters(Filters::MAILCHIMP, 'server'))) {
			throw MissingFilterInfoException::view_exception(Filters::MAILCHIMP, 'server');
		}

		if (empty($this->client)) {
			$this->mailchimpMarketingClient->setConfig();
			$this->client = $this->mailchimpMarketingClient->getClient();
		}
	}
}
