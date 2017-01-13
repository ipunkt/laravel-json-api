<?php
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;

/**
 * Class TestSerializer
 *
 * AbstractSerializer::getRelationship soll getestet werden, ist aber abstrakt.
 */
class TestSerializer extends \Ipunkt\LaravelJsonApi\Serializers\Serializer {

	/**
	 * returns attributes for model
	 *
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @return array
	 */
	protected function attributes($model): array {
		return [];
	}
}

/**
 * Class SerializerTest
 */
class SerializerTest extends TestCase {

	/**
	 * @test
	 */
	public function empty_relationship_is_not_added() {
		$relationSerializer = Mockery::mock(\Ipunkt\LaravelJsonApi\Serializers\Serializer::class);

		$serializer = Mockery::mock(\Ipunkt\LaravelJsonApi\Serializers\Serializer::class);
		$serializer->shouldReceive('getRelationship')->with([], 'testinclude')->andReturn(null);
		$serializer->shouldReceive('getRelationship')->with([], 'included')->andReturn( new Relationship( new Resource([], $relationSerializer) ) );
		$serializer->shouldReceive('getType')->with([])->andReturn('model');

		$resource = new \Tobscure\JsonApi\Resource([], $serializer);
		$resource->with(['testinclude', 'included']);

		$relationships = $resource->getRelationships();
		$this->assertArrayHasKey('included', $relationships);
		$this->assertArrayNotHasKey('testinclude', $relationships);
	}


	/**
	 * @test
	 */
	public function getRelationship_returns_null_for_empty_resources() {
		$resourceManager = Mockery::mock(\Ipunkt\LaravelJsonApi\Resources\ResourceManager::class);

		$relationRepository = Mockery::mock(\Ipunkt\LaravelJsonApi\Contracts\OneToOneRelationRepository::class);
		$relationRepository->shouldReceive('getOne')->andReturn(null);

		$relationSerializer = Mockery::mock(\Ipunkt\LaravelJsonApi\Serializers\Serializer::class);

		$resourceManager->shouldReceive('resolveRepository')->andReturn($relationRepository);
		$resourceManager->shouldReceive('resolveSerializer')->andReturn($relationSerializer);

		$serializer = new TestSerializer($resourceManager);

		$relationship = $serializer->getRelationship([], 'test');

		$this->assertNull($relationship);
	}

}