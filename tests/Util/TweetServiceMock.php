<?php

namespace Tests\Util;

use App\Service\TweetService\TweetService;

class TweetServiceMock extends TweetService
{
    protected function fetchTweetDataById($id): array
    {
        $data = [
        	123 => [
	            'id' => 123,
	            'text' => 'foo',
	            'user' => [
	                'id' => 6,
	                'name' => 'bar',
	                'followers_count' => 12
	            ]
            ],
	        321 => [
		        'id' => 321,
		        'text' => 'foo2',
		        'user' => [
			        'id' => 6,
			        'name' => 'bar2',
			        'followers_count' => 21
		        ]
	        ]
        ];
        if (array_key_exists($id, $data)) {
	        return $data[$id];
        } else {
        	return ['errors' => 'model not found'];
        }
    }

    protected function fetchRetweetsDataById($id): array
    {
	    $data = [
	    	123 => [
	            [
	                'id' => 1,
	                'text' => 'foo',
	                'user' => [
	                    'id' => 6,
	                    'name' => 'bar',
	                    'followers_count' => 6
	                ]
	            ],
	            [
	                'id' => 2,
	                'text' => 'foo',
	                'user' => [
	                    'id' => 6,
	                    'name' => 'bar',
	                    'followers_count' => 12
	                ]
	            ],
	            [
	                'id' => 3,
	                'text' => 'foo',
	                'user' => [
	                    'id' => 6,
	                    'name' => 'bar',
	                    'followers_count' => 24
	                ]
	            ]
            ],
		    321 => []
	    ];
	    if (array_key_exists($id, $data)) {
		    return $data[$id];
	    } else {
		    return ['errors' => 'model not found'];
	    }
    }
}
