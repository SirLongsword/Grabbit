<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';
$isLoggedInHeader = isset($_SESSION['user_id']);
if ($isLoggedInHeader) {
    $stmtHeader = $pdo->prepare('SELECT username FROM users WHERE id = ? LIMIT 1');
    $stmtHeader->execute([$_SESSION['user_id']]);
    $userHeader = $stmtHeader->fetch(PDO::FETCH_ASSOC);
} else {
    $userHeader = ['username' => "Login"];
} ?>

<header>
    <div class="menu">
        <div class="dropDown"> <button class="dropBtn">≡</button>
            <div class="dropDownContent"> <a href="<?= BASE_URL ?>/index.php">Home</a> <a
                    href="<?= BASE_URL ?>/account.php">Account</a> <a href="<?= BASE_URL ?>/create_listing.php">Create Listing</a>
            </div>
        </div>
    </div> 
    <!-- if logged in -->
      <?php if ($isLoggedInHeader): ?>
        <div class="inbox-link" style="display: inline-block; margin-right: 15px; vertical-align: middle;"> <a
                href="<?= BASE_URL ?>/messages/messages.php"> Inbox </a> </div> <?php endif; ?>
    <div class="logo" style="display: inline-block; vertical-align: middle;"> <a href="<?= BASE_URL ?>/index.php"> <img
                src="<?= BASE_URL ?>/images/logo/logo.png" alt="Grabbit" class="logo-desktop" /> <img
                src="<?= BASE_URL ?>/images/mascot/Mascot.png" alt="Grabbit" class="logo-mobile" /> </a> </div>
    <div class="searchbar"> <input type="text" id="searchInput" placeholder="Search listings..." autocomplete="off" />
        <div id="liveResults" class="live-results"></div>
    </div>
    <div class="account"> <a href="<?= $isLoggedInHeader ? BASE_URL . '/account.php' : BASE_URL . '/login.php' ?>"
            class="accountBtn"> <?= $isLoggedInHeader ? 'Hi, ' . htmlspecialchars($userHeader['username']) : 'Login' ?>
        </a> </div>
</header>
