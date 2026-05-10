<?php
require_once 'config.php';
if (isset($_SESSION['customer_id'])) { header('Location: /BIA PROJECT/index.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($conn, $_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    if (!$email || !$pass) { $errors[] = 'Email and password are required.'; }
    else {
        $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT customer_id,first_name,last_name,password_hash FROM Customer WHERE email='$email' LIMIT 1"));
        if ($row && password_verify($pass, $row['password_hash'])) {
            $_SESSION['customer_id']    = $row['customer_id'];
            $_SESSION['customer_name']  = $row['first_name'] . ' ' . $row['last_name'];
            $_SESSION['customer_email'] = $email;
            $_SESSION['customer_fname'] = $row['first_name'];
            $_SESSION['flash'] = ['type'=>'success','msg'=>'Welcome back, ' . $row['first_name'] . '!'];
            header('Location: /BIA PROJECT/index.php'); exit;
        } else { $errors[] = 'Invalid email or password.'; }
    }
}
$pageTitle = 'Login';
require_once 'includes/header.php';
?>
<div class="min-h-screen flex items-center justify-center px-4 py-16 gradient-hero">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-gradient-to-br from-brand-500 to-brand-800 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900">Welcome Back</h1>
            <p class="text-gray-500 text-sm mt-1">Sign in to your ShopVerse account</p>
        </div>
        <?php if ($errors): ?>
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-5 text-sm text-red-700">
                <?php foreach ($errors as $e): ?><p>• <?= htmlspecialchars($e) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
            </div>
            <button type="submit" class="w-full btn-primary text-white py-3.5 rounded-xl font-bold text-base shadow-lg mt-2">
                Sign In
            </button>
        </form>
        <p class="text-center text-sm text-gray-500 mt-6">
            Don't have an account? <a href="/BIA PROJECT/register.php" class="text-brand-600 font-semibold hover:underline">Register</a>
        </p>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
