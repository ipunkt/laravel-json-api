<?php

namespace Ipunkt\LaravelJsonApi\Repositories\Conditions;

use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\TakesConditions;

class IdIsCondition implements RepositoryCondition
{

	protected $allowedIds = [];

    /**
     * IdIsCondition constructor.
     * @param string $id
     */
    public function __construct(string $id) {
	    $ids = explode(',', $id);

	    //
	    if( count($ids) < 2 )
	    	$ids = $id;

	    $this->allowedIds = $ids;
    }

	/**
	 * apply a builder
	 *
	 * @param TakesConditions $builder
	 */
	public function apply(TakesConditions $builder) {
		$allowedIDs = $this->allowedIds;

		if( !is_array($allowedIDs) ) {
			$builder->where('id', $allowedIDs);

			return;
		}

		$builder->whereIn('id', $allowedIDs);
	}
}