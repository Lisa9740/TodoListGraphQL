<?php

namespace App\Graphql\Mutations;

use App\Models\Todo;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreateTodoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createTodo'
    ];

    public function type(): Type
    {
        return GraphQL::type('Todo');
    }

    public function args(): array
    {
        return [
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
            ],


        ];
    }

    public function resolve($root, $args)
    {
        $todo = new Todo();
        $todo->fill($args);
        $todo->save();

        return $todo;
    }
}
