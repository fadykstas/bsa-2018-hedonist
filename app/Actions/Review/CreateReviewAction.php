<?php

namespace Hedonist\Actions\Review;

use Hedonist\Entities\Review\Review;
use Hedonist\Repositories\Review\ReviewRepositoryInterface;

class CreateReviewAction
{
    private $reviewRepository;

    public function __construct()
    {
        $this->reviewRepository = app()->make(ReviewRepositoryInterface::class);
    }

    public function execute(CreateReviewRequest $request): CreateReviewResponse
    {
        $review = $this->reviewRepository->save(
            new Review(
                [
                    'user_id'       => $request->getUserId(),
                    'place_id'      => $request->getPlaceId(),
                    'description'   => $request->getDescription()
                ]
            )
        );
        return new CreateReviewResponse($review);
    }
}
