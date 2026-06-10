<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Jotdown API',
    description: 'Interactive API documentation for the Jotdown backend service.'
)]
#[OA\Server(
    url: '/',
    description: 'Current application host'
)]
#[OA\Tag(
    name: 'System',
    description: 'System and health endpoints'
)]
#[OA\Tag(
    name: 'Auth',
    description: 'Authentication and password reset endpoints'
)]
final class OpenApiSpec
{
}
