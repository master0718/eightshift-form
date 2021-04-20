<?php

namespace EightshiftFormsTests\Integrations\Buckaroo;

use EightshiftFormsTests\BaseTest;
use EightshiftForms\Integrations\Buckaroo\ResponseFactory;
use EightshiftForms\Integrations\Buckaroo\Response;

class BuckarooResponse extends BaseTest
{

  protected function _inject(DataProvider $dataProvider)
  {
    $this->dataProvider = $dataProvider;
  }

  public function testIdealSuccessfulResponse()
  {
    $response = ResponseFactory::build( $this->dataProvider->idealSuccessResponseMock());
    $this->assertTrue( $response->is_ideal());
    $this->assertEquals( $response->get_status(), Response::STATUS_CODE_SUCCESS );
  }

  public function testIdealErrorResponse()
  {
    $response = ResponseFactory::build( $this->dataProvider->idealErrorResponseMock());
    $this->assertTrue( $response->is_ideal());
    $this->assertEquals( $response->get_status(), Response::STATUS_CODE_ERROR );
  }

  public function testIdealRejectResponse()
  {
    $response = ResponseFactory::build( $this->dataProvider->idealRejectResponseMock());
    $this->assertTrue( $response->is_ideal());
    $this->assertEquals( $response->get_status(), Response::STATUS_CODE_REJECT );
  }

  public function testIdealCancelledResponse()
  {
    $response = ResponseFactory::build( $this->dataProvider->idealCancelledResponseMock());
    $this->assertTrue( $response->is_ideal());
    $this->assertEquals( $response->get_status(), Response::STATUS_CODE_CANCELLED );
  }

  public function testIdealCancelledByBackButtonResponse()
  {
    $response = ResponseFactory::build( $this->dataProvider->idealCancelledResponseWhenUserClicksBackMock());
    $this->assertEquals( $response->get_status(), Response::STATUS_CODE_CANCELLED );
  }

  public function testEmandateFailResponse()
  {
    $response = ResponseFactory::build( $this->dataProvider->emandateFailedResponseMock());
    $this->assertTrue( $response->is_emandate());
    $this->assertEquals( $response->get_status(), Response::STATUS_CODE_ERROR );
  }

  public function testEmandateCancelledResponse()
  {
    $response = ResponseFactory::build( $this->dataProvider->emandateCancelledResponseMock());
    $this->assertTrue( $response->is_emandate());
    $this->assertEquals( $response->get_status(), Response::STATUS_CODE_CANCELLED );
  }
}