    <!-- UNIFIED JAVASCRIPT FOR GENERAL SITE INTERACTIVITY -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- WORKING: SIDEBAR EXPAND/COLLAPSE LOGIC (WITH GRAPH RESIZE FIX) ---
            const sidebar = document.getElementById('app-sidebar');
            const menuToggleBtn = document.getElementById('menu-toggle-btn');
            const menuCloseBtn = document.getElementById('menu-close-btn');

            // This function will be called when the sidebar is toggled.
            // It safely checks if the chart from footer-live.php exists and resizes it.
            const handleSidebarToggle = () => {
                // Check if 'chargingChart' is defined in the window scope and is not null
                if (typeof window.chargingChart !== 'undefined' && window.chargingChart) {
                    // Wait for the CSS animation to finish before re-rendering the chart
                    setTimeout(() => {
                        window.chargingChart.render();
                    }, 350); // This duration should match your sidebar's CSS transition time
                }
            };

            if (sidebar && menuToggleBtn) {
                menuToggleBtn.addEventListener('click', () => {
                    sidebar.classList.add('expanded');
                    handleSidebarToggle(); // Call the resize handler
                });
            }

            if (sidebar && menuCloseBtn) {
                menuCloseBtn.addEventListener('click', () => {
                    sidebar.classList.remove('expanded');
                    handleSidebarToggle(); // Call the resize handler
                });
            }

            // --- DATE & TIME WIDGET ---
            const dateElement = document.getElementById('current-date');
            const timeElement = document.getElementById('current-time');
            if (dateElement && timeElement) {
                const updateDateTime = () => {
                    const now = new Date();
                    dateElement.textContent = now.toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    timeElement.textContent = ` | ${now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true })}`;
                };
                updateDateTime();
                setInterval(updateDateTime, 1000);
            }

            // --- USER PROFILE DROPDOWN ---
            const profileTrigger = document.getElementById('user-profile-trigger');
            const profileDropdown = document.getElementById('profile-dropdown-menu');
            if (profileTrigger && profileDropdown) {
                profileTrigger.addEventListener('click', function(event) {
                    event.stopPropagation();
                    profileDropdown.classList.toggle('show');
                    profileTrigger.classList.toggle('active');
                });
                window.addEventListener('click', function(event) {
                    if (!profileTrigger.contains(event.target) && profileDropdown.classList.contains('show')) {
                        profileDropdown.classList.remove('show');
                        profileTrigger.classList.remove('active');
                    }
                });
            }

            // --- THEME TOGGLE LOGIC (WITH CHART/MAP AWARENESS) ---
            const themeToggleBtn = document.getElementById('theme-toggle-btn');
            const htmlEl = document.documentElement;
            if (themeToggleBtn) {
                const themeIcon = themeToggleBtn.querySelector('i');
                const setTheme = (theme) => {
                    htmlEl.setAttribute('data-theme', theme);
                    if (themeIcon) {
                        themeIcon.className = theme === 'dark' ? 'far fa-sun' : 'far fa-moon';
                    }
                    localStorage.setItem('theme', theme);

                    // Safely check if the dashboard-specific functions and variables exist
                    if (typeof window.initializeAllCanvases === 'function') {
                        if (window.map) {
                            window.map.setOptions({
                                styles: theme === 'dark' ? window.darkMapStyles : window.lightMapStyles
                            });
                        }
                        // Redraw gauges and chart with new theme colors
                        setTimeout(window.initializeAllCanvases, 50);
                    }
                };
                const savedTheme = localStorage.getItem('theme') || 'dark';
                setTheme(savedTheme);
                themeToggleBtn.addEventListener('click', function() {
                    const currentTheme = htmlEl.getAttribute('data-theme');
                    setTheme(currentTheme === 'light' ? 'dark' : 'light');
                });
            }

            // --- MODAL CONFIRMATION LOGIC ---
            // This code remains exactly as you provided it.
            const modal = document.getElementById('confirmation-modal');
            if (modal) {
                const modalImage = document.getElementById('modal-image');
                const modalTitle = document.getElementById('modal-title');
                const modalMessage = document.getElementById('modal-message');
                const confirmBtn = document.getElementById('modal-confirm-btn');
                const cancelBtn = document.getElementById('modal-cancel-btn');
                const closeBtn = document.getElementById('modal-close-btn');
                let actionToConfirm = null;

                function openModal(title, message, confirmText, confirmClass, imageSrc, action) {
                    modalTitle.textContent = title;
                    modalMessage.textContent = message;
                    confirmBtn.textContent = confirmText;
                    if (imageSrc) {
                        modalImage.src = imageSrc;
                        modalImage.classList.add('show');
                    }
                    confirmBtn.className = 'modal-btn modal-btn-confirm ' + confirmClass;
                    actionToConfirm = action;
                    modal.classList.add('show');
                }

                function closeModal() {
                    modal.classList.remove('show');
                    modalImage.classList.remove('show');
                    modalImage.src = '';
                    actionToConfirm = null;
                }
                document.querySelectorAll('.action-button.btn-start, .action-button.btn-stop').forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();
                        const chargerId = this.closest('tr')?.querySelector('td:nth-child(3)')?.textContent || 'this charger';
                        if (this.classList.contains('btn-start')) {
                            openModal('Start Charging', `Are you sure you want to start charging for ${chargerId}?`, 'Start', 'confirm-start', 'assets/start-charging.jpg', () => {
                                console.log(`CONFIRMED: Starting charge for ${chargerId}`);
                                alert(`Starting charge for ${chargerId}!`);
                            });
                        } else if (this.classList.contains('btn-stop')) {
                            openModal('Stop Charging', `Are you sure you want to stop charging for ${chargerId}?`, 'Stop', 'confirm-stop', 'assets/stop-charging.png', () => {
                                console.log(`CONFIRMED: Stopping charge for ${chargerId}`);
                                alert(`Stopping charge for ${chargerId}!`);
                            });
                        }
                    });
                });
                confirmBtn.addEventListener('click', () => {
                    if (typeof actionToConfirm === 'function') actionToConfirm();
                    closeModal();
                });
                cancelBtn.addEventListener('click', closeModal);
                closeBtn.addEventListener('click', closeModal);
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) closeModal();
                });
            }
        });
    </script>
    </body>

    </html>