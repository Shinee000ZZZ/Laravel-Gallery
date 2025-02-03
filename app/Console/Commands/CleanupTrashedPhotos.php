<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Photos;
use Illuminate\Support\Facades\Storage;

class CleanupTrashedPhotos extends Command
{
    protected $signature = 'photos:cleanup';
    protected $description = 'Permanently delete photos that have been in trash for more than 7 days';

    public function handle()
    {
        $oldTrashedPhotos = Photos::onlyTrashed()
            ->where('deleted_at', '<=', now()->subDays(7))
            ->get();

        foreach ($oldTrashedPhotos as $photo) {
            // Hapus file foto dari storage
            if ($photo->image_path && Storage::exists($photo->image_path)) {
                Storage::delete($photo->image_path);
            }

            // Hapus permanen foto
            $photo->forceDelete();
        }

        $this->info('Cleaned up ' . $oldTrashedPhotos->count() . ' photos from trash.');
    }
}
