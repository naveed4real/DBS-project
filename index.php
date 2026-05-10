<?php
$pageTitle = 'Shop All Products';
$pageDesc  = 'Browse our wide range of quality products across all categories.';
require_once 'config.php';

// ── Filters ──────────────────────────────────────────────────────────────────
$where   = [];
$params  = [];
$types   = '';

if (!empty($_GET['search'])) {
    $s = '%' . sanitize($conn, $_GET['search']) . '%';
    $where[] = "(p.product_name LIKE ? OR p.description LIKE ?)";
    $params[] = $s; $params[] = $s;
    $types   .= 'ss';
}

if (!empty($_GET['category']) && is_numeric($_GET['category'])) {
    $where[] = "p.category_id = ?";
    $params[] = (int)$_GET['category'];
    $types   .= 'i';
}

if (!empty($_GET['sort'])) {
    $sortMap = [
        'price_asc'  => 'p.price ASC',
        'price_desc' => 'p.price DESC',
        'newest'     => 'p.product_id DESC',
        'name'       => 'p.product_name ASC',
    ];
    $orderBy = $sortMap[$_GET['sort']] ?? 'p.product_id DESC';
} else {
    $orderBy = 'p.product_id DESC';
}

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// ── Query ─────────────────────────────────────────────────────────────────────
$sql = "SELECT p.*, c.category_name
        FROM Product p
        LEFT JOIN Category c ON p.category_id = c.category_id
        $whereSQL
        ORDER BY $orderBy";

if ($params) {
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, $sql);
}
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// ── Category filter data ───────────────────────────────────────────────────────
$activeCatResult = !empty($_GET['category'])
    ? mysqli_query($conn, "SELECT category_name FROM Category WHERE category_id=" . (int)$_GET['category'])
    : null;
$activeCat = $activeCatResult ? mysqli_fetch_assoc($activeCatResult) : null;

require_once 'includes/header.php';
?>

<!-- ── Hero Banner (shown only on home without filters) ── -->
<?php if (empty($_GET['search']) && empty($_GET['category'])): ?>
<section class="gradient-hero text-white py-20 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <span class="inline-block bg-brand-500/20 text-brand-300 text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-widest uppercase">New Season Arrivals</span>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight">
            Discover Premium<br>
            <span class="bg-gradient-to-r from-brand-300 to-cyan-300 bg-clip-text text-transparent">Products</span>
        </h1>
        <p class="text-gray-400 text-lg mb-8 max-w-xl mx-auto">Shop thousands of quality products with unbeatable prices and fast delivery right to your door.</p>
        <form method="GET" action="/BIA PROJECT/index.php" class="flex gap-2 max-w-md mx-auto">
            <input type="text" name="search" placeholder="Search for anything..." class="flex-1 px-5 py-3 rounded-xl bg-white/10 backdrop-blur border border-white/20 text-white placeholder-gray-400 outline-none focus:border-brand-400 transition">
            <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-semibold shadow-lg">Search</button>
        </form>
    </div>
</section>
<?php endif; ?>

<!-- ── Products Section ── -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header Row -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                <?php if ($activeCat): ?>
                    🏷️ <?= htmlspecialchars($activeCat['category_name']) ?>
                <?php elseif (!empty($_GET['search'])): ?>
                    🔍 Results for "<?= htmlspecialchars($_GET['search']) ?>"
                <?php else: ?>
                    All Products
                <?php endif; ?>
            </h2>
            <p class="text-gray-500 text-sm mt-1"><?= count($products) ?> product<?= count($products) !== 1 ? 's' : '' ?> found</p>
        </div>
        <div class="flex items-center gap-3">
            <?php if (!empty($_GET['search']) || !empty($_GET['category'])): ?>
                <a href="/BIA PROJECT/index.php" class="text-sm text-gray-500 hover:text-brand-600 flex items-center gap-1 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Clear filters
                </a>
            <?php endif; ?>
            <form method="GET" class="flex items-center gap-2">
                <?php if (!empty($_GET['search'])): ?><input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>"><?php endif; ?>
                <?php if (!empty($_GET['category'])): ?><input type="hidden" name="category" value="<?= (int)$_GET['category'] ?>"><?php endif; ?>
                <select name="sort" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-700 outline-none focus:border-brand-400 cursor-pointer">
                    <option value="">Sort by: Featured</option>
                    <option value="price_asc"  <?= ($_GET['sort'] ?? '') === 'price_asc'  ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                    <option value="name"       <?= ($_GET['sort'] ?? '') === 'name'       ? 'selected' : '' ?>>Name A–Z</option>
                    <option value="newest"     <?= ($_GET['sort'] ?? '') === 'newest'     ? 'selected' : '' ?>>Newest First</option>
                </select>
            </form>
        </div>
    </div>

    <?php if (empty($products)): ?>
        <div class="text-center py-20">
            <div class="text-6xl mb-4">🛍️</div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No products found</h3>
            <p class="text-gray-500 mb-6">Try a different search or browse all categories.</p>
            <a href="/BIA PROJECT/index.php" class="btn-primary text-white px-6 py-3 rounded-xl font-semibold inline-block">Browse All</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($products as $p): ?>
                <div class="card-hover bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm group">
                    <!-- Product Image -->
                    <a href="/BIA PROJECT/product.php?id=<?= $p['product_id'] ?>">
                        <div class="relative h-52 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                            <?php if (!empty($p['image_url'])): ?>
                                <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['product_name']) ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-5xl">🛒</div>
                            <?php endif; ?>
                            <!-- Category Badge -->
                            <span class="absolute top-3 left-3 bg-brand-600 text-white text-xs px-2 py-1 rounded-lg font-medium">
                                <?= htmlspecialchars($p['category_name'] ?? 'General') ?>
                            </span>
                            <!-- Stock Badge -->
                            <?php if ($p['stock_quantity'] <= 0): ?>
                                <span class="absolute top-3 right-3 bg-red-500 text-white text-xs px-2 py-1 rounded-lg font-medium">Out of Stock</span>
                            <?php elseif ($p['stock_quantity'] <= $p['reorder_level']): ?>
                                <span class="absolute top-3 right-3 bg-amber-500 text-white text-xs px-2 py-1 rounded-lg font-medium">Low Stock</span>
                            <?php endif; ?>
                        </div>
                    </a>

                    <!-- Product Info -->
                    <div class="p-4">
                        <a href="/BIA PROJECT/product.php?id=<?= $p['product_id'] ?>">
                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1 hover:text-brand-600 transition"><?= htmlspecialchars($p['product_name']) ?></h3>
                        </a>
                        <p class="text-gray-500 text-xs line-clamp-2 mb-3"><?= htmlspecialchars($p['description'] ?? '') ?></p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-brand-600"><?= formatPrice($p['price']) ?></span>
                            <span class="text-xs text-gray-400"><?= $p['stock_quantity'] ?> left</span>
                        </div>
                        <!-- Add to Cart -->
                        <form method="POST" action="/BIA PROJECT/cart.php" class="mt-3">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?= $p['product_id'] ?>">
                            <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                            <button type="submit" <?= $p['stock_quantity'] <= 0 ? 'disabled' : '' ?>
                                class="w-full btn-primary text-white py-2.5 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 <?= $p['stock_quantity'] <= 0 ? 'opacity-50 cursor-not-allowed' : '' ?>">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                <?= $p['stock_quantity'] <= 0 ? 'Out of Stock' : 'Add to Cart' ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once 'includes/footer.php'; ?>
