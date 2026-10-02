<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BackupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_artisan_db_backup_creates_backup_file()
    {
        $exitCode = Artisan::call('db:backup');
        $this->assertEquals(0, $exitCode);

        $backupDir = storage_path('app/backups');
        $this->assertDirectoryExists($backupDir);
    }

    /** @test */
    public function test_backup_web_interface_is_accessible()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('backup.index'));
        $response->assertStatus(200);
        $response->assertSee('BACKUP DATABASE OTOMATIS');
    }
}
