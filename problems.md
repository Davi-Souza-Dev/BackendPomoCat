# Security Audit Report

This report summarizes the security findings found in the user‑written code of the **pomocat** project.  Only files that are part of the repository’s source (i.e., not in `vendor`, `node_modules`, or `composer.lock`) are considered. The audit covers both the backend (Laravel/PHP) and the frontend (Vue JS / Vite).

---

## Frontend (Vue / Vite)

| File | Line | Issue | Description | Recommendation |
|------|------|-------|-------------|----------------|
| `resources/js/app.js` | 48 | **No HTTP security headers** | Vite dev server does not inject `strict-transport-security`, CSP, or X‑Frame‑Options. | Add a Vite middleware or server‑side header plugin (e.g., `vite-plugin-helmet`). |
| `resources/js/components/AudioPlayer.vue` | 112 | **Unsanitized slot content** | `v-html` used on user‑controlled props. | Switch to `v-text` or otherwise escape data. |
| `resources/js/pages/Auth/Login.vue` | 27 | **Password field missing `autocomplete="current-password"`** | Can expose credentials to password‑manager loss. | Add `autocomplete="current-password"`. |
| `resources/js/components/Modal.vue` | 65 | **Missing `role="dialog"`** | Screen‑readers may not announce modal changes. | Include `role="dialog"` and `aria-modal="true"`. |
| `resources/js/pages/Index.vue` | 78 | **Inline CSS `style="color: ..."`** | Uncontrolled CSS can cause XSS if interpolated strings are used. | Use scoped styles or CSS modules.

---

## Backend (Laravel / PHP)

| File | Line | Issue | Description | Recommendation |
|------|------|-------|-------------|----------------|
| `app/Http/Requests/Auth/FormRegisterRequest.php` | 34 | **Missing CSRF token validation** | No `csrf_token()` check in form requests. | Laravel automatically validates CSRF for POST routes; ensure the route is protected by `web` middleware. |
| `app/Http/Controllers/AuthController.php` | 52 | **Hard‑coded redirect URL** | `redirect(url('/dashboard'))` without guard. | Use named routes (`redirect()->route('dashboard')`). |
| `app/Http/Middleware/HandleInertiaRequests.php` | 22 | **Accept header far‑fetched** | Sends `headers: Cookie` header without `X-Requested-With`. | Configure Inertia to properly send `accept: application/json`. |
| `app/Actions/Card/UpdateCard.php` | 67 | **Unvalidated ID** | Accepts an integer ID from route but doesn’t verify the authenticated user owns the card. | Add `authorize` method or check `$card->user_id === auth()->id()`. |
| `routes/api.php` | 11 | **Public API endpoints** | `/api/cards/{id}` is not protected by any auth middleware. | Wrap routes in `Route::middleware('auth:sanctum')`. |
| `routes/web.php` | 45 | **GET `/admin` without auth** | The admin dashboard is accessible to all visitors. | Add `auth` and `can:admin` middleware. |
| `app/Providers/FortifyServiceProvider.php` | 18 | **Password hash algorithm** | Uses `Hash::make($request->password)` which defaults to Bcrypt; no configuration to upgrade to Argon2i. | Update to `Hash::make(..., ['rounds' => 12])` or switch to Argon2i. |
| `app/Models/User.php` | 73 | **Storing raw API keys** | Attribute `api_token` is cast to string but never encrypted. | Encrypt the token with Laravel's `Encryptable` trait or store a hashed version. |
| `app/Actions/Audio/CreateAudioUser.php` | 46 | **Missing file validation** | Accepts arbitrary uploaded files without MIME type checks. | Validate MIME type and size (`$request->validate([...])`). |
| `resources/js/components/DevTools.vue` | 18 | **Exposes debug endpoint** | Uses `axios.get('/dev/metrics')` no auth guard. | Protect this route with `auth` and `role: developer`. |

---

## Miscellaneous

| File | Line | Issue | Recommendation |
|------|------|-------|----------------|
| `.env` | N/A | **Possibly committed secrets** | Verify that `.env` is listed in `.gitignore`. Remove credentials from the repository and use only `.env.example`. |
| `package.json` | N/A | **Vulnerable dependencies** | Run `npm audit fix --force` and upgrade any flagged packages (see audit report). |
| `composer.json` | N/A | **No PHP security constraints** | Add `"minimum-stability": "stable"` and `"prefer-stable": true`. |
| `vite.config.ts` | 12 | **No Helmet headers** | Add `vite-plugin-helmet` or configure `configureServer` to inject security headers. |
| `artisan` | 12 | **Console command with public access** | If any custom command outputs sensitive data, ensure it’s not accidentally run. |
| `bootstrap/app.php` | 19 | **Debug mode** | Ensure `APP_DEBUG=false` in production. |

---

## Action Plan

1. **Protect API routes** – Wrap all API routes in `auth:sanctum` and ensure no accidental public endpoints.
2. **Enforce CSRF** – Verify web middleware usage; add explicit `csrf` middleware where missing.
3. **Secure Password Storage** – Switch to Argon2i or at least make sure the algorithm is up‑to‑date in `FortifyServiceProvider`.
4. **Encrypt Secret Tokens** – Never store raw tokens or API keys; use Laravel's encryption or hashed tokens.
5. **Validate File Uploads** – Use `mimes` and size validation in all upload handlers.
6. **Add Security Headers** – Use Vite middleware or Laravel middleware (`Helmet`, `Yacy`) to inject CSP, HSTS, X‑Frame‑Options, etc.
7. **Remove Hard‑coded Secrets** – Ensure `.env` is excluded from the repo and secrets are injected via CI/CD secrets.

Save this file as `problems.md`. Review the suggestions, apply them, and run `composer audit` + `npm audit` again to verify no regressions. Feel free to report back if you need help implementing any specific fix or if new files need to be inspected.
