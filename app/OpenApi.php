<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Athletica API',
    description: 'API REST de la plateforme sport connecte Athletica',
    contact: new OA\Contact(email: 'contact@athletica.io', name: 'Athletica Support')
)]
#[OA\Server(url: '/api', description: 'Serveur API Athletica')]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
class OpenApi
{
}
