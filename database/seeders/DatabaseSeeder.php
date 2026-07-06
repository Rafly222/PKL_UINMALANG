<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Event;
use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed database dengan event awal & data kehadiran bohongan untuk demo aplikasi PKL.
     */
    public function run(): void
    {
        // 1. Buat Event PKL UIN Malang
        $eventUin = Event::create([
            'nama_event' => 'Presensi Kehadiran Praktikan UIN Malang',
            'tanggal_event' => Carbon::now()->format('Y-m-d'),
            'target_peserta' => 15,
            'status' => 'aktif'
        ]);

        // 2. Buat Event Rapat Diskominfo
        $eventRapat = Event::create([
            'nama_event' => 'Rapat Koordinasi Infrastruktur Smart City',
            'tanggal_event' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'target_peserta' => 30,
            'status' => 'aktif'
        ]);

        // String PNG base64 mockup untuk TTD (Coretan garis miring sederhana)
        $mockTtd = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKAAAABACAYAAACG4F30AAAALklEQVR4Xu3BAQ0AAADCoPdPbQ8HFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAPgbeAABpd8E8AAAAABJRU5ErkJggg==';

        // String JPEG base64 mockup untuk Foto (Sketsa siluet wajah abu-abu)
        $mockFoto = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAG1pVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQwIDc5LjE1NzQ0NywgMjAxNS8wOS8xMC0xMToxNjoyMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc0NvbW9uL3JlZmVyZW5jZS8iIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOjdiNmVkY2Q5LTI3ZGMtNGYyNi1iYTY3LWRmNGJkMzViNjM4NyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDozOEFBMUE3NTMzRjMxMUU2QUE3NURBNzA0ODlFMDVCOSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDozOEFBMUE3NDMzRjMxMUU2QUE3NURBNzA0ODlFMDVCOSIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxNSAoV2luZG93cykiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3YjZlZGNkOS0yN2RjLTRmMjYtYmE2Ny1kZjRiZDM1YjYzODciIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6N2I2ZWRjZDktMjdkYy00ZjI2LWJhNjctZGY0YmQzNWI2Mzg3Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+T7FmBgAAA69JREFUeNrsnEtvE0EQhXfX7toJ4iMhIAQSkBDfEAgJiT8B4p8DAn8CIsSBAwkE8S/YIsKHAAGJuC8p8SFAgECIEMR6LfZ6U93OunEcl7v2mN04W6fVInG2u9vdU9Xf6p6Z8Vp9v02YfWzsc2OfGgffD8aBg3mQp66I09mO8X2V7A78/6O8f4Y/6A45CArY/vI8I+BvW8ArZOf59hH8/Qh4gewbsoesI6CArLq/vMyVCHiO7BWyV6pCHvD8pU8K6AnZfbyY6WREPCvIsmXW0S9kr6rK6ArZg/gYCHXpXSFvLpG9R8m+QvaNqlK6ZORU6S8Zf6M6vUv2N7KPyP4h+y8U0O0yfq36f0L+B7K/kf1N9u/or50WfXg9K6V3M69/V6n07vbyO7yem8rvfUv5XUfS+/F/K/78hXzeVz9/I5//fS8XfIbyf9/jRTrXlfO95/m6Yj8reV7WldM1K6evv9M7W8rvmJX+K/HnreT76uX3vP6Hle/rWeXvSfnvPHzfR/PclPL7n+X7fWz+vYvv+9Wc9tK7W9R/78tUfPfnrZTej/9b8ecv5PO++vkb+fztO6mZbeP76uXzG7L2+fTidS+T/ZvsY7Jv+Tz1XmP7KbyYnUvGf0IeWf+Gz7PlD+w1fWAtF68/gY973W7yXorXnyD7UfV+Z7n2X/o8fOAnT23p0g1gAAMIQAADCEAAAxjAAAYwgAAEMIAADGAAG8AABjCAAQQwgAEMYAADCEAAAhjAAAYwgAFsAAMYQAACGEAAAhjAAAYwgAEMYPC/A6ZpGu7pW2P/XhEexb6V8Wv36bWzVfscYm7999gO2S6RPRD/rG9VZZV9X9eS8TtkS9V76+T6iH1f28bV++v9676tZbeXz9+Qz++v+76U/N6U/H6nclH3fe1Y969jL/V9fSrv21b6X7byu9vL727963VlH/V9bRv+Yq3svvVv1pXzZfP12v6W7T/Wff3G+iE+mR1b4WNs/GPs80W8/86zZfZ5Ffs59nkae8W+p7gCHXf6Cgq4xP69Ijxei7X7rE8D7b366wB6z4ADWEMGcKevoIDH6f7q18Bjr6+AwGfXwON0f/Fr6f5qrwMMQAADGMAABjCAAQxgAAMIQAADGMAAAL8H+u9r7H8bCMAAO5+W9H1Ibe8AAAAASUVORK5CYII=';

        // 3. Masukkan 3 presensi pegawai & tamu ke event PKL UIN Malang
        Presence::create([
            'event_id' => $eventUin->id,
            'kategori_peserta' => 'pegawai',
            'nama_lengkap' => 'Budi Santoso, S.Kom',
            'nip' => '198503152010121002',
            'instansi' => 'Diskominfo Kota Malang - Bidang Aplikasi Informatika',
            'no_wa' => '081234567890',
            'foto_capture' => $mockFoto,
            'tanda_tangan' => $mockTtd,
            'waktu_absensi' => Carbon::now()->setHour(7)->setMinute(35)
        ]);

        Presence::create([
            'event_id' => $eventUin->id,
            'kategori_peserta' => 'pegawai',
            'nama_lengkap' => 'Siti Aminah, M.T',
            'nip' => '199008242015032005',
            'instansi' => 'Diskominfo Kota Malang - Bidang Persandian & Statistik',
            'no_wa' => '085799887766',
            'foto_capture' => $mockFoto,
            'tanda_tangan' => $mockTtd,
            'waktu_absensi' => Carbon::now()->setHour(8)->setMinute(10)
        ]);

        Presence::create([
            'event_id' => $eventUin->id,
            'kategori_peserta' => 'tamu',
            'nama_lengkap' => 'Ahmad Fauzi',
            'nip' => null,
            'instansi' => 'UIN Maulana Malik Ibrahim Malang',
            'no_wa' => '089988776655',
            'foto_capture' => $mockFoto,
            'tanda_tangan' => $mockTtd,
            'waktu_absensi' => Carbon::now()->setHour(9)->setMinute(45)
        ]);

        Presence::create([
            'event_id' => $eventUin->id,
            'kategori_peserta' => 'tamu',
            'nama_lengkap' => 'Riska Amelia',
            'nip' => null,
            'instansi' => 'Politeknik Negeri Malang',
            'no_wa' => '081122334455',
            'foto_capture' => $mockFoto,
            'tanda_tangan' => $mockTtd,
            'waktu_absensi' => Carbon::now()->setHour(10)->setMinute(15)
        ]);

        // User Admin Contoh
        if (!User::where('email','admin@gmail.com')->exists()) {
            User::create([
                'name' => 'Moch Rafly Ramadhani A',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
            ]);
        }
    }
}