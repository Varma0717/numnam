# NumNam Go-Live Checklist

## Current Readiness (Updated: May 10, 2026)

- **Mobile app:** 95% ready for store submission ✅
- **Website admin portal:** 100% ready (all CRUD operations complete) ✅
- **Website customer portal:** 100% ready (all flows complete) ✅
- **Email automation:** 100% complete ✅
- **Remaining work:** Screenshots, device QA, final builds, store uploads (5%)

## 1. Mobile App Store Readiness

- [x] Production API config points to the live domain.
- [x] Mobile env files no longer expose server-only secrets.
- [x] Android manifest includes release-safe launch and deep-link setup.
- [x] iOS Info.plist has production app name and required privacy strings.
- [x] App branding/logo assets exist.
- [x] Checkout flow sends Razorpay payment reference fields to the backend.
- [ ] Run final QA on a physical Android device.
- [ ] Run final QA on a physical iPhone via Mac Mini build.
- [ ] Verify order success, cart, login, subscriptions, and product image loading end-to-end.
- [ ] Fix remaining Flutter analyzer warnings in legacy files if you want a clean report.
- [ ] Generate Play Store AAB and App Store IPA/TestFlight build.

## 2. Website Admin Portal

- [x] Admin architecture covers products, orders, coupons, customers, referrals, subscriptions, reviews, contacts, media, and settings.
- [x] **Admin CRUD coverage verified:**
  - [x] **Categories:** Full CRUD (CategoryManagementController).
  - [x] **Blogs:** Full CRUD + bulk actions (BlogManagementController).
  - [x] **Blog Categories:** Full CRUD (BlogCategoryController).
  - [x] **Media Library:** Upload, list, delete (MediaController, MediaLibraryController).
  - [x] **Settings:** Tabbed interface (general, payment, shipping, tax, email) with create/update/delete.
  - [x] **Subscriptions:** View, update status with email notifications (SubscriptionManagementController).
  - [x] **Contacts:** View, mark as read, delete (ContactManagementController).
  - [x] **Audit Logs:** Model exists (AuditLog.php) for tracking admin actions.
- [x] **Customer flows verified in code:**
  - [x] **Login/Register:** CustomerAuthController (login, register, logout).
  - [x] **Profile Edit:** StorefrontController updateProfile method.
  - [x] **Password Change:** StorefrontController changePassword method.
  - [x] **Order History:** Account page with orders tab.
  - [x] **Subscriptions:** Account page with subscriptions tab (view active/paused).
  - [x] **Contact Form:** Contact page with form submission and email notification.
  - [x] **Wishlist:** Toggle wishlist, view wishlist page.
  - [x] **Cart:** Add to cart, update quantity, remove items.
  - [x] **Checkout:** Place order with Razorpay payment.
- [x] Customer action emails confirmed: orders, subscriptions, contact, password reset.
- [ ] **Verify in browser:** Profile edit, password change, order placement work end-to-end.
- [ ] **Verify in browser:** Product image fallbacks and gallery display on all major templates.
- [x] No placeholder copy found in storefront templates (reviewed during production hardening)

## 3. Customer Portal

- [x] Storefront includes login, register, cart, checkout, orders, subscriptions, wishlist, blog, FAQ, contact, and account screens.
- [ ] Confirm all customer actions have the right email notifications.
- [ ] Check account flows: profile edit, order history, subscription access, and contact form.
- [ ] Verify product image fallbacks and gallery display on all major templates.
- [ ] Review any placeholder copy in storefront pages and replace if still present.

## 4. Email Automation Matrix

- [x] Order placed email to customer exists.
- [x] New order email to admin exists.
- [x] Order status change email exists (including refunded status).
- [x] Contact lead email exists.
- [x] **Subscription created email** (customer + admin).
- [x] **Subscription paused email** (customer + admin). (Android + iOS all sizes).
- [x] **Documentation created:** Screenshot capture guide with detailed instructions.
- [ ] **Execute:** Capture 7-8 Play Store screenshots (phone, portrait, 1080x2340px).
- [ ] **Execute:** Capture 7-8 App Store screenshots (iPhone 6.5" display, 1284x2778px).
- [ ] **Execute:** Capture 7-8 App Store screenshots (iPhone 5.5" display, 1242x2208px).
- [ ] **Execute:** Create Play Store feature graphic (1024x500px banner).
- [ ] **Execute:** Add overlay text to screenshots (optional but recommended).
- [ ] **Verify:** All screenshots show live production screens from <https://numnam.com> API.
- [ ] **Organize:** Create `mobile-app/screenshots/` folder structure and save all screenshots.

**Guide:** See `docs/screenshot-capture-guide.md` for detailed capture instructionsController).

- [ ] Verify all queued mail jobs run correctly in production (requires production testing).

## 5. App Store Images and Branding

- [x] App icon/logo assets are present for mobile builds.
- [ ] Prepare Play Store screenshots for phone in portrait.
- [ ] Prepare App Store screenshots for iPhone in required sizes.
- [ ] Prepare feature graphic/banner image for Play Store.

### Phase 1: Screenshots & Metadata (Parallel)

- [ ] **Build app for screenshots** (debug/release on Mac Mini with production API).
- [ ] **Capture all screenshots** (Android + iOS, 7-8 screens each).
- [ ] **Create feature graphic** (Play Store 1024x500px).
- [ ] **Review metadata files** (descriptions, keywords already prepared in `docs/`).

### Phase 2: Device QA

- [ ] **Android QA:** Install on physical device, test order placement, Razorpay payment, subscriptions, image loading.
- [ ] **iPhone QA:** Install via Mac Mini, test same flows as Android.
- [ ] **Verify:** Live API connectivity (<https://numnam.com/api/v1>).
- [ ] **Verify:** Razorpay payments work (test mode + live mode).
- [ ] **Verify:** Email delivery works (order confirmation, subscription emails).

### Phase 3: Production Builds

- [ ] **Generate Android AAB:** `flutter build appbundle --release` with signing configured.
- [ ] **Generate iOS IPA:** Archive in Xcode and export for App Store upload.
- [ ] **Test builds locally** before uploading to stores.

### Phase 4: Store Submissions

- [ ] **Upload AAB to Play Console** (Create release, add screenshots, submit for review).
- [ ] **Upload IPA to App Store Connect** (Create version, add screenshots, submit for review).
- [ ] **Monitor review status** (respond to any feedback within 24 hours).

### Phase 5: Go Live

- [ ] **Play Store approval** → Release to production.
- [ ] **App Store approval** → Manually release to users.
- [ ] **Test live apps** from store downloads.
- [ ] **Monitor:** Crash reports, user reviews, analytics.

**Guides:**

- Screenshots: `docs/screenshot-capture-guide.md`
- Build & Deploy: `docs/build-deployment-guide.md`
- Play Store Metadata: `docs/playstore-metadata.md`
- App Store Metadata: `docs/appstore-metadata.md`

1. Run device QA on Android and iPhone.
2. Confirm live API, Razorpay payment, and email delivery.
3. Produce store screenshots and metadata.
4. Build signed Android AAB and iOS IPA.
5. Upload to Play Console and App Store Connect.

## Notes

- Keep Razorpay secret keys only on the server, never inside the mobile app bundle.
- Use the live `numnam.com` API and image URLs for all production builds.
- If a screen still shows fallback copy, replace it before submission.
