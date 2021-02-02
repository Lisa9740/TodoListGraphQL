<?php

namespace App\GraphQL\Queries;

use App\Models\Todo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class TodoQuery extends Query
{
    protected $attributes = [
        'name' => 'todo',
    ];

    public function type(): Type
    {
        return GraphQL::type('Todo');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => ['required']
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return Todo::findOrFail($args['id']);
    }
}
