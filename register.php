<?php
require_once 'config.php';
if (isset($_SESSION['customer_id'])) { header('Location: /BIA PROJECT/index.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = sanitize($conn, $_POST['first_name'] ?? '');
    $last  = sanitize($conn, $_POST['last_name']  ?? '');
    $email = sanitize($conn, $_POST['email']       ?? '');
    $phone = sanitize($conn, $_POST['phone']       ?? '');
    $pass  = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    if (!$first || !$email || !$pass) $errors[] = 'First name, email and password are required.';
    if ($pass !== $pass2) $errors[] = 'Passwords do not match.';
    if (strlen($pass) < 6)  $errors[] = 'Password must be at least 6 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';

    if (empty($errors)) {
        $exists = mysqli_fetch_assoc(mysqli_query($conn, "SELECT customer_id FROM Customer WHERE email='$email'"));
        if ($exists) { $errors[] = 'An account with this email already exists.'; }
        else {
            $nextId   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(MAX(customer_id),0)+1 AS n FROM Customer"))['n'];
            $passHash = password_hash($pass, PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO Customer (customer_id,first_name,last_name,email,password_hash,mobile_number,total_spent)
                VALUES ($nextId,'$first','$last','$email','$passHash','$phone',0)");
            $_SESSION['customer_id']    = $nextId;
            $_SESSION['customer_name']  = "$first $last";
            $_SESSION['customer_email'] = $email;
            $_SESSION['customer_fname'] = $first;
            $_SESSION['flash'] = ['type'=>'success','msg'=>"Account created! Welcome, $first!"];
            header('Location: /BIA PROJECT/index.php'); exit;
        }
    }
}
$pageTitle = 'Register';
require_once 'includes/header.php';
?>
<div class="min-h-screen flex items-center justify-center px-4 py-16 gradient-hero">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-gradient-to-br from-brand-500 to-brand-800 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900">Create Account</h1>
            <p class="text-gray-500 text-sm mt-1">Join ShopVerse and start shopping</p>
        </div>
        <?php if ($errors): ?>
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-5 text-sm text-red-700">
                <?php foreach ($errors as $e): ?><p>• <?= htmlspecialchars($e) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                    <input type="text" name="first_name" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                <input type="tel" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password * (min 6 chars)</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                <input type="password" name="password2" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            </div>
            <button type="submit" class="w-full btn-primary text-white py-3.5 rounded-xl font-bold text-base shadow-lg mt-2">
                Create Account
            </button>
        </form>
        <p class="text-center text-sm text-gray-500 mt-6">
            Already have an account? <a href="/BIA PROJECT/login.php" class="text-brand-600 font-semibold hover:underline">Login</a>
        </p>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
