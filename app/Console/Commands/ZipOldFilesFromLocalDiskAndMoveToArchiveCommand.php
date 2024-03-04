<?php

namespace App\Console\Commands;

use App\Actions\TestSsl\ZipOldScanFilesAndMoveToArchiveAction;
use Exception;
use Illuminate\Console\Command;
use Log;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ZipOldFilesFromLocalDiskAndMoveToArchiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tlsscan:archiveOldFiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Zip old files from local disk and move to archive';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle(): int
    {
        $this->info('Started the command - ' . $this->signature);
        Log::info('Started the command - ' . $this->signature);
        if(ZipOldScanFilesAndMoveToArchiveAction::run()){
            $this->info('Finished the command - ' . $this->signature);
            Log::info('Finished the command - ' . $this->signature);
            return CommandAlias::SUCCESS;
        } else {
            $this->error('Error in the command - ' . $this->signature);
            Log::info('Error in the command - ' . $this->signature);
            return CommandAlias::FAILURE;
        }
    }
}
