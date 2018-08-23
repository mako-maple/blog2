<?php
namespace App\Loggers;

use Illuminate\Support\Facades\Auth;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use App\Actlog;
use \Route;

class DatabaseMonologHandler extends AbstractProcessingHandler
{
    protected function write(array $record)
    {
        $request = Route::getCurrentRequest();
        $data = [
            'level' => $record['level'],
            'level_name' => $record['level_name'],
            'context' => count($record['context']) != 0 ? json_encode($record['context']) : null,
            'channel' => $record['channel'],
            'message' => $record['message'],

            'url' => $request -> path(),
            'user_agent' => $request -> userAgent(),
            'remote_addr' => $request -> ip(),

            'user_id' => Auth::id() > 0 ? Auth::id() : null,

            'route' => Route::currentRouteName(),
        ];
        Actlog::create($data);
    }
}
