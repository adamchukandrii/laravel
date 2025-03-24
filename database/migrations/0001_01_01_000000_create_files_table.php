<?php

declare(strict_types=1);

use App\Models\File;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public const TABLE_NAME = 'files';

    public function up(): void
    {
        Schema::create(
            self::TABLE_NAME,
            static function (Blueprint $table) {
                $table->id();
                $table->string(File::NAME);
                $table->string(File::PATH);
                $table->integer(File::SIZE);
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
