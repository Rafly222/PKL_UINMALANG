<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Event;
use App\Models\Presence;

class MigrateDiskominfoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diskominfo:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasi data dari database Diskominfo (kominfopresensi) ke database E-Presensi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Menghubungkan ke database diskominfo (kominfopresensi)...");

        try {
            DB::connection('diskominfo')->getPdo();
        } catch (\Exception $e) {
            $this->error("Gagal terhubung ke database diskominfo: " . $e->getMessage());
            return 1;
        }

        $this->info("Koneksi berhasil. Memulai migrasi data...\n");

        DB::statement("ALTER TABLE presences MODIFY nip VARCHAR(100) NULL");

        DB::beginTransaction();

        try {
            // 1. Migrasi Users
            $this->info("--- 1. Memindahkan Data Users ---");
            $diskominfoUsers = DB::connection('diskominfo')->table('users')->get();
            $adminUser = User::where('role', 'admin')->first();
            $defaultAdminId = $adminUser ? $adminUser->id : 1;

            foreach ($diskominfoUsers as $dUser) {
                $user = User::firstOrCreate(
                    ['email' => $dUser->email],
                    [
                        'name' => $dUser->name,
                        'password' => $dUser->password,
                        'role' => 'admin',
                        'status' => 'approved',
                        'created_at' => $dUser->created_at ?? now(),
                        'updated_at' => $dUser->updated_at ?? now(),
                    ]
                );
                if (!$adminUser) {
                    $adminUser = $user;
                    $defaultAdminId = $user->id;
                }
            }
            $this->info("Selesai memindahkan " . count($diskominfoUsers) . " user.\n");

            // 2. Migrasi Events
            $this->info("--- 2. Memindahkan Data Events ---");
            $diskominfoEvents = DB::connection('diskominfo')->table('event_names')->get();
            $eventCount = 0;

            foreach ($diskominfoEvents as $dEvent) {
                $audienceType = 'semua';
                $accessType = ($dEvent->type == '2') ? 'privat' : 'publik';

                $existingUuid = DB::table('events')->where('id', $dEvent->id)->value('uuid');

                DB::table('events')->updateOrInsert(
                    ['id' => $dEvent->id],
                    [
                        'uuid' => $existingUuid ?? (string) \Illuminate\Support\Str::uuid(),
                        'user_id' => $defaultAdminId,
                        'name' => $dEvent->name,
                        'date' => $dEvent->event_date ?? now()->toDateString(),
                        'time_start' => '08:00',
                        'time_end' => '17:00',
                        'access_type' => $accessType,
                        'password' => ($accessType === 'privat') ? Hash::make('1234') : null,
                        'audience_type' => $audienceType,
                        'fields' => json_encode(['sc-name', 'sc-phone', 'sc-gender', 'sc-institution', 'sc-email', 'sc-photo', 'sc-signature']),
                        'custom_fields' => json_encode([
                            ['label' => 'Jabatan', 'type' => 'text'],
                            ['label' => 'Alamat', 'type' => 'text'],
                            ['label' => 'Keperluan', 'type' => 'text']
                        ]),
                        'created_at' => $dEvent->created_at ?? now(),
                        'updated_at' => $dEvent->updated_at ?? now(),
                    ]
                );
                $eventCount++;
            }
            $this->info("Selesai memindahkan {$eventCount} events.\n");

            // 3. Migrasi Presences
            $this->info("--- 3. Memindahkan Data Presensi (registered_events) ---");
            $diskominfoPresences = DB::connection('diskominfo')->table('registered_events')->get();
            $presenceCount = 0;

            foreach ($diskominfoPresences as $dPresence) {
                // Pastikan event terkait ada
                if (!DB::table('events')->where('id', $dPresence->id_event)->exists()) {
                    continue;
                }

                $gender = ($dPresence->jenis_kelamin == 1) ? 'Laki-Laki' : 'Perempuan';

                $dataPresensi = [
                    'Email' => $dPresence->email,
                    'Jenis Kelamin' => $gender,
                    'WhatsApp' => $dPresence->tlfn,
                ];

                if (!empty($dPresence->jabatan)) {
                    $dataPresensi['Jabatan'] = $dPresence->jabatan;
                }
                if (!empty($dPresence->alamat)) {
                    $dataPresensi['Alamat'] = $dPresence->alamat;
                }
                if (!empty($dPresence->keperluan)) {
                    $dataPresensi['Keperluan'] = $dPresence->keperluan;
                }

                $photoPath = !empty($dPresence->pictures) ? 'presences/photos/' . $dPresence->pictures : null;
                $signaturePath = !empty($dPresence->signature) ? 'presences/signatures/' . $dPresence->signature : null;

                $existingUuid = DB::table('presences')->where('id', $dPresence->id)->value('uuid');

                DB::table('presences')->updateOrInsert(
                    ['id' => $dPresence->id],
                    [
                        'uuid' => $existingUuid ?? (string) \Illuminate\Support\Str::uuid(),
                        'event_id' => $dPresence->id_event,
                        'name' => $dPresence->name,
                        'institution' => $dPresence->instansi ?? '-',
                        'phone' => $dPresence->tlfn,
                        'nip' => !empty($dPresence->nip) ? substr($dPresence->nip, 0, 100) : null,
                        'photo' => $photoPath,
                        'signature' => $signaturePath,
                        'data_presensi' => json_encode($dataPresensi),
                        'created_at' => $dPresence->created_at ?? now(),
                        'updated_at' => $dPresence->updated_at ?? now(),
                    ]
                );
                $presenceCount++;
            }

            DB::commit();

            $this->info("Selesai memindahkan {$presenceCount} presences.\n");
            $this->info("SUCCESS: SELURUH DATA DISKOMINFO BERHASIL DIIMPOR!");
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Terjadi kesalahan saat memindahkan data: " . $e->getMessage());
            return 1;
        }
    }
}
