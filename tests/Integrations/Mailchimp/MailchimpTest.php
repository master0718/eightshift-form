<?php

namespace EightshiftFormsTests\Integrations\Mailchimp;

use EightshiftForms\Main\Main;
use EightshiftForms\Hooks\Filters;
use EightshiftForms\Integrations\Mailchimp\Mailchimp;
use EightshiftFormsTests\BaseTest;
use EightshiftFormsVendor\GuzzleHttp\Exception\ClientException;

class MailchimpTest extends BaseTest
{

  protected function _inject(DataProvider $dataProvider)
  {
    $this->dataProvider = $dataProvider;
  }

  protected function _before() {
    parent::_before();
    $this->mailerlite = $this->di_container->get( Mailerlite::class );
  }

  public function testAddOrUpdateMember()
  {
    $this->addHooks();
    $params = [
      'listId' => 'list-id',
      'email' => DataProvider::MOCK_EMAIL,
      'mergeFields' => [
        'FNAME' => 'some name',
      ],
    ];

    $response = $this->mailchimp->add_or_update_member(
      $params['listId'],
      $params['email'],
      $params['mergeFields'],
      []
    );

    $this->assertEquals($response, $this->dataProvider->getMockAddOrUpdateMemberResponse( $params ));
  }

  public function testAddOrUpdateMemberIfMissingListId()
  {
    $this->addHooks();
    $params = [
      'listId' => DataProvider::INVALID_LIST_ID,
      'email' => DataProvider::MOCK_EMAIL,
      'mergeFields' => [
        'FNAME' => 'some name',
      ],
    ];

    try {      
      $this->mailchimp->add_or_update_member(
        $params['listId'],
        $params['email'],
        $params['mergeFields'],
        []
      );

      $this->assertEquals(1,0);
    } catch(ClientException $e) {
      $this->assertIsObject($e);
    }
  }

  /**
   * Mocking that a certain filter exists. See documentation of Brain Monkey:
   * https://brain-wp.github.io/BrainMonkey/docs/wordpress-hooks-added.html
   *
   * We can't return any actual value, we can just "mock register" this filter.
   *
   * @return void
   */
  protected function addHooks() {
    add_filter( Filters::MAILCHIMP, function($key) {
      return $key;
    }, 1, 1);
  }
}