<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel,WithHeadingRow,WithChunkReading,WithBatchInserts,ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            // Update if existing or create a new user
            User::updateOrCreate(
                [
                    'email' => $row['email'], // Unique key to avoid duplicates
                ],
                [
                    'name' => $row['name'],
                    'password' => Hash::make($row['password']),
                ]
            );
        } catch (\Exception $e) {
            \log::error('Error al importar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Chunk size for processing data in fragments
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 300;
    }

    /**
     * * Lot size for bulk inserts.
     *
     * @return int
     */
    public function batchSize(): int
    {
        return 6000;
    }

}
