<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #000000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        REKAPITULASI PRESENSI KEHADIRAN<br>
        Kegiatan: {{ $event->name }}<br>
        Tanggal: {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}<br>
        Waktu: {{ \Carbon\Carbon::parse($event->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->time_end)->format('H:i') }} WIB
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>NIP</th>
                <th>Nama Lengkap</th>
                <th>Nomor WhatsApp</th>
                <th>Instansi</th>
                <th>Kategori Peserta</th>
                <th>Waktu Presensi</th>
                <th>Foto Wajah</th>
                <th>Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presences as $index => $presence)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>'{{ $presence->nik }}</td>
                    <td>'{{ $presence->nip ?? '-' }}</td>
                    <td>{{ $presence->name }}</td>
                    <td>{{ $presence->phone ?? '-' }}</td>
                    <td>{{ $presence->institution }}</td>
                    <td>{{ $presence->nip ? 'Pegawai Pemerintah' : 'Masyarakat Umum' }}</td>
                    <td>{{ $presence->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i:s') }} WIB</td>
                    <td style="text-align: center; vertical-align: middle;">
                        @if($presence->photo)
                            <a href="{{ route('presence.photo', $presence->id) }}" target="_blank">Lihat Foto</a>
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        @if($presence->signature)
                            <a href="{{ route('presence.signature', $presence->id) }}" target="_blank">Lihat TTD</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
