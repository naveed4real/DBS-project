</main>

<!-- ── Footer ── -->
<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-brand-500 to-brand-800 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-lg">ShopVerse</span>
                </div>
                <p class="text-sm text-gray-400">Your premium online shopping destination. Quality products, fast delivery, best prices.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/BIA PROJECT/index.php" class="hover:text-white transition">Home</a></li>
                    <li><a href="/BIA PROJECT/cart.php" class="hover:text-white transition">Cart</a></li>
                    <li><a href="/BIA PROJECT/checkout.php" class="hover:text-white transition">Checkout</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Account</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/BIA PROJECT/login.php" class="hover:text-white transition">Login</a></li>
                    <li><a href="/BIA PROJECT/register.php" class="hover:text-white transition">Register</a></li>
                    <li><a href="/BIA PROJECT/orders.php" class="hover:text-white transition">My Orders</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Admin</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/BIA PROJECT/admin.php" class="hover:text-white transition">Dashboard</a></li>
                    <li><a href="/BIA PROJECT/admin.php?tab=products" class="hover:text-white transition">Products</a></li>
                    <li><a href="/BIA PROJECT/admin.php?tab=orders" class="hover:text-white transition">Orders</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-6 flex flex-col md:flex-row justify-between items-center gap-2 text-sm text-gray-500">
            <p>© <?= date('Y') ?> ShopVerse — DBMS 6th Semester Project</p>
            <p>Built with PHP & MySQL | Tailwind CSS</p>
        </div>
    </div>
</footer>
</body>
</html>
