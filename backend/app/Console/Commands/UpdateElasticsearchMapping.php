<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Search\ElasticsearchService;
use Illuminate\Support\Facades\Log;

class UpdateElasticsearchMapping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:update-mapping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Elasticsearch mapping';

    /**
     * Execute the console command.
     */
    public function handle(ElasticsearchService $elasticsearchService)
    {
        $this->info('Updating Elasticsearch mapping...');

        if($elasticsearchService->updateMapping()){
            $this->info('Elasticsearch mapping updated successfully');
        }else{
            $this->error('Elasticsearch mapping update failed');
        }
    }
}
