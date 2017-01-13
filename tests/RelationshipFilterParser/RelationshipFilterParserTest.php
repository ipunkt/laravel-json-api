<?php

/**
 * Class RelationshipFilterParserTest
 */
class RelationshipFilterParserTest extends TestCase {


	/**
	 * @test
	 */
	public function it_can_filter_based_on_relations() {
		$filters = [
			'parent' => 'notthere',
			'relation.applicable' => 'there',
		];

		$reltaionshipFilterParser = new \Ipunkt\LaravelJsonApi\Services\RelationshipFilterParser\RelationshipFilterParser();
		$filters = $reltaionshipFilterParser->filtersForRelationship('parent', 'relation', $filters);

		$this->assertArrayNotHasKey('parent', $filters);
		$this->assertArrayHasKey('applicable', $filters);
	}

	/**
	 * @test
	 */
	public function it_can_filter_based_on_resource_and_relations() {

		$filters = [
			'parent' => 'notthere',
			'parent.relation.applicable' => 'there',
			'otherparent.relation.not-applicable' => 'notthere',
		];

		$reltaionshipFilterParser = new \Ipunkt\LaravelJsonApi\Services\RelationshipFilterParser\RelationshipFilterParser();
		$filters = $reltaionshipFilterParser->filtersForRelationship('parent', 'relation', $filters);

		$this->assertArrayNotHasKey('parent', $filters);
		$this->assertArrayHasKey('applicable', $filters);
		$this->assertArrayNotHasKey('not-applicable', $filters);
	}
}