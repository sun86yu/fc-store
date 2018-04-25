<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class DBCleaner extends Command
{
    protected $signature = 'db:clean {table}';

    protected $description = 'Clean DB-Table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Do something
        Schema::dropIfExists($this->argument('table'));
    }
}
