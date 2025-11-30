<?php

/**
 * Report Access Configuration
 * 
 * Mapping report routes dengan role yang memiliki akses
 * Format: 'report_route' => ['role_title_1', 'role_title_2', ...]
 * 
 * Jika role belum ada di database, report akan tetap bisa diakses
 * untuk menghindari error saat role diisi bertahap.
 * 
 * Untuk memberikan akses ke semua role, gunakan ['*']
 * Untuk memberikan akses hanya ke admin, gunakan ['admin']
 */

return [
    // Monthly Report - bisa diakses semua role (atau sesuaikan dengan kebutuhan)
    'monthly' => ['*'],
    
    // BI Report - bisa diakses semua role (atau sesuaikan dengan kebutuhan)
    'bi' => ['*'],
    
    // SPDR Report - hanya untuk role SPDR Estimating
    'spdr' => ['SPDR Estimating'],
    
    // TMA Report - hanya untuk role TMA Estimating
    'tma' => ['TMA Estimating'],
    
    // Category Report - bisa diakses semua role (atau sesuaikan dengan kebutuhan)
    'category' => ['*'],
    
    // Tambahkan report baru di sini
    // 'new_report' => ['Role 1', 'Role 2'],
    
    // Catatan: Jika report menggunakan ['*'], semua role termasuk "TMA Estimating" bisa akses
    // Jika ingin membatasi, tambahkan role spesifik, contoh:
    // 'monthly' => ['SPDR Estimating', 'TMA Estimating', 'BI Estimating'],
];

