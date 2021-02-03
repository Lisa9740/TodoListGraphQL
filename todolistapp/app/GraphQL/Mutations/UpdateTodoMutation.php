<?php

namespace App\graphql\Mutations;

use App\Models\Todo;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UpdateTodoMutation extends Mutation
{
    protected array $attributes = [
        'name' => 'updateTodo'
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
            'title' => [
                'name' => 'title',
                'type' =>  Type::nonNull(Type::string()),
            ],
            'date_execution' => [
                'name' => 'date_execution',
                'type' =>  Type::nonNull(Type::string()),
            ],
            'priority' => [
                'name' => 'priority',
                'type' =>  Type::nonNull(Type::string()),
            ],
            'description' => [
                'name' => 'description',
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
