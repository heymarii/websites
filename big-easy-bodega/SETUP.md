# Big Easy Bodega — go-live setup (SiteGround + WooCommerce)

Domain: **bigeasybodega.com**  
Location copy: **10X Apartments, Building 26, next to apartment 26102**

Use this checklist after the theme and `beb-bodega-core` plugin are uploaded.

---

## 1. SiteGround hosting

1. Log into SiteGround → **Sites** → create or select the site for `bigeasybodega.com`.
2. Prefer **PHP 8.1+** (SiteGround → Site Tools → Devs → PHP Manager).
3. Install WordPress via Site Tools → **WordPress** → Install (or use an existing install).
4. Set the site URL to `https://bigeasybodega.com` (and `www` redirect if desired).
5. Enable **free SSL** (Site Tools → Security → SSL Manager) and force HTTPS.

### Upload theme & plugin

**Option A — File Manager / SFTP**

- Upload `theme/big-easy-bodega` to `wp-content/themes/big-easy-bodega`
- Upload `plugin/beb-bodega-core` to `wp-content/plugins/beb-bodega-core`

**Option B — Zip**

```bash
cd theme && zip -r big-easy-bodega.zip big-easy-bodega
cd ../plugin && zip -r beb-bodega-core.zip beb-bodega-core
```

Then **Appearance → Themes → Add New → Upload** and **Plugins → Add New → Upload**.

Activate **WooCommerce**, then **Big Easy Bodega Core**, then the **Big Easy Bodega** theme.

---

## 2. DNS

At your domain registrar (or SiteGround DNS if nameservers point there):

| Type | Name | Value |
|------|------|--------|
| A | `@` | SiteGround site IP (from Site Tools → Site → IP) |
| A or CNAME | `www` | Same IP, or CNAME to `@` / SiteGround host |

Allow DNS propagation, then confirm `https://bigeasybodega.com` loads with a valid certificate.

Optional: email (MX) via SiteGround or Google Workspace — not required for the shop.

---

## 3. WooCommerce basics

1. **WooCommerce → Home** — finish setup. Address: your Texas business / apartment address.
2. **WooCommerce → Settings → General**
   - Store address (Texas)
   - Selling location: United States
   - Enable taxes
3. **Products**: turn on stock management globally (**Settings → Products → Inventory** → manage stock).
4. **Shipping**: for v1 you can disable shipping methods and treat all orders as local pickup. Or add a free “Building 26 pickup” method.
5. **Accounts**: guest checkout **on** (Settings → Accounts & Privacy) so residents need not register.
6. Pages: ensure Shop, Cart, Checkout, My Account exist and are assigned under **WooCommerce → Settings → Advanced**.
7. **Settings → Reading**: homepage = a published page titled Home (theme `front-page.php` renders the bodega home).

---

## 4. Texas sales tax

1. **WooCommerce → Settings → Tax** → enable taxes.
2. Choose **Tax options**:
   - Prices entered exclusive of tax (typical) **or** inclusive — pick one and stick to it.
   - Display tax totals in cart/checkout as itemized.
3. **Standard rates** → Insert row:
   - Country: `US`
   - State: `TX`
   - Rate %: your applicable combined rate for the store’s address (city/county/state). Confirm current rate with a Texas tax resource or accountant — do not guess for production.
   - Tax name: `TX Sales Tax`
4. Save. Place a test order and confirm tax appears on cart/checkout.

WooCommerce Tax / WooCommerce Shipping automated tax can be used instead if you connect a Jetpack/Woo account — optional.

---

## 5. Payment handles

**No API secrets** are stored in the theme. v1 uses public payment-profile links. These are the **default Customizer values** (already baked into the theme):

| Method | Display | URL |
|--------|---------|-----|
| Venmo | `@heymarii` | `https://venmo.com/u/heymarii` |
| PayPal | paypal.me/heymarii | `https://paypal.me/heymarii` |
| Cash App | `$heymarii` (cashtag uses `$`) | `https://cash.app/$heymarii` |
| Apple Pay | — | **Omitted for v1** (Customizer field blank / optional) |

1. Confirm under **Appearance → Customize → Big Easy Bodega** (only change if the owner updates handles).
2. **WooCommerce → Settings → Payments** → enable **Bodega payment links**. Disable gateways you do not use.
3. Test: place an order, confirm links open `@heymarii` / `$heymarii` profiles, and the payment note asks for apartment number.

**Owner workflow:** when payment arrives in Venmo, PayPal, or Cash App, open the WooCommerce order → set status to **Processing**. Stock already decreased when the order was placed; the thank-you page then shows the door code.

---

## 6. Door code

Default pickup door code: **`12345`** (set automatically when **Big Easy Bodega Core** is activated; single WordPress option `beb_door_code`).

1. Open **WooCommerce → Bodega Settings** — you should see `12345` in **Pickup door code**.
2. It appears on the order received / thank-you page only when status is **Processing** or **Completed**.
3. **To change it later:** edit the field on that same settings screen and click Save. Newly loaded thank-you pages use the latest value right away.
4. Tip: rotate the code if it is shared too widely, and mark outstanding orders Completed before rotating if needed.

---

## 7. Maps, contact, menus

1. Customizer → **Google Maps embed URL**: in Google Maps, open the location → Share → Embed a map → copy the `src` URL only into the field.
2. Set contact phone and `hello@bigeasybodega.com` (or your real inbox).
3. **Appearance → Menus**: Primary = Home, Shop (, Cart). Footer optional.
4. **Appearance → Customize → Site Identity**: site title “Big Easy Bodega”; optional custom logo (defaults to fleur-de-lis mark).

---

## 8. Sample catalog

```bash
# SSH into SiteGround, cd to WordPress root
wp eval-file /path/to/sample-data/seed-products.php
```

Or create products manually under **Products → Add new** using categories Pantry / Drinks / Paper products / Misc. See `README.md` owner guide.

---

## 9. Smoke test checklist

- [ ] Home shows yellow hero logo, Start shopping → Shop
- [ ] Newly added / On sale carousels show products
- [ ] Map section + location copy
- [ ] Request item form → appears under **Item Requests**
- [ ] Shop cards: image, title, price, short description, Add to cart
- [ ] Checkout requires name, phone, apartment; tax line for TX
- [ ] Payment links visible; order goes **On hold**
- [ ] After setting **Processing**, thank-you shows door code + pickup instructions
- [ ] Mobile: menu toggle, readable hero, tap-friendly cart buttons

---

## 10. Security & hygiene

- Use a strong admin password and SiteGround 2FA.
- Keep WordPress, WooCommerce, theme, and plugin updated.
- Do not commit real payment credentials or door codes to git — configure them only in wp-admin.
- Restrict who has Administrator / Shop manager roles.

---

## Support contacts (configure in Customizer)

Residents see the contact phone/email on the thank-you page when something goes wrong with pickup or payment.
