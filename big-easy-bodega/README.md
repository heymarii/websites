# Big Easy Bodega

WordPress theme + small companion plugin for **[bigeasybodega.com](https://bigeasybodega.com)** — a New Orleans–inspired convenience bodega for residents of **10X Apartments, Building 26 (next to apartment 26102)**.

Residents browse live WooCommerce stock, check out online, pay via **Venmo (@heymarii) / PayPal / Cash App ($heymarii)**, then pick up with a **door code** after you confirm payment. Apple Pay is optional and omitted for v1.

## What’s in this folder

| Path | Purpose |
|------|---------|
| `theme/big-easy-bodega/` | Installable WordPress theme (WooCommerce templates + brand UI) |
| `plugin/beb-bodega-core/` | Checkout fields, door code, item-request CPT, payment-links gateway |
| `sample-data/` | 12 sample products (JSON + WP-CLI seed) |
| `SETUP.md` | SiteGround go-live, DNS, tax, payments, door code |

## Brand

- Mustard / gold yellow `#EBB440` and dark teal `#0D3D38` (black accent OK)
- Hero uses the yellow banner logo; header/favicon use the compact fleur-de-lis mark
- Logo files live in `theme/big-easy-bodega/assets/images/`

If you have the original PNG banners, replace or add them alongside the SVG files (`logo-banner-yellow`, `logo-banner-black`, `logo-mark`, `favicon`).

## Quick install (fresh WordPress)

1. Install and activate **WooCommerce**.
2. Copy `theme/big-easy-bodega` → `wp-content/themes/big-easy-bodega` and activate **Big Easy Bodega**.
3. Copy `plugin/beb-bodega-core` → `wp-content/plugins/beb-bodega-core` and activate **Big Easy Bodega Core**.
4. Run WooCommerce setup (skip shipping if pickup-only). Create pages: Shop, Cart, Checkout, My Account (WooCommerce can create these).
5. Set **Settings → Reading → Your homepage displays** to a static page (create a blank “Home” page). The theme’s `front-page.php` powers the homepage.
6. Assign menus: Appearance → Menus → Primary (Home, Shop) and optional Footer.
7. Follow **SETUP.md** for Texas tax, payment handles, door code, and maps.

### Sample products

From the WordPress root (SSH / WP-CLI):

```bash
wp eval-file wp-content/themes/big-easy-bodega/../../../big-easy-bodega/sample-data/seed-products.php
```

If the repo sits next to WordPress, adjust the path. You can also copy `sample-data/` onto the server and run:

```bash
wp eval-file /path/to/sample-data/seed-products.php
```

Categories created: **Pantry**, **Drinks**, **Paper products**, **Misc**.

## Owner guide — managing inventory

You do **not** need a developer for day-to-day stock.

1. Log into WordPress Admin.
2. Go to **Products → All Products**.
3. **Add product** or edit an existing one:
   - **Product name**, **short description** (shows on shop cards), and **Product image**.
   - **Regular price** / optional **Sale price**.
   - **Inventory**: enable stock management, set **Stock quantity**. Quantity drops when someone places an order.
   - **Product categories**: Pantry, Drinks, Paper products, or Misc.
4. Click **Update** / **Publish**. The shop and homepage carousels update automatically.
5. To put something on the “On sale” carousel, set a **Sale price** lower than the regular price.
6. Out of stock: set quantity to `0` or mark **Out of stock**. Hide or delete products you no longer carry.

### Orders & door code

1. Resident places order → status **On hold** (awaiting payment).
2. Confirm Venmo / PayPal / Cash App payment (check @heymarii / $heymarii in the apps).
3. Open **WooCommerce → Orders**, open the order, set status to **Processing** (or **Completed**).
4. The resident’s thank-you / order page then shows the **door code**. Default is **`12345`** (stored once as the `beb_door_code` option).
5. **To change the door code later:** WordPress Admin → **WooCommerce → Bodega Settings** → edit **Pickup door code** → Save. New thank-you pages use the updated value immediately.
6. Apartment number, phone, and name are on the order (billing + Apartment field).

### Item requests

Homepage form submissions appear under **Item Requests** in the admin menu (name, apartment, phone, requested item).

### Payment links & location copy

Checkout payment links ship with the owner handles already set (Customizer defaults):

| Method | Handle / link |
|--------|----------------|
| Venmo | [@heymarii](https://venmo.com/u/heymarii) |
| PayPal | [paypal.me/heymarii](https://paypal.me/heymarii) |
| Cash App | [$heymarii](https://cash.app/$heymarii) (cashtag uses `$`) |
| Apple Pay | Not offered in v1 — leave the Customizer field blank (or add a URL later) |

Change links anytime under **Appearance → Customize → Big Easy Bodega**. Same screen: Google Maps embed, pickup instructions, contact phone/email.

## Architecture notes

- Presentation and WooCommerce template overrides live in the **theme**.
- Checkout fields, CPT, door code option, and the offline **Bodega payment links** gateway live in the **plugin** (survives theme switches).
- No payment API secrets in code — public payment-profile URLs only (defaults: Venmo `@heymarii`, PayPal `paypal.me/heymarii`, Cash App `$heymarii`).

## Requirements

- WordPress 6.4+
- PHP 8.0+
- WooCommerce 8+ (recommended current stable)

## License

GPL-2.0-or-later (WordPress theme/plugin standard).
