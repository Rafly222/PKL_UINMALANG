<?php

namespace App\Services;

class EmployeeService
{
    protected static array $employeeDb = [
        "198503152010121002" => [
            "name" => "Siti Aminah, M.T",
            "institution" => "Dinas Komunikasi dan Informatika",
            "phone" => "081255566778"
        ],
        "199508242018031005" => [
            "name" => "Rafly Pratama",
            "institution" => "Diskominfo - Infrastruktur TIK",
            "phone" => "081299998888"
        ],
        "197812052005011001" => [
            "name" => "Ir. Hermawan Adi, M.M",
            "institution" => "Pemerintah Kota Malang - Sekdin",
            "phone" => "081344445555"
        ]
    ];

    public static function findByNip(string $nip): ?array
    {
        return static::$employeeDb[$nip] ?? null;
    }

    public static function resolveInstitution(string $participantType, ?string $nip, ?string $inputInstitution): string
    {
        if ($inputInstitution) {
            return $inputInstitution;
        }

        if ($participantType === 'pegawai') {
            if ($nip && isset(static::$employeeDb[$nip])) {
                return static::$employeeDb[$nip]['institution'];
            }
            return 'Pemerintah Kota Malang';
        }

        return 'Masyarakat Umum';
    }
}
