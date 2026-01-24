<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthAttemptService
{
    private array $config = [
        'password' => ['table' => 'password_attempts', 'max' => 3, 'hours' => 24],
        'otp'      => ['table' => 'otp_attempts',      'max' => 5,  'hours' => 1],
    ];

    private function config(string $type): array
    {
        return $this->config[$type] ?? throw new BadRequestHttpException('Invalid attempt type.');
    }

    public function reset(string $type, int $userId): void
    {
        DB::table($this->config($type)['table'])
            ->where('user_id', $userId)
            ->update([
                'failed_attempts' => 0,
                'last_failed_at'  => null,
                'updated_at'      => now(),
            ]);
    }

    public function record(string $type, int $userId): void
    {
        $table = $this->config($type)['table'];

        $row = DB::table($table)->where('user_id', $userId)->first();

        if (!$row) {
            DB::table($table)->insert([
                'user_id'         => $userId,
                'failed_attempts' => 1,
                'last_failed_at'  => now(),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
            return;
        }

        DB::table($table)
            ->where('user_id', $userId)
            ->update([
                'failed_attempts' => $row->failed_attempts + 1,
                'last_failed_at'  => now(),
                'updated_at'      => now(),
            ]);
    }

    public function isMaxed(string $type, int $userId): bool
    {
        $cfg   = $this->config($type);
        $table = $cfg['table'];
        $max   = $cfg['max'];
        $hours = $cfg['hours'];

        $row = DB::table($table)->where('user_id', $userId)->first();

        if (!$row || $row->failed_attempts < $max) {
            return false;
        }

        $expires = Carbon::parse($row->last_failed_at)->addHours($hours);

        if (now()->greaterThanOrEqualTo($expires)) {
            DB::table($table)
                ->where('user_id', $userId)
                ->update([
                    'failed_attempts' => 0,
                    'last_failed_at'  => null,
                    'updated_at'      => now(),
                ]);
            return false;
        }

        return true;
    }
}