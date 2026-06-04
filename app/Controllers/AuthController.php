<?php

namespace App\Controllers;

use App\Models\User;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;

class AuthController
{
    private Logger $logger; // <-- tambah type hint

    public function __construct()
    {
        $this->logger = new Logger('auth');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/app.log', Logger::INFO));
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function showLogin(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole($_SESSION['role']);
            exit;
        }
        include __DIR__ . '/../../views/auth/login.php';
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::where('email', $email)->first();

        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['name'] = $user->name;
            $_SESSION['role'] = $user->role;
            $_SESSION['email'] = $user->email;

            // Log aktivitas login
            logActivity('Login', "User {$user->email} berhasil login");

            $this->logger->info("Login berhasil", ['email' => $email, 'role' => $user->role]);
            $this->redirectBasedOnRole($user->role);
        } else {
            $this->logger->warning("Login gagal", ['email' => $email]);
            $_SESSION['error'] = 'Email atau password salah.';
            header('Location: index.php?action=login');
        }
        exit;
    }

    public function logout(): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        $email = $_SESSION['email'] ?? 'unknown';
        
        // Log aktivitas logout
        if ($userId) {
            logActivity('Logout', "User {$email} logout");
        }
        
        $this->logger->info("Logout", ['user_id' => $userId]);
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }

    public function forgot(): void
    {
        include __DIR__ . '/../../views/auth/forgot.php';
    }

    public function sendResetLink(): void
    {
        $email = $_POST['email'] ?? '';
        $user = User::where('email', $email)->first();
        if (!$user) {
            $_SESSION['error'] = 'Email tidak terdaftar.';
            header('Location: index.php?action=forgot');
            exit;
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['reset_token'] = $token;
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_expires'] = Carbon::now()->addHour()->timestamp;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'];
            $mail->Password   = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
            $mail->Port       = $_ENV['MAIL_PORT'];
            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password Hafalan Tracker';
            $resetLink = $_ENV['APP_URL'] . "/index.php?action=reset&token=" . $token;
            $mail->Body    = "Klik link berikut untuk reset password: <a href='$resetLink'>$resetLink</a>";
            $mail->send();
            
            // Log permintaan reset
            logActivity('Lupa Password', "User {$email} meminta reset password");
            
            $_SESSION['success'] = 'Link reset telah dikirim ke email Anda.';
        } catch (Exception $e) {
            $this->logger->error("Email gagal dikirim", ['error' => $mail->ErrorInfo]);
            $_SESSION['error'] = 'Gagal mengirim email. Coba lagi.';
        }
        header('Location: index.php?action=login');
        exit;
    }

    private function redirectBasedOnRole(string $role): void
    {
        switch ($role) {
            case 'admin': header('Location: index.php?action=admin/dashboard'); break;
            case 'ustadz': header('Location: index.php?action=ustadz/dashboard'); break;
            case 'orangtua': header('Location: index.php?action=orangtua/dashboard'); break;
            default: header('Location: index.php?action=login');
        }
        exit;
    }
}