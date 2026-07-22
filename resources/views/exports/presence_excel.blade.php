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
            vertical-align: middle;
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
                @if($event->audience_type === 'pegawai' || $event->audience_type === 'semua')
                    <th>NIP</th>
                @endif
                <th>Nama Lengkap</th>
                
                {{-- Dynamic Semi-Custom Columns --}}
                @if(in_array('sc-phone', $event->fields ?? []))
                    <th>Nomor WhatsApp</th>
                @endif
                @if(in_array('sc-gender', $event->fields ?? []))
                    <th>Jenis Kelamin</th>
                @endif
                <th>Instansi</th>
                @if(in_array('sc-email', $event->fields ?? []))
                    <th>Email</th>
                @endif

                {{-- Dynamic Custom Columns --}}
                @if($event->custom_fields && count($event->custom_fields) > 0)
                    @foreach($event->custom_fields as $cf)
                        <th>{{ $cf['label'] }}</th>
                    @endforeach
                @endif

                @if($event->audience_type === 'semua')
                    <th>Kategori Peserta</th>
                @endif



                @if(in_array('sc-photo', $event->fields ?? []))
                    <th style="width: 80px; text-align: center;">Foto Wajah</th>
                @endif
                @if(in_array('sc-signature', $event->fields ?? []))
                    <th style="width: 120px; text-align: center;">Tanda Tangan</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($presences as $index => $presence)
                <tr style="height: 120px;">
                    <td>{{ $index + 1 }}</td>
                    @if($event->audience_type === 'pegawai' || $event->audience_type === 'semua')
                        <td style="mso-number-format:'\@';">{{ $presence->nip ?? '-' }}</td>
                    @endif
                    <td>{{ $presence->name }}</td>
                    
                    {{-- WhatsApp --}}
                    @if(in_array('sc-phone', $event->fields ?? []))
                        <td style="mso-number-format:'\@';">{{ $presence->phone ?? '-' }}</td>
                    @endif
                    
                    {{-- Gender --}}
                    @if(in_array('sc-gender', $event->fields ?? []))
                        <td>{{ $presence->data_presensi['Jenis Kelamin'] ?? '-' }}</td>
                    @endif
                    
                    <td>{{ $presence->institution }}</td>
                    
                    {{-- Email --}}
                    @if(in_array('sc-email', $event->fields ?? []))
                        <td>{{ $presence->data_presensi['Email'] ?? '-' }}</td>
                    @endif

                    {{-- Custom Fields --}}
                    @if($event->custom_fields && count($event->custom_fields) > 0)
                        @foreach($event->custom_fields as $cf)
                            <td>{{ $presence->data_presensi[$cf['label']] ?? '-' }}</td>
                        @endforeach
                    @endif

                    @if($event->audience_type === 'semua')
                        <td>{{ $presence->nip ? 'Pegawai Pemerintah' : 'Masyarakat Umum' }}</td>
                    @endif



                    {{-- Photo --}}
                    @if(in_array('sc-photo', $event->fields ?? []))
                        <td style="width: 80px; text-align: center; vertical-align: middle;">
                            @if($presence->photo)
                                <img src="{{ route('presence.photo', $presence->id) }}" width="60" height="80">
                            @else
                                -
                            @endif
                        </td>
                    @endif

                    {{-- Signature --}}
                    @if(in_array('sc-signature', $event->fields ?? []))
                        <td style="width: 120px; text-align: center; vertical-align: middle;">
                            @if($presence->signature)
                                <img src="{{ route('presence.signature', $presence->id) }}" width="100" height="50">
                            @else
                                -
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
