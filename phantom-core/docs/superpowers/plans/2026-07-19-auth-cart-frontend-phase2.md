# Phase 2: Auth System + Cart Endpoints + Frontend Unification

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add nonce-based auth to REST API write endpoints, create 5 WooCommerce cart endpoints, and unify frontend cart operations under Phantom REST.

**Architecture:** Cookie-based nonce auth via `X-Phantom-Nonce` header. 5 new POST cart endpoints wrapping `WC_Cart` methods. Frontend JS enhanced fetch replaces legacy `wc-ajax` + Store API calls.

**Tech Stack:** PHP 8.0+, WordPress REST API, WooCommerce, Vanilla JS (no build tools)

## Global Constraints
- `wp_create_nonce('phantom_api')` — nonce action must be `phantom_api`
- `X-Phantom-Nonce` — header name must match exactly in PHP and JS
- `window.phantomData.api_nonce` — JS property name
- All cart endpoints return `get_cart_data()` format
- No new dependencies (WordPress + WooCommerce only)
- Feature detection (`class_exists('WooCommerce')`) before any WC call

---

### Task 1: Inject nonce into shell.php PhantomData

**Files:**
- Modify: `templates/shell.php:683-684`

**Interfaces:**
- Consumes: `wp_create_nonce('phantom_api')`
- Produces: `window.phantomData.api_nonce` — available to frontend JS

- [ ] **Step 1: Add api_nonce to PhantomData array**

Edit `templates/shell.php` — add `'api_nonce'` right after `'can_edit'`:

```php
$data['can_edit']  = is_user_logged_in() && current_user_can( 'edit_theme_options' );
$data['api_nonce'] = wp_create_nonce( 'phantom_api' );
```

- [ ] **Step 2: Verify syntax**

```bash
php -l templates/shell.php
```
Expected: `No syntax errors detected in templates/shell.php`

- [ ] **Step 3: Commit**

```bash
git add templates/shell.php
git commit -m "feat: inject api_nonce into PhantomData"
```

---

### Task 2: Auth system in rest-controller.php

**Files:**
- Modify: `includes/class-rest-controller.php:348-358`

**Interfaces:**
- Consumes: `$_SERVER['HTTP_X_PHANTOM_NONCE']` or `$request->get_header('X-Phantom-Nonce')`
- Consumes: `wp_verify_nonce()`
- Produces: `verify_nonce(): bool|WP_Error`, `settings_write_permission_check(): bool`, `cart_write_permission_check(): bool`
- Updated `admin_permission_check()` now includes nonce verification

- [ ] **Step 1: Add verify_nonce helper method**

Insert after the `partial_permission_check` method (after line 358):

```php
private function verify_nonce(): bool|\WP_Error {
    if ( ! function_exists( 'wp_verify_nonce' ) ) {
        return true; // WP not loaded — allow in CLI context
    }
    $nonce = '';
    if ( function_exists( 'rest_get_server' ) ) {
        $server = rest_get_server();
        if ( $server ) {
            $request = $server->get_request();
            if ( $request ) {
                $nonce = $request->get_header( 'X-Phantom-Nonce' ) ?? '';
            }
        }
    }
    if ( '' === $nonce ) {
        $nonce = isset( $_SERVER['HTTP_X_PHANTOM_NONCE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_PHANTOM_NONCE'] ) ) : '';
    }
    if ( '' === $nonce || ! wp_verify_nonce( $nonce, 'phantom_api' ) ) {
        return new \WP_Error(
            'rest_forbidden',
            __( 'Invalid or missing nonce.', 'phantom-core' ),
            array( 'status' => 401 )
        );
    }
    return true;
}
```

- [ ] **Step 2: Add settings_write_permission_check**

Insert after `verify_nonce`:

```php
public function settings_write_permission_check(): bool|\WP_Error {
    $nonce = $this->verify_nonce();
    if ( is_wp_error( $nonce ) ) {
        return $nonce;
    }
    return current_user_can( 'edit_theme_options' );
}
```

- [ ] **Step 3: Add cart_write_permission_check**

```php
public function cart_write_permission_check(): bool|\WP_Error {
    return $this->verify_nonce();
}
```

- [ ] **Step 4: Update admin_permission_check to include nonce**

Replace the existing `admin_permission_check`:

```php
public function admin_permission_check(): bool|\WP_Error {
    $nonce = $this->verify_nonce();
    if ( is_wp_error( $nonce ) ) {
        return $nonce;
    }
    return current_user_can( 'manage_options' );
}
```

- [ ] **Step 5: Update permission callbacks on settings write endpoints**

Replace `'permission_callback' => array( $this, 'settings_permission_check' )` with `'permission_callback' => array( $this, 'settings_write_permission_check' )` on:

1. Line 40: `'update_settings'` (POST /settings)
2. Line 59: `'update_setting'` (PUT /settings/{key})
3. Line 65: `'delete_setting'` (DELETE /settings/{key})

The GET route at line 34 (get_settings) and GET route at line 53 (get_setting) keep `settings_permission_check` unchanged.

- [ ] **Step 6: Verify syntax**

```bash
php -l includes/class-rest-controller.php
```
Expected: `No syntax errors detected`

- [ ] **Step 7: Commit**

```bash
git add includes/class-rest-controller.php
git commit -m "feat: add nonce verification to REST API write endpoints"
```

---

### Task 3: Cart write endpoints in rest-controller.php

**Files:**
- Modify: `includes/class-rest-controller.php`

**Interfaces:**
- Consumes: `cart_write_permission_check()` from Task 2
- Consumes: `WC()->cart->add_to_cart()`, `set_quantity()`, `remove_cart_item()`, `apply_coupon()`, `remove_coupon()`
- Produces: `add_to_cart()`, `update_cart_item()`, `remove_cart_item_endpoint()`, `apply_coupon_endpoint()`, `remove_coupon_endpoint()` callback methods

- [ ] **Step 1: Register 5 new routes after the existing GET /cart route (line 293)**

Add right after line 293 (after `get_cart` route closing `)`):

```php
register_rest_route(
    $this->namespace,
    '/cart/add',
    array(
        array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => array( $this, 'add_to_cart_endpoint' ),
            'permission_callback' => array( $this, 'cart_write_permission_check' ),
            'args'                => array(
                'product_id'   => array(
                    'required'          => true,
                    'type'              => 'integer',
                    'sanitize_callback' => 'absint',
                ),
                'quantity'     => array(
                    'required'          => false,
                    'type'              => 'integer',
                    'default'           => 1,
                    'sanitize_callback' => 'absint',
                ),
                'variation_id' => array(
                    'required'          => false,
                    'type'              => 'integer',
                    'default'           => 0,
                    'sanitize_callback' => 'absint',
                ),
                'variation'    => array(
                    'required'          => false,
                    'type'              => 'object',
                    'default'           => array(),
                ),
            ),
        ),
    )
);

register_rest_route(
    $this->namespace,
    '/cart/update',
    array(
        array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => array( $this, 'update_cart_item_endpoint' ),
            'permission_callback' => array( $this, 'cart_write_permission_check' ),
            'args'                => array(
                'key'      => array(
                    'required'          => true,
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'quantity' => array(
                    'required'          => true,
                    'type'              => 'integer',
                    'sanitize_callback' => 'absint',
                ),
            ),
        ),
    )
);

register_rest_route(
    $this->namespace,
    '/cart/remove',
    array(
        array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => array( $this, 'remove_cart_item_endpoint' ),
            'permission_callback' => array( $this, 'cart_write_permission_check' ),
            'args'                => array(
                'key' => array(
                    'required'          => true,
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
            ),
        ),
    )
);

register_rest_route(
    $this->namespace,
    '/cart/coupon',
    array(
        array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => array( $this, 'apply_coupon_endpoint' ),
            'permission_callback' => array( $this, 'cart_write_permission_check' ),
            'args'                => array(
                'code' => array(
                    'required'          => true,
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
            ),
        ),
    )
);

register_rest_route(
    $this->namespace,
    '/cart/remove-coupon',
    array(
        array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => array( $this, 'remove_coupon_endpoint' ),
            'permission_callback' => array( $this, 'cart_write_permission_check' ),
            'args'                => array(
                'code' => array(
                    'required'          => true,
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
            ),
        ),
    )
);
```

- [ ] **Step 2: Add `add_to_cart_endpoint` callback method**

Insert before `get_cart()` (before line 1244):

```php
public function add_to_cart_endpoint( \WP_REST_Request $request ): \WP_REST_Response {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return $this->wp_error( 'woocommerce_inactive', __( 'WooCommerce is not active.', 'phantom-core' ), 400 );
    }
    try {
        $product_id   = $request->get_param( 'product_id' );
        $quantity     = $request->get_param( 'quantity' );
        $variation_id = $request->get_param( 'variation_id' );
        $variation    = $request->get_param( 'variation' );
        $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, (array) $variation );
        if ( false === $cart_item_key ) {
            return $this->wp_error( 'add_to_cart_failed', __( 'Could not add item to cart.', 'phantom-core' ), 400 );
        }
        return new \WP_REST_Response( $this->get_cart_data(), 200 );
    } catch ( \Throwable $e ) {
        return $this->wp_error( 'server_error', __( 'Internal server error.', 'phantom-core' ), 500 );
    }
}
```

- [ ] **Step 3: Add `update_cart_item_endpoint` callback**

```php
public function update_cart_item_endpoint( \WP_REST_Request $request ): \WP_REST_Response {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return $this->wp_error( 'woocommerce_inactive', __( 'WooCommerce is not active.', 'phantom-core' ), 400 );
    }
    try {
        $key      = $request->get_param( 'key' );
        $quantity = $request->get_param( 'quantity' );
        if ( null === WC()->cart->get_cart_item( $key ) ) {
            return $this->wp_error( 'invalid_key', __( 'Cart item not found.', 'phantom-core' ), 400 );
        }
        WC()->cart->set_quantity( $key, $quantity );
        return new \WP_REST_Response( $this->get_cart_data(), 200 );
    } catch ( \Throwable $e ) {
        return $this->wp_error( 'server_error', __( 'Internal server error.', 'phantom-core' ), 500 );
    }
}
```

- [ ] **Step 4: Add `remove_cart_item_endpoint` callback**

```php
public function remove_cart_item_endpoint( \WP_REST_Request $request ): \WP_REST_Response {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return $this->wp_error( 'woocommerce_inactive', __( 'WooCommerce is not active.', 'phantom-core' ), 400 );
    }
    try {
        $key = $request->get_param( 'key' );
        if ( null === WC()->cart->get_cart_item( $key ) ) {
            return $this->wp_error( 'invalid_key', __( 'Cart item not found.', 'phantom-core' ), 400 );
        }
        WC()->cart->remove_cart_item( $key );
        return new \WP_REST_Response( $this->get_cart_data(), 200 );
    } catch ( \Throwable $e ) {
        return $this->wp_error( 'server_error', __( 'Internal server error.', 'phantom-core' ), 500 );
    }
}
```

- [ ] **Step 5: Add `apply_coupon_endpoint` callback**

```php
public function apply_coupon_endpoint( \WP_REST_Request $request ): \WP_REST_Response {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return $this->wp_error( 'woocommerce_inactive', __( 'WooCommerce is not active.', 'phantom-core' ), 400 );
    }
    try {
        $code = $request->get_param( 'code' );
        $result = WC()->cart->apply_coupon( $code );
        if ( is_wp_error( $result ) ) {
            return $this->wp_error( 'coupon_error', $result->get_error_message(), 400 );
        }
        return new \WP_REST_Response( $this->get_cart_data(), 200 );
    } catch ( \Throwable $e ) {
        return $this->wp_error( 'server_error', __( 'Internal server error.', 'phantom-core' ), 500 );
    }
}
```

- [ ] **Step 6: Add `remove_coupon_endpoint` callback**

```php
public function remove_coupon_endpoint( \WP_REST_Request $request ): \WP_REST_Response {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return $this->wp_error( 'woocommerce_inactive', __( 'WooCommerce is not active.', 'phantom-core' ), 400 );
    }
    try {
        $code = $request->get_param( 'code' );
        WC()->cart->remove_coupon( $code );
        return new \WP_REST_Response( $this->get_cart_data(), 200 );
    } catch ( \Throwable $e ) {
        return $this->wp_error( 'server_error', __( 'Internal server error.', 'phantom-core' ), 500 );
    }
}
```

- [ ] **Step 7: Refactor `get_cart()` to use `get_cart_data()`**

Replace the body of `get_cart()` (lines 1244-1284) with:

```php
public function get_cart(): \WP_REST_Response {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return $this->wp_error( 'woocommerce_inactive', __( 'WooCommerce is not active.', 'phantom-core' ), 400 );
    }
    try {
        return new \WP_REST_Response( $this->get_cart_data(), 200 );
    } catch ( \Throwable $e ) {
        return new \WP_REST_Response( array( 'items' => array(), 'total' => '', 'totalItems' => 0, 'currency' => '' ), 200 );
    }
}
```

- [ ] **Step 8: Remove `$items` variable from `get_cart_data()` — wait, `get_cart_data()` is already clean**

No change needed. `get_cart_data()` at line 1420 is already the single source.

- [ ] **Step 9: Verify syntax**

```bash
php -l includes/class-rest-controller.php
```
Expected: `No syntax errors detected`

- [ ] **Step 10: Commit**

```bash
git add includes/class-rest-controller.php
git commit -m "feat: add 5 cart write endpoints (add/update/remove/coupon/remove-coupon)"
```

---

### Task 4: frontend fetchJSON enhancement

**Files:**
- Modify: `frontend/assets/js/phantom-data.js:14-38`

**Interfaces:**
- Consumes: `window.phantomData.api_nonce` (from Task 1)
- Consumes: `apiBase` (existing)
- Produces: Enhanced `fetchJSON(path, options)` — accepts `{ method, body }` options

- [ ] **Step 1: Rewrite fetchJSON to support POST/PUT/DELETE with nonce**

Replace the current `fetchJSON` function (lines 14-38):

```javascript
function fetchJSON(path, options) {
  options = options || {};
  var method = options.method || 'GET';
  var body = options.body;

  // Cache only for GET
  if (method === 'GET' && cache[path] && (Date.now() - cache[path].ts < cacheTTL)) {
    return Promise.resolve(cache[path].data);
  }

  var timeout = options.timeout || 10000;
  var controller = new AbortController();
  var timer = setTimeout(function () { controller.abort(); }, timeout);

  var qIdx = path.indexOf('?');
  var url;
  if (qIdx === -1) {
    url = apiBase + path;
  } else {
    var baseHasQuery = apiBase.indexOf('?') !== -1;
    url = apiBase + path.substring(0, qIdx) + (baseHasQuery ? '&' : '?') + path.substring(qIdx + 1);
  }

  var fetchOpts = {
    method: method,
    signal: controller.signal,
    credentials: 'same-origin'
  };

  if (method !== 'GET') {
    var apiNonce = (window.phantomData && window.phantomData.api_nonce) || (window.PhantomData && window.PhantomData.api_nonce) || '';
    fetchOpts.headers = {
      'Content-Type': 'application/json',
      'X-Phantom-Nonce': apiNonce
    };
    if (body) {
      fetchOpts.body = JSON.stringify(body);
    }
  }

  return fetch(url, fetchOpts).then(function (r) {
    clearTimeout(timer);
    if (!r.ok) throw new Error('HTTP ' + r.status);
    return r.json();
  }).then(function (data) {
    if (method === 'GET') {
      cache[path] = { data: data, ts: Date.now() };
    }
    return data;
  }).catch(function (err) {
    clearTimeout(timer);
    throw err;
  });
}
```

- [ ] **Step 2: Update all existing fetchJSON calls to pass method explicitly (they default to GET — no change needed)**

The existing calls like `fetchJSON('/products?per_page=' + count + '&page=1')` default to `method: 'GET'`, so no change needed.

- [ ] **Step 3: Verify syntax**

```bash
node -e "var fs=require('fs'); var c=fs.readFileSync('frontend/assets/js/phantom-data.js','utf8'); try { new Function(c); console.log('OK'); } catch(e) { console.log('FAIL: ' + e.message); }"
```
Expected: `OK`

- [ ] **Step 4: Commit**

```bash
git add frontend/assets/js/phantom-data.js
git commit -m "feat: enhanced fetchJSON with POST/PUT/DELETE and nonce support"
```

---

### Task 5: Frontend cart unification

**Files:**
- Modify: `frontend/assets/js/phantom-data.js`

**Interfaces:**
- Consumes: Enhanced `fetchJSON()` from Task 4
- Consumes: Spec — replace `wcAjax()` and `storeApiUpdateItem()` with `fetchJSON()` cart calls

- [ ] **Step 1: Replace the add-to-cart handler**

Locate the add-to-cart handler block (around line 821-858). Replace the `wcAjax('add_to_cart', fd)` call:

```javascript
// Add to cart
btn = e.target.closest('.add-to-cart-trigger, .quatity_button_wrapper a.primary_btn');
if (btn) {
  var pid = btn.getAttribute('data-product_id');
  var sku = btn.getAttribute('data-product_sku');
  var qtyInput = btn.closest('.actions') ? btn.closest('.actions').querySelector('.number') : null;
  if (!qtyInput) qtyInput = btn.closest('.quatity_button_wrapper') ? btn.closest('.quatity_button_wrapper').querySelector('input[name="quantity"]') : null;
  var qty = qtyInput ? parseInt(qtyInput.value, 10) || 1 : 1;

  showToast('Adding to cart...', 'info');
  fetchJSON('/cart/add', {
    method: 'POST',
    body: { product_id: parseInt(pid, 10) || 0, quantity: qty }
  }).then(function (data) {
    showToast('Added to cart!', 'success');
    renderCartUI(data);
  }).catch(function (err) {
    showToast('Failed to add to cart', 'error');
    console.error('[PhantomCore] Add to cart error:', err);
  });
  return;
}
```

- [ ] **Step 2: Replace the remove-from-cart handler**

Locate the remove-from-cart block (around line 862-878). Replace:

```javascript
// Remove from cart
key = btn.getAttribute('data-cart-key');
fetchJSON('/cart/remove', {
  method: 'POST',
  body: { key: key }
}).then(function (data) {
  renderCartUI(data);
}).catch(function (err) {
  showToast('Failed to remove item', 'error');
  console.error('[PhantomCore] Remove from cart error:', err);
});
```

- [ ] **Step 3: Replace the quantity update handler**

Locate the quantity minus/plus blocks (around lines 910-935). Replace the `wcAjax` and `storeApiUpdateItem` calls:

In the minus handler:
```javascript
key = btn.getAttribute('data-cart-key');
var numEl = document.querySelector('.number[data-cart-key="' + key + '"]');
if (!numEl) return;
var val = parseInt(numEl.textContent, 10);
if (val > 1) {
  fetchJSON('/cart/update', {
    method: 'POST',
    body: { key: key, quantity: val - 1 }
  }).then(function (data) {
    renderCartUI(data);
  }).catch(function (err) {
    console.error('[PhantomCore] Update cart error:', err);
  });
}
```

In the plus handler:
```javascript
key = btn.getAttribute('data-cart-key');
var numEl2 = document.querySelector('.number[data-cart-key="' + key + '"]');
if (!numEl2) return;
var val2 = parseInt(numEl2.textContent, 10);
fetchJSON('/cart/update', {
  method: 'POST',
  body: { key: key, quantity: val2 + 1 }
}).then(function (data) {
  renderCartUI(data);
}).catch(function (err) {
  console.error('[PhantomCore] Update cart error:', err);
});
```

- [ ] **Step 4: Remove the wcAjax function, getStoreNonce function, and storeApiUpdateItem function**

Delete the entire `getStoreNonce()` function (lines 751-754):
```javascript
// Remove:
function getStoreNonce() {
  const el = document.querySelector('meta[name="wc-nonce"]');
  return el ? el.getAttribute('content') : '';
}
```

Delete the entire `wcAjax()` function:
```javascript
// Remove:
function wcAjax(endpoint, formData) {
  const url = '/?wc-ajax=' + endpoint;
  const nonceEl = document.querySelector('meta[name="wc-nonce"]');
  if (nonceEl) formData.append('security', nonceEl.getAttribute('content'));
  return fetch(url, {
    method: 'POST',
    credentials: 'same-origin',
    body: formData
  }).then(function (r) { return r.json(); });
}
```

Delete the entire `storeApiUpdateItem()` function:
```javascript
// Remove:
function storeApiUpdateItem(key, qty) {
  return fetch('/wp-json/wc/store/v1/cart/update-item', {
    ...
  });
}
```

- [ ] **Step 5: Add `renderCartUI()` helper and remove the separate `fetchJSON('/cart').then(...)` on page load**

The `renderCartUI` function already exists as the callback logic in the existing cart fetches. Replace the inline cart rendering (lines 668-762) with a named function:

```javascript
function renderCartUI(data) {
  if (!data || !data.items) return;
  delete cache['/cart'];
  updateCartCount(data.totalItems);
  var cartInfo = document.querySelector('.shopping-cart .shopping-cart-info');
  if (!cartInfo) return;
  cartInfo.innerHTML = '';
  data.items.forEach(function (item) {
    var div = document.createElement('div');
    div.className = 'shopping-cart-box';
    div.innerHTML = '<div class="media"><a href="' + item.url + '" class="pull-left"><img alt="" src="' + item.image + '" class="img-fluid"></a><div class="media-body"><h6><a href="' + item.url + '">' + item.name + '</a></h6><span class="price">' + item.subtotal + '</span><span class="qty">Qty: ' + item.qty + '</span></div></div>';
    cartInfo.appendChild(div);
  });
  if (data.items.length === 0) {
    cartInfo.innerHTML = '<p>Your cart is empty.</p>';
  }
}
```

Then replace the inline `fetchJSON('/cart')` at line 668:
```javascript
fetchJSON('/cart').then(function (data) {
  renderCartUI(data);
}).catch(function () {});
```

And the one at line 1399:
```javascript
fetchJSON('/cart').then(function (data) {
  if (data.totalItems !== undefined) updateCartCount(data.totalItems);
});
```
Wait — this is for the initial badge count on page load, not the full cart UI. Keep this one but just clear cache first:
```javascript
delete cache['/cart'];
fetchJSON('/cart').then(function (data) {
  if (data.totalItems !== undefined) updateCartCount(data.totalItems);
});
```

- [ ] **Step 6: Remove the `wc-nonce` meta tag dependency from shell.php?**

The `<meta name="wc-nonce">` is still used by WooCommerce's own JS (not our code anymore). We can leave it in shell.php — not our concern.

Actually wait — the `wc-nonce` meta tag at line 521-522 of shell.php is generated by our code. After removing all JS dependencies on it, should we remove it? The WooCommerce legacy AJAX endpoints still need it. Keep it — removing it would break any third-party WC JS.

- [ ] **Step 7: Verify syntax**

```bash
node -e "var fs=require('fs'); var c=fs.readFileSync('frontend/assets/js/phantom-data.js','utf8'); try { new Function(c); console.log('OK'); } catch(e) { console.log('FAIL: ' + e.message); }"
```
Expected: `OK`

- [ ] **Step 8: Commit**

```bash
git add frontend/assets/js/phantom-data.js
git commit -m "feat: unify cart operations under Phantom REST; remove legacy wc-ajax"
```

---

### Task 6: Verification (100/100 gate)

**Files:** All modified files

- [ ] **Step 1: Run PHP syntax check on all modified files**

```bash
php -l templates/shell.php; php -l includes/class-rest-controller.php
```
Expected: Both pass

- [ ] **Step 2: Run JS syntax check**

```bash
node -e "var fs=require('fs'); var c=fs.readFileSync('frontend/assets/js/phantom-data.js','utf8'); try { new Function(c); console.log('OK'); } catch(e) { console.log('FAIL: ' + e.message); }"
```
Expected: `OK`

- [ ] **Step 3: Check for remaining wcAjax references**

```bash
Select-String -Path "frontend/assets/js/phantom-data.js" -Pattern "wcAjax|storeApiUpdateItem|getStoreNonce" -SimpleMatch
```
Expected: No matches

- [ ] **Step 4: Verify nonce consistency**

Check `X-Phantom-Nonce` exists in PHP:
```bash
Select-String -Path "includes/class-rest-controller.php" -Pattern "X-Phantom-Nonce" -SimpleMatch
```
Expected: Found in `verify_nonce()`

Check `api_nonce` exists in JS:
```bash
Select-String -Path "frontend/assets/js/phantom-data.js" -Pattern "api_nonce" -SimpleMatch
```
Expected: Found in enhanced fetchJSON

- [ ] **Step 5: Run full audit grep for remaining legacy patterns**

```bash
Select-String -Path "frontend/assets/js/phantom-data.js" -Pattern "wc-ajax|wc/store/v1/cart" -SimpleMatch
```
Expected: No matches

- [ ] **Step 6: Verify all 5 cart endpoints registered**

```bash
Select-String -Path "includes/class-rest-controller.php" -Pattern "cart/add|cart/update|cart/remove|cart/coupon|cart/remove-coupon" -SimpleMatch
```
Expected: All 5 routes found

- [ ] **Step 7: Verify permission callbacks are correct**

```bash
Select-String -Path "includes/class-rest-controller.php" -Pattern "cart_write_permission_check|settings_write_permission_check" -SimpleMatch
```
Expected: Both found

- [ ] **Step 8: Commit final verification**

```bash
git add -A
git commit -m "chore: verification pass — all syntax clean, legacy references removed"
```
