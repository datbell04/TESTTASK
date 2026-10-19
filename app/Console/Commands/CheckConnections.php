<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckConnections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-connections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Print the connection status of MySQL and MongoDB';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $mysqlOk = $this->checkMysql();
        $mongoOk = $this->checkMongo();

        return $mysqlOk && $mongoOk ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Ping the MySQL connection used by relational data.
     */
    private function checkMysql(): bool
    {
        try {
            DB::connection('mysql')->getPdo();
            $this->info('MySQL: OK');

            return true;
        } catch (Throwable $exception) {
            $this->error('MySQL: FAIL - '.$exception->getMessage());

            return false;
        }
    }

    /**
     * Ping the MongoDB connection used by submission documents.
     */
    private function checkMongo(): bool
    {
        try {
            DB::connection('mongodb')->getDatabase()->command(['ping' => 1])->toArray();
            $this->info('MongoDB: OK');

            return true;
        } catch (Throwable $exception) {
            $this->error('MongoDB: FAIL - '.$exception->getMessage());

            return false;
        }
    }
}
