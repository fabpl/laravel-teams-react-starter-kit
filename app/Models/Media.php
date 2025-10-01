<?php

declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

/**
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property array<array-key, mixed> $manipulations
 * @property array<array-key, mixed> $custom_properties
 * @property array<array-key, mixed> $generated_conversions
 * @property array<array-key, mixed> $responsive_images
 * @property int|null $order_column
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read mixed $extension
 * @property-read mixed $human_readable_size
 * @property-read \Illuminate\Database\Eloquent\Model|Eloquent $model
 * @property-read mixed $original_url
 * @property-read mixed $preview_url
 * @property-read mixed $type
 */
final class Media extends BaseMedia {}
