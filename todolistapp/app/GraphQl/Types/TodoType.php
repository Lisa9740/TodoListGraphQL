<?php

namespace App\GraphQL\Types;

use App\Models\Todo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TodoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Todo',
        'description' => 'Collection of books and details of Author',
        'model' => Todo::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id of a particular book',
            ],
            'title' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The title of the task',
            ],
            'date_execution' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The international standard book number for the book',
            ],
            'priority' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The international standard book number for the book',
            ],
            'description' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The international standard book number for the book',
            ], 'is_executed' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'The international standard book number for the book',
            ],
        ];
    }
}
