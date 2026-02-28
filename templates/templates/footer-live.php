        <!-- Scripts for the Detailed Dashboard View -->
        <!-- 1. CanvasJS for the charging graph -->
        <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

        <!-- 2. Google Maps API -->
        <!-- IMPORTANT: Replace YOUR_API_KEY with your actual Google Maps API Key -->
        <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAGyz_AKrqUYBlstzhgwHQ_dDpyOh5OYP8&callback=initMap&libraries=marker"
        async defer></script>

        <!-- 3. Page-specific JavaScript for the dashboard -->
        <script>
            // --- DASHBOARD SPECIFIC SCRIPT ---

            // Make variables globally accessible so the main script in footer.php can see them
            window.chargingChart = null;
            window.map = null;
            window.marker = null;

            const getCssVar = (varName) => getComputedStyle(document.documentElement).getPropertyValue(varName).trim();

            function drawGauge(canvasId) {
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;
                const ctx = canvas.getContext('2d');
                const value = parseFloat(canvas.dataset.value);
                const max = parseFloat(canvas.dataset.max);
                const color = canvas.dataset.color;
                const dpr = window.devicePixelRatio || 1;
                const rect = canvas.getBoundingClientRect();
                canvas.width = rect.width * dpr;
                canvas.height = rect.height * dpr;
                ctx.scale(dpr, dpr);
                const width = canvas.clientWidth;
                const height = canvas.clientHeight;
                const centerX = width / 2;
                const centerY = height;
                const lineWidth = 12;
                const radius = (width / 2) - (lineWidth / 2);
                ctx.clearRect(0, 0, width, height);
                const startAngle = Math.PI;
                const endAngle = 2 * Math.PI;
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, startAngle, endAngle);
                ctx.lineWidth = lineWidth;
                ctx.strokeStyle = getCssVar('--gauge-track-color');
                ctx.lineCap = 'round';
                ctx.stroke();
                if (value > 0) {
                    const valuePercentage = value / max;
                    const progressEndAngle = startAngle + (valuePercentage * Math.PI);
                    ctx.beginPath();
                    ctx.arc(centerX, centerY, radius, startAngle, progressEndAngle);
                    ctx.lineWidth = lineWidth;
                    ctx.strokeStyle = color;
                    ctx.lineCap = 'round';
                    ctx.stroke();
                }
            }

            function initializeCanvasJSChart() {
                const isDarkTheme = (document.documentElement.getAttribute('data-theme') || 'dark') === 'dark';
                const dps = [{
                    x: 12,
                    y: 355
                }, {
                    x: 20,
                    y: 368
                }, {
                    x: 30,
                    y: 378
                }, {
                    x: 40,
                    y: 384
                }, {
                    x: 50,
                    y: 388
                }, {
                    x: 60,
                    y: 392,
                    markerType: "triangle",
                    markerColor: "#FFF",
                    markerBorderColor: getCssVar('--accent-purple'),
                    markerSize: 14,
                    markerBorderThickness: 2
                }, {
                    x: 70,
                    y: 396
                }, {
                    x: 80,
                    y: 402
                }];
                const options = {
                    theme: isDarkTheme ? "dark2" : "light2",
                    backgroundColor: "transparent",
                    animationEnabled: true,
                    axisX: {
                        title: "SoC (%)",
                        suffix: "%",
                        gridColor: getCssVar('--car-widget-border'),
                        lineColor: getCssVar('--car-widget-border'),
                        tickColor: getCssVar('--car-widget-border'),
                        labelFontColor: getCssVar('--text-muted'),
                        titleFontColor: getCssVar('--text-secondary'),
                    },
                    axisY: {
                        title: "Voltage (V)",
                        suffix: "V",
                        gridColor: getCssVar('--car-widget-border'),
                        lineColor: getCssVar('--car-widget-border'),
                        tickColor: getCssVar('--car-widget-border'),
                        labelFontColor: getCssVar('--text-muted'),
                        titleFontColor: getCssVar('--text-secondary'),
                    },
                    toolTip: {
                        content: "{y}V @ {x}%"
                    },
                    data: [{
                        type: "splineArea",
                        color: getCssVar('--accent-purple'),
                        fillOpacity: 0.2,
                        markerType: "circle",
                        markerSize: 8,
                        dataPoints: dps
                    }]
                };
                window.chargingChart = new CanvasJS.Chart("charging-canvas-container", options);
                window.chargingChart.render();
            }

            // This function is called by the theme switcher in footer.php
            window.initializeAllCanvases = () => {
                drawGauge('voltage-gauge');
                drawGauge('current-gauge');
                drawGauge('leakage-gauge');
                drawGauge('temp-gauge');
                initializeCanvasJSChart();
            }

            // Run the initial setup when this script loads
            window.initializeAllCanvases();
            window.addEventListener('resize', () => {
                if (window.chargingChart) window.chargingChart.render();
            });

            // --- Google Maps Specific Code (must be in global scope for callback) ---
            window.darkMapStyles = [{
                "elementType": "geometry",
                "stylers": [{
                    "color": "#242f3e"
                }]
            }, {
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#746855"
                }]
            }, {
                "elementType": "labels.text.stroke",
                "stylers": [{
                    "color": "#242f3e"
                }]
            }, {
                "featureType": "administrative.locality",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#d59563"
                }]
            }, {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#d59563"
                }]
            }, {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#263c3f"
                }]
            }, {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#6b9a76"
                }]
            }, {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#38414e"
                }]
            }, {
                "featureType": "road",
                "elementType": "geometry.stroke",
                "stylers": [{
                    "color": "#212a37"
                }]
            }, {
                "featureType": "road",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9ca5b3"
                }]
            }, {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#746855"
                }]
            }, {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [{
                    "color": "#1f2835"
                }]
            }, {
                "featureType": "road.highway",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#f3d19c"
                }]
            }, {
                "featureType": "transit",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#2f3948"
                }]
            }, {
                "featureType": "transit.station",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#d59563"
                }]
            }, {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#17263c"
                }]
            }, {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#515c6d"
                }]
            }, {
                "featureType": "water",
                "elementType": "labels.text.stroke",
                "stylers": [{
                    "color": "#17263c"
                }]
            }];
            window.lightMapStyles = [{
                "elementType": "geometry",
                "stylers": [{
                    "color": "#f5f5f5"
                }]
            }, {
                "elementType": "labels.icon",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#616161"
                }]
            }, {
                "elementType": "labels.text.stroke",
                "stylers": [{
                    "color": "#f5f5f5"
                }]
            }, {
                "featureType": "administrative.land_parcel",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#bdbdbd"
                }]
            }, {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#eeeeee"
                }]
            }, {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#757575"
                }]
            }, {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#e5e5e5"
                }]
            }, {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            }, {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#ffffff"
                }]
            }, {
                "featureType": "road.arterial",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#757575"
                }]
            }, {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#dadada"
                }]
            }, {
                "featureType": "road.highway",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#616161"
                }]
            }, {
                "featureType": "road.local",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            }, {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#e5e5e5"
                }]
            }, {
                "featureType": "transit.station",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#eeeeee"
                }]
            }, {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#c9c9c9"
                }]
            }, {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            }];

            function initMap() {
                const locationCoords = {
                    lat: 9.2885,
                    lng: 79.3129
                }; // Rameswaram, Tamilnadu
                const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                window.map = new google.maps.Map(document.getElementById("map"), {
                    center: locationCoords,
                    zoom: 15,
                    disableDefaultUI: true,
                    styles: isDark ? window.darkMapStyles : window.lightMapStyles
                });
                window.marker = new google.maps.Marker({
                    position: locationCoords,
                    map: window.map,
                    title: "Charger Location"
                });
            }
            window.initMap = initMap;
        </script>