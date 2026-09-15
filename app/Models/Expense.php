<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Expense extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'expense_category_id',
        'amount',
        'description',
        'expense_date',
        'payment_method',
        'reference_number',
        'attachment_path',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    /**
     * Disk where new attachments are stored. Prefers S3/R2 when credentials
     * and bucket are configured, otherwise falls back to the local disk.
     */
    public static function attachmentDisk(): string
    {
        $s3 = config('filesystems.disks.s3');

        return ! empty($s3['key']) && ! empty($s3['bucket']) ? 's3' : 'public';
    }

    /**
     * Disks to look into when reading an attachment. The configured disk goes
     * first, then the local disk so legacy records keep working.
     *
     * @return array<int, string>
     */
    public static function attachmentDisks(): array
    {
        return self::attachmentDisk() === 's3' ? ['s3', 'public'] : ['public'];
    }

    /**
     * Resolve the storage-relative path from the stored value, supporting both
     * the new relative paths and legacy absolute URLs.
     */
    public function attachmentRelativePath(): ?string
    {
        $value = $this->attachment_path;

        if (! $value) {
            return null;
        }

        if (! str_starts_with($value, 'http')) {
            return ltrim($value, '/');
        }

        $path = ltrim((string) parse_url($value, PHP_URL_PATH), '/');

        return str_starts_with($path, 'storage/') ? substr($path, 8) : $path;
    }

    public function attachmentFilename(): string
    {
        return basename((string) $this->attachmentRelativePath()) ?: 'adjunto';
    }

    public function deleteAttachment(): void
    {
        $path = $this->attachmentRelativePath();

        if (! $path) {
            return;
        }

        foreach (self::attachmentDisks() as $disk) {
            try {
                $storage = Storage::disk($disk);

                if ($storage->exists($path)) {
                    $storage->delete($path);
                }
            } catch (\Throwable $e) {
                Log::warning("No se pudo eliminar el adjunto del egreso {$this->id} en el disco {$disk}: ".$e->getMessage());
            }
        }
    }
}
