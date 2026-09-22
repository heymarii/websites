#!/usr/bin/env bash
# Seed Big Easy Bodega sample products via WP-CLI.
# Usage (from WordPress root): bash wp-content/themes/big-easy-bodega/../../../../sample-data/seed-products.sh
# Or copy this file to the server and run from the WP root with WP-CLI available.
set -euo pipefail

if ! command -v wp >/dev/null 2>&1; then
  echo "WP-CLI (wp) is required." >&2
  exit 1
fi

echo "Creating product categories…"
for pair in "pantry:Pantry" "drinks:Drinks" "paper-products:Paper products" "misc:Misc"; do
  slug="${pair%%:*}"
  name="${pair##*:}"
  if ! wp term get product_cat "$slug" --by=slug --field=term_id >/dev/null 2>&1; then
    wp term create product_cat "$name" --slug="$slug" >/dev/null
    echo "  + $name"
  else
    echo "  = $name (exists)"
  fi
done

create_product() {
  local name="$1" price="$2" sale="$3" cat="$4" short="$5" stock="$6" sku="$7"
  local existing
  existing="$(wp post list --post_type=product --name="$(echo "$name" | tr '[:upper:]' '[:lower:]' | sed 's/[^a-z0-9]/-/g;s/--*/-/g;s/-$//')" --field=ID 2>/dev/null | head -1 || true)"
  if [[ -n "${existing}" ]]; then
    echo "  = skip existing: $name"
    return
  fi

  local args=(wc product create --user=1 --porcelain --name="$name" --regular_price="$price" --short_description="$short" --description="$short" --categories="[{\"slug\":\"$cat\"}]" --type=simple --status=publish)
  # Prefer wp wc if available; fallback to post create + meta.
  local id=""
  if wp wc product create --help >/dev/null 2>&1; then
    if [[ -n "$sale" ]]; then
      id="$(wp wc product create --user=1 --porcelain --name="$name" --regular_price="$price" --sale_price="$sale" --short_description="$short" --type=simple --status=publish --sku="$sku" 2>/dev/null || true)"
    else
      id="$(wp wc product create --user=1 --porcelain --name="$name" --regular_price="$price" --short_description="$short" --type=simple --status=publish --sku="$sku" 2>/dev/null || true)"
    fi
  fi

  if [[ -z "$id" ]]; then
    id="$(wp post create --post_type=product --post_status=publish --post_title="$name" --post_excerpt="$short" --porcelain)"
    wp post meta update "$id" _regular_price "$price"
    wp post meta update "$id" _price "${sale:-$price}"
    if [[ -n "$sale" ]]; then
      wp post meta update "$id" _sale_price "$sale"
    fi
    wp post meta update "$id" _manage_stock yes
    wp post meta update "$id" _stock "$stock"
    wp post meta update "$id" _stock_status instock
    wp post meta update "$id" _sku "$sku"
    wp post meta update "$id" _virtual no
    wp post meta update "$id" _sold_individually no
    wp term set "$id" product_cat --by=slug "$cat" 2>/dev/null || wp post term set "$id" product_cat "$cat" --by=slug
  else
    wp post meta update "$id" _manage_stock yes
    wp post meta update "$id" _stock "$stock"
    wp post meta update "$id" _stock_status instock
    wp post term set "$id" product_cat "$cat" --by=slug 2>/dev/null || true
  fi
  echo "  + $name (#$id)"
}

echo "Creating sample products…"
create_product "Café Bustelo Ground Coffee" "8.99" "" "pantry" "Classic espresso grind — bodega staple." "24" "BEB-COFFEE-01"
create_product "Instant Ramen 6-Pack" "4.50" "3.99" "pantry" "Late-night classic, chicken flavor." "40" "BEB-RAMEN-01"
create_product "Tortilla Chips" "3.75" "" "pantry" "Salted corn chips, shareable bag." "30" "BEB-CHIPS-01"
create_product "Sparkling Water 8-Pack" "6.49" "5.99" "drinks" "Crisp and cold — lime." "28" "BEB-SPARK-01"
create_product "Bottled Sweet Tea" "2.25" "" "drinks" "Southern sweet tea, 20oz." "36" "BEB-TEA-01"
create_product "Cola 12-Pack Cans" "7.99" "" "drinks" "Ice-cold fridge stock." "20" "BEB-COLA-01"
create_product "Paper Towels 6-Roll" "9.50" "8.50" "paper-products" "Absorbent rolls for the apartment." "18" "BEB-TOWEL-01"
create_product "Toilet Paper 12-Pack" "11.99" "" "paper-products" "Soft 2-ply, always needed." "22" "BEB-TP-01"
create_product "Facial Tissues" "2.99" "" "paper-products" "Cube box for the counter." "25" "BEB-TISSUE-01"
create_product "AA Batteries 8-Pack" "6.99" "" "misc" "For remotes and game controllers." "16" "BEB-BATT-01"
create_product "Phone Charging Cable" "9.99" "7.99" "misc" "USB-C cable, 3ft." "14" "BEB-CABLE-01"
create_product "Laundry Detergent Pods" "12.50" "" "misc" "24-count pods — scent free." "12" "BEB-LAUNDRY-01"

echo "Done. Assign product images in Products → edit each item if desired."
