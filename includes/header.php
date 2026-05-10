<?php
require_once dirname(__DIR__) . '/config.php';
$cartCount = getCartCount();

// Fetch categories for nav
$catResult = mysqli_query($conn, "SELECT category_id, category_name FROM Category ORDER BY category_name");
$categories = mysqli_fetch_all($catResult, MYSQLI_ASSOC);

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — ShopVerse' : 'ShopVerse — Premium Online Store' ?></title>
    <meta name="description" content="<?= isset($pageDesc) ? htmlspecialchars($pageDesc) : 'Shop the latest products at ShopVerse — your premium online shopping destination.' ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#f0f4ff',
                            100: '#dbe4ff',
                            200: '#bac8ff',
                            300: '#91a7ff',
                            400: '#748ffc',
                            500: '#5c7cfa',
                            600: '#4c6ef5',
                            700: '#4263eb',
                            800: '#3b5bdb',
                            900: '#364fc7',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-blur { backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
        .card-hover { transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.12); }
        .btn-primary { background: linear-gradient(135deg, #4c6ef5, #3b5bdb); transition: all 0.2s ease; }
        .btn-primary:hover { background: linear-gradient(135deg, #5c7cfa, #4263eb); transform: translateY(-1px); box-shadow: 0 4px 15px rgba(76,110,245,0.4); }
        .gradient-hero { background: linear-gradient(135deg, #1a1c2e 0%, #16213e 50%, #0f3460 100%); }
        .dropdown:hover .dropdown-menu { display: block; }
        .dropdown-menu { display: none; animation: fadeIn 0.15s ease; }
        @keyframes fadeIn { from { opacity:0; transform: translateY(-8px); } to { opacity:1; transform:translateY(0); } }
        .badge-cart { animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{ transform:scale(1); } 50%{ transform:scale(1.1); } }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<!-- ── Sticky Navbar ── -->
<nav class="sticky top-0 z-50 nav-blur bg-white/90 border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Logo -->
            <a href="/BIA PROJECT/index.php" class="flex items-center gap-2 group">
                <div class="w-9 h-9 bg-gradient-to-br from-brand-500 to-brand-800 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-brand-600 to-brand-900 bg-clip-text text-transparent">ShopVerse</span>
            </a>

            <!-- Desktop Nav Links -->
            <div class="hidden md:flex items-center gap-1">
                <a href="/BIA PROJECT/index.php" class="px-3 py-2 rounded-lg text-sm font-medium <?= $currentPage === 'index.php' ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-100' ?> transition">Home</a>

                <!-- Categories Dropdown -->
                <div class="relative dropdown">
                    <button class="px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition flex items-center gap-1">
                        Categories
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="dropdown-menu absolute left-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
                        <a href="/BIA PROJECT/index.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 transition">🛍️ All Products</a>
                        <hr class="my-1 border-gray-100">
                        <?php foreach ($categories as $cat): ?>
                            <a href="/BIA PROJECT/index.php?category=<?= $cat['category_id'] ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 transition">
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Admin link intentionally hidden from navbar -->
            </div>

            <!-- Right Side -->
            <div class="flex items-center gap-3">
                <!-- Search -->
                <form method="GET" action="/BIA PROJECT/index.php" class="hidden md:flex items-center bg-gray-100 rounded-xl px-3 py-1.5 gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                        class="bg-transparent text-sm text-gray-700 outline-none w-40 placeholder-gray-400">
                </form>

                <!-- Cart -->
                <a href="/BIA PROJECT/cart.php" id="cartBtn" class="relative flex items-center gap-2 btn-primary text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Cart
                    <?php if ($cartCount > 0): ?>
                        <span id="cartBadge" class="badge-cart absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                            <?= $cartCount ?>
                        </span>
                    <?php endif; ?>
                </a>

                <!-- Auth -->
                <?php if (isset($_SESSION['customer_id'])): ?>
                    <div class="relative dropdown hidden md:block">
                        <button class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-brand-600 transition px-2 py-1 rounded-lg hover:bg-gray-100">
                            <div class="w-8 h-8 bg-gradient-to-br from-brand-400 to-brand-700 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                <?= strtoupper(substr($_SESSION['customer_name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <?= htmlspecialchars($_SESSION['customer_name'] ?? 'Account') ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
                            <a href="/BIA PROJECT/orders.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700">My Orders</a>
                            <a href="/BIA PROJECT/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/BIA PROJECT/login.php" class="hidden md:inline-flex items-center gap-1 text-sm font-medium text-gray-600 hover:text-brand-600 transition px-3 py-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Flash Message -->
<?php if (isset($_SESSION['flash'])): ?>
    <div id="flashMsg" class="fixed top-20 right-4 z-50 max-w-sm bg-white border <?= $_SESSION['flash']['type'] === 'success' ? 'border-green-300 bg-green-50 text-green-800' : 'border-red-300 bg-red-50 text-red-800' ?> rounded-xl shadow-lg px-5 py-4 flex items-center gap-3 animate-bounce-once">
        <span class="text-xl"><?= $_SESSION['flash']['type'] === 'success' ? '✅' : '❌' ?></span>
        <p class="text-sm font-medium"><?= htmlspecialchars($_SESSION['flash']['msg']) ?></p>
    </div>
    <script>setTimeout(() => { const m = document.getElementById('flashMsg'); if(m) m.style.opacity='0', m.style.transition='opacity 0.5s', setTimeout(()=>m.remove(),500); }, 3000);</script>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<main class="min-h-screen">
