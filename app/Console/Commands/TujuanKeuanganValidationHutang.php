<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TujuanKeuangan;
use App\Models\Hutang;
use Auth;
use DB;

class TujuanKeuanganValidationHutang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tujuankeuangan:hutang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validation hutang in tujuan keuangan {--id=}';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}
