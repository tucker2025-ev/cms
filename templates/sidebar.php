<?php
// Set a default current page if one isn't defined to avoid errors
$currentPage = isset($currentPage) ? $currentPage : 'dashboard';
?>
<!-- SIDEBAR -->
<aside class="sidebar" id="app-sidebar">
    <!-- Collapsed State Logo -->
    <a href="" class="sidebar-logo" title="Dashboard">
        <img src="assets/icon.png" alt="">
    </a>

    <!-- Expanded State Header -->
    <div class="sidebar-header">
        <img src="assets/icon.png" alt="">
        <span class="brand-name">Tucker CMS</span>
    </div>

    <!-- NAVIGATION WITH DYNAMIC ACTIVE STATE -->
    <nav class="sidebar-nav">
        <ul>
            <li class="nav-group-title"><span>Control & Monitoring</span></li>
            <li class="nav-item <?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>">
                <a href="index.php"> <i class="fas fa-home"></i> <span>Dashboard</span> </a>
            </li>
            <li class="nav-item <?php echo ($currentPage == 'remote_start') ? 'active' : ''; ?>">
                <a href="remote-start.php"> <i class="fas fa-power-off"></i> <span>Remote Start / Stop</span> </a>
            </li>
            <li class="nav-item <?php echo ($currentPage == 'live_charging') ? 'active' : ''; ?>">
                <a href="live-charging-session.php"> <i class="fas fa-bolt"></i> <span>Live Charging</span> </a>
            </li>
            <li class="nav-item <?php echo ($currentPage == 'error-log') ? 'active' : ''; ?>">
                <a href="charger-errors.php"> <i class="fas fa-triangle-exclamation"></i> <span>Charger Errors</span> </a>
            </li>

            <!-- === MISSING ITEMS ADDED BELOW === -->

            <li class="nav-group-title"><span>Chargers & Tariffs</span></li>
            <li class="nav-item <?php echo ($currentPage == 'charge_points') ? 'active' : ''; ?>">
                <a href="chargePoint.php"> <i class="fas fa-charging-station"></i> <span>Charge Point</span> </a>
            </li>
            <li class="nav-item <?php echo ($currentPage == 'config') ? 'active' : ''; ?>">
                <a href="config.php"> <i class="fas fa-cogs"></i> <span>Config</span> </a>
            </li>
            <li class="nav-item <?php echo ($currentPage == 'tariffs') ? 'active' : ''; ?>">
                <a href="view-tariff.php"> <i class="fas fa-tags"></i> <span>TOD Unit Fare</span> </a>
            </li>

            <li class="nav-group-title"><span>Assets</span></li>
            <li class="nav-item <?php echo ($currentPage == 'cms') ? 'active' : ''; ?>">
                <a href="cms.php"> <i class="fas fa-cogs"></i> <span>CMS</span> </a>
            </li>
            <li class="nav-item <?php echo ($currentPage == 'create_operator') ? 'active' : ''; ?>">
                <a href="cpo.php"> <i class="fas fa-user-tie"></i> <span>CPO</span> </a>
            </li>

            <li class="nav-item <?php echo ($currentPage == 'create_station') ? 'active' : ''; ?>">
                <a href="station.php"> <i class="fas fa-map-location-dot"></i> <span>Station</span> </a>
            </li>


            <li class="nav-group-title"><span>Revenue</span></li>
             <li class="nav-item <?php echo ($currentPage == 'settlement') ? 'active' : ''; ?>">
                <a href="settlement.php"> <i class="fas fa-file-invoice-dollar"></i> <span>Settlements</span> </a>
            </li>
            <li class="nav-item <?php echo ($currentPage == 'cpo_settlements') ? 'active' : ''; ?>">
                <a href="cpo-settlement.php"> <i class="fas fa-chart-line"></i> <span>CPO Revenue</span> </a>
            </li>
           
            <li class="nav-item <?php echo ($currentPage == 'charging_history') ? 'active' : ''; ?>">
                <a href="charging-history.php"> <i class="fas fa-history"></i> <span>Charging History</span> </a>
            </li>

            <li class="nav-group-title"><span>Wallet & Payments</span></li>
            <li class="nav-item <?php echo ($currentPage == 'users') ? 'active' : ''; ?>">
                <a href="users.php"> <i class="fas fa-wallet"></i> <span>User Wallet</span> </a>
            </li>
           
            <li class="nav-item <?php echo ($currentPage == 'razorpay transactions') ? 'active' : ''; ?>">
                <a href="Razorpay.php"> <i class="fas fa-credit-card"></i> <span>Razorpay Transactions</span> </a>
            </li>

        </ul>
    </nav>

    <!-- Open/Close Buttons -->
    <div class="sidebar-profile" id="menu-toggle-btn" title="Open Menu">
        <i class="fas fa-bars"></i>
    </div>
    <div class="sidebar-close">
        <button class="close-btn" id="menu-close-btn" title="Close Menu">
            <i class="fas fa-times"></i>
        </button>
    </div>
</aside>