<?php
use Carbon\Carbon;
use Ipunkt\LaravelJsonApi\Validation\PayloadValidators\DummyValidator;

/**
 * Class RefreshTokenTest
 */
class RefreshTokenTest extends TestCase {

	/**
	 * @test
	 */
	public function it_can_refresh_valid_token() {
		$user = $this->generatePremiumUser();

		$this->loginWithCredentials($user->email);
		$this->apiPost('/secure/v1/tokens/refresh');
		$this->assertResponseStatus(201);
	}

	/**
	 * @test
	 */
	public function it_can_refresh_expired_token_less_than_refresh_ttl_ago() {

		$refreshTtl = config('jwt.refresh_ttl');
		$ttl = config('jwt.ttl');
		$this->assertTrue($ttl * 3 < $refreshTtl);

		$startTimestamp = Carbon::now()->setTimezone('UTC')->subMinutes($ttl * 3)->timestamp;
		$customClaims = [
			'sub' => '1',
			'iat' => $startTimestamp,
			'exp' => Carbon::now()->subMinutes($ttl * 2)->timestamp,
			'nbf' => $startTimestamp,
			'issuer' => 'unittest',
		];

		$factory = new \Tymon\JWTAuth\PayloadFactory( app(\Tymon\JWTAuth\Claims\Factory::class), request(), new DummyValidator() );
		$payload = $factory->make($customClaims);

		$token = JWTAuth::encode($payload);

		$this->setToken($token);

		$this->apiPost('/secure/v1/tokens/refresh');
		$this->assertResponseStatus(201);
	}

	/**
	 * @test
	 */
	public function it_cant_refresh_expired_token_issued_more_than_refresh_ttl_ago() {

		$refreshTtl = config('jwt.refresh_ttl');

		$startTime = Carbon::now()->setTimezone('UTC')->subMinutes($refreshTtl+2);
		$startTimestamp = $startTime->timestamp;
		$customClaims = [
			'sub' => '1',
			'iat' => $startTimestamp,
			'exp' => Carbon::now()->subMinutes(2)->timestamp,
			'nbf' => $startTimestamp,
			'issuer' => 'unittest',
		];

		$factory = new \Tymon\JWTAuth\PayloadFactory( app(\Tymon\JWTAuth\Claims\Factory::class), request(), new DummyValidator() );
		$payload = $factory->make($customClaims);

		$token = JWTAuth::encode($payload);

		$this->setToken($token);

		$this->apiPost('/secure/v1/tokens/refresh');
		$this->assertResponseStatus(400);
	}
}