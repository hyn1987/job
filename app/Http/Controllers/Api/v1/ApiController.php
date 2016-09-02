<?php namespace Wawjob\Http\Controllers\Api\v1;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Config;
use Auth;

abstract class ApiController extends BaseController {

  use DispatchesCommands, ValidatesRequests;

  /**
   * Secret key
   */
  public $secret;

  public function __construct() {
    $this->secret = Config::get('api.key.v1');
  }

  /**
   * Generate JWT token.
   *
   * @return string
   */
  public function generateJWT($payload, $header = false)
  {
    return generate_jwt($this->secret, $payload, $header);
  }

  /**
   * Parse the JWT and return payload part.
   *
   * @return string
   */
  public function parseJWT($token)
  {
    return parse_jwt($this->secret, $token);
  }
}