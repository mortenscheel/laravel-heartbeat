<?php

namespace MortenScheel\Heartbeat\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use MortenScheel\Heartbeat\Heartbeat;
use MortenScheel\Heartbeat\HeartbeatMonitor;

class MonitorHeartbeatCommand extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'heartbeat:monitor {--no-dispatch : Do not dispatch heartbeat jobs}
                                              {--no-warn     : Do not send e-mail warnings}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Dispatch and monitor queue heartbeats';

    /**
     * Execute the console command.
     * @return int
     */
    public function handle(HeartbeatMonitor $monitor)
    {
        if (!$monitor->isEnabled()) {
            $this->warn('Heartbeat is not enabled');
            return 1;
        }
        if (!$this->option('no-dispatch')) {
            $monitor->dispatchHeartbeatJobs();
        }
        $heartbeats = $monitor->getLatestHeartbeats();
        $this->renderHeartbeatsTable($heartbeats);
        $unhealthy = $heartbeats->filter(function (Heartbeat $heartbeat) {
            return !$heartbeat->isHealthy();
        });
        if ($unhealthy->isNotEmpty() && !$this->option('no-warn')) {
            $monitor->warnRecipients($unhealthy);
        }
        return 0;
    }

    private function renderHeartbeatsTable(Collection $heartbeats): void
    {
        $headers = ['Queue', 'Last heartbeat', 'Max allowed'];
        $rows = $heartbeats->map(function (Heartbeat $heartbeat) {
            return [
                $heartbeat->getQueue(),
                sprintf(
                    '<fg=%s>%s</>',
                    $heartbeat->isHealthy() ? 'green' : 'red',
                    $heartbeat->diffForHumans(),
                ),
                $heartbeat->maxTimeForHumans()
            ];
        });
        $this->table($headers, $rows);
    }
}
