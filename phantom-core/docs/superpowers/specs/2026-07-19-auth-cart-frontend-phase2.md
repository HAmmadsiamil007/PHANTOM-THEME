# Phase 2: Auth System + Cart Endpoints + Frontend Unification

## Status
- **Date**: 2026-07-19
- **Status**: Approved design
- **Priority**: High

## 1. Auth System

### Nonce Flow
1. `shell.php` generates `wp_create_nonce('phantom_api')` and embeds in `PhantomData` JSON
2. `phantom-data.js` reads `window.phantomData.api_nonce` and sends as `X-Phantom-Nonce` header
3. `class-rest-controller.php` validates the nonce via `wp_verify_nonce()` on write endpoints
4. Public GET endpoints skip nonce (unchanged)

### Permission Model

Principle: Nonce on write endpoints only (CSRF protection). GET endpoints keep existing permission logic unchanged.

| Permission callback | Nonce | Capability | Applied to |
|---|---|---|---|
| `'__return_true'` | No | None | Public GET: products, posts, pages, menus, categories, attributes, variations, reviews, page-data, cart |
| `settings_permission_check` (existing) | No | `edit_theme_options` | GET settings, schema, options, partial |
| `settings_write_permission_check` | **Yes** | `edit_theme_options` | POST /settings, PUT/DELETE /settings/{key} |
| `admin_permission_check` (existing) | **Yes** | `manage_options` | POST /import, /cache/flush, create/update/delete product, GET /export |
| `cart_write_permission_check` | **Yes** | None (guest OK) | POST /cart/add/update/remove/remove-coupon |

Key changes from current:
- Settings write endpoints add nonce check on top of existing `edit_theme_options`
- Admin write endpoints add nonce check on top of existing `manage_options`
- Cart write endpoints are new, require nonce but no capability (guest checkout)
- All GET endpoints keep their existing permission callbacks unchanged

### Implementation
- Add `verify_nonce()` helper: reads `X-Phantom-Nonce` header, calls `wp_verify_nonce($nonce, 'phantom_api')`
- If nonce missing or invalid → return `WP_Error` with 401 status (WP REST API handles this as forbidden)
- Create combined callbacks that call `verify_nonce()` then delegate to existing check
- Add `cart_write_permission_check()` for new cart endpoints
- `partial_permission_check` remains unchanged (GET, `edit_theme_options`, no nonce)

## 2. Cart Endpoints

### Current State
- `GET /phantom/v1/cart` exists (returns cart data)
- `get_cart_data()` private helper duplicates `get_cart()` logic
- No write cart endpoints

### New Endpoints

All under `phantom/v1` namespace, all require `cart_write_permission_check`:

| Method | Route | Body params | WooCommerce wrapper |
|---|---|---|---|
| `POST` | `/cart/add` | `product_id` (int, required), `quantity` (int, default 1), `variation_id` (int, optional), `variation` (object, optional) | `WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation )` |
| `POST` | `/cart/update` | `key` (string, required), `quantity` (int, required) | `WC()->cart->set_quantity( $key, $quantity )` |
| `POST` | `/cart/remove` | `key` (string, required) | `WC()->cart->remove_cart_item( $key )` |
| `POST` | `/cart/coupon` | `code` (string, required) | `WC()->cart->apply_coupon( $code )` |
| `POST` | `/cart/remove-coupon` | `code` (string, required) | `WC()->cart->remove_coupon( $code )` |

Note: `POST /cart/remove-coupon` is used instead of `DELETE /cart/coupon` because DELETE with a JSON body is non-standard and less reliable across fetch implementations.

### Response Format
All cart write endpoints return the **updated cart data** directly (`get_cart_data()`). No separate GET request needed:
```json
{
  "items": [
    {
      "key": "abc123",
      "id": 42,
      "name": "Product Name",
      "price": "$10.00",
      "qty": 2,
      "subtotal": "$20.00",
      "total": "$20.00",
      "image": "https://...",
      "url": "https://..."
    }
  ],
  "total": "$100.00",
  "totalItems": 3,
  "currency": "$"
}
```

### Cleanup
- Refactor `get_cart()` (public endpoint) to call `get_cart_data()` internally, eliminating duplicate code
- `get_cart_data()` becomes the single source of truth for cart state

### Error Handling
- WooCommerce inactive → 400 `woocommerce_inactive`
- Invalid/missing params → 400 `missing_param` / `invalid_param`
- `add_to_cart()` returns false → 400 `add_to_cart_failed`
- `apply_coupon()` returns WP_Error → bubble the message
- Generic try/catch → 500 fallback

## 3. Frontend Rewrite

### Changes to `phantom-data.js`

#### `fetchJSON()` enhancement
- Accept optional `method` param (default `'GET'`) and `body` param
- For POST/PUT/DELETE: add `Content-Type: application/json` header and `X-Phantom-Nonce` header
- Keep cache logic for GET requests only; skip cache for writes

#### Cart operations rewrite
Replace 3-way cart system with unified Phantom REST calls:

| Current code | Replaced with |
|---|---|
| `wcAjax('add_to_cart', fd)` | `fetchJSON('/cart/add', { method: 'POST', body: data })` |
| `wcAjax('remove_from_cart', fd)` | `fetchJSON('/cart/remove', { method: 'POST', body: data })` |
| `storeApiUpdateItem(key, qty)` | `fetchJSON('/cart/update', { method: 'POST', body: { key, quantity } })` |
| `getStoreNonce()` | removed |
| `wcAjax()` function | removed entirely |
| `storeApiUpdateItem()` | removed entirely |

#### Cart refresh after mutation
- Write endpoints return updated cart data directly in the response body
- On success: UI updates directly from the response data (items, total, totalItems)
- On error: show toast, keep current UI
- Cache invalidation: `delete cache['/cart']` after each successful write to avoid stale data on subsequent manual GET /cart calls

### Changes to `shell.php`
- Add `'api_nonce' => wp_create_nonce('phantom_api')` to the PhantomData JSON block in `inject_bridge()`

### No changes to
- `phantom-bridge.js` (read-only settings proxy, unaffected)
- CSS / template files
- Customizer / settings registration

## Files Modified
- `includes/class-rest-controller.php` — auth + 5 cart endpoints + permission cleanup
- `frontend/assets/js/phantom-data.js` — fetchJSON enhancement + cart unification + wcAjax removal
- `templates/shell.php` — add api_nonce to PhantomData

## Files Unchanged
- `includes/class-settings-registry.php`
- `includes/class-custom-css.php`
- `includes/class-customizer.php`
- `frontend/assets/js/phantom-bridge.js`
- All `includes/custom-css/*.php`
- All `includes/custom-controls/*.php`
- `woocommerce/*`

## Dependencies
- WordPress REST API (already active)
- WooCommerce (must be active for cart endpoints)
- PHP 8.0+ (already required by plugin)

## Testing Notes
- Cart endpoints can be tested via curl with `X-Phantom-Nonce` header
- Nonce value obtainable from page source (`<script id="phantom-bridge-data">` JSON)
- Frontend tests: manual click-through add/update/remove/coupon flows
