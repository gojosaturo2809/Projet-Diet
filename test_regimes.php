<?php
// Load CodeIgniter config
require_once 'preload.php';
require_once 'vendor/autoload.php';

$config = new \Config\App();
\Config\Services::injectMock('config', $config);

use CodeIgniter\Database\Config as DBConfig;

$db = DBConfig::connect();

// Check existing users
echo "=== Existing Users ===\n";
$users = $db->table('utilisateur')->select('id, nom, email, role, solde')->limit(10)->get()->getResultArray();
foreach ($users as $user) {
    echo sprintf("ID: %d | Email: %s | Role: %s | Solde: %.2f Ar\n", 
        $user['id'], $user['email'], $user['role'], $user['solde']);
}

// Create or update a test user with balance
echo "\n=== Creating Test User ===\n";
$testEmail = 'test@test.com';
$testPassword = 'password123';
$testSolde = 50000; // 50,000 Ar

// Check if user exists
$existing = $db->table('utilisateur')->where('email', $testEmail)->get()->getRow('array');

if ($existing) {
    echo "User exists, updating solde...\n";
    $db->table('utilisateur')->where('email', $testEmail)->update(['solde' => $testSolde]);
    echo "Updated user ID " . $existing['id'] . " with solde: " . $testSolde . " Ar\n";
} else {
    echo "Creating new user...\n";
    $hashedPassword = password_hash($testPassword, PASSWORD_BCRYPT);
    $db->table('utilisateur')->insert([
        'nom' => 'Test User',
        'prenom' => 'Test',
        'email' => $testEmail,
        'mot_de_passe' => $hashedPassword,
        'role' => 'user',
        'solde' => $testSolde
    ]);
    echo "Created test user with email: " . $testEmail . ", password: " . $testPassword . "\n";
    echo "Solde: " . $testSolde . " Ar\n";
}

echo "\n=== Test Complete ===\n";
echo "You can now login with:\n";
echo "Email: " . $testEmail . "\n";
echo "Password: " . $testPassword . "\n";
