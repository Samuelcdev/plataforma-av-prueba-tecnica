<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperativesSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Daniel Moreno', 'Paula Cardenas', 'Santiago Ruiz', 'Laura Quintero', 'Miguel Forero',
            'Camila Parra', 'Jhonatan Salazar', 'Valentina Rojas', 'Andres Mejia', 'Nicolas Cifuentes',
            'Juliana Castro', 'Sebastian Rios', 'Catalina Naranjo', 'Felipe Acevedo', 'Tatiana Galindo',
            'Diego Pineda', 'Luisa Bernal', 'Kevin Mendoza', 'Manuela Ospina', 'Esteban Cardona',
            'Natalia Herrera', 'Javier Lopez', 'Diana Aguirre', 'Juan Camilo Torres', 'Erika Duarte',
            'Alejandro Salas', 'Martha Pardo', 'Cristian Suarez', 'Sofia Arboleda', 'David Lemus',
            'Maria Camila Arias', 'Yeferson Marin', 'Angie Lozano', 'Brayan Cuellar', 'Lorena Castano',
        ];

        $documentTypes = ['CC', 'CC', 'CC', 'CE', 'TI'];
        $rows = [];

        foreach ($names as $index => $name) {
            $i = $index + 1;

            $rows[] = [
                'id' => sprintf('50000000-0000-0000-0000-%012d', $i),
                'document_type' => $documentTypes[$index % count($documentTypes)],
                'document' => sprintf('7007%06d', $i),
                'name' => $name,
                'is_active' => $i % 9 !== 0,
            ];
        }

        DB::table('operatives')->insert($rows);
    }
}
