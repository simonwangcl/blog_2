<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\UserModel;

class UpdateUserImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:pictures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up avatar and cover pictures';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

    }
}