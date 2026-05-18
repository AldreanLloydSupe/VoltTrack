<?php

namespace App\Console\Commands;

use App\Models\Meter;
use App\Models\Property;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Handles PruneDeletedRecords responsibilities.
 */
class PruneDeletedRecords extends Command
{
    protected $signature = 'deleted-records:prune {--days=30 : Number of days to keep deleted records}';

    protected $description = 'Permanently delete resident and property records after the deleted-record retention period.';

    /**
     * Handle.
     */
    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));
        $cutoff = now()->subDays($days);

        $deletedProperties = Property::onlyTrashed()
            ->with('meters:id')
            ->where('deleted_at', '<=', $cutoff)
            ->get();

        $deletedResidents = User::onlyTrashed()
            ->where('role', 'renter')
            ->where('deleted_at', '<=', $cutoff)
            ->get();

        DB::transaction(function () use ($deletedProperties, $deletedResidents) {
            $deletedProperties->each(function (Property $property) {
                $meterIds = $property->meters->pluck('id');

                if ($meterIds->isNotEmpty()) {
                    Meter::query()->whereKey($meterIds)->delete();
                }

                $property->meters()->detach();
                $property->forceDelete();
            });

            $deletedResidents->each->forceDelete();
        });

        $this->info(sprintf(
            'Permanently deleted %d resident(s) and %d property record(s).',
            $deletedResidents->count(),
            $deletedProperties->count()
        ));

        return self::SUCCESS;
    }
}
