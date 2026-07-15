# Optix Framework REST API

**Namespace:** `optix/v1`  
**Base URL:** `https://example.com/wp-json/optix/v1`  
**Authentication:** WordPress cookie authentication (logged-in admin) or Application Password.  
**Permission:** All routes require `manage_options` capability.

---

## 1. GET /settings

List all registered settings with optional filtering and pagination.

**Endpoint:** `GET /wp-json/optix/v1/settings`

**Query Parameters:**

| Param     | Type    | Default | Description                        |
|-----------|---------|---------|------------------------------------|
| `section` | string  | `""`    | Filter by section name             |
| `per_page`| integer | `50`    | Items per page (1–500)             |
| `page`    | integer | `1`     | Page number                        |

**Response headers:** `X-WP-Total`, `X-WP-TotalPages`

**Example request:**

```bash
curl -X GET "https://example.com/wp-json/optix/v1/settings?section=general&per_page=10&page=1" \
  -H "Authorization: Bearer <application_password>"
```

**Example response:**

```json
[
  {
    "key": "site_title",
    "value": "My Site",
    "default": "",
    "type": "string",
    "section": "general",
    "label": "Site Title"
  },
  {
    "key": "enable_search",
    "value": true,
    "default": true,
    "type": "boolean",
    "section": "general",
    "label": "Enable Search"
  }
]
```

**Error responses:**

| Status | Code             | Description                     |
|--------|------------------|---------------------------------|
| 401    | `rest_not_logged_in` | Authentication required     |
| 403    | `rest_cannot_manage_options` | Insufficient permissions |

---

## 2. POST /settings

Bulk update multiple settings at once.

**Endpoint:** `POST /wp-json/optix/v1/settings`

**Body parameters:**

| Param      | Type   | Required | Description                         |
|------------|--------|----------|-------------------------------------|
| `settings` | object | yes      | Object of key-value pairs to update |

**Example request:**

```bash
curl -X POST "https://example.com/wp-json/optix/v1/settings" \
  -H "Authorization: Bearer <application_password>" \
  -H "Content-Type: application/json" \
  -d '{
    "settings": {
      "site_title": "New Title",
      "enable_search": false
    }
  }'
```

**Example response (200):**

```json
{
  "updated": {
    "site_title": "New Title",
    "enable_search": false
  },
  "errors": []
}
```

**Example response with unknown keys (207):**

```json
{
  "updated": {
    "site_title": "New Title"
  },
  "errors": [
    "Unknown setting key: nonexistent_key"
  ]
}
```

**Error responses:**

| Status | Code              | Description                                  |
|--------|-------------------|----------------------------------------------|
| 400    | `invalid_settings`| `settings` must be a non-empty object        |
| 401    | `rest_not_logged_in` | Authentication required                   |
| 403    | `rest_cannot_manage_options` | Insufficient permissions           |
| 207    | —                 | Partial success (some keys unknown)          |

---

## 3. POST /settings/batch

Alias for bulk update. Identical behavior to `POST /settings`.

**Endpoint:** `POST /wp-json/optix/v1/settings/batch`

**Body parameters:**

| Param      | Type   | Required | Description                         |
|------------|--------|----------|-------------------------------------|
| `settings` | object | yes      | Object of key-value pairs to update |

**Example request:**

```bash
curl -X POST "https://example.com/wp-json/optix/v1/settings/batch" \
  -H "Authorization: Bearer <application_password>" \
  -H "Content-Type: application/json" \
  -d '{
    "settings": {
      "theme_color": "#ff6600",
      "footer_text": "© 2026"
    }
  }'
```

**Example response (200):**

```json
{
  "updated": {
    "theme_color": "#ff6600",
    "footer_text": "© 2026"
  },
  "errors": []
}
```

**Error responses:** Same as `POST /settings`.

---

## 4. GET /settings/{key}

Retrieve a single setting by its registry key.

**Endpoint:** `GET /wp-json/optix/v1/settings/{key}`

**Path parameters:**

| Param | Type   | Required | Description  |
|-------|--------|----------|--------------|
| `key` | string | yes      | Setting key  |

**Example request:**

```bash
curl -X GET "https://example.com/wp-json/optix/v1/settings/site_title" \
  -H "Authorization: Bearer <application_password>"
```

**Example response:**

```json
{
  "key": "site_title",
  "value": "My Site",
  "default": "",
  "type": "string",
  "section": "general",
  "label": "Site Title"
}
```

**Error responses:**

| Status | Code       | Description                          |
|--------|------------|--------------------------------------|
| 401    | `rest_not_logged_in` | Authentication required         |
| 403    | `rest_cannot_manage_options` | Insufficient permissions |
| 404    | `not_found`| Setting key not found in registry    |

---

## 5. PUT /settings/{key}

Update a single setting by its registry key.

**Endpoint:** `PUT /wp-json/optix/v1/settings/{key}`

**Path parameters:**

| Param | Type   | Required | Description  |
|-------|--------|----------|--------------|
| `key` | string | yes      | Setting key  |

**Body parameters:**

| Param   | Type | Required | Description   |
|---------|------|----------|---------------|
| `value` | mixed | yes      | Setting value |

**Example request:**

```bash
curl -X PUT "https://example.com/wp-json/optix/v1/settings/site_title" \
  -H "Authorization: Bearer <application_password>" \
  -H "Content-Type: application/json" \
  -d '{
    "value": "Updated Site Title"
  }'
```

**Example response:**

```json
{
  "key": "site_title",
  "value": "Updated Site Title",
  "default": "",
  "type": "string",
  "section": "general",
  "label": "Site Title"
}
```

**Error responses:**

| Status | Code       | Description                          |
|--------|------------|--------------------------------------|
| 400    | `rest_missing_callback_param` | Missing `value` parameter |
| 401    | `rest_not_logged_in` | Authentication required         |
| 403    | `rest_cannot_manage_options` | Insufficient permissions |
| 404    | `not_found`| Setting key not found in registry    |

---

## 6. DELETE /settings/{key}

Delete (reset) a single setting to its registry default.

**Endpoint:** `DELETE /wp-json/optix/v1/settings/{key}`

**Path parameters:**

| Param | Type   | Required | Description  |
|-------|--------|----------|--------------|
| `key` | string | yes      | Setting key  |

**Example request:**

```bash
curl -X DELETE "https://example.com/wp-json/optix/v1/settings/site_title" \
  -H "Authorization: Bearer <application_password>"
```

**Example response:**

```json
{
  "key": "site_title",
  "default": "",
  "reset": true
}
```

**Error responses:**

| Status | Code       | Description                          |
|--------|------------|--------------------------------------|
| 401    | `rest_not_logged_in` | Authentication required         |
| 403    | `rest_cannot_manage_options` | Insufficient permissions |
| 404    | `not_found`| Setting key not found in registry    |

---

## 7. GET /schema

Get the full settings schema (all registered entries with their metadata, excluding current values).

**Endpoint:** `GET /wp-json/optix/v1/schema`

**Example request:**

```bash
curl -X GET "https://example.com/wp-json/optix/v1/schema" \
  -H "Authorization: Bearer <application_password>"
```

**Example response:**

```json
{
  "site_title": {
    "section": "general",
    "type": "string",
    "default": "",
    "label": "Site Title"
  },
  "enable_search": {
    "section": "general",
    "type": "boolean",
    "default": true,
    "label": "Enable Search"
  },
  "theme_color": {
    "section": "appearance",
    "type": "string",
    "default": "#000000",
    "label": "Theme Color"
  }
}
```

**Error responses:**

| Status | Code       | Description                          |
|--------|------------|--------------------------------------|
| 401    | `rest_not_logged_in` | Authentication required         |
| 403    | `rest_cannot_manage_options` | Insufficient permissions |

---

## 8. GET /profile

Get the currently active profile name.

**Endpoint:** `GET /wp-json/optix/v1/profile`

**Example request:**

```bash
curl -X GET "https://example.com/wp-json/optix/v1/profile" \
  -H "Authorization: Bearer <application_password>"
```

**Example response:**

```json
{
  "profile": "default"
}
```

**Error responses:**

| Status | Code       | Description                          |
|--------|------------|--------------------------------------|
| 401    | `rest_not_logged_in` | Authentication required         |
| 403    | `rest_cannot_manage_options` | Insufficient permissions |

---

## 9. PUT /profile

Set the active profile.

**Endpoint:** `PUT /wp-json/optix/v1/profile`

**Body parameters:**

| Param     | Type   | Required | Description    |
|-----------|--------|----------|----------------|
| `profile` | string | yes      | Profile name   |

**Example request:**

```bash
curl -X PUT "https://example.com/wp-json/optix/v1/profile" \
  -H "Authorization: Bearer <application_password>" \
  -H "Content-Type: application/json" \
  -d '{
    "profile": "kids-collection"
  }'
```

**Example response:**

```json
{
  "profile": "kids-collection"
}
```

**Error responses:**

| Status | Code               | Description                              |
|--------|--------------------|------------------------------------------|
| 400    | `invalid_profile`  | Profile name must be a non-empty string  |
| 401    | `rest_not_logged_in` | Authentication required                 |
| 403    | `rest_cannot_manage_options` | Insufficient permissions         |
| 404    | `profile_not_found`| Profile directory does not exist          |

---

## 10. POST /export

Export all current settings as a JSON document.

**Endpoint:** `POST /wp-json/optix/v1/export`

**Example request:**

```bash
curl -X POST "https://example.com/wp-json/optix/v1/export" \
  -H "Authorization: Bearer <application_password>"
```

**Example response:**

```json
{
  "version": "1.0.0",
  "exported": "2026-07-14 19:30:00",
  "settings": {
    "site_title": "My Site",
    "enable_search": true,
    "theme_color": "#ff6600"
  }
}
```

**Error responses:**

| Status | Code       | Description                          |
|--------|------------|--------------------------------------|
| 401    | `rest_not_logged_in` | Authentication required         |
| 403    | `rest_cannot_manage_options` | Insufficient permissions |

---

## 11. POST /import

Import settings from a JSON object.

**Endpoint:** `POST /wp-json/optix/v1/import`

**Body parameters:**

| Param      | Type   | Required | Description                              |
|------------|--------|----------|------------------------------------------|
| `settings` | object | yes      | Object of key-value pairs to import      |

**Example request:**

```bash
curl -X POST "https://example.com/wp-json/optix/v1/import" \
  -H "Authorization: Bearer <application_password>" \
  -H "Content-Type: application/json" \
  -d '{
    "settings": {
      "site_title": "Imported Title",
      "enable_search": false
    }
  }'
```

**Example response (200):**

```json
{
  "imported": ["site_title", "enable_search"],
  "errors": []
}
```

**Example response with unknown keys (207):**

```json
{
  "imported": ["site_title"],
  "errors": [
    "Unknown setting key: nonexistent_key"
  ]
}
```

**Notes:**
- Import writes directly to `Options_Manager` (bypassing the registry `set()` method), then flushes the registry cache.
- Only keys present in the registry are accepted — unknown keys are returned as errors.

**Error responses:**

| Status | Code              | Description                                  |
|--------|-------------------|----------------------------------------------|
| 400    | `invalid_settings`| `settings` must be a non-empty object        |
| 401    | `rest_not_logged_in` | Authentication required                   |
| 403    | `rest_cannot_manage_options` | Insufficient permissions           |
| 207    | —                 | Partial success (some keys unknown)          |

---

## 12. GET /profiles

List all available profiles in the theme's `profiles/` directory.

**Endpoint:** `GET /wp-json/optix/v1/profiles`

**Example request:**

```bash
curl -X GET "https://example.com/wp-json/optix/v1/profiles" \
  -H "Authorization: Bearer <application_password>"
```

**Example response:**

```json
[
  {
    "name": "default",
    "active": true,
    "has_css": true,
    "has_js": true
  },
  {
    "name": "kids-collection",
    "active": false,
    "has_css": true,
    "has_js": false
  }
]
```

**Response fields:**

| Field     | Type    | Description                                 |
|-----------|---------|---------------------------------------------|
| `name`    | string  | Profile directory name                      |
| `active`  | boolean | Whether this is the currently active profile|
| `has_css` | boolean | Whether `assets/css/style.css` exists       |
| `has_js`  | boolean | Whether `assets/js/script.js` exists        |

**Error responses:**

| Status | Code       | Description                          |
|--------|------------|--------------------------------------|
| 401    | `rest_not_logged_in` | Authentication required         |
| 403    | `rest_cannot_manage_options` | Insufficient permissions |

---

## 13. POST /presets

Save current settings (or provided data) as a named preset.

**Endpoint:** `POST /wp-json/optix/v1/presets`

**Body parameters:**

| Param  | Type   | Required | Description                                                    |
|--------|--------|----------|----------------------------------------------------------------|
| `name` | string | yes      | Preset name                                                    |
| `data` | object | no       | Key-value data to save. If omitted, current settings are used. |

**Example request (snapshot current settings):**

```bash
curl -X POST "https://example.com/wp-json/optix/v1/presets" \
  -H "Authorization: Bearer <application_password>" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "my-preset"
  }'
```

**Example request (with explicit data):**

```bash
curl -X POST "https://example.com/wp-json/optix/v1/presets" \
  -H "Authorization: Bearer <application_password>" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "dark-theme",
    "data": {
      "theme_color": "#111111",
      "text_color": "#ffffff"
    }
  }'
```

**Example response:**

```json
{
  "name": "my-preset",
  "saved": true
}
```

**Error responses:**

| Status | Code           | Description                                |
|--------|----------------|--------------------------------------------|
| 400    | `invalid_name` | Preset name must be a non-empty string     |
| 400    | `invalid_data` | Preset data must be an object if provided  |
| 401    | `rest_not_logged_in` | Authentication required               |
| 403    | `rest_cannot_manage_options` | Insufficient permissions       |
| 500    | `save_failed`  | Failed to save preset                      |

---

## 14. GET /presets/{name}

Load a previously saved preset by name.

**Endpoint:** `GET /wp-json/optix/v1/presets/{name}`

**Path parameters:**

| Param  | Type   | Required | Description  |
|--------|--------|----------|--------------|
| `name` | string | yes      | Preset name  |

**Example request:**

```bash
curl -X GET "https://example.com/wp-json/optix/v1/presets/my-preset" \
  -H "Authorization: Bearer <application_password>"
```

**Example response:**

```json
{
  "name": "my-preset",
  "values": {
    "site_title": "My Site",
    "enable_search": true,
    "theme_color": "#ff6600"
  }
}
```

**Note:** Loading a preset returns the stored values but does **not** apply them. Use the returned values with `POST /settings` or `POST /import` to apply.

**Error responses:**

| Status | Code       | Description                          |
|--------|------------|--------------------------------------|
| 401    | `rest_not_logged_in` | Authentication required         |
| 403    | `rest_cannot_manage_options` | Insufficient permissions |
| 404    | `not_found`| Preset not found                     |

---

## 15. POST /cache/flush

Flush all Optix caches (settings registry cache + engine cache).

**Endpoint:** `POST /wp-json/optix/v1/cache/flush`

**Example request:**

```bash
curl -X POST "https://example.com/wp-json/optix/v1/cache/flush" \
  -H "Authorization: Bearer <application_password>"
```

**Example response:**

```json
{
  "success": true,
  "message": "All Optix caches flushed."
}
```

**Error responses:**

| Status | Code       | Description                          |
|--------|------------|--------------------------------------|
| 401    | `rest_not_logged_in` | Authentication required         |
| 403    | `rest_cannot_manage_options` | Insufficient permissions |

---

## Route Summary

| #  | Method   | Endpoint              | Description                       |
|----|----------|-----------------------|-----------------------------------|
| 1  | `GET`    | `/settings`           | List all settings                 |
| 2  | `POST`   | `/settings`           | Bulk update settings              |
| 3  | `POST`   | `/settings/batch`     | Batch update (alias)              |
| 4  | `GET`    | `/settings/{key}`     | Get single setting                |
| 5  | `PUT`    | `/settings/{key}`     | Update single setting             |
| 6  | `DELETE` | `/settings/{key}`     | Reset single setting              |
| 7  | `GET`    | `/schema`             | Get full settings schema          |
| 8  | `GET`    | `/profile`            | Get active profile                |
| 9  | `PUT`    | `/profile`            | Set active profile                |
| 10 | `POST`   | `/export`             | Export all settings               |
| 11 | `POST`   | `/import`             | Import settings                   |
| 12 | `GET`    | `/profiles`           | List available profiles           |
| 13 | `POST`   | `/presets`            | Save current settings as preset   |
| 14 | `GET`    | `/presets/{name}`     | Load preset by name               |
| 15 | `POST`   | `/cache/flush`        | Flush all caches                  |
