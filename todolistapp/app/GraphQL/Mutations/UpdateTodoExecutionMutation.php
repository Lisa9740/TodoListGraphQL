<?php

namespace App\graphql\Mutations;

use App\Models\Todo;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UpdateTodoExecutionMutation extends Mutation
{
    protected array $attributes = [
        'name' => 'updateTodoExecution'
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
                'type' =>  Type::nonNull(Type::string()),
            ],
            'is_executed' => [
                'name' => 'is_executed',
                'type' =>  Type::nonNull(Type::boolean()),
            ]

        ];
    }

    public function resolve($root, $args)
    {
        $todo = Todo::findOrFail($args['id']);
        $todo->fill($args);
        $todo->save();

        return $todo;
    }
}
