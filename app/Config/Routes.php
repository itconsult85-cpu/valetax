<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth::index');

// Autentikasi
$routes->get('auth', 'Auth::index');
$routes->post('auth/process', 'Auth::process');
$routes->get('auth/logout', 'Auth::logout');

// ==========================================
// RUTE TERLINDUNGI (Wajib Login)
// ==========================================
$routes->get('AdminDashboard', 'AdminDashboard::index', ['filter' => 'authFilter']);
$routes->get('AdminDashboard/botControl/(:segment)', 'AdminDashboard::botControl/$1', ['filter' => 'authFilter']);
$routes->get('AdminDashboard', 'AdminDashboard::index', ['filter' => 'authFilter']);
$routes->post('AdminDashboard/tambahUser', 'AdminDashboard::tambahUser', ['filter' => 'authFilter']);
$routes->get('AdminDashboard/botControl/(:segment)', 'AdminDashboard::botControl/$1', ['filter' => 'authFilter']);
$routes->post('AdminDashboard/gantiPassword', 'AdminDashboard::gantiPassword', ['filter' => 'authFilter']);

// Pengaturan Alur (Flow)
$routes->get('BotSettings', 'BotSettings::index', ['filter' => 'authFilter']);
$routes->post('BotSettings/updateGlobals', 'BotSettings::updateGlobals', ['filter' => 'authFilter']);
$routes->post('BotSettings/saveFlow', 'BotSettings::saveFlow', ['filter' => 'authFilter']);
$routes->get('BotSettings/deleteFlow/(:num)', 'BotSettings::deleteFlow/$1', ['filter' => 'authFilter']);
$routes->get('BotSettings/hapusMedia/(:num)/(:segment)', 'BotSettings::hapusMedia/$1/$2', ['filter' => 'authFilter']);

// Kamus Auto-Respon (FAQ)
$routes->get('BotAutoRespon', 'BotAutoRespon::index', ['filter' => 'authFilter']);
$routes->post('BotAutoRespon/simpan', 'BotAutoRespon::simpan', ['filter' => 'authFilter']);
$routes->post('BotAutoRespon/simpanCerdas', 'BotAutoRespon::simpanCerdas', ['filter' => 'authFilter']);
$routes->get('BotAutoRespon/hapus/(:num)', 'BotAutoRespon::hapus/$1', ['filter' => 'authFilter']);

// Riwayat Chat
$routes->get('ChatHistory', 'ChatHistory::index', ['filter' => 'authFilter']);
$routes->get('ChatHistory/getDetailChat/(:segment)', 'ChatHistory::getDetailChat/$1', ['filter' => 'authFilter']);

// ==========================================
// RUTE UTILITAS
// ==========================================
$routes->get('generatepassword', 'GeneratePassword::index');
