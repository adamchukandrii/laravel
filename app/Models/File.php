<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const NAME = 'name';
    public const PATH = 'path';
    public const SIZE = 'size';

    /**
     * @var list<string>
     */
    protected $fillable = [self::NAME, self::PATH, self::SIZE];
}
