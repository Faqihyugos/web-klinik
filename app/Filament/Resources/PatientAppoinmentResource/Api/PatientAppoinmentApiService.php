<?php
namespace App\Filament\Resources\PatientAppoinmentResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\PatientAppoinmentResource;
use Illuminate\Routing\Router;


class PatientAppoinmentApiService extends ApiService
{
    protected static string | null $resource = PatientAppoinmentResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
