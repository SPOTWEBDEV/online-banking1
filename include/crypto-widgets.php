<?php
/*
 * Shared crypto widgets (free TradingView embeds)
 * Save as: include/crypto-widgets.php
 *
 * Usage on a page:
 *   include("../include/crypto-widgets.php");   // adjust the ../ depth
 *   <head>  ... <?php cw_styles(); ?>
 *   <body>  <?php cw_ticker(); ?>
 *           <?php cw_heading('Market snapshot', 'Live prices'); cw_minis(); ?>
 *           <?php cw_heading('Live crypto market'); cw_market_overview(); ?>
 */

function cw_styles()
{
?>
    <style>
        .cw-ticker,
        .cw-mini,
        .cw-panel {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 24px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .cw-ticker {
            margin-bottom: 8px;
        }

        .cw-minis {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .cw-mini {
            height: 220px;
        }

        .cw-mini .tradingview-widget-container,
        .cw-mini .tradingview-widget-container__widget {
            height: 100%;
            width: 100%;
        }

        .cw-panel {
            padding: 4px;
        }

        .cw-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin: 28px 0 14px;
        }

        .cw-heading h5 {
            margin: 0;
            font-weight: 700;
            font-size: 17px;
        }

        .cw-heading .live {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #16a34a;
        }

        .cw-heading .live::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #16a34a;
            animation: cwPulse 1.6s infinite;
        }

        @keyframes cwPulse {
            0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, .5); }
            70% { box-shadow: 0 0 0 8px rgba(22, 163, 74, 0); }
            100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
        }

        @media (max-width: 991px) {
            .cw-minis {
                grid-template-columns: 1fr;
            }

            .cw-mini {
                height: 200px;
            }
        }
    </style>
<?php
}

/* Section title with a pulsing "Live" tag */
function cw_heading($title, $tag = 'Live')
{
?>
    <div class="cw-heading">
        <h5><?= htmlspecialchars($title) ?></h5>
        <span class="live"><?= htmlspecialchars($tag) ?></span>
    </div>
<?php
}

/* Scrolling price bar */
function cw_ticker()
{
?>
    <div class="cw-ticker">
        <div class="tradingview-widget-container">
            <div class="tradingview-widget-container__widget"></div>
            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
                {
                    "symbols": [
                        {"proName": "BINANCE:BTCUSDT", "title": "Bitcoin"},
                        {"proName": "BINANCE:ETHUSDT", "title": "Ethereum"},
                        {"proName": "BINANCE:BNBUSDT", "title": "BNB"},
                        {"proName": "BINANCE:SOLUSDT", "title": "Solana"},
                        {"proName": "BINANCE:XRPUSDT", "title": "XRP"},
                        {"proName": "BINANCE:DOGEUSDT", "title": "Dogecoin"},
                        {"proName": "BINANCE:ADAUSDT", "title": "Cardano"}
                    ],
                    "showSymbolLogo": true,
                    "colorTheme": "light",
                    "isTransparent": false,
                    "displayMode": "adaptive",
                    "locale": "en"
                }
            </script>
        </div>
    </div>
<?php
}

/* Row of small price charts */
function cw_minis($coins = null)
{
    if ($coins === null) {
        $coins = ['BINANCE:BTCUSDT', 'BINANCE:ETHUSDT', 'BINANCE:SOLUSDT'];
    }
?>
    <div class="cw-minis">
        <?php foreach ($coins as $symbol): ?>
            <div class="cw-mini">
                <div class="tradingview-widget-container">
                    <div class="tradingview-widget-container__widget"></div>
                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                        {
                            "symbol": "<?= htmlspecialchars($symbol) ?>",
                            "width": "100%",
                            "height": "100%",
                            "locale": "en",
                            "dateRange": "1M",
                            "colorTheme": "light",
                            "isTransparent": false,
                            "autosize": true,
                            "largeChartUrl": ""
                        }
                    </script>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php
}

/* Bigger crypto market table with sparkline charts */
function cw_market_overview()
{
?>
    <div class="cw-panel">
        <div class="tradingview-widget-container">
            <div class="tradingview-widget-container__widget"></div>
            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js" async>
                {
                    "colorTheme": "light",
                    "dateRange": "12M",
                    "showChart": true,
                    "locale": "en",
                    "width": "100%",
                    "height": "420",
                    "largeChartUrl": "",
                    "isTransparent": false,
                    "showSymbolLogo": true,
                    "showFloatingTooltip": false,
                    "tabs": [
                        {
                            "title": "Crypto",
                            "symbols": [
                                {"s": "BINANCE:BTCUSDT", "d": "Bitcoin"},
                                {"s": "BINANCE:ETHUSDT", "d": "Ethereum"},
                                {"s": "BINANCE:BNBUSDT", "d": "BNB"},
                                {"s": "BINANCE:SOLUSDT", "d": "Solana"},
                                {"s": "BINANCE:XRPUSDT", "d": "XRP"},
                                {"s": "BINANCE:DOGEUSDT", "d": "Dogecoin"}
                            ],
                            "originalTitle": "Crypto"
                        }
                    ]
                }
            </script>
        </div>
    </div>
<?php
}