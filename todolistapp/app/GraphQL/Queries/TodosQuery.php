<?php

namespace App\Graphql\Queries;

use App\Models\Todo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class TodosQuery extends Query
{
    protected array $attributes = [
        'name' => 'todos',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Todo'));
    }

    public function args(): array
    {
        return [
            'priority' => [
                'name' => 'priority',
                'type' => Type::string(),
            ],
        ];
    }
    public function resolve($root, $args)
    {

        if (isset($args['id'])) {
            return Todo::find($args['id']);
        }

        if (isset($args['priority'])) {
            return Todo::where('priority', $args['priority'])->get();
        }

        return Todo::all();
    }
}
